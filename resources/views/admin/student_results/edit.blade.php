@extends('layouts.dashboard')

@section('title', 'Edit Result')

@section('content')
    <div class="card">
        <h2 style="margin-top:0;">Edit Student Result</h2>
        @if($record->exam)
            <p style="margin-top:-6px;color:#6b7280;">
                Exam: {{ $record->exam->exam_name }} ({{ $record->exam->exam_year }}) -
                {{ $record->exam->schoolClass?->class_name }}
                {{ $record->exam->section?->section_name ? '('.$record->exam->section->section_name.')' : '' }}
            </p>
        @endif
        <form method="POST" action="{{ route('admin.student-results.update', $record) }}">
            @csrf
            @method('PUT')
            @include('admin.student_results._form')
        </form>
    </div>
@endsection
