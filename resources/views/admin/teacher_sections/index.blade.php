@extends('layouts.dashboard')

@section('title', 'Teacher Sections')

@section('content')
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;margin-bottom:12px;">
            <h2 style="margin:0;">Teacher-Section Assignments</h2>
            <a class="btn" href="{{ route('admin.teacher-sections.create') }}">Assign Teacher</a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Teacher</th>
                    <th>Section</th>
                    <th>Class</th>
                    <th>Primary</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($assignments as $assignment)
                    <tr>
                        <td>{{ $assignment->teacher?->full_name ?? '-' }}</td>
                        <td>{{ $assignment->section?->section_name ?? '-' }}</td>
                        <td>{{ $assignment->section?->schoolClass?->class_name ?? '-' }}</td>
                        <td>{{ $assignment->is_primary ? 'Yes' : 'No' }}</td>
                        <td>
                            <div class="inline-actions">
                                <a href="{{ route('admin.teacher-sections.edit', $assignment) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.teacher-sections.destroy', $assignment) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-link" onclick="return confirm('Delete assignment?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No assignments found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $assignments->links() }}</div>
    </div>
@endsection
