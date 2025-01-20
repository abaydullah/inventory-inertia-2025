<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectRequest;
use App\Http\Resources\SubjectResource;
use App\Models\Subject;
use Illuminate\Support\Facades\Request;


class SubjectController extends Controller
{
    public function index()
    {
        $subjects = SubjectResource::collection(Subject::latest()->when(Request::input('search'), function ($query,$search){
            $query->where('name','like',"%{$search}%");
        })->paginate(10)->withQueryString());
        return inertia()->render('Subject/Index',['subjects' => $subjects,'filters' => Request::only(['search'])]);
    }

    public function create()
    {
        return inertia()->render('Subject/Add');
    }

    public function store(SubjectRequest $request)
    {
      $subject = Subject::create($request->only('name'));

      if (!$subject){
          return back()->with('error', 'Subject Not Created Successfully');
      }

        return back()->with('success', 'Subject Created Successfully');

    }

    public function update($id,SubjectRequest $request)
    {
        $subject = Subject::find($id);
      $subject->update($request->only('name'));

        return back()->with('success', 'Subject Updated Successfully');

    }
    public function destroy($id)
    {
        $subject = Subject::find($id);
         $subject->delete();

        return back()->with('success', 'Subject deleted Successfully');

    }
}
