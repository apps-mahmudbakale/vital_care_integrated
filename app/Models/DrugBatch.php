<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DrugBatch extends Model
{
    protected $fillable = [
        'drug_id',
        'store_id',
        'batch_number',
        'quantity',
        'expiry_date',
    ];

    public function drug()
    {
        return $this->belongsTo(Drug::class);
    }

    public function store()
    {
        return $this->belongsTo(PharmacyStore::class, 'store_id');
    }
}
