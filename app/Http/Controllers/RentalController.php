<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Rental;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class RentalController extends Controller
{
    public function index()
    {
        $rentals = Rental::where('user_id', Auth::id())
            ->with(['car', 'payment'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('rentals.index', compact('rentals'));
    }

    public function create(Car $car)
    {
        // Check if car is available
        if (!$car->is_available || !$car->is_approved) {
            return redirect()->route('cars.show', $car)
                ->with('error', 'This car is not available for booking.');
        }

        return view('rentals.create', compact('car'));
    }

    public function store(Request $request, Car $car)
    {
        // Validate the request
        $request->validate([
            'pickup_date' => 'required|date|after:today',
            'return_date' => 'required|date|after:pickup_date',
            'pickup_location' => 'required|string|max:255',
            'return_location' => 'required|string|max:255',
            'special_requests' => 'nullable|string',
        ]);

        try {
            // Check if car exists and is available
            if (!$car || !$car->exists) {
                return back()->with('error', 'Car not found.');
            }

            if (!$car->is_available || !$car->is_approved) {
                return back()->with('error', 'This car is not available for booking.');
            }

            // Check for overlapping bookings
            $overlappingRental = Rental::where('car_id', $car->id)
                ->whereIn('status', ['pending', 'confirmed', 'active'])
                ->where(function ($query) use ($request) {
                    $query->whereBetween('pickup_date', [$request->pickup_date, $request->return_date])
                        ->orWhereBetween('return_date', [$request->pickup_date, $request->return_date])
                        ->orWhere(function ($q) use ($request) {
                            $q->where('pickup_date', '<=', $request->pickup_date)
                                ->where('return_date', '>=', $request->return_date);
                        });
                })
                ->exists();

            if ($overlappingRental) {
                return back()->with('error', 'Car is already booked for the selected dates.');
            }

            // Calculate days and total amount
            $pickupDate = Carbon::parse($request->pickup_date);
            $returnDate = Carbon::parse($request->return_date);
            $days = $pickupDate->diffInDays($returnDate) + 1;
            $totalAmount = $car->price_per_day * $days;
            $totalWithDeposit = $totalAmount + $car->security_deposit;

            // Begin transaction
            DB::beginTransaction();

            try {
                // Create rental
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
                    'extras' => null,
                ]);

                // Create payment record - INCLUDING total_amount
                $payment = Payment::create([
                    'rental_id' => $rental->id,
                    'transaction_id' => 'TXN_' . strtoupper(uniqid()),
                    'amount' => $totalWithDeposit,
                    'tax_amount' => 0,
                    'discount_amount' => 0,
                    'total_amount' => $totalWithDeposit,  // THIS IS THE KEY FIX
                    'payment_method' => 'credit_card',
                    'status' => 'pending',
                    'payment_details' => null,
                    'notes' => 'Initial payment for rental #' . $rental->id,
                ]);

                DB::commit();

                // Redirect to payment page
                return redirect()->route('payments.process', $payment)
                    ->with('success', 'Booking created! Please complete payment.');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Booking creation failed: ' . $e->getMessage());
                Log::error($e->getTraceAsString());

                return back()->with('error', 'Failed to create booking. Error: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            Log::error('Booking validation failed: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return back()->with('error', 'Failed to create booking. Please try again.');
        }
    }

    public function show(Rental $rental)
    {
        // Check if user owns this rental or is admin
        if ($rental->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $rental->load(['car', 'payment']);
        return view('rentals.show', compact('rental'));
    }

    public function cancel(Rental $rental)
    {
        // Check if user owns this rental or is admin
        if ($rental->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        if (!$rental->canBeCancelled()) {
            return back()->with('error', 'This booking cannot be cancelled.');
        }

        try {
            DB::beginTransaction();

            $rental->update(['status' => 'cancelled']);

            // Make car available again
            $rental->car->update(['is_available' => true]);

            DB::commit();

            return back()->with('success', 'Booking cancelled successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Rental cancellation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to cancel booking. Please try again.');
        }
    }

    public function confirm(Rental $rental)
    {
        // Admin only
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        if ($rental->status !== 'pending') {
            return back()->with('error', 'This booking cannot be confirmed.');
        }

        try {
            DB::beginTransaction();

            $rental->update(['status' => 'confirmed']);

            DB::commit();

            return back()->with('success', 'Booking confirmed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Rental confirmation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to confirm booking.');
        }
    }

    public function complete(Rental $rental)
    {
        // Admin only
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        if ($rental->status !== 'active') {
            return back()->with('error', 'This booking cannot be completed.');
        }

        try {
            DB::beginTransaction();

            $rental->update(['status' => 'completed']);
            $rental->car->update(['is_available' => true]);

            DB::commit();

            return back()->with('success', 'Booking completed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Rental completion failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to complete booking.');
        }
    }
}
