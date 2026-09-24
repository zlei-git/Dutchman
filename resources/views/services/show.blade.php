@extends('layouts.app')

@section('title', $service->name . ' — Dutchman Barbershop')

@section('content')
<section style="padding: 3rem 0 5rem;">
    <div class="container" style="max-width: 980px;">
        <!-- Breadcrumbs -->
        <div style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 2rem; font-weight: 600;">
            <a href="{{ route('home') }}" class="nav-link">Beranda</a> &nbsp;/&nbsp;
            <a href="{{ route('services.index') }}" class="nav-link">Layanan</a> &nbsp;/&nbsp;
            <span style="color: var(--text);">{{ $service->name }}</span>
        </div>

        <div style="display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 3rem; align-items: start; margin-bottom: 4rem;">
            <!-- Left: Service Photo Showcase (Borderless) -->
            <div>
                <div style="border-radius: var(--radius-lg); overflow: hidden; aspect-ratio: 4/3; margin-bottom: 1.5rem; box-shadow: var(--shadow-md); background: var(--surface);">
                    <img src="{{ asset($service->photo) }}" alt="{{ $service->name }}" style="width: 100%; height: 100%; object-fit: cover; border: none !important;">
                </div>

                <div class="card" style="padding: 1.5rem; background: var(--surface);">
                    <div style="font-size: 0.82rem; font-weight: 800; color: var(--coral); letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 0.75rem;">
                        APA YANG TERMASUK DALAM LAYANAN INI
                    </div>
                    <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.9rem; color: var(--text-muted);">
                        <li style="display: flex; gap: 0.5rem; align-items: center;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--coral)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>Konsultasi model rambut disesuaikan dengan bentuk wajah &amp; jenis rambut</span>
                        </li>
                        <li style="display: flex; gap: 0.5rem; align-items: center;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--coral)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>Aplikasi busa handuk hangat &amp; cukur leher presisi</span>
                        </li>
                        <li style="display: flex; gap: 0.5rem; align-items: center;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--coral)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>Keramas rambut segar &amp; pijat relaksasi kepala</span>
                        </li>
                        <li style="display: flex; gap: 0.5rem; align-items: center;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--coral)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>Finishing styling pomade / matte paste + minuman dingin gratis</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Right: Details & Direct Booking CTA -->
            <div>
                @if($service->badge)
                    <div style="display: inline-block; background: var(--yellow); color: #18181B; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; padding: 0.25rem 0.75rem; border-radius: var(--radius-full); margin-bottom: 0.75rem;">
                        {{ $service->badge }}
                    </div>
                @endif
                <h1 style="font-size: 2.4rem; margin-bottom: 0.5rem; font-family: var(--font-display);">{{ $service->name }}</h1>

                <div style="display: flex; align-items: baseline; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <span style="font-family: var(--font-display); font-size: 2rem; color: var(--coral); font-weight: 700;">
                        {{ $service->formatted_price }}
                    </span>
                    <span style="font-size: 0.9rem; color: var(--text-muted); display: flex; align-items: center; gap: 0.35rem; font-weight: 600;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <span>Sesi {{ $service->duration_minutes }} menit</span>
                    </span>
                </div>

                <p style="font-size: 1rem; line-height: 1.7; color: var(--text-muted); margin-bottom: 2rem;">
                    {{ $service->description }}
                </p>

                <div class="card" style="padding: 1.75rem; background: var(--surface); margin-bottom: 2rem; box-shadow: var(--shadow-sm);">
                    <div style="font-weight: 800; font-size: 1.15rem; margin-bottom: 0.35rem; font-family: var(--font-display);">Reservasi Layanan Ini</div>
                    <p style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 1.25rem;">
                        Pilih master barber favorit Anda dan nikmati perawatan tanpa perlu mengantre.
                    </p>
                    <a href="{{ route('booking.create', ['service_id' => $service->id]) }}" class="btn btn-coral" style="width: 100%; height: 50px; font-size: 1rem;">
                        <span>RESERVASI SEKARANG</span>
                    </a>
                </div>

                <div style="font-size: 0.86rem; color: var(--text-muted); font-weight: 600;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span>Tanpa biaya deposit di muka • Bayar setelah selesai melalui QRIS / Tunai</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <span>Jadwalkan ulang atau batalkan dengan mudah hingga 2 jam sebelumnya</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Other Services Cross-sell -->
        @if($otherServices->count() > 0)
            <div style="border-top: 1.5px solid var(--border-subtle); padding-top: 3rem;">
                <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 1.75rem;">
                    <h2 style="font-family: var(--font-display);">Layanan &amp; Paket Lainnya</h2>
                    <a href="{{ route('services.index') }}" class="nav-link" style="color: var(--coral); font-weight: 700;">Lihat Semua Menu &rarr;</a>
                </div>

                <div class="grid grid-cols-3" style="gap: 1.5rem;">
                    @foreach($otherServices as $other)
                        <div class="card" style="display: flex; flex-direction: column; overflow: hidden; background: var(--surface);">
                            <div style="aspect-ratio: 16/10; width: 100%; overflow: hidden;">
                                <img src="{{ asset($other->photo) }}" alt="{{ $other->name }}" style="width: 100%; height: 100%; object-fit: cover; border: none !important;">
                            </div>
                            <div style="padding: 1.25rem; display: flex; flex-direction: column; flex: 1;">
                                <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.25rem;">
                                    <h3 style="font-size: 1.1rem; font-family: var(--font-display);">{{ $other->name }}</h3>
                                    <span style="font-weight: 700; color: var(--coral); font-family: var(--font-display);">{{ $other->formatted_price }}</span>
                                </div>
                                <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 1rem; flex: 1;">
                                    {{ Str::limit($other->description, 60) }}
                                </p>
                                <a href="{{ route('booking.create', ['service_id' => $other->id]) }}" class="btn btn-outline btn-sm" style="width: 100%; justify-content: center;">
                                    <span>Pilih Layanan</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
