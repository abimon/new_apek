<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kamande extends Model
{
    protected $table = 'kamandes';
    protected $fillable = ['name', 'email', 'phone', 'message', 'status'];
    protected $hidden = ['created_at', 'updated_at'];
    // protected $casts = ['status' => 'boolean'];
}
