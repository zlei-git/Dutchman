@extends('layouts.admin')

@section('title', 'User Management — PAWMART')
@section('page_title', 'Customer & User Accounts')

@section('content')
<div class="card" style="padding: 1.75rem;">
    <!-- Filters -->
    <div class="flex items-center justify-between flex-wrap gap-3" style="margin-bottom: 1.5rem;">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center gap-2 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email..." class="form-input" style="min-height: 40px; width: 240px;">
            <select name="role" class="form-select" style="min-height: 40px; width: 140px;" onchange="this.form.submit();">
                <option value="">All Roles</option>
                <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Customer (User)</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
            </select>
            <button type="submit" class="btn btn-sm btn-secondary">Filter</button>
        </form>
    </div>

    <!-- Users Table -->
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Contact</th>
                    <th>Role</th>
                    <th>Orders Count</th>
                    <th>Bookings Count</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem;">
                                    {{ substr($u->name, 0, 1) }}
                                </div>
                                <div>
                                    <strong>{{ $u->name }}</strong>
                                    <div style="font-size: 0.75rem; color: var(--muted);">Joined {{ $u->created_at->format('d M Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>{{ $u->email }}</div>
                            <div style="font-size: 0.75rem; color: var(--muted);">{{ $u->phone ?? 'No phone' }}</div>
                        </td>
                        <td>
                            <span class="badge {{ $u->role === 'admin' ? 'badge-primary' : 'badge-muted' }}">
                                {{ strtoupper($u->role) }}
                            </span>
                        </td>
                        <td>
                            <strong>{{ $u->orders_count }}</strong> order(s)
                        </td>
                        <td>
                            <strong>{{ $u->bookings_count }}</strong> booking(s)
                        </td>
                        <td>
                            <span class="badge {{ $u->status === 'active' ? 'badge-success' : 'badge-danger' }}">
                                {{ ucfirst($u->status) }}
                            </span>
                        </td>
                        <td>
                            @if($u->id !== auth()->id())
                                <form action="{{ route('admin.users.toggle', $u->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $u->status === 'active' ? 'btn-danger' : 'btn-secondary' }}" style="padding: 0.2rem 0.6rem; font-size: 0.75rem;">
                                        {{ $u->status === 'active' ? 'Suspend' : 'Activate' }}
                                    </button>
                                </form>
                            @else
                                <span style="font-size: 0.75rem; color: var(--muted);">Current Account</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--muted); padding: 3rem;">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $users->links() }}
    </div>
</div>
@endsection
