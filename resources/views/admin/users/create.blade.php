@extends('layouts.dashboard')

@section('title', 'Create User')

@section('content')
    <div class="card">
        <h2>Create User</h2>
        <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
            @include('admin.users._form', ['submitLabel' => 'Create User'])
        </form>
    </div>
@endsection
