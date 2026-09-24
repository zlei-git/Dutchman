@extends('layouts.app')

@section('title', 'Ringkasan Reservasi — Dutchman Barbershop')

@section('content')
<section style="padding: 4.5rem 0 6.5rem; background: #FAF7F2; min-height: calc(100vh - 76px); display: flex; align-items: center; justify-content: center;">
    <div class="container" style="max-width: 640px;">

        <div class="card" style="background: #FFFFFF; border: 1px solid var(--border); border-radius: 8px; padding: 3rem 2.5rem; box-shadow: 0 10px 35px rgba(0,0,0,0.06);">
            
            <!-- Top Status Header -->
            <div style="text-align: center; border-bottom: 1.5px solid var(--border); padding-bottom: 2rem; margin-bottom: 2rem;">
                <div style="font-family: var(--font-serif); font-size: 0.95rem; font-style: italic; color: #777777; margin-bottom: 0.35rem;">
                    Dutchman Barbershop · Surabaya
                </div>
                <h1 style="font-family: var(--font-display); font-size: 2rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: #111111; margin: 0 0 0.75rem;">
                    RINGKASAN RESERVASI
                </h1>
                <div style="display: inline-flex; align-items: center; gap: 0.4rem; background: #111111; color: #FFFFFF; padding: 0.35rem 1rem; border-radius: 4px; font-family: var(--font-display); font-size: 0.85rem; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase;">
                    <span>Terkonfirmasi</span>
                    <span style="color: #4CAF50;">✓</span>
                </div>
                <div style="margin-top: 1rem; font-size: 0.85rem; color: #777777;">
                    No. Booking: <strong style="font-family: var(--font-display); color: #111111; letter-spacing: 0.05em; font-size: 0.95rem;">{{ $booking->booking_number }}</strong>
                </div>
            </div>

            <!-- Detailed Key-Value Sections -->
            <div style="display: flex; flex-direction: column; gap: 1.6rem; font-size: 0.95rem;">
                
                <!-- SERVICES -->
                <div>
                    <div style="font-family: var(--font-display); font-size: 0.82rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; color: #888888; margin-bottom: 0.65rem;">
                        LAYANAN
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        @forelse($booking->items as $item)
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div style="font-weight: 700; color: #111111;">
                                    {{ $item->service?->name ?? 'Gentlemen Haircut' }}
                                </div>
                                <div style="font-family: var(--font-display); font-weight: 700; color: #111111;">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </div>
                            </div>
                        @empty
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div style="font-weight: 700; color: #111111;">Standard Haircut</div>
                                <div style="font-family: var(--font-display); font-weight: 700; color: #111111;">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- ADD-ON -->
                @if($booking->addons->isNotEmpty())
                    <div style="border-top: 1px dashed var(--border); padding-top: 1.25rem;">
                        <div style="font-family: var(--font-display); font-size: 0.82rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; color: #888888; margin-bottom: 0.65rem;">
                            MINUMAN &amp; TAMBAHAN
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                            @foreach($booking->addons as $addon)
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div style="font-weight: 700; color: #111111;">
                                        {{ $addon->name }}
                                        @if($addon->quantity > 1)
                                            <span style="font-size: 0.8rem; color: #777777;">&times; {{ $addon->quantity }}</span>
                                        @endif
                                    </div>
                                    <div style="font-family: var(--font-display); font-weight: 700; color: #111111;">
                                        Rp {{ number_format($addon->subtotal, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- BARBER & MEJA -->
                <div style="border-top: 1px dashed var(--border); padding-top: 1.25rem;">
                    <div style="font-family: var(--font-display); font-size: 0.82rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; color: #888888; margin-bottom: 0.35rem;">
                        NOMOR MEJA &amp; KURSI
                    </div>
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div style="font-weight: 700; color: #111111; font-size: 1.05rem;">
                            Station Kursi {{ substr($booking->chair_code ?: 'A1', 1) }}
                        </div>
                        <span style="font-family: var(--font-display); font-size: 0.82rem; font-weight: 800; background: #111111; color: #FFFFFF; padding: 0.2rem 0.65rem; border-radius: 3px; letter-spacing: 0.05em;">
                            MEJA {{ $booking->chair_code ?: ($booking->barber?->chair_code ?: 'A1') }}
                        </span>
                    </div>
                </div>

                <!-- DATE -->
                <div style="border-top: 1px dashed var(--border); padding-top: 1.25rem;">
                    <div style="font-family: var(--font-display); font-size: 0.82rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; color: #888888; margin-bottom: 0.35rem;">
                        TANGGAL
                    </div>
                    <div style="font-weight: 700; color: #111111; font-size: 1.02rem;">
                        {{ $booking->booking_date->translatedFormat('l, d F Y') }}
                    </div>
                </div>

                <!-- TIME -->
                <div style="border-top: 1px dashed var(--border); padding-top: 1.25rem;">
                    <div style="font-family: var(--font-display); font-size: 0.82rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; color: #888888; margin-bottom: 0.35rem;">
                        JAM
                    </div>
                    <div style="font-weight: 700; color: #111111; font-size: 1.02rem;">
                        {{ $booking->booking_time }} WIB
                    </div>
                </div>

                <!-- DURATION -->
                <div style="border-top: 1px dashed var(--border); padding-top: 1.25rem;">
                    <div style="font-family: var(--font-display); font-size: 0.82rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; color: #888888; margin-bottom: 0.35rem;">
                        DURASI
                    </div>
                    <div style="font-weight: 700; color: #111111; font-size: 1.02rem;">
                        {{ $booking->duration_minutes }} menit
                    </div>
                </div>

                <!-- PAYMENT METHOD -->
                <div style="border-top: 1px dashed var(--border); padding-top: 1.25rem;">
                    <div style="font-family: var(--font-display); font-size: 0.82rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; color: #888888; margin-bottom: 0.35rem;">
                        METODE PEMBAYARAN
                    </div>
                    <div style="font-weight: 700; color: #111111; text-transform: uppercase; font-family: var(--font-display);">
                        {{ $booking->payment?->payment_method ? strtoupper(str_replace('_', ' ', $booking->payment->payment_method)) : 'MIDTRANS SNAP (ONLINE)' }}
                    </div>
                </div>

                <!-- PAYMENT STATUS -->
                <div style="border-top: 1px dashed var(--border); padding-top: 1.25rem;">
                    <div style="font-family: var(--font-display); font-size: 0.82rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; color: #888888; margin-bottom: 0.35rem;">
                        STATUS PEMBAYARAN
                    </div>
                    <div>
                        <span style="font-family: var(--font-display); font-weight: 800; font-size: 0.84rem; color: #2E7D32; background: #E8F5E9; padding: 0.25rem 0.75rem; border-radius: 3px; letter-spacing: 0.05em; text-transform: uppercase;">
                            {{ $booking->payment?->status === 'paid' || $booking->payment?->status === 'settlement' || $booking->payment?->status === 'capture' ? 'Lunas' : ($booking->payment?->status ? ucfirst($booking->payment->status) : 'Lunas') }}
                        </span>
                    </div>
                </div>

                <!-- TOTAL PAID -->
                <div style="border-top: 2px solid #111111; padding-top: 1.25rem; display: flex; justify-content: space-between; align-items: baseline;">
                    <div style="font-family: var(--font-display); font-size: 0.95rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; color: #111111;">
                        TOTAL DIBAYAR
                    </div>
                    <div style="font-family: var(--font-display); font-size: 1.85rem; font-weight: 900; color: #111111;">
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                    </div>
                </div>

            </div>

            <!-- Actions -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 2.75rem;">
                @php
                    $startDateTime = \Carbon\Carbon::parse($booking->booking_date->format('Y-m-d') . ' ' . $booking->booking_time);
                    $endDateTime = (clone $startDateTime)->addMinutes($booking->duration_minutes);
                    $calTitle = urlencode("Dutchman Barbershop — " . ($booking->items->first()?->service?->name ?? 'Haircut'));
                    $calDetails = urlencode("No. Booking: {$booking->booking_number}\nBarber: " . ($booking->barber?->name ?? 'Dutchman') . " (Meja {$booking->chair_code})\nAlamat: Jl. Rungkut Madya No.55A, Surabaya");
                    $calLocation = urlencode("Dutchman Barbershop, Jl. Rungkut Madya No.55A, Surabaya");
                    $googleCalUrl = "https://calendar.google.com/calendar/render?action=TEMPLATE&text={$calTitle}&dates=" . $startDateTime->format('Ymd\THis') . "/" . $endDateTime->format('Ymd\THis') . "&details={$calDetails}&location={$calLocation}";
                @endphp

                <a href="{{ $googleCalUrl }}" target="_blank" rel="noopener noreferrer" class="btn" style="background: #111111; color: #FFFFFF; font-weight: 800; font-size: 0.85rem; letter-spacing: 0.06em; text-transform: uppercase; border-radius: 4px; padding: 0.85rem 1rem;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                    <span>SIMPAN KE KALENDER</span>
                </a>

                <a href="https://wa.me/6282110009744?text={{ urlencode('Halo Dutchman Barbershop, saya ingin konfirmasi booking ID: ' . $booking->booking_number) }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline" style="border: 1px solid var(--border); color: #111111; font-weight: 700; font-size: 0.85rem; letter-spacing: 0.06em; text-transform: uppercase; border-radius: 4px; padding: 0.85rem 1rem;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <span>HUBUNGI BARBERSHOP</span>
                </a>
            </div>

            <div style="text-align: center; margin-top: 1.75rem;">
                <a href="{{ route('home') }}" style="color: #777777; font-size: 0.84rem; text-decoration: underline; font-weight: 600;">
                    &larr; Kembali ke Beranda Dutchman
                </a>
            </div>

        </div>

    </div>
</section>
@endsection
