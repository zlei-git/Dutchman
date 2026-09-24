<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Promotion;
use Illuminate\Http\Request;

class AdminPromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::latest()->get();
        return view('admin.promotions.index', compact('promotions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:promotions,code',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'type' => 'required|in:PERCENTAGE,FIXED',
            'value' => 'required|numeric|min:0',
            'min_spend' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'quota' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['is_active'] = true;

        $promo = Promotion::create($validated);

        AuditLog::log('CREATE_PROMO', 'Promotion', $promo->id, null, $promo->toArray());

        return back()->with('success', "Promotion '{$promo->code}' created.");
    }

    public function toggle($id)
    {
        $promo = Promotion::findOrFail($id);
        $promo->update(['is_active' => !$promo->is_active]);

        $status = $promo->is_active ? 'activated' : 'deactivated';
        AuditLog::log('TOGGLE_PROMO', 'Promotion', $promo->id, null, ['is_active' => $promo->is_active]);

        return back()->with('success', "Promotion '{$promo->code}' {$status}.");
    }

    public function destroy($id)
    {
        $promo = Promotion::findOrFail($id);
        AuditLog::log('DELETE_PROMO', 'Promotion', $promo->id, $promo->toArray(), null);
        $promo->delete();

        return back()->with('success', 'Promotion removed.');
    }
}
