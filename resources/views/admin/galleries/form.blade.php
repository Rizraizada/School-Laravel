@extends('layouts.dashboard')

@section('title', $title)

@section('content')
    <div class="card">
        <h2>{{ $title }}</h2>
        <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data">
            @csrf
            @if($method !== 'POST')
                @method($method)
            @endif

            <div class="form-grid">
                <div class="form-group">
                    <label for="title">Title *</label>
                    <input id="title" type="text" name="title" value="{{ old('title', $gallery->title) }}" required>
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    <input id="category" type="text" name="category" value="{{ old('category', $gallery->category) }}">
                </div>
            </div>
            <div class="form-group" style="margin-top:12px;">
                <label for="description">Description</label>
                <textarea id="description" name="description">{{ old('description', $gallery->description) }}</textarea>
            </div>
            <div class="form-group" style="margin-top:12px;">
                <label for="image">Image</label>
                <input id="image" type="file" name="image" accept="image/*">
                @if($gallery->image)
                    <p style="margin-top:8px;"><img class="preview" src="{{ asset('storage/'.$gallery->image) }}" alt="gallery image"></p>
                @endif
            </div>
            <div style="margin-top:14px;">
                <button class="btn" type="submit">Save</button>
                <a class="btn" style="background:#6b7280;" href="{{ route('admin.galleries.index') }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
