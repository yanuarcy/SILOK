@extends('Template.template')

@push('style')
<link href="{{ Vite::asset('resources/css/style.css') }}" rel="stylesheet">
<style>
.service-category:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,123,255,0.1);
}
.layanan-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
.list-group-item:hover {
    background-color: rgba(0,123,255,0.05);
    border-left: 4px solid var(--bs-primary);
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
            <h1 class="display-4 text-white mb-4">Panduan Layanan Administrasi Kependudukan</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('informasi-umum.index') }}" class="text-white">Informasi Umum</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">Panduan Layanan</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Panduan Content Start -->
    <div class="container-xxl py-6">
        <div class="container">
            <!-- Introduction -->
            <div class="row g-5 mb-5">
                <div class="col-12">
                    <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 800px;">
                        <div class="d-inline-block border rounded-pill text-primary px-4 mb-3">Panduan Lengkap</div>
                        <h2 class="mb-4">Daftar persyaratan dokumen untuk berbagai layanan administrasi kependudukan yang tersedia di Kelurahan Jemur Wonosari</h2>
                        <p class="mb-4">Pastikan dokumen lengkap sebelum mengajukan permohonan</p>
                    </div>
                </div>
            </div>

            <!-- Search & Filter -->
            <div class="row g-4 mb-5">
                <div class="col-12">
                    <div class="card search-filter wow fadeInUp" data-wow-delay="0.1s">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                        <input type="text" class="form-control" placeholder="Cari layanan..." id="searchInput" value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <select class="form-select" id="kategoriFilter">
                                        <option value="">Semua Kategori</option>
                                        @foreach($kategori_layanan as $kategori)
                                            <option value="{{ $kategori['slug'] }}">{{ $kategori['nama'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <button class="btn btn-primary w-100" type="button" onclick="performSearch()">Cari</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kategori Layanan -->
            <div class="row g-4 mb-5">
                <div class="col-12">
                    <div class="card categories-card wow fadeInUp" data-wow-delay="0.1s">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0"><i class="bi bi-grid-3x3-gap me-2"></i>Kategori Layanan</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="row g-0">
                                @foreach($kategori_layanan as $kategori)
                                <div class="col-lg-4 col-md-6">
                                    <div class="category-link d-block p-4 text-decoration-none border-end border-bottom">
                                        <div class="d-flex align-items-center">
                                            <div class="category-icon me-3">
                                                <div class="icon-circle" style="background-color: {{ $kategori['color'] }}20; color: {{ $kategori['color'] }}; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                    <i class="{{ $kategori['icon'] }} fa-lg"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="category-name mb-1" style="color: {{ $kategori['color'] }}">{{ $kategori['nama'] }}</h6>
                                                <small class="text-muted">{{ $kategori['total_layanan'] }} layanan</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Layanan Populer -->
            <div class="row g-4 mb-5">
                <div class="col-12">
                    <h4 class="mb-4 wow fadeInUp" data-wow-delay="0.1s">
                        <i class="bi bi-star-fill text-warning me-2"></i>Layanan Populer
                    </h4>
                </div>
                @foreach($layanan_populer as $index => $layanan)
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="{{ 0.1 + ($index * 0.1) }}s">
                    <div class="card layanan-card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="layanan-number me-3">
                                    <span class="badge bg-primary rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 16px;">{{ $index + 1 }}</span>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="card-title text-primary mb-2">{{ $layanan['nama'] }}</h5>
                                    <span class="badge bg-light text-dark mb-2">{{ $layanan['kategori'] }}</span>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $layanan['estimasi'] }}</small>
                                        <small class="text-success fw-bold">{{ $layanan['biaya'] }}</small>
                                    </div>
                                    <a href="{{ route('panduan.detail', $layanan['slug']) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye me-1"></i>Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Semua Layanan A-Z -->
            <div class="row g-4 mb-5">
                <div class="col-12">
                    <h4 class="mb-4 wow fadeInUp" data-wow-delay="0.1s">
                        <i class="bi bi-list-ul me-2"></i>Semua Layanan (A-Z)
                    </h4>
                </div>
                <div class="col-12">
                    <div class="card services-list wow fadeInUp" data-wow-delay="0.1s">
                        <div class="card-header bg-light">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="card-title mb-0">Daftar Layanan</h6>
                                </div>
                                <div class="col-auto">
                                    <small class="text-muted">Total: {{ $total_layanan }} layanan</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush" id="layananList">
                                <!-- Content akan dimuat dari JavaScript -->
                                <div class="text-center p-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Download Formulir -->
            <div class="row g-4 mb-5">
                <div class="col-12">
                    <div class="card download-forms wow fadeInUp" data-wow-delay="0.1s">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title mb-0"><i class="bi bi-download me-2"></i>Download Formulir</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-4">Download formulir permohonan untuk mempercepat proses pelayanan di kantor kelurahan</p>
                            <div class="row">
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <a href="{{ route('panduan.download-formulir', 'ktp-kartu-tanda-penduduk') }}" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-file-pdf me-2"></i>Form KTP
                                    </a>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <a href="{{ route('panduan.download-formulir', 'kk-kartu-keluarga') }}" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-file-pdf me-2"></i>Form KK
                                    </a>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <a href="{{ route('panduan.download-formulir', 'kia-kartu-identitas-anak') }}" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-file-pdf me-2"></i>Form KIA
                                    </a>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <a href="{{ route('panduan.download-formulir', 'skaw-surat-keterangan-ahli-waris') }}" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-file-pdf me-2"></i>Form SKAW
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Umum -->
            <div class="row g-4">
                <div class="col-lg-8 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="card info-card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0"><i class="bi bi-info-circle me-2"></i>Informasi Penting</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6><i class="bi bi-clock text-primary me-2"></i>Jam Pelayanan:</h6>
                                    <ul class="list-unstyled mb-3">
                                        <li class="mb-1">Senin - Kamis: 08:00 - 15:00 WIB</li>
                                        <li class="mb-1">Jumat: 08:00 - 11:30 WIB</li>
                                        <li class="mb-1">Sabtu: Tutup</li>
                                        <li class="mb-1">Minggu & Hari Libur: Tutup</li>
                                        <li class="mb-1 text-primary">Layanan online 24/7</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="bi bi-exclamation-triangle text-warning me-2"></i>Catatan:</h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-1">Bawa dokumen asli untuk verifikasi</li>
                                        <li class="mb-1">Pastikan data sesuai dokumen</li>
                                        <li class="mb-1">Datang tepat waktu</li>
                                        <li class="mb-1">Pelayanan dihentikan 30 menit sebelum tutup</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="card contact-card">
                        <div class="card-header bg-success text-white">
                            <h5 class="card-title mb-0"><i class="bi bi-telephone me-2"></i>Butuh Bantuan?</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-3">Hubungi kami untuk informasi lebih lanjut</p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('Contact') }}" class="btn btn-primary">
                                    <i class="bi bi-telephone me-2"></i>Hubungi Kami
                                </a>
                                <a href="#" class="btn btn-success">
                                    <i class="bi bi-whatsapp me-2"></i>WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Panduan Content End -->

    @include('layouts.newsletter')
    @include('layouts.footer')
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadSemuaLayanan();

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const kategoriFilter = document.getElementById('kategoriFilter');

    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });

    kategoriFilter.addEventListener('change', performSearch);
});

function performSearch() {
    const query = document.getElementById('searchInput').value;
    const kategori = document.getElementById('kategoriFilter').value;

    // Show loading
    document.getElementById('layananList').innerHTML = '<div class="text-center p-4"><div class="spinner-border text-primary" role="status"></div></div>';

    // Perform search via API
    const url = `/api/panduan/search?q=${encodeURIComponent(query)}&kategori=${encodeURIComponent(kategori)}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            displaySearchResults(data.data);
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('layananList').innerHTML = '<div class="text-center p-4 text-danger">Terjadi kesalahan saat mencari</div>';
        });
}

function loadSemuaLayanan() {
    fetch('/api/panduan/layanan')
        .then(response => response.json())
        .then(data => {
            displayLayananList(data.data);
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('layananList').innerHTML = '<div class="text-center p-4 text-danger">Gagal memuat data</div>';
        });
}

function displayLayananList(layanan) {
    const html = layanan.map(item => `
        <a href="/panduan/detail/${item.slug}" class="list-group-item list-group-item-action">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">${item.nama_layanan}</h6>
                    <small class="text-muted">${item.deskripsi || ''}</small>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </div>
        </a>
    `).join('');

    document.getElementById('layananList').innerHTML = html;
}

function displaySearchResults(results) {
    if (results.length === 0) {
        document.getElementById('layananList').innerHTML = '<div class="text-center p-4 text-muted">Tidak ada layanan yang ditemukan</div>';
        return;
    }

    const html = results.map(item => `
        <a href="/panduan/detail/${item.slug}" class="list-group-item list-group-item-action">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">${item.nama}</h6>
                    <small class="text-muted">${item.kategori} • ${item.estimasi}</small>
                    <p class="mb-1 small">${item.deskripsi}</p>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </div>
        </a>
    `).join('');

    document.getElementById('layananList').innerHTML = html;
}
</script>
@endpush

@endsection
