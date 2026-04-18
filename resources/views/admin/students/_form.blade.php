<div class="form-grid">
    <div class="form-group">
        <label for="name">Student Name *</label>
        <input id="name" type="text" name="name" value="{{ old('name', $student->name ?? '') }}" required>
    </div>
    <div class="form-group">
        <label for="section_id">Section *</label>
        <select id="section_id" name="section_id" required>
            <option value="">Select Section</option>
            @foreach($sections as $section)
                <option value="{{ $section->id }}" @selected((int) old('section_id', $student->section_id ?? 0) === $section->id)>
                    {{ $section->schoolClass?->class_name }} - {{ $section->section_name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="user_id">Assigned Teacher</label>
        <select id="user_id" name="user_id">
            <option value="">None</option>
            @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}" @selected((int) old('user_id', $student->user_id ?? 0) === $teacher->id)>
                    {{ $teacher->full_name }} ({{ $teacher->role }})
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="phone">Phone</label>
        <input id="phone" type="text" name="phone" value="{{ old('phone', $student->phone ?? '') }}">
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $student->email ?? '') }}">
    </div>
    <div class="form-group">
        <label for="gender">Gender</label>
        @php $gender = old('gender', $student->gender ?? ''); @endphp
        <select id="gender" name="gender">
            <option value="">Select</option>
            <option value="male" @selected($gender === 'male')>Male</option>
            <option value="female" @selected($gender === 'female')>Female</option>
            <option value="other" @selected($gender === 'other')>Other</option>
        </select>
    </div>
    <div class="form-group">
        <label for="position">Father Name (position)</label>
        <input id="position" type="text" name="position" value="{{ old('position', $student->position ?? '') }}">
    </div>
    <div class="form-group">
        <label for="expertise">Mother Name (expertise)</label>
        <input id="expertise" type="text" name="expertise" value="{{ old('expertise', $student->expertise ?? '') }}">
    </div>
</div>
<div class="form-group" style="margin-top:12px;">
    <label for="address">Address</label>
    <textarea id="address" name="address">{{ old('address', $student->address ?? '') }}</textarea>
</div>
<div class="form-group" style="margin-top:12px;">
    <label for="image">Profile Image</label>
    <input id="image" type="file" name="image" accept="image/*">
    @if(!empty($student?->image))
        <p style="margin-top:8px;"><img class="preview" src="{{ asset('storage/'.$student->image) }}" alt="student image"></p>
    @endif
</div>
