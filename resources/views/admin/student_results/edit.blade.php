@extends('layouts.dashboard')

@section('title', 'Edit Result')

@section('content')
    <div class="card">
        <h2 style="margin-top:0;">Edit Student Result</h2>
        <form method="POST" action="{{ route('admin.student-results.update', $record) }}">
            @csrf
            @method('PUT')
            @include('admin.student_results._form')
        </form>
    </div>
@endsection
