<?php

namespace App\Http\Controllers;

use App\Http\Requests\Student\StudentRequest;
use App\Http\Requests\Student\StoreRequest;
use App\Http\Requests\Student\UpdateRequest;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = StudentResource::collection(Student::latest()->when($request->input('search'), function ($query,$search){
            $query->where('name','like',"%{$search}%");
        })->paginate(10)->withQueryString());
        return inertia()->render('Student/Index',['students' => $students,'filters' => $request->only(['search'])]);
    }

    public function create()
    {
        return inertia()->render('Student/Add');
    }

    public function store(StoreRequest $request)
    {
        $student = Student::create($request->only('name','roll','mobile'));

        if (!$student){
            return back()->with('error', 'Student Not Created Successfully');
        }

        return back()->with('success', 'Student Created Successfully');

    }

    public function update($id, UpdateRequest $request)
    {
        $student = Student::find($id);
        $student->update($request->only('name','roll','mobile'));

        return back()->with('success', 'Student Updated Successfully');

    }
    public function destroy($id)
    {
        $student = Student::find($id);
        $student->delete();

        return back()->with('success', 'Student deleted Successfully');

    }
}
