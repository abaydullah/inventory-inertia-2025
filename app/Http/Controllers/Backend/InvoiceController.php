<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\StoreRequest;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\ProductResource;
use App\Models\Account;
use App\Models\Category;
use App\Models\Color;
use App\Models\Customer;
use App\Models\Deposit;
use App\Models\Group;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Product;
use App\Models\ProductSell;
use App\Models\ProductStock;
use App\Models\Size;
use App\Models\Unit;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        $invoices = InvoiceResource::collection(Invoice::latest()->paginate(10));
        $groups = Group::all();
        $categories = Category::all();
        $accounts = Account::all();
        return Inertia::render("Backend/Invoice/Index", ['invoices' => $invoices, 'suppliers' => $customers, 'groups' => $groups, 'categories' => $categories, 'accounts' => $accounts]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sizes = Size::all();
        $colors = Color::all();
        $units = Unit::all();
        $customers = Customer::all();
        $products = ProductResource::collection(Product::whereHas('purchase_products')->orWhereHas('stocks')->get());
        $groups = Group::all();
        $categories = Category::all();
        $accounts = Account::all();
        return Inertia::render("Backend/Invoice/Store", ['products' => $products, 'customers' => $customers, 'groups' => $groups, 'categories' => $categories, 'accounts' => $accounts, 'sizes' => $sizes, 'colors' => $colors, 'units' => $units]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {

        $invoice = Invoice::create($request->only(['subtotal', 'discount', 'discount_type', 'discount_amount', 'total', 'payment', 'due', 'customer_id', 'group_id']) + ['date' => date('Y-m-d', strtotime($request->date)), 'user_id' => auth()->id()]);
        $deposit = Deposit::create($request->only(
                'payer',
                'note',
                'receive',
                'return',
                'customer_id',
                'account_id') + ['invoice_id' => $invoice->id, 'user_id' => auth()->id(), 'amount' => $request->payment]);

        foreach ($request->items as $item) {
            $invoiceProduct = InvoiceProduct::create([
                'invoice_id' => $invoice->id,
                'product_id' => $item['id'],
                'customer_id' => $request->customer_id,
                'qty' => $item['qty'],
                'buy_price' => $item['buy_price'],
                'sell_price' => $item['sell_price'],
                'discount' => $item['discount'],
                'discount_type' => $item['discount_type'],
                'discount_amount' => $item['discount_amount'],
                'total_buy_price' => $item['total_buy_price'],
                'total_sell_price' => $item['total_sell_price'],
                'user_id' => auth()->id(),
            ]);

            foreach ($item['sells'] as $sell) {

                ProductSell::create([
                    'product_id' => $sell['product_id'],
                    'invoice_product_id' => $invoiceProduct->id,
                    'buy_price' => $sell['buy_price'],
                    'sell_price' => $sell['sell_price'],
                    'discount' => $sell['discount'],
                    'discount_type' => $sell['discount_type'],
                    'discount_amount' => $sell['discount_amount'],
                    'total_buy_price' => $sell['total_buy_price'],
                    'total_sell_price' => $sell['total_sell_price'],
                    'color_id' => $sell['color_id'],
                    'size_id' => $sell['size_id'],
                    'unit_id' => $sell['unit_id'],
                    'unit_size' => $sell['unit_size'],
                    'qty' => $sell['qty'],
                    'user_id' => auth()->id(),
                ]);
            }
        }


        return back()->with('success', 'Invoice added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $invoice = InvoiceResource::make(Invoice::find($id));
        $customers = Customer::all();
        $products = Product::all();
        $groups = Group::all();
        $categories = Category::all();
        $accounts = Account::all();
        return Inertia::render("Backend/Invoice/Edit", ['products' => $products, 'customers' => $customers, 'groups' => $groups, 'categories' => $categories, 'accounts' => $accounts, 'invoice' => $invoice]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {


        $invoices = Invoice::find($id);
        $invoices->update($request->only(['subtotal', 'discount', 'discount_type', 'discount_amount', 'total', 'payment', 'due', 'supplier_id', 'group_id']) + ['date' => date('Y-m-d', strtotime($request->date)), 'user_id' => auth()->id()]);

        foreach ($request->withdraws as $payment) {
            $withdraw = Deposit::find($payment['id']);
            $withdraw->update([
                'note' => $payment['note'],
                'receive' => $payment['receive'],
                'return' => $payment['return'],
                'supplier_id' => $payment['supplier_id'],
                'account_id' => $payment['account_id'],
                'amount' => $payment['amount'],
            ]);
        }

        foreach ($request->items as $item) {

            if (isset($item['invoice_product_id'])) {
                $invoicesProduct = InvoiceProduct::find($item['invoice_product_id']);
                $invoicesProduct->update(
                    [
                        'invoice_id' => $invoices->id,
                        'product_id' => $item['id'],
                        'qty' => $item['qty'],
                        'buy_price' => $item['buy_price'],
                        'sell_price' => $item['sell_price'],
                        'total_buy_price' => $item['total_buy_price'],
                        'total_sell_price' => $item['total_sell_price'],
                        'user_id' => auth()->id(),
                    ]
                );
            } else {

                InvoiceProduct::create([
                    'invoice_id' => $invoices->id,
                    'product_id' => $item['id'],
                    'qty' => $item['qty'],
                    'buy_price' => $item['buy_price'],
                    'sell_price' => $item['sell_price'],
                    'total_buy_price' => $item['total_buy_price'],
                    'total_sell_price' => $item['total_sell_price'],
                    'user_id' => auth()->id(),
                ]);
            }
        }

        $ids = collect($request->items)->pluck('id');
        InvoiceProduct::where('invoice_id', $invoices->id)->whereNotIn('product_id', $ids)->delete();
        return redirect(route('invoices.index'))->with('success', 'Invoice Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $invoices = Invoice::find($id);
        if ($invoices->invoiceProducts()->count() > 0) {
            $invoices->invoiceProducts()->delete();
        }
        if ($invoices->withdraws()->count() > 0) {
            $invoices->withdraws()->delete();
        }
        $invoices->delete();
        return redirect(route('invoices.index'))->with('success', 'Invoice deleted successfully.');
    }
}
