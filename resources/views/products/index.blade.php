@extends('layouts.app')

@section('title', 'Katalog Produk — PAWMART Pet Shop')
@section('meta_description', 'Jelajahi produk kebutuhan hewan peliharaan terbaik: makanan kucing & anjing, camilan lezat, mainan, perlengkapan salon, aksesoris, dan vitamin.')

@section('content')
<div class="container" style="padding: 2.5rem 1.25rem 4rem;">
    <!-- Catalog Header & Breadcrumb -->
    <div style="margin-bottom: 2rem;">
        <div class="flex items-center gap-2" style="font-size: 0.85rem; color: var(--muted); margin-bottom: 0.5rem;">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <span style="color: var(--text); font-weight: 600;">Products</span>
        </div>
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1>Katalog Pet Care</h1>
                <p style="color: var(--muted); margin-top: 0.25rem;">Menampilkan {{ $products->total() }} produk kebutuhan nutrisi dan perawatan peliharaan.</p>
            </div>

            <!-- Search Form -->
            <form action="{{ route('products.index') }}" method="GET" style="display: flex; gap: 0.5rem; max-width: 380px; width: 100%;">
                @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                @if(request('pet_type')) <input type="hidden" name="pet_type" value="{{ request('pet_type') }}"> @endif
                @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
                <div style="position: relative; flex: 1;">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari makanan, camilan, mainan..." class="form-input" style="padding-left: 2.5rem; min-height: 44px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; left: 12px; top: 13px; color: var(--muted);"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Cari</button>
            </form>
        </div>
    </div>

    <!-- Active Filters Pills -->
    @if(request()->anyFilled(['category', 'pet_type', 'search', 'size', 'min_price', 'max_price', 'in_stock']))
        <div class="flex items-center gap-2 flex-wrap" style="margin-bottom: 1.5rem; padding: 0.75rem 1rem; background: #FFFFFF; border-radius: var(--radius-sm); border: 1px solid var(--border);">
            <span style="font-size: 0.8rem; font-weight: 700; color: var(--muted);">Filter Aktif:</span>
            @if(request('category'))
                <span class="badge badge-primary">{{ ucfirst(str_replace('-', ' ', request('category'))) }}</span>
            @endif
            @if(request('pet_type'))
                <span class="badge badge-accent">Hewan: {{ ucfirst(request('pet_type')) }}</span>
            @endif
            @if(request('search'))
                <span class="badge badge-accent">Kata Kunci: "{{ request('search') }}"</span>
            @endif
            @if(request('size'))
                <span class="badge badge-muted">Ukuran: {{ request('size') }}</span>
            @endif
            @if(request('in_stock'))
                <span class="badge badge-success">Hanya Yang Tersedia</span>
            @endif
            <a href="{{ route('products.index') }}" style="font-size: 0.8rem; color: var(--danger); font-weight: 700; margin-left: auto;">Reset Semua</a>
        </div>
    @endif

    <!-- Catalog Layout: Sidebar Filters + Products Grid -->
    <div style="display: grid; grid-template-columns: 260px 1fr; gap: 2rem; align-items: start;">
        <!-- Filter Sidebar (Desktop) -->
        <aside class="card" style="padding: 1.5rem;">
            <form action="{{ route('products.index') }}" method="GET" id="catalogFilterForm">
                @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif

                <!-- Sorting Option -->
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Urutkan</label>
                    <select name="sort" class="form-select" onchange="document.getElementById('catalogFilterForm').submit();">
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Produk Terbaru</option>
                        <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Paling Populer</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Harga: Terendah ke Tertinggi</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Harga: Tertinggi ke Terendah</option>
                    </select>
                </div>

                <hr style="border: 0; border-top: 1px solid var(--border); margin: 1.25rem 0;">

                <!-- Pet Type Filter -->
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Jenis Hewan</label>
                    <div class="flex flex-col gap-2" style="margin-top: 0.5rem;">
                        <label class="flex items-center gap-2" style="font-size: 0.9rem; cursor: pointer;">
                            <input type="radio" name="pet_type" value="" {{ !request('pet_type') ? 'checked' : '' }} onchange="this.form.submit();">
                            <span>Semua Hewan</span>
                        </label>
                        <label class="flex items-center gap-2" style="font-size: 0.9rem; cursor: pointer;">
                            <input type="radio" name="pet_type" value="cat" {{ request('pet_type') === 'cat' ? 'checked' : '' }} onchange="this.form.submit();">
                            <span>Kucing (Cat)</span>
                        </label>
                        <label class="flex items-center gap-2" style="font-size: 0.9rem; cursor: pointer;">
                            <input type="radio" name="pet_type" value="dog" {{ request('pet_type') === 'dog' ? 'checked' : '' }} onchange="this.form.submit();">
                            <span>Anjing (Dog)</span>
                        </label>
                    </div>
                </div>

                <hr style="border: 0; border-top: 1px solid var(--border); margin: 1.25rem 0;">

                <!-- Categories Filter -->
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Kategori Produk</label>
                    <div class="flex flex-col gap-2" style="margin-top: 0.5rem;">
                        <label class="flex items-center gap-2" style="font-size: 0.9rem; cursor: pointer;">
                            <input type="radio" name="category" value="" {{ !request('category') ? 'checked' : '' }} onchange="this.form.submit();">
                            <span>Semua Kategori</span>
                        </label>
                        @foreach($categories as $cat)
                            <label class="flex items-center justify-between" style="font-size: 0.9rem; cursor: pointer;">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="category" value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'checked' : '' }} onchange="this.form.submit();">
                                    <span>{{ $cat->name }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <hr style="border: 0; border-top: 1px solid var(--border); margin: 1.25rem 0;">

                <!-- Size / Packaging Variant Filter -->
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Varian / Ukuran Kemasan</label>
                    <div class="flex flex-wrap gap-2" style="margin-top: 0.5rem;">
                        @foreach($availableSizes as $size)
                            <button type="submit" name="size" value="{{ $size }}" 
                                    class="btn btn-sm {{ request('size') == $size ? 'btn-primary' : 'btn-secondary' }}"
                                    style="min-width: 42px; min-height: 34px; padding: 0.2rem 0.5rem; font-size: 0.75rem;">
                                {{ $size }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <hr style="border: 0; border-top: 1px solid var(--border); margin: 1.25rem 0;">

                <!-- Price Range Filter -->
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Maksimal Harga (Rp)</label>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Contoh: 150000" class="form-input" step="25000">
                </div>

                <!-- Stock Availability -->
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="flex items-center gap-2" style="font-size: 0.9rem; cursor: pointer;">
                        <input type="checkbox" name="in_stock" value="1" {{ request('in_stock') ? 'checked' : '' }} onchange="this.form.submit();">
                        <span>Hanya Yang Tersedia (In Stock)</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Terapkan Filter</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary btn-block" style="margin-top: 0.5rem;">Reset Filter</a>
            </form>
        </aside>

        <!-- Products Listing Section -->
        <div>
            @if($products->count() > 0)
                <div class="product-grid">
                    @foreach($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>

                <!-- Pagination -->
                <div style="margin-top: 3rem;">
                    {{ $products->links() }}
                </div>
            @else
                <div class="card">
                    <x-empty-state 
                        title="Produk Tidak Ditemukan" 
                        description="Kami tidak menemukan produk yang cocok dengan kriteria filter yang Anda pilih. Coba bersihkan filter atau gunakan kata kunci lain."
                        actionText="Lihat Semua Produk"
                        actionUrl="{{ route('products.index') }}"
                    />
                </div>
            @endif
        </div>
    </div>
</div>

<style>
@media (max-width: 900px) {
    div[style*="grid-template-columns: 260px 1fr"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
