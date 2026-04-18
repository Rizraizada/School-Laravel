<div class="form-grid">
    <div class="form-group">
        <label for="title">Title *</label>
        <input id="title" type="text" name="title" value="{{ old('title', $activity->title ?? '') }}" required>
    </div>
    <div class="form-group">
        <label for="date">Date *</label>
        <input id="date" type="date" name="date" value="{{ old('date', isset($activity->date) ? \Illuminate\Support\Carbon::parse($activity->date)->format('Y-m-d') : '') }}" required>
    </div>
    <div class="form-group">
        <label for="author">Author</label>
        <input id="author" type="text" name="author" value="{{ old('author', $activity->author ?? '') }}">
    </div>
    <div class="form-group">
        <label for="image">Image</label>
        <input id="image" type="file" name="image" accept="image/*">
        @if(!empty($activity?->image))
            <p style="margin-top:8px;"><img class="preview" src="{{ asset('storage/'.$activity->image) }}" alt="activity image"></p>
        @endif
    </div>
</div>
