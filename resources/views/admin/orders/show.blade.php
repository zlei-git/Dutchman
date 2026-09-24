@extends('layouts.admin')

@section('title', 'Manage Order #' . $order->order_number)
@section('page_title', 'Order #' . $order->order_number)

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline">&larr; Back to Orders</a>
</div>

<div style="display: grid; grid-template-columns: 1fr 360px; gap: 2rem; align-items: start;">
    <!-- Order Items & Customer Details -->
    <div class="card" style="padding: 1.75rem;">
        <div class="flex items-center justify-between" style="padding-bottom: 1rem; border-bottom: 1px solid var(--border); margin-bottom: 1.25rem;">
            <div>
                <span style="font-size: 0.8rem; color: var(--muted);">Order Date</span>
                <div style="font-weight: 700;">{{ $order->created_at->format('l, d F Y - H:i') }} WIB</div>
            </div>
            <div class="flex items-center gap-2">
                <x-order-status :status="$order->order_status" />
                <span class="badge {{ $order->payment_status === 'PAID' ? 'badge-success' : 'badge-warning' }}">
                    {{ $order->payment_status }}
                </span>
            </div>
        </div>

        <!-- Items Table -->
        <h4 style="margin-bottom: 1rem;">Items Ordered</h4>
        <div class="table-responsive" style="margin-bottom: 1.5rem;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Packaging / Variant</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <img src="{{ $item->product->image_url ?? asset('images/products/placeholder.svg') }}" alt="{{ $item->product_name }}" style="width: 45px; height: 35px; object-fit: cover; border-radius: var(--radius-sm); background: #f0f0f0;">
                                    <strong>{{ $item->product_name }}</strong>
                                </div>
                            </td>
                            <td>{{ $item->size }} @if($item->color) &bull; {{ $item->color }} @endif</td>
                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td><strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Price Breakdown -->
        <div class="flex flex-col gap-2" style="padding-top: 1rem; border-top: 1px solid var(--border); font-size: 0.9rem;">
            <div class="flex justify-between">
                <span style="color: var(--muted);">Subtotal</span>
                <strong>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</strong>
            </div>
            @if($order->discount > 0)
                <div class="flex justify-between" style="color: var(--success);">
                    <span>Discount (Voucher: {{ $order->promo_code }})</span>
                    <strong>- Rp {{ number_format($order->discount, 0, ',', '.') }}</strong>
                </div>
            @endif
            <div class="flex justify-between">
                <span style="color: var(--muted);">Shipping Fee ({{ $order->shipping_method }})</span>
                <strong>Rp {{ number_format($order->shipping_fee, 0, ',', '.') }}</strong>
            </div>
            <div class="flex justify-between items-baseline" style="border-top: 2px solid var(--primary); padding-top: 0.75rem; margin-top: 0.5rem;">
                <strong style="font-size: 1.1rem;">Grand Total</strong>
                <strong style="font-family: var(--font-heading); font-size: 1.4rem; color: var(--primary);">
                    Rp {{ number_format($order->total, 0, ',', '.') }}
                </strong>
            </div>
        </div>
    </div>

    <!-- Right Sidebar: Status Updaters & Addresses -->
    <div class="flex flex-col gap-4">
        <!-- Update Order Status Card -->
        <div class="card" style="padding: 1.5rem; border-top: 4px solid var(--primary);">
            <h4 style="margin-bottom: 1rem;">Update Order Status</h4>
            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Order Lifecycle Status</label>
                    <select name="order_status" class="form-select">
                        @foreach(['PENDING', 'CONFIRMED', 'PROCESSING', 'READY', 'SHIPPED', 'COMPLETED', 'CANCELLED'] as $st)
                            <option value="{{ $st }}" {{ $order->order_status === $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-sm btn-primary btn-block">Update Status</button>
            </form>
        </div>

        <!-- Update Payment Status Card -->
        <div class="card" style="padding: 1.5rem; border-top: 4px solid var(--accent);">
            <h4 style="margin-bottom: 1rem;">Update Payment Status</h4>
            <form action="{{ route('admin.orders.payment_status', $order->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Payment Status</label>
                    <select name="payment_status" class="form-select">
                        @foreach(['UNPAID', 'PENDING', 'PAID', 'FAILED', 'REFUNDED'] as $pst)
                            <option value="{{ $pst }}" {{ $order->payment_status === $pst ? 'selected' : '' }}>{{ $pst }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-sm btn-accent btn-block">Update Payment</button>
            </form>
        </div>

        <!-- Customer & Delivery Address -->
        <div class="card" style="padding: 1.5rem;">
            <h4 style="margin-bottom: 0.75rem;">Customer & Shipping</h4>
            <div style="font-size: 0.85rem; line-height: 1.6;">
                <strong>{{ $order->user->name }}</strong>
                <div style="color: var(--muted);">{{ $order->user->email }} &bull; {{ $shippingAddress['phone'] ?? $order->user->phone }}</div>

                <div style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid var(--border-light);">
                    <strong>Delivery Location:</strong>
                    <div>{{ $shippingAddress['address'] ?? 'No address provided' }}</div>
                    <div>{{ $shippingAddress['city'] ?? '' }} {{ $shippingAddress['district'] ?? '' }} {{ $shippingAddress['postal_code'] ?? '' }}</div>
                </div>

                @if($order->notes)
                    <div style="margin-top: 0.5rem; font-style: italic; color: var(--muted);">
                        "{{ $order->notes }}"
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 900px) {
    div[style*="grid-template-columns: 1fr 360px"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
