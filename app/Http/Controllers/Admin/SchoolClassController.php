<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index(): View
    {
        return view('admin.classes.index', [
            'items' => SchoolClass::orderBy('class_name')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.classes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'class_name' => ['required', 'string', 'max:255', 'unique:classes,class_name'],
        ]);

        SchoolClass::create($validated);

        return redirect()->route('admin.classes.index')->with('success', 'Class created successfully.');
    }

    public function edit(SchoolClass $class): View
    {
        return view('admin.classes.edit', ['item' => $class]);
    }

    public function update(Request $request, SchoolClass $class): RedirectResponse
    {
        $validated = $request->validate([
            'class_name' => ['required', 'string', 'max:255', 'unique:classes,class_name,'.$class->id],
        ]);

        $class->update($validated);

        return redirect()->route('admin.classes.index')->with('success', 'Class updated successfully.');
    }

    public function destroy(SchoolClass $class): RedirectResponse
    {
        $class->delete();

        return redirect()->route('admin.classes.index')->with('success', 'Class deleted successfully.');
    }
}
