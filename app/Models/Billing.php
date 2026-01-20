<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    //
    protected $fillable = [
        'service',
        'service_id',
        'user_id',
        'quantity',
        'amount',
        'payer_id',
        'status',
        'bill_ref',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'user_id');
    }

    public function payer()
    {
        return $this->belongsTo(Patient::class, 'payer_id');
    }
}
