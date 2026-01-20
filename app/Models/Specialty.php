<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'follow_up_price',
    ];

    public function templates()
    {
        return $this->hasMany(ConsultationTemplate::class);
    }
}
