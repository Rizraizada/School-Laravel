<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\SubjectConfig;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ExamController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'class_id' => ['nullable', 'exists:classes,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
            'exam_year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
        ]);

        $exams = Exam::query()
            ->with(['schoolClass', 'section', 'subjects'])
            ->when(! empty($filters['class_id']), fn ($query) => $query->where('class_id', (int) $filters['class_id']))
            ->when(! empty($filters['section_id']), fn ($query) => $query->where('section_id', (int) $filters['section_id']))
            ->when(! empty($filters['exam_year']), fn ($query) => $query->where('exam_year', (int) $filters['exam_year']))
            ->orderByDesc('exam_year')
            ->orderBy('exam_name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.exams.index', [
            'exams' => $exams,
            'classes' => SchoolClass::orderBy('class_name')->get(),
            'sections' => Section::with('schoolClass')->orderBy('section_name')->get(),
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        return view('admin.exams.create', [
            'exam' => new Exam(),
            'classes' => SchoolClass::orderBy('class_name')->get(),
            'sections' => Section::with('schoolClass')->orderBy('section_name')->get(),
            'subjectConfigs' => SubjectConfig::query()
                ->with('subject')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('subject_name')
                ->get(),
            'isEdit' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePayload($request);
        $this->ensureSectionBelongsToClass($validated);

        /** @var Exam $exam */
        $exam = Exam::create([
            'exam_name' => $validated['exam_name'],
            'exam_year' => $validated['exam_year'],
            'class_id' => $validated['class_id'],
            'section_id' => $validated['section_id'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'created_by' => $request->user()?->id,
        ]);

        $this->syncExamSubjects($exam, $validated);

        return redirect()->route('admin.exams.index')
            ->with('success', 'Exam created successfully with selected subjects.');
    }

    public function edit(Exam $exam): View
    {
        $exam->load('subjects');

        return view('admin.exams.edit', [
            'exam' => $exam,
            'classes' => SchoolClass::orderBy('class_name')->get(),
            'sections' => Section::with('schoolClass')->orderBy('section_name')->get(),
            'subjectConfigs' => SubjectConfig::query()
                ->with('subject')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('subject_name')
                ->get(),
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, Exam $exam): RedirectResponse
    {
        $validated = $this->validatePayload($request);
        $this->ensureSectionBelongsToClass($validated);

        $exam->update([
            'exam_name' => $validated['exam_name'],
            'exam_year' => $validated['exam_year'],
            'class_id' => $validated['class_id'],
            'section_id' => $validated['section_id'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->syncExamSubjects($exam, $validated, true);

        return redirect()->route('admin.exams.index')
            ->with('success', 'Exam updated successfully.');
    }

    public function destroy(Exam $exam): RedirectResponse
    {
        $exam->delete();

        return redirect()->route('admin.exams.index')->with('success', 'Exam deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatePayload(Request $request): array
    {
        return $request->validate([
            'exam_name' => ['required', 'string', 'max:255'],
            'exam_year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'class_id' => ['required', 'exists:classes,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
            'notes' => ['nullable', 'string'],
            'subjects' => ['required', 'array', 'min:1'],
            'subjects.*.subject_config_id' => ['nullable', 'exists:subject_config,id'],
            'subjects.*.subject_name' => ['required', 'string', 'max:255'],
            'subjects.*.subject_code' => ['nullable', 'string', 'max:40'],
            'subjects.*.full_mark' => ['required', 'integer', 'min:1', 'max:500'],
            'subjects.*.pass_mark' => ['required', 'integer', 'min:0', 'max:500'],
            'subjects.*.sort_order' => ['nullable', 'integer', 'min:0', 'max:10000'],
            'subjects.*.is_optional' => ['nullable', 'boolean'],
            'subjects.*.include_in_gpa' => ['nullable', 'boolean'],
            'subjects.*.include_in_total_score' => ['nullable', 'boolean'],
        ]);
    }

    /**
     * @param  array<string, mixed>  $validated
     */
    private function syncExamSubjects(Exam $exam, array $validated, bool $replaceExisting = false): void
    {
        $rows = collect($validated['subjects'] ?? [])
            ->filter(fn ($row) => ! empty($row['subject_name']))
            ->values();

        if ($replaceExisting) {
            $exam->subjects()->delete();
        }

        foreach ($rows as $row) {
            $config = null;
            if (! empty($row['subject_config_id'])) {
                $config = SubjectConfig::query()->find((int) $row['subject_config_id']);
            }

            $exam->subjects()->create([
                'subject_id' => $config?->subject_id,
                'subject_config_id' => $config?->id,
                'subject_name' => $row['subject_name'],
                'subject_code' => $row['subject_code'] ?: ($config?->subject_code),
                'full_mark' => (int) $row['full_mark'],
                'pass_mark' => (int) $row['pass_mark'],
                'is_optional' => (bool) ($row['is_optional'] ?? false),
                'include_in_gpa' => array_key_exists('include_in_gpa', $row)
                    ? (bool) $row['include_in_gpa']
                    : (bool) ($config?->include_in_gpa ?? true),
                'include_in_total_score' => array_key_exists('include_in_total_score', $row)
                    ? (bool) $row['include_in_total_score']
                    : (bool) ($config?->include_in_total_score ?? true),
                'sort_order' => (int) ($row['sort_order'] ?? 0),
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $validated
     */
    private function ensureSectionBelongsToClass(array $validated): void
    {
        if (empty($validated['section_id'])) {
            return;
        }

        $isValid = Section::query()
            ->whereKey((int) $validated['section_id'])
            ->where('class_id', (int) $validated['class_id'])
            ->exists();

        if (! $isValid) {
            throw ValidationException::withMessages([
                'section_id' => 'Selected section does not belong to selected class.',
            ]);
        }
    }
}
