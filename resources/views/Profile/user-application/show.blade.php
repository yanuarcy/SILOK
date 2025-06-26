@extends('Template.template')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">

    <style>
        .form-control:focus {
            border-color: #6777ef;
            box-shadow: 0 0 0 0.2rem rgba(103, 119, 239, 0.25);
        }

        .form-control[readonly] {
            background-color: #f8f9fa;
            border-color: #e9ecef;
            cursor: default;
        }

        .signature-display {
            border: 2px solid #dee2e6;
            border-radius: 8px;
            background-color: #f8f9fa;
            min-height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .signature-display img {
            max-width: 100%;
            max-height: 130px;
            border-radius: 4px;
        }

        .signature-display.empty {
            color: #6c757d;
            font-style: italic;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .section-divider {
            border-top: 2px solid #e9ecef;
            margin: 30px 0 20px 0;
            padding-top: 20px;
        }

        .status-badge {
            font-size: 14px;
            padding: 8px 16px;
            border-radius: 20px;
        }

        .file-display {
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            background-color: #f8f9fa;
        }

        .file-info {
            background-color: #e8f5e8;
            border-radius: 8px;
            padding: 15px;
            border-left: 4px solid #28a745;
        }

        .workflow-progress {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .workflow-step {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 6px;
        }

        .workflow-step.completed {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }

        .workflow-step.current {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
        }

        .workflow-step.pending {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
        }

        .workflow-step.rejected {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }

        .workflow-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 16px;
        }

        .workflow-icon.completed {
            background-color: #28a745;
            color: white;
        }

        .workflow-icon.current {
            background-color: #ffc107;
            color: #212529;
        }

        .workflow-icon.pending {
            background-color: #6c757d;
            color: white;
        }

        .workflow-icon.rejected {
            background-color: #dc3545;
            color: white;
        }

        .document-actions {
            background: #e8f4f8;
            border: 1px solid #bee5eb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .metadata-section {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .application-type-badge {
            display: inline-block;
            padding: 8px 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        @media print {
            .document-actions,
            .card-footer,
            .btn,
            .breadcrumb {
                display: none !important;
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
                <h1>Detail Permohonan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('user-applications.index') }}">Permohonan Saya</a>
                    </div>
                    <div class="breadcrumb-item">{{ $application->nomor_surat }}</div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <!-- Document Status & Actions -->
                    <div class="document-actions">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="application-type-badge">
                                    {{ $application->jenis_permohonan }}
                                </div>
                                <h5 class="mb-2">
                                    <i class="fas fa-file-alt text-primary"></i>
                                    {{ $application->nomor_surat }}
                                </h5>
                                <p class="mb-0 text-muted">
                                    {{ $application->judul_permohonan }}
                                </p>
                                <p class="mb-0 text-muted">
                                    Diajukan pada {{ $application->formatted_created_date }}
                                </p>
                                <p class="mb-0 text-info">
                                    <strong>RT {{ $application->rt }} / RW {{ $application->rw }}</strong>
                                </p>
                            </div>
                            <div class="col-md-4 text-right">
                                <div class="mb-2">
                                    {!! $application->status_badge !!}
                                </div>
                                <div class="btn-group">
                                    @if($application->canPreviewPDF())
                                        <a href="{{ route('user-applications.preview-pdf', $application->id) }}"
                                           class="btn btn-secondary btn-sm"
                                           target="_blank">
                                            <i class="fas fa-eye"></i> Preview PDF
                                        </a>
                                    @endif

                                    @if($application->canDownloadPDF())
                                        <a href="{{ route('user-applications.download-pdf', $application->id) }}"
                                           class="btn btn-success btn-sm">
                                            <i class="fas fa-download"></i> Download PDF
                                        </a>
                                    @endif

                                    <a href="{{ route('user-applications.index') }}" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Workflow Progress -->
                    <div class="workflow-progress">
                        <h6 class="mb-3"><i class="fas fa-tasks"></i> Progress Persetujuan</h6>

                        <div class="workflow-step {{ $application->workflow_progress['submitted'] ? 'completed' : 'pending' }}">
                            <div class="workflow-icon {{ $application->workflow_progress['submitted'] ? 'completed' : 'pending' }}">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                            <div>
                                <strong>Dokumen Diajukan</strong>
                                <div class="text-muted small">{{ $application->formatted_created_date }}</div>
                            </div>
                        </div>

                        <div class="workflow-step {{ $application->status === 'rejected_rt' ? 'rejected' : ($application->workflow_progress['rt_approved'] ? 'completed' : ($application->status === 'pending_rt' ? 'current' : 'pending')) }}">
                            <div class="workflow-icon {{ $application->status === 'rejected_rt' ? 'rejected' : ($application->workflow_progress['rt_approved'] ? 'completed' : ($application->status === 'pending_rt' ? 'current' : 'pending')) }}">
                                <i class="fas {{ $application->status === 'rejected_rt' ? 'fa-times' : 'fa-user-check' }}"></i>
                            </div>
                            <div>
                                <strong>Persetujuan RT {{ $application->rt }}</strong>
                                @if($application->approved_rt_at)
                                    <div class="text-muted small">
                                        {{ $application->status === 'rejected_rt' ? 'Ditolak' : 'Disetujui' }} pada {{ $application->approved_rt_at->format('d/m/Y') }}
                                        @if($application->approverRT)
                                            oleh {{ $application->approverRT->name }}
                                        @endif
                                    </div>
                                    @if($application->catatan_rt)
                                        <div class="text-info small mt-1">
                                            <i class="fas fa-comment"></i> {{ $application->catatan_rt }}
                                        </div>
                                    @endif
                                @else
                                    <div class="text-muted small">Menunggu persetujuan RT {{ $application->rt }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="workflow-step {{ $application->status === 'rejected_rw' ? 'rejected' : ($application->workflow_progress['rw_approved'] ? 'completed' : (in_array($application->status, ['approved_rt', 'pending_rw']) ? 'current' : 'pending')) }}">
                            <div class="workflow-icon {{ $application->status === 'rejected_rw' ? 'rejected' : ($application->workflow_progress['rw_approved'] ? 'completed' : (in_array($application->status, ['approved_rt', 'pending_rw']) ? 'current' : 'pending')) }}">
                                <i class="fas {{ $application->status === 'rejected_rw' ? 'fa-times' : 'fa-user-check' }}"></i>
                            </div>
                            <div>
                                <strong>Persetujuan RW {{ $application->rw }}</strong>
                                @if($application->approved_rw_at)
                                    <div class="text-muted small">
                                        {{ $application->status === 'rejected_rw' ? 'Ditolak' : 'Disetujui' }} pada {{ $application->approved_rw_at->format('d/m/Y') }}
                                        @if($application->approverRW)
                                            oleh {{ $application->approverRW->name }}
                                        @endif
                                    </div>
                                    @if($application->catatan_rw)
                                        <div class="text-info small mt-1">
                                            <i class="fas fa-comment"></i> {{ $application->catatan_rw }}
                                        </div>
                                    @endif
                                @else
                                    <div class="text-muted small">Menunggu persetujuan RW {{ $application->rw }}</div>
                                @endif
                            </div>
                        </div>

                        @if($application->workflow_progress['needs_kelurahan'])
                        <div class="workflow-step {{ $application->status === 'rejected_kelurahan' ? 'rejected' : ($application->workflow_progress['kelurahan_approved'] ? 'completed' : (in_array($application->status, ['approved_rw', 'pending_kelurahan']) ? 'current' : 'pending')) }}">
                            <div class="workflow-icon {{ $application->status === 'rejected_kelurahan' ? 'rejected' : ($application->workflow_progress['kelurahan_approved'] ? 'completed' : (in_array($application->status, ['approved_rw', 'pending_kelurahan']) ? 'current' : 'pending')) }}">
                                <i class="fas {{ $application->status === 'rejected_kelurahan' ? 'fa-times' : 'fa-university' }}"></i>
                            </div>
                            <div>
                                <strong>Persetujuan Kelurahan</strong>
                                @if($application->approved_kelurahan_at)
                                    <div class="text-muted small">
                                        {{ $application->status === 'rejected_kelurahan' ? 'Ditolak' : 'Disetujui' }} pada {{ $application->approved_kelurahan_at->format('d/m/Y') }}
                                        @if($application->approverKelurahan)
                                            oleh {{ $application->approverKelurahan->name }}
                                        @endif
                                    </div>
                                    @if($application->catatan_kelurahan)
                                        <div class="text-info small mt-1">
                                            <i class="fas fa-comment"></i> {{ $application->catatan_kelurahan }}
                                        </div>
                                    @endif
                                @else
                                    <div class="text-muted small">Menunggu persetujuan Kelurahan</div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="workflow-step {{ $application->workflow_progress['completed'] ? 'completed' : 'pending' }}">
                            <div class="workflow-icon {{ $application->workflow_progress['completed'] ? 'completed' : 'pending' }}">
                                <i class="fas fa-check-double"></i>
                            </div>
                            <div>
                                <strong>Dokumen Selesai</strong>
                                @if($application->workflow_progress['completed'])
                                    <div class="text-muted small">Dokumen siap diunduh</div>
                                @else
                                    <div class="text-muted small">Menunggu semua persetujuan</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Application Details -->
                    <div class="card">
                        <div class="card-header">
                            <h4>Detail Permohonan - {{ $application->nomor_surat }}</h4>
                        </div>
                        <div class="card-body">
                            {{-- Basic Information --}}
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="text-primary">
                                        <i class="fas fa-info-circle"></i> Informasi Dasar
                                    </h5>
                                    <hr>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nomor Surat</label>
                                        <input type="text" class="form-control" value="{{ $application->nomor_surat }}" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label>Jenis Permohonan</label>
                                        <input type="text" class="form-control" value="{{ $application->jenis_permohonan }}" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label>Nama Pemohon</label>
                                        <input type="text" class="form-control" value="{{ $application->nama_pemohon }}" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label>NIK</label>
                                        <input type="text" class="form-control" value="{{ $application->nik }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tanggal Pengajuan</label>
                                        <input type="text" class="form-control" value="{{ $application->formatted_created_date }}" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label>Status Saat Ini</label>
                                        <div>{!! $application->status_badge !!}</div>
                                    </div>

                                    <div class="form-group">
                                        <label>RT / RW</label>
                                        <input type="text" class="form-control" value="{{ $application->rt_rw_display }}" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control" value="{{ $application->user->email }}" readonly>
                                    </div>
                                </div>
                            </div>

                            {{-- Judul dan Deskripsi --}}
                            <div class="section-divider">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="text-primary">
                                            <i class="fas fa-clipboard-list"></i> Detail Permohonan
                                        </h5>
                                        <hr>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Judul Permohonan</label>
                                            <input type="text" class="form-control" value="{{ $application->judul_permohonan }}" readonly>
                                        </div>

                                        @if($application->deskripsi_permohonan)
                                        <div class="form-group">
                                            <label>Deskripsi Permohonan</label>
                                            <textarea class="form-control" rows="3" readonly>{{ $application->deskripsi_permohonan }}</textarea>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Metadata khusus per jenis --}}
                            @if($application->metadata && count($application->metadata) > 0)
                            <div class="section-divider">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="text-primary">
                                            <i class="fas fa-list-alt"></i> Informasi Tambahan
                                        </h5>
                                        <hr>
                                    </div>
                                </div>

                                <div class="metadata-section">
                                    @if($application->jenis_permohonan === 'PUNTADEWA')
                                        <div class="row">
                                            @if(isset($application->metadata['alamat_asal']))
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Alamat Asal</label>
                                                    <textarea class="form-control" rows="2" readonly>{{ $application->metadata['alamat_asal'] }}</textarea>
                                                </div>
                                            </div>
                                            @endif

                                            @if(isset($application->metadata['nama_penjamin']))
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nama Penjamin</label>
                                                    <input type="text" class="form-control" value="{{ $application->metadata['nama_penjamin'] }}" readonly>
                                                </div>
                                            </div>
                                            @endif

                                            @if(isset($application->metadata['alamat_penjamin']))
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Alamat Penjamin</label>
                                                    <textarea class="form-control" rows="2" readonly>{{ $application->metadata['alamat_penjamin'] }}</textarea>
                                                </div>
                                            </div>
                                            @endif

                                            @if(isset($application->metadata['no_telp_penjamin']))
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>No. Telepon Penjamin</label>
                                                    <input type="text" class="form-control" value="{{ $application->metadata['no_telp_penjamin'] }}" readonly>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            {{-- File Information --}}
                            @if($application->file_pdf)
                            <div class="section-divider">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="text-primary">
                                            <i class="fas fa-file-pdf"></i> File Dokumen
                                        </h5>
                                        <hr>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="file-info">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-pdf fa-2x text-danger mr-3"></i>
                                                <div>
                                                    <div class="font-weight-bold">{{ basename($application->file_pdf) }}</div>
                                                    <div class="text-muted small">
                                                        Status: {{ $application->isApproved() ? 'Dokumen Final' : 'Draft/Preview' }}
                                                    </div>
                                                    <div class="text-muted small">
                                                        Total Download: {{ $application->download_count }} kali
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- Approval History --}}
                            <div class="section-divider">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="text-primary">
                                            <i class="fas fa-history"></i> Riwayat Persetujuan
                                        </h5>
                                        <hr>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="metadata-section">
                                            <div class="row">
                                                @if($application->approved_rt_at)
                                                <div class="col-md-4">
                                                    <h6 class="text-info">RT {{ $application->rt }}</h6>
                                                    <p class="mb-1">
                                                        <strong>{{ $application->status === 'rejected_rt' ? 'Ditolak' : 'Disetujui' }}</strong>
                                                    </p>
                                                    <p class="mb-1 text-muted small">
                                                        {{ $application->approved_rt_at->format('d/m/Y H:i') }}
                                                    </p>
                                                    @if($application->approverRT)
                                                    <p class="mb-1 text-muted small">
                                                        oleh: {{ $application->approverRT->name }}
                                                    </p>
                                                    @endif
                                                    @if($application->catatan_rt)
                                                    <p class="mb-0 text-info small">
                                                        <em>"{{ $application->catatan_rt }}"</em>
                                                    </p>
                                                    @endif
                                                </div>
                                                @endif

                                                @if($application->approved_rw_at)
                                                <div class="col-md-4">
                                                    <h6 class="text-info">RW {{ $application->rw }}</h6>
                                                    <p class="mb-1">
                                                        <strong>{{ $application->status === 'rejected_rw' ? 'Ditolak' : 'Disetujui' }}</strong>
                                                    </p>
                                                    <p class="mb-1 text-muted small">
                                                        {{ $application->approved_rw_at->format('d/m/Y H:i') }}
                                                    </p>
                                                    @if($application->approverRW)
                                                    <p class="mb-1 text-muted small">
                                                        oleh: {{ $application->approverRW->name }}
                                                    </p>
                                                    @endif
                                                    @if($application->catatan_rw)
                                                    <p class="mb-0 text-info small">
                                                        <em>"{{ $application->catatan_rw }}"</em>
                                                    </p>
                                                    @endif
                                                </div>
                                                @endif

                                                @if($application->approved_kelurahan_at)
                                                <div class="col-md-4">
                                                    <h6 class="text-info">Kelurahan</h6>
                                                    <p class="mb-1">
                                                        <strong>{{ $application->status === 'rejected_kelurahan' ? 'Ditolak' : 'Disetujui' }}</strong>
                                                    </p>
                                                    <p class="mb-1 text-muted small">
                                                        {{ $application->approved_kelurahan_at->format('d/m/Y H:i') }}
                                                    </p>
                                                    @if($application->approverKelurahan)
                                                    <p class="mb-1 text-muted small">
                                                        oleh: {{ $application->approverKelurahan->name }}
                                                    </p>
                                                    @endif
                                                    @if($application->catatan_kelurahan)
                                                    <p class="mb-0 text-info small">
                                                        <em>"{{ $application->catatan_kelurahan }}"</em>
                                                    </p>
                                                    @endif
                                                </div>
                                                @endif
                                            </div>

                                            @if(!$application->approved_rt_at && !$application->approved_rw_at && !$application->approved_kelurahan_at)
                                            <div class="text-center text-muted">
                                                <i class="fas fa-clock fa-2x mb-2"></i>
                                                <p>Belum ada riwayat persetujuan</p>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Link to Source Detail --}}
                            @if($sourceData)
                            <div class="section-divider">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="text-primary">
                                            <i class="fas fa-external-link-alt"></i> Detail Lengkap
                                        </h5>
                                        <hr>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="metadata-section text-center">
                                            <p class="mb-3">Untuk melihat detail lengkap permohonan ini, klik tombol di bawah:</p>
                                            <a href="{{ $application->detail_url }}" class="btn btn-primary">
                                                <i class="fas fa-eye"></i> Lihat Detail Lengkap {{ $application->jenis_permohonan }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
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

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            console.log('✅ User Application Show page initialized successfully');
        });
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
