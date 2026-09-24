@extends('layouts.admin')

@section('title', 'Store Branches — WALKEN')
@section('page_title', 'Physical Store Lounges')

@section('content')
<div style="display: grid; grid-template-columns: 1fr 380px; gap: 2rem; align-items: start;">
    <!-- Branches List -->
    <div class="card" style="padding: 1.75rem;">
        <h3 style="font-size: 1.2rem; margin-bottom: 1.25rem;">Active Stores</h3>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Store Branch</th>
                        <th>Address & City</th>
                        <th>Operating Hours</th>
                        <th>Hourly Slot Capacity</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($branches as $b)
                        <tr>
                            <td>
                                <strong>{{ $b->name }}</strong>
                                <div style="font-size: 0.75rem; color: var(--muted);">Tel: {{ $b->phone }}</div>
                            </td>
                            <td>
                                <div style="font-size: 0.85rem;">{{ $b->address }}</div>
                                <div style="font-size: 0.75rem; color: var(--muted);">{{ $b->city }}</div>
                            </td>
                            <td>
                                {{ substr($b->opening_time, 0, 5) }} – {{ substr($b->closing_time, 0, 5) }}
                            </td>
                            <td>
                                <strong>{{ $b->slot_capacity }}</strong> visitors / hr
                            </td>
                            <td>
                                <span class="badge {{ $b->status ? 'badge-success' : 'badge-danger' }}">
                                    {{ $b->status ? 'Active' : 'Closed' }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('admin.branches.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Delete branch {{ $b->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" style="padding: 0.2rem 0.6rem; font-size: 0.75rem;" {{ $b->bookings_count > 0 ? 'disabled title="Cannot delete branch with bookings"' : '' }}>
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--muted); padding: 2rem;">No branches registered.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Branch Form -->
    <div class="card" style="padding: 1.75rem;">
        <h3 style="font-size: 1.15rem; margin-bottom: 1.25rem;">Add Store Branch</h3>

        <form action="{{ route('admin.branches.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Branch Name *</label>
                <input type="text" name="name" required class="form-input" placeholder="e.g. WALKEN South (PIM 2)">
            </div>

            <div class="form-group">
                <label class="form-label">Phone Hotline</label>
                <input type="text" name="phone" class="form-input" placeholder="021-75920000">
            </div>

            <div class="form-group">
                <label class="form-label">Address *</label>
                <textarea name="address" required class="form-textarea" placeholder="Full mall or street address..."></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">City *</label>
                <input type="text" name="city" required class="form-input" placeholder="e.g. Jakarta Selatan">
            </div>

            <div class="grid grid-cols-2" style="gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Opening Time</label>
                    <input type="time" name="opening_time" value="10:00" required class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Closing Time</label>
                    <input type="time" name="closing_time" value="22:00" required class="form-input">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Slot Capacity (Bookings / Hour)</label>
                <input type="number" name="slot_capacity" value="3" min="1" max="20" required class="form-input">
                <span class="form-hint">Prevents store overcrowding during try-on sessions.</span>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Add Store Branch</button>
        </form>
    </div>
</div>

<style>
@media (max-width: 900px) {
    div[style*="grid-template-columns: 1fr 380px"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
