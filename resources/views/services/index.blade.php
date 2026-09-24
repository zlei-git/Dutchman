@extends('layouts.app')

@section('title', 'Layanan & Harga — Dutchman Barbershop Surabaya')

@section('content')
<!-- ==========================================================================
     SERVICES & PRICING HEADER
     Direct title • Comprehensive Dutchman Menu Book
     ========================================================================== -->
<section style="padding: 4.5rem 0 2rem; background: #FFFFFF; border-bottom: 1px solid var(--border);">
    <div class="container">
        <div style="max-width: 800px; margin: 0 auto; text-align: center;">
            <h1 style="font-family: var(--font-display); font-size: clamp(2.4rem, 5vw, 3.6rem); font-weight: 800; color: #111111; text-transform: uppercase; line-height: 1.1; margin-bottom: 0.85rem;">
                Layanan &amp; Harga
            </h1>
            <p style="font-size: 1.1rem; line-height: 1.65; color: #666666;">
                Daftar lengkap layanan pangkas rambut pria, cukur jenggot tradisional, perawatan wajah &amp; rambut, serta pewarnaan sesuai buku menu resmi Dutchman Barbershop Surabaya.
            </p>
        </div>
    </div>
</section>

<!-- ==========================================================================
     CATEGORIZED MENU SECTIONS
     Haircut • Trim & Shave • Hair Tattoo & Styling • Treatment • Face Mask • Coloring
     ========================================================================== -->
<section style="padding: 4rem 0 6rem; background: #F8F8F8;">
    <div class="container" style="max-width: 1000px;">
        @php
            $groupedServices = $services->groupBy(function($item) {
                return $item->category ?: 'Haircut';
            });
            
            $categoryOrder = [
                'Haircut' => ['desc' => 'Paket pangkas rambut pria dan anak dengan ritual cuci, tonic, dan styling pomade.'],
                'Trim & Shave' => ['desc' => 'Perapian brewok dan cukur pisau lipat tradisional dengan handuk hangat aromaterapi.'],
                'Hair Tattoo & Styling' => ['desc' => 'Seni hair tattoo artistik dan styling rambut profesional.'],
                'Treatment' => ['desc' => 'Nutrisi akar rambut, penanganan rambut rontok, dan creambath relaksasi kepala.'],
                'Face Mask' => ['desc' => 'Perawatan masker wajah alami pembersih minyak dan pencerah kulit.'],
                'Coloring' => ['desc' => 'Pewarnaan rambut hitam natural hingga highlight fashion kontras modern.'],
            ];
        @endphp

        <div style="display: flex; flex-direction: column; gap: 3.5rem;">
            @foreach($categoryOrder as $catName => $meta)
                @php
                    $catServices = $groupedServices->get($catName, collect());
                @endphp

                @if($catServices->isNotEmpty())
                    <div style="background: #FFFFFF; border: 1px solid var(--border); border-radius: 8px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.04);">
                        <!-- Category Header Banner -->
                        <div style="padding: 1.5rem 2rem; background: #111111; color: #FFFFFF; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
                            <div>
                                <h2 style="font-family: var(--font-display); font-size: 1.35rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase; margin: 0; color: #FFFFFF;">
                                    {{ $catName }}
                                </h2>
                                <p style="font-size: 0.88rem; color: #BBBBBB; margin: 0.35rem 0 0; line-height: 1.4;">
                                    {{ $meta['desc'] }}
                                </p>
                            </div>
                            <span style="font-size: 0.82rem; font-weight: 700; color: #CCCCCC; text-transform: uppercase; letter-spacing: 0.08em; background: #222222; padding: 0.3rem 0.75rem; border-radius: 3px;">
                                {{ $catServices->count() }} Pilihan
                            </span>
                        </div>

                        <!-- Service Items List -->
                        <div style="display: flex; flex-direction: column;">
                            @foreach($catServices as $service)
                                <div class="service-item-row" style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 2rem; border-bottom: 1px solid #EEEEEE; transition: background 0.2s ease;">
                                    <!-- Left: Info & Description -->
                                    <div style="max-width: 68%;">
                                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.35rem; flex-wrap: wrap;">
                                            <h3 style="font-family: var(--font-display); font-size: 1.18rem; font-weight: 800; color: #111111; margin: 0;">
                                                {{ $service->name }}
                                            </h3>
                                            @if($service->badge)
                                                <span style="background: #111111; color: #FFFFFF; font-size: 0.7rem; font-weight: 800; padding: 0.2rem 0.55rem; border-radius: 2px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                    {{ $service->badge }}
                                                </span>
                                            @endif
                                        </div>

                                        <p style="font-size: 0.92rem; line-height: 1.55; color: #555555; margin: 0 0 0.4rem;">
                                            {{ $service->description }}
                                        </p>

                                        <div style="font-size: 0.82rem; color: #888888; font-weight: 600;">
                                            Durasi: {{ $service->duration_minutes }} menit
                                        </div>
                                    </div>

                                    <!-- Right: Price & Arrow Button to Book Now -->
                                    <div style="display: flex; align-items: center; gap: 1.5rem; text-align: right;">
                                        <div>
                                            <div style="font-family: var(--font-display); font-size: 1.35rem; font-weight: 800; color: #111111; white-space: nowrap;">
                                                {{ $service->formatted_price }}
                                            </div>
                                            <div style="font-size: 0.75rem; color: #888888; font-weight: 600; text-transform: uppercase;">
                                                Netto
                                            </div>
                                        </div>

                                        <!-- Arrow Button: Redirect to Book Now with Selected Service -->
                                        <a href="{{ route('booking.create', ['service_id' => $service->id]) }}" class="service-arrow-btn" style="display: inline-flex; align-items: center; justify-content: center; width: 44px; height: 44px; border-radius: 50%; background: #111111; color: #FFFFFF; text-decoration: none; transition: all 0.2s ease; flex-shrink: 0;" title="Pilih Layanan &amp; Reservasi Sekarang">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Bottom Notice & Booking CTA -->
        <div style="margin-top: 4rem; padding: 2.5rem; background: #FFFFFF; border: 1px solid var(--border); border-radius: 8px; text-align: center; box-shadow: 0 6px 24px rgba(0,0,0,0.04);">
            <h3 style="font-family: var(--font-display); font-size: 1.5rem; font-weight: 800; color: #111111; margin-bottom: 0.75rem; text-transform: uppercase;">
                Siap Tampil Rapi &amp; Percaya Diri?
            </h3>
            <p style="font-size: 1rem; color: #666666; max-width: 550px; margin: 0 auto 2rem; line-height: 1.6;">
                Reservasi jadwal Anda sekarang tanpa perlu mengantre. Master barber kami siap menyambut Anda di Jl. Rungkut Madya No.55A, Surabaya.
            </p>
            <a href="{{ route('booking.create') }}" class="btn" style="background: #111111; color: #FFFFFF; font-weight: 800; font-size: 0.95rem; padding: 0.95rem 2.8rem; border-radius: 4px; text-transform: uppercase;">
                <span>RESERVASI SEKARANG &rarr;</span>
            </a>
        </div>
    </div>
</section>

@push('styles')
<style>
    .service-item-row:hover {
        background-color: #FAFAFA;
    }
    .service-item-row:hover .service-arrow-btn {
        transform: translateX(4px);
        background-color: #000000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.25);
    }
</style>
@endpush
@endsection
