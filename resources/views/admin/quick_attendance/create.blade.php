@extends('layouts.dashboard')

@section('title', 'Quick Attendance - New')

@section('content')
    <div class="card">
        <h2 style="margin-top:0;">Add Quick Attendance</h2>
        <form method="POST" action="{{ route('admin.quick-attendance.store') }}">
            @csrf
            @php($record = new \App\Models\QuickAttendance())
            @include('admin.quick_attendance._form')
            <div style="margin-top: 14px;">
                <button class="btn" type="submit">Save</button>
                <a class="btn" style="background:#6b7280;" href="{{ route('admin.quick-attendance.index') }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
