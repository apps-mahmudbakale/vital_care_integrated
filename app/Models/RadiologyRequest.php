<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RadiologyRequest extends Model
{
    protected $fillable = [
        'patient_id',
        'radiology_test_id',
        'user_id',
        'request_note',
        'request_ref',
        'priority',
        'status',
        'findings',
    ];

    public function isPaid()
    {
        $billing = Billing::where('service_id', $this->radiology_test_id)
            ->where('bill_ref', $this->request_ref)
            ->where('service', 'LIKE', 'radiology:%')
            ->first();

        return $billing && $billing->status == 1;
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function test()
    {
        return $this->belongsTo(RadiologyTest::class, 'radiology_test_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function result()
    {
        return $this->hasOne(RadiologyResult::class, 'radiology_request_id');
    }
}
