@extends('layouts.dashboard')

@section('title', 'Create Class')

@section('content')
    <div class="card">
        <h2 style="margin-top:0;">Create Class</h2>
        <form method="POST" action="{{ route('admin.classes.store') }}">
            @csrf
            <div class="form-group">
                <label for="class_name">Class Name *</label>
                <input id="class_name" type="text" name="class_name" value="{{ old('class_name') }}" required>
            </div>
            <div class="inline-actions" style="margin-top:14px;">
                <button class="btn" type="submit">Save Class</button>
                <a class="btn" style="background:#6b7280;" href="{{ route('admin.classes.index') }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
