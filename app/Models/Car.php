<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'name',
        'brand',
        'type',
        'license_plate',
        'status',
        'stock',
        'price_per_day',
        'penalty',
        'description',
    ];

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
    public function type()
    {
        return $this->belongsTo(Type::class);
    }
    public function decreaseStock($amount = 1)
    {
        $this->stock = max(0, $this->stock - $amount);
        $this->save();
    }

    public function increaseStock($amount = 1)
    {
        $this->stock += $amount;
        $this->save();
    }
}
