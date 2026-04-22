@php
    $oldSubjects = collect(old('subjects', []));
    $existingSubjects = collect($exam->subjects ?? [])->map(function ($subject) {
        return [
            'subject_config_id' => $subject->subject_config_id,
            'subject_name' => $subject->subject_name,
            'subject_code' => $subject->subject_code,
            'full_mark' => $subject->full_mark,
            'pass_mark' => $subject->pass_mark,
            'sort_order' => $subject->sort_order,
            'is_optional' => (bool) $subject->is_optional,
            'include_in_gpa' => (bool) $subject->include_in_gpa,
            'include_in_total_score' => (bool) $subject->include_in_total_score,
        ];
    });
    $subjectRows = $oldSubjects->isNotEmpty() ? $oldSubjects->values() : $existingSubjects->values();

    $subjectConfigsByClass = $subjectConfigs->groupBy('class_id')->map(function ($items) {
        return $items->map(fn ($item) => [
            'id' => $item->id,
            'subject_name' => $item->subject_name,
            'subject_code' => $item->subject_code,
            'full_mark' => $item->full_mark,
            'pass_mark' => $item->pass_mark,
            'sort_order' => $item->sort_order,
            'is_optional' => (bool) $item->is_optional,
            'include_in_gpa' => (bool) $item->include_in_gpa,
            'include_in_total_score' => (bool) $item->include_in_total_score,
        ])->values();
    });
@endphp

<div class="form-grid">
    <div class="form-group">
        <label for="exam_name">Exam Name *</label>
        <input id="exam_name" type="text" name="exam_name" value="{{ old('exam_name', $exam->exam_name ?? '') }}" required placeholder="Example: Class Six Test">
    </div>
    <div class="form-group">
        <label for="exam_year">Exam Year *</label>
        <input id="exam_year" type="number" min="2000" max="2100" name="exam_year" value="{{ old('exam_year', $exam->exam_year ?? now()->year) }}" required>
    </div>
    <div class="form-group">
        <label for="class_id">Class *</label>
        <select id="class_id" name="class_id" required>
            <option value="">Select class</option>
            @foreach($classes as $class)
                <option value="{{ $class->id }}" @selected((string) old('class_id', $exam->class_id ?? '') === (string) $class->id)>
                    {{ $class->class_name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="section_id">Section (Optional)</label>
        <select id="section_id" name="section_id">
            <option value="">All sections under class</option>
            @foreach($sections as $section)
                <option
                    value="{{ $section->id }}"
                    data-class-id="{{ $section->class_id }}"
                    @selected((string) old('section_id', $exam->section_id ?? '') === (string) $section->id)
                >
                    {{ $section->schoolClass?->class_name }} - {{ $section->section_name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group" style="margin-top: 12px;">
    <label for="notes">Notes</label>
    <textarea id="notes" name="notes" placeholder="Any instruction or note">{{ old('notes', $exam->notes ?? '') }}</textarea>
</div>

<div class="form-group" style="margin-top: 8px;">
    @php($isActive = old('is_active', (int) ($exam->is_active ?? true)))
    <label>
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" @checked((string) $isActive === '1')>
        Active exam
    </label>
</div>

<div class="card" style="margin-top: 14px; padding: 12px;">
    <div class="section-header">
        <h3 style="margin:0;">Exam Subjects</h3>
        <div class="inline-actions">
            <button class="btn" type="button" id="load-class-subjects">Load Class Subjects</button>
            <button class="btn" type="button" id="add-subject-row">Add Subject Row</button>
        </div>
    </div>
    <p style="margin-top:8px;color:#6b7280;">
        Select class first, then click "Load Class Subjects". You can customize pass mark, optional, GPA inclusion and total score inclusion per subject.
    </p>
    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>Subject Name</th>
                <th>Code</th>
                <th>Full Mark</th>
                <th>Pass Mark</th>
                <th>Optional</th>
                <th>GPA</th>
                <th>Total</th>
                <th>Order</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody id="exam-subject-rows"></tbody>
        </table>
    </div>
</div>

<div style="margin-top:14px;">
    <button class="btn" type="submit">{{ $isEdit ? 'Update' : 'Create' }} Exam</button>
    <a class="btn" style="background:#6b7280;" href="{{ route('admin.exams.index') }}">Cancel</a>
</div>

<script>
    (() => {
        const classSelect = document.getElementById('class_id');
        const sectionSelect = document.getElementById('section_id');
        const loadButton = document.getElementById('load-class-subjects');
        const addRowButton = document.getElementById('add-subject-row');
        const rowsContainer = document.getElementById('exam-subject-rows');

        if (!classSelect || !sectionSelect || !rowsContainer || !loadButton || !addRowButton) {
            return;
        }

        const subjectConfigsByClass = @json($subjectConfigsByClass);
        const initialRows = @json($subjectRows);
        let rowIndex = 0;

        const filterSections = () => {
            const classId = classSelect.value;
            for (const option of sectionSelect.options) {
                if (!option.value) {
                    option.hidden = false;
                    continue;
                }
                const visible = !classId || option.dataset.classId === classId;
                option.hidden = !visible;
                if (!visible && option.selected) {
                    sectionSelect.value = '';
                }
            }
        };

        const asBoolean = (value, defaultValue = false) => {
            if (value === true || value === 1 || value === '1' || value === 'true') {
                return true;
            }
            if (value === false || value === 0 || value === '0' || value === 'false') {
                return false;
            }

            return defaultValue;
        };

        const createCheckboxCell = (name, checked) => {
            const wrapper = document.createElement('div');
            wrapper.style.display = 'flex';
            wrapper.style.alignItems = 'center';
            wrapper.style.justifyContent = 'center';

            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = name;
            hidden.value = '0';

            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.name = name;
            checkbox.value = '1';
            checkbox.checked = !!checked;

            wrapper.appendChild(hidden);
            wrapper.appendChild(checkbox);
            return wrapper;
        };

        const appendRow = (data = {}) => {
            const index = rowIndex++;
            const row = document.createElement('tr');

            const configId = data.subject_config_id ?? '';
            const subjectName = data.subject_name ?? '';
            const subjectCode = data.subject_code ?? '';
            const fullMark = data.full_mark ?? 100;
            const passMark = data.pass_mark ?? 33;
            const sortOrder = data.sort_order ?? index + 1;
            const isOptional = asBoolean(data.is_optional, false);
            const includeInGpa = asBoolean(data.include_in_gpa, true);
            const includeInTotal = asBoolean(data.include_in_total_score, true);

            row.innerHTML = `
                <td>
                    <input type="hidden" name="subjects[${index}][subject_config_id]" value="${configId}">
                    <input type="text" name="subjects[${index}][subject_name]" value="${subjectName}" required>
                </td>
                <td><input type="text" name="subjects[${index}][subject_code]" value="${subjectCode}"></td>
                <td><input type="number" min="1" max="500" name="subjects[${index}][full_mark]" value="${fullMark}" required></td>
                <td><input type="number" min="0" max="500" name="subjects[${index}][pass_mark]" value="${passMark}" required></td>
                <td></td>
                <td></td>
                <td></td>
                <td><input type="number" min="0" name="subjects[${index}][sort_order]" value="${sortOrder}"></td>
                <td><button class="btn-link" type="button">Remove</button></td>
            `;

            row.children[4].appendChild(createCheckboxCell(`subjects[${index}][is_optional]`, isOptional));
            row.children[5].appendChild(createCheckboxCell(`subjects[${index}][include_in_gpa]`, includeInGpa));
            row.children[6].appendChild(createCheckboxCell(`subjects[${index}][include_in_total_score]`, includeInTotal));

            row.querySelector('button').addEventListener('click', () => row.remove());
            rowsContainer.appendChild(row);
        };

        const clearRows = () => {
            rowsContainer.innerHTML = '';
            rowIndex = 0;
        };

        const loadFromClass = () => {
            const classId = classSelect.value;
            const rows = subjectConfigsByClass[classId] ?? [];
            clearRows();

            if (!rows.length) {
                appendRow();
                return;
            }

            for (const row of rows) {
                appendRow(row);
            }
        };

        classSelect.addEventListener('change', filterSections);
        loadButton.addEventListener('click', loadFromClass);
        addRowButton.addEventListener('click', () => appendRow());
        filterSections();

        if (initialRows.length) {
            clearRows();
            for (const row of initialRows) {
                appendRow(row);
            }
        } else {
            appendRow();
        }
    })();
</script>
