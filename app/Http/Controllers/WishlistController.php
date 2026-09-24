<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $wishlists = Auth::user()->wishlists()
            ->with(['product.images', 'product.variants', 'product.category'])
            ->latest()
            ->paginate(12);

        return view('user.wishlist', compact('wishlists'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = Auth::user();
        $productId = $request->product_id;

        $existing = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $existing->delete();
            $added = false;
            $message = 'Product removed from your wishlist.';
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $productId,
            ]);
            $added = true;
            $message = 'Product saved to your wishlist!';
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'added' => $added,
                'message' => $message,
                'wishlist_count' => $user->wishlists()->count(),
            ]);
        }

        return back()->with('success', $message);
    }

    public function moveToCart(Request $request, $id)
    {
        $wishlist = Auth::user()->wishlists()->with('product.variants')->findOrFail($id);
        $product = $wishlist->product;

        // Pick first available variant
        $variant = $product->variants->where('stock', '>', 0)->first();

        if (!$variant) {
            return back()->with('error', 'This shoe is currently out of stock.');
        }

        $result = $this->cartService->addItem($variant->id, 1);

        if ($result['success']) {
            $wishlist->delete();
            return redirect()->route('user.cart')->with('success', "{$product->name} moved to your cart!");
        }

        return back()->with('error', $result['message']);
    }
}
