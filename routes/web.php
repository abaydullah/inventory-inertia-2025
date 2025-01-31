<?php

use App\Http\Controllers\Backend\AccountController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\GroupController;
use App\Http\Controllers\Backend\InvoiceController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\PurchaseController;
use App\Http\Controllers\Backend\SupplierController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\Backend\DashboardController as BackendDashboardController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TestController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;
use Inertia\Inertia;


Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
});

Route::domain(config('app.url'))->group(function () {
    Route::get('admin/dashboard', function () {

        $users = \App\Models\User::all();
        return Inertia::render('Dashboard', ['users' => $users]);
    })->name('admin.dashboard')->middleware('auth');


});

require __DIR__ . '/auth.php';
