<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'appointment_type_id',
        'clinic_id',
        'specialty_id',
        'start_at',
        'end_at',
        'is_all_day',
        'is_follow_up',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_all_day' => 'boolean',
        'is_follow_up' => 'boolean',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointmentType()
    {
        return $this->belongsTo(AppointmentType::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
