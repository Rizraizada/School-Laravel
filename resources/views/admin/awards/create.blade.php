@extends('layouts.dashboard')

@section('title', 'Create Award')

@section('content')
    <div class="card">
        <h2>Create Award</h2>
        <form method="POST" action="{{ route('admin.awards.store') }}" enctype="multipart/form-data">
            @csrf
            @php($award = new \App\Models\Award())
            @php($isEdit = false)
            @include('admin.awards._form')
        </form>
    </div>
@endsection
