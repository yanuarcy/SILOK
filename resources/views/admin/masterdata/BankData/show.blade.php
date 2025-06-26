@extends('Template.template')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">

    <style>
        .detail-section {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #fdfdfd;
        }

        .detail-section h6 {
            color: #495057;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .detail-item {
            margin-bottom: 15px;
        }

        .detail-label {
            font-weight: 500;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .detail-value {
            color: #2c3e50;
            font-size: 15px;
            line-height: 1.5;
        }

        .detail-value.empty {
            color: #999;
            font-style: italic;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-published {
            background-color: #d4edda;
            color: #155724;
        }

        .status-draft {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-archived {
            background-color: #f8d7da;
            color: #721c24;
        }

        .active-badge {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .inactive-badge {
            background-color: #f8d7da;
            color: #721c24;
        }

        .tags-container {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .tag-item {
            background-color: #6777ef;
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .breadcrumb-item a {
            color: #6777ef;
            text-decoration: none;
        }

        .breadcrumb-item a:hover {
            text-decoration: underline;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .section-divider {
            border-top: 2px solid #e9ecef;
            margin: 30px 0;
            padding-top: 20px;
        }

        /* Gallery Styles */
        .gallery-navigation {
            margin-bottom: 30px;
            text-align: center;
        }

        .gallery-nav-btn {
            transition: all 0.3s ease;
            margin: 0 5px;
        }

        .gallery-nav-btn.active {
            background-color: #6777ef !important;
            color: white !important;
            border-color: #6777ef !important;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }

        .gallery-item {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(103, 119, 239, 0.1);
            transition: all 0.3s ease;
            background: #f8f9fa;
            aspect-ratio: 16/9;
            cursor: pointer;
        }

        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(103, 119, 239, 0.2);
        }

        .gallery-item img,
        .gallery-item video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .gallery-item:hover img,
        .gallery-item:hover video {
            transform: scale(1.05);
        }

        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(103, 119, 239, 0.8), rgba(67, 94, 190, 0.8));
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
            font-size: 2rem;
            margin-bottom: 8px;
            display: block;
        }

        .gallery-overlay-content span {
            font-size: 0.9rem;
            font-weight: 600;
        }

        .video-indicator {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .no-files {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .no-files i {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        /* Modal Styles */
        #lightboxModal {
            z-index: 9999 !important;
        }

        .modal-backdrop {
            z-index: 9998 !important;
        }

        #lightboxModal .modal-content {
            background: #000;
            border: none;
        }

        #lightboxModal .modal-body {
            padding: 0;
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #lightboxModal .btn-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.9);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            z-index: 10000;
            transition: all 0.3s ease;
        }

        #lightboxModal .btn-nav:hover {
            background: white;
            transform: translateY(-50%) scale(1.1);
        }

        #lightboxModal .btn-prev {
            left: 20px;
        }

        #lightboxModal .btn-next {
            right: 20px;
        }

        #lightboxModal .lightbox-counter {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            z-index: 10001;
        }

        /* Download Button Styles */
        .btn-download {
            background: rgba(40, 167, 69, 0.9) !important;
            border: none !important;
            border-radius: 50% !important;
            width: 35px !important;
            height: 35px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all 0.3s ease !important;
            opacity: 0;
            transform: scale(0.8);
        }

        .gallery-item:hover .btn-download {
            opacity: 1;
            transform: scale(1);
        }

        .btn-download:hover {
            background: rgba(40, 167, 69, 1) !important;
            transform: scale(1.1) !important;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4) !important;
        }

        .btn-download i {
            font-size: 0.8rem;
            color: white !important;
        }

        @media (max-width: 768px) {
            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 15px;
            }

            .gallery-navigation {
                margin-bottom: 20px;
            }

            .gallery-nav-btn {
                font-size: 0.85rem;
                padding: 8px 16px;
            }
        }
    </style>
@endpush

@section('Dashboard')
    @include('admin.dashboard.header')
    @include('admin.dashboard.sidebar')

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Detail Bank Data</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('admin.masterdata.BankData.index') }}">Data Bank Data</a>
                    </div>
                    <div class="breadcrumb-item">Detail Bank Data</div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4>{{ $bankData->judul_kegiatan }}</h4>
                            <div>
                                @if($bankData->canBeEditedBy(Auth::user()))
                                    <a href="{{ route('admin.masterdata.BankData.edit', $bankData->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                @endif
                                <a href="{{ route('admin.masterdata.BankData.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Basic Information -->
                            <div class="detail-section">
                                <h6><i class="fas fa-info-circle text-primary"></i> Informasi Dasar</h6>
                                <div class="info-grid">
                                    <div class="detail-item">
                                        <div class="detail-label">Jenis Bank Data</div>
                                        <div class="detail-value">
                                            @php
                                                $jenisOptions = [
                                                    'Kelurahan' => 'Bank Data Kelurahan',
                                                    'RW' => 'Bank Data RW',
                                                    'RT' => 'Bank Data RT'
                                                ];
                                            @endphp
                                            {{ $jenisOptions[$bankData->jenis_bank_data] ?? $bankData->jenis_bank_data }}
                                        </div>
                                    </div>

                                    @if($bankData->jenis_bank_data === 'RW' || $bankData->jenis_bank_data === 'RT')
                                        <div class="detail-item">
                                            <div class="detail-label">Wilayah</div>
                                            <div class="detail-value">
                                                @if($bankData->jenis_bank_data === 'RW')
                                                    RW {{ $bankData->nomor_rw }}
                                                @else
                                                    RT {{ $bankData->nomor_rt }} RW {{ $bankData->nomor_rw }}
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <div class="detail-item">
                                        <div class="detail-label">Tanggal Kegiatan</div>
                                        <div class="detail-value {{ !$bankData->tanggal_kegiatan ? 'empty' : '' }}">
                                            {{ $bankData->tanggal_kegiatan ? $bankData->tanggal_kegiatan->format('d F Y') : 'Tanggal tidak diset' }}
                                        </div>
                                    </div>

                                    <div class="detail-item">
                                        <div class="detail-label">Lokasi Kegiatan</div>
                                        <div class="detail-value {{ !$bankData->lokasi ? 'empty' : '' }}">
                                            {{ $bankData->lokasi ?: 'Tidak ada lokasi' }}
                                        </div>
                                    </div>

                                    <div class="detail-item">
                                        <div class="detail-label">Status</div>
                                        <div class="detail-value">
                                            <span class="status-badge status-{{ strtolower($bankData->status) }}">
                                                {{ $bankData->status }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="detail-item">
                                        <div class="detail-label">Status Aktif</div>
                                        <div class="detail-value">
                                            <span class="status-badge {{ $bankData->is_active ? 'active-badge' : 'inactive-badge' }}">
                                                {{ $bankData->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="detail-item">
                                        <div class="detail-label">Urutan Tampil</div>
                                        <div class="detail-value">
                                            {{ $bankData->urutan_tampil ?: 'Tidak diatur' }}
                                        </div>
                                    </div>

                                    <div class="detail-item">
                                        <div class="detail-label">Jumlah Views</div>
                                        <div class="detail-value">
                                            <span class="badge badge-info">{{ $bankData->view_count ?? 0 }} views</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="detail-section">
                                <h6><i class="fas fa-file-alt text-success"></i> Deskripsi Kegiatan</h6>
                                <div class="detail-value">
                                    {!! nl2br(e($bankData->deskripsi)) !!}
                                </div>
                            </div>

                            <!-- Tags -->
                            @if($bankData->tags && count($bankData->tags) > 0)
                                <div class="detail-section">
                                    <h6><i class="fas fa-tags text-warning"></i> Tags/Kategori</h6>
                                    <div class="tags-container">
                                        @foreach($bankData->tags as $tag)
                                            <span class="tag-item">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Files Section -->
                            @if(($bankData->files_foto && count($bankData->files_foto) > 0) || ($bankData->files_video && count($bankData->files_video) > 0))
                                <div class="section-divider">
                                    <h6><i class="fas fa-folder-open text-info"></i> File Media</h6>
                                </div>

                                <!-- Gallery Navigation -->
                                <div class="gallery-navigation">
                                    @if($bankData->files_foto && count($bankData->files_foto) > 0)
                                        <button class="btn btn-outline-primary gallery-nav-btn active" data-tab="foto">
                                            <i class="fas fa-images"></i> Foto ({{ count($bankData->files_foto) }})
                                        </button>
                                    @endif
                                    @if($bankData->files_video && count($bankData->files_video) > 0)
                                        <button class="btn btn-outline-danger gallery-nav-btn {{ !$bankData->files_foto || count($bankData->files_foto) == 0 ? 'active' : '' }}" data-tab="video">
                                            <i class="fas fa-video"></i> Video ({{ count($bankData->files_video) }})
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
                                                     data-type="foto"
                                                     data-index="{{ $index }}"
                                                     data-src="{{ Storage::url($foto) }}">
                                                    <img src="{{ Storage::url($foto) }}" alt="Foto {{ $index + 1 }}" loading="lazy">
                                                    <div class="gallery-overlay">
                                                        <div class="gallery-overlay-content">
                                                            <i class="fas fa-search-plus"></i>
                                                            <span>Lihat Foto</span>
                                                        </div>
                                                    </div>
                                                    <!-- Download Button -->
                                                    <div class="position-absolute top-0 end-0 p-2">
                                                        <a href="{{ Storage::url($foto) }}"
                                                           class="btn btn-success btn-sm btn-download"
                                                           download
                                                           title="Download Foto {{ $index + 1 }}"
                                                           onclick="event.stopPropagation();">
                                                            <i class="fas fa-download"></i>
                                                        </a>
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
                                                     data-type="video"
                                                     data-index="{{ $index }}"
                                                     data-src="{{ Storage::url($video) }}">
                                                    <video muted preload="metadata" style="width: 100%; height: 100%; object-fit: cover;">
                                                        <source src="{{ Storage::url($video) }}" type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                    <div class="video-indicator">
                                                        <i class="fas fa-play"></i>
                                                        <span>Video</span>
                                                    </div>
                                                    <div class="gallery-overlay">
                                                        <div class="gallery-overlay-content">
                                                            <i class="fas fa-play-circle"></i>
                                                            <span>Putar Video</span>
                                                        </div>
                                                    </div>
                                                    <!-- Download Button -->
                                                    <div class="position-absolute top-0 end-0 p-2">
                                                        <a href="{{ Storage::url($video) }}"
                                                           class="btn btn-success btn-sm btn-download"
                                                           download
                                                           title="Download Video {{ $index + 1 }}"
                                                           onclick="event.stopPropagation();">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="detail-section">
                                    <div class="no-files">
                                        <i class="fas fa-folder-open"></i>
                                        <h5>Tidak Ada File</h5>
                                        <p>Belum ada foto atau video yang diupload untuk kegiatan ini.</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Metadata -->
                            <div class="detail-section">
                                <h6><i class="fas fa-clock text-secondary"></i> Informasi Sistem</h6>
                                <div class="info-grid">
                                    <div class="detail-item">
                                        <div class="detail-label">Dibuat Pada</div>
                                        <div class="detail-value">
                                            {{ $bankData->created_at ? $bankData->created_at->format('d F Y H:i') : 'Tidak tersedia' }}
                                        </div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Terakhir Diupdate</div>
                                        <div class="detail-value">
                                            {{ $bankData->updated_at ? $bankData->updated_at->format('d F Y H:i') : 'Tidak tersedia' }}
                                        </div>
                                    </div>
                                    @if($bankData->created_by)
                                        <div class="detail-item">
                                            <div class="detail-label">Dibuat Oleh</div>
                                            <div class="detail-value">{{ $bankData->created_by }}</div>
                                        </div>
                                    @endif
                                    @if($bankData->updated_by)
                                        <div class="detail-item">
                                            <div class="detail-label">Diupdate Oleh</div>
                                            <div class="detail-value">{{ $bankData->updated_by }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Lightbox Modal -->
    <div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 p-3 d-flex justify-content-between align-items-center">
                    <div class="lightbox-counter text-white">1 / 1</div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Image Display -->
                    <img id="lightboxImage"
                         src=""
                         alt=""
                         class="img-fluid w-100"
                         style="max-height: 80vh; object-fit: contain; display: none;">

                    <!-- Video Display -->
                    <video id="lightboxVideo"
                           class="w-100"
                           controls
                           preload="metadata"
                           style="max-height: 80vh; display: none;">
                        <source src="" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>

                    <!-- Navigation Buttons -->
                    <button id="lightboxPrev" class="btn btn-nav btn-prev" type="button">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button id="lightboxNext" class="btn btn-nav btn-next" type="button">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <div class="modal-footer border-0 p-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-2">
                        <a id="downloadBtn" href="" class="btn btn-success btn-sm" download>
                            <i class="fas fa-download"></i> Download
                        </a>
                        <button type="button" class="btn btn-info btn-sm" onclick="shareCurrentMedia()">
                            <i class="fas fa-share"></i> Share
                        </button>
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- General JS Scripts -->
    <script src="{{ asset('library/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('library/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            let currentGallery = [];
            let currentIndex = 0;
            let currentType = 'foto';
            let modalInstance = null;

            // Initialize galleries data
            const fotoGallery = @json($bankData->files_foto ? array_map(function($foto) { return Storage::url($foto); }, $bankData->files_foto) : []);
            const videoGallery = @json($bankData->files_video ? array_map(function($video) { return Storage::url($video); }, $bankData->files_video) : []);

            console.log('Galleries initialized:', { foto: fotoGallery.length, video: videoGallery.length });

            // Gallery tab switching
            $('.gallery-nav-btn').on('click', function() {
                const tab = $(this).data('tab');
                console.log('Switching to tab:', tab);

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
            });

            // Handle gallery item click
            $(document).on('click', '.gallery-item', function(e) {
                e.preventDefault();
                e.stopPropagation();

                currentType = $(this).data('type');
                currentIndex = parseInt($(this).data('index'));

                console.log('Gallery item clicked:', currentType, currentIndex);

                // Set current gallery
                if (currentType === 'foto') {
                    currentGallery = fotoGallery;
                } else {
                    currentGallery = videoGallery;
                }

                // Initialize modal
                const modalElement = document.getElementById('lightboxModal');
                modalInstance = new bootstrap.Modal(modalElement, {
                    backdrop: true,
                    keyboard: true,
                    focus: true
                });

                // Show modal
                modalInstance.show();

                // Load content after modal is shown
                setTimeout(() => {
                    showLightboxContent();
                }, 200);
            });

            // Navigation buttons
            $('#lightboxPrev').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Previous clicked');
                currentIndex = currentIndex > 0 ? currentIndex - 1 : currentGallery.length - 1;
                showLightboxContent();
            });

            $('#lightboxNext').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Next clicked');
                currentIndex = currentIndex < currentGallery.length - 1 ? currentIndex + 1 : 0;
                showLightboxContent();
            });

            // Keyboard navigation
            $(document).on('keydown', function(e) {
                if ($('#lightboxModal').is(':visible')) {
                    if (e.key === 'ArrowLeft') {
                        $('#lightboxPrev').click();
                    } else if (e.key === 'ArrowRight') {
                        $('#lightboxNext').click();
                    } else if (e.key === 'Escape') {
                        if (modalInstance) {
                            modalInstance.hide();
                        }
                    }
                }
            });

            function showLightboxContent() {
                const src = currentGallery[currentIndex];
                console.log('Showing content:', src, 'Type:', currentType);

                // Update counter
                $('.lightbox-counter').text(`${currentIndex + 1} / ${currentGallery.length}`);

                // Update download button
                $('#downloadBtn').attr('href', src);

                // Reset elements
                $('#lightboxImage, #lightboxVideo').hide();

                if (currentType === 'foto') {
                    // Show image
                    $('#lightboxImage').attr('src', src).show();

                    // Update download button text
                    $('#downloadBtn').html('<i class="fas fa-download"></i> Download Foto');

                    // Pause any video
                    const videoElement = $('#lightboxVideo')[0];
                    if (videoElement) {
                        videoElement.pause();
                        videoElement.currentTime = 0;
                    }
                } else {
                    // Show video
                    const videoElement = $('#lightboxVideo')[0];
                    const sourceElement = $('#lightboxVideo source');

                    if (videoElement && sourceElement) {
                        sourceElement.attr('src', src);
                        videoElement.load();
                        $('#lightboxVideo').show();

                        // Update download button text
                        $('#downloadBtn').html('<i class="fas fa-download"></i> Download Video');

                        // Auto play when ready
                        videoElement.addEventListener('loadeddata', function() {
                            console.log('Video loaded, attempting to play');
                            videoElement.play().catch(error => {
                                console.log('Autoplay prevented:', error);
                            });
                        }, { once: true });
                    }
                }

                // Show/hide navigation buttons
                if (currentGallery.length <= 1) {
                    $('.btn-nav').hide();
                } else {
                    $('.btn-nav').show();
                }
            }

            // Handle download button with loading state
            $(document).on('click', '.btn-download, #downloadBtn', function() {
                const btn = $(this);
                const originalHtml = btn.html();

                btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

                setTimeout(() => {
                    btn.html(originalHtml).prop('disabled', false);
                }, 1500);
            });

            // Share functionality
            window.shareCurrentMedia = function() {
                const src = currentGallery[currentIndex];
                const mediaType = currentType === 'foto' ? 'Foto' : 'Video';
                const title = `${mediaType} - {{ $bankData->judul_kegiatan }}`;

                if (navigator.share) {
                    navigator.share({
                        title: title,
                        text: `Lihat ${mediaType} dari kegiatan: {{ $bankData->judul_kegiatan }}`,
                        url: src
                    }).catch(err => console.log('Error sharing:', err));
                } else {
                    // Fallback: copy to clipboard
                    navigator.clipboard.writeText(src).then(() => {
                        alert('Link media berhasil disalin ke clipboard!');
                    }).catch(() => {
                        alert('Gagal menyalin link');
                    });
                }
            };

            // Handle modal close
            $('#lightboxModal').on('hidden.bs.modal', function() {
                console.log('Modal hidden');

                // Stop video
                const videoElement = $('#lightboxVideo')[0];
                if (videoElement) {
                    videoElement.pause();
                    videoElement.currentTime = 0;
                }

                // Clear sources
                $('#lightboxImage').attr('src', '');
                $('#lightboxVideo source').attr('src', '');

                // Hide elements
                $('#lightboxImage, #lightboxVideo').hide();

                // Reset modal instance
                modalInstance = null;
            });

            // Video hover preview
            $('.gallery-item video').each(function() {
                const video = this;
                const $galleryItem = $(this).closest('.gallery-item');

                $galleryItem.on('mouseenter', function() {
                    if (video.readyState >= 2) {
                        video.currentTime = 1; // Start from 1 second
                        video.play().catch(() => {
                            // Auto-play failed, which is normal
                        });
                    }
                });

                $galleryItem.on('mouseleave', function() {
                    video.pause();
                    video.currentTime = 0;
                });

                // Handle video errors
                video.addEventListener('error', function(e) {
                    console.error('Video error:', e);
                    $galleryItem.find('.video-indicator').html('<i class="fas fa-exclamation-triangle"></i><span>Error</span>');
                });
            });

            // Initialize default gallery
            if (fotoGallery.length > 0) {
                currentType = 'foto';
                $('#foto-gallery').show();
                $('#video-gallery').hide();
            } else if (videoGallery.length > 0) {
                currentType = 'video';
                $('#foto-gallery').hide();
                $('#video-gallery').show();
                $('.gallery-nav-btn[data-tab="video"]').addClass('active');
                $('.gallery-nav-btn[data-tab="foto"]').removeClass('active');
            }

            console.log('✅ Bank Data detail page initialized successfully');
        });
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
