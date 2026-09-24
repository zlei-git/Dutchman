@extends('layouts.app')

@section('title', 'Masuk Akun — Dutchman Barbershop')

@section('content')
<section style="padding: 4rem 0 6rem; min-height: calc(100vh - 76px); display: flex; align-items: center; justify-content: center; background: #FAF7F2;">
    <div class="container" style="max-width: 540px; width: 100%;">
        <div class="card" style="padding: 3.25rem 3rem; background: #FFFFFF; border: 1px solid var(--border); border-radius: 8px; box-shadow: 0 10px 35px rgba(0,0,0,0.06);">
            <div style="text-align: center; margin-bottom: 2.25rem;">
                <div class="brand-logo" style="justify-content: center; margin-bottom: 1rem;">
                    <img src="{{ asset('images/barbershop/official-avatar.jpg') }}" alt="Dutchman Barbershop" class="brand-logo-img" style="width: 58px; height: 58px; border-radius: 50%;">
                </div>
                <h2 style="font-family: var(--font-display); font-size: 2rem; font-weight: 800; letter-spacing: 0.04em; color: #111111; text-transform: uppercase; margin-bottom: 0.4rem;">
                    Masuk Akun Pelanggan
                </h2>
                <p style="color: #666666; font-size: 0.95rem; line-height: 1.5; margin: 0;">
                    Akses jadwal reservasi dan riwayat kunjungan Anda.
                </p>
            </div>

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <div style="margin-bottom: 1.35rem;">
                    <label for="emailInput" style="display: block; font-size: 0.8rem; font-weight: 700; color: #444444; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.45rem;">
                        Alamat Email / Username
                    </label>
                    <input type="text" id="emailInput" name="email" value="{{ old('email') }}" required autofocus style="width: 100%; height: 48px; background: #FAF7F2; border: 1.5px solid var(--border); border-radius: 4px; color: #111111; padding: 0 1rem; font-family: var(--font-sans); font-size: 0.95rem; outline: none; transition: border-color 0.2s;" placeholder="Username">
                    @error('email')
                        <span style="color: var(--danger); font-size: 0.82rem; margin-top: 0.35rem; display: block; font-weight: 600;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 1.35rem;">
                    <label for="passwordInput" style="display: block; font-size: 0.8rem; font-weight: 700; color: #444444; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.45rem;">
                        Kata Sandi
                    </label>
                    <input type="password" id="passwordInput" name="password" required style="width: 100%; height: 48px; background: #FAF7F2; border: 1.5px solid var(--border); border-radius: 4px; color: #111111; padding: 0 1rem; font-family: var(--font-sans); font-size: 0.95rem; outline: none; transition: border-color 0.2s;" placeholder="Password">
                    @error('password')
                        <span style="color: var(--danger); font-size: 0.82rem; margin-top: 0.35rem; display: block; font-weight: 600;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 1.75rem; display: flex; align-items: center; justify-content: space-between; font-size: 0.88rem;">
                    <label style="display: flex; align-items: center; gap: 0.45rem; cursor: pointer; color: #666666; font-weight: 600;">
                        <input type="checkbox" name="remember" value="1" style="accent-color: #111111; width: 16px; height: 16px;">
                        <span>Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="btn" style="width: 100%; height: 50px; background: #111111; color: #FFFFFF; font-weight: 800; font-size: 0.95rem; letter-spacing: 0.08em; text-transform: uppercase; border-radius: 4px; box-shadow: 0 4px 16px rgba(0,0,0,0.15); display: flex; align-items: center; justify-content: center;">
                    <span>MASUK</span>
                </button>
            </form>

            <div style="text-align: center; margin-top: 2rem; font-size: 0.92rem; color: #666666; border-top: 1px solid var(--border); padding-top: 1.5rem;">
                Belum punya akun? 
                <a href="{{ route('register') }}" style="color: #111111; font-weight: 800; text-decoration: underline;">Daftar Akun</a>
            </div>
        </div>
    </div>
</section>
@endsection
