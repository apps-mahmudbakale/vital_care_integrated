<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadiologyResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'radiology_request_id',
        'radiology_test_id',
        'user_id',
        'findings',
        'is_approved',
        'approved_by',
        'approved_at',
        'image'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function request()
    {
        return $this->belongsTo(RadiologyRequest::class, 'radiology_request_id');
    }

    public function test()
    {
        return $this->belongsTo(RadiologyTest::class, 'radiology_test_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
