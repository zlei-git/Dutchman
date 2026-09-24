<?php

namespace App\Services;

use App\Models\InventoryTransaction;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ProductVariant;
use App\Models\Promotion;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderService
{
    protected CartService $cartService;
    protected MidtransService $midtransService;

    public function __construct(CartService $cartService, MidtransService $midtransService)
    {
        $this->cartService = $cartService;
        $this->midtransService = $midtransService;
    }

    public function processCheckout(User $user, array $checkoutData): Order
    {
        return DB::transaction(function () use ($user, $checkoutData) {
            $shippingMethod = $checkoutData['shipping_method'] ?? 'Regular';
            $promoCode = $checkoutData['promo_code'] ?? null;
            $paymentMethod = $checkoutData['payment_method'] ?? 'Midtrans Snap';

            // 1. Recalculate summary strictly from database
            $summary = $this->cartService->calculateSummary($promoCode, $shippingMethod);

            if (empty($summary['items'])) {
                throw new Exception('Keranjang belanja Anda kosong. Silakan pilih produk terlebih dahulu.');
            }

            // 2. Validate variant stock and lock for update
            foreach ($summary['items'] as $item) {
                $variant = ProductVariant::lockForUpdate()->find($item['variant_id']);
                if (!$variant) {
                    throw new Exception("Varian produk {$item['product_name']} tidak lagi tersedia.");
                }
                if ($variant->stock < $item['quantity']) {
                    throw new Exception("Stok tidak mencukupi untuk {$item['product_name']} ({$variant->size}). Tersedia: {$variant->stock}.");
                }
            }

            $orderType = ($shippingMethod === 'Store Pickup' || ($checkoutData['order_type'] ?? '') === 'PICKUP') ? 'PICKUP' : 'DELIVERY';

            // 3. Create Order
            $orderNumber = Order::generateOrderNumber();
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $user->id,
                'branch_id' => $checkoutData['branch_id'] ?? null,
                'order_type' => $orderType,
                'subtotal' => $summary['subtotal'],
                'discount' => $summary['discount'],
                'shipping_fee' => $summary['shipping_fee'],
                'total' => $summary['total'],
                'promo_code' => $summary['promo_code'],
                'shipping_method' => $shippingMethod,
                'payment_method' => $paymentMethod,
                'payment_status' => 'PENDING',
                'order_status' => 'PENDING',
                'recipient_name' => $checkoutData['recipient_name'] ?? $user->name,
                'recipient_phone' => $checkoutData['phone'] ?? $user->phone,
                'shipping_address' => json_encode([
                    'recipient_name' => $checkoutData['recipient_name'] ?? $user->name,
                    'phone' => $checkoutData['phone'] ?? $user->phone,
                    'address' => $checkoutData['address'] ?? 'Ambil di Toko',
                    'city' => $checkoutData['city'] ?? 'Surabaya',
                    'district' => $checkoutData['district'] ?? null,
                    'postal_code' => $checkoutData['postal_code'] ?? null,
                ]),
                'notes' => $checkoutData['notes'] ?? null,
            ]);

            // 4. Create Order Items, Deduct Stock, Record Inventory Logs
            foreach ($summary['items'] as $item) {
                $variant = ProductVariant::find($item['variant_id']);
                $variant->decrement('stock', $item['quantity']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $variant->product_id,
                    'product_variant_id' => $variant->id,
                    'product_name' => $item['product_name'],
                    'size' => $item['size'],
                    'color' => $item['color'] ?? null,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Audit log inventory deduction
                InventoryTransaction::create([
                    'product_variant_id' => $variant->id,
                    'type' => 'OUT',
                    'quantity' => $item['quantity'],
                    'reference' => 'Order #' . $order->order_number,
                    'notes' => "Purchased by {$user->name}",
                ]);
            }

            // 5. Generate Midtrans Snap Transaction
            $snapResult = $this->midtransService->createSnapTransaction($order);
            if (!empty($snapResult['token'])) {
                $order->update(['snap_token' => $snapResult['token']]);
            }

            // 6. Create Base Payment Record
            $paymentRef = 'PAY-' . strtoupper(bin2hex(random_bytes(4))) . '-' . now()->format('His');
            Payment::create([
                'order_id' => $order->id,
                'payment_number' => $paymentRef,
                'amount' => $order->total,
                'payment_method' => $paymentMethod,
                'status' => 'PENDING',
                'payment_details' => [
                    'note' => 'PAWMART Pet Care Payment',
                    'snap_token' => $order->snap_token,
                    'instructions' => $this->getPaymentInstructions($paymentMethod, $order->total, $paymentRef),
                ],
            ]);

            // 7. Update Promotion count if used
            if ($summary['promo_code']) {
                Promotion::where('code', $summary['promo_code'])->increment('used_count');
            }

            // 8. Clear user cart
            $this->cartService->clearCart();

            return $order;
        });
    }

    public function reorder(Order $order): array
    {
        $addedCount = 0;
        $failedCount = 0;

        foreach ($order->items as $item) {
            if ($item->variant && $item->variant->stock >= $item->quantity) {
                $this->cartService->addItem($item->variant->id, $item->quantity);
                $addedCount++;
            } else {
                $failedCount++;
            }
        }

        return [
            'success' => $addedCount > 0,
            'added' => $addedCount,
            'failed' => $failedCount,
        ];
    }

    private function getPaymentInstructions(string $method, float $amount, string $ref): array
    {
        $formatted = 'Rp ' . number_format($amount, 0, ',', '.');
        return match ($method) {
            'Bank Transfer' => [
                'bank' => 'BCA Virtual Account',
                'account_number' => '8921 7731 0029 4810',
                'account_name' => 'PT PAWMART PET CARE INDONESIA',
                'amount' => $formatted,
                'expiry' => '24 hours',
            ],
            'QRIS Demo', 'QRIS' => [
                'type' => 'QRIS Realtime Dynamic / Midtrans QRIS',
                'merchant' => 'PAWMART PET CARE & GROOMING',
                'amount' => $formatted,
                'nmid' => 'ID202688491024',
            ],
            'E-Wallet Demo', 'E-Wallet' => [
                'providers' => ['GoPay', 'ShopeePay', 'OVO', 'DANA'],
                'phone' => '0812-3344-5566',
                'account_name' => 'PAWMART Pet Care',
                'amount' => $formatted,
            ],
            'Midtrans Snap' => [
                'type' => 'Midtrans Snap Payment Gateway',
                'note' => 'Selesaikan pembayaran menggunakan pop-up Midtrans Snap yang muncul di layar.',
                'amount' => $formatted,
            ],
            default => [
                'type' => 'Cash on Delivery (COD)',
                'note' => 'Siapkan uang pas saat pesanan diantar oleh kurir PAWMART Express.',
                'amount' => $formatted,
            ],
        };
    }
}
