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
        /* Styling untuk table header */
        .table-striped thead th {
            background-color: #f4f6f9;
            color: #34395e;
            font-weight: 600;
            padding: 15px;
            border-bottom: 2px solid #e9ecef;
        }

        /* Styling untuk table body */
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

        /* Summary cards styles */
        .summary-card {
            transition: all 0.2s ease;
            border-left: 4px solid transparent;
        }

        .summary-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .summary-card.total { border-left-color: #007bff; }
        .summary-card.approved { border-left-color: #28a745; }
        .summary-card.pending { border-left-color: #ffc107; }
        .summary-card.rejected { border-left-color: #dc3545; }
        .summary-card.kelurahan { border-left-color: #6f42c1; }

        .jenis-breakdown {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
        }

        .jenis-item {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 10px 15px;
            margin-bottom: 10px;
            backdrop-filter: blur(10px);
        }

        .dataTables_wrapper {
            padding: 0 15px;
        }

        .dataTables_wrapper .dataTables_length select {
            margin: 0 5px;
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid #dee2e6;
        }

        .dataTables_wrapper .dataTables_filter input {
            padding: 5px 10px;
            border-radius: 4px;
            border: 1px solid #e9ecef;
            margin-left: 10px;
        }

        /* Pagination Styling */
        .dataTables_wrapper .dataTables_paginate {
            padding: 20px 0 !important;
            margin-top: 15px !important;
            border-top: 1px solid #e9ecef;
        }

        .dataTables_wrapper .dataTables_info {
            padding: 20px 0 !important;
            margin-top: 15px !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            margin: 0 5px !important;
            padding: 5px 12px !important;
            border-radius: 4px !important;
            border: 1px solid #dee2e6 !important;
            background: white !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #0d6efd !important;
            color: white !important;
            border-color: #0d6efd !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f8f9fa !important;
            color: #0d6efd !important;
            border-color: #0d6efd !important;
        }

        .dataTables_wrapper .bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
            padding: 0 15px;
        }

        .dataTables_info {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .dataTables_empty {
            font-size: 16px !important;
            color: #6c757d !important;
            background: #f8f9fa !important;
            border-radius: 8px !important;
            box-shadow: inset 0 0 0 1px rgba(0,0,0,.05) !important;
        }

        .dataTables_empty i {
            font-size: 48px !important;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .dataTables_wrapper {
                padding: 0 10px;
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
                    <div class="breadcrumb-item active"><a href="{{ route('Dashboard.General') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Permohonan Saya</div>
                </div>
            </div>

            {{-- Summary Cards --}}
            <div class="row mb-4">
                <div class="col-md-2">
                    <div class="card summary-card total border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-file-alt fa-2x text-primary mb-2"></i>
                            <h6 class="card-title text-muted">Total</h6>
                            <h4 class="text-primary mb-0" id="totalPengajuanCount">-</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card summary-card pending border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                            <h6 class="card-title text-muted">Menunggu RT</h6>
                            <h4 class="text-warning mb-0" id="pendingRtCount">-</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card summary-card pending border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-clock fa-2x text-info mb-2"></i>
                            <h6 class="card-title text-muted">Menunggu RW</h6>
                            <h4 class="text-info mb-0" id="pendingRwCount">-</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card summary-card kelurahan border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-university fa-2x text-purple mb-2"></i>
                            <h6 class="card-title text-muted">Menunggu Kelurahan</h6>
                            <h4 class="text-purple mb-0" id="pendingKelurahanCount">-</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card summary-card approved border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                            <h6 class="card-title text-muted">Disetujui</h6>
                            <h4 class="text-success mb-0" id="approvedPengajuanCount">-</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card summary-card rejected border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
                            <h6 class="card-title text-muted">Ditolak</h6>
                            <h4 class="text-danger mb-0" id="rejectedPengajuanCount">-</h4>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Breakdown by Jenis --}}
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="jenis-breakdown">
                        <h6 class="mb-3">
                            <i class="fas fa-chart-pie"></i> Breakdown Jenis Permohonan
                        </h6>
                        <div class="row" id="jenisBreakdown">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between w-100">
                                <div>
                                    <h4>Riwayat Permohonan Saya</h4>
                                    <small class="text-muted" id="applicationStatusSummary">Loading...</small>
                                </div>
                                <div>
                                    <button class="btn btn-outline-primary btn-sm mr-1" onclick="refreshApplicationData()" title="Refresh Data">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                    <div class="d-inline-block position-relative">
                                        <button class="btn btn-primary" type="button" id="addPermohonanBtn" onclick="showApplicationMenu()">
                                            <i class="fas fa-plus"></i> Tambah Permohonan
                                            <i class="fas fa-chevron-down ml-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="dataApplications-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>File Info</th>
                                            <th>Nomor & Judul</th>
                                            <th>Jenis</th>
                                            <th>Tanggal</th>
                                            <th>Status</th>
                                            <th>Workflow</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <div class="footer-left">
            Copyright &copy; 2018 <div class="bullet"></div> Design By <a href="https://nauval.in/">Muhamad
                Nauval Azhar</a>
        </div>
        <div class="footer-right">
            2.3.0
        </div>
    </footer>
@endsection

@push('scripts')
    <!-- General JS Scripts -->
    <script src="{{ asset('library/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('library/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('library/popper.js/dist/popper.min.js') }}"></script>
    <script src="{{ asset('library/popper.js/dist/popper-utils.min.js') }}"></script>
    <script src="{{ asset('library/popper.js/dist/umd/popper.js') }}"></script>


    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let table;

        $(document).ready(function() {
            // Initialize DataTable
            table = $('#dataApplications-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('user-applications.getData') }}",
                    error: function(xhr, error, thrown) {
                        console.log('DataTables error:', {xhr, error, thrown});
                        console.log('Response:', xhr.responseJSON);
                    }
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    { data: 'file_info', name: 'file_info', orderable: false },
                    { data: 'nomor_judul', name: 'nomor_surat' },
                    { data: 'jenis', name: 'jenis_permohonan' },
                    { data: 'tanggal', name: 'created_at' },
                    { data: 'status', name: 'status' },
                    { data: 'workflow', name: 'workflow', orderable: false },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                order: [[4, 'desc']], // Sort by tanggal desc
                language: {
                    searchPlaceholder: "Cari permohonan...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    emptyTable: `
                        <div class="text-center my-5">
                            <div class="mb-3">
                                <i class="fas fa-file-alt fs-1 text-muted"></i>
                            </div>
                            <h6 class="fw-semibold text-muted">Belum ada Permohonan</h6>
                            <p class="text-muted small">Permohonan Anda akan muncul di sini</p>
                        </div>
                    `,
                    zeroRecords: `
                        <div class="text-center my-5">
                            <div class="mb-3">
                                <i class="fas fa-search fs-1 text-muted"></i>
                            </div>
                            <h6 class="fw-semibold text-muted">Permohonan tidak ditemukan</h6>
                            <p class="text-muted small">Coba ubah kata kunci pencarian</p>
                        </div>
                    `,
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ permohonan",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 permohonan",
                    infoFiltered: "(difilter dari _MAX_ total permohonan)",
                    paginate: {
                        first: 'First',
                        last: 'Last',
                        next: 'Next',
                        previous: 'Previous'
                    },
                }
            });

            // Load initial summary data
            loadSummaryData();

            // Auto refresh setiap 30 detik
            setInterval(function() {
                table.ajax.reload(null, false);
                loadSummaryData();
            }, 30000);

            console.log('✅ User Applications page initialized successfully');
        });

        // Function untuk load summary data
        function loadSummaryData() {
            $.ajax({
                url: "{{ route('user-applications.getSummary') }}",
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        updateSummaryDisplay(response.data);
                    }
                },
                error: function(xhr) {
                    console.error('Error loading summary data:', xhr);
                }
            });
        }

        // Function untuk update tampilan summary
        function updateSummaryDisplay(data) {
            $('#totalPengajuanCount').text(data.total_pengajuan);
            $('#pendingRtCount').text(data.pending_rt);
            $('#pendingRwCount').text(data.pending_rw);
            $('#pendingKelurahanCount').text(data.pending_kelurahan);
            $('#approvedPengajuanCount').text(data.approved_pengajuan);
            $('#rejectedPengajuanCount').text(data.rejected_pengajuan);

            // Update status summary
            let statusText = `Total: ${data.total_pengajuan} | RT: ${data.pending_rt} | RW: ${data.pending_rw} | Kelurahan: ${data.pending_kelurahan} | Disetujui: ${data.approved_pengajuan} | Ditolak: ${data.rejected_pengajuan}`;
            $('#applicationStatusSummary').text(statusText);

            // Update breakdown by jenis
            updateJenisBreakdown(data.by_jenis);
        }

        // Function untuk update breakdown jenis
        function updateJenisBreakdown(byJenis) {
            const jenisNames = {
                'PUNTADEWA': 'PUNTADEWA',
                'PSU': 'PSU',
                'SKAW': 'SKAW',
                'SURAT_PENGANTAR': 'Surat Pengantar',
                'VERIFIKASI_DOMISILI': 'Verifikasi Domisili'
            };

            const jenisIcons = {
                'PUNTADEWA': 'fas fa-home',
                'PSU': 'fas fa-briefcase',
                'SKAW': 'fas fa-users',
                'SURAT_PENGANTAR': 'fas fa-envelope',
                'VERIFIKASI_DOMISILI': 'fas fa-map-marker-alt'
            };

            let html = '';

            if (Object.keys(byJenis).length === 0) {
                html = '<div class="col-12 text-center"><p class="mb-0">Belum ada permohonan</p></div>';
            } else {
                for (const [jenis, count] of Object.entries(byJenis)) {
                    const jenisName = jenisNames[jenis] || jenis;
                    const jenisIcon = jenisIcons[jenis] || 'fas fa-file';

                    html += `
                        <div class="col-md-2 col-sm-4 col-6">
                            <div class="jenis-item text-center">
                                <i class="${jenisIcon} fa-lg mb-2"></i>
                                <div class="font-weight-bold">${count}</div>
                                <small>${jenisName}</small>
                            </div>
                        </div>
                    `;
                }
            }

            $('#jenisBreakdown').html(html);
        }

        // Function untuk refresh data manual
        function refreshApplicationData() {
            table.ajax.reload();
            loadSummaryData();

            // Show refresh notification
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                icon: 'info',
                title: 'Data diperbarui'
            });
        }

        function showApplicationMenu() {
            Swal.fire({
                title: '<i class="fas fa-plus-circle text-primary"></i> Tambah Permohonan',
                html: `
                    <div class="row">
                        <div class="col-12">
                            <div class="list-group">
                                <a href="{{ route('puntadewa.create') }}" class="list-group-item list-group-item-action d-flex align-items-center p-3">
                                    <div class="mr-3">
                                        <i class="fas fa-home fa-2x text-primary"></i>
                                    </div>
                                    <div class="text-left">
                                        <h6 class="mb-1">PUNTADEWA</h6>
                                        <p class="mb-0 text-muted small">Pernyataan Tempat Tinggal Non Permanen</p>
                                    </div>
                                </a>

                                <button type="button" class="list-group-item list-group-item-action d-flex align-items-center p-3" onclick="showComingSoon('PSU')">
                                    <div class="mr-3">
                                        <i class="fas fa-briefcase fa-2x text-success"></i>
                                    </div>
                                    <div class="text-left">
                                        <h6 class="mb-1">PSU <span class="badge badge-warning">Coming Soon</span></h6>
                                        <p class="mb-0 text-muted small">Permohonan Surat Usaha</p>
                                    </div>
                                </button>

                                <button type="button" class="list-group-item list-group-item-action d-flex align-items-center p-3" onclick="showComingSoon('SKAW')">
                                    <div class="mr-3">
                                        <i class="fas fa-users fa-2x text-warning"></i>
                                    </div>
                                    <div class="text-left">
                                        <h6 class="mb-1">SKAW <span class="badge badge-warning">Coming Soon</span></h6>
                                        <p class="mb-0 text-muted small">Surat Keterangan Anak & Wali</p>
                                    </div>
                                </button>

                                <a href="{{ route('surat-pengantar.create') }}" class="list-group-item list-group-item-action d-flex align-items-center p-3">
                                    <div class="mr-3">
                                        <i class="fas fa-envelope fa-2x text-info"></i>
                                    </div>
                                    <div class="text-left">
                                        <h6 class="mb-1">Surat Pengantar</h6>
                                        <p class="mb-0 text-muted small">Surat Pengantar RT/RW</p>
                                    </div>
                                </a>

                                <button type="button" class="list-group-item list-group-item-action d-flex align-items-center p-3" onclick="showComingSoon('Verifikasi Domisili')">
                                    <div class="mr-3">
                                        <i class="fas fa-map-marker-alt fa-2x text-purple"></i>
                                    </div>
                                    <div class="text-left">
                                        <h6 class="mb-1">Verifikasi Domisili <span class="badge badge-warning">Coming Soon</span></h6>
                                        <p class="mb-0 text-muted small">Verifikasi Alamat Domisili</p>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                `,
                showCloseButton: true,
                showConfirmButton: false,
                width: '500px',
                customClass: {
                    htmlContainer: 'text-left'
                }
            });
        }

        // Function untuk coming soon notification
        function showComingSoon(jenisPermohonan) {
            Swal.close(); // Close the menu first

            setTimeout(() => {
                Swal.fire({
                    icon: 'info',
                    title: 'Coming Soon',
                    html: `
                        <div class="text-center">
                            <i class="fas fa-clock fa-3x text-warning mb-3"></i>
                            <p>Fitur <strong>${jenisPermohonan}</strong> sedang dalam pengembangan dan akan segera tersedia.</p>
                            <p class="text-muted small">Saat ini Anda dapat menggunakan fitur PUNTADEWA yang sudah tersedia.</p>
                        </div>
                    `,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#6777ef'
                });
            }, 300);
        }
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
