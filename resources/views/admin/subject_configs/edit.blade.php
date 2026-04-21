@extends('layouts.dashboard')

@section('title', 'Edit Subject Configuration')

@section('content')
    <div class="card">
        <h2 style="margin-top:0;">Edit Subject Configuration</h2>
        <form method="POST" action="{{ route('admin.subject-config.update', $subjectConfig) }}">
            @csrf
            @method('PUT')
            @include('admin.subject_configs._form', ['isEdit' => true])
        </form>
    </div>
@endsection
