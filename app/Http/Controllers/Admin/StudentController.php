<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use App\Support\CrudHelpers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    use CrudHelpers;

    public function index(): View
    {
        return view('admin.students.index', [
            'students' => Student::with(['section.schoolClass', 'teacher'])->latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.students.create', [
            'sections' => Section::with('schoolClass')->orderBy('section_name')->get(),
            'teachers' => User::whereIn('role', ['teacher', 'headmaster', 'admin'])->orderBy('full_name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'position' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:4096'],
            'email' => ['nullable', 'email', 'max:255'],
            'gender' => ['nullable', 'in:male,female,other'],
            'expertise' => ['nullable', 'string', 'max:255'],
        ]);

        $data['image'] = $this->storeUploadedFile($request->file('image'), 'students');

        Student::create($data);

        return redirect()->route('admin.students.index')->with('success', 'Student added successfully.');
    }

    public function edit(Student $student): View
    {
        return view('admin.students.edit', [
            'student' => $student,
            'sections' => Section::with('schoolClass')->orderBy('section_name')->get(),
            'teachers' => User::whereIn('role', ['teacher', 'headmaster', 'admin'])->orderBy('full_name')->get(),
        ]);
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'position' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:4096'],
            'email' => ['nullable', 'email', 'max:255'],
            'gender' => ['nullable', 'in:male,female,other'],
            'expertise' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeUploadedFile($request->file('image'), 'students');
        }

        $student->update($data);

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $student->delete();

        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully.');
    }
}
