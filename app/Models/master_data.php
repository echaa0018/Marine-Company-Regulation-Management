<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class master_data extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_data';

    protected $fillable = [
        'type',
        'code',
        'name',
        'description',
        'parent_code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}