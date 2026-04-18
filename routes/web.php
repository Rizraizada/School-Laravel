<?php

use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\AwardController;
use App\Http\Controllers\Admin\BoardDirectorController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\QuickAttendanceController;
use App\Http\Controllers\Admin\SchoolClassController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherSectionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicSiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicSiteController::class, 'home'])->name('home');
Route::get('/notices', [PublicSiteController::class, 'notices'])->name('public.notices');
Route::get('/gallery', [PublicSiteController::class, 'gallery'])->name('public.gallery');
Route::get('/committee', [PublicSiteController::class, 'committee'])->name('public.committee');
Route::get('/staff', [PublicSiteController::class, 'staff'])->name('public.staff');
Route::get('/messages', [PublicSiteController::class, 'messages'])->name('public.messages');
Route::get('/contact', [PublicSiteController::class, 'contact'])->name('public.contact');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('students', StudentController::class)
        ->except(['show'])
        ->names('admin.students')
        ->middleware('role:teacher,headmaster,admin');

    Route::get('/attendance', [AttendanceController::class, 'index'])
        ->name('admin.attendance.index')
        ->middleware('role:teacher,headmaster,admin');
    Route::post('/attendance/bulk', [AttendanceController::class, 'storeBulk'])
        ->name('admin.attendance.store-bulk')
        ->middleware('role:teacher,headmaster,admin');
    Route::get('/attendance/report', [AttendanceController::class, 'report'])
        ->name('admin.attendance.report')
        ->middleware('role:teacher,headmaster,admin');

    Route::resource('quick-attendance', QuickAttendanceController::class)
        ->except(['show'])
        ->names('admin.quick-attendance')
        ->middleware('role:teacher,headmaster,admin');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:headmaster,admin'])
    ->group(function (): void {
        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('classes', SchoolClassController::class)->except(['show']);
        Route::resource('teacher-sections', TeacherSectionController::class)->except(['show']);
        Route::resource('notices', NoticeController::class)->except(['show']);
        Route::resource('sliders', SliderController::class)->except(['show']);
        Route::resource('awards', AwardController::class)->except(['show']);
        Route::resource('galleries', GalleryController::class)->except(['show']);
        Route::resource('directors', BoardDirectorController::class)->except(['show']);
        Route::resource('activities', ActivityController::class)->except(['show']);

        Route::get('/sections', [SectionController::class, 'index'])->name('sections.index');
        Route::post('/sections', [SectionController::class, 'store'])->name('sections.store');
        Route::put('/sections/{section}', [SectionController::class, 'update'])->name('sections.update');
        Route::delete('/sections/{section}', [SectionController::class, 'destroy'])->name('sections.destroy');
    });
