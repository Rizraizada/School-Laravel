<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Award;
use App\Models\BoardDirector;
use App\Models\Gallery;
use App\Models\Notice;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Contracts\View\View;

class PublicSiteController extends Controller
{
    public function home(): View
    {
        return view('public.home', [
            'sliders' => Slider::latest()->take(5)->get(),
            'notices' => Notice::latest('date')->take(6)->get(),
            'awards' => Award::latest()->take(6)->get(),
            'activities' => Activity::latest('date')->take(6)->get(),
        ]);
    }

    public function notices(): View
    {
        return view('public.notices', [
            'notices' => Notice::latest('date')->paginate(12),
        ]);
    }

    public function gallery(): View
    {
        return view('public.gallery', [
            'galleries' => Gallery::latest()->paginate(12),
        ]);
    }

    public function committee(): View
    {
        return view('public.committee', [
            'directors' => BoardDirector::latest()->paginate(12),
        ]);
    }

    public function staff(): View
    {
        return view('public.staff', [
            'staffMembers' => User::whereIn('role', ['teacher', 'headmaster', 'admin'])->latest()->paginate(20),
        ]);
    }

    public function messages(): View
    {
        $headmaster = User::where('role', 'headmaster')->latest()->first();

        return view('public.messages', [
            'headmaster' => $headmaster,
        ]);
    }

    public function contact(): View
    {
        return view('public.contact');
    }
}
