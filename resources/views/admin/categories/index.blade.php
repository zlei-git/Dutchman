@extends('layouts.admin')

@section('title', 'Category Management — PAWMART')
@section('page_title', 'Pet Product Categories')

@section('content')
<div style="display: grid; grid-template-columns: 1fr 340px; gap: 2rem; align-items: start;">
    <!-- Categories List -->
    <div class="card" style="padding: 1.75rem;">
        <h3 style="font-size: 1.2rem; margin-bottom: 1.25rem;">Active Categories</h3>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Category Name</th>
                        <th>Slug</th>
                        <th>Products Count</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                        <tr>
                            <td>
                                <strong>{{ $cat->name }}</strong>
                                <div style="font-size: 0.75rem; color: var(--muted);">{{ $cat->description }}</div>
                            </td>
                            <td><code>{{ $cat->slug }}</code></td>
                            <td>
                                <strong>{{ $cat->products_count }}</strong> items
                            </td>
                            <td>
                                <span class="badge {{ $cat->status ? 'badge-success' : 'badge-danger' }}">
                                    {{ $cat->status ? 'Active' : 'Disabled' }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Delete category {{ $cat->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" style="padding: 0.2rem 0.6rem; font-size: 0.75rem;" {{ $cat->products_count > 0 ? 'disabled title="Cannot delete category with products"' : '' }}>
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--muted); padding: 2rem;">No categories defined yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Category Card -->
    <div class="card" style="padding: 1.75rem;">
        <h3 style="font-size: 1.15rem; margin-bottom: 1.25rem;">Add New Category</h3>

        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Category Name *</label>
                <input type="text" name="name" required class="form-input" placeholder="e.g. Training & Gym">
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-textarea" placeholder="Brief category description..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Add Category</button>
        </form>
    </div>
</div>

<style>
@media (max-width: 900px) {
    div[style*="grid-template-columns: 1fr 340px"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
