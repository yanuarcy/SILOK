@extends('Template.template')

@push('style')
<link href="{{ Vite::asset('resources/css/style.css') }}" rel="stylesheet">

<style>
    .bank-data-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        height: 100%;
        overflow: hidden;
        background: white;
    }

    .bank-data-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        cursor: pointer;
    }

    .card-image-wrapper {
        position: relative;
        height: 200px;
        overflow: hidden;
        background: linear-gradient(45deg, #f8f9fa, #e9ecef);
    }

    .card-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .bank-data-card:hover .card-image {
        transform: scale(1.05);
    }

    .card-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(103,119,239,0.8), rgba(118,75,162,0.8));
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .bank-data-card:hover .card-overlay {
        opacity: 1;
    }

    .overlay-content {
        text-align: center;
        color: white;
    }

    .overlay-content i {
        font-size: 2rem;
        margin-bottom: 10px;
    }

    .bank-data-header {
        padding: 20px 20px 15px 20px;
        position: relative;
    }

    .bank-data-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
        margin: 0 0 8px 0;
        line-height: 1.4;
        padding-right: 80px;
    }

    .bank-data-location {
        font-size: 0.85rem;
        color: #6c757d;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .bank-data-body {
        padding: 0 20px 20px 20px;
    }

    .bank-data-description {
        color: #495057;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 15px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-align: justify;
    }

    .bank-data-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        border-top: 1px solid #e9ecef;
        border-bottom: 1px solid #e9ecef;
        margin: 15px 0;
    }

    .meta-date {
        font-size: 0.85rem;
        color: #6c757d;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .meta-files {
        font-size: 0.85rem;
        color: #28a745;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .bank-data-tags {
        margin-bottom: 15px;
        min-height: 32px;
    }

    .bank-data-tags .badge {
        font-size: 0.7rem;
        padding: 4px 8px;
        margin-right: 5px;
        margin-bottom: 5px;
        background: linear-gradient(45deg, #f8f9fa, #e9ecef);
        color: #495057;
        border: 1px solid #dee2e6;
    }

    .bank-data-actions {
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

    .search-suggestions {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #dee2e6;
        border-top: none;
        border-radius: 0 0 8px 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        z-index: 1000;
        max-height: 200px;
        overflow-y: auto;
    }

    .suggestion-item {
        padding: 8px 12px;
        cursor: pointer;
        border-bottom: 1px solid #f8f9fa;
        transition: background-color 0.2s ease;
    }

    .suggestion-item:hover {
        background-color: #f8f9fa;
    }

    .suggestion-item:last-child {
        border-bottom: none;
    }

    .search-box-container {
        position: relative;
    }

    mark {
        background-color: #fff3cd;
        padding: 1px 2px;
        border-radius: 2px;
    }

    .btn-loading {
        pointer-events: none;
    }

    .animate__fadeInUp {
        animation-duration: 0.5s;
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

    .stats-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 40px 30px;
        margin-bottom: 40px;
        color: white;
    }

    .stat-card {
        text-align: center;
        padding: 20px;
        background: rgba(255,255,255,0.1);
        border-radius: 10px;
        backdrop-filter: blur(10px);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
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
    .badge-kelurahan { background: linear-gradient(45deg, #667eea, #764ba2); color: white; }
    .badge-rw { background: linear-gradient(45deg, #28a745, #20c997); color: white; }
    .badge-rt { background: linear-gradient(45deg, #ffc107, #fd7e14); color: #333; }

    @media (max-width: 768px) {
        .filter-section {
            padding: 20px;
        }

        .bank-data-actions {
            flex-direction: column;
        }

        .bank-data-title {
            font-size: 1rem;
            padding-right: 70px;
        }

        .badge-jenis {
            font-size: 0.7rem;
            padding: 6px 10px;
        }

        .stats-section {
            padding: 30px 20px;
        }

        .stat-number {
            font-size: 2rem;
        }
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

    <!-- Hero Section -->
    <div class="container-xxl py-6">
        <div class="container mt-5">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow zoomIn" data-wow-delay="0.1s">
                    <img class="img-fluid" src="{{ Vite::asset('resources/images/img/bank-data-hero.png') }}" alt="Bank Data">
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="d-inline-block border rounded-pill text-primary px-4 mb-3">Bank Data</div>
                    <h2 class="mb-4">Penyimpanan dan Pengelolaan Data-data RT/RW</h2>
                    <p class="mb-4">Temukan dan akses seluruh dokumentasi kegiatan warga Kelurahan Jemurwonosari. Sistem bank data kami menyediakan penyimpanan yang terorganisir untuk foto dan video kegiatan di setiap tingkat wilayah.</p>
                    <div class="row g-3 mb-4">
                        <div class="col-12 d-flex">
                            <div class="flex-shrink-0 btn-lg-square rounded-circle bg-primary">
                                <i class="fas fa-images text-white"></i>
                            </div>
                            <div class="ms-4">
                                <h6>Dokumentasi Lengkap</h6>
                                <span>Foto dan video kegiatan dari seluruh RT/RW dan Kelurahan.</span>
                            </div>
                        </div>
                        <div class="col-12 d-flex">
                            <div class="flex-shrink-0 btn-lg-square rounded-circle bg-primary">
                                <i class="fas fa-eye text-white"></i>
                            </div>
                            <div class="ms-4">
                                <h6>Akses Mudah</h6>
                                <span>Lihat dan akses dokumentasi kegiatan dengan mudah dan cepat.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="container-xxl">
        <div class="container">
            <div class="stats-section">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <div class="stat-number">{{ $stats['total_kelurahan'] }}</div>
                            <div class="stat-label">Data Kelurahan</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <div class="stat-number">{{ $stats['total_rw'] }}</div>
                            <div class="stat-label">Data RW</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <div class="stat-number">{{ $stats['total_rt'] }}</div>
                            <div class="stat-label">Data RT</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <div class="stat-number">{{ $stats['total_files'] }}</div>
                            <div class="stat-label">Total File</div>
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
                <form method="GET" action="{{ route('bankdata.index') }}" id="filterForm">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Pencarian</label>
                            <input type="text"
                                class="form-control search-box"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Cari kegiatan, deskripsi...">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Jenis Bank Data</label>
                            <select class="form-select filter-select" name="jenis">
                                <option value="">Semua Jenis</option>
                                @foreach($jenisOptions as $key => $jenis)
                                    <option value="{{ $key }}" {{ request('jenis') == $key ? 'selected' : '' }}>
                                        {{ $jenis }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
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
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Filter</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-fill">
                                    <i class="bi bi-search"></i> Cari
                                </button>
                                <a href="{{ route('bankdata.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Results Section -->
            @if($bankData->count() > 0)
                <div class="row">
                    @foreach($bankData as $item)
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="card bank-data-card position-relative">
                                <!-- Badge Jenis -->
                                <span class="badge badge-{{ strtolower($item->jenis_bank_data) }} badge-jenis">
                                    {{ $item->jenis_bank_data }}
                                    @if($item->jenis_bank_data == 'RW' && $item->nomor_rw)
                                        {{ $item->nomor_rw }}
                                    @elseif($item->jenis_bank_data == 'RT' && $item->nomor_rt)
                                        {{ $item->nomor_rt }}
                                    @endif
                                </span>

                                <!-- Card Image -->
                                <div class="card-image-wrapper">
                                    @if($item->first_image)
                                        <img src="{{ $item->first_image }}" class="card-image" alt="{{ $item->judul_kegiatan }}">
                                    @else
                                        <div class="card-image d-flex align-items-center justify-content-center bg-light">
                                            <i class="fas fa-images fa-3x text-muted"></i>
                                        </div>
                                    @endif

                                    <div class="card-overlay">
                                        <div class="overlay-content">
                                            <i class="fas fa-eye"></i>
                                            <div>Lihat Detail</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="bank-data-header">
                                    <h5 class="bank-data-title">{{ $item->judul_kegiatan }}</h5>
                                    <p class="bank-data-location">
                                        <i class="bi bi-geo-alt"></i>
                                        {{ $item->lokasi ?: $item->wilayah_lengkap }}
                                    </p>
                                </div>

                                <div class="bank-data-body">
                                    <p class="bank-data-description">{{ $item->deskripsi }}</p>

                                    <div class="bank-data-tags">
                                        @if($item->tags && count($item->tags) > 0)
                                            @foreach(array_slice($item->tags, 0, 3) as $tag)
                                                <span class="badge"># {{ $tag }}</span>
                                            @endforeach
                                            @if(count($item->tags) > 3)
                                                <span class="badge">+{{ count($item->tags) - 3 }} lainnya</span>
                                            @endif
                                        @endif
                                    </div>

                                    <div class="bank-data-meta">
                                        <span class="meta-date">
                                            <i class="bi bi-calendar3"></i>
                                            {{ $item->tanggal_kegiatan->format('d M Y') }}
                                        </span>
                                        <span class="meta-files">
                                            <i class="bi bi-files"></i>
                                            {{ $item->total_files }} file
                                        </span>
                                    </div>

                                    <div class="bank-data-actions">
                                        <a href="{{ route('bankdata.show', $item->id) }}" class="btn btn-view">
                                            <i class="bi bi-eye"></i> Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $bankData->withQueryString()->links() }}
                </div>
            @else
                <div class="no-data">
                    <i class="bi bi-archive"></i>
                    <h4>Tidak ada data ditemukan</h4>
                    <p class="mb-0">
                        @if(request()->hasAny(['search', 'jenis', 'tahun']))
                            Coba ubah kriteria pencarian atau filter yang Anda gunakan.
                        @else
                            Belum ada data bank data yang tersedia.
                        @endif
                    </p>
                </div>
            @endif

            <!-- Recent Activities Section -->
            @if($recentActivities->count() > 0)
                <div class="mt-5">
                    <h4 class="mb-4 text-center">Aktivitas Terbaru</h4>
                    <div class="row">
                        @foreach($recentActivities as $activity)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                @if($activity->first_image != asset('images/default-placeholder.jpg'))
                                                    <img src="{{ $activity->first_image }}" class="rounded" width="50" height="50" style="object-fit: cover;">
                                                @else
                                                    <div class="bg-primary rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                        <i class="fas fa-images text-white"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ms-3 flex-grow-1">
                                                <h6 class="mb-1 small">{{ Str::limit($activity->judul_kegiatan, 40) }}</h6>
                                                <p class="text-muted small mb-0">
                                                    {{ $activity->wilayah_lengkap }} • {{ $activity->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
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

    // Enhanced search with better UX
    let searchTimeout;
    let searchRequest;

    $('.search-box').on('input', function() {
        const searchValue = $(this).val();

        // Clear previous timeout and request
        clearTimeout(searchTimeout);
        if (searchRequest) {
            searchRequest.abort();
        }

        // Auto-submit search
        searchTimeout = setTimeout(function() {
            if (searchValue.length >= 2 || searchValue.length === 0) {
                $('#filterForm').submit();
            }
        }, 800); // Increased delay for better UX
    });

    // Add search autocomplete (optional)
    $('.search-box').on('focus', function() {
        if ($(this).val().length >= 2) {
            showSearchSuggestions($(this).val());
        }
    });

    // Search suggestions functionality
    function showSearchSuggestions(term) {
        if (term.length < 2) return;

        searchRequest = $.ajax({
            url: '/api/bank-data/suggestions',
            method: 'GET',
            data: { term: term },
            success: function(suggestions) {
                if (suggestions.length > 0) {
                    let suggestionHtml = '<div class="search-suggestions">';
                    suggestions.forEach(function(suggestion) {
                        suggestionHtml += `<div class="suggestion-item" data-value="${suggestion}">${suggestion}</div>`;
                    });
                    suggestionHtml += '</div>';

                    $('.search-box').after(suggestionHtml);
                }
            },
            error: function() {
                // Handle error silently
            }
        });
    }

    // Handle suggestion clicks
    $(document).on('click', '.suggestion-item', function() {
        const value = $(this).data('value');
        $('.search-box').val(value);
        $('.search-suggestions').remove();
        $('#filterForm').submit();
    });

    // Hide suggestions when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.search-box, .search-suggestions').length) {
            $('.search-suggestions').remove();
        }
    });

    // Animate cards on scroll
    $(window).on('scroll', function() {
        $('.bank-data-card').each(function() {
            const cardTop = $(this).offset().top;
            const cardBottom = cardTop + $(this).outerHeight();
            const windowTop = $(window).scrollTop();
            const windowBottom = windowTop + $(window).height();

            if (cardBottom >= windowTop && cardTop <= windowBottom) {
                $(this).addClass('animate__animated animate__fadeInUp');
            }
        });
    });

    // Handle card click for better UX
    $('.bank-data-card').on('click', function(e) {
        // Don't trigger if clicking on buttons or links
        if (!$(e.target).hasClass('btn') && !$(e.target).closest('.btn').length) {
            const url = $(this).find('.btn-view').attr('href');
            if (url) {
                window.location.href = url;
            }
        }
    });

    // Add loading state to filter form
    $('#filterForm').on('submit', function() {
        const submitButton = $(this).find('button[type="submit"]');
        const originalText = submitButton.html();

        submitButton.html('<i class="fas fa-spinner fa-spin"></i> Mencari...').prop('disabled', true);

        // Re-enable after a delay (in case of no redirect)
        setTimeout(() => {
            submitButton.html(originalText).prop('disabled', false);
        }, 3000);
    });

    // Keyboard shortcuts for search
    $(document).on('keydown', function(e) {
        // Ctrl+F or Cmd+F to focus search
        if ((e.ctrlKey || e.metaKey) && e.which === 70) {
            e.preventDefault();
            $('.search-box').focus();
        }

        // Escape to clear search
        if (e.which === 27) {
            $('.search-box').val('');
            $('.search-suggestions').remove();
        }
    });

    // Add search tips tooltip
    $('.search-box').attr('title', 'Tips: Cari berdasarkan judul kegiatan, deskripsi, lokasi, atau tag');

    // Initialize tooltips if Bootstrap 5 is available
    if (typeof bootstrap !== 'undefined') {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // Highlight search terms in results
    function highlightSearchTerms() {
        const searchTerm = $('.search-box').val();
        if (searchTerm && searchTerm.length >= 2) {
            $('.bank-data-card').each(function() {
                const card = $(this);
                const title = card.find('.bank-data-title');
                const description = card.find('.bank-data-description');

                // Highlight in title
                if (title.length) {
                    const titleText = title.text();
                    const highlightedTitle = titleText.replace(
                        new RegExp(`(${searchTerm})`, 'gi'),
                        '<mark>$1</mark>'
                    );
                    title.html(highlightedTitle);
                }

                // Highlight in description
                if (description.length) {
                    const descText = description.text();
                    const highlightedDesc = descText.replace(
                        new RegExp(`(${searchTerm})`, 'gi'),
                        '<mark>$1</mark>'
                    );
                    description.html(highlightedDesc);
                }
            });
        }
    }

    // Apply highlighting after page load
    setTimeout(highlightSearchTerms, 100);
});
</script>
@endpush
