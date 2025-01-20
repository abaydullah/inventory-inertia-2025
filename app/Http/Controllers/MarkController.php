<?php

namespace App\Http\Controllers;

use App\Http\Requests\MarkRequest;
use App\Http\Resources\MarkResource;
use App\Models\Mark;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Test;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MarkController extends Controller
{
    public function index(Request $request)
    {

        $roll = $request->input('roll');
        $subject = $request->input('subject');
        $student = $request->input('student');

        $student_roll = Student::where('roll',$roll)->first();
        $test = $request->input('test');
        $start_date = $request->start_date ? date('Y-m-d',strtotime($request->start_date)) : null;
        $end_date = $request->end_date ? date('Y-m-d',strtotime($request->end_date)) : null;

        $query = Mark::latest();

        if (!is_null($student_roll?->roll)){
            $query->where('student_id',$student_roll->id);
        }
        if (!is_null($student)){
            $query->where('student_id',$student['id']);
        }
        if (!is_null($subject)){
            $query->where('subject_id',$subject['id']);
        }
        if (!is_null($test)){
            $query->where('test_id',$test['id']);
        }
        if (!is_null($start_date)){
            $query->whereBetween('date',[$start_date,$end_date]);
        }

        $query =$query->paginate(10)->withQueryString();
        $marks = MarkResource::collection($query);


        $subjects = Subject::all();
        $students = Student::all();
        $tests = Test::all();
        return inertia()->render('Mark/Index',['marks' => $marks,'subjects' => $subjects,'students' => $students,'tests' => $tests, 'filters' => [
        'roll' => $roll,
        'subject' => $subject,
        'student' => $student,
        'test' => $test,
        'start_date' => $start_date,
        'end_date' => $end_date,
        ]]);
    }

    public function create()
    {
        return inertia()->render('Mark/Add');
    }

    public function store(MarkRequest $request)
    {
        $mark = Mark::create($request->only(
            'student_id',
            'subject_id',
            'test_id',
            'mcq',
            'cq',
            'total_exam',
        )+[
            'date' => date('Y-m-d',strtotime($request->date))
            ]);

        if (!$mark){
            return back()->with('error', 'Mark Not Created Successfully');
        }

        return back()->with('success', 'Mark Created Successfully');

    }

    public function update($id,MarkRequest $request)
    {
        $mark = Mark::find($id);
        $mark->update($request->only(
                'student_id',
                'subject_id',
                'test_id',
                'mcq',
                'cq',
                'total_exam',
            )+[
                'date' => date('Y-m-d',strtotime($request->date)),  'total_mark' => $request->mcq+$request->cq
            ]);

        return back()->with('success', 'Mark Updated Successfully');

    }
    public function destroy($id)
    {
        $mark = Mark::find($id);
        $mark->delete();

        return back()->with('success', 'Mark deleted Successfully');

    }
}
