<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function process(Payment $payment)
    {
        if ($payment->rental->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $payment->load('rental.car');
        return view('payments.process', compact('payment'));
    }

    public function confirm(Request $request, Payment $payment)
    {
        $request->validate([
            'payment_method' => 'required|in:credit_card,debit_card,paypal',
            'card_number' => 'required_if:payment_method,credit_card,debit_card|string',
            'card_expiry' => 'required_if:payment_method,credit_card,debit_card|string',
            'card_cvv' => 'required_if:payment_method,credit_card,debit_card|string',
        ]);

        // Simulate payment processing
        $payment->update([
            'payment_method' => $request->payment_method,
            'payment_details' => json_encode([
                'card_number' => substr($request->card_number, -4),
                'card_expiry' => $request->card_expiry,
            ]),
            'status' => 'completed',
            'transaction_id' => 'TXN_' . strtoupper(uniqid()),
        ]);

        // Update rental status
        $payment->rental->update(['status' => 'confirmed']);

        // Update car availability
        $payment->rental->car->update(['is_available' => false]);

        return redirect()->route('rentals.show', $payment->rental)
            ->with('success', 'Payment confirmed! Your booking is now active.');
    }
}
