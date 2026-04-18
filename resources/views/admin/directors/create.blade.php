@extends('layouts.dashboard')

@section('title', 'Create Director')

@section('content')
    <div class="card">
        <h2 style="margin-top:0;">Create Board Director</h2>
        <form method="POST" action="{{ route('admin.directors.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.directors._form', ['director' => new \App\Models\BoardDirector(), 'isEdit' => false])
        </form>
    </div>
@endsection
