<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Award;
use App\Support\CrudHelpers;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AwardController extends Controller
{
    use CrudHelpers;

    public function index(): View
    {
        return view('admin.awards.index', [
            'awards' => Award::latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.awards.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $validated['image'] = $this->storeUploadedFile($request->file('image'), 'awards');

        Award::create($validated);

        return redirect()->route('admin.awards.index')->with('success', 'Award added.');
    }

    public function edit(Award $award): View
    {
        return view('admin.awards.edit', compact('award'));
    }

    public function update(Request $request, Award $award): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->storeUploadedFile($request->file('image'), 'awards');
        }

        $award->update($validated);

        return redirect()->route('admin.awards.index')->with('success', 'Award updated.');
    }

    public function destroy(Award $award): RedirectResponse
    {
        $award->delete();

        return redirect()->route('admin.awards.index')->with('success', 'Award deleted.');
    }
}
