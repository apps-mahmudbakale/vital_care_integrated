<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DrugGeneric extends Model
{
    protected $fillable = [
        'name',
        'category_id',
    ];

    public function drugs()
    {
        return $this->hasMany(Drug::class, 'generic_id');
    }

    public function category()
    {
        return $this->belongsTo(DrugCategory::class, 'category_id');
    }
}
