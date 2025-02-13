<?php

namespace App\Http\Controllers;

use App\Models\Car; // Pastikan model Car sudah ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class CarController extends Controller
{
    public function index()
    {
        $cars = Car::all(); // Mengambil semua data mobil
        return view('product', compact('cars'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048', // Validasi file gambar
        ]);

        // Meng-upload file gambar ke storage/public dan mendapatkan path-nya
        $imagePath = $request->file('image')->store('public/cars'); // Simpan di storage/app/public/cars

        // Simpan path gambar di database, tanpa 'public/' agar bisa diakses dengan benar
        Car::create([
            'type' => $request->type,
            'brand' => $request->brand,
            'price_per_day' => $request->price_per_day,
            'car_images' => str_replace('public/', '', $imagePath), // Hapus 'public/' agar path-nya benar
        ]);
    }
    public function getProduct()
    {
        $cars = Car::all()->map(function ($car) {
            // Cek apakah image tersedia
            $car->image_url = Storage::exists('public/car_images/' . $car->image)
                ? Storage::url('car_images/' . $car->image)
                : null; // Jika gambar tidak ada, biarkan null
            return $car;
        });

        return view('product', compact('cars'));
    }
}
