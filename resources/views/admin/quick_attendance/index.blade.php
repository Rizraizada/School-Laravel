@extends('layouts.dashboard')

@section('title', 'Quick Attendance')

@section('content')
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">
            <h2 style="margin:0;">Quick Attendance Records</h2>
            <a class="btn" href="{{ route('admin.quick-attendance.create') }}">Record Quick Attendance</a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Class / Section</th>
                    <th>Male</th>
                    <th>Female</th>
                    <th>Recorded By</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($records as $record)
                    <tr>
                        <td>{{ $record->attendance_date->format('d M, Y') }}</td>
                        <td>{{ $record->section->schoolClass->class_name }} - {{ $record->section->section_name }}</td>
                        <td>{{ $record->male_count }}</td>
                        <td>{{ $record->female_count }}</td>
                        <td>{{ $record->recorder?->full_name ?? 'N/A' }}</td>
                        <td>
                            <div class="inline-actions">
                                <a href="{{ route('admin.quick-attendance.edit', $record) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.quick-attendance.destroy', $record) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-link" onclick="return confirm('Delete this record?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">No records found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:12px;">{{ $records->links() }}</div>
    </div>
@endsection
