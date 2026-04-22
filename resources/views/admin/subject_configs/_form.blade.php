<div class="form-grid">
    <div class="form-group">
        <label for="class_id">Class (Optional)</label>
        <select id="class_id" name="class_id">
            <option value="">Select class</option>
            @foreach($classes as $class)
                <option value="{{ $class->id }}" @selected(old('class_id', $subjectConfig->class_id ?? '') == $class->id)>
                    {{ $class->class_name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="class_level">Class Level *</label>
        <input id="class_level" type="text" name="class_level" value="{{ old('class_level', $subjectConfig->class_level ?? '') }}" required placeholder="Example: Class 6">
    </div>

    <div class="form-group">
        <label for="group_name">Group Name</label>
        <input id="group_name" type="text" name="group_name" value="{{ old('group_name', $subjectConfig->group_name ?? '') }}" placeholder="General / Science / Commerce">
    </div>

    <div class="form-group">
        <label for="subject_id">Master Subject</label>
        <select id="subject_id" name="subject_id">
            <option value="">Select subject</option>
            @foreach($subjects as $subject)
                <option
                    value="{{ $subject->id }}"
                    data-subject-name="{{ $subject->subject_name }}"
                    data-subject-code="{{ $subject->subject_code }}"
                    data-subject-type="{{ $subject->subject_type }}"
                    @selected((string) old('subject_id', $subjectConfig->subject_id ?? '') === (string) $subject->id)
                >
                    {{ $subject->subject_name }}{{ $subject->subject_code ? ' ('.$subject->subject_code.')' : '' }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="subject_code">Subject Code</label>
        <input id="subject_code" type="text" name="subject_code" value="{{ old('subject_code', $subjectConfig->subject_code ?? '') }}">
    </div>

    <div class="form-group">
        <label for="subject_name">Subject Name *</label>
        <input id="subject_name" type="text" name="subject_name" value="{{ old('subject_name', $subjectConfig->subject_name ?? '') }}" required>
    </div>

    <div class="form-group">
        <label for="subject_type">Subject Type *</label>
        <select id="subject_type" name="subject_type" required>
            @php($subjectType = old('subject_type', $subjectConfig->subject_type ?? 'compulsory'))
            <option value="compulsory" @selected($subjectType === 'compulsory')>Compulsory</option>
            <option value="optional" @selected($subjectType === 'optional')>Optional</option>
            <option value="practical" @selected($subjectType === 'practical')>Practical</option>
        </select>
    </div>

    <div class="form-group">
        <label for="full_mark">Full Mark *</label>
        <input id="full_mark" type="number" min="1" name="full_mark" value="{{ old('full_mark', $subjectConfig->full_mark ?? 100) }}" required>
    </div>

    <div class="form-group">
        <label for="pass_mark">Pass Mark *</label>
        <input id="pass_mark" type="number" min="0" name="pass_mark" value="{{ old('pass_mark', $subjectConfig->pass_mark ?? 33) }}" required>
    </div>

    <div class="form-group">
        <label for="subjective_mark">Subjective Mark</label>
        <input id="subjective_mark" type="number" min="0" name="subjective_mark" value="{{ old('subjective_mark', $subjectConfig->subjective_mark ?? '') }}">
    </div>

    <div class="form-group">
        <label for="mcq_mark">MCQ Mark</label>
        <input id="mcq_mark" type="number" min="0" name="mcq_mark" value="{{ old('mcq_mark', $subjectConfig->mcq_mark ?? '') }}">
    </div>

    <div class="form-group">
        <label for="practical_mark">Practical Mark</label>
        <input id="practical_mark" type="number" min="0" name="practical_mark" value="{{ old('practical_mark', $subjectConfig->practical_mark ?? '') }}">
    </div>

    <div class="form-group">
        <label for="sort_order">Sort Order *</label>
        <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $subjectConfig->sort_order ?? 0) }}" required>
    </div>
</div>

<div class="form-grid" style="margin-top: 12px;">
    <div class="form-group">
        @php($isOptional = old('is_optional', (int) ($subjectConfig->is_optional ?? false)))
        <label>
            <input type="hidden" name="is_optional" value="0">
            <input type="checkbox" name="is_optional" value="1" @checked((string) $isOptional === '1')>
            Optional Subject
        </label>
    </div>
    <div class="form-group">
        @php($includeInGpa = old('include_in_gpa', (int) ($subjectConfig->include_in_gpa ?? true)))
        <label>
            <input type="hidden" name="include_in_gpa" value="0">
            <input type="checkbox" name="include_in_gpa" value="1" @checked((string) $includeInGpa === '1')>
            Include in GPA
        </label>
    </div>
    <div class="form-group">
        @php($includeInTotal = old('include_in_total_score', (int) ($subjectConfig->include_in_total_score ?? true)))
        <label>
            <input type="hidden" name="include_in_total_score" value="0">
            <input type="checkbox" name="include_in_total_score" value="1" @checked((string) $includeInTotal === '1')>
            Include in Total Score
        </label>
    </div>
    <div class="form-group">
        @php($isActive = old('is_active', (int) ($subjectConfig->is_active ?? true)))
        <label>
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked((string) $isActive === '1')>
            Active
        </label>
    </div>
</div>

<div style="margin-top: 14px;" class="inline-actions">
    <button class="btn" type="submit">{{ $isEdit ? 'Update Configuration' : 'Save Configuration' }}</button>
    <a class="btn" style="background:#6b7280;" href="{{ route('admin.subject-config.index') }}">Cancel</a>
</div>

<script>
    (() => {
        const subjectSelect = document.getElementById('subject_id');
        const subjectNameInput = document.getElementById('subject_name');
        const subjectCodeInput = document.getElementById('subject_code');
        const subjectTypeSelect = document.getElementById('subject_type');
        if (!subjectSelect || !subjectNameInput || !subjectCodeInput || !subjectTypeSelect) {
            return;
        }

        const syncFromMasterSubject = () => {
            const option = subjectSelect.options[subjectSelect.selectedIndex];
            if (!option || !option.value) {
                return;
            }

            if (!subjectNameInput.value) {
                subjectNameInput.value = option.dataset.subjectName || '';
            }
            if (!subjectCodeInput.value) {
                subjectCodeInput.value = option.dataset.subjectCode || '';
            }
            if (option.dataset.subjectType) {
                subjectTypeSelect.value = option.dataset.subjectType;
            }
        };

        subjectSelect.addEventListener('change', syncFromMasterSubject);
        syncFromMasterSubject();
    })();
</script>
