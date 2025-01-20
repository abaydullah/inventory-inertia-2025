<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\NoticeResource;
use App\Models\Banner;
use App\Models\Course;
use App\Models\Notice;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function home()
    {
        $banners = Banner::get();
        return inertia()->render('Frontend/Index',[ 'banners'=> $banners]);
    }
    public function notices()
    {
        $notices = NoticeResource::collection(Notice::published()->paginate());
        return inertia()->render('Frontend/Notice/Index',[ 'notices'=> $notices]);
    }
    public function notice($id)
    {
        $notice = NoticeResource::make(Notice::published()->findOrFail($id));
        return inertia()->render('Frontend/Notice/View',[ 'notice'=> $notice]);
    }

    public function login()
    {

        return inertia()->render('Frontend/Auth/Login');
    }
    public function loginUser(Request $request)
    {

        if (! Auth::attempt($request->only('email','password')+['role_id' => 'student'])) {

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }
    public function register()
    {

        return inertia()->render('Frontend/Auth/Register');
    }
    public function registerUser(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    public function download($id)
    {

        $notice = Notice::published()->findOrFail($id);
        return Storage::disk('local')->download($notice->file);
    }


}
