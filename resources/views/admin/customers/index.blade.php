@extends('layouts.admin')

@section('title', 'Pelanggan')
@section('page_title', 'Daftar Pelanggan')

@section('admin_content')
<!-- Search Ribbon -->
<div class="card" style="padding: 1.25rem; margin-bottom: 1.75rem;">
    <form action="{{ route('admin.customers.index') }}" method="GET" style="display: flex; gap: 0.75rem; align-items: center;">
        <div style="flex: 1;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pelanggan berdasarkan nama, email, atau no. telepon..." style="width: 100%; height: 38px; padding: 0 0.75rem; font-size: 0.85rem;">
        </div>
        <button type="submit" class="btn btn-brass" style="height: 38px;">
            Cari
        </button>
        @if(request('search'))
            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline" style="height: 38px;">
                Reset
            </a>
        @endif
    </form>
</div>

<!-- Customer Table -->
<div class="card" style="overflow: hidden;">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nama Pelanggan</th>
                    <th>Alamat Email</th>
                    <th>WhatsApp / No. HP</th>
                    <th>Total Kunjungan</th>
                    <th>Terdaftar Sejak</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $c)
                    <tr>
                        <td>
                            <strong style="color: var(--text);">{{ $c->name }}</strong>
                        </td>
                        <td>{{ $c->email }}</td>
                        <td>{{ $c->phone ?? '—' }}</td>
                        <td>
                            <span class="badge badge-brass">{{ $c->bookings_count }} Kunjungan</span>
                        </td>
                        <td>{{ $c->created_at->format('d M Y') }}</td>
                        <td>
                            <span class="badge {{ $c->status === 'active' ? 'badge-success' : 'badge-danger' }}">
                                {{ $c->status === 'active' ? 'AKTIF' : 'SUSPEND' }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.customers.toggle', $c->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline btn-sm">
                                    {{ $c->status === 'active' ? 'Tangguhkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 3rem; text-align: center; color: var(--text-muted);">
                            Tidak ada data pelanggan yang terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($customers->hasPages())
        <div style="padding: 1.25rem; border-top: 1px solid var(--border);">
            {{ $customers->links() }}
        </div>
    @endif
</div>
@endsection
