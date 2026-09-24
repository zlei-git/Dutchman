@extends('layouts.app')

@section('title', 'Konfirmasi Reservasi — ' . $booking->booking_number . ' | Dutchman Barbershop')

@section('content')
<section style="padding: 3rem 0 5rem;">
    <div class="container" style="max-width: 680px;">
        <!-- Success Confirmation Card -->
        <div class="card" style="padding: 2.75rem; background: var(--surface); box-shadow: var(--shadow-md);">
            <!-- Top Icon & Title -->
            <div style="text-align: center; margin-bottom: 2rem;">
                <div style="width: 64px; height: 64px; border-radius: var(--radius-full); background: var(--green-soft); color: #065F46; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; box-shadow: var(--shadow-sm);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <div style="display: inline-block; background: var(--green-soft); color: #065F46; font-weight: 800; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.08em; padding: 0.35rem 0.85rem; border-radius: var(--radius-full); margin-bottom: 0.6rem;">
                    ★ RESERVASI TERKONFIRMASI
                </div>
                <h1 style="font-size: 2.2rem; margin-top: 0.25rem; font-family: var(--font-display);">Kursi Anda Telah Dipesan!</h1>
                <p style="font-size: 1rem; color: var(--text-muted); margin-top: 0.35rem;">
                    Kami menantikan kedatangan Anda di Dutchman Barbershop Surabaya.
                </p>
            </div>

            <!-- Booking Reference Ticket -->
            <div style="background: var(--bg); border: 2px dashed var(--coral); border-radius: var(--radius-md); padding: 1.25rem 1.5rem; display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <div>
                    <div style="font-size: 0.74rem; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.08em; font-weight: 800;">No. Referensi Booking</div>
                    <div style="font-family: var(--font-display); font-size: 1.6rem; color: var(--coral); font-weight: 800; letter-spacing: 0.04em; margin-top: 0.15rem;">
                        {{ $booking->booking_number }}
                    </div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 0.74rem; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.08em; font-weight: 800;">Status</div>
                    <span style="display: inline-block; background: var(--green-soft); color: #065F46; font-weight: 800; font-size: 0.78rem; text-transform: uppercase; padding: 0.25rem 0.75rem; border-radius: var(--radius-full); margin-top: 0.25rem;">
                        {{ strtoupper($booking->status) }}
                    </span>
                </div>
            </div>

            <!-- Detailed Itinerary Breakdown -->
            <div class="flex flex-col gap-4" style="font-size: 0.95rem; border-bottom: 1.5px solid var(--border-subtle); padding-bottom: 2rem; margin-bottom: 2rem;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--text-muted); font-weight: 600;">Tanggal &amp; Jam</span>
                    <strong style="color: var(--text); font-size: 1.05rem;">
                        {{ $booking->booking_date->translatedFormat('l, d F Y') }} pukul {{ substr($booking->booking_time, 0, 5) }} WIB
                    </strong>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--text-muted); font-weight: 600;">Master Barber</span>
                    <span style="font-weight: 700; color: var(--text);">
                        {{ $booking->barber ? $booking->barber->name : 'Barber Bebas (Tersedia)' }}
                    </span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--text-muted); font-weight: 600;">Layanan</span>
                    <div style="text-align: right;">
                        @foreach($booking->items as $item)
                            <div style="font-weight: 700; color: var(--text);">{{ $item->service_name }} ({{ $item->duration_minutes }} menit)</div>
                        @endforeach
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--text-muted); font-weight: 600;">Nama Pelanggan</span>
                    <span style="font-weight: 700;">{{ $booking->customer_name }}</span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--text-muted); font-weight: 600;">Nomor WhatsApp / HP</span>
                    <span>{{ $booking->customer_phone }}</span>
                </div>

                @if($booking->notes)
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <span style="color: var(--text-muted); font-weight: 600;">Catatan / Model Potongan</span>
                        <span style="max-width: 320px; text-align: right; color: var(--text-muted);">{{ $booking->notes }}</span>
                    </div>
                @endif

                <div style="display: flex; justify-content: space-between; align-items: baseline; border-top: 1.5px solid var(--border-subtle); padding-top: 1rem; margin-top: 0.5rem;">
                    <span style="font-size: 1rem; font-weight: 700;">Bayar di Studio</span>
                    <span style="font-family: var(--font-display); font-size: 1.6rem; color: var(--coral); font-weight: 800;">
                        {{ $booking->formatted_price }}
                    </span>
                </div>
            </div>

            <!-- Studio Location Reminder -->
            <div style="background: var(--bg); padding: 1.25rem; border-radius: var(--radius-sm); margin-bottom: 2rem; font-size: 0.9rem; color: var(--text-muted); line-height: 1.55;">
                <strong style="color: var(--text); display: block; margin-bottom: 0.35rem; font-family: var(--font-display); font-size: 1.05rem;">Dutchman Barbershop Surabaya</strong>
                Jl. Rungkut Madya No.55A, Rungkut Kidul, Surabaya, Jawa Timur 60293. Area parkir khusus pelanggan tersedia gratis di lokasi.
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col gap-3">
                @php
                    $waText = urlencode("Halo Dutchman Barbershop, saya sudah memesan jadwal dengan no. booking " . $booking->booking_number . " pada tanggal " . $booking->booking_date->format('d M Y') . " jam " . substr($booking->booking_time, 0, 5) . ".");
                    $gcalTitle = urlencode("Jadwal Dutchman Barbershop (" . $booking->booking_number . ")");
                    $gcalDetails = urlencode("Barber: " . ($booking->barber ? $booking->barber->name : "Barber Bertugas") . "\nLokasi: Jl. Rungkut Madya No.55A, Surabaya\nBooking ID: " . $booking->booking_number);
                    $startTime = $booking->booking_date->format('Ymd') . 'T' . str_replace(':', '', substr($booking->booking_time, 0, 5)) . '00';
                    $endTime = $booking->booking_date->format('Ymd') . 'T' . date('His', strtotime($booking->booking_time . ' +45 minutes'));
                    $gcalUrl = "https://calendar.google.com/calendar/render?action=TEMPLATE&text={$gcalTitle}&dates={$startTime}/{$endTime}&details={$gcalDetails}&location=Jl.+Rungkut+Madya+No.55A,+Surabaya";
                @endphp

                <div class="grid grid-cols-2" style="gap: 1rem;">
                    <a href="{{ $gcalUrl }}" target="_blank" rel="noopener" class="btn btn-outline" style="width: 100%;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                        <span>Simpan ke Google Calendar</span>
                    </a>

                    <a href="https://wa.me/6282110009744?text={{ $waText }}" target="_blank" rel="noopener" class="btn btn-coral" style="width: 100%;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span>WhatsApp Studio</span>
                    </a>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem; font-size: 0.9rem; font-weight: 600;">
                    <a href="{{ route('home') }}" class="nav-link" style="display: flex; align-items: center; gap: 0.35rem;">
                        &larr; Kembali ke Beranda
                    </a>
                    @auth
                        <a href="{{ route('user.bookings.index') }}" class="nav-link" style="color: var(--coral);">
                            Lihat di Booking Saya &rarr;
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
