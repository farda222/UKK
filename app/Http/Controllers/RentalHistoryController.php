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
            $customer = Customer::where('name', $user->name)->first();

            // Check if customer data exists for the user
            if ($customer) {
                $rentals = Rental::with(['car', 'customer'])
                    ->where('customer_id', $customer->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                // No customer record found, so no rentals to show
                $rentals = collect();
            }
        } else {
            // If admin (role 0), get all rentals
            $rentals = Rental::with(['car', 'customer'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('history', compact('rentals', 'isAdmin'));
    }
}
