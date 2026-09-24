<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
        ]);

        $category = Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'image' => $validated['image'] ?? null,
            'status' => true,
        ]);

        AuditLog::log('CREATE_CATEGORY', 'Category', $category->id, null, $category->toArray());

        return back()->with('success', "Category '{$category->name}' created.");
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $id,
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        $old = $category->toArray();
        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'image' => $validated['image'] ?? $category->image,
            'status' => $request->boolean('status', true),
        ]);

        AuditLog::log('UPDATE_CATEGORY', 'Category', $category->id, $old, $category->toArray());

        return back()->with('success', "Category '{$category->name}' updated.");
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete category that still has associated products.');
        }

        AuditLog::log('DELETE_CATEGORY', 'Category', $category->id, $category->toArray(), null);
        $category->delete();

        return back()->with('success', 'Category deleted.');
    }
}
