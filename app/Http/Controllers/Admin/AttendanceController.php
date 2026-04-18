<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $sectionId = $request->integer('section_id');
        $date = $request->input('date', now()->toDateString());

        $sections = Section::orderBy('section_name')->get();
        $students = Student::query()
            ->when($sectionId, fn ($query) => $query->where('section_id', $sectionId))
            ->with(['section.schoolClass'])
            ->orderBy('name')
            ->get();

        $existing = Attendance::whereDate('attendance_date', $date)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        return view('admin.attendance.index', [
            'sections' => $sections,
            'students' => $students,
            'selectedSection' => $sectionId,
            'selectedDate' => $date,
            'existing' => $existing,
        ]);
    }

    public function storeBulk(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'attendance_date' => ['required', 'date'],
            'attendance' => ['required', 'array'],
            'attendance.*.student_id' => ['required', 'exists:students,id'],
            'attendance.*.status' => ['required', 'in:present,absent,late,excused'],
            'attendance.*.remarks' => ['nullable', 'string'],
        ]);

        foreach ($validated['attendance'] as $entry) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $entry['student_id'],
                    'attendance_date' => $validated['attendance_date'],
                ],
                [
                    'status' => $entry['status'],
                    'remarks' => $entry['remarks'] ?? null,
                    'recorded_by' => Auth::id(),
                ]
            );
        }

        return back()->with('success', 'Attendance saved successfully.');
    }

    public function report(Request $request): View
    {
        $sectionId = $request->integer('section_id');
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $sections = Section::orderBy('section_name')->get();
        $records = Attendance::query()
            ->with(['student.section.schoolClass', 'recorder'])
            ->when($sectionId, fn ($query) => $query->whereHas('student', fn ($studentQuery) => $studentQuery->where('section_id', $sectionId)))
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->orderByDesc('attendance_date')
            ->paginate(30)
            ->withQueryString();

        return view('admin.attendance.report', [
            'sections' => $sections,
            'records' => $records,
            'selectedSection' => $sectionId,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
