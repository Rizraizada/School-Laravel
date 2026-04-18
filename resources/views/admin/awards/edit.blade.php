@extends('layouts.dashboard')

@section('title', 'Edit Award')

@section('content')
    <div class="card">
        <h2>Edit Award</h2>
        <form method="POST" action="{{ route('admin.awards.update', $award) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.awards._form', ['isEdit' => true])
        </form>
    </div>
@endsection
