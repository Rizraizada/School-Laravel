@extends('layouts.dashboard')

@section('title', 'Attendance Report')

@section('content')
    <div class="card">
        <h2>Attendance Report</h2>
        <form method="GET" class="form-grid" style="margin-bottom: 12px;">
            <div class="form-group">
                <label>Section</label>
                <select name="section_id">
                    <option value="">All Sections</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}" @selected((string)$selectedSection === (string)$section->id)>
                            {{ $section->schoolClass?->class_name }} - {{ $section->section_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Start Date</label>
                <input type="date" name="start_date" value="{{ $startDate }}">
            </div>
            <div class="form-group">
                <label>End Date</label>
                <input type="date" name="end_date" value="{{ $endDate }}">
            </div>
            <div class="form-group">
                <label>&nbsp;</label>
                <button class="btn" type="submit">Filter</button>
            </div>
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Student</th>
                    <th>Section</th>
                    <th>Status</th>
                    <th>Recorded By</th>
                    <th>Remarks</th>
                </tr>
                </thead>
                <tbody>
                @forelse($records as $record)
                    <tr>
                        <td>{{ $record->attendance_date->format('d M Y') }}</td>
                        <td>{{ $record->student?->name ?? '-' }}</td>
                        <td>{{ $record->student?->section?->schoolClass?->class_name }} - {{ $record->student?->section?->section_name }}</td>
                        <td>{{ ucfirst($record->status) }}</td>
                        <td>{{ $record->recorder?->full_name ?? '-' }}</td>
                        <td>{{ $record->remarks ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6">No attendance records found for this filter.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $records->links() }}</div>
    </div>
@endsection
