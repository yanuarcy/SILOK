{{-- resources/views/SuratMasuk/psu/index.blade.php --}}
@extends('Template.template')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/owl.carousel/dist/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/owl.carousel/dist/assets/owl.theme.default.min.css') }}">

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
            background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
        }

        .summary-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .summary-card.total {
            border-left-color: #17a2b8;
            background: linear-gradient(135deg, #e8f8fd 0%, #f8f9fa 100%);
        }
        .summary-card.monthly {
            border-left-color: #007bff;
            background: linear-gradient(135deg, #e3f2fd 0%, #f8f9fa 100%);
        }
        .summary-card.weekly {
            border-left-color: #28a745;
            background: linear-gradient(135deg, #e8f5e8 0%, #f8f9fa 100%);
        }
        .summary-card.daily {
            border-left-color: #ffc107;
            background: linear-gradient(135deg, #fff9e6 0%, #f8f9fa 100%);
        }

        .summary-card .card-body {
            padding: 1.5rem;
        }

        .summary-card i {
            opacity: 0.8;
        }

        .summary-card h4 {
            font-weight: 700;
            font-size: 2rem;
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

        /* Special styling for surat masuk */
        .surat-masuk-badge {
            background: linear-gradient(45deg, #17a2b8, #20c997);
            color: white;
            border: none;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
        }

        .sender-info {
            border-left: 3px solid #17a2b8;
            padding-left: 12px;
            background: linear-gradient(90deg, rgba(23,162,184,0.1) 0%, transparent 100%);
            border-radius: 0 8px 8px 0;
        }

        /* Card custom styling */
        .card {
            border: none;
            box-shadow: 0 0 35px 0 rgba(154, 161, 171, 0.15);
            border-radius: 10px;
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            border: none;
            padding: 1.5rem;
        }

        .card-header h4 {
            margin-bottom: 0;
            color: white;
            font-weight: 600;
        }

        .section-header h1 {
            color: #34395e;
            font-weight: 700;
        }

        /* Button styling */
        .btn-outline-info {
            border-color: #17a2b8;
            color: #17a2b8;
        }

        .btn-outline-info:hover {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: white;
        }

        .btn-info {
            background: linear-gradient(45deg, #17a2b8, #20c997);
            border: none;
        }

        .btn-info:hover {
            background: linear-gradient(45deg, #138496, #1e7e34);
            transform: translateY(-1px);
        }

        /* Dropdown menu styling */
        .dropdown-menu {
            border: none;
            box-shadow: 0 0 35px 0 rgba(154, 161, 171, 0.15);
            border-radius: 8px;
        }

        .dropdown-item {
            padding: 10px 20px;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #17a2b8;
        }

        .dropdown-item i {
            width: 20px;
            margin-right: 10px;
        }

        /* Badge notification */
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* Table row hover effect */
        .table-striped tbody tr:hover {
            background-color: rgba(23,162,184,0.1) !important;
            cursor: pointer;
        }

        /* Actions button styling */
        .btn-sm {
            padding: 5px 12px;
            font-size: 12px;
            border-radius: 5px;
            font-weight: 500;
        }

        .btn-success {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
        }

        .btn-success:hover {
            background: linear-gradient(45deg, #218838, #1e7e34);
            transform: translateY(-1px);
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .dataTables_wrapper {
                padding: 0 10px;
            }

            .summary-card h4 {
                font-size: 1.5rem;
            }

            .card-header {
                padding: 1rem;
            }

            .section-header h1 {
                font-size: 1.5rem;
            }
        }

        /* Loading animation */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #17a2b8;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
@endpush

@section('Dashboard')
    @include('admin.dashboard.header')
    @include('admin.dashboard.sidebar')

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Surat Masuk PSU</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('Dashboard.General') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">PSU</div>
                    <div class="breadcrumb-item">Surat Masuk</div>
                </div>
            </div>

            {{-- Summary Cards --}}
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card summary-card total border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <i class="fas fa-inbox fa-2x text-info"></i>
                            </div>
                            <h6 class="card-title text-muted mb-2">Total Surat Masuk</h6>
                            <h4 class="text-info mb-0 font-weight-bold" id="totalSuratMasukCount">
                                <span class="loading-spinner"></span>
                            </h4>
                            <small class="text-muted">Keseluruhan</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card summary-card monthly border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <i class="fas fa-calendar-month fa-2x text-primary"></i>
                            </div>
                            <h6 class="card-title text-muted mb-2">Bulan Ini</h6>
                            <h4 class="text-primary mb-0 font-weight-bold" id="bulanIniCount">
                                <span class="loading-spinner"></span>
                            </h4>
                            <small class="text-muted">{{ now()->format('F Y') }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card summary-card weekly border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <i class="fas fa-calendar-week fa-2x text-success"></i>
                            </div>
                            <h6 class="card-title text-muted mb-2">Minggu Ini</h6>
                            <h4 class="text-success mb-0 font-weight-bold" id="mingguIniCount">
                                <span class="loading-spinner"></span>
                            </h4>
                            <small class="text-muted">7 hari terakhir</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card summary-card daily border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <i class="fas fa-calendar-day fa-2x text-warning"></i>
                            </div>
                            <h6 class="card-title text-muted mb-2">Hari Ini</h6>
                            <h4 class="text-warning mb-0 font-weight-bold" id="hariIniCount">
                                <span class="loading-spinner"></span>
                            </h4>
                            <small class="text-muted">{{ now()->format('d M Y') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between w-100 align-items-center">
                                <div>
                                    <h4><i class="fas fa-inbox mr-2"></i>Surat Masuk PSU Internal</h4>
                                    <small class="text-white-50" id="suratMasukStatusSummary">
                                        <i class="fas fa-spinner fa-spin mr-1"></i>Loading...
                                    </small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-outline-light btn-sm mr-2" onclick="refreshSuratMasukData()" title="Refresh Data">
                                        <i class="fas fa-sync-alt"></i> Refresh
                                    </button>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-light btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-filter text-primary"></i> <span id="filterText">Semua</span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#" onclick="filterByPeriod('all')">
                                                <i class="fas fa-list text-secondary"></i> Semua
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#" onclick="filterByPeriod('today')">
                                                <i class="fas fa-calendar-day text-warning"></i> Hari Ini
                                            </a>
                                            <a class="dropdown-item" href="#" onclick="filterByPeriod('week')">
                                                <i class="fas fa-calendar-week text-success"></i> Minggu Ini
                                            </a>
                                            <a class="dropdown-item" href="#" onclick="filterByPeriod('month')">
                                                <i class="fas fa-calendar-month text-primary"></i> Bulan Ini
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="dataSuratMasukPsu-table">
                                    <thead>
                                        <tr>
                                            <th width="5%" class="text-center">No</th>
                                            <th width="25%">Nomor & Judul</th>
                                            <th width="20%">Pengirim</th>
                                            <th width="15%" class="text-center">Tanggal Diterima</th>
                                            <th width="10%" class="text-center">Status</th>
                                            <th width="25%" class="text-center">Actions</th>
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

    {{-- Modal Detail Surat (Optional) --}}
    <div class="modal fade" id="detailSuratModal" tabindex="-1" role="dialog" aria-labelledby="detailSuratModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="detailSuratModalLabel">
                        <i class="fas fa-eye mr-2"></i>Detail Surat Masuk PSU
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="detailSuratContent">
                    <div class="text-center py-4">
                        <i class="fas fa-spinner fa-spin fa-2x text-info"></i>
                        <p class="mt-2">Loading detail surat...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Tutup
                    </button>
                    <button type="button" class="btn btn-info" id="previewSuratBtn">
                        <i class="fas fa-eye mr-2"></i>Preview PDF
                    </button>
                    <button type="button" class="btn btn-success" id="downloadSuratBtn">
                        <i class="fas fa-download mr-2"></i>Download
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- General JS Scripts -->
    <script src="{{ asset('library/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('library/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>

    <script>
        let table;
        let currentFilter = 'all';

        $(document).ready(function() {
            // Function untuk load notification counts
            function loadPsuNotificationCounts() {
                // console.log('testing load PSU notifications');
                // Hanya untuk role yang memiliki akses ke surat masuk
                @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'user', 'Ketua RT', 'Ketua RW']))

                // Load surat masuk count
                $.ajax({
                    url: "{{ route('surat-masuk.psu.summary') }}",
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const totalSuratMasuk = response.data.total_surat_masuk || 0;
                            const newSuratToday = response.data.hari_ini || 0;

                            // Update badge surat masuk (tampilkan jika ada surat hari ini)
                            if (newSuratToday > 0) {
                                $('#surat-masuk-psu-count').text(newSuratToday).show();
                            } else {
                                $('#surat-masuk-psu-count').hide();
                            }

                            // Update total notification di menu utama
                            if (totalSuratMasuk > 0) {
                                $('#total-psu-notification').text(totalSuratMasuk).show();
                            } else {
                                $('#total-psu-notification').hide();
                            }
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading surat masuk notification:', xhr);
                        // Hide badges on error
                        $('#surat-masuk-psu-count').hide();
                        $('#total-psu-notification').hide();
                    }
                });

                @endif
            }

            // Load initial counts
            loadPsuNotificationCounts();

            // Auto refresh every 60 seconds (1 minute)
            setInterval(loadPsuNotificationCounts, 60000);

            // Refresh when page becomes visible (user switches back to tab)
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    loadPsuNotificationCounts();
                }
            });

            // Refresh when user clicks on surat masuk menu
            $('a[href="{{ route('surat-masuk.psu.index') }}"]').on('click', function() {
                // Reset badge surat masuk karena user sudah buka halaman
                setTimeout(function() {
                    $('#surat-masuk-psu-count').hide();
                }, 1000);
            });
        });

        $(document).ready(function() {
            // Initialize DataTable
            table = $('#dataSuratMasukPsu-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('surat-masuk.psu.data') }}",
                    data: function(d) {
                        d.filter_period = currentFilter;
                    },
                    error: function(xhr, error, thrown) {
                        console.log('DataTables error:', {xhr, error, thrown});
                        console.log('Response:', xhr.responseJSON);

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal memuat data. Silakan refresh halaman.',
                            confirmButtonColor: '#17a2b8'
                        });
                    }
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'nomor_judul',
                        name: 'nomor_surat',
                        render: function(data, type, row) {
                            return data;
                        }
                    },
                    {
                        data: 'pengirim',
                        name: 'pengirim',
                        orderable: false,
                        render: function(data, type, row) {
                            return '<div class="sender-info">' + data + '</div>';
                        }
                    },
                    {
                        data: 'tanggal',
                        name: 'created_at',
                        className: 'text-center',
                        render: function(data, type, row) {
                            return '<small class="text-muted">' + data + '</small>';
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        className: 'text-center',
                        render: function(data, type, row) {
                            return '<span class="badge surat-masuk-badge">Diterima</span>';
                        }
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                order: [[3, 'desc']], // Sort by tanggal desc
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                language: {
                    searchPlaceholder: "Cari surat masuk...",
                    lengthMenu: "Tampilkan _MENU_ surat",
                    emptyTable: `
                        <div class="text-center my-5 py-4">
                            <div class="mb-3">
                                <i class="fas fa-inbox-empty fa-4x text-muted" style="opacity: 0.5;"></i>
                            </div>
                            <h6 class="fw-semibold text-muted">Belum ada Surat Masuk</h6>
                            <p class="text-muted small mb-0">Surat masuk PSU akan muncul di sini ketika Ketua RT/RW mengirim PSU internal kepada Anda</p>
                            <small class="text-muted">atau ketika Anda menjadi target dari PSU Internal</small>
                        </div>
                    `,
                    zeroRecords: `
                        <div class="text-center my-5 py-4">
                            <div class="mb-3">
                                <i class="fas fa-search fa-4x text-muted" style="opacity: 0.5;"></i>
                            </div>
                            <h6 class="fw-semibold text-muted">Surat tidak ditemukan</h6>
                            <p class="text-muted small mb-0">Coba ubah kata kunci pencarian atau filter periode</p>
                        </div>
                    `,
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ surat",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 surat",
                    infoFiltered: "(difilter dari _MAX_ total surat)",
                    paginate: {
                        first: '&laquo;&laquo;',
                        last: '&raquo;&raquo;',
                        next: '&raquo;',
                        previous: '&laquo;'
                    },
                    processing: `
                        <div class="d-flex justify-content-center align-items-center py-4">
                            <div class="loading-spinner mr-2"></div>
                            <span class="text-muted">Memuat data...</span>
                        </div>
                    `
                },
                drawCallback: function(settings) {
                    // Add tooltips to buttons
                    $('[title]').tooltip();
                }
            });

            // Load initial summary data
            loadSummaryData();

            // Auto refresh setiap 30 detik
            setInterval(function() {
                table.ajax.reload(null, false);
                loadSummaryData();
            }, 30000);

            // Handle mark as read button
            $('#dataSuratMasukPsu-table').on('click', '.btn-mark-read', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                markAsRead(id);
            });
        });

        // Function untuk load summary data
        function loadSummaryData() {
            $.ajax({
                url: "{{ route('surat-masuk.psu.summary') }}",
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        updateSummaryDisplay(response.data);
                    }
                },
                error: function(xhr) {
                    console.error('Error loading summary data:', xhr);
                    // Set default values on error
                    updateSummaryDisplay({
                        total_surat_masuk: 0,
                        bulan_ini: 0,
                        minggu_ini: 0,
                        hari_ini: 0
                    });
                }
            });
        }

        // Function untuk update tampilan summary
        function updateSummaryDisplay(data) {
            $('#totalSuratMasukCount').html(data.total_surat_masuk || 0);
            $('#bulanIniCount').html(data.bulan_ini || 0);
            $('#mingguIniCount').html(data.minggu_ini || 0);
            $('#hariIniCount').html(data.hari_ini || 0);

            // Update status summary
            let statusText = `Total: ${data.total_surat_masuk || 0} | Bulan: ${data.bulan_ini || 0} | Minggu: ${data.minggu_ini || 0} | Hari: ${data.hari_ini || 0}`;
            $('#suratMasukStatusSummary').html('<i class="fas fa-info-circle mr-1"></i>' + statusText);
        }

        // Function untuk refresh data manual
        function refreshSuratMasukData() {
            // Show loading state
            $('#suratMasukStatusSummary').html('<i class="fas fa-spinner fa-spin mr-1"></i>Memperbarui data...');

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
                title: 'Data diperbarui',
                background: '#17a2b8',
                color: 'white'
            });
        }

        // Function untuk filter berdasarkan periode
        function filterByPeriod(period) {
            currentFilter = period;
            table.ajax.reload();

            // Update dropdown text
            let filterText = {
                'all': 'Semua',
                'today': 'Hari Ini',
                'week': 'Minggu Ini',
                'month': 'Bulan Ini'
            };

            $('#filterText').text(filterText[period]);

            // Show filter notification
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                icon: 'success',
                title: `Filter: ${filterText[period]}`,
                background: '#28a745',
                color: 'white'
            });
        }

        // Function untuk mark as read
        function markAsRead(id) {
            $.ajax({
                url: `/surat-masuk/psu/${id}/mark-read`,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        table.ajax.reload(null, false);
                        loadSummaryData();

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            icon: 'success',
                            title: 'Surat ditandai sudah dibaca',
                            background: '#28a745',
                            color: 'white'
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal menandai surat sebagai sudah dibaca',
                        confirmButtonColor: '#17a2b8'
                    });
                }
            });
        }

        // Function untuk show detail modal
        function showDetailSurat(id) {
            $('#detailSuratModal').modal('show');
            $('#detailSuratContent').html(`
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin fa-2x text-info"></i>
                    <p class="mt-2">Loading detail surat...</p>
                </div>
            `);

            $.ajax({
                url: `/surat-masuk/psu/${id}/detail`,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const data = response.data;
                        $('#detailSuratContent').html(`
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card border-info">
                                        <div class="card-header bg-info text-white">
                                            <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Informasi Surat</h6>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-sm table-borderless">
                                                <tr>
                                                    <td width="40%" class="font-weight-bold">Nomor Surat:</td>
                                                    <td>${data.nomor_surat}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Judul:</td>
                                                    <td>${data.judul}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Hal:</td>
                                                    <td>${data.hal}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Sifat:</td>
                                                    <td><span class="badge badge-secondary">${data.sifat}</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Tanggal Diterima:</td>
                                                    <td>${data.tanggal_diterima}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Status Baca:</td>
                                                    <td>${data.read_at ? '<span class="badge badge-success">Sudah Dibaca</span>' : '<span class="badge badge-warning">Belum Dibaca</span>'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Download Count:</td>
                                                    <td><span class="badge badge-info">${data.download_count} kali</span></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-primary">
                                        <div class="card-header bg-primary text-white">
                                            <h6 class="mb-0"><i class="fas fa-user mr-2"></i>Informasi Pengirim</h6>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-sm table-borderless">
                                                <tr>
                                                    <td width="40%" class="font-weight-bold">Nama:</td>
                                                    <td>${data.pengirim.nama}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Jabatan:</td>
                                                    <td><span class="badge badge-primary">${data.pengirim.role}</span></td>
                                                </tr>
                                                ${data.pengirim.rt ? `
                                                <tr>
                                                    <td class="font-weight-bold">RT/RW:</td>
                                                    <td>RT ${String(data.pengirim.rt).padStart(2, '0')} / RW ${String(data.pengirim.rw).padStart(2, '0')}</td>
                                                </tr>` : ''}
                                                <tr>
                                                    <td class="font-weight-bold">Target:</td>
                                                    <td>
                                                        ${data.target_info.type === 'semua_rt' ?
                                                            `<span class="badge badge-info">Semua Warga RT ${String(data.target_info.rt).padStart(2, '0')}</span>` :
                                                            data.target_info.type === 'semua_rw' ?
                                                            `<span class="badge badge-info">Semua Warga RW ${String(data.target_info.rw).padStart(2, '0')}</span>` :
                                                            '<span class="badge badge-info">Individual</span>'
                                                        }
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="card border-success">
                                        <div class="card-header bg-success text-white">
                                            <h6 class="mb-0"><i class="fas fa-file-alt mr-2"></i>Isi Surat</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="bg-light p-3 rounded">
                                                ${data.isi_surat.replace(/\n/g, '<br>')}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);

                        // Update modal buttons
                        $('#previewSuratBtn').off('click').on('click', function() {
                            window.open(`/surat-masuk/psu/${id}/preview`, '_blank');
                        });

                        $('#downloadSuratBtn').off('click').on('click', function() {
                            window.location.href = `/surat-masuk/psu/${id}/download`;
                        });
                    }
                },
                error: function(xhr) {
                    $('#detailSuratContent').html(`
                        <div class="text-center py-4">
                            <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                            <p class="mt-2 text-danger">Gagal memuat detail surat</p>
                        </div>
                    `);
                }
            });
        }

        // Event listener untuk double click pada row table
        $('#dataSuratMasukPsu-table tbody').on('dblclick', 'tr', function () {
            const data = table.row(this).data();
            if (data && data.DT_RowIndex) {
                // Get the actual ID from the row data
                const rowData = table.row(this).data();
                console.log('Row data:', rowData);
                // You might need to adjust this based on your actual data structure
                showDetailSurat(rowData.id);
            }
        });

        // Initialize tooltips
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

        // Handle page visibility change untuk auto refresh
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                // Page became visible, refresh data
                table.ajax.reload(null, false);
                loadSummaryData();
            }
        });
    </script>

    <!-- Include SweetAlert2 for notifications -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
