<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Branch;
use App\Services\CartService;
use App\Services\MidtransService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class CheckoutController extends Controller
{
    protected CartService $cartService;
    protected OrderService $orderService;
    protected MidtransService $midtransService;

    public function __construct(CartService $cartService, OrderService $orderService, MidtransService $midtransService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
        $this->midtransService = $midtransService;
    }

    public function index()
    {
        $user = Auth::user();
        $promoCode = session('applied_promo');
        $shippingMethod = session('shipping_method', 'Regular');
        $summary = $this->cartService->calculateSummary($promoCode, $shippingMethod);

        if (empty($summary['items'])) {
            return redirect()->route('products.index')->with('error', 'Keranjang belanja Anda kosong. Silakan pilih produk terlebih dahulu.');
        }

        $defaultAddress = $user->addresses()->where('is_default', true)->first()
            ?? $user->addresses()->first();

        $branches = Branch::where('status', true)->get();
        $snapClientKey = $this->midtransService->getClientKey();
        $snapJsUrl = $this->midtransService->getSnapJsUrl();

        return view('user.checkout', compact('summary', 'user', 'defaultAddress', 'branches', 'snapClientKey', 'snapJsUrl'));
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'district' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'shipping_method' => 'required|in:Regular,Express,Store Pickup',
            'branch_id' => 'nullable|required_if:shipping_method,Store Pickup|exists:branches,id',
            'payment_method' => 'required|in:Midtrans Snap,Bank Transfer,QRIS Demo,E-Wallet Demo,Cash on Delivery',
            'notes' => 'nullable|string|max:500',
            'save_address' => 'nullable|boolean',
        ]);

        $user = Auth::user();

        // Optionally save address to user's address book
        if ($request->boolean('save_address')) {
            Address::firstOrCreate([
                'user_id' => $user->id,
                'recipient_name' => $validated['recipient_name'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'district' => $validated['district'] ?? null,
                'postal_code' => $validated['postal_code'] ?? '-',
            ], [
                'label' => 'Alamat Pengiriman',
                'is_default' => $user->addresses()->count() === 0,
            ]);
        }

        $validated['promo_code'] = session('applied_promo');

        try {
            $order = $this->orderService->processCheckout($user, $validated);
            session()->forget('applied_promo');
            session()->forget('shipping_method');

            return redirect()->route('user.orders.show', $order->id)
                ->with('success', "Pesanan #{$order->order_number} berhasil dibuat! Silakan lakukan pembayaran.");
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
