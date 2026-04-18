<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuickAttendance;
use App\Models\Section;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuickAttendanceController extends Controller
{
    public function index(): View
    {
        return view('admin.quick_attendance.index', [
            'records' => QuickAttendance::with(['section.schoolClass', 'recorder'])->latest('attendance_date')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.quick_attendance.create', [
            'sections' => Section::with('schoolClass')->orderBy('section_name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'section_id' => ['required', 'exists:sections,id'],
            'attendance_date' => ['required', 'date'],
            'male_count' => ['required', 'integer', 'min:0'],
            'female_count' => ['required', 'integer', 'min:0'],
        ]);

        QuickAttendance::updateOrCreate(
            [
                'section_id' => $validated['section_id'],
                'attendance_date' => $validated['attendance_date'],
            ],
            [
                'male_count' => $validated['male_count'],
                'female_count' => $validated['female_count'],
                'recorded_by' => $request->user()?->id,
            ]
        );

        return redirect()->route('admin.quick-attendance.index')
            ->with('success', 'Quick attendance saved successfully.');
    }

    public function edit(QuickAttendance $quickAttendance): View
    {
        return view('admin.quick_attendance.edit', [
            'record' => $quickAttendance,
            'sections' => Section::with('schoolClass')->orderBy('section_name')->get(),
        ]);
    }

    public function update(Request $request, QuickAttendance $quickAttendance): RedirectResponse
    {
        $validated = $request->validate([
            'section_id' => ['required', 'exists:sections,id'],
            'attendance_date' => ['required', 'date'],
            'male_count' => ['required', 'integer', 'min:0'],
            'female_count' => ['required', 'integer', 'min:0'],
        ]);

        $quickAttendance->update($validated + ['recorded_by' => $request->user()?->id]);

        return redirect()->route('admin.quick-attendance.index')
            ->with('success', 'Quick attendance updated successfully.');
    }

    public function destroy(QuickAttendance $quickAttendance): RedirectResponse
    {
        $quickAttendance->delete();

        return redirect()->route('admin.quick-attendance.index')
            ->with('success', 'Quick attendance deleted successfully.');
    }
}
