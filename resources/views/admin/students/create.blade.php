@extends('layouts.dashboard')

@section('title', 'Add Student')

@section('content')
    <div class="card">
        <h2>Add Student</h2>
        <form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.students._form', ['student' => new \App\Models\Student(), 'isEdit' => false])
        </form>
    </div>
@endsection
