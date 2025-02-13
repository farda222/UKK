<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserData extends Model
{
    protected $fillable = ['user_id', 'name', 'phone', 'address', 'no_ktp'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
