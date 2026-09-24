@extends('layouts.admin')

@section('title', 'Edit Product — ' . $product->name . ' — PAWMART')
@section('page_title', 'Edit Pet Product')

@section('content')
<div class="card" style="padding: 2rem; max-width: 900px;">
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')

        <h3 style="font-size: 1.2rem; margin-bottom: 1.25rem;">Basic Information</h3>

        <div class="grid grid-cols-3" style="gap: 1.25rem;">
            <div class="form-group">
                <label class="form-label">Product Name *</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="form-input">
            </div>

            <div class="form-group">
                <label class="form-label">Category *</label>
                <select name="category_id" required class="form-select">
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ old('category_id', $product->category_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Target Pet Type *</label>
                <select name="pet_type" required class="form-select">
                    <option value="all" {{ old('pet_type', $product->pet_type) === 'all' ? 'selected' : '' }}>Dogs & Cats (All)</option>
                    <option value="dog" {{ old('pet_type', $product->pet_type) === 'dog' ? 'selected' : '' }}>Dogs Only</option>
                    <option value="cat" {{ old('pet_type', $product->pet_type) === 'cat' ? 'selected' : '' }}>Cats Only</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-3" style="gap: 1.25rem;">
            <div class="form-group">
                <label class="form-label">Regular Price (Rp) *</label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0" step="1000" class="form-input">
            </div>

            <div class="form-group">
                <label class="form-label">Discount Price (Rp, Optional)</label>
                <input type="number" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}" min="0" step="1000" class="form-input">
            </div>

            <div class="form-group">
                <label class="form-label">Promotional Badge</label>
                <select name="badge" class="form-select">
                    <option value="" {{ !$product->badge ? 'selected' : '' }}>None</option>
                    <option value="NEW" {{ $product->badge === 'NEW' ? 'selected' : '' }}>NEW</option>
                    <option value="SALE" {{ $product->badge === 'SALE' ? 'selected' : '' }}>SALE</option>
                    <option value="BEST SELLER" {{ $product->badge === 'BEST SELLER' ? 'selected' : '' }}>BEST SELLER</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2" style="gap: 1.25rem;">
            <div class="form-group">
                <label class="form-label">Brand</label>
                <input type="text" name="brand" value="{{ old('brand', $product->brand ?? 'PAWMART') }}" class="form-input">
            </div>

            <div class="form-group">
                <label class="form-label">Ingredients / Material Specifications</label>
                <input type="text" name="material" value="{{ old('material', $product->material) }}" class="form-input" placeholder="e.g. Real chicken meat, omega-3, taurine, grain-free formula">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Primary Image Path / URL</label>
            <input type="text" name="image_url" value="{{ old('image_url', $product->primaryImage->image_path ?? '') }}" class="form-input">
        </div>

        <div class="form-group">
            <label class="form-label">Product Description</label>
            <textarea name="description" class="form-textarea">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="flex gap-4" style="margin-bottom: 2rem;">
            <label class="flex items-center gap-2" style="font-size: 0.9rem; cursor: pointer;">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                <span>Mark as Featured Product</span>
            </label>
            <label class="flex items-center gap-2" style="font-size: 0.9rem; cursor: pointer;">
                <input type="checkbox" name="is_new" value="1" {{ old('is_new', $product->is_new) ? 'checked' : '' }}>
                <span>Mark as New Arrival</span>
            </label>
            <label class="flex items-center gap-2" style="font-size: 0.9rem; cursor: pointer;">
                <input type="checkbox" name="status" value="1" {{ old('status', $product->status) ? 'checked' : '' }}>
                <span>Active Status</span>
            </label>
        </div>

        <!-- Variants Section -->
        <hr style="border: 0; border-top: 1px solid var(--border); margin: 2rem 0;">
        <div class="flex items-center justify-between" style="margin-bottom: 1rem;">
            <div>
                <h3 style="font-size: 1.2rem;">Product Variants (Packaging & Sizes)</h3>
                <p style="color: var(--muted); font-size: 0.85rem;">Modify existing stock counts or add new packaging variants.</p>
            </div>
            <button type="button" id="addVariantRowBtn" class="btn btn-sm btn-secondary">+ Add Package Row</button>
        </div>

        <div class="table-responsive" style="margin-bottom: 2rem;">
            <table class="table" id="variantsTable">
                <thead>
                    <tr>
                        <th>Packaging / Size</th>
                        <th>Variant / Color</th>
                        <th>Stock Qty</th>
                        <th>SKU</th>
                    </tr>
                </thead>
                <tbody id="variantsBody">
                    @foreach($product->variants as $idx => $v)
                        <tr>
                            <input type="hidden" name="variants[{{ $idx }}][id]" value="{{ $v->id }}">
                            <td>
                                <input type="text" name="variants[{{ $idx }}][size]" value="{{ $v->size }}" required class="form-input" style="min-height: 38px; width: 80px;">
                            </td>
                            <td>
                                <input type="text" name="variants[{{ $idx }}][color]" value="{{ $v->color }}" required class="form-input" style="min-height: 38px; width: 140px;">
                            </td>
                            <td>
                                <input type="number" name="variants[{{ $idx }}][stock]" value="{{ $v->stock }}" required min="0" class="form-input" style="min-height: 38px; width: 90px;">
                            </td>
                            <td>
                                <input type="text" name="variants[{{ $idx }}][sku]" value="{{ $v->sku }}" class="form-input" style="min-height: 38px; width: 160px;">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Product</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    let rowIndex = {{ $product->variants->count() + 10 }};
    const body = document.getElementById('variantsBody');
    document.getElementById('addVariantRowBtn').addEventListener('click', () => {
        rowIndex++;
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="text" name="variants[${rowIndex}][size]" value="44" required class="form-input" style="min-height: 38px; width: 80px;"></td>
            <td><input type="text" name="variants[${rowIndex}][color]" value="Triple Black" required class="form-input" style="min-height: 38px; width: 140px;"></td>
            <td><input type="number" name="variants[${rowIndex}][stock]" value="10" required min="0" class="form-input" style="min-height: 38px; width: 90px;"></td>
            <td><input type="text" name="variants[${rowIndex}][sku]" placeholder="Auto" class="form-input" style="min-height: 38px; width: 160px;"></td>
        `;
        body.appendChild(tr);
    });
});
</script>
@endsection
