<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamSubject;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentResult;
use App\Services\ResultCalculationService;
use App\Services\ResultPdfService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StudentResultController extends Controller
{
    public function __construct(
        private readonly ResultCalculationService $calculationService,
        private readonly ResultPdfService $pdfService
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'student_name' => ['nullable', 'string', 'max:255'],
            'roll_no' => ['nullable', 'string', 'max:50'],
            'exam_year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'exam_id' => ['nullable', 'exists:exams,id'],
            'class_id' => ['nullable', 'exists:classes,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
        ]);

        $results = StudentResult::query()
            ->with(['student', 'schoolClass', 'section', 'exam'])
            ->when(! empty($filters['student_name']), fn ($query) => $query->where('student_name', 'like', '%'.$filters['student_name'].'%'))
            ->when(! empty($filters['roll_no']), fn ($query) => $query->where('roll_no', 'like', '%'.$filters['roll_no'].'%'))
            ->when(! empty($filters['exam_year']), fn ($query) => $query->where('exam_year', (int) $filters['exam_year']))
            ->when(! empty($filters['exam_id']), fn ($query) => $query->where('exam_id', (int) $filters['exam_id']))
            ->when(! empty($filters['class_id']), fn ($query) => $query->where('class_id', (int) $filters['class_id']))
            ->when(! empty($filters['section_id']), fn ($query) => $query->where('section_id', (int) $filters['section_id']))
            ->orderByDesc('exam_year')
            ->orderByDesc('exam_id')
            ->orderBy('student_name')
            ->paginate(20)
            ->withQueryString();

        $summary = StudentResult::query()
            ->selectRaw('class_id, section_id, exam_id, exam_year, count(*) as total_records, avg(gpa) as avg_gpa')
            ->when(! empty($filters['exam_year']), fn ($query) => $query->where('exam_year', (int) $filters['exam_year']))
            ->when(! empty($filters['exam_id']), fn ($query) => $query->where('exam_id', (int) $filters['exam_id']))
            ->when(! empty($filters['class_id']), fn ($query) => $query->where('class_id', (int) $filters['class_id']))
            ->when(! empty($filters['section_id']), fn ($query) => $query->where('section_id', (int) $filters['section_id']))
            ->with(['schoolClass', 'section', 'exam'])
            ->groupBy(['class_id', 'section_id', 'exam_id', 'exam_year'])
            ->orderByDesc('exam_year')
            ->limit(20)
            ->get();

        return view('admin.student_results.index', [
            'results' => $results,
            'summary' => $summary,
            'exams' => Exam::with(['schoolClass', 'section'])
                ->orderByDesc('exam_year')
                ->orderBy('exam_name')
                ->get(),
            'classes' => SchoolClass::orderBy('class_name')->get(),
            'sections' => Section::with('schoolClass')->orderBy('section_name')->get(),
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        $exams = Exam::query()
            ->with(['schoolClass', 'section', 'subjects'])
            ->where('is_active', true)
            ->orderByDesc('exam_year')
            ->orderBy('exam_name')
            ->get();
        $students = Student::with(['section.schoolClass'])->orderBy('name')->get();

        return view('admin.student_results.create', [
            'students' => $students,
            'studentMeta' => $students->map(fn (Student $student) => [
                'id' => $student->id,
                'name' => $student->name,
                'roll_no' => $student->roll_no,
                'registration_no' => $student->registration_no,
                'father_name' => $student->father_name,
                'mother_name' => $student->mother_name,
                'class_id' => $student->section?->class_id,
                'section_id' => $student->section_id,
                'class_name' => $student->section?->schoolClass?->class_name,
                'section_name' => $student->section?->section_name,
            ])->values(),
            'classes' => SchoolClass::orderBy('class_name')->get(),
            'sections' => Section::with('schoolClass')->orderBy('section_name')->get(),
            'exams' => $exams,
            'examSubjectsByExam' => $exams->mapWithKeys(fn ($exam) => [$exam->id => $exam->subjects->map(fn ($subject) => [
                'id' => $subject->id,
                'subject_name' => $subject->subject_name,
                'subject_code' => $subject->subject_code,
                'full_mark' => $subject->full_mark,
                'pass_mark' => $subject->pass_mark,
                'is_optional' => $subject->is_optional,
                'include_in_gpa' => $subject->include_in_gpa,
                'include_in_total_score' => $subject->include_in_total_score,
                'sort_order' => $subject->sort_order,
            ])->values()]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($request->has('results')) {
            return $this->storeBulk($request);
        }

        $payload = $this->validatedPayload($request);
        [$exam, $student] = $this->resolveExamAndStudent($payload);
        $calculated = $this->calculateResult($payload, $exam);

        $result = StudentResult::create([
            'student_id' => $student->id,
            'class_id' => $exam->class_id,
            'section_id' => $exam->section_id ?: $student->section_id,
            'exam_id' => $exam->id,
            'recorded_by' => $request->user()?->id,
            'student_name' => $payload['student_name'] ?: $student->name,
            'roll_no' => $payload['roll_no'] ?: $student->roll_no,
            'registration_no' => $payload['registration_no'] ?: $student->registration_no,
            'father_name' => $payload['father_name'] ?: $student->father_name,
            'mother_name' => $payload['mother_name'] ?: $student->mother_name,
            'group_name' => $payload['group_name'] ?: null,
            'class_level' => $exam->schoolClass?->class_name,
            'section_name' => $exam->section?->section_name ?: $student->section?->section_name,
            'exam_name' => $exam->exam_name,
            'exam_year' => $exam->exam_year,
            'total_marks' => $calculated['total_marks'],
            'gpa' => $calculated['gpa'],
            'grade' => $calculated['grade'],
            'result_status' => $calculated['result_status'],
            'merit_position' => $payload['merit_position'] ?: null,
            'raw_marks' => $calculated['raw_marks'],
            'meta' => $calculated['meta'],
        ]);

        $result->items()->createMany($calculated['items']);

        return redirect()->route('admin.student-results.edit', $result)
            ->with('success', 'Student result created successfully.');
    }

    private function storeBulk(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'exam_id' => ['required', 'exists:exams,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
            'results' => ['required', 'array', 'min:1'],
            'results.*.student_id' => ['required', 'integer', 'exists:students,id'],
            'results.*.marks' => ['nullable', 'array'],
            'results.*.marks.*.exam_subject_id' => ['required', 'integer', 'exists:exam_subjects,id'],
            'results.*.marks.*.obtained_mark' => ['nullable', 'numeric', 'min:0'],
        ]);

        $exam = Exam::query()
            ->with(['schoolClass', 'section', 'subjects'])
            ->findOrFail((int) $validated['exam_id']);
        $forcedSectionId = isset($validated['section_id']) ? (int) $validated['section_id'] : null;

        if ($exam->section_id !== null && $forcedSectionId !== null && (int) $exam->section_id !== $forcedSectionId) {
            throw ValidationException::withMessages([
                'section_id' => 'Selected section does not match the exam section.',
            ]);
        }

        if ($forcedSectionId !== null) {
            $sectionMatchesExamClass = Section::query()
                ->whereKey($forcedSectionId)
                ->where('class_id', (int) $exam->class_id)
                ->exists();
            if (! $sectionMatchesExamClass) {
                throw ValidationException::withMessages([
                    'section_id' => 'Selected section does not belong to the exam class.',
                ]);
            }
        }

        $rows = collect($validated['results'] ?? [])->values();
        $studentIds = $rows->pluck('student_id')->map(fn ($id) => (int) $id)->unique()->values();
        $students = Student::query()
            ->with('section.schoolClass')
            ->whereIn('id', $studentIds)
            ->get()
            ->keyBy('id');

        $processed = 0;
        foreach ($rows as $row) {
            $studentId = (int) ($row['student_id'] ?? 0);
            $marks = collect($row['marks'] ?? [])->values();
            $hasAnyMark = $marks->contains(fn ($markRow) => ($markRow['obtained_mark'] ?? null) !== null && $markRow['obtained_mark'] !== '');
            if (! $hasAnyMark) {
                continue;
            }

            $hasBlankMark = $marks->contains(fn ($markRow) => ($markRow['obtained_mark'] ?? null) === null || $markRow['obtained_mark'] === '');
            if ($hasBlankMark) {
                throw ValidationException::withMessages([
                    'results' => 'Please enter all subject marks for each expanded student.',
                ]);
            }

            /** @var Student|null $student */
            $student = $students->get($studentId);
            if (! $student) {
                continue;
            }

            $this->validateStudentBelongsToExam($student, $exam, $forcedSectionId);
            $calculated = $this->calculateResult(['marks' => $marks->all()], $exam);

            /** @var StudentResult $result */
            $result = StudentResult::query()->firstOrNew([
                'student_id' => $student->id,
                'exam_id' => $exam->id,
            ]);
            $result->fill([
                'class_id' => $exam->class_id,
                'section_id' => $exam->section_id ?: $student->section_id,
                'recorded_by' => $request->user()?->id,
                'student_name' => $student->name,
                'roll_no' => $student->roll_no,
                'registration_no' => $student->registration_no,
                'father_name' => $student->father_name,
                'mother_name' => $student->mother_name,
                'group_name' => $student->group_name ?? null,
                'class_level' => $exam->schoolClass?->class_name,
                'section_name' => $exam->section?->section_name ?: $student->section?->section_name,
                'exam_name' => $exam->exam_name,
                'exam_year' => $exam->exam_year,
                'total_marks' => $calculated['total_marks'],
                'gpa' => $calculated['gpa'],
                'grade' => $calculated['grade'],
                'result_status' => $calculated['result_status'],
                'raw_marks' => $calculated['raw_marks'],
                'meta' => $calculated['meta'],
            ]);
            $result->save();

            $result->items()->delete();
            $result->items()->createMany($calculated['items']);
            $processed++;
        }

        if ($processed === 0) {
            throw ValidationException::withMessages([
                'results' => 'No student marks were submitted. Expand at least one student and fill all subject marks.',
            ]);
        }

        return redirect()->route('admin.student-results.index')
            ->with('success', "Student results saved for {$processed} student(s).");
    }

    public function edit(StudentResult $studentResult): View
    {
        $studentResult->load(['items', 'exam.subjects']);
        $exams = Exam::query()
            ->with(['schoolClass', 'section', 'subjects'])
            ->where('is_active', true)
            ->orWhere('id', $studentResult->exam_id)
            ->orderByDesc('exam_year')
            ->orderBy('exam_name')
            ->get();
        $students = Student::with(['section.schoolClass'])->orderBy('name')->get();

        return view('admin.student_results.edit', [
            'record' => $studentResult,
            'students' => $students,
            'studentMeta' => $students->mapWithKeys(fn (Student $student) => [$student->id => [
                'id' => $student->id,
                'name' => $student->name,
                'roll_no' => $student->roll_no,
                'registration_no' => $student->registration_no,
                'father_name' => $student->father_name,
                'mother_name' => $student->mother_name,
                'class_id' => $student->section?->class_id,
                'section_id' => $student->section_id,
            ]]),
            'classes' => SchoolClass::orderBy('class_name')->get(),
            'sections' => Section::with('schoolClass')->orderBy('section_name')->get(),
            'exams' => $exams,
            'examSubjectsByExam' => $exams->mapWithKeys(fn ($exam) => [$exam->id => $exam->subjects->map(fn ($subject) => [
                'id' => $subject->id,
                'subject_name' => $subject->subject_name,
                'subject_code' => $subject->subject_code,
                'full_mark' => $subject->full_mark,
                'pass_mark' => $subject->pass_mark,
                'is_optional' => $subject->is_optional,
                'include_in_gpa' => $subject->include_in_gpa,
                'include_in_total_score' => $subject->include_in_total_score,
                'sort_order' => $subject->sort_order,
            ])->values()]),
        ]);
    }

    public function update(Request $request, StudentResult $studentResult): RedirectResponse
    {
        $payload = $this->validatedPayload($request);
        [$exam, $student] = $this->resolveExamAndStudent($payload);
        $calculated = $this->calculateResult($payload, $exam);

        $studentResult->update([
            'student_id' => $student->id,
            'class_id' => $exam->class_id,
            'section_id' => $exam->section_id ?: $student->section_id,
            'exam_id' => $exam->id,
            'recorded_by' => $request->user()?->id,
            'student_name' => $payload['student_name'] ?: $student->name,
            'roll_no' => $payload['roll_no'] ?: $student->roll_no,
            'registration_no' => $payload['registration_no'] ?: $student->registration_no,
            'father_name' => $payload['father_name'] ?: $student->father_name,
            'mother_name' => $payload['mother_name'] ?: $student->mother_name,
            'group_name' => $payload['group_name'] ?: null,
            'class_level' => $exam->schoolClass?->class_name,
            'section_name' => $exam->section?->section_name ?: $student->section?->section_name,
            'exam_name' => $exam->exam_name,
            'exam_year' => $exam->exam_year,
            'total_marks' => $calculated['total_marks'],
            'gpa' => $calculated['gpa'],
            'grade' => $calculated['grade'],
            'result_status' => $calculated['result_status'],
            'merit_position' => $payload['merit_position'] ?: null,
            'raw_marks' => $calculated['raw_marks'],
            'meta' => $calculated['meta'],
        ]);

        $studentResult->items()->delete();
        $studentResult->items()->createMany($calculated['items']);

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

    public function pdf(StudentResult $studentResult)
    {
        $studentResult->load(['schoolClass', 'section', 'exam', 'items']);

        $pdfBinary = $this->pdfService->buildResultPdf($studentResult);

        return response()->streamDownload(
            fn () => print($pdfBinary),
            'result-'.$studentResult->id.'.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }

    private function validatedPayload(Request $request): array
    {
        return $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'exam_id' => ['required', 'exists:exams,id'],
            'student_name' => ['nullable', 'string', 'max:255'],
            'roll_no' => ['nullable', 'string', 'max:50'],
            'registration_no' => ['nullable', 'string', 'max:100'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'group_name' => ['nullable', 'string', 'max:100'],
            'merit_position' => ['nullable', 'integer', 'min:1'],
            'marks' => ['required', 'array', 'min:1'],
            'marks.*.exam_subject_id' => ['required', 'integer', 'exists:exam_subjects,id'],
            'marks.*.obtained_mark' => ['nullable', 'numeric', 'min:0'],
        ]);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array{0: Exam, 1: Student}
     */
    private function resolveExamAndStudent(array $payload): array
    {
        $exam = Exam::query()
            ->with(['schoolClass', 'section', 'subjects'])
            ->findOrFail((int) $payload['exam_id']);
        $student = Student::query()
            ->with('section.schoolClass')
            ->findOrFail((int) $payload['student_id']);

        $this->validateStudentBelongsToExam($student, $exam);

        return [$exam, $student];
    }

    private function validateStudentBelongsToExam(Student $student, Exam $exam, ?int $forcedSectionId = null): void
    {
        if ((int) $student->section?->class_id !== (int) $exam->class_id) {
            throw ValidationException::withMessages([
                'student_id' => 'Selected student does not belong to the exam class.',
            ]);
        }

        if ($exam->section_id !== null && (int) $student->section_id !== (int) $exam->section_id) {
            throw ValidationException::withMessages([
                'student_id' => 'Selected student does not belong to the exam section.',
            ]);
        }

        if ($forcedSectionId !== null && (int) $student->section_id !== $forcedSectionId) {
            throw ValidationException::withMessages([
                'student_id' => 'Selected student does not belong to the selected section.',
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function calculateResult(array $payload, Exam $exam): array
    {
        $examSubjects = $exam->subjects;

        if ($examSubjects->isEmpty()) {
            throw ValidationException::withMessages([
                'exam_id' => 'The selected exam has no configured subjects.',
            ]);
        }

        $markRows = collect($payload['marks'] ?? [])
            ->map(fn ($row) => [
                'exam_subject_id' => (int) ($row['exam_subject_id'] ?? 0),
                'obtained_mark' => $row['obtained_mark'] ?? 0,
            ]);

        $subjectIds = $examSubjects->pluck('id')->all();
        $invalidSubject = $markRows->first(fn ($row) => ! in_array($row['exam_subject_id'], $subjectIds, true));
        if ($invalidSubject) {
            throw ValidationException::withMessages([
                'marks' => 'Submitted marks contain invalid exam subjects.',
            ]);
        }

        $filledMap = $markRows->keyBy('exam_subject_id');
        $completedRows = $examSubjects->map(function (ExamSubject $subject) use ($filledMap): array {
            $row = $filledMap->get($subject->id, []);

            return [
                'exam_subject_id' => $subject->id,
                'obtained_mark' => $row['obtained_mark'] ?? 0,
            ];
        });

        return $this->calculationService->calculate($completedRows, $examSubjects);
    }
}
