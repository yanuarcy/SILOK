@extends('Template.template')

@vite('resources/sass/app/about.scss')

@push('style')

<link href="{{ Vite::asset('resources/css/style.css') }}" rel="stylesheet">

@endpush

@section('Content')
<div class="container-xxl bg-white p-0">
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    @include('layouts.nav')

    <!-- About Start -->
    <div class="container-xxl py-6">
        <div class="container mt-5">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow zoomIn" data-wow-delay="0.1s">
                    <img class="img-fluid" src="{{ Vite::asset('resources/images/img/about.png') }}">
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="d-inline-block border rounded-pill text-primary px-4 mb-3">Tentang Kami</div>
                    <h2 class="mb-4">Sistem Informasi Layanan Online Kelurahan (SILOK)</h2>
                    <p class="mb-4">SILOK adalah sistem layanan inovatif yang dirancang untuk mempermudah akses informasi dan layanan administrasi kependudukan secara online di tingkat kelurahan. Kami berkomitmen untuk mewujudkan pelayanan publik yang efisien, efektif, dan gratis bagi seluruh warga.</p>
                    <div class="row g-3 mb-4">
                        <div class="col-12 d-flex">
                            <div class="flex-shrink-0 btn-lg-square rounded-circle bg-primary">
                                <i class="fas fa-info-circle text-white"></i>
                            </div>
                            <div class="ms-4">
                                <h6>Informasi Terintegrasi</h6>
                                <span>Akses cepat ke informasi layanan dan kegiatan RT, RW, dan LPMK.</span>
                            </div>
                        </div>
                        <div class="col-12 d-flex">
                            <div class="flex-shrink-0 btn-lg-square rounded-circle bg-primary">
                                <i class="fas fa-file-alt text-white"></i>
                            </div>
                            <div class="ms-4">
                                <h6>Administrasi Kependudukan Online</h6>
                                <span>Pengurusan dokumen kependudukan secara efektif dan tanpa biaya.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="silok-info">
        <div class="container py-5">
            <header class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">
                <h1 class="display-4 fw-bold text-primary mb-3">Mengenal Lebih Dekat SILOK</h1>
                <p class="lead text-secondary mx-auto" style="max-width: 700px;">
                    SILOK (Sistem Informasi Layanan Online Kelurahan) adalah inovasi dalam pelayanan publik untuk meningkatkan efisiensi dan efektivitas administrasi kependudukan di tingkat kelurahan.
                </p>
            </header>

            <section class="features mb-5">
                <h2 class="text-center mb-4 wow fadeInUp" data-wow-delay="0.1s">Fitur Utama SILOK</h2>
                <div class="row g-4">
                    @foreach ([
                        ['icon' => 'bi-info-circle', 'title' => 'Informasi Terpadu', 'description' => 'Akses cepat ke informasi layanan dan kegiatan RT, RW, dan LPMK dalam satu platform.'],
                        ['icon' => 'bi-file-text', 'title' => 'Administrasi Online', 'description' => 'Pengurusan dokumen kependudukan secara online, menghemat waktu dan tenaga.'],
                        ['icon' => 'bi bi-cash', 'title' => 'Layanan Gratis', 'description' => 'Semua layanan SILOK dapat diakses tanpa biaya, mewujudkan pelayanan publik yang terjangkau.'],
                        ['icon' => 'bi-clock', 'title' => 'Akses 24/7', 'description' => 'Layanan dapat diakses kapan saja, memberikan fleksibilitas bagi warga.'],
                    ] as $index => $feature)
                        <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="{{ 0.1 + ($index % 4) * 0.2 }}s">
                            <div class="card h-100 feature-card">
                                <div class="card-body text-center">
                                    <i class="bi {{ $feature['icon'] }} feature-icon"></i>
                                    <h3 class="card-title mt-3">{{ $feature['title'] }}</h3>
                                    <p class="card-text">{{ $feature['description'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="how-it-works mb-5">
                <h2 class="text-center mb-4 wow fadeInUp" data-wow-delay="0.1s">Cara Kerja SILOK</h2>
                <div class="accordion" id="accordionHowItWorks">
                    @foreach ([
                        'Pendaftaran: Warga mendaftar akun SILOK secara online atau melalui kantor kelurahan.',
                        'Verifikasi: Data pengguna diverifikasi untuk memastikan keamanan dan keabsahan.',
                        'Akses Layanan: Pengguna dapat mengakses berbagai layanan administrasi kependudukan.',
                        'Pengajuan Dokumen: Warga dapat mengajukan permohonan dokumen secara online.',
                        'Pemrosesan: Petugas kelurahan memproses pengajuan secara digital.',
                        'Pemberitahuan: Warga menerima notifikasi status pengajuan mereka.',
                        'Pengambilan Dokumen: Dokumen siap diambil di kantor kelurahan atau dikirim secara digital.'
                    ] as $index => $step)
                        <div class="accordion-item wow fadeInUp" data-wow-delay="0.1s">
                            <h2 class="accordion-header" id="heading{{ $index }}">
                                <button class="accordion-button @if($index !== 0) collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">
                                    Langkah {{ $index + 1 }}
                                </button>
                            </h2>
                            <div id="collapse{{ $index }}" class="accordion-collapse collapse @if($index === 0) show @endif" aria-labelledby="heading{{ $index }}" data-bs-parent="#accordionHowItWorks">
                                <div class="accordion-body">
                                    {{ $step }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="benefits mb-5">
                <h2 class="text-center mb-4 wow fadeInUp" data-wow-delay="0.1s">Manfaat SILOK</h2>
                <div class="row g-4">
                    @foreach ([
                        ['icon' => 'bi-graph-up', 'text' => 'Efisiensi waktu dan biaya'],
                        ['icon' => 'bi-check-circle', 'text' => 'Peningkatan akurasi data'],
                        ['icon' => 'bi-shield-check', 'text' => 'Transparansi proses'],
                        ['icon' => 'bi-info-circle', 'text' => 'Kemudahan akses informasi'],
                        ['icon' => 'bi-building', 'text' => 'Pengurangan beban administratif'],
                        ['icon' => 'bi-star', 'text' => 'Peningkatan kualitas layanan'],
                    ] as $index => $benefit)
                        <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="{{ 0.1 + ($index % 4) * 0.2 }}s">
                            <div class="card benefit-card">
                                <div class="card-body">
                                    <i class="bi {{ $benefit['icon'] }} benefit-icon"></i>
                                    <span>{{ $benefit['text'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>

    <!-- About End -->

    @include('layouts.newsletter')

    @include('layouts.footer')

</div>

@endsection
