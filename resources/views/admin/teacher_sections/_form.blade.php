<div class="form-grid">
    <div class="form-group">
        <label for="user_id">Teacher *</label>
        <select id="user_id" name="user_id" required>
            <option value="">Select teacher</option>
            @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}" @selected((string) old('user_id', $assignment->user_id ?? '') === (string) $teacher->id)>
                    {{ $teacher->full_name }} ({{ $teacher->username }})
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="section_id">Section *</label>
        <select id="section_id" name="section_id" required>
            <option value="">Select section</option>
            @foreach($sections as $section)
                <option value="{{ $section->id }}" @selected((string) old('section_id', $assignment->section_id ?? '') === (string) $section->id)>
                    {{ $section->section_name }} - {{ $section->schoolClass->class_name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label>
            <input type="checkbox" name="is_primary" value="1" @checked(old('is_primary', $assignment->is_primary ?? false))>
            Primary teacher for this section
        </label>
    </div>
</div>
<div class="mt-3">
    <button class="btn" type="submit">{{ $isEdit ? 'Update Assignment' : 'Assign Teacher' }}</button>
    <a class="btn" style="background:#6b7280;" href="{{ route('admin.teacher-sections.index') }}">Cancel</a>
</div>
