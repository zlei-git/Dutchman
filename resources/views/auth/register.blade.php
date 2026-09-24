@extends('layouts.app')

@section('title', 'Daftar Akun — Dutchman Barbershop')

@section('content')
<section style="padding: 3.5rem 0 5rem;">
    <div class="container" style="max-width: 460px;">
        <div class="card" style="padding: 2.5rem; background: var(--surface); box-shadow: var(--shadow-md);">
            <div style="text-align: center; margin-bottom: 2rem;">
                <div class="brand-logo" style="justify-content: center; margin-bottom: 0.75rem;">
                    <img src="{{ asset('images/barbershop/official-avatar.jpg') }}" alt="Dutchman" class="brand-logo-img">
                </div>
                <h2 style="font-family: var(--font-display); font-size: 1.8rem;">Registrasi Akun Pelanggan</h2>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.25rem;">
                    Buat akun untuk memantau jadwal reservasi dan kemudahan booking berikutnya.
                </p>
            </div>

            <form method="POST" action="{{ route('register.post') }}">
                @csrf

                <div style="margin-bottom: 1.25rem;">
                    <label for="name" style="display: block; font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem;">
                        Nama Lengkap
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus style="width: 100%; height: 44px; background: var(--bg); border: 1.5px solid var(--border); border-radius: var(--radius-sm); color: var(--text); padding: 0 0.85rem; font-family: var(--font-sans); font-size: 0.92rem; outline: none;" placeholder="Alexander Pratama">
                    @error('name')
                        <span style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem; display: block; font-weight: 600;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <label for="email" style="display: block; font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem;">
                        Alamat Email
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required style="width: 100%; height: 44px; background: var(--bg); border: 1.5px solid var(--border); border-radius: var(--radius-sm); color: var(--text); padding: 0 0.85rem; font-family: var(--font-sans); font-size: 0.92rem; outline: none;" placeholder="alex@domain.com">
                    @error('email')
                        <span style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem; display: block; font-weight: 600;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <label for="phone" style="display: block; font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem;">
                        Nomor WhatsApp / HP
                    </label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" style="width: 100%; height: 44px; background: var(--bg); border: 1.5px solid var(--border); border-radius: var(--radius-sm); color: var(--text); padding: 0 0.85rem; font-family: var(--font-sans); font-size: 0.92rem; outline: none;" placeholder="081234567890">
                    @error('phone')
                        <span style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem; display: block; font-weight: 600;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <label for="password" style="display: block; font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem;">
                        Kata Sandi
                    </label>
                    <input type="password" id="password" name="password" required style="width: 100%; height: 44px; background: var(--bg); border: 1.5px solid var(--border); border-radius: var(--radius-sm); color: var(--text); padding: 0 0.85rem; font-family: var(--font-sans); font-size: 0.92rem; outline: none;" placeholder="Min. 8 karakter">
                    @error('password')
                        <span style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem; display: block; font-weight: 600;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 1.75rem;">
                    <label for="password_confirmation" style="display: block; font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem;">
                        Konfirmasi Kata Sandi
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required style="width: 100%; height: 44px; background: var(--bg); border: 1.5px solid var(--border); border-radius: var(--radius-sm); color: var(--text); padding: 0 0.85rem; font-family: var(--font-sans); font-size: 0.92rem; outline: none;" placeholder="Ulangi kata sandi">
                </div>

                <button type="submit" class="btn btn-coral" style="width: 100%; height: 48px;">
                    <span>DAFTAR AKUN</span>
                </button>
            </form>

            <div style="text-align: center; margin-top: 1.75rem; font-size: 0.88rem; color: var(--text-muted);">
                Sudah memiliki akun? 
                <a href="{{ route('login') }}" style="color: var(--coral); font-weight: 700;">Masuk</a>
            </div>
        </div>
    </div>
</section>
@endsection
