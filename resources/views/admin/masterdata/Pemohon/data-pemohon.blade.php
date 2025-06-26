@extends('Template.template')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/owl.carousel/dist/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/owl.carousel/dist/assets/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">

    <style>
        /* Table styling */
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

        /* Date Range Picker Styling */
        .daterange-btn {
            min-width: 250px;
            text-align: left;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 10px 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .daterange-btn:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            color: white;
        }

        .daterange-btn:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.25);
        }

        .daterange-btn i {
            margin-right: 8px;
        }

        /* Period Title Styling */
        #periodTitle {
            color: #2c5aa0;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 15px;
            padding: 10px 15px;
            background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 100%);
            border-left: 4px solid #667eea;
            border-radius: 0 8px 8px 0;
        }

        .daterangepicker {
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }

        .daterangepicker .ranges {
            background-color: #f8f9fa;
            border-right: 1px solid #e9ecef;
        }

        .daterangepicker .ranges li {
            color: #495057;
            padding: 10px 15px;
            border-radius: 6px;
            margin: 2px 8px;
            transition: all 0.2s ease;
        }

        .daterangepicker .ranges li:hover {
            background-color: #667eea;
            color: white;
            transform: translateX(5px);
        }

        .daterangepicker .ranges li.active {
            background-color: #667eea;
            color: white;
            font-weight: 600;
        }

        .daterangepicker .calendar-table {
            background-color: white;
        }

        .daterangepicker .calendar-table .available:hover {
            background-color: #667eea;
            color: white;
        }

        .daterangepicker .calendar-table .in-range {
            background-color: rgba(102, 126, 234, 0.2);
            color: #333;
        }

        .daterangepicker .calendar-table .start-date,
        .daterangepicker .calendar-table .end-date {
            background-color: #667eea;
            color: white;
        }

        .daterangepicker .drp-buttons {
            padding: 15px;
            background-color: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }

        .daterangepicker .drp-buttons .btn {
            margin-left: 8px;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
        }

        /* Filter section styling */
        .filter-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid #e3e6f0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .filter-section .form-group {
            margin-bottom: 15px;
        }

        .filter-section .form-group:last-child {
            margin-bottom: 0;
        }

        /* Statistics cards */
        .stats-card {
            transition: all 0.2s ease;
            border-left: 4px solid transparent;
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .stats-card.total { border-left-color: #007bff; }
        .stats-card.terlayani { border-left-color: #28a745; }
        .stats-card.belum-terlayani { border-left-color: #dc3545; }
        .stats-card.online { border-left-color: #17a2b8; }
        .stats-card.offline { border-left-color: #6c757d; }

        /* Button styling */
        .btn-export {
            margin-right: 5px;
            margin-bottom: 5px;
        }

        .btn-filter {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: white;
        }

        .btn-filter:hover {
            background-color: #138496;
            border-color: #117a8b;
            color: white;
        }

        .btn-reset {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        .btn-reset:hover {
            background-color: #5a6268;
            border-color: #545b62;
            color: white;
        }

        /* DataTables styling */
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

        @media (max-width: 768px) {
            .daterange-btn {
                min-width: 200px;
                font-size: 14px;
            }

            .filter-section {
                padding: 20px 15px;
            }

            #periodTitle {
                font-size: 14px;
                padding: 8px 12px;
            }
        }

        @media (max-width: 576px) {
            .daterange-btn {
                width: 100%;
                min-width: auto;
                margin-bottom: 10px;
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
            </div>

            {{-- Statistics Cards --}}
            <div class="row mb-4">
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card stats-card total border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-2x text-primary mb-2"></i>
                            <h6 class="card-title text-muted">Total Pemohon</h6>
                            <h4 class="text-primary mb-0" id="totalPemohon">-</h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card stats-card terlayani border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                            <h6 class="card-title text-muted">Terlayani</h6>
                            <h4 class="text-success mb-0" id="terlayani">-</h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card stats-card belum-terlayani border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-clock fa-2x text-danger mb-2"></i>
                            <h6 class="card-title text-muted">Belum Terlayani</h6>
                            <h4 class="text-danger mb-0" id="belumTerlayani">-</h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card stats-card online border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-globe fa-2x text-info mb-2"></i>
                            <h6 class="card-title text-muted">Online</h6>
                            <h4 class="text-info mb-0" id="online">-</h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card stats-card offline border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-building fa-2x text-secondary mb-2"></i>
                            <h6 class="card-title text-muted">Offline</h6>
                            <h4 class="text-secondary mb-0" id="offline">-</h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card stats-card hari-ini border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-day fa-2x text-warning mb-2"></i>
                            <h6 class="card-title text-muted">Hari Ini</h6>
                            <h4 class="text-warning mb-0" id="hariIni">-</h4>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter Section --}}
            <div class="filter-section">
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="mb-3">
                            {{-- <i class="fas fa-filter"></i> --}}
                            <span id="periodTitle">Data Periode s/d</span>
                        </h6>
                    </div>
                </div>
                <form id="filterForm">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="d-block">Date Range Picker With Button</label>
                                <a href="javascript:;" class="btn btn-primary daterange-btn icon-left btn-icon" id="dateRangeButton">
                                    <i class="fas fa-calendar"></i>
                                    <span>Choose Date</span>
                                </a>
                                {{-- Hidden inputs untuk menyimpan tanggal yang dipilih --}}
                                <input type="hidden" id="start_date" name="start_date" value="{{ date('Y-m-d') }}">
                                <input type="hidden" id="end_date" name="end_date" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="status_filter">Status:</label>
                                <select class="form-control" id="status_filter" name="status">
                                    <option value="">Semua</option>
                                    <option value="0">Belum Terlayani</option>
                                    <option value="1">Terlayani</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="jenis_antrian_filter">Jenis Antrian:</label>
                                <select class="form-control" id="jenis_antrian_filter" name="jenis_antrian">
                                    <option value="">Semua</option>
                                    <option value="Online">Online</option>
                                    <option value="Offline">Offline</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="jenis_layanan_filter">Jenis Layanan:</label>
                                <select class="form-control" id="jenis_layanan_filter" name="jenis_layanan">
                                    <option value="">Semua</option>
                                    {{-- Options akan diisi via JavaScript --}}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="d-flex">
                                    <button type="button" class="btn btn-filter btn-sm mr-2" onclick="applyFilter()">
                                        <i class="fas fa-search"></i> Tampilkan
                                    </button>
                                    <button type="button" class="btn btn-reset btn-sm" onclick="resetFilter()">
                                        <i class="fas fa-undo"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between w-100">
                                <div>
                                    <h4>Data Pemohon</h4>
                                    <small class="text-muted" id="dateRangeInfo">Menampilkan data hari ini</small>
                                </div>
                                <div>
                                    <button class="btn btn-success btn-sm btn-export" onclick="exportExcel()" title="Export to Excel">
                                        <i class="fas fa-file-excel"></i> Excel
                                    </button>
                                    <button class="btn btn-danger btn-sm btn-export" onclick="exportPdf()" title="Export to PDF">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </button>
                                    <button class="btn btn-info btn-sm btn-export" onclick="printData()" title="Print">
                                        <i class="fas fa-print"></i> Print
                                    </button>
                                    <button class="btn btn-outline-primary btn-sm" onclick="refreshData()" title="Refresh Data">
                                        <i class="fas fa-sync-alt"></i> Refresh
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="dataPemohon-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Nama</th>
                                            <th>No WhatsApp</th>
                                            <th>Alamat</th>
                                            <th>Jenis Layanan</th>
                                            <th>Keterangan</th>
                                            <th>Jenis Antrian</th>
                                            <th>Jenis Pengiriman</th>
                                            <th>Status</th>
                                            <th>Dilayani Oleh</th>
                                            <th>Tanggal Dilayani</th>
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
    <script src="{{ asset('library/bootstrap-daterangepicker/daterangepicker.js') }}"></script>


    <script>
        let table;
        let currentFilters = {};
        let currentRangeLabel = 'Today';
        let currentStartDate = moment();
        let currentEndDate = moment();

        $(document).ready(function() {
            // Initialize date range picker
            initializeDateRangePicker();

            // Initialize DataTable
            initializeDataTable();

            // Load initial statistics
            loadStatistics();

            // Load filter options
            loadFilterOptions();

            // Set initial period title
            updatePeriodTitle('Today');

            // Auto refresh every 60 seconds
            setInterval(function() {
                table.ajax.reload(null, false);
                loadStatistics();
            }, 60000);
        });

        function initializeDateRangePicker() {
            $("#dateRangeButton").daterangepicker({
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [
                        moment().subtract(1, 'month').startOf('month'),
                        moment().subtract(1, 'month').endOf('month')
                    ]
                },
                startDate: moment(),
                endDate: moment(),
                locale: {
                    format: 'YYYY-MM-DD',
                    separator: ' - ',
                    applyLabel: 'Apply',
                    cancelLabel: 'Cancel',
                    fromLabel: 'From',
                    toLabel: 'To',
                    customRangeLabel: 'Custom Range',
                    weekLabel: 'W',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    monthNames: [
                        'January', 'February', 'March', 'April', 'May', 'June',
                        'July', 'August', 'September', 'October', 'November', 'December'
                    ],
                    firstDay: 1
                },
                opens: 'left',
                drops: 'down',
                buttonClasses: 'btn',
                applyClass: 'btn-primary',
                cancelClass: 'btn-secondary'
            }, function(start, end, label) {
                // Callback ketika range dipilih
                currentRangeLabel = label;
                currentStartDate = start;
                currentEndDate = end;

                // Update hidden inputs
                $('#start_date').val(start.format('YYYY-MM-DD'));
                $('#end_date').val(end.format('YYYY-MM-DD'));

                // Update button text
                updateButtonText(start, end, label);

                // Update period title
                updatePeriodTitle(label);

                // Auto apply filter jika bukan custom range
                if (label !== 'Custom Range') {
                    applyFilter();
                }

                console.log('Date range selected:', {
                    label: label,
                    start: start.format('YYYY-MM-DD'),
                    end: end.format('YYYY-MM-DD')
                });
            });

            // Set initial button text untuk Today
            updateButtonText(moment(), moment(), 'Today');
        }

        function updateButtonText(start, end, label) {
            let buttonText = '';

            switch(label) {
                case 'Today':
                    buttonText = '<i class="fas fa-calendar"></i> Today (' + start.format('DD MMM YYYY') + ')';
                    break;
                case 'Yesterday':
                    buttonText = '<i class="fas fa-calendar"></i> Yesterday (' + start.format('DD MMM YYYY') + ')';
                    break;
                case 'Last 7 Days':
                    buttonText = '<i class="fas fa-calendar"></i> Last 7 Days';
                    break;
                case 'Last 30 Days':
                    buttonText = '<i class="fas fa-calendar"></i> Last 30 Days';
                    break;
                case 'This Month':
                    buttonText = '<i class="fas fa-calendar"></i> This Month (' + start.format('MMM YYYY') + ')';
                    break;
                case 'Last Month':
                    buttonText = '<i class="fas fa-calendar"></i> Last Month (' + start.format('MMM YYYY') + ')';
                    break;
                case 'Custom Range':
                default:
                    if (start.isSame(end, 'day')) {
                        buttonText = '<i class="fas fa-calendar"></i> ' + start.format('DD MMM YYYY');
                    } else {
                        buttonText = '<i class="fas fa-calendar"></i> ' + start.format('DD MMM YYYY') + ' - ' + end.format('DD MMM YYYY');
                    }
                    break;
            }

            $('.daterange-btn').html(buttonText);
        }

        function updatePeriodTitle(label) {
            let titleText = '';

            switch(label) {
                case 'Today':
                    titleText = 'Data Periode Hari Ini';
                    break;
                case 'Yesterday':
                    titleText = 'Data Periode Kemarin';
                    break;
                case 'Last 7 Days':
                    titleText = 'Data Periode 7 Hari Terakhir';
                    break;
                case 'Last 30 Days':
                    titleText = 'Data Periode 30 Hari Terakhir';
                    break;
                case 'This Month':
                    titleText = 'Data Periode Bulan Ini';
                    break;
                case 'Last Month':
                    titleText = 'Data Periode Bulan Lalu';
                    break;
                case 'Custom Range':
                default:
                    if (currentStartDate && currentEndDate) {
                        if (currentStartDate.isSame(currentEndDate, 'day')) {
                            titleText = 'Data Periode ' + currentStartDate.format('DD MMMM YYYY');
                        } else {
                            titleText = 'Data Periode ' + currentStartDate.format('DD MMM YYYY') + ' s/d ' + currentEndDate.format('DD MMM YYYY');
                        }
                    } else {
                        titleText = 'Data Periode Custom';
                    }
                    break;
            }

            $('#periodTitle').html('<i class="fas fa-filter"></i> ' + titleText);
        }

        function loadFilterOptions() {
            // Load jenis layanan options
            $.ajax({
                url: "{{ route('pemohon.filterOptions') }}",
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const jenisLayananSelect = $('#jenis_layanan_filter');
                        jenisLayananSelect.empty().append('<option value="">Semua</option>');

                        response.data.jenis_layanan.forEach(function(item) {
                            jenisLayananSelect.append(`<option value="${item}">${item}</option>`);
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Error loading filter options:', xhr);
                }
            });
        }

        function initializeDataTable() {
            table = $('#dataPemohon-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('pemohon.getData') }}",
                    data: function(d) {
                        // Add filter parameters
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                        d.status = $('#status_filter').val();
                        d.jenis_antrian = $('#jenis_antrian_filter').val();
                        d.jenis_layanan = $('#jenis_layanan_filter').val();
                    },
                    error: function(xhr, error, thrown) {
                        console.log('DataTables error:', {xhr, error, thrown});
                    }
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        width: '5%'
                    },
                    {
                        data: 'tanggal_formatted',
                        name: 'tanggal',
                        width: '10%'
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        width: '12%'
                    },
                    {
                        data: 'no_whatsapp',
                        name: 'no_whatsapp',
                        width: '8%',
                        render: function(data) {
                            return data || '-';
                        }
                    },
                    {
                        data: 'alamat',
                        name: 'alamat',
                        width: '12%'
                    },
                    {
                        data: 'jenis_layanan',
                        name: 'jenis_layanan',
                        width: '10%'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                        width: '12%'
                    },
                    {
                        data: 'jenis_antrian',
                        name: 'jenis_antrian',
                        width: '8%'
                    },
                    {
                        data: 'jenis_pengiriman',
                        name: 'jenis_pengiriman',
                        width: '10%',
                        render: function(data) {
                            return data || '-';
                        }
                    },
                    {
                        data: 'status_badge',
                        name: 'status',
                        orderable: false,
                        width: '8%'
                    },
                    {
                        data: 'dilayani_oleh',
                        name: 'dilayani_oleh',
                        width: '8%',
                        render: function(data) {
                            return data || '-';
                        }
                    },
                    {
                        data: 'tanggal_dilayani_formatted',
                        name: 'tanggal_dilayani',
                        width: '10%'
                    }
                ],
                order: [[1, 'desc']], // Sort by tanggal
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                language: {
                    searchPlaceholder: "Cari data...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    emptyTable: `
                        <div class="text-center my-5">
                            <div class="mb-3">
                                <i class="fas fa-users fs-1 text-muted"></i>
                            </div>
                            <h6 class="fw-semibold text-muted">Belum ada Data Pemohon</h6>
                            <p class="text-muted small">Data pemohon akan muncul di sini</p>
                        </div>
                    `,
                    zeroRecords: `
                        <div class="text-center my-5">
                            <div class="mb-3">
                                <i class="fas fa-search fs-1 text-muted"></i>
                            </div>
                            <h6 class="fw-semibold text-muted">Tidak ada data yang sesuai</h6>
                            <p class="text-muted small">Coba ubah filter atau kata kunci pencarian</p>
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
                },
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                drawCallback: function(settings) {
                    // Update date range info
                    updateDateRangeInfo();
                }
            });
        }

        function applyFilter() {
            // Store current filters
            currentFilters = {
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val(),
                status: $('#status_filter').val(),
                jenis_antrian: $('#jenis_antrian_filter').val(),
                jenis_layanan: $('#jenis_layanan_filter').val()
            };

            // Reload table with new filters
            table.ajax.reload();

            // Reload statistics
            loadStatistics();

            // Show loading notification
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                icon: 'info',
                title: 'Filter diterapkan untuk ' + currentRangeLabel
            });
        }

        function resetFilter() {
            // Reset date range ke Today
            const today = moment();
            currentRangeLabel = 'Today';
            currentStartDate = today;
            currentEndDate = today;

            // Update date range picker
            $('.daterange-btn').data('daterangepicker').setStartDate(today);
            $('.daterange-btn').data('daterangepicker').setEndDate(today);

            // Update hidden inputs
            $('#start_date').val(today.format('YYYY-MM-DD'));
            $('#end_date').val(today.format('YYYY-MM-DD'));

            // Update button text
            updateButtonText(today, today, 'Today');

            // Update period title
            updatePeriodTitle('Today');

            // Reset other filters
            $('#status_filter').val('');
            $('#jenis_antrian_filter').val('');
            $('#jenis_layanan_filter').val('');

            // Clear current filters
            currentFilters = {};

            // Reload table
            table.ajax.reload();

            // Reload statistics
            loadStatistics();

            // Show reset notification
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                icon: 'success',
                title: 'Filter direset ke Today'
            });
        }


        function loadStatistics() {
            const params = new URLSearchParams({
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val()
            });

            $.ajax({
                url: "{{ route('pemohon.statistics') }}?" + params.toString(),
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const stats = response.data.summary;
                        $('#totalPemohon').text(stats.total_pemohon.toLocaleString());
                        $('#terlayani').text(stats.terlayani.toLocaleString());
                        $('#belumTerlayani').text(stats.belum_terlayani.toLocaleString());
                        $('#online').text(stats.online.toLocaleString());
                        $('#offline').text(stats.offline.toLocaleString());
                        $('#hariIni').text(stats.hari_ini?.toLocaleString() || '-');
                    }
                },
                error: function(xhr) {
                    console.error('Error loading statistics:', xhr);
                }
            });
        }

        function updateDateRangeInfo() {
            const startDate = $('#start_date').val();
            const endDate = $('#end_date').val();

            let infoText = '';
            if (currentRangeLabel) {
                switch(currentRangeLabel) {
                    case 'Today':
                        infoText = `Menampilkan data hari ini (${startDate})`;
                        break;
                    case 'Yesterday':
                        infoText = `Menampilkan data kemarin (${startDate})`;
                        break;
                    case 'Last 7 Days':
                        infoText = `Menampilkan data 7 hari terakhir`;
                        break;
                    case 'Last 30 Days':
                        infoText = `Menampilkan data 30 hari terakhir`;
                        break;
                    case 'This Month':
                        infoText = `Menampilkan data bulan ini`;
                        break;
                    case 'Last Month':
                        infoText = `Menampilkan data bulan lalu`;
                        break;
                    default:
                        if (startDate === endDate) {
                            infoText = `Menampilkan data ${startDate}`;
                        } else {
                            infoText = `Menampilkan data ${startDate} s/d ${endDate}`;
                        }
                        break;
                }
            } else {
                if (startDate === endDate) {
                    infoText = `Menampilkan data ${startDate}`;
                } else {
                    infoText = `Menampilkan data ${startDate} s/d ${endDate}`;
                }
            }

            $('#dateRangeInfo').text(infoText);
        }

        function exportExcel() {
            const params = new URLSearchParams({
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val(),
                status: $('#status_filter').val(),
                jenis_antrian: $('#jenis_antrian_filter').val()
            });

            window.location.href = "{{ route('pemohon.exportExcel') }}?" + params.toString();
        }

        function previewPdf() {
            // ✅ Ambil nilai filter dengan benar
            const params = new URLSearchParams({
                start_date: $('#start_date').val() || '',
                end_date: $('#end_date').val() || '',
                status: $('#status_filter').val() || '',
                jenis_antrian: $('#jenis_antrian_filter').val() || '',
                jenis_layanan: $('#jenis_layanan_filter').val() || ''
            });

            // ✅ DEBUGGING: Log parameter yang dikirim
            console.log('Preview PDF Parameters:', {
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val(),
                status: $('#status_filter').val(),
                jenis_antrian: $('#jenis_antrian_filter').val(),
                jenis_layanan: $('#jenis_layanan_filter').val()
            });

            // ✅ Hapus parameter kosong untuk menghindari konflik
            const cleanParams = new URLSearchParams();
            params.forEach((value, key) => {
                if (value && value.trim() !== '') {
                    cleanParams.append(key, value.trim());
                }
            });

            const url = "{{ route('pemohon.previewPdf') }}?" + cleanParams.toString();
            console.log('Preview URL:', url);

            // ✅ Buka di tab baru
            window.open(url, '_blank');
        }

        function exportPdf() {
            // Show confirmation with preview option
            Swal.fire({
                title: 'Download Laporan PDF',
                text: 'Apakah Anda ingin melihat preview terlebih dahulu?',
                icon: 'question',
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: '<i class="fas fa-eye"></i> Preview Dulu',
                denyButtonText: '<i class="fas fa-download"></i> Download Langsung',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#667eea',
                denyButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Preview first
                    previewPdf();
                } else if (result.isDenied) {
                    // Direct download
                    const params = new URLSearchParams({
                        start_date: $('#start_date').val() || '',
                        end_date: $('#end_date').val() || '',
                        status: $('#status_filter').val() || '',
                        jenis_antrian: $('#jenis_antrian_filter').val() || '',
                        jenis_layanan: $('#jenis_layanan_filter').val() || ''
                    });

                    // ✅ DEBUGGING: Log parameter yang dikirim
                    console.log('Export PDF Parameters:', {
                        start_date: $('#start_date').val(),
                        end_date: $('#end_date').val(),
                        status: $('#status_filter').val(),
                        jenis_antrian: $('#jenis_antrian_filter').val(),
                        jenis_layanan: $('#jenis_layanan_filter').val()
                    });

                    // ✅ Hapus parameter kosong
                    const cleanParams = new URLSearchParams();
                    params.forEach((value, key) => {
                        if (value && value.trim() !== '') {
                            cleanParams.append(key, value.trim());
                        }
                    });

                    const url = "{{ route('pemohon.exportPdf') }}?" + cleanParams.toString();
                    console.log('Export URL:', url);

                    window.location.href = url;

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        icon: 'success',
                        title: 'Download dimulai'
                    });
                }
            });
        }

        function printData() {
            // ✅ Ambil parameter filter yang sama seperti PDF/Excel
            const params = new URLSearchParams({
                start_date: $('#start_date').val() || '',
                end_date: $('#end_date').val() || '',
                status: $('#status_filter').val() || '',
                jenis_antrian: $('#jenis_antrian_filter').val() || '',
                jenis_layanan: $('#jenis_layanan_filter').val() || ''
            });

            // ✅ Hapus parameter kosong
            const cleanParams = new URLSearchParams();
            params.forEach((value, key) => {
                if (value && value.trim() !== '') {
                    cleanParams.append(key, value.trim());
                }
            });

            // ✅ OPSI 1: Buka di tab baru untuk print
            const url = "{{ route('pemohon.printData') }}?" + cleanParams.toString();
            const printWindow = window.open(url, '_blank');

            // ✅ Auto print saat window load (opsional)
            printWindow.onload = function() {
                setTimeout(function() {
                    printWindow.print();
                }, 500);
            };
        }
        function refreshData() {
            table.ajax.reload();
            loadStatistics();

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

        // Handle enter key in filter form
        $('#filterForm input, #filterForm select').on('keypress change', function(e) {
            if (e.type === 'keypress' && e.which === 13) {
                applyFilter();
            }
        });
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
