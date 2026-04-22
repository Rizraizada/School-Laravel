<div class="form-grid">
    <div class="form-group">
        <label for="student_id">Student</label>
        <select id="student_id" name="student_id">
            <option value="">Select student (optional)</option>
            @foreach($students as $student)
                <option value="{{ $student->id }}" @selected((string) old('student_id', $record->student_id) === (string) $student->id)>
                    {{ $student->name }} - {{ $student->section?->schoolClass?->class_name }} {{ $student->section?->section_name ? '('.$student->section->section_name.')' : '' }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="student_name">Student Name *</label>
        <input id="student_name" type="text" name="student_name" value="{{ old('student_name', $record->student_name) }}" required>
    </div>
    <div class="form-group">
        <label for="roll_no">Roll No</label>
        <input id="roll_no" type="text" name="roll_no" value="{{ old('roll_no', $record->roll_no) }}">
    </div>
    <div class="form-group">
        <label for="registration_no">Registration No</label>
        <input id="registration_no" type="text" name="registration_no" value="{{ old('registration_no', $record->registration_no) }}">
    </div>
    <div class="form-group">
        <label for="class_id">Class</label>
        <select id="class_id" name="class_id">
            <option value="">Select class</option>
            @foreach($classes as $class)
                <option value="{{ $class->id }}" @selected((string) old('class_id', $record->class_id) === (string) $class->id)>
                    {{ $class->class_name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="section_id">Section</label>
        <select id="section_id" name="section_id">
            <option value="">Select section</option>
            @foreach($sections as $section)
                <option value="{{ $section->id }}" @selected((string) old('section_id', $record->section_id) === (string) $section->id)>
                    {{ $section->schoolClass?->class_name }} - {{ $section->section_name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="exam_name">Exam Name *</label>
        <input id="exam_name" type="text" name="exam_name" value="{{ old('exam_name', $record->exam_name ?: 'Final') }}" required>
    </div>
    <div class="form-group">
        <label for="exam_year">Exam Year *</label>
        <input id="exam_year" type="number" name="exam_year" min="2000" max="2100" value="{{ old('exam_year', $record->exam_year ?: now()->year) }}" required>
    </div>
    <div class="form-group">
        <label for="father_name">Father Name</label>
        <input id="father_name" type="text" name="father_name" value="{{ old('father_name', $record->father_name) }}">
    </div>
    <div class="form-group">
        <label for="mother_name">Mother Name</label>
        <input id="mother_name" type="text" name="mother_name" value="{{ old('mother_name', $record->mother_name) }}">
    </div>
    <div class="form-group">
        <label for="group_name">Group</label>
        <input id="group_name" type="text" name="group_name" value="{{ old('group_name', $record->group_name) }}">
    </div>
    <div class="form-group">
        <label for="class_level">Class Level</label>
        <input id="class_level" type="text" name="class_level" value="{{ old('class_level', $record->class_level) }}">
    </div>
    <div class="form-group">
        <label for="section_name">Section Name (Text)</label>
        <input id="section_name" type="text" name="section_name" value="{{ old('section_name', $record->section_name) }}">
    </div>
    <div class="form-group">
        <label for="bangla">Bangla</label>
        <input id="bangla" type="number" step="0.01" min="0" max="100" name="bangla" value="{{ old('bangla', $record->bangla) }}">
    </div>
    <div class="form-group">
        <label for="english">English</label>
        <input id="english" type="number" step="0.01" min="0" max="100" name="english" value="{{ old('english', $record->english) }}">
    </div>
    <div class="form-group">
        <label for="mathematics">Mathematics</label>
        <input id="mathematics" type="number" step="0.01" min="0" max="100" name="mathematics" value="{{ old('mathematics', $record->mathematics) }}">
    </div>
    <div class="form-group">
        <label for="science">Science</label>
        <input id="science" type="number" step="0.01" min="0" max="100" name="science" value="{{ old('science', $record->science) }}">
    </div>
    <div class="form-group">
        <label for="religion">Religion</label>
        <input id="religion" type="number" step="0.01" min="0" max="100" name="religion" value="{{ old('religion', $record->religion) }}">
    </div>
    <div class="form-group">
        <label for="ict">ICT</label>
        <input id="ict" type="number" step="0.01" min="0" max="100" name="ict" value="{{ old('ict', $record->ict) }}">
    </div>
    <div class="form-group">
        <label for="social_science">Social Science</label>
        <input id="social_science" type="number" step="0.01" min="0" max="100" name="social_science" value="{{ old('social_science', $record->social_science) }}">
    </div>
    <div class="form-group">
        <label for="agriculture">Agriculture</label>
        <input id="agriculture" type="number" step="0.01" min="0" max="100" name="agriculture" value="{{ old('agriculture', $record->agriculture) }}">
    </div>
    <div class="form-group">
        <label for="higher_math">Higher Math</label>
        <input id="higher_math" type="number" step="0.01" min="0" max="100" name="higher_math" value="{{ old('higher_math', $record->higher_math) }}">
    </div>
    <div class="form-group">
        <label for="biology">Biology</label>
        <input id="biology" type="number" step="0.01" min="0" max="100" name="biology" value="{{ old('biology', $record->biology) }}">
    </div>
    <div class="form-group">
        <label for="chemistry">Chemistry</label>
        <input id="chemistry" type="number" step="0.01" min="0" max="100" name="chemistry" value="{{ old('chemistry', $record->chemistry) }}">
    </div>
    <div class="form-group">
        <label for="physics">Physics</label>
        <input id="physics" type="number" step="0.01" min="0" max="100" name="physics" value="{{ old('physics', $record->physics) }}">
    </div>
    <div class="form-group">
        <label for="total_marks">Total Marks</label>
        <input id="total_marks" type="number" step="0.01" min="0" name="total_marks" value="{{ old('total_marks', $record->total_marks) }}">
    </div>
    <div class="form-group">
        <label for="gpa">GPA</label>
        <input id="gpa" type="number" step="0.01" min="0" max="5" name="gpa" value="{{ old('gpa', $record->gpa) }}">
    </div>
    <div class="form-group">
        <label for="grade">Grade</label>
        <input id="grade" type="text" name="grade" value="{{ old('grade', $record->grade) }}">
    </div>
    <div class="form-group">
        <label for="result_status">Result Status</label>
        <input id="result_status" type="text" name="result_status" value="{{ old('result_status', $record->result_status) }}">
    </div>
    <div class="form-group">
        <label for="merit_position">Merit Position</label>
        <input id="merit_position" type="number" min="1" name="merit_position" value="{{ old('merit_position', $record->merit_position) }}">
    </div>
</div>

<div style="margin-top:14px;">
    <button class="btn" type="submit">{{ $isEdit ? 'Update' : 'Save' }} Result</button>
    <a class="btn" style="background:#6b7280;" href="{{ route('admin.student-results.index') }}">Cancel</a>
</div>
