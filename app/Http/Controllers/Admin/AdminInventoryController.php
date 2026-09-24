<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\InventoryTransaction;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class AdminInventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductVariant::with(['product.category'])->orderBy('stock', 'asc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%")
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
        $transactions = InventoryTransaction::with('variant.product')->latest()->take(10)->get();

        return view('admin.stocks.index', compact('variants', 'transactions'));
    }

    public function update(Request $request, $id)
    {
        $variant = ProductVariant::with('product')->findOrFail($id);
        $request->validate([
            'stock' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:255',
        ]);

        $oldStock = $variant->stock;
        $newStock = (int)$request->stock;
        $diff = $newStock - $oldStock;

        $variant->update(['stock' => $newStock]);

        InventoryTransaction::create([
            'product_variant_id' => $variant->id,
            'type' => $diff >= 0 ? 'IN' : 'OUT',
            'quantity' => abs($diff),
            'reference' => 'Manual Adjustment',
            'notes' => $request->notes ?? "Stock adjusted from {$oldStock} to {$newStock}",
        ]);

        AuditLog::log('UPDATE_STOCK', 'ProductVariant', $variant->id, ['stock' => $oldStock], ['stock' => $variant->stock]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'stock' => $variant->stock]);
        }

        return back()->with('success', "Stok {$variant->product->name} ({$variant->size}) berhasil diperbarui menjadi {$variant->stock}.");
    }
}
