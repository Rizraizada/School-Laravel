<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Attendance;
use App\Models\Notice;
use App\Models\QuickAttendance;
use App\Models\Section;
use App\Models\Slider;
use App\Models\Student;
use App\Models\User;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard.index', [
            'stats' => [
                'users' => User::count(),
                'students' => Student::count(),
                'sections' => Section::count(),
                'attendances' => Attendance::count(),
                'quick_attendances' => QuickAttendance::count(),
                'notices' => Notice::count(),
                'sliders' => Slider::count(),
                'activities' => Activity::count(),
            ],
            'recentNotices' => Notice::orderByDesc('date')->limit(5)->get(),
            'recentActivities' => Activity::orderByDesc('date')->limit(5)->get(),
        ]);
    }
}
