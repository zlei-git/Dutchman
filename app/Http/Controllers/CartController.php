<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(Request $request)
    {
        $promoCode = session('applied_promo');
        $shippingMethod = session('shipping_method', 'Regular');
        $summary = $this->cartService->calculateSummary($promoCode, $shippingMethod);

        return view('user.cart', compact('summary'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'variant_id' => 'nullable|exists:product_variants,id',
            'product_id' => 'nullable|exists:products,id',
            'size' => 'nullable|string',
            'color' => 'nullable|string',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $quantity = $request->input('quantity', 1);

        $variantId = $request->input('variant_id');
        if (!$variantId && $request->filled('product_id') && $request->filled('size')) {
            $variant = ProductVariant::where('product_id', $request->product_id)
                ->where('size', $request->size)
                ->when($request->filled('color'), function ($q) use ($request) {
                    $q->where('color', $request->color);
                })
                ->first();

            if ($variant) {
                $variantId = $variant->id;
            }
        }

        if (!$variantId) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Please choose a size first.'], 422);
            }
            return back()->with('error', 'Please choose a size first.');
        }

        $result = $this->cartService->addItem($variantId, $quantity);

        if ($request->wantsJson()) {
            return response()->json($result, $result['success'] ? 200 : 422);
        }

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        if ($request->boolean('buy_now')) {
            return redirect()->route('user.checkout');
        }

        return back()->with('success', $result['message']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0|max:20',
        ]);

        $result = $this->cartService->updateQuantity((int)$id, (int)$request->quantity);

        if ($request->wantsJson()) {
            $promoCode = session('applied_promo');
            $shippingMethod = session('shipping_method', 'Regular');
            $summary = $this->cartService->calculateSummary($promoCode, $shippingMethod);
            return response()->json(array_merge($result, ['summary' => $summary]));
        }

        return redirect()->route('user.cart')->with(
            $result['success'] ? 'success' : 'error',
            $result['message']
        );
    }

    public function remove(Request $request, $id)
    {
        $result = $this->cartService->removeItem((int)$id);

        if ($request->wantsJson()) {
            $promoCode = session('applied_promo');
            $shippingMethod = session('shipping_method', 'Regular');
            $summary = $this->cartService->calculateSummary($promoCode, $shippingMethod);
            return response()->json(array_merge($result, ['summary' => $summary]));
        }

        return redirect()->route('user.cart')->with('success', $result['message']);
    }

    public function applyPromo(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $code = strtoupper(trim($request->code));
        $shippingMethod = session('shipping_method', 'Regular');
        $summary = $this->cartService->calculateSummary($code, $shippingMethod);

        if ($summary['promo_code']) {
            session(['applied_promo' => $code]);
            return back()->with('success', $summary['promo_message']);
        }

        return back()->with('error', $summary['promo_message'] ?? 'Invalid promo code.');
    }

    public function removePromo()
    {
        session()->forget('applied_promo');
        return back()->with('success', 'Promo code removed.');
    }
}
