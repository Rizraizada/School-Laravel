<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Support\CrudHelpers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityController extends Controller
{
    use CrudHelpers;

    public function index(): View
    {
        return view('admin.activities.index', [
            'activities' => Activity::orderByDesc('date')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.activities.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'author' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $validated['image'] = $this->storeUploadedFile($request->file('image'), 'activities') ?? null;
        Activity::create($validated);

        return redirect()->route('admin.activities.index')->with('status', 'Activity created.');
    }

    public function edit(Activity $activity): View
    {
        return view('admin.activities.edit', compact('activity'));
    }

    public function update(Request $request, Activity $activity): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'author' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $imagePath = $this->storeUploadedFile($request->file('image'), 'activities');
        if ($imagePath !== null) {
            $validated['image'] = $imagePath;
        }

        $activity->update($validated);

        return redirect()->route('admin.activities.index')->with('status', 'Activity updated.');
    }

    public function destroy(Activity $activity): RedirectResponse
    {
        $activity->delete();

        return redirect()->route('admin.activities.index')->with('status', 'Activity deleted.');
    }
}
