<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants', 'images'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->paginate(15)->withQueryString();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'pet_type' => 'nullable|string|in:dog,cat,all',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'brand' => 'nullable|string|max:100',
            'material' => 'nullable|string|max:255',
            'badge' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            'image_url' => 'nullable|string',
            // Variants
            'variants' => 'required|array|min:1',
            'variants.*.size' => 'required|string',
            'variants.*.color' => 'required|string',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.sku' => 'nullable|string',
        ]);

        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        $product = Product::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'pet_type' => $validated['pet_type'] ?? 'all',
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'discount_price' => $validated['discount_price'] ?? null,
            'brand' => $validated['brand'] ?? 'PAWMART',
            'material' => $validated['material'] ?? null,
            'badge' => $validated['badge'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'is_new' => $request->boolean('is_new'),
            'status' => $request->boolean('status', true),
        ]);

        // Primary Image
        $imagePath = $validated['image_url'] ?? 'images/products/placeholder.svg';
        ProductImage::create([
            'product_id' => $product->id,
            'image_path' => $imagePath,
            'is_primary' => true,
            'sort_order' => 0,
        ]);

        // Variants
        foreach ($validated['variants'] as $v) {
            ProductVariant::create([
                'product_id' => $product->id,
                'size' => $v['size'],
                'color' => $v['color'],
                'stock' => (int)$v['stock'],
                'sku' => $v['sku'] ?? strtoupper($slug . '-' . $v['size'] . '-' . substr($v['color'], 0, 3)),
            ]);
        }

        AuditLog::log('CREATE_PRODUCT', 'Product', $product->id, null, $product->toArray());

        return redirect()->route('admin.products.index')->with('success', "Product '{$product->name}' created successfully!");
    }

    public function edit($id)
    {
        $product = Product::with(['variants', 'images'])->findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'pet_type' => 'nullable|string|in:dog,cat,all',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'brand' => 'nullable|string|max:100',
            'material' => 'nullable|string|max:255',
            'badge' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            'image_url' => 'nullable|string',
            // Variants
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.size' => 'required|string',
            'variants.*.color' => 'required|string',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.sku' => 'nullable|string',
        ]);

        $oldData = $product->toArray();

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'pet_type' => $validated['pet_type'] ?? ($product->pet_type ?? 'all'),
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'discount_price' => $validated['discount_price'] ?? null,
            'brand' => $validated['brand'] ?? 'PAWMART',
            'material' => $validated['material'] ?? null,
            'badge' => $validated['badge'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'is_new' => $request->boolean('is_new'),
            'status' => $request->boolean('status', true),
        ]);

        if (!empty($validated['image_url'])) {
            $primaryImg = $product->primaryImage;
            if ($primaryImg) {
                $primaryImg->update(['image_path' => $validated['image_url']]);
            } else {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $validated['image_url'],
                    'is_primary' => true,
                ]);
            }
        }

        // Sync variants
        if (!empty($validated['variants'])) {
            $submittedIds = [];
            foreach ($validated['variants'] as $v) {
                if (!empty($v['id'])) {
                    $variant = ProductVariant::find($v['id']);
                    if ($variant) {
                        $variant->update([
                            'size' => $v['size'],
                            'color' => $v['color'],
                            'stock' => (int)$v['stock'],
                            'sku' => $v['sku'] ?? $variant->sku,
                        ]);
                        $submittedIds[] = $variant->id;
                    }
                } else {
                    $newV = ProductVariant::create([
                        'product_id' => $product->id,
                        'size' => $v['size'],
                        'color' => $v['color'],
                        'stock' => (int)$v['stock'],
                        'sku' => $v['sku'] ?? strtoupper($product->slug . '-' . $v['size'] . '-' . substr($v['color'], 0, 3)),
                    ]);
                    $submittedIds[] = $newV->id;
                }
            }
        }

        AuditLog::log('UPDATE_PRODUCT', 'Product', $product->id, $oldData, $product->toArray());

        return redirect()->route('admin.products.index')->with('success', "Product '{$product->name}' updated successfully!");
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $name = $product->name;
        AuditLog::log('DELETE_PRODUCT', 'Product', $product->id, $product->toArray(), null);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', "Product '{$name}' deleted.");
    }
}
