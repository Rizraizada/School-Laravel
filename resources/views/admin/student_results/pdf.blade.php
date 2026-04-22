<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Result Sheet</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #111827;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 16px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 3px 0;
        }
        .info-table,
        .result-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .info-table td {
            border: 1px solid #d1d5db;
            padding: 6px 8px;
        }
        .result-table th,
        .result-table td {
            border: 1px solid #d1d5db;
            padding: 6px 8px;
            text-align: left;
        }
        .result-table th {
            background: #eef2ff;
        }
        .summary {
            margin-top: 10px;
            width: 100%;
            border-collapse: collapse;
        }
        .summary td {
            border: 1px solid #d1d5db;
            padding: 6px 8px;
            width: 25%;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }} - Student Result Sheet</h1>
        <p>{{ $record->exam_name }} - {{ $record->exam_year }}</p>
        <p>
            {{ $record->schoolClass?->class_name ?? $record->class_level }}
            {{ ($record->section?->section_name ?? $record->section_name) ? '('.($record->section?->section_name ?? $record->section_name).')' : '' }}
        </p>
    </div>

    <table class="info-table">
        <tr>
            <td><strong>Student Name:</strong> {{ $record->student_name }}</td>
            <td><strong>Roll:</strong> {{ $record->roll_no ?: '-' }}</td>
        </tr>
        <tr>
            <td><strong>Registration:</strong> {{ $record->registration_no ?: '-' }}</td>
            <td><strong>Result Status:</strong> {{ strtoupper($record->result_status ?? '-') }}</td>
        </tr>
        <tr>
            <td><strong>Father Name:</strong> {{ $record->father_name ?: '-' }}</td>
            <td><strong>Mother Name:</strong> {{ $record->mother_name ?: '-' }}</td>
        </tr>
    </table>

    <table class="result-table">
        <thead>
            <tr>
                <th>Subject</th>
                <th>Code</th>
                <th>Mark</th>
                <th>Full Mark</th>
                <th>Pass Mark</th>
                <th>Grade</th>
                <th>GPA Point</th>
                <th>Flags</th>
            </tr>
        </thead>
        <tbody>
            @forelse($record->items as $item)
                <tr>
                    <td>{{ $item->subject_name }}</td>
                    <td>{{ $item->subject_code ?: '-' }}</td>
                    <td>{{ $item->obtained_mark }}</td>
                    <td>{{ $item->full_mark }}</td>
                    <td>{{ $item->pass_mark }}</td>
                    <td>{{ $item->grade_letter ?: '-' }}</td>
                    <td>{{ $item->gpa_point }}</td>
                    <td>
                        {{ $item->is_optional ? 'Optional ' : '' }}
                        {{ $item->include_in_gpa ? 'GPA ' : '' }}
                        {{ $item->include_in_total_score ? 'Total' : '' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No subject marks found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="summary">
        <tr>
            <td><strong>Total Marks:</strong> {{ $record->total_marks ?? '-' }}</td>
            <td><strong>GPA:</strong> {{ $record->gpa ?? '-' }}</td>
            <td><strong>Grade:</strong> {{ $record->grade ?? '-' }}</td>
            <td><strong>Merit:</strong> {{ $record->merit_position ?? '-' }}</td>
        </tr>
    </table>
</body>
</html>
