@extends('Template.template')

@push('style')
<link href="{{ Vite::asset('resources/css/style.css') }}" rel="stylesheet">

<style>
    .perpu-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        height: 100%;
        overflow: hidden;
        background: white;
    }

    .perpu-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }

    .perpu-header {
        background: linear-gradient(45deg, #f8f9fa, #e9ecef);
        padding: 20px 20px 15px 20px;
        border-bottom: 3px solid #6777ef;
        position: relative;
    }

    .perpu-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
        margin: 0 0 8px 0;
        line-height: 1.4;
        padding-right: 80px; /* Space for badge */
    }

    .perpu-subtitle {
        font-size: 0.9rem;
        color: #6c757d;
        margin: 0;
        line-height: 1.3;
    }

    .perpu-body {
        padding: 20px;
    }

    .perpu-about {
        color: #495057;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 15px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-align: justify;
    }

    .perpu-tags {
        margin-bottom: 15px;
        min-height: 32px;
    }

    .perpu-tags .badge {
        font-size: 0.75rem;
        padding: 4px 8px;
        margin-right: 5px;
        margin-bottom: 5px;
    }

    .perpu-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        border-top: 1px solid #e9ecef;
        border-bottom: 1px solid #e9ecef;
        margin: 15px 0;
    }

    .perpu-date {
        font-size: 0.85rem;
        color: #6c757d;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .perpu-download {
        font-size: 0.85rem;
        color: #28a745;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .perpu-actions {
        display: flex;
        gap: 10px;
    }

    .btn-view {
        background: linear-gradient(45deg, #667eea, #764ba2);
        border: none;
        color: white;
        border-radius: 25px;
        padding: 10px 20px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        flex: 1;
        text-align: center;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-download {
        background: linear-gradient(45deg, #28a745, #20c997);
        border: none;
        color: white;
        border-radius: 25px;
        padding: 10px 20px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        flex: 1;
        text-align: center;
    }

    .btn-download:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        color: white;
    }

    .filter-section {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        margin-bottom: 40px;
    }

    .search-box {
        border-radius: 25px;
        border: 2px solid #e9ecef;
        padding: 12px 20px;
        transition: all 0.3s ease;
    }

    .search-box:focus {
        border-color: #6777ef;
        box-shadow: 0 0 0 0.2rem rgba(103, 119, 239, 0.25);
    }

    .filter-select {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        padding: 10px 15px;
    }

    .filter-select:focus {
        border-color: #6777ef;
        box-shadow: 0 0 0 0.2rem rgba(103, 119, 239, 0.25);
    }

    .badge-jenis {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 8px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        z-index: 2;
    }

    /* PDF Viewer Styles */
    .pdf-viewer-container {
        display: none;
        margin-top: 30px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        overflow: hidden;
    }

    .pdf-viewer-header {
        background: linear-gradient(45deg, #667eea, #764ba2);
        color: white;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .pdf-viewer-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin: 0;
    }

    #pdfFrame {
        width: 100%;
        height: 1975px;
    }

    .pdf-close-btn {
        background: rgba(255,255,255,0.2);
        border: none;
        color: white;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .pdf-close-btn:hover {
        background: rgba(255,255,255,0.3);
        transform: scale(1.1);
    }

    .pdf-viewer-content {
        height: 80vh;
        background: #f8f9fa;
    }

    .pdf-viewer-content iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    .pdf-loading {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 200px;
        color: #6c757d;
    }

    .no-data {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }

    .no-data i {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .pagination {
        justify-content: center;
        margin-top: 40px;
    }

    .page-link {
        border-radius: 10px;
        margin: 0 5px;
        border: none;
        color: #6777ef;
        background: #f8f9fa;
    }

    .page-link:hover, .page-item.active .page-link {
        background: #6777ef;
        color: white;
    }

    /* Badge Colors */
    .badge-undang-undang { background: linear-gradient(45deg, #667eea, #764ba2); }
    .badge-peraturan-pemerintah { background: linear-gradient(45deg, #28a745, #20c997); }
    .badge-peraturan-walikota { background: linear-gradient(45deg, #28a745, #20c997); }
    .badge-peraturan-daerah { background: linear-gradient(45deg, #ffc107, #fd7e14); }
    .badge-peraturan-menteri { background: linear-gradient(45deg, #dc3545, #e83e8c); }
    .badge-keputusan { background: linear-gradient(45deg, #17a2b8, #6f42c1); }
    .badge-instruksi { background: linear-gradient(45deg, #6c757d, #495057); }
    .badge-lainnya { background: linear-gradient(45deg, #6c757d, #495057); }

    @media (max-width: 768px) {
        .filter-section {
            padding: 20px;
        }

        .perpu-actions {
            flex-direction: column;
        }

        .perpu-title {
            font-size: 1rem;
            padding-right: 70px;
        }

        .badge-jenis {
            font-size: 0.7rem;
            padding: 6px 10px;
        }

        .pdf-viewer-content {
            height: 60vh;
        }
    }

    /* Loading Animation */
    .btn-loading {
        position: relative;
        pointer-events: none;
    }

    .btn-loading::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        margin: auto;
        border: 2px solid transparent;
        border-top-color: #ffffff;
        border-radius: 50%;
        animation: spin 1s ease infinite;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
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

    <!-- Two Column Section like About Page -->
    <div class="container-xxl py-6">
        <div class="container mt-5">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow zoomIn" data-wow-delay="0.1s">
                    <img class="img-fluid" src="{{ Vite::asset('resources/images/img/peraturan-rules.png') }}">
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="d-inline-block border rounded-pill text-primary px-4 mb-3">Peraturan Perundang-undangan</div>
                    <h2 class="mb-4">Akses Dokumen Peraturan Resmi</h2>
                    <p class="mb-4">Temukan dan akses seluruh dokumen peraturan perundang-undangan resmi Kelurahan Jemurwonosari. Sistem kami menyediakan akses mudah dan cepat ke berbagai jenis peraturan yang berlaku.</p>
                    <div class="row g-3 mb-4">
                        <div class="col-12 d-flex">
                            <div class="flex-shrink-0 btn-lg-square rounded-circle bg-primary">
                                <i class="fas fa-file-alt text-white"></i>
                            </div>
                            <div class="ms-4">
                                <h6>Dokumen Lengkap</h6>
                                <span>Akses ke semua jenis peraturan perundang-undangan yang berlaku.</span>
                            </div>
                        </div>
                        <div class="col-12 d-flex">
                            <div class="flex-shrink-0 btn-lg-square rounded-circle bg-primary">
                                <i class="fas fa-download text-white"></i>
                            </div>
                            <div class="ms-4">
                                <h6>Download Gratis</h6>
                                <span>Unduh dokumen peraturan dalam format PDF secara gratis.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container-xxl py-6">
        <div class="container">
            <div class="filter-section">
                <form method="GET" action="{{ route('perpu.index') }}" id="filterForm">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Pencarian</label>
                            <input type="text"
                                class="form-control search-box"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Cari judul, nomor, atau kata kunci...">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Jenis Peraturan</label>
                            <select class="form-select filter-select" name="jenis">
                                <option value="">Semua Jenis</option>
                                @foreach($jenisOptions as $jenis)
                                    <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>
                                        {{ $jenis }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Tahun</label>
                            <select class="form-select filter-select" name="tahun">
                                <option value="">Semua Tahun</option>
                                @foreach($tahunOptions as $tahun)
                                    <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                        {{ $tahun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-search"></i> Cari
                            </button>
                            <a href="{{ route('perpu.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Results Section -->
            @if($perpu->count() > 0)
                <div class="row">
                    @foreach($perpu as $item)
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="card perpu-card position-relative">
                                <!-- Badge Jenis -->
                                <span class="badge badge-{{ strtolower(str_replace([' ', '-'], '-', $item->jenis_peraturan)) }} badge-jenis text-white">
                                    {{ $item->jenis_peraturan }}
                                </span>

                                <div class="perpu-header">
                                    <h5 class="perpu-title">{{ $item->full_title }}</h5>
                                    <p class="perpu-subtitle">{{ $item->judul }}</p>
                                </div>

                                <div class="perpu-body">
                                    <p class="perpu-about">{{ $item->tentang }}</p>

                                    <div class="perpu-tags">
                                        @if($item->tags && count($item->tags) > 0)
                                            @foreach($item->tags as $tag)
                                                <span class="badge bg-light text-muted"># {{ $tag }}</span>
                                            @endforeach
                                        @endif
                                    </div>

                                    <div class="perpu-meta">
                                        <span class="perpu-date">
                                            <i class="bi bi-calendar3"></i>
                                            {{ $item->tanggal_penetapan->format('d M Y') }}
                                        </span>
                                        <span class="perpu-download">
                                            <i class="bi bi-download"></i>
                                            {{ number_format($item->download_count) }}x
                                        </span>
                                    </div>

                                    <div class="perpu-actions">
                                        <button class="btn btn-view btn-sm btn-view-pdf"
                                                data-id="{{ $item->id }}"
                                                data-title="{{ $item->full_title }}"
                                                data-url="{{ $item->pdf_url }}">
                                            <i class="bi bi-eye"></i> Lihat PDF
                                        </button>
                                        <a href="{{ route('perpu.download', $item->id) }}" class="btn btn-download btn-sm">
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- PDF Viewer Container -->
                <div id="pdfViewer" class="pdf-viewer-container">
                    <div class="pdf-viewer-header">
                        <h5 class="pdf-viewer-title" id="pdfTitle">Preview Dokumen</h5>
                        <button class="pdf-close-btn" id="closePdfBtn">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </div>
                    <div class="pdf-viewer-content">
                        <div class="pdf-loading" id="pdfLoading">
                            <div class="spinner-border text-primary me-3" role="status"></div>
                            <span>Memuat dokumen...</span>
                        </div>
                        <iframe id="pdfFrame" frameborder="0" style="display: none;"></iframe>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $perpu->withQueryString()->links() }}
                </div>
            @else
                <div class="no-data">
                    <i class="bi bi-file-earmark-text"></i>
                    <h4>Tidak ada data ditemukan</h4>
                    <p class="mb-0">
                        @if(request()->hasAny(['search', 'jenis', 'tahun']))
                            Coba ubah kriteria pencarian atau filter yang Anda gunakan.
                        @else
                            Belum ada peraturan perundang-undangan yang tersedia.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>

    @include('layouts.newsletter')
    @include('layouts.footer')
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto submit form when filter changes
    $('.filter-select').on('change', function() {
        $('#filterForm').submit();
    });

    // Search with delay
    let searchTimeout;
    $('.search-box').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            if ($('.search-box').val().length >= 3 || $('.search-box').val().length === 0) {
                $('#filterForm').submit();
            }
        }, 500);
    });

    // PDF Viewer functionality
    $('.btn-view-pdf').on('click', function(e) {
        e.preventDefault();

        const btn = $(this);
        const originalHtml = btn.html();
        const title = btn.data('title');
        const url = btn.data('url');

        // Add loading state
        btn.addClass('btn-loading').html('<span style="opacity: 0;">Memuat...</span>');

        // Set title
        $('#pdfTitle').text(title);

        // Show viewer and loading
        $('#pdfViewer').slideDown(300);
        $('#pdfLoading').show();
        $('#pdfFrame').hide();

        // Scroll to PDF viewer
        $('html, body').animate({
            scrollTop: $('#pdfViewer').offset().top - 100
        }, 500);

        // Load PDF in iframe
        const iframe = $('#pdfFrame');

        iframe.on('load', function() {
            $('#pdfLoading').hide();
            iframe.show();
            btn.removeClass('btn-loading').html(originalHtml);
        });

        // Handle iframe error
        iframe.on('error', function() {
            $('#pdfLoading').html(`
                <div class="text-center">
                    <i class="bi bi-exclamation-circle text-warning" style="font-size: 2rem;"></i>
                    <p class="mt-2 mb-0">Gagal memuat dokumen. Silakan coba lagi.</p>
                </div>
            `);
            btn.removeClass('btn-loading').html(originalHtml);
        });

        // Set iframe source
        iframe.attr('src', url + '#toolbar=1&navpanes=1&scrollbar=1&view=FitH');
    });

    // Close PDF viewer
    $('#closePdfBtn').on('click', function() {
        $('#pdfViewer').slideUp(300);
        $('#pdfFrame').attr('src', ''); // Clear iframe
    });

    // Close PDF viewer when clicking outside
    $(document).on('click', function(e) {
        if ($(e.target).closest('#pdfViewer, .btn-view-pdf').length === 0) {
            if ($('#pdfViewer').is(':visible')) {
                $('#pdfViewer').slideUp(300);
                $('#pdfFrame').attr('src', '');
            }
        }
    });

    // Handle ESC key to close PDF viewer
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('#pdfViewer').is(':visible')) {
            $('#pdfViewer').slideUp(300);
            $('#pdfFrame').attr('src', '');
        }
    });

    // Animate cards on scroll
    $(window).on('scroll', function() {
        $('.perpu-card').each(function() {
            const cardTop = $(this).offset().top;
            const cardBottom = cardTop + $(this).outerHeight();
            const windowTop = $(window).scrollTop();
            const windowBottom = windowTop + $(window).height();

            if (cardBottom >= windowTop && cardTop <= windowBottom) {
                $(this).addClass('animate__animated animate__fadeInUp');
            }
        });
    });
});
</script>
@endpush
