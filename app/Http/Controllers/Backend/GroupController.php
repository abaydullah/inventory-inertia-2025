<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Group\StoreRequest;
use App\Http\Requests\Group\UpdateRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $groups = GroupResource::collection(Group::latest()->when($request->input('search'), function ($query,$search){
            $query->where('name','like',"%{$search}%")->orWhere('slug','like',"%{$search}%");
        })->paginate(10)->withQueryString());
        return inertia()->render('Backend/Group/Index',['groups' => $groups,'filters' => $request->only(['search'])]);
    }

//    public function create()
//    {
//        return inertia()->render('Group/Add');
//    }

    public function store(StoreRequest $request)
    {
        $group = Group::create($this->storeData($request));

        if (!$group){
            return back()->with('error', 'Group Not Created Successfully');
        }

        return back()->with('success', 'Group Created Successfully');

    }

    public function update($id,UpdateRequest $request)
    {
        $group = Group::find($id);
        $group->update($this->storeData($request,$group));

        return back()->with('success', 'Group Updated Successfully');

    }
    public function destroy($id)
    {
        $group = Group::find($id);


        if ($group->suppliers()->count() > 0 || $group->customers()->count() > 0){
            return back()->with('error', "This Group Can't Delete");
        }
        $group->delete();
        return back()->with('success', 'Group deleted Successfully');

    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function storeData($request,$group = null): array
    {
        return $request->only('name', 'icon', 'status')+['slug' => Str::slug($request->name,'-')];
    }
}
