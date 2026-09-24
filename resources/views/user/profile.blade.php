@extends('layouts.app')

@section('title', 'Profil & Pengaturan — Dutchman Barbershop')

@section('content')
<section style="padding: 3rem 0 5rem;">
    <div class="container" style="max-width: 820px;">
        <!-- Header -->
        <div style="margin-bottom: 2.5rem;">
            <span class="badge badge-brass" style="margin-bottom: 0.5rem;">PENGATURAN</span>
            <h1>Pengaturan Akun</h1>
            <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.25rem;">
                Kelola informasi kontak pribadi dan keamanan kata sandi Anda.
            </p>
        </div>

        <div class="grid grid-cols-2" style="gap: 2rem; align-items: start;">
            <!-- Personal Info Card -->
            <div class="card" style="padding: 2rem; background: var(--surface);">
                <div style="font-family: var(--font-serif); font-size: 1.3rem; margin-bottom: 1.25rem;">Informasi Pribadi</div>

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
                        <input type="email" value="{{ $user->email }}" disabled style="width: 100%; height: 44px; background: rgba(0,0,0,0.2); border: 1px solid var(--border); border-radius: var(--radius-sm); color: var(--text-muted); padding: 0 0.85rem; font-family: var(--font-sans); font-size: 0.9rem; cursor: not-allowed;">
                        <span style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-top: 0.25rem;">Alamat email tidak dapat diubah langsung.</span>
                    </div>

                    <div style="margin-bottom: 1.75rem;">
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem;">
                            Nomor WhatsApp / HP
                        </label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="cth. 081234567890" style="width: 100%; height: 44px; background: var(--surface-light); border: 1px solid var(--border); border-radius: var(--radius-sm); color: var(--text); padding: 0 0.85rem; font-family: var(--font-sans); font-size: 0.9rem; outline: none;">
                    </div>

                    <button type="submit" class="btn btn-brass" style="width: 100%;">
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            <!-- Security / Password Card -->
            <div class="card" style="padding: 2rem; background: var(--surface);">
                <div style="font-family: var(--font-serif); font-size: 1.3rem; margin-bottom: 1.25rem;">Ubah Kata Sandi</div>

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
</section>
@endsection
