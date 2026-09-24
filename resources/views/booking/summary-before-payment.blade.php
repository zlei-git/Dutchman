@extends('layouts.app')

@section('title', 'Menunggu Persetujuan Admin — Dutchman Barbershop')

@section('content')
<section style="padding: 4.5rem 0 6rem; background: #FAF7F2; min-height: calc(100vh - 76px); display: flex; align-items: center;">
    <div class="container" style="max-width: 680px;">
        
        <!-- Status Card -->
        <div class="card" style="background: #FFFFFF; border: 1px solid var(--border); border-radius: 8px; padding: 2.75rem 2.5rem; box-shadow: 0 8px 30px rgba(0,0,0,0.06);">
            
            <!-- Header -->
            <div style="text-align: center; margin-bottom: 2rem; border-bottom: 1.5px solid var(--border); padding-bottom: 1.75rem;">
                <div style="font-family: var(--font-serif); font-size: 0.95rem; font-style: italic; color: #777777; margin-bottom: 0.35rem;">
                    Dutchman Barbershop · Surabaya
                </div>
                <h1 style="font-family: var(--font-display); font-size: 1.85rem; font-weight: 800; letter-spacing: 0.06em; text-transform: uppercase; color: #111111; margin: 0;">
                    RINGKASAN BOOKING
                </h1>
                <div style="font-size: 0.82rem; color: #888888; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; margin-top: 0.4rem;">
                    No. Referensi: <span style="color: #111111; font-weight: 800;">{{ $booking->booking_number }}</span>
                </div>
            </div>

            <!-- Real-time Admin Approval Status Banner -->
            <div id="statusBanner" style="margin-bottom: 2rem; padding: 1.25rem 1.5rem; border-radius: 6px; {{ $booking->status === 'confirmed' ? 'background: #ECFDF5; border: 1.5px solid #10B981;' : ($booking->status === 'cancelled' ? 'background: #FEF2F2; border: 1.5px solid #EF4444;' : 'background: #FFFBEB; border: 1.5px solid #F59E0B;') }}">
                
                @if($booking->status === 'confirmed')
                    <div style="display: flex; align-items: center; gap: 0.85rem;">
                        <div style="width: 38px; height: 38px; border-radius: 50%; background: #10B981; color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; flex-shrink: 0; font-weight: 800;">
                            ✓
                        </div>
                        <div>
                            <div style="font-family: var(--font-display); font-weight: 800; font-size: 1rem; color: #065F46; text-transform: uppercase; letter-spacing: 0.05em;">
                                RESERVASI DISETUJUI ADMIN
                            </div>
                            <div style="font-size: 0.85rem; color: #047857; margin-top: 0.15rem; line-height: 1.45;">
                                Jadwal Anda telah dikonfirmasi oleh tim Dutchman Barbershop. Sampai jumpa di studio!
                            </div>
                        </div>
                    </div>
                @elseif($booking->status === 'cancelled')
                    <div style="display: flex; align-items: center; gap: 0.85rem;">
                        <div style="width: 38px; height: 38px; border-radius: 50%; background: #EF4444; color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; flex-shrink: 0; font-weight: 800;">
                            &times;
                        </div>
                        <div>
                            <div style="font-family: var(--font-display); font-weight: 800; font-size: 1rem; color: #991B1B; text-transform: uppercase; letter-spacing: 0.05em;">
                                RESERVASI TIDAK DAPAT DITERIMA
                            </div>
                            <div style="font-size: 0.85rem; color: #B91C1C; margin-top: 0.15rem; line-height: 1.45;">
                                Mohon maaf, jadwal pada jam tersebut sedang penuh atau berhalangan. Silakan pilih jadwal lain atau hubungi WhatsApp kami.
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Status: PENDING (Waiting for Admin) -->
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div class="pending-spinner-wrap" style="position: relative; width: 42px; height: 42px; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                            <div class="pulse-ring"></div>
                            <div style="width: 14px; height: 14px; border-radius: 50%; background: #D97706;"></div>
                        </div>
                        <div style="flex: 1;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                                <span style="font-family: var(--font-display); font-weight: 800; font-size: 0.98rem; color: #92400E; text-transform: uppercase; letter-spacing: 0.05em;">
                                    MENUNGGU PERSETUJUAN ADMIN
                                </span>
                                <span style="font-size: 0.7rem; font-weight: 700; background: #FEF3C7; color: #B45309; border: 1px solid #FCD34D; padding: 0.15rem 0.5rem; border-radius: 12px; letter-spacing: 0.04em;">
                                    LIVE CHECK
                                </span>
                            </div>
                            <div style="font-size: 0.86rem; color: #78350F; margin-top: 0.25rem; line-height: 1.5;">
                                Reservasi Anda telah terkirim. Admin studio Dutchman sedang meninjau ketersediaan slot. Halaman ini akan <strong>otomatis terupdate</strong> begitu admin menyetujui reservasi Anda.
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            <!-- Content Details -->
            <div style="display: flex; flex-direction: column; gap: 1.5rem; font-size: 0.95rem;">
                
                <!-- Services -->
                <div>
                    <div style="font-family: var(--font-display); font-size: 0.8rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; color: #777777; margin-bottom: 0.65rem;">
                        LAYANAN YANG DIPILIH
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        @forelse($booking->items as $item)
                            <div style="display: flex; justify-content: space-between; align-items: center; color: #222222;">
                                <div style="font-weight: 600;">
                                    {{ $item->service?->name ?? 'Layanan Gentleman' }}
                                    <span style="font-size: 0.78rem; color: #888888; font-weight: 400; margin-left: 0.35rem;">({{ $item->duration }} menit)</span>
                                </div>
                                <div style="font-family: var(--font-display); font-weight: 700; color: #111111;">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </div>
                            </div>
                        @empty
                            <div style="display: flex; justify-content: space-between; align-items: center; color: #222222;">
                                <span style="font-weight: 600;">Perawatan Standar</span>
                                <span style="font-family: var(--font-display); font-weight: 700;">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Add-on -->
                @if($booking->addons->isNotEmpty())
                    <div style="border-top: 1px dashed var(--border); padding-top: 1.25rem;">
                        <div style="font-family: var(--font-display); font-size: 0.8rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; color: #777777; margin-bottom: 0.65rem;">
                            MINUMAN &amp; PERAWATAN TAMBAHAN
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                            @foreach($booking->addons as $addon)
                                <div style="display: flex; justify-content: space-between; align-items: center; color: #222222;">
                                    <div style="font-weight: 600;">
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

                <!-- Barber & Station -->
                <div style="border-top: 1px dashed var(--border); padding-top: 1.25rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div>
                        <div style="font-family: var(--font-display); font-size: 0.78rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: #777777; margin-bottom: 0.35rem;">
                            NOMOR MEJA &amp; KURSI
                        </div>
                        <div style="color: #111111; font-weight: 700; font-size: 1.02rem;">
                            {{ $booking->barber ? $booking->barber->name : 'Pilih Otomatis' }}
                        </div>
                        <div style="display: inline-block; font-family: var(--font-display); font-size: 0.78rem; font-weight: 800; background: #111111; color: #FFFFFF; padding: 0.15rem 0.55rem; border-radius: 3px; margin-top: 0.25rem; letter-spacing: 0.05em;">
                            MEJA {{ $booking->chair_code ?: ($booking->barber?->chair_code ?: 'A1') }}
                        </div>
                    </div>

                    <div>
                        <div style="font-family: var(--font-display); font-size: 0.78rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: #777777; margin-bottom: 0.35rem;">
                            ESTIMASI DURASI
                        </div>
                        <div style="color: #111111; font-weight: 700; font-size: 1.02rem;">
                            {{ $booking->duration_minutes }} menit
                        </div>
                        <div style="font-size: 0.78rem; color: #777777; margin-top: 0.25rem;">
                            Sesi eksklusif untuk Anda
                        </div>
                    </div>
                </div>

                <!-- Date & Time -->
                <div style="border-top: 1px dashed var(--border); padding-top: 1.25rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div>
                        <div style="font-family: var(--font-display); font-size: 0.78rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: #777777; margin-bottom: 0.35rem;">
                            TANGGAL KUNJUNGAN
                        </div>
                        <div style="color: #111111; font-weight: 700; font-size: 1.02rem;">
                            {{ $booking->booking_date->translatedFormat('d F Y') }}
                        </div>
                        <div style="font-size: 0.78rem; color: #777777; margin-top: 0.15rem;">
                            {{ $booking->booking_date->translatedFormat('l') }}
                        </div>
                    </div>

                    <div>
                        <div style="font-family: var(--font-display); font-size: 0.78rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: #777777; margin-bottom: 0.35rem;">
                            JAM KUNJUNGAN
                        </div>
                        <div style="color: #111111; font-weight: 700; font-size: 1.02rem;">
                            {{ substr($booking->booking_time, 0, 5) }} WIB
                        </div>
                        <div style="font-size: 0.78rem; color: #777777; margin-top: 0.15rem;">
                            Harap hadir 5 menit sebelumnya
                        </div>
                    </div>
                </div>

                <!-- Customer Info -->
                <div style="border-top: 1px dashed var(--border); padding-top: 1.25rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div>
                        <div style="font-family: var(--font-display); font-size: 0.78rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: #777777; margin-bottom: 0.35rem;">
                            NAMA PEMESAN
                        </div>
                        <div style="color: #111111; font-weight: 700;">
                            {{ $booking->customer_name }}
                        </div>
                    </div>

                    <div>
                        <div style="font-family: var(--font-display); font-size: 0.78rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: #777777; margin-bottom: 0.35rem;">
                            WHATSAPP
                        </div>
                        <div style="color: #111111; font-weight: 700;">
                            {{ $booking->customer_phone }}
                        </div>
                    </div>
                </div>

                <!-- Total Amount -->
                <div style="border-top: 2px solid #111111; margin-top: 0.5rem; padding-top: 1.25rem; display: flex; justify-content: space-between; align-items: baseline;">
                    <div>
                        <div style="font-family: var(--font-display); font-size: 0.92rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: #111111;">
                            TOTAL BIAYA
                        </div>
                        <div style="font-size: 0.78rem; color: #777777;">
                            Bayar di tempat / kasir studio
                        </div>
                    </div>
                    <div style="font-family: var(--font-display); font-size: 1.85rem; font-weight: 900; color: #111111;">
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                    </div>
                </div>

            </div>

            <!-- Action Buttons -->
            <div style="margin-top: 2.5rem; display: flex; flex-direction: column; gap: 0.85rem;">
                
                @if($booking->status === 'confirmed')
                    <a href="{{ route('booking.success', $booking->id) }}" class="btn" style="width: 100%; height: 50px; background: #111111; color: #FFFFFF; font-weight: 800; font-size: 0.95rem; letter-spacing: 0.06em; text-transform: uppercase; border-radius: 4px; display: flex; align-items: center; justify-content: center; gap: 0.5rem; text-decoration: none;">
                        <span>LIHAT TIKET &amp; BUKTI RESERVASI &rarr;</span>
                    </a>
                @elseif($booking->status === 'cancelled')
                    <a href="{{ route('booking.create') }}" class="btn" style="width: 100%; height: 50px; background: #111111; color: #FFFFFF; font-weight: 800; font-size: 0.95rem; letter-spacing: 0.06em; text-transform: uppercase; border-radius: 4px; display: flex; align-items: center; justify-content: center; gap: 0.5rem; text-decoration: none;">
                        <span>PILIH JADWAL LAIN</span>
                    </a>
                @else
                    @php
                        $waText = urlencode("Halo Dutchman Barbershop, saya ingin konfirmasi reservasi booking nomor {$booking->booking_number} atas nama {$booking->customer_name} pada tanggal " . $booking->booking_date->format('d M Y') . " jam " . substr($booking->booking_time, 0, 5) . " WIB.");
                    @endphp
                    <button type="button" id="pay-button" class="btn" style="width: 100%; height: 50px; background: #111111; color: #FFFFFF; font-weight: 800; font-size: 0.92rem; letter-spacing: 0.06em; text-transform: uppercase; border-radius: 4px; display: flex; align-items: center; justify-content: center; gap: 0.5rem; border: none; cursor: pointer;">
                        <span id="pay-button-text">BAYAR SEKARANG</span>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </button>

                    <a href="https://wa.me/6282110009744?text={{ $waText }}" target="_blank" rel="noopener noreferrer" class="btn" style="width: 100%; height: 48px; background: #25D366; color: #FFFFFF; font-weight: 800; font-size: 0.88rem; letter-spacing: 0.04em; text-transform: uppercase; border-radius: 4px; display: flex; align-items: center; justify-content: center; gap: 0.6rem; text-decoration: none;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span>KONFIRMASI VIA WHATSAPP ADMIN</span>
                    </a>

                    <div style="display: flex; gap: 0.75rem;">
                        <a href="{{ route('home') }}" class="btn btn-outline" style="flex: 1; height: 44px; font-size: 0.85rem; font-weight: 700; border: 1px solid var(--border); color: #333333; text-align: center; display: flex; align-items: center; justify-content: center;">
                            Kembali ke Beranda
                        </a>
                        @auth
                            <a href="{{ route('user.bookings.index') }}" class="btn btn-outline" style="flex: 1; height: 44px; font-size: 0.85rem; font-weight: 700; border: 1px solid var(--border); color: #333333; text-align: center; display: flex; align-items: center; justify-content: center;">
                                Booking Saya
                            </a>
                        @endauth
                    </div>
                @endif

            </div>

        </div>

    </div>
</section>

<style>
    .pulse-ring {
        position: absolute;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: 3px solid #F59E0B;
        animation: pulseRingAnim 1.6s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
    }
    @keyframes pulseRingAnim {
        0% { transform: scale(0.6); opacity: 1; }
        80% { transform: scale(1.4); opacity: 0; }
        100% { transform: scale(1.5); opacity: 0; }
    }
</style>
@endsection

@push('scripts')
@if($booking->status === 'pending')
<script>
    // Live Polling every 2.5 seconds for instant admin approval response
    const bookingId = {{ $booking->id }};
    const pollInterval = setInterval(() => {
        fetch(`/booking/${bookingId}/status`, {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.is_confirmed) {
                clearInterval(pollInterval);
                // Immediately transition to success screen
                window.location.href = data.success_url || "{{ route('booking.success', $booking->id) }}";
            } else if (data.is_cancelled) {
                clearInterval(pollInterval);
                window.location.reload();
            }
        })
        .catch(err => console.log('Checking booking status...', err));
    }, 2500);
</script>
@endif
@endpush
