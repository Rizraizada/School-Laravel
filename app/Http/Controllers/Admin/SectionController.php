<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SectionController extends Controller
{
    public function index(): View
    {
        return view('admin.sections.index', [
            'sections' => Section::with('schoolClass')->latest()->paginate(20),
            'classes' => SchoolClass::orderBy('class_name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'section_name' => ['required', 'string', 'max:100'],
            'class_id' => ['required', 'exists:classes,id'],
        ]);

        Section::create($validated);

        return back()->with('status', 'Section created successfully.');
    }

    public function update(Request $request, Section $section): RedirectResponse
    {
        $validated = $request->validate([
            'section_name' => ['required', 'string', 'max:100'],
            'class_id' => ['required', 'exists:classes,id'],
        ]);

        $section->update($validated);

        return back()->with('status', 'Section updated successfully.');
    }

    public function destroy(Section $section): RedirectResponse
    {
        $section->delete();

        return back()->with('status', 'Section deleted successfully.');
    }
}
