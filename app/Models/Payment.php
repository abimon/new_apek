<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable =[
        'account',
        'method',
        'amount',
        'phone',
        'uid',
        'status',
        'trackingID'
    ];
}
