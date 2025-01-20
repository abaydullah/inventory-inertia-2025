<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supplier\StoreRequest;
use App\Http\Requests\Supplier\UpdateRequest;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $suppliers = SupplierResource::collection(Supplier::latest()->when($request->input('search'), function ($query,$search){
            $query->where('company_name','like',"%{$search}%")->orWhere('mobile','like',"%{$search}%")->orWhere('email','like',"{$search}")->orWhere('due','like',"{$search}");
        })->paginate(10)->withQueryString());
        return inertia()->render('Backend/Supplier/Index',['suppliers' => $suppliers,'filters' => $request->only(['search'])]);
    }

//    public function create()
//    {
//        return inertia()->render('Supplier/Add');
//    }

    public function store(StoreRequest $request)
    {
        $supplier = Supplier::create($this->storeData($request));
        $this->groupable($request,$supplier);
        if (!$supplier){
            return back()->with('error', 'Supplier Not Created Successfully');
        }

        return back()->with('success', 'Supplier Created Successfully');

    }

    public function update($id,UpdateRequest $request)
    {
        $supplier = Supplier::find($id);
        $supplier->update($this->storeData($request,$supplier));

        $supplier->groups()->sync(collect($request->group_id)->pluck('id'));
        return back()->with('success', 'Supplier Updated Successfully');

    }
    public function destroy($id)
    {
        $supplier = Supplier::find($id);
        if ($supplier?->photo && Storage::disk('public')->exists($supplier?->photo)){
            Storage::disk('public')->delete($supplier?->photo);
        }
        $supplier->groups()->detach();
        $supplier->delete();

        return back()->with('success', 'Supplier deleted Successfully');

    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    protected function storeData($request,$supplier = null): array
    {

        if ($request->photo){
            if ($supplier !== null && isset($supplier->photo) && Storage::disk('public')->exists($supplier->photo)){
                Storage::disk('public')->delete($supplier?->photo);
            }
            return $request->only('company_name', 'proprietor_name', 'mobile', 'address', 'due','email')

                + ['photo' => $request->photo->storePublicly(
                    'suppliers',
                    ['disk' => 'public']
                )];
        }
        return $request->only('company_name', 'proprietor_name', 'mobile', 'address', 'due','email');
    }

    protected function groupable($request,$model)
    {
        $model->groups()->sync(collect($request->group_id)->pluck('id'));
    }
}
