<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    use HasFactory;

    protected $fillable = [
        'procedure_category_id',
        'name',
        'price',
        'description',
        'template',
    ];

    public function category()
    {
        return $this->belongsTo(ProcedureCategory::class, 'procedure_category_id');
    }
}
