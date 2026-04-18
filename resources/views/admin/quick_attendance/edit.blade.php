@extends('layouts.dashboard')

@section('title', 'Edit Quick Attendance')

@section('content')
    <div class="card">
        <h2 style="margin-top:0;">Edit Quick Attendance Record</h2>
        <form method="POST" action="{{ route('admin.quick-attendance.update', $record) }}">
            @csrf
            @method('PUT')
            @include('admin.quick_attendance._form', ['record' => $record])
        </form>
    </div>
@endsection
