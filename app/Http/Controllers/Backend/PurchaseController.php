<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Purchase\StoreRequest;
use App\Http\Resources\PurchaseResource;
use App\Models\Account;
use App\Models\Category;
use App\Models\Color;
use App\Models\Group;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\Size;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use Inertia\Inertia;
use function Laravel\Prompts\form;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::all();
        $purchases = PurchaseResource::collection(Purchase::all());
        $groups = Group::all();
        $categories = Category::all();
        $accounts = Account::all();
        return Inertia::render("Backend/Purchase/Index", ['purchases' => $purchases, 'suppliers' => $suppliers, 'groups' => $groups, 'categories' => $categories, 'accounts' => $accounts]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sizes = Size::all();
        $colors = Color::all();
        $units = Unit::all();
        $suppliers = Supplier::all();
        $products = Product::all();
        $groups = Group::all();
        $categories = Category::all();
        $accounts = Account::all();
        return Inertia::render("Backend/Purchase/Store", ['products' => $products, 'suppliers' => $suppliers, 'groups' => $groups, 'categories' => $categories, 'accounts' => $accounts, 'sizes' => $sizes, 'colors' => $colors, 'units' => $units]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $purchase = Purchase::create($request->only(['subtotal', 'discount', 'discount_type', 'discount_amount', 'total', 'payment', 'due', 'supplier_id', 'group_id']) + ['date' => date('Y-m-d', strtotime($request->date)), 'user_id' => auth()->id()]);
        $withdraw = Withdraw::create($request->only(
                'payer',
                'note',
                'receive',
                'return',
                'supplier_id',
                'account_id') + ['purchase_id' => $purchase->id, 'user_id' => auth()->id(), 'amount' => $request->payment]);

        foreach ($request->items as $item) {


            $purchase_product = PurchaseProduct::create([
                'purchase_id' => $purchase->id,
                'product_id' => $item['id'],
                'qty' => $item['qty'],
                'buy_price' => $item['buy_price'],
                'sell_price' => $item['sell_price'],
                'total_buy_price' => $item['total_buy_price'],
                'total_sell_price' => $item['total_sell_price'],
                'user_id' => auth()->id(),
            ]);
//            if ($item->stocks->count() > 0) {
            foreach ($item['stocks'] as $stock) {

                ProductStock::create([
                    'product_id' => $stock['product_id'],
                    'purchase_product_id' => $purchase_product->id,
                    'buy_price' => $stock['buy_price'],
                    'sell_price' => $stock['sell_price'],
                    'color_id' => $stock['color_id'],
                    'size_id' => $stock['size_id'],
                    'unit_id' => $stock['unit_id'],
                    'unit_size' => $stock['unit_size'],
                    'qty' => $stock['qty'],
                    'user_id' => auth()->id(),
                ]);
            }
//            }
        }
        return back()->with('success', 'Purchase added successfully.');
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
        $sizes = Size::all();
        $colors = Color::all();
        $units = Unit::all();
        $purchase = PurchaseResource::make(Purchase::find($id));
        $suppliers = Supplier::all();
        $products = Product::all();
        $groups = Group::all();
        $categories = Category::all();
        $accounts = Account::all();
        return Inertia::render("Backend/Purchase/Edit", ['products' => $products, 'suppliers' => $suppliers, 'groups' => $groups, 'categories' => $categories, 'accounts' => $accounts, 'purchase' => $purchase, 'sizes' => $sizes, 'colors' => $colors, 'units' => $units]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {


        $purchase = Purchase::find($id);
        $purchase->update($request->only(['subtotal', 'discount', 'discount_type', 'discount_amount', 'total', 'payment', 'due', 'supplier_id', 'group_id']) + ['date' => date('Y-m-d', strtotime($request->date)), 'user_id' => auth()->id()]);

        foreach ($request->withdraws as $payment) {
            $withdraw = Withdraw::find($payment['id']);
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

            if (isset($item['purchase_product_id'])) {
                $purchaseProduct = PurchaseProduct::find($item['purchase_product_id']);
                $purchaseProduct->update(
                    [
                        'purchase_id' => $purchase->id,
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

                $purchaseProduct = PurchaseProduct::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['id'],
                    'qty' => $item['qty'],
                    'buy_price' => $item['buy_price'],
                    'sell_price' => $item['sell_price'],
                    'total_buy_price' => $item['total_buy_price'],
                    'total_sell_price' => $item['total_sell_price'],
                    'user_id' => auth()->id(),
                ]);
            }

            foreach ($item['stocks'] as $stock) {
                if (is_int($stock['id'])) {
                    $productStock = ProductStock::findOrFail($stock['id']);
                    if ($productStock) {
                        $productStock->update(
                            [
                                'product_id' => $stock['product_id'],
                                'purchase_product_id' => $purchaseProduct->id,
                                'buy_price' => $stock['buy_price'],
                                'sell_price' => $stock['sell_price'],
                                'color_id' => $stock['color_id'],
                                'size_id' => $stock['size_id'],
                                'unit_id' => $stock['unit_id'],
                                'unit_size' => $stock['unit_size'],
                                'qty' => $stock['qty'],
                                'user_id' => auth()->id(),
                            ]
                        );
                    }
                    $stockIdsFilter = collect($item['stocks'])->pluck('id');
                    $stockIds = $stockIdsFilter->reject(function ($value, int $key) {
                        return !is_int($value);
                    });

                    $productStocks = ProductStock::where('purchase_product_id', $purchaseProduct->id)->whereNotIn('id', $stockIds);
                    if ($productStocks->count() > 0) {
                        $productStocks->deleteForce();
                    }
                } else {

                    ProductStock::create([
                        'product_id' => $stock['product_id'],
                        'purchase_product_id' => $purchaseProduct->id,
                        'buy_price' => $stock['buy_price'],
                        'sell_price' => $stock['sell_price'],
                        'color_id' => $stock['color_id'],
                        'size_id' => $stock['size_id'],
                        'unit_id' => $stock['unit_id'],
                        'unit_size' => $stock['unit_size'],
                        'qty' => $stock['qty'],
                        'user_id' => auth()->id(),
                    ]);
                }
            }


        }

        $ids = collect($request->items)->pluck('id');
        PurchaseProduct::where('purchase_id', $purchase->id)->whereNotIn('product_id', $ids)->delete();
        return redirect(route('purchases.index'))->with('success', 'Purchase Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $purchase = Purchase::find($id);
        if ($purchase->purchaseProducts()->count() > 0) {
            $purchase->purchaseProducts()->delete();
        }
        if ($purchase->withdraws()->count() > 0) {
            $purchase->withdraws()->delete();
        }
        $purchase->delete();
        return redirect(route('purchases.index'))->with('success', 'Purchase deleted successfully.');
    }
}
