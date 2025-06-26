{{-- resources/views/Skaw/index-telah-sidang.blade.php --}}
@extends('Template.template')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">

    <style>
        .table-striped thead th {
            background-color: #f4f6f9;
            color: #34395e;
            font-weight: 600;
            padding: 15px;
            border-bottom: 2px solid #e9ecef;
        }

        .table-striped tbody tr {
            transition: all 0.3s ease;
        }

        .table-striped tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        .table-striped tbody tr:hover {
            background-color: #f4f6f9;
        }

        .table-striped tbody td {
            padding: 12px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
        }

        .summary-card {
            transition: all 0.2s ease;
            border-left: 4px solid transparent;
        }

        .summary-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .summary-card.evidence-uploaded { border-left-color: #ffc107; }
        .summary-card.lurah-approved { border-left-color: #17a2b8; }
        .summary-card.camat-approved { border-left-color: #28a745; }
        .summary-card.total { border-left-color: #6f42c1; }

        .approval-timeline {
            position: relative;
            padding-left: 30px;
        }

        .approval-timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }

        .approval-step {
            position: relative;
            padding: 10px 0;
        }

        .approval-step::before {
            content: '';
            position: absolute;
            left: -24px;
            top: 15px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #e9ecef;
        }

        .approval-step.completed::before {
            background: #28a745;
        }

        .approval-step.pending::before {
            background: #ffc107;
        }

        .evidence-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }

        .evidence-thumbnail {
            width: 100px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #dee2e6;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .evidence-thumbnail:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .status-priority {
            position: relative;
        }

        .status-priority.high {
            background: linear-gradient(45deg, #ff6b6b, #ffa500);
            color: white;
        }

        .status-priority.medium {
            background: linear-gradient(45deg, #4ecdc4, #44a08d);
            color: white;
        }

        .status-priority.low {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }

        .document-status {
            display: inline-flex;
            align-items: center;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            margin: 2px;
        }

        .document-status.has-evidence {
            background-color: #e7f3ff;
            color: #0066cc;
        }

        .document-status.has-ttd-scan {
            background-color: #f0f9e7;
            color: #4d7c0f;
        }

        .document-status.need-approval {
            background-color: #fff3cd;
            color: #856404;
        }

        .approval-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background: white;
        }

        .approval-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 10px;
        }

        .evidence-preview {
            max-width: 150px;
            max-height: 120px;
            object-fit: cover;
            border-radius: 6px;
            border: 2px solid #dee2e6;
            cursor: pointer;
            transition: all 0.2s;
        }

        .evidence-preview:hover {
            border-color: #007bff;
            transform: scale(1.05);
        }

        .approval-buttons {
            display: flex;
            gap: 8px;
            margin-top: 10px;
        }

        .btn-approve {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-approve:hover {
            background: linear-gradient(45deg, #218838, #1e9e8a);
            color: white;
        }

        .btn-reject {
            background: linear-gradient(45deg, #dc3545, #fd7e14);
            border: none;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-reject:hover {
            background: linear-gradient(45deg, #c82333, #fd6c0e);
            color: white;
        }

        .modal-evidence {
            max-width: 90%;
            max-height: 80vh;
            object-fit: contain;
        }
    </style>
@endpush

@section('Dashboard')
    @include('admin.dashboard.header')
    @include('admin.dashboard.sidebar')

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>SKAW Telah Sidang - Menunggu Approval</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('Dashboard.General') }}">Dashboard</a>
                    </div>
                    <div class="breadcrumb-item">SKAW</div>
                    <div class="breadcrumb-item active">Telah Sidang</div>
                </div>
            </div>

            {{-- Summary Cards --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card summary-card evidence-uploaded border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-camera fa-2x text-warning mb-2"></i>
                            <h6 class="card-title text-muted">Evidence Uploaded</h6>
                            <h4 class="text-warning mb-0" id="evidenceUploadedCount">-</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card summary-card lurah-approved border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-user-tie fa-2x text-info mb-2"></i>
                            <h6 class="card-title text-muted">Lurah Approved</h6>
                            <h4 class="text-info mb-0" id="lurahApprovedCount">-</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card summary-card camat-approved border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-user-check fa-2x text-success mb-2"></i>
                            <h6 class="card-title text-muted">Camat Approved</h6>
                            <h4 class="text-success mb-0" id="camatApprovedCount">-</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card summary-card total border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-gavel fa-2x text-purple mb-2"></i>
                            <h6 class="card-title text-muted">Total Sidang</h6>
                            <h4 class="text-purple mb-0" id="totalTelahSidangCount">-</h4>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Role-specific Action Panel --}}
            @if($userRole === 'Lurah')
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0"><i class="fas fa-user-tie mr-2"></i>Panel Lurah - Review Evidence Sidang</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p><strong>Tugas Lurah:</strong></p>
                                        <ul class="small">
                                            <li>Review evidence foto sidang yang telah diupload Back Office</li>
                                            <li>Pastikan evidence foto menunjukkan proses sidang yang valid</li>
                                            <li>Berikan approval sebagai tanda tracking bahwa berkas telah diproses TTD basah oleh Lurah</li>
                                            <li>Berikan catatan jika diperlukan untuk perbaikan atau informasi tambahan</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="alert alert-info mb-0">
                                            <i class="fas fa-info-circle"></i>
                                            <strong>Catatan:</strong><br>
                                            <small>Approval ini adalah tracking status digital. TTD basah tetap dilakukan secara manual pada dokumen fisik.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($userRole === 'Camat')
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="fas fa-user-check mr-2"></i>Panel Camat - Final Approval</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p><strong>Tugas Camat:</strong></p>
                                        <ul class="small">
                                            <li>Review berkas SKAW yang sudah diapprove Lurah</li>
                                            <li>Pastikan semua evidence dan dokumen sudah lengkap dan valid</li>
                                            <li>Berikan final approval sebagai tanda tracking bahwa berkas telah diproses TTD basah oleh Camat</li>
                                            <li>Setelah approval Camat, berkas siap untuk finalisasi oleh Back Office</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="alert alert-success mb-0">
                                            <i class="fas fa-info-circle"></i>
                                            <strong>Catatan:</strong><br>
                                            <small>Setelah approval Camat, Back Office akan memproses SKAW final yang sudah tertandatangani.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($userRole === 'Back Office')
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-upload mr-2"></i>Panel Back Office - Upload Evidence Sidang</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p><strong>Tugas Back Office:</strong></p>
                                        <ul class="small">
                                            <li>Upload evidence foto sidang yang sudah dilaksanakan</li>
                                            <li>Upload scan berkas SKAW yang sudah ditandatangani pemohon</li>
                                            <li>Pastikan semua dokumen sudah lengkap sebelum submit untuk approval</li>
                                            <li>Monitor proses approval dari Lurah dan Camat</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#uploadEvidenceModal">
                                            <i class="fas fa-camera mr-2"></i>Upload Evidence Sidang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Data Table --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4>Daftar SKAW Telah Sidang</h4>
                            <div class="d-flex align-items-center">
                                <div class="input-group" style="max-width: 300px;">
                                    <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Cari berdasarkan nama atau nomor...">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary btn-sm" type="button">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="telahSidangTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 3%;">#</th>
                                            <th style="width: 15%;">Nomor Permohonan</th>
                                            <th style="width: 18%;">Nama Pemohon</th>
                                            <th style="width: 18%;">Nama Pewaris</th>
                                            <th style="width: 12%;">Tanggal Sidang</th>
                                            <th style="width: 15%;">Status Approval</th>
                                            <th style="width: 12%;">Evidence</th>
                                            <th style="width: 7%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($telahSidang as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <strong class="text-primary">{{ $item->nomor_permohonan }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $item->created_at->format('d M Y') }}</small>
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong>{{ $item->user->name ?? 'N/A' }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $item->user->nik ?? 'N/A' }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong>{{ $item->pewaris->nama_lengkap ?? 'N/A' }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $item->pewaris->nik ?? 'N/A' }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($item->jadwalSidang)
                                                        <strong class="text-success">{{ \Carbon\Carbon::parse($item->jadwalSidang->tanggal_sidang)->format('d M Y') }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ \Carbon\Carbon::parse($item->jadwalSidang->tanggal_sidang)->format('H:i') }}</small>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="approval-timeline">
                                                        <div class="approval-step {{ $item->evidence_foto_sidang ? 'completed' : 'pending' }}">
                                                            <span class="document-status {{ $item->evidence_foto_sidang ? 'has-evidence' : 'need-approval' }}">
                                                                Evidence: {{ $item->evidence_foto_sidang ? 'Upload' : 'Pending' }}
                                                            </span>
                                                        </div>
                                                        <div class="approval-step {{ $item->tanggal_lurah_approved ? 'completed' : 'pending' }}">
                                                            <span class="document-status {{ $item->tanggal_lurah_approved ? 'has-ttd-scan' : 'need-approval' }}">
                                                                Lurah: {{ $item->tanggal_lurah_approved ? 'Approved' : 'Pending' }}
                                                            </span>
                                                        </div>
                                                        <div class="approval-step {{ $item->tanggal_camat_approved ? 'completed' : 'pending' }}">
                                                            <span class="document-status {{ $item->tanggal_camat_approved ? 'has-ttd-scan' : 'need-approval' }}">
                                                                Camat: {{ $item->tanggal_camat_approved ? 'Approved' : 'Pending' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($item->evidence_foto_sidang)
                                                        <div class="evidence-gallery">
                                                            @php
                                                                $evidences = is_string($item->evidence_foto_sidang) ? json_decode($item->evidence_foto_sidang, true) : $item->evidence_foto_sidang;
                                                            @endphp
                                                            @if($evidences && is_array($evidences))
                                                                @foreach(array_slice($evidences, 0, 2) as $evidence)
                                                                    <img src="{{ asset('storage/' . $evidence) }}"
                                                                         class="evidence-thumbnail"
                                                                         onclick="showEvidenceModal('{{ asset('storage/' . $evidence) }}')"
                                                                         alt="Evidence">
                                                                @endforeach
                                                                @if(count($evidences) > 2)
                                                                    <div class="evidence-thumbnail d-flex align-items-center justify-content-center bg-light">
                                                                        <small>+{{ count($evidences) - 2 }}</small>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span class="text-muted">
                                                            <i class="fas fa-camera-slash"></i> Belum Upload
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                                                                data-toggle="dropdown">
                                                            <i class="fas fa-cog"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="{{ route('skaw.show', $item->id) }}">
                                                                <i class="fas fa-eye mr-2"></i>Detail
                                                            </a>

                                                            @if($userRole === 'Back Office' && !$item->evidence_foto_sidang)
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item text-primary" href="#"
                                                                   onclick="uploadEvidence('{{ $item->id }}')">
                                                                    <i class="fas fa-camera mr-2"></i>Upload Evidence
                                                                </a>
                                                            @endif

                                                            @if($userRole === 'Lurah' && $item->evidence_foto_sidang && !$item->tanggal_lurah_approved)
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item text-warning" href="#"
                                                                   onclick="approveByLurah('{{ $item->id }}')">
                                                                    <i class="fas fa-check mr-2"></i>Approve (Lurah)
                                                                </a>
                                                            @endif

                                                            @if($userRole === 'Camat' && $item->tanggal_lurah_approved && !$item->tanggal_camat_approved)
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item text-success" href="#"
                                                                   onclick="approveByCamat('{{ $item->id }}')">
                                                                    <i class="fas fa-check-double mr-2"></i>Final Approve (Camat)
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i class="fas fa-gavel fa-3x mb-3"></i>
                                                        <h6>Belum ada SKAW yang telah sidang</h6>
                                                        <p class="small">Data SKAW yang sudah selesai sidang akan muncul di sini</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if($telahSidang->hasPages())
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $telahSidang->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- Upload Evidence Modal --}}
    @if($userRole === 'Back Office')
        <div class="modal fade" id="uploadEvidenceModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-camera mr-2"></i>Upload Evidence Sidang
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <form id="uploadEvidenceForm" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Pilih Permohonan SKAW</label>
                                <select class="form-control" name="permohonan_id" required>
                                    <option value="">-- Pilih Permohonan --</option>
                                    @foreach($readyForEvidence as $ready)
                                        <option value="{{ $ready->id }}">
                                            {{ $ready->nomor_permohonan }} - {{ $ready->user->name ?? 'N/A' }}
                                            ({{ $ready->pewaris->nama_lengkap ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Upload Foto Evidence Sidang</label>
                                <input type="file" class="form-control" name="evidence_foto[]"
                                       accept="image/*" multiple required>
                                <small class="text-muted">Format: JPG, PNG, JPEG. Max: 5MB per file. Bisa upload multiple foto.</small>
                            </div>

                            <div class="form-group">
                                <label>Keterangan Evidence</label>
                                <textarea class="form-control" name="keterangan_evidence" rows="3"
                                         placeholder="Keterangan mengenai foto evidence sidang..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload mr-2"></i>Upload Evidence
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Evidence View Modal --}}
    <div class="modal fade" id="evidenceModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Evidence Foto Sidang</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="evidenceImage" src="" class="modal-evidence" alt="Evidence">
                </div>
            </div>
        </div>
    </div>

    {{-- Approval Modal --}}
    <div class="modal fade" id="approvalModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-check mr-2"></i>Approval Confirmation
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="approvalForm">
                    <div class="modal-body">
                        <input type="hidden" name="permohonan_id" id="approvalPermohonanId">
                        <input type="hidden" name="approval_type" id="approvalType">

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span id="approvalMessage"></span>
                        </div>

                        <div class="form-group">
                            <label>Catatan (Opsional)</label>
                            <textarea class="form-control" name="catatan" rows="3"
                                     placeholder="Berikan catatan jika diperlukan..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check mr-2"></i>Approve
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#telahSidangTable').DataTable({
                "pageLength": 10,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Data tidak ditemukan",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data tersedia",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });

            // Custom search
            $('#searchInput').on('keyup', function() {
                $('#telahSidangTable').DataTable().search(this.value).draw();
            });

            // Load summary counts
            loadSummaryCounts();
        });

        function loadSummaryCounts() {
            $.ajax({
                url: '{{ route("skaw.telah-sidang-summary") }}',
                method: 'GET',
                success: function(response) {
                    $('#evidenceUploadedCount').text(response.evidenceUploaded || 0);
                    $('#lurahApprovedCount').text(response.lurahApproved || 0);
                    $('#camatApprovedCount').text(response.camatApproved || 0);
                    $('#totalTelahSidangCount').text(response.totalTelahSidang || 0);
                },
                error: function() {
                    console.log('Error loading summary counts');
                }
            });
        }

        // Show evidence modal
        function showEvidenceModal(imageSrc) {
            $('#evidenceImage').attr('src', imageSrc);
            $('#evidenceModal').modal('show');
        }

        // Upload evidence function
        function uploadEvidence(permohonanId) {
            $('select[name="permohonan_id"]').val(permohonanId);
            $('#uploadEvidenceModal').modal('show');
        }

        // Handle upload evidence form
        $('#uploadEvidenceForm').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: '{{ route("skaw.upload-evidence") }}',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.success) {
                        $('#uploadEvidenceModal').modal('hide');
                        showNotification('success', 'Evidence berhasil diupload!');
                        location.reload();
                    } else {
                        showNotification('error', response.message || 'Terjadi kesalahan');
                    }
                },
                error: function(xhr) {
                    let message = 'Terjadi kesalahan';
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    showNotification('error', message);
                }
            });
        });

        // Approve by Lurah
        function approveByLurah(permohonanId) {
            $('#approvalPermohonanId').val(permohonanId);
            $('#approvalType').val('lurah');
            $('#approvalMessage').text('Anda akan memberikan approval sebagai Lurah. Ini menandakan bahwa berkas telah diproses dan ditandatangani secara basah oleh Lurah.');
            $('#approvalModal').modal('show');
        }

        // Approve by Camat
        function approveByCamat(permohonanId) {
            $('#approvalPermohonanId').val(permohonanId);
            $('#approvalType').val('camat');
            $('#approvalMessage').text('Anda akan memberikan final approval sebagai Camat. Setelah ini, berkas akan siap untuk finalisasi oleh Back Office.');
            $('#approvalModal').modal('show');
        }

        // Handle approval form
        $('#approvalForm').on('submit', function(e) {
            e.preventDefault();

            let formData = $(this).serialize();
            let approvalType = $('#approvalType').val();
            let url = '';

            if(approvalType === 'lurah') {
                url = '{{ route("skaw.approve-lurah") }}';
            } else if(approvalType === 'camat') {
                url = '{{ route("skaw.approve-camat") }}';
            }

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.success) {
                        $('#approvalModal').modal('hide');
                        showNotification('success', 'Approval berhasil diberikan!');
                        location.reload();
                    } else {
                        showNotification('error', response.message || 'Terjadi kesalahan');
                    }
                },
                error: function(xhr) {
                    let message = 'Terjadi kesalahan';
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    showNotification('error', message);
                }
            });
        });

        function showNotification(type, message) {
            // Implement your notification system here
            // Could use SweetAlert, Toastr, or custom notification
            if(type === 'success') {
                alert('✓ ' + message);
            } else {
                alert('✗ ' + message);
            }
        }

        // Auto refresh summary counts every 30 seconds
        setInterval(function() {
            loadSummaryCounts();
        }, 30000);
    </script>
@endpush
