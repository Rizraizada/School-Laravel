@extends('layouts.dashboard')

@section('title', 'Sections')

@section('content')
    <div class="card">
        <h2>Create Section</h2>
        <form method="POST" action="{{ route('admin.sections.store') }}" class="form-grid">
            @csrf
            <div class="form-group">
                <label for="section_name">Section Name *</label>
                <input id="section_name" type="text" name="section_name" value="{{ old('section_name') }}" required>
            </div>
            <div class="form-group">
                <label for="class_id">Class *</label>
                <select id="class_id" name="class_id" required>
                    <option value="">Select class</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="align-self:end;">
                <button type="submit" class="btn">Create</button>
            </div>
        </form>
    </div>

    <div class="card" style="margin-top: 16px;">
        <h2>All Sections</h2>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Section</th>
                        <th>Class</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sections as $section)
                        <tr>
                            <td>{{ $section->section_name }}</td>
                            <td>{{ $section->schoolClass->class_name ?? '-' }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.sections.update', $section) }}" style="display:inline-flex; gap:8px; align-items:center;">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="section_name" value="{{ $section->section_name }}" required style="width:130px;">
                                    <select name="class_id" required style="width:140px;">
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" @selected($section->class_id === $class->id)>{{ $class->class_name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn">Update</button>
                                </form>
                                <form method="POST" action="{{ route('admin.sections.destroy', $section) }}" style="display:inline-block; margin-left:8px;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-link" onclick="return confirm('Delete this section?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3">No sections found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:12px;">{{ $sections->links() }}</div>
    </div>
@endsection
