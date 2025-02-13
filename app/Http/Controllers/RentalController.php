<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\Customer;
use App\Models\Car;
use Illuminate\Support\Facades\Auth;

class RentalController extends Controller
{
    public function processRental(Request $request)
    {
        // Validasi input
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'customer_name' => 'required',
            'car_name' => 'required',
            'price_per_day' => 'required|numeric',
            'fine' => 'required|numeric',
        ]);

        // Cari customer berdasarkan nama
        $customer = Customer::where('name', $request->customer_name)->first();
        if (!$customer) {
            return redirect()->back()->withErrors(['message' => 'Customer tidak ditemukan.']);
        }

        // Cari mobil berdasarkan nama
        $car = Car::where('name', $request->car_name)->first();
        if (!$car) {
            return redirect()->back()->withErrors(['message' => 'Mobil tidak ditemukan.']);
        }

        // Hitung total harga sewa
        $startDate = strtotime($request->start_date);
        $endDate = strtotime($request->end_date);
        $days = ($endDate - $startDate) / 86400; // Konversi ke hari
        $totalPrice = $days * $request->price_per_day;

        // Simpan data rental ke database
        Rental::create([
            'customer_id' => $customer->id,
            'car_id' => $car->id,
            'mulai_sewa' => $request->start_date,
            'selesai_sewa' => $request->end_date,
            'total_harga' => $totalPrice,
            'status' => 'pending', // Default status
        ]);

        // Bersihkan session rental
        session()->forget('rental');

        return redirect()->route('home')->with('success', 'Penyewaan berhasil dikonfirmasi!');
    }

    public function cancel(Rental $rental)
    {
        $rental->update(['status' => 'dibatalkan']);
        return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan');
    }
}
