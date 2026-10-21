<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'name',
        'email',
        'role',
        'password',
        'is_active',
        'force_password_change',
        'portfolio_enabled',
        'portfolio_slug',
        'portfolio_is_public',
        'professional_title',
        'portfolio_summary',
        'portfolio_email',
        'portfolio_phone',
        'portfolio_location',
        'portfolio_website',
        'linkedin_url',
        'github_url',
        'professional_photo',
        'portfolio_skills',
        'portfolio_experience',
        'portfolio_education',
        'portfolio_certifications',
        'portfolio_languages',
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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'portfolio_enabled' => 'boolean',
            'portfolio_is_public' => 'boolean',
            'is_active' => 'boolean',
            'force_password_change' => 'boolean',
            'portfolio_skills' => 'array',
            'portfolio_experience' => 'array',
            'portfolio_education' => 'array',
            'portfolio_certifications' => 'array',
            'portfolio_languages' => 'array',
        ];
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function portfolioProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'portfolio_owner_id');
    }
}
