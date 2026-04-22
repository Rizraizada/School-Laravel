@extends('layouts.dashboard')

@section('title', 'Exam Setup')

@section('content')
    <div class="card">
        <div class="section-header">
            <h2 style="margin:0;">Exam Setup</h2>
            <a class="btn" href="{{ route('admin.exams.create') }}">Create Exam</a>
        </div>

        <form method="GET" action="{{ route('admin.exams.index') }}" class="form-grid" style="margin-top:14px;">
            <div class="form-group">
                <label for="class_id">Class</label>
                <select id="class_id" name="class_id">
                    <option value="">All classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" @selected(($filters['class_id'] ?? null) == $class->id)>
                            {{ $class->class_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="section_id">Section</label>
                <select id="section_id" name="section_id">
                    <option value="">All sections</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}" @selected(($filters['section_id'] ?? null) == $section->id)>
                            {{ $section->schoolClass?->class_name }} - {{ $section->section_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="exam_year">Year</label>
                <input id="exam_year" type="number" min="2000" max="2100" name="exam_year" value="{{ $filters['exam_year'] ?? '' }}">
            </div>
            <div class="form-group" style="align-self:end;">
                <div class="inline-actions">
                    <button class="btn" type="submit">Filter</button>
                    <a class="btn" style="background:#6b7280;" href="{{ route('admin.exams.index') }}">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <div class="card" style="margin-top:16px;">
        <h3 style="margin-top:0;">Exam List</h3>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Exam Name</th>
                    <th>Year</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Subjects</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($exams as $exam)
                    <tr>
                        <td>{{ $exam->exam_name }}</td>
                        <td>{{ $exam->exam_year }}</td>
                        <td>{{ $exam->schoolClass?->class_name ?? '-' }}</td>
                        <td>{{ $exam->section?->section_name ?? 'All Sections' }}</td>
                        <td>{{ $exam->subjects->count() }}</td>
                        <td>{{ $exam->is_active ? 'Active' : 'Inactive' }}</td>
                        <td>
                            <div class="inline-actions">
                                <a href="{{ route('admin.exams.edit', $exam) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.exams.destroy', $exam) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-link" onclick="return confirm('Delete this exam?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7">No exams found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:12px;">{{ $exams->links() }}</div>
    </div>
@endsection
