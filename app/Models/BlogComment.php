<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogComment extends Model
{
    protected $fillable = ['blog_post_id', 'name', 'email', 'body', 'approved_at'];

    protected function casts(): array
    {
        return ['approved_at' => 'datetime'];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class, 'blog_post_id');
    }
}
