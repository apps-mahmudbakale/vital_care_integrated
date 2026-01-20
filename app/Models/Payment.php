<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'billing_id',
        'bill_ref',
        'cashpoint_id',
        'payment_method',
        'paying_amount',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function billing()
    {
        return $this->belongsTo(Billing::class);
    }
}
