@extends('layouts.dashboard')

@section('title', 'Student Promotion')

@section('content')
    <div class="card">
        <div class="section-header" style="margin-bottom: 12px;">
            <h2 style="margin: 0;">Student Promotion</h2>
            <a class="btn" href="{{ route('admin.students.index') }}">Back to Students</a>
        </div>

        <p style="margin-top: 0; color: #6b7280;">
            Move all students from one class/section to another (example: Class 6 to Class 7).
        </p>

        <form action="{{ route('admin.students.promote') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label for="from_class_id">From Class *</label>
                    <select id="from_class_id" name="from_class_id" required>
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" @selected((string) old('from_class_id') === (string) $class->id)>
                                {{ $class->class_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="from_section_id">From Section *</label>
                    <select id="from_section_id" name="from_section_id" required>
                        <option value="">Select Section</option>
                        @foreach($sections as $section)
                            <option
                                value="{{ $section->id }}"
                                data-class-id="{{ $section->class_id }}"
                                @selected((int) old('from_section_id', 0) === $section->id)
                            >
                                {{ $section->schoolClass?->class_name }} - {{ $section->section_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-grid" style="margin-top: 12px;">
                <div class="form-group">
                    <label for="to_class_id">To Class *</label>
                    <select id="to_class_id" name="to_class_id" required>
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" @selected((string) old('to_class_id') === (string) $class->id)>
                                {{ $class->class_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="to_section_id">To Section *</label>
                    <select id="to_section_id" name="to_section_id" required>
                        <option value="">Select Section</option>
                        @foreach($sections as $section)
                            <option
                                value="{{ $section->id }}"
                                data-class-id="{{ $section->class_id }}"
                                @selected((int) old('to_section_id', 0) === $section->id)
                            >
                                {{ $section->schoolClass?->class_name }} - {{ $section->section_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-top: 14px;">
                <button
                    class="btn"
                    type="submit"
                    onclick="return confirm('Promote students from selected section to target section?')"
                >
                    Promote Students
                </button>
            </div>
        </form>
    </div>

    <script>
        (() => {
            const bindSectionFilter = (classFieldId, sectionFieldId) => {
                const classSelect = document.getElementById(classFieldId);
                const sectionSelect = document.getElementById(sectionFieldId);
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
            };

            bindSectionFilter('from_class_id', 'from_section_id');
            bindSectionFilter('to_class_id', 'to_section_id');
        })();
    </script>
@endsection
