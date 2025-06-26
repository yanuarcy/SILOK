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
        /* Enhanced summary cards styles */
        .summary-card {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            border-left: 4px solid transparent;
            border-radius: 12px !important;
            overflow: hidden;
            position: relative;
        }

        .summary-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 12px 24px rgba(0,0,0,0.15) !important;
        }

        .summary-card.total {
            border-left-color: #007bff;
            background: linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
        }

        .summary-card.positive {
            border-left-color: #28a745;
            background: linear-gradient(135deg, #ffffff 0%, #f8fff9 100%);
        }

        .summary-card.negative {
            border-left-color: #dc3545;
            background: linear-gradient(135deg, #ffffff 0%, #fff8f8 100%);
        }

        .summary-card.testimonial {
            border-left-color: #17a2b8;
            background: linear-gradient(135deg, #ffffff 0%, #f8fdff 100%);
        }

        .summary-card h3 {
            font-size: 2.2rem;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .summary-card h6 {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        /* Icon circle styles */
        .icon-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            position: relative;
        }

        .bg-primary-light { background: rgba(0, 123, 255, 0.1); }
        .bg-success-light { background: rgba(40, 167, 69, 0.1); }
        .bg-danger-light { background: rgba(220, 53, 69, 0.1); }
        .bg-info-light { background: rgba(23, 162, 184, 0.1); }

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

        /* Styling untuk tombol action */
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
            padding: 5px 15px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
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

        /* Progress bars for statistics */
        .progress-container {
            position: relative;
            margin-bottom: 8px;
        }

        .progress-modern {
            height: 8px;
            border-radius: 20px;
            background: rgba(0,0,0,0.1);
            overflow: hidden;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
        }

        .progress-modern .progress-bar {
            border-radius: 20px;
            transition: width 0.8s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
            overflow: hidden;
        }

        .bg-gradient-success {
            background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
        }

        .bg-gradient-danger {
            background: linear-gradient(90deg, #dc3545 0%, #e74c3c 100%);
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
        }

        .progress-label {
            font-size: 0.9rem;
            margin-top: 4px;
            display: inline-block;
        }

        /* .progress-bar-success { background-color: #28a745; }
        .progress-bar-danger { background-color: #dc3545; }
        .progress-bar-warning { background-color: #ffc107; } */

        /* Badge improvements */
        .badge-info-light {
            background: rgba(23, 162, 184, 0.15);
            color: #17a2b8;
            border: 1px solid rgba(23, 162, 184, 0.2);
            font-size: 0.85rem;
            border-radius: 20px;
        }

        /* Bulk action buttons */
        .bulk-actions {
            display: none;
            background: #f8f9fa;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
        }

        .bulk-actions.show {
            display: block;
        }

        /* Animation for loading */
        .summary-card .fa-spinner {
            color: #6c757d;
            font-size: 1.5rem;
        }

        /* Hover effects for interactive elements */
        .summary-card:hover .icon-circle {
            transform: scale(1.1) rotate(5deg);
            transition: transform 0.3s ease;
        }

        .summary-card:hover .progress-bar {
            animation: pulse 1s ease-in-out;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.8; }
            100% { opacity: 1; }
        }

        /* Additional enhancements */
        .summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.8) 50%, transparent 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .summary-card:hover::before {
            opacity: 1;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .dataTables_wrapper {
                padding: 0 10px;
            }

            .summary-card {
                margin-bottom: 1rem;
            }

            .summary-card h3 {
                font-size: 1.8rem;
            }

            .icon-circle {
                width: 50px;
                height: 50px;
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
                <h1>Data Survey Kepuasan Masyarakat</h1>
            </div>

            {{-- Summary Cards --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card summary-card total border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <div class="icon-circle bg-primary-light">
                                    <i class="fas fa-poll fa-lg text-primary"></i>
                                </div>
                            </div>
                            <h6 class="card-title text-muted mb-2">Total Survey</h6>
                            <h3 class="text-primary mb-1 font-weight-bold" id="totalSurveysCount">-</h3>
                            <div class="text-center">
                                <small class="text-muted bg-light px-2 py-1 rounded" id="recentSurveysText">-</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card summary-card positive border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <div class="icon-circle bg-success-light">
                                    <i class="fas fa-smile fa-lg text-success"></i>
                                </div>
                            </div>
                            <h6 class="card-title text-muted mb-2">Respon Positif</h6>
                            <h3 class="text-success mb-2 font-weight-bold" id="positiveCount">-</h3>

                            <!-- Enhanced Progress Bar -->
                            <div class="progress-container mb-2">
                                <div class="progress progress-modern">
                                    <div class="progress-bar bg-gradient-success" id="positiveBar" style="width: 0%"></div>
                                </div>
                                <small class="progress-label text-success font-weight-bold" id="positivePercentage">0%</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card summary-card negative border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <div class="icon-circle bg-danger-light">
                                    <i class="fas fa-frown fa-lg text-danger"></i>
                                </div>
                            </div>
                            <h6 class="card-title text-muted mb-2">Respon Negatif</h6>
                            <h3 class="text-danger mb-2 font-weight-bold" id="negativeCount">-</h3>

                            <!-- Enhanced Progress Bar -->
                            <div class="progress-container mb-2">
                                <div class="progress progress-modern">
                                    <div class="progress-bar bg-gradient-danger" id="negativeBar" style="width: 0%"></div>
                                </div>
                                <small class="progress-label text-danger font-weight-bold" id="negativePercentage">0%</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card summary-card testimonial border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <div class="icon-circle bg-info-light">
                                    <i class="fas fa-quote-left fa-lg text-info"></i>
                                </div>
                            </div>
                            <h6 class="card-title text-muted mb-2">Testimonial Aktif</h6>
                            <h3 class="text-info mb-1 font-weight-bold" id="activeTestimonialCount">-</h3>
                            <div class="mt-2">
                                <div class="d-flex justify-content-center align-items-center">
                                    <span class="badge badge-info-light px-3 py-2">
                                        <i class="fas fa-chart-line mr-1"></i>
                                        Indeks: <span id="satisfactionIndex" class="font-weight-bold">-</span>%
                                    </span>
                                </div>
                            </div>
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
                                    <h4>Data SKM {{ getOrganizationName() }}</h4>
                                    <small class="text-muted" id="skmStatusSummary">Loading...</small>
                                </div>
                                <div>
                                    <button class="btn btn-outline-primary btn-sm mr-1" onclick="refreshSkmData()" title="Refresh Data">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                    <button class="btn btn-info btn-sm mr-1" onclick="showStatistics()" title="Lihat Statistik Detail">
                                        <i class="fas fa-chart-bar"></i> Statistik
                                    </button>
                                    <button class="btn btn-warning btn-sm mr-1" onclick="bulkManageTestimonial()" title="Kelola Testimonial Massal">
                                        <i class="fas fa-list-check"></i> Kelola Testimonial
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Bulk Actions -->
                            <div class="bulk-actions" id="bulkActions">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span id="selectedCount">0</span> item dipilih
                                    </div>
                                    <div>
                                        <button class="btn btn-success btn-sm mr-2" onclick="bulkActivateTestimonial()">
                                            <i class="fas fa-eye"></i> Tampilkan di Testimonial
                                        </button>
                                        <button class="btn btn-warning btn-sm mr-2" onclick="bulkDeactivateTestimonial()">
                                            <i class="fas fa-eye-slash"></i> Sembunyikan dari Testimonial
                                        </button>
                                        <button class="btn btn-secondary btn-sm" onclick="clearSelection()">
                                            <i class="fas fa-times"></i> Batal
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped" id="skm-table">
                                    <thead>
                                        <tr>
                                            <th width="40">
                                                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                            </th>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>User</th>
                                            <th>Alamat</th>
                                            <th>Tingkat Kepuasan</th>
                                            <th>Sentimen</th>
                                            <th>Kritik & Saran</th>
                                            <th>Status Testimonial</th>
                                            <th>Tanggal</th>
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

    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Survey Kepuasan Masyarakat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="detailModalBody">
                    <!-- Content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Modal -->
    <div class="modal fade" id="statisticsModal" tabindex="-1" role="dialog" aria-labelledby="statisticsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statisticsModalLabel">Statistik Survey Kepuasan Masyarakat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="statisticsModalBody">
                    <!-- Statistics content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let table;
        let selectedRows = [];

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Initialize DataTable
            table = $('#skm-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.data-skm.data') }}",
                    error: function(xhr, error, thrown) {
                        console.log('DataTables error:', {xhr, error, thrown});
                        console.log('Response:', xhr.responseJSON);
                    }
                },
                columns: [
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `<input type="checkbox" class="row-checkbox" data-id="${row.id}" onchange="toggleRowSelection(${row.id})">`;
                        }
                    },
                    {
                        data: null,
                        className: 'text-center',
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { data: 'nama', name: 'nama' },
                    { data: 'nama_user', name: 'user.name' },
                    {
                        data: 'alamat',
                        name: 'alamat',
                        render: function(data, type, row) {
                            return data.length > 30 ? data.substr(0, 30) + '...' : data;
                        }
                    },
                    {
                        data: 'tingkat_kepuasan_badge',
                        name: 'tingkat_kepuasan',
                        className: 'text-center'
                    },
                    {
                        data: 'sentiment_badge',
                        name: 'sentiment',
                        className: 'text-center',
                        orderable: false
                    },
                    {
                        data: 'kritik_saran_short',
                        name: 'kritik_saran',
                        orderable: false
                    },
                    {
                        data: 'status_badge',
                        name: 'status',
                        className: 'text-center'
                    },
                    {
                        data: 'tanggal_formatted',
                        name: 'created_at',
                        className: 'text-center'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                order: [[9, 'desc']], // Sort by created_at
                language: {
                    searchPlaceholder: "Cari data SKM...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    emptyTable: `
                        <div class="text-center my-5">
                            <div class="mb-3">
                                <i class="fas fa-clipboard-list fs-1 text-muted"></i>
                            </div>
                            <h6 class="fw-semibold text-muted">Belum ada Data Survey</h6>
                            <p class="text-muted small">Data survey kepuasan masyarakat akan muncul di sini</p>
                        </div>
                    `,
                    zeroRecords: `
                        <div class="text-center my-5">
                            <div class="mb-3">
                                <i class="fas fa-search fs-1 text-muted"></i>
                            </div>
                            <h6 class="fw-semibold text-muted">Data tidak ditemukan</h6>
                            <p class="text-muted small">Coba gunakan kata kunci lain</p>
                        </div>
                    `,
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    paginate: {
                        first: 'First',
                        last: 'Last',
                        next: 'Next',
                        previous: 'Previous'
                    },
                }
            });

            // Load initial summary data
            setTimeout(function() {
                loadSummaryData();
            }, 500);

            // Auto refresh setiap 30 detik
            setInterval(function() {
                // Only auto-refresh if page is visible
                if (!document.hidden) {
                    if (table) {
                        table.ajax.reload(null, false);
                    }
                    loadSummaryData();
                }
            }, 30000); // 30 seconds

            // Refresh saat page menjadi visible kembali
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    loadSummaryData();
                }
            });

            // Detail button handler
            $('#skm-table').on('click', '.btn-detail', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                showDetailModal(id);
            });

            // Toggle Status handler
            $('#skm-table').on('click', '.btn-toggle-status', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                const currentStatus = $(this).data('status');
                const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
                const statusText = newStatus === 'active' ? 'menampilkan di testimonial' : 'menyembunyikan dari testimonial';

                Swal.fire({
                    title: 'Konfirmasi',
                    text: `Yakin ingin ${statusText} data ini?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#20c997',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Ubah!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        toggleTestimonialStatus(id);
                    }
                });
            });

            // Hapus Delete handler karena tidak ada tombol delete lagi
        });

        // Function untuk load summary data
        function loadSummaryData() {
            // URL yang benar sesuai dengan route prefix
            const summaryUrl = '/Master-Data/data-skm/summary';

            $.ajax({
                url: summaryUrl,
                type: 'GET',
                beforeSend: function() {
                    // Show loading state pada cards
                    $('#totalSurveysCount').html('<i class="fas fa-spinner fa-spin"></i>');
                    $('#positiveCount').html('<i class="fas fa-spinner fa-spin"></i>');
                    $('#negativeCount').html('<i class="fas fa-spinner fa-spin"></i>');
                    $('#activeTestimonialCount').html('<i class="fas fa-spinner fa-spin"></i>');
                    $('#skmStatusSummary').text('Loading...');
                },
                success: function(response) {
                    console.log('✅ Summary loaded:', response);

                    if (response.success && response.data) {
                        updateSummaryDisplay(response.data);
                    } else {
                        console.error('❌ Invalid response format:', response);
                        showSummaryError();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('❌ Error loading summary:', {
                        status: xhr.status,
                        statusText: xhr.statusText,
                        responseText: xhr.responseText,
                        url: summaryUrl
                    });
                    showSummaryError();
                }
            });
        }

        // Function untuk update tampilan summary
        function updateSummaryDisplay(data) {
            console.log('Updating display with data:', data);

            try {
                // Total Survey dengan enhanced animation
                const totalSurveys = data.total_surveys || 0;
                animateCounterEnhanced('#totalSurveysCount', totalSurveys, 'primary');
                updateRecentSurveys(data.recent_surveys || 0);

                // Positive stats dengan enhanced animation
                const sangatPuas = data.sangat_puas || 0;
                const puas = data.puas || 0;
                const positiveTotal = sangatPuas + puas;

                animateCounterEnhanced('#positiveCount', positiveTotal, 'success');

                const positivePercentage = data.positive_percentage || 0;
                updatePercentageWithAnimation('#positivePercentage', positivePercentage, 'success');

                // Enhanced progress bar animation
                animateProgressBar('#positiveBar', positivePercentage, 'success');

                // Negative stats dengan enhanced animation
                const tidakPuas = data.tidak_puas || 0;
                animateCounterEnhanced('#negativeCount', tidakPuas, 'danger');

                const negativePercentage = data.negative_percentage || 0;
                updatePercentageWithAnimation('#negativePercentage', negativePercentage, 'danger');

                // Enhanced progress bar animation
                animateProgressBar('#negativeBar', negativePercentage, 'danger');

                // Testimonial stats dengan enhanced animation
                const activeSurveys = data.active_surveys || 0;
                animateCounterEnhanced('#activeTestimonialCount', activeSurveys, 'info');

                const satisfactionIndex = data.satisfaction_index || 0;
                animateSatisfactionIndex(satisfactionIndex);

                // Update status summary dengan typing effect
                const statusText = `Total: ${totalSurveys} Survey | Positif: ${positivePercentage}% | Negatif: ${negativePercentage}% | Indeks: ${satisfactionIndex}%`;
                typewriterEffect('#skmStatusSummary', statusText);

                console.log('Display updated successfully');

            } catch (error) {
                console.error('Error updating display:', error);
                showSummaryError();
            }
        }

        // Function untuk animasi counter
        function animateCounterEnhanced(selector, targetValue, colorType = 'primary') {
            const element = $(selector);

            if (!element.length) {
                console.error('Element not found:', selector);
                return;
            }

            const currentText = element.text();
            const startValue = parseInt(currentText.replace(/[^0-9]/g, '')) || 0;

            if (startValue === targetValue) {
                element.text(targetValue);
                return;
            }

            // Add pulsing effect during animation
            element.addClass('animate-pulse');

            // Stop any existing animation
            element.stop();

            $({ countNum: startValue }).animate({
                countNum: targetValue
            }, {
                duration: 1500,
                easing: 'easeOutCubic',
                step: function() {
                    const currentNum = Math.floor(this.countNum);
                    element.text(currentNum.toLocaleString());

                    // Add color transition effect
                    if (currentNum > startValue) {
                        element.addClass(`text-${colorType}-bright`);
                    }
                },
                complete: function() {
                    element.text(targetValue.toLocaleString());
                    element.removeClass('animate-pulse');

                    // Add completion effect
                    element.addClass('animate-bounce');
                    setTimeout(() => {
                        element.removeClass('animate-bounce text-primary-bright text-success-bright text-danger-bright text-info-bright');
                    }, 600);
                }
            });
        }

        function animateProgressBar(selector, percentage, colorType = 'primary') {
            const element = $(selector);

            if (!element.length) return;

            // Stop any existing animation
            element.stop();

            // Add glow effect
            element.addClass(`progress-glow-${colorType}`);

            element.animate({
                width: `${percentage}%`
            }, {
                duration: 1200,
                easing: 'easeOutCubic',
                step: function(now) {
                    // Dynamic box-shadow based on progress
                    const intensity = now / 100;
                    const glowColor = getGlowColor(colorType);
                    element.css('box-shadow', `0 0 ${intensity * 10}px ${glowColor}`);
                },
                complete: function() {
                    setTimeout(() => {
                        element.removeClass(`progress-glow-${colorType}`);
                    }, 1000);
                }
            });
        }

        function updatePercentageWithAnimation(selector, percentage, colorType = 'primary') {
            const element = $(selector);

            if (!element.length) return;

            const startValue = parseInt(element.text()) || 0;

            $({ countNum: startValue }).animate({
                countNum: percentage
            }, {
                duration: 1000,
                easing: 'easeOutCubic',
                step: function() {
                    element.text(Math.floor(this.countNum) + '%');
                },
                complete: function() {
                    element.text(percentage + '%');

                    // Add sparkle effect for high percentages
                    if (percentage > 80) {
                        addSparkleEffect(element);
                    }
                }
            });
        }

        // Update recent surveys with smooth transition
        function updateRecentSurveys(count) {
            const element = $('#recentSurveysText');
            const newText = `${count} survey minggu ini`;

            element.fadeOut(300, function() {
                element.text(newText).fadeIn(500);
            });
        }

        // Animate satisfaction index with special effects
        function animateSatisfactionIndex(value) {
            const element = $('#satisfactionIndex');

            if (!element.length) return;

            const startValue = parseInt(element.text()) || 0;

            $({ countNum: startValue }).animate({
                countNum: value
            }, {
                duration: 1800,
                easing: 'easeOutCubic',
                step: function() {
                    const currentValue = Math.floor(this.countNum);
                    element.text(currentValue);

                    // Change color based on satisfaction level
                    element.removeClass('text-danger text-warning text-success');
                    if (currentValue >= 80) {
                        element.addClass('text-success');
                    } else if (currentValue >= 60) {
                        element.addClass('text-warning');
                    } else {
                        element.addClass('text-danger');
                    }
                },
                complete: function() {
                    element.text(value);

                    // Add celebration effect for high satisfaction
                    if (value >= 90) {
                        addCelebrationEffect(element);
                    }
                }
            });
        }

        // Typewriter effect for status summary
        function typewriterEffect(selector, text, speed = 50) {
            const element = $(selector);

            if (!element.length) return;

            element.text('');
            let i = 0;

            const typeInterval = setInterval(function() {
                if (i < text.length) {
                    element.text(element.text() + text.charAt(i));
                    i++;
                } else {
                    clearInterval(typeInterval);
                }
            }, speed);
        }

        // Helper function to get glow colors
        function getGlowColor(colorType) {
            const colors = {
                'primary': 'rgba(0, 123, 255, 0.5)',
                'success': 'rgba(40, 167, 69, 0.5)',
                'danger': 'rgba(220, 53, 69, 0.5)',
                'info': 'rgba(23, 162, 184, 0.5)',
                'warning': 'rgba(255, 193, 7, 0.5)'
            };
            return colors[colorType] || colors.primary;
        }

        // Add sparkle effect for high percentages
        function addSparkleEffect(element) {
            const sparkle = $('<i class="fas fa-star sparkle-effect"></i>');
            element.parent().append(sparkle);

            sparkle.css({
                position: 'absolute',
                top: '10px',
                right: '10px',
                color: '#ffd700',
                animation: 'sparkle 1s ease-in-out'
            });

            setTimeout(() => {
                sparkle.remove();
            }, 1000);
        }

        // Add celebration effect for high satisfaction
        function addCelebrationEffect(element) {
            // Create confetti-like effect
            for (let i = 0; i < 5; i++) {
                setTimeout(() => {
                    const confetti = $('<span class="confetti">🎉</span>');
                    element.parent().append(confetti);

                    confetti.css({
                        position: 'absolute',
                        top: Math.random() * 50 + 'px',
                        left: Math.random() * 100 + 'px',
                        animation: 'confetti 2s ease-out forwards'
                    });

                    setTimeout(() => {
                        confetti.remove();
                    }, 2000);
                }, i * 200);
            }
        }

        const enhancedAnimationCSS = `
        <style>
        /* Enhanced animation classes */
        .animate-pulse {
            animation: pulse 1s infinite;
        }

        .animate-bounce {
            animation: bounce 0.6s ease-in-out;
        }

        .animate-shake {
            animation: shake 0.5s ease-in-out;
        }

        .text-primary-bright { color: #0056b3 !important; }
        .text-success-bright { color: #155724 !important; }
        .text-danger-bright { color: #721c24 !important; }
        .text-info-bright { color: #0c5460 !important; }

        .progress-glow-primary { box-shadow: 0 0 10px rgba(0, 123, 255, 0.6) !important; }
        .progress-glow-success { box-shadow: 0 0 10px rgba(40, 167, 69, 0.6) !important; }
        .progress-glow-danger { box-shadow: 0 0 10px rgba(220, 53, 69, 0.6) !important; }
        .progress-glow-info { box-shadow: 0 0 10px rgba(23, 162, 184, 0.6) !important; }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; transform: scale(1.05); }
        }

        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% { transform: translate3d(0,0,0); }
            40%, 43% { transform: translate3d(0,-8px,0); }
            70% { transform: translate3d(0,-4px,0); }
            90% { transform: translate3d(0,-2px,0); }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-2px); }
            20%, 40%, 60%, 80% { transform: translateX(2px); }
        }

        @keyframes sparkle {
            0% { opacity: 0; transform: scale(0) rotate(0deg); }
            50% { opacity: 1; transform: scale(1.2) rotate(180deg); }
            100% { opacity: 0; transform: scale(0) rotate(360deg); }
        }

        @keyframes confetti {
            0% { opacity: 1; transform: translateY(0) rotate(0deg); }
            100% { opacity: 0; transform: translateY(50px) rotate(360deg); }
        }

        /* Custom easing for jQuery animations */
        .easing-cubic { transition-timing-function: cubic-bezier(0.25, 0.8, 0.25, 1); }
        </style>
        `;

        // Inject enhanced CSS
        $('head').append(enhancedAnimationCSS);

        // Add custom easing to jQuery
        $.easing.easeOutCubic = function (x, t, b, c, d) {
            return c*((t=t/d-1)*t*t + 1) + b;
        };

        // Function untuk menampilkan error state
        function showSummaryError() {
            console.log('Showing enhanced summary error state');

            // Animate error state
            const errorElements = [
                '#totalSurveysCount', '#positiveCount',
                '#negativeCount', '#activeTestimonialCount'
            ];

            errorElements.forEach((selector, index) => {
                setTimeout(() => {
                    $(selector).text('Error').addClass('text-danger animate-shake');
                }, index * 200);
            });

            $('#skmStatusSummary').text('⚠️ Error: Gagal memuat data statistik');
            $('#recentSurveysText').text('Data tidak tersedia');
            $('#positivePercentage').text('0%');
            $('#negativePercentage').text('0%');
            $('#satisfactionIndex').text('0');

            // Reset progress bars with error state
            $('#positiveBar, #negativeBar').stop().css({
                'width': '0%',
                'background': '#dc3545'
            });

            // Remove error animation after some time
            setTimeout(() => {
                $('.animate-shake').removeClass('animate-shake');
            }, 2000);
        }

        // Function untuk menampilkan detail modal
        function showDetailModal(id) {
            const detailUrl = `/Master-Data/data-skm/${id}`;

            $.ajax({
                url: detailUrl,
                type: 'GET',
                beforeSend: function() {
                    $('#detailModalBody').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
                    $('#detailModal').modal('show');
                },
                success: function(response) {
                    if (response.success) {
                        const data = response.data;
                        const sentimentBadge = getSentimentBadge(data.sentiment);
                        const statusBadge = data.status === 'active'
                            ? '<span class="badge badge-success">Ditampilkan di Testimonial</span>'
                            : '<span class="badge badge-secondary">Tidak Ditampilkan</span>';
                        const kepuasanBadge = getKepuasanBadge(data.tingkat_kepuasan);

                        let modalContent = `
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Nama</strong></td>
                                            <td>:</td>
                                            <td>${data.nama}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>User</strong></td>
                                            <td>:</td>
                                            <td>${data.user}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Alamat</strong></td>
                                            <td>:</td>
                                            <td>${data.alamat}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tingkat Kepuasan</strong></td>
                                            <td>:</td>
                                            <td>${kepuasanBadge}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Sentimen</strong></td>
                                            <td>:</td>
                                            <td>${sentimentBadge}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status Testimonial</strong></td>
                                            <td>:</td>
                                            <td>${statusBadge}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Submit</strong></td>
                                            <td>:</td>
                                            <td>${data.tanggal}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6><strong>Kritik dan Saran:</strong></h6>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <p class="mb-0">${data.kritik_saran}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        $('#detailModalBody').html(modalContent);
                    }
                },
                error: function(xhr) {
                    $('#detailModalBody').html('<div class="alert alert-danger">Error loading detail data</div>');
                }
            });
        }

        // Helper functions untuk badge (tambahkan setelah function refreshSkmData)
        function getSentimentBadge(sentiment) {
            const badges = {
                'positive': '<span class="badge badge-success"><i class="fas fa-smile"></i> Positif</span>',
                'negative': '<span class="badge badge-danger"><i class="fas fa-frown"></i> Negatif</span>',
                'neutral': '<span class="badge badge-warning"><i class="fas fa-meh"></i> Netral</span>'
            };
            return badges[sentiment] || '<span class="badge badge-secondary">-</span>';
        }

        function getKepuasanBadge(tingkatKepuasan) {
            const badges = {
                'Sangat Puas': '<span class="badge badge-success">Sangat Puas</span>',
                'Puas': '<span class="badge badge-primary">Puas</span>',
                'Tidak Puas': '<span class="badge badge-warning">Tidak Puas</span>'
            };
            return badges[tingkatKepuasan] || '<span class="badge badge-secondary">-</span>';
        }

        // Function untuk menampilkan statistik modal
        function showStatistics() {
            const summaryUrl = '/Master-Data/data-skm/summary';

            $.ajax({
                url: summaryUrl,
                type: 'GET',
                beforeSend: function() {
                    $('#statisticsModalBody').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
                    $('#statisticsModal').modal('show');
                },
                success: function(response) {
                    if (response.success) {
                        const data = response.data;

                        let content = `
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card border-0 shadow">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0"><i class="fas fa-chart-line"></i> Ringkasan Statistik</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row text-center">
                                                <div class="col-md-3">
                                                    <h3 class="text-primary mb-1">${data.total_surveys}</h3>
                                                    <p class="text-muted mb-0">Total Survey</p>
                                                    <small class="text-info">${data.recent_surveys} minggu ini</small>
                                                </div>
                                                <div class="col-md-3">
                                                    <h3 class="text-success mb-1">${data.positive_percentage}%</h3>
                                                    <p class="text-muted mb-0">Tingkat Kepuasan</p>
                                                    <small class="text-success">${data.sangat_puas + data.puas} responden</small>
                                                </div>
                                                <div class="col-md-3">
                                                    <h3 class="text-info mb-1">${data.satisfaction_index}%</h3>
                                                    <p class="text-muted mb-0">Indeks Kepuasan</p>
                                                    <small class="text-info">Skor weighted</small>
                                                </div>
                                                <div class="col-md-3">
                                                    <h3 class="text-warning mb-1">${data.active_surveys}/10</h3>
                                                    <p class="text-muted mb-0">Testimonial Aktif</p>
                                                    <small class="text-warning">Limit maksimal</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card border-0 shadow">
                                        <div class="card-header bg-success text-white">
                                            <h6 class="mb-0"><i class="fas fa-chart-pie"></i> Distribusi Tingkat Kepuasan</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span><i class="fas fa-smile text-success"></i> Sangat Puas</span>
                                                    <span><strong>${data.sangat_puas}</strong> (${((data.sangat_puas / data.total_surveys) * 100).toFixed(1)}%)</span>
                                                </div>
                                                <div class="progress mb-2" style="height: 10px;">
                                                    <div class="progress-bar bg-success" style="width: ${((data.sangat_puas / data.total_surveys) * 100).toFixed(1)}%"></div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span><i class="fas fa-smile text-primary"></i> Puas</span>
                                                    <span><strong>${data.puas}</strong> (${((data.puas / data.total_surveys) * 100).toFixed(1)}%)</span>
                                                </div>
                                                <div class="progress mb-2" style="height: 10px;">
                                                    <div class="progress-bar bg-primary" style="width: ${((data.puas / data.total_surveys) * 100).toFixed(1)}%"></div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span><i class="fas fa-frown text-warning"></i> Tidak Puas</span>
                                                    <span><strong>${data.tidak_puas}</strong> (${((data.tidak_puas / data.total_surveys) * 100).toFixed(1)}%)</span>
                                                </div>
                                                <div class="progress mb-2" style="height: 10px;">
                                                    <div class="progress-bar bg-warning" style="width: ${((data.tidak_puas / data.total_surveys) * 100).toFixed(1)}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-0 shadow">
                                        <div class="card-header bg-info text-white">
                                            <h6 class="mb-0"><i class="fas fa-brain"></i> Analisis Sentimen</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span><i class="fas fa-smile text-success"></i> Positif</span>
                                                    <span><strong>${data.sentiment_stats.positive}</strong> (${data.sentiment_stats.positive_percentage}%)</span>
                                                </div>
                                                <div class="progress mb-2" style="height: 10px;">
                                                    <div class="progress-bar bg-success" style="width: ${data.sentiment_stats.positive_percentage}%"></div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span><i class="fas fa-meh text-warning"></i> Netral</span>
                                                    <span><strong>${data.sentiment_stats.neutral}</strong> (${data.sentiment_stats.neutral_percentage}%)</span>
                                                </div>
                                                <div class="progress mb-2" style="height: 10px;">
                                                    <div class="progress-bar bg-warning" style="width: ${data.sentiment_stats.neutral_percentage}%"></div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span><i class="fas fa-frown text-danger"></i> Negatif</span>
                                                    <span><strong>${data.sentiment_stats.negative}</strong> (${data.sentiment_stats.negative_percentage}%)</span>
                                                </div>
                                                <div class="progress mb-2" style="height: 10px;">
                                                    <div class="progress-bar bg-danger" style="width: ${data.sentiment_stats.negative_percentage}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        $('#statisticsModalBody').html(content);
                    }
                },
                error: function(xhr) {
                    $('#statisticsModalBody').html('<div class="alert alert-danger">Error loading statistics</div>');
                }
            });
        }

        // Function untuk toggle status testimonial individual
        function toggleTestimonialStatus(id) {
            const toggleUrl = `/Master-Data/data-skm/${id}/toggle-status`;

            $.ajax({
                url: toggleUrl,
                type: 'PATCH', // Sesuai dengan route method
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Sedang mengupdate status testimonial',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    Swal.close();

                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });

                        // Reload table and summary
                        table.ajax.reload(null, false);
                        loadSummaryData();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Terjadi kesalahan saat mengupdate status'
                        });
                    }
                },
                error: function(xhr) {
                    Swal.close();
                    let errorMessage = 'Terjadi kesalahan saat memproses permintaan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMessage
                    });
                }
            });
        }

        // Selection functions
        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll').checked;
            const checkboxes = document.querySelectorAll('.row-checkbox');

            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll;
                const id = parseInt(checkbox.dataset.id);
                if (selectAll && !selectedRows.includes(id)) {
                    selectedRows.push(id);
                } else if (!selectAll && selectedRows.includes(id)) {
                    selectedRows = selectedRows.filter(rowId => rowId !== id);
                }
            });

            updateBulkActions();
        }

        function toggleRowSelection(id) {
            const checkbox = document.querySelector(`input[data-id="${id}"]`);
            if (checkbox.checked && !selectedRows.includes(id)) {
                selectedRows.push(id);
            } else if (!checkbox.checked && selectedRows.includes(id)) {
                selectedRows = selectedRows.filter(rowId => rowId !== id);
            }

            updateBulkActions();
        }

        function updateBulkActions() {
            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');

            if (selectedRows.length > 0) {
                bulkActions.classList.add('show');
                selectedCount.textContent = selectedRows.length;
            } else {
                bulkActions.classList.remove('show');
            }
        }

        function clearSelection() {
            selectedRows = [];
            document.getElementById('selectAll').checked = false;
            document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = false);
            updateBulkActions();
        }

        function bulkManageTestimonial() {
            if (selectedRows.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak ada item dipilih',
                    text: 'Pilih minimal satu item untuk dikelola'
                });
                return;
            }

            Swal.fire({
                title: 'Kelola Testimonial',
                text: `Pilih aksi untuk ${selectedRows.length} item yang dipilih:`,
                icon: 'question',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Tampilkan di Testimonial',
                denyButtonText: 'Sembunyikan dari Testimonial',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    bulkActivateTestimonial();
                } else if (result.isDenied) {
                    bulkDeactivateTestimonial();
                }
            });
        }

        function bulkActivateTestimonial() {
            const bulkUrl = '/Master-Data/data-skm/bulk-toggle-testimonial';

            $.ajax({
                url: bulkUrl,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    ids: selectedRows,
                    action: 'activate'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message
                        });
                        table.ajax.reload();
                        loadSummaryData();
                        clearSelection();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat memproses bulk action'
                    });
                }
            });
        }

        function bulkDeactivateTestimonial() {
            const bulkUrl = '/Master-Data/data-skm/bulk-toggle-testimonial';

            $.ajax({
                url: bulkUrl,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    ids: selectedRows,
                    action: 'deactivate'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message
                        });
                        table.ajax.reload();
                        loadSummaryData();
                        clearSelection();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat memproses bulk action'
                    });
                }
            });
        }

        // Function untuk refresh data manual
        function refreshSkmData() {
            // Show loading toast
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1000,
                timerProgressBar: true,
                icon: 'info',
                title: 'Memperbarui data...'
            });

            // Reload table
            if (table) {
                table.ajax.reload(function() {
                    // After table reload, load summary
                    loadSummaryData();
                    clearSelection();
                });
            } else {
                // If table not available, just load summary
                loadSummaryData();
            }
        }
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
