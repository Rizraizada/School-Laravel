@extends('layouts.dashboard')

@section('title', 'Students')

@section('content')
    <div class="card">
        <div class="section-header" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
            <h2 style="margin:0;">Student Records</h2>
            <a class="btn" href="{{ route('admin.students.create') }}">Add Student</a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Section</th>
                    <th>Teacher</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($students as $student)
                    <tr>
                        <td>{{ $student->name }}</td>
                        <td>
                            {{ $student->section?->section_name ?? '-' }}
                            @if($student->section?->schoolClass)
                                <small>({{ $student->section->schoolClass->class_name }})</small>
                            @endif
                        </td>
                        <td>{{ $student->teacher?->full_name ?? '-' }}</td>
                        <td>{{ $student->phone ?? '-' }}</td>
                        <td>{{ $student->email ?? '-' }}</td>
                        <td>
                            <div class="inline-actions">
                                <a href="{{ route('admin.students.edit', $student) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.students.destroy', $student) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-link" onclick="return confirm('Delete student?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">No students found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $students->links() }}</div>
    </div>
@endsection
