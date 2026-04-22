<?php

use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\AwardController;
use App\Http\Controllers\Admin\BoardDirectorController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\QuickAttendanceController;
use App\Http\Controllers\Admin\SchoolClassController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\StudentResultController;
use App\Http\Controllers\Admin\SubjectConfigController;
use App\Http\Controllers\Admin\TeacherSectionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicResultController;
use App\Http\Controllers\PublicSiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicSiteController::class, 'home'])->name('home');
Route::get('/notices', [PublicSiteController::class, 'notices'])->name('public.notices');
Route::get('/gallery', [PublicSiteController::class, 'gallery'])->name('public.gallery');
Route::get('/committee', [PublicSiteController::class, 'committee'])->name('public.committee');
Route::get('/staff', [PublicSiteController::class, 'staff'])->name('public.staff');
Route::get('/messages', [PublicSiteController::class, 'messages'])->name('public.messages');
Route::get('/contact', [PublicSiteController::class, 'contact'])->name('public.contact');
Route::get('/student-result', [PublicResultController::class, 'index'])->name('public.results');

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

    Route::resource('exams', ExamController::class)
        ->except(['show'])
        ->names('admin.exams')
        ->middleware('role:teacher,headmaster,admin');

    Route::get('/student-results', [StudentResultController::class, 'index'])
        ->name('admin.student-results.index')
        ->middleware('role:teacher,headmaster,admin');
    Route::get('/student-results/create', [StudentResultController::class, 'create'])
        ->name('admin.student-results.create')
        ->middleware('role:teacher,headmaster,admin');
    Route::post('/student-results', [StudentResultController::class, 'store'])
        ->name('admin.student-results.store')
        ->middleware('role:teacher,headmaster,admin');
    Route::get('/student-results/{studentResult}/edit', [StudentResultController::class, 'edit'])
        ->name('admin.student-results.edit')
        ->middleware('role:teacher,headmaster,admin');
    Route::put('/student-results/{studentResult}', [StudentResultController::class, 'update'])
        ->name('admin.student-results.update')
        ->middleware('role:teacher,headmaster,admin');
    Route::delete('/student-results/{studentResult}', [StudentResultController::class, 'destroy'])
        ->name('admin.student-results.destroy')
        ->middleware('role:teacher,headmaster,admin');
    Route::get('/student-results/{studentResult}/pdf', [StudentResultController::class, 'pdf'])
        ->name('admin.student-results.pdf')
        ->middleware('role:teacher,headmaster,admin');
    Route::delete('/student-results', [StudentResultController::class, 'bulkDestroy'])
        ->name('admin.student-results.bulk-destroy')
        ->middleware('role:teacher,headmaster,admin');

    Route::resource('subject-config', SubjectConfigController::class)
        ->except(['show'])
        ->names('admin.subject-config')
        ->middleware('role:headmaster,admin');
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
