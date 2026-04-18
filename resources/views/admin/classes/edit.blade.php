@extends('layouts.dashboard')

@section('title', 'Edit Class')

@section('content')
    <div class="card">
        <h2>Edit Class</h2>
        <form method="POST" action="{{ route('admin.classes.update', $item) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="class_name">Class Name *</label>
                <input id="class_name" type="text" name="class_name" value="{{ old('class_name', $item->class_name) }}" required>
            </div>
            <div class="inline-actions" style="margin-top:12px;">
                <button class="btn" type="submit">Update Class</button>
                <a class="btn" style="background:#6b7280;" href="{{ route('admin.classes.index') }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
