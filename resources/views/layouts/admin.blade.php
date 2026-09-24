<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Studio Admin') — Dutchman Barbershop</title>

    <!-- Google Fonts: EB Garamond & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400..800;1,400..800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #0E0E0C;
            --sidebar-bg: #141412;
            --main-bg: #181816;
            --surface: #20201C;
            --surface-hover: #2A2A25;
            --text: #F4F1EA;
            --text-muted: #9E9A8E;
            --border: #2E2D27;
            --brass: #C2A675;
            --brass-hover: #D4B988;
            --brass-soft: rgba(194, 166, 117, 0.15);
            --danger: #E05252;
            --success: #52B788;
            --font-serif: 'EB Garamond', Garamond, Georgia, serif;
            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --radius-sm: 4px;
            --radius-md: 6px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-color: var(--main-bg);
            color: var(--text);
            font-family: var(--font-sans);
            font-size: 14px;
            line-height: 1.5;
            min-height: 100vh;
        }

        h1, h2, h3, h4, .font-serif {
            font-family: var(--font-serif);
            font-weight: 500;
            color: var(--text);
        }

        a { color: inherit; text-decoration: none; }

        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .admin-sidebar {
            width: 250px;
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            height: 100vh;
        }

        .admin-brand {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
        }

        .brand-logo {
            font-family: var(--font-serif);
            font-size: 1.25rem;
            letter-spacing: 0.1em;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 0.55rem;
            font-weight: 600;
        }

        .brand-logo-img {
            width: 28px;
            height: 28px;
            border-radius: var(--radius-sm);
            object-fit: cover;
            border: 1px solid var(--border);
        }

        .admin-nav {
            list-style: none;
            padding: 1.25rem 0.75rem;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            flex: 1;
            overflow-y: auto;
        }

        .admin-nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.85rem;
            border-radius: var(--radius-sm);
            color: var(--text-muted);
            font-weight: 500;
            font-size: 0.88rem;
            transition: all 0.15s ease;
        }

        .admin-nav-item:hover {
            color: var(--text);
            background-color: var(--surface);
        }

        .admin-nav-item.active {
            color: #11110F;
            background-color: var(--brass);
            font-weight: 600;
        }

        .admin-nav-item.active svg {
            stroke: #11110F;
        }

        /* Topbar & Content */
        .admin-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .admin-topbar {
            height: 64px;
            background-color: var(--sidebar-bg);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .admin-content {
            padding: 2rem;
            flex: 1;
        }

        /* Utility Components */
        .card {
            background-color: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.84rem;
            font-weight: 600;
            padding: 0.55rem 1.1rem;
            border-radius: var(--radius-sm);
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.15s ease;
            min-height: 38px;
        }

        .btn-brass {
            background: var(--brass);
            color: #11110F;
        }
        .btn-brass:hover { background: var(--brass-hover); }

        .btn-outline {
            background: transparent;
            border-color: var(--border);
            color: var(--text);
        }
        .btn-outline:hover { background: var(--surface-hover); border-color: var(--text); }

        .btn-sm { padding: 0.35rem 0.75rem; font-size: 0.78rem; min-height: 32px; }
        .btn-danger { background: rgba(224,82,82,0.15); color: var(--danger); border: 1px solid rgba(224,82,82,0.3); }

        .badge {
            display: inline-flex;
            align-items: center;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 0.2rem 0.55rem;
            border-radius: 2px;
        }
        .badge-brass { background: var(--brass-soft); color: var(--brass); border: 1px solid rgba(165,138,91,0.3); }
        .badge-success { background: rgba(82,183,136,0.15); color: var(--success); border: 1px solid rgba(82,183,136,0.3); }
        .badge-danger { background: rgba(224,82,82,0.15); color: var(--danger); border: 1px solid rgba(224,82,82,0.3); }
        .badge-muted { background: var(--surface-hover); color: var(--text-muted); border: 1px solid var(--border); }

        /* Tables */
        .table-responsive { width: 100%; overflow-x: auto; }
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.86rem;
        }
        .admin-table th {
            padding: 0.85rem 1.25rem;
            background: rgba(0,0,0,0.25);
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.72rem;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border);
        }
        .admin-table td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            color: var(--text);
            vertical-align: middle;
        }
        .admin-table tr:hover td {
            background-color: rgba(255,255,255,0.02);
        }

        .flex { display: flex; }
        .flex-col { flex-direction: column; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-1 { gap: 0.25rem; }
        .gap-2 { gap: 0.5rem; }
        .gap-3 { gap: 0.75rem; }
        .gap-4 { gap: 1rem; }
        .gap-6 { gap: 1.5rem; }
        .w-full { width: 100%; }

        .grid { display: grid; }
        .grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-cols-4 { grid-template-columns: repeat(4, 1fr); }
        .grid-cols-5 { grid-template-columns: repeat(5, 1fr); }

        .alert {
            padding: 0.85rem 1.25rem;
            border-radius: var(--radius-sm);
            font-size: 0.88rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .alert-success { background: rgba(82, 183, 136, 0.12); border: 1px solid rgba(82, 183, 136, 0.35); color: #72D5A3; }
        .alert-danger { background: rgba(224, 82, 82, 0.12); border: 1px solid rgba(224, 82, 82, 0.35); color: #FFA5A5; }

        input, select, textarea {
            background: var(--sidebar-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text);
            font-family: var(--font-sans);
            outline: none;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-brand">
                <a href="{{ route('admin.dashboard') }}" class="brand-logo">
                    <img src="{{ asset('images/barbershop/official-avatar.jpg') }}" alt="Dutchman" class="brand-logo-img">
                    <span>DUTCHMAN</span>
                </a>
                <span class="badge badge-brass" style="font-size: 0.65rem;">ADMIN</span>
            </div>

            <ul class="admin-nav">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                        <span>Dasbor</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.bookings.index') }}" class="admin-nav-item {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="m9 16 2 2 4-4"/></svg>
                        <span>Jadwal Booking</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.services.index') }}" class="admin-nav-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><line x1="20" y1="4" x2="8.12" y2="15.88"/><line x1="14.47" y1="14.48" x2="20" y2="20"/><line x1="8.12" y1="8.12" x2="12" y2="12"/></svg>
                        <span>Layanan &amp; Harga</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.barbers.index') }}" class="admin-nav-item {{ request()->routeIs('admin.barbers.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <span>Master Barber &amp; Meja</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.customers.index') }}" class="admin-nav-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <span>Pelanggan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.settings.index') }}" class="admin-nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                        <span>Pengaturan Studio</span>
                    </a>
                </li>
            </ul>

            <div style="padding: 1.25rem; border-top: 1px solid var(--border); font-size: 0.82rem;">
                <div style="font-weight: 600; color: var(--text);">{{ auth()->user()->name }}</div>
                <div style="color: var(--text-muted); font-size: 0.75rem; margin-bottom: 0.75rem;">{{ auth()->user()->email }}</div>
                <div class="flex gap-2">
                    <a href="{{ route('home') }}" class="btn btn-outline btn-sm flex-1" target="_blank">Lihat Web</a>
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm" title="Keluar">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Body -->
        <div class="admin-main">
            <header class="admin-topbar">
                <div style="font-size: 1.15rem; font-weight: 700; color: var(--text);">
                    @yield('page_title', 'Administrasi Studio')
                </div>
                <div class="flex items-center gap-3">
                    <span style="font-size: 0.82rem; color: var(--text-muted); display: none; @media(min-width: 640px){display: inline;}">{{ now()->translatedFormat('l, d F Y') }}</span>

                    <!-- Admin Booking Notification Bell -->
                    <div style="position: relative;" id="adminNotificationWrapper">
                        <button type="button" id="notifBellBtn" onclick="toggleAdminNotifications()" style="position: relative; background: var(--surface); border: 1px solid var(--border); width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--text); cursor: pointer; transition: all 0.2s;" title="Notifikasi Booking">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                            @php
                                $badgeCount = isset($adminNewCount) ? $adminNewCount : 0;
                            @endphp
                            <span id="notifBadge" style="display: {{ $badgeCount > 0 ? 'flex' : 'none' }}; position: absolute; top: -3px; right: -3px; background: #E05252; color: #FFFFFF; font-size: 0.68rem; font-weight: 800; min-width: 18px; height: 18px; border-radius: 9px; align-items: center; justify-content: center; padding: 0 4px; box-shadow: 0 0 8px rgba(224,82,82,0.8);">
                                {{ $badgeCount }}
                            </span>
                        </button>

                        <!-- Notification Dropdown Menu -->
                        <div id="notifDropdown" style="display: none; position: absolute; right: 0; top: 48px; width: 350px; background: #181815; border: 1px solid var(--border); border-radius: var(--radius-md); box-shadow: 0 16px 36px rgba(0,0,0,0.65); z-index: 100; overflow: hidden;">
                            <div style="padding: 0.85rem 1.1rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: rgba(0,0,0,0.3);">
                                <div style="font-weight: 700; font-family: var(--font-serif); font-size: 1.05rem; color: var(--text); display: flex; align-items: center; gap: 0.4rem;">
                                    <span>🔔 Booking Baru</span>
                                </div>
                                <span style="font-size: 0.72rem; color: var(--brass); font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">Waktu Nyata</span>
                            </div>
                            <div id="notifList" style="max-height: 380px; overflow-y: auto;">
                                @if(isset($adminRecentBookings) && $adminRecentBookings->count() > 0)
                                    @foreach($adminRecentBookings as $nb)
                                        <a href="{{ route('admin.bookings.index') }}?search={{ $nb->booking_number }}" style="display: block; padding: 0.85rem 1.1rem; border-bottom: 1px solid var(--border); transition: background 0.15s; text-decoration: none;" onmouseover="this.style.background='var(--surface-hover)'" onmouseout="this.style.background='transparent'">
                                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.2rem;">
                                                <span style="font-weight: 700; font-size: 0.88rem; color: var(--text);">{{ $nb->customer_name }}</span>
                                                <span style="font-size: 0.72rem; color: var(--text-muted);">{{ $nb->created_at ? $nb->created_at->diffForHumans() : 'Baru saja' }}</span>
                                            </div>
                                            <div style="font-size: 0.8rem; color: var(--brass); margin-bottom: 0.25rem;">
                                                {{ $nb->items->first()?->service_name ?? 'Layanan Barbershop' }} • Meja {{ $nb->chair_code ?: ($nb->barber?->chair_code ?: 'A1') }}
                                            </div>
                                            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; color: var(--text-muted);">
                                                <span>📅 {{ $nb->booking_date ? $nb->booking_date->format('d M') : '' }} ({{ substr($nb->booking_time, 0, 5) }})</span>
                                                <span class="badge {{ $nb->status === 'confirmed' ? 'badge-success' : 'badge-brass' }}" style="font-size: 0.65rem; padding: 0.1rem 0.45rem;">{{ strtoupper($nb->status) }}</span>
                                            </div>
                                        </a>
                                    @endforeach
                                @else
                                    <div style="padding: 2rem; text-align: center; color: var(--text-muted); font-size: 0.84rem;">
                                        Belum ada notifikasi booking baru.
                                    </div>
                                @endif
                            </div>
                            <div style="padding: 0.65rem; text-align: center; border-top: 1px solid var(--border); background: rgba(0,0,0,0.25);">
                                <a href="{{ route('admin.bookings.index') }}" style="font-size: 0.78rem; color: var(--brass); font-weight: 600;">Lihat Semua Booking &rarr;</a>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('booking.create') }}" class="btn btn-brass btn-sm" target="_blank">
                        + Booking Baru
                    </a>

                    <form action="{{ route('logout') }}" method="POST" style="margin: 0; display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm" title="Keluar / Logout" style="border-color: rgba(239,68,68,0.3); color: #EF4444; font-size: 0.82rem; display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.45rem 0.8rem; cursor: pointer;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            </header>

            <div class="admin-content">
                @if(session('success'))
                    <div class="alert alert-success">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('admin_content')
            </div>
        </div>
    </div>

    <script>
        function toggleAdminNotifications() {
            const dropdown = document.getElementById('notifDropdown');
            if (!dropdown) return;
            dropdown.style.display = dropdown.style.display === 'none' || dropdown.style.display === '' ? 'block' : 'none';
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('adminNotificationWrapper');
            const dropdown = document.getElementById('notifDropdown');
            if (wrapper && dropdown && !wrapper.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });

        // Periodic live poll for new bookings (every 20s)
        function pollBookingNotifications() {
            fetch("{{ route('admin.notifications') }}")
                .then(res => res.json())
                .then(data => {
                    if (!data.success) return;
                    const badge = document.getElementById('notifBadge');
                    if (badge) {
                        badge.textContent = data.count;
                        badge.style.display = data.count > 0 ? 'flex' : 'none';
                    }
                    const list = document.getElementById('notifList');
                    if (list && data.notifications && data.notifications.length > 0) {
                        list.innerHTML = data.notifications.map(n => `
                            <div style="padding: 0.85rem 1.1rem; border-bottom: 1px solid var(--border); transition: background 0.15s;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.2rem;">
                                    <a href="${n.url}" style="font-weight: 700; font-size: 0.88rem; color: var(--text); text-decoration: none;">${n.customer_name}</a>
                                    <span style="font-size: 0.72rem; color: var(--text-muted);">${n.time_ago}</span>
                                </div>
                                <div style="font-size: 0.8rem; color: var(--brass); margin-bottom: 0.25rem;">
                                    ${n.service_name} • Meja ${n.chair_code} • ${n.total_formatted}
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; color: var(--text-muted); margin-bottom: ${n.status === 'pending' ? '0.5rem' : '0'};">
                                    <span>📅 ${n.booking_date} (${n.booking_time})</span>
                                    <span class="badge ${n.status === 'confirmed' ? 'badge-success' : (n.status === 'pending' ? 'badge-brass' : 'badge-danger')}" style="font-size: 0.65rem; padding: 0.1rem 0.45rem;">${n.status.toUpperCase()}</span>
                                </div>
                                ${n.status === 'pending' ? `
                                    <div style="display: flex; gap: 0.35rem; margin-top: 0.4rem;">
                                        <button type="button" onclick="quickAdminUpdateStatus(${n.id}, 'confirmed')" class="btn btn-sm" style="flex: 1; background: #2E7D32; color: #FFFFFF; font-weight: 700; border: none; height: 26px; padding: 0; font-size: 0.72rem; cursor: pointer;">
                                            ✓ Terima
                                        </button>
                                        <button type="button" onclick="quickAdminUpdateStatus(${n.id}, 'cancelled')" class="btn btn-danger btn-sm" style="flex: 1; height: 26px; padding: 0; font-size: 0.72rem; font-weight: 700; cursor: pointer;">
                                            ✕ Tolak
                                        </button>
                                    </div>
                                ` : ''}
                            </div>
                        `).join('');
                    }
                })
                .catch(() => {});
        }

        function quickAdminUpdateStatus(bookingId, newStatus) {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch(`/admin/bookings/${bookingId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    _method: 'PUT',
                    status: newStatus
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    pollBookingNotifications();
                    if (window.location.pathname.includes('/admin/bookings') || window.location.pathname.includes('/admin/dashboard') || window.location.pathname === '/admin') {
                        window.location.reload();
                    }
                }
            })
            .catch(err => console.error('Failed to update status', err));
        }

        setInterval(pollBookingNotifications, 5000);
    </script>

    @stack('scripts')
</body>
</html>
