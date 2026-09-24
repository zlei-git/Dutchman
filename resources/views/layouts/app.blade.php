<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dutchman Barbershop — Sharp cuts. Quiet confidence.')</title>

    <!-- Google Fonts: Cinzel, Playfair Display & Source Sans 3 (Classic Vintage Gentleman) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Playfair+Display:ital,wght@0,600;0,700;1,500;1,600&family=Source+Sans+3:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    <!-- Unified Minimalist Gentleman CSS System (No Brown • No Yellow Outlines) -->
    <style>
        :root {
            --bg: #F8F8F8;
            --surface: #FFFFFF;
            --surface-alt: #F2F2F2;
            --surface-hover: #EAEAEA;
            --text: #111111;
            --text-muted: #666666;
            --border: #E0E0E0;
            --border-light: #EBEBEB;
            --border-subtle: #EAEAEA;
            --accent: #111111;
            --accent-hover: #222222;
            --leather: #111111;
            --leather-hover: #222222;
            --leather-soft: #F4F4F4;
            --coral: #111111;
            --coral-hover: #222222;
            --coral-soft: #F4F4F4;
            --gold: #111111;
            --gold-hover: #222222;
            --gold-soft: #F4F4F4;
            --yellow: #111111;
            --yellow-soft: #F4F4F4;
            --brass: #111111;
            --brass-hover: #222222;
            --brass-soft: #F4F4F4;
            --blue: #111111;
            --blue-soft: #F4F4F4;
            --green: #111111;
            --green-soft: #F4F4F4;
            --danger: #992323;
            --success: #1B5E20;
            --font-display: 'Cinzel', Georgia, serif;
            --font-serif: 'Playfair Display', Georgia, serif;
            --font-sans: 'Source Sans 3', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --radius-xs: 2px;
            --radius-sm: 4px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-full: 9999px;
            --shadow-sm: 0 2px 6px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 6px 18px rgba(0, 0, 0, 0.07);
            --shadow-lg: 0 16px 36px rgba(0, 0, 0, 0.1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: var(--font-sans);
            font-size: 16px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        h1, h2, h3, h4, .font-serif, .font-display {
            font-family: var(--font-display);
            font-weight: 400;
            letter-spacing: -0.01em;
            color: var(--text);
        }

        h1 { font-size: clamp(2.4rem, 5vw, 3.8rem); line-height: 1.12; }
        h2 { font-size: clamp(1.8rem, 3.5vw, 2.5rem); line-height: 1.2; }
        h3 { font-size: 1.45rem; line-height: 1.3; }
        p { color: var(--text-muted); }

        img {
            border: none !important;
            outline: none;
        }

        a {
            color: inherit;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        /* Container & Layout */
        .container {
            width: 100%;
            max-width: 1240px;
            margin-left: auto;
            margin-right: auto;
            padding-left: 2rem;
            padding-right: 2rem;
        }

        @media (max-width: 768px) {
            .container { padding-left: 1.25rem; padding-right: 1.25rem; }
        }

        /* Navbar */
        .navbar {
            background: rgba(250, 247, 242, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1.5px solid var(--border-subtle);
            position: sticky;
            top: 0;
            z-index: 50;
            height: 76px;
            display: flex;
            align-items: center;
        }

        .navbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .brand-logo {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            line-height: 1;
        }

        .brand-logo-img {
            width: 52px;
            height: 52px;
            border-radius: 6px;
            object-fit: cover;
            border: 1.5px solid var(--border);
            display: block;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .brand-logo-img:hover {
            transform: scale(1.04);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 2.25rem;
            font-family: var(--font-display);
            font-size: 0.84rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-weight: 700;
        }

        .nav-link {
            color: var(--text-muted);
            transition: color 0.2s ease;
            position: relative;
            padding-bottom: 4px;
        }

        .nav-link:hover {
            color: var(--text);
        }

        .nav-link.active {
            color: var(--text);
            font-weight: 800;
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 1.5px;
            background-color: var(--text);
        }

        /* Buttons (Classic Vintage Gentleman) */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-family: var(--font-display);
            font-size: 0.88rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 0.7rem 1.4rem;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            min-height: 44px;
        }

        .btn-brass, .btn-coral, .btn-leather {
            background-color: var(--leather);
            color: #FAF6EF;
            border-color: #5C2F10;
        }

        .btn-brass:hover, .btn-coral:hover, .btn-leather:hover {
            background-color: var(--leather-hover);
            color: #FFFFFF;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(122, 63, 24, 0.28);
        }

        .btn-gold {
            background-color: var(--gold);
            color: #18110B;
            border-color: #A6843E;
        }

        .btn-gold:hover {
            background-color: var(--gold-hover);
            color: #0F0A06;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(197, 160, 89, 0.35);
        }

        .btn-outline {
            background-color: transparent;
            color: var(--text);
            border: 1px solid var(--border);
        }

        .btn-outline:hover {
            border-color: var(--leather);
            background-color: var(--surface-alt);
            color: var(--leather);
            transform: translateY(-1px);
        }

        .btn-sm {
            padding: 0.4rem 0.95rem;
            font-size: 0.78rem;
            min-height: 36px;
        }

        /* Borderless Card System */
        .card {
            background-color: var(--surface);
            border: none;
            box-shadow: var(--shadow-sm);
            border-radius: var(--radius-md);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        /* Badges (Playful Retro Stickers) */
        .badge {
            display: inline-flex;
            align-items: center;
            font-size: 0.74rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            padding: 0.3rem 0.8rem;
            border-radius: var(--radius-full);
            border: none;
        }

        .badge-brass, .badge-coral {
            background-color: var(--coral-soft);
            color: var(--coral);
        }

        .badge-yellow {
            background-color: var(--yellow-soft);
            color: #B45309;
        }

        .badge-muted {
            background-color: var(--surface-alt);
            color: var(--text-muted);
        }

        .badge-success {
            background-color: var(--green-soft);
            color: #065F46;
        }

        /* Utilities */
        .flex { display: flex; }
        .flex-col { flex-direction: column; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .justify-center { justify-content: center; }
        .gap-1 { gap: 0.25rem; }
        .gap-2 { gap: 0.5rem; }
        .gap-3 { gap: 0.75rem; }
        .gap-4 { gap: 1rem; }
        .gap-6 { gap: 1.5rem; }
        .gap-8 { gap: 2rem; }
        .flex-wrap { flex-wrap: wrap; }
        .flex-1 { flex: 1; }
        .w-full { width: 100%; }

        .grid { display: grid; }
        .grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-cols-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-cols-4 { grid-template-columns: repeat(4, 1fr); }

        @media (max-width: 900px) {
            .grid-cols-3, .grid-cols-4 { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 640px) {
            .grid-cols-2, .grid-cols-3, .grid-cols-4 { grid-template-columns: 1fr; }
            .nav-links { display: none; }
            .mobile-menu-btn { display: flex !important; }
        }

        /* Flash Messages */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: var(--radius-sm);
            font-size: 0.92rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: var(--shadow-sm);
        }

        .alert-success {
            background-color: var(--green-soft);
            color: #065F46;
        }

        .alert-error {
            background-color: var(--coral-soft);
            color: #991B1B;
        }

        /* Mobile Nav Drawer */
        .mobile-drawer {
            display: none;
            position: fixed;
            top: 76px;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: var(--bg);
            border-top: 1.5px solid var(--border-subtle);
            padding: 2rem 1.5rem;
            z-index: 49;
            flex-direction: column;
            gap: 1.5rem;
        }

        .mobile-drawer.open {
            display: flex;
        }

        /* Minimal Retro Footer */
        .footer {
            margin-top: auto;
            border-top: 1.5px solid var(--border-subtle);
            background-color: var(--surface-alt);
            padding: 4rem 0 2.5rem;
            font-size: 0.92rem;
        }

        /* Top Running Marquee Banner (Classic Vintage Gentleman) */
        .top-marquee-bar {
            background-color: #140E09;
            color: var(--gold);
            overflow: hidden;
            white-space: nowrap;
            position: relative;
            padding: 0.55rem 0;
            font-family: var(--font-display);
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            border-bottom: 1px solid rgba(197, 160, 89, 0.22);
            z-index: 55;
            user-select: none;
        }

        .marquee-track {
            display: flex;
            width: max-content;
            will-change: transform;
            animation: marquee-scroll 30s linear infinite;
        }

        .marquee-track:hover {
            animation-play-state: paused;
        }

        .marquee-items {
            display: inline-flex;
            align-items: center;
            gap: 2rem;
            padding-right: 2rem;
            flex-shrink: 0;
        }

        .marquee-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-style: normal;
            font-size: 0.95rem;
            color: var(--gold);
        }

        @keyframes marquee-scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* Feature Cartoon Cards System (Image 1 Birds Barbershop Style) */
        .feature-cartoon-card {
            border-radius: 22px;
            padding: 2.75rem 1.75rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            gap: 1.25rem;
            text-decoration: none;
            box-shadow: var(--shadow-sm);
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.25s ease;
            border: none !important;
        }

        .feature-cartoon-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 32px rgba(0, 0, 0, 0.12);
        }

        .feature-cartoon-text {
            font-family: var(--font-sans);
            font-weight: 900;
            font-style: italic;
            font-size: clamp(1.4rem, 2.2vw, 1.85rem);
            line-height: 1.12;
            text-transform: uppercase;
            letter-spacing: -0.01em;
        }

        /* Birds Barbershop Hero Checkered Pillars & Full Photo Showcase */
        .checkerboard-pillar {
            width: 56px;
            flex-shrink: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='56' height='56' viewBox='0 0 56 56'%3E%3Crect width='28' height='28' fill='%2318181B'/%3E%3Crect x='28' width='28' height='28' fill='%239E9E9E'/%3E%3Crect y='28' width='28' height='28' fill='%239E9E9E'/%3E%3Crect x='28' y='28' width='28' height='28' fill='%2318181B'/%3E%3C/svg%3E");
            background-repeat: repeat-y;
            background-size: 56px 56px;
            z-index: 10;
        }

        @media (max-width: 900px) {
            .checkerboard-pillar {
                width: 32px;
                background-size: 32px 32px;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 32 32'%3E%3Crect width='16' height='16' fill='%2318181B'/%3E%3Crect x='16' width='16' height='16' fill='%239E9E9E'/%3E%3Crect y='16' width='16' height='16' fill='%239E9E9E'/%3E%3Crect x='16' y='16' width='16' height='16' fill='%2318181B'/%3E%3C/svg%3E");
            }
        }

        @media (max-width: 600px) {
            .checkerboard-pillar {
                display: none;
            }
        }

        .hero-birds-headline {
            font-family: var(--font-impact, 'Impact', sans-serif);
            font-style: italic;
            font-weight: 900;
            font-size: clamp(3.2rem, 7.5vw, 5.8rem);
            line-height: 0.94;
            letter-spacing: -0.01em;
            color: #FFFFFF !important;
            text-transform: uppercase;
            margin-bottom: 1.15rem;
            text-shadow: 0 4px 24px rgba(0,0,0,0.7);
        }

        .hero-pill-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-impact, 'Impact', sans-serif);
            font-style: italic;
            font-weight: 900;
            font-size: 1.15rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            padding: 0.85rem 2.2rem;
            border-radius: var(--radius-full);
            text-decoration: none;
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
            transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.2s ease;
            border: none;
            min-height: 48px;
        }

        .hero-pill-btn:hover {
            transform: translateY(-2px) scale(1.03);
            box-shadow: 0 10px 28px rgba(0,0,0,0.4);
        }

        .hero-pill-white {
            background-color: #FFFFFF;
            color: #18181B;
        }

        .hero-pill-white:hover {
            background-color: #F4F4F5;
            color: #000000;
        }

        .hero-pill-lime {
            background-color: #D4F038;
            color: #18181B;
        }

        .hero-pill-lime:hover {
            background-color: #E2FA4D;
            color: #000000;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Top Navigation -->
    <header class="navbar">
        <div class="container navbar-inner">
            <a href="{{ route('home') }}" class="brand-logo" title="Dutchman Barbershop" aria-label="Dutchman Barbershop">
                <img src="{{ asset('images/barbershop/official-avatar.jpg') }}" alt="Dutchman Barbershop" class="brand-logo-img">
            </a>

            <!-- Desktop Links -->
            <nav class="nav-links">
                <a href="{{ route('services.index') }}" class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}">Layanan</a>
                <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">Tentang Kami</a>
                <a href="{{ route('map') }}" class="nav-link {{ request()->routeIs('map') ? 'active' : '' }}">Lokasi &amp; Peta</a>

                @auth
                    <a href="{{ route('user.profile') }}" class="nav-link {{ request()->routeIs('user.profile*') ? 'active' : '' }}">Profil Saya</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="nav-link" style="color: var(--coral);">Panel Admin</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="nav-link">Masuk</a>
                @endauth
            </nav>

            <!-- Primary CTA -->
            <div class="flex items-center gap-3">
                <a href="{{ route('booking.create') }}" class="btn btn-coral">
                    <span>RESERVASI SEKARANG</span>
                </a>

                <!-- Mobile Hamburger Toggle -->
                <button type="button" class="btn btn-outline btn-sm mobile-menu-btn" style="display: none; padding: 0.4rem 0.6rem;" id="menuToggle" aria-label="Menu Navigasi">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Drawer Menu -->
    <div class="mobile-drawer" id="mobileDrawer">
        <a href="{{ route('home') }}" class="nav-link" style="font-size: 1.3rem; font-family: var(--font-display);">Beranda</a>
        <a href="{{ route('services.index') }}" class="nav-link" style="font-size: 1.3rem; font-family: var(--font-display);">Layanan &amp; Harga</a>
        <a href="{{ route('about') }}" class="nav-link" style="font-size: 1.3rem; font-family: var(--font-display);">Tentang Dutchman</a>
        <a href="{{ route('map') }}" class="nav-link" style="font-size: 1.3rem; font-family: var(--font-display);">Lokasi &amp; Peta</a>
        <hr style="border: 0; border-top: 1.5px solid var(--border-subtle);">
        @auth
            <a href="{{ route('user.profile') }}" class="nav-link" style="font-size: 1.1rem; font-weight: 600;">Profil &amp; Bookingan Saya</a>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="nav-link" style="color: var(--coral); font-size: 1.1rem;">Dasbor Admin</a>
            @endif
            <form action="{{ route('logout') }}" method="POST" style="margin-top: 1rem;">
                @csrf
                <button type="submit" class="btn btn-outline btn-sm w-full">Keluar</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn btn-outline w-full">Masuk</a>
            <a href="{{ route('register') }}" class="btn btn-coral w-full">Daftar Akun</a>
        @endauth
    </div>

    <!-- Main Content -->
    <main style="flex: 1;">
        @if(session('success') || session('error'))
        <div class="container" style="padding-top: 1rem;">
            @if(session('success'))
                <div class="alert alert-success">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer Styled Ala Screenshot 5 (Black #111111, Logo, Tagline, Socials, Navigation, NO Subscribe) -->
    <footer style="background: #111111; color: #FFFFFF; padding: 4.5rem 0 2.5rem; border-top: 1px solid #222222; font-family: var(--font-sans);">
        <div class="container">
            <div style="display: grid; grid-template-columns: 1.5fr 1fr 1.2fr; gap: 3.5rem; padding-bottom: 3.5rem; border-bottom: 1px solid #262626;">
                <!-- Left: Big Logo, Tagline & Socials -->
                <div>
                    <a href="{{ route('home') }}" style="display: inline-block; text-decoration: none; margin-bottom: 1.25rem;">
                        <span style="font-family: var(--font-display); font-size: 2rem; font-weight: 900; letter-spacing: 0.08em; color: #FFFFFF; text-transform: uppercase;">
                            DUTCHMAN
                        </span>
                        <div style="font-size: 0.74rem; letter-spacing: 0.28em; color: #888888; text-transform: uppercase; margin-top: -4px;">
                            BARBERSHOP • SURABAYA
                        </div>
                    </a>

                    <p style="font-size: 0.95rem; line-height: 1.65; color: #A0A0A0; max-width: 360px; margin-bottom: 1.75rem;">
                        Kami hadir untuk memberikan potongan rambut terbaik dan menyempurnakan hari Anda. Ada masukan atau saran untuk kami? Beritahu kami.<br>
                        <span style="display: block; margin-top: 0.5rem; font-style: italic; color: #CCCCCC;">Salam hangat, Dutchman</span>
                    </p>

                    <!-- Social Icons Row -->
                    <div style="display: flex; gap: 0.85rem; align-items: center;">
                        <a href="https://instagram.com/dutchmanbarbershop" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; border-radius: 4px; background: #222222; color: #FFFFFF; text-decoration: none; transition: background 0.2s;" title="Instagram">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                        </a>
                        <a href="https://wa.me/6282110009744" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; border-radius: 4px; background: #222222; color: #FFFFFF; text-decoration: none; transition: background 0.2s;" title="WhatsApp">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </a>

                    </div>
                </div>

                <!-- Middle: Explore Navigation -->
                <div>
                    <div style="font-family: var(--font-display); font-size: 0.95rem; font-weight: 800; letter-spacing: 0.1em; color: #FFFFFF; text-transform: uppercase; margin-bottom: 1.5rem;">
                        JELAJAHI
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.85rem; font-size: 0.95rem; font-weight: 600;">
                        <a href="{{ route('home') }}" style="color: #BBBBBB; text-decoration: none; transition: color 0.2s;">Beranda</a>
                        <a href="{{ route('about') }}" style="color: #BBBBBB; text-decoration: none; transition: color 0.2s;">Tentang Dutchman</a>
                        <a href="{{ route('services.index') }}" style="color: #BBBBBB; text-decoration: none; transition: color 0.2s;">Layanan &amp; Harga</a>
                        <a href="{{ route('map') }}" style="color: #BBBBBB; text-decoration: none; transition: color 0.2s;">Lokasi &amp; Peta</a>
                        <a href="{{ route('booking.create') }}" style="color: #FFFFFF; font-weight: 800; text-decoration: none; transition: color 0.2s;">Reservasi Sekarang</a>
                        @auth
                            <a href="{{ route('user.bookings.index') }}" style="color: #BBBBBB; text-decoration: none; transition: color 0.2s;">Booking Saya</a>
                        @endauth
                    </div>
                </div>

                <!-- Right: Location & Hours -->
                <div>
                    <div style="font-family: var(--font-display); font-size: 0.95rem; font-weight: 800; letter-spacing: 0.1em; color: #FFFFFF; text-transform: uppercase; margin-bottom: 1.5rem;">
                        LOKASI &amp; JAM OPERASIONAL
                    </div>
                    <div style="font-size: 0.92rem; color: #A0A0A0; line-height: 1.65;">
                        <div style="color: #FFFFFF; font-weight: 700; margin-bottom: 0.35rem;">
                            Jl. Rungkut Madya No.55A, Surabaya
                        </div>
                        <div style="color: #888888; font-size: 0.85rem; margin-bottom: 1rem;">
                            Rungkut Kidul, Kec. Rungkut, Surabaya 60293
                        </div>
                        <div style="margin-bottom: 0.35rem;">
                            <strong style="color: #CCCCCC;">Senin – Kamis:</strong> 11:00 – 21:00 WIB
                        </div>
                        <div style="margin-bottom: 0.35rem;">
                            <strong style="color: #CCCCCC;">Jumat:</strong> 13:00 – 21:00 WIB
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <strong style="color: #CCCCCC;">Sabtu – Minggu:</strong> 11:00 – 22:00 WIB
                        </div>
                        <div>
                            <span style="color: #CCCCCC; font-weight: 600;">Telepon / WA:</span> 
                            <a href="https://wa.me/6282110009744" style="color: #FFFFFF; text-decoration: underline; font-weight: 700;">+62 821-1000-9744</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright -->
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; padding-top: 2rem; font-size: 0.85rem; color: #777777;">
                <div>
                    &copy; {{ date('Y') }} Dutchman Barbershop Surabaya. Seluruh hak cipta dilindungi.
                </div>
                <div>
                    Seni Barbershop Klasik Berkualitas Tinggi
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('menuToggle')?.addEventListener('click', function () {
            document.getElementById('mobileDrawer')?.classList.toggle('open');
        });
    </script>
    @stack('scripts')
</body>
</html>
