<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'address',
        'no_ktp',
        'phone',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
