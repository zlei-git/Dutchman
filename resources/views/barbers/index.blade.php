@extends('layouts.app')

@section('title', 'Master Barbers — Dutchman Barbershop')

@section('content')
<section style="padding: 3rem 0 5rem;">
    <div class="container">
        <!-- Header -->
        <div style="text-align: center; max-width: 650px; margin: 0 auto 3.5rem;">
            <div style="display: inline-block; background: var(--yellow-soft); color: #B45309; font-weight: 800; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.08em; padding: 0.35rem 0.85rem; border-radius: var(--radius-full); margin-bottom: 0.75rem; box-shadow: var(--shadow-sm); transform: rotate(-2deg);">
                ★ TIM MASTER BARBER
            </div>
            <h1 style="font-family: var(--font-display); font-size: clamp(2.4rem, 4.5vw, 3.4rem);">Master Barber</h1>
            <p style="margin-top: 0.5rem; font-size: 1.05rem; color: var(--text-muted);">
                Master barber kami berdedikasi tinggi dengan keahlian presisi dan mendengarkan keinginan Anda secara saksama. Pilih barber favorit Anda atau pilih kursi yang tersedia paling awal.
            </p>
        </div>

        <!-- Barbers Grid (Borderless Uniform 1:1 Images) -->
        <div class="grid grid-cols-2" style="gap: 2.5rem; max-width: 960px; margin: 0 auto;">
            @foreach($barbers as $barber)
                <div class="card" style="display: flex; gap: 1.75rem; padding: 1.75rem; background: var(--surface); align-items: start;">
                    <!-- Borderless Square 1:1 Image -->
                    <div style="width: 130px; height: 130px; border-radius: var(--radius-md); overflow: hidden; flex-shrink: 0; background: var(--bg);">
                        <img src="{{ asset($barber->photo) }}" alt="{{ $barber->name }}" style="width: 100%; height: 100%; object-fit: cover; border: none !important;">
                    </div>

                    <div style="flex: 1; display: flex; flex-direction: column;">
                        <div style="display: flex; justify-content: space-between; align-items: baseline;">
                            <h3 style="font-size: 1.35rem; font-family: var(--font-display);">{{ $barber->name }}</h3>
                            <span style="background: var(--yellow-soft); color: #B45309; font-weight: 800; font-size: 0.72rem; padding: 0.2rem 0.6rem; border-radius: var(--radius-full);">
                                {{ $barber->experience_years }} THN PENGALAMAN
                            </span>
                        </div>

                        <div style="font-size: 0.85rem; color: var(--coral); font-weight: 700; margin-top: 0.2rem; margin-bottom: 0.75rem;">
                            {{ $barber->specialty }}
                        </div>

                        <p style="font-size: 0.88rem; color: var(--text-muted); line-height: 1.55; margin-bottom: 1.5rem; flex: 1;">
                            {{ $barber->bio }}
                        </p>

                        <div>
                            <a href="{{ route('booking.create', ['barber_id' => $barber->id]) }}" class="btn btn-coral btn-sm" style="width: 100%;">
                                <span>Pilih Kursi {{ explode(' ', $barber->name)[0] }}</span>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Any Barber Quick CTA -->
        <div style="text-align: center; margin-top: 4rem; padding: 2.5rem; background: var(--surface-alt); border-radius: var(--radius-md); max-width: 720px; margin-left: auto; margin-right: auto; box-shadow: var(--shadow-sm);">
            <div style="display: inline-block; background: var(--green-soft); color: #065F46; font-weight: 800; font-size: 0.74rem; text-transform: uppercase; padding: 0.3rem 0.75rem; border-radius: var(--radius-full); margin-bottom: 0.5rem;">
                KURSI TERCEPAT
            </div>
            <h3 style="font-size: 1.35rem; margin-bottom: 0.5rem; font-family: var(--font-display);">Butuh Potong Rambut Hari Ini?</h3>
            <p style="font-size: 0.92rem; color: var(--text-muted); margin-bottom: 1.25rem;">
                Pilih opsi "Barber Bebas (Tersedia)" saat reservasi untuk mendapatkan jam kosong tercepat di studio kami.
            </p>
            <a href="{{ route('booking.create') }}" class="btn btn-coral">
                <span>CEK JADWAL TERSEDIA</span>
            </a>
        </div>
    </div>
</section>
@endsection
