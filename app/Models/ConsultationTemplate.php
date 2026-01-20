<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultationTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'specialty_id',
        'name',
        'content',
    ];

    protected $casts = [
        // 'content' => 'json',
    ];

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }
}
