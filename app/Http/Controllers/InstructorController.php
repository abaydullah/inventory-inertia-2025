<?php

namespace App\Http\Controllers;

use App\Http\Requests\Instructor\StoreRequest;
use App\Http\Requests\Instructor\UpdateRequest;
use App\Http\Resources\InstructorResource;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstructorController extends Controller
{
    public function index(Request $request)
    {
        $instructors = InstructorResource::collection(Instructor::latest()->when($request->input('search'), function ($query,$search){
            $query->where('name','like',"%{$search}%")->orWhere('mobile','like',"%{$search}%")->orWhere('gender','like',"{$search}");
        })->paginate(10)->withQueryString());
        return inertia()->render('Instructor/Index',['instructors' => $instructors,'filters' => $request->only(['search'])]);
    }

    public function create()
    {
        return inertia()->render('Instructor/Add');
    }

    public function store(StoreRequest $request)
    {
        $instructor = Instructor::create($this->storeData($request));

        if (!$instructor){
            return back()->with('error', 'Instructor Not Created Successfully');
        }

        return back()->with('success', 'Instructor Created Successfully');

    }

    public function update($id,UpdateRequest $request)
    {
        $instructor = Instructor::find($id);
        $instructor->update($this->storeData($request,$instructor));

        return back()->with('success', 'Instructor Updated Successfully');

    }
    public function destroy($id)
    {
        $instructor = Instructor::find($id);
        if (Storage::disk('public')->exists($instructor?->image)){
            Storage::disk('public')->delete($instructor?->image);
        }
        $instructor->delete();

        return back()->with('success', 'Instructor deleted Successfully');

    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function storeData($request,$instructor = null): array
    {
        if ($request->image){
                if ($instructor !== null && Storage::disk('public')->exists($instructor->image)){
                    Storage::disk('public')->delete($instructor?->image);
                }
            return $request->only('name', 'gender', 'experience', 'occupation', 'about', 'mobile', 'status')

                + ['image' => $request->image->storePublicly(
                    'instructors',
                    ['disk' => 'public']
                )];
        }
        return $request->only('name', 'gender', 'experience', 'occupation', 'about', 'mobile', 'status');
    }


}
