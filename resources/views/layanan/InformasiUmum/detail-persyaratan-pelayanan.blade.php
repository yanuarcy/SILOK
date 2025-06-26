@extends('Template.template')

@push('style')
<link href="{{ Vite::asset('resources/css/style.css') }}" rel="stylesheet">
<style>
.info-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.persyaratan-item:hover {
    transform: translateX(5px);
}
.nav-tabs .nav-link.active {
    background: var(--bs-primary);
    color: white;
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
            <h1 class="display-5 text-white mb-4">{{ $layanan['nama'] }}</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('panduan.index') }}" class="text-white">Panduan Layanan</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">{{ $layanan['nama'] }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Detail Content Start -->
    <div class="container-xxl py-6">
        <div class="container">
            <!-- Header Info -->
            <div class="row g-5 mb-5">
                <div class="col-lg-8">
                    <div class="card layanan-header wow fadeInUp" data-wow-delay="0.1s">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start mb-3">
                                <span class="badge bg-primary bg-opacity-10 px-3 py-2">
                                    <i class="bi bi-tag me-1"></i>{{ $layanan['kategori'] }}
                                </span>
                            </div>
                            <h2 class="card-title text-primary mb-3">{{ $layanan['nama'] }}</h2>
                            <p class="card-text text-muted mb-4">{{ $layanan['deskripsi'] }}</p>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="info-item text-center p-3 border rounded">
                                        <i class="bi bi-clock text-primary fa-2x mb-2"></i>
                                        <h6>Estimasi Waktu</h6>
                                        <span class="text-primary fw-bold">{{ $layanan['estimasi'] }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-item text-center p-3 border rounded">
                                        <i class="bi bi-currency-dollar text-success fa-2x mb-2"></i>
                                        <h6>Biaya</h6>
                                        <span class="text-success fw-bold">{{ $layanan['biaya'] }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-item text-center p-3 border rounded">
                                        <i class="bi bi-geo-alt text-info fa-2x mb-2"></i>
                                        <h6>Lokasi</h6>
                                        <span class="text-info fw-bold">{{ $layanan['lokasi'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card quick-actions wow fadeInUp" data-wow-delay="0.3s">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0"><i class="bi bi-lightning me-2"></i>Aksi Cepat</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                @if($layanan['has_formulir'])
                                <a href="{{ route('panduan.download-formulir', $layanan['slug']) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-download me-2"></i>Download Formulir
                                </a>
                                @endif
                                <a href="{{ route('Contact') }}" class="btn btn-outline-success">
                                    <i class="bi bi-telephone me-2"></i>Hubungi Kami
                                </a>
                                <button class="btn btn-outline-warning" onclick="window.print()">
                                    <i class="bi bi-printer me-2"></i>Cetak Halaman
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="row g-5 mb-4">
                <div class="col-12">
                    <div class="card tabs-card wow fadeInUp" data-wow-delay="0.1s">
                        <div class="card-body p-0">
                            <ul class="nav nav-tabs nav-fill" id="layananTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="persyaratan-tab" data-bs-toggle="tab" data-bs-target="#persyaratan" type="button" role="tab">
                                        <i class="bi bi-list-check me-2"></i>Persyaratan
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="alur-tab" data-bs-toggle="tab" data-bs-target="#alur" type="button" role="tab">
                                        <i class="bi bi-arrow-right-circle me-2"></i>Alur Perizinan
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="unduh-tab" data-bs-toggle="tab" data-bs-target="#unduh" type="button" role="tab">
                                        <i class="bi bi-download me-2"></i>Unduh Dokumen
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="row g-5 mb-5">
                <div class="col-12">
                    <div class="tab-content" id="layananTabsContent">
                        <!-- Persyaratan Tab -->
                        <div class="tab-pane fade show active" id="persyaratan" role="tabpanel">
                            <div class="card wow fadeInUp" data-wow-delay="0.1s">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="card-title mb-0"><i class="bi bi-list-check me-2"></i>Persyaratan yang diperlukan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="persyaratan-list">
                                                @foreach($layanan['persyaratan'] as $index => $persyaratan)
                                                <div class="persyaratan-item d-flex align-items-start mb-3 p-3 border-start border-danger border-3 bg-light rounded">
                                                    <div class="persyaratan-number me-3">
                                                        <span class="badge bg-danger rounded-circle" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                                            {{ $index + 1 }}
                                                        </span>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="persyaratan-name mb-1">{{ $persyaratan }}</h6>
                                                        <span class="badge bg-danger small">Wajib</span>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="alert alert-info">
                                                <h6><i class="bi bi-info-circle me-2"></i>Catatan Penting</h6>
                                                <ul class="mb-0 small">
                                                    <li>Dokumen asli wajib dibawa untuk verifikasi</li>
                                                    <li>Fotokopi dokumen dalam kondisi jelas</li>
                                                    <li>Pas foto terbaru dengan latar putih</li>
                                                    <li>Semua dokumen dalam bahasa Indonesia</li>
                                                </ul>
                                            </div>

                                            <div class="alert alert-warning">
                                                <h6><i class="bi bi-exclamation-triangle me-2"></i>Tips</h6>
                                                <p class="mb-0 small">Siapkan semua dokumen sebelum datang ke kantor untuk mempercepat proses pelayanan.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Alur Perizinan Tab -->
                        <div class="tab-pane fade" id="alur" role="tabpanel">
                            <div class="card wow fadeInUp" data-wow-delay="0.1s">
                                <div class="card-header bg-success text-white">
                                    <h5 class="card-title mb-0"><i class="bi bi-arrow-right-circle me-2"></i>Alur Perizinan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="alur-timeline">
                                        @foreach($layanan['alur_perizinan'] as $index => $alur)
                                        <div class="timeline-item d-flex mb-4">
                                            <div class="timeline-marker me-4">
                                                <div class="step-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 50%; font-weight: bold;">
                                                    {{ $index + 1 }}
                                                </div>
                                                @if(!$loop->last)
                                                <div class="timeline-line bg-success mx-auto mt-2" style="width: 2px; height: 40px;"></div>
                                                @endif
                                            </div>
                                            <div class="timeline-content flex-grow-1">
                                                <div class="card border-0 shadow-sm">
                                                    <div class="card-body p-3">
                                                        <h6 class="card-title text-success mb-2">{{ $alur }}</h6>
                                                        <small class="text-muted">
                                                            <i class="bi bi-clock me-1"></i>Estimasi: 15-30 menit
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>

                                    <div class="alert alert-success mt-4">
                                        <h6><i class="bi bi-check-circle me-2"></i>Informasi Penting</h6>
                                        <p class="mb-0">
                                            Waktu pelayanan dapat bervariasi tergantung kelengkapan dokumen dan antrian.
                                            <strong>{{ $layanan['jam_layanan'] }}</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Unduh Dokumen Tab -->
                        <div class="tab-pane fade" id="unduh" role="tabpanel">
                            <div class="card wow fadeInUp" data-wow-delay="0.1s">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="card-title mb-0"><i class="bi bi-download me-2"></i>Unduh Dokumen</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        @if($layanan['has_formulir'])
                                        <div class="col-lg-4 col-md-6">
                                            <div class="card download-card h-100 border-warning">
                                                <div class="card-body text-center">
                                                    <i class="bi bi-file-pdf text-danger fa-3x mb-3"></i>
                                                    <h6>Formulir Permohonan</h6>
                                                    <p class="text-muted small">{{ $layanan['nama'] }}</p>
                                                    <a href="{{ route('panduan.download-formulir', $layanan['slug']) }}" class="btn btn-warning btn-sm w-100">
                                                        <i class="bi bi-download me-1"></i>Download PDF
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="col-lg-4 col-md-6">
                                            <div class="card download-card h-100 border-info">
                                                <div class="card-body text-center">
                                                    <i class="bi bi-list-check text-primary fa-3x mb-3"></i>
                                                    <h6>Checklist Persyaratan</h6>
                                                    <p class="text-muted small">Daftar lengkap persyaratan</p>
                                                    <button class="btn btn-info btn-sm w-100" onclick="downloadChecklist()">
                                                        <i class="bi bi-download me-1"></i>Download PDF
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-6">
                                            <div class="card download-card h-100 border-success">
                                                <div class="card-body text-center">
                                                    <i class="bi bi-diagram-3 text-success fa-3x mb-3"></i>
                                                    <h6>Flowchart Alur</h6>
                                                    <p class="text-muted small">Diagram alur pelayanan</p>
                                                    <button class="btn btn-success btn-sm w-100" onclick="downloadFlowchart()">
                                                        <i class="bi bi-download me-1"></i>Download PDF
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="alert alert-info mt-4">
                                        <h6><i class="bi bi-info-circle me-2"></i>Informasi Download</h6>
                                        <ul class="mb-0">
                                            <li>Formulir dalam format PDF yang dapat diisi dan dicetak</li>
                                            <li>Checklist persyaratan untuk memastikan kelengkapan dokumen</li>
                                            <li>Flowchart alur untuk memahami proses pelayanan</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Layanan Terkait -->
            @if(count($layanan_terkait) > 0)
            <div class="row g-4 mb-5">
                <div class="col-12">
                    <h4 class="mb-4 wow fadeInUp" data-wow-delay="0.1s">
                        <i class="bi bi-link-45deg me-2"></i>Layanan Terkait
                    </h4>
                </div>
                @foreach($layanan_terkait as $terkait)
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="card layanan-terkait h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h6 class="card-title text-primary mb-2">{{ $terkait['nama'] }}</h6>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $terkait['estimasi'] }}</small>
                                <small class="text-success fw-bold">{{ $terkait['biaya'] }}</small>
                            </div>
                            <a href="{{ route('panduan.detail', $terkait['slug']) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye me-1"></i>Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Contact & Help -->
            <div class="row g-4">
                <div class="col-lg-8 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="card contact-help">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0"><i class="bi bi-question-circle me-2"></i>Butuh Bantuan?</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-3">Jika Anda memiliki pertanyaan atau membutuhkan bantuan terkait layanan ini, jangan ragu untuk menghubungi kami.</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6><i class="bi bi-telephone text-primary me-2"></i>Kontak</h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-1">Telepon: (031) 123-4567</li>
                                        <li class="mb-1">WhatsApp: +62812-3456-7890</li>
                                        <li class="mb-1">Email: layanan@jemurwonosari.surabaya.go.id</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="bi bi-clock text-primary me-2"></i>Jam Layanan</h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-1">{{ $layanan['jam_layanan'] }}</li>
                                        <li class="mb-1">Online: 24/7</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="card quick-actions-bottom">
                        <div class="card-header bg-success text-white">
                            <h5 class="card-title mb-0"><i class="bi bi-lightning me-2"></i>Aksi Cepat</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('panduan.index') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                                </a>
                                <button class="btn btn-outline-info" onclick="shareUrl()">
                                    <i class="bi bi-share me-2"></i>Bagikan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Detail Content End -->

    @include('layouts.newsletter')
    @include('layouts.footer')
</div>

@push('scripts')
<script>
function shareUrl() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $layanan["nama"] }} - Panduan Layanan',
            text: '{{ $layanan["deskripsi"] }}',
            url: window.location.href
        });
    } else {
        navigator.clipboard.writeText(window.location.href).then(function() {
            alert('Link berhasil disalin ke clipboard!');
        });
    }
}

function downloadChecklist() {
    alert('Fitur download checklist akan segera tersedia');
}

function downloadFlowchart() {
    alert('Fitur download flowchart akan segera tersedia');
}
</script>
@endpush

@endsection
