<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamSubject extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'subject_id',
        'subject_config_id',
        'subject_name',
        'subject_code',
        'full_mark',
        'pass_mark',
        'is_optional',
        'include_in_gpa',
        'include_in_total_score',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'full_mark' => 'integer',
            'pass_mark' => 'integer',
            'is_optional' => 'boolean',
            'include_in_gpa' => 'boolean',
            'include_in_total_score' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function subjectConfig(): BelongsTo
    {
        return $this->belongsTo(SubjectConfig::class);
    }

    public function resultItems(): HasMany
    {
        return $this->hasMany(StudentResultItem::class);
    }
}
