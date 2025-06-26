@extends('Template.template')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">

    <style>
        <style>
        .pdf-container {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .pdf-header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .pdf-viewer {
            width: 100%;
            height: 80vh;
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        .info-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .info-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #6777ef;
        }

        .info-label {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .info-value {
            color: #2c3e50;
            font-weight: 500;
            font-size: 1rem;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .btn-action {
            padding: 10px 20px;
            border-radius: 25px;
            border: none;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-back {
            background: #6c757d;
            color: white;
        }

        .btn-back:hover {
            background: #5a6268;
            color: white;
            transform: translateY(-2px);
        }

        .btn-edit {
            background: #ffc107;
            color: #212529;
        }

        .btn-edit:hover {
            background: #e0a800;
            color: #212529;
            transform: translateY(-2px);
        }

        .btn-download {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
        }

        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
            color: white;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background: #c82333;
            color: white;
            transform: translateY(-2px);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .stats-item {
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
        }

        .stats-number {
            font-size: 2rem;
            font-weight: bold;
            display: block;
        }

        .stats-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .tag-container {
            margin-top: 15px;
        }

        .tag-item {
            display: inline-block;
            background: #e9ecef;
            color: #495057;
            padding: 5px 12px;
            margin: 3px;
            border-radius: 15px;
            font-size: 0.85rem;
        }

        @media (max-width: 768px) {
            .action-buttons {
                flex-direction: column;
            }

            .pdf-viewer {
                height: 60vh;
            }

            .info-grid {
                grid-template-columns: 1fr;
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
                <h1>Detail Peraturan Perundang-undangan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('admin.Perpu.index') }}">Data Peraturan</a>
                    </div>
                    <div class="breadcrumb-item">Detail</div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <a href="{{ route('admin.Perpu.index') }}" class="btn-action btn-back">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('admin.Perpu.edit', $perpu) }}" class="btn-action btn-edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.Perpu.download', $perpu->id) }}" class="btn-action btn-download">
                            <i class="fas fa-download"></i> Download
                        </a>
                        <button class="btn-action btn-delete" onclick="confirmDelete()">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>

                    <!-- Document Info -->
                    <div class="info-card">
                        <h4 class="mb-4">{{ $perpu->full_title }}</h4>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Jenis Peraturan</div>
                                <div class="info-value">{!! $perpu->jenis_badge !!}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Nomor Peraturan</div>
                                <div class="info-value">{{ $perpu->nomor_peraturan }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Tahun</div>
                                <div class="info-value">{{ $perpu->tahun }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Tanggal Penetapan</div>
                                <div class="info-value">{{ $perpu->tanggal_penetapan->format('d F Y') }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Status</div>
                                <div class="info-value">
                                    {!! $perpu->status_badge !!}
                                    {!! $perpu->active_badge !!}
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Ukuran File</div>
                                <div class="info-value">{{ $perpu->formatted_file_size }}</div>
                            </div>
                        </div>

                        <div class="info-item mt-3">
                            <div class="info-label">Tentang</div>
                            <div class="info-value">{{ $perpu->tentang }}</div>
                        </div>

                        @if($perpu->deskripsi)
                            <div class="info-item mt-3">
                                <div class="info-label">Deskripsi</div>
                                <div class="info-value">{{ $perpu->deskripsi }}</div>
                            </div>
                        @endif

                        @if($perpu->tags && count($perpu->tags) > 0)
                            <div class="tag-container">
                                <div class="info-label">Tags</div>
                                @foreach($perpu->tags as $tag)
                                    <span class="tag-item"># {{ $tag }}</span>
                                @endforeach
                            </div>
                        @endif

                        <!-- Statistics -->
                        <div class="stats-grid">
                            <div class="stats-item">
                                <span class="stats-number">{{ number_format($perpu->download_count) }}</span>
                                <span class="stats-label">Total Downloads</span>
                            </div>
                            <div class="stats-item">
                                <span class="stats-number">{{ $perpu->urutan_tampil }}</span>
                                <span class="stats-label">Urutan Tampil</span>
                            </div>
                            <div class="stats-item">
                                <span class="stats-number">{{ $perpu->tanggal_upload->format('d/m/Y') }}</span>
                                <span class="stats-label">Tanggal Upload</span>
                            </div>
                        </div>
                    </div>

                    <!-- PDF Viewer -->
                    <div class="pdf-container">
                        <div class="pdf-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ $perpu->file_pdf }}</h5>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary" onclick="refreshPdf()">
                                        <i class="fas fa-sync-alt"></i> Refresh
                                    </button>
                                    <button class="btn btn-sm btn-outline-info" onclick="openFullscreen()">
                                        <i class="fas fa-expand"></i> Fullscreen
                                    </button>
                                </div>
                            </div>
                        </div>

                        <embed src="{{ asset('storage/perpu/' . $perpu->file_pdf) }}#toolbar=1&navpanes=1&scrollbar=1"
                               type="application/pdf"
                               class="pdf-viewer"
                               id="pdfViewer">
                    </div>

                    <!-- Delete Form (Hidden) -->
                    <form id="deleteForm" action="{{ route('admin.Perpu.destroy', $perpu->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
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
            // Check if PDF file exists and handle errors
            $('#pdfViewer').on('error', function() {
                $(this).replaceWith(
                    '<div class="alert alert-danger text-center" style="height: 80vh; display: flex; align-items: center; justify-content: center; flex-direction: column;">' +
                    '<i class="fas fa-exclamation-triangle fa-3x mb-3"></i>' +
                    '<h5>File PDF tidak dapat dimuat</h5>' +
                    '<p>File mungkin rusak atau tidak ditemukan.</p>' +
                    '<a href="{{ route('admin.Perpu.edit', $perpu) }}" class="btn btn-warning">Upload Ulang File</a>' +
                    '</div>'
                );
            });

            // Auto-refresh PDF every 30 seconds (optional)
            // setInterval(refreshPdf, 30000);
        });

        function refreshPdf() {
            const pdfViewer = document.getElementById('pdfViewer');
            const currentSrc = pdfViewer.src;
            pdfViewer.src = currentSrc + '?t=' + new Date().getTime();
        }

        function openFullscreen() {
            const pdfViewer = document.getElementById('pdfViewer');
            if (pdfViewer.requestFullscreen) {
                pdfViewer.requestFullscreen();
            } else if (pdfViewer.webkitRequestFullscreen) {
                pdfViewer.webkitRequestFullscreen();
            } else if (pdfViewer.msRequestFullscreen) {
                pdfViewer.msRequestFullscreen();
            }
        }

        function confirmDelete() {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: 'Apakah Anda yakin?',
                text: `Hapus peraturan "${@json($perpu->full_title)}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: $('#deleteForm').attr('action'),
                        method: 'DELETE',
                        data: $('#deleteForm').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            swalWithBootstrapButtons.fire(
                                'Terhapus!',
                                'Data peraturan berhasil dihapus.',
                                'success'
                            ).then(() => {
                                window.location.href = "{{ route('admin.Perpu.index') }}";
                            });
                        },
                        error: function(xhr) {
                            swalWithBootstrapButtons.fire(
                                'Error!',
                                xhr.responseJSON?.message || 'Terjadi kesalahan saat menghapus.',
                                'error'
                            );
                        }
                    });
                }
            });
        }

        // Keyboard shortcuts
        $(document).on('keydown', function(e) {
            // ESC to go back
            if (e.keyCode === 27) {
                window.location.href = "{{ route('admin.Perpu.index') }}";
            }
            // Ctrl+E to edit
            if (e.ctrlKey && e.keyCode === 69) {
                e.preventDefault();
                window.location.href = "{{ route('admin.Perpu.edit', $perpu) }}";
            }
            // Ctrl+D to download
            if (e.ctrlKey && e.keyCode === 68) {
                e.preventDefault();
                window.location.href = "{{ route('admin.Perpu.download', $perpu->id) }}";
            }
            // F11 for fullscreen
            if (e.keyCode === 122) {
                e.preventDefault();
                openFullscreen();
            }
        });
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
