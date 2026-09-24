<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images', 'variants'])->where('status', true);

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('ingredients', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('variants', function ($vq) use ($search) {
                      $vq->where('sku', 'like', "%{$search}%")
                         ->orWhere('size', 'like', "%{$search}%");
                  });
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $categorySlug = $request->category;
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Pet Type filter (Dog, Cat, All)
        if ($request->filled('pet_type')) {
            $petType = strtolower($request->pet_type);
            if ($petType !== 'all') {
                $query->where(function ($q) use ($petType) {
                    $q->where('pet_type', $petType)->orWhere('pet_type', 'all');
                });
            }
        }

        // Variant size / packaging filter
        if ($request->filled('size')) {
            $size = $request->size;
            $query->whereHas('variants', function ($q) use ($size) {
                $q->where('size', $size)->where('stock', '>', 0);
            });
        }

        // Price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float)$request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float)$request->max_price);
        }

        // Stock availability
        if ($request->boolean('in_stock')) {
            $query->whereHas('variants', function ($q) {
                $q->where('stock', '>', 0);
            });
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('review_count', 'desc')->orderBy('rating', 'desc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(9)->withQueryString();
        $categories = Category::where('status', true)->get();

        // Distinct available package sizes
        $availableSizes = ProductVariant::distinct()->orderBy('size')->pluck('size');

        return view('products.index', compact(
            'products',
            'categories',
            'availableSizes'
        ));
    }

    public function show($identifier)
    {
        // Support finding by ID or Slug
        $product = Product::with(['category', 'images', 'variants'])
            ->where(function ($q) use ($identifier) {
                $q->where('id', $identifier)->orWhere('slug', $identifier);
            })
            ->where('status', true)
            ->firstOrFail();

        $relatedProducts = Product::with(['category', 'images', 'variants'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', true)
            ->take(4)
            ->get();

        $isInWishlist = false;
        if (Auth::check()) {
            $isInWishlist = Auth::user()->wishlists()->where('product_id', $product->id)->exists();
        }

        return view('products.show', compact('product', 'relatedProducts', 'isInWishlist'));
    }
}
