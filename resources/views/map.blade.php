@extends('layouts.app')

@section('title', 'Lokasi Studio & Petunjuk Arah — Dutchman Barbershop Surabaya')

@section('content')
<!-- ==========================================================================
     HERO: EDITORIAL LOCATION INTRO
     Cinematic background • Restrained serif typography • Clear CTA hierarchy
     ========================================================================== -->
<section class="map-hero-section">
    <!-- Cinematic Photographic Background -->
    <div class="map-hero-backdrop">
        <img src="{{ asset('images/barbershop/dutchman-official-maps.jpg') }}" 
             alt="Dutchman Barbershop Interior Surabaya" 
             class="map-hero-img">
    </div>

    <!-- Dark Layered Overlay for Calm Contrast -->
    <div class="map-hero-overlay"></div>

    <!-- Editorial Content -->
    <div class="container map-hero-container">
        <div class="map-hero-eyebrow">
            DUTCHMAN BARBERSHOP · SURABAYA
        </div>

        <h1 class="map-hero-title">
            TEMUKAN LOKASI<br>DUTCHMAN.
        </h1>

        <p class="map-hero-subtitle">
            Kunjungi studio kami di Rungkut, Surabaya.
        </p>

        <!-- Refined CTA Hierarchy: Primary (Book) vs Secondary Ghost (Directions) -->
        <div class="map-hero-ctas">
            <a href="{{ route('booking.create') }}" class="btn-hero-primary">
                <span>RESERVASI SEKARANG</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                </svg>
            </a>

            <a href="#location-map" class="btn-hero-secondary">
                <span>PETUNJUK ARAH</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m6 9 6 6 6-6"/>
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- ==========================================================================
     LOCATION SECTION: 60:40 EQUAL-HEIGHT SPLIT SCREEN
     Grayscale Calm Map • Architectural Info Panel • Scannable Hours
     ========================================================================== -->
<section id="location-map" class="map-content-section">
    <div class="container">
        <!-- Section Intro -->
        <div class="map-section-header">
            <div class="map-section-eyebrow">KUNJUNGI KAMI</div>
            <h2 class="map-section-title">
                LOKASI STUDIO &amp; PETUNJUK ARAH
            </h2>
            <p class="map-section-desc">
                Kunjungi Dutchman Barbershop di Rungkut, Surabaya.
            </p>
        </div>

        <!-- 60:40 Split-Screen Grid (Equal Height on Desktop) -->
        <div class="location-split-grid">
            <!-- Left: Subdued Grayscale Interactive Google Map (60%) -->
            <div class="map-frame-wrapper">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.348148962635!2d112.77262737593673!3d-7.331226292677443!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fb24fc52fb7b%3A0x8ba3b7e250f5381d!2sDutchman%20Barbershop!5e0!3m2!1sen!2sid!4v1711200000000!5m2!1sen!2sid" 
                    title="Peta Lokasi Dutchman Barbershop Surabaya"
                    class="map-iframe"
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

            <!-- Right: Refined Editorial Information Card (40%) -->
            <div class="info-panel-wrapper">
                <div class="info-panel-body">
                    <!-- Branch Header -->
                    <div class="info-header">
                        <div class="info-eyebrow">STUDIO SURABAYA</div>
                        <h3 class="info-title">
                            DUTCHMAN BARBERSHOP
                        </h3>
                        <p class="info-tagline">
                            Perawatan rambut pria klasik Eropa, cukur handuk hangat tradisional, dan styling modern.
                        </p>
                    </div>

                    <div class="info-divider"></div>

                    <!-- Details Group -->
                    <div class="info-list">
                        <!-- Address -->
                        <div class="info-item">
                            <div class="info-icon">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                            </div>
                            <div class="info-item-content">
                                <div class="info-label">ALAMAT</div>
                                <div class="info-value">
                                    Jl. Rungkut Madya No.55A, Rungkut Kidul, Kec. Rungkut, Surabaya, Jawa Timur 60293
                                </div>
                            </div>
                        </div>

                        <!-- Opening Hours (Scannable 2-Column Aligned) -->
                        <div class="info-item">
                            <div class="info-icon">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                            </div>
                            <div class="info-item-content">
                                <div class="info-label">JAM OPERASIONAL</div>
                                <div class="hours-schedule">
                                    <div class="hours-row">
                                        <span class="hours-day">Senin – Kamis</span>
                                        <span class="hours-time">11:00 — 21:00 WIB</span>
                                    </div>
                                    <div class="hours-row">
                                        <span class="hours-day">Jumat</span>
                                        <span class="hours-time">13:00 — 21:00 WIB</span>
                                    </div>
                                    <div class="hours-row">
                                        <span class="hours-day">Sabtu – Minggu</span>
                                        <span class="hours-time">11:00 — 22:00 WIB</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact WhatsApp / Phone -->
                        <div class="info-item">
                            <div class="info-icon">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                            </div>
                            <div class="info-item-content">
                                <div class="info-label">WHATSAPP / TELEPON</div>
                                <div class="info-value">
                                    <a href="https://wa.me/6282110009744" 
                                       target="_blank" 
                                       rel="noopener noreferrer" 
                                       class="info-action-link">
                                        +62 821-1000-9744
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Google Maps External Link -->
                        <div class="info-item">
                            <div class="info-icon">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polygon points="3 11 22 2 13 21 11 13 3 11"/>
                                </svg>
                            </div>
                            <div class="info-item-content">
                                <div class="info-label">PETUNJUK GOOGLE MAPS</div>
                                <div class="info-value">
                                    <a href="https://www.google.com/maps/place/Dutchman+Barbershop/@-7.3312263,112.7752023,17z/data=!4m8!3m7!1s0x2dd7fb24fc52fb7b:0x8ba3b7e250f5381d!8m2!3d-7.3312263!4d112.7752023!9m1!1b1!16s%2Fg%2F11vdhkbmgz" 
                                       target="_blank" 
                                       rel="noopener noreferrer" 
                                       class="info-maps-link">
                                        <span>Buka di Google Maps</span>
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                            <polyline points="15 3 21 3 21 9"/>
                                            <line x1="10" y1="14" x2="21" y2="3"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Anchored Primary Booking CTA -->
                <div class="info-panel-footer">
                    <a href="{{ route('booking.create') }}" class="btn-book-studio">
                        <span>RESERVASI DI CABANG INI</span>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================================================
     SCOPED STYLES: EDITORIAL LOCATION & EQUAL HEIGHT SYSTEM
     ========================================================================== -->
<style>
    /* Hero Section */
    .map-hero-section {
        position: relative;
        width: 100%;
        min-height: 380px;
        background: #111111;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 4.5rem 0;
    }

    .map-hero-backdrop {
        position: absolute;
        inset: 0;
        z-index: 1;
    }

    .map-hero-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center 40%;
        filter: brightness(0.42) contrast(1.05) grayscale(20%);
        display: block;
    }

    .map-hero-overlay {
        position: absolute;
        inset: 0;
        z-index: 2;
        background: linear-gradient(180deg, rgba(17,17,17,0.5) 0%, rgba(17,17,17,0.78) 100%);
    }

    .map-hero-container {
        position: relative;
        z-index: 5;
        text-align: center;
        max-width: 820px;
        margin: 0 auto;
    }

    .map-hero-eyebrow {
        font-family: var(--font-sans);
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: #D4D4D4;
        margin-bottom: 0.85rem;
    }

    .map-hero-title {
        font-family: var(--font-serif);
        font-size: clamp(2.3rem, 5vw, 4rem);
        font-weight: 700;
        letter-spacing: 0.02em;
        color: #FFFFFF;
        line-height: 1.1;
        text-transform: uppercase;
        margin-bottom: 1rem;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.6);
    }

    .map-hero-subtitle {
        font-family: var(--font-sans);
        font-size: clamp(1rem, 1.6vw, 1.15rem);
        color: #E2E2E2;
        max-width: 520px;
        margin: 0 auto 2.25rem;
        line-height: 1.6;
    }

    .map-hero-ctas {
        display: flex;
        gap: 1rem;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
    }

    /* Primary vs Secondary CTAs */
    .btn-hero-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        background: #FFFFFF;
        color: #111111;
        font-family: var(--font-sans);
        font-weight: 700;
        font-size: 0.85rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 0.85rem 1.9rem;
        border-radius: 4px;
        text-decoration: none;
        border: 1px solid #FFFFFF;
        transition: all 0.25s ease;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.35);
    }

    .btn-hero-primary:hover {
        background: #EFEFEF;
        border-color: #EFEFEF;
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.45);
    }

    .btn-hero-secondary {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        background: transparent;
        color: #FFFFFF;
        font-family: var(--font-sans);
        font-weight: 600;
        font-size: 0.85rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 0.85rem 1.8rem;
        border-radius: 4px;
        text-decoration: none;
        border: 1px solid rgba(255, 255, 255, 0.38);
        transition: all 0.25s ease;
    }

    .btn-hero-secondary:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: #FFFFFF;
        color: #FFFFFF;
    }

    /* Location Content Section */
    .map-content-section {
        padding: 4.5rem 0 5.5rem;
        background: #FAF9F6;
        border-top: 1px solid var(--border);
    }

    .map-section-header {
        margin-bottom: 2.25rem;
    }

    .map-section-eyebrow {
        font-family: var(--font-sans);
        font-size: 0.74rem;
        font-weight: 700;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        color: #777777;
        margin-bottom: 0.35rem;
    }

    .map-section-title {
        font-family: var(--font-display);
        font-size: clamp(1.8rem, 3.2vw, 2.5rem);
        color: #111111;
        font-weight: 800;
        text-transform: uppercase;
        line-height: 1.15;
        margin-bottom: 0.4rem;
    }

    .map-section-desc {
        font-size: 1rem;
        color: #666666;
        margin: 0;
    }

    /* 60:40 Equal Height Grid System */
    .location-split-grid {
        display: grid;
        grid-template-columns: 3fr 2fr;
        gap: 2rem;
        align-items: stretch;
    }

    /* Left Map (60%) */
    .map-frame-wrapper {
        position: relative;
        width: 100%;
        height: 100%;
        min-height: 540px;
        background: #EAE6DF;
        border: 1px solid var(--border);
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    }

    .map-iframe {
        width: 100%;
        height: 100%;
        min-height: 540px;
        border: 0;
        display: block;
        filter: grayscale(1) contrast(0.92) brightness(0.98);
        transition: filter 0.45s ease;
    }

    .map-frame-wrapper:hover .map-iframe {
        filter: grayscale(0.2) contrast(0.96) brightness(1);
    }

    /* Right Info Panel (40%) */
    .info-panel-wrapper {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        background: #FFFFFF;
        border: 1px solid var(--border);
        border-radius: 6px;
        padding: 2.25rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    }

    .info-panel-body {
        flex: 1;
    }

    .info-header {
        margin-bottom: 1.25rem;
    }

    .info-eyebrow {
        font-family: var(--font-sans);
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: #888888;
        margin-bottom: 0.3rem;
    }

    .info-title {
        font-family: var(--font-display);
        font-size: 1.4rem;
        font-weight: 800;
        color: #111111;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        margin-bottom: 0.4rem;
    }

    .info-tagline {
        font-size: 0.9rem;
        line-height: 1.55;
        color: #666666;
        margin: 0;
    }

    .info-divider {
        height: 1px;
        background: #EFEFEF;
        margin: 1.35rem 0 1.5rem;
    }

    /* Info List */
    .info-list {
        display: flex;
        flex-direction: column;
        gap: 1.35rem;
    }

    .info-item {
        display: flex;
        gap: 0.85rem;
        align-items: flex-start;
    }

    .info-icon {
        color: #111111;
        flex-shrink: 0;
        margin-top: 2px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 22px;
    }

    .info-item-content {
        flex: 1;
        min-width: 0;
    }

    .info-label {
        font-family: var(--font-sans);
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #888888;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-size: 0.92rem;
        color: #222222;
        line-height: 1.5;
    }

    /* 2-Column Scannable Opening Hours */
    .hours-schedule {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        margin-top: 0.25rem;
    }

    .hours-row {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        font-size: 0.9rem;
        padding-bottom: 0.25rem;
        border-bottom: 1px dashed #F0F0F0;
    }

    .hours-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .hours-day {
        color: #555555;
        font-weight: 500;
    }

    .hours-time {
        color: #111111;
        font-weight: 700;
        font-variant-numeric: tabular-nums;
        text-align: right;
    }

    /* Action Links */
    .info-action-link {
        color: #111111;
        font-weight: 700;
        text-decoration: none;
        border-bottom: 1px solid #CCCCCC;
        transition: all 0.2s ease;
    }

    .info-action-link:hover {
        color: #000000;
        border-bottom-color: #111111;
    }

    .info-maps-link {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        color: #111111;
        font-weight: 700;
        font-size: 0.88rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .info-maps-link:hover {
        opacity: 0.75;
        transform: translateX(2px);
    }

    /* Panel Footer & Primary Booking CTA */
    .info-panel-footer {
        margin-top: 1.75rem;
        padding-top: 1.25rem;
        border-top: 1px solid #EFEFEF;
    }

    .btn-book-studio {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.65rem;
        width: 100%;
        background: #111111;
        color: #FFFFFF;
        font-family: var(--font-sans);
        font-size: 0.85rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 0.9rem 1.5rem;
        border-radius: 4px;
        text-decoration: none;
        transition: all 0.25s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .btn-book-studio:hover {
        background: #2B2B2B;
        color: #FFFFFF;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.16);
    }

    /* ==========================================================================
       RESPONSIVE BREAKPOINTS (Mobile & Tablet)
       ========================================================================== --> */
    @media (max-width: 992px) {
        .location-split-grid {
            grid-template-columns: 1fr;
            gap: 1.75rem;
        }

        .map-frame-wrapper {
            min-height: 380px;
            height: 380px;
        }

        .map-iframe {
            min-height: 380px;
            height: 380px;
        }

        .info-panel-wrapper {
            padding: 2rem 1.75rem;
        }
    }

    @media (max-width: 640px) {
        .map-hero-section {
            min-height: 320px;
            padding: 3.5rem 0;
        }

        .map-hero-title {
            font-size: 2.2rem;
        }

        .map-hero-ctas {
            flex-direction: column;
            width: 100%;
        }

        .btn-hero-primary, .btn-hero-secondary {
            width: 100%;
            justify-content: center;
        }

        .map-content-section {
            padding: 3.5rem 0 4.5rem;
        }

        .map-frame-wrapper {
            min-height: 320px;
            height: 320px;
        }

        .map-iframe {
            min-height: 320px;
            height: 320px;
        }

        .info-panel-wrapper {
            padding: 1.5rem;
        }

        .hours-row {
            flex-direction: column;
            gap: 0.15rem;
            align-items: flex-start;
        }

        .hours-time {
            text-align: left;
        }
    }
</style>
@endsection
