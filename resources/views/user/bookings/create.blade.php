@extends('layouts.app')

@section('title', 'Booking Grooming — PAWMART')

@section('content')
<div class="container container-narrow" style="padding: 2.5rem 1.25rem 4rem;">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2" style="font-size: 0.85rem; color: var(--muted); margin-bottom: 0.5rem;">
        <a href="{{ route('home') }}">Home</a>
        <span>/</span>
        <a href="{{ route('user.bookings.index') }}">Bookings</a>
        <span>/</span>
        <span style="color: var(--text); font-weight: 600;">Booking Grooming</span>
    </div>

    <div style="margin-bottom: 2rem;">
        <span class="badge badge-accent" style="margin-bottom: 0.5rem;">RESERVASI SALON GROOMING</span>
        <h1>Booking Layanan Grooming</h1>
        <p style="color: var(--muted); margin-top: 0.25rem;">
            Dapatkan kepastian jadwal perawatan salon untuk anabul Anda tanpa perlu mengantre lama. Pilih layanan, cabang salon, dan jam slot yang tersedia.
        </p>
    </div>

    <div class="card" style="padding: 2.25rem;">
        <form action="{{ route('user.bookings.store') }}" method="POST" id="bookingForm">
            @csrf

            <!-- 1. Select Grooming Service -->
            <div class="form-group" style="margin-bottom: 2rem;">
                <label class="form-label" style="font-size: 1rem; margin-bottom: 0.75rem;">
                    1. Pilih Paket Layanan Grooming
                </label>
                <div class="grid grid-cols-2" style="gap: 1rem;">
                    @foreach($services as $srv)
                        <label class="card js-service-card {{ $selectedServiceId == $srv->id ? 'active' : '' }}" 
                               style="padding: 1.25rem; cursor: pointer; border: 2px solid {{ $selectedServiceId == $srv->id ? 'var(--accent)' : 'var(--border)' }}; transition: all 0.2s ease;">
                            <input type="radio" name="grooming_service_id" value="{{ $srv->id }}" {{ $selectedServiceId == $srv->id ? 'checked' : '' }} style="display: none;" required>
                            <div class="flex items-center justify-between" style="margin-bottom: 0.25rem;">
                                <strong style="font-size: 1rem; color: var(--primary);">{{ $srv->name }}</strong>
                                <span style="font-weight: 800; color: var(--accent);">Rp {{ number_format($srv->price, 0, ',', '.') }}</span>
                            </div>
                            <div style="font-size: 0.8rem; color: var(--muted); margin-bottom: 0.5rem;">
                                Estimasi: ± {{ $srv->duration_minutes }} menit &bull; {{ ucfirst($srv->pet_type) }}
                            </div>
                            <p style="font-size: 0.8rem; color: var(--text); line-height: 1.4; margin: 0;">
                                {{ $srv->description }}
                            </p>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- 2. Pet Details -->
            <div class="form-group" style="margin-bottom: 2rem;">
                <label class="form-label" style="font-size: 1rem; margin-bottom: 0.75rem;">
                    2. Data Hewan Peliharaan (Anabul)
                </label>
                <div class="grid grid-cols-3" style="gap: 1rem;">
                    <div>
                        <label class="form-label">Nama Peliharaan *</label>
                        <input type="text" name="pet_name" value="{{ old('pet_name', 'Mochi') }}" placeholder="Contoh: Mochi" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Jenis Hewan *</label>
                        <select name="pet_type" class="form-select" required>
                            <option value="Cat" {{ old('pet_type', $selectedPetType) === 'Cat' ? 'selected' : '' }}>Kucing (Cat)</option>
                            <option value="Dog" {{ old('pet_type', $selectedPetType) === 'Dog' ? 'selected' : '' }}>Anjing (Dog)</option>
                            <option value="Other" {{ old('pet_type') === 'Other' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Ras / Breed (Opsional)</label>
                        <input type="text" name="pet_breed" value="{{ old('pet_breed', 'Persian Mix') }}" placeholder="Contoh: Persian, Golden" class="form-input">
                    </div>
                </div>
            </div>

            <!-- 3. Select Store Branch -->
            <div class="form-group" style="margin-bottom: 2rem;">
                <label class="form-label" style="font-size: 1rem; margin-bottom: 0.75rem;">
                    3. Pilih Cabang Salon PAWMART
                </label>
                <div class="grid grid-cols-3" style="gap: 1rem;">
                    @foreach($branches as $branch)
                        <label class="card js-branch-card {{ $selectedBranchId == $branch->id ? 'active' : '' }}" 
                               style="padding: 1.25rem; cursor: pointer; border: 2px solid {{ $selectedBranchId == $branch->id ? 'var(--primary)' : 'var(--border)' }}; transition: all 0.2s ease;">
                            <input type="radio" name="branch_id" value="{{ $branch->id }}" {{ $selectedBranchId == $branch->id ? 'checked' : '' }} style="display: none;" required>
                            <strong style="display: block; font-size: 0.95rem; margin-bottom: 0.25rem;">{{ $branch->name }}</strong>
                            <div style="font-size: 0.8rem; color: var(--muted); line-height: 1.4;">{{ $branch->address }}</div>
                            <div style="font-size: 0.75rem; color: var(--accent); font-weight: 700; margin-top: 0.5rem;">
                                {{ substr($branch->opening_time, 0, 5) }} - {{ substr($branch->closing_time, 0, 5) }} WIB
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- 4. Select Date & Dynamic Time Slot -->
            <div class="grid grid-cols-2" style="gap: 1.5rem; margin-bottom: 2rem;">
                <div>
                    <label class="form-label" style="font-size: 1rem; margin-bottom: 0.5rem;">
                        4. Tanggal Perawatan
                    </label>
                    <input type="date" name="booking_date" id="bookingDateInput" 
                           value="{{ old('booking_date', date('Y-m-d', strtotime('+1 day'))) }}" 
                           min="{{ date('Y-m-d') }}" 
                           required 
                           class="form-input">
                </div>

                <div>
                    <div class="flex items-center justify-between" style="margin-bottom: 0.5rem;">
                        <label class="form-label" style="font-size: 1rem;">
                            5. Jam Slot Tersedia
                        </label>
                        <span id="slotLoadingText" style="font-size: 0.8rem; color: var(--muted); display: none;">Mengecek slot...</span>
                    </div>

                    <div class="flex flex-wrap gap-2" id="timeSlotsContainer">
                        <!-- Loaded dynamically via AJAX -->
                    </div>
                    <input type="hidden" name="booking_time" id="selectedTimeSlotInput" value="{{ old('booking_time') }}" required>
                    <div id="timeValidationHint" style="display: none; color: var(--danger); font-size: 0.8rem; margin-top: 0.4rem; font-weight: 600;">
                        * Silakan pilih salah satu jam slot yang tersedia.
                    </div>
                </div>
            </div>

            <!-- 5. Customer Contact & Special Notes -->
            <div class="form-group" style="margin-bottom: 2rem;">
                <label class="form-label" style="font-size: 1rem; margin-bottom: 0.75rem;">
                    6. Kontak Pemilik & Catatan Khusus
                </label>
                <div class="grid grid-cols-2" style="gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label class="form-label">Nama Pemilik *</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name', auth()->user()->name) }}" required class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Nomor WhatsApp / HP *</label>
                        <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone ?? '081234567890') }}" required class="form-input">
                    </div>
                </div>

                <div>
                    <label class="form-label">Catatan Tambahan untuk Groomer (Opsional)</label>
                    <textarea name="notes" rows="3" class="form-textarea" placeholder="Contoh: Mochi mudah takut saat grooming, tolong tangani perlahan.">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary btn-lg btn-block" id="submitBookingBtn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                <span>Konfirmasi Reservasi Grooming</span>
            </button>
        </form>
    </div>
</div>

<style>
@media (max-width: 768px) {
    div[style*="grid-template-columns: 2fr 1fr"],
    .grid-cols-2, .grid-cols-3 {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const branchCards = document.querySelectorAll('.js-branch-card');
    const serviceCards = document.querySelectorAll('.js-service-card');
    const dateInput = document.getElementById('bookingDateInput');
    const slotContainer = document.getElementById('timeSlotsContainer');
    const timeInput = document.getElementById('selectedTimeSlotInput');
    const loadingText = document.getElementById('slotLoadingText');
    const timeValidationHint = document.getElementById('timeValidationHint');
    const form = document.getElementById('bookingForm');

    // Service selection highlight
    serviceCards.forEach(card => {
        card.addEventListener('click', function() {
            serviceCards.forEach(c => {
                c.style.borderColor = 'var(--border)';
                c.classList.remove('active');
            });
            this.style.borderColor = 'var(--accent)';
            this.classList.add('active');
            const radio = this.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;
        });
    });

    // Branch selection highlight & slot reload
    branchCards.forEach(card => {
        card.addEventListener('click', function() {
            branchCards.forEach(c => {
                c.style.borderColor = 'var(--border)';
                c.classList.remove('active');
            });
            this.style.borderColor = 'var(--primary)';
            this.classList.add('active');
            const radio = this.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
                loadSlots();
            }
        });
    });

    dateInput.addEventListener('change', loadSlots);

    function getSelectedBranchId() {
        const checked = document.querySelector('input[name="branch_id"]:checked');
        return checked ? checked.value : null;
    }

    function loadSlots() {
        const branchId = getSelectedBranchId();
        const date = dateInput.value;

        if (!branchId || !date) return;

        loadingText.style.display = 'inline';
        slotContainer.innerHTML = '<span style="color: var(--muted); font-size: 0.85rem;">Memuat ketersediaan slot...</span>';

        fetch(`/api/booking-slots?branch_id=${branchId}&date=${date}`)
            .then(res => res.json())
            .then(data => {
                loadingText.style.display = 'none';
                slotContainer.innerHTML = '';

                if (!data.slots || data.slots.length === 0) {
                    slotContainer.innerHTML = '<span style="color: var(--danger); font-size: 0.85rem;">Tidak ada slot tersedia untuk tanggal ini.</span>';
                    return;
                }

                let autoSelected = false;

                data.slots.forEach(slot => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = `btn btn-sm ${slot.available ? 'btn-secondary' : 'btn-outline'}`;
                    btn.style.cssText = 'min-width: 68px; min-height: 38px; font-weight: 700;';

                    if (slot.available) {
                        btn.innerHTML = `${slot.time} <span style="font-size: 0.7rem; opacity: 0.8; margin-left: 2px;">(${slot.remaining} tersisa)</span>`;
                        btn.addEventListener('click', function() {
                            document.querySelectorAll('#timeSlotsContainer button').forEach(b => {
                                b.classList.remove('btn-primary');
                                b.classList.add('btn-secondary');
                            });
                            this.classList.remove('btn-secondary');
                            this.classList.add('btn-primary');
                            timeInput.value = slot.time;
                            if (timeValidationHint) timeValidationHint.style.display = 'none';
                        });

                        // Select if matches old input or first available
                        if (!autoSelected && (!timeInput.value || timeInput.value === slot.time)) {
                            btn.classList.remove('btn-secondary');
                            btn.classList.add('btn-primary');
                            timeInput.value = slot.time;
                            autoSelected = true;
                        }
                    } else {
                        btn.disabled = true;
                        btn.style.opacity = '0.4';
                        btn.style.cursor = 'not-allowed';
                        btn.innerHTML = `${slot.time} <span style="font-size: 0.7rem; color: var(--danger);">(Penuh)</span>`;
                    }

                    slotContainer.appendChild(btn);
                });
            })
            .catch(() => {
                loadingText.style.display = 'none';
                slotContainer.innerHTML = '<span style="color: var(--danger); font-size: 0.85rem;">Gagal memuat jadwal slot. Silakan refresh.</span>';
            });
    }

    // Initial load
    loadSlots();

    // Form submit validation
    form.addEventListener('submit', function(e) {
        if (!timeInput.value) {
            e.preventDefault();
            timeValidationHint.style.display = 'block';
            timeValidationHint.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
});
</script>
@endpush
