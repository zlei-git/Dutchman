@extends('layouts.app')

@section('title', 'Jadwal Booking Saya — Dutchman Barbershop')

@section('content')
<section style="padding: 3rem 0 5rem;">
    <div class="container" style="max-width: 900px;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
            <div>
                <div style="display: inline-block; background: var(--coral-soft); color: var(--coral); font-weight: 800; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.08em; padding: 0.35rem 0.85rem; border-radius: var(--radius-full); margin-bottom: 0.5rem; transform: rotate(-1.5deg);">
                    ★ RESERVASI SAYA
                </div>
                <h1 style="font-family: var(--font-display); font-size: 2.4rem;">Jadwal Booking Kursi Saya</h1>
                <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.25rem;">
                    Kelola sesi perawatan mendatang dan pantau riwayat kunjungan Anda.
                </p>
            </div>
            <a href="{{ route('booking.create') }}" class="btn btn-coral">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <span>Reservasi Jadwal Baru</span>
            </a>
        </div>

        <!-- UPCOMING APPOINTMENTS -->
        <div style="margin-bottom: 3.5rem;">
            <div style="font-family: var(--font-display); font-size: 1.4rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.75rem;">
                <span>Jadwal Mendatang</span>
                <span class="badge badge-coral">{{ $upcomingBookings->count() }}</span>
            </div>

            @forelse($upcomingBookings as $booking)
                <div class="card" style="padding: 1.75rem; margin-bottom: 1.25rem; background: var(--surface); box-shadow: var(--shadow-sm);">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem; margin-bottom: 1rem;">
                        <div>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <span style="font-family: var(--font-display); font-size: 1.3rem; color: var(--coral); font-weight: 800;">
                                    {{ $booking->booking_number }}
                                </span>
                                @if($booking->status === 'confirmed')
                                    <span class="badge badge-success">TERKONFIRMASI ADMIN</span>
                                @elseif($booking->status === 'pending')
                                    <a href="{{ route('booking.summary.before', $booking->id) }}" class="badge" style="background: #FEF3C7; color: #B45309; border: 1px solid #FCD34D; text-decoration: none;" title="Klik untuk pantau persetujuan admin">
                                        ⏳ MENUNGGU PERSETUJUAN ADMIN
                                    </a>
                                @endif
                            </div>
                            <div style="font-size: 1.05rem; font-weight: 700; margin-top: 0.35rem; color: var(--text);">
                                {{ $booking->booking_date->translatedFormat('l, d F Y') }} pukul {{ substr($booking->booking_time, 0, 5) }} WIB
                            </div>
                        </div>

                        <div style="text-align: right;">
                            <div style="font-family: var(--font-display); font-size: 1.4rem; color: var(--coral); font-weight: 700;">
                                {{ $booking->formatted_price }}
                            </div>
                            <div style="font-size: 0.78rem; color: var(--text-muted); font-weight: 600;">Bayar di studio</div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; background: var(--bg); padding: 1.25rem; border-radius: var(--radius-sm); margin-bottom: 1.25rem; font-size: 0.9rem;">
                        <div>
                            <span style="color: var(--text-muted); display: block; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">Layanan</span>
                            @foreach($booking->items as $item)
                                <strong style="color: var(--text);">{{ $item->service_name }} ({{ $item->duration_minutes }} menit)</strong>
                            @endforeach
                        </div>
                        <div>
                            <span style="color: var(--text-muted); display: block; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">Barber</span>
                            <strong style="color: var(--text);">{{ $booking->barber ? $booking->barber->name : 'Barber Bebas (Tersedia)' }}</strong>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                        <a href="{{ route('booking.confirmation', $booking->booking_number) }}" class="btn btn-outline btn-sm">
                            <span>Lihat Bukti Reservasi</span>
                        </a>

                        <form action="{{ route('user.bookings.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan reservasi #{{ $booking->booking_number }}?');">
                            @csrf
                            <button type="submit" class="btn btn-outline btn-sm" style="color: var(--danger); border-color: rgba(239, 68, 68, 0.4);">
                                Batalkan Reservasi
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="card" style="padding: 2.5rem; text-align: center; color: var(--text-muted); background: var(--surface);">
                    <p style="margin-bottom: 1rem;">Anda belum memiliki jadwal reservasi mendatang.</p>
                    <a href="{{ route('booking.create') }}" class="btn btn-coral btn-sm">Pesan Kursi Sekarang</a>
                </div>
            @endforelse
        </div>

        <!-- PAST / COMPLETED APPOINTMENTS -->
        <div>
            <div style="font-family: var(--font-display); font-size: 1.4rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.75rem;">
                <span>Riwayat Selesai</span>
                <span class="badge badge-muted">{{ $pastBookings->count() }}</span>
            </div>

            @forelse($pastBookings as $booking)
                <div class="card" style="padding: 1.5rem; margin-bottom: 1rem; opacity: 0.9; background: var(--surface);">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                        <div>
                            <div style="font-weight: 700; color: var(--text); font-size: 1rem;">{{ $booking->booking_number }} &bull; {{ $booking->booking_date->translatedFormat('d M Y') }}</div>
                            <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.2rem;">
                                {{ $booking->items->pluck('service_name')->join(', ') }} bersama {{ $booking->barber ? $booking->barber->name : 'Staff Barber' }}
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <span class="badge badge-muted">{{ strtoupper($booking->status) }}</span>
                            <span style="font-weight: 700; color: var(--text);">{{ $booking->formatted_price }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <p style="color: var(--text-muted); font-size: 0.9rem;">Belum ada riwayat kunjungan terdahulu.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
