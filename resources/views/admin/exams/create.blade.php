@extends('layouts.dashboard')

@section('title', 'Create Exam')

@section('content')
    <div class="card">
        <h2 style="margin-top:0;">Create Exam</h2>
        <form method="POST" action="{{ route('admin.exams.store') }}">
            @csrf
            @php($exam = new \App\Models\Exam())
            @php($isEdit = false)
            @include('admin.exams._form')
        </form>
    </div>
@endsection
