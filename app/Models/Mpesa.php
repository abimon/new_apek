<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mpesa extends Model
{
    protected $fillable = [
        'TransactionType',
        'order_code',
        'TransAmount',
        'MpesaReceiptNumber',
        'TransactionDate',
        'PhoneNumber',
        'response',
    ];
}
