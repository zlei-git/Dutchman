@extends('layouts.app')
@section('title', 'Order #' . $order->order_number . ' — PAWMART')

@section('content')
<div class="container" style="padding: 2.5rem 1.25rem 4rem;">
    <!-- Header -->
    <div style="margin-bottom: 2rem;">
        <div class="flex items-center gap-2" style="font-size: 0.85rem; color: var(--muted); margin-bottom: 0.5rem;">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <a href="{{ route('user.orders.index') }}">Orders</a>
            <span>/</span>
            <span style="color: var(--text); font-weight: 600;">#{{ $order->order_number }}</span>
        </div>
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1>Order #{{ $order->order_number }}</h1>
                <p style="color: var(--muted); margin-top: 0.25rem;">Placed on {{ $order->created_at->format('l, d F Y \a\t H:i') }} WIB</p>
            </div>
            <div class="flex items-center gap-2">
                <x-order-status :status="$order->order_status" />
                <span class="badge {{ $order->payment_status === 'PAID' ? 'badge-success' : 'badge-warning' }}">
                    Payment: {{ $order->payment_status }}
                </span>
            </div>
        </div>
    </div>

    <!-- Order Lifecycle Progress Bar -->
    @php
        $statuses = ['PENDING', 'CONFIRMED', 'PROCESSING', 'SHIPPED', 'COMPLETED'];
        $currentIdx = array_search($order->order_status, $statuses);
        if ($currentIdx === false && $order->order_status === 'CANCELLED') {
            $currentIdx = -1;
        }
    @endphp

    <div class="card" style="padding: 2rem; margin-bottom: 2.5rem;">
        <h4 style="margin-bottom: 1.5rem;">Order Delivery Status</h4>

        @if($order->order_status === 'CANCELLED')
            <div class="alert alert-danger" style="margin-bottom: 0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                <span>This order was cancelled. Reserved items have been returned to warehouse stock.</span>
            </div>
        @else
            <div class="flex items-center justify-between" style="position: relative; max-width: 800px; margin: 0 auto;">
                <!-- Track Line -->
                <div style="position: absolute; top: 16px; left: 20px; right: 20px; height: 4px; background: var(--border); z-index: 1;"></div>
                <div style="position: absolute; top: 16px; left: 20px; width: {{ max(0, $currentIdx) * 25 }}%; height: 4px; background: var(--success); z-index: 2; transition: width 0.4s ease;"></div>

                @foreach($statuses as $idx => $step)
                    <div style="position: relative; z-index: 3; text-align: center; width: 80px;">
                        <div style="width: 34px; height: 34px; border-radius: 50%; margin: 0 auto; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.8rem; background: {{ $idx <= $currentIdx ? 'var(--success)' : 'var(--surface)' }}; color: {{ $idx <= $currentIdx ? 'white' : 'var(--muted)' }}; border: 2px solid {{ $idx <= $currentIdx ? 'var(--success)' : 'var(--border)' }};">
                            @if($idx < $currentIdx)
                                ✓
                            @else
                                {{ $idx + 1 }}
                            @endif
                        </div>
                        <span style="font-size: 0.75rem; font-weight: 700; display: block; margin-top: 0.5rem; color: {{ $idx <= $currentIdx ? 'var(--primary)' : 'var(--muted)' }};">
                            {{ ucfirst(strtolower($step)) }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Main Grid: Items & Payment / Shipping -->
    <div style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 2rem; align-items: start;">
        <!-- Left: Items Ordered -->
        <div class="card" style="padding: 1.75rem;">
            <h3 style="font-size: 1.2rem; margin-bottom: 1.25rem;">Purchased Items</h3>

            <div class="flex flex-col gap-4">
                @foreach($order->items as $item)
                    <div class="flex items-center gap-4 flex-wrap" style="padding-bottom: 1rem; border-bottom: 1px solid var(--border-light);">
                        <div style="width: 80px; height: 65px; background: #f0f0f0; border-radius: var(--radius-sm); overflow: hidden; flex-shrink: 0;">
                            <img src="{{ $item->product->image_url ?? asset('images/products/placeholder.svg') }}" alt="{{ $item->product_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>

                        <div style="flex: 1; min-width: 180px;">
                            <a href="{{ route('products.show', $item->product->slug ?? $item->product_id) }}" style="font-weight: 800; font-size: 1rem; color: var(--primary);">
                                {{ $item->product_name }}
                            </a>
                            <div style="font-size: 0.85rem; color: var(--muted); margin-top: 0.25rem;">
                                {{ $item->size }} @if($item->color) &bull; {{ $item->color }} @endif &bull; {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                            </div>
                        </div>

                        <div style="font-family: var(--font-heading); font-weight: 800; font-size: 1.1rem; color: var(--primary);">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Total Breakdown -->
            <div class="flex flex-col gap-2" style="margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid var(--border); font-size: 0.95rem;">
                <div class="flex justify-between">
                    <span style="color: var(--muted);">Subtotal</span>
                    <strong>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</strong>
                </div>
                @if($order->discount > 0)
                    <div class="flex justify-between" style="color: var(--success);">
                        <span>Voucher Discount ({{ $order->promo_code }})</span>
                        <strong>- Rp {{ number_format($order->discount, 0, ',', '.') }}</strong>
                    </div>
                @endif
                <div class="flex justify-between">
                    <span style="color: var(--muted);">Shipping Fee ({{ $order->shipping_method }})</span>
                    <strong>Rp {{ number_format($order->shipping_fee, 0, ',', '.') }}</strong>
                </div>
                <div class="flex justify-between items-baseline" style="border-top: 2px solid var(--primary); padding-top: 1rem; margin-top: 0.5rem;">
                    <strong style="font-size: 1.15rem;">Grand Total</strong>
                    <strong style="font-family: var(--font-heading); font-size: 1.4rem; color: var(--primary);">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </strong>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3" style="margin-top: 2rem;">
                <form action="{{ route('user.orders.reorder', $order->id) }}" method="POST" style="flex: 1;">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-block">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                        <span>Reorder Items</span>
                    </button>
                </form>

                @if(in_array($order->order_status, ['PENDING', 'CONFIRMED']))
                    <form action="{{ route('user.orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                        @csrf
                        <button type="submit" class="btn btn-outline" style="color: var(--danger); border-color: var(--danger);">
                            Cancel Order
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Right: Shipping & Payment Summary -->
        <div class="flex flex-col gap-4">
            <!-- Payment Simulator & Receipt -->
            <div class="card" style="padding: 1.75rem; border-top: 4px solid var(--accent);">
                <div class="flex items-center justify-between" style="margin-bottom: 1rem;">
                    <h4 style="font-size: 1.1rem;">Payment Method</h4>
                    <span class="badge {{ $order->payment_status === 'PAID' ? 'badge-success' : 'badge-warning' }}">
                        {{ $order->payment_status }}
                    </span>
                </div>

                <div style="font-size: 0.9rem; margin-bottom: 1.25rem;">
                    <strong>{{ $order->payment_method }}</strong>
                    <div style="color: var(--muted); font-size: 0.8rem; margin-top: 0.2rem;">
                        Reference: {{ $order->payment->payment_reference ?? 'N/A' }}
                    </div>
                </div>

                <!-- Instructions based on payment method -->
                <div style="background: var(--background); padding: 1rem; border-radius: var(--radius-sm); font-size: 0.85rem; margin-bottom: 1.25rem;">
                    @if($order->payment_method === 'Midtrans Snap')
                        <div><strong>Gateway:</strong> Midtrans Snap Payment</div>
                        <div style="color: var(--muted); margin-top: 0.25rem;">Supports BCA/Mandiri/BNI/BRI VA, GoPay, ShopeePay, QRIS, & Credit Card.</div>
                        @if($order->snap_token)
                            <div style="font-size: 0.75rem; color: var(--muted); margin-top: 0.25rem; font-family: monospace;">Token: {{ substr($order->snap_token, 0, 16) }}...</div>
                        @endif
                    @elseif($order->payment_method === 'Bank Transfer')
                        <div><strong>Bank:</strong> BCA Virtual Account</div>
                        <div style="font-family: monospace; font-size: 1rem; font-weight: 800; color: var(--primary); margin: 0.35rem 0;">
                            8271 9002 4819 0122
                        </div>
                        <div style="color: var(--muted);">A/N PT PAWMART INDONESIA</div>
                    @elseif($order->payment_method === 'QRIS Demo')
                        <div style="text-align: center; padding: 0.5rem 0;">
                            <div style="font-weight: 700; margin-bottom: 0.5rem;">Scan QRIS Demo</div>
                            <div style="display: inline-block; background: white; padding: 0.75rem; border: 1px solid var(--border); border-radius: var(--radius-sm);">
                                <svg width="120" height="120" viewBox="0 0 24 24" fill="var(--primary)"><path d="M3 3h7v7H3zm2 2v3h3V5zm8-2h7v7h-7zm2 2v3h3V5zM3 13h7v7H3zm2 2v3h3v-3zm11 1h2v2h-2zm-3-3h2v2h-2zm4 4h2v2h-2zm-2-2h2v2h-2z"/></svg>
                            </div>
                            <div style="font-size: 0.75rem; color: var(--muted); margin-top: 0.35rem;">NMID: ID1020084920481</div>
                        </div>
                    @elseif($order->payment_method === 'E-Wallet Demo')
                        <div><strong>E-Wallet:</strong> GoPay / OVO / DANA</div>
                        <div>Account: 0812-9988-7766 (PAWMART Official)</div>
                    @else
                        <div><strong>Cash on Delivery (COD):</strong> Please prepare exact cash upon pet package arrival.</div>
                    @endif
                </div>

                <!-- Midtrans Snap Trigger Button & Simulation Fallback -->
                @if($order->payment_status !== 'PAID' && $order->order_status !== 'CANCELLED')
                    @if($order->snap_token)
                        <button type="button" id="pay-button" class="btn btn-accent btn-block" style="margin-bottom: 0.75rem;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                            <span>Pay with Midtrans Snap</span>
                        </button>
                    @endif

                    <form action="{{ route('user.orders.pay', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-block" style="font-size: 0.85rem;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>Simulate Instant Payment (Demo)</span>
                        </button>
                    </form>
                    <p style="font-size: 0.75rem; color: var(--muted); text-align: center; margin-top: 0.5rem;">
                        Click above for sandbox/prototype test confirmation.
                    </p>
                @else
                    <div style="color: var(--success); font-weight: 700; font-size: 0.85rem; text-align: center;">
                        ✓ Payment confirmed on {{ $order->paid_at ? $order->paid_at->format('d M Y, H:i') : 'Recorded' }}
                    </div>
                @endif
            </div>

            <!-- Delivery Address -->
            <div class="card" style="padding: 1.75rem;">
                <h4 style="font-size: 1.1rem; margin-bottom: 1rem;">
                    {{ $order->order_type === 'PICKUP' ? 'Store Pickup Location' : 'Delivery Address' }}
                </h4>

                @if($order->order_type === 'PICKUP' && $order->branch)
                    <div style="font-size: 0.9rem; line-height: 1.6;">
                        <strong>{{ $order->branch->name }}</strong>
                        <div style="color: var(--muted); margin-top: 0.25rem;">{{ $order->branch->address }}, {{ $order->branch->city }}</div>
                        <div style="color: var(--muted); font-size: 0.8rem;">Phone: {{ $order->branch->phone }}</div>
                    </div>
                @else
                    <div style="font-size: 0.9rem; line-height: 1.6;">
                        <strong>{{ $shippingAddress['recipient_name'] ?? $order->user->name }}</strong>
                        <div style="color: var(--muted);">{{ $shippingAddress['phone'] ?? $order->user->phone }}</div>
                        <div style="margin-top: 0.5rem; color: var(--text);">
                            {{ $shippingAddress['address'] ?? 'No address provided' }}
                        </div>
                        <div style="color: var(--muted); font-size: 0.85rem;">
                            {{ $shippingAddress['city'] ?? '' }} {{ $shippingAddress['district'] ? ', ' . $shippingAddress['district'] : '' }} {{ $shippingAddress['postal_code'] ?? '' }}
                        </div>
                        @if($order->notes)
                            <div style="margin-top: 0.75rem; padding: 0.5rem; background: var(--background); border-radius: var(--radius-sm); font-size: 0.8rem;">
                                <strong>Notes:</strong> {{ $order->notes }}
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 900px) {
    div[style*="grid-template-columns: 1.2fr 0.8fr"] {
        grid-template-columns: 1fr !important;
    }
}
</style>

@if($order->snap_token && $order->payment_status !== 'PAID')
<script src="{{ $snapJsUrl }}" data-client-key="{{ $snapClientKey }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const payBtn = document.getElementById('pay-button');
    if (payBtn) {
        payBtn.addEventListener('click', function() {
            window.snap.pay('{{ $order->snap_token }}', {
                onSuccess: function(result) {
                    window.location.reload();
                },
                onPending: function(result) {
                    window.location.reload();
                },
                onError: function(result) {
                    alert('Pembayaran gagal atau dibatalkan.');
                },
                onClose: function() {
                    console.log('Customer closed popup without finishing payment');
                }
            });
        });
    }
});
</script>
@endif
@endsection
