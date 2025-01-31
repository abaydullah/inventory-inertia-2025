<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreRequest;
use App\Http\Requests\Customer\UpdateRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = CustomerResource::collection(Customer::with('groups')->latest()->when($request->input('search'), function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")->orWhere('mobile', 'like', "%{$search}%")->orWhere('gender', 'like', "{$search}");
        })->paginate(10)->withQueryString());
        
        return inertia()->render('Backend/Customer/Index', ['customers' => $customers, 'filters' => $request->only(['search'])]);
    }

//    public function create()
//    {
//        return inertia()->render('Customer/Add');
//    }

    public function store(StoreRequest $request)
    {
        $customer = Customer::create($this->storeData($request));
        $ids = collect($request->group_id)->pluck('id');

        $customer->groups()->sync($ids);
        if (!$customer) {
            return back()->with('error', 'Customer Not Created Successfully');
        }

        return back()->with('success', 'Customer Created Successfully');

    }

    public function update($id, UpdateRequest $request)
    {
        $customer = Customer::find($id);
        $customer->update($this->storeData($request, $customer));
        $ids = collect($request->group_id)->pluck('id');

        $customer->groups()->sync($ids);


        return back()->with('success', 'Customer Updated Successfully');

    }

    public function destroy($id)
    {
        $customer = Customer::find($id);
        if (Storage::disk('public')->exists($customer?->photo)) {
            Storage::disk('public')->delete($customer?->photo);
        }
        $customer->groups()->detach();
        $customer->delete();

        return back()->with('success', 'Customer deleted Successfully');

    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function storeData($request, $customer = null): array
    {
        if ($request->photo) {
            if ($customer !== null && isset($customer->photo) && Storage::disk('public')->exists($customer->photo)) {
                Storage::disk('public')->delete($customer?->photo);
            }
            return $request->only('name', 'mobile', 'address', 'due', 'email', 'status', 'gender')

                + ['photo' => $request->photo->storePublicly(
                    'customers',
                    ['disk' => 'public']
                )];
        }
        return $request->only('name', 'mobile', 'address', 'due', 'email', 'status', 'gender');
    }

}
