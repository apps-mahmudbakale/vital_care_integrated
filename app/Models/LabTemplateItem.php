<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabTemplateItem extends Model
{
    protected $fillable = ['lab_template_id', 'lab_parameter_id', 'reference', 'unit'];

  public function parameter()
  {
    return $this->belongsTo(LabParameter::class, 'lab_parameter_id');
  }

  public function template()
  {
    return $this->belongsTo(LabTemplate::class, 'lab_template_id');
  }
}
