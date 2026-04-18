@extends('layouts.dashboard')

@section('title', 'Attendance')

@section('content')
    <div class="card">
        <h2 style="margin-top:0;">Bulk Attendance Entry</h2>
        <form method="GET" class="form-grid" style="margin-bottom:14px;">
            <div class="form-group">
                <label for="section_id">Section</label>
                <select id="section_id" name="section_id">
                    <option value="">All Sections</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}" @selected((int)$selectedSection === (int)$section->id)>
                            {{ $section->section_name }} ({{ $section->schoolClass->class_name ?? '-' }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="date">Date</label>
                <input id="date" type="date" name="date" value="{{ $selectedDate }}">
            </div>
            <div class="form-group" style="align-self:end;">
                <button type="submit" class="btn">Filter</button>
            </div>
        </form>

        @if($students->isNotEmpty())
            <form method="POST" action="{{ route('admin.attendance.store-bulk') }}">
                @csrf
                <input type="hidden" name="attendance_date" value="{{ $selectedDate }}">
                <div class="table-wrap">
                    <table>
                        <thead>
                        <tr>
                            <th>Student</th>
                            <th>Section</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($students as $index => $student)
                            @php $row = $existing[$student->id] ?? null; @endphp
                            <tr>
                                <td>
                                    {{ $student->name }}
                                    <input type="hidden" name="attendance[{{ $index }}][student_id]" value="{{ $student->id }}">
                                </td>
                                <td>{{ $student->section->section_name ?? '-' }}</td>
                                <td>
                                    @php $status = old("attendance.$index.status", $row?->status ?? 'present'); @endphp
                                    <select name="attendance[{{ $index }}][status]">
                                        <option value="present" @selected($status === 'present')>Present</option>
                                        <option value="absent" @selected($status === 'absent')>Absent</option>
                                        <option value="late" @selected($status === 'late')>Late</option>
                                        <option value="excused" @selected($status === 'excused')>Excused</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="attendance[{{ $index }}][remarks]" value="{{ old("attendance.$index.remarks", $row?->remarks) }}">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="margin-top:14px;">
                    <button class="btn" type="submit">Save Attendance</button>
                </div>
            </form>
        @else
            <p>No students found for this filter.</p>
        @endif
    </div>
@endsection
