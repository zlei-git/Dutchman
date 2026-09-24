@extends('layouts.app')

@section('title', 'Keranjang Belanja — PAWMART')

@section('content')
<div class="container" style="padding: 2.5rem 1.25rem 4rem;">
    <!-- Header -->
    <div style="margin-bottom: 2rem;">
        <div class="flex items-center gap-2" style="font-size: 0.85rem; color: var(--muted); margin-bottom: 0.5rem;">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <span style="color: var(--text); font-weight: 600;">Keranjang Belanja</span>
        </div>
        <h1>Keranjang Belanja</h1>
    </div>

    @if(!empty($summary['items']) && count($summary['items']) > 0)
        <div style="display: grid; grid-template-columns: 1fr 360px; gap: 2.5rem; align-items: start;">
            <!-- Cart Items List -->
            <div class="card" style="padding: 1.5rem;">
                <div class="flex items-center justify-between" style="padding-bottom: 1rem; border-bottom: 1px solid var(--border); margin-bottom: 1.25rem;">
                    <strong>{{ $summary['item_count'] }} Produk di Keranjang</strong>
                    <span style="font-size: 0.85rem; color: var(--muted);">Harga</span>
                </div>

                <div class="flex flex-col gap-4">
                    @foreach($summary['items'] as $item)
                        <div class="flex items-center gap-4 flex-wrap" style="padding-bottom: 1.25rem; border-bottom: 1px solid var(--border-light);">
                            <!-- Product Image -->
                            <div style="width: 90px; height: 75px; background: #F4F4F4; border-radius: var(--radius-sm); overflow: hidden; flex-shrink: 0;">
                                <img src="{{ $item['image'] }}" alt="{{ $item['product_name'] }}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>

                            <!-- Product Info -->
                            <div style="flex: 1; min-width: 180px;">
                                <a href="{{ route('products.show', $item['slug']) }}" style="font-weight: 800; font-size: 1rem; color: var(--primary);">
                                    {{ $item['product_name'] }}
                                </a>
                                <div class="flex items-center gap-2" style="font-size: 0.85rem; color: var(--muted); margin-top: 0.2rem;">
                                    <span>Varian: <strong>{{ $item['size'] }}</strong></span>
                                    @if($item['color'])
                                        <span>&bull;</span>
                                        <span>Warna: <strong>{{ $item['color'] }}</strong></span>
                                    @endif
                                </div>
                                <div style="font-size: 0.8rem; color: var(--muted); margin-top: 0.2rem;">
                                    Stok tersedia: {{ $item['stock'] }} unit
                                </div>
                            </div>

                            <!-- Quantity Controls -->
                            <div class="flex items-center gap-2">
                                <form action="{{ route('user.cart.update', $item['id']) }}" method="POST" class="flex items-center gap-1">
                                    @csrf
                                    <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}" class="btn btn-secondary btn-sm" style="min-width: 32px; min-height: 32px; padding: 0;">-</button>
                                    <span style="font-weight: 800; min-width: 32px; text-align: center; font-size: 0.95rem;">{{ $item['quantity'] }}</span>
                                    <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}" class="btn btn-secondary btn-sm" style="min-width: 32px; min-height: 32px; padding: 0;" {{ $item['quantity'] >= $item['stock'] ? 'disabled' : '' }}>+</button>
                                </form>
                            </div>

                            <!-- Subtotal & Remove -->
                            <div style="text-align: right; min-width: 120px;">
                                <div style="font-family: var(--font-heading); font-weight: 800; font-size: 1.05rem; color: var(--primary);">
                                    Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                </div>
                                <div style="font-size: 0.75rem; color: var(--muted);">
                                    @ Rp {{ number_format($item['price'], 0, ',', '.') }}
                                </div>
                                <form action="{{ route('user.cart.remove', $item['id']) }}" method="POST" style="margin-top: 0.4rem;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="color: var(--danger); font-size: 0.8rem; font-weight: 600; text-decoration: underline; background: none; border: none; cursor: pointer;" onclick="return confirm('Hapus produk ini dari keranjang?')">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-between" style="margin-top: 1.5rem;">
                    <a href="{{ route('products.index') }}" class="btn btn-outline btn-sm">
                        &larr; Lanjut Belanja
                    </a>
                </div>
            </div>

            <!-- Order Summary Card -->
            <div class="card" style="padding: 1.75rem; position: sticky; top: 96px;">
                <h3 style="font-size: 1.25rem; margin-bottom: 1.25rem;">Ringkasan Belanja</h3>

                <!-- Voucher Promo Input -->
                <div style="margin-bottom: 1.5rem;">
                    <form action="{{ route('user.cart.promo') }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="text" name="code" value="{{ $summary['promo_code'] ?? '' }}" placeholder="Kode Voucher Promo" class="form-input" style="min-height: 42px; font-size: 0.85rem; text-transform: uppercase;">
                        <button type="submit" class="btn btn-primary btn-sm" style="min-height: 42px;">Gunakan</button>
                    </form>

                    @if(!empty($summary['promo_code']))
                        <div class="flex items-center justify-between" style="margin-top: 0.5rem; background: var(--success-soft); padding: 0.5rem 0.75rem; border-radius: var(--radius-sm);">
                            <div class="flex items-center gap-1" style="font-size: 0.8rem; color: var(--success); font-weight: 700;">
                                <span>🏷️ Voucher <strong>{{ $summary['promo_code'] }}</strong> Aktif</span>
                            </div>
                            <form action="{{ route('user.cart.promo.remove') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color: var(--danger); font-size: 0.75rem; font-weight: 700; background: none; border: none; cursor: pointer;">Hapus</button>
                            </form>
                        </div>
                    @else
                        <div style="font-size: 0.75rem; color: var(--muted); margin-top: 0.4rem;">
                            Hint: Coba kode demo promo <strong>PAW10</strong> (Diskon 10%)
                        </div>
                    @endif
                </div>

                <div class="flex flex-col gap-2" style="font-size: 0.95rem; border-top: 1px solid var(--border); padding-top: 1.25rem; margin-bottom: 1.25rem;">
                    <div class="flex justify-between">
                        <span style="color: var(--muted);">Subtotal Produk</span>
                        <strong style="color: var(--primary);">Rp {{ number_format($summary['subtotal'], 0, ',', '.') }}</strong>
                    </div>

                    @if($summary['discount'] > 0)
                        <div class="flex justify-between" style="color: var(--success);">
                            <span>Potongan Diskon</span>
                            <strong>- Rp {{ number_format($summary['discount'], 0, ',', '.') }}</strong>
                        </div>
                    @endif

                    <div class="flex justify-between">
                        <span style="color: var(--muted);">Estimasi Ongkir</span>
                        <strong>
                            @if($summary['shipping_fee'] == 0)
                                <span style="color: var(--success); font-weight: 700;">GRATIS</span>
                            @else
                                Rp {{ number_format($summary['shipping_fee'], 0, ',', '.') }}
                            @endif
                        </strong>
                    </div>
                </div>

                <div class="flex justify-between items-baseline" style="border-top: 2px solid var(--primary); padding-top: 1.25rem; margin-bottom: 1.5rem;">
                    <span style="font-size: 1.1rem; font-weight: 800;">Total Tagihan</span>
                    <span style="font-family: var(--font-heading); font-size: 1.5rem; font-weight: 900; color: var(--primary);">
                        Rp {{ number_format($summary['total'], 0, ',', '.') }}
                    </span>
                </div>

                <a href="{{ route('user.checkout') }}" class="btn btn-accent btn-lg btn-block">
                    <span>Lanjut ke Pembayaran</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    @else
        <div class="card">
            <x-empty-state 
                title="Keranjang Belanja Kosong"
                description="Belum ada produk kebutuhan hewan peliharaan di keranjang Anda. Jelajahi pilihan makanan bernutrisi, camilan sehat, dan mainan sekarang!"
                actionText="Belanja Produk Sekarang"
                actionUrl="{{ route('products.index') }}"
            />
        </div>
    @endif
</div>

<style>
@media (max-width: 900px) {
    div[style*="grid-template-columns: 1fr 360px"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
