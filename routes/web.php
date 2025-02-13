<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\UserDataController;
use App\Http\Controllers\RentalHistoryController;

Route::middleware('auth')->get('/checkout/form/{carId}', [CheckoutController::class, 'form'])->name('checkout.form');
Route::get('/checkout/form/{carId}', [CheckoutController::class, 'form'])->name('checkout.form');
Route::get('/history', [RentalController::class, 'history'])->name('rental.history')->middleware('auth');


Route::post('/process-checkout/{car}', [CheckoutController::class, 'store'])->name('process.checkout');
// Definisikan rute checkout hanya di dalam grup middleware 'auth' untuk memastikan hanya pengguna yang login yang bisa mengaksesnya
Route::middleware('auth')->group(function () {
    Route::post('checkout/{car}', [CheckoutController::class, 'processCheckout'])->name('process.checkout');
    Route::post('/checkout/{car}', [CheckoutController::class, 'store'])->name('process.checkout');

    Route::post('/process-checkout/{car}', [CheckoutController::class, 'store'])->name('process.checkout');

    Route::get('/checkout/{car}', [ProductController::class, 'checkout'])->name('checkout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/product', [CarController::class, 'index'])->name('product');
    Route::get('/', function () {
        return view('dashboard');
    })->name('home');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');

    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    Route::get('/confirmation', [CheckoutController::class, 'confirmation'])->name('confirmation');

    Route::post('/process-rental', [RentalController::class, 'store'])->name('process.rental');

    Route::get('/confirmation', function () {
        return view('confirmation');
    })->name('confirmation');

    Route::post('/user-data', [UserDataController::class, 'store'])->name('user-data.store');

    Route::post('/checkout/{carId}', [CheckoutController::class, 'store'])->name('rent');

    Route::post('/checkout/{carId}', [CheckoutController::class, 'store'])->name('checkout.store');

    // Route untuk GET request
    Route::get('/checkout/{carId}', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::post('/checkout/{carId}', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::middleware('auth')->get('/checkout/form/{carId}', [CheckoutController::class, 'form'])->name('checkout.form');
    Route::get('/checkout/form/{carId}', [CheckoutController::class, 'form'])->name('checkout.form');

    Route::post('/process-rental', [RentalController::class, 'processRental'])->name('process.rental');

    Route::get('/rental-history', [RentalHistoryController::class, 'index'])->name('rental.history');

    Route::patch('/rental/{rental}/cancel', [RentalController::class, 'cancel'])->name('rental.cancel');

    Route::get('/product/{car}', [ProductController::class, 'show'])->name('product.detail');
});

require __DIR__ . '/auth.php';
