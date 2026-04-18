<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Support\CrudHelpers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SliderController extends Controller
{
    use CrudHelpers;

    public function index(): View
    {
        return view('admin.sliders.index', [
            'sliders' => Slider::latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.sliders.form', [
            'slider' => new Slider(),
            'isEdit' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'image' => ['required', 'image', 'max:4096'],
        ]);

        $validated['image'] = $this->storeUploadedFile($request->file('image'), 'sliders');

        Slider::create($validated);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider image added.');
    }

    public function edit(Slider $slider): View
    {
        return view('admin.sliders.form', [
            'slider' => $slider,
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, Slider $slider): RedirectResponse
    {
        $validated = $request->validate([
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->storeUploadedFile($request->file('image'), 'sliders');
        }

        $slider->update($validated);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider image updated.');
    }

    public function destroy(Slider $slider): RedirectResponse
    {
        $slider->delete();

        return redirect()->route('admin.sliders.index')->with('success', 'Slider image deleted.');
    }
}
