@extends('layouts.app')

@section('title', 'Profil & Bookingan Saya — Dutchman Barbershop')

@section('content')
<section style="padding: 2.5rem 0 5rem;">
    <div class="container" style="max-width: 960px;">

        <!-- Profile Top Banner Card -->
        <div class="card" style="padding: 2rem; background: linear-gradient(135deg, #181815 0%, #1f1f1a 100%); border: 1px solid var(--border); border-radius: var(--radius-md); margin-bottom: 2.5rem; box-shadow: 0 12px 32px rgba(0,0,0,0.4);">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
                <!-- Avatar & Identity Info -->
                <div style="display: flex; align-items: center; gap: 1.25rem;">
                    <div style="width: 68px; height: 68px; border-radius: 50%; background: #262622; border: 2px solid var(--coral); display: flex; align-items: center; justify-content: center; font-family: var(--font-display); font-size: 1.6rem; font-weight: 700; color: var(--coral); box-shadow: 0 0 16px rgba(212,240,56,0.2);">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap;">
                            <h1 style="font-family: var(--font-display); font-size: 1.75rem; margin: 0; color: var(--text);">
                                {{ $user->name }}
                            </h1>
                            <span class="badge badge-brass" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                                {{ $user->isAdmin() ? 'ADMINISTRATOR' : 'MEMBER DUTCHMAN' }}
                            </span>
                        </div>
                        <div style="color: var(--text-muted); font-size: 0.88rem; margin-top: 0.35rem; display: flex; gap: 1rem; flex-wrap: wrap;">
                            <span>{{ $user->email }}</span>
                            @if($user->phone)
                                <span>&bull; {{ $user->phone }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Corner Actions: Logout & New Booking -->
                <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                    <a href="{{ route('booking.create') }}" class="btn btn-coral btn-sm" style="display: inline-flex; align-items: center; gap: 0.4rem;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        <span>Reservasi Baru</span>
                    </a>

                    <!-- Prominent Logout Button -->
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0; display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm" title="Keluar dari akun Anda" style="border-color: rgba(239,68,68,0.4); color: #EF4444; display: inline-flex; align-items: center; gap: 0.45rem; padding: 0.5rem 0.95rem; cursor: pointer; transition: all 0.2s;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                            <span style="font-weight: 600;">Keluar / Log Out</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- SECTION 1: BOOKINGAN SAYA (Jadwal Kursi Mendatang & Riwayat Barber) -->
        <div style="margin-bottom: 3.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem;">
                <div>
                    <h2 style="font-family: var(--font-display); font-size: 1.45rem; margin: 0; display: flex; align-items: center; gap: 0.65rem;">
                        <span>Bookingan Saya di Barber</span>
                        <span class="badge badge-coral" style="font-size: 0.75rem;">{{ $upcomingBookings->count() }} Aktif</span>
                    </h2>
                    <p style="color: var(--text-muted); font-size: 0.88rem; margin-top: 0.25rem;">
                        Semua antrean sesi potong rambut Anda di Dutchman Barbershop.
                    </p>
                </div>
            </div>

            <!-- UPCOMING APPOINTMENTS -->
            @forelse($upcomingBookings as $booking)
                <div class="card" style="padding: 1.75rem; margin-bottom: 1.25rem; background: var(--surface); border: 1px solid var(--border); box-shadow: var(--shadow-sm); border-radius: var(--radius-md);">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem;">
                        <div>
                            <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                                <span style="font-family: var(--font-display); font-size: 1.3rem; color: var(--coral); font-weight: 800;">
                                    #{{ $booking->booking_number }}
                                </span>
                                @if($booking->status === 'confirmed')
                                    <span class="badge badge-success" style="font-size: 0.75rem; padding: 0.3rem 0.65rem;">✓ TERKONFIRMASI ADMIN</span>
                                @elseif($booking->status === 'pending')
                                    <a href="{{ route('booking.summary.before', $booking->id) }}" class="badge" style="background: #FEF3C7; color: #B45309; border: 1px solid #FCD34D; text-decoration: none; font-size: 0.75rem; padding: 0.3rem 0.65rem;" title="Klik untuk pantau persetujuan admin secara live">
                                        ⏳ MENUNGGU PERSETUJUAN ADMIN
                                    </a>
                                @endif
                            </div>
                            <div style="font-size: 1.05rem; font-weight: 700; margin-top: 0.4rem; color: var(--text);">
                                {{ $booking->booking_date->translatedFormat('l, d F Y') }} &bull; pukul {{ substr($booking->booking_time, 0, 5) }} WIB
                            </div>
                        </div>

                        <div style="text-align: right;">
                            <div style="font-family: var(--font-display); font-size: 1.35rem; color: var(--coral); font-weight: 700;">
                                {{ $booking->formatted_price }}
                            </div>
                            <div style="font-size: 0.78rem; color: var(--text-muted); font-weight: 500;">Bayar langsung di kasir studio</div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; background: var(--bg); padding: 1.15rem 1.25rem; border-radius: var(--radius-sm); border: 1px solid var(--border-subtle); margin-bottom: 1.25rem; font-size: 0.9rem;">
                        <div>
                            <span style="color: var(--text-muted); display: block; font-size: 0.72rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Layanan</span>
                            @foreach($booking->items as $item)
                                <strong style="color: var(--text); display: block;">{{ $item->service ? $item->service->name : ($item->service_name ?? 'Layanan Pangkas') }} ({{ $item->duration ?: ($item->service ? $item->service->duration_minutes : 45) }} mnt)</strong>
                            @endforeach
                        </div>
                        <div>
                            <span style="color: var(--text-muted); display: block; font-size: 0.72rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Barber Pilihan</span>
                            <strong style="color: var(--text); display: block;">{{ $booking->barber ? $booking->barber->name : 'Barber Bebas (Tersedia)' }}</strong>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
                        <a href="{{ route('booking.confirmation', $booking->booking_number) }}" class="btn btn-outline btn-sm">
                            <span>Lihat Bukti Reservasi</span>
                        </a>

                        <form action="{{ route('user.bookings.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan reservasi #{{ $booking->booking_number }}?');">
                            @csrf
                            <button type="submit" class="btn btn-outline btn-sm" style="color: var(--danger); border-color: rgba(239, 68, 68, 0.35);">
                                Batalkan Reservasi
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="card" style="padding: 2.25rem; text-align: center; color: var(--text-muted); background: var(--surface); border: 1px dashed var(--border); border-radius: var(--radius-md);">
                    <p style="margin-bottom: 1rem; font-size: 0.95rem;">Anda saat ini belum memiliki jadwal reservasi kursi yang aktif.</p>
                    <a href="{{ route('booking.create') }}" class="btn btn-coral btn-sm">Pesan Kursi Sekarang</a>
                </div>
            @endforelse

            <!-- PAST / COMPLETED BOOKINGS HISTORY -->
            @if($pastBookings->count() > 0)
                <div style="margin-top: 2rem;">
                    <div style="font-family: var(--font-display); font-size: 1.15rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.6rem; color: var(--text-muted);">
                        <span>Riwayat Booking Terdahulu</span>
                        <span class="badge badge-muted" style="font-size: 0.72rem;">{{ $pastBookings->count() }} Selesai</span>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @foreach($pastBookings as $booking)
                            <div class="card" style="padding: 1.15rem 1.4rem; opacity: 0.85; background: var(--surface); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm);">
                                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
                                    <div>
                                        <div style="font-weight: 700; color: var(--text); font-size: 0.95rem;">
                                            #{{ $booking->booking_number }} &bull; {{ $booking->booking_date->translatedFormat('d M Y') }}
                                        </div>
                                        <div style="font-size: 0.82rem; color: var(--text-muted); margin-top: 0.2rem;">
                                            {{ $booking->items->map(fn($item) => $item->service ? $item->service->name : ($item->service_name ?? 'Layanan Pangkas'))->join(', ') }} bersama {{ $booking->barber ? $booking->barber->name : 'Staff Barber' }}
                                        </div>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 0.85rem;">
                                        <span class="badge badge-muted" style="font-size: 0.72rem;">{{ strtoupper($booking->status) }}</span>
                                        <span style="font-weight: 700; color: var(--text); font-size: 0.92rem;">{{ $booking->formatted_price }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <hr style="border: 0; border-top: 1px solid var(--border); margin: 3rem 0;">

        <!-- SECTION 2: PENGATURAN INFORMASI AKUN & PASSWORD -->
        <div>
            <div style="margin-bottom: 1.5rem;">
                <h2 style="font-family: var(--font-display); font-size: 1.45rem; margin: 0;">Pengaturan Data Akun</h2>
                <p style="color: var(--text-muted); font-size: 0.88rem; margin-top: 0.25rem;">
                    Perbarui nama, nomor kontak WhatsApp, dan kata sandi Anda.
                </p>
            </div>

            <div class="grid grid-cols-2" style="gap: 2rem; align-items: start;">
                <!-- Personal Info Card -->
                <div class="card" style="padding: 2rem; background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md);">
                    <div style="font-family: var(--font-serif); font-size: 1.25rem; margin-bottom: 1.25rem; color: var(--text);">Informasi Kontak</div>

                    <form action="{{ route('user.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div style="margin-bottom: 1.25rem;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem;">
                                Nama Lengkap
                            </label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required style="width: 100%; height: 44px; background: var(--surface-light); border: 1px solid var(--border); border-radius: var(--radius-sm); color: var(--text); padding: 0 0.85rem; font-family: var(--font-sans); font-size: 0.9rem; outline: none;">
                        </div>

                        <div style="margin-bottom: 1.25rem;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem;">
                                Alamat Email
                            </label>
                            <input type="email" value="{{ $user->email }}" disabled style="width: 100%; height: 44px; background: rgba(0,0,0,0.25); border: 1px solid var(--border); border-radius: var(--radius-sm); color: var(--text-muted); padding: 0 0.85rem; font-family: var(--font-sans); font-size: 0.9rem; cursor: not-allowed;">
                            <span style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-top: 0.25rem;">Alamat email terdaftar secara permanen.</span>
                        </div>

                        <div style="margin-bottom: 1.75rem;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem;">
                                Nomor WhatsApp / HP
                            </label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="cth. 081234567890" style="width: 100%; height: 44px; background: var(--surface-light); border: 1px solid var(--border); border-radius: var(--radius-sm); color: var(--text); padding: 0 0.85rem; font-family: var(--font-sans); font-size: 0.9rem; outline: none;">
                        </div>

                        <button type="submit" class="btn btn-brass" style="width: 100%;">
                            Simpan Perubahan Kontak
                        </button>
                    </form>
                </div>

                <!-- Password Card -->
                <div class="card" style="padding: 2rem; background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md);">
                    <div style="font-family: var(--font-serif); font-size: 1.25rem; margin-bottom: 1.25rem; color: var(--text);">Keamanan Kata Sandi</div>

                    <form action="{{ route('user.profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div style="margin-bottom: 1.25rem;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem;">
                                Kata Sandi Saat Ini
                            </label>
                            <input type="password" name="current_password" required style="width: 100%; height: 44px; background: var(--surface-light); border: 1px solid var(--border); border-radius: var(--radius-sm); color: var(--text); padding: 0 0.85rem; font-family: var(--font-sans); font-size: 0.9rem; outline: none;">
                        </div>

                        <div style="margin-bottom: 1.25rem;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem;">
                                Kata Sandi Baru
                            </label>
                            <input type="password" name="password" required placeholder="Min. 8 karakter" style="width: 100%; height: 44px; background: var(--surface-light); border: 1px solid var(--border); border-radius: var(--radius-sm); color: var(--text); padding: 0 0.85rem; font-family: var(--font-sans); font-size: 0.9rem; outline: none;">
                        </div>

                        <div style="margin-bottom: 1.75rem;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem;">
                                Konfirmasi Kata Sandi Baru
                            </label>
                            <input type="password" name="password_confirmation" required placeholder="Ulangi kata sandi baru" style="width: 100%; height: 44px; background: var(--surface-light); border: 1px solid var(--border); border-radius: var(--radius-sm); color: var(--text); padding: 0 0.85rem; font-family: var(--font-sans); font-size: 0.9rem; outline: none;">
                        </div>

                        <button type="submit" class="btn btn-outline" style="width: 100%;">
                            Perbarui Kata Sandi
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
