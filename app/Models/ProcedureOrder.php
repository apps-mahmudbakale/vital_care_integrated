<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcedureOrder extends Model
{
    protected $fillable = [
        'check_in_id',
        'patient_id',
        'procedure_id',
        'icd_10_id',
        'notes',
        'user_id',
    ];

    public function checkIn()
    {
        return $this->belongsTo(CheckIn::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function procedure()
    {
        return $this->belongsTo(Procedure::class);
    }

    public function icd10()
    {
        return $this->belongsTo(ICD10::class, 'icd_10_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
