@extends('layouts.app')

@section('title', 'My Orders — PAWMART')

@section('content')
<div class="container" style="padding: 2.5rem 1.25rem 4rem;">
    <!-- Header -->
    <div style="margin-bottom: 2rem;">
        <div class="flex items-center gap-2" style="font-size: 0.85rem; color: var(--muted); margin-bottom: 0.5rem;">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <a href="{{ route('user.dashboard') }}">Dashboard</a>
            <span>/</span>
            <span style="color: var(--text); font-weight: 600;">Orders</span>
        </div>
        <h1>Order History</h1>
        <p style="color: var(--muted); margin-top: 0.25rem;">Track recent deliveries, view past purchases, and initiate reorders.</p>
    </div>

    <!-- Status Filters -->
    <div class="flex gap-2 flex-wrap" style="margin-bottom: 2rem;">
        <a href="{{ route('user.orders.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-secondary' }}">
            All ({{ auth()->user()->orders()->count() }})
        </a>
        <a href="{{ route('user.orders.index', ['status' => 'PENDING']) }}" class="btn btn-sm {{ request('status') === 'PENDING' ? 'btn-primary' : 'btn-secondary' }}">
            Pending Payment
        </a>
        <a href="{{ route('user.orders.index', ['status' => 'CONFIRMED']) }}" class="btn btn-sm {{ request('status') === 'CONFIRMED' ? 'btn-primary' : 'btn-secondary' }}">
            Confirmed
        </a>
        <a href="{{ route('user.orders.index', ['status' => 'SHIPPED']) }}" class="btn btn-sm {{ request('status') === 'SHIPPED' ? 'btn-primary' : 'btn-secondary' }}">
            Shipped
        </a>
        <a href="{{ route('user.orders.index', ['status' => 'COMPLETED']) }}" class="btn btn-sm {{ request('status') === 'COMPLETED' ? 'btn-primary' : 'btn-secondary' }}">
            Completed
        </a>
    </div>

    @if($orders->count() > 0)
        <div class="flex flex-col gap-4">
            @foreach($orders as $order)
                <div class="card" style="padding: 1.5rem;">
                    <div class="flex items-center justify-between flex-wrap gap-3" style="padding-bottom: 1rem; border-bottom: 1px solid var(--border); margin-bottom: 1.25rem;">
                        <div>
                            <span style="font-size: 0.8rem; color: var(--muted);">Order Reference</span>
                            <h3 style="font-size: 1.15rem; color: var(--primary);">#{{ $order->order_number }}</h3>
                        </div>

                        <div class="flex items-center gap-3">
                            <span style="font-size: 0.85rem; color: var(--muted);">{{ $order->created_at->format('d M Y, H:i') }}</span>
                            <x-order-status :status="$order->order_status" />
                        </div>
                    </div>

                    <!-- Items snapshot -->
                    <div class="flex flex-col gap-3" style="margin-bottom: 1.25rem;">
                        @foreach($order->items as $item)
                            <div class="flex items-center gap-3">
                                <img src="{{ $item->product->image_url ?? asset('images/products/placeholder.svg') }}" alt="{{ $item->product_name }}" style="width: 50px; height: 42px; object-fit: cover; border-radius: var(--radius-sm); background: #f0f0f0;">
                                <div style="flex: 1;">
                                    <strong>{{ $item->product_name }}</strong>
                                    <div style="font-size: 0.8rem; color: var(--muted);">
                                        {{ $item->size }} @if($item->color) &bull; {{ $item->color }} @endif &bull; Qty: {{ $item->quantity }}
                                    </div>
                                </div>
                                <div style="font-weight: 700;">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex items-center justify-between flex-wrap gap-3" style="padding-top: 1rem; border-top: 1px solid var(--border-light);">
                        <div class="flex items-center gap-2">
                            <span class="badge {{ $order->payment_status === 'PAID' ? 'badge-success' : 'badge-warning' }}">
                                Payment: {{ $order->payment_status }}
                            </span>
                            <span style="font-size: 0.85rem; color: var(--muted);">Via {{ $order->payment_method }}</span>
                        </div>

                        <div class="flex items-center gap-4">
                            <div style="text-align: right;">
                                <span style="font-size: 0.8rem; color: var(--muted);">Total Amount</span>
                                <div style="font-family: var(--font-heading); font-size: 1.2rem; font-weight: 800; color: var(--primary);">
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                </div>
                            </div>

                            <a href="{{ route('user.orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                                <span>Track Order</span>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 2rem;">
            {{ $orders->links() }}
        </div>
    @else
        <div class="card">
            <x-empty-state 
                title="No Orders Found"
                description="You have no recorded orders in this status category."
                actionText="Explore Footwear"
                actionUrl="{{ route('products.index') }}"
            />
        </div>
    @endif
</div>
@endsection
