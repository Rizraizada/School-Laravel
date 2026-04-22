<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentResult;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentResultController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'student_name' => ['nullable', 'string', 'max:255'],
            'roll_no' => ['nullable', 'string', 'max:50'],
            'exam_year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'class_id' => ['nullable', 'exists:classes,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
        ]);

        $results = StudentResult::query()
            ->with(['student', 'schoolClass', 'section'])
            ->when(! empty($filters['student_name']), fn ($query) => $query->where('student_name', 'like', '%'.$filters['student_name'].'%'))
            ->when(! empty($filters['roll_no']), fn ($query) => $query->where('roll_no', 'like', '%'.$filters['roll_no'].'%'))
            ->when(! empty($filters['exam_year']), fn ($query) => $query->where('exam_year', (int) $filters['exam_year']))
            ->when(! empty($filters['class_id']), fn ($query) => $query->where('class_id', (int) $filters['class_id']))
            ->when(! empty($filters['section_id']), fn ($query) => $query->where('section_id', (int) $filters['section_id']))
            ->orderByDesc('exam_year')
            ->orderBy('student_name')
            ->paginate(20)
            ->withQueryString();

        $summary = StudentResult::query()
            ->selectRaw('class_id, section_id, exam_year, count(*) as total_records, avg(gpa) as avg_gpa')
            ->when(! empty($filters['exam_year']), fn ($query) => $query->where('exam_year', (int) $filters['exam_year']))
            ->when(! empty($filters['class_id']), fn ($query) => $query->where('class_id', (int) $filters['class_id']))
            ->when(! empty($filters['section_id']), fn ($query) => $query->where('section_id', (int) $filters['section_id']))
            ->with(['schoolClass', 'section'])
            ->groupBy(['class_id', 'section_id', 'exam_year'])
            ->orderByDesc('exam_year')
            ->limit(20)
            ->get();

        return view('admin.student_results.index', [
            'results' => $results,
            'summary' => $summary,
            'classes' => SchoolClass::orderBy('class_name')->get(),
            'sections' => Section::with('schoolClass')->orderBy('section_name')->get(),
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        return view('admin.student_results.create', [
            'record' => new StudentResult(),
            'students' => Student::with(['section.schoolClass'])->orderBy('name')->get(),
            'classes' => SchoolClass::orderBy('class_name')->get(),
            'sections' => Section::with('schoolClass')->orderBy('section_name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        StudentResult::create($this->validatedPayload($request) + [
            'recorded_by' => $request->user()?->id,
        ]);

        return redirect()->route('admin.student-results.index')
            ->with('success', 'Student result created successfully.');
    }

    public function edit(StudentResult $studentResult): View
    {
        return view('admin.student_results.edit', [
            'record' => $studentResult,
            'students' => Student::with(['section.schoolClass'])->orderBy('name')->get(),
            'classes' => SchoolClass::orderBy('class_name')->get(),
            'sections' => Section::with('schoolClass')->orderBy('section_name')->get(),
        ]);
    }

    public function update(Request $request, StudentResult $studentResult): RedirectResponse
    {
        $studentResult->update($this->validatedPayload($request) + [
            'recorded_by' => $request->user()?->id,
        ]);

        return redirect()->route('admin.student-results.index')
            ->with('success', 'Student result updated successfully.');
    }

    public function destroy(StudentResult $studentResult): RedirectResponse
    {
        $studentResult->delete();

        return redirect()->route('admin.student-results.index')
            ->with('success', 'Student result deleted successfully.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'result_ids' => ['required', 'array'],
            'result_ids.*' => ['required', 'integer', 'exists:student_results,id'],
        ]);

        StudentResult::whereIn('id', $validated['result_ids'])->delete();

        return redirect()->route('admin.student-results.index')
            ->with('success', 'Selected student results deleted successfully.');
    }

    private function validatedPayload(Request $request): array
    {
        return $request->validate([
            'student_id' => ['nullable', 'exists:students,id'],
            'class_id' => ['nullable', 'exists:classes,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
            'student_name' => ['required', 'string', 'max:255'],
            'roll_no' => ['nullable', 'string', 'max:50'],
            'registration_no' => ['nullable', 'string', 'max:100'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'group_name' => ['nullable', 'string', 'max:100'],
            'class_level' => ['nullable', 'string', 'max:100'],
            'section_name' => ['nullable', 'string', 'max:100'],
            'exam_name' => ['required', 'string', 'max:120'],
            'exam_year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'bangla' => ['nullable', 'numeric', 'between:0,100'],
            'english' => ['nullable', 'numeric', 'between:0,100'],
            'mathematics' => ['nullable', 'numeric', 'between:0,100'],
            'science' => ['nullable', 'numeric', 'between:0,100'],
            'religion' => ['nullable', 'numeric', 'between:0,100'],
            'ict' => ['nullable', 'numeric', 'between:0,100'],
            'social_science' => ['nullable', 'numeric', 'between:0,100'],
            'agriculture' => ['nullable', 'numeric', 'between:0,100'],
            'higher_math' => ['nullable', 'numeric', 'between:0,100'],
            'biology' => ['nullable', 'numeric', 'between:0,100'],
            'chemistry' => ['nullable', 'numeric', 'between:0,100'],
            'physics' => ['nullable', 'numeric', 'between:0,100'],
            'total_marks' => ['nullable', 'numeric', 'min:0', 'max:1200'],
            'gpa' => ['nullable', 'numeric', 'between:0,5'],
            'grade' => ['nullable', 'string', 'max:10'],
            'result_status' => ['nullable', Rule::in(['pass', 'fail', 'withheld', 'promoted'])],
            'merit_position' => ['nullable', 'integer', 'min:1'],
        ]);
    }
}
