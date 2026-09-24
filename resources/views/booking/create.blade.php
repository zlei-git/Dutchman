@extends('layouts.app')

@section('title', 'Reservasi Jadwal — Dutchman Barbershop')

@section('content')
<section style="padding: 2.5rem 0 5rem; background: var(--bg);">
    <div class="container" style="max-width: 1240px;">

        <!-- Sign In Prompt Banner (Matching Zenoti Screenshot) -->
        @guest
            <div style="background: #FFFDF8; border: 1px solid var(--border); padding: 1rem 1.5rem; border-radius: var(--radius-sm); margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; box-shadow: var(--shadow-sm);">
                <div style="font-size: 0.95rem; color: var(--text); font-family: var(--font-sans);">
                    Silakan masuk untuk menjadwalkan, membatalkan booking, dan melihat riwayat profil Anda.
                </div>
                <a href="{{ route('login') }}" class="btn btn-leather btn-sm" style="padding: 0.45rem 1.4rem;">
                    Masuk
                </a>
            </div>
        @else
            <div style="background: #FFFDF8; border: 1px solid var(--border); padding: 0.85rem 1.5rem; border-radius: var(--radius-sm); margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div style="font-size: 0.92rem; color: var(--text);">
                    Selamat datang kembali, <strong style="color: var(--leather); font-family: var(--font-display);">{{ Auth::user()->name }}</strong>. Jadwalkan sesi perawatan rambut Anda.
                </div>
                <a href="{{ route('user.bookings.index') }}" class="btn btn-outline btn-sm">
                    Booking Saya
                </a>
            </div>
        @endguest

        <!-- Two Column Zenoti Layout -->
        <div style="display: grid; grid-template-columns: 1fr 380px; gap: 2.5rem; align-items: start;">

            <!-- LEFT COLUMN: Main Booking Flow -->
            <div>
                <!-- Shop Header & Booking For Selector -->
                <div class="card" style="padding: 1.75rem 2rem; background: var(--surface); border: 1px solid var(--border); margin-bottom: 1.75rem;">
                    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-light); padding-bottom: 0.75rem;">
                        <div>
                            <div style="font-size: 0.75rem; font-family: var(--font-display); font-weight: 700; color: var(--text-muted); letter-spacing: 0.1em; text-transform: uppercase;">
                                STUDIO
                            </div>
                            <div style="font-size: 1.25rem; font-family: var(--font-display); font-weight: 700; color: var(--text); margin-top: 0.2rem;">
                                DUTCHMAN BARBERSHOP — RUNGKUT
                            </div>
                        </div>
                        <span style="font-size: 0.82rem; color: var(--gold); font-family: var(--font-display); font-weight: 700; letter-spacing: 0.04em;">
                            SURABAYA
                        </span>
                    </div>

                    <!-- Appointment For Dropdown -->
                    <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                        <label style="font-size: 0.92rem; color: var(--text); font-weight: 600;">
                            Saya ingin memesan jadwal untuk
                        </label>
                        <select id="bookingPartySelect" style="height: 40px; padding: 0 1rem; background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius-xs); font-family: var(--font-sans); font-size: 0.9rem; font-weight: 600; color: var(--text); outline: none;">
                            <option value="me">Hanya Saya</option>
                            <option value="two">Saya &amp; Teman</option>
                        </select>
                    </div>
                </div>

                <!-- SELECT A SERVICE(S) Section -->
                <div class="card" style="padding: 2rem; background: var(--surface); border: 1px solid var(--border); margin-bottom: 2rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                        <h2 style="font-size: 1.15rem; font-family: var(--font-display); font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--text);">
                            PILIH LAYANAN
                        </h2>
                        <span style="font-size: 0.82rem; color: var(--text-muted);">
                            Layanan profesional bergaransi
                        </span>
                    </div>

                    <!-- Service Search Input (Real-time Filter) -->
                    <div style="position: relative; margin-bottom: 1.75rem;">
                        <input type="text" id="serviceSearchInput" placeholder="Cari layanan..." style="width: 100%; height: 46px; background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius-xs); padding: 0 1rem 0 2.75rem; font-family: var(--font-sans); font-size: 0.92rem; color: var(--text); outline: none;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2" style="position: absolute; left: 14px; top: 14px;"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    </div>

                    <!-- Service Category Accordions (Dynamic from Dutchman Menu Book) -->
                    <div class="service-accordion-group" style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @php
                            $bookingGroups = $services->groupBy(function($item) {
                                return $item->category ?: 'Haircut';
                            });
                            $activeService = null;
                            if ($selectedServiceId) {
                                $activeService = $services->firstWhere('id', (int)$selectedServiceId);
                            }
                            if (!$activeService) {
                                $activeService = $services->first();
                            }
                        @endphp

                        @foreach($bookingGroups as $categoryName => $catServices)
                            @php
                                $catId = 'cat-' . \Illuminate\Support\Str::slug($categoryName);
                                $isCatActive = $activeService && $catServices->contains('id', $activeService->id);
                                $isCatOpen = $selectedServiceId ? $isCatActive : $loop->first;
                            @endphp
                            <div class="accordion-item" style="border: 1px solid var(--border); border-radius: var(--radius-xs); overflow: hidden; background: #FFFFFF;">
                                <button type="button" class="accordion-header" onclick="toggleAccordion('{{ $catId }}')" style="width: 100%; padding: 1.1rem 1.4rem; background: var(--surface-alt); border: none; text-align: left; display: flex; justify-content: space-between; align-items: center; cursor: pointer; font-family: var(--font-display); font-weight: 700; font-size: 0.98rem; color: var(--text); letter-spacing: 0.04em;">
                                    <div style="display: flex; align-items: center; gap: 0.65rem;">
                                        <span id="icon-{{ $catId }}" style="font-size: 0.8rem; color: #111111; transition: transform 0.2s;">{{ $isCatOpen ? '▼' : '▶' }}</span>
                                        <span>{{ $categoryName }}</span>
                                    </div>
                                    <span style="font-size: 0.78rem; font-family: var(--font-sans); color: var(--text-muted); font-weight: 600;">{{ $catServices->count() }} Layanan</span>
                                </button>
                                <div id="{{ $catId }}" class="accordion-content" style="display: {{ $isCatOpen ? 'block' : 'none' }}; padding: 0.5rem 1.25rem 1rem;">
                                    @foreach($catServices as $s)
                                        @php
                                            $isThisSelected = $activeService && ((int)$s->id === (int)$activeService->id);
                                        @endphp
                                        <div class="service-row {{ $isThisSelected ? 'service-row-selected' : '' }}" id="service-row-{{ $s->id }}" data-name="{{ strtolower($s->name) }}" data-id="{{ $s->id }}" data-price="{{ $s->price }}" data-formatted-price="{{ $s->formatted_price }}" data-duration="{{ $s->duration_minutes }}" style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0.75rem; border-bottom: 1px solid var(--border-light); gap: 1.5rem; border-radius: 4px; transition: all 0.25s ease; {{ $isThisSelected ? 'background: #FAF7F2; border-left: 4px solid #111111;' : '' }}">
                                            <div style="flex: 1;">
                                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                    <span style="font-family: var(--font-display); font-weight: 700; font-size: 1.05rem; color: var(--text);">
                                                        {{ $s->name }}
                                                    </span>
                                                    @if($s->badge)
                                                        <span style="background: #111111; color: #FFFFFF; font-size: 0.68rem; font-weight: 800; padding: 0.15rem 0.45rem; border-radius: 2px; text-transform: uppercase;">
                                                            {{ $s->badge }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div style="font-size: 0.84rem; color: var(--text-muted); margin-top: 0.2rem; line-height: 1.45;">
                                                    {{ $s->description }}
                                                </div>
                                                <div style="font-size: 0.8rem; color: var(--gold); font-family: var(--font-display); margin-top: 0.35rem;">
                                                    Sesi {{ $s->duration_minutes }} menit
                                                </div>
                                            </div>
                                            <div style="text-align: right; flex-shrink: 0;">
                                                <div style="font-family: var(--font-display); font-weight: 700; font-size: 1.15rem; color: #111111; margin-bottom: 0.5rem;">
                                                    {{ $s->formatted_price }}
                                                </div>
                                                <button type="button" class="btn btn-sm btn-select-service" onclick="selectService({{ $s->id }}, '{{ addslashes($s->name) }}', {{ $s->price }}, '{{ $s->formatted_price }}', {{ $s->duration_minutes }})" id="btn-srv-{{ $s->id }}" style="padding: 0.35rem 1.1rem; border-radius: var(--radius-xs); {{ $isThisSelected ? 'background: #111111; color: #FFFFFF; border: 1px solid #111111;' : 'background: var(--bg); border: 1px solid var(--border); color: var(--text);' }}">
                                                    {{ $isThisSelected ? '✓ Terpilih' : 'Pilih' }}
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- SCHEDULING CONSOLE (Date, Time Slot & Contact Details) -->
                <form action="{{ route('booking.store') }}" method="POST" id="bookingMasterForm">
                    @csrf

                    <!-- Hidden Inputs for Form Submission -->
                    <input type="hidden" name="service_id" id="formServiceId" value="{{ old('service_id', $activeService ? $activeService->id : $services->first()->id) }}">
                    <input type="hidden" name="barber_id" id="formBarberId" value="{{ old('barber_id', $selectedBarberId) }}">
                    <input type="hidden" name="booking_date" id="formBookingDate" value="{{ old('booking_date', $selectedDate) }}">
                    <input type="hidden" name="booking_time" id="formBookingTime" value="{{ old('booking_time', '11:00') }}">

                    <!-- Step 2: Choose Meja / Kursi (A1, A2, A3, A4) -->
                    <div class="card" style="padding: 2rem; background: var(--surface); border: 1px solid var(--border); margin-bottom: 2rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-light); padding-bottom: 0.75rem;">
                            <div>
                                <h3 style="font-size: 1.15rem; font-family: var(--font-display); font-weight: 700; color: var(--text); letter-spacing: 0.04em;">
                                    PILIH NOMOR MEJA &amp; KURSI
                                </h3>
                                <p style="font-size: 0.84rem; color: var(--text-muted); margin-top: 0.2rem;">
                                    Pilih nomor meja kursi potong rambut Anda. Kursi yang sudah terisi atau sedang dalam perbaikan ditandai secara otomatis.
                                </p>
                            </div>
                            <span style="font-family: var(--font-display); font-size: 0.8rem; color: var(--gold); font-weight: 700;">
                                LANGKAH 2
                            </span>
                        </div>

                        <!-- Real-time Chairs / Meja Grid -->
                        <div id="chairsContainer" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1rem;">
                            <!-- Option 1: Pilih Otomatis (Meja A1-A4) -->
                            <div class="chair-select-card {{ empty($selectedBarberId) ? 'active' : '' }}" id="chairCardAuto" onclick="selectChair('', 'A1-A4', this)" style="border: 1.5px solid {{ empty($selectedBarberId) ? '#111111' : 'var(--border)' }}; background: {{ empty($selectedBarberId) ? '#FAF7F2' : '#FFFFFF' }}; border-radius: var(--radius-xs); padding: 1.25rem 1rem; cursor: pointer; text-align: center; transition: all 0.2s;">
                                <div style="width: 44px; height: 44px; border-radius: 50%; background: #111111; color: #FFFFFF; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem;">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                                </div>
                                <div style="font-family: var(--font-display); font-weight: 700; color: var(--text); font-size: 1.05rem;">PILIH OTOMATIS</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.2rem;">Carikan meja tersedia</div>
                                <div style="margin-top: 0.6rem;">
                                    <span style="display: inline-block; font-size: 0.72rem; font-weight: 800; background: #ECFDF5; color: #047857; border: 1px solid #A7F3D0; padding: 0.15rem 0.5rem; border-radius: 3px; text-transform: uppercase;">
                                        TERSEDIA
                                    </span>
                                </div>
                            </div>

                            <!-- Specific Meja Cards (A1, A2, A3, A4) -->
                            @foreach($initialChairs as $chair)
                                @php
                                    $isCActive = (string)old('barber_id', $selectedBarberId) === (string)$chair['id'];
                                    $isUnavailable = !$chair['available'];
                                @endphp
                                <div class="chair-select-card {{ $isCActive ? 'active' : '' }} {{ $isUnavailable ? 'disabled' : '' }}" 
                                    id="chairCard-{{ $chair['id'] }}"
                                    data-id="{{ $chair['id'] }}"
                                    data-chair="{{ $chair['chair_code'] }}"
                                    data-status="{{ $chair['status'] }}"
                                    onclick="{{ $chair['available'] ? "selectChair({$chair['id']}, '{$chair['chair_code']}', this)" : "warnChairUnavailable('{$chair['badge_text']}')" }}"
                                    style="border: 1.5px solid {{ $chair['status'] === 'booked' ? '#FCA5A5' : ($chair['status'] === 'maintenance' ? '#FCD34D' : ($isCActive ? '#111111' : 'var(--border)')) }}; 
                                           background: {{ $chair['status'] === 'booked' ? '#FFF5F5' : ($chair['status'] === 'maintenance' ? '#FFFBEB' : ($isCActive ? '#FAF7F2' : '#FFFFFF')) }}; 
                                           opacity: {{ $isUnavailable ? '0.7' : '1' }};
                                           cursor: {{ $isUnavailable ? 'not-allowed' : 'pointer' }};
                                           border-radius: var(--radius-xs); padding: 1.25rem 1rem; text-align: center; transition: all 0.2s;">
                                    
                                    <!-- Barber Chair Station Icon -->
                                    <div class="chair-icon-box" style="width: 44px; height: 44px; border-radius: 50%; background: {{ $chair['status'] === 'booked' ? '#FEE2E2' : ($chair['status'] === 'maintenance' ? '#FEF3C7' : '#FAF7F2') }}; border: 1.5px solid {{ $chair['status'] === 'booked' ? '#FCA5A5' : ($chair['status'] === 'maintenance' ? '#FCD34D' : 'var(--border)') }}; color: {{ $chair['status'] === 'booked' ? '#DC2626' : ($chair['status'] === 'maintenance' ? '#D97706' : '#111111') }}; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.65rem;">
                                        @if($chair['status'] === 'maintenance')
                                            <!-- Maintenance Wrench Icon -->
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                                        @elseif($chair['status'] === 'booked')
                                            <!-- Lock Icon -->
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                        @else
                                            <!-- Vintage Barber Chair Icon -->
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 6h10a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z"/><path d="M12 14v4"/><path d="M8 21h8"/><path d="M9 3h6"/><path d="M4 11h2"/><path d="M18 11h2"/></svg>
                                        @endif
                                    </div>

                                    <div style="font-family: var(--font-display); font-weight: 700; color: var(--text); font-size: 1.05rem;">
                                        {{ $chair['title'] }}
                                    </div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.15rem;">
                                        {{ $chair['subtitle'] }}
                                    </div>

                                    <!-- Status / Warning Badge -->
                                    <div style="margin-top: 0.6rem;">
                                        @if($chair['status'] === 'booked')
                                            <span class="chair-badge" style="display: inline-block; font-size: 0.72rem; font-weight: 800; background: #FEE2E2; color: #DC2626; border: 1px solid #FCA5A5; padding: 0.15rem 0.5rem; border-radius: 3px; text-transform: uppercase;">
                                                KURSI SUDAH DIBOOKING
                                            </span>
                                        @elseif($chair['status'] === 'maintenance')
                                            <span class="chair-badge" style="display: inline-block; font-size: 0.72rem; font-weight: 800; background: #FEF3C7; color: #D97706; border: 1px solid #FCD34D; padding: 0.15rem 0.5rem; border-radius: 3px; text-transform: uppercase;">
                                                MEJA SEDANG DIPERBAIKI
                                            </span>
                                        @else
                                            <span class="chair-badge" style="display: inline-block; font-size: 0.72rem; font-weight: 800; background: #ECFDF5; color: #047857; border: 1px solid #A7F3D0; padding: 0.15rem 0.5rem; border-radius: 3px; text-transform: uppercase;">
                                                TERSEDIA
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Real-time Warning Banner -->
                        <div id="chairWarningAlert" style="display: none; margin-top: 1.25rem; padding: 0.85rem 1.15rem; border-radius: 4px; background: #FFF5F5; border: 1px solid #FCA5A5; color: #DC2626; font-size: 0.85rem; font-weight: 600; align-items: center; gap: 0.5rem;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span id="chairWarningText"></span>
                        </div>
                    </div>

                    <!-- Step 3: Choose Date & Available Slot -->
                    <div class="card" style="padding: 2rem; background: var(--surface); border: 1px solid var(--border); margin-bottom: 2rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-light); padding-bottom: 0.75rem;">
                            <div>
                                <h3 style="font-size: 1.15rem; font-family: var(--font-display); font-weight: 700; color: var(--text); letter-spacing: 0.04em;">
                                    PILIH TANGGAL &amp; JAM
                                </h3>
                                <p style="font-size: 0.84rem; color: var(--text-muted); margin-top: 0.2rem;">
                                    Jadwal waktu nyata. Buka setiap hari 11:00 – 21:00 (Jum: 13:00 / Weekend: 22:00) WIB.
                                </p>
                            </div>
                            <span style="font-family: var(--font-display); font-size: 0.8rem; color: var(--gold); font-weight: 700;">
                                LANGKAH 3
                            </span>
                        </div>

                        <!-- Horizontal Date Carousel -->
                        <div style="display: flex; gap: 0.65rem; overflow-x: auto; padding-bottom: 0.75rem; margin-bottom: 1.75rem;" class="date-scroll-container">
                            @foreach($dates as $d)
                                <button type="button" class="date-card-btn {{ $d['date'] === old('booking_date', $selectedDate) ? 'active' : '' }}" onclick="selectDate('{{ $d['date'] }}', this)" style="flex: 0 0 76px; padding: 0.85rem 0.5rem; border-radius: var(--radius-xs); border: 1px solid {{ $d['date'] === old('booking_date', $selectedDate) ? 'var(--leather)' : 'var(--border)' }}; background: {{ $d['date'] === old('booking_date', $selectedDate) ? 'var(--leather-soft)' : 'var(--surface-alt)' }}; cursor: pointer; text-align: center; transition: all 0.2s;">
                                    <div style="font-size: 0.74rem; font-family: var(--font-display); font-weight: 700; color: var(--text-muted); text-transform: uppercase;">{{ $d['day_name'] }}</div>
                                    <div style="font-size: 1.35rem; font-weight: 800; font-family: var(--font-display); color: {{ $d['date'] === old('booking_date', $selectedDate) ? 'var(--leather)' : 'var(--text)' }}; line-height: 1.2; margin: 0.15rem 0;">{{ $d['day_num'] }}</div>
                                    <div style="font-size: 0.72rem; color: var(--text-muted);">{{ $d['month_name'] }}</div>
                                </button>
                            @endforeach
                        </div>

                        <!-- Available Time Slots Grid -->
                        <div>
                            <div style="font-size: 0.82rem; font-family: var(--font-display); font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.75rem;">
                                Pilihan Jam Kunjungan
                            </div>
                            <div id="slotsContainer" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(88px, 1fr)); gap: 0.65rem;">
                                @foreach($initialSlots as $slot)
                                    <button type="button" class="time-slot-btn {{ $slot['time'] === old('booking_time', '11:00') ? 'active' : '' }}" 
                                        {{ $slot['available'] ? '' : 'disabled' }}
                                        onclick="selectTime('{{ $slot['time'] }}', this)"
                                        style="padding: 0.65rem 0.4rem; border-radius: var(--radius-xs); font-family: var(--font-sans); font-size: 0.9rem; font-weight: 700; border: 1px solid {{ $slot['time'] === old('booking_time', '11:00') ? 'var(--leather)' : 'var(--border)' }}; background: {{ $slot['time'] === old('booking_time', '11:00') ? 'var(--leather)' : ($slot['available'] ? 'var(--surface-alt)' : '#F2ECE4') }}; color: {{ $slot['time'] === old('booking_time', '11:00') ? '#FFFFFF' : ($slot['available'] ? 'var(--text)' : '#A3968A') }}; cursor: {{ $slot['available'] ? 'pointer' : 'not-allowed' }}; transition: all 0.2s;">
                                        {{ $slot['time'] }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Optional Add-on & Drink -->
                    <div class="card" style="padding: 2rem; background: var(--surface); border: 1px solid var(--border); margin-bottom: 2rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-light); padding-bottom: 0.75rem;">
                            <div>
                                <h3 style="font-size: 1.15rem; font-family: var(--font-display); font-weight: 700; color: var(--text); letter-spacing: 0.04em;">
                                    MINUMAN &amp; PERAWATAN TAMBAHAN (OPSIONAL)
                                </h3>
                                <p style="font-size: 0.84rem; color: var(--text-muted); margin-top: 0.2rem;">
                                    Lengkapi waktu santai Anda dengan racikan minuman dingin atau perawatan ekstra.
                                </p>
                            </div>
                            <span style="font-family: var(--font-display); font-size: 0.8rem; color: var(--gold); font-weight: 700;">
                                LANGKAH 4
                            </span>
                        </div>

                        @php
                            $groupedAddons = $addons->groupBy('category');
                        @endphp

                        @foreach($groupedAddons as $categoryName => $catAddons)
                            <div style="margin-bottom: 1.75rem;">
                                <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.85rem;">
                                    <h4 style="font-family: var(--font-display); font-size: 0.95rem; font-weight: 700; color: var(--text); letter-spacing: 0.05em; text-transform: uppercase;">
                                        DUTCHMAN {{ $categoryName }} BREW
                                    </h4>
                                    <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500;">
                                        ({{ $catAddons->count() }} pilihan)
                                    </span>
                                </div>

                                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1rem;">
                                    @foreach($catAddons as $addon)
                                        <label class="addon-card" style="border: 1px solid var(--border); border-radius: var(--radius-xs); padding: 1rem 1rem; background: #FFFFFF; display: flex; align-items: flex-start; gap: 0.85rem; cursor: pointer; transition: all 0.2s; position: relative;">
                                            <input type="checkbox" name="addon_ids[]" value="{{ $addon->id }}" data-price="{{ $addon->price }}" data-name="{{ $addon->name }}" onchange="toggleAddon(this)" style="margin-top: 3px; accent-color: #111111; width: 18px; height: 18px; cursor: pointer;">
                                            <div style="flex: 1;">
                                                <div style="display: flex; justify-content: space-between; align-items: baseline; gap: 0.5rem;">
                                                    <div style="font-family: var(--font-display); font-weight: 700; font-size: 0.98rem; color: #111111;">
                                                        {{ $addon->name }}
                                                    </div>
                                                    <div style="font-family: var(--font-display); font-weight: 700; font-size: 0.92rem; color: #111111; white-space: nowrap;">
                                                        {{ $addon->formatted_price }}
                                                    </div>
                                                </div>
                                                <p style="font-size: 0.78rem; color: #777777; margin: 0.25rem 0 0; line-height: 1.35;">
                                                    {{ $addon->description }}
                                                </p>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Step 5: Customer Details & Booking Confirmation -->
                    <div class="card" style="padding: 2rem; background: var(--surface); border: 1px solid var(--border);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-light); padding-bottom: 0.75rem;">
                            <div>
                                <h3 style="font-size: 1.15rem; font-family: var(--font-display); font-weight: 700; color: var(--text); letter-spacing: 0.04em;">
                                    INFORMASI PELANGGAN
                                </h3>
                                <p style="font-size: 0.84rem; color: var(--text-muted); margin-top: 0.2rem;">
                                    Kami akan mengirimkan rincian konfirmasi jadwal booking ke nomor WhatsApp Anda.
                                </p>
                            </div>
                            <span style="font-family: var(--font-display); font-size: 0.8rem; color: var(--gold); font-weight: 700;">
                                LANGKAH 5
                            </span>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                            <div>
                                <label style="display: block; font-size: 0.78rem; font-family: var(--font-display); font-weight: 700; color: var(--text); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.06em;">
                                    Nama Lengkap *
                                </label>
                                <input type="text" name="customer_name" required value="{{ old('customer_name', $user ? $user->name : '') }}" placeholder="cth. Julian Pratama" style="width: 100%; height: 44px; background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius-xs); padding: 0 0.85rem; font-family: var(--font-sans); font-size: 0.92rem; color: var(--text); outline: none;">
                            </div>

                            <div>
                                <label style="display: block; font-size: 0.78rem; font-family: var(--font-display); font-weight: 700; color: var(--text); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.06em;">
                                    Nomor WhatsApp *
                                </label>
                                <input type="tel" name="customer_phone" required value="{{ old('customer_phone', $user ? $user->phone : '') }}" placeholder="cth. 081234567890" style="width: 100%; height: 44px; background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius-xs); padding: 0 0.85rem; font-family: var(--font-sans); font-size: 0.92rem; color: var(--text); outline: none;">
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                            <div>
                                <label style="display: block; font-size: 0.78rem; font-family: var(--font-display); font-weight: 700; color: var(--text); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.06em;">
                                    Alamat Email (Opsional)
                                </label>
                                <input type="email" name="customer_email" value="{{ old('customer_email', $user ? $user->email : '') }}" placeholder="nama@email.com" style="width: 100%; height: 44px; background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius-xs); padding: 0 0.85rem; font-family: var(--font-sans); font-size: 0.92rem; color: var(--text); outline: none;">
                            </div>

                            <div>
                                <label style="display: block; font-size: 0.78rem; font-family: var(--font-display); font-weight: 700; color: var(--text); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.06em;">
                                    Permintaan Model Potongan / Catatan
                                </label>
                                <input type="text" name="notes" value="{{ old('notes') }}" placeholder="cth. Mid fade, bagian atas dirapikan sedikit" style="width: 100%; height: 44px; background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius-xs); padding: 0 0.85rem; font-family: var(--font-sans); font-size: 0.92rem; color: var(--text); outline: none;">
                            </div>
                        </div>

                        <!-- Final Submission Button -->
                        <div style="padding-top: 1.25rem; border-top: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                            <div style="font-size: 0.82rem; color: var(--text-muted);">
                                ⏱️ Jadwal booking Anda akan langsung ditinjau &amp; disetujui oleh admin Dutchman.
                            </div>
                            <button type="submit" class="btn" style="padding: 0.9rem 2.5rem; font-size: 0.92rem; background: #111111; color: #FFFFFF; font-weight: 800; border-radius: 4px; box-shadow: 0 4px 16px rgba(0,0,0,0.25);">
                                <span>KIRIM BOOKING SEKARANG</span>
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- RIGHT COLUMN: Sticky Sidebar (Dynamic Booking Summary) -->
            <div style="position: sticky; top: 96px; display: flex; flex-direction: column; gap: 1.5rem;">

                <!-- Dynamic Booking Summary Box -->
                <div class="card" id="summaryCard" style="padding: 1.5rem; background: var(--surface-alt); border: 1px solid var(--border);">
                    <div style="font-family: var(--font-display); font-weight: 700; font-size: 0.95rem; color: var(--text); text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 1rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">
                        RINGKASAN RESERVASI
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.88rem;">
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--text-muted);">Layanan:</span>
                            <strong id="sumServiceName" style="color: var(--text); font-family: var(--font-display);">
                                {{ $activeService ? $activeService->name : $services->first()->name }}
                            </strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--text-muted);">Durasi:</span>
                            <span id="sumServiceDuration" style="color: var(--text);">
                                {{ $activeService ? $activeService->duration_minutes : $services->first()->duration_minutes }} menit
                            </span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--text-muted);">Nomor Meja:</span>
                            <strong id="sumBarberName" style="color: var(--text); font-family: var(--font-display);">
                                @php
                                    $curBarber = $barbers->firstWhere('id', (int)old('barber_id', $selectedBarberId));
                                @endphp
                                {{ $curBarber ? 'Meja ' . ($curBarber->chair_code ?: 'A1') : 'Pilih Otomatis (Meja A1-A4)' }}
                            </strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--text-muted);">Tanggal:</span>
                            <strong id="sumBookingDate" style="color: var(--text);">
                                {{ Carbon\Carbon::parse(old('booking_date', $selectedDate))->translatedFormat('d M Y') }}
                            </strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--text-muted);">Jam Kunjungan:</span>
                            <strong id="sumBookingTime" style="color: var(--leather); font-family: var(--font-display);">
                                {{ old('booking_time', '11:00') }} WIB
                            </strong>
                        </div>

                        <!-- Selected Addons container in summary -->
                        <div id="sumAddonsWrapper" style="display: none; border-top: 1px dashed var(--border); padding-top: 0.6rem; flex-direction: column; gap: 0.35rem;">
                            <div style="font-size: 0.76rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700; letter-spacing: 0.05em;">Tambahan:</div>
                            <div id="sumAddonsList" style="display: flex; flex-direction: column; gap: 0.3rem;"></div>
                        </div>

                        <div style="border-top: 1px solid var(--border); padding-top: 0.75rem; display: flex; justify-content: space-between; align-items: baseline;">
                            <span style="font-weight: 700; color: var(--text);">Total Pembayaran:</span>
                            <span id="sumServicePrice" style="font-family: var(--font-display); font-size: 1.25rem; font-weight: 700; color: var(--leather);">
                                {{ $activeService ? $activeService->formatted_price : $services->first()->formatted_price }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- Client Side Scripts for Accordion, Search, Auto-Scroll & Slot Loading -->
@push('scripts')
<style>
    @keyframes serviceHighlightPulse {
        0% {
            box-shadow: 0 0 0 0 rgba(17, 17, 17, 0.45);
            background-color: #F6EDE1;
            transform: scale(1);
        }
        35% {
            box-shadow: 0 0 0 8px rgba(17, 17, 17, 0.18);
            background-color: #F0DFC8;
            transform: scale(1.012);
        }
        70% {
            box-shadow: 0 0 0 12px rgba(17, 17, 17, 0);
            background-color: #FAF7F2;
            transform: scale(1);
        }
        100% {
            box-shadow: none;
            background-color: #FAF7F2;
            transform: scale(1);
        }
    }

    .service-highlight-pulse {
        animation: serviceHighlightPulse 2s cubic-bezier(0.25, 1, 0.5, 1);
        position: relative;
        z-index: 5;
    }
</style>

<script>
    // Accordion Toggle
    function toggleAccordion(contentId) {
        const content = document.getElementById(contentId);
        const icon = document.getElementById('icon-' + contentId);
        if (content.style.display === 'none' || content.style.display === '') {
            content.style.display = 'block';
            if (icon) icon.textContent = '▼';
        } else {
            content.style.display = 'none';
            if (icon) icon.textContent = '▶';
        }
    }

    let currentServicePrice = {{ $activeService ? $activeService->price : ($services->first() ? $services->first()->price : 0) }};
    let selectedAddons = [];

    function updateTotalPrice() {
        const addonsTotal = selectedAddons.reduce((acc, curr) => acc + curr.price, 0);
        const grandTotal = currentServicePrice + addonsTotal;
        document.getElementById('sumServicePrice').textContent = 'Rp ' + Number(grandTotal).toLocaleString('id-ID');
    }

    // Select Meja / Chair
    function selectChair(id, chairCode, el) {
        if (el && el.classList.contains('disabled')) {
            const status = el.dataset.status;
            const msg = status === 'maintenance' 
                ? 'Meja ' + chairCode + ' sedang dalam perbaikan teknis.' 
                : 'Kursi meja ' + chairCode + ' sudah dibooking pada jam ini.';
            warnChairUnavailable(msg);
            return;
        }

        document.getElementById('formBarberId').value = id;
        document.querySelectorAll('.chair-select-card').forEach(card => {
            if (!card.classList.contains('disabled')) {
                card.style.borderColor = 'var(--border)';
                card.style.background = '#FFFFFF';
            }
            card.classList.remove('active');
        });

        if (el && !el.classList.contains('disabled')) {
            el.style.borderColor = '#111111';
            el.style.background = '#FAF7F2';
            el.classList.add('active');
        }

        const mejaLabel = (!id || chairCode === 'A1-A4') ? 'Pilih Otomatis (Meja A1-A4)' : `Meja ${chairCode}`;
        document.getElementById('sumBarberName').textContent = mejaLabel;

        const alertBox = document.getElementById('chairWarningAlert');
        if (alertBox) alertBox.style.display = 'none';
    }

    // Alias for backward compatibility
    function selectBarber(id, name, chairCode, el) {
        selectChair(id, chairCode, el);
    }

    function warnChairUnavailable(msg) {
        const alertBox = document.getElementById('chairWarningAlert');
        const textSpan = document.getElementById('chairWarningText');
        if (alertBox && textSpan) {
            textSpan.textContent = 'Peringatan: ' + msg + ' Silakan pilih nomor meja lain atau ganti jam booking.';
            alertBox.style.display = 'flex';
            alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } else {
            alert('Peringatan: ' + msg);
        }
    }

    // Toggle Add-on & Beverage
    function toggleAddon(checkbox) {
        const id = checkbox.value;
        const name = checkbox.dataset.name;
        const price = parseInt(checkbox.dataset.price) || 0;
        const parentCard = checkbox.closest('.addon-card');

        if (checkbox.checked) {
            if (!selectedAddons.find(item => item.id === id)) {
                selectedAddons.push({ id, name, price });
            }
            if (parentCard) {
                parentCard.style.borderColor = '#111111';
                parentCard.style.background = '#FAF7F2';
            }
        } else {
            selectedAddons = selectedAddons.filter(item => item.id !== id);
            if (parentCard) {
                parentCard.style.borderColor = 'var(--border)';
                parentCard.style.background = '#FFFFFF';
            }
        }

        // Render to summary sidebar
        const wrapper = document.getElementById('sumAddonsWrapper');
        const list = document.getElementById('sumAddonsList');
        if (selectedAddons.length > 0) {
            wrapper.style.display = 'flex';
            list.innerHTML = selectedAddons.map(item => `
                <div style="display: flex; justify-content: space-between; color: var(--text);">
                    <span>• ${item.name}</span>
                    <span>Rp ${Number(item.price).toLocaleString('id-ID')}</span>
                </div>
            `).join('');
        } else {
            wrapper.style.display = 'none';
            list.innerHTML = '';
        }

        updateTotalPrice();
    }

    // Select Service
    function selectService(id, name, price, formattedPrice, duration) {
        document.getElementById('formServiceId').value = id;
        document.getElementById('sumServiceName').textContent = name;
        document.getElementById('sumServiceDuration').textContent = duration + ' menit';
        currentServicePrice = parseInt(price) || 0;
        updateTotalPrice();

        // Update all rows visual state
        document.querySelectorAll('.service-row').forEach(row => {
            row.classList.remove('service-row-selected');
            row.style.background = '';
            row.style.borderLeft = '';
        });

        // Update button visual states
        document.querySelectorAll('.btn-select-service').forEach(btn => {
            btn.style.background = 'var(--bg)';
            btn.style.color = 'var(--text)';
            btn.style.borderColor = 'var(--border)';
            btn.textContent = 'Pilih';
        });

        const activeRow = document.getElementById('service-row-' + id);
        if (activeRow) {
            activeRow.classList.add('service-row-selected');
            activeRow.style.background = '#FAF7F2';
            activeRow.style.borderLeft = '4px solid #111111';
        }

        const activeBtn = document.getElementById('btn-srv-' + id);
        if (activeBtn) {
            activeBtn.style.background = '#111111';
            activeBtn.style.color = '#FFFFFF';
            activeBtn.style.borderColor = '#111111';
            activeBtn.textContent = '✓ Terpilih';
        }
    }

    // Auto-scroll & Auto-select when service_id is in URL
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const serviceId = urlParams.get('service_id');

        if (serviceId) {
            const targetRow = document.getElementById('service-row-' + serviceId);
            if (targetRow) {
                // Ensure parent accordion is expanded
                const parentAccordion = targetRow.closest('.accordion-content');
                if (parentAccordion) {
                    parentAccordion.style.display = 'block';
                    const catId = parentAccordion.id;
                    const icon = document.getElementById('icon-' + catId);
                    if (icon) icon.textContent = '▼';
                }

                // Smooth animated auto-scroll to the selected service
                setTimeout(function() {
                    targetRow.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });

                    // Add highlight pulse animation
                    targetRow.classList.add('service-highlight-pulse');
                    setTimeout(() => {
                        targetRow.classList.remove('service-highlight-pulse');
                    }, 2500);
                }, 350);
            }
        }
    });

    // Select Date
    function selectDate(dateStr, btnElement) {
        document.getElementById('formBookingDate').value = dateStr;
        document.querySelectorAll('.date-card-btn').forEach(btn => {
            btn.style.borderColor = 'var(--border)';
            btn.style.background = 'var(--surface-alt)';
            const num = btn.querySelector('div:nth-child(2)');
            if (num) num.style.color = 'var(--text)';
        });

        btnElement.style.borderColor = 'var(--leather)';
        btnElement.style.background = 'var(--leather-soft)';
        const num = btnElement.querySelector('div:nth-child(2)');
        if (num) num.style.color = 'var(--leather)';

        // Format date in summary
        document.getElementById('sumBookingDate').textContent = dateStr;

        // Fetch Slots via API
        fetchSlots(dateStr);

        // Fetch real-time chairs availability
        fetchChairsStatus(dateStr, document.getElementById('formBookingTime').value);
    }

    // Select Time
    function selectTime(timeStr, btnElement) {
        document.getElementById('formBookingTime').value = timeStr;
        document.getElementById('sumBookingTime').textContent = timeStr;

        document.querySelectorAll('.time-slot-btn').forEach(btn => {
            if (!btn.disabled) {
                btn.style.background = 'var(--surface-alt)';
                btn.style.color = 'var(--text)';
                btn.style.borderColor = 'var(--border)';
            }
        });

        btnElement.style.background = 'var(--leather)';
        btnElement.style.color = '#FFFFFF';
        btnElement.style.borderColor = 'var(--leather)';

        // Fetch real-time chairs availability for this time slot
        fetchChairsStatus(document.getElementById('formBookingDate').value, timeStr);
    }

    // Fetch Chairs Status via API
    function fetchChairsStatus(date, time) {
        if (!date || !time) return;

        fetch(`/api/chairs?date=${encodeURIComponent(date)}&time=${encodeURIComponent(time)}`)
            .then(res => res.json())
            .then(data => {
                if (!data.success || !data.chairs) return;

                const selectedBarberInput = document.getElementById('formBarberId');
                const currentSelectedId = selectedBarberInput.value;

                data.chairs.forEach(chair => {
                    const card = document.getElementById('chairCard-' + chair.id);
                    if (!card) return;

                    const iconBox = card.querySelector('.chair-icon-box');
                    const badge = card.querySelector('.chair-badge');

                    card.dataset.status = chair.status;

                    if (chair.status === 'booked') {
                        card.classList.add('disabled');
                        card.classList.remove('active');
                        card.style.borderColor = '#FCA5A5';
                        card.style.background = '#FFF5F5';
                        card.style.opacity = '0.7';
                        card.style.cursor = 'not-allowed';
                        card.onclick = () => warnChairUnavailable('Kursi meja ' + chair.chair_code + ' sudah dibooking pada jam ' + time + ' WIB.');

                        if (iconBox) {
                            iconBox.style.background = '#FEE2E2';
                            iconBox.style.borderColor = '#FCA5A5';
                            iconBox.style.color = '#DC2626';
                            iconBox.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>';
                        }

                        if (badge) {
                            badge.textContent = 'KURSI SUDAH DIBOOKING';
                            badge.style.background = '#FEE2E2';
                            badge.style.color = '#DC2626';
                            badge.style.borderColor = '#FCA5A5';
                        }

                        if (currentSelectedId == chair.id) {
                            selectChair('', 'A1-A4', document.getElementById('chairCardAuto'));
                            warnChairUnavailable(`Kursi ${chair.title} sudah dibooking pada jam ${time} WIB. Meja dialihkan ke Otomatis.`);
                        }
                    } else if (chair.status === 'maintenance') {
                        card.classList.add('disabled');
                        card.classList.remove('active');
                        card.style.borderColor = '#FCD34D';
                        card.style.background = '#FFFBEB';
                        card.style.opacity = '0.7';
                        card.style.cursor = 'not-allowed';
                        card.onclick = () => warnChairUnavailable('Meja ' + chair.chair_code + ' sedang dalam perbaikan teknis.');

                        if (iconBox) {
                            iconBox.style.background = '#FEF3C7';
                            iconBox.style.borderColor = '#FCD34D';
                            iconBox.style.color = '#D97706';
                            iconBox.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>';
                        }

                        if (badge) {
                            badge.textContent = 'MEJA SEDANG DIPERBAIKI';
                            badge.style.background = '#FEF3C7';
                            badge.style.color = '#D97706';
                            badge.style.borderColor = '#FCD34D';
                        }

                        if (currentSelectedId == chair.id) {
                            selectChair('', 'A1-A4', document.getElementById('chairCardAuto'));
                            warnChairUnavailable(`Kursi ${chair.title} sedang dalam perbaikan. Meja dialihkan ke Otomatis.`);
                        }
                    } else {
                        // Available
                        card.classList.remove('disabled');
                        card.style.opacity = '1';
                        card.style.cursor = 'pointer';
                        card.onclick = () => selectChair(chair.id, chair.chair_code, card);

                        const isCurrent = currentSelectedId == chair.id;
                        card.style.borderColor = isCurrent ? '#111111' : 'var(--border)';
                        card.style.background = isCurrent ? '#FAF7F2' : '#FFFFFF';

                        if (iconBox) {
                            iconBox.style.background = '#FAF7F2';
                            iconBox.style.borderColor = 'var(--border)';
                            iconBox.style.color = '#111111';
                            iconBox.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 6h10a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z"/><path d="M12 14v4"/><path d="M8 21h8"/><path d="M9 3h6"/><path d="M4 11h2"/><path d="M18 11h2"/></svg>';
                        }

                        if (badge) {
                            badge.textContent = 'TERSEDIA';
                            badge.style.background = '#ECFDF5';
                            badge.style.color = '#047857';
                            badge.style.borderColor = '#A7F3D0';
                        }
                    }
                });
            })
            .catch(err => console.error('Failed to fetch chairs status', err));
    }

    // Slot & Chair In-Memory Caches for 0ms Instant Response
    const slotsCache = {};
    const initialDateKey = document.getElementById('formBookingDate').value;
    slotsCache[initialDateKey] = @json($initialSlots);

    function renderSlots(slots) {
        const container = document.getElementById('slotsContainer');
        const selectedTime = document.getElementById('formBookingTime').value;
        container.innerHTML = '';
        slots.forEach(slot => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'time-slot-btn' + (slot.time === selectedTime ? ' active' : '');
            btn.textContent = slot.time;
            btn.disabled = !slot.available;
            const isCurrent = slot.time === selectedTime;
            btn.style.cssText = `padding: 0.65rem 0.4rem; border-radius: var(--radius-xs); font-family: var(--font-sans); font-size: 0.9rem; font-weight: 700; border: 1px solid ${isCurrent ? 'var(--leather)' : 'var(--border)'}; background: ${isCurrent ? 'var(--leather)' : (slot.available ? 'var(--surface-alt)' : '#F2ECE4')}; color: ${isCurrent ? '#FFFFFF' : (slot.available ? 'var(--text)' : '#A3968A')}; cursor: ${slot.available ? 'pointer' : 'not-allowed'}; transition: all 0.15s;`;

            if (slot.available) {
                btn.onclick = () => selectTime(slot.time, btn);
            }
            container.appendChild(btn);
        });
    }

    // Fetch Slots via API with Instant Cache
    function fetchSlots(date) {
        if (slotsCache[date]) {
            renderSlots(slotsCache[date]);
            return;
        }

        const container = document.getElementById('slotsContainer');
        container.style.opacity = '0.4';

        fetch(`/api/slots?date=${encodeURIComponent(date)}`)
            .then(res => res.json())
            .then(data => {
                container.style.opacity = '1';
                if (data.success && data.slots) {
                    slotsCache[date] = data.slots;
                    renderSlots(data.slots);
                }
            })
            .catch(() => {
                container.style.opacity = '1';
            });
    }

    // Background prefetch for instant clicking
    setTimeout(() => {
        document.querySelectorAll('.date-card-btn').forEach(btn => {
            const onclickAttr = btn.getAttribute('onclick') || '';
            const m = onclickAttr.match(/'(\d{4}-\d{2}-\d{2})'/);
            if (m && m[1]) {
                const d = m[1];
                if (!slotsCache[d]) {
                    fetch(`/api/slots?date=${encodeURIComponent(d)}`)
                        .then(r => r.json())
                        .then(res => {
                            if (res.success && res.slots) slotsCache[d] = res.slots;
                        })
                        .catch(() => {});
                }
            }
        });
    }, 250);

    // Service Search Filter
    document.getElementById('serviceSearchInput').addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        document.querySelectorAll('.service-row').forEach(row => {
            const name = row.getAttribute('data-name');
            if (name.includes(query)) {
                row.style.display = 'flex';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endpush
@endsection
