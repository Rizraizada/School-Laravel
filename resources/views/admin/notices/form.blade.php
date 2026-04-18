@extends('layouts.dashboard')

@section('title', $isEdit ? 'Edit Notice' : 'Create Notice')

@section('content')
    <div class="card">
        <h2>{{ $isEdit ? 'Edit Notice' : 'Create Notice' }}</h2>
        <form method="POST" action="{{ $isEdit ? route('admin.notices.update', $record) : route('admin.notices.store') }}">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif
            <div class="form-grid">
                <div class="form-group">
                    <label>Title *</label>
                    <input type="text" name="title" required value="{{ old('title', $record->title) }}">
                </div>
                <div class="form-group">
                    <label>Date *</label>
                    <input type="date" name="date" required value="{{ old('date', optional($record->date)->format('Y-m-d')) }}">
                </div>
                <div class="form-group">
                    <label>Badge</label>
                    <input type="text" name="badge" value="{{ old('badge', $record->badge) }}" placeholder="Urgent / New / Notice">
                </div>
            </div>
            <div class="form-group" style="margin-top:12px;">
                <label>Content *</label>
                <textarea name="content" required>{{ old('content', $record->content) }}</textarea>
            </div>
            <div class="inline-actions" style="margin-top:16px;">
                <button class="btn" type="submit">{{ $isEdit ? 'Update Notice' : 'Create Notice' }}</button>
                <a class="btn" style="background:#6b7280;" href="{{ route('admin.notices.index') }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
