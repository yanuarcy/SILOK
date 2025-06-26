@extends('Template.template')

@vite('resources/sass/app/data-penduduk.scss')

@push('style')
<link href="{{ Vite::asset('resources/css/style.css') }}" rel="stylesheet">
<style>
    /* Enhanced styling untuk dashboard kependudukan */
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        color: white;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        position: relative;
        overflow: hidden;
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
        z-index: 1;
    }

    .stats-card * {
        position: relative;
        z-index: 2;
    }

    .stats-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 25px 50px rgba(102, 126, 234, 0.4);
    }

    .stats-card.primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .stats-card.success {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .stats-card.info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .stats-card.warning {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }

    .stats-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.9;
    }

    .stats-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0.5rem 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .stats-label {
        font-size: 1rem;
        opacity: 0.9;
        margin-bottom: 0.5rem;
    }

    .stats-subtitle {
        font-size: 0.85rem;
        opacity: 0.7;
    }

    /* Demographic cards styling */
    .demographic-card {
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        background: white;
    }

    .demographic-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .demographic-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 1.5rem;
    }

    .demographic-card .card-header h5 {
        margin: 0;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .demographic-item {
        padding: 1rem;
        border-bottom: 1px solid #f8f9fa;
        transition: all 0.3s ease;
    }

    .demographic-item:hover {
        background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 100%);
        padding-left: 1.5rem;
    }

    .demographic-item:last-child {
        border-bottom: none;
    }

    .progress-bar-animated {
        background: linear-gradient(45deg, #667eea, #764ba2);
        animation: progress-animation 2s ease-in-out;
    }

    @keyframes progress-animation {
        0% { width: 0%; }
    }

    /* Info update card */
    .info-update {
        background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(252, 182, 159, 0.3);
    }

    .info-update .card-body {
        padding: 2rem;
    }

    /* Badge customizations */
    .badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 500;
    }

    /* Loading animation */
    .loading-shimmer {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    /* Responsive improvements */
    @media (max-width: 768px) {
        .stats-card {
            padding: 1.5rem;
            margin-bottom: 1rem;
        }

        .stats-number {
            font-size: 2rem;
        }

        .stats-icon {
            font-size: 2.5rem;
        }
    }

    /* Animation delays for staggered effect */
    .stats-card:nth-child(1) { animation-delay: 0.1s; }
    .stats-card:nth-child(2) { animation-delay: 0.2s; }
    .stats-card:nth-child(3) { animation-delay: 0.3s; }
    .stats-card:nth-child(4) { animation-delay: 0.4s; }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.8s ease-out forwards;
        opacity: 0;
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

    <!-- Page Header Start -->
    <div class="container-xxl py-6 bg-primary mb-5">
        <div class="container text-center py-6">
            <h1 class="display-4 text-white mb-4">Data Kependudukan</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('informasi-umum.index') }}" class="text-white">Informasi Umum</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">Data Kependudukan</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Data Kependudukan Content Start -->
    <div class="container-xxl py-6">
        <div class="container">
            <!-- Statistik Utama -->
            <div class="row g-5 mb-5">
                <div class="col-12">
                    <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                        <div class="d-inline-block border rounded-pill text-primary px-4 mb-3">Informasi Penduduk</div>
                        <h2 class="mb-4">Data Kependudukan {{ getOrganizationName() }}</h2>
                        <p class="mb-4">Informasi terkini mengenai data kependudukan dan statistik wilayah yang diperbarui secara berkala</p>
                    </div>
                </div>
            </div>

            <!-- Enhanced Statistics Cards -->
            <div class="row g-4 mb-5">
                <div class="col-lg-3 col-md-6 wow fadeInUp animate-fade-in-up" data-wow-delay="0.1s">
                    <div class="stats-card primary">
                        <div class="stats-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="stats-number" id="totalPenduduk">{{ number_format($statistics['total_penduduk']) }}</div>
                        <div class="stats-label">Total Penduduk</div>
                        <div class="stats-subtitle">Jiwa terdaftar</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow fadeInUp animate-fade-in-up" data-wow-delay="0.2s">
                    <div class="stats-card success">
                        <div class="stats-icon">
                            <i class="bi bi-house-door-fill"></i>
                        </div>
                        <div class="stats-number" id="totalKK">{{ number_format($statistics['total_kk']) }}</div>
                        <div class="stats-label">Kepala Keluarga</div>
                        <div class="stats-subtitle">KK terdaftar</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow fadeInUp animate-fade-in-up" data-wow-delay="0.3s">
                    <div class="stats-card info">
                        <div class="stats-icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div class="stats-number" id="totalRW">{{ $statistics['total_rw'] }}</div>
                        <div class="stats-label">Jumlah RW</div>
                        <div class="stats-subtitle">Rukun Warga</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow fadeInUp animate-fade-in-up" data-wow-delay="0.4s">
                    <div class="stats-card warning">
                        <div class="stats-icon">
                            <i class="bi bi-building"></i>
                        </div>
                        <div class="stats-number" id="totalRT">{{ $statistics['total_rt'] }}</div>
                        <div class="stats-label">Jumlah RT</div>
                        <div class="stats-subtitle">Rukun Tetangga</div>
                    </div>
                </div>
            </div>

            <!-- Demografis Detail -->
            <div class="row g-4 mb-5">
                <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="card demographic-card h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0 text-white"><i class="bi bi-bar-chart me-2"></i>Berdasarkan Usia</h5>
                        </div>
                        <div class="card-body p-0">
                            @foreach($demographic_data['age_groups'] as $age => $count)
                            <div class="demographic-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-person-circle text-primary"></i>
                                    </div>
                                    <span class="fw-medium">{{ $age }}</span>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary">{{ number_format($count) }}</div>
                                    <small class="text-muted">({{ number_format(($count / $statistics['total_penduduk']) * 100, 1) }}%)</small>
                                    <div class="progress mt-1" style="height: 4px; width: 60px;">
                                        <div class="progress-bar progress-bar-animated" style="width: {{ ($count / $statistics['total_penduduk']) * 100 }}%"></div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="card demographic-card h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0 text-white"><i class="bi bi-gender-ambiguous me-2"></i>Berdasarkan Jenis Kelamin</h5>
                        </div>
                        <div class="card-body p-0">
                            @foreach($demographic_data['gender'] as $gender => $count)
                            <div class="demographic-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-{{ $gender == 'Laki-laki' ? 'person-fill' : 'person-fill' }} text-primary"></i>
                                    </div>
                                    <span class="fw-medium">{{ $gender }}</span>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary">{{ number_format($count) }}</div>
                                    <small class="text-muted">({{ number_format(($count / $statistics['total_penduduk']) * 100, 1) }}%)</small>
                                    <div class="progress mt-1" style="height: 4px; width: 60px;">
                                        <div class="progress-bar progress-bar-animated bg-{{ $gender == 'Laki-laki' ? 'info' : 'success' }}" style="width: {{ ($count / $statistics['total_penduduk']) * 100 }}%"></div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="card demographic-card h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0 text-white"><i class="bi bi-mortarboard me-2"></i>Berdasarkan Pendidikan</h5>
                        </div>
                        <div class="card-body p-0">
                            @foreach($demographic_data['education'] as $education => $count)
                            <div class="demographic-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-book text-primary"></i>
                                    </div>
                                    <span class="fw-medium">{{ $education }}</span>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary">{{ number_format($count) }}</div>
                                    <small class="text-muted">({{ number_format(($count / $statistics['total_penduduk']) * 100, 1) }}%)</small>
                                    <div class="progress mt-1" style="height: 4px; width: 60px;">
                                        <div class="progress-bar progress-bar-animated bg-warning" style="width: {{ ($count / $statistics['total_penduduk']) * 100 }}%"></div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Update -->
            <div class="row g-4 mb-5">
                <div class="col-12">
                    <div class="card info-update wow fadeInUp" data-wow-delay="0.1s">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-lg-2 text-center mb-3 mb-lg-0">
                                    <div class="display-6 text-primary">
                                        <i class="bi bi-calendar-check"></i>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <h5 class="card-title text-dark fw-bold">Informasi Update Data</h5>
                                    <p class="card-text mb-2 text-muted">Data kependudukan terakhir diperbarui pada <strong>{{ $statistics['last_updated'] }}</strong></p>
                                    <p class="card-text mb-0 text-muted">Data diupdate secara berkala setiap bulan. Untuk informasi terbaru atau koreksi data, silakan hubungi petugas kelurahan.</p>
                                </div>
                                <div class="col-lg-2 text-lg-end">
                                    <a href="{{ route('Contact') }}" class="btn btn-primary btn-lg">
                                        <i class="bi bi-telephone me-2"></i>Hubungi Kami
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Statistics -->
            <div class="row g-4 mb-5">
                <div class="col-12">
                    <div class="text-center mx-auto mb-4" style="max-width: 600px;">
                        <h3 class="mb-3">Statistik Tambahan</h3>
                        <p class="text-muted">Analisis lebih mendalam mengenai distribusi kependudukan</p>
                    </div>
                </div>

                <div class="col-md-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="card text-center border-0 shadow-sm">
                        <div class="card-body py-4">
                            <div class="display-6 text-info mb-3">
                                <i class="bi bi-calculator"></i>
                            </div>
                            <h5 class="card-title">Rata-rata per KK</h5>
                            <h3 class="text-info" id="rataKK">{{ $statistics['total_kk'] > 0 ? number_format($statistics['total_penduduk'] / $statistics['total_kk'], 1) : 0 }}</h3>
                            <p class="text-muted mb-0">Jiwa per Kepala Keluarga</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="card text-center border-0 shadow-sm">
                        <div class="card-body py-4">
                            <div class="display-6 text-warning mb-3">
                                <i class="bi bi-geo"></i>
                            </div>
                            <h5 class="card-title">Rata-rata per RW</h5>
                            <h3 class="text-warning" id="rataRW">{{ $statistics['total_rw'] > 0 ? number_format($statistics['total_penduduk'] / $statistics['total_rw'], 0) : 0 }}</h3>
                            <p class="text-muted mb-0">Jiwa per RW</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="card text-center border-0 shadow-sm">
                        <div class="card-body py-4">
                            <div class="display-6 text-success mb-3">
                                <i class="bi bi-house"></i>
                            </div>
                            <h5 class="card-title">Rata-rata per RT</h5>
                            <h3 class="text-success" id="rataRT">{{ $statistics['total_rt'] > 0 ? number_format($statistics['total_penduduk'] / $statistics['total_rt'], 0) : 0 }}</h3>
                            <p class="text-muted mb-0">Jiwa per RT</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Data Kependudukan Content End -->

    @include('layouts.newsletter')
    @include('layouts.footer')
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Animate numbers on scroll
    function animateValue(element, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const value = Math.floor(progress * (end - start) + start);
            element.textContent = value.toLocaleString();
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    }

    // Initialize animations when in viewport
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const element = entry.target;
                const endValue = parseInt(element.dataset.value || element.textContent.replace(/[^\d]/g, ''));

                if (endValue > 0) {
                    animateValue(element, 0, endValue, 2000);
                }

                observer.unobserve(element);
            }
        });
    });

    // Observe stat numbers
    document.querySelectorAll('.stats-number').forEach(el => {
        el.dataset.value = el.textContent.replace(/[^\d]/g, '');
        observer.observe(el);
    });

    // Auto refresh data every 5 minutes
    setInterval(function() {
        refreshKependudukanData();
    }, 300000);

    function refreshKependudukanData() {
        $.ajax({
            url: '{{ route("api.kependudukan.statistics") }}',
            type: 'GET',
            success: function(response) {
                if (response.success && response.data) {
                    updateDisplayData(response.data);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error refreshing data:', error);
            }
        });
    }

    function updateDisplayData(data) {
        // Update main statistics
        if (data.statistics) {
            $('#totalPenduduk').text(data.statistics.total_penduduk.toLocaleString());
            $('#totalKK').text(data.statistics.total_kk.toLocaleString());
            $('#totalRW').text(data.statistics.total_rw);
            $('#totalRT').text(data.statistics.total_rt);

            // Update calculated averages
            if (data.statistics.total_kk > 0) {
                $('#rataKK').text((data.statistics.total_penduduk / data.statistics.total_kk).toFixed(1));
            }
            if (data.statistics.total_rw > 0) {
                $('#rataRW').text(Math.round(data.statistics.total_penduduk / data.statistics.total_rw));
            }
            if (data.statistics.total_rt > 0) {
                $('#rataRT').text(Math.round(data.statistics.total_penduduk / data.statistics.total_rt));
            }
        }
    }

    // Trigger fade-in animations
    setTimeout(() => {
        document.querySelectorAll('.animate-fade-in-up').forEach((el, index) => {
            setTimeout(() => {
                el.style.opacity = '1';
            }, index * 200);
        });
    }, 100);
});
</script>
@endpush

@endsection
