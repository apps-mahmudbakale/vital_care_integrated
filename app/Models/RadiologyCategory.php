<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadiologyCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function tests()
    {
        return $this->hasMany(RadiologyTest::class);
    }
}
