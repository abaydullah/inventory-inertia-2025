<?php

use App\Http\Controllers\Backend\AccountController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\GroupController;
use App\Http\Controllers\Backend\InvoiceController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\PurchaseController;
use App\Http\Controllers\Backend\SupplierController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TestController;

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', DashboardController::class)->name('admin.dashboard');
    Route::resource('customers', CustomerController::class);
    Route::resource('groups', GroupController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('accounts', AccountController::class);
    Route::resource('purchases', PurchaseController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('tests', TestController::class);
    Route::resource('marks', MarkController::class);
    Route::resource('students', StudentController::class);
    Route::resource('instructors', InstructorController::class);
    Route::resource('banners', BannerController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('notices', NoticeController::class);
});
Route::post('/language', LanguageController::class)->name('language.store');
