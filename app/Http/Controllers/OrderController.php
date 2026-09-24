<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MidtransService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected OrderService $orderService;
    protected MidtransService $midtransService;

    public function __construct(OrderService $orderService, MidtransService $midtransService)
    {
        $this->orderService = $orderService;
        $this->midtransService = $midtransService;
    }

    public function index(Request $request)
    {
        $query = Auth::user()->orders()->with(['items.product.images', 'payment'])->latest();

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        $orders = $query->paginate(10);

        return view('user.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Auth::user()->orders()
            ->with(['items.product.images', 'payment', 'branch', 'gatewayTransactions'])
            ->findOrFail($id);

        $shippingAddress = is_string($order->shipping_address) ? json_decode($order->shipping_address, true) : $order->shipping_address;
        $paymentDetails = $order->payment ? (is_string($order->payment->details) ? json_decode($order->payment->details, true) : $order->payment->details) : [];

        $snapClientKey = $this->midtransService->getClientKey();
        $snapJsUrl = $this->midtransService->getSnapJsUrl();

        return view('user.orders.show', compact('order', 'shippingAddress', 'paymentDetails', 'snapClientKey', 'snapJsUrl'));
    }

    public function simulatePayment($id)
    {
        $order = Auth::user()->orders()->findOrFail($id);

        if ($order->payment_status === 'PAID') {
            return back()->with('info', 'Pesanan ini sudah berstatus LUNAS (PAID).');
        }

        $order->update([
            'payment_status' => 'PAID',
            'order_status' => 'CONFIRMED',
            'paid_at' => now(),
        ]);

        if ($order->payment) {
            $order->payment->update([
                'status' => 'PAID',
                'paid_at' => now(),
            ]);
        }

        return back()->with('success', 'Simulasi Pembayaran Berhasil! Status pesanan kini CONFIRMED.');
    }

    public function cancel($id)
    {
        $order = Auth::user()->orders()->findOrFail($id);

        if (!in_array($order->order_status, ['PENDING', 'CONFIRMED'])) {
            return back()->with('error', 'Tidak dapat membatalkan pesanan yang sedang diproses atau dikirim.');
        }

        // Return stock
        foreach ($order->items as $item) {
            if ($item->variant) {
                $item->variant->increment('stock', $item->quantity);
            }
        }

        $order->update([
            'order_status' => 'CANCELLED',
            'payment_status' => $order->payment_status === 'PAID' ? 'REFUNDED' : 'FAILED',
        ]);

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function reorder($id)
    {
        $order = Auth::user()->orders()->with('items.variant')->findOrFail($id);

        $result = $this->orderService->reorder($order);

        if ($result['success']) {
            return redirect()->route('user.cart')->with(
                'success',
                "Reorder berhasil: {$result['added']} item dimasukkan kembali ke keranjang belanja Anda!"
            );
        }

        return back()->with('error', 'Semua item dalam pesanan ini sedang habis stok.');
    }
}
