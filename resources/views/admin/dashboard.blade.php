@extends('layouts.admin')

@section('title', 'Ringkasan Studio')
@section('page_title', 'Ringkasan Studio & Jadwal Hari Ini')

@section('admin_content')

@if(isset($recentBookings) && $recentBookings->count() > 0)
    @php
        $latestB = $recentBookings->first();
        $isVeryRecent = $latestB && $latestB->created_at && $latestB->created_at->diffInHours(now()) < 12;
    @endphp
    @if($isVeryRecent)
        <div style="background: linear-gradient(90deg, rgba(194,166,117,0.18), rgba(194,166,117,0.06)); border: 1px solid rgba(194,166,117,0.4); border-radius: var(--radius-sm); padding: 1rem 1.4rem; margin-bottom: 1.75rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 0.85rem;">
                <div style="width: 38px; height: 38px; border-radius: 50%; background: var(--brass); color: #11110F; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;">
                    🔔
                </div>
                <div>
                    <div style="font-weight: 700; font-size: 0.95rem; color: var(--text);">
                        Pesanan Booking Baru Masuk: <span style="color: var(--brass);">{{ $latestB->customer_name }}</span> ({{ $latestB->booking_number }})
                    </div>
                    <div style="font-size: 0.82rem; color: var(--text-muted); margin-top: 0.15rem;">
                        {{ $latestB->items->first()?->service_name ?? 'Layanan Barbershop' }} • Meja {{ $latestB->chair_code ?: ($latestB->barber?->chair_code ?: 'A1') }} • Jadwal: {{ $latestB->booking_date ? $latestB->booking_date->format('d M Y') : '' }} jam {{ substr($latestB->booking_time, 0, 5) }} • {{ $latestB->formatted_total_price }} ({{ strtoupper($latestB->status) }})
                    </div>
                </div>
            </div>
            <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                @if($latestB->status === 'pending')
                    <form action="{{ route('admin.bookings.status', $latestB->id) }}" method="POST" style="margin: 0;">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="confirmed">
                        <button type="submit" class="btn btn-sm" style="background: #2E7D32; color: #FFFFFF; font-weight: 700; border: none; cursor: pointer;">
                            ✓ Terima (Setujui)
                        </button>
                    </form>
                    <form action="{{ route('admin.bookings.status', $latestB->id) }}" method="POST" style="margin: 0;">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="btn btn-sm btn-danger" style="font-weight: 700; cursor: pointer;">
                            ✕ Tolak
                        </button>
                    </form>
                @endif
                <a href="{{ route('admin.bookings.index') }}?search={{ $latestB->booking_number }}" class="btn btn-brass btn-sm" style="font-size: 0.8rem;">
                    Buka Detail &rarr;
                </a>
            </div>
        </div>
    @endif
@endif

<!-- Metric KPI Cards -->
<div class="grid grid-cols-5" style="gap: 1.25rem; margin-bottom: 2rem;">
    <div class="card" style="padding: 1.25rem;">
        <div style="font-size: 0.72rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700; letter-spacing: 0.05em;">Booking Hari Ini</div>
        <div style="font-family: var(--font-serif); font-size: 2rem; color: var(--text); margin-top: 0.25rem;">{{ $todayTotal }}</div>
        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.2rem;">Terjadwal hari ini</div>
    </div>

    <div class="card" style="padding: 1.25rem; border-left: 3px solid var(--success);">
        <div style="font-size: 0.72rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700; letter-spacing: 0.05em;">Terkonfirmasi</div>
        <div style="font-family: var(--font-serif); font-size: 2rem; color: var(--success); margin-top: 0.25rem;">{{ $todayConfirmed }}</div>
        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.2rem;">Siap dilayani</div>
    </div>

    <div class="card" style="padding: 1.25rem; border-left: 3px solid var(--brass);">
        <div style="font-size: 0.72rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700; letter-spacing: 0.05em;">Menunggu</div>
        <div style="font-family: var(--font-serif); font-size: 2rem; color: var(--brass); margin-top: 0.25rem;">{{ $todayPending }}</div>
        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.2rem;">Menunggu konfirmasi / hadir</div>
    </div>

    <div class="card" style="padding: 1.25rem;">
        <div style="font-size: 0.72rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700; letter-spacing: 0.05em;">Selesai</div>
        <div style="font-family: var(--font-serif); font-size: 2rem; color: var(--text); margin-top: 0.25rem;">{{ $todayCompleted }}</div>
        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.2rem;">Layanan selesai</div>
    </div>

    <div class="card" style="padding: 1.25rem; border-left: 3px solid var(--brass);">
        <div style="font-size: 0.72rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700; letter-spacing: 0.05em;">Pendapatan Hari Ini</div>
        <div style="font-family: var(--font-serif); font-size: 1.6rem; color: var(--brass); margin-top: 0.4rem;">
            Rp {{ number_format($todayRevenue, 0, ',', '.') }}
        </div>
        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.2rem;">Terkonfirmasi &amp; selesai</div>
    </div>
</div>

<!-- Today's Schedule Table -->
<div class="card" style="margin-bottom: 2.5rem; overflow: hidden;">
    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="font-size: 1.15rem;">Jadwal Kursi &amp; Booking Hari Ini</h3>
            <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.15rem;">Antrean janji temu waktu nyata untuk {{ today()->format('d M Y') }}</p>
        </div>
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline btn-sm">Semua Booking &rarr;</a>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Jam</th>
                    <th>No. Ref</th>
                    <th>Pelanggan</th>
                    <th>Layanan</th>
                    <th>Master Barber</th>
                    <th>Status</th>
                    <th>Aksi Cepat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($todayBookings as $b)
                    <tr>
                        <td style="font-weight: 700; color: var(--brass); font-size: 0.95rem;">
                            {{ substr($b->booking_time, 0, 5) }} WIB
                        </td>
                        <td>
                            <span style="font-family: var(--font-serif); letter-spacing: 0.05em; font-weight: 600;">{{ $b->booking_number }}</span>
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ $b->customer_name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $b->customer_phone }}</div>
                        </td>
                        <td>
                            @foreach($b->items as $item)
                                <div>{{ $item->service_name }} ({{ $item->duration_minutes }} mnt)</div>
                            @endforeach
                        </td>
                        <td>
                            @if($b->barber)
                                <div style="font-weight: 600;">{{ $b->barber->name }}</div>
                            @else
                                <span class="badge badge-muted">Barber Bebas</span>
                            @endif
                        </td>
                        <td>
                            @if($b->status === 'confirmed')
                                <span class="badge badge-success">TERKONFIRMASI</span>
                            @elseif($b->status === 'pending')
                                <span class="badge badge-brass">MENUNGGU</span>
                            @elseif($b->status === 'completed')
                                <span class="badge badge-muted">SELESAI</span>
                            @else
                                <span class="badge badge-danger">DIBATALKAN</span>
                            @endif
                        </td>
                        <td>
                            @if($b->status === 'pending')
                                <div style="display: flex; gap: 0.35rem; align-items: center;">
                                    <form action="{{ route('admin.bookings.status', $b->id) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="btn btn-sm" style="background: #2E7D32; color: #FFFFFF; font-weight: 700; border: none; height: 30px; padding: 0 0.65rem; cursor: pointer;" title="Setujui Reservasi">
                                            ✓ Terima
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.bookings.status', $b->id) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" class="btn btn-danger btn-sm" style="height: 30px; padding: 0 0.55rem; font-weight: 700; cursor: pointer;" title="Tolak Reservasi">
                                            ✕ Tolak
                                        </button>
                                    </form>
                                </div>
                            @else
                                <form action="{{ route('admin.bookings.status', $b->id) }}" method="POST" style="display: flex; gap: 0.4rem; align-items: center;">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" style="height: 30px; font-size: 0.75rem; padding: 0 0.5rem; background: var(--main-bg);">
                                        <option value="pending" {{ $b->status === 'pending' ? 'selected' : '' }}>Menunggu</option>
                                        <option value="confirmed" {{ $b->status === 'confirmed' ? 'selected' : '' }}>Terkonfirmasi</option>
                                        <option value="completed" {{ $b->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                        <option value="cancelled" {{ $b->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                    <button type="submit" class="btn btn-outline btn-sm" style="height: 30px; padding: 0 0.6rem; font-size: 0.75rem;">
                                        Ubah
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 2.5rem; text-align: center; color: var(--text-muted);">
                            Belum ada jadwal janji temu untuk hari ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Quick Stats & Recent Bookings -->
<div class="grid grid-cols-2" style="gap: 1.5rem;">
    <!-- Recent Activity -->
    <div class="card" style="padding: 1.5rem;">
        <h3 style="font-size: 1.15rem; margin-bottom: 1rem;">Booking Terbaru</h3>
        <div class="flex flex-col gap-3">
            @foreach($recentBookings as $rec)
                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border); font-size: 0.84rem;">
                    <div>
                        <div style="font-weight: 600;">{{ $rec->booking_number }} — {{ $rec->customer_name }}</div>
                        <div style="font-size: 0.76rem; color: var(--text-muted);">
                            {{ $rec->booking_date->format('d M') }} jam {{ substr($rec->booking_time, 0, 5) }} / {{ $rec->barber ? $rec->barber->name : 'Bebas' }}
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: 600; color: var(--brass);">{{ $rec->formatted_price }}</div>
                        <span class="badge {{ $rec->status === 'confirmed' ? 'badge-success' : 'badge-muted' }}" style="font-size: 0.65rem;">
                            @if($rec->status === 'confirmed')
                                TERKONFIRMASI
                            @elseif($rec->status === 'pending')
                                MENUNGGU
                            @elseif($rec->status === 'completed')
                                SELESAI
                            @else
                                DIBATALKAN
                            @endif
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Studio Roster Summary -->
    <div class="card" style="padding: 1.5rem;">
        <h3 style="font-size: 1.15rem; margin-bottom: 1rem;">Operasional Studio</h3>
        <div class="grid grid-cols-2" style="gap: 1rem; margin-bottom: 1.5rem;">
            <div style="background: var(--main-bg); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--border);">
                <div style="font-size: 0.75rem; color: var(--text-muted);">Barber Aktif</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: var(--text); margin-top: 0.25rem;">{{ $activeBarbersCount }}</div>
            </div>
            <div style="background: var(--main-bg); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--border);">
                <div style="font-size: 0.75rem; color: var(--text-muted);">Layanan Aktif</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: var(--text); margin-top: 0.25rem;">{{ $activeServicesCount }}</div>
            </div>
            <div style="background: var(--main-bg); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--border);">
                <div style="font-size: 0.75rem; color: var(--text-muted);">Total Pelanggan</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: var(--text); margin-top: 0.25rem;">{{ $customersCount }}</div>
            </div>
            <div style="background: var(--main-bg); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--border);">
                <div style="font-size: 0.75rem; color: var(--text-muted);">Total Semua Booking</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: var(--text); margin-top: 0.25rem;">{{ $allBookingsCount }}</div>
            </div>
        </div>

        <div style="display: flex; gap: 0.75rem;">
            <a href="{{ route('admin.services.index') }}" class="btn btn-outline btn-sm flex-1">Kelola Layanan</a>
            <a href="{{ route('admin.barbers.index') }}" class="btn btn-outline btn-sm flex-1">Kelola Barber</a>
        </div>
    </div>
</div>
@endsection
