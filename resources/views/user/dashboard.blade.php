@extends('layouts.app')

@section('title', 'Customer Dashboard — PAWMART')

@section('content')
<div class="container" style="padding: 2.5rem 1.25rem 4rem;">
    <!-- Welcome Banner -->
    <div style="background: var(--primary); color: white; border-radius: var(--radius-lg); padding: 2.5rem; margin-bottom: 2.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1.5rem;">
        <div>
            <span class="badge badge-accent" style="margin-bottom: 0.5rem;">CUSTOMER ACCOUNT</span>
            <h1 style="color: white; font-size: clamp(1.8rem, 3vw, 2.5rem);">Hello, {{ $user->name }}</h1>
            <p style="color: #A3A3A3; margin-top: 0.25rem;">Welcome to your personal PAWMART portal. Track orders, manage grooming appointments, and view saved pet supplies.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('user.bookings.create') }}" class="btn btn-accent">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="m9 16 2 2 4-4"/></svg>
                <span>Book Grooming</span>
            </a>
            <a href="{{ route('user.profile') }}" class="btn btn-outline-white">Profile Settings</a>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-3" style="gap: 1.5rem; margin-bottom: 2.5rem;">
        <a href="{{ route('user.orders.index') }}" class="card" style="padding: 1.5rem; text-decoration: none; border-top: 4px solid var(--primary);">
            <div class="flex items-center justify-between">
                <div>
                    <span style="font-size: 0.8rem; font-weight: 700; color: var(--muted); text-transform: uppercase;">Active Orders</span>
                    <h2 style="font-size: 2.25rem; margin-top: 0.25rem;">{{ $activeOrdersCount }}</h2>
                </div>
                <div style="width: 48px; height: 48px; background: var(--background); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                </div>
            </div>
            <div class="flex items-center gap-1" style="font-size: 0.85rem; color: var(--accent); font-weight: 700; margin-top: 1rem;">
                <span>View Order History</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </div>
        </a>

        <a href="{{ route('user.bookings.index') }}" class="card" style="padding: 1.5rem; text-decoration: none; border-top: 4px solid var(--accent);">
            <div class="flex items-center justify-between">
                <div>
                    <span style="font-size: 0.8rem; font-weight: 700; color: var(--muted); text-transform: uppercase;">Grooming Appointments</span>
                    <h2 style="font-size: 2.25rem; margin-top: 0.25rem;">{{ $upcomingBookingsCount }}</h2>
                </div>
                <div style="width: 48px; height: 48px; background: var(--accent-soft); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--accent);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="m9 16 2 2 4-4"/></svg>
                </div>
            </div>
            <div class="flex items-center gap-1" style="font-size: 0.85rem; color: var(--accent); font-weight: 700; margin-top: 1rem;">
                <span>Manage Appointments</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </div>
        </a>

        <a href="{{ route('user.wishlist') }}" class="card" style="padding: 1.5rem; text-decoration: none; border-top: 4px solid var(--danger);">
            <div class="flex items-center justify-between">
                <div>
                    <span style="font-size: 0.8rem; font-weight: 700; color: var(--muted); text-transform: uppercase;">Saved Wishlist</span>
                    <h2 style="font-size: 2.25rem; margin-top: 0.25rem;">{{ $wishlistCount }}</h2>
                </div>
                <div style="width: 48px; height: 48px; background: var(--danger-soft); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--danger);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                </div>
            </div>
            <div class="flex items-center gap-1" style="font-size: 0.85rem; color: var(--accent); font-weight: 700; margin-top: 1rem;">
                <span>View Wishlist</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </div>
        </a>
    </div>

    <!-- Active Orders & Upcoming Bookings Rows -->
    <div style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 2rem;">
        <!-- Recent Orders -->
        <div class="card" style="padding: 1.75rem;">
            <div class="flex items-center justify-between" style="margin-bottom: 1.5rem;">
                <h3>Recent Orders</h3>
                <a href="{{ route('user.orders.index') }}" style="font-size: 0.85rem; font-weight: 700; color: var(--accent);">All Orders &rarr;</a>
            </div>

            @if($recentOrders->count() > 0)
                <div class="flex flex-col gap-3">
                    @foreach($recentOrders as $order)
                        <div style="padding: 1rem; background: var(--background); border-radius: var(--radius-sm); border: 1px solid var(--border);">
                            <div class="flex items-center justify-between" style="margin-bottom: 0.5rem;">
                                <div>
                                    <strong style="font-size: 0.95rem;">#{{ $order->order_number }}</strong>
                                    <span style="font-size: 0.8rem; color: var(--muted); margin-left: 0.5rem;">{{ $order->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                <x-order-status :status="$order->order_status" />
                            </div>

                            <div style="font-size: 0.85rem; color: var(--muted); margin-bottom: 0.75rem;">
                                {{ $order->items->count() }} item(s) &bull; Total: <strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="badge {{ $order->payment_status === 'PAID' ? 'badge-success' : 'badge-warning' }}">
                                    Payment: {{ $order->payment_status }}
                                </span>
                                <a href="{{ route('user.orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <x-empty-state 
                    title="No Orders Yet" 
                    description="You haven't placed any pet supply orders yet."
                    actionText="Browse Pet Supplies"
                    actionUrl="{{ route('products.index') }}"
                />
            @endif
        </div>

        <!-- Upcoming Grooming Appointments -->
        <div class="card" style="padding: 1.75rem;">
            <div class="flex items-center justify-between" style="margin-bottom: 1.5rem;">
                <h3>Grooming Appointments</h3>
                <a href="{{ route('user.bookings.index') }}" style="font-size: 0.85rem; font-weight: 700; color: var(--accent);">All Appointments &rarr;</a>
            </div>

            @if($recentBookings->count() > 0)
                <div class="flex flex-col gap-3">
                    @foreach($recentBookings as $booking)
                        <div style="padding: 1rem; background: var(--background); border-radius: var(--radius-sm); border: 1px solid var(--border);">
                            <div class="flex items-center justify-between" style="margin-bottom: 0.5rem;">
                                <strong style="font-size: 0.9rem;">#{{ $booking->booking_number }}</strong>
                                <x-order-status :status="$booking->status" />
                            </div>

                            <div style="font-weight: 700; font-size: 0.95rem; color: var(--primary);">
                                {{ $booking->service->name ?? ($booking->groomingService->name ?? 'Grooming Service') }}
                            </div>

                            <div style="font-size: 0.85rem; color: var(--text); margin-top: 0.2rem;">
                                🐾 <strong>{{ $booking->pet_name }}</strong> ({{ ucfirst($booking->pet_type) }} &bull; {{ $booking->pet_breed ?? 'Standard' }})
                            </div>

                            <div style="font-size: 0.85rem; color: var(--muted); margin-top: 0.25rem;">
                                📍 {{ $booking->branch->name }}
                            </div>

                            <div style="font-size: 0.85rem; font-weight: 600; color: var(--text); margin-top: 0.5rem;">
                                🗓️ {{ $booking->booking_date->format('l, d M Y') }} &bull; ⏰ {{ $booking->booking_time }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <x-empty-state 
                    title="No Grooming Appointments" 
                    description="Reserve a professional pet grooming session for your beloved cat or dog."
                    actionText="Book Grooming Now"
                    actionUrl="{{ route('user.bookings.create') }}"
                />
            @endif
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
@endsection
