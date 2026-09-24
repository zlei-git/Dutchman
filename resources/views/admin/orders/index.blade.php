@extends('layouts.admin')

@section('title', 'Order Management — WALKEN')
@section('page_title', 'Customer Orders')

@section('content')
<div class="card" style="padding: 1.75rem;">
    <!-- Filters & Search -->
    <div class="flex items-center justify-between flex-wrap gap-3" style="margin-bottom: 1.5rem;">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex items-center gap-2 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Order # or Customer..." class="form-input" style="min-height: 40px; width: 220px;">
            <select name="order_status" class="form-select" style="min-height: 40px; width: 160px;" onchange="this.form.submit();">
                <option value="">All Order Status</option>
                <option value="PENDING" {{ request('order_status') === 'PENDING' ? 'selected' : '' }}>PENDING</option>
                <option value="CONFIRMED" {{ request('order_status') === 'CONFIRMED' ? 'selected' : '' }}>CONFIRMED</option>
                <option value="PROCESSING" {{ request('order_status') === 'PROCESSING' ? 'selected' : '' }}>PROCESSING</option>
                <option value="SHIPPED" {{ request('order_status') === 'SHIPPED' ? 'selected' : '' }}>SHIPPED</option>
                <option value="COMPLETED" {{ request('order_status') === 'COMPLETED' ? 'selected' : '' }}>COMPLETED</option>
                <option value="CANCELLED" {{ request('order_status') === 'CANCELLED' ? 'selected' : '' }}>CANCELLED</option>
            </select>
            <select name="payment_status" class="form-select" style="min-height: 40px; width: 160px;" onchange="this.form.submit();">
                <option value="">All Payments</option>
                <option value="UNPAID" {{ request('payment_status') === 'UNPAID' ? 'selected' : '' }}>UNPAID</option>
                <option value="PENDING" {{ request('payment_status') === 'PENDING' ? 'selected' : '' }}>PENDING</option>
                <option value="PAID" {{ request('payment_status') === 'PAID' ? 'selected' : '' }}>PAID</option>
                <option value="FAILED" {{ request('payment_status') === 'FAILED' ? 'selected' : '' }}>FAILED</option>
            </select>
            <button type="submit" class="btn btn-sm btn-secondary">Filter</button>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Type & Shipping</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Order Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>
                            <strong>#{{ $order->order_number }}</strong>
                        </td>
                        <td style="font-size: 0.8rem; color: var(--muted);">
                            {{ $order->created_at->format('d M Y, H:i') }}
                        </td>
                        <td>
                            <div style="font-weight: 700;">{{ $order->user->name }}</div>
                            <div style="font-size: 0.75rem; color: var(--muted);">{{ $order->user->email }}</div>
                        </td>
                        <td>
                            <span class="badge badge-muted">{{ $order->order_type }}</span>
                            <div style="font-size: 0.75rem; color: var(--muted); margin-top: 0.2rem;">{{ $order->shipping_method }}</div>
                        </td>
                        <td>
                            <strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
                            <div style="font-size: 0.75rem; color: var(--muted);">{{ $order->items->count() }} item(s)</div>
                        </td>
                        <td>
                            <span class="badge {{ $order->payment_status === 'PAID' ? 'badge-success' : 'badge-warning' }}">
                                {{ $order->payment_status }}
                            </span>
                            <div style="font-size: 0.75rem; color: var(--muted); margin-top: 0.2rem;">{{ $order->payment_method }}</div>
                        </td>
                        <td>
                            <x-order-status :status="$order->order_status" />
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary" style="padding: 0.3rem 0.75rem; font-size: 0.8rem;">
                                Manage
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--muted); padding: 3rem;">
                            No orders found matching the filter criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $orders->links() }}
    </div>
</div>
@endsection
