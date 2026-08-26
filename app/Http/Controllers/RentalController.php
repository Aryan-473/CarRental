<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Rental;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{
    public function create(Car $car)
    {
        return view('rentals.create', compact('car'));
    }

    public function store(Request $request, Car $car)
    {
        $request->validate([
            'pickup_date' => 'required|date|after:today',
            'return_date' => 'required|date|after:pickup_date',
            'pickup_location' => 'required|string',
            'return_location' => 'required|string',
            'special_requests' => 'nullable|string',
        ]);

        // Check availability
        $existingRental = Rental::where('car_id', $car->id)
            ->whereIn('status', ['pending', 'confirmed', 'active'])
            ->where(function($query) use ($request) {
                $query->whereBetween('pickup_date', [$request->pickup_date, $request->return_date])
                      ->orWhereBetween('return_date', [$request->pickup_date, $request->return_date]);
            })
            ->exists();

        if ($existingRental) {
            return back()->with('error', 'Car is not available for selected dates.');
        }

        DB::transaction(function() use ($request, $car) {
            $days = now()->parse($request->pickup_date)->diffInDays(now()->parse($request->return_date)) + 1;
            $totalAmount = $car->price_per_day * $days;

            $rental = Rental::create([
                'user_id' => Auth::id(),
                'car_id' => $car->id,
                'pickup_date' => $request->pickup_date,
                'return_date' => $request->return_date,
                'pickup_location' => $request->pickup_location,
                'return_location' => $request->return_location,
                'total_amount' => $totalAmount,
                'security_deposit' => $car->security_deposit,
                'status' => 'pending',
                'special_requests' => $request->special_requests,
            ]);

            // Create payment record
            $payment = Payment::create([
                'rental_id' => $rental->id,
                'amount' => $totalAmount + $car->security_deposit,
                'payment_method' => 'credit_card', // Default, will be updated during payment processing
                'status' => 'pending',
                'transaction_id' => 'TXN_' . uniqid(),
            ]);

            // Redirect to payment
            return redirect()->route('payments.process', $payment);
        });

        return redirect()->route('rentals.index')->with('success', 'Booking created successfully!');
    }

    public function index()
    {
        $rentals = Rental::where('user_id', Auth::id())
                        ->with('car', 'payment')
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
        return view('rentals.index', compact('rentals'));
    }

    public function show(Rental $rental)
    {
        if ($rental->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        $rental->load('car', 'payment');
        return view('rentals.show', compact('rental'));
    }

    public function cancel(Rental $rental)
    {
        if ($rental->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        if (in_array($rental->status, ['cancelled', 'completed'])) {
            return back()->with('error', 'Cannot cancel this booking.');
        }

        $rental->update(['status' => 'cancelled']);
        return back()->with('success', 'Booking cancelled successfully.');
    }
}