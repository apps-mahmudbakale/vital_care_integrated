<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'generic_id',
        'name',
        'price',
        'threshold',
        'weight',
        'is_active',
    ];

    public function category()
    {
        return $this->belongsTo(DrugCategory::class, 'category_id');
    }

    public function generic()
    {
        return $this->belongsTo(DrugGeneric::class, 'generic_id');
    }

    public function batches()
    {
        return $this->hasMany(DrugBatch::class);
    }
}
