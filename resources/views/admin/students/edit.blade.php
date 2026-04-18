@extends('layouts.dashboard')

@section('title', 'Edit Student')

@section('content')
    <div class="card">
        <h2>Edit Student</h2>
        <form method="POST" action="{{ route('admin.students.update', $student) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.students._form', ['student' => $student, 'sections' => $sections, 'teachers' => $teachers, 'isEdit' => true])
        </form>
    </div>
@endsection
