@extends('layouts.dashboard')

@section('title', 'Edit Teacher Assignment')

@section('content')
    <div class="card">
        <h2 style="margin-top:0;">Edit Teacher Assignment</h2>
        <form method="POST" action="{{ route('admin.teacher-sections.update', $assignment) }}">
            @csrf
            @method('PUT')
            @include('admin.teacher_sections._form')
        </form>
    </div>
@endsection
