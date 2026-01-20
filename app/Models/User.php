<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'phone',
        'password',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function FullName()
    {
        return $this->firstname . " " . $this->lastname;
    }

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    public function nok()
    {
        return $this->hasOne(NextOfKin::class, 'patient_id');
    }
    protected $appends = ['last_message', 'last_message_time'];

    public function getLastMessageAttribute()
    {
        $message = Message::where(function ($query) {
            $query->where('sender_id', $this->id)
                ->where('receiver_id', auth()->id());
        })->orWhere(function ($query) {
            $query->where('sender_id', auth()->id())
                ->where('receiver_id', $this->id);
        })->latest()->first();

        return $message ? $message->content : null;
    }

    public function getLastMessageTimeAttribute()
    {
        $message = Message::where(function ($query) {
            $query->where('sender_id', $this->id)
                ->where('receiver_id', auth()->id());
        })->orWhere(function ($query) {
            $query->where('sender_id', auth()->id())
                ->where('receiver_id', $this->id);
        })->latest()->first();

        return $message ? $message->created_at->diffForHumans() : null;
    }
}
