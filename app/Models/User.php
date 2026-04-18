<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'full_name',
        'email',
        'password',
        'role',
        'phone',
        'gender',
        'expertise',
        'address',
        'position',
        'description',
        'image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function assignedSections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'teacher_sections')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(Attendance::class, 'recorded_by');
    }

    public function quickAttendanceRecords(): HasMany
    {
        return $this->hasMany(QuickAttendance::class, 'recorded_by');
    }

    public function isAdminLike(): bool
    {
        return in_array($this->role, ['admin', 'headmaster'], true);
    }
}
