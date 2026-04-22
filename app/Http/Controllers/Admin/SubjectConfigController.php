<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\SubjectConfig;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectConfigController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'class_level' => ['nullable', 'string', 'max:120'],
            'group_name' => ['nullable', 'string', 'max:120'],
        ]);
        $classLevel = trim((string) ($filters['class_level'] ?? ''));
        $groupName = trim((string) ($filters['group_name'] ?? ''));

        $configs = SubjectConfig::query()
            ->with('schoolClass')
            ->when($classLevel !== '', fn ($query) => $query->where('class_level', $classLevel))
            ->when($groupName !== '', fn ($query) => $query->where('group_name', $groupName))
            ->orderBy('class_level')
            ->orderBy('group_name')
            ->orderBy('sort_order')
            ->orderBy('subject_name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.subject_configs.index', [
            'configs' => $configs,
            'classLevels' => SubjectConfig::query()
                ->select('class_level')
                ->distinct()
                ->orderBy('class_level')
                ->pluck('class_level'),
            'groupNames' => SubjectConfig::query()
                ->whereNotNull('group_name')
                ->where('group_name', '!=', '')
                ->select('group_name')
                ->distinct()
                ->orderBy('group_name')
                ->pluck('group_name'),
            'filters' => [
                'class_level' => $classLevel,
                'group_name' => $groupName,
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.subject_configs.create', [
            'subjectConfig' => new SubjectConfig(),
            'classes' => SchoolClass::orderBy('class_name')->get(),
            'subjects' => Subject::where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('subject_name')
                ->get(),
            'isEdit' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePayload($request);

        SubjectConfig::create($validated);

        return redirect()->route('admin.subject-config.index')
            ->with('success', 'Subject configuration created successfully.');
    }

    public function edit(SubjectConfig $subjectConfig): View
    {
        return view('admin.subject_configs.edit', [
            'subjectConfig' => $subjectConfig,
            'classes' => SchoolClass::orderBy('class_name')->get(),
            'subjects' => Subject::where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('subject_name')
                ->get(),
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, SubjectConfig $subjectConfig): RedirectResponse
    {
        $validated = $this->validatePayload($request);

        $subjectConfig->update($validated);

        return redirect()->route('admin.subject-config.index')
            ->with('success', 'Subject configuration updated successfully.');
    }

    public function destroy(SubjectConfig $subjectConfig): RedirectResponse
    {
        $subjectConfig->delete();

        return redirect()->route('admin.subject-config.index')
            ->with('success', 'Subject configuration deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatePayload(Request $request): array
    {
        return $request->validate([
            'class_id' => ['nullable', 'exists:classes,id'],
            'class_level' => ['required', 'string', 'max:120'],
            'group_name' => ['nullable', 'string', 'max:120'],
            'subject_id' => ['nullable', 'exists:subjects,id'],
            'subject_code' => ['nullable', 'string', 'max:40'],
            'subject_name' => ['required', 'string', 'max:255'],
            'subject_type' => ['required', 'string', 'max:120'],
            'full_mark' => ['required', 'integer', 'min:1', 'max:500'],
            'pass_mark' => ['required', 'integer', 'min:0', 'max:500'],
            'subjective_mark' => ['nullable', 'integer', 'min:0', 'max:500'],
            'mcq_mark' => ['nullable', 'integer', 'min:0', 'max:500'],
            'practical_mark' => ['nullable', 'integer', 'min:0', 'max:500'],
            'is_optional' => ['nullable', 'boolean'],
            'include_in_gpa' => ['nullable', 'boolean'],
            'include_in_total_score' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:10000'],
        ]) + [
            'is_optional' => $request->boolean('is_optional'),
            'include_in_gpa' => $request->boolean('include_in_gpa', true),
            'include_in_total_score' => $request->boolean('include_in_total_score', true),
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => (int) $request->input('sort_order', 0),
        ];
    }
}
