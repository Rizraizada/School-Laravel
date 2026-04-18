<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoardDirector;
use App\Support\CrudHelpers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BoardDirectorController extends Controller
{
    use CrudHelpers;

    public function index(): View
    {
        return view('admin.directors.index', [
            'directors' => BoardDirector::latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.directors.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'committee' => ['nullable', 'string', 'max:255'],
            'details' => ['nullable', 'string'],
            'image_url' => ['nullable', 'image', 'max:4096'],
        ]);

        $data['image_url'] = $this->storeUploadedFile($request->file('image_url'), 'directors');

        BoardDirector::create($data);

        return redirect()->route('admin.directors.index')->with('status', 'Director created successfully.');
    }

    public function edit(BoardDirector $director): View
    {
        return view('admin.directors.edit', [
            'director' => $director,
        ]);
    }

    public function update(Request $request, BoardDirector $director): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'committee' => ['nullable', 'string', 'max:255'],
            'details' => ['nullable', 'string'],
            'image_url' => ['nullable', 'image', 'max:4096'],
        ]);

        $uploadedImage = $this->storeUploadedFile($request->file('image_url'), 'directors');
        if ($uploadedImage !== null) {
            $data['image_url'] = $uploadedImage;
        } else {
            unset($data['image_url']);
        }

        $director->update($data);

        return redirect()->route('admin.directors.index')->with('status', 'Director updated successfully.');
    }

    public function destroy(BoardDirector $director): RedirectResponse
    {
        $director->delete();

        return redirect()->route('admin.directors.index')->with('status', 'Director removed successfully.');
    }
}
