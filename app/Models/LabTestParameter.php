<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabTestParameter extends Model
{
    use HasFactory;

    protected $fillable = [
        'lab_test_id',
        'name',
        'unit',
        'reference_range',
        'sort_order',
    ];

    public function test()
    {
        return $this->belongsTo(LabTest::class, 'lab_test_id');
    }
}
