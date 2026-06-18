<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'technologies',
        'category',
        'image',
        'github_url',
        'live_url',
        'featured',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'technologies' => 'array',
    ];
}
