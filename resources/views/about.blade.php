@extends('layouts.app')

@section('title', 'About Dutchman Barbershop — Built Around Classic Barbering')

@section('content')
<!-- ==========================================================================
     SECTION 01 — HERO
     Spacious, strong typography, editorial tone
     ========================================================================== -->
<section style="padding: 5.5rem 0 4.5rem; background: #FAF7F2; border-bottom: 1px solid var(--border);">
    <div class="container" style="max-width: 980px; text-align: center;">
        <div style="font-size: 0.82rem; font-weight: 800; letter-spacing: 0.18em; text-transform: uppercase; color: #888888; margin-bottom: 1.1rem;">
            ABOUT DUTCHMAN
        </div>
        <h1 style="font-family: var(--font-display); font-size: clamp(2.6rem, 5.5vw, 4.4rem); font-weight: 800; color: #111111; text-transform: uppercase; line-height: 1.1; margin-bottom: 1.75rem; letter-spacing: 0.02em;">
            MORE THAN JUST A HAIRCUT.
        </h1>
        <p style="font-size: clamp(1.05rem, 1.8vw, 1.25rem); color: #444444; line-height: 1.75; max-width: 780px; margin: 0 auto;">
            Dutchman Barbershop lahir dari apresiasi terhadap barbering klasik — ketelitian dalam setiap potongan, perhatian terhadap detail, dan keyakinan bahwa grooming seharusnya tidak terasa terburu-buru.
        </p>
    </div>
</section>

<!-- ==========================================================================
     SECTION 02 — THE STORY
     Brand philosophy & origins
     ========================================================================== -->
<section style="padding: 6rem 0; background: #FFFFFF; border-bottom: 1px solid var(--border);">
    <div class="container" style="max-width: 1100px;">
        <div style="display: grid; grid-template-columns: 1.1fr 1fr; gap: 4.5rem; align-items: center;" class="about-story-grid">
            <!-- Left: Text -->
            <div>
                <div style="font-size: 0.8rem; font-weight: 800; letter-spacing: 0.18em; text-transform: uppercase; color: #888888; margin-bottom: 0.85rem;">
                    THE STORY BEHIND DUTCHMAN
                </div>
                <h2 style="font-family: var(--font-display); font-size: clamp(2rem, 3.5vw, 2.75rem); font-weight: 800; color: #111111; text-transform: uppercase; line-height: 1.15; margin-bottom: 1.75rem; letter-spacing: 0.02em;">
                    BUILT AROUND CLASSIC BARBERING.
                </h2>
                <div style="font-size: 1.02rem; line-height: 1.8; color: #555555; display: flex; flex-direction: column; gap: 1.25rem;">
                    <p style="margin: 0;">
                        Dutchman Barbershop dibangun dengan satu pemikiran sederhana: potongan rambut yang baik bukan hanya tentang bagaimana rambut terlihat setelah selesai, tetapi bagaimana seseorang merasa ketika meninggalkan kursi barber.
                    </p>
                    <p style="margin: 0;">
                        Karena itu, kami mempertahankan elemen-elemen yang membuat barbering klasik begitu personal — konsultasi, ketelitian gunting manual, perhatian pada detail, hingga finishing yang dikerjakan dengan sabar.
                    </p>
                    <p style="margin: 0; color: #111111; font-weight: 600;">
                        Di saat semuanya semakin cepat, Dutchman memilih untuk tetap memberikan ruang bagi proses.
                    </p>
                </div>
            </div>

            <!-- Right: Authentic Studio Image -->
            <div>
                <div style="border-radius: 6px; overflow: hidden; box-shadow: 0 16px 40px rgba(0,0,0,0.1); aspect-ratio: 4/5; background: #111111;">
                    <img src="{{ asset('images/barbershop/dutchman-official-maps.jpg') }}" alt="Studio Dutchman Barbershop Surabaya" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                </div>
                <div style="margin-top: 0.85rem; font-size: 0.82rem; color: #888888; letter-spacing: 0.04em;">
                    Studio Dutchman Barbershop • Berdiri sejak 2020 di Surabaya
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================================================
     SECTION 03 — THE DUTCHMAN WAY
     3 Principles & Editorial Visual
     ========================================================================== -->
<section style="padding: 6rem 0; background: #FAF7F2; border-bottom: 1px solid var(--border);">
    <div class="container" style="max-width: 1100px;">
        <div style="display: grid; grid-template-columns: 1fr 1.1fr; gap: 4.5rem; align-items: center;" class="about-way-grid">
            <!-- Left: Authentic Haircut Craft Visual -->
            <div>
                <div style="border-radius: 6px; overflow: hidden; box-shadow: 0 16px 40px rgba(0,0,0,0.1); aspect-ratio: 4/5; background: #111111;">
                    <img src="{{ asset('images/barbershop/dutchman-action-craft.jpg') }}" alt="Proses Barbering Dutchman Barbershop Surabaya" style="width: 100%; height: 100%; object-fit: cover; object-position: center; display: block;">
                </div>
            </div>

            <!-- Right: 3 Principles -->
            <div>
                <div style="font-size: 0.8rem; font-weight: 800; letter-spacing: 0.18em; text-transform: uppercase; color: #888888; margin-bottom: 0.85rem;">
                    THE DUTCHMAN WAY
                </div>
                <h2 style="font-family: var(--font-display); font-size: clamp(2rem, 3.5vw, 2.75rem); font-weight: 800; color: #111111; text-transform: uppercase; line-height: 1.15; margin-bottom: 2.5rem; letter-spacing: 0.02em;">
                    PRECISION IN EVERY DETAIL.
                </h2>

                <div style="display: flex; flex-direction: column; gap: 2rem;">
                    <!-- 01 -->
                    <div style="display: flex; gap: 1.5rem; align-items: flex-start;">
                        <span style="font-family: var(--font-display); font-size: 1.35rem; font-weight: 800; color: #111111; line-height: 1; padding-top: 0.2rem;">01</span>
                        <div>
                            <h3 style="font-family: var(--font-display); font-size: 1.05rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: #111111; margin-bottom: 0.35rem;">
                                CONSULTATION
                            </h3>
                            <p style="font-size: 0.95rem; line-height: 1.65; color: #666666; margin: 0;">
                                Setiap potongan dimulai dengan memahami apa yang Anda inginkan — bukan sekadar memilih gaya dari katalog.
                            </p>
                        </div>
                    </div>

                    <!-- 02 -->
                    <div style="display: flex; gap: 1.5rem; align-items: flex-start;">
                        <span style="font-family: var(--font-display); font-size: 1.35rem; font-weight: 800; color: #111111; line-height: 1; padding-top: 0.2rem;">02</span>
                        <div>
                            <h3 style="font-family: var(--font-display); font-size: 1.05rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: #111111; margin-bottom: 0.35rem;">
                                CRAFT
                            </h3>
                            <p style="font-size: 0.95rem; line-height: 1.65; color: #666666; margin: 0;">
                                Setiap bentuk, tekstur, dan detail dikerjakan dengan teknik yang sesuai dengan karakter rambut dan kebutuhan Anda.
                            </p>
                        </div>
                    </div>

                    <!-- 03 -->
                    <div style="display: flex; gap: 1.5rem; align-items: flex-start;">
                        <span style="font-family: var(--font-display); font-size: 1.35rem; font-weight: 800; color: #111111; line-height: 1; padding-top: 0.2rem;">03</span>
                        <div>
                            <h3 style="font-family: var(--font-display); font-size: 1.05rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: #111111; margin-bottom: 0.35rem;">
                                FINISH
                            </h3>
                            <p style="font-size: 0.95rem; line-height: 1.65; color: #666666; margin: 0;">
                                Detail terakhir menentukan keseluruhan hasil. Karena itu, finishing tidak pernah dianggap sebagai bagian yang bisa dilewati.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================================================
     SECTION 04 — KENAPA HARUS KAMI (VERY IMPORTANT)
     2x2 Grid with Thin Separators, Large Editorial Numbers, Generous Whitespace
     ========================================================================== -->
<section style="padding: 6.5rem 0; background: #FFFFFF; border-bottom: 1px solid var(--border);">
    <div class="container" style="max-width: 1100px;">
        <!-- Header -->
        <div style="margin-bottom: 3.5rem;">
            <div style="font-size: 0.8rem; font-weight: 800; letter-spacing: 0.18em; text-transform: uppercase; color: #888888; margin-bottom: 0.85rem;">
                KENAPA HARUS KAMI
            </div>
            <h2 style="font-family: var(--font-display); font-size: clamp(2.2rem, 4vw, 3.2rem); font-weight: 800; color: #111111; text-transform: uppercase; line-height: 1.12; margin-bottom: 0.85rem; letter-spacing: 0.02em;">
                BUKAN SEKADAR POTONG RAMBUT.
            </h2>
            <p style="font-size: 1.05rem; color: #666666; margin: 0; line-height: 1.6;">
                Ada alasan kenapa setiap detail di Dutchman dibuat dengan perhatian.
            </p>
        </div>

        <!-- 2x2 Editorial Grid -->
        <div style="border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);">
            <!-- Row 1: Items 01 & 02 -->
            <div class="kenapa-row" style="display: grid; grid-template-columns: 1fr 1fr; border-bottom: 1px solid var(--border);">
                <!-- 01 -->
                <div class="kenapa-col" style="padding: 3.5rem 3.5rem 3.5rem 0; border-right: 1px solid var(--border);">
                    <div style="font-family: var(--font-display); font-size: 2.6rem; font-weight: 800; color: #111111; line-height: 1; margin-bottom: 1.35rem; opacity: 0.85;">
                        01
                    </div>
                    <h3 style="font-family: var(--font-display); font-size: 1.18rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase; color: #111111; margin-bottom: 0.85rem; line-height: 1.3;">
                        PRESISI DALAM SETIAP POTONGAN
                    </h3>
                    <p style="font-size: 0.98rem; line-height: 1.75; color: #555555; margin: 0;">
                        Setiap potongan dikerjakan dengan perhatian pada bentuk, proporsi, tekstur, dan detail. Bukan sekadar memendekkan rambut, tetapi memastikan hasil akhirnya sesuai dengan karakter dan gaya yang diinginkan.
                    </p>
                </div>

                <!-- 02 -->
                <div class="kenapa-col-right" style="padding: 3.5rem 0 3.5rem 3.5rem;">
                    <div style="font-family: var(--font-display); font-size: 2.6rem; font-weight: 800; color: #111111; line-height: 1; margin-bottom: 1.35rem; opacity: 0.85;">
                        02
                    </div>
                    <h3 style="font-family: var(--font-display); font-size: 1.18rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase; color: #111111; margin-bottom: 0.85rem; line-height: 1.3;">
                        CLASSIC BARBERING, MODERN APPROACH
                    </h3>
                    <p style="font-size: 0.98rem; line-height: 1.75; color: #555555; margin: 0;">
                        Dutchman mempertahankan elemen barbering klasik seperti gunting manual dan straight razor, namun tetap menyesuaikan teknik dengan kebutuhan grooming pria modern.
                    </p>
                </div>
            </div>

            <!-- Row 2: Items 03 & 04 -->
            <div class="kenapa-row" style="display: grid; grid-template-columns: 1fr 1fr;">
                <!-- 03 -->
                <div class="kenapa-col" style="padding: 3.5rem 3.5rem 3.5rem 0; border-right: 1px solid var(--border);">
                    <div style="font-family: var(--font-display); font-size: 2.6rem; font-weight: 800; color: #111111; line-height: 1; margin-bottom: 1.35rem; opacity: 0.85;">
                        03
                    </div>
                    <h3 style="font-family: var(--font-display); font-size: 1.18rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase; color: #111111; margin-bottom: 0.85rem; line-height: 1.3;">
                        TIDAK TERBURU-BURU
                    </h3>
                    <p style="font-size: 0.98rem; line-height: 1.75; color: #555555; margin: 0;">
                        Kami percaya potongan yang baik membutuhkan waktu. Mulai dari konsultasi, proses cutting, hingga finishing, setiap tahap dilakukan dengan ritme yang nyaman dan penuh perhatian.
                    </p>
                </div>

                <!-- 04 -->
                <div class="kenapa-col-right" style="padding: 3.5rem 0 3.5rem 3.5rem;">
                    <div style="font-family: var(--font-display); font-size: 2.6rem; font-weight: 800; color: #111111; line-height: 1; margin-bottom: 1.35rem; opacity: 0.85;">
                        04
                    </div>
                    <h3 style="font-family: var(--font-display); font-size: 1.18rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase; color: #111111; margin-bottom: 0.85rem; line-height: 1.3;">
                        DATANG SEBAGAI TAMU, PULANG DENGAN LEBIH PERCAYA DIRI
                    </h3>
                    <p style="font-size: 0.98rem; line-height: 1.75; color: #555555; margin: 0;">
                        Atmosfer, pelayanan, detail grooming, dan hasil akhir dirancang sebagai satu pengalaman yang utuh — bukan hanya sebuah kunjungan untuk potong rambut.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================================================
     SECTION 05 — BEHIND THE CHAIR
     Process from consultation to finish
     ========================================================================== -->
<section style="padding: 6rem 0; background: #FAF7F2; border-bottom: 1px solid var(--border);">
    <div class="container" style="max-width: 1100px;">
        <div style="max-width: 760px; margin-bottom: 3.5rem;">
            <div style="font-size: 0.8rem; font-weight: 800; letter-spacing: 0.18em; text-transform: uppercase; color: #888888; margin-bottom: 0.85rem;">
                BEHIND THE CHAIR
            </div>
            <h2 style="font-family: var(--font-display); font-size: clamp(2.2rem, 4vw, 3.2rem); font-weight: 800; color: #111111; text-transform: uppercase; line-height: 1.12; margin-bottom: 1.35rem; letter-spacing: 0.02em;">
                FROM CONSULTATION TO FINISH.
            </h2>
            <div style="font-size: 1.02rem; line-height: 1.75; color: #555555; display: flex; flex-direction: column; gap: 0.85rem;">
                <p style="margin: 0;">
                    Setiap kunjungan dimulai dari percakapan sederhana. Kami ingin memahami gaya yang Anda inginkan, bagaimana rambut Anda tumbuh, dan seperti apa hasil yang paling sesuai.
                </p>
                <p style="margin: 0;">
                    Setelah itu, proses berjalan secara bertahap — dari cutting, detailing, hingga finishing — dengan perhatian yang sama pada setiap tahap.
                </p>
            </div>
        </div>

        <!-- Minimal 4-Step Process Visual -->
        <div class="process-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 2rem; border-top: 1px solid var(--border); padding-top: 2.75rem;">
            <div>
                <div style="font-family: var(--font-display); font-size: 1.6rem; font-weight: 800; color: #111111; margin-bottom: 0.45rem;">
                    01
                </div>
                <div style="font-family: var(--font-display); font-size: 1.1rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: #111111; margin-bottom: 0.35rem;">
                    CONSULT
                </div>
                <div style="font-size: 0.86rem; color: #777777; line-height: 1.5;">
                    Pemahaman bentuk wajah, arah tumbuh rambut, dan gaya personal.
                </div>
            </div>

            <div>
                <div style="font-family: var(--font-display); font-size: 1.6rem; font-weight: 800; color: #111111; margin-bottom: 0.45rem;">
                    02
                </div>
                <div style="font-family: var(--font-display); font-size: 1.1rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: #111111; margin-bottom: 0.35rem;">
                    CUT
                </div>
                <div style="font-size: 0.86rem; color: #777777; line-height: 1.5;">
                    Pemotongan bertahap dengan proporsi dan tekstur yang presisi.
                </div>
            </div>

            <div>
                <div style="font-family: var(--font-display); font-size: 1.6rem; font-weight: 800; color: #111111; margin-bottom: 0.45rem;">
                    03
                </div>
                <div style="font-family: var(--font-display); font-size: 1.1rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: #111111; margin-bottom: 0.35rem;">
                    DETAIL
                </div>
                <div style="font-size: 0.86rem; color: #777777; line-height: 1.5;">
                    Perapian garis leher, fade, dan cukur pisau lipat tradisional.
                </div>
            </div>

            <div>
                <div style="font-family: var(--font-display); font-size: 1.6rem; font-weight: 800; color: #111111; margin-bottom: 0.45rem;">
                    04
                </div>
                <div style="font-family: var(--font-display); font-size: 1.1rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: #111111; margin-bottom: 0.35rem;">
                    FINISH
                </div>
                <div style="font-size: 0.86rem; color: #777777; line-height: 1.5;">
                    Pencucian bersih, tonic penyegar, dan penataan pomade sesuai selera.
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================================================
     SECTION 06 — THE DUTCHMAN STANDARD
     Strong editorial moment, serif statement, generous whitespace
     ========================================================================== -->
<section style="padding: 7.5rem 0; background: #111111; color: #FFFFFF; text-align: center;">
    <div class="container" style="max-width: 860px;">
        <div style="font-size: 0.8rem; font-weight: 800; letter-spacing: 0.22em; text-transform: uppercase; color: #888888; margin-bottom: 2rem;">
            THE DUTCHMAN STANDARD
        </div>
        <blockquote style="font-family: var(--font-display); font-size: clamp(2.4rem, 5.2vw, 4.2rem); font-weight: 800; line-height: 1.16; text-transform: uppercase; letter-spacing: 0.02em; margin: 0 0 2rem; color: #FFFFFF;">
            &ldquo;GOOD GROOMING<br>SHOULD NEVER<br>FEEL RUSHED.&rdquo;
        </blockquote>
        <p style="font-size: clamp(1.02rem, 1.8vw, 1.18rem); color: #BBBBBB; max-width: 580px; margin: 0 auto; line-height: 1.75;">
            Karena hasil yang baik bukan hanya terlihat dari potongannya, tetapi juga terasa dari bagaimana prosesnya.
        </p>
    </div>
</section>

<!-- ==========================================================================
     SECTION 07 — FINAL CTA
     Clean Booking CTA (No redundant location block)
     ========================================================================== -->
<section style="padding: 6.5rem 0 7.5rem; background: #FFFFFF; text-align: center;">
    <div class="container" style="max-width: 680px;">
        <div style="font-size: 0.8rem; font-weight: 800; letter-spacing: 0.18em; text-transform: uppercase; color: #888888; margin-bottom: 0.85rem;">
            YOUR NEXT CUT
        </div>
        <h2 style="font-family: var(--font-display); font-size: clamp(2.2rem, 4vw, 3rem); font-weight: 800; color: #111111; text-transform: uppercase; line-height: 1.15; margin-bottom: 1rem; letter-spacing: 0.02em;">
            READY FOR YOUR NEXT CUT?
        </h2>
        <p style="font-size: 1.05rem; color: #555555; line-height: 1.65; margin: 0 auto 2.5rem; max-width: 520px;">
            Temukan waktu yang paling nyaman untuk kunjungan Anda berikutnya.
        </p>
        <div style="display: flex; gap: 1.5rem; justify-content: center; align-items: center; flex-wrap: wrap;">
            <a href="{{ route('booking.create') }}" class="btn" style="background: #111111; color: #FFFFFF; font-weight: 800; font-size: 0.95rem; padding: 0.95rem 2.5rem; border-radius: 4px; letter-spacing: 0.06em; text-transform: uppercase; box-shadow: 0 4px 18px rgba(0,0,0,0.18);">
                <span>BOOK APPOINTMENT &rarr;</span>
            </a>
            <a href="{{ route('services.index') }}" style="font-family: var(--font-display); font-size: 0.92rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: #111111; text-decoration: none; border-bottom: 2px solid #111111; padding-bottom: 0.35rem; transition: opacity 0.2s ease;">
                <span>VIEW SERVICES &rarr;</span>
            </a>
        </div>
    </div>
</section>

@push('styles')
<style>
@media (max-width: 900px) {
    .about-story-grid,
    .about-way-grid {
        grid-template-columns: 1fr !important;
        gap: 2.75rem !important;
    }
    .process-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 2rem !important;
    }
}

@media (max-width: 768px) {
    .kenapa-row {
        grid-template-columns: 1fr !important;
        border-bottom: none !important;
    }
    .kenapa-col {
        padding: 2.5rem 0 !important;
        border-right: none !important;
        border-bottom: 1px solid var(--border) !important;
    }
    .kenapa-col-right {
        padding: 2.5rem 0 !important;
        border-bottom: 1px solid var(--border) !important;
    }
    .process-grid {
        grid-template-columns: 1fr !important;
        gap: 1.75rem !important;
    }
}
</style>
@endpush
@endsection
