@extends('layouts.admin')

@section('title', 'Pengaturan Studio')
@section('page_title', 'Konfigurasi & Kebijakan Studio')

@section('admin_content')
<div style="max-width: 840px;">
    <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
        <h3 style="font-size: 1.25rem; margin-bottom: 1.25rem;">Profil Studio</h3>

        <div class="grid grid-cols-2" style="gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.35rem;">Nama Brand / Barbershop</label>
                <input type="text" value="{{ $shop['name'] }}" readonly style="width: 100%; height: 38px; padding: 0 0.75rem; background: var(--main-bg);">
            </div>
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.35rem;">Tagline Brand</label>
                <input type="text" value="{{ $shop['tagline'] }}" readonly style="width: 100%; height: 38px; padding: 0 0.75rem; background: var(--main-bg);">
            </div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.35rem;">Alamat Studio</label>
            <input type="text" value="{{ $shop['address'] }}" readonly style="width: 100%; height: 38px; padding: 0 0.75rem; background: var(--main-bg);">
        </div>

        <div class="grid grid-cols-2" style="gap: 1.5rem;">
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.35rem;">Nomor WhatsApp Concierge</label>
                <input type="text" value="{{ $shop['phone'] }}" readonly style="width: 100%; height: 38px; padding: 0 0.75rem; background: var(--main-bg);">
            </div>
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.35rem;">Akun Instagram</label>
                <input type="text" value="{{ $shop['instagram'] }}" readonly style="width: 100%; height: 38px; padding: 0 0.75rem; background: var(--main-bg);">
            </div>
        </div>
    </div>

    <div class="card" style="padding: 2rem;">
        <h3 style="font-size: 1.25rem; margin-bottom: 1.25rem;">Aturan Jadwal &amp; Kapasitas Kursi</h3>

        <div class="grid grid-cols-2" style="gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.35rem;">Jam Operasional</label>
                <input type="text" value="{{ $shop['opening_hours'] }}" readonly style="width: 100%; height: 38px; padding: 0 0.75rem; background: var(--main-bg);">
                <span style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem; display: block;">11:00 - 21:00 (Jum: 13:00) / 22:00 (Akhir Pekan) WIB</span>
            </div>
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.35rem;">Interval Waktu Slot</label>
                <input type="text" value="{{ $shop['slot_interval'] }}" readonly style="width: 100%; height: 38px; padding: 0 0.75rem; background: var(--main-bg);">
                <span style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem; display: block;">Interval langkah janji temu terjadwal</span>
            </div>
        </div>

        <div style="background: var(--main-bg); padding: 1.25rem; border-radius: var(--radius-sm); border: 1px solid var(--border); font-size: 0.85rem; color: var(--text-muted); line-height: 1.6;">
            <strong style="color: var(--text); display: block; margin-bottom: 0.35rem;">Pencegahan Tabrakan Jadwal Otomatis:</strong>
            Sistem penjadwalan cerdas Dutchman secara otomatis memeriksa ketersediaan setiap barber pada interval 30 menit. Saat opsi 'Barber Bebas' dipilih pelanggan, sistem menghitung kapasitas kursi studio dan menugaskan master barber yang sedang lowong, sehingga bebas dari risiko jadwal ganda (double-booking).
        </div>
    </div>
</div>
@endsection
