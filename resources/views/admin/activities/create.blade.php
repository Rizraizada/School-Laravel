@extends('layouts.dashboard')

@section('title', 'Add Activity')

@section('content')
    <div class="card">
        <h2>Add Activity</h2>
        <form method="POST" action="{{ route('admin.activities.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.activities._form')
            <div class="inline-actions" style="margin-top: 14px;">
                <button class="btn" type="submit">Create</button>
                <a href="{{ route('admin.activities.index') }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
