@extends('Template.template')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">

    <style>
        .file-preview-container {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .file-preview-item {
            text-align: center;
            margin-bottom: 15px;
        }

        .file-preview-item img {
            max-width: 100%;
            max-height: 300px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .file-info-card {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
        }

        .info-value {
            color: #6c757d;
        }

        .badge-large {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
    </style>
@endpush

@section('Dashboard')
    @include('admin.dashboard.header')
    @include('admin.dashboard.sidebar')

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Detail Spesimen TTD & Stempel</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('admin.masterdata.Spesimen.index') }}">Spesimen TTD & Stempel</a>
                    </div>
                    <div class="breadcrumb-item">Detail Spesimen</div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ $spesimen->nama_pejabat }}</h4>
                            <div class="card-header-action">
                                @if($spesimen->canBeEditedBy(auth()->user()))
                                    <a href="{{ route('admin.masterdata.Spesimen.edit', $spesimen->id) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                @endif
                                <a href="{{ route('admin.masterdata.Spesimen.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Informasi Pejabat -->
                                <div class="col-md-4">
                                    <div class="file-info-card">
                                        <h6 class="mb-3"><i class="fas fa-user text-primary"></i> Informasi Pejabat</h6>

                                        <div class="info-item">
                                            <span class="info-label">Nama Pejabat:</span>
                                            <span class="info-value">{{ $spesimen->nama_pejabat }}</span>
                                        </div>

                                        <div class="info-item">
                                            <span class="info-label">User Account:</span>
                                            <span class="info-value">{{ $spesimen->user->name }}</span>
                                        </div>

                                        <div class="info-item">
                                            <span class="info-label">Jabatan:</span>
                                            <span class="info-value">{!! $spesimen->jabatan_badge !!}</span>
                                        </div>

                                        <div class="info-item">
                                            <span class="info-label">Wilayah:</span>
                                            <span class="info-value">{{ $spesimen->wilayah_lengkap }}</span>
                                        </div>

                                        <div class="info-item">
                                            <span class="info-label">Status:</span>
                                            <span class="info-value">{!! $spesimen->status_badge !!}</span>
                                        </div>

                                        <div class="info-item">
                                            <span class="info-label">Status Aktif:</span>
                                            <span class="info-value">{!! $spesimen->active_badge !!}</span>
                                        </div>

                                        @if($spesimen->keterangan)
                                            <div class="info-item">
                                                <span class="info-label">Keterangan:</span>
                                                <span class="info-value">{{ $spesimen->keterangan }}</span>
                                            </div>
                                        @endif

                                        <div class="info-item">
                                            <span class="info-label">Dibuat:</span>
                                            <span class="info-value">{{ $spesimen->created_at->format('d/m/Y H:i') }}</span>
                                        </div>

                                        <div class="info-item">
                                            <span class="info-label">Diupdate:</span>
                                            <span class="info-value">{{ $spesimen->updated_at->format('d/m/Y H:i') }}</span>
                                        </div>

                                        <div class="info-item">
                                            <span class="info-label">Dibuat oleh:</span>
                                            <span class="info-value">{{ $spesimen->creator->name }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- File Preview -->
                                <div class="col-md-8">
                                    <!-- TTD Preview -->
                                    @if($spesimen->file_ttd)
                                        <div class="file-preview-container">
                                            <h6 class="mb-3"><i class="fas fa-signature text-primary"></i> Tanda Tangan (TTD)</h6>
                                            <div class="file-preview-item">
                                                <img src="{{ $spesimen->ttd_url }}" alt="Tanda Tangan" class="img-fluid">
                                            </div>
                                            <div class="text-center">
                                                <small class="text-muted">{{ basename($spesimen->file_ttd) }}</small>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Stempel Preview -->
                                    @if($spesimen->file_stempel)
                                        <div class="file-preview-container">
                                            <h6 class="mb-3"><i class="fas fa-stamp text-success"></i> Stempel</h6>
                                            <div class="file-preview-item">
                                                <img src="{{ $spesimen->stempel_url }}" alt="Stempel" class="img-fluid">
                                            </div>
                                            <div class="text-center">
                                                <small class="text-muted">{{ basename($spesimen->file_stempel) }}</small>
                                            </div>
                                        </div>
                                    @endif

                                    @if(!$spesimen->file_ttd && !$spesimen->file_stempel)
                                        <div class="file-preview-container text-center">
                                            <i class="fas fa-file-image fa-3x text-muted mb-3"></i>
                                            <h6 class="text-muted">Tidak ada file yang diupload</h6>
                                            <p class="text-muted small">Belum ada file TTD atau Stempel untuk spesimen ini.</p>
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
@endsection

@push('scripts')
    <!-- General JS Scripts -->
    <script src="{{ asset('library/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('library/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Image zoom on click
            $('.file-preview-item img').on('click', function() {
                const imgSrc = $(this).attr('src');
                const imgAlt = $(this).attr('alt');

                Swal.fire({
                    title: imgAlt,
                    imageUrl: imgSrc,
                    imageAlt: imgAlt,
                    showCloseButton: true,
                    showConfirmButton: false,
                    width: 'auto',
                    customClass: {
                        image: 'img-fluid'
                    }
                });
            });

            // Add hover effect
            $('.file-preview-item img').on('mouseenter', function() {
                $(this).css({
                    'transform': 'scale(1.02)',
                    'transition': 'transform 0.3s ease',
                    'cursor': 'pointer'
                });
            }).on('mouseleave', function() {
                $(this).css('transform', 'scale(1)');
            });

            console.log('✅ Spesimen Show page initialized successfully');
        });
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
