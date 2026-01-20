<?php


namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Wallet extends Model
{
  use HasFactory;

  protected $table = 'wallet';
  protected $fillable = ['patient_id','balance'];

  public function patient()
  {
    return $this->belongsTo(Patient::class);
  }

  public function transactions()
  {
    return $this->hasMany(WalletTransaction::class);
  }

}
