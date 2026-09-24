<?php

namespace App\Http\Controllers;

use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    protected MidtransService $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    public function handleMidtrans(Request $request): JsonResponse
    {
        $payload = $request->all();

        Log::info('Midtrans Webhook Received', $payload);

        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;

        if ($signatureKey && !$this->midtransService->verifySignature($orderId, $statusCode, $grossAmount, $signatureKey)) {
            Log::warning('Midtrans Webhook Signature Mismatch: ' . $orderId);
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 403);
        }

        $handled = $this->midtransService->handleNotification($payload);

        if ($handled) {
            return response()->json(['status' => 'success', 'message' => 'Notification processed successfully']);
        }

        return response()->json(['status' => 'ignored', 'message' => 'Order not found or invalid payload'], 404);
    }
}
