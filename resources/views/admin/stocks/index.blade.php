@extends('layouts.admin')

@section('title', 'Inventory & Stocks — PAWMART')
@section('page_title', 'Pet Products Inventory & Stock Levels')

@section('content')
<div class="card" style="padding: 1.75rem;">
    <!-- Filters -->
    <div class="flex items-center justify-between flex-wrap gap-3" style="margin-bottom: 1.5rem;">
        <form action="{{ route('admin.stocks.index') }}" method="GET" class="flex items-center gap-2 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search product, size, or SKU..." class="form-input" style="min-height: 40px; width: 250px;">
            <label class="flex items-center gap-2" style="font-size: 0.85rem; font-weight: 600; cursor: pointer; margin-left: 0.5rem;">
                <input type="checkbox" name="low_stock_only" value="1" {{ request('low_stock_only') ? 'checked' : '' }} onchange="this.form.submit();">
                <span style="color: var(--danger);">Low Stock Only (&le; 5)</span>
            </label>
            <button type="submit" class="btn btn-sm btn-secondary">Search</button>
        </form>
    </div>

    <!-- Inventory Table -->
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Packaging / Size</th>
                    <th>Color/Variant</th>
                    <th>SKU</th>
                    <th>Current Stock</th>
                    <th>Adjust Stock</th>
                </tr>
            </thead>
            <tbody>
                @forelse($variants as $v)
                    <tr style="{{ $v->stock <= 5 ? 'background-color: #FEF2F2;' : '' }}">
                        <td>
                            <strong>{{ $v->product->name }}</strong>
                        </td>
                        <td>{{ $v->product->category->name }}</td>
                        <td>
                            <span class="badge badge-primary" style="font-size: 0.8rem;">{{ $v->size }}</span>
                        </td>
                        <td>{{ $v->color }}</td>
                        <td><code>{{ $v->sku }}</code></td>
                        <td>
                            <strong style="font-size: 1.1rem; color: {{ $v->stock <= 5 ? 'var(--danger)' : 'var(--text)' }};">
                                {{ $v->stock }}
                            </strong>
                            @if($v->stock <= 5)
                                <span class="badge badge-danger" style="margin-left: 0.35rem; font-size: 0.65rem;">LOW</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.stocks.update', $v->id) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <input type="number" name="stock" value="{{ $v->stock }}" min="0" required class="form-input" style="width: 75px; min-height: 32px; padding: 0.2rem 0.5rem; font-size: 0.85rem; text-align: center;">
                                <button type="submit" class="btn btn-sm btn-primary" style="min-height: 32px; padding: 0.2rem 0.6rem; font-size: 0.75rem;">
                                    Save
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--muted); padding: 3rem;">
                            No stock records found matching your filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $variants->links() }}
    </div>
</div>
@endsection
