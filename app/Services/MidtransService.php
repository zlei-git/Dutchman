<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    protected string $serverKey;
    protected string $clientKey;
    protected bool $isProduction;
    protected string $apiUrl;
    protected string $snapUrl;

    public function __construct()
    {
        $this->serverKey = (string) config('midtrans.server_key');
        $this->clientKey = (string) config('midtrans.client_key');
        $this->isProduction = (bool) config('midtrans.is_production', false);
        $this->apiUrl = (string) config('midtrans.api_url');
        $this->snapUrl = (string) config('midtrans.snap_url');

        // Configure Official Midtrans PHP SDK
        Config::$serverKey = $this->serverKey;
        Config::$clientKey = $this->clientKey;
        Config::$isProduction = $this->isProduction;
        Config::$isSanitized = (bool) config('midtrans.is_sanitized', true);
        Config::$is3ds = (bool) config('midtrans.is_3ds', true);
    }

    public function getClientKey(): string
    {
        return $this->clientKey;
    }

    public function getSnapUrl(): string
    {
        return $this->snapUrl;
    }

    public function isProduction(): bool
    {
        return $this->isProduction;
    }

    /**
     * Create Snap Transaction token for a booking
     * Authoritative calculation strictly on the server-side
     */
    public function createSnapTransaction(Booking $booking): array
    {
        // 1. Recalculate price strictly from DB items & addons (Never trust client)
        $booking->loadMissing(['items.service', 'addons']);

        $servicesTotal = $booking->items->sum('price');
        $addonsTotal = $booking->addons->sum(function ($addon) {
            return $addon->price * $addon->quantity;
        });

        $calculatedTotal = $servicesTotal + $addonsTotal;
        if ($calculatedTotal <= 0) {
            $calculatedTotal = $booking->total_price ?: 100000;
        }

        // Keep database total accurate
        if ((float) $booking->total_price != (float) $calculatedTotal) {
            $booking->total_price = $calculatedTotal;
            $booking->save();
        }

        // Unique order ID per transaction attempt
        $orderId = $booking->booking_number;
        $existingPayment = $booking->payment;
        if ($existingPayment && in_array($existingPayment->status, ['failed', 'expired', 'cancelled'])) {
            $orderId = $booking->booking_number . '-' . substr(time(), -4);
        }

        // 2. Prepare Item Details
        $itemDetails = [];
        foreach ($booking->items as $item) {
            $serviceName = $item->service?->name ?? 'Barber Service';
            $itemDetails[] = [
                'id' => 'SVC-' . $item->service_id,
                'price' => (int) round($item->price),
                'quantity' => 1,
                'name' => mb_substr($serviceName, 0, 50),
            ];
        }

        foreach ($booking->addons as $addon) {
            $itemDetails[] = [
                'id' => 'ADD-' . $addon->id,
                'price' => (int) round($addon->price),
                'quantity' => (int) $addon->quantity,
                'name' => mb_substr($addon->name, 0, 50),
            ];
        }

        // Verify items sum equals gross_amount exactly
        $itemsSum = array_reduce($itemDetails, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        if ($itemsSum != (int) round($calculatedTotal)) {
            $itemDetails = [
                [
                    'id' => 'BOOKING-' . $booking->id,
                    'price' => (int) round($calculatedTotal),
                    'quantity' => 1,
                    'name' => mb_substr('Dutchman Appointment (' . $booking->booking_number . ')', 0, 50),
                ]
            ];
        }

        // 3. Customer Details
        $customerName = trim($booking->customer_name ?: 'Gentleman Guest');
        $customerEmail = $booking->customer_email ?: 'guest@dutchmanbarbershop.test';
        $customerPhone = $booking->customer_phone ?: '081210009744';

        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) round($calculatedTotal),
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $customerName,
                'email' => $customerEmail,
                'phone' => $customerPhone,
            ],
            'callbacks' => [
                'finish' => route('booking.success', $booking->id),
            ],
        ];

        // 4. Request Midtrans Snap API via Official SDK
        $token = null;
        $redirectUrl = null;
        $rawResponse = [];

        if (!empty($this->serverKey)) {
            try {
                // Official Midtrans SDK Snap transaction
                $snapResponse = Snap::createTransaction($payload);
                if (is_object($snapResponse)) {
                    $token = $snapResponse->token ?? null;
                    $redirectUrl = $snapResponse->redirect_url ?? null;
                    $rawResponse = (array) $snapResponse;
                } else if (is_array($snapResponse)) {
                    $token = $snapResponse['token'] ?? null;
                    $redirectUrl = $snapResponse['redirect_url'] ?? null;
                    $rawResponse = $snapResponse;
                }
            } catch (Exception $e) {
                Log::error('Midtrans Snap Exception: ' . $e->getMessage(), [
                    'order_id' => $orderId,
                    'payload' => $payload,
                ]);
                throw new Exception('Midtrans Error: Gagal membuat sesi pembayaran Midtrans. ' . $e->getMessage());
            }
        } else {
            // Local Sandbox simulation fallback when Server Key is not yet configured in .env
            $token = 'SANDBOX-SIMULATION-' . md5($orderId . time());
            $redirectUrl = 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $token;
            $rawResponse = [
                'token' => $token,
                'redirect_url' => $redirectUrl,
                'note' => 'Local sandbox simulation (MIDTRANS_SERVER_KEY not set in .env)',
            ];
        }

        // 5. Store / Update Payment Record in Database
        $payment = Payment::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'order_id' => $orderId,
                'snap_token' => $token,
                'amount' => $calculatedTotal,
                'status' => 'pending',
                'raw_response' => json_encode($rawResponse),
            ]
        );

        return [
            'token' => $token,
            'snap_token' => $token,
            'redirect_url' => $redirectUrl,
            'order_id' => $orderId,
            'amount' => $calculatedTotal,
            'payment' => $payment,
        ];
    }

    /**
     * Verify SHA512 signature from Midtrans Webhook:
     * SHA512(order_id + status_code + gross_amount + ServerKey)
     */
    public function verifySignature(string $orderId, string $statusCode, string $grossAmount, string $receivedSignature): bool
    {
        if (empty($this->serverKey)) {
            return true; // Sandbox simulation mode without keys
        }

        $input = $orderId . $statusCode . $grossAmount . $this->serverKey;
        $computed = hash('sha512', $input);

        return hash_equals($computed, $receivedSignature);
    }

    /**
     * Process incoming notification webhook from Midtrans
     * Fully idempotent — duplicate notifications do not re-process or duplicate records
     */
    public function handleNotification(array $payload): array
    {
        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? 'midtrans';
        $transactionId = $payload['transaction_id'] ?? null;

        if (!$orderId) {
            throw new Exception('Order ID is required in notification payload.');
        }

        // 1. Signature Verification
        if (!empty($this->serverKey)) {
            if (!$signatureKey || !$this->verifySignature((string)$orderId, (string)$statusCode, (string)$grossAmount, (string)$signatureKey)) {
                Log::warning('Midtrans Notification: Signature verification failed', [
                    'order_id' => $orderId,
                    'status_code' => $statusCode,
                    'gross_amount' => $grossAmount,
                ]);
                throw new Exception('Invalid Midtrans Signature Key.');
            }
        }

        // 2. Locate Payment & Booking
        $payment = Payment::where('order_id', $orderId)->first();
        if (!$payment) {
            // Check without retry suffix
            $parts = explode('-', $orderId);
            $baseNumber = ($parts[0] ?? '') . '-' . ($parts[1] ?? '') . '-' . ($parts[2] ?? '');
            $booking = Booking::where('booking_number', $orderId)
                ->orWhere('booking_number', $baseNumber)
                ->first();

            if ($booking && $booking->payment) {
                $payment = $booking->payment;
            } else {
                throw new Exception("Payment record with Order ID '{$orderId}' not found.");
            }
        }

        $booking = $payment->booking;

        // 3. Idempotency Check: if already paid, simply acknowledge
        if ($payment->status === 'paid' && in_array($transactionStatus, ['capture', 'settlement'])) {
            Log::info("Idempotent notification acknowledged: Order {$orderId} is already paid.");
            return [
                'success' => true,
                'message' => 'Order is already marked as paid.',
                'order_id' => $orderId,
                'payment_status' => 'paid',
                'booking_status' => $booking->status,
            ];
        }

        // 4. Map Midtrans Status to Application Status
        $paymentStatus = 'pending';
        $bookingStatus = $booking->status;

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $paymentStatus = 'pending';
                $bookingStatus = 'pending';
            } else if ($fraudStatus == 'accept') {
                $paymentStatus = 'paid';
                $bookingStatus = 'confirmed';
            }
        } elseif ($transactionStatus == 'settlement') {
            $paymentStatus = 'paid';
            $bookingStatus = 'confirmed';
        } elseif (in_array($transactionStatus, ['cancel', 'deny'])) {
            $paymentStatus = 'failed';
            $bookingStatus = 'cancelled';
        } elseif ($transactionStatus == 'expire') {
            $paymentStatus = 'expired';
            $bookingStatus = 'cancelled';
        } elseif ($transactionStatus == 'pending') {
            $paymentStatus = 'pending';
            $bookingStatus = 'pending';
        } elseif (in_array($transactionStatus, ['refund', 'partial_refund'])) {
            $paymentStatus = 'refunded';
        }

        // 5. Update Payment Record
        $payment->status = $paymentStatus;
        $payment->transaction_id = $transactionId ?: $payment->transaction_id;
        $payment->payment_method = $paymentType ?: $payment->payment_method;
        if ($paymentStatus === 'paid' && !$payment->paid_at) {
            $payment->paid_at = now();
        }
        $payment->raw_response = json_encode($payload);
        $payment->save();

        // 6. Update Booking Record
        $booking->status = $bookingStatus;
        $booking->save();

        Log::info("Midtrans Notification Processed for Order {$orderId}", [
            'payment_status' => $paymentStatus,
            'booking_status' => $bookingStatus,
            'payment_id' => $payment->id,
        ]);

        return [
            'success' => true,
            'order_id' => $orderId,
            'payment_status' => $paymentStatus,
            'booking_status' => $bookingStatus,
            'booking_id' => $booking->id,
        ];
    }
}
