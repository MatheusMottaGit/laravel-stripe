<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'status',
        'payment_intent_id',
        'currency',
        'amount',
        'customer_email',
    ];
    
}
