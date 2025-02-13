<?php

namespace App\Http\Controllers;

use App\Models\Car;

class ProductController extends Controller
{
    public function checkout($id)
    {
        $car = Car::findOrFail($id); // Cari data mobil berdasarkan ID
        return view('checkoutproduct', compact('car')); // Kirim data ke view
    }

    public function show(Car $car)
    {
        return view('detail', compact('car')); // Sesuaikan dengan nama file view Anda
    }
}
