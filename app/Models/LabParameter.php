<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabParameter extends Model
{
  use HasFactory;

  protected $fillable = ['name'];

  public function templateItems()
  {
    return $this->hasMany(LabTemplateItem::class);
  }
}
