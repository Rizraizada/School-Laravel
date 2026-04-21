<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branch';

    protected $fillable = [
        'image_url',
        'branch_name',
        'branch_address',
        'branch_email',
        'branch_incharge',
        'branch_phone',
    ];
}
