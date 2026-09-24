@extends('layouts.admin')

@section('title', 'Product Management — PAWMART')
@section('page_title', 'Product Catalog Management')

@section('content')
<div class="card" style="padding: 1.75rem;">
    <!-- Top Bar: Search, Category Filter, and Add Button -->
    <div class="flex items-center justify-between flex-wrap gap-3" style="margin-bottom: 1.5rem;">
        <form action="{{ route('admin.products.index') }}" method="GET" class="flex items-center gap-2 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="form-input" style="min-height: 40px; width: 220px;">
            <select name="category_id" class="form-select" style="min-height: 40px; width: 160px;" onchange="this.form.submit();">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-sm btn-secondary">Filter</button>
        </form>

        <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-accent">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <span>Add New Product</span>
        </a>
    </div>

    <!-- Products Table -->
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Variants & Stock</th>
                    <th>Badge</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 50px; height: 40px; object-fit: cover; border-radius: var(--radius-sm); background: #f0f0f0;">
                                <div>
                                    <strong>{{ $product->name }}</strong>
                                    <div style="font-size: 0.75rem; color: var(--muted);">{{ $product->brand }} &bull; SKU: {{ $product->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $product->category->name }}</td>
                        <td>
                            <div><strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong></div>
                            @if($product->discount_price)
                                <div style="font-size: 0.75rem; color: var(--accent); font-weight: 600;">
                                    Sale: Rp {{ number_format($product->discount_price, 0, ',', '.') }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <div><strong>{{ $product->variants->sum('stock') }}</strong> total units</div>
                            <div style="font-size: 0.75rem; color: var(--muted);">
                                {{ $product->variants->count() }} package variants
                            </div>
                        </td>
                        <td>
                            @if($product->badge)
                                <span class="badge {{ $product->badge === 'SALE' ? 'badge-accent' : ($product->badge === 'NEW' ? 'badge-primary' : 'badge-warning') }}">
                                    {{ $product->badge }}
                                </span>
                            @else
                                <span style="color: var(--muted); font-size: 0.8rem;">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $product->status ? 'badge-success' : 'badge-danger' }}">
                                {{ $product->status ? 'Active' : 'Draft' }}
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-secondary" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;">
                                    Edit
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete product \'{{ $product->name }}\'?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--muted); padding: 3rem;">
                            No products found matching the query.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $products->links() }}
    </div>
</div>
@endsection
