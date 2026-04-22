@extends('layouts.dashboard')

@section('title', 'Create Subject Configuration')

@section('content')
    <div class="card">
        <h2 style="margin-top:0;">Create Subject Configuration</h2>
        <form method="POST" action="{{ route('admin.subject-config.store') }}">
            @csrf
            @php($subjectConfig = new \App\Models\SubjectConfig())
            @php($isEdit = false)
            @include('admin.subject_configs._form')
        </form>
    </div>
@endsection
