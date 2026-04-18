@extends('layouts.dashboard')

@section('title', 'Assign Teacher Section')

@section('content')
    <div class="card">
        <h2>Create Assignment</h2>
        <form method="POST" action="{{ route('admin.teacher-sections.store') }}">
            @csrf
            @include('admin.teacher_sections._form')
        </form>
    </div>
@endsection
