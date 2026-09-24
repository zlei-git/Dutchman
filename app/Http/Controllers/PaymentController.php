<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\MidtransService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected MidtransService $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Request Snap Token for a Booking
     */
    public function createSnap(Request $request, Booking $booking)
    {
        try {
            $result = $this->midtransService->createSnapTransaction($booking);

            return response()->json([
                'success' => true,
                'snap_token' => $result['token'],
                'token' => $result['token'],
                'redirect_url' => $result['redirect_url'],
                'order_id' => $result['order_id'],
                'amount' => $result['amount'],
                'client_key' => $this->midtransService->getClientKey(),
                'snap_url' => $this->midtransService->getSnapUrl(),
            ]);
        } catch (Exception $e) {
            Log::error('PaymentController createSnap Exception: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Unable to initialize payment. ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Handle Midtrans Webhook Notification
     */
    public function notification(Request $request)
    {
        try {
            $payload = $request->all();
            Log::info('Midtrans Webhook Received', $payload);

            $result = $this->midtransService->handleNotification($payload);

            return response()->json([
                'status' => 'success',
                'message' => 'Notification processed successfully.',
                'data' => $result,
            ], 200);
        } catch (Exception $e) {
            Log::error('Midtrans Webhook Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Development / Sandbox direct simulation helper
     */
    public function simulateSuccess(Request $request, Booking $booking)
    {
        if (config('midtrans.is_production')) {
            abort(404);
        }

        $payment = $booking->payment()->firstOrCreate(
            ['booking_id' => $booking->id],
            [
                'order_id' => $booking->booking_number,
                'amount' => $booking->total_price,
            ]
        );

        $paymentMethod = $request->input('method', 'QRIS (Sandbox)');

        $payment->status = 'paid';
        $payment->payment_method = $paymentMethod;
        $payment->paid_at = now();
        $payment->transaction_id = 'SANDBOX-TRX-' . strtoupper(uniqid());
        $payment->raw_response = json_encode(['simulated' => true, 'timestamp' => now()]);
        $payment->save();

        $booking->status = 'confirmed';
        $booking->save();

        return redirect()->route('booking.success', $booking->id);
    }
}
