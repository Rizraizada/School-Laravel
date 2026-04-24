@extends('layouts.dashboard')

@section('title', 'Create Student Results')

@section('content')
    <div class="card">
        <h2 style="margin-top:0;">Create Student Results (Section-wise)</h2>
        <p style="margin-top:-6px;color:#6b7280;">
            Select exam and section to load all students together. Expand each student row to enter subject marks and save in bulk.
        </p>

        <form method="POST" action="{{ route('admin.student-results.store') }}" id="bulk-result-form">
            @csrf
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
                                @selected((string) old('exam_id') === (string) $exam->id)
                            >
                                {{ $exam->exam_name }} ({{ $exam->exam_year }}) - {{ $exam->schoolClass?->class_name }}
                                {{ $exam->section?->section_name ? '('.$exam->section->section_name.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="section_id">Section</label>
                    <select id="section_id" name="section_id">
                        <option value="">All sections under exam class</option>
                        @foreach($sections as $section)
                            <option
                                value="{{ $section->id }}"
                                data-class-id="{{ $section->class_id }}"
                                @selected((string) old('section_id') === (string) $section->id)
                            >
                                {{ $section->schoolClass?->class_name }} - {{ $section->section_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button class="btn" type="button" id="load-students-btn">Load Students</button>
                </div>
            </div>

            <div class="card" style="margin-top:16px;">
                <h3 style="margin-top:0;">Students</h3>
                <p style="margin-top:0;color:#6b7280;">
                    Click Expand to enter subject marks for a student. Marks are out of the full mark shown for each subject.
                </p>
                <div class="table-wrap">
                    <table>
                        <thead>
                        <tr>
                            <th style="width:100px;">Action</th>
                            <th>Student</th>
                            <th>Roll</th>
                            <th>Registration</th>
                            <th>Class</th>
                            <th>Section</th>
                        </tr>
                        </thead>
                        <tbody id="student-rows">
                            <tr>
                                <td colspan="6">Select exam and click "Load Students".</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div style="margin-top:14px;" class="inline-actions">
                <button class="btn" type="submit">Save All Results</button>
                <a class="btn" style="background:#6b7280;" href="{{ route('admin.student-results.index') }}">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        (() => {
            const examSelect = document.getElementById('exam_id');
            const sectionSelect = document.getElementById('section_id');
            const loadStudentsBtn = document.getElementById('load-students-btn');
            const studentRows = document.getElementById('student-rows');
            const bulkResultForm = document.getElementById('bulk-result-form');
            if (!examSelect || !sectionSelect || !loadStudentsBtn || !studentRows || !bulkResultForm) {
                return;
            }

            const students = @json($studentMeta);
            const examSubjectsByExam = @json($examSubjectsByExam);
            const oldResults = @json(old('results', []));
            let rowIndex = 0;

            const getSelectedExam = () => examSelect.options[examSelect.selectedIndex] || null;

            const filterSectionOptions = () => {
                const examOption = getSelectedExam();
                const examClassId = examOption?.dataset.classId || '';
                const examSectionId = examOption?.dataset.sectionId || '';

                for (const option of sectionSelect.options) {
                    if (!option.value) {
                        option.hidden = false;
                        continue;
                    }

                    const classMatch = !examClassId || option.dataset.classId === examClassId;
                    const sectionMatch = !examSectionId || option.value === examSectionId;
                    option.hidden = !(classMatch && sectionMatch);
                    if (option.hidden && option.selected) {
                        sectionSelect.value = '';
                    }
                }

                if (examSectionId) {
                    sectionSelect.value = examSectionId;
                    sectionSelect.disabled = true;
                } else {
                    sectionSelect.disabled = false;
                }
            };

            const normalizeMarks = (marks) => {
                if (!Array.isArray(marks)) {
                    return {};
                }

                const map = {};
                for (const row of marks) {
                    const subjectId = String(row?.exam_subject_id ?? '');
                    if (!subjectId) {
                        continue;
                    }
                    map[subjectId] = row?.obtained_mark ?? '';
                }
                return map;
            };

            const buildMarksEditor = (subjects, studentIndex, savedRows = []) => {
                const marksMap = normalizeMarks(savedRows);
                const wrapper = document.createElement('div');
                wrapper.className = 'card';
                wrapper.style.margin = '8px 0';
                wrapper.style.padding = '10px';
                wrapper.innerHTML = `
                    <div class="table-wrap">
                        <table>
                            <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Code</th>
                                <th>Full</th>
                                <th>Pass</th>
                                <th>Optional</th>
                                <th>Mark</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                `;

                const tbody = wrapper.querySelector('tbody');
                tbody.innerHTML = subjects.map((subject, markIndex) => {
                    const savedMark = marksMap[String(subject.id)] ?? '';
                    return `
                        <tr>
                            <td>
                                ${subject.subject_name}
                                <input type="hidden" name="results[${studentIndex}][marks][${markIndex}][exam_subject_id]" value="${subject.id}">
                            </td>
                            <td>${subject.subject_code || '-'}</td>
                            <td>${subject.full_mark}</td>
                            <td>${subject.pass_mark}</td>
                            <td>${subject.is_optional ? 'Yes' : 'No'}</td>
                            <td>
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    max="${subject.full_mark}"
                                    name="results[${studentIndex}][marks][${markIndex}][obtained_mark]"
                                    value="${savedMark}"
                                >
                            </td>
                        </tr>
                    `;
                }).join('');

                return wrapper;
            };

            const renderStudentRows = (rows, preserved = []) => {
                if (!rows.length) {
                    studentRows.innerHTML = '<tr><td colspan="6">No students found for selected exam/section.</td></tr>';
                    return;
                }

                const examId = examSelect.value;
                const subjects = examSubjectsByExam[String(examId)] || [];
                if (!subjects.length) {
                    studentRows.innerHTML = '<tr><td colspan="6">Selected exam has no configured subjects.</td></tr>';
                    return;
                }

                const preservedByStudent = {};
                if (Array.isArray(preserved)) {
                    for (const row of preserved) {
                        const sid = String(row?.student_id ?? '');
                        if (sid) {
                            preservedByStudent[sid] = row;
                        }
                    }
                }

                studentRows.innerHTML = '';
                rowIndex = 0;

                for (const student of rows) {
                    const currentIndex = rowIndex++;
                    const savedRow = preservedByStudent[String(student.id)] || {};
                    const collapsedRow = document.createElement('tr');
                    collapsedRow.innerHTML = `
                        <td>
                            <button class="btn-link" type="button" data-expand-row="1">Expand</button>
                        </td>
                        <td>
                            ${student.name}
                            <input type="hidden" name="results[${currentIndex}][student_id]" value="${student.id}">
                        </td>
                        <td>${student.roll_no || '-'}</td>
                        <td>${student.registration_no || '-'}</td>
                        <td>${student.class_name || '-'}</td>
                        <td>${student.section_name || '-'}</td>
                    `;

                    const expandedRow = document.createElement('tr');
                    expandedRow.style.display = 'none';
                    const expandedCell = document.createElement('td');
                    expandedCell.colSpan = 6;
                    expandedCell.appendChild(buildMarksEditor(subjects, currentIndex, savedRow.marks || []));
                    expandedRow.appendChild(expandedCell);

                    collapsedRow.querySelector('[data-expand-row="1"]').addEventListener('click', (event) => {
                        const button = event.currentTarget;
                        const isVisible = expandedRow.style.display !== 'none';
                        expandedRow.style.display = isVisible ? 'none' : '';
                        button.textContent = isVisible ? 'Expand' : 'Collapse';
                    });

                    studentRows.appendChild(collapsedRow);
                    studentRows.appendChild(expandedRow);
                }
            };

            const matchingStudents = () => {
                const examOption = getSelectedExam();
                const examClassId = examOption?.dataset.classId || '';
                const examSectionId = examOption?.dataset.sectionId || '';
                const selectedSectionId = sectionSelect.value;

                return students.filter((student) => {
                    const classMatch = !examClassId || String(student.class_id || '') === String(examClassId);
                    const examSectionMatch = !examSectionId || String(student.section_id || '') === String(examSectionId);
                    const sectionMatch = !selectedSectionId || String(student.section_id || '') === String(selectedSectionId);
                    return classMatch && examSectionMatch && sectionMatch;
                });
            };

            const loadStudents = (preservedRows = []) => {
                const examId = examSelect.value;
                if (!examId) {
                    studentRows.innerHTML = '<tr><td colspan="6">Select an exam first.</td></tr>';
                    return;
                }
                renderStudentRows(matchingStudents(), preservedRows);
            };

            examSelect.addEventListener('change', () => {
                filterSectionOptions();
                studentRows.innerHTML = '<tr><td colspan="6">Click "Load Students" to refresh the list.</td></tr>';
            });
            sectionSelect.addEventListener('change', () => {
                studentRows.innerHTML = '<tr><td colspan="6">Click "Load Students" to refresh the list.</td></tr>';
            });
            loadStudentsBtn.addEventListener('click', () => loadStudents());

            filterSectionOptions();
            if (oldResults.length) {
                loadStudents(oldResults);
            }
        })();
    </script>
@endsection
