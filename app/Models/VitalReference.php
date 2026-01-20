<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VitalReference extends Model
{
    protected $fillable = [
        'name',
        'min_value',
        'max_value',
        'unit',
    ];
}
