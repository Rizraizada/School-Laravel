<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubjectConfig extends Model
{
    use HasFactory;

    protected $table = 'subject_config';

    protected $fillable = [
        'class_id',
        'class_level',
        'group_name',
        'subject_code',
        'subject_name',
        'subject_type',
        'full_mark',
        'pass_mark',
        'subjective_mark',
        'mcq_mark',
        'practical_mark',
        'is_optional',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_optional' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}
