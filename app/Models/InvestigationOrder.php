<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestigationOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'check_in_id',
        'patient_id',
        'investigatable_id',
        'investigatable_type',
        'status',
        'notes',
        'user_id',
    ];

    public function investigatable()
    {
        return $this->morphTo();
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
