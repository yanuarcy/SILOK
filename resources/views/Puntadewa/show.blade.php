@extends('Template.template')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">

    <!-- Leaflet CSS for Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

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
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .signature-display img {
            max-width: 100%;
            max-height: 180px;
            border-radius: 4px;
        }

        .signature-display.empty {
            color: #6c757d;
            font-style: italic;
        }

        .signature-pad {
            border: 2px solid #dee2e6;
            border-radius: 8px;
            background-color: white;
        }

        #map {
            height: 250px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
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

        .approval-section {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .approval-item {
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }

        .approval-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .document-actions {
            background: #e8f4f8;
            border: 1px solid #bee5eb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .approval-actions {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .approval-form {
            background: #e8f5e8;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .required {
            color: #dc3545;
        }

        @media print {
            .document-actions,
            .approval-actions,
            .approval-form,
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
                <h1>Detail PUNTADEWA</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('puntadewa.index') }}">Data PUNTADEWA</a>
                    </div>
                    <div class="breadcrumb-item">Detail {{ $puntadewa->nomor_surat }}</div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <!-- Document Status & Actions -->
                    <div class="document-actions">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="mb-2">
                                    <i class="fas fa-file-alt text-primary"></i>
                                    {{ $puntadewa->nomor_surat }}
                                </h5>
                                <p class="mb-0 text-muted">
                                    Diajukan pada {{ $puntadewa->formatted_created_date }} oleh {{ $puntadewa->user->name }}
                                </p>
                                <p class="mb-0 text-info">
                                    <strong>RT {{ $puntadewa->rt }} / RW {{ $puntadewa->rw }}</strong>
                                </p>
                            </div>
                            <div class="col-md-4 text-right">
                                <div class="mb-2">
                                    {!! $puntadewa->status_badge !!}
                                </div>
                                <div class="btn-group">
                                    @if($puntadewa->canPreviewPDF())
                                        <a href="{{ route('puntadewa.preview-pdf', $puntadewa->id) }}"
                                           class="btn btn-secondary btn-sm"
                                           target="_blank">
                                            <i class="fas fa-eye"></i> Preview PDF
                                        </a>
                                    @endif

                                    @if($puntadewa->canDownloadPDF())
                                        <a href="{{ route('puntadewa.download-pdf', $puntadewa->id) }}"
                                           class="btn btn-success btn-sm">
                                            <i class="fas fa-download"></i> Download PDF
                                        </a>
                                    @endif

                                    @if($puntadewa->canBeEdited() && Auth::user()->role === 'user' && $puntadewa->user_id === Auth::id())
                                        <a href="{{ route('puntadewa.edit', $puntadewa->id) }}"
                                           class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    @endif

                                    <button type="button" class="btn btn-info btn-sm" onclick="printDocument()">
                                        <i class="fas fa-print"></i> Print
                                    </button>

                                    <a href="{{ route('puntadewa.index') }}" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Approval Form for RT -->
                    @if($canApproveRT)
                        <div class="approval-form" id="approval-rt-form">
                            <h6 class="mb-3">
                                <i class="fas fa-user-check text-success"></i>
                                Persetujuan RT {{ Auth::user()->rt }}
                            </h6>
                            <form id="approveRTForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tanda Tangan RT</label>
                                            <div id="ttd-rt-preview" class="signature-display" style="min-height: 200px;">
                                                <span class="text-muted">Memuat TTD RT...</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Stempel RT</label>
                                            <div id="stempel-rt-preview" class="signature-display" style="min-height: 200px;">
                                                <span class="text-muted">Memuat Stempel RT...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Catatan (Opsional)</label>
                                    <textarea class="form-control" name="catatan_rt" rows="3" placeholder="Catatan untuk persetujuan"></textarea>
                                </div>
                                <div class="text-right">
                                    <button type="button" class="btn btn-success" id="btn-approve-rt">
                                        <i class="fas fa-check"></i> Setujui sebagai RT
                                    </button>
                                    <button type="button" class="btn btn-danger ml-2" id="btn-reject-rt">
                                        <i class="fas fa-times"></i> Tolak sebagai RT
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                    <!-- Approval Form for RW -->
                    @if($canApproveRW)
                        <div class="approval-form" id="approval-rw-form">
                            <h6 class="mb-3">
                                <i class="fas fa-user-check text-success"></i>
                                Persetujuan RW {{ Auth::user()->rw }}
                            </h6>
                            <form id="approveRWForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tanda Tangan RW</label>
                                            <div id="ttd-rw-preview" class="signature-display" style="min-height: 200px;">
                                                <span class="text-muted">Memuat TTD RW...</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Stempel RW</label>
                                            <div id="stempel-rw-preview" class="signature-display" style="min-height: 200px;">
                                                <span class="text-muted">Memuat Stempel RW...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Catatan (Opsional)</label>
                                    <textarea class="form-control" name="catatan_rw" rows="3" placeholder="Catatan untuk persetujuan"></textarea>
                                </div>
                                <div class="text-right">
                                    <button type="button" class="btn btn-success" id="btn-approve-rw">
                                        <i class="fas fa-check"></i> Setujui sebagai RW
                                    </button>
                                    <button type="button" class="btn btn-danger ml-2" id="btn-reject-rw">
                                        <i class="fas fa-times"></i> Tolak sebagai RW
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                    <!-- Workflow Progress -->
                    <div class="workflow-progress">
                        <h6 class="mb-3"><i class="fas fa-tasks"></i> Progress Persetujuan</h6>

                        <div class="workflow-step {{ $puntadewa->workflow_progress['submitted'] ? 'completed' : 'pending' }}">
                            <div class="workflow-icon {{ $puntadewa->workflow_progress['submitted'] ? 'completed' : 'pending' }}">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                            <div>
                                <strong>Dokumen Diajukan</strong>
                                <div class="text-muted small">{{ $puntadewa->formatted_created_date }}</div>
                            </div>
                        </div>

                        <div class="workflow-step {{ $puntadewa->status === 'rejected_rt' ? 'rejected' : ($puntadewa->workflow_progress['rt_approved'] ? 'completed' : ($puntadewa->status === 'pending_rt' ? 'current' : 'pending')) }}">
                            <div class="workflow-icon {{ $puntadewa->status === 'rejected_rt' ? 'rejected' : ($puntadewa->workflow_progress['rt_approved'] ? 'completed' : ($puntadewa->status === 'pending_rt' ? 'current' : 'pending')) }}">
                                <i class="fas {{ $puntadewa->status === 'rejected_rt' ? 'fa-times' : 'fa-user-check' }}"></i>
                            </div>
                            <div>
                                <strong>Persetujuan RT {{ $puntadewa->rt }}</strong>
                                @if($puntadewa->approved_rt_at)
                                    <div class="text-muted small">
                                        {{ $puntadewa->status === 'rejected_rt' ? 'Ditolak' : 'Disetujui' }} pada {{ $puntadewa->formatted_approved_rt_date }}
                                        @if($puntadewa->approverRT)
                                            oleh {{ $puntadewa->approverRT->name }}
                                        @endif
                                    </div>
                                    @if($puntadewa->catatan_rt)
                                        <div class="text-info small mt-1">
                                            <i class="fas fa-comment"></i> {{ $puntadewa->catatan_rt }}
                                        </div>
                                    @endif
                                @else
                                    <div class="text-muted small">Menunggu persetujuan RT {{ $puntadewa->rt }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="workflow-step {{ $puntadewa->status === 'rejected_rw' ? 'rejected' : ($puntadewa->workflow_progress['rw_approved'] ? 'completed' : (in_array($puntadewa->status, ['approved_rt', 'pending_rw']) ? 'current' : 'pending')) }}">
                            <div class="workflow-icon {{ $puntadewa->status === 'rejected_rw' ? 'rejected' : ($puntadewa->workflow_progress['rw_approved'] ? 'completed' : (in_array($puntadewa->status, ['approved_rt', 'pending_rw']) ? 'current' : 'pending')) }}">
                                <i class="fas {{ $puntadewa->status === 'rejected_rw' ? 'fa-times' : 'fa-user-check' }}"></i>
                            </div>
                            <div>
                                <strong>Persetujuan RW {{ $puntadewa->rw }}</strong>
                                @if($puntadewa->approved_rw_at)
                                    <div class="text-muted small">
                                        {{ $puntadewa->status === 'rejected_rw' ? 'Ditolak' : 'Disetujui' }} pada {{ $puntadewa->formatted_approved_rw_date }}
                                        @if($puntadewa->approverRW)
                                            oleh {{ $puntadewa->approverRW->name }}
                                        @endif
                                    </div>
                                    @if($puntadewa->catatan_rw)
                                        <div class="text-info small mt-1">
                                            <i class="fas fa-comment"></i> {{ $puntadewa->catatan_rw }}
                                        </div>
                                    @endif
                                @else
                                    <div class="text-muted small">Menunggu persetujuan RW {{ $puntadewa->rw }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="workflow-step {{ $puntadewa->workflow_progress['completed'] ? 'completed' : 'pending' }}">
                            <div class="workflow-icon {{ $puntadewa->workflow_progress['completed'] ? 'completed' : 'pending' }}">
                                <i class="fas fa-check-double"></i>
                            </div>
                            <div>
                                <strong>Dokumen Selesai</strong>
                                @if($puntadewa->workflow_progress['completed'])
                                    <div class="text-muted small">Dokumen siap diunduh</div>
                                @else
                                    <div class="text-muted small">Menunggu semua persetujuan</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Main Document Content -->
                    <div class="card">
                        <div class="card-header">
                            <h4>Detail PUNTADEWA - {{ $puntadewa->nomor_surat }}</h4>
                        </div>
                        <div class="card-body">
                            {{-- Data Pemohon --}}
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="text-primary">
                                        <i class="fas fa-user"></i> Data Pemohon
                                    </h5>
                                    <hr>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama Pemohon</label>
                                        <input type="text" class="form-control" value="{{ $puntadewa->nama_pemohon }}" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label>NIK</label>
                                        <input type="text" class="form-control" value="{{ $puntadewa->nik }}" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label>Alamat Asal</label>
                                        <textarea class="form-control" rows="3" readonly>{{ $puntadewa->alamat_asal }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>File KK Asal</label>
                                        <div class="file-info">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-pdf fa-2x text-danger mr-3"></i>
                                                <div>
                                                    <div class="font-weight-bold">{{ basename($puntadewa->file_kk_asal) }}</div>
                                                    <div class="text-muted small">
                                                        <a href="{{ Storage::url($puntadewa->file_kk_asal) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i> Lihat File
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control" value="{{ $puntadewa->user->email }}" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label>No. Telepon</label>
                                        <input type="text" class="form-control" value="{{ $puntadewa->user->telp ?? '-' }}" readonly>
                                    </div>
                                </div>
                            </div>

                            {{-- RT/RW Info --}}
                            <div class="section-divider">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="text-primary">
                                            <i class="fas fa-map-marker-alt"></i> Lokasi RT/RW
                                        </h5>
                                        <hr>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>RT</label>
                                            <input type="text" class="form-control" value="RT {{ $puntadewa->rt }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>RW</label>
                                            <input type="text" class="form-control" value="RW {{ $puntadewa->rw }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Alasan Tinggal --}}
                            <div class="section-divider">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="text-primary">
                                            <i class="fas fa-clipboard-list"></i> Alasan Tinggal di Surabaya
                                        </h5>
                                        <hr>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Alamat Tempat Tinggal</label>
                                            <textarea class="form-control" rows="3" readonly>{{ $puntadewa->alasan_tinggal }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                {{-- Detail Alasan --}}
                                @if($puntadewa->nama_perusahaan || $puntadewa->nama_sekolah || $puntadewa->nama_rumah_sakit || $puntadewa->alasan_lainnya)
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-info">Detail Alasan:</h6>
                                    </div>
                                </div>

                                @if($puntadewa->nama_perusahaan)
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-info">1. Bekerja :</h6>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Perusahaan / Wiraswasta</label>
                                            <input type="text" class="form-control" value="{{ $puntadewa->nama_perusahaan }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Alamat Perusahaan</label>
                                            <input type="text" class="form-control" value="{{ $puntadewa->alamat_perusahaan ?? '-' }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($puntadewa->nama_sekolah)
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-info">2. Sekolah :</h6>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Sekolah / Perguruan Tinggi</label>
                                            <input type="text" class="form-control" value="{{ $puntadewa->nama_sekolah }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Alamat Sekolah/Perguruan Tinggi</label>
                                            <input type="text" class="form-control" value="{{ $puntadewa->alamat_sekolah ?? '-' }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($puntadewa->nama_rumah_sakit)
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-info">3. Kesehatan :</h6>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Rumah Sakit / Klinik</label>
                                            <input type="text" class="form-control" value="{{ $puntadewa->nama_rumah_sakit }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Alamat Rumah Sakit</label>
                                            <input type="text" class="form-control" value="{{ $puntadewa->alamat_rumah_sakit ?? '-' }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($puntadewa->alasan_lainnya)
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-info">4. Alasan Lainnya :</h6>
                                        <div class="form-group">
                                            <textarea class="form-control" rows="3" readonly>{{ $puntadewa->alasan_lainnya }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endif
                            </div>

                            {{-- Data Penjamin --}}
                            <div class="section-divider">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="text-primary">
                                            <i class="fas fa-user-shield"></i> Data Penjamin (Pemilik Rumah Kost/Kontrakan/Sejenisnya)
                                        </h5>
                                        <hr>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Penjamin</label>
                                            <input type="text" class="form-control" value="{{ $puntadewa->nama_penjamin }}" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label>NIK Penjamin</label>
                                            <input type="text" class="form-control" value="{{ $puntadewa->nik_penjamin }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Alamat Penjamin</label>
                                            <textarea class="form-control" rows="3" readonly>{{ $puntadewa->alamat_penjamin }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>No. Telepon Penjamin</label>
                                            <input type="text" class="form-control" value="{{ $puntadewa->no_telp_penjamin }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Lokasi --}}
                            @if($puntadewa->latitude && $puntadewa->longitude)
                            <div class="section-divider">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="text-primary">
                                            <i class="fas fa-map-marker-alt"></i> Lokasi Tempat Tinggal
                                        </h5>
                                        <hr>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Koordinat Lokasi</label>
                                            <input type="text" class="form-control" value="{{ $puntadewa->latitude }}, {{ $puntadewa->longitude }}" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label>Alamat Lokasi</label>
                                            <textarea class="form-control" rows="3" readonly>{{ $puntadewa->alamat_lokasi ?? '-' }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Peta Lokasi</label>
                                            <div id="map"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- Tanda Tangan --}}
                            <div class="section-divider">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="text-primary">
                                            <i class="fas fa-signature"></i> Tanda Tangan Digital
                                        </h5>
                                        <hr>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tanda Tangan Pemohon</label>
                                            <div class="signature-display">
                                                @if($puntadewa->ttd_pemohon)
                                                    <img src="{{ $puntadewa->ttd_pemohon }}" alt="Tanda Tangan Pemohon">
                                                @else
                                                    <span class="empty">Belum ada tanda tangan</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tanda Tangan Pemilik Kost/Kontrakan</label>
                                            <div class="signature-display">
                                                @if($puntadewa->ttd_pemilik_kost)
                                                    <img src="{{ $puntadewa->ttd_pemilik_kost }}" alt="Tanda Tangan Pemilik Kost">
                                                @else
                                                    <span class="empty">Belum ada tanda tangan</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Tanda Tangan RT & RW --}}
                                @if($puntadewa->ttd_rt || $puntadewa->ttd_rw)
                                    <div class="row">
                                        @if($puntadewa->ttd_rt)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Tanda Tangan RT</label>
                                                <div class="signature-display">
                                                    @php
                                                        // Handle both base64 data and file paths
                                                        if (str_starts_with($puntadewa->ttd_rt, 'data:image/')) {
                                                            $ttdRTSrc = $puntadewa->ttd_rt;
                                                        } else {
                                                            $ttdRTSrc = Storage::url($puntadewa->ttd_rt);
                                                        }
                                                    @endphp
                                                    <img src="{{ $ttdRTSrc }}" alt="Tanda Tangan RT">
                                                </div>
                                            </div>
                                            @if($puntadewa->stempel_rt)
                                            <div class="form-group">
                                                <label>Stempel RT</label>
                                                <div class="signature-display">
                                                    @php
                                                        // Handle both base64 data and file paths
                                                        if (str_starts_with($puntadewa->stempel_rt, 'data:image/')) {
                                                            $stempelRTSrc = $puntadewa->stempel_rt;
                                                        } else {
                                                            $stempelRTSrc = Storage::url($puntadewa->stempel_rt);
                                                        }
                                                    @endphp
                                                    <img src="{{ $stempelRTSrc }}" alt="Stempel RT">
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        @endif

                                        @if($puntadewa->ttd_rw)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Tanda Tangan RW</label>
                                                <div class="signature-display">
                                                    @php
                                                        // Handle both base64 data and file paths
                                                        if (str_starts_with($puntadewa->ttd_rw, 'data:image/')) {
                                                            $ttdRWSrc = $puntadewa->ttd_rw;
                                                        } else {
                                                            $ttdRWSrc = Storage::url($puntadewa->ttd_rw);
                                                        }
                                                    @endphp
                                                    <img src="{{ $ttdRWSrc }}" alt="Tanda Tangan RW">
                                                </div>
                                            </div>
                                            @if($puntadewa->stempel_rw)
                                            <div class="form-group">
                                                <label>Stempel RW</label>
                                                <div class="signature-display">
                                                    @php
                                                        // Handle both base64 data and file paths
                                                        if (str_starts_with($puntadewa->stempel_rw, 'data:image/')) {
                                                            $stempelRWSrc = $puntadewa->stempel_rw;
                                                        } else {
                                                            $stempelRWSrc = Storage::url($puntadewa->stempel_rw);
                                                        }
                                                    @endphp
                                                    <img src="{{ $stempelRWSrc }}" alt="Stempel RW">
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                @endif
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

    <!-- Leaflet JS for Map -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Signature Pad -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let map;
        let marker;
        let signaturePadRT, stempelPadRT;
        let signaturePadRW, stempelPadRW; // signaturePadPemilikKostRW
        let rtSpecimenData = null;
        let rwSpecimenData = null;

        $(document).ready(function() {
            // Initialize map if location exists
            @if($puntadewa->latitude && $puntadewa->longitude)
            initMap();
            @endif

            // Initialize signature pads for approval
            // @if($canApproveRT)
            // initSignaturePadsRT();
            // @endif

            // @if($canApproveRW)
            // initSignaturePadsRW();
            // @endif

            // Load RT spesimen data
            @if($canApproveRT)
                loadRTSpesimen();
            @endif

            // Load RW spesimen data
            @if($canApproveRW)
                loadRWSpesimen();
            @endif

            // RT Approval handlers
            $('#btn-approve-rt').on('click', function() {
                handleRTApproval(true);
            });

            $('#btn-reject-rt').on('click', function() {
                handleRTApproval(false);
            });

            // RW Approval handlers
            $('#btn-approve-rw').on('click', function() {
                handleRWApproval(true);
            });

            $('#btn-reject-rw').on('click', function() {
                handleRWApproval(false);
            });

            console.log('✅ PUNTADEWA Show page initialized successfully');
        });

        function initMap() {
            const lat = {{ $puntadewa->latitude }};
            const lng = {{ $puntadewa->longitude }};

            map = L.map('map').setView([lat, lng], 16);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Add marker
            marker = L.marker([lat, lng]).addTo(map);
        }

        function loadRTSpesimen() {
            console.log('🔄 Loading RT Spesimen...');

            $.ajax({
                url: "{{ route('puntadewa.get-rt-spesimen', $puntadewa->id) }}",
                method: 'GET',
                timeout: 10000,
                success: function(response) {
                    console.log('✅ RT Spesimen loaded successfully:', response);

                    if (response.success) {
                        const data = response.data;

                        // Simpan data spesimen untuk digunakan saat approval
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

                        // Show warning if either is missing
                        if (!data.ttd_rt || !data.stempel_rt) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Spesimen Tidak Lengkap',
                                text: 'TTD atau Stempel RT belum diupload. Silakan hubungi admin.',
                                confirmButtonText: 'OK'
                            });
                        }
                    } else {
                        handleSpesimenError('RT', response.message, response.debug);
                    }
                },
                error: function(xhr) {
                    console.error('❌ Error loading RT Spesimen:', xhr);
                    handleSpesimenError('RT', 'Error memuat spesimen RT', xhr.responseJSON?.debug);

                    if (xhr.status === 404) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Data Spesimen Tidak Ditemukan',
                            text: 'Data spesimen TTD/Stempel RT tidak ditemukan. Silakan hubungi admin.',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        }

        function loadRWSpesimen() {
            console.log('🔄 Loading RW Spesimen...');

            $.ajax({
                url: "{{ route('puntadewa.get-rw-spesimen', $puntadewa->id) }}",
                method: 'GET',
                timeout: 10000,
                success: function(response) {
                    console.log('✅ RW Spesimen loaded successfully:', response);

                    if (response.success) {
                        const data = response.data;

                        // Simpan data spesimen untuk digunakan saat approval
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

                        // Show warning if either is missing
                        if (!data.ttd_rw || !data.stempel_rw) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Spesimen Tidak Lengkap',
                                text: 'TTD atau Stempel RW belum diupload. Silakan hubungi admin.',
                                confirmButtonText: 'OK'
                            });
                        }
                    } else {
                        handleSpesimenError('RW', response.message, response.debug);
                    }
                },
                error: function(xhr) {
                    console.error('❌ Error loading RW Spesimen:', xhr);
                    handleSpesimenError('RW', 'Error memuat spesimen RW', xhr.responseJSON?.debug);

                    if (xhr.status === 404) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Data Spesimen Tidak Ditemukan',
                            text: 'Data spesimen TTD/Stempel RW tidak ditemukan. Silakan hubungi Operator / Tambahkan data terlebih dahulu.',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        }

        function handleSpesimenError(type, message, debugInfo) {
            const ttdId = type === 'RT' ? '#ttd-rt-preview' : '#ttd-rw-preview';
            const stempelId = type === 'RT' ? '#stempel-rt-preview' : '#stempel-rw-preview';

            $(ttdId).html(`
                <div class="text-danger text-center">
                    <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                    <br><small>${message}</small>
                </div>
            `);

            $(stempelId).html(`
                <div class="text-danger text-center">
                    <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                    <br><small>${message}</small>
                </div>
            `);

            // Log debug info if available
            if (debugInfo) {
                console.log(`Debug info for ${type} spesimen:`, debugInfo);
            }
        }

        function initSignaturePadsRT() {
            // RT Signature pad
            const canvasRT = document.getElementById('signaturePadRT');
            signaturePadRT = new SignaturePad(canvasRT, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)'
            });

            // RT Stempel pad
            const canvasStempelRT = document.getElementById('stempelPadRT');
            stempelPadRT = new SignaturePad(canvasStempelRT, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)'
            });

            // Resize canvas when window resizes
            window.addEventListener('resize', resizeCanvasesRT);
            resizeCanvasesRT();
        }

        function initSignaturePadsRW() {
            // RW Signature pad
            const canvasRW = document.getElementById('signaturePadRW');
            signaturePadRW = new SignaturePad(canvasRW, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)'
            });

            // RW Stempel pad
            const canvasStempelRW = document.getElementById('stempelPadRW');
            stempelPadRW = new SignaturePad(canvasStempelRW, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)'
            });

            // Pemilik Kost signature for RW
            // const canvasPemilikKostRW = document.getElementById('signaturePadPemilikKostRW');
            // signaturePadPemilikKostRW = new SignaturePad(canvasPemilikKostRW, {
            //     backgroundColor: 'rgb(255, 255, 255)',
            //     penColor: 'rgb(0, 0, 0)'
            // });

            // Resize canvas when window resizes
            window.addEventListener('resize', resizeCanvasesRW);
            resizeCanvasesRW();
        }

        function resizeCanvasesRT() {
            if (signaturePadRT) {
                const canvas = document.getElementById('signaturePadRT');
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext('2d').scale(ratio, ratio);
                signaturePadRT.clear();
            }

            if (stempelPadRT) {
                const canvas = document.getElementById('stempelPadRT');
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext('2d').scale(ratio, ratio);
                stempelPadRT.clear();
            }
        }

        function resizeCanvasesRW() {
            if (signaturePadRW) {
                const canvas = document.getElementById('signaturePadRW');
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext('2d').scale(ratio, ratio);
                signaturePadRW.clear();
            }

            if (stempelPadRW) {
                const canvas = document.getElementById('stempelPadRW');
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext('2d').scale(ratio, ratio);
                stempelPadRW.clear();
            }

            // if (signaturePadPemilikKostRW) {
            //     const canvas = document.getElementById('signaturePadPemilikKostRW');
            //     const ratio = Math.max(window.devicePixelRatio || 1, 1);
            //     canvas.width = canvas.offsetWidth * ratio;
            //     canvas.height = canvas.offsetHeight * ratio;
            //     canvas.getContext('2d').scale(ratio, ratio);
            //     signaturePadPemilikKostRW.clear();
            // }
        }

        function clearSignatureRT() {
            if (signaturePadRT) {
                signaturePadRT.clear();
            }
        }

        function clearStempelRT() {
            if (stempelPadRT) {
                stempelPadRT.clear();
            }
        }

        function clearSignatureRW() {
            if (signaturePadRW) {
                signaturePadRW.clear();
            }
        }

        function clearStempelRW() {
            if (stempelPadRW) {
                stempelPadRW.clear();
            }
        }

        // function clearSignaturePemilikKostRW() {
        //     if (signaturePadPemilikKostRW) {
        //         signaturePadPemilikKostRW.clear();
        //     }
        // }

        // Function untuk mengkonversi URL gambar ke base64
        // async function urlToBase64(url) {
        //     try {
        //         const response = await fetch(url);
        //         const blob = await response.blob();

        //         return new Promise((resolve, reject) => {
        //             const reader = new FileReader();
        //             reader.onloadend = () => resolve(reader.result);
        //             reader.onerror = reject;
        //             reader.readAsDataURL(blob);
        //         });
        //     } catch (error) {
        //         console.error('Error converting URL to base64:', error);
        //         throw error;
        //     }
        // }

        function handleRTApproval(isApprove) {
             if (!isApprove) {
                // For rejection, only need note
                const catatan = $('textarea[name="catatan_rt"]').val();
                if (!catatan.trim()) {
                    Swal.fire('Error', 'Catatan penolakan wajib diisi.', 'error');
                    return;
                }
            }

            const action = isApprove ? 'menyetujui' : 'menolak';

            Swal.fire({
                title: `Konfirmasi ${action.charAt(0).toUpperCase() + action.slice(1)}`,
                text: `Apakah Anda yakin ingin ${action} permohonan PUNTADEWA ini?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: `Ya, ${action.charAt(0).toUpperCase() + action.slice(1)}`,
                cancelButtonText: 'Batal',
                confirmButtonColor: isApprove ? '#28a745' : '#dc3545',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    processRTApproval(isApprove);
                }
            });
        }

        function processRTApproval(isApprove) {
            const btn = isApprove ? $('#btn-approve-rt') : $('#btn-reject-rt');
            const originalText = btn.html();

            btn.html('<i class="fas fa-spinner fa-spin"></i> Memproses...').prop('disabled', true);

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('catatan_rt', $('textarea[name="catatan_rt"]').val());

            // Kirim URL spesimen (bukan base64)
            if (isApprove && rtSpecimenData) {
                if (rtSpecimenData.ttd_rt) {
                    formData.append('ttd_rt_url', rtSpecimenData.ttd_rt);
                }

                if (rtSpecimenData.stempel_rt) {
                    formData.append('stempel_rt_url', rtSpecimenData.stempel_rt);
                }
            }

            const url = isApprove ?
                "{{ route('puntadewa.approve-rt', $puntadewa->id) }}" :
                "{{ route('puntadewa.reject-rt', $puntadewa->id) }}";

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            location.reload();
                        });
                    }
                },
                error: function(xhr) {
                    btn.html(originalText).prop('disabled', false);

                    let errorMessage = 'Terjadi kesalahan saat memproses permohonan.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire('Error', errorMessage, 'error');
                }
            });
        }

        function handleRWApproval(isApprove) {
        if (isApprove) {
            // Validate spesimen data tersedia
            if (!rwSpecimenData) {
                Swal.fire('Error', 'Data spesimen RW tidak tersedia. Silakan refresh halaman.', 'error');
                return;
            }

            if (!rwSpecimenData.ttd_rw) {
                Swal.fire('Error', 'TTD RW tidak tersedia. Silakan hubungi admin.', 'error');
                return;
            }

            if (!rwSpecimenData.stempel_rw) {
                Swal.fire('Error', 'Stempel RW tidak tersedia. Silakan hubungi admin.', 'error');
                return;
            }
        } else {
            // For rejection, only need note
            const catatan = $('textarea[name="catatan_rw"]').val();
            if (!catatan.trim()) {
                Swal.fire('Error', 'Catatan penolakan wajib diisi.', 'error');
                return;
            }
        }

        const action = isApprove ? 'menyetujui' : 'menolak';

        Swal.fire({
            title: `Konfirmasi ${action.charAt(0).toUpperCase() + action.slice(1)}`,
            text: `Apakah Anda yakin ingin ${action} permohonan PUNTADEWA ini?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: `Ya, ${action.charAt(0).toUpperCase() + action.slice(1)}`,
            cancelButtonText: 'Batal',
            confirmButtonColor: isApprove ? '#28a745' : '#dc3545',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                processRWApproval(isApprove);
            }
        });
    }

        function processRWApproval(isApprove) {
            const btn = isApprove ? $('#btn-approve-rw') : $('#btn-reject-rw');
            const originalText = btn.html();

            btn.html('<i class="fas fa-spinner fa-spin"></i> Memproses...').prop('disabled', true);

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('catatan_rw', $('textarea[name="catatan_rw"]').val());

            // Kirim URL spesimen (bukan base64)
            if (isApprove && rwSpecimenData) {
                if (rwSpecimenData.ttd_rw) {
                    formData.append('ttd_rw_url', rwSpecimenData.ttd_rw);
                }

                if (rwSpecimenData.stempel_rw) {
                    formData.append('stempel_rw_url', rwSpecimenData.stempel_rw);
                }
            }

            const url = isApprove ?
                "{{ route('puntadewa.approve-rw', $puntadewa->id) }}" :
                "{{ route('puntadewa.reject-rw', $puntadewa->id) }}";

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            location.reload();
                        });
                    }
                },
                error: function(xhr) {
                    btn.html(originalText).prop('disabled', false);

                    let errorMessage = 'Terjadi kesalahan saat memproses permohonan.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire('Error', errorMessage, 'error');
                }
            });
        }

        function printDocument() {
            window.print();
        }
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
