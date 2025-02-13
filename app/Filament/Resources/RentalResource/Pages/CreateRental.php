<?php

namespace App\Filament\Resources\RentalResource\Pages;

use App\Filament\Resources\RentalResource;
use Filament\Actions;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;

class CreateRental extends CreateRecord
{
    protected static string $resource = RentalResource::class;
    protected function handleRecordCreation(array $data): Model
    {
        $car = \App\Models\Car::find($data['car_id']);
        $diffDays = now()->parse($data['mulai_sewa'])->diffInDays(now()->parse($data['selesai_sewa'])) + 1;

        $data['total_harga'] = $car ? $car->price_per_day * $diffDays : 0;

        Log::info('Creating Record:', $data); // Log data untuk debugging

        return static::getModel()::create($data);
    }
}
