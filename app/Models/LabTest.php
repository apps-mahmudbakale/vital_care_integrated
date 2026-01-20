<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'lab_category_id',
        'name',
        'price',
        'description',
        'template_id',
    ];

    public function template()
    {
        return $this->belongsTo(LabTemplate::class, 'template_id');
    }

    public function category()
    {
        return $this->belongsTo(LabCategory::class, 'lab_category_id');
    }

    public function parameters()
    {
        return $this->hasMany(LabTestParameter::class)->orderBy('sort_order', 'asc');
    }
}
