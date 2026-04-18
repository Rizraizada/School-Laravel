<div class="form-grid">
    <div class="form-group">
        <label for="name">Name *</label>
        <input id="name" type="text" name="name" value="{{ old('name', $director->name ?? '') }}" required>
    </div>
    <div class="form-group">
        <label for="position">Position *</label>
        <input id="position" type="text" name="position" value="{{ old('position', $director->position ?? '') }}" required>
    </div>
    <div class="form-group">
        <label for="committee">Committee</label>
        <input id="committee" type="text" name="committee" value="{{ old('committee', $director->committee ?? '') }}">
    </div>
    <div class="form-group">
        <label for="image_url">Image</label>
        <input id="image_url" type="file" name="image_url" accept="image/*">
    </div>
</div>

<div class="form-group" style="margin-top:12px;">
    <label for="details">Details</label>
    <textarea id="details" name="details">{{ old('details', $director->details ?? '') }}</textarea>
</div>

@if(!empty($director->image_url))
    <p class="mt-2"><img class="preview" src="{{ asset('storage/'.$director->image_url) }}" alt="{{ $director->name }}"></p>
@endif

<div class="inline-actions" style="margin-top:16px;">
    <button class="btn" type="submit">{{ $isEdit ? 'Update Director' : 'Create Director' }}</button>
    <a class="btn" style="background:#6b7280;" href="{{ route('admin.directors.index') }}">Cancel</a>
</div>
