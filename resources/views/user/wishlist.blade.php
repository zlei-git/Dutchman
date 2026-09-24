@extends('layouts.app')

@section('title', 'My Wishlist — PAWMART')

@section('content')
<div class="container" style="padding: 2.5rem 1.25rem 4rem;">
    <!-- Header -->
    <div style="margin-bottom: 2rem;">
        <div class="flex items-center gap-2" style="font-size: 0.85rem; color: var(--muted); margin-bottom: 0.5rem;">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <a href="{{ route('user.dashboard') }}">Dashboard</a>
            <span>/</span>
            <span style="color: var(--text); font-weight: 600;">Wishlist</span>
        </div>
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1>Saved Pet Products</h1>
                <p style="color: var(--muted); margin-top: 0.25rem;">You have {{ $wishlists->total() }} item(s) saved for later.</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-outline btn-sm">Explore Pet Supplies</a>
        </div>
    </div>

    @if($wishlists->count() > 0)
        <div class="product-grid">
            @foreach($wishlists as $w)
                @php $product = $w->product; @endphp
                <div class="product-card">
                    <div class="product-card-img-wrap">
                        @if($product->badge)
                            <span class="badge {{ $product->badge === 'SALE' ? 'badge-accent' : 'badge-primary' }} product-card-badge">
                                {{ $product->badge }}
                            </span>
                        @endif

                        <a href="{{ route('products.show', $product->slug ?? $product->id) }}">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-card-img">
                        </a>
                    </div>

                    <div class="product-card-info">
                        <div class="product-category-tag">{{ $product->category->name }}</div>
                        <a href="{{ route('products.show', $product->slug ?? $product->id) }}" class="product-title">
                            {{ $product->name }}
                        </a>

                        <div class="product-price-row">
                            <div class="product-price">Rp {{ number_format($product->effective_price, 0, ',', '.') }}</div>
                            @if($product->discount_price && $product->discount_price < $product->price)
                                <div class="product-old-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            @endif
                        </div>

                        <div class="flex flex-col gap-2" style="margin-top: 0.5rem;">
                            <form action="{{ route('user.wishlist.move_to_cart', $w->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-accent btn-block">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                                    <span>Move to Cart</span>
                                </button>
                            </form>

                            <form action="{{ route('user.wishlist.toggle') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="btn btn-sm btn-secondary btn-block" style="color: var(--danger);">
                                    Remove
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 3rem;">
            {{ $wishlists->links() }}
        </div>
    @else
        <div class="card">
            <x-empty-state 
                title="Your Wishlist is Empty"
                description="Save your favorite silhouettes here as you explore the catalog."
                actionText="Explore Footwear"
                actionUrl="{{ route('products.index') }}"
            />
        </div>
    @endif
</div>
@endsection
