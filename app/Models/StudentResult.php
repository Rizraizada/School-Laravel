<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'class_id',
        'section_id',
        'recorded_by',
        'student_name',
        'roll_no',
        'registration_no',
        'father_name',
        'mother_name',
        'group_name',
        'class_level',
        'section_name',
        'exam_name',
        'exam_year',
        'bangla',
        'english',
        'mathematics',
        'science',
        'religion',
        'ict',
        'social_science',
        'agriculture',
        'higher_math',
        'biology',
        'chemistry',
        'physics',
        'total_marks',
        'gpa',
        'grade',
        'result_status',
        'merit_position',
        'raw_marks',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'exam_year' => 'integer',
            'bangla' => 'decimal:2',
            'english' => 'decimal:2',
            'mathematics' => 'decimal:2',
            'science' => 'decimal:2',
            'religion' => 'decimal:2',
            'ict' => 'decimal:2',
            'social_science' => 'decimal:2',
            'agriculture' => 'decimal:2',
            'higher_math' => 'decimal:2',
            'biology' => 'decimal:2',
            'chemistry' => 'decimal:2',
            'physics' => 'decimal:2',
            'total_marks' => 'decimal:2',
            'gpa' => 'decimal:2',
            'merit_position' => 'integer',
            'raw_marks' => 'array',
            'meta' => 'array',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
