@extends('layouts.dashboard')

@section('title', 'Edit Activity')

@section('content')
    <div class="card">
        <h2>Edit Activity</h2>
        <form method="POST" action="{{ route('admin.activities.update', $activity) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.activities._form', ['activity' => $activity])
        </form>
    </div>
@endsection
