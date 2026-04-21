<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuickAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'attendance_date',
        'male_count',
        'female_count',
        'total_male',
        'total_female',
        'total_students',
        'absent_student_ids',
        'recorded_by',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'absent_student_ids' => 'array',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
