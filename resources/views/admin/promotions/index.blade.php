@extends('layouts.admin')

@section('title', 'Promotions & Vouchers — PAWMART')
@section('page_title', 'Promo Codes & Discount Vouchers')

@section('content')
<div style="display: grid; grid-template-columns: 1fr 360px; gap: 2rem; align-items: start;">
    <!-- Promotions List -->
    <div class="card" style="padding: 1.75rem;">
        <h3 style="font-size: 1.2rem; margin-bottom: 1.25rem;">Active Promotional Vouchers</h3>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Type & Discount</th>
                        <th>Min. Spend</th>
                        <th>Usage / Quota</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promotions as $promo)
                        <tr>
                            <td>
                                <strong>{{ $promo->code }}</strong>
                                <div style="font-size: 0.75rem; color: var(--muted);">{{ $promo->name }}</div>
                            </td>
                            <td>
                                @if($promo->type === 'PERCENTAGE')
                                    <strong>{{ (int)$promo->value }}% OFF</strong>
                                    @if($promo->max_discount)
                                        <div style="font-size: 0.75rem; color: var(--muted);">Max: Rp {{ number_format($promo->max_discount, 0, ',', '.') }}</div>
                                    @endif
                                @else
                                    <strong>Rp {{ number_format($promo->value, 0, ',', '.') }} OFF</strong>
                                @endif
                            </td>
                            <td>
                                Rp {{ number_format($promo->min_spend, 0, ',', '.') }}
                            </td>
                            <td>
                                <strong>{{ $promo->used_count }}</strong> / {{ $promo->quota ?? '∞' }}
                            </td>
                            <td>
                                <span class="badge {{ $promo->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $promo->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <form action="{{ route('admin.promotions.toggle', $promo->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-secondary" style="padding: 0.2rem 0.6rem; font-size: 0.75rem;">
                                            {{ $promo->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.promotions.destroy', $promo->id) }}" method="POST" onsubmit="return confirm('Delete voucher {{ $promo->code }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" style="padding: 0.2rem 0.5rem; font-size: 0.75rem;">X</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--muted); padding: 2rem;">No promotions created yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Promotion Form -->
    <div class="card" style="padding: 1.75rem;">
        <h3 style="font-size: 1.15rem; margin-bottom: 1.25rem;">Create Promo Code</h3>

        <form action="{{ route('admin.promotions.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Promo Code *</label>
                <input type="text" name="code" required class="form-input" placeholder="e.g. FLASH20" style="text-transform: uppercase;">
            </div>

            <div class="form-group">
                <label class="form-label">Campaign Name *</label>
                <input type="text" name="name" required class="form-input" placeholder="e.g. Mid-Season Flash Sale">
            </div>

            <div class="grid grid-cols-2" style="gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        <option value="PERCENTAGE">Percentage (%)</option>
                        <option value="FIXED">Fixed Amount (Rp)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Value *</label>
                    <input type="number" name="value" required min="1" class="form-input" placeholder="e.g. 10 or 50000">
                </div>
            </div>

            <div class="grid grid-cols-2" style="gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Min. Spend (Rp)</label>
                    <input type="number" name="min_spend" value="0" min="0" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Max Discount (Rp)</label>
                    <input type="number" name="max_discount" class="form-input" placeholder="Optional">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Usage Quota</label>
                <input type="number" name="quota" value="200" min="1" class="form-input">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Publish Voucher</button>
        </form>
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
