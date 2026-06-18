<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fiallable = [
        'user_id',
        'education',
        'experience',
        'skills',
        'certifications',
        'languages',
        'hobbies',
        'references',
        'profile_picture',
        'resume',
        'linkedin',
        'github',
        'portfolio',
        'website',
        'bio',
        'address',
        'phone',
        'email',
        'social_media_links',
        'status'
    ];
}
