<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Course;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function dashboard()
    {

        return inertia()->render('Frontend/Dashboard/Index');
    }

    public function student_edit(Request $request)
    {

        return Inertia::render('Frontend/Dashboard/Profile/Index', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    public function student_update(ProfileUpdateRequest $request)
    {

        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('student.profile.edit');
    }

    public function student_destroy(Request $request)
    {

        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    //course


    public function courses_list()
    {
        $courses = Course::where('status',1)->get();
        return inertia()->render('Frontend/Dashboard/CourseList/Index',['courses' => $courses]);
    }public function courses()
{

    return inertia()->render('Frontend/Dashboard/Course/Index');
}

}
