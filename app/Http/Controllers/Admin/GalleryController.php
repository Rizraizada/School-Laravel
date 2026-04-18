<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Support\CrudHelpers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    use CrudHelpers;

    public function index(): View
    {
        return view('admin.galleries.index', [
            'galleries' => Gallery::latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.galleries.form', [
            'gallery' => new Gallery(),
            'formAction' => route('admin.galleries.store'),
            'method' => 'POST',
            'title' => 'Create Gallery Item',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $validated['image'] = $this->storeUploadedFile($request->file('image'), 'uploads/gallery');

        Gallery::create($validated);

        return redirect()->route('admin.galleries.index')->with('success', 'Gallery item created.');
    }

    public function edit(Gallery $gallery): View
    {
        return view('admin.galleries.form', [
            'gallery' => $gallery,
            'formAction' => route('admin.galleries.update', $gallery),
            'method' => 'PUT',
            'title' => 'Edit Gallery Item',
        ]);
    }

    public function update(Request $request, Gallery $gallery): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->storeUploadedFile($request->file('image'), 'uploads/gallery');
        } else {
            unset($validated['image']);
        }

        $gallery->update($validated);

        return redirect()->route('admin.galleries.index')->with('success', 'Gallery item updated.');
    }

    public function destroy(Gallery $gallery): RedirectResponse
    {
        $gallery->delete();

        return redirect()->route('admin.galleries.index')->with('success', 'Gallery item deleted.');
    }
}
