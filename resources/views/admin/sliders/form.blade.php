@extends('layouts.dashboard')

@section('title', $isEdit ? 'Edit Slider' : 'Add Slider')

@section('content')
    <div class="card">
        <form method="POST" enctype="multipart/form-data" action="{{ $isEdit ? route('admin.sliders.update', $slider) : route('admin.sliders.store') }}">
            @csrf
            @if($isEdit) @method('PUT') @endif
            <div class="form-group">
                <label for="image">Slider Image {{ $isEdit ? '' : '*' }}</label>
                <input id="image" type="file" name="image" accept="image/*" {{ $isEdit ? '' : 'required' }}>
                @if($isEdit && $slider->image)
                    <p style="margin-top: 8px;">
                        <img class="preview" src="{{ asset('storage/'.$slider->image) }}" alt="Slider image">
                    </p>
                @endif
            </div>
            <div class="inline-actions" style="margin-top: 14px;">
                <button class="btn" type="submit">{{ $isEdit ? 'Update' : 'Create' }}</button>
                <a class="btn" style="background:#6b7280;" href="{{ route('admin.sliders.index') }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
