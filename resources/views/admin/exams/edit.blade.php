@extends('layouts.dashboard')

@section('title', 'Edit Exam')

@section('content')
    <div class="card">
        <h2 style="margin-top:0;">Edit Exam</h2>
        <form method="POST" action="{{ route('admin.exams.update', $exam) }}">
            @csrf
            @method('PUT')
            @include('admin.exams._form', ['isEdit' => true])
        </form>
    </div>
@endsection
