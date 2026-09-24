@extends('layouts.admin')

@section('title', 'Sales & Booking Reports — PAWMART')
@section('page_title', 'Analytics & Reports')

@section('content')
<!-- Top Row Analytics Cards -->
<div class="grid grid-cols-3" style="gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Monthly Sales Breakdown -->
    <div class="card" style="padding: 1.5rem;">
        <h3 style="font-size: 1.15rem; margin-bottom: 1rem;">Monthly Sales</h3>
        <div class="flex flex-col gap-3">
            @forelse($monthlySales as $ms)
                <div class="flex items-center justify-between" style="padding: 0.75rem; background: var(--background); border-radius: var(--radius-sm);">
                    <div>
                        <strong>{{ date('F Y', strtotime($ms->month . '-01')) }}</strong>
                        <div style="font-size: 0.75rem; color: var(--muted);">{{ $ms->total_orders }} paid order(s)</div>
                    </div>
                    <strong style="color: var(--primary);">
                        Rp {{ number_format($ms->revenue, 0, ',', '.') }}
                    </strong>
                </div>
            @empty
                <p style="color: var(--muted); font-size: 0.85rem;">No paid monthly transactions yet.</p>
            @endforelse
        </div>
    </div>

    <!-- Orders by Status -->
    <div class="card" style="padding: 1.5rem;">
        <h3 style="font-size: 1.15rem; margin-bottom: 1rem;">Order Status Distribution</h3>
        <div class="flex flex-col gap-2">
            @foreach($ordersByStatus as $obs)
                <div class="flex items-center justify-between" style="padding: 0.5rem 0; border-bottom: 1px solid var(--border-light);">
                    <x-order-status :status="$obs->order_status" />
                    <strong>{{ $obs->count }}</strong>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Bookings by Branch -->
    <div class="card" style="padding: 1.5rem;">
        <h3 style="font-size: 1.15rem; margin-bottom: 1rem;">Try On Sessions by Store</h3>
        <div class="flex flex-col gap-3">
            @foreach($bookingsByBranch as $bbb)
                <div class="flex items-center justify-between" style="padding: 0.75rem; background: var(--background); border-radius: var(--radius-sm);">
                    <div>
                        <strong style="font-size: 0.9rem;">{{ $bbb->branch->name ?? 'Online' }}</strong>
                        <div style="font-size: 0.75rem; color: var(--muted);">{{ $bbb->branch->city ?? '' }}</div>
                    </div>
                    <span class="badge badge-accent" style="font-size: 0.85rem;">
                        {{ $bbb->count }} visits
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Top Selling Footwear Table -->
<div class="card" style="padding: 1.75rem;">
    <h3 style="font-size: 1.2rem; margin-bottom: 1.25rem;">Best Performing Footwear</h3>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Footwear Model</th>
                    <th>Units Sold</th>
                    <th>Gross Revenue</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topSelling as $ts)
                    <tr>
                        <td>
                            <strong>{{ $ts->product_name }}</strong>
                        </td>
                        <td>
                            <strong>{{ $ts->total_qty }}</strong> pairs
                        </td>
                        <td>
                            <strong style="color: var(--primary);">Rp {{ number_format($ts->total_revenue, 0, ',', '.') }}</strong>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: var(--muted); padding: 2rem;">No sales data available yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
