<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentResultItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_result_id',
        'exam_subject_id',
        'subject_id',
        'subject_name',
        'subject_code',
        'obtained_mark',
        'full_mark',
        'pass_mark',
        'is_optional',
        'include_in_gpa',
        'include_in_total_score',
        'gpa_point',
        'grade_letter',
        'is_passed',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'obtained_mark' => 'decimal:2',
            'gpa_point' => 'decimal:2',
            'is_optional' => 'boolean',
            'include_in_gpa' => 'boolean',
            'include_in_total_score' => 'boolean',
            'is_passed' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function studentResult(): BelongsTo
    {
        return $this->belongsTo(StudentResult::class);
    }

    public function examSubject(): BelongsTo
    {
        return $this->belongsTo(ExamSubject::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
