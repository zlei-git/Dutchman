@props(['product'])

@php
    $isInWishlist = false;
    if (auth()->check()) {
        $isInWishlist = auth()->user()->wishlists()->where('product_id', $product->id)->exists();
    }
@endphp

<div class="product-card">
    <div class="product-card-img-wrap">
        @if($product->badge)
            <span class="badge {{ $product->badge === 'SALE' ? 'badge-accent' : ($product->badge === 'NEW' ? 'badge-primary' : 'badge-warning') }} product-card-badge">
                {{ $product->badge }}
            </span>
        @endif

        <!-- Wishlist Button -->
        <button type="button" 
                class="product-wishlist-btn js-wishlist-toggle {{ $isInWishlist ? 'active' : '' }}" 
                data-product-id="{{ $product->id }}" 
                title="{{ $isInWishlist ? 'Hapus dari Wishlist' : 'Tambah ke Wishlist' }}"
                aria-label="Wishlist">
            @if($isInWishlist)
                <svg width="18" height="18" viewBox="0 0 24 24" fill="#DC2626" stroke="#DC2626" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
            @else
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
            @endif
        </button>

        <a href="{{ route('products.show', $product->slug ?? $product->id) }}">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-card-img" loading="lazy">
        </a>
    </div>

    <div class="product-card-info">
        <div class="flex items-center justify-between" style="margin-bottom: 0.25rem;">
            <div class="product-category-tag">{{ $product->category->name ?? 'Pet Food' }}</div>
            @if($product->pet_type && $product->pet_type !== 'all')
                <span style="font-size: 0.7rem; font-weight: 700; color: var(--accent); text-transform: uppercase;">
                    {{ $product->pet_type === 'cat' ? 'Kucing' : 'Anjing' }}
                </span>
            @endif
        </div>
        <a href="{{ route('products.show', $product->slug ?? $product->id) }}" class="product-title" title="{{ $product->name }}">
            {{ $product->name }}
        </a>

        <!-- Star rating -->
        <div class="flex items-center gap-1" style="margin-bottom: 0.5rem; font-size: 0.8rem; color: #F59E0B;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <span style="font-weight: 700; color: var(--text);">{{ $product->rating }}</span>
            <span style="color: var(--muted); font-size: 0.75rem;">({{ $product->review_count }})</span>
        </div>

        <div class="product-price-row">
            <div class="product-price">Rp {{ number_format($product->effective_price, 0, ',', '.') }}</div>
            @if($product->discount_price && $product->discount_price < $product->price)
                <div class="product-old-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
            @endif
        </div>

        <div class="product-card-actions">
            <a href="{{ route('products.show', $product->slug ?? $product->id) }}" class="btn btn-sm btn-primary btn-block">
                <span>Lihat Detail</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</div>
