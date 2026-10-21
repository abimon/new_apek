<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsLog extends Model
{
    protected $fillable = ['path', 'session_hash', 'event', 'project_id', 'referrer', 'visited_at'];
}