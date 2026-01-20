<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabResult extends Model
{
  use HasFactory;

  protected $casts = [
    'approved_at' => 'datetime',
  ];

  protected $fillable = [
    'patient_id',
    'lab_request_id',
    'lab_test_id',
    'lab_template_id',
    'user_id',
    'pathologist_comments',
    'image',
    'is_approved',
    'approved_by',
    'approved_at'
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function approvedBy()
  {
    return $this->belongsTo(User::class, 'approved_by');
  }

  public function labTest()
  {
    return $this->belongsTo(Laboratory::class, 'lab_test_id'); // assuming Laboratory model
  }

  public function template()
  {
    return $this->belongsTo(LabTemplate::class, 'lab_template_id');
  }

  public function patient()
  {
    return $this->belongsTo(Patient::class);
  }

  public function items()
  {
    return $this->hasMany(LabResultItem::class);
  }
}
