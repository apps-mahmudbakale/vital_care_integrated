<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $table = 'wallet_transaction';

    protected $fillable = [
        'wallet_id',
        'transaction_type',
        'transaction_amount',
        'transaction_id',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
