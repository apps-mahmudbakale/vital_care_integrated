<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DrugRequest extends Model
{
    protected $fillable = [
        'patient_id',
        'drug_id',
        'quantity',
        'dosage',
        'instruction', // Keeping for backward compatibility if needed, or alias for notes
        'notes',
        'status',
        'user_id',
        'bill_ref',
        'check_in_id',
        'store_id',
        'frequency',
        'duration',
        'duration_unit',
        'generic_id',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    
    public function generic()
    {
        return $this->belongsTo(DrugGeneric::class, 'generic_id');
    }

    public function drug()
    {
        return $this->belongsTo(Drug::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(PharmacyStore::class, 'store_id');
    }

    public function checkIn()
    {
        return $this->belongsTo(CheckIn::class);
    }
}
