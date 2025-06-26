@extends('Template.template')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">

    <style>
        .document-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .document-number {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .document-subtitle {
            opacity: 0.9;
            font-size: 1rem;
        }

        .info-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border: none;
            margin-bottom: 25px;
            overflow: hidden;
        }

        .info-card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 3px solid #e3f2fd;
            padding: 20px 25px;
            border-radius: 12px 12px 0 0;
        }

        .info-card-body {
            padding: 25px;
        }

        .info-row {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f1f3f4;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
            min-width: 180px;
            display: flex;
            align-items: center;
        }

        .info-label i {
            margin-right: 8px;
            color: #667eea;
            width: 16px;
        }

        .info-value {
            flex: 1;
            color: #212529;
            padding-left: 20px;
        }

        .progress-timeline {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            padding: 25px;
            margin-bottom: 25px;
        }

        .timeline-step {
            display: flex;
            align-items: center;
            padding: 15px 0;
            position: relative;
        }

        .timeline-step:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 19px;
            top: 50px;
            width: 2px;
            height: 30px;
            background: #dee2e6;
        }

        .timeline-step.completed::after {
            background: #28a745;
        }

        .step-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 14px;
            font-weight: 600;
            z-index: 1;
        }

        .step-icon.completed {
            background: #28a745;
            color: white;
        }

        .step-icon.current {
            background: #ffc107;
            color: #000;
        }

        .step-icon.pending {
            background: #e9ecef;
            color: #6c757d;
        }

        .step-icon.rejected {
            background: #dc3545;
            color: white;
        }

        .step-content h6 {
            margin: 0;
            font-weight: 600;
            color: #212529;
        }

        .step-content small {
            color: #6c757d;
        }

        .signature-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .signature-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            padding: 20px;
            text-align: center;
            border: 2px solid #f8f9fa;
            transition: all 0.3s ease;
        }

        .signature-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .signature-card.available {
            border-color: #28a745;
            background: linear-gradient(135deg, #f8fff9 0%, #ffffff 100%);
        }

        .signature-card.missing {
            border-color: #ffc107;
            background: linear-gradient(135deg, #fffbf0 0%, #ffffff 100%);
        }

        .signature-image {
            max-width: 100%;
            max-height: 150px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .approval-buttons {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 12px;
            padding: 25px;
            margin-top: 25px;
            text-align: center;
            border: 2px solid #e3f2fd;
        }

        .btn-approve {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            margin: 0 10px;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
            transition: all 0.3s ease;
        }

        .btn-approve:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        }

        .btn-reject {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            margin: 0 10px;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
            transition: all 0.3s ease;
        }

        .btn-reject:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
        }

        .action-buttons {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            padding: 25px;
            text-align: center;
        }

        .btn-action {
            margin: 5px 10px;
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-action:hover {
            transform: translateY(-2px);
        }

        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .status-pending {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
        }

        .status-approved {
            background: linear-gradient(135deg, #d4edda 0%, #b2dfdb 100%);
            color: #155724;
        }

        .status-rejected {
            background: linear-gradient(135deg, #f8d7da 0%, #ffcccb 100%);
            color: #721c24;
        }

        .notes-section {
            background: linear-gradient(135deg, #e3f2fd 0%, #ffffff 100%);
            border-left: 5px solid #2196f3;
            border-radius: 8px;
            padding: 20px;
            margin-top: 15px;
        }

        .breadcrumb-modern {
            background: transparent;
            padding: 0;
            margin-bottom: 20px;
        }

        .breadcrumb-modern .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
            color: #6c757d;
            font-weight: bold;
        }

        .document-meta {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 12px;
            padding: 20px;
            margin-top: 25px;
            border: 1px solid #e9ecef;
        }

        .meta-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f1f3f4;
        }

        .meta-item:last-child {
            border-bottom: none;
        }

        .meta-label {
            font-weight: 600;
            color: #495057;
        }

        .meta-value {
            color: #212529;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .signature-gallery {
                grid-template-columns: 1fr;
            }

            .document-header {
                padding: 20px;
                text-align: center;
            }

            .info-row {
                flex-direction: column;
                align-items: flex-start;
            }

            .info-label {
                min-width: auto;
                margin-bottom: 5px;
            }

            .info-value {
                padding-left: 0;
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
                <h1>{{ $pageTitle }}</h1>
                {{-- <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-modern">
                        <li class="breadcrumb-item">
                            <a href="{{ route('surat-pengantar.index') }}">Data Surat Pengantar</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Detail {{ $suratPengantar->nomor_surat }}
                        </li>
                    </ol>
                </nav> --}}
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('surat-pengantar.index') }}">Data Surat Pengantar</a>
                    </div>
                    <div class="breadcrumb-item">
                        Detail {{ $suratPengantar->nomor_surat }}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <!-- Document Header -->
                    <div class="document-header">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="document-number">
                                    <i class="fas fa-file-alt mr-3"></i>{{ $suratPengantar->nomor_surat }}
                                </div>
                                <div class="document-subtitle">
                                    Diajukan pada {{ $suratPengantar->formatted_created_date }} oleh {{ $suratPengantar->nama_lengkap }}
                                </div>
                                <div class="mt-2">
                                    <small><i class="fas fa-map-marker-alt mr-1"></i>RT {{ sprintf('%02d', $suratPengantar->rt) }} / RW {{ sprintf('%02d', $suratPengantar->rw) }}</small>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-right">
                                <div class="status-badge status-{{ $suratPengantar->status === 'approved_rw' ? 'approved' : ($suratPengantar->isRejected() ? 'rejected' : 'pending') }}">
                                    {{ $suratPengantar->status_text }}
                                </div>
                                <div class="mt-3">
                                    @if($suratPengantar->canPreviewPDF())
                                        <a href="{{ route('surat-pengantar.preview-pdf', $suratPengantar->id) }}"
                                           class="btn btn-outline-light btn-sm mr-2" target="_blank">
                                            <i class="fas fa-eye"></i> Preview PDF
                                        </a>
                                    @endif
                                    @if($suratPengantar->canDownloadPDF())
                                        <a href="{{ route('surat-pengantar.download-pdf', $suratPengantar->id) }}"
                                           class="btn btn-light btn-sm mr-2">
                                            <i class="fas fa-download"></i> Download PDF
                                        </a>
                                    @endif
                                    @if($suratPengantar->canBeEdited() && Auth::user()->role === 'user')
                                        <a href="{{ route('surat-pengantar.edit', $suratPengantar->id) }}"
                                           class="btn btn-warning btn-sm mr-2">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    @endif
                                    <a href="{{ route('surat-pengantar.index') }}" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Timeline -->
                    <div class="progress-timeline">
                        <h5 class="mb-4">
                            <i class="fas fa-clipboard-list text-primary mr-2"></i>Progress Persetujuan
                        </h5>

                        <div class="timeline-step completed">
                            <div class="step-icon completed">
                                <i class="fas fa-file-upload"></i>
                            </div>
                            <div class="step-content">
                                <h6>Dokumen Diajukan</h6>
                                <small>{{ $suratPengantar->formatted_created_date }}</small>
                            </div>
                        </div>

                        <div class="timeline-step {{ in_array($suratPengantar->status, ['approved_rt', 'approved_rw']) ? 'completed' : ($suratPengantar->status === 'rejected_rt' ? 'rejected' : 'pending') }}">
                            <div class="step-icon {{ in_array($suratPengantar->status, ['approved_rt', 'approved_rw']) ? 'completed' : ($suratPengantar->status === 'rejected_rt' ? 'rejected' : 'current') }}">
                                <i class="fas {{ $suratPengantar->status === 'rejected_rt' ? 'fa-times' : 'fa-user-check' }}"></i>
                            </div>
                            <div class="step-content">
                                <h6>Persetujuan RT {{ sprintf('%02d', $suratPengantar->rt) }}</h6>
                                <small>
                                    @if($suratPengantar->approved_rt_at)
                                        {{ $suratPengantar->status === 'rejected_rt' ? 'Ditolak' : 'Disetujui' }} pada {{ $suratPengantar->formatted_approved_rt_date }}
                                        oleh {{ $suratPengantar->approverRT->name ?? 'Ketua RT' }}
                                    @else
                                        Menunggu persetujuan RT
                                    @endif
                                </small>
                                @if($suratPengantar->catatan_rt)
                                    <div class="notes-section">
                                        <strong>Catatan RT:</strong><br>
                                        {{ $suratPengantar->catatan_rt }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="timeline-step {{ $suratPengantar->status === 'approved_rw' ? 'completed' : ($suratPengantar->status === 'rejected_rw' ? 'rejected' : 'pending') }}">
                            <div class="step-icon {{ $suratPengantar->status === 'approved_rw' ? 'completed' : ($suratPengantar->status === 'rejected_rw' ? 'rejected' : ($suratPengantar->status === 'approved_rt' ? 'current' : 'pending')) }}">
                                <i class="fas {{ $suratPengantar->status === 'rejected_rw' ? 'fa-times' : 'fa-user-check' }}"></i>
                            </div>
                            <div class="step-content">
                                <h6>Persetujuan RW {{ sprintf('%02d', $suratPengantar->rw) }}</h6>
                                <small>
                                    @if($suratPengantar->approved_rw_at)
                                        {{ $suratPengantar->status === 'rejected_rw' ? 'Ditolak' : 'Disetujui' }} pada {{ $suratPengantar->formatted_approved_rw_date }}
                                        oleh {{ $suratPengantar->approverRW->name ?? 'Ketua RW' }}
                                    @else
                                        Menunggu persetujuan RW
                                    @endif
                                </small>
                                @if($suratPengantar->catatan_rw)
                                    <div class="notes-section">
                                        <strong>Catatan RW:</strong><br>
                                        {{ $suratPengantar->catatan_rw }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="timeline-step {{ $suratPengantar->status === 'approved_rw' ? 'completed' : 'pending' }}">
                            <div class="step-icon {{ $suratPengantar->status === 'approved_rw' ? 'completed' : 'pending' }}">
                                <i class="fas fa-download"></i>
                            </div>
                            <div class="step-content">
                                <h6>Dokumen Selesai</h6>
                                <small>
                                    @if($suratPengantar->status === 'approved_rw')
                                        Dokumen siap diunduh
                                    @else
                                        Menunggu persetujuan lengkap
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Data Pemohon -->
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-user text-primary mr-2"></i>Data Pemohon
                                    </h5>
                                </div>
                                <div class="info-card-body">
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="fas fa-user"></i>Nama Lengkap
                                        </div>
                                        <div class="info-value">{{ $suratPengantar->nama_lengkap }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="fas fa-id-card"></i>NIK/KTP
                                        </div>
                                        <div class="info-value">{{ $suratPengantar->nomor_kk ?? $suratPengantar->nik }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="fas fa-map-marker-alt"></i>Alamat
                                        </div>
                                        <div class="info-value">{{ $suratPengantar->alamat }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="fas fa-briefcase"></i>Pekerjaan
                                        </div>
                                        <div class="info-value">{{ $suratPengantar->pekerjaan }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="fas fa-venus-mars"></i>Jenis Kelamin
                                        </div>
                                        <div class="info-value">{{ $suratPengantar->jenis_kelamin }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="fas fa-birthday-cake"></i>Tempat, Tgl Lahir
                                        </div>
                                        <div class="info-value">{{ $suratPengantar->tempat_lahir }}, {{ $suratPengantar->formatted_tanggal_lahir }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Data Tambahan -->
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-info-circle text-primary mr-2"></i>Data Tambahan
                                    </h5>
                                </div>
                                <div class="info-card-body">
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="fas fa-pray"></i>Agama
                                        </div>
                                        <div class="info-value">{{ $suratPengantar->agama }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="fas fa-heart"></i>Status Perkawinan
                                        </div>
                                        <div class="info-value">{{ $suratPengantar->status_perkawinan }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="fas fa-flag"></i>Kewarganegaraan
                                        </div>
                                        <div class="info-value">{{ $suratPengantar->kewarganegaraan }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="fas fa-crosshairs"></i>Tujuan
                                        </div>
                                        <div class="info-value">{{ $suratPengantar->tujuan }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="fas fa-clipboard-list"></i>Keperluan
                                        </div>
                                        <div class="info-value">{{ $suratPengantar->keperluan }}</div>
                                    </div>
                                    @if($suratPengantar->keterangan_lain)
                                    <div class="info-row">
                                        <div class="info-label">
                                            <i class="fas fa-comment"></i>Keterangan Lain
                                        </div>
                                        <div class="info-value">{{ $suratPengantar->keterangan_lain }}</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tanda Tangan Digital -->
                    <div class="info-card">
                        <div class="info-card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-signature text-primary mr-2"></i>Tanda Tangan Digital
                            </h5>
                        </div>
                        <div class="info-card-body">
                            <div class="signature-gallery">
                                <!-- TTD Pemohon -->
                                <div class="signature-card {{ $suratPengantar->hasPemohonSignature() ? 'available' : 'missing' }}">
                                    <h6 class="mb-3">
                                        <i class="fas fa-user mr-2"></i>Tanda Tangan Pemohon
                                    </h6>
                                    @if($suratPengantar->hasPemohonSignature())
                                        <img src="{{ $suratPengantar->ttd_pemohon_url }}"
                                             alt="TTD Pemohon"
                                             class="signature-image">
                                        <p class="mt-3 mb-0">
                                            <i class="fas fa-check-circle text-success mr-1"></i>
                                            <small class="text-success">Tersedia</small>
                                        </p>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-signature fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">Belum ditandatangani</p>
                                        </div>
                                    @endif
                                    <small class="text-muted d-block mt-2">{{ $suratPengantar->nama_lengkap }}</small>
                                </div>

                                <!-- TTD RT -->
                                <div class="signature-card {{ $suratPengantar->hasRTSignature() ? 'available' : 'missing' }}">
                                    <h6 class="mb-3">
                                        <i class="fas fa-user-tie mr-2"></i>Tanda Tangan RT
                                    </h6>
                                    @if($suratPengantar->hasRTSignature())
                                        <img src="{{ $suratPengantar->ttd_rt_url }}"
                                             alt="TTD RT"
                                             class="signature-image">
                                        <p class="mt-3 mb-0">
                                            <i class="fas fa-check-circle text-success mr-1"></i>
                                            <small class="text-success">Tersedia</small>
                                        </p>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-signature fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">Belum ditandatangani</p>
                                        </div>
                                    @endif
                                    <small class="text-muted d-block mt-2">
                                        {{ $suratPengantar->approverRT->name ?? 'Ketua RT ' . sprintf('%02d', $suratPengantar->rt) }}
                                    </small>
                                </div>

                                <!-- Stempel RT -->
                                @if($suratPengantar->hasRTStamp())
                                <div class="signature-card available">
                                    <h6 class="mb-3">
                                        <i class="fas fa-stamp mr-2"></i>Stempel RT
                                    </h6>
                                    <img src="{{ $suratPengantar->stempel_rt_url }}"
                                         alt="Stempel RT"
                                         class="signature-image">
                                    <p class="mt-3 mb-0">
                                        <i class="fas fa-check-circle text-success mr-1"></i>
                                        <small class="text-success">Tersedia</small>
                                    </p>
                                    <small class="text-muted d-block mt-2">Stempel RT {{ sprintf('%02d', $suratPengantar->rt) }}</small>
                                </div>
                                @endif

                                <!-- TTD RW -->
                                <div class="signature-card {{ $suratPengantar->hasRWSignature() ? 'available' : 'missing' }}">
                                    <h6 class="mb-3">
                                        <i class="fas fa-user-tie mr-2"></i>Tanda Tangan RW
                                    </h6>
                                    @if($suratPengantar->hasRWSignature())
                                        <img src="{{ $suratPengantar->ttd_rw_url }}"
                                             alt="TTD RW"
                                             class="signature-image">
                                        <p class="mt-3 mb-0">
                                            <i class="fas fa-check-circle text-success mr-1"></i>
                                            <small class="text-success">Tersedia</small>
                                        </p>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-signature fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">Belum ditandatangani</p>
                                        </div>
                                    @endif
                                    <small class="text-muted d-block mt-2">
                                        {{ $suratPengantar->approverRW->name ?? 'Ketua RW ' . sprintf('%02d', $suratPengantar->rw) }}
                                    </small>
                                </div>

                                <!-- Stempel RW -->
                                @if($suratPengantar->hasRWStamp())
                                <div class="signature-card available">
                                    <h6 class="mb-3">
                                        <i class="fas fa-stamp mr-2"></i>Stempel RW
                                    </h6>
                                    <img src="{{ $suratPengantar->stempel_rw_url }}"
                                         alt="Stempel RW"
                                         class="signature-image">
                                    <p class="mt-3 mb-0">
                                        <i class="fas fa-check-circle text-success mr-1"></i>
                                        <small class="text-success">Tersedia</small>
                                    </p>
                                    <small class="text-muted d-block mt-2">Stempel RW {{ sprintf('%02d', $suratPengantar->rw) }}</small>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Approval Buttons for RT/RW -->
                    @if($canApproveRT || $canApproveRW)
                    <div class="approval-buttons">
                        <h5 class="mb-4">
                            <i class="fas fa-gavel text-primary mr-2"></i>Panel Persetujuan
                        </h5>

                        @if($canApproveRT)
                        <div class="mb-3">
                            <h6 class="text-muted mb-3">Persetujuan RT {{ sprintf('%02d', $suratPengantar->rt) }}</h6>
                            <button type="button"
                                    class="btn btn-approve btn-approve-rt"
                                    data-id="{{ $suratPengantar->id }}"
                                    data-name="{{ $suratPengantar->nama_lengkap }}">
                                <i class="fas fa-check mr-2"></i>Setujui sebagai RT
                            </button>
                            <button type="button"
                                    class="btn btn-reject btn-reject-rt"
                                    data-id="{{ $suratPengantar->id }}"
                                    data-name="{{ $suratPengantar->nama_lengkap }}">
                                <i class="fas fa-times mr-2"></i>Tolak sebagai RT
                            </button>
                        </div>
                        @endif

                        @if($canApproveRW)
                        <div class="mb-3">
                            <h6 class="text-muted mb-3">Persetujuan RW {{ sprintf('%02d', $suratPengantar->rw) }}</h6>
                            <button type="button"
                                    class="btn btn-approve btn-approve-rw"
                                    data-id="{{ $suratPengantar->id }}"
                                    data-name="{{ $suratPengantar->nama_lengkap }}">
                                <i class="fas fa-check mr-2"></i>Setujui sebagai RW
                            </button>
                            <button type="button"
                                    class="btn btn-reject btn-reject-rw"
                                    data-id="{{ $suratPengantar->id }}"
                                    data-name="{{ $suratPengantar->nama_lengkap }}">
                                <i class="fas fa-times mr-2"></i>Tolak sebagai RW
                            </button>
                        </div>
                        @endif

                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Petunjuk:</strong> Pastikan semua data sudah benar sebelum memberikan persetujuan.
                            Tanda tangan dan stempel digital akan ditambahkan secara otomatis setelah persetujuan.
                        </div>
                    </div>
                    @endif

                    <!-- Document Metadata -->
                    <div class="document-meta">
                        <h6 class="mb-3">
                            <i class="fas fa-info-circle text-primary mr-2"></i>Informasi Dokumen
                        </h6>
                        <div class="meta-item">
                            <span class="meta-label">Nomor Surat:</span>
                            <span class="meta-value">{{ $suratPengantar->nomor_surat }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Tanggal Pengajuan:</span>
                            <span class="meta-value">{{ $suratPengantar->formatted_created_date }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Status Saat Ini:</span>
                            <span class="meta-value">{{ $suratPengantar->status_text }}</span>
                        </div>
                        @if($suratPengantar->approved_rt_at)
                        <div class="meta-item">
                            <span class="meta-label">Disetujui RT:</span>
                            <span class="meta-value">{{ $suratPengantar->formatted_approved_rt_date }} oleh {{ $suratPengantar->approverRT->name ?? 'Ketua RT' }}</span>
                        </div>
                        @endif
                        @if($suratPengantar->approved_rw_at)
                        <div class="meta-item">
                            <span class="meta-label">Disetujui RW:</span>
                            <span class="meta-value">{{ $suratPengantar->formatted_approved_rw_date }} oleh {{ $suratPengantar->approverRW->name ?? 'Ketua RW' }}</span>
                        </div>
                        @endif
                        <div class="meta-item">
                            <span class="meta-label">Progress:</span>
                            <span class="meta-value">{{ $suratPengantar->progress_percentage }}% selesai</span>
                        </div>
                        @if($suratPengantar->file_pdf)
                        <div class="meta-item">
                            <span class="meta-label">File PDF:</span>
                            <span class="meta-value">Tersedia</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Include Modal Components from Index View -->
    @include('SuratPengantar.partials.modal')
@endsection

@push('scripts')
    <!-- General JS Scripts -->
    <script src="{{ asset('library/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('library/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let rtSpecimenData = null;
        let rwSpecimenData = null;

        $(document).ready(function() {
            // RT Approve handler
            $('.btn-approve-rt').on('click', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                $('#approveRTModalLabel').text('Setujui Surat Pengantar - RT: ' + name);
                $('#approveRTForm').data('id', id);
                $('#approveRTModal').modal('show');

                // Clear form and load RT spesimen
                $('#approveRTForm')[0].reset();
                loadRTSpesimen(id);
            });

            // RT Reject handler
            $('.btn-reject-rt').on('click', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                $('#rejectRTModalLabel').text('Tolak Surat Pengantar - RT: ' + name);
                $('#rejectRTForm').data('id', id);
                $('#rejectRTModal').modal('show');

                // Clear form
                $('#rejectRTForm')[0].reset();
            });

            // RW Approve handler
            $('.btn-approve-rw').on('click', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                $('#approveRWModalLabel').text('Setujui Surat Pengantar - RW: ' + name);
                $('#approveRWForm').data('id', id);
                $('#approveRWModal').modal('show');

                // Clear form and load RW spesimen
                $('#approveRWForm')[0].reset();
                loadRWSpesimen(id);
            });

            // RW Reject handler
            $('.btn-reject-rw').on('click', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                $('#rejectRWModalLabel').text('Tolak Surat Pengantar - RW: ' + name);
                $('#rejectRWForm').data('id', id);
                $('#rejectRWModal').modal('show');

                // Clear form
                $('#rejectRWForm')[0].reset();
            });

            // RT Approve form submit
            $('#approveRTForm').on('submit', function(e) {
                e.preventDefault();

                const id = $(this).data('id');

                if (!rtSpecimenData || !rtSpecimenData.ttd_rt || !rtSpecimenData.stempel_rt) {
                    Swal.fire('Error', 'Data spesimen TTD/Stempel RT tidak lengkap. Silakan hubungi admin.', 'error');
                    return;
                }

                const formData = new FormData(this);
                formData.append('ttd_rt_url', rtSpecimenData.ttd_rt);
                formData.append('stempel_rt_url', rtSpecimenData.stempel_rt);

                submitApproval(`/surat-pengantar/${id}/approve-rt`, formData, '#approveRTModal');
            });

            // RT Reject form submit
            $('#rejectRTForm').on('submit', function(e) {
                e.preventDefault();

                const id = $(this).data('id');
                const formData = new FormData(this);

                submitApproval(`/surat-pengantar/${id}/reject-rt`, formData, '#rejectRTModal');
            });

            // RW Approve form submit
            $('#approveRWForm').on('submit', function(e) {
                e.preventDefault();

                const id = $(this).data('id');

                if (!rwSpecimenData || !rwSpecimenData.ttd_rw || !rwSpecimenData.stempel_rw) {
                    Swal.fire('Error', 'Data spesimen TTD/Stempel RW tidak lengkap. Silakan hubungi admin.', 'error');
                    return;
                }

                const formData = new FormData(this);
                formData.append('ttd_rw_url', rwSpecimenData.ttd_rw);
                formData.append('stempel_rw_url', rwSpecimenData.stempel_rw);

                submitApproval(`/surat-pengantar/${id}/approve-rw`, formData, '#approveRWModal');
            });

            // RW Reject form submit
            $('#rejectRWForm').on('submit', function(e) {
                e.preventDefault();

                const id = $(this).data('id');
                const formData = new FormData(this);

                submitApproval(`/surat-pengantar/${id}/reject-rw`, formData, '#rejectRWModal');
            });
        });

        // Load RT Spesimen Data
        function loadRTSpesimen(id) {
            $.ajax({
                url: `/surat-pengantar/${id}/get-rt-spesimen`,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        const data = response.data;
                        rtSpecimenData = data;

                        // Update TTD preview
                        if (data.ttd_rt) {
                            $('#ttd-rt-preview').html(`
                                <img src="${data.ttd_rt}" alt="TTD RT" style="max-width: 100%; max-height: 180px;">
                                <div class="mt-2 text-center">
                                    <small class="text-muted">${data.nama_pejabat || 'Ketua RT'}</small>
                                </div>
                            `);
                        } else {
                            $('#ttd-rt-preview').html(`
                                <div class="text-warning text-center">
                                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                    <br>TTD RT belum diupload
                                </div>
                            `);
                        }

                        // Update Stempel preview
                        if (data.stempel_rt) {
                            $('#stempel-rt-preview').html(`
                                <img src="${data.stempel_rt}" alt="Stempel RT" style="max-width: 100%; max-height: 180px;">
                                <div class="mt-2 text-center">
                                    <small class="text-muted">Stempel RT</small>
                                </div>
                            `);
                        } else {
                            $('#stempel-rt-preview').html(`
                                <div class="text-warning text-center">
                                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                    <br>Stempel RT belum diupload
                                </div>
                            `);
                        }
                    }
                },
                error: function(xhr) {
                    console.error('Error loading RT Spesimen:', xhr);
                }
            });
        }

        // Load RW Spesimen Data
        function loadRWSpesimen(id) {
            $.ajax({
                url: `/surat-pengantar/${id}/get-rw-spesimen`,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        const data = response.data;
                        rwSpecimenData = data;

                        // Update TTD preview
                        if (data.ttd_rw) {
                            $('#ttd-rw-preview').html(`
                                <img src="${data.ttd_rw}" alt="TTD RW" style="max-width: 100%; max-height: 180px;">
                                <div class="mt-2 text-center">
                                    <small class="text-muted">${data.nama_pejabat || 'Ketua RW'}</small>
                                </div>
                            `);
                        } else {
                            $('#ttd-rw-preview').html(`
                                <div class="text-warning text-center">
                                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                    <br>TTD RW belum diupload
                                </div>
                            `);
                        }

                        // Update Stempel preview
                        if (data.stempel_rw) {
                            $('#stempel-rw-preview').html(`
                                <img src="${data.stempel_rw}" alt="Stempel RW" style="max-width: 100%; max-height: 180px;">
                                <div class="mt-2 text-center">
                                    <small class="text-muted">Stempel RW</small>
                                </div>
                            `);
                        } else {
                            $('#stempel-rw-preview').html(`
                                <div class="text-warning text-center">
                                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                    <br>Stempel RW belum diupload
                                </div>
                            `);
                        }
                    }
                },
                error: function(xhr) {
                    console.error('Error loading RW Spesimen:', xhr);
                }
            });
        }

        // Function untuk submit approval/rejection
        function submitApproval(url, formData, modalSelector) {
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.success) {
                        $(modalSelector).modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
                }
            });
        }
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
