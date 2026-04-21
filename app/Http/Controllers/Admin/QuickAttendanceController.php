<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuickAttendance;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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
            'students' => [],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'section_id' => ['required', 'exists:sections,id'],
            'attendance_date' => ['required', 'date'],
            'male_count' => ['required', 'integer', 'min:0'],
            'female_count' => ['required', 'integer', 'min:0'],
            'total_male' => ['nullable', 'integer', 'min:0'],
            'total_female' => ['nullable', 'integer', 'min:0'],
            'total_students' => ['nullable', 'integer', 'min:0'],
            'absent_student_ids' => ['nullable', 'string'],
        ]);

        $absentStudentIds = $this->parseAbsentStudentIds(
            $validated['absent_student_ids'] ?? null,
            (int) $validated['section_id']
        );
        $totalMale = (int) ($validated['total_male'] ?? $validated['male_count']);
        $totalFemale = (int) ($validated['total_female'] ?? $validated['female_count']);
        $totalStudents = (int) ($validated['total_students'] ?? ($totalMale + $totalFemale));

        QuickAttendance::updateOrCreate(
            [
                'section_id' => $validated['section_id'],
                'attendance_date' => $validated['attendance_date'],
            ],
            array_merge($validated, [
                'male_count' => $validated['male_count'],
                'female_count' => $validated['female_count'],
                'total_male' => $totalMale,
                'total_female' => $totalFemale,
                'total_students' => $totalStudents,
                'absent_student_ids' => $absentStudentIds,
                'recorded_by' => $request->user()?->id,
            ])
        );

        return redirect()->route('admin.quick-attendance.index')
            ->with('success', 'Quick attendance saved successfully.');
    }

    public function edit(QuickAttendance $quickAttendance): View
    {
        return view('admin.quick_attendance.edit', [
            'record' => $quickAttendance,
            'sections' => Section::with('schoolClass')->orderBy('section_name')->get(),
            'students' => $quickAttendance->section?->students()->orderBy('name')->get() ?? collect(),
        ]);
    }

    public function update(Request $request, QuickAttendance $quickAttendance): RedirectResponse
    {
        $validated = $request->validate([
            'section_id' => ['required', 'exists:sections,id'],
            'attendance_date' => ['required', 'date'],
            'male_count' => ['required', 'integer', 'min:0'],
            'female_count' => ['required', 'integer', 'min:0'],
            'total_male' => ['nullable', 'integer', 'min:0'],
            'total_female' => ['nullable', 'integer', 'min:0'],
            'total_students' => ['nullable', 'integer', 'min:0'],
            'absent_student_ids' => ['nullable', 'string'],
        ]);

        $absentStudentIds = $this->parseAbsentStudentIds(
            $validated['absent_student_ids'] ?? null,
            (int) $validated['section_id']
        );
        $totalMale = (int) ($validated['total_male'] ?? $validated['male_count']);
        $totalFemale = (int) ($validated['total_female'] ?? $validated['female_count']);
        $totalStudents = (int) ($validated['total_students'] ?? ($totalMale + $totalFemale));

        $quickAttendance->update(array_merge($validated, [
            'total_male' => $totalMale,
            'total_female' => $totalFemale,
            'total_students' => $totalStudents,
            'absent_student_ids' => $absentStudentIds,
            'recorded_by' => $request->user()?->id,
        ]));

        return redirect()->route('admin.quick-attendance.index')
            ->with('success', 'Quick attendance updated successfully.');
    }

    public function destroy(QuickAttendance $quickAttendance): RedirectResponse
    {
        $quickAttendance->delete();

        return redirect()->route('admin.quick-attendance.index')
            ->with('success', 'Quick attendance deleted successfully.');
    }

    /**
     * @return array<int, int>
     */
    private function parseAbsentStudentIds(?string $rawIds, int $sectionId): array
    {
        if ($rawIds === null || trim($rawIds) === '') {
            return [];
        }

        $ids = collect(explode(',', $rawIds))
            ->map(fn (string $value): int => (int) trim($value))
            ->filter(fn (int $value): bool => $value > 0)
            ->unique()
            ->values()
            ->all();

        if ($ids === []) {
            return [];
        }

        $validCount = Student::whereIn('id', $ids)
            ->where('section_id', $sectionId)
            ->count();
        if ($validCount !== count($ids)) {
            throw ValidationException::withMessages([
                'absent_student_ids' => 'One or more absent student IDs are invalid.',
            ]);
        }

        return $ids;
    }
}
