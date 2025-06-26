{{-- resources/views/Skaw/show.blade.php --}}
@extends('Template.template')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">

    <style>
        .detail-card {
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .detail-header {
            background: linear-gradient(135deg, #6777ef 0%, #5a67d8 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 12px 12px 0 0;
            margin: 0;
        }

        .detail-body {
            padding: 20px;
        }

        .info-row {
            display: flex;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f1f1f1;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
            width: 200px;
            flex-shrink: 0;
        }

        .info-value {
            color: #6c757d;
            flex: 1;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-submitted {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
        }

        .status-draft {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
        }

        .status-approved {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            color: white;
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }

        .timeline-item {
            position: relative;
            padding: 15px 0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -23px;
            top: 20px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #e9ecef;
            border: 2px solid white;
        }

        .timeline-item.completed::before {
            background: #28a745;
        }

        .timeline-item.current::before {
            background: #ffc107;
        }

        .file-list {
            list-style: none;
            padding: 0;
        }

        .file-item {
            display: flex;
            justify-content: between;
            align-items: center;
            padding: 8px 12px;
            margin-bottom: 5px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid #007bff;
        }

        .file-name {
            font-weight: 500;
            color: #495057;
        }

        .file-size {
            font-size: 11px;
            color: #6c757d;
        }

        .download-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 4px 8px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 11px;
        }

        .download-btn:hover {
            background: #218838;
            color: white;
            text-decoration: none;
        }

        .anak-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
        }

        .saksi-card {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
        }

        .action-buttons {
            position: sticky;
            bottom: 20px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            margin-top: 30px;
        }

        .btn-back {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            border: none;
            color: white;
        }

        .btn-edit {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            border: none;
            color: #212529;
        }

        .btn-delete {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none;
            color: white;
        }

        .document-preview {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            margin: 10px 0;
        }
    </style>
@endpush

@section('Dashboard')
    @include('admin.dashboard.header')
    @include('admin.dashboard.sidebar')

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Detail SKAW</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('skaw.permohonan-saya') }}">SKAW</a>
                    </div>
                    <div class="breadcrumb-item active">Detail</div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    {{-- Detail SKAW --}}
                    <div class="detail-card card">
                        <div class="detail-header">
                            <h5 class="mb-0">
                                <i class="fas fa-file-alt mr-2"></i>
                                Informasi SKAW
                            </h5>
                        </div>
                        <div class="detail-body">
                            <div class="info-row">
                                <div class="info-label">Nomor Surat:</div>
                                <div class="info-value">
                                    <strong class="text-primary">{{ $skaw->nomor_surat }}</strong>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Status:</div>
                                <div class="info-value">
                                    <span class="status-badge status-{{ $skaw->status }}">
                                        {{ ucfirst(str_replace('_', ' ', $skaw->status)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Tanggal Dibuat:</div>
                                <div class="info-value">{{ $skaw->created_at->format('d F Y, H:i') }} WIB</div>
                            </div>
                            @if($skaw->submitted_at)
                            <div class="info-row">
                                <div class="info-label">Tanggal Diajukan:</div>
                                <div class="info-value">{{ $skaw->submitted_at->format('d F Y, H:i') }} WIB</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Data Pemohon --}}
                    <div class="detail-card card">
                        <div class="detail-header">
                            <h5 class="mb-0">
                                <i class="fas fa-user mr-2"></i>
                                Data Pemohon
                            </h5>
                        </div>
                        <div class="detail-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <div class="info-label">Nama Lengkap:</div>
                                        <div class="info-value">{{ $skaw->nama_lengkap }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">NIK:</div>
                                        <div class="info-value">{{ $skaw->nik }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Tempat, Tanggal Lahir:</div>
                                        <div class="info-value">{{ $skaw->tempat_lahir }}, {{ $skaw->tanggal_lahir->format('d F Y') }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Jenis Kelamin:</div>
                                        <div class="info-value">{{ $skaw->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Agama:</div>
                                        <div class="info-value">{{ $skaw->agama }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Status Perkawinan:</div>
                                        <div class="info-value">{{ $skaw->status_perkawinan }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <div class="info-label">Pekerjaan:</div>
                                        <div class="info-value">{{ $skaw->pekerjaan }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Kewarganegaraan:</div>
                                        <div class="info-value">{{ $skaw->kewarganegaraan }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Alamat:</div>
                                        <div class="info-value">{{ $skaw->alamat }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">RT/RW:</div>
                                        <div class="info-value">RT {{ sprintf('%02d', $skaw->rt) }} / RW {{ sprintf('%02d', $skaw->rw) }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">No. KK:</div>
                                        <div class="info-value">{{ $skaw->nomor_kk }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Email:</div>
                                        <div class="info-value">{{ $skaw->email }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">No. Telepon:</div>
                                        <div class="info-value">{{ $skaw->no_telepon ?? '-' }}</div>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h6 class="text-primary mb-3">Data Khusus SKAW</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <div class="info-label">No. Akta Perkawinan:</div>
                                        <div class="info-value">{{ $skaw->nomor_akta_perkawinan ?? '-' }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Tanggal Terbit Akta Perkawinan:</div>
                                        <div class="info-value">
                                            {{ $skaw->tanggal_terbit_akta_perkawinan ? \Carbon\Carbon::parse($skaw->tanggal_terbit_akta_perkawinan)->format('d F Y') : '-' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <div class="info-label">Jumlah Anak:</div>
                                        <div class="info-value">{{ $skaw->jumlah_anak }} anak</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Data Anak --}}
                    @if($skaw->jumlah_anak > 0 && $skaw->anakList->count() > 0)
                    <div class="detail-card card">
                        <div class="detail-header">
                            <h5 class="mb-0">
                                <i class="fas fa-child mr-2"></i>
                                Data Anak ({{ $skaw->anakList->count() }} anak)
                            </h5>
                        </div>
                        <div class="detail-body">
                            @foreach($skaw->anakList as $anak)
                            <div class="anak-card">
                                <h6 class="text-primary mb-2">Anak ke-{{ $anak->urutan }}</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-row">
                                            <div class="info-label">Nama Lengkap:</div>
                                            <div class="info-value">{{ $anak->nama_lengkap }}</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Tempat, Tanggal Lahir:</div>
                                            <div class="info-value">{{ $anak->tempat_lahir }}, {{ \Carbon\Carbon::parse($anak->tanggal_lahir)->format('d F Y') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-row">
                                            <div class="info-label">Jenis Kelamin:</div>
                                            <div class="info-value">{{ $anak->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Alamat:</div>
                                            <div class="info-value">{{ $anak->alamat }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Data Pewaris --}}
                    <div class="detail-card card">
                        <div class="detail-header">
                            <h5 class="mb-0">
                                <i class="fas fa-user-tie mr-2"></i>
                                Data Pewaris (Yang Meninggal)
                            </h5>
                        </div>
                        <div class="detail-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <div class="info-label">NIK:</div>
                                        <div class="info-value">{{ $skaw->pewaris_nik }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Nama Lengkap:</div>
                                        <div class="info-value">{{ $skaw->pewaris_nama_lengkap }} {{ $skaw->pewaris_gelar ? ', ' . $skaw->pewaris_gelar : '' }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Tempat, Tanggal Lahir:</div>
                                        <div class="info-value">{{ $skaw->pewaris_tempat_lahir }}, {{ $skaw->pewaris_tanggal_lahir->format('d F Y') }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Tempat Tinggal Terakhir:</div>
                                        <div class="info-value">{{ $skaw->pewaris_tempat_tinggal_terakhir }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <div class="info-label">Tanggal Kematian:</div>
                                        <div class="info-value">{{ $skaw->pewaris_tanggal_kematian->format('d F Y') }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Tempat Kematian:</div>
                                        <div class="info-value">{{ $skaw->pewaris_tempat_kematian }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">No. Akta Kematian:</div>
                                        <div class="info-value">{{ $skaw->pewaris_nomor_akta_kematian }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Tanggal Terbit Akta Kematian:</div>
                                        <div class="info-value">{{ $skaw->pewaris_tanggal_terbit_akta_kematian->format('d F Y') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Data Saksi --}}
                    <div class="detail-card card">
                        <div class="detail-header">
                            <h5 class="mb-0">
                                <i class="fas fa-users mr-2"></i>
                                Data Saksi
                            </h5>
                        </div>
                        <div class="detail-body">
                            @if($skaw->data_saksi)
                                @php $dataSaksi = is_string($skaw->data_saksi) ? json_decode($skaw->data_saksi, true) : $skaw->data_saksi; @endphp

                                {{-- Saksi 1 --}}
                                @if(isset($dataSaksi['saksi1']))
                                <div class="saksi-card">
                                    <h6 class="text-warning mb-2">Saksi 1</h6>
                                    <div class="info-row">
                                        <div class="info-label">Nama Lengkap:</div>
                                        <div class="info-value">{{ $dataSaksi['saksi1']['nama_lengkap'] }} {{ $dataSaksi['saksi1']['gelar'] ? ', ' . $dataSaksi['saksi1']['gelar'] : '' }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Alamat:</div>
                                        <div class="info-value">{{ $dataSaksi['saksi1']['alamat'] }}</div>
                                    </div>
                                </div>
                                @endif

                                {{-- Saksi 2 --}}
                                @if(isset($dataSaksi['saksi2']))
                                <div class="saksi-card">
                                    <h6 class="text-warning mb-2">Saksi 2</h6>
                                    <div class="info-row">
                                        <div class="info-label">Nama Lengkap:</div>
                                        <div class="info-value">{{ $dataSaksi['saksi2']['nama_lengkap'] }} {{ $dataSaksi['saksi2']['gelar'] ? ', ' . $dataSaksi['saksi2']['gelar'] : '' }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Alamat:</div>
                                        <div class="info-value">{{ $dataSaksi['saksi2']['alamat'] }}</div>
                                    </div>
                                </div>
                                @endif
                            @else
                                <p class="text-muted">Data saksi tidak tersedia</p>
                            @endif
                        </div>
                    </div>

                    {{-- File Persyaratan --}}
                    <div class="detail-card card">
                        <div class="detail-header">
                            <h5 class="mb-0">
                                <i class="fas fa-folder-open mr-2"></i>
                                File Persyaratan ({{ $skaw->files->count() }} file)
                            </h5>
                        </div>
                        <div class="detail-body">
                            @if($skaw->files->count() > 0)
                                <ul class="file-list">
                                    @foreach($skaw->files as $file)
                                    <li class="file-item">
                                        <div>
                                            <div class="file-name">{{ $file->getFileTypeLabel() }}</div>
                                            <div class="file-size">{{ $file->file_name }} ({{ $file->getFormattedSize() }})</div>
                                        </div>
                                        <a href="{{ asset('storage/' . $file->file_path) }}"
                                           class="download-btn" target="_blank">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted">Belum ada file yang diupload</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    {{-- Timeline Progress --}}
                    <div class="detail-card card">
                        <div class="detail-header">
                            <h5 class="mb-0">
                                <i class="fas fa-tasks mr-2"></i>
                                Progress SKAW
                            </h5>
                        </div>
                        <div class="detail-body">
                            <div class="timeline">
                                <div class="timeline-item {{ $skaw->status != 'draft' ? 'completed' : 'current' }}">
                                    <strong>Diajukan</strong>
                                    <div class="text-muted small">
                                        {{ $skaw->submitted_at ? $skaw->submitted_at->format('d M Y, H:i') : 'Belum diajukan' }}
                                    </div>
                                </div>

                                <div class="timeline-item {{ $skaw->front_office_approved_at ? 'completed' : ($skaw->status == 'submitted' ? 'current' : '') }}">
                                    <strong>Review Front Office</strong>
                                    <div class="text-muted small">
                                        {{ $skaw->front_office_approved_at ? $skaw->front_office_approved_at->format('d M Y, H:i') : 'Menunggu review' }}
                                    </div>
                                </div>

                                <div class="timeline-item {{ $skaw->file_tanda_terima ? 'completed' : '' }}">
                                    <strong>Tanda Terima & Draft SKAW</strong>
                                    <div class="text-muted small">
                                        {{ $skaw->file_tanda_terima ? 'Dokumen tersedia' : 'Belum dibuat' }}
                                    </div>
                                </div>

                                <div class="timeline-item {{ $skaw->tanggal_sidang ? 'completed' : '' }}">
                                    <strong>Jadwal Sidang</strong>
                                    <div class="text-muted small">
                                        {{ $skaw->tanggal_sidang ? $skaw->tanggal_sidang->format('d M Y') : 'Belum dijadwalkan' }}
                                    </div>
                                </div>

                                <div class="timeline-item {{ $skaw->evidence_uploaded_at ? 'completed' : '' }}">
                                    <strong>Upload Evidence</strong>
                                    <div class="text-muted small">
                                        {{ $skaw->evidence_uploaded_at ? $skaw->evidence_uploaded_at->format('d M Y, H:i') : 'Belum upload' }}
                                    </div>
                                </div>

                                <div class="timeline-item {{ $skaw->lurah_approved_at ? 'completed' : '' }}">
                                    <strong>Approval Lurah</strong>
                                    <div class="text-muted small">
                                        {{ $skaw->lurah_approved_at ? $skaw->lurah_approved_at->format('d M Y, H:i') : 'Menunggu approval' }}
                                    </div>
                                </div>

                                <div class="timeline-item {{ $skaw->camat_approved_at ? 'completed' : '' }}">
                                    <strong>Approval Camat</strong>
                                    <div class="text-muted small">
                                        {{ $skaw->camat_approved_at ? $skaw->camat_approved_at->format('d M Y, H:i') : 'Menunggu approval' }}
                                    </div>
                                </div>

                                <div class="timeline-item {{ $skaw->completed_at ? 'completed' : '' }}">
                                    <strong>SKAW Final</strong>
                                    <div class="text-muted small">
                                        {{ $skaw->completed_at ? $skaw->completed_at->format('d M Y, H:i') : 'Belum selesai' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Documents --}}
                    @if($skaw->file_tanda_terima || $skaw->file_skaw_draft || $skaw->file_skaw_final)
                    <div class="detail-card card">
                        <div class="detail-header">
                            <h5 class="mb-0">
                                <i class="fas fa-file-pdf mr-2"></i>
                                Dokumen SKAW
                            </h5>
                        </div>
                        <div class="detail-body">
                            @if($skaw->file_tanda_terima)
                            <div class="document-preview">
                                <i class="fas fa-receipt fa-3x text-info mb-2"></i>
                                <div><strong>Tanda Terima</strong></div>
                                <a href="{{ route('skaw.preview-tanda-terima', $skaw->id) }}"
                                   class="btn btn-info btn-sm mt-2" target="_blank">
                                    <i class="fas fa-eye"></i> Preview
                                </a>
                            </div>
                            @endif

                            @if($skaw->file_skaw_draft)
                            <div class="document-preview">
                                <i class="fas fa-file-alt fa-3x text-warning mb-2"></i>
                                <div><strong>SKAW Draft</strong></div>
                                <a href="{{ route('skaw.preview-draft', $skaw->id) }}"
                                   class="btn btn-warning btn-sm mt-2" target="_blank">
                                    <i class="fas fa-eye"></i> Preview
                                </a>
                            </div>
                            @endif

                            @if($skaw->file_skaw_final)
                            <div class="document-preview">
                                <i class="fas fa-certificate fa-3x text-success mb-2"></i>
                                <div><strong>SKAW Final</strong></div>
                                <a href="{{ route('skaw.preview-final', $skaw->id) }}"
                                   class="btn btn-success btn-sm mt-2" target="_blank">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="action-buttons">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('skaw.permohonan-saya') }}" class="btn btn-back">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>

                    <div>
                        {{-- Edit button - only if status is draft or submitted and user is owner --}}
                        @if($skaw->canBeEditedBy(Auth::user()))
                            <a href="{{ route('skaw.edit', $skaw->id) }}" class="btn btn-edit mr-2">
                                <i class="fas fa-edit mr-2"></i>Edit
                            </a>
                        @endif

                        {{-- Delete button - only if status is draft or submitted and user is owner/admin --}}
                        @if($skaw->canBeDeletedBy(Auth::user()))
                            <form action="{{ route('skaw.destroy', $skaw->id) }}" method="POST" class="d-inline" id="deleteForm">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-delete" onclick="confirmDelete('{{ $skaw->nama_lengkap }}')">
                                    <i class="fas fa-trash mr-2"></i>Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmDelete(nama) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                html: `Apakah Anda yakin ingin menghapus SKAW atas nama <strong>${nama}</strong>?<br><br><small class="text-muted">Data yang sudah dihapus tidak dapat dikembalikan.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus',
                cancelButtonText: '<i class="fas fa-times"></i> Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm').submit();
                }
            });
        }
    </script>
@endpush
