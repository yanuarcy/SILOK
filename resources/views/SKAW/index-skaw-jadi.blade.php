{{-- resources/views/Skaw/index-skaw-jadi.blade.php --}}
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

        .summary-card.completed { border-left-color: #28a745; }
        .summary-card.this-month { border-left-color: #007bff; }
        .summary-card.ready-pickup { border-left-color: #17a2b8; }
        .summary-card.total { border-left-color: #6f42c1; }

        .document-final {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .download-section {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .download-btn {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            margin: 4px;
            transition: background 0.2s;
        }

        .download-btn:hover {
            background: #218838;
            color: white;
            text-decoration: none;
        }

        .download-btn i {
            margin-right: 8px;
        }

        .status-completed {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .completion-timeline {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
        }

        .timeline-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .timeline-item i {
            color: #28a745;
            margin-right: 8px;
            width: 16px;
        }

        .pickup-info {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            border-radius: 6px;
            padding: 10px;
            margin-top: 10px;
        }

        .pickup-info.ready {
            background: #d4edda;
            border-color: #c3e6cb;
        }

        .statistics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-item {
            text-align: center;
            padding: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }

        .stat-label {
            font-size: 12px;
            color: #6c757d;
            margin-top: 4px;
        }
    </style>
@endpush

@section('Dashboard')
    @include('admin.dashboard.header')
    @include('admin.dashboard.sidebar')

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>SKAW Jadi - Dokumen Selesai</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('Dashboard.General') }}">Dashboard</a>
                    </div>
                    <div class="breadcrumb-item">SKAW</div>
                    <div class="breadcrumb-item active">SKAW Jadi</div>
                </div>
            </div>

            {{-- Summary Cards --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card summary-card completed border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                            <h6 class="card-title text-muted">SKAW Selesai</h6>
                            <h4 class="text-success mb-0" id="completedCount">-</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card summary-card this-month border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-check fa-2x text-primary mb-2"></i>
                            <h6 class="card-title text-muted">Bulan Ini</h6>
                            <h4 class="text-primary mb-0" id="thisMonthCount">-</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card summary-card ready-pickup border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-hand-holding fa-2x text-info mb-2"></i>
                            <h6 class="card-title text-muted">Siap Diambil</h6>
                            <h4 class="text-info mb-0" id="readyPickupCount">-</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card summary-card total border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-archive fa-2x text-purple mb-2"></i>
                            <h6 class="card-title text-muted">Total Arsip</h6>
                            <h4 class="text-purple mb-0" id="totalArchiveCount">-</h4>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statistics Dashboard --}}
            @if(in_array($userRole, ['Front Office', 'Back Office', 'Lurah', 'Camat']))
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-chart-bar mr-2"></i>Statistik SKAW Selesai</h6>
                            </div>
                            <div class="card-body">
                                <div class="statistics-grid">
                                    <div class="stat-item">
                                        <div class="stat-number" id="todayCompleted">0</div>
                                        <div class="stat-label">Hari Ini</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-number" id="weekCompleted">0</div>
                                        <div class="stat-label">Minggu Ini</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-number" id="monthCompleted">0</div>
                                        <div class="stat-label">Bulan Ini</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-number" id="yearCompleted">0</div>
                                        <div class="stat-label">Tahun Ini</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Role-specific Action Panel --}}
            @if($userRole === 'Back Office')
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="fas fa-upload mr-2"></i>Panel Back Office - Upload SKAW Final</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p><strong>Tugas Back Office:</strong></p>
                                        <ul class="small">
                                            <li>Upload dokumen SKAW final yang sudah ditandatangani Lurah dan Camat</li>
                                            <li>Pastikan dokumen sudah dalam format PDF dan dapat dibaca dengan jelas</li>
                                            <li>Input tanggal penyelesaian dan catatan jika diperlukan</li>
                                            <li>Set status dokumen sebagai "Siap Diambil" atau "Telah Diambil"</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="alert alert-success mb-0">
                                            <i class="fas fa-info-circle"></i>
                                            <strong>Catatan:</strong><br>
                                            <small>SKAW yang sudah final akan tersedia untuk diunduh pemohon dan menjadi arsip kelurahan.</small>
                                        </div>
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
                            <h4>Daftar SKAW Selesai</h4>
                            <div class="d-flex align-items-center">
                                @if(in_array($userRole, ['Front Office', 'Back Office']))
                                    <button type="button" class="btn btn-success btn-sm mr-2" data-toggle="modal" data-target="#uploadFinalModal">
                                        <i class="fas fa-upload"></i> Upload SKAW Final
                                    </button>
                                @endif
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
                                <table class="table table-striped" id="skawJadiTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 3%;">#</th>
                                            <th style="width: 15%;">Nomor Permohonan</th>
                                            <th style="width: 20%;">Nama Pemohon</th>
                                            <th style="width: 20%;">Nama Pewaris</th>
                                            <th style="width: 12%;">Tanggal Selesai</th>
                                            <th style="width: 10%;">Status Pengambilan</th>
                                            <th style="width: 10%;">Dokumen</th>
                                            <th style="width: 10%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($skawJadi as $index => $item)
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
                                                    @if($item->tanggal_selesai)
                                                        <strong class="text-success">{{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('H:i') }}</small>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($item->status_pengambilan === 'siap_diambil')
                                                        <span class="badge badge-info">
                                                            <i class="fas fa-hand-holding mr-1"></i>Siap Diambil
                                                        </span>
                                                    @elseif($item->status_pengambilan === 'sudah_diambil')
                                                        <span class="badge badge-success">
                                                            <i class="fas fa-check-circle mr-1"></i>Sudah Diambil
                                                        </span>
                                                    @else
                                                        <span class="badge badge-secondary">
                                                            <i class="fas fa-clock mr-1"></i>Proses
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="download-section p-2">
                                                        @if($item->file_skaw_final)
                                                            <a href="{{ asset('storage/' . $item->file_skaw_final) }}"
                                                               class="download-btn btn-sm" target="_blank">
                                                                <i class="fas fa-download"></i>SKAW
                                                            </a>
                                                        @endif

                                                        @if($item->file_tanda_terima)
                                                            <a href="{{ asset('storage/' . $item->file_tanda_terima) }}"
                                                               class="download-btn btn-sm" target="_blank">
                                                                <i class="fas fa-receipt"></i>Tanda Terima
                                                            </a>
                                                        @endif
                                                    </div>
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

                                                            @if(in_array($userRole, ['Back Office']) && $item->status_pengambilan === 'siap_diambil')
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item text-success" href="#"
                                                                   onclick="markAsPickedUp('{{ $item->id }}')">
                                                                    <i class="fas fa-check mr-2"></i>Tandai Sudah Diambil
                                                                </a>
                                                            @endif

                                                            @if(in_array($userRole, ['Back Office']))
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item" href="{{ route('skaw.edit-final', $item->id) }}">
                                                                    <i class="fas fa-edit mr-2"></i>Update Dokumen
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
                                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                                        <h6>Belum ada SKAW yang selesai</h6>
                                                        <p class="small">Data SKAW yang sudah selesai akan muncul di sini</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if($skawJadi->hasPages())
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $skawJadi->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- Upload Final SKAW Modal --}}
    @if(in_array($userRole, ['Front Office', 'Back Office']))
        <div class="modal fade" id="uploadFinalModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-upload mr-2"></i>Upload SKAW Final
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <form id="uploadFinalForm" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Pilih Permohonan SKAW</label>
                                <select class="form-control" name="permohonan_id" required>
                                    <option value="">-- Pilih Permohonan --</option>
                                    @foreach($readyForFinal as $ready)
                                        <option value="{{ $ready->id }}">
                                            {{ $ready->nomor_permohonan }} - {{ $ready->user->name ?? 'N/A' }}
                                            ({{ $ready->pewaris->nama_lengkap ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Upload SKAW Final (PDF)</label>
                                <input type="file" class="form-control" name="file_skaw_final"
                                       accept=".pdf" required>
                                <small class="text-muted">Format: PDF, Max: 10MB</small>
                            </div>

                            <div class="form-group">
                                <label>Tanggal Selesai</label>
                                <input type="datetime-local" class="form-control" name="tanggal_selesai"
                                       value="{{ now()->format('Y-m-d\TH:i') }}" required>
                            </div>

                            <div class="form-group">
                                <label>Status Pengambilan</label>
                                <select class="form-control" name="status_pengambilan" required>
                                    <option value="siap_diambil">Siap Diambil</option>
                                    <option value="sudah_diambil">Sudah Diambil</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Catatan (Opsional)</label>
                                <textarea class="form-control" name="catatan_final" rows="3"
                                         placeholder="Catatan mengenai penyelesaian SKAW..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-upload mr-2"></i>Upload Final
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#skawJadiTable').DataTable({
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
                $('#skawJadiTable').DataTable().search(this.value).draw();
            });

            // Load summary counts
            loadSummaryCounts();

            // Load statistics if user has access
            @if(in_array($userRole, ['Front Office', 'Back Office', 'Lurah', 'Camat']))
                loadStatistics();
            @endif
        });

        function loadSummaryCounts() {
            $.ajax({
                url: '{{ route("skaw.summary-counts") }}',
                method: 'GET',
                success: function(response) {
                    $('#completedCount').text(response.completed || 0);
                    $('#thisMonthCount').text(response.thisMonth || 0);
                    $('#readyPickupCount').text(response.readyPickup || 0);
                    $('#totalArchiveCount').text(response.totalArchive || 0);
                },
                error: function() {
                    console.log('Error loading summary counts');
                }
            });
        }

        function loadStatistics() {
            $.ajax({
                url: '{{ route("skaw.statistics") }}',
                method: 'GET',
                success: function(response) {
                    $('#todayCompleted').text(response.todayCompleted || 0);
                    $('#weekCompleted').text(response.weekCompleted || 0);
                    $('#monthCompleted').text(response.monthCompleted || 0);
                    $('#yearCompleted').text(response.yearCompleted || 0);
                },
                error: function() {
                    console.log('Error loading statistics');
                }
            });
        }

        // Handle upload final form
        $('#uploadFinalForm').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: '{{ route("skaw.upload-final") }}',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.success) {
                        $('#uploadFinalModal').modal('hide');
                        showNotification('success', 'SKAW final berhasil diupload!');
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

        // Mark as picked up
        function markAsPickedUp(id) {
            if(confirm('Apakah Anda yakin SKAW ini sudah diambil pemohon?')) {
                $.ajax({
                    url: '{{ route("skaw.mark-picked-up", ":id") }}'.replace(':id', id),
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if(response.success) {
                            showNotification('success', 'Status berhasil diupdate!');
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
            }
        }

        function showNotification(type, message) {
            // Implement your notification system here
            // Could use SweetAlert, Toastr, or custom notification
            if(type === 'success') {
                alert('✓ ' + message);
            } else {
                alert('✗ ' + message);
            }
        }
    </script>
@endpush
