<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VitalReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'vital_reference_id',
        'value',
        'user_id',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reference()
    {
        return $this->belongsTo(VitalReference::class, 'vital_reference_id');
    }
}
