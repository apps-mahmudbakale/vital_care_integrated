<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Tonysm\RichTextLaravel\Models\Traits\HasRichText;

class CheckIn extends Model
{
  use HasFactory;
  use HasRichText;

  protected $richTextAttributes = [
    'presenting_complaint',
    'treatment_plan',
  ];

  protected $fillable = [
    'patient_id',
    'check_in_date',
    'clinic_id',
    'appointment_type_id',
    'specialty_id',
    'appointment_id',
  ];

  public function patient()
  {
      return $this->belongsTo(Patient::class);
  }

  public function diagnoses()
  {
      return $this->hasMany(Diagnosis::class);
  }

  public function prescriptions()
  {
      return $this->hasMany(Prescription::class);
  }

  public function drugRequests()
  {
      return $this->hasMany(DrugRequest::class);
  }

  public function investigations()
  {
      return $this->hasMany(InvestigationOrder::class);
  }

  public function vitals()
  {
      return $this->hasMany(VitalReading::class, 'patient_id', 'patient_id')
                 ->where('created_at', '>=', $this->created_at);
  }

  public function appointment()
  {
      return $this->belongsTo(Appointment::class);
  }

  public function clinic()
  {
      return $this->belongsTo(Clinic::class);
  }

  public function appointmentType()
  {
      return $this->belongsTo(AppointmentType::class);
  }

  public function specialty()
  {
      return $this->belongsTo(Specialty::class);
  }
}
