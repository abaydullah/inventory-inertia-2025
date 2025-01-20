<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = CategoryResource::collection(Category::latest()->when($request->input('search'), function ($query,$search){
            $query->where('name','like',"%{$search}%")->orWhere('slug','like',"%{$search}%");
        })->paginate(10)->withQueryString());


        return inertia()->render('Backend/Category/Index',['categories' => $categories,'filters' => $request->only(['search'])]);
    }


//    public function create()
//    {
//        return inertia()->render('Category/Add');
//    }

    public function store(StoreRequest $request)
    {
        $category = Category::create($this->storeData($request));

        if (!$category){
            return back()->with('error', 'Category Not Created Successfully');
        }

        return back()->with('success', 'Category Created Successfully');

    }

    public function update($id,UpdateRequest $request)
    {
        $category = Category::find($id);
        $category->update($this->storeData($request,$category));

        return back()->with('success', 'Category Updated Successfully');

    }
    public function destroy($id)
    {
        $category = Category::find($id);


        if ($category->products()->count() > 0){
            return back()->with('error', "This Category Already Used So You Can't Delete It");
        }
        $category->delete();
        return back()->with('success', 'Category deleted Successfully');

    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function storeData($request,$category = null): array
    {
        return $request->only('name', 'icon', 'status')+['slug' => Str::slug($request->name,'-')];
    }
}
