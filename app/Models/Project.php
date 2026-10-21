<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'slug', 'client', 'project_type', 'summary', 'challenge', 'solution', 'cover_image', 'screenshots', 'features', 'outcomes', 'live_url', 'accent', 'owner_involvement', 'is_featured', 'is_published', 'portfolio_owner_id', 'portfolio_review_status', 'portfolio_reviewed_by', 'portfolio_reviewed_at'];

    protected function casts(): array
    {
        return [
            'screenshots' => 'array',
            'features' => 'array',
            'outcomes' => 'array',
            'owner_involvement' => 'boolean',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'portfolio_reviewed_at' => 'datetime',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function portfolioOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'portfolio_owner_id');
    }

    public function portfolioReviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'portfolio_reviewed_by');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function techStacks(): BelongsToMany
    {
        return $this->belongsToMany(TechStack::class);
    }
}
