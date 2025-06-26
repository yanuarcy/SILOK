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

            .timeline-step.auto-approved::after {
                background: #17a2b8;
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

            .step-icon.auto-approved {
                background: #17a2b8;
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

            .status-auto-approved {
                background: linear-gradient(135deg, #b3e5fc 0%, #81d4fa 100%);
                color: #01579b;
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

            .psu-type-badge {
                font-size: 12px;
                padding: 6px 12px;
                border-radius: 15px;
                font-weight: 600;
            }

            .psu-internal {
                background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
                color: white;
            }

            .psu-external {
                background: linear-gradient(135deg, #6f42c1 0%, #5a2d91 100%);
                color: white;
            }

            .file-list {
                list-style: none;
                padding: 0;
            }

            .file-list li {
                background: #f8f9fa;
                border: 1px solid #dee2e6;
                border-radius: 6px;
                padding: 10px 15px;
                margin-bottom: 10px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .file-list li:last-child {
                margin-bottom: 0;
            }

            .file-icon {
                color: #6777ef;
                margin-right: 10px;
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
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item">
                            <a href="{{ route('psu.index') }}">Data PSU</a>
                        </div>
                        <div class="breadcrumb-item">
                            Detail {{ $psu->nomor_surat }}
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
                                        <i class="fas fa-file-alt mr-3"></i>{{ $psu->nomor_surat }}
                                    </div>
                                    <div class="document-subtitle">
                                        Diajukan pada {{ $psu->formatted_created_date }} oleh {{ $psu->nama_lengkap }}
                                    </div>
                                    <div class="mt-2">
                                        <small><i class="fas fa-map-marker-alt mr-1"></i>RT {{ sprintf('%02d', $psu->rt) }} / RW {{ sprintf('%02d', $psu->rw) }}</small>
                                        <span class="psu-type-badge {{ $psu->isPSUInternal() ? 'psu-internal' : 'psu-external' }} ml-2">
                                            {{ $psu->ditujukan_kepada_display }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-right">
                                    <div class="status-badge status-{{ $psu->status === 'auto_approved' ? 'auto-approved' : ($psu->isApproved() ? 'approved' : ($psu->isRejected() ? 'rejected' : 'pending')) }}">
                                        {{ $psu->status_text }}
                                    </div>
                                    <div class="mt-3">
                                        @if($psu->canPreviewPDF())
                                            <a href="{{ route('psu.preview-pdf', $psu->id) }}"
                                            class="btn btn-outline-light btn-sm mr-2" target="_blank">
                                                <i class="fas fa-eye"></i> Preview PDF
                                            </a>
                                        @endif
                                        @if($psu->canDownloadPDF())
                                            <a href="{{ route('psu.download-pdf', $psu->id) }}"
                                            class="btn btn-light btn-sm mr-2">
                                                <i class="fas fa-download"></i> Download PDF
                                            </a>
                                        @endif
                                        @if($psu->canBeEdited() && Auth::user()->role === 'user')
                                            <a href="{{ route('psu.edit', $psu->id) }}"
                                            class="btn btn-warning btn-sm mr-2">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        @endif
                                        <a href="{{ route('psu.index') }}" class="btn btn-secondary btn-sm">
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

                            @php
                                $progress = $psu->workflow_progress;
                            @endphp

                            <!-- Step 1: Document Submitted -->
                            <div class="timeline-step completed">
                                <div class="step-icon completed">
                                    <i class="fas fa-file-upload"></i>
                                </div>
                                <div class="step-content">
                                    <h6>Dokumen Diajukan</h6>
                                    <small>{{ $psu->formatted_created_date }}</small>
                                </div>
                            </div>

                            @if($progress['auto_approved'])
                                <!-- Auto Approved -->
                                <div class="timeline-step auto-approved">
                                    <div class="step-icon auto-approved">
                                        <i class="fas fa-check-double"></i>
                                    </div>
                                    <div class="step-content">
                                        <h6>Auto Approved</h6>
                                        <small>PSU Internal - Langsung selesai</small>
                                    </div>
                                </div>
                            @else
                                <!-- Step 2: RT Approval -->
                                @if($progress['needs_rt'])
                                    <div class="timeline-step {{ $progress['rt_approved'] ? 'completed' : ($psu->status === 'pending_rt' ? 'current' : ($psu->status === 'rejected_rt' ? 'rejected' : 'pending')) }}">
                                        <div class="step-icon {{ $progress['rt_approved'] ? 'completed' : ($psu->status === 'rejected_rt' ? 'rejected' : ($psu->status === 'pending_rt' ? 'current' : 'pending')) }}">
                                            <i class="fas {{ $psu->status === 'rejected_rt' ? 'fa-times' : 'fa-user-check' }}"></i>
                                        </div>
                                        <div class="step-content">
                                            <h6>Persetujuan RT {{ sprintf('%02d', $psu->rt) }}</h6>
                                            <small>
                                                @if($psu->approved_rt_at)
                                                    {{ $psu->status === 'rejected_rt' ? 'Ditolak' : 'Disetujui' }} pada {{ $psu->formatted_approved_rt_date }}
                                                    @if($psu->approverRT)
                                                        oleh {{ $psu->approverRT->name }}
                                                    @endif
                                                @else
                                                    Menunggu persetujuan RT
                                                @endif
                                            </small>
                                            @if($psu->catatan_rt)
                                                <div class="notes-section">
                                                    <strong>Catatan RT:</strong><br>
                                                    {{ $psu->catatan_rt }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Step 3: RW Approval -->
                                @if($progress['needs_rw'])
                                    <div class="timeline-step {{ $progress['rw_approved'] ? 'completed' : (in_array($psu->status, ['approved_rt', 'pending_rw']) ? 'current' : ($psu->status === 'rejected_rw' ? 'rejected' : 'pending')) }}">
                                        <div class="step-icon {{ $progress['rw_approved'] ? 'completed' : ($psu->status === 'rejected_rw' ? 'rejected' : (in_array($psu->status, ['approved_rt', 'pending_rw']) ? 'current' : 'pending')) }}">
                                            <i class="fas {{ $psu->status === 'rejected_rw' ? 'fa-times' : 'fa-user-check' }}"></i>
                                        </div>
                                        <div class="step-content">
                                            <h6>Persetujuan RW {{ sprintf('%02d', $psu->rw) }}</h6>
                                            <small>
                                                @if($psu->approved_rw_at)
                                                    {{ $psu->status === 'rejected_rw' ? 'Ditolak' : 'Disetujui' }} pada {{ $psu->formatted_approved_rw_date }}
                                                    @if($psu->approverRW)
                                                        oleh {{ $psu->approverRW->name }}
                                                    @endif
                                                @else
                                                    {{ $progress['rt_approved'] ? 'Menunggu persetujuan RW' : 'Menunggu RT disetujui terlebih dahulu' }}
                                                @endif
                                            </small>
                                            @if($psu->catatan_rw)
                                                <div class="notes-section">
                                                    <strong>Catatan RW:</strong><br>
                                                    {{ $psu->catatan_rw }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Step 4: Kelurahan Approval -->
                                @if($progress['needs_kelurahan'])
                                    <div class="timeline-step {{ $progress['kelurahan_approved'] ? 'completed' : (in_array($psu->status, ['approved_rw', 'pending_kelurahan']) ? 'current' : ($psu->status === 'rejected_kelurahan' ? 'rejected' : 'pending')) }}">
                                        <div class="step-icon {{ $progress['kelurahan_approved'] ? 'completed' : ($psu->status === 'rejected_kelurahan' ? 'rejected' : (in_array($psu->status, ['approved_rw', 'pending_kelurahan']) ? 'current' : 'pending')) }}">
                                            <i class="fas {{ $psu->status === 'rejected_kelurahan' ? 'fa-times' : 'fa-stamp' }}"></i>
                                        </div>
                                        <div class="step-content">
                                            <h6>Persetujuan Kelurahan</h6>
                                            <small>
                                                @if($psu->approved_kelurahan_at)
                                                    {{ $psu->status === 'rejected_kelurahan' ? 'Ditolak' : 'Disetujui' }} pada {{ $psu->formatted_approved_kelurahan_date }}
                                                    @if($psu->approverKelurahan)
                                                        oleh {{ $psu->approverKelurahan->name }}
                                                    @endif
                                                @else
                                                    {{ $progress['rw_approved'] ? 'Menunggu persetujuan Kelurahan' : 'Menunggu RW disetujui terlebih dahulu' }}
                                                @endif
                                            </small>
                                            @if($psu->catatan_kelurahan)
                                                <div class="notes-section">
                                                    <strong>Catatan Kelurahan:</strong><br>
                                                    {{ $psu->catatan_kelurahan }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endif

                            <!-- Final Step: Document Ready -->
                            <div class="timeline-step {{ $psu->isApproved() || $psu->status === 'auto_approved' ? 'completed' : 'pending' }}">
                                <div class="step-icon {{ $psu->isApproved() || $psu->status === 'auto_approved' ? 'completed' : 'pending' }}">
                                    <i class="fas fa-download"></i>
                                </div>
                                <div class="step-content">
                                    <h6>Dokumen Selesai</h6>
                                    <small>
                                        @if($psu->isApproved() || $psu->status === 'auto_approved')
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
                                            <div class="info-value">{{ $psu->nama_lengkap }}</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-id-card"></i>NIK/KTP
                                            </div>
                                            <div class="info-value">{{ $psu->nomor_kk ?? $psu->nik }}</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-map-marker-alt"></i>Alamat
                                            </div>
                                            <div class="info-value">{{ $psu->alamat }}</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-briefcase"></i>Pekerjaan
                                            </div>
                                            <div class="info-value">{{ $psu->pekerjaan }}</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-venus-mars"></i>Jenis Kelamin
                                            </div>
                                            <div class="info-value">{{ $psu->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-birthday-cake"></i>Tempat, Tgl Lahir
                                            </div>
                                            <div class="info-value">{{ $psu->tempat_lahir }}, {{ $psu->formatted_tanggal_lahir }}</div>
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
                                            <div class="info-value">{{ $psu->agama }}</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-heart"></i>Status Perkawinan
                                            </div>
                                            <div class="info-value">{{ $psu->status_perkawinan }}</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-flag"></i>Kewarganegaraan
                                            </div>
                                            <div class="info-value">{{ $psu->kewarganegaraan }}</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-crosshairs"></i>Ditujukan Kepada
                                            </div>
                                            <div class="info-value">{{ $psu->ditujukan_kepada_display }}</div>
                                        </div>
                                        @if($psu->nama_ketua_rt)
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-user-tie"></i>Nama Ketua RT
                                            </div>
                                            <div class="info-value">{{ $psu->nama_ketua_rt }}</div>
                                        </div>
                                        @endif
                                        @if($psu->nama_ketua_rw)
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-user-tie"></i>Nama Ketua RW
                                            </div>
                                            <div class="info-value">{{ $psu->nama_ketua_rw }}</div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Surat PSU -->
                        <div class="info-card">
                            <div class="info-card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-file-alt text-primary mr-2"></i>Detail Surat PSU
                                </h5>
                            </div>
                            <div class="info-card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-calendar"></i>Bulan
                                            </div>
                                            <div class="info-value">
                                                {{ \Carbon\Carbon::create()->month($psu->bulan)->locale('id')->monthName }} ({{ $psu->bulan }})
                                            </div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-exclamation-circle"></i>Sifat Surat
                                            </div>
                                            <div class="info-value">
                                                <span class="badge badge-{{ $psu->sifat === 'Penting' ? 'warning' : ($psu->sifat === 'Segera' ? 'danger' : ($psu->sifat === 'Rahasia' ? 'dark' : 'secondary')) }}">
                                                    {{ $psu->sifat }}
                                                </span>
                                            </div>
                                        </div>
                                        @if($psu->tujuan_internal)
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-building"></i>Tujuan Internal
                                            </div>
                                            <div class="info-value">{{ strtoupper($psu->tujuan_internal) }}</div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-clipboard"></i>Hal
                                            </div>
                                            <div class="info-value">{{ $psu->hal }}</div>
                                        </div>
                                        @if($psu->tujuan_eksternal)
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-external-link-alt"></i>Tujuan Eksternal
                                            </div>
                                            <div class="info-value">{{ $psu->tujuan_eksternal }}</div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">
                                        <i class="fas fa-file-text"></i>Isi Surat
                                    </div>
                                    <div class="info-value">
                                        <div style="max-height: 200px; overflow-y: auto; white-space: pre-wrap;">{{ $psu->isi_surat }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- File Lampiran -->
                        @if($psu->file_lampiran && count($psu->file_lampiran) > 0)
                        <div class="info-card">
                            <div class="info-card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-paperclip text-primary mr-2"></i>File Lampiran
                                </h5>
                            </div>
                            <div class="info-card-body">
                                <ul class="file-list">
                                    @foreach($psu->file_lampiran as $index => $file)
                                        <li>
                                            <div class="d-flex align-items-center flex-grow-1">
                                                <i class="fas fa-file file-icon"></i>
                                                <span>File {{ $index + 1 }} - {{ basename($file) }}</span>
                                            </div>
                                            <a href="{{ Storage::url($file) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif

                        {{-- SECTION TANDA TERIMA --}}
                        @if($psu->status === 'pending_kelurahan' && $psu->surat_tanda_terima)
                        <!-- File Tanda Terima -->
                        <div class="info-card">
                            <div class="info-card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-receipt text-success mr-2"></i>Surat Tanda Terima
                                </h5>
                            </div>
                            <div class="info-card-body">
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    <strong>Tanda Terima Tersedia!</strong>
                                    Dokumen Anda telah diterima oleh Front Office Kelurahan.
                                </div>

                                <div class="file-list">
                                    <li>
                                        <div class="d-flex align-items-center flex-grow-1">
                                            <i class="fas fa-file-pdf file-icon text-danger"></i>
                                            <div>
                                                <span class="font-weight-bold">Surat Tanda Terima</span>
                                                <br>
                                                <small class="text-muted">
                                                    Nomor Agenda: {{ $psu->metadata['nomor_agenda_kelurahan'] ?? 'N/A' }}
                                                    <br>
                                                    Diterima pada: {{ $psu->received_kelurahan_at ? $psu->received_kelurahan_at->format('d/m/Y H:i') : 'N/A' }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="btn-group">
                                            <a href="{{ route('psu.preview-tanda-terima', $psu->id) }}"
                                            class="btn btn-sm btn-outline-success mr-2"
                                            target="_blank">
                                                <i class="fas fa-eye"></i> Preview Tanda Terima
                                            </a>
                                            <a href="{{ Storage::url($psu->surat_tanda_terima) }}"
                                            download="Tanda_Terima_{{ str_replace(['/', '\\'], '_', $psu->nomor_surat) }}.pdf"
                                            class="btn btn-sm btn-success">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </li>
                                </div>

                                <div class="mt-3">
                                    <small class="text-info">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Simpan tanda terima ini sebagai bukti bahwa dokumen Anda telah diterima oleh Kelurahan.
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- SECTION DISPOSISI LURAH --}}
                        @if(in_array($psu->status, ['processing_lurah', 'processed_lurah']) && isset($psu->metadata['file_disposisi_signed']))
                        <!-- File Disposisi Lurah -->
                        <div class="info-card">
                            <div class="info-card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-file-signature text-info mr-2"></i>Disposisi Lurah
                                </h5>
                            </div>
                            <div class="info-card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-user-tie mr-2"></i>
                                    <strong>Disposisi Tersedia!</strong>
                                    Lurah telah memproses dan menandatangani disposisi untuk dokumen Anda.
                                </div>

                                <div class="file-list">
                                    <li>
                                        <div class="d-flex align-items-center flex-grow-1">
                                            <i class="fas fa-file-signature file-icon text-info"></i>
                                            <div>
                                                <span class="font-weight-bold">Lembar Disposisi Lurah (Ditandatangani)</span>
                                                <br>
                                                <small class="text-muted">
                                                    Diproses pada: {{ $psu->processed_lurah_at ? $psu->processed_lurah_at->format('d/m/Y H:i') : 'N/A' }}
                                                    <br>
                                                    Diteruskan ke: {{ $psu->metadata['diteruskan_kepada'] ?? 'Back Office' }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="btn-group">
                                            <a href="{{ Storage::url($psu->metadata['file_disposisi_signed']) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-outline-info mr-2">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                            <a href="{{ Storage::url($psu->metadata['file_disposisi_signed']) }}"
                                            download="Disposisi_Lurah_{{ str_replace(['/', '\\'], '_', $psu->nomor_surat) }}.pdf"
                                            class="btn btn-sm btn-info">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </li>
                                </div>

                                @if($psu->catatan_lurah)
                                <div class="notes-section">
                                    <strong>Catatan Lurah:</strong><br>
                                    {{ $psu->catatan_lurah }}
                                </div>
                                @endif

                                <div class="mt-3">
                                    <small class="text-info">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Disposisi ini menunjukkan instruksi Lurah untuk pemrosesan dokumen Anda selanjutnya.
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endif

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
                                    <div class="signature-card {{ $psu->hasPemohonSignature() ? 'available' : 'missing' }}">
                                        <h6 class="mb-3">
                                            <i class="fas fa-user mr-2"></i>Tanda Tangan Pemohon
                                        </h6>
                                        @if($psu->hasPemohonSignature())
                                            <img src="{{ $psu->ttd_pemohon_url }}"
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
                                        <small class="text-muted d-block mt-2">{{ $psu->nama_lengkap }}</small>
                                    </div>

                                    <!-- TTD RT -->
                                    @if($psu->needsRTApproval())
                                    <div class="signature-card {{ $psu->hasRTSignature() ? 'available' : 'missing' }}">
                                        <h6 class="mb-3">
                                            <i class="fas fa-user-tie mr-2"></i>Tanda Tangan RT
                                        </h6>
                                        @if($psu->hasRTSignature())
                                            <img src="{{ $psu->ttd_rt_url }}"
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
                                            {{ $psu->approverRT->name ?? 'Ketua RT ' . sprintf('%02d', $psu->rt) }}
                                        </small>
                                    </div>

                                    <!-- Stempel RT -->
                                    @if($psu->hasRTStamp())
                                    <div class="signature-card available">
                                        <h6 class="mb-3">
                                            <i class="fas fa-stamp mr-2"></i>Stempel RT
                                        </h6>
                                        <img src="{{ $psu->stempel_rt_url }}"
                                            alt="Stempel RT"
                                            class="signature-image">
                                        <p class="mt-3 mb-0">
                                            <i class="fas fa-check-circle text-success mr-1"></i>
                                            <small class="text-success">Tersedia</small>
                                        </p>
                                        <small class="text-muted d-block mt-2">Stempel RT {{ sprintf('%02d', $psu->rt) }}</small>
                                    </div>
                                    @endif
                                    @endif

                                    <!-- TTD RW -->
                                    @if($psu->needsRWApproval())
                                    <div class="signature-card {{ $psu->hasRWSignature() ? 'available' : 'missing' }}">
                                        <h6 class="mb-3">
                                            <i class="fas fa-user-tie mr-2"></i>Tanda Tangan RW
                                        </h6>
                                        @if($psu->hasRWSignature())
                                            <img src="{{ $psu->ttd_rw_url }}"
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
                                            {{ $psu->approverRW->name ?? 'Ketua RW ' . sprintf('%02d', $psu->rw) }}
                                        </small>
                                    </div>

                                    <!-- Stempel RW -->
                                    @if($psu->hasRWStamp())
                                    <div class="signature-card available">
                                        <h6 class="mb-3">
                                            <i class="fas fa-stamp mr-2"></i>Stempel RW
                                        </h6>
                                        <img src="{{ $psu->stempel_rw_url }}"
                                            alt="Stempel RW"
                                            class="signature-image">
                                        <p class="mt-3 mb-0">
                                            <i class="fas fa-check-circle text-success mr-1"></i>
                                            <small class="text-success">Tersedia</small>
                                        </p>
                                        <small class="text-muted d-block mt-2">Stempel RW {{ sprintf('%02d', $psu->rw) }}</small>
                                    </div>
                                    @endif
                                    @endif

                                    <!-- TTD Kelurahan -->
                                    @if($psu->needsKelurahanApproval())
                                    <div class="signature-card {{ $psu->hasKelurahanSignature() ? 'available' : 'missing' }}">
                                        <h6 class="mb-3">
                                            <i class="fas fa-user-tie mr-2"></i>Tanda Tangan Kelurahan
                                        </h6>
                                        @if($psu->hasKelurahanSignature())
                                            <img src="{{ $psu->ttd_kelurahan_url }}"
                                                alt="TTD Kelurahan"
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
                                            {{ $psu->approverKelurahan->name ?? 'Lurah' }}
                                        </small>
                                    </div>

                                    <!-- Stempel Kelurahan -->
                                    @if($psu->hasKelurahanStamp())
                                    <div class="signature-card available">
                                        <h6 class="mb-3">
                                            <i class="fas fa-stamp mr-2"></i>Stempel Kelurahan
                                        </h6>
                                        <img src="{{ $psu->stempel_kelurahan_url }}"
                                            alt="Stempel Kelurahan"
                                            class="signature-image">
                                        <p class="mt-3 mb-0">
                                            <i class="fas fa-check-circle text-success mr-1"></i>
                                            <small class="text-success">Tersedia</small>
                                        </p>
                                        <small class="text-muted d-block mt-2">Stempel Kelurahan</small>
                                    </div>
                                    @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Panel untuk PSU Internal (yang auto-approved) -->
                        @if($psu->isPSUInternal())
                            <div class="internal-psu-panel">
                                <h5 class="mb-4">
                                    <i class="fas fa-check-double text-success mr-2"></i>PSU Internal
                                </h5>
                                <div class="alert alert-success">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <strong>Status:</strong> PSU ini adalah PSU Internal yang telah otomatis disetujui untuk keperluan warga RT/RW.
                                    Tidak memerlukan proses persetujuan tambahan.
                                </div>
                            </div>
                        @endif

                        @if($psu->level_akhir === 'kelurahan' && ($canReceiveKelurahan || $canProcessLurah || $canApproveBackOffice))
                            <div class="kelurahan-workflow-panel">
                                <h5 class="mb-4">
                                    <i class="fas fa-building text-success mr-2"></i>Panel Workflow Kelurahan
                                </h5>

                                <!-- Step 1: Penerimaan di Front Office -->
                                @if($canReceiveKelurahan)
                                    <div class="workflow-step mb-3">
                                        <h6 class="text-muted mb-3">
                                            <i class="fas fa-inbox mr-2"></i>Step 1: Penerimaan di Front Office
                                        </h6>
                                        <p class="small text-muted mb-3">
                                            Terima PSU yang sudah disetujui RW, buat Tanda Terima dan Disposisi untuk Lurah
                                        </p>
                                        <button type="button"
                                                class="btn btn-primary btn-receive-kelurahan"
                                                data-id="{{ $psu->id }}"
                                                data-name="{{ $psu->nama_lengkap }}">
                                            <i class="fas fa-inbox mr-2"></i>Terima di Kelurahan
                                        </button>
                                    </div>
                                @endif

                                <!-- Step 2: Proses Disposisi Lurah -->
                                @if($canProcessLurah)
                                    <div class="workflow-step mb-3">
                                        <h6 class="text-muted mb-3">
                                            <i class="fas fa-user-tie mr-2"></i>Step 2: Proses Disposisi Lurah
                                        </h6>
                                        <p class="small text-muted mb-3">
                                            Isi dan tandatangani disposisi, berikan instruksi untuk Back Office
                                        </p>
                                        <button type="button"
                                                class="btn btn-warning btn-process-lurah"
                                                data-id="{{ $psu->id }}"
                                                data-name="{{ $psu->nama_lengkap }}">
                                            <i class="fas fa-signature mr-2"></i>Proses Disposisi
                                        </button>
                                    </div>
                                @endif

                                <!-- Step 3: Approve Final Back Office -->
                                @if($canApproveBackOffice)
                                    <div class="workflow-step mb-3">
                                        <h6 class="text-muted mb-3">
                                            <i class="fas fa-check-circle mr-2"></i>Step 3: Approve Final Back Office
                                        </h6>
                                        <p class="small text-muted mb-3">
                                            Selesaikan seluruh workflow PSU dan ubah status menjadi completed
                                        </p>
                                        <button type="button"
                                                class="btn btn-success btn-approve-back-office"
                                                data-id="{{ $psu->id }}"
                                                data-name="{{ $psu->nama_lengkap }}">
                                            <i class="fas fa-check-circle mr-2"></i>Approve & Selesaikan
                                        </button>
                                    </div>
                                @endif

                                <!-- Status Progress -->
                                <div class="workflow-progress mt-4">
                                    <h6 class="text-muted mb-3">Progress Workflow:</h6>
                                    <div class="progress mb-2" style="height: 20px;">
                                        @php
                                            $progressPercentage = 0;
                                            if ($psu->status === 'approved_rw') $progressPercentage = 25;
                                            elseif ($psu->status === 'pending_kelurahan') $progressPercentage = 50;
                                            elseif ($psu->status === 'processing_lurah') $progressPercentage = 65;
                                            elseif ($psu->status === 'processed_lurah') $progressPercentage = 85;
                                            elseif ($psu->status === 'completed') $progressPercentage = 100;
                                        @endphp
                                        <div class="progress-bar bg-success" role="progressbar"
                                            style="width: {{ $progressPercentage }}%"
                                            aria-valuenow="{{ $progressPercentage }}"
                                            aria-valuemin="0"
                                            aria-valuemax="100">
                                            {{ $progressPercentage }}%
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        @if($psu->status === 'approved_rw')
                                            Menunggu penerimaan di Front Office
                                        @elseif($psu->status === 'pending_kelurahan')
                                            Menunggu disposisi Lurah
                                        @elseif($psu->status === 'processing_lurah')
                                            Sedang diproses Lurah
                                        @elseif($psu->status === 'processed_lurah')
                                            Menunggu approval final Back Office
                                        @elseif($psu->status === 'completed')
                                            Workflow selesai
                                        @endif
                                    </small>
                                </div>

                                <div class="alert alert-success mt-4">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <strong>Informasi Workflow:</strong>
                                    @if($psu->hasBeenReceivedAtKelurahan())
                                        <span class="badge badge-success mr-2">
                                            <i class="fas fa-receipt mr-1"></i>Tanda Terima Tersedia
                                        </span>
                                    @endif
                                    @if($psu->hasDisposisiLurah())
                                        <span class="badge badge-info mr-2">
                                            <i class="fas fa-clipboard-list mr-1"></i>Disposisi Dibuat
                                        </span>
                                    @endif
                                    @if($psu->hasSignedDisposisiLurah())
                                        <span class="badge badge-warning mr-2">
                                            <i class="fas fa-signature mr-1"></i>Disposisi Ditandatangani
                                        </span>
                                    @endif
                                    <br>
                                    <small class="mt-2 d-block">
                                        Setiap step akan menghasilkan dokumen yang dapat diunduh untuk tracking dan arsip.
                                    </small>
                                </div>
                            </div>
                        @endif

                        <!-- Approval Buttons for RT/RW/Kelurahan -->
                        @if(($canApproveRT || $canApproveRW) && !$psu->isPSUInternal())
                            <div class="approval-buttons">
                                <h5 class="mb-4">
                                    <i class="fas fa-gavel text-primary mr-2"></i>Panel Persetujuan
                                </h5>

                                @if($canApproveRT)
                                <div class="mb-3">
                                    <h6 class="text-muted mb-3">Persetujuan RT {{ sprintf('%02d', $psu->rt) }}</h6>
                                    <button type="button"
                                            class="btn btn-approve btn-approve-rt"
                                            data-id="{{ $psu->id }}"
                                            data-name="{{ $psu->nama_lengkap }}">
                                        <i class="fas fa-check mr-2"></i>Setujui sebagai RT
                                    </button>
                                    <button type="button"
                                            class="btn btn-reject btn-reject-rt"
                                            data-id="{{ $psu->id }}"
                                            data-name="{{ $psu->nama_lengkap }}">
                                        <i class="fas fa-times mr-2"></i>Tolak sebagai RT
                                    </button>
                                </div>
                                @endif

                                @if($canApproveRW)
                                <div class="mb-3">
                                    <h6 class="text-muted mb-3">Persetujuan RW {{ sprintf('%02d', $psu->rw) }}</h6>
                                    <button type="button"
                                            class="btn btn-approve btn-approve-rw"
                                            data-id="{{ $psu->id }}"
                                            data-name="{{ $psu->nama_lengkap }}">
                                        <i class="fas fa-check mr-2"></i>Setujui sebagai RW
                                    </button>
                                    <button type="button"
                                            class="btn btn-reject btn-reject-rw"
                                            data-id="{{ $psu->id }}"
                                            data-name="{{ $psu->nama_lengkap }}">
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
                                <span class="meta-value">{{ $psu->nomor_surat }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Tanggal Pengajuan:</span>
                                <span class="meta-value">{{ $psu->formatted_created_date }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Status Saat Ini:</span>
                                <span class="meta-value">{{ $psu->status_text }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Jenis PSU:</span>
                                <span class="meta-value">{{ $psu->isPSUInternal() ? 'PSU Internal' : 'PSU External' }}</span>
                            </div>
                            @if($psu->approved_rt_at)
                            <div class="meta-item">
                                <span class="meta-label">Disetujui RT:</span>
                                <span class="meta-value">{{ $psu->formatted_approved_rt_date }} oleh {{ $psu->approverRT->name ?? 'Ketua RT' }}</span>
                            </div>
                            @endif
                            @if($psu->approved_rw_at)
                            <div class="meta-item">
                                <span class="meta-label">Disetujui RW:</span>
                                <span class="meta-value">{{ $psu->formatted_approved_rw_date }} oleh {{ $psu->approverRW->name ?? 'Ketua RW' }}</span>
                            </div>
                            @endif
                            @if($psu->approved_kelurahan_at)
                            <div class="meta-item">
                                <span class="meta-label">Disetujui Kelurahan:</span>
                                <span class="meta-value">{{ $psu->formatted_approved_kelurahan_date }} oleh {{ $psu->approverKelurahan->name ?? 'Lurah' }}</span>
                            </div>
                            @endif
                            <div class="meta-item">
                                <span class="meta-label">Progress:</span>
                                <span class="meta-value">{{ $psu->progress_percentage }}% selesai</span>
                            </div>
                            @if($psu->file_pdf)
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

        <!-- Include Modal Components from PSU Index View -->
        @include('Psu.partials.modal')

    @endsection

    @push('scripts')
        <!-- General JS Scripts -->
        <script src="{{ asset('library/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{ asset('library/bootstrap/dist/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            let rtSpecimenData = null;
            let rwSpecimenData = null;
            let kelurahanSpecimenData = null;

            $(document).ready(function() {
                // RT Approve handler
                $('.btn-approve-rt').on('click', function() {
                    const id = $(this).data('id');
                    const name = $(this).data('name');

                    $('#approveRTModalLabel').text('Setujui PSU - RT: ' + name);
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

                    $('#rejectRTModalLabel').text('Tolak PSU - RT: ' + name);
                    $('#rejectRTForm').data('id', id);
                    $('#rejectRTModal').modal('show');

                    // Clear form
                    $('#rejectRTForm')[0].reset();
                });

                // RW Approve handler
                $('.btn-approve-rw').on('click', function() {
                    const id = $(this).data('id');
                    const name = $(this).data('name');

                    $('#approveRWModalLabel').text('Setujui PSU - RW: ' + name);
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

                    $('#rejectRWModalLabel').text('Tolak PSU - RW: ' + name);
                    $('#rejectRWForm').data('id', id);
                    $('#rejectRWModal').modal('show');

                    // Clear form
                    $('#rejectRWForm')[0].reset();
                });

                $('.btn-receive-kelurahan').on('click', function() {
                    const id = $(this).data('id');
                    const name = $(this).data('name');

                    console.log('Receive Kelurahan clicked for ID:', id);

                    // Set modal data SEBELUM show modal
                    $('#receiveKelurahanModal').data('psu-id', id);
                    $('#receiveKelurahanForm').data('id', id);

                    // Load PSU details terlebih dahulu
                    loadPsuDetailsForReceive(id);

                    // Load Front Office spesimen (TTD + Stempel)
                    loadFrontOfficeSpesimen(id);

                    // Kemudian show modal
                    $('#receiveKelurahanModal').modal('show');
                    $('#receiveKelurahanModal .modal-title').text(`Terima di Kelurahan - ${name}`);
                });

                $('#receiveKelurahanModal').on('hidden.bs.modal', function() {
                    $(this).find('form')[0]?.reset();
                    $('#confirmReceive').prop('checked', false);

                    // Clear loaded data
                    $('#receiveNomorSurat').val('');
                    $('#receiveNamaPemohon').val('');
                    $('#receiveHal').val('');
                    $('#catatanFrontOffice').val('');

                    // Reset spesimen previews
                    $('#ttd-front-office-preview, #stempel-kelurahan-receive-preview').html(`
                        <div class="text-center text-muted">
                            <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                            <br>Memuat spesimen...
                        </div>
                    `);

                    console.log('Receive modal reset');
                });

                // Kelurahan Approve handler
                $('.btn-approve-kelurahan').on('click', function() {
                    const id = $(this).data('id');
                    const name = $(this).data('name');

                    $('#approveKelurahanModalLabel').text('Setujui PSU - Kelurahan: ' + name);
                    $('#approveKelurahanForm').data('id', id);
                    $('#approveKelurahanModal').modal('show');

                    // Clear form and load Kelurahan spesimen
                    $('#approveKelurahanForm')[0].reset();
                    loadKelurahanSpesimen(id);
                });

                // Kelurahan Reject handler
                $('.btn-reject-kelurahan').on('click', function() {
                    const id = $(this).data('id');
                    const name = $(this).data('name');

                    $('#rejectKelurahanModalLabel').text('Tolak PSU - Kelurahan: ' + name);
                    $('#rejectKelurahanForm').data('id', id);
                    $('#rejectKelurahanModal').modal('show');

                    // Clear form
                    $('#rejectKelurahanForm')[0].reset();
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

                    submitApproval(`/psu/${id}/approve-rt`, formData, '#approveRTModal');
                });

                // RT Reject form submit
                $('#rejectRTForm').on('submit', function(e) {
                    e.preventDefault();

                    const id = $(this).data('id');
                    const formData = new FormData(this);

                    submitApproval(`/psu/${id}/reject-rt`, formData, '#rejectRTModal');
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

                    submitApproval(`/psu/${id}/approve-rw`, formData, '#approveRWModal');
                });

                // RW Reject form submit
                $('#rejectRWForm').on('submit', function(e) {
                    e.preventDefault();

                    const id = $(this).data('id');
                    const formData = new FormData(this);

                    submitApproval(`/psu/${id}/reject-rw`, formData, '#rejectRWModal');
                });

                // Validation untuk submit form receive kelurahan
                $('#receiveKelurahanForm').on('submit', function(e) {
                    e.preventDefault();
                    const id = $(this).data('id');

                    console.log('Submitting receive form for ID:', id);

                    if (!id) {
                        Swal.fire('Error', 'ID PSU tidak ditemukan', 'error');
                        return;
                    }

                    // Validate TTD dan Stempel tersedia
                    const hasTTD = $('#ttd-front-office-preview input[name="ttd_front_office_url"]').length > 0;
                    const hasStempel = $('#stempel-kelurahan-receive-preview input[name="stempel_kelurahan_url"]').length > 0;

                    if (!hasTTD || !hasStempel) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Data Spesimen Tidak Lengkap',
                            text: 'TTD Front Office dan Stempel Kelurahan harus tersedia. Silakan hubungi administrator untuk mengupload spesimen yang diperlukan.'
                        });
                        return;
                    }

                    // Validate confirmation checkbox
                    if (!$('#confirmReceive').is(':checked')) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Konfirmasi Diperlukan',
                            text: 'Harap centang kotak konfirmasi sebelum melanjutkan.'
                        });
                        return;
                    }

                    const formData = new FormData(this);
                    const submitBtn = $('#receiveKelurahanBtn');
                    const originalText = submitBtn.html();
                    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...');

                    $.ajax({
                        url: `/psu/${id}/receive-kelurahan`,
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            console.log('Receive kelurahan response:', response);

                            if(response.success) {
                                $('#receiveKelurahanModal').modal('hide');
                                table.ajax.reload();
                                loadSummaryData();

                                // Show success with document links if available
                                let successHtml = response.message;
                                if (response.data?.tanda_terima_url || response.data?.disposisi_url) {
                                    successHtml += '<div class="mt-3">';
                                    if (response.data.tanda_terima_url) {
                                        successHtml += `<a href="${response.data.tanda_terima_url}" target="_blank" class="btn btn-info btn-sm mr-2">
                                            <i class="fas fa-receipt"></i> Lihat Tanda Terima
                                        </a>`;
                                    }
                                    if (response.data.disposisi_url) {
                                        successHtml += `<a href="${response.data.disposisi_url}" target="_blank" class="btn btn-warning btn-sm">
                                            <i class="fas fa-clipboard-list"></i> Lihat Disposisi
                                        </a>`;
                                    }
                                    successHtml += '</div>';
                                }

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil Diterima!',
                                    html: successHtml,
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error submitting receive kelurahan:', {xhr, status, error});
                            console.error('Response text:', xhr.responseText);

                            const message = xhr.responseJSON?.message || 'Terjadi kesalahan saat memproses data.';
                            Swal.fire('Error!', message, 'error');
                        },
                        complete: function() {
                            submitBtn.prop('disabled', false).html(originalText);
                        }
                    });
                });

                // Kelurahan Approve form submit
                $('#approveKelurahanForm').on('submit', function(e) {
                    e.preventDefault();

                    const id = $(this).data('id');

                    if (!kelurahanSpecimenData || !kelurahanSpecimenData.ttd_kelurahan || !kelurahanSpecimenData.stempel_kelurahan) {
                        Swal.fire('Error', 'Data spesimen TTD/Stempel Kelurahan tidak lengkap. Silakan hubungi admin.', 'error');
                        return;
                    }

                    const formData = new FormData(this);
                    formData.append('ttd_kelurahan_url', kelurahanSpecimenData.ttd_kelurahan);
                    formData.append('stempel_kelurahan_url', kelurahanSpecimenData.stempel_kelurahan);

                    submitApproval(`/psu/${id}/approve-kelurahan`, formData, '#approveKelurahanModal');
                });

                // Kelurahan Reject form submit
                $('#rejectKelurahanForm').on('submit', function(e) {
                    e.preventDefault();

                    const id = $(this).data('id');
                    const formData = new FormData(this);

                    submitApproval(`/psu/${id}/reject-kelurahan`, formData, '#rejectKelurahanModal');
                });
            });

            // Load RT Spesimen Data
            function loadRTSpesimen(id) {
                $.ajax({
                    url: `/psu/${id}/get-rt-spesimen`,
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
                    url: `/psu/${id}/get-rw-spesimen`,
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

            function loadPsuDetailsForReceive(psuId) {
                console.log('Loading PSU details for receive, ID:', psuId); // Debug log

                $.ajax({
                    url: `/psu/${psuId}`,
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log('PSU details loaded:', response); // Debug log

                        if (response && response.nomor_surat) {
                            $('#receiveNomorSurat').val(response.nomor_surat);
                            $('#receiveNamaPemohon').val(response.nama_lengkap);
                            $('#receiveHal').val(response.hal);
                        } else {
                            console.error('Invalid response format:', response);
                            Swal.fire('Error', 'Format response tidak valid', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading PSU details:', {xhr, status, error});
                        console.error('Response text:', xhr.responseText);

                        Swal.fire('Error', 'Gagal memuat detail PSU: ' + (xhr.responseJSON?.message || error), 'error');
                    }
                });
            }

            function showSpesimenError(message) {
                const errorHtml = `
                    <div class="text-center text-danger">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                        <br>${message}
                    </div>
                `;
                $('#ttd-front-office-preview, #stempel-kelurahan-receive-preview').html(errorHtml);
            }

            function loadFrontOfficeSpesimen(psuId) {
                console.log('Loading Front Office spesimen for ID:', psuId);

                $.ajax({
                    url: `/psu/${psuId}/get-front-office-spesimen`,
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log('Front Office spesimen response:', response);

                        if (response.success) {
                            // Update TTD Front Office preview
                            if (response.data.ttd_front_office) {
                                $('#ttd-front-office-preview').html(`
                                    <img src="${response.data.ttd_front_office}" alt="TTD Front Office" style="max-width: 100%; max-height: 180px;">
                                    <div class="mt-2 text-center">
                                        <small class="text-muted">${response.data.nama_pejabat_front_office || 'Front Office'}</small>
                                    </div>
                                    <input type="hidden" name="ttd_front_office_url" value="${response.data.ttd_front_office}">
                                `);
                            } else {
                                $('#ttd-front-office-preview').html(`
                                    <div class="text-center text-warning">
                                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                        <br>TTD Front Office belum diupload
                                        <br><small class="text-muted">Silakan hubungi admin untuk mengupload TTD Front Office</small>
                                    </div>
                                `);
                            }

                            // Update Stempel Kelurahan preview
                            if (response.data.stempel_kelurahan) {
                                $('#stempel-kelurahan-receive-preview').html(`
                                    <img src="${response.data.stempel_kelurahan}" alt="Stempel Kelurahan" style="max-width: 100%; max-height: 180px;">
                                    <div class="mt-2 text-center">
                                        <small class="text-muted">Stempel Kelurahan</small>
                                    </div>
                                    <input type="hidden" name="stempel_kelurahan_url" value="${response.data.stempel_kelurahan}">
                                `);
                            } else {
                                $('#stempel-kelurahan-receive-preview').html(`
                                    <div class="text-center text-warning">
                                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                        <br>Stempel Kelurahan belum diupload
                                        <br><small class="text-muted">Silakan hubungi admin untuk mengupload Stempel Kelurahan</small>
                                    </div>
                                `);
                            }

                            // Show debug info in console if available
                            if (response.debug) {
                                console.log('Debug info:', response.debug);
                                console.log('Available jabatan:', response.debug.available_jabatan);
                            }

                            // Show messages if any
                            if (response.message && response.message.length > 0) {
                                console.warn('Spesimen warnings:', response.message);
                            }

                        } else {
                            console.error('Invalid response format:', response);
                            showSpesimenError('Error memuat data spesimen');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading Front Office spesimen:', {xhr, status, error});
                        console.error('Response text:', xhr.responseText);

                        let errorMessage = 'Error memuat data spesimen';
                        if (xhr.status === 404) {
                            errorMessage = 'Route tidak ditemukan. Pastikan route sudah terdaftar.';
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        showSpesimenError(errorMessage);
                    }
                });
            }

            // Load Kelurahan Spesimen Data
            function loadKelurahanSpesimen(id) {
                $.ajax({
                    url: `/psu/${id}/get-kelurahan-spesimen`,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const data = response.data;
                            kelurahanSpecimenData = data;

                            // Update TTD preview
                            if (data.ttd_kelurahan) {
                                $('#ttd-kelurahan-preview').html(`
                                    <img src="${data.ttd_kelurahan}" alt="TTD Kelurahan" style="max-width: 100%; max-height: 180px;">
                                    <div class="mt-2 text-center">
                                        <small class="text-muted">${data.nama_pejabat || 'Lurah'}</small>
                                    </div>
                                `);
                            } else {
                                $('#ttd-kelurahan-preview').html(`
                                    <div class="text-warning text-center">
                                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                        <br>TTD Kelurahan belum diupload
                                    </div>
                                `);
                            }

                            // Update Stempel preview
                            if (data.stempel_kelurahan) {
                                $('#stempel-kelurahan-preview').html(`
                                    <img src="${data.stempel_kelurahan}" alt="Stempel Kelurahan" style="max-width: 100%; max-height: 180px;">
                                    <div class="mt-2 text-center">
                                        <small class="text-muted">Stempel Kelurahan</small>
                                    </div>
                                `);
                            } else {
                                $('#stempel-kelurahan-preview').html(`
                                    <div class="text-warning text-center">
                                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                        <br>Stempel Kelurahan belum diupload
                                    </div>
                                `);
                            }
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading Kelurahan Spesimen:', xhr);
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
