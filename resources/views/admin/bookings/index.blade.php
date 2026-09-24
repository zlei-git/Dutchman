@extends('layouts.admin')

@section('title', 'Semua Booking')
@section('page_title', 'Manajemen Janji Temu & Booking')

@section('admin_content')
<!-- Filter Ribbon -->
<div class="card" style="padding: 1.25rem; margin-bottom: 1.75rem;">
    <form action="{{ route('admin.bookings.index') }}" method="GET" style="display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr auto auto; gap: 0.75rem; align-items: end;">
        <div>
            <label style="display: block; font-size: 0.74rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.35rem;">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="No. Ref, Nama, No. HP..." style="width: 100%; height: 38px; padding: 0 0.75rem; font-size: 0.85rem;">
        </div>

        <div>
            <label style="display: block; font-size: 0.74rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.35rem;">Status</label>
            <select name="status" style="width: 100%; height: 38px; padding: 0 0.75rem; font-size: 0.85rem;">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Terkonfirmasi</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>

        <div>
            <label style="display: block; font-size: 0.74rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.35rem;">Barber</label>
            <select name="barber_id" style="width: 100%; height: 38px; padding: 0 0.75rem; font-size: 0.85rem;">
                <option value="">Semua Barber</option>
                @foreach($barbers as $b)
                    <option value="{{ $b->id }}" {{ request('barber_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label style="display: block; font-size: 0.74rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.35rem;">Tanggal</label>
            <input type="date" name="date" value="{{ request('date') }}" style="width: 100%; height: 38px; padding: 0 0.75rem; font-size: 0.85rem;">
        </div>

        <div>
            <button type="submit" class="btn btn-brass" style="height: 38px;">
                Terapkan Filter
            </button>
        </div>

        @if(request()->anyFilled(['search', 'status', 'barber_id', 'date']))
            <div>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline" style="height: 38px;">
                    Reset
                </a>
            </div>
        @endif
    </form>
</div>

<!-- Appointments Table -->
<div class="card" style="overflow: hidden;">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>No. Ref</th>
                    <th>Tanggal &amp; Jam</th>
                    <th>Pelanggan</th>
                    <th>Layanan &amp; Durasi</th>
                    <th>Barber</th>
                    <th>Total Biaya</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>
                            <strong style="font-family: var(--font-serif); font-size: 0.95rem; color: var(--brass);">
                                {{ $booking->booking_number }}
                            </strong>
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ $booking->booking_date->format('d M Y') }}</div>
                            <div style="font-size: 0.78rem; color: var(--brass);">{{ substr($booking->booking_time, 0, 5) }} WIB</div>
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ $booking->customer_name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $booking->customer_phone }}</div>
                        </td>
                        <td>
                            @foreach($booking->items as $item)
                                <div>{{ $item->service_name }}</div>
                                <div style="font-size: 0.72rem; color: var(--text-muted);">{{ $item->duration_minutes }} menit</div>
                            @endforeach
                        </td>
                        <td>
                            @if($booking->barber)
                                <div>{{ $booking->barber->name }}</div>
                            @else
                                <span class="badge badge-muted">Bebas / Auto</span>
                            @endif
                        </td>
                        <td>
                            <span style="font-family: var(--font-serif); font-weight: 600;">{{ $booking->formatted_price }}</span>
                        </td>
                        <td>
                            @if($booking->status === 'confirmed')
                                <span class="badge badge-success">TERKONFIRMASI</span>
                            @elseif($booking->status === 'pending')
                                <span class="badge badge-brass">MENUNGGU</span>
                            @elseif($booking->status === 'completed')
                                <span class="badge badge-muted">SELESAI</span>
                            @else
                                <span class="badge badge-danger">DIBATALKAN</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.4rem; align-items: center;">
                                @if($booking->status === 'pending')
                                    <form action="{{ route('admin.bookings.status', $booking->id) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="btn btn-sm" style="background: #2E7D32; color: #FFFFFF; font-weight: 700; border: none; height: 30px; padding: 0 0.65rem; cursor: pointer;" title="Setujui Reservasi">
                                            ✓ Terima
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.bookings.status', $booking->id) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" class="btn btn-danger btn-sm" style="height: 30px; padding: 0 0.55rem; font-weight: 700; cursor: pointer;" title="Tolak Reservasi">
                                            ✕ Tolak
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.bookings.status', $booking->id) }}" method="POST" style="display: flex; gap: 0.35rem;">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" style="height: 30px; font-size: 0.74rem; padding: 0 0.4rem; background: var(--main-bg);">
                                            <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Menunggu</option>
                                            <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Terkonfirmasi</option>
                                            <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                            <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                        </select>
                                        <button type="submit" class="btn btn-outline btn-sm" style="height: 30px; padding: 0 0.5rem; font-size: 0.75rem;">
                                            Ubah
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Hapus permanen booking #{{ $booking->booking_number }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" style="height: 30px; padding: 0 0.5rem; font-size: 0.75rem;" title="Hapus">
                                        &times;
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="padding: 3rem; text-align: center; color: var(--text-muted);">
                            Tidak ada data booking yang sesuai dengan filter pencarian.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($bookings->hasPages())
        <div style="padding: 1.25rem; border-top: 1px solid var(--border);">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
@endsection
