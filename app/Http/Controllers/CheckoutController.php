<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Car;
use App\Models\UserData;

class CheckoutController extends Controller
{
    public function store(Request $request, $carId)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->withErrors(['message' => 'Silakan login terlebih dahulu.']);
        }

        // Dapatkan data mobil
        $car = Car::findOrFail($carId);

        if ($car->stock < 1) {
            return back()->with('error', 'Stok mobil tidak tersedia');
        }

        // Kurangi stok dan ubah status jika stok habis
        $car->update([
            'stock' => $car->stock - 1,
            'status' => ($car->stock - 1) < 1 ? 'rented' : 'available'
        ]);

        // Periksa apakah user sudah memiliki data diri
        $userData = UserData::where('user_id', $user->id)->first();

        // Jika user sudah memiliki data diri
        if ($userData) {
            // Cek apakah data diri yang dimasukkan sama dengan yang ada
            if (
                $userData->name === $request->input('name') &&
                $userData->phone === $request->input('phone') &&
                $userData->address === $request->input('address') &&
                $userData->no_ktp === $request->input('no_ktp')
            ) {
                // Data diri sudah terdaftar dengan benar, lanjut ke konfirmasi
                $car = Car::findOrFail($carId);

                // Persiapkan data rental
                $rentalData = [
                    'customer_name' => $userData->name,
                    'car_name' => $car->name,
                    'price_per_day' => $car->price_per_day,
                    'fine' => $car->fine ?? 0,
                ];

                // Simpan data rental ke session
                session(['rental' => $rentalData]);

                // Redirect ke halaman konfirmasi
                return redirect()->route('confirmation');
            } else {
                // Jika data diri berbeda, tampilkan alert dan tidak simpan
                return redirect()->route('checkout.form', $carId)->with('alert', 'Satu akun hanya bisa memiliki satu data diri.');
            }
        }

        // Validasi input jika data diri belum ada
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'no_ktp' => 'required|string|unique:user_data,no_ktp',
        ]);

        // Simpan data user ke tabel user_data
        UserData::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'no_ktp' => $validated['no_ktp'],
        ]);

        // Simpan data user ke tabel customers
        Customer::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'no_ktp' => $validated['no_ktp'],
            'role' => 'customer', // Default sebagai customer
        ]);

        // Dapatkan data mobil
        $car = Car::findOrFail($carId);

        // Persiapkan data rental
        $rentalData = [
            'customer_name' => $validated['name'],
            'car_name' => $car->name,
            'price_per_day' => $car->price_per_day,
            'fine' => $car->fine ?? 0,
        ];

        // Simpan data rental ke session
        session(['rental' => $rentalData]);

        // Redirect ke halaman konfirmasi
        return redirect()->route('confirmation');
    }

    public function confirmation(Request $request)
    {
        // Ambil data dari session
        $rental = session('rental');

        // Periksa apakah data rental ada di session
        if (!$rental) {
            return redirect()->route('home')->withErrors(['message' => 'Data rental tidak ditemukan.']);
        }

        // Tampilkan view dengan data rental
        return view('confirmation', compact('rental'));
    }

    public function form($carId)
    {
        // Dapatkan data mobil berdasarkan ID
        $car = Car::findOrFail($carId);

        // Tampilkan view form checkout
        return view('checkoutproduct', compact('car'));
    }
}
