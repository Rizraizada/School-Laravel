<div class="form-grid">
    <div class="form-group">
        <label for="section_id">Section *</label>
        <select id="section_id" name="section_id" required>
            <option value="">Select section</option>
            @foreach($sections as $section)
                <option value="{{ $section->id }}" @selected(old('section_id', $record->section_id ?? '') == $section->id)>
                    {{ $section->schoolClass->class_name }} - {{ $section->section_name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="attendance_date">Date *</label>
        <input id="attendance_date" type="date" name="attendance_date" value="{{ old('attendance_date', optional($record->attendance_date)->format('Y-m-d') ?? now()->toDateString()) }}" required>
    </div>
    <div class="form-group">
        <label for="male_count">Male Count *</label>
        <input id="male_count" type="number" min="0" name="male_count" value="{{ old('male_count', $record->male_count ?? 0) }}" required>
    </div>
    <div class="form-group">
        <label for="female_count">Female Count *</label>
        <input id="female_count" type="number" min="0" name="female_count" value="{{ old('female_count', $record->female_count ?? 0) }}" required>
    </div>
</div>
