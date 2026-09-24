@extends('layouts.app')

@section('title', 'Pembayaran Kedaluwarsa — Dutchman Barbershop')

@section('content')
<section style="padding: 5rem 0 6.5rem; background: #FAF7F2; min-height: calc(100vh - 76px); display: flex; align-items: center; justify-content: center;">
    <div class="container" style="max-width: 540px;">
        <div class="card" style="background: #FFFFFF; border: 1px solid var(--border); border-radius: 8px; padding: 3.5rem 2.5rem; box-shadow: 0 10px 35px rgba(0,0,0,0.06); text-align: center;">

            <!-- Subtle Clock Icon -->
            <div style="width: 64px; height: 64px; border-radius: 50%; background: #FFF3E0; color: #E65100; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>

            <h1 style="font-family: var(--font-display); font-size: 2rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: #111111; margin: 0 0 0.75rem;">
                PEMBAYARAN KEDALUWARSA
            </h1>

            <p style="font-size: 1.05rem; color: #444444; margin: 0 0 0.35rem;">
                Sesi pembayaran Anda telah kedaluwarsa.
            </p>
            <p style="font-size: 0.92rem; color: #888888; margin-bottom: 2.25rem;">
                Silakan buat reservasi baru untuk mengonfirmasi jadwal Anda.
            </p>

            <div style="background: #F8F8F8; border: 1px solid var(--border); border-radius: 6px; padding: 1.25rem; font-size: 0.88rem; color: #666666; margin-bottom: 2rem;">
                No. Booking: <strong style="color: #111111;">{{ $booking->booking_number }}</strong>
            </div>

            <div>
                <a href="{{ route('booking.create') }}" class="btn" style="width: 100%; height: 48px; background: #111111; color: #FFFFFF; font-weight: 800; font-size: 0.92rem; letter-spacing: 0.08em; text-transform: uppercase; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                    BUAT RESERVASI BARU
                </a>
            </div>

        </div>
    </div>
</section>
@endsection
