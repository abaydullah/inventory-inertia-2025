<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\StoreRequest;
use App\Http\Requests\Account\UpdateRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $accounts = AccountResource::collection(Account::latest()->when($request->input('search'), function ($query,$search){
            $query->where('name','like',"%{$search}%")->orWhere('slug','like',"%{$search}%");
        })->paginate(10)->withQueryString());


        return inertia()->render('Backend/Account/Index',['accounts' => $accounts,'filters' => $request->only(['search'])]);
    }


//    public function create()
//    {
//        return inertia()->render('Account/Add');
//    }

    public function store(StoreRequest $request)
    {
        $account = Account::create($this->storeData($request));

        if (!$account){
            return back()->with('error', 'Account Not Created Successfully');
        }

        return back()->with('success', 'Account Created Successfully');

    }

    public function update($id,UpdateRequest $request)
    {
        $account = Account::find($id);
        $account->update($this->storeData($request,$account));

        return back()->with('success', 'Account Updated Successfully');

    }
    public function destroy($id)
    {
        $account = Account::find($id);


//        if ($account->products()->count() > 0){
//            return back()->with('error', "This Account Already Used So You Can't Delete It");
//        }
        $account->delete();
        return back()->with('success', 'Account deleted Successfully');

    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function storeData($request,$account = null): array
    {
        return $request->only('name', 'description', 'balance','account_number','mobile')+['user_id'=>auth()->id()];
    }
}
