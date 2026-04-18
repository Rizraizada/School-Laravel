<div class="form-grid">
    <div class="form-group">
        <label for="title">Title *</label>
        <input id="title" name="title" type="text" value="{{ old('title', $award->title ?? '') }}" required>
    </div>
    <div class="form-group">
        <label for="subtitle">Subtitle</label>
        <input id="subtitle" name="subtitle" type="text" value="{{ old('subtitle', $award->subtitle ?? '') }}">
    </div>
</div>
<div class="form-group" style="margin-top:12px;">
    <label for="image">Image</label>
    <input id="image" name="image" type="file" accept="image/*">
    @if(!empty($award?->image))
        <p style="margin-top:8px;"><img class="preview" src="{{ asset('storage/'.$award->image) }}" alt="award image"></p>
    @endif
</div>
<div style="margin-top:14px;">
    <button class="btn" type="submit">{{ $isEdit ? 'Update' : 'Create' }}</button>
    <a class="btn" style="background:#6b7280;" href="{{ route('admin.awards.index') }}">Cancel</a>
</div>
