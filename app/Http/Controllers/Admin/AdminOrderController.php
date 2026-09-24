<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items', 'payment'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('order_status')) {
            $query->where('order_status', $request->order_status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'items.product.images', 'payment', 'branch'])->findOrFail($id);
        $shippingAddress = json_decode($order->shipping_address, true) ?? [];
        $paymentDetails = $order->payment ? json_decode($order->payment->details, true) : [];

        return view('admin.orders.show', compact('order', 'shippingAddress', 'paymentDetails'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $request->validate([
            'order_status' => 'required|in:PENDING,CONFIRMED,PROCESSING,READY,SHIPPED,COMPLETED,CANCELLED',
        ]);

        $oldStatus = $order->order_status;
        $newStatus = $request->order_status;

        $updates = ['order_status' => $newStatus];
        if ($newStatus === 'SHIPPED' && !$order->shipped_at) {
            $updates['shipped_at'] = now();
        }
        if ($newStatus === 'COMPLETED' && !$order->completed_at) {
            $updates['completed_at'] = now();
        }

        // If cancelled, return stock
        if ($newStatus === 'CANCELLED' && $oldStatus !== 'CANCELLED') {
            foreach ($order->items as $item) {
                if ($item->variant) {
                    $item->variant->increment('stock', $item->quantity);
                }
            }
        }

        $order->update($updates);

        AuditLog::log('UPDATE_ORDER_STATUS', 'Order', $order->id, ['status' => $oldStatus], ['status' => $newStatus]);

        return back()->with('success', "Order status updated from {$oldStatus} to {$newStatus}.");
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $request->validate([
            'payment_status' => 'required|in:UNPAID,PENDING,PAID,FAILED,REFUNDED',
        ]);

        $old = $order->payment_status;
        $new = $request->payment_status;

        $updates = ['payment_status' => $new];
        if ($new === 'PAID') {
            $updates['paid_at'] = now();
            if ($order->order_status === 'PENDING') {
                $updates['order_status'] = 'CONFIRMED';
            }
        }

        $order->update($updates);

        if ($order->payment) {
            $order->payment->update([
                'status' => $new,
                'paid_at' => $new === 'PAID' ? now() : null,
            ]);
        }

        AuditLog::log('UPDATE_PAYMENT_STATUS', 'Order', $order->id, ['payment_status' => $old], ['payment_status' => $new]);

        return back()->with('success', "Payment status updated to {$new}.");
    }
}
