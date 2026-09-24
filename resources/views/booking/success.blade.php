@extends('layouts.app')

@section('title', 'Reservasi Berhasil — Dutchman Barbershop')

@section('content')
<section style="padding: 5rem 0 6.5rem; background: #FAF7F2; min-height: calc(100vh - 76px); display: flex; align-items: center; justify-content: center;">
    <div class="container" style="max-width: 580px;">

        <div class="card fade-in-up" style="background: #FFFFFF; border: 1px solid var(--border); border-radius: 8px; padding: 3.5rem 2.5rem; box-shadow: 0 10px 35px rgba(0,0,0,0.06); text-align: center;">

            <!-- Subtle Animated Checkmark (No confetti, elegant gentleman aesthetic) -->
            <div class="checkmark-wrapper" style="margin: 0 auto 1.75rem;">
                <svg class="subtle-checkmark" width="68" height="68" viewBox="0 0 52 52">
                    <circle class="checkmark-circle" cx="26" cy="26" r="24" fill="none" stroke="#111111" stroke-width="2"/>
                    <path class="checkmark-check" fill="none" stroke="#111111" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                </svg>
            </div>

            <!-- Title -->
            <h1 style="font-family: var(--font-display); font-size: 2.1rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: #111111; margin: 0 0 0.5rem;">
                RESERVASI BERHASIL
            </h1>
            <p style="font-size: 1.05rem; color: #555555; margin-bottom: 2.25rem;">
                Jadwal reservasi Anda telah berhasil dikonfirmasi.
            </p>

            <!-- Appointment Summary Details Block -->
            <div style="background: #F9F9F8; border: 1px solid var(--border); border-radius: 6px; padding: 1.75rem; text-align: left; display: flex; flex-direction: column; gap: 1.15rem; margin-bottom: 2.5rem; font-size: 0.92rem;">
                
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: #777777; font-size: 0.82rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em;">No. Booking</span>
                    <strong style="color: #111111; font-family: var(--font-display); font-size: 1.05rem; letter-spacing: 0.05em;">{{ $booking->booking_number }}</strong>
                </div>

                <div style="border-top: 1px dashed var(--border); padding-top: 0.85rem; display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <div style="color: #111111; font-weight: 700; font-size: 0.98rem;">
                            {{ $booking->items->pluck('service.name')->filter()->join(' + ') ?: 'Layanan Barbershop' }}
                            @if($booking->addons->isNotEmpty())
                                <span style="color: #777777; font-size: 0.82rem; display: block; font-weight: normal; margin-top: 0.15rem;">
                                    + {{ $booking->addons->pluck('name')->join(', ') }}
                                </span>
                            @endif
                        </div>
                        <div style="color: #666666; font-size: 0.84rem; margin-top: 0.2rem;">
                            Station Kursi · <span style="font-weight: 700; color: #111111;">MEJA {{ $booking->chair_code ?: ($booking->barber?->chair_code ?: 'A1') }}</span>
                        </div>
                    </div>
                </div>

                <div style="border-top: 1px dashed var(--border); padding-top: 0.85rem; display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: #777777;">Jadwal</span>
                    <strong style="color: #111111;">
                        {{ $booking->booking_date->translatedFormat('d F Y') }} · {{ $booking->booking_time }} WIB
                    </strong>
                </div>

                <div style="border-top: 1px dashed var(--border); padding-top: 0.85rem; display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: #777777;">Pembayaran</span>
                    <span style="font-family: var(--font-display); font-weight: 800; font-size: 0.82rem; color: #047857; background: #ECFDF5; border: 1px solid #A7F3D0; padding: 0.2rem 0.65rem; border-radius: 3px; letter-spacing: 0.05em; text-transform: uppercase;">
                        ✓ DISETUJUI ADMIN
                    </span>
                </div>

                <div style="border-top: 1px solid #111111; padding-top: 0.85rem; display: flex; justify-content: space-between; align-items: baseline;">
                    <span style="color: #111111; font-weight: 700; text-transform: uppercase; font-size: 0.85rem;">Total Biaya</span>
                    <strong style="font-family: var(--font-display); font-size: 1.35rem; color: #111111;">
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                    </strong>
                </div>

            </div>

            <!-- Single Action Button: VIEW BOOKING SUMMARY -->
            <div>
                <a href="{{ route('booking.summary', $booking->id) }}" class="btn" style="width: 100%; height: 50px; background: #111111; color: #FFFFFF; font-weight: 800; font-size: 0.95rem; letter-spacing: 0.08em; text-transform: uppercase; border-radius: 4px; box-shadow: 0 4px 16px rgba(0,0,0,0.25); display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <span>LIHAT RINGKASAN RESERVASI</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            </div>

        </div>

    </div>
</section>

<style>
    /* Subtle Fade-in Animation */
    .fade-in-up {
        animation: fadeInUp 0.5s ease-out forwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(16px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Subtle SVG Checkmark Draw Animation */
    .checkmark-wrapper {
        width: 68px;
        height: 68px;
    }

    .subtle-checkmark {
        display: block;
    }

    .checkmark-circle {
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }

    .checkmark-check {
        transform-origin: 50% 50%;
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        animation: stroke 0.35s cubic-bezier(0.65, 0, 0.45, 1) 0.5s forwards;
    }

    @keyframes stroke {
        100% {
            stroke-dashoffset: 0;
        }
    }
</style>
@endsection
