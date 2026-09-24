@extends('layouts.app')

@section('title', 'Pembayaran Gagal — Dutchman Barbershop')

@section('content')
<section style="padding: 5rem 0 6.5rem; background: #FAF7F2; min-height: calc(100vh - 76px); display: flex; align-items: center; justify-content: center;">
    <div class="container" style="max-width: 540px;">
        <div class="card" style="background: #FFFFFF; border: 1px solid var(--border); border-radius: 8px; padding: 3.5rem 2.5rem; box-shadow: 0 10px 35px rgba(0,0,0,0.06); text-align: center;">

            <!-- Subtle Error Icon -->
            <div style="width: 64px; height: 64px; border-radius: 50%; background: #FFEBEE; color: #C62828; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </div>

            <h1 style="font-family: var(--font-display); font-size: 2rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: #111111; margin: 0 0 0.75rem;">
                PEMBAYARAN GAGAL
            </h1>

            <p style="font-size: 1.05rem; color: #444444; margin: 0 0 0.35rem;">
                Pembayaran Anda tidak dapat diselesaikan.
            </p>
            <p style="font-size: 0.92rem; color: #888888; margin-bottom: 2.25rem;">
                Jadwal reservasi Anda belum terkonfirmasi.
            </p>

            <div style="background: #F8F8F8; border: 1px solid var(--border); border-radius: 6px; padding: 1.25rem; font-size: 0.88rem; color: #666666; margin-bottom: 2rem;">
                No. Booking: <strong style="color: #111111;">{{ $booking->booking_number }}</strong> · Total: <strong>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                <a href="{{ route('booking.summary.before', $booking->id) }}" class="btn" style="width: 100%; height: 48px; background: #111111; color: #FFFFFF; font-weight: 800; font-size: 0.92rem; letter-spacing: 0.08em; text-transform: uppercase; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                    COBA LAGI
                </a>
                <a href="{{ route('booking.create') }}" class="btn btn-outline" style="width: 100%; height: 48px; border: 1px solid var(--border); color: #111111; font-weight: 700; font-size: 0.92rem; letter-spacing: 0.06em; text-transform: uppercase; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                    KEMBALI KE RESERVASI
                </a>
            </div>

        </div>
    </div>
</section>
@endsection
