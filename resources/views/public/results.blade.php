@extends('layouts.app')

@section('full_width', '1')

@section('content')
    <section class="panel">
        <h3 class="panel-header">Student Result Search</h3>
        <div class="panel-body">
            <form method="GET" action="{{ route('public.results') }}">
                <div style="display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:10px;">
                    <div class="form-group">
                        <label for="search">Name/Roll/Registration</label>
                        <input id="search" type="text" name="search" value="{{ $filters['search'] ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="exam_year">Exam Year</label>
                        <input id="exam_year" type="number" name="exam_year" min="2000" max="2100" value="{{ $filters['exam_year'] ?? '' }}">
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
                        <button class="btn" type="submit">Search</button>
                        <a class="btn btn-light" href="{{ route('public.results') }}">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <section class="panel">
        <h3 class="panel-header">Matching Results</h3>
        <div class="panel-body">
            <div class="portal-table-wrap">
                <table class="portal-table">
                    <thead>
                    <tr>
                        <th>Student</th>
                        <th>Roll</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Exam</th>
                        <th>Year</th>
                        <th>Total</th>
                        <th>GPA</th>
                        <th>Grade</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($results as $result)
                        <tr>
                            <td>{{ $result->student_name }}</td>
                            <td>{{ $result->roll_no ?: '-' }}</td>
                            <td>{{ $result->schoolClass?->class_name ?? $result->class_level ?? '-' }}</td>
                            <td>{{ $result->section?->section_name ?? $result->section_name ?? '-' }}</td>
                            <td>{{ $result->exam_name }}</td>
                            <td>{{ $result->exam_year }}</td>
                            <td>{{ $result->total_marks ?? '-' }}</td>
                            <td>{{ $result->gpa ?? '-' }}</td>
                            <td>{{ $result->grade ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-muted">No result found for the provided search.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination">{{ $results->links() }}</div>
        </div>
    </section>

    <section class="panel">
        <h3 class="panel-header">Grouped Summary</h3>
        <div class="panel-body portal-table-wrap">
            <table class="portal-table">
                <thead>
                <tr>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Year</th>
                    <th>Total Students</th>
                    <th>Avg GPA</th>
                </tr>
                </thead>
                <tbody>
                @forelse($summary as $row)
                    <tr>
                        <td>{{ $row->schoolClass?->class_name ?? '-' }}</td>
                        <td>{{ $row->section?->section_name ?? '-' }}</td>
                        <td>{{ $row->exam_year }}</td>
                        <td>{{ $row->total_students }}</td>
                        <td>{{ $row->avg_gpa !== null ? number_format((float) $row->avg_gpa, 2) : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-muted">No grouped summary available.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
