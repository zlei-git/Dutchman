@extends('layouts.admin')

@section('title', 'Add New Product — PAWMART')
@section('page_title', 'Create Pet Product')

@section('content')
<div class="card" style="padding: 2rem; max-width: 900px;">
    <form action="{{ route('admin.products.store') }}" method="POST">
        @csrf

        <h3 style="font-size: 1.2rem; margin-bottom: 1.25rem;">Basic Information</h3>

        <div class="grid grid-cols-3" style="gap: 1.25rem;">
            <div class="form-group">
                <label class="form-label">Product Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="form-input" placeholder="e.g. Royal Canin Maxi Adult Dog Food">
            </div>

            <div class="form-group">
                <label class="form-label">Category *</label>
                <select name="category_id" required class="form-select">
                    <option value="">-- Choose Category --</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Target Pet Type *</label>
                <select name="pet_type" required class="form-select">
                    <option value="all" {{ old('pet_type') === 'all' ? 'selected' : '' }}>Dogs & Cats (All)</option>
                    <option value="dog" {{ old('pet_type') === 'dog' ? 'selected' : '' }}>Dogs Only</option>
                    <option value="cat" {{ old('pet_type') === 'cat' ? 'selected' : '' }}>Cats Only</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-3" style="gap: 1.25rem;">
            <div class="form-group">
                <label class="form-label">Regular Price (Rp) *</label>
                <input type="number" name="price" value="{{ old('price') }}" required min="0" step="1000" class="form-input" placeholder="e.g. 185000">
            </div>

            <div class="form-group">
                <label class="form-label">Discount Price (Rp, Optional)</label>
                <input type="number" name="discount_price" value="{{ old('discount_price') }}" min="0" step="1000" class="form-input" placeholder="e.g. 165000">
            </div>

            <div class="form-group">
                <label class="form-label">Promotional Badge</label>
                <select name="badge" class="form-select">
                    <option value="">None</option>
                    <option value="NEW">NEW</option>
                    <option value="SALE">SALE</option>
                    <option value="BEST SELLER">BEST SELLER</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2" style="gap: 1.25rem;">
            <div class="form-group">
                <label class="form-label">Brand</label>
                <input type="text" name="brand" value="{{ old('brand', 'PAWMART') }}" class="form-input">
            </div>

            <div class="form-group">
                <label class="form-label">Ingredients / Material Specifications</label>
                <input type="text" name="material" value="{{ old('material') }}" class="form-input" placeholder="e.g. Real chicken meat, omega-3, taurine, grain-free formula">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Image Path / URL</label>
            <input type="text" name="image_url" value="{{ old('image_url', 'images/products/street-runner.svg') }}" class="form-input" placeholder="e.g. images/products/street-runner.svg or full URL">
            <span class="form-hint">Path relative to public folder or external image URL.</span>
        </div>

        <div class="form-group">
            <label class="form-label">Product Description</label>
            <textarea name="description" class="form-textarea" placeholder="Detailed product craftsmanship and fit narrative...">{{ old('description') }}</textarea>
        </div>

        <div class="flex gap-4" style="margin-bottom: 2rem;">
            <label class="flex items-center gap-2" style="font-size: 0.9rem; cursor: pointer;">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                <span>Mark as Featured Product</span>
            </label>
            <label class="flex items-center gap-2" style="font-size: 0.9rem; cursor: pointer;">
                <input type="checkbox" name="is_new" value="1" {{ old('is_new', 1) ? 'checked' : '' }}>
                <span>Mark as New Arrival</span>
            </label>
            <label class="flex items-center gap-2" style="font-size: 0.9rem; cursor: pointer;">
                <input type="checkbox" name="status" value="1" checked>
                <span>Active Status</span>
            </label>
        </div>

        <!-- Variants Section -->
        <hr style="border: 0; border-top: 1px solid var(--border); margin: 2rem 0;">
        <div class="flex items-center justify-between" style="margin-bottom: 1rem;">
            <div>
                <h3 style="font-size: 1.2rem;">Product Variants (Packaging / Sizes)</h3>
                <p style="color: var(--muted); font-size: 0.85rem;">Configure inventory stocks for each available packaging weight or size.</p>
            </div>
            <button type="button" id="addVariantRowBtn" class="btn btn-sm btn-secondary">+ Add Package Row</button>
        </div>

        <div class="table-responsive" style="margin-bottom: 2rem;">
            <table class="table" id="variantsTable">
                <thead>
                    <tr>
                        <th>Size / Weight</th>
                        <th>Variant / Color</th>
                        <th>Stock Qty</th>
                        <th>SKU (Optional)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="variantsBody">
                    @foreach(['500g', '1kg', '2kg'] as $idx => $size)
                        <tr>
                            <td>
                                <input type="text" name="variants[{{ $idx }}][size]" value="{{ $size }}" required class="form-input" style="min-height: 38px; width: 100px;">
                            </td>
                            <td>
                                <input type="text" name="variants[{{ $idx }}][color]" value="Standard" required class="form-input" style="min-height: 38px; width: 140px;">
                            </td>
                            <td>
                                <input type="number" name="variants[{{ $idx }}][stock]" value="{{ 20 - ($idx * 5) }}" required min="0" class="form-input" style="min-height: 38px; width: 90px;">
                            </td>
                            <td>
                                <input type="text" name="variants[{{ $idx }}][sku]" placeholder="Auto" class="form-input" style="min-height: 38px; width: 140px;">
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger js-remove-variant-row" style="padding: 0.2rem 0.5rem; font-size: 0.75rem;">X</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Product</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    let rowIndex = 10;
    const body = document.getElementById('variantsBody');
    document.getElementById('addVariantRowBtn').addEventListener('click', () => {
        rowIndex++;
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="text" name="variants[${rowIndex}][size]" value="4kg" required class="form-input" style="min-height: 38px; width: 100px;"></td>
            <td><input type="text" name="variants[${rowIndex}][color]" value="Standard" required class="form-input" style="min-height: 38px; width: 140px;"></td>
            <td><input type="number" name="variants[${rowIndex}][stock]" value="10" required min="0" class="form-input" style="min-height: 38px; width: 90px;"></td>
            <td><input type="text" name="variants[${rowIndex}][sku]" placeholder="Auto" class="form-input" style="min-height: 38px; width: 140px;"></td>
            <td><button type="button" class="btn btn-sm btn-danger js-remove-variant-row" style="padding: 0.2rem 0.5rem; font-size: 0.75rem;">X</button></td>
        `;
        body.appendChild(tr);
    });

    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('js-remove-variant-row')) {
            const tr = e.target.closest('tr');
            if (body.children.length > 1) {
                tr.remove();
            } else {
                alert('A product must have at least one variant size.');
            }
        }
    });
});
</script>
@endsection
