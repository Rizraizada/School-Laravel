@extends('layouts.dashboard')

@section('title', 'Student Results')

@section('content')
    <div class="card">
        <div class="section-header">
            <h2 style="margin:0;">Student Results</h2>
            <a class="btn" href="{{ route('admin.student-results.create') }}">Add Result</a>
        </div>

        <form method="GET" action="{{ route('admin.student-results.index') }}" class="form-grid" style="margin-top:14px;">
            <div class="form-group">
                <label for="student_name">Student Name</label>
                <input id="student_name" type="text" name="student_name" value="{{ $filters['student_name'] ?? '' }}" placeholder="Search by name">
            </div>
            <div class="form-group">
                <label for="roll_no">Roll No</label>
                <input id="roll_no" type="text" name="roll_no" value="{{ $filters['roll_no'] ?? '' }}" placeholder="Search by roll">
            </div>
            <div class="form-group">
                <label for="exam_year">Year</label>
                <input id="exam_year" type="number" min="2000" max="2100" name="exam_year" value="{{ $filters['exam_year'] ?? '' }}" placeholder="Exam year">
            </div>
            <div class="form-group">
                <label for="class_id">Class</label>
                <select id="class_id" name="class_id">
                    <option value="">All classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" @selected(($filters['class_id'] ?? null) == $class->id)>{{ $class->class_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="section_id">Section</label>
                <select id="section_id" name="section_id">
                    <option value="">All sections</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}" @selected(($filters['section_id'] ?? null) == $section->id)>
                            {{ $section->schoolClass?->class_name ? $section->schoolClass->class_name.' - ' : '' }}{{ $section->section_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="align-self:end;">
                <div class="inline-actions">
                    <button class="btn" type="submit">Filter</button>
                    <a class="btn" style="background:#6b7280;" href="{{ route('admin.student-results.index') }}">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <div class="card" style="margin-top:16px;">
        <form method="POST" action="{{ route('admin.student-results.bulk-destroy') }}">
            @csrf
            @method('DELETE')

            <div class="section-header">
                <h3 style="margin:0;">Result Entries</h3>
                <button class="btn btn-danger" type="submit" onclick="return confirm('Delete selected result rows?')">Bulk Delete</button>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th style="width:40px;">
                            <input type="checkbox" onclick="document.querySelectorAll('input[name=&quot;result_ids[]&quot;]').forEach((el) => el.checked = this.checked)">
                        </th>
                        <th>Student</th>
                        <th>Roll</th>
                        <th>Class/Section</th>
                        <th>Exam</th>
                        <th>Year</th>
                        <th>Total</th>
                        <th>GPA</th>
                        <th>Grade</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($results as $result)
                        <tr>
                            <td><input type="checkbox" name="result_ids[]" value="{{ $result->id }}"></td>
                            <td>{{ $result->student_name }}</td>
                            <td>{{ $result->roll_no ?: '-' }}</td>
                            <td>
                                {{ $result->schoolClass?->class_name ?? $result->class_level ?? '-' }}
                                @if($result->section?->section_name || $result->section_name)
                                    - {{ $result->section?->section_name ?? $result->section_name }}
                                @endif
                            </td>
                            <td>{{ $result->exam_name }}</td>
                            <td>{{ $result->exam_year }}</td>
                            <td>{{ $result->total_marks ?? '-' }}</td>
                            <td>{{ $result->gpa ?? '-' }}</td>
                            <td>{{ $result->grade ?? '-' }}</td>
                            <td>
                                <div class="inline-actions">
                                    <a href="{{ route('admin.student-results.edit', $result) }}">Edit</a>
                                    <form method="POST" action="{{ route('admin.student-results.destroy', $result) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-link" onclick="return confirm('Delete this result?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">No student result found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </form>
        <div class="pagination">{{ $results->links() }}</div>
    </div>

    <div class="card" style="margin-top:16px;">
        <h3 style="margin-top:0;">Grouped Summary</h3>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Year</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Total Students</th>
                    <th>Avg GPA</th>
                </tr>
                </thead>
                <tbody>
                @forelse($summary as $row)
                    <tr>
                        <td>{{ $row->exam_year }}</td>
                        <td>{{ $row->schoolClass?->class_name ?? '-' }}</td>
                        <td>{{ $row->section?->section_name ?? '-' }}</td>
                        <td>{{ $row->total_records }}</td>
                        <td>{{ $row->avg_gpa !== null ? number_format((float) $row->avg_gpa, 2) : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No summary data available.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
