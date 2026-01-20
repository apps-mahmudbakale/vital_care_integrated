<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadiologyTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'radiology_category_id',
        'name',
        'price',
        'description',
        'template',
    ];

    public function category()
    {
        return $this->belongsTo(RadiologyCategory::class, 'radiology_category_id');
    }
}
