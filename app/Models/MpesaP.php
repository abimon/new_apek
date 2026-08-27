<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MpesaP extends Model
{
    protected $fillable = [
        'TransactionType',
        'trackingID',
        'TransAmount',
        'MpesaReceiptNumber',
        'TransactionDate',
        'PhoneNumber',
        'response',
    ];
}
