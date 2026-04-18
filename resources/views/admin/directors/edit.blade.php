@extends('layouts.dashboard')

@section('title', 'Edit Director')

@section('content')
    <div class="card">
        <h2>Edit Director</h2>
        <form method="POST" action="{{ route('admin.directors.update', $director) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.directors._form', ['director' => $director, 'isEdit' => true])
        </form>
    </div>
@endsection
