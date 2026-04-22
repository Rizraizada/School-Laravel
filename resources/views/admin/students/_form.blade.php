<div class="form-grid">
    <div class="form-group">
        <label for="name">Student Name *</label>
        <input id="name" type="text" name="name" value="{{ old('name', $student->name ?? '') }}" required>
    </div>
    <div class="form-group">
        <label for="class_id">Class *</label>
        <select id="class_id" name="class_id" required>
            <option value="">Select Class</option>
            @php
                $selectedClassId = old('class_id', $student->section?->class_id);
            @endphp
            @foreach($classes as $class)
                <option value="{{ $class->id }}" @selected((string) $selectedClassId === (string) $class->id)>
                    {{ $class->class_name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="section_id">Section *</label>
        <select id="section_id" name="section_id" required>
            <option value="">Select Section</option>
            @foreach($sections as $section)
                <option
                    value="{{ $section->id }}"
                    data-class-id="{{ $section->class_id }}"
                    @selected((int) old('section_id', $student->section_id ?? 0) === $section->id)
                >
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
        <label for="roll_no">Roll No</label>
        <input id="roll_no" type="text" name="roll_no" value="{{ old('roll_no', $student->roll_no ?? '') }}">
    </div>
    <div class="form-group">
        <label for="registration_no">Registration No</label>
        <input id="registration_no" type="text" name="registration_no" value="{{ old('registration_no', $student->registration_no ?? '') }}">
    </div>
    <div class="form-group">
        <label for="father_name">Father Name</label>
        <input id="father_name" type="text" name="father_name" value="{{ old('father_name', $student->father_name ?? '') }}">
    </div>
    <div class="form-group">
        <label for="mother_name">Mother Name</label>
        <input id="mother_name" type="text" name="mother_name" value="{{ old('mother_name', $student->mother_name ?? '') }}">
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
        <label for="position">Guardian Profession</label>
        <input id="position" type="text" name="position" value="{{ old('position', $student->position ?? '') }}">
    </div>
    <div class="form-group">
        <label for="expertise">Additional Notes</label>
        <input id="expertise" type="text" name="expertise" value="{{ old('expertise', $student->expertise ?? '') }}">
    </div>
</div>
<div class="form-group" style="margin-top:12px;">
    <label for="address">Address</label>
    <textarea id="address" name="address">{{ old('address', $student->address ?? '') }}</textarea>
</div>

<script>
    (() => {
        const classSelect = document.getElementById('class_id');
        const sectionSelect = document.getElementById('section_id');
        if (!classSelect || !sectionSelect) {
            return;
        }

        const syncSections = () => {
            const classId = classSelect.value;
            for (const option of sectionSelect.options) {
                if (!option.value) {
                    option.hidden = false;
                    continue;
                }

                const optionClassId = option.dataset.classId || '';
                const visible = !classId || optionClassId === classId;
                option.hidden = !visible;
                if (!visible && option.selected) {
                    sectionSelect.value = '';
                }
            }
        };

        classSelect.addEventListener('change', syncSections);
        syncSections();
    })();
</script>
<div class="form-group" style="margin-top:12px;">
    <label for="image">Profile Image</label>
    <input id="image" type="file" name="image" accept="image/*">
    @if(!empty($student?->image))
        <p style="margin-top:8px;"><img class="preview" src="{{ asset('storage/'.$student->image) }}" alt="student image"></p>
    @endif
</div>
