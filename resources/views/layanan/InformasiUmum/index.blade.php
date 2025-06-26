@extends('Template.template')

{{-- @vite('resources/sass/app/informasi-umum.scss') --}}

@push('style')
    <link href="{{ Vite::asset('resources/css/style.css') }}" rel="stylesheet">
    <style>

        .feature-icon {
            transition: transform 0.3s ease;
        }

        .card:hover .feature-icon {
            transform: scale(1.1);
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
        }
        .meeting-section {
            background: #f8f9fa;
            padding: 60px 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-badge {
            display: inline-block;
            background: rgba(32, 201, 151, 0.1);
            color: #20c997;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 15px;
        }

        .main-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .main-description {
            font-size: 1.1rem;
            color: #6c757d;
            max-width: 600px;
            margin: 0 auto;
        }

        .content-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: start;
            margin-bottom: 50px;
        }

        @media (max-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }
        }

        .schedule-section h4,
        .features-section h4 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 25px;
        }

        .schedule-table {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .schedule-table table {
            width: 100%;
            margin: 0;
        }

        .schedule-table thead {
            background: #20c997;
        }

        .schedule-table thead th {
            color: white;
            font-weight: 600;
            padding: 15px;
            border: none;
        }

        .schedule-table tbody td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }

        .schedule-table tbody tr:last-child td {
            border-bottom: none;
        }

        .schedule-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-badge.aktif {
            background-color: #d4edda;
            color: #155724;
        }

        .status-badge.terjadwal {
            background-color: #cce7ff;
            color: #004085;
        }

        .status-badge.pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .features-list {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px 0;
        }

        .feature-item:last-child {
            margin-bottom: 0;
        }

        .feature-icon {
            width: 24px;
            height: 24px;
            margin-right: 15px;
            color: #20c997;
            font-size: 1.2rem;
        }

        .feature-text {
            font-size: 1rem;
            color: #495057;
            font-weight: 500;
        }

        .access-button {
            text-align: center;
        }

        .btn-access {
            background: #20c997;
            color: white;
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border: none;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(32, 201, 151, 0.3);
        }

        .btn-access:hover {
            background: #1ea085;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(32, 201, 151, 0.4);
            color: white;
        }

        .btn-access i {
            font-size: 1.2rem;
        }
    </style>
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

    <!-- Page Header Start - Simple like Contact page -->
    {{-- <div class="container-xxl py-6 bg-primary mb-5">
        <div class="container text-center py-6">
            <h1 class="display-4 text-white mb-4">Informasi Umum</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Home</a></li>
                    <li class="breadcrumb-item"><a href="#" class="text-white">Layanan</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">Informasi Umum</li>
                </ol>
            </nav>
        </div>
    </div> --}}
    <!-- Page Header End -->

    <!-- About Informasi Umum Start -->
    <div class="container-xxl py-6">
        <div class="container mt-5">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow zoomIn" data-wow-delay="0.1s">
                    <img class="img-fluid" src="{{ Vite::asset('resources/images/img/informasi-umum.png') }}">
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="d-inline-block border rounded-pill text-primary px-4 mb-3">Layanan Informasi</div>
                    <h2 class="mb-4">Kami Menyediakan Layanan Informasi Kelurahan</h2>
                    <p class="mb-4">Platform terintegrasi untuk mengakses berbagai informasi penting {{ getOrganizationName() }}. Kami menyediakan akses mudah ke data kependudukan, koordinasi meeting online, dan persyaratan layanan administrasi untuk kemudahan warga.</p>
                    <div class="row g-3 mb-4">
                        <div class="col-12 d-flex">
                            <div class="flex-shrink-0 btn-lg-square rounded-circle bg-primary">
                                <i class="bi bi-people-fill text-white"></i>
                            </div>
                            <div class="ms-4">
                                <h6>Data Kependudukan</h6>
                                <span>Informasi statistik penduduk terkini dan akurat.</span>
                            </div>
                        </div>
                        <div class="col-12 d-flex">
                            <div class="flex-shrink-0 btn-lg-square rounded-circle bg-primary">
                                <i class="bi bi-camera-video text-white"></i>
                            </div>
                            <div class="ms-4">
                                <h6>Koordinasi Meeting Online</h6>
                                <span>Platform rapat virtual untuk pengurus RW/RT.</span>
                            </div>
                        </div>
                        <div class="col-12 d-flex">
                            <div class="flex-shrink-0 btn-lg-square rounded-circle bg-primary">
                                <i class="bi bi-file-earmark-check text-white"></i>
                            </div>
                            <div class="ms-4">
                                <h6>Persyaratan Layanan</h6>
                                <span>Panduan lengkap untuk berbagai layanan administrasi.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About Informasi Umum End -->

    <!-- Data Kependudukan Section Start -->
    <div class="container-xxl py-6">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <div class="d-inline-block border rounded-pill text-primary px-4 mb-3">Data Kependudukan</div>
                <h2 class="mb-4">Statistik Penduduk Kelurahan Jemur Wonosari</h2>
                <p class="mb-4">Informasi terkini mengenai data kependudukan dan statistik wilayah yang diupdate secara berkala</p>
                <small class="text-muted">
                    <i class="bi bi-calendar3 me-1"></i>
                    Terakhir diperbarui: <strong>{{ $statistics['last_updated'] }}</strong>
                    <span class="mx-2">•</span>
                    Periode: <strong>{{ $statistics['periode'] }}</strong>
                </small>
            </div>

            <!-- Main Statistics Cards -->
            <div class="row g-4 mb-5">
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="card text-center h-100 border-0 shadow-lg">
                        <div class="card-body p-4">
                            <div class="feature-icon mb-3">
                                <i class="bi bi-people-fill text-primary" style="font-size: 3rem;"></i>
                            </div>
                            <h3 class="text-primary mb-2">{{ number_format($statistics['total_penduduk']) }}</h3>
                            <h6 class="text-dark mb-2">Total Penduduk</h6>
                            <p class="text-muted mb-0">Jumlah penduduk terdaftar</p>
                            <div class="mt-3">
                                <small class="badge bg-primary bg-opacity-10">
                                    <i class="bi bi-trending-up me-1"></i>
                                    Aktif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="card text-center h-100 border-0 shadow-lg">
                        <div class="card-body p-4">
                            <div class="feature-icon mb-3">
                                <i class="bi bi-house-door-fill text-primary" style="font-size: 3rem;"></i>
                            </div>
                            <h3 class="text-primary mb-2">{{ number_format($statistics['total_kk']) }}</h3>
                            <h6 class="text-dark mb-2">Kepala Keluarga</h6>
                            <p class="text-muted mb-0">Total KK terdaftar</p>
                            <div class="mt-3">
                                <small class="badge bg-success bg-opacity-10">
                                    <i class="bi bi-calculator me-1"></i>
                                    ~{{ $statistics['rata_rata_per_kk'] }} jiwa/KK
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="card text-center h-100 border-0 shadow-lg">
                        <div class="card-body p-4">
                            <div class="feature-icon mb-3">
                                <i class="bi bi-geo-alt-fill text-primary" style="font-size: 3rem;"></i>
                            </div>
                            <h3 class="text-primary mb-2">{{ $statistics['total_rw'] }}</h3>
                            <h6 class="text-dark mb-2">Jumlah RW</h6>
                            <p class="text-muted mb-0">Rukun Warga aktif</p>
                            <div class="mt-3">
                                <small class="badge bg-info bg-opacity-10">
                                    <i class="bi bi-people me-1"></i>
                                    ~{{ number_format($statistics['rata_rata_per_rw']) }} jiwa/RW
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.7s">
                    <div class="card text-center h-100 border-0 shadow-lg">
                        <div class="card-body p-4">
                            <div class="feature-icon mb-3">
                                <i class="bi bi-building text-primary" style="font-size: 3rem;"></i>
                            </div>
                            <h3 class="text-primary mb-2">{{ $statistics['total_rt'] }}</h3>
                            <h6 class="text-dark mb-2">Jumlah RT</h6>
                            <p class="text-muted mb-0">Rukun Tetangga aktif</p>
                            <div class="mt-3">
                                <small class="badge bg-warning bg-opacity-10">
                                    <i class="bi bi-house me-1"></i>
                                    ~{{ number_format($statistics['rata_rata_per_rt']) }} jiwa/RT
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Statistics -->
            <div class="row g-4 mb-5">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-graph-up me-2"></i>
                                Highlights Demografi
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <div class="border-end h-100 d-flex flex-column justify-content-center">
                                        <h5 class="text-primary mb-1">{{ $statistics['dominant_age_group'] }}</h5>
                                        <small class="text-muted">Kelompok Usia Dominan</small>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <div class="border-end h-100 d-flex flex-column justify-content-center">
                                        <h5 class="text-success mb-1">{{ $statistics['gender_ratio'] }}</h5>
                                        <small class="text-muted">Rasio Laki-laki/Perempuan</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="h-100 d-flex flex-column justify-content-center">
                                        <h5 class="text-info mb-1">{{ $statistics['education_rate'] }}%</h5>
                                        <small class="text-muted">Tingkat Pendidikan Tinggi (SMA+)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="feature-icon">
                                    <i class="bi bi-bar-chart-fill text-primary" style="font-size: 2.5rem;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Detail Lengkap</h6>
                                <p class="text-muted mb-2">Lihat breakdown lengkap data kependudukan berdasarkan usia, jenis kelamin, dan pendidikan</p>
                                <a href="{{ route('informasi-umum.data-kependudukan') }}" class="btn btn-outline-primary btn-sm">
                                    Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="feature-icon">
                                    <i class="bi bi-download text-success" style="font-size: 2.5rem;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Data Terbaru</h6>
                                <p class="text-muted mb-2">Data diperbarui secara berkala untuk memastikan akurasi informasi statistik</p>
                                <button onclick="refreshStatistics()" class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Refresh Data
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <div class="card border-primary border-2 bg-primary bg-opacity-5">
                    <div class="card-body py-5">
                        <h4 class="text-primary mb-3">Butuh Informasi Lebih Detail?</h4>
                        <p class="text-white mb-4">Lihat breakdown lengkap data demografis dan statistik wilayah Kelurahan Jemur Wonosari</p>
                        <a href="{{ route('informasi-umum.data-kependudukan') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-arrow-right me-2"></i>Lihat Detail Data Kependudukan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Data Kependudukan Section End -->

    <!-- Meeting Content Start -->
    <div class="meeting-section">
        <div class="content-wrapper">
            <!-- Section Title -->
            <div class="section-title wow fadeInUp" data-wow-delay="0.1s">
                <div class="section-badge">Zoom Meeting</div>
                <h2 class="main-title">Rapat Online RW/RT</h2>
                <p class="main-description">
                    Platform koordinasi online untuk pengurus RW/RT dengan menggunakan Google Meet untuk komunikasi yang efektif
                </p>
            </div>

            <!-- Main Content Grid -->
            <div class="content-grid">
                <!-- Jadwal Rapat Rutin -->
                <div class="schedule-section wow fadeInUp" data-wow-delay="0.1s">
                    <h4>Jadwal Rapat Rutin</h4>
                    <div class="schedule-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Jenis Rapat</th>
                                    <th>Jadwal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($meeting_schedule as $schedule)
                                <tr>
                                    <td>{{ $schedule['title'] }}</td>
                                    <td>{{ $schedule['schedule'] }}</td>
                                    <td>
                                        <span class="status-badge {{ strtolower($schedule['status']) }}">
                                            {{ $schedule['status'] }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Fitur Meeting Online -->
                <div class="features-section wow fadeInUp" data-wow-delay="0.3s">
                    <h4>Fitur Meeting Online</h4>
                    <div class="features-list">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div class="feature-text">Akses mudah dengan Google Meet</div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div class="feature-text">Jadwal rapat yang terorganisir</div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div class="feature-text">Khusus untuk pengurus RW/RT</div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div class="feature-text">Koordinasi yang efektif</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Button Akses Meeting Detail -->
            <div class="access-button wow fadeInUp" data-wow-delay="0.5s">
                <a href="{{ route('meeting.detail') }}" class="btn-access">
                    <i class="bi bi-camera-video"></i>
                    Akses Meeting Detail
                </a>
            </div>
        </div>
    </div>
    <!-- Meeting Content End -->

    <!-- Panduan Layanan Section Start -->
    <div class="container-xxl py-6">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 700px;">
                <div class="d-inline-block border rounded-pill text-primary px-4 mb-3">Panduan Layanan</div>
                <h2 class="mb-4">Panduan Lengkap Layanan Administrasi Kependudukan</h2>
                <p class="mb-4">Temukan informasi lengkap mengenai persyaratan, prosedur, dan formulir untuk berbagai layanan administrasi kependudukan</p>
            </div>

            <!-- Kategori Layanan -->
            <div class="row g-4 mb-5">
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="card service-category h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="service-icon mb-4">
                                <div class="icon-circle bg-primary bg-gradient text-white mx-auto" style="width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-file-earmark-person fa-2x"></i>
                                </div>
                            </div>
                            <h5 class="card-title text-primary">Pencatatan Sipil</h5>
                            <p class="card-text text-muted">Akta kelahiran, akta kematian, dan pencatatan peristiwa penting lainnya</p>
                            <div class="mt-3">
                                <small class="text-primary fw-bold">4 Layanan Tersedia</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="card service-category h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="service-icon mb-4">
                                <div class="icon-circle bg-success bg-gradient text-white mx-auto" style="width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-file-earmark-person-fill fa-2x"></i>
                                </div>
                            </div>
                            <h5 class="card-title text-success">Pendaftaran Penduduk</h5>
                            <p class="card-text text-muted">KTP, KK, KIA dan layanan pendaftaran penduduk lainnya</p>
                            <div class="mt-3">
                                <small class="text-success fw-bold">7 Layanan Tersedia</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="card service-category h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="service-icon mb-4">
                                <div class="icon-circle bg-warning bg-gradient text-white mx-auto" style="width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-file-text fa-2x"></i>
                                </div>
                            </div>
                            <h5 class="card-title text-warning">Surat Keterangan</h5>
                            <p class="card-text text-muted">SKAW, SKT, dan berbagai surat keterangan lainnya</p>
                            <div class="mt-3">
                                <small class="text-warning fw-bold">2 Layanan Tersedia</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Layanan Populer -->
            <div class="row g-4 mb-5">
                <div class="col-12">
                    <h4 class="text-center mb-4 wow fadeInUp" data-wow-delay="0.1s">Layanan Populer</h4>
                </div>

                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="card popular-service border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="service-number me-3">
                                    <span class="badge bg-primary rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">1</span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="card-title text-primary mb-2">KTP (Kartu Tanda Penduduk)</h6>
                                    <p class="card-text text-muted mb-2">Pengurusan KTP baru, perpanjangan, dan perubahan data</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted"><i class="bi bi-clock me-1"></i>14 hari kerja</small>
                                        <small class="text-success fw-bold">Gratis</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="card popular-service border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="service-number me-3">
                                    <span class="badge bg-success rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">2</span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="card-title text-success mb-2">KK (Kartu Keluarga)</h6>
                                    <p class="card-text text-muted mb-2">Pengurusan KK baru, perubahan data, dan cetak ulang</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted"><i class="bi bi-clock me-1"></i>7-14 hari kerja</small>
                                        <small class="text-success fw-bold">Gratis</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="card popular-service border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="service-number me-3">
                                    <span class="badge bg-info rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">3</span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="card-title text-info mb-2">Akta Kelahiran</h6>
                                    <p class="card-text text-muted mb-2">Pengurusan akta kelahiran baru dan duplikat</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted"><i class="bi bi-clock me-1"></i>7-14 hari kerja</small>
                                        <small class="text-success fw-bold">Gratis</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.4s">
                    <div class="card popular-service border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="service-number me-3">
                                    <span class="badge bg-warning rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">4</span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="card-title text-warning mb-2">SKAW (Surat Keterangan Ahli Waris)</h6>
                                    <p class="card-text text-muted mb-2">Surat keterangan untuk keperluan waris</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted"><i class="bi bi-clock me-1"></i>1-3 hari kerja</small>
                                        <small class="text-success fw-bold">Gratis</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Search -->
            <div class="row g-4 mb-5">
                <div class="col-12">
                    <div class="card search-card border-0 shadow-sm wow fadeInUp" data-wow-delay="0.1s">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <h5 class="card-title">Cari Layanan</h5>
                                <p class="card-text text-muted">Temukan layanan yang Anda butuhkan dengan cepat</p>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                        <input type="text" class="form-control" placeholder="Cari layanan (contoh: KTP, Akta Kelahiran, SKAW)..." id="searchLayanan">
                                        <button class="btn btn-primary" type="button" onclick="searchLayanan()">Cari</button>
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted">Pencarian populer:
                                            <a href="javascript:void(0)" class="text-primary me-2" onclick="quickSearch('KTP')">KTP</a>
                                            <a href="javascript:void(0)" class="text-primary me-2" onclick="quickSearch('KK')">KK</a>
                                            <a href="javascript:void(0)" class="text-primary me-2" onclick="quickSearch('Akta')">Akta Kelahiran</a>
                                            <a href="javascript:void(0)" class="text-primary" onclick="quickSearch('SKAW')">SKAW</a>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="row g-4">
                <div class="col-12">
                    <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                        <a href="{{ route('panduan.index') }}" class="btn btn-primary btn-lg px-5 py-3 me-3">
                            <i class="bi bi-book me-2"></i>Lihat Semua Panduan
                        </a>
                        <a href="{{ route('Contact') }}" class="btn btn-outline-primary btn-lg px-5 py-3">
                            <i class="bi bi-telephone me-2"></i>Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>

            <!-- Informasi Penting -->
            <div class="row g-4 mt-4">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="alert alert-info border-0 shadow-sm">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-info-circle fa-2x text-info me-3 mt-1"></i>
                            <div>
                                <h6 class="alert-heading">Informasi Penting</h6>
                                <p class="mb-0">Pastikan semua dokumen sudah lengkap sebelum mengajukan permohonan. Bawa dokumen asli untuk verifikasi.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="alert alert-success border-0 shadow-sm">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-clock fa-2x text-success me-3 mt-1"></i>
                            <div>
                                <h6 class="alert-heading">Jam Pelayanan</h6>
                                <p class="mb-0">Senin-Kamis: 08:00-15:00 WIB<br>Jumat: 08:00-11:30 WIB<br>Layanan online 24/7</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.newsletter')
    @include('layouts.footer')
</div>

@endsection

@push('scripts')
    <script>
        function searchLayanan() {
            const query = document.getElementById('searchLayanan').value;
            if (query.trim()) {
                window.location.href = `/panduan?search=${encodeURIComponent(query)}`;
            } else {
                window.location.href = '/panduan';
            }
        }

        function quickSearch(term) {
            document.getElementById('searchLayanan').value = term;
            searchLayanan();
        }

        // Enter key search
        document.getElementById('searchLayanan').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchLayanan();
            }
        });

        // Function to refresh statistics
        function refreshStatistics() {
            // Show loading state
            const btn = event.target;
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="spinner-border spinner-border-sm me-1"></i> Loading...';
            btn.disabled = true;

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Make AJAX call to refresh data
            fetch('{{ route("api.informasi-umum.refresh-statistics") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Show success message
                    showToast('Data berhasil diperbarui!', 'success');

                    // Update the display without full page reload
                    if (data.data) {
                        updateStatisticsDisplay(data.data);
                    } else {
                        // If no data returned, reload page
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                } else {
                    showToast(data.message || 'Gagal memperbarui data', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat memperbarui data: ' + error.message, 'error');
            })
            .finally(() => {
                // Restore button state
                btn.innerHTML = originalHTML;
                btn.disabled = false;
            });
        }

        // Simple toast notification
        function showToast(message, type = 'info') {
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
            toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            toast.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            // Add to body
            document.body.appendChild(toast);

            // Auto remove after 3 seconds
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 3000);
        }

        // Auto refresh data every 5 minutes (optional)
        setInterval(() => {
            fetch('{{ route("api.informasi-umum.statistics") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Data refreshed automatically');
                        // Optionally update specific elements without full page reload
                        updateStatisticsDisplay(data.data);
                    }
                })
                .catch(error => console.log('Auto refresh failed:', error));
        }, 300000); // 5 minutes

        // Function to update statistics display without page reload
        function updateStatisticsDisplay(statistics) {
            // Update main numbers
            const elements = {
                'total_penduduk': statistics.total_penduduk,
                'total_kk': statistics.total_kk,
                'total_rw': statistics.total_rw,
                'total_rt': statistics.total_rt
            };

            Object.keys(elements).forEach(key => {
                const element = document.querySelector(`[data-stat="${key}"]`);
                if (element) {
                    element.textContent = elements[key].toLocaleString();
                }
            });
        }
    </script>
@endpush
