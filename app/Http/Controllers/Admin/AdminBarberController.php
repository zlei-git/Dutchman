<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminBarberController extends Controller
{
    public function index()
    {
        $barbers = Barber::withCount('bookings')->orderBy('sort_order')->get();
        return view('admin.barbers.index', compact('barbers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'specialty' => 'required|string|max:150',
            'experience_years' => 'required|integer|min:1|max:50',
            'bio' => 'nullable|string',
            'photo' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . rand(100, 999);
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['photo'] = $validated['photo'] ?? 'images/barbershop/barber-andre.jpg';

        Barber::create($validated);

        return redirect()->route('admin.barbers.index')->with('success', 'Master barber baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $barber = Barber::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'specialty' => 'required|string|max:150',
            'experience_years' => 'required|integer|min:1|max:50',
            'bio' => 'nullable|string',
            'photo' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $barber->update($validated);

        return redirect()->route('admin.barbers.index')->with('success', 'Profil master barber berhasil diperbarui.');
    }

    public function toggleMaintenance($id)
    {
        $barber = Barber::findOrFail($id);
        $barber->is_maintenance = !$barber->is_maintenance;
        $barber->save();

        $chair = $barber->chair_code ?: 'A' . $barber->id;
        $statusMsg = $barber->is_maintenance 
            ? "Status Meja {$chair} berhasil diubah ke 'Sedang Diperbaiki'." 
            : "Status Meja {$chair} berhasil diaktifkan kembali ('Tersedia').";

        return redirect()->back()->with('success', $statusMsg);
    }

    public function destroy($id)
    {
        $barber = Barber::findOrFail($id);
        $name = $barber->name;
        $barber->delete();

        return redirect()->route('admin.barbers.index')->with('success', "Master barber '{$name}' berhasil dihapus.");
    }
}
