<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\TeacherSection;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherSectionController extends Controller
{
    public function index(): View
    {
        return view('admin.teacher_sections.index', [
            'assignments' => TeacherSection::with(['teacher', 'section.schoolClass'])->latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.teacher_sections.create', [
            'teachers' => User::where('role', 'teacher')->orderBy('full_name')->get(),
            'sections' => Section::with('schoolClass')->orderBy('section_name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'is_primary' => ['nullable', 'boolean'],
        ]);

        TeacherSection::create([
            ...$validated,
            'is_primary' => $request->boolean('is_primary'),
        ]);

        return redirect()->route('admin.teacher-sections.index')->with('success', 'Teacher assignment created.');
    }

    public function edit(TeacherSection $teacherSection): View
    {
        return view('admin.teacher_sections.edit', [
            'assignment' => $teacherSection,
            'teachers' => User::where('role', 'teacher')->orderBy('full_name')->get(),
            'sections' => Section::with('schoolClass')->orderBy('section_name')->get(),
        ]);
    }

    public function update(Request $request, TeacherSection $teacherSection): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'is_primary' => ['nullable', 'boolean'],
        ]);

        $teacherSection->update([
            ...$validated,
            'is_primary' => $request->boolean('is_primary'),
        ]);

        return redirect()->route('admin.teacher-sections.index')->with('success', 'Teacher assignment updated.');
    }

    public function destroy(TeacherSection $teacherSection): RedirectResponse
    {
        $teacherSection->delete();

        return redirect()->route('admin.teacher-sections.index')->with('success', 'Teacher assignment deleted.');
    }
}
