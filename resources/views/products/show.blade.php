@extends('layouts.app')

@section('title', $product->name . ' — PAWMART')
@section('meta_description', Str::limit(strip_tags($product->description), 150))

@section('content')
<div class="container" style="padding: 2.5rem 1.25rem 4rem;">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2" style="font-size: 0.85rem; color: var(--muted); margin-bottom: 2rem;">
        <a href="{{ route('home') }}">Home</a>
        <span>/</span>
        <a href="{{ route('products.index') }}">Products</a>
        <span>/</span>
        <a href="{{ route('products.index', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a>
        <span>/</span>
        <span style="color: var(--text); font-weight: 600;">{{ $product->name }}</span>
    </div>

    <!-- Product Detail Grid -->
    <div class="product-detail-grid" style="display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 3.5rem; align-items: start;">
        <!-- Left: Image Gallery & Product Specs -->
        <div>
            <div class="card" style="background: #F4F4F4; overflow: hidden; border-radius: var(--radius-lg); position: relative;">
                @if($product->badge)
                    <span class="badge {{ $product->badge === 'SALE' ? 'badge-accent' : 'badge-primary' }}" style="position: absolute; top: 16px; left: 16px; z-index: 2;">
                        {{ $product->badge }}
                    </span>
                @endif
                <img id="mainProductImage" src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100%; aspect-ratio: 4 / 3; object-fit: cover; transition: transform 0.3s ease;">
            </div>

            <!-- Thumbnail Selector -->
            @if($product->images->count() > 1)
                <div class="flex gap-3" style="margin-top: 1rem;">
                    @foreach($product->images as $img)
                        <div class="card js-thumb-btn {{ $loop->first ? 'active' : '' }}" 
                             data-img="{{ $img->url }}"
                             style="width: 80px; height: 60px; cursor: pointer; border: 2px solid {{ $loop->first ? 'var(--accent)' : 'transparent' }}; overflow: hidden;">
                            <img src="{{ $img->url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Features & Nutrition Highlights -->
            <div class="card" style="margin-top: 2rem; padding: 1.5rem;">
                <h4 style="margin-bottom: 0.75rem; color: var(--primary);">Informasi Nutrisi & Spesifikasi</h4>
                @if($product->ingredients)
                    <div style="margin-bottom: 1rem;">
                        <strong style="font-size: 0.85rem; color: var(--text); display: block; margin-bottom: 0.25rem;">Komposisi Bahan:</strong>
                        <p style="color: var(--muted); font-size: 0.85rem; line-height: 1.6;">
                            {{ $product->ingredients }}
                        </p>
                    </div>
                @endif

                <div class="grid grid-cols-2" style="gap: 1rem; font-size: 0.85rem;">
                    <div style="background: var(--background); padding: 0.75rem; border-radius: var(--radius-sm);">
                        <strong>Untuk Hewan:</strong> {{ ucfirst($product->pet_type === 'cat' ? 'Kucing (Cat)' : ($product->pet_type === 'dog' ? 'Anjing (Dog)' : 'Semua Hewan Peliharaan')) }}
                    </div>
                    <div style="background: var(--background); padding: 0.75rem; border-radius: var(--radius-sm);">
                        <strong>Kategori:</strong> {{ $product->category->name }}
                    </div>
                    <div style="background: var(--background); padding: 0.75rem; border-radius: var(--radius-sm);">
                        <strong>Brand:</strong> {{ $product->brand ?? 'PAWMART' }}
                    </div>
                    <div style="background: var(--background); padding: 0.75rem; border-radius: var(--radius-sm);">
                        <strong>Kemasan:</strong> {{ $product->weight_info ?? 'Segel Kedap Udara' }}
                    </div>
                </div>

                @if($product->shipping_info)
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-light); font-size: 0.85rem; color: var(--muted);">
                        <strong style="color: var(--text);">Informasi Pengiriman:</strong> {{ $product->shipping_info }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Right: Product Purchase Info -->
        <div>
            <div style="font-size: 0.85rem; font-weight: 800; color: var(--accent); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.5rem;">
                {{ $product->category->name }}
            </div>
            <h1 style="font-size: clamp(1.8rem, 3.5vw, 2.5rem); margin-bottom: 0.75rem;">{{ $product->name }}</h1>

            <!-- Rating & Reviews -->
            <div class="flex items-center gap-2" style="margin-bottom: 1.25rem;">
                <div class="flex items-center" style="color: #F59E0B;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </div>
                <strong style="font-size: 0.95rem;">{{ $product->rating }} / 5.0</strong>
                <span style="color: var(--muted); font-size: 0.85rem;">({{ $product->review_count }} ulasan pembeli terverifikasi)</span>
            </div>

            <!-- Price -->
            <div class="flex items-baseline gap-3" style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--border);">
                <span style="font-family: var(--font-heading); font-size: 2rem; font-weight: 800; color: var(--primary);">
                    Rp {{ number_format($product->effective_price, 0, ',', '.') }}
                </span>
                @if($product->discount_price && $product->discount_price < $product->price)
                    <span style="font-size: 1.25rem; color: var(--muted-light); text-decoration: line-through;">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                    <span class="badge badge-accent">
                        HEMAT {{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
                    </span>
                @endif
            </div>

            <!-- Description -->
            <div style="color: var(--muted); font-size: 0.95rem; line-height: 1.6; margin-bottom: 2rem;">
                {{ $product->description }}
            </div>

            <!-- Add to Cart / Buy Form -->
            <form action="{{ route('cart.add') }}" method="POST" id="purchaseForm">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="variant_id" id="selectedVariantId" value="">

                <!-- Size / Packaging Variant Selection -->
                <div style="margin-bottom: 1.5rem;">
                    <div class="flex items-center justify-between" style="margin-bottom: 0.5rem;">
                        <label class="form-label">
                            Pilih Varian / Ukuran Kemasan: <strong id="selectedSizeLabel" style="color: var(--accent);">-</strong>
                        </label>
                        <span id="stockStatusLabel" style="font-size: 0.8rem; font-weight: 700; color: var(--muted);">
                            Total stok: {{ $product->total_stock }} unit
                        </span>
                    </div>

                    <div class="flex flex-wrap gap-2" id="sizeSelectorWrap">
                        @foreach($product->variants as $variant)
                            <button type="button" 
                                    class="btn btn-secondary js-size-choice" 
                                    data-size="{{ $variant->size }}"
                                    data-variant-id="{{ $variant->id }}"
                                    data-stock="{{ $variant->stock }}"
                                    style="min-width: 58px; min-height: 44px; font-weight: 700;">
                                {{ $variant->size }}
                            </button>
                        @endforeach
                    </div>
                    <input type="hidden" name="size" id="selectedSizeInput" value="" required>
                    <div id="sizeValidationHint" style="display: none; color: var(--danger); font-size: 0.8rem; margin-top: 0.4rem; font-weight: 600;">
                        * Silakan pilih varian kemasan terlebih dahulu.
                    </div>
                </div>

                <!-- Quantity Selection -->
                <div style="margin-bottom: 2rem;">
                    <label class="form-label" style="margin-bottom: 0.5rem;">Jumlah Pembelian</label>
                    <div class="flex items-center gap-2" style="max-width: 140px;">
                        <button type="button" id="qtyMinusBtn" class="btn btn-secondary btn-icon" style="border-radius: var(--radius-sm); font-size: 1.2rem;">-</button>
                        <input type="number" name="quantity" id="qtyInput" value="1" min="1" max="20" class="form-input text-center" style="text-align: center; font-weight: 700;">
                        <button type="button" id="qtyPlusBtn" class="btn btn-secondary btn-icon" style="border-radius: var(--radius-sm); font-size: 1.2rem;">+</button>
                    </div>
                </div>

                <!-- CTA Action Buttons -->
                <div class="flex flex-col gap-3" style="margin-bottom: 1.5rem;">
                    <div class="flex gap-3">
                        <button type="submit" name="buy_now" value="0" class="btn btn-primary btn-lg flex-1">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                            <span>Tambah ke Keranjang</span>
                        </button>
                        <button type="submit" name="buy_now" value="1" class="btn btn-accent btn-lg flex-1">
                            <span>Beli Sekarang</span>
                        </button>
                    </div>

                    <!-- Book Grooming CTA Button -->
                    <a href="{{ route('user.bookings.create') }}" class="btn btn-outline btn-lg btn-block">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="m9 16 2 2 4-4"/></svg>
                        <span>Reservasi Grooming Salon</span>
                    </a>
                </div>
            </form>

            <!-- Store Guarantee Strip -->
            <div class="flex items-center justify-between" style="padding: 1rem; background: var(--background); border-radius: var(--radius-sm); font-size: 0.85rem; color: var(--muted);">
                <div class="flex items-center gap-2">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <span>100% Produk Original</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <span>Higienis & Bersih</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Garansi Mutu Nutrisi</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div style="margin-top: 5rem;">
            <div class="flex items-center justify-between" style="margin-bottom: 2rem;">
                <h2>Produk Terkait Lainnya</h2>
                <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="btn btn-sm btn-outline">Lihat Kategori</a>
            </div>

            <div class="product-grid">
                @foreach($relatedProducts as $rel)
                    <x-product-card :product="$rel" />
                @endforeach
            </div>
        </div>
    @endif
</div>

<style>
@media (max-width: 900px) {
    .product-detail-grid {
        grid-template-columns: 1fr !important;
        gap: 2rem !important;
    }
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Thumbnail click
    document.querySelectorAll('.js-thumb-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.js-thumb-btn').forEach(b => {
                b.style.borderColor = 'transparent';
                b.classList.remove('active');
            });
            this.style.borderColor = 'var(--accent)';
            this.classList.add('active');
            const mainImg = document.getElementById('mainProductImage');
            if (mainImg) mainImg.src = this.dataset.img;
        });
    });

    // Variant selection
    const sizeChoices = document.querySelectorAll('.js-size-choice');
    const sizeLabel = document.getElementById('selectedSizeLabel');
    const sizeInput = document.getElementById('selectedSizeInput');
    const variantIdInput = document.getElementById('selectedVariantId');
    const stockStatus = document.getElementById('stockStatusLabel');
    const validationHint = document.getElementById('sizeValidationHint');

    if (sizeChoices.length > 0) {
        // Auto-select first variant
        selectVariant(sizeChoices[0]);
    }

    sizeChoices.forEach(btn => {
        btn.addEventListener('click', function() {
            selectVariant(this);
        });
    });

    function selectVariant(btn) {
        sizeChoices.forEach(b => {
            b.classList.remove('btn-primary');
            b.classList.add('btn-secondary');
        });
        btn.classList.remove('btn-secondary');
        btn.classList.add('btn-primary');

        const size = btn.dataset.size;
        const stock = parseInt(btn.dataset.stock);
        const variantId = btn.dataset.variantId;

        sizeLabel.textContent = size;
        sizeInput.value = size;
        variantIdInput.value = variantId;

        if (stock <= 5) {
            stockStatus.innerHTML = `<span style="color: var(--danger);">Sisa ${stock} unit lagi!</span>`;
        } else {
            stockStatus.innerHTML = `<span style="color: var(--success);">Tersedia ${stock} unit</span>`;
        }

        if (validationHint) validationHint.style.display = 'none';
    }

    // Quantity stepper
    const qtyInput = document.getElementById('qtyInput');
    const qtyMinus = document.getElementById('qtyMinusBtn');
    const qtyPlus = document.getElementById('qtyPlusBtn');

    if (qtyMinus && qtyInput) {
        qtyMinus.addEventListener('click', () => {
            let val = parseInt(qtyInput.value) || 1;
            if (val > 1) qtyInput.value = val - 1;
        });
    }

    if (qtyPlus && qtyInput) {
        qtyPlus.addEventListener('click', () => {
            let val = parseInt(qtyInput.value) || 1;
            if (val < 20) qtyInput.value = val + 1;
        });
    }

    // Purchase form validation
    const purchaseForm = document.getElementById('purchaseForm');
    if (purchaseForm) {
        purchaseForm.addEventListener('submit', function(e) {
            if (!sizeInput.value) {
                e.preventDefault();
                if (validationHint) validationHint.style.display = 'block';
            }
        });
    }
});
</script>
@endpush
