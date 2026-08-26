<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function process(Payment $payment)
    {
        // Check if user owns this payment or is admin
        if ($payment->rental->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        // If payment is completed, redirect to rental show
        if ($payment->status === 'completed') {
            return redirect()->route('rentals.show', $payment->rental)
                ->with('info', 'This payment has already been completed.');
        }

        // If payment is failed, allow retry
        if ($payment->status === 'failed') {
            // Reset the payment status to pending for retry
            $payment->update([
                'status' => 'pending',
                'transaction_id' => 'TXN_' . strtoupper(uniqid()),
                'payment_details' => null,
                'paid_at' => null,
                'notes' => 'Retry payment for rental #' . $payment->rental->id,
            ]);

            return view('payments.process', compact('payment'))
                ->with('info', 'Your previous payment attempt failed. Please try again.');
        }

        if ($payment->status !== 'pending') {
            return redirect()->route('rentals.show', $payment->rental)
                ->with('error', 'This payment cannot be processed.');
        }

        $payment->load('rental.car');
        return view('payments.process', compact('payment'));
    }

    public function confirm(Request $request, Payment $payment)
    {
        // Check if user owns this payment or is admin
        if ($payment->rental->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        // If payment is already completed, redirect
        if ($payment->status === 'completed') {
            return redirect()->route('rentals.show', $payment->rental)
                ->with('info', 'This payment has already been completed.');
        }

        // If payment is failed, allow retry
        if ($payment->status === 'failed') {
            // Reset the payment for retry
            $payment->update([
                'status' => 'pending',
                'transaction_id' => 'TXN_' . strtoupper(uniqid()),
                'payment_details' => null,
                'paid_at' => null,
            ]);
        }

        if ($payment->status !== 'pending') {
            return redirect()->route('rentals.show', $payment->rental)
                ->with('error', 'This payment cannot be processed.');
        }

        // Validate the request
        $request->validate([
            'payment_method' => 'required|in:credit_card,debit_card,paypal',
            'card_number' => 'required_if:payment_method,credit_card,debit_card|string|max:20',
            'card_expiry' => 'required_if:payment_method,credit_card,debit_card|string|max:5',
            'card_cvv' => 'required_if:payment_method,credit_card,debit_card|string|max:4',
        ]);

        try {
            DB::beginTransaction();

            // Validate payment details (no random failures)
            $this->validatePaymentDetails($request, $payment);

            // Get card details if provided
            $cardLastFour = null;
            $cardBrand = null;
            $paymentDetails = null;

            if (in_array($request->payment_method, ['credit_card', 'debit_card']) && $request->filled('card_number')) {
                $cardNumber = preg_replace('/\D/', '', $request->card_number);
                $cardLastFour = substr($cardNumber, -4);
                $cardBrand = $this->getCardBrand($cardNumber);
                $paymentDetails = json_encode([
                    'card_last_four' => $cardLastFour,
                    'card_brand' => $cardBrand,
                    'card_expiry' => $request->card_expiry,
                    'payment_date' => now()->toDateTimeString(),
                    'payment_method' => $request->payment_method,
                ]);
            }

            // Update payment
            $payment->update([
                'payment_method' => $request->payment_method,
                'card_last_four' => $cardLastFour,
                'card_brand' => $cardBrand,
                'payment_gateway' => 'stripe',
                'gateway_response' => json_encode([
                    'payment_date' => now()->toDateTimeString(),
                    'status' => 'success',
                    'payment_method' => $request->payment_method,
                ]),
                'status' => 'completed',
                'payment_details' => $paymentDetails,
                'paid_at' => now(),
                'transaction_id' => 'TXN_' . strtoupper(uniqid()),
            ]);

            // Update rental status
            $rental = $payment->rental;
            $rental->update(['status' => 'confirmed']);

            // Update car availability
            $rental->car->update(['is_available' => false]);

            DB::commit();

            return redirect()->route('rentals.show', $rental)
                ->with('success', 'Payment confirmed! Your booking is now active.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment confirmation failed: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            // Mark payment as failed with error message
            $payment->update([
                'status' => 'failed',
                'notes' => 'Payment failed on ' . now()->toDateTimeString() . ': ' . $e->getMessage()
            ]);

            return back()->with('error', 'Payment processing failed: ' . $e->getMessage());
        }
    }

    public function refund(Payment $payment)
    {
        // Admin only
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        if ($payment->status !== 'completed') {
            return back()->with('error', 'Only completed payments can be refunded.');
        }

        try {
            DB::beginTransaction();

            $payment->update([
                'status' => 'refunded',
                'refunded_at' => now(),
                'notes' => 'Refunded on ' . now()->toDateTimeString()
            ]);
            $payment->rental->update(['status' => 'cancelled']);
            $payment->rental->car->update(['is_available' => true]);

            DB::commit();

            return back()->with('success', 'Payment refunded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment refund failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to refund payment.');
        }
    }

    public function retry(Payment $payment)
    {
        // Check if user owns this payment or is admin
        if ($payment->rental->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        // Only allow retry for failed payments
        if ($payment->status !== 'failed') {
            return redirect()->route('rentals.show', $payment->rental)
                ->with('error', 'This payment cannot be retried.');
        }

        // Reset the payment for retry
        $payment->update([
            'status' => 'pending',
            'transaction_id' => 'TXN_' . strtoupper(uniqid()),
            'payment_details' => null,
            'paid_at' => null,
            'notes' => 'Retry attempt on ' . now()->toDateTimeString()
        ]);

        return redirect()->route('payments.process', $payment)
            ->with('info', 'Payment reset. Please try again.');
    }

    private function validatePaymentDetails(Request $request, Payment $payment)
    {
        // For PayPal, skip card validation
        if ($request->payment_method === 'paypal') {
            // Simulate PayPal success
            return;
        }

        // For credit/debit card, validate card details
        if (in_array($request->payment_method, ['credit_card', 'debit_card'])) {
            // Validate card number
            $cardNumber = preg_replace('/\D/', '', $request->card_number);
            if (empty($cardNumber)) {
                throw new \Exception('Card number is required.');
            }

            if (strlen($cardNumber) < 13 || strlen($cardNumber) > 19) {
                throw new \Exception('Invalid card number. Please enter a valid card number (13-19 digits).');
            }

            // Validate expiry date
            $expiry = explode('/', $request->card_expiry);
            if (count($expiry) !== 2) {
                throw new \Exception('Invalid expiry date format. Please use MM/YY format.');
            }

            $month = (int)trim($expiry[0]);
            $year = (int)trim($expiry[1]);
            $currentYear = (int)date('y');
            $currentMonth = (int)date('m');

            if ($month < 1 || $month > 12) {
                throw new \Exception('Invalid expiry month. Please enter a month between 01 and 12.');
            }

            if ($year < $currentYear || ($year == $currentYear && $month < $currentMonth)) {
                throw new \Exception('Card has expired. Please use a valid card.');
            }

            // Validate CVV
            $cvv = preg_replace('/\D/', '', $request->card_cvv);
            if (empty($cvv)) {
                throw new \Exception('CVV is required.');
            }

            if (strlen($cvv) < 3 || strlen($cvv) > 4) {
                throw new \Exception('Invalid CVV. Please enter a valid 3-4 digit CVV.');
            }

            // All valid - continue
            return;
        }

        throw new \Exception('Invalid payment method selected.');
    }

    private function getCardBrand($cardNumber)
    {
        $cardNumber = preg_replace('/\D/', '', $cardNumber);
        $patterns = [
            'visa' => '/^4/',
            'mastercard' => '/^5[1-5]/',
            'amex' => '/^3[47]/',
            'discover' => '/^6(?:011|5)/',
        ];

        foreach ($patterns as $brand => $pattern) {
            if (preg_match($pattern, $cardNumber)) {
                return $brand;
            }
        }
        return 'unknown';
    }
}
