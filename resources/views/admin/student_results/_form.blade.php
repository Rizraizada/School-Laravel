@php
    $selectedExamId = old('exam_id', $record->exam_id);
    $existingMarks = collect(old('marks', []))
        ->keyBy(fn ($row) => (string) ($row['exam_subject_id'] ?? ''));
    if ($record->exists && $existingMarks->isEmpty()) {
        $existingMarks = $record->items->keyBy(fn ($item) => (string) $item->exam_subject_id)
            ->map(fn ($item) => [
                'exam_subject_id' => $item->exam_subject_id,
                'obtained_mark' => $item->obtained_mark,
            ]);
    }
@endphp

<div class="form-grid">
    <div class="form-group">
        <label for="exam_id">Exam *</label>
        <select id="exam_id" name="exam_id" required>
            <option value="">Select exam</option>
            @foreach($exams as $exam)
                <option
                    value="{{ $exam->id }}"
                    data-class-id="{{ $exam->class_id }}"
                    data-section-id="{{ $exam->section_id }}"
                    @selected((string) $selectedExamId === (string) $exam->id)
                >
                    {{ $exam->exam_name }} ({{ $exam->exam_year }}) - {{ $exam->schoolClass?->class_name }}
                    {{ $exam->section?->section_name ? '('.$exam->section->section_name.')' : '' }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="student_id">Student *</label>
        <select id="student_id" name="student_id" required>
            <option value="">Select student</option>
            @foreach($students as $student)
                <option
                    value="{{ $student->id }}"
                    data-class-id="{{ $student->section?->class_id }}"
                    data-section-id="{{ $student->section_id }}"
                    data-name="{{ $student->name }}"
                    data-roll="{{ $student->roll_no }}"
                    data-registration="{{ $student->registration_no }}"
                    data-father="{{ $student->father_name }}"
                    data-mother="{{ $student->mother_name }}"
                    @selected((string) old('student_id', $record->student_id) === (string) $student->id)
                >
                    {{ $student->name }} - {{ $student->section?->schoolClass?->class_name }}{{ $student->section?->section_name ? ' ('.$student->section->section_name.')' : '' }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="student_name">Student Name</label>
        <input id="student_name" type="text" name="student_name" value="{{ old('student_name', $record->student_name) }}" placeholder="Auto from selected student">
    </div>
    <div class="form-group">
        <label for="roll_no">Roll No</label>
        <input id="roll_no" type="text" name="roll_no" value="{{ old('roll_no', $record->roll_no) }}" placeholder="Auto from selected student">
    </div>
    <div class="form-group">
        <label for="registration_no">Registration No</label>
        <input id="registration_no" type="text" name="registration_no" value="{{ old('registration_no', $record->registration_no) }}" placeholder="Auto from selected student">
    </div>
    <div class="form-group">
        <label for="father_name">Father Name</label>
        <input id="father_name" type="text" name="father_name" value="{{ old('father_name', $record->father_name) }}" placeholder="Auto from selected student">
    </div>
    <div class="form-group">
        <label for="mother_name">Mother Name</label>
        <input id="mother_name" type="text" name="mother_name" value="{{ old('mother_name', $record->mother_name) }}" placeholder="Auto from selected student">
    </div>
    <div class="form-group">
        <label for="group_name">Group (optional)</label>
        <input id="group_name" type="text" name="group_name" value="{{ old('group_name', $record->group_name) }}">
    </div>
    <div class="form-group">
        <label for="merit_position">Merit Position</label>
        <input id="merit_position" type="number" min="1" name="merit_position" value="{{ old('merit_position', $record->merit_position) }}">
    </div>
</div>

<div class="card" style="margin-top:16px;">
    <h3 style="margin-top:0;">Subject-wise Marks</h3>
    <p style="margin-top:0;color:#6b7280;">Subjects are loaded from selected exam configuration. Pass mark, optional/GPA rules are already set in exam setup.</p>
    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>Subject</th>
                <th>Code</th>
                <th>Full Mark</th>
                <th>Pass Mark</th>
                <th>Optional</th>
                <th>GPA?</th>
                <th>Total?</th>
                <th>Obtained Mark</th>
            </tr>
            </thead>
            <tbody id="marks-table-body">
                <tr><td colspan="8">Select an exam to load subjects.</td></tr>
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top:14px;" class="inline-actions">
    <button class="btn" type="submit">{{ $isEdit ? 'Update' : 'Save' }} Result</button>
    @if($record->exists)
        <a class="btn" href="{{ route('admin.student-results.pdf', $record) }}" style="background:#0ea5e9;">Download PDF</a>
    @endif
    <a class="btn" style="background:#6b7280;" href="{{ route('admin.student-results.index') }}">Cancel</a>
</div>

<script>
    (() => {
        const examSelect = document.getElementById('exam_id');
        const studentSelect = document.getElementById('student_id');
        const marksTableBody = document.getElementById('marks-table-body');
        if (!examSelect || !studentSelect || !marksTableBody) {
            return;
        }

        const examSubjectsByExam = @json($examSubjectsByExam);
        const existingMarks = @json($existingMarks);

        const studentNameInput = document.getElementById('student_name');
        const rollInput = document.getElementById('roll_no');
        const registrationInput = document.getElementById('registration_no');
        const fatherInput = document.getElementById('father_name');
        const motherInput = document.getElementById('mother_name');

        const buildRows = () => {
            const examId = examSelect.value;
            const subjects = examSubjectsByExam[String(examId)] || [];
            if (!subjects.length) {
                marksTableBody.innerHTML = '<tr><td colspan="8">No subject found for selected exam.</td></tr>';
                return;
            }

            marksTableBody.innerHTML = subjects.map((subject, idx) => {
                const existing = existingMarks[String(subject.id)] || {};
                const existingMark = existing.obtained_mark ?? '';
                const toBool = (value, defaultValue = false) => {
                    if (value === true || value === 1 || value === '1' || value === 'true') {
                        return true;
                    }
                    if (value === false || value === 0 || value === '0' || value === 'false') {
                        return false;
                    }
                    return defaultValue;
                };
                const checkedOptional = toBool(subject.is_optional) ? 'Yes' : 'No';
                const checkedGpa = toBool(subject.include_in_gpa, true) ? 'Yes' : 'No';
                const checkedTotal = toBool(subject.include_in_total_score, true) ? 'Yes' : 'No';

                return `
                    <tr>
                        <td>
                            ${subject.subject_name}
                            <input type="hidden" name="marks[${idx}][exam_subject_id]" value="${subject.id}">
                        </td>
                        <td>${subject.subject_code || '-'}</td>
                        <td>${subject.full_mark}</td>
                        <td>${subject.pass_mark}</td>
                        <td>${checkedOptional}</td>
                        <td>${checkedGpa}</td>
                        <td>${checkedTotal}</td>
                        <td>
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                max="${subject.full_mark}"
                                name="marks[${idx}][obtained_mark]"
                                value="${existingMark}"
                                required
                            >
                        </td>
                    </tr>
                `;
            }).join('');
        };

        const syncStudentFields = () => {
            const option = studentSelect.options[studentSelect.selectedIndex];
            if (!option || !option.value) {
                return;
            }

            if (studentNameInput && !studentNameInput.value) {
                studentNameInput.value = option.dataset.name || '';
            }
            if (rollInput && !rollInput.value) {
                rollInput.value = option.dataset.roll || '';
            }
            if (registrationInput && !registrationInput.value) {
                registrationInput.value = option.dataset.registration || '';
            }
            if (fatherInput && !fatherInput.value) {
                fatherInput.value = option.dataset.father || '';
            }
            if (motherInput && !motherInput.value) {
                motherInput.value = option.dataset.mother || '';
            }
        };

        const filterStudentsByExam = () => {
            const examOption = examSelect.options[examSelect.selectedIndex];
            const examClassId = examOption?.dataset.classId || '';
            const examSectionId = examOption?.dataset.sectionId || '';

            for (const option of studentSelect.options) {
                if (!option.value) {
                    option.hidden = false;
                    continue;
                }

                const studentClassId = option.dataset.classId || '';
                const studentSectionId = option.dataset.sectionId || '';
                const classMatch = !examClassId || examClassId === studentClassId;
                const sectionMatch = !examSectionId || examSectionId === studentSectionId;
                option.hidden = !(classMatch && sectionMatch);
                if (option.hidden && option.selected) {
                    studentSelect.value = '';
                }
            }
        };

        examSelect.addEventListener('change', () => {
            filterStudentsByExam();
            buildRows();
        });
        studentSelect.addEventListener('change', syncStudentFields);

        filterStudentsByExam();
        buildRows();
        syncStudentFields();
    })();
</script>
