<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultingRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'name',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
