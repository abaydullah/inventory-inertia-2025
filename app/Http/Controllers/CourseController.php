<?php

namespace App\Http\Controllers;

use App\Http\Requests\Course\StoreRequest;
use App\Http\Requests\Course\UpdateRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index(Request $request)
    {

        $search = $request->input('search');
        $fee_type = $request->input('fee_type');
        $course_type = $request->input('course_type');
        $type = $request->input('type');
        $start_date = $request->start_date ? date('Y-m-d',strtotime($request->start_date)) : null;
        $end_date = $request->end_date ? date('Y-m-d',strtotime($request->end_date)) : null;

        $query = Course::latest();

        if (!is_null($search)){
            $query->where('name','like',"%{$search}%");
        }
        if (!is_null($fee_type)){
            $query->where('fee_type',$fee_type);
        }
        if (!is_null($course_type)){
            $query->where('course_type',$course_type);
        }
        if (!is_null($type)){
            $query->where('type',$type);
        }
        if (!is_null($start_date)){
            $query->whereBetween('created_at',[$start_date,$end_date]);
        }

        $query = $query->with('instructors')->paginate(10)->withQueryString();
        $courses = CourseResource::collection($query);
        $instructors = Instructor::all('id','name');
        return inertia()->render('Course/Index',['courses' => $courses,'instructors' => $instructors,'filters' => [
            'search'=> $search,
            'fee_type'=> $fee_type,
            'course_type'=> $course_type,
            'type'=> $type,
            'start_date'=> $start_date,
            'end_date'=> $end_date,
        ]]);
    }


    public function store(StoreRequest $request)
    {
        $course = Course::create($this->storeData($request));
        $course->instructors()->attach($request->instructors);
        if (!$course){
            return back()->with('error', 'Course Not Created Successfully');
        }

        return back()->with('success', 'Course Created Successfully');

    }

    public function update($id,UpdateRequest $request)
    {
        $course = Course::find($id);
        $course->update($this->storeData($request,$course));
        $instructor_ids = collect($request->instructors)->pluck('id');
        $course->instructors()->sync($request->instructors);

        return back()->with('success', 'Course Updated Successfully');

    }
    public function destroy($id)
    {
        $course = Course::find($id);
        if (Storage::disk('public')->exists($course?->image)){
            Storage::disk('public')->delete($course?->image);
        }
        $course->instructors()->detach();
        $course->delete();

        return back()->with('success', 'Course deleted Successfully');

    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function storeData($request,$course = null): array
    {
        if ($request->image){
            if ($course !== null && Storage::disk('public')->exists($course->image)){
                Storage::disk('public')->delete($course?->image);
            }
            return $request->only( 'name', 'fee', 'fee_type', 'course_type', 'type', 'body','status')

                + ['image' => $request->image->storePublicly(
                    'courses',
                    ['disk' => 'public']
                )];
        }
        return $request->only( 'name', 'fee', 'fee_type', 'course_type', 'type', 'body','status');
    }
}
