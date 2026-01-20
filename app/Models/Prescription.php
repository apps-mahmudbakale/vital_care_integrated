<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'check_in_id',
        'patient_id',
        'drug_id',
        'dosage',
        'frequency',
        'duration',
        'notes',
        'status',
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

    public function drug()
    {
        return $this->belongsTo(Drug::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
