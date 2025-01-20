<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = ProductResource::collection(Product::latest()->when($request->input('search'), function ($query,$search){
            $query->where('name','like',"%{$search}%")->orWhere('mobile','like',"%{$search}%")->orWhere('gender','like',"{$search}");
        })->paginate(10)->withQueryString());
        $categories = CategoryResource::collection(Category::all());
        return inertia()->render('Backend/Product/Index',['products' => $products,'filters' => $request->only(['search']),'categories' => $categories]);
    }

//    public function create()
//    {
//        return inertia()->render('Product/Add');
//    }

    public function store(StoreRequest $request)
    {
        $product = Product::create($this->storeData($request));

        $product->groups()->sync(collect($request->group_id)->pluck('id'));
        if (!$product){
            return back()->with('error', 'Product Not Created Successfully');
        }

        return back()->with('success', 'Product Created Successfully');

    }

    public function update($id,UpdateRequest $request)
    {
        $product = Product::find($id);

        $product->update($this->storeData($request,$product));
        $ids = collect($request->group_id)->pluck('id');

        $product->groups()->sync($ids);


        return back()->with('success', 'Product Updated Successfully');

    }
    public function destroy($id)
    {
        $product = Product::find($id);
        if (Storage::disk('public')->exists($product?->image)){
            Storage::disk('public')->delete($product?->image);
        }
        $product->groups()->detach();
        $product->delete();

        return back()->with('success', 'Product deleted Successfully');

    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function storeData($request,$product = null): array
    {
        if ($request->image){
            if ($product !== null && isset($product->image) && Storage::disk('public')->exists($product->image)){
                Storage::disk('public')->delete($product?->image);
            }
            return $request->only('name', 'description', 'buy_price', 'sell_price', 'barcode', 'warning_stock','category_id','opening_stock','status')

                + ['image' => $request->image->storePublicly(
                    'products',
                    ['disk' => 'public']
                )];
        }
        return $request->only('name', 'description', 'buy_price', 'sell_price', 'barcode','warning_stock','category_id','opening_stock','status');
    }
}
