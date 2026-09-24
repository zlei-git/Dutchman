<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $allBookings = $user->bookings()
            ->with(['items.service', 'barber'])
            ->latest('booking_date')
            ->get();

        $today = today()->format('Y-m-d');

        $upcomingBookings = $allBookings->filter(function ($b) use ($today) {
            return $b->booking_date->format('Y-m-d') >= $today && !in_array($b->status, ['completed', 'cancelled']);
        });

        $pastBookings = $allBookings->reject(function ($b) use ($upcomingBookings) {
            return $upcomingBookings->contains('id', $b->id);
        });

        return view('user.profile', compact('user', 'allBookings', 'upcomingBookings', 'pastBookings'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini yang Anda masukkan tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Kata sandi berhasil diperbarui.');
    }

    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:50',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'district' => 'nullable|string|max:100',
            'postal_code' => 'required|string|max:10',
            'is_default' => 'nullable|boolean',
        ]);

        $user = Auth::user();
        $isDefault = $request->boolean('is_default') || $user->addresses()->count() === 0;

        if ($isDefault) {
            $user->addresses()->update(['is_default' => false]);
        }

        $validated['user_id'] = $user->id;
        $validated['is_default'] = $isDefault;

        Address::create($validated);

        return back()->with('success', 'Address added successfully.');
    }

    public function deleteAddress($id)
    {
        $address = Auth::user()->addresses()->findOrFail($id);
        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $first = Auth::user()->addresses()->first();
            if ($first) {
                $first->update(['is_default' => true]);
            }
        }

        return back()->with('success', 'Address removed.');
    }

    public function setDefaultAddress($id)
    {
        $user = Auth::user();
        $user->addresses()->update(['is_default' => false]);
        $user->addresses()->where('id', $id)->update(['is_default' => true]);

        return back()->with('success', 'Default address updated.');
    }
}
