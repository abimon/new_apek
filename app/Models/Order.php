<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'items',
        'total',
        'payment_status',
        'order_code',
        'delivery_status'
    ];
}
