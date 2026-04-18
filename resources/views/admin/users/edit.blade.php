@extends('layouts.dashboard')

@section('title', 'Edit User')

@section('content')
    <div class="card">
        <h2>Edit User</h2>
        <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.users._form', ['user' => $user, 'isEdit' => true])
        </form>
    </div>
@endsection
