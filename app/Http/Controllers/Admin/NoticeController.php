<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NoticeController extends Controller
{
    public function index(): View
    {
        return view('admin.notices.index', [
            'records' => Notice::orderByDesc('date')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.notices.form', [
            'record' => new Notice(),
            'isEdit' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'content' => ['required', 'string'],
            'badge' => ['nullable', 'string', 'max:100'],
        ]);

        Notice::create($validated);

        return redirect()->route('admin.notices.index')->with('status', 'Notice added.');
    }

    public function edit(Notice $notice): View
    {
        return view('admin.notices.form', [
            'record' => $notice,
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, Notice $notice): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'content' => ['required', 'string'],
            'badge' => ['nullable', 'string', 'max:100'],
        ]);

        $notice->update($validated);

        return redirect()->route('admin.notices.index')->with('status', 'Notice updated.');
    }

    public function destroy(Notice $notice): RedirectResponse
    {
        $notice->delete();

        return redirect()->route('admin.notices.index')->with('status', 'Notice deleted.');
    }
}
