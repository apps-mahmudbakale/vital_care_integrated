<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Diagnosis extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'check_in_id',
        'user_id',
        'icd_10_id',
        'notes',
        'status',
        'presenting_complain',
        'treatment_plan',
    ];

    public function icd10()
    {
        return $this->belongsTo(Icd10::class, 'icd_10_id');
    }

    public function checkIn()
    {
        return $this->belongsTo(CheckIn::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
