<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class AdminStockController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductVariant::with(['product.category'])->orderBy('stock', 'asc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%")
                  ->orWhere('color', 'like', "%{$search}%")
                  ->orWhere('size', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->boolean('low_stock_only')) {
            $query->where('stock', '<=', 5);
        }

        $variants = $query->paginate(20)->withQueryString();

        return view('admin.stocks.index', compact('variants'));
    }

    public function update(Request $request, $id)
    {
        $variant = ProductVariant::with('product')->findOrFail($id);
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $oldStock = $variant->stock;
        $variant->update(['stock' => (int)$request->stock]);

        AuditLog::log('UPDATE_STOCK', 'ProductVariant', $variant->id, ['stock' => $oldStock], ['stock' => $variant->stock]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'stock' => $variant->stock]);
        }

        return back()->with('success', "Stock for {$variant->product->name} (Size {$variant->size}) updated to {$variant->stock}.");
    }
}
