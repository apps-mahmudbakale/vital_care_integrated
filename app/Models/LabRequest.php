<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabRequest extends Model
{
    protected $fillable = [
        'patient_id',
        'test_id',
        'user_id',
        'request_note',
        'request_ref',
        'priority',
        'status',
        'findings',
        'specimen_received_at',
    ];

    protected $casts = [
        'specimen_received_at' => 'datetime',
    ];

    public function isSpecimenReceived()
    {
        return $this->specimen_received_at !== null;
    }

    public function isPaid()
    {
        $billing = Billing::where('service_id', $this->test_id)
            ->where('bill_ref', $this->request_ref)
            ->where('service', 'LIKE', 'laboratory:%')
            ->first();

        return $billing && $billing->status == 1;
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function test()
    {
        return $this->belongsTo(LabTest::class, 'test_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function result()
    {
        return $this->hasOne(LabResult::class);
    }
}
