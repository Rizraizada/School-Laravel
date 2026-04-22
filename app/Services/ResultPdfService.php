<?php

namespace App\Services;

use App\Models\StudentResult;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ResultPdfService
{
    /**
     * Generate PDF content using Python reportlab.
     */
    public function buildResultPdf(StudentResult $record): string
    {
        $payload = [
            'school_name' => config('app.name'),
            'student_name' => (string) $record->student_name,
            'roll_no' => (string) ($record->roll_no ?? '-'),
            'registration_no' => (string) ($record->registration_no ?? '-'),
            'father_name' => (string) ($record->father_name ?? '-'),
            'mother_name' => (string) ($record->mother_name ?? '-'),
            'exam_name' => (string) $record->exam_name,
            'exam_year' => (string) $record->exam_year,
            'class_name' => (string) ($record->schoolClass?->class_name ?? $record->class_level ?? '-'),
            'section_name' => (string) ($record->section?->section_name ?? $record->section_name ?? '-'),
            'result_status' => strtoupper((string) ($record->result_status ?? '-')),
            'total_marks' => (string) ($record->total_marks ?? '-'),
            'gpa' => (string) ($record->gpa ?? '-'),
            'grade' => (string) ($record->grade ?? '-'),
            'merit_position' => (string) ($record->merit_position ?? '-'),
            'items' => $record->items->map(fn ($item) => [
                'subject_name' => (string) $item->subject_name,
                'subject_code' => (string) ($item->subject_code ?? '-'),
                'obtained_mark' => (string) $item->obtained_mark,
                'full_mark' => (string) $item->full_mark,
                'pass_mark' => (string) $item->pass_mark,
                'grade_letter' => (string) ($item->grade_letter ?? '-'),
                'gpa_point' => (string) $item->gpa_point,
                'flags' => trim(implode(' ', array_filter([
                    $item->is_optional ? 'Optional' : null,
                    $item->include_in_gpa ? 'GPA' : null,
                    $item->include_in_total_score ? 'Total' : null,
                ]))),
            ])->values()->all(),
        ];

        $script = <<<'PY'
import io
import json
import sys

try:
    from reportlab.lib import colors
    from reportlab.lib.pagesizes import A4
    from reportlab.lib.styles import getSampleStyleSheet
    from reportlab.platypus import Paragraph, SimpleDocTemplate, Spacer, Table, TableStyle
except Exception:
    colors = None

payload = json.loads(sys.stdin.read())
if colors is not None:
    buffer = io.BytesIO()
    doc = SimpleDocTemplate(buffer, pagesize=A4, leftMargin=28, rightMargin=28, topMargin=28, bottomMargin=28)
    styles = getSampleStyleSheet()
    elements = []

    elements.append(Paragraph(f"<b>{payload['school_name']} - Student Result Sheet</b>", styles["Title"]))
    elements.append(Spacer(1, 6))
    elements.append(Paragraph(f"{payload['exam_name']} - {payload['exam_year']} | {payload['class_name']} ({payload['section_name']})", styles["Normal"]))
    elements.append(Spacer(1, 12))

    info_data = [
        [f"Student Name: {payload['student_name']}", f"Roll: {payload['roll_no']}"],
        [f"Registration: {payload['registration_no']}", f"Result Status: {payload['result_status']}"],
        [f"Father Name: {payload['father_name']}", f"Mother Name: {payload['mother_name']}"],
    ]
    info_table = Table(info_data, colWidths=[260, 260])
    info_table.setStyle(TableStyle([
        ("GRID", (0, 0), (-1, -1), 0.6, colors.grey),
        ("VALIGN", (0, 0), (-1, -1), "MIDDLE"),
        ("FONTSIZE", (0, 0), (-1, -1), 9),
    ]))
    elements.append(info_table)
    elements.append(Spacer(1, 12))

    rows = [["Subject", "Code", "Mark", "Full", "Pass", "Grade", "GPA", "Flags"]]
    for item in payload["items"]:
        rows.append([
            item["subject_name"],
            item["subject_code"] or "-",
            item["obtained_mark"],
            item["full_mark"],
            item["pass_mark"],
            item["grade_letter"] or "-",
            item["gpa_point"],
            item["flags"] or "-",
        ])

    marks_table = Table(rows, repeatRows=1, colWidths=[145, 48, 45, 45, 45, 45, 45, 100])
    marks_table.setStyle(TableStyle([
        ("GRID", (0, 0), (-1, -1), 0.6, colors.grey),
        ("BACKGROUND", (0, 0), (-1, 0), colors.HexColor("#eef2ff")),
        ("FONTNAME", (0, 0), (-1, 0), "Helvetica-Bold"),
        ("FONTSIZE", (0, 0), (-1, -1), 8.5),
        ("VALIGN", (0, 0), (-1, -1), "MIDDLE"),
    ]))
    elements.append(marks_table)
    elements.append(Spacer(1, 10))

    summary_data = [[
        f"Total Marks: {payload['total_marks']}",
        f"GPA: {payload['gpa']}",
        f"Grade: {payload['grade']}",
        f"Merit: {payload['merit_position']}",
    ]]
    summary_table = Table(summary_data, colWidths=[130, 130, 130, 130])
    summary_table.setStyle(TableStyle([
        ("GRID", (0, 0), (-1, -1), 0.6, colors.grey),
        ("FONTSIZE", (0, 0), (-1, -1), 9),
    ]))
    elements.append(summary_table)

    doc.build(elements)
    sys.stdout.buffer.write(buffer.getvalue())
    sys.exit(0)

# Fallback PDF generator without external dependencies.
def esc(text):
    return text.replace("\\", "\\\\").replace("(", "\\(").replace(")", "\\)")

lines = [
    f"{payload['school_name']} - Student Result Sheet",
    f"{payload['exam_name']} - {payload['exam_year']} | {payload['class_name']} ({payload['section_name']})",
    "",
    f"Student: {payload['student_name']} | Roll: {payload['roll_no']} | Reg: {payload['registration_no']}",
    f"Father: {payload['father_name']} | Mother: {payload['mother_name']}",
    f"Status: {payload['result_status']}",
    "",
    "Subjects:",
]
for item in payload["items"]:
    lines.append(
        f"- {item['subject_name']} [{item['subject_code'] or '-'}]: "
        f"{item['obtained_mark']}/{item['full_mark']} (Pass {item['pass_mark']}), "
        f"Grade {item['grade_letter'] or '-'}, GPA {item['gpa_point']}, {item['flags'] or '-'}"
    )
lines.extend([
    "",
    f"Total Marks: {payload['total_marks']}",
    f"GPA: {payload['gpa']}",
    f"Grade: {payload['grade']}",
    f"Merit: {payload['merit_position']}",
])

font_size = 10
leading = 14
x = 40
y_start = 810
stream_lines = ["BT", "/F1 10 Tf"]
y = y_start
for line in lines[:48]:
    stream_lines.append(f"1 0 0 1 {x} {y} Tm ({esc(line)}) Tj")
    y -= leading
stream_lines.append("ET")
stream = "\n".join(stream_lines).encode("latin-1", errors="replace")

objs = []
objs.append(b"1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj\n")
objs.append(b"2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj\n")
objs.append(b"3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >> endobj\n")
objs.append(b"4 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj\n")
objs.append(f"5 0 obj << /Length {len(stream)} >> stream\n".encode("ascii") + stream + b"\nendstream endobj\n")

pdf = io.BytesIO()
pdf.write(b"%PDF-1.4\n")
offsets = [0]
for obj in objs:
    offsets.append(pdf.tell())
    pdf.write(obj)

xref_pos = pdf.tell()
pdf.write(f"xref\n0 {len(objs)+1}\n".encode("ascii"))
pdf.write(b"0000000000 65535 f \n")
for off in offsets[1:]:
    pdf.write(f"{off:010d} 00000 n \n".encode("ascii"))
pdf.write(
    (
        "trailer << /Size {size} /Root 1 0 R >>\nstartxref\n{xref}\n%%EOF"
        .format(size=len(objs)+1, xref=xref_pos)
    ).encode("ascii")
)
sys.stdout.buffer.write(pdf.getvalue())
PY;

        $process = new Process(['python3', '-c', $script]);
        $process->setInput(json_encode($payload, JSON_UNESCAPED_UNICODE));
        $process->setTimeout(30);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $output = $process->getOutput();
        if ($output === '') {
            throw new \RuntimeException('Failed to generate PDF content.');
        }

        return $output;
    }
}
