@extends('layouts.app')

@section('title', 'Dutchman Barbershop — Potongan Rapi. Rasa Percaya Diri Sejati.')

@section('content')
<!-- ==========================================================================
     SECTION 1: HERO (Full-Screen Landscape 3-Video Cycle from Dutchman)
     3 Videos loop and crossfade smoothly • Mobile-friendly title • Single 'RESERVASI SEKARANG' button
     ========================================================================== -->
<section style="position: relative; width: 100%; height: calc(100vh - 76px); min-height: 560px; background: #000000; overflow: hidden; display: flex; align-items: center; justify-content: center;">
    <!-- 3 Dutchman Videos Seamless Crossfade Loop -->
    <div id="hero-video-container" style="position: absolute; inset: 0; width: 100%; height: 100%; overflow: hidden; background: #000000;">
        <video id="hero-vid-0" autoplay muted playsinline preload="auto" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center; filter: brightness(0.65); opacity: 1; transition: opacity 0.8s ease-in-out; display: block;">
            <source src="{{ asset('videos/dutchman-1.mp4') }}" type="video/mp4">
        </video>
        <video id="hero-vid-1" muted playsinline preload="auto" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center; filter: brightness(0.65); opacity: 0; transition: opacity 0.8s ease-in-out; display: block;">
            <source src="{{ asset('videos/dutchman-2.mp4') }}" type="video/mp4">
        </video>
        <video id="hero-vid-2" muted playsinline preload="auto" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center; filter: brightness(0.65); opacity: 0; transition: opacity 0.8s ease-in-out; display: block;">
            <source src="{{ asset('videos/dutchman-3.mp4') }}" type="video/mp4">
        </video>
    </div>

    <!-- Clean Neutral Dark Overlay -->
    <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.65) 100%); pointer-events: none;"></div>

    <!-- Centered Editorial Content (Scaled for Mobile • Single Button) -->
    <div class="container" style="position: relative; z-index: 5; text-align: center; max-width: 820px; padding: 2rem 1.25rem;">
        <!-- Clean Responsive Headline -->
        <h1 style="font-family: var(--font-display); font-size: clamp(2.2rem, 5.2vw, 3.8rem); font-weight: 900; letter-spacing: 0.05em; color: #FFFFFF; line-height: 1.15; margin-bottom: 1.2rem; text-shadow: 0 4px 24px rgba(0,0,0,0.85); text-transform: uppercase;">
            POTONGAN RAPI. PERCAYA DIRI SEJATI.
        </h1>

        <!-- Subtitle -->
        <p style="font-size: clamp(1rem, 2vw, 1.2rem); color: #E5E5E5; max-width: 620px; margin: 0 auto 2.5rem; line-height: 1.6; text-shadow: 0 2px 10px rgba(0,0,0,0.9);">
            Seni pangkas rambut klasik Eropa &amp; perawatan pria premium di Surabaya.
        </p>

        <!-- Single Action Button: RESERVASI SEKARANG -> -->
        <div style="display: flex; justify-content: center; align-items: center;">
            <a href="{{ route('booking.create') }}" class="btn" style="background: #FFFFFF; color: #111111; font-weight: 800; font-size: 0.98rem; letter-spacing: 0.06em; padding: 1rem 3.4rem; border-radius: 4px; box-shadow: 0 4px 24px rgba(0,0,0,0.45); text-transform: uppercase; display: inline-flex; align-items: center; gap: 0.6rem;">
                <span>RESERVASI SEKARANG</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>

<!-- ==========================================================================
     STRIP BAR KHUSUS: 3 Core Highlights / USPs
     Tempat Pangkas Rambut Terbaik | Pelayanan Bintang 5 | 100% Steril
     ========================================================================== -->
<div style="background: #111111; border-top: 1px solid rgba(255,255,255,0.1); border-bottom: 1px solid #222222; padding: 1.25rem 0;">
    <div class="container" style="max-width: 1200px;">
        <div class="strip-bar-special" style="display: grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; text-align: center;">
            <div class="strip-item" style="padding: 0.5rem 1.5rem; border-right: 1px solid rgba(255, 255, 255, 0.18); display: flex; align-items: center; justify-content: center; gap: 0.75rem;">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #FFFFFF; flex-shrink: 0;"><circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><line x1="20" y1="4" x2="8.12" y2="15.88"/><line x1="14.47" y1="14.48" x2="20" y2="20"/><line x1="8.12" y1="8.12" x2="12" y2="12"/></svg>
                <span style="font-family: var(--font-display); font-size: clamp(0.88rem, 1.35vw, 1.05rem); font-weight: 800; letter-spacing: 0.05em; color: #FFFFFF; text-transform: uppercase;">
                    Tempat Pangkas Rambut Terbaik
                </span>
            </div>

            <div class="strip-item" style="padding: 0.5rem 1.5rem; border-right: 1px solid rgba(255, 255, 255, 0.18); display: flex; align-items: center; justify-content: center; gap: 0.75rem;">
                <div style="display: inline-flex; gap: 2px; color: #FFFFFF; font-size: 0.95rem;">
                    ★ ★ ★ ★ ★
                </div>
                <span style="font-family: var(--font-display); font-size: clamp(0.88rem, 1.35vw, 1.05rem); font-weight: 800; letter-spacing: 0.05em; color: #FFFFFF; text-transform: uppercase;">
                    Pelayanan Bintang 5
                </span>
            </div>

            <div class="strip-item" style="padding: 0.5rem 1.5rem; display: flex; align-items: center; justify-content: center; gap: 0.75rem;">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #FFFFFF; flex-shrink: 0;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                <span style="font-family: var(--font-display); font-size: clamp(0.88rem, 1.35vw, 1.05rem); font-weight: 800; letter-spacing: 0.05em; color: #FFFFFF; text-transform: uppercase;">
                    100% Steril
                </span>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .strip-bar-special {
        grid-template-columns: 1fr !important;
        gap: 0.85rem !important;
    }
    .strip-bar-special .strip-item {
        border-right: none !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.12) !important;
        padding-bottom: 0.85rem !important;
    }
    .strip-bar-special .strip-item:last-child {
        border-bottom: none !important;
        padding-bottom: 0 !important;
    }
}
</style>

<!-- ==========================================================================
     SECTION 2: ABOUT DUTCHMAN / THE DUTCHMAN EXPERIENCE
     Short introduction to the Dutchman experience • Teaser to full About page
     ========================================================================== -->
<section id="about" style="padding: 6rem 0; background: #FFFFFF; border-bottom: 1px solid var(--border);">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1.15fr 1fr; gap: 4.5rem; align-items: center;">
            <!-- Left Column: Experience Points & CTA -->
            <div>
                <!-- Eyebrow -->
                <div style="font-size: 0.8rem; font-weight: 800; letter-spacing: 0.16em; text-transform: uppercase; color: #888888; margin-bottom: 0.65rem;">
                    ABOUT DUTCHMAN
                </div>

                <!-- Main Heading -->
                <h2 style="font-size: clamp(2.2rem, 3.8vw, 3.2rem); font-family: var(--font-display); color: #111111; font-weight: 800; line-height: 1.12; margin-bottom: 1.25rem; text-transform: uppercase; letter-spacing: 0.02em;">
                    THE DUTCHMAN EXPERIENCE
                </h2>

                <!-- Description -->
                <p style="font-size: 1.05rem; line-height: 1.7; color: #444444; margin-bottom: 2.25rem;">
                    Lebih dari sekadar potong rambut. Dutchman memadukan ketelitian barbering klasik, ritual grooming, dan hospitality yang membuat setiap kunjungan terasa lebih personal.
                </p>

                <!-- 4 Concise Experience Points -->
                <div style="display: flex; flex-direction: column; gap: 1.4rem; margin-bottom: 2.5rem;">
                    <!-- 01 -->
                    <div style="display: flex; gap: 1.1rem; align-items: flex-start;">
                        <span style="font-family: var(--font-display); font-size: 1.1rem; font-weight: 800; color: #111111; line-height: 1; padding-top: 0.15rem;">01</span>
                        <div>
                            <div style="font-family: var(--font-display); font-size: 0.95rem; font-weight: 800; letter-spacing: 0.06em; text-transform: uppercase; color: #111111; margin-bottom: 0.2rem;">
                                CLASSIC ATMOSPHERE
                            </div>
                            <p style="font-size: 0.92rem; line-height: 1.6; color: #666666; margin: 0;">
                                Ruang yang terinspirasi dari karakter barber klasik — tenang, maskulin, dan dibuat untuk membuat Anda nyaman.
                            </p>
                        </div>
                    </div>

                    <!-- 02 -->
                    <div style="display: flex; gap: 1.1rem; align-items: flex-start;">
                        <span style="font-family: var(--font-display); font-size: 1.1rem; font-weight: 800; color: #111111; line-height: 1; padding-top: 0.15rem;">02</span>
                        <div>
                            <div style="font-family: var(--font-display); font-size: 0.95rem; font-weight: 800; letter-spacing: 0.06em; text-transform: uppercase; color: #111111; margin-bottom: 0.2rem;">
                                FACIAL &amp; RELAXATION
                            </div>
                            <p style="font-size: 0.92rem; line-height: 1.6; color: #666666; margin: 0;">
                                Mulai dari warm towel hingga facial steam, setiap detail grooming dirancang untuk melengkapi pengalaman.
                            </p>
                        </div>
                    </div>

                    <!-- 03 -->
                    <div style="display: flex; gap: 1.1rem; align-items: flex-start;">
                        <span style="font-family: var(--font-display); font-size: 1.1rem; font-weight: 800; color: #111111; line-height: 1; padding-top: 0.15rem;">03</span>
                        <div>
                            <div style="font-family: var(--font-display); font-size: 0.95rem; font-weight: 800; letter-spacing: 0.06em; text-transform: uppercase; color: #111111; margin-bottom: 0.2rem;">
                                PRECISION BARBERING
                            </div>
                            <p style="font-size: 0.92rem; line-height: 1.6; color: #666666; margin: 0;">
                                Teknik gunting manual dan traditional straight razor dipadukan dengan perhatian pada bentuk, detail, dan finishing.
                            </p>
                        </div>
                    </div>

                    <!-- 04 -->
                    <div style="display: flex; gap: 1.1rem; align-items: flex-start;">
                        <span style="font-family: var(--font-display); font-size: 1.1rem; font-weight: 800; color: #111111; line-height: 1; padding-top: 0.15rem;">04</span>
                        <div>
                            <div style="font-family: var(--font-display); font-size: 0.95rem; font-weight: 800; letter-spacing: 0.06em; text-transform: uppercase; color: #111111; margin-bottom: 0.2rem;">
                                HOSPITALITY
                            </div>
                            <p style="font-size: 0.92rem; line-height: 1.6; color: #666666; margin: 0;">
                                Pelayanan yang ramah, personal, dan tidak terburu-buru — karena pengalaman yang baik dimulai sebelum potongan pertama.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- CTA to About Page -->
                <div>
                    <a href="{{ route('about') }}" style="display: inline-flex; align-items: center; gap: 0.6rem; font-family: var(--font-display); font-size: 0.92rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; color: #111111; text-decoration: none; border-bottom: 2px solid #111111; padding-bottom: 0.35rem; transition: opacity 0.2s ease;">
                        <span>DISCOVER DUTCHMAN</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Right Column: Authentic Dutchman Studio Visual -->
            <div>
                <div style="border-radius: 6px; overflow: hidden; box-shadow: 0 16px 40px rgba(0,0,0,0.1); aspect-ratio: 4/3; background: #111111;">
                    <img src="{{ asset('images/barbershop/fakta-dutchman.jpg') }}" alt="The Dutchman Barbershop Experience" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                </div>
                <div style="margin-top: 0.85rem; font-size: 0.82rem; color: #888888; letter-spacing: 0.03em;">
                    Suasana Studio &amp; Ritual Grooming • Dutchman Barbershop Surabaya
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================================================
     SECTION 3: PELAYANAN (Tanpa Pricing & Tanpa Harga di Halaman Depan)
     Minimal 6 layanan menarik • Mengarahkan untuk menjelajahi services pages
     ========================================================================== -->
<section id="services" style="padding: 5.5rem 0; background: #F8F8F8; border-bottom: 1px solid var(--border);">
    <div class="container">
        <!-- Direct Section Heading (Tanpa Tulisan 'Pricing' Sesuai Request) -->
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem; flex-wrap: wrap; gap: 1.5rem;">
            <div>
                <h2 style="font-size: clamp(2rem, 3.8vw, 3rem); font-family: var(--font-display); color: #111111; font-weight: 800; text-transform: uppercase; line-height: 1.15; margin-bottom: 0.5rem;">
                    Pelayanan
                </h2>
                <p style="font-size: 1.05rem; color: #666666; max-width: 600px;">
                    Pilihan layanan pangkas rambut klasik, cukur tradisional handuk hangat, dan ritual perawatan wajah pria di Dutchman Barbershop.
                </p>
            </div>
            <a href="{{ route('services.index') }}" class="btn" style="background: #111111; color: #FFFFFF; font-weight: 800; padding: 0.85rem 2rem; border-radius: 4px;">
                <span>SEMUA MENU &amp; HARGA &rarr;</span>
            </a>
        </div>

        <!-- Uniform Grid of 6 Featured Services (Tanpa Nominal Harga Sesuai Request) -->
        <div class="grid grid-cols-3" style="gap: 2rem;">
            @php
                // Ambil 6 layanan pilihan yang paling menarik
                $featuredServices = $services->take(6);
            @endphp
            @foreach($featuredServices as $service)
                <div class="card" style="display: flex; flex-direction: column; overflow: hidden; height: 100%; background: #FFFFFF; border: 1px solid var(--border); border-radius: 6px; box-shadow: 0 4px 16px rgba(0,0,0,0.04);">
                    <!-- Real Authentic Barber Photo (No AI) -->
                    <div style="aspect-ratio: 16/11; width: 100%; overflow: hidden; position: relative; background: #000000; border: none; outline: none;">
                        <img src="{{ asset($service->photo) }}" alt="{{ $service->name }}" style="width: 100%; height: 100%; object-fit: cover; display: block; border: none; outline: none;">
                        @if($service->badge)
                            <div style="position: absolute; top: 12px; right: 12px;">
                                <span style="background: #111111; color: #FFFFFF; font-weight: 800; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.06em; padding: 0.35rem 0.8rem; border-radius: 3px;">
                                    {{ $service->badge }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <div style="padding: 1.75rem; display: flex; flex-direction: column; flex: 1;">
                        <div style="margin-bottom: 0.4rem;">
                            <h3 style="font-size: 1.25rem; font-family: var(--font-display); color: #111111; font-weight: 800; line-height: 1.25;">
                                {{ $service->name }}
                            </h3>
                        </div>

                        <div style="font-size: 0.84rem; color: #777777; margin-bottom: 0.85rem; font-weight: 600;">
                            {{ $service->duration_minutes }} menit sesi
                        </div>

                        <p style="font-size: 0.92rem; line-height: 1.6; color: #555555; margin-bottom: 1.75rem; flex: 1;">
                            {{ $service->description }}
                        </p>

                        <!-- Direct Booking Button -->
                        <a href="{{ route('booking.create', ['service_id' => $service->id]) }}" class="btn" style="width: 100%; justify-content: center; background: #111111; color: #FFFFFF; font-weight: 700; font-size: 0.88rem; padding: 0.75rem 1rem; border-radius: 4px;">
                            <span>PILIH LAYANAN</span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Big Button: Explore All Services & Pricing -->
        <div style="text-align: center; margin-top: 3.5rem;">
            <a href="{{ route('services.index') }}" class="btn" style="background: #111111; color: #FFFFFF; font-weight: 800; font-size: 0.95rem; padding: 0.95rem 2.8rem; border-radius: 4px; text-transform: uppercase; box-shadow: 0 4px 16px rgba(0,0,0,0.15);">
                <span>JELAJAHI SEMUA MENU &amp; HARGA &rarr;</span>
            </a>
        </div>
    </div>
</section>

<!-- ==========================================================================
     SECTION 4: GOOGLE MAPS REVIEWS & LOKASI SURABAYA
     Direct title • Real Surabaya Customer Reviews
     ========================================================================== -->
<section id="reviews" style="padding: 5.5rem 0; background: #FFFFFF;">
    <div class="container">
        <!-- Direct Section Heading (No Text Above It) -->
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem; flex-wrap: wrap; gap: 1.5rem;">
            <div>
                <h2 style="font-size: clamp(2rem, 3.8vw, 3rem); font-family: var(--font-display); color: #111111; font-weight: 800; text-transform: uppercase; line-height: 1.15; margin-bottom: 0.5rem;">
                    Ulasan Google Maps
                </h2>
                <p style="font-size: 1.05rem; color: #666666; max-width: 540px;">
                    Ulasan nyata dari pelanggan setia Dutchman Barbershop di Jl. Rungkut Madya No.55A, Surabaya.
                </p>
            </div>

            <!-- Google Rating Summary Badge -->
            <a href="https://www.google.com/maps/place/Dutchman+Barbershop/@-7.3312263,112.7752023,17z/data=!4m8!3m7!1s0x2dd7fb24fc52fb7b:0x8ba3b7e250f5381d!8m2!3d-7.3312263!4d112.7752023!9m1!1b1!16s%2Fg%2F11vdhkbmgz"
               target="_blank"
               rel="noopener noreferrer"
               style="text-decoration: none; background: #F8F8F8; border: 1px solid var(--border); padding: 0.9rem 1.6rem; border-radius: 6px; display: flex; align-items: center; gap: 1rem; transition: transform 0.2s, box-shadow 0.2s;"
               onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.08)';"
               onmouseout="this.style.boxShadow='none';">
                <div style="font-size: 2.2rem; font-weight: 900; font-family: var(--font-display); color: #111111; line-height: 1;">
                    5.0
                </div>
                <div>
                    <div style="color: #F59E0B; font-size: 1.05rem; line-height: 1.2; letter-spacing: 1px;">★★★★★</div>
                    <div style="font-size: 0.78rem; color: #666666; font-weight: 600; margin-top: 0.2rem;">
                        1.032+ Ulasan Pelanggan
                    </div>
                </div>
            </a>
        </div>

        <!-- Reviews Grid -->
        <div class="grid grid-cols-3" style="gap: 1.75rem;">
            <!-- Review 1: Aditya Wanda Rahmansyah -->
            <div class="card" style="padding: 1.75rem; background: #F8F8F8; border: 1px solid var(--border); border-radius: 6px; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; align-items: center; gap: 0.9rem; margin-bottom: 0.9rem;">
                        <img src="{{ asset('images/reviews/aditya.jpg') }}" alt="Aditya Wanda Rahmansyah" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 1.5px solid #E0E0E0; flex-shrink: 0;">
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-family: var(--font-display); font-weight: 800; font-size: 1.05rem; color: #111111; line-height: 1.2; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                                Aditya Wanda Rahmansyah
                            </div>
                            <div style="font-size: 0.78rem; color: #777777; margin-top: 0.2rem; display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap;">
                                <span style="color: #E37400; font-weight: 600;">★ Local Guide</span>
                                <span>•</span>
                                <span>36 ulasan</span>
                                <span>•</span>
                                <span>2 bulan lalu</span>
                            </div>
                        </div>
                    </div>
                    <div style="color: #F59E0B; font-size: 0.95rem; margin-bottom: 0.75rem; letter-spacing: 1px;">
                        ★★★★★
                    </div>
                    <p style="font-size: 0.92rem; line-height: 1.6; color: #444444; font-style: italic;">
                        "Harga 100 ribu dapet capster yang informatif, ruangan yang nyaman banget, service yang professional. Walaupun pilih package yang termurah tapi service nya terbaik."
                    </p>
                </div>
                <div style="margin-top: 1.25rem; padding-top: 0.75rem; border-top: 1px solid #EFEFEF; display: flex; align-items: center; justify-content: space-between;">
                    <span style="font-size: 0.75rem; color: #888888; font-weight: 500;">Ulasan Asli Terverifikasi</span>
                </div>
            </div>

            <!-- Review 2: salma -->
            <div class="card" style="padding: 1.75rem; background: #F8F8F8; border: 1px solid var(--border); border-radius: 6px; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; align-items: center; gap: 0.9rem; margin-bottom: 0.9rem;">
                        <img src="{{ asset('images/reviews/salma.jpg') }}" alt="salma" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 1.5px solid #E0E0E0; flex-shrink: 0;">
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-family: var(--font-display); font-weight: 800; font-size: 1.05rem; color: #111111; line-height: 1.2;">
                                salma
                            </div>
                            <div style="font-size: 0.78rem; color: #777777; margin-top: 0.2rem; display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap;">
                                <span>5 ulasan</span>
                                <span>•</span>
                                <span>8 foto</span>
                                <span>•</span>
                                <span>sebulan lalu</span>
                            </div>
                        </div>
                    </div>
                    <div style="color: #F59E0B; font-size: 0.95rem; margin-bottom: 0.75rem; letter-spacing: 1px;">
                        ★★★★★
                    </div>
                    <p style="font-size: 0.92rem; line-height: 1.6; color: #444444; font-style: italic;">
                        "barber yg pelayanannya bagusss, capsternya tau potongan yg di mauu, ramah bgtttt. kakak kasirnya jg baikkk dan ramah pol jelasin benefit treatmentnya satu\". ada harga ada kualitass ya, minumannya jg lengkap ada coffee dan non coffee buat nunggu giliran potong, tempatnya cozy bgt. playlist lagunya jg keren\" 👍🏻👍🏻👍🏻👍🏻"
                    </p>
                </div>
                <div style="margin-top: 1.25rem; padding-top: 0.75rem; border-top: 1px solid #EFEFEF; display: flex; align-items: center; justify-content: space-between;">
                    <span style="font-size: 0.75rem; color: #888888; font-weight: 500;">Ulasan Asli Terverifikasi</span>
                </div>
            </div>

            <!-- Review 3: Dea Marcelya -->
            <div class="card" style="padding: 1.75rem; background: #F8F8F8; border: 1px solid var(--border); border-radius: 6px; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; align-items: center; gap: 0.9rem; margin-bottom: 0.9rem;">
                        <img src="{{ asset('images/reviews/dea.jpg') }}" alt="Dea Marcelya" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 1.5px solid #E0E0E0; flex-shrink: 0;">
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-family: var(--font-display); font-weight: 800; font-size: 1.05rem; color: #111111; line-height: 1.2;">
                                Dea Marcelya
                            </div>
                            <div style="font-size: 0.78rem; color: #777777; margin-top: 0.2rem; display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap;">
                                <span>4 ulasan</span>
                                <span>•</span>
                                <span>4 foto</span>
                                <span>•</span>
                                <span>4 bulan lalu</span>
                            </div>
                        </div>
                    </div>
                    <div style="color: #F59E0B; font-size: 0.95rem; margin-bottom: 0.75rem; letter-spacing: 1px;">
                        ★★★★★
                    </div>
                    <p style="font-size: 0.92rem; line-height: 1.6; color: #444444; font-style: italic;">
                        "layanan potong terbaik, hasil rapih dan bagus. ramah dan informatif sekali. cocok untuk perawatan rambut yang bermasalah seperti kering. capster memberi info yang sangat membantu sekali"
                    </p>
                </div>
                <div style="margin-top: 1.25rem; padding-top: 0.75rem; border-top: 1px solid #EFEFEF; display: flex; align-items: center; justify-content: space-between;">
                    <span style="font-size: 0.75rem; color: #888888; font-weight: 500;">Ulasan Asli Terverifikasi</span>
                </div>
            </div>
        </div>

        <!-- View All Reviews Link Button -->
        <div style="margin-top: 2.5rem; text-align: center;">
            <a href="https://www.google.com/maps/place/Dutchman+Barbershop/@-7.3312263,112.7752023,17z/data=!4m8!3m7!1s0x2dd7fb24fc52fb7b:0x8ba3b7e250f5381d!8m2!3d-7.3312263!4d112.7752023!9m1!1b1!16s%2Fg%2F11vdhkbmgz"
               target="_blank"
               rel="noopener noreferrer"
               style="display: inline-flex; align-items: center; gap: 0.6rem; padding: 0.8rem 1.8rem; border: 1.5px solid #111111; border-radius: 4px; color: #111111; font-weight: 700; font-size: 0.88rem; text-decoration: none; transition: all 0.2s;"
               onmouseover="this.style.background='#111111'; this.style.color='#FFFFFF';"
               onmouseout="this.style.background='transparent'; this.style.color='#111111';">
                <span>Lihat Seluruh 1.032+ Ulasan di Google Maps</span>
                <span style="font-size: 1.1rem; line-height: 1;">→</span>
            </a>
        </div>
    </div>
</section>

<!-- ==========================================================================
     SECTION 5: FROM THE CHAIR (INSTAGRAM EDITORIAL GALLERY)
     Directly below Reviews • Official Dutchman Barbershop Instagram
     ========================================================================== -->
@php
    $instagramGallery = [
        [
            'id' => 'chair-1',
            'type' => 'REEL',
            'image' => asset('images/instagram/insta-1.jpg'),
            'instagramUrl' => 'https://www.instagram.com/dutchmanbarbershop/reel/DQlQZEej99s/',
            'alt' => 'Dutchman Barbershop Surabaya - Front desk and customer reception',
        ],
        [
            'id' => 'chair-2',
            'type' => 'POST',
            'image' => asset('images/instagram/insta-2.jpg'),
            'instagramUrl' => 'https://www.instagram.com/dutchmanbarbershop/p/Dbm4V72DwSQ/',
            'alt' => 'Dutchman Membership - More than a haircut, classic gentleman taper fade',
        ],
        [
            'id' => 'chair-3',
            'type' => 'REEL',
            'image' => asset('images/instagram/insta-3.jpg'),
            'instagramUrl' => 'https://www.instagram.com/dutchmanbarbershop/reel/DBUy61NBzWQ/',
            'alt' => 'Inside Dutchman Barbershop - Barber stations and cutting session atmosphere',
        ],
        [
            'id' => 'chair-4',
            'type' => 'POST',
            'image' => asset('images/instagram/insta-4.jpg'),
            'instagramUrl' => 'https://www.instagram.com/dutchmanbarbershop/p/DdVnun1jyf_/',
            'alt' => 'Dutchman Clay - Matte Texture Strong Hold styling pomade',
        ],
        [
            'id' => 'chair-5',
            'type' => 'REEL',
            'image' => asset('images/instagram/insta-5.jpg'),
            'instagramUrl' => 'https://www.instagram.com/dutchmanbarbershop/reel/DcS9Kr-PC9t/',
            'alt' => 'Modern haircut and gentleman styling from Dutchman barber chair',
        ],
    ];
@endphp

<section id="from-the-chair" style="padding: 5.5rem 0 6rem; background: #FAF9F6; border-top: 1px solid var(--border);">
    <div class="container">
        <!-- Section Header -->
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2.75rem; flex-wrap: wrap; gap: 1.5rem;">
            <div>
                <!-- Instagram Handle Badge -->
                <a href="https://www.instagram.com/dutchmanbarbershop/"
                   target="_blank"
                   rel="noopener noreferrer"
                   style="display: inline-flex; align-items: center; gap: 0.5rem; color: #111111; text-decoration: none; font-size: 0.88rem; font-weight: 700; letter-spacing: 0.04em; margin-bottom: 0.6rem; transition: opacity 0.2s;"
                   onmouseover="this.style.opacity='0.75';"
                   onmouseout="this.style.opacity='1';">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0;">
                        <rect width="20" height="20" x="2" y="2" rx="5" ry="5"/>
                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                        <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/>
                    </svg>
                    <span>@dutchmanbarbershop</span>
                </a>

                <h2 style="font-size: clamp(2rem, 3.8vw, 3rem); font-family: var(--font-display); color: #111111; font-weight: 800; text-transform: uppercase; line-height: 1.15; margin-bottom: 0.5rem;">
                    FROM THE CHAIR
                </h2>
                <p style="font-size: 1.05rem; color: #666666; max-width: 540px; margin: 0;">
                    Keseharian &amp; karya potongan rambut di Dutchman.
                </p>
            </div>

            <!-- Header CTA Button -->
            <a href="https://www.instagram.com/dutchmanbarbershop/"
               target="_blank"
               rel="noopener noreferrer"
               style="display: inline-flex; align-items: center; gap: 0.65rem; padding: 0.85rem 1.75rem; background: #111111; color: #FFFFFF; font-family: var(--font-sans); font-size: 0.85rem; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; text-decoration: none; border-radius: 4px; transition: all 0.25s ease;"
               onmouseover="this.style.background='#2A2A2A'; this.style.transform='translateY(-1px)';"
               onmouseout="this.style.background='#111111'; this.style.transform='translateY(0)';">
                <span>LIHAT INSTAGRAM</span>
                <span style="font-size: 1rem; line-height: 1;">→</span>
            </a>
        </div>

        <!-- Editorial Instagram Gallery Grid -->
        <div class="from-chair-grid">
            @foreach($instagramGallery as $item)
                <a href="{{ $item['instagramUrl'] }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="chair-card"
                   aria-label="{{ $item['type'] }} on Dutchman Barbershop Instagram: {{ $item['alt'] }}">
                    <!-- Card Media Container -->
                    <div class="chair-media-wrap">
                        <img src="{{ $item['image'] }}"
                             alt="{{ $item['alt'] }}"
                             loading="lazy"
                             class="chair-img">

                        <!-- Subtle Metadata Pill (POST / REEL) -->
                        <div class="chair-type-pill">
                            @if($item['type'] === 'REEL')
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor">
                                    <polygon points="5 3 19 12 5 21 5 3"/>
                                </svg>
                            @else
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                                    <circle cx="9" cy="9" r="2"/>
                                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                                </svg>
                            @endif
                            <span>{{ $item['type'] }}</span>
                        </div>

                        <!-- Refined Dark Overlay with Hover Callout -->
                        <div class="chair-overlay">
                            <span class="chair-overlay-text">
                                LIHAT DI INSTAGRAM →
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<style>
    .from-chair-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1.35rem;
    }

    .chair-card {
        display: block;
        text-decoration: none;
        position: relative;
        border-radius: 6px;
        overflow: hidden;
        background: #111111;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        transition: transform 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94), box-shadow 0.3s ease;
        outline: none;
    }

    .chair-card:focus-visible {
        outline: 2px solid #111111;
        outline-offset: 3px;
    }

    .chair-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
    }

    .chair-media-wrap {
        position: relative;
        width: 100%;
        aspect-ratio: 4 / 5;
        overflow: hidden;
        background: #EAE6DF;
    }

    .chair-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .chair-card:hover .chair-img {
        transform: scale(1.05);
    }

    .chair-type-pill {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        z-index: 2;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.28rem 0.65rem;
        background: rgba(17, 17, 17, 0.72);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        color: #FFFFFF;
        font-family: var(--font-sans);
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        border-radius: 4px;
        pointer-events: none;
        border: 1px solid rgba(255, 255, 255, 0.12);
    }

    .chair-overlay {
        position: absolute;
        inset: 0;
        background: rgba(17, 17, 17, 0.45);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        padding: 1rem;
        z-index: 3;
    }

    .chair-card:hover .chair-overlay {
        opacity: 1;
    }

    .chair-overlay-text {
        font-family: var(--font-sans);
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #FFFFFF;
        padding: 0.6rem 1rem;
        background: rgba(17, 17, 17, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 4px;
        transform: translateY(4px);
        transition: transform 0.3s ease;
        text-align: center;
    }

    .chair-card:hover .chair-overlay-text {
        transform: translateY(0);
    }

    @media (max-width: 1024px) {
        .from-chair-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 1.25rem;
        }
    }

    @media (max-width: 768px) {
        .from-chair-grid {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 0.85rem;
            margin-left: -1.25rem;
            margin-right: -1.25rem;
            padding-left: 1.25rem;
            padding-right: 1.25rem;
            gap: 1rem;
        }

        .from-chair-grid::-webkit-scrollbar {
            height: 4px;
        }

        .from-chair-grid::-webkit-scrollbar-track {
            background: #EAE6DF;
            border-radius: 2px;
        }

        .from-chair-grid::-webkit-scrollbar-thumb {
            background: #999999;
            border-radius: 2px;
        }

        .chair-card {
            flex: 0 0 220px;
            scroll-snap-align: start;
        }

        .chair-media-wrap {
            aspect-ratio: 4 / 5;
        }
    }
</style>

@push('scripts')
<script>
    // 3 Dutchman Videos Seamless Crossfade Loop
    document.addEventListener('DOMContentLoaded', function () {
        const vids = [
            document.getElementById('hero-vid-0'),
            document.getElementById('hero-vid-1'),
            document.getElementById('hero-vid-2')
        ];

        let currentIndex = 0;
        let isTransitioning = false;

        // Start video 0 immediately
        if (vids[0]) {
            vids[0].play().catch(() => {});
        }

        function transitionToNext() {
            if (isTransitioning) return;
            isTransitioning = true;

            const nextIndex = (currentIndex + 1) % vids.length;
            const currentVid = vids[currentIndex];
            const nextVid = vids[nextIndex];

            if (nextVid) {
                nextVid.currentTime = 0;
                const p = nextVid.play();
                const doFade = () => {
                    nextVid.style.opacity = '1';
                    if (currentVid) currentVid.style.opacity = '0';
                    currentIndex = nextIndex;
                    setTimeout(() => { isTransitioning = false; }, 800);
                };

                if (p !== undefined) {
                    p.then(doFade).catch(doFade);
                } else {
                    doFade();
                }
            } else {
                isTransitioning = false;
            }
        }

        vids.forEach((v) => {
            if (v) {
                v.addEventListener('ended', transitionToNext);
                v.addEventListener('timeupdate', function () {
                    if (v.duration && v.currentTime >= v.duration - 0.4) {
                        transitionToNext();
                    }
                });
            }
        });
    });
</script>
@endpush
@endsection
