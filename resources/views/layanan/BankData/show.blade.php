@extends('Template.template')

@push('style')
<link href="{{ Vite::asset('resources/css/style.css') }}" rel="stylesheet">

<style>
    :root {
        --primary-color: #00B98E;
        --primary-dark: #009975;
        --primary-light: #1AC8A2;
        --light-bg: #f8fffe;
        --border-color: #e5f5f2;
    }

    .content-wrapper {
        padding-top: 40px;
    }

    .main-content-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,185,142,0.1);
        overflow: hidden;
        margin-bottom: 30px;
    }

    .activity-header {
        background: linear-gradient(135deg, var(--light-bg) 0%, #f0fffe 100%);
        padding: 40px;
        border-bottom: 1px solid var(--border-color);
        position: relative;
    }

    .activity-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: var(--primary-color);
    }

    .back-button {
        background: rgba(0,185,142,0.1);
        border: 2px solid rgba(0,185,142,0.2);
        color: var(--primary-color);
        padding: 10px 20px;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 25px;
        font-size: 0.9rem;
    }

    .back-button:hover {
        background: rgba(0,185,142,0.15);
        color: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,185,142,0.2);
    }

    .activity-title {
        font-size: 2rem;
        font-weight: 800;
        color: #2d3748;
        margin-bottom: 15px;
        line-height: 1.3;
    }

    .badge-jenis {
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 20px;
        display: inline-block;
        background: var(--primary-color);
        color: white;
    }

    .meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 25px;
    }

    .meta-item {
        background: white;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,185,142,0.1);
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .meta-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,185,142,0.15);
    }

    .meta-icon {
        width: 45px;
        height: 45px;
        background: var(--primary-color);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.1rem;
        margin-bottom: 12px;
    }

    .meta-label {
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 4px;
        font-weight: 500;
    }

    .meta-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
    }

    .meta-sub {
        font-size: 0.8rem;
        color: #94a3b8;
        margin-top: 2px;
    }

    .content-section {
        padding: 40px;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--border-color);
    }

    .section-icon {
        width: 50px;
        height: 50px;
        background: var(--primary-color);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .section-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #2d3748;
        margin: 0;
    }

    .description-content {
        background: var(--light-bg);
        border: 1px solid var(--border-color);
        padding: 30px;
        border-radius: 15px;
        position: relative;
    }

    .description-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-color);
        border-radius: 0 2px 2px 0;
    }

    .description-text {
        color: #475569;
        line-height: 1.7;
        font-size: 1rem;
        margin: 0;
        text-align: justify;
    }

    .tags-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 15px;
    }

    .tag-item {
        background: var(--primary-color);
        color: white;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .tag-item:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0,185,142,0.3);
        background: var(--primary-dark);
    }

    .gallery-navigation {
        display: flex;
        justify-content: center;
        margin-bottom: 35px;
        background: var(--light-bg);
        padding: 6px;
        border-radius: 50px;
        box-shadow: inset 0 2px 8px rgba(0,185,142,0.1);
        border: 1px solid var(--border-color);
    }

    .gallery-nav-btn {
        background: transparent;
        border: none;
        color: #64748b;
        padding: 12px 24px;
        border-radius: 45px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 130px;
        justify-content: center;
        font-size: 0.9rem;
    }

    .gallery-nav-btn.active,
    .gallery-nav-btn:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(0,185,142,0.25);
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 20px;
    }

    .gallery-item {
        position: relative;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0,185,142,0.1);
        transition: all 0.4s ease;
        background: var(--light-bg);
        aspect-ratio: 4/3;
        cursor: pointer;
    }

    .gallery-item:hover {
        transform: translateY(-6px) scale(1.01);
        box-shadow: 0 15px 35px rgba(0,185,142,0.2);
    }

    .gallery-item img,
    .gallery-item video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .gallery-item:hover img,
    .gallery-item:hover video {
        transform: scale(1.08);
    }

    .gallery-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(0,185,142,0.85), rgba(0,153,117,0.85));
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .gallery-item:hover .gallery-overlay {
        opacity: 1;
    }

    .gallery-overlay-content {
        text-align: center;
        color: white;
    }

    .gallery-overlay-content i {
        font-size: 2.5rem;
        margin-bottom: 8px;
        display: block;
    }

    .gallery-overlay-content span {
        font-size: 0.9rem;
        font-weight: 600;
    }

    .video-indicator {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(0,0,0,0.8);
        color: white;
        padding: 6px 10px;
        border-radius: 15px;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        gap: 4px;
        backdrop-filter: blur(10px);
    }

    .related-section {
        background: var(--light-bg);
        padding: 50px 40px;
        margin: 0 -40px -40px;
    }

    .related-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,185,142,0.08);
        transition: all 0.3s ease;
        height: 100%;
        overflow: hidden;
        background: white;
    }

    .related-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 15px 35px rgba(0,185,142,0.15);
    }

    .related-image {
        height: 160px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .related-card:hover .related-image {
        transform: scale(1.08);
    }

    .related-card .card-body {
        padding: 20px;
    }

    .related-card .card-title {
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 12px;
        line-height: 1.3;
        font-size: 1rem;
    }

    .related-meta {
        color: #64748b;
        font-size: 0.85rem;
        margin-bottom: 15px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .related-meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-view-related {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.85rem;
    }

    .btn-view-related:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0,185,142,0.3);
        background: var(--primary-dark);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #64748b;
    }

    .empty-state i {
        font-size: 3.5rem;
        margin-bottom: 20px;
        opacity: 0.6;
        color: #94a3b8;
    }

    .empty-state h5 {
        color: #475569;
        margin-bottom: 8px;
        font-weight: 600;
    }

    /* Lightbox Improvements */
    .lightbox-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255,255,255,0.95);
        color: #374151;
        border: none;
        padding: 12px 16px;
        font-size: 1.3rem;
        cursor: pointer;
        border-radius: 50%;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }

    .lightbox-nav:hover {
        background: white;
        transform: translateY(-50%) scale(1.05);
    }

    .lightbox-prev { left: 25px; }
    .lightbox-next { right: 25px; }

    .lightbox-counter {
        position: absolute;
        bottom: 25px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0,0,0,0.8);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        backdrop-filter: blur(10px);
        font-weight: 500;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .activity-header {
            padding: 25px 20px;
        }

        .activity-title {
            font-size: 1.5rem;
        }

        .meta-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .content-section {
            padding: 25px 20px;
        }

        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 15px;
        }

        .gallery-navigation {
            flex-direction: column;
            gap: 6px;
            padding: 10px;
        }

        .related-section {
            padding: 30px 20px;
            margin: 0 -20px -25px;
        }

        .lightbox-nav {
            padding: 10px 12px;
            font-size: 1.1rem;
        }

        .lightbox-prev { left: 15px; }
        .lightbox-next { right: 15px; }
    }

    /* Animation Classes */
    .fade-in-up {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.6s ease forwards;
    }

    .fade-in-up:nth-child(1) { animation-delay: 0.1s; }
    .fade-in-up:nth-child(2) { animation-delay: 0.2s; }
    .fade-in-up:nth-child(3) { animation-delay: 0.3s; }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
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

    <!-- Page Header Start -->
    <div class="container-xxl py-6 bg-primary mb-5">
        <div class="container text-center py-6">
            <h1 class="display-4 text-white mb-4">Detail Bank Data</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('bankdata.index') }}" class="text-white">Bank Data</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">{{ Str::limit($bankData->judul_kegiatan, 30) }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Main Content -->
    <div class="container-xxl">
        <div class="container">
            <div class="content-wrapper">
                <!-- Main Content Card -->
                <div class="main-content-card">
                    <!-- Activity Header -->
                    <div class="activity-header">
                        <a href="{{ route('bankdata.index') }}" class="back-button">
                            <i class="bi bi-arrow-left"></i>
                            <span>Kembali ke Bank Data</span>
                        </a>

                        <span class="badge-jenis">
                            <i class="bi bi-bookmark-fill me-1"></i>
                            {{ $bankData->jenis_bank_data }}
                            @if($bankData->jenis_bank_data == 'RW' && $bankData->nomor_rw)
                                {{ $bankData->nomor_rw }}
                            @elseif($bankData->jenis_bank_data == 'RT' && $bankData->nomor_rt)
                                {{ $bankData->nomor_rt }}
                            @endif
                        </span>

                        <h1 class="activity-title">{{ $bankData->judul_kegiatan }}</h1>

                        <div class="meta-grid">
                            <div class="meta-item fade-in-up">
                                <div class="meta-icon">
                                    <i class="bi bi-calendar-event"></i>
                                </div>
                                <div class="meta-label">Tanggal Kegiatan</div>
                                <div class="meta-value">{{ $bankData->tanggal_kegiatan->format('d F Y') }}</div>
                                <div class="meta-sub">{{ $bankData->tanggal_kegiatan->diffForHumans() }}</div>
                            </div>

                            <div class="meta-item fade-in-up">
                                <div class="meta-icon">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>
                                <div class="meta-label">Lokasi</div>
                                <div class="meta-value">{{ $bankData->lokasi ?: $bankData->wilayah_lengkap }}</div>
                                <div class="meta-sub">Tempat kegiatan</div>
                            </div>

                            <div class="meta-item fade-in-up">
                                <div class="meta-icon">
                                    <i class="bi bi-collection"></i>
                                </div>
                                <div class="meta-label">Dokumentasi</div>
                                <div class="meta-value">{{ $bankData->foto_count + $bankData->video_count }} File</div>
                                <div class="meta-sub">{{ $bankData->foto_count }} foto, {{ $bankData->video_count }} video</div>
                            </div>

                            <div class="meta-item fade-in-up">
                                <div class="meta-icon">
                                    <i class="bi bi-eye-fill"></i>
                                </div>
                                <div class="meta-label">Viewers</div>
                                <div class="meta-value">{{ number_format($bankData->view_count) }}</div>
                                <div class="meta-sub">Kali dilihat</div>
                            </div>
                        </div>
                    </div>

                    <!-- Description Section -->
                    <div class="content-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="bi bi-info-circle-fill"></i>
                            </div>
                            <h2 class="section-title">Deskripsi Kegiatan</h2>
                        </div>
                        <div class="description-content">
                            <p class="description-text">{{ $bankData->deskripsi }}</p>
                        </div>

                        <!-- Tags Section -->
                        @if($bankData->tags && count($bankData->tags) > 0)
                        <div style="margin-top: 25px;">
                            <h5 style="color: #475569; margin-bottom: 15px; font-weight: 600; font-size: 1rem;">
                                <i class="bi bi-tags-fill me-2" style="color: var(--primary-color);"></i>Tags
                            </h5>
                            <div class="tags-container">
                                @foreach($bankData->tags as $tag)
                                    <a href="{{ route('bankdata.index', ['search' => $tag]) }}" class="tag-item">
                                        <i class="bi bi-hash"></i>
                                        {{ $tag }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Gallery Section -->
                    @if(($bankData->files_foto && count($bankData->files_foto) > 0) || ($bankData->files_video && count($bankData->files_video) > 0))
                    <div class="content-section" style="border-top: 1px solid var(--border-color);">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="bi bi-images"></i>
                            </div>
                            <h2 class="section-title">Galeri Dokumentasi</h2>
                        </div>

                        <!-- Gallery Navigation -->
                        <div class="gallery-navigation">
                            @if($bankData->files_foto && count($bankData->files_foto) > 0)
                                <button class="gallery-nav-btn active" data-tab="foto">
                                    <i class="bi bi-image-fill"></i>
                                    <span>Foto ({{ count($bankData->files_foto) }})</span>
                                </button>
                            @endif
                            @if($bankData->files_video && count($bankData->files_video) > 0)
                                <button class="gallery-nav-btn {{ !$bankData->files_foto || count($bankData->files_foto) == 0 ? 'active' : '' }}" data-tab="video">
                                    <i class="bi bi-play-circle-fill"></i>
                                    <span>Video ({{ count($bankData->files_video) }})</span>
                                </button>
                            @endif
                        </div>

                        <!-- Gallery Content -->
                        <div class="gallery-content">
                            <!-- Foto Gallery -->
                            @if($bankData->files_foto && count($bankData->files_foto) > 0)
                            <div class="gallery-grid" id="foto-gallery" style="{{ !$bankData->files_foto || count($bankData->files_foto) == 0 ? 'display: none;' : '' }}">
                                @foreach($bankData->files_foto as $index => $foto)
                                    <div class="gallery-item"
                                         data-bs-toggle="modal"
                                         data-bs-target="#lightboxModal"
                                         data-type="foto"
                                         data-index="{{ $index }}"
                                         data-src="{{ Storage::url($foto) }}">
                                        <img src="{{ Storage::url($foto) }}" alt="Foto {{ $index + 1 }}" loading="lazy">
                                        <div class="gallery-overlay">
                                            <div class="gallery-overlay-content">
                                                <i class="bi bi-zoom-in"></i>
                                                <span>Lihat Foto</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @endif

                            <!-- Video Gallery -->
                            @if($bankData->files_video && count($bankData->files_video) > 0)
                            <div class="gallery-grid" id="video-gallery" style="{{ $bankData->files_foto && count($bankData->files_foto) > 0 ? 'display: none;' : '' }}">
                                @foreach($bankData->files_video as $index => $video)
                                    <div class="gallery-item"
                                        data-bs-toggle="modal"
                                        data-bs-target="#lightboxModal"
                                        data-type="video"
                                        data-index="{{ $index }}"
                                        data-src="{{ Storage::url($video) }}">
                                        <video muted preload="metadata" style="width: 100%; height: 100%; object-fit: cover;">
                                            <source src="{{ Storage::url($video) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                        <div class="video-indicator">
                                            <i class="bi bi-play-fill"></i>
                                            <span>Video</span>
                                        </div>
                                        <div class="gallery-overlay">
                                            <div class="gallery-overlay-content">
                                                <i class="bi bi-play-circle"></i>
                                                <span>Putar Video</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                    @else
                    <div class="content-section" style="border-top: 1px solid var(--border-color);">
                        <div class="empty-state">
                            <i class="bi bi-image"></i>
                            <h5>Belum Ada Dokumentasi</h5>
                            <p>Dokumentasi foto dan video belum tersedia untuk kegiatan ini.</p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Related Bank Data -->
                @if($relatedBankData->count() > 0)
                <div class="main-content-card">
                    <div class="related-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="bi bi-collection"></i>
                            </div>
                            <h2 class="section-title">Kegiatan Terkait</h2>
                        </div>
                        <div class="row">
                            @foreach($relatedBankData as $related)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card related-card">
                                        @if($related->first_image && $related->first_image != asset('images/default-placeholder.jpg'))
                                            <img src="{{ $related->first_image }}" class="related-image" alt="{{ $related->judul_kegiatan }}">
                                        @else
                                            <div class="related-image bg-light d-flex align-items-center justify-content-center">
                                                <i class="bi bi-image text-muted" style="font-size: 1.8rem;"></i>
                                            </div>
                                        @endif
                                        <div class="card-body">
                                            <h6 class="card-title">{{ Str::limit($related->judul_kegiatan, 55) }}</h6>
                                            <div class="related-meta">
                                                <div class="related-meta-item">
                                                    <i class="bi bi-calendar3"></i>
                                                    <span>{{ $related->tanggal_kegiatan->format('d M Y') }}</span>
                                                </div>
                                                <div class="related-meta-item">
                                                    <i class="bi bi-geo-alt"></i>
                                                    <span>{{ $related->wilayah_lengkap }}</span>
                                                </div>
                                                <div class="related-meta-item">
                                                    <i class="bi bi-collection"></i>
                                                    <span>{{ $related->total_files }} file</span>
                                                </div>
                                            </div>
                                            <a href="{{ route('bankdata.show', $related->id) }}" class="btn-view-related">
                                                <i class="bi bi-eye"></i>
                                                <span>Lihat Detail</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Lightbox Modal -->
    <div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content bg-dark border-0">
                <div class="modal-header border-0 p-2">
                    <span id="lightboxCounter" class="text-white small">1 / 1</span>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0 position-relative">
                    <!-- Image Display -->
                    <img id="lightboxImage"
                        src=""
                        alt=""
                        class="img-fluid w-100 d-none"
                        style="max-height: 80vh; object-fit: contain;">

                    <!-- Video Display -->
                    <video id="lightboxVideo"
                        class="w-100 d-none"
                        controls
                        preload="metadata"
                        style="max-height: 80vh; background: #000;">
                        <source src="" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>

                    <!-- Navigation Buttons -->
                    <button id="lightboxPrev"
                            class="btn btn-light lightbox-nav position-absolute top-50 start-0 translate-middle-y ms-3"
                            style="z-index: 1000; border-radius: 50%; width: 50px; height: 50px;">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button id="lightboxNext"
                            class="btn btn-light lightbox-nav position-absolute top-50 end-0 translate-middle-y me-3"
                            style="z-index: 1000; border-radius: 50%; width: 50px; height: 50px;">
                        <i class="bi bi-chevron-right"></i>
                    </button>
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
        $(document).ready(function() {
            let currentGallery = [];
            let currentIndex = 0;
            let currentType = 'foto';

            // Initialize galleries
            const fotoGallery = @json($bankData->files_foto ? array_map(function($foto) { return Storage::url($foto); }, $bankData->files_foto) : []);
            const videoGallery = @json($bankData->files_video ? array_map(function($video) { return Storage::url($video); }, $bankData->files_video) : []);

            // Gallery tab switching
            $('.gallery-nav-btn').on('click', function() {
                const tab = $(this).data('tab');

                // Update active tab
                $('.gallery-nav-btn').removeClass('active');
                $(this).addClass('active');

                // Show/hide galleries
                if (tab === 'foto') {
                    $('#foto-gallery').show();
                    $('#video-gallery').hide();
                    currentType = 'foto';
                } else {
                    $('#video-gallery').show();
                    $('#foto-gallery').hide();
                    currentType = 'video';
                }

                // Animate gallery items
                animateGalleryItems();
            });

            // Handle gallery item click
            $('.gallery-item').on('click', function() {
                currentType = $(this).data('type');
                currentIndex = parseInt($(this).data('index'));

                if (currentType === 'foto') {
                    currentGallery = fotoGallery;
                } else {
                    currentGallery = videoGallery;
                }

                showLightboxContent();
            });

            // Lightbox navigation
            $('#lightboxPrev').on('click', function() {
                currentIndex = currentIndex > 0 ? currentIndex - 1 : currentGallery.length - 1;
                showLightboxContent();
            });

            $('#lightboxNext').on('click', function() {
                currentIndex = currentIndex < currentGallery.length - 1 ? currentIndex + 1 : 0;
                showLightboxContent();
            });

            // Keyboard navigation
            $(document).on('keydown', function(e) {
                if ($('#lightboxModal').hasClass('show')) {
                    if (e.key === 'ArrowLeft') {
                        $('#lightboxPrev').click();
                    } else if (e.key === 'ArrowRight') {
                        $('#lightboxNext').click();
                    } else if (e.key === 'Escape') {
                        $('#lightboxModal').modal('hide');
                    }
                }
            });

            function showLightboxContent() {
                const src = currentGallery[currentIndex];
                console.log('Loading content:', src, 'Type:', currentType); // Debug log

                // Update counter
                $('#lightboxCounter').text(`${currentIndex + 1} / ${currentGallery.length}`);

                if (currentType === 'foto') {
                    $('#lightboxImage').attr('src', src).removeClass('d-none');
                    $('#lightboxVideo').addClass('d-none');

                    // Pause video if switching from video
                    const videoElement = $('#lightboxVideo')[0];
                    if (videoElement) {
                        videoElement.pause();
                        videoElement.currentTime = 0;
                    }
                } else {
                    // Handle video
                    const videoElement = $('#lightboxVideo')[0];
                    const sourceElement = $('#lightboxVideo source');

                    // Hide image
                    $('#lightboxImage').addClass('d-none');

                    // Update video source
                    sourceElement.attr('src', src);

                    // Load and show video
                    if (videoElement) {
                        videoElement.load();

                        // Add event listeners for better debugging
                        videoElement.addEventListener('loadstart', function() {
                            console.log('Video load started');
                        });

                        videoElement.addEventListener('canplay', function() {
                            console.log('Video can play');
                        });

                        videoElement.addEventListener('error', function(e) {
                            console.error('Video error:', e);
                            showToast('Error loading video', 'error');
                        });

                        // Show video element
                        $('#lightboxVideo').removeClass('d-none');

                        // Try to play after a short delay
                        setTimeout(() => {
                            videoElement.play().catch(error => {
                                console.log('Autoplay prevented:', error);
                                // This is normal for many browsers
                            });
                        }, 100);
                    }
                }

                // Show/hide navigation buttons
                if (currentGallery.length <= 1) {
                    $('.lightbox-nav').hide();
                } else {
                    $('.lightbox-nav').show();
                }
            }

            // Hide video when modal is closed
            $('#lightboxModal').on('hidden.bs.modal', function() {
                const videoElement = $('#lightboxVideo')[0];
                if (videoElement) {
                    videoElement.pause();
                    videoElement.currentTime = 0;
                }
                $('#lightboxImage').attr('src', '');
                $('#lightboxVideo source').attr('src', '');
            });

            // Animate gallery items
            function animateGalleryItems() {
                $('.gallery-item').each(function(index) {
                    $(this).css({
                        'opacity': '0',
                        'transform': 'translateY(20px)'
                    });

                    setTimeout(() => {
                        $(this).css({
                            'opacity': '1',
                            'transform': 'translateY(0)',
                            'transition': 'all 0.5s ease'
                        });
                    }, index * 80);
                });
            }

            // Back button with smooth transition
            $('.back-button').on('click', function(e) {
                e.preventDefault();

                const originalHtml = $(this).html();
                $(this).html('<i class="fas fa-spinner fa-spin"></i> <span>Loading...</span>');

                setTimeout(() => {
                    window.location.href = $(this).attr('href');
                }, 300);
            });

            // Improved video preview on hover - Remove #t=1 fragment
            $('.gallery-item video').each(function() {
                const video = this;
                const $galleryItem = $(this).closest('.gallery-item');

                // Remove fragment from src if exists
                const originalSrc = video.src;
                if (originalSrc.includes('#t=')) {
                    video.src = originalSrc.split('#t=')[0];
                }

                $galleryItem.on('mouseenter', function() {
                    if (video.readyState >= 2) {
                        video.currentTime = 0; // Start from beginning instead of 1 second
                        video.play().catch(() => {
                            // Auto-play failed, which is normal
                        });
                    }
                });

                $galleryItem.on('mouseleave', function() {
                    video.pause();
                    video.currentTime = 0;
                });

                // Add error handling for preview videos
                video.addEventListener('error', function(e) {
                    console.error('Preview video error:', e);
                    $galleryItem.find('.video-indicator').html('<i class="bi bi-exclamation-triangle"></i><span>Error</span>');
                });
            });

            // Share functionality
            function shareContent() {
                if (navigator.share) {
                    navigator.share({
                        title: '{{ $bankData->judul_kegiatan }}',
                        text: '{{ Str::limit($bankData->deskripsi, 100) }}',
                        url: window.location.href
                    }).catch(err => console.log('Error sharing:', err));
                } else {
                    navigator.clipboard.writeText(window.location.href).then(() => {
                        showToast('Link berhasil disalin ke clipboard!', 'success');
                    }).catch(() => {
                        showToast('Gagal menyalin link', 'error');
                    });
                }
            }

            // Toast notification
            function showToast(message, type = 'info') {
                const bgClass = type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : 'bg-primary';
                const iconClass = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle';

                const toastHtml = `
                    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
                        <div class="toast align-items-center text-white ${bgClass} border-0" role="alert">
                            <div class="d-flex">
                                <div class="toast-body">
                                    <i class="bi bi-${iconClass} me-2"></i>
                                    ${message}
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                            </div>
                        </div>
                    </div>
                `;

                $('body').append(toastHtml);
                $('.toast').last().toast('show');

                $('.toast').last().on('hidden.bs.toast', function() {
                    $(this).closest('.toast-container').remove();
                });
            }

            // Make shareContent global
            window.shareContent = shareContent;

            // Floating action buttons with primary green color
            const fabHtml = `
                <div class="floating-actions position-fixed" style="bottom: 30px; right: 30px; z-index: 1000; opacity: 0;">
                    <div class="d-flex flex-column gap-3">
                        <button class="btn rounded-circle p-3 shadow" onclick="shareContent()" title="Bagikan"
                                style="width: 50px; height: 50px; background: #00B98E; color: white; border: none;">
                            <i class="bi bi-share-fill"></i>
                        </button>
                        <button class="btn btn-secondary rounded-circle p-3 shadow" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" title="Ke Atas"
                                style="width: 50px; height: 50px;">
                            <i class="bi bi-arrow-up"></i>
                        </button>
                    </div>
                </div>
            `;

            setTimeout(() => {
                $('body').append(fabHtml);
            }, 1500);

            // Show/hide FAB based on scroll
            $(window).on('scroll', function() {
                const scrollTop = $(window).scrollTop();
                const fabContainer = $('.floating-actions');

                if (scrollTop > 400) {
                    fabContainer.css('opacity', '1');
                } else {
                    fabContainer.css('opacity', '0');
                }
            });

            // Initialize animations
            setTimeout(() => {
                $('.fade-in-up').each(function(index) {
                    setTimeout(() => {
                        $(this).css({
                            'opacity': '1',
                            'transform': 'translateY(0)'
                        });
                    }, index * 200);
                });

                animateGalleryItems();
            }, 300);

            // Track viewing analytics
            let startTime = Date.now();
            let maxScroll = 0;

            $(window).on('scroll', function() {
                const scrollPercent = Math.round(($(window).scrollTop() / ($(document).height() - $(window).height())) * 100);
                maxScroll = Math.max(maxScroll, scrollPercent);
            });

            $(window).on('beforeunload', function() {
                const viewTime = Math.round((Date.now() - startTime) / 1000);

                if (viewTime > 10) {
                    console.log('View Analytics:', {
                        bankDataId: {{ $bankData->id }},
                        viewTime: viewTime,
                        maxScroll: maxScroll,
                        timestamp: new Date().toISOString()
                    });
                }
            });

            console.log('✅ Bank Data detail page initialized successfully');
        });
    </script>

<!-- Additional CSS for floating actions -->
<style>
.floating-actions {
    transition: opacity 0.3s ease;
}

.floating-actions .btn {
    transition: all 0.3s ease;
}

.floating-actions .btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,185,142,0.3) !important;
}

.floating-actions .btn:first-child:hover {
    background: #009975 !important;
}

@media (max-width: 768px) {
    .floating-actions {
        bottom: 20px !important;
        right: 20px !important;
    }

    .floating-actions .btn {
        width: 45px !important;
        height: 45px !important;
        padding: 10px !important;
        font-size: 0.9rem;
    }
}
</style>
@endpush
