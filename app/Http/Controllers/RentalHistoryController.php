<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\Customer;
use App\Models\User;

class RentalHistoryController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $user = User::where('id', $userId)->first();
        $isAdmin = $user->role === 0; // Changed to check for role 0

        // If user is a customer (role 1)
        if (!$isAdmin) {
            $customerId = Customer::where('name', $user->name)->first()->id;
            $rentals = Rental::with(['car', 'customer'])
                ->where('customer_id', $customerId)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // If admin (role 0), get all rentals
            $rentals = Rental::with(['car', 'customer'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('history', compact('rentals', 'isAdmin'));
    }
}
