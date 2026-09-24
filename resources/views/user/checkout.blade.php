@extends('layouts.app')
@section('title', 'Checkout — PAWMART')

@section('content')
<div class="container" style="padding: 2.5rem 1.25rem 4rem;">
    <!-- Header -->
    <div style="margin-bottom: 2rem;">
        <div class="flex items-center gap-2" style="font-size: 0.85rem; color: var(--muted); margin-bottom: 0.5rem;">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <a href="{{ route('user.cart') }}">Cart</a>
            <span>/</span>
            <span style="color: var(--text); font-weight: 600;">Checkout</span>
        </div>
        <h1>Checkout Order</h1>
    </div>

    <form action="{{ route('user.checkout.process') }}" method="POST" id="checkoutForm">
        @csrf

        <div style="display: grid; grid-template-columns: 1fr 380px; gap: 2.5rem; align-items: start;">
            <!-- Left: Checkout Details (Address, Shipping, Payment) -->
            <div class="flex flex-col gap-6">
                <!-- 1. Customer Information -->
                <div class="card" style="padding: 1.75rem;">
                    <div class="flex items-center gap-2" style="margin-bottom: 1.25rem;">
                        <span style="width: 28px; height: 28px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem;">1</span>
                        <h3 style="font-size: 1.2rem;">Customer Information</h3>
                    </div>

                    <div class="grid grid-cols-2" style="gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="recipient_name" value="{{ old('recipient_name', $defaultAddress->recipient_name ?? $user->name) }}" required class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $defaultAddress->phone ?? $user->phone) }}" required class="form-input" placeholder="081234567890">
                        </div>
                    </div>
                </div>

                <!-- 2. Shipping Address -->
                <div class="card" style="padding: 1.75rem;">
                    <div class="flex items-center gap-2" style="margin-bottom: 1.25rem;">
                        <span style="width: 28px; height: 28px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem;">2</span>
                        <h3 style="font-size: 1.2rem;">Delivery Address</h3>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Street Address / Building / Unit</label>
                        <textarea name="address" required class="form-textarea" placeholder="Complete address including street, unit or RT/RW">{{ old('address', $defaultAddress->address ?? '') }}</textarea>
                    </div>

                    <div class="grid grid-cols-3" style="gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label">City / Regency</label>
                            <input type="text" name="city" value="{{ old('city', $defaultAddress->city ?? 'Jakarta Selatan') }}" required class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">District (Kecamatan)</label>
                            <input type="text" name="district" value="{{ old('district', $defaultAddress->district ?? '') }}" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Postal Code</label>
                            <input type="text" name="postal_code" value="{{ old('postal_code', $defaultAddress->postal_code ?? '') }}" class="form-input">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Delivery Notes (Optional)</label>
                        <input type="text" name="notes" value="{{ old('notes') }}" class="form-input" placeholder="e.g. Leave with security, ring doorbell">
                    </div>
                </div>

                <!-- 3. Shipping Method -->
                <div class="card" style="padding: 1.75rem;">
                    <div class="flex items-center gap-2" style="margin-bottom: 1.25rem;">
                        <span style="width: 28px; height: 28px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem;">3</span>
                        <h3 style="font-size: 1.2rem;">Shipping Method</h3>
                    </div>

                    <div class="flex flex-col gap-3">
                        <label class="card" style="padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between; cursor: pointer; border: 2px solid var(--primary);">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="shipping_method" value="Regular" checked onchange="updateShipping(25000, 'Regular')">
                                <div>
                                    <strong>Regular Delivery (2 - 4 business days)</strong>
                                    <div style="font-size: 0.8rem; color: var(--muted);">Standard secure door-to-door courier service</div>
                                </div>
                            </div>
                            <strong style="color: var(--primary);">
                                @if($summary['subtotal'] >= 1000000)
                                    <span style="color: var(--success);">FREE</span>
                                @else
                                    Rp 25.000
                                @endif
                            </strong>
                        </label>

                        <label class="card" style="padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between; cursor: pointer;">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="shipping_method" value="Express" onchange="updateShipping(45000, 'Express')">
                                <div>
                                    <strong>Express 1-Day Delivery (Priority Courier)</strong>
                                    <div style="font-size: 0.8rem; color: var(--muted);">Guaranteed next-day delivery across Jabodetabek</div>
                                </div>
                            </div>
                            <strong style="color: var(--primary);">Rp 45.000</strong>
                        </label>

                        <label class="card" style="padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between; cursor: pointer;">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="shipping_method" value="Store Pickup" onchange="updateShipping(0, 'Store Pickup')">
                                <div>
                                    <strong>Store Pickup (Click & Collect)</strong>
                                    <div style="font-size: 0.8rem; color: var(--muted);">Collect ready pet supplies from your chosen PAWMART branch store</div>
                                </div>
                            </div>
                            <strong style="color: var(--success);">FREE</strong>
                        </label>

                        <!-- Branch selector if Store Pickup chosen -->
                        <div id="branchSelectWrap" style="display: none; margin-top: 0.5rem;">
                            <label class="form-label">Select Pickup Store Branch</label>
                            <select name="branch_id" class="form-select">
                                @foreach($branches as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }} ({{ $b->city }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- 4. Payment Method -->
                <div class="card" style="padding: 1.75rem;">
                    <div class="flex items-center justify-between" style="margin-bottom: 1.25rem;">
                        <div class="flex items-center gap-2">
                            <span style="width: 28px; height: 28px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem;">4</span>
                            <h3 style="font-size: 1.2rem;">Payment Method</h3>
                        </div>
                        <span class="badge badge-accent">Midtrans Ready</span>
                    </div>

                    <div class="alert alert-info" style="font-size: 0.85rem; margin-bottom: 1.25rem;">
                        <span>ℹ️ PAWMART integrates Midtrans Snap Gateway (VA, GoPay, QRIS, Credit Card) with instant order status updates and automated payment verification.</span>
                    </div>

                    <div class="flex flex-col gap-3">
                        <label class="card" style="padding: 1rem 1.25rem; display: flex; align-items: center; gap: 1rem; cursor: pointer; border: 2px solid var(--accent); background: #FFF8F5;">
                            <input type="radio" name="payment_method" value="Midtrans Snap" checked>
                            <div style="flex: 1;">
                                <div class="flex items-center gap-2">
                                    <strong>Midtrans Snap (Online Payment)</strong>
                                    <span class="badge badge-accent" style="font-size: 0.7rem;">RECOMMENDED</span>
                                </div>
                                <div style="font-size: 0.8rem; color: var(--muted);">BCA, Mandiri, BNI, BRI, GoPay, ShopeePay, QRIS, Credit Card</div>
                            </div>
                        </label>

                        <label class="card" style="padding: 1rem 1.25rem; display: flex; align-items: center; gap: 1rem; cursor: pointer;">
                            <input type="radio" name="payment_method" value="Bank Transfer">
                            <div>
                                <strong>Bank Transfer (BCA Virtual Account Demo)</strong>
                                <div style="font-size: 0.8rem; color: var(--muted);">Automatic verification via 16-digit simulated VA</div>
                            </div>
                        </label>

                        <label class="card" style="padding: 1rem 1.25rem; display: flex; align-items: center; gap: 1rem; cursor: pointer;">
                            <input type="radio" name="payment_method" value="QRIS Demo">
                            <div>
                                <strong>QRIS Instant Demo</strong>
                                <div style="font-size: 0.8rem; color: var(--muted);">Scan dynamic QR with any Indonesian banking or e-wallet app</div>
                            </div>
                        </label>

                        <label class="card" style="padding: 1rem 1.25rem; display: flex; align-items: center; gap: 1rem; cursor: pointer;">
                            <input type="radio" name="payment_method" value="E-Wallet Demo">
                            <div>
                                <strong>E-Wallet Demo (GoPay / OVO / DANA / ShopeePay)</strong>
                                <div style="font-size: 0.8rem; color: var(--muted);">Direct push notification simulation</div>
                            </div>
                        </label>

                        <label class="card" style="padding: 1rem 1.25rem; display: flex; align-items: center; gap: 1rem; cursor: pointer;">
                            <input type="radio" name="payment_method" value="Cash on Delivery">
                            <div>
                                <strong>Cash on Delivery (COD)</strong>
                                <div style="font-size: 0.8rem; color: var(--muted);">Pay in cash directly to the courier upon pet package arrival</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Right: Order Summary Sidebar -->
            <div class="card" style="padding: 1.75rem; position: sticky; top: 96px;">
                <h3 style="font-size: 1.25rem; margin-bottom: 1.25rem;">Order Summary</h3>

                <!-- Cart Items Snapshot -->
                <div class="flex flex-col gap-3" style="max-height: 240px; overflow-y: auto; margin-bottom: 1.25rem; padding-right: 0.5rem;">
                    @foreach($summary['items'] as $item)
                        <div class="flex items-center gap-3">
                            <img src="{{ $item['image'] }}" alt="{{ $item['product_name'] }}" style="width: 50px; height: 42px; object-fit: cover; border-radius: var(--radius-sm); background: #f0f0f0;">
                            <div style="flex: 1; font-size: 0.85rem;">
                                <div style="font-weight: 700; color: var(--primary);">{{ $item['product_name'] }}</div>
                                <div style="color: var(--muted); font-size: 0.75rem;">Size {{ $item['size'] }} &bull; Qty {{ $item['quantity'] }}</div>
                            </div>
                            <div style="font-weight: 700; font-size: 0.9rem;">
                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex flex-col gap-2" style="font-size: 0.9rem; border-top: 1px solid var(--border); padding-top: 1.25rem; margin-bottom: 1.25rem;">
                    <div class="flex justify-between">
                        <span style="color: var(--muted);">Subtotal</span>
                        <strong>Rp {{ number_format($summary['subtotal'], 0, ',', '.') }}</strong>
                    </div>

                    @if($summary['discount'] > 0)
                        <div class="flex justify-between" style="color: var(--success);">
                            <span>Discount ({{ $summary['promo_code'] }})</span>
                            <strong>- Rp {{ number_format($summary['discount'], 0, ',', '.') }}</strong>
                        </div>
                    @endif

                    <div class="flex justify-between">
                        <span style="color: var(--muted);">Shipping Fee</span>
                        <strong id="displayShippingFee">Rp {{ number_format($summary['shipping_fee'], 0, ',', '.') }}</strong>
                    </div>
                </div>

                <div class="flex justify-between items-baseline" style="border-top: 2px solid var(--primary); padding-top: 1.25rem; margin-bottom: 1.5rem;">
                    <span style="font-size: 1.1rem; font-weight: 800;">Grand Total</span>
                    <span id="displayGrandTotal" style="font-family: var(--font-heading); font-size: 1.5rem; font-weight: 900; color: var(--primary);">
                        Rp {{ number_format($summary['total'], 0, ',', '.') }}
                    </span>
                </div>

                <button type="submit" class="btn btn-accent btn-lg btn-block">
                    <span>Place Order</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </button>

                <p style="font-size: 0.75rem; color: var(--muted); text-align: center; margin-top: 1rem;">
                    🔒 All prices are securely validated on the server. Your transaction is encrypted.
                </p>
            </div>
        </div>
    </form>
</div>

<script>
let rawSubtotal = {{ (float)$summary['subtotal'] }};
let rawDiscount = {{ (float)$summary['discount'] }};

function updateShipping(fee, method) {
    if (rawSubtotal >= 1000000 && method === 'Regular') {
        fee = 0;
    }

    const branchWrap = document.getElementById('branchSelectWrap');
    if (branchWrap) {
        branchWrap.style.display = (method === 'Store Pickup') ? 'block' : 'none';
    }

    const total = Math.max(0, rawSubtotal - rawDiscount + fee);

    const shipElem = document.getElementById('displayShippingFee');
    if (shipElem) {
        shipElem.textContent = (fee === 0) ? 'FREE' : 'Rp ' + fee.toLocaleString('id-ID');
    }

    const totalElem = document.getElementById('displayGrandTotal');
    if (totalElem) {
        totalElem.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }
}
</script>

<style>
@media (max-width: 900px) {
    div[style*="grid-template-columns: 1fr 380px"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
