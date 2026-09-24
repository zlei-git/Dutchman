<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminBranchController extends Controller
{
    public function index()
    {
        $branches = Branch::withCount('bookings')->get();
        return view('admin.branches.index', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:branches,name',
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'opening_time' => 'required',
            'closing_time' => 'required',
            'slot_capacity' => 'required|integer|min:1|max:20',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = true;

        $branch = Branch::create($validated);

        AuditLog::log('CREATE_BRANCH', 'Branch', $branch->id, null, $branch->toArray());

        return back()->with('success', "Branch '{$branch->name}' added successfully.");
    }

    public function update(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:branches,name,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'opening_time' => 'required',
            'closing_time' => 'required',
            'slot_capacity' => 'required|integer|min:1|max:20',
            'status' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = $request->boolean('status', true);

        $old = $branch->toArray();
        $branch->update($validated);

        AuditLog::log('UPDATE_BRANCH', 'Branch', $branch->id, $old, $branch->toArray());

        return back()->with('success', "Branch '{$branch->name}' updated.");
    }

    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);
        if ($branch->bookings()->count() > 0) {
            return back()->with('error', 'Cannot delete branch that has booking history.');
        }

        AuditLog::log('DELETE_BRANCH', 'Branch', $branch->id, $branch->toArray(), null);
        $branch->delete();

        return back()->with('success', 'Branch deleted.');
    }
}
