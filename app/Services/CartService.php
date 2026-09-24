<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\Promotion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function getCart(): Cart
    {
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            // If there's an existing session cart before login, merge it
            $sessionId = Session::getId();
            $sessionCart = Cart::where('session_id', $sessionId)->whereNull('user_id')->first();
            if ($sessionCart) {
                foreach ($sessionCart->items as $item) {
                    $existingItem = CartItem::where('cart_id', $cart->id)
                        ->where('product_variant_id', $item->product_variant_id)
                        ->first();
                    if ($existingItem) {
                        $existingItem->quantity += $item->quantity;
                        $existingItem->save();
                    } else {
                        $item->cart_id = $cart->id;
                        $item->save();
                    }
                }
                $sessionCart->delete();
            }
            return $cart;
        }

        $sessionId = Session::getId();
        return Cart::firstOrCreate(['session_id' => $sessionId, 'user_id' => null]);
    }

    public function addItem(int $variantId, int $quantity = 1): array
    {
        $variant = ProductVariant::with('product')->find($variantId);
        if (!$variant) {
            return ['success' => false, 'message' => 'Product variant not found.'];
        }

        if ($variant->stock < $quantity) {
            return [
                'success' => false,
                'message' => "Only {$variant->stock} items available in stock for this size/color."
            ];
        }

        $cart = $this->getCart();
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_variant_id', $variantId)
            ->first();

        $effectivePrice = $variant->product->effective_price;

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($newQuantity > $variant->stock) {
                return [
                    'success' => false,
                    'message' => "Cannot add more. Stock limit ({$variant->stock}) reached."
                ];
            }
            $cartItem->quantity = $newQuantity;
            $cartItem->price = $effectivePrice;
            $cartItem->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_variant_id' => $variantId,
                'quantity' => $quantity,
                'price' => $effectivePrice,
            ]);
        }

        return ['success' => true, 'message' => "{$variant->product->name} added to cart!"];
    }

    public function updateQuantity(int $cartItemId, int $quantity): array
    {
        $cart = $this->getCart();
        $item = CartItem::where('cart_id', $cart->id)->where('id', $cartItemId)->first();

        if (!$item) {
            return ['success' => false, 'message' => 'Cart item not found.'];
        }

        if ($quantity <= 0) {
            $item->delete();
            return ['success' => true, 'message' => 'Item removed from cart.'];
        }

        $variant = $item->variant;
        if ($variant && $quantity > $variant->stock) {
            return [
                'success' => false,
                'message' => "Maximum available stock is {$variant->stock}."
            ];
        }

        $item->quantity = $quantity;
        // Refresh with latest product price
        if ($variant && $variant->product) {
            $item->price = $variant->product->effective_price;
        }
        $item->save();

        return ['success' => true, 'message' => 'Cart updated.'];
    }

    public function removeItem(int $cartItemId): array
    {
        $cart = $this->getCart();
        CartItem::where('cart_id', $cart->id)->where('id', $cartItemId)->delete();
        return ['success' => true, 'message' => 'Item removed from cart.'];
    }

    public function clearCart(): void
    {
        $cart = $this->getCart();
        $cart->items()->delete();
    }

    public function calculateSummary(?string $promoCode = null, string $shippingMethod = 'Regular'): array
    {
        $cart = $this->getCart();
        $cart->load(['items.variant.product.images']);

        $subtotal = 0;
        $itemsData = [];

        foreach ($cart->items as $item) {
            // Re-verify latest effective price from database
            $price = $item->variant->product->effective_price;
            $itemSubtotal = $price * $item->quantity;
            $subtotal += $itemSubtotal;

            $itemsData[] = [
                'id' => $item->id,
                'variant_id' => $item->product_variant_id,
                'product_name' => $item->variant->product->name,
                'slug' => $item->variant->product->slug,
                'size' => $item->variant->size,
                'color' => $item->variant->color,
                'price' => $price,
                'quantity' => $item->quantity,
                'stock' => $item->variant->stock,
                'subtotal' => $itemSubtotal,
                'image' => $item->variant->product->image_url,
            ];
        }

        // Calculate shipping fee
        $shippingFee = 25000; // Regular default
        if ($shippingMethod === 'Express') {
            $shippingFee = 45000;
        } elseif ($shippingMethod === 'Store Pickup') {
            $shippingFee = 0;
        }

        // Free shipping on orders >= Rp 1.000.000 for regular shipping
        if ($subtotal >= 1000000 && $shippingMethod === 'Regular') {
            $shippingFee = 0;
        }

        // Promo / Discount
        $discount = 0;
        $promoMessage = null;
        $appliedPromo = null;

        if ($promoCode) {
            $promo = Promotion::where('code', strtoupper(trim($promoCode)))->first();
            if ($promo) {
                $validation = $promo->isValidFor($subtotal);
                if ($validation['valid']) {
                    $discount = $promo->calculateDiscount($subtotal);
                    $appliedPromo = $promo;
                    $promoMessage = "Voucher '{$promo->code}' applied successfully!";
                } else {
                    $promoMessage = $validation['message'];
                }
            } else {
                $promoMessage = 'Invalid promo code.';
            }
        }

        $total = max(0, $subtotal - $discount + $shippingFee);

        return [
            'items' => $itemsData,
            'item_count' => $cart->items->sum('quantity'),
            'subtotal' => $subtotal,
            'discount' => $discount,
            'shipping_fee' => $shippingFee,
            'shipping_method' => $shippingMethod,
            'promo_code' => $appliedPromo ? $appliedPromo->code : null,
            'promo_message' => $promoMessage,
            'total' => $total,
        ];
    }
}
