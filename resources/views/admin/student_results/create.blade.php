@extends('layouts.dashboard')

@section('title', 'Create Student Result')

@section('content')
    <div class="card">
        <h2 style="margin-top:0;">Create Student Result</h2>
        <form method="POST" action="{{ route('admin.student-results.store') }}">
            @csrf
            @php($record = new \App\Models\StudentResult())
            @php($isEdit = false)
            @include('admin.student_results._form')
        </form>
    </div>
@endsection
