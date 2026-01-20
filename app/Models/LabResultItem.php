<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabResultItem extends Model
{
  use HasFactory;
  protected $fillable = ['lab_result_id', 'lab_template_item_id', 'value'];

  public function templateItem()
  {
    return $this->belongsTo(LabTemplateItem::class, 'lab_template_item_id');
  }

  public function result()
  {
    return $this->belongsTo(LabResult::class, 'lab_result_id');
  }

  public function getRemarkAttribute()
  {
    $value = floatval($this->value);
    $reference = $this->templateItem->reference;

    if (!$reference || !is_numeric($this->value)) {
        return null;
    }

    // Handle range: "min - max" or "min-max"
    if (preg_match('/^(\d+\.?\d*)\s*-\s*(\d+\.?\d*)$/', $reference, $matches)) {
        $min = floatval($matches[1]);
        $max = floatval($matches[2]);
        if ($value < $min) return 'Low';
        if ($value > $max) return 'High';
        return 'Normal';
    }

    // Handle less than: "< max" or "<max"
    if (preg_match('/^<\s*(\d+\.?\d*)$/', $reference, $matches)) {
        $max = floatval($matches[1]);
        return ($value < $max) ? 'Normal' : 'High';
    }

    // Handle greater than: "> min" or ">min"
    if (preg_match('/^>\s*(\d+\.?\d*)$/', $reference, $matches)) {
        $min = floatval($matches[1]);
        return ($value > $min) ? 'Normal' : 'Low';
    }

    return null;
  }
}
