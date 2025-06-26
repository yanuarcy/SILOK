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

        /* Styling untuk tombol action */
        .btn-sm {
            padding: 5px 10px;
            border-radius: 4px;
            transition: all 0.3s ease;
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

        /* Custom badge styling */
        .badge {
            font-size: 0.75em;
            padding: 0.35em 0.65em;
        }

        /* File info styling */
        .file-info {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .file-info-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
        }

        /* Pejabat info styling */
        .pejabat-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .pejabat-name {
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.9rem;
        }

        .user-name {
            font-size: 0.8rem;
            color: #6c757d;
        }

        .pejabat-note {
            font-size: 0.75rem;
            color: #495057;
            font-style: italic;
        }

        /* Wilayah styling */
        .wilayah-info {
            background: #e3f2fd;
            color: #1565c0;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-block;
        }

        /* Status info styling */
        .status-container {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        /* Action buttons styling */
        .btn-group .btn-sm {
            margin: 0 1px;
        }

        .btn-group .btn-sm:first-child {
            margin-left: 0;
        }

        .btn-group .btn-sm:last-child {
            margin-right: 0;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .dataTables_wrapper {
                padding: 0 10px;
            }

            .table-responsive {
                font-size: 0.875rem;
            }

            .card-header .d-flex {
                flex-direction: column;
                gap: 10px;
            }

            .card-header h4 {
                margin-bottom: 0;
            }

            .btn {
                width: 100%;
            }
        }

        /* Loading state */
        .dataTables_processing {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 200px;
            margin-left: -100px;
            margin-top: -25px;
            text-align: center;
            padding: 10px;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        /* Custom scrollbar untuk table */
        .table-responsive::-webkit-scrollbar {
            height: 8px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Hover effect untuk rows */
        .table-striped tbody tr:hover .btn {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Icon animations */
        .fa-signature, .fa-stamp {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }

        /* Success message styling */
        .alert-success {
            border-left: 4px solid #28a745;
        }

        /* Info box styling */
        .info-box {
            background: #e8f4fd;
            border: 1px solid #bee5eb;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .info-box h6 {
            color: #0c5460;
            margin-bottom: 10px;
        }

        .info-box p {
            color: #0c5460;
            margin-bottom: 0;
            font-size: 0.9rem;
        }

        /* File status indicators */
        .file-status-complete {
            color: #28a745;
        }

        .file-status-partial {
            color: #ffc107;
        }

        .file-status-empty {
            color: #dc3545;
        }
    </style>
@endpush

@section('Dashboard')
    @include('admin.dashboard.header')
    @include('admin.dashboard.sidebar')

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Spesimen TTD & Stempel</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active">Master Data</div>
                    <div class="breadcrumb-item">Spesimen TTD & Stempel</div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="row">
                <div class="col-12">
                    <div class="info-box">
                        <h6><i class="fas fa-info-circle me-2"></i>Informasi Spesimen TTD & Stempel</h6>
                        <p>Kelola data spesimen tanda tangan dan stempel dari para pejabat Ketua RT dan Ketua RW. Data ini digunakan untuk keperluan dokumen resmi kelurahan.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            @auth
                                <div class="d-flex justify-content-between w-100 align-items-center">
                                    <div>
                                        <h4 class="mb-1">Data Spesimen TTD & Stempel</h4>
                                        <small class="text-muted">
                                            {{ auth()->user()->role === 'admin' ? 'Semua Data' : (auth()->user()->role === 'Ketua RW' ? 'RW ' . auth()->user()->rw : 'RT ' . auth()->user()->rt . ' RW ' . auth()->user()->rw) }}
                                        </small>
                                    </div>
                                    <div>
                                        <button class="btn btn-primary" onclick="createSpesimen()">
                                            <i class="fas fa-plus"></i> Tambah Spesimen
                                        </button>
                                    </div>
                                </div>
                            @endauth
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="spesimen-table">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="15%">File Info</th>
                                            <th width="25%">Pejabat & User</th>
                                            <th width="12%">Jabatan</th>
                                            <th width="13%">Wilayah</th>
                                            <th width="15%">Status</th>
                                            <th width="15%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be populated by DataTables -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i>
                                    Klik tombol <strong>Lihat File</strong> untuk melihat preview TTD dan Stempel
                                </small>
                                <small class="text-muted">
                                    Last updated: <span id="last-updated">{{ now()->format('d/m/Y H:i') }}</span>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards (Optional) -->
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-signature"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total TTD</h4>
                            </div>
                            <div class="card-body" id="total-ttd">
                                Loading...
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-stamp"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Stempel</h4>
                            </div>
                            <div class="card-body" id="total-stempel">
                                Loading...
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Ketua RT</h4>
                            </div>
                            <div class="card-body" id="total-rt">
                                Loading...
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Ketua RW</h4>
                            </div>
                            <div class="card-body" id="total-rw">
                                Loading...
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
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('library/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            const table = $('#spesimen-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('admin.masterdata.Spesimen.data') }}",
                    error: function(xhr, error, thrown) {
                        console.log('DataTables error:', {xhr, error, thrown});
                        console.log('Response:', xhr.responseJSON);

                        Swal.fire({
                            icon: 'error',
                            title: 'Error Loading Data',
                            text: 'Terjadi kesalahan saat memuat data. Silakan refresh halaman.',
                            confirmButtonText: 'Refresh',
                            allowOutsideClick: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                    }
                },
                columns: [
                    {
                        data: null,
                        className: 'text-center',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'file_info',
                        name: 'file_ttd',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'pejabat_info',
                        name: 'nama_pejabat',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'jabatan_badge',
                        name: 'jabatan',
                        className: 'text-center',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'wilayah',
                        name: 'nomor_rw',
                        className: 'text-center',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'status_info',
                        name: 'status',
                        className: 'text-center',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                order: [[2, 'asc']], // Sort by nama_pejabat asc
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                language: {
                    searchPlaceholder: "Cari spesimen...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    search: "Pencarian:",
                    processing: '<div class="d-flex justify-content-center align-items-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div><span class="ml-2">Memuat data...</span></div>',
                    emptyTable: `
                        <div class="text-center my-5">
                            <div class="mb-3">
                                <i class="fas fa-signature fs-1 text-muted"></i>
                            </div>
                            <h6 class="fw-semibold text-muted">Belum ada Data Spesimen</h6>
                            <p class="text-muted small">Data spesimen TTD & Stempel akan muncul di sini</p>
                            <button class="btn btn-primary btn-sm" onclick="createSpesimen()">
                                <i class="fas fa-plus"></i> Tambah Spesimen Pertama
                            </button>
                        </div>
                    `,
                    zeroRecords: `
                        <div class="text-center my-5">
                            <div class="mb-3">
                                <i class="fas fa-search fs-1 text-muted"></i>
                            </div>
                            <h6 class="fw-semibold text-muted">Data tidak ditemukan</h6>
                            <p class="text-muted small">Coba gunakan kata kunci lain atau hapus filter pencarian</p>
                            <button class="btn btn-outline-secondary btn-sm" onclick="table.search('').draw();">
                                <i class="fas fa-times"></i> Hapus Pencarian
                            </button>
                        </div>
                    `,
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    loadingRecords: "Memuat...",
                    paginate: {
                        first: '<i class="fas fa-angle-double-left"></i>',
                        last: '<i class="fas fa-angle-double-right"></i>',
                        next: '<i class="fas fa-angle-right"></i>',
                        previous: '<i class="fas fa-angle-left"></i>'
                    },
                },
                drawCallback: function(settings) {
                    // Update last updated time
                    $('#last-updated').text(new Date().toLocaleString('id-ID'));

                    // Initialize tooltips
                    $('[data-toggle="tooltip"]').tooltip();

                    // Add loading state to buttons
                    $('.btn-delete').on('click', function() {
                        $(this).html('<i class="fas fa-spinner fa-spin"></i>');
                    });
                }
            });

            // Delete handler with improved UX
            $('#spesimen-table').on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                const name = $(this).data('name');
                const button = $(this);
                const originalHtml = button.html();

                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-danger mx-2',
                        cancelButton: 'btn btn-secondary mx-2'
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: 'Konfirmasi Hapus',
                    html: `Apakah Anda yakin ingin menghapus data spesimen:<br><strong>${name}</strong>?<br><br><small class="text-danger">Data yang dihapus tidak dapat dikembalikan!</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
                    cancelButtonText: '<i class="fas fa-times"></i> Batal',
                    reverseButtons: true,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        button.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

                        $.ajax({
                            url: form.attr('action'),
                            method: 'DELETE',
                            data: form.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Reload table
                                    table.ajax.reload(null, false);

                                    // Update statistics
                                    loadStatistics();

                                    // Show success message
                                    swalWithBootstrapButtons.fire({
                                        title: 'Berhasil!',
                                        text: response.message || 'Data spesimen berhasil dihapus.',
                                        icon: 'success',
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                } else {
                                    throw new Error(response.message || 'Gagal menghapus data');
                                }
                            },
                            error: function(xhr) {
                                console.error('Delete error:', xhr);

                                let errorMessage = 'Terjadi kesalahan saat menghapus data.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }

                                swalWithBootstrapButtons.fire({
                                    title: 'Error!',
                                    text: errorMessage,
                                    icon: 'error'
                                });

                                // Restore button
                                button.html(originalHtml).prop('disabled', false);
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: 'Dibatalkan',
                            text: 'Data spesimen Anda aman :)',
                            icon: 'info',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
            });

            // Auto refresh functionality
            let autoRefreshInterval;
            function startAutoRefresh() {
                autoRefreshInterval = setInterval(function() {
                    table.ajax.reload(null, false); // false = stay on current page
                    loadStatistics();
                }, 300000); // Refresh every 5 minutes
            }

            // Load statistics
            function loadStatistics() {
                // This would typically be an API call to get statistics
                // For now, we'll use placeholder values
                setTimeout(function() {
                    $('#total-ttd').text('Loading...');
                    $('#total-stempel').text('Loading...');
                    $('#total-rt').text('Loading...');
                    $('#total-rw').text('Loading...');

                    // Simulate API call delay
                    setTimeout(function() {
                        $('#total-ttd').text('-');
                        $('#total-stempel').text('-');
                        $('#total-rt').text('-');
                        $('#total-rw').text('-');
                    }, 1000);
                }, 100);
            }

            // Quick search functionality
            $('#quick-search').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Export functionality (if needed)
            function exportData(format) {
                const searchValue = table.search();
                const url = `{{ route('admin.masterdata.Spesimen.index') }}/export/${format}?search=${encodeURIComponent(searchValue)}`;
                window.open(url, '_blank');
            }

            // Keyboard shortcuts
            $(document).on('keydown', function(e) {
                // Ctrl+N for new spesimen
                if ((e.ctrlKey || e.metaKey) && e.which === 78) {
                    e.preventDefault();
                    createSpesimen();
                }

                // Ctrl+R for refresh
                if ((e.ctrlKey || e.metaKey) && e.which === 82) {
                    e.preventDefault();
                    table.ajax.reload();
                    loadStatistics();
                }
            });

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Load initial statistics
            loadStatistics();

            // Start auto refresh (optional)
            // startAutoRefresh();

            console.log('✅ Spesimen Index page initialized successfully');
        });

        // Global function for create button
        function createSpesimen() {
            window.location.href = "{{ route('admin.masterdata.Spesimen.create') }}";
        }

        // Global function for refresh
        function refreshData() {
            $('#spesimen-table').DataTable().ajax.reload();
            loadStatistics();
        }

        // Show keyboard shortcuts modal
        function showKeyboardShortcuts() {
            Swal.fire({
                title: 'Keyboard Shortcuts',
                html: `
                    <div class="text-left">
                        <p><kbd>Ctrl+N</kbd> - Tambah Spesimen Baru</p>
                        <p><kbd>Ctrl+R</kbd> - Refresh Data</p>
                        <p><kbd>Esc</kbd> - Tutup Modal</p>
                    </div>
                `,
                showConfirmButton: false,
                showCloseButton: true,
                customClass: {
                    container: 'keyboard-shortcuts-modal'
                }
            });
        }

        // Show info about the page
        function showPageInfo() {
            Swal.fire({
                title: 'Tentang Halaman Ini',
                html: `
                    <div class="text-left">
                        <h6>Spesimen TTD & Stempel</h6>
                        <p>Halaman ini digunakan untuk mengelola data spesimen tanda tangan dan stempel dari para pejabat Ketua RT dan Ketua RW.</p>

                        <h6 class="mt-3">Fitur:</h6>
                        <ul class="text-left">
                            <li>Tambah, edit, dan hapus data spesimen</li>
                            <li>Upload file TTD dan Stempel</li>
                            <li>Preview file gambar</li>
                            <li>Filter data berdasarkan wilayah</li>
                            <li>Export data ke Excel/PDF</li>
                        </ul>

                        <h6 class="mt-3">Tips:</h6>
                        <ul class="text-left">
                            <li>Gunakan file gambar dengan background transparan</li>
                            <li>Ukuran file maksimal 5MB</li>
                            <li>Format yang didukung: JPG, PNG, GIF, SVG</li>
                        </ul>
                    </div>
                `,
                width: '600px',
                showConfirmButton: true,
                confirmButtonText: 'Mengerti'
            });
        }

        function deleteSpesimen(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading
                    Swal.fire({
                        title: 'Menghapus...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Kirim request DELETE
                    fetch(`/spesimen/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        return response.json().then(data => ({
                            status: response.status,
                            ok: response.ok,
                            data: data
                        }));
                    })
                    .then(result => {
                        if (result.ok) {
                            // Berhasil dihapus
                            Swal.fire({
                                title: 'Berhasil!',
                                text: result.data.message,
                                icon: 'success'
                            }).then(() => {
                                // Reload halaman atau hapus row dari tabel
                                location.reload();
                                // Atau jika menggunakan DataTables:
                                // $('#your-table').DataTable().ajax.reload();
                            });
                        } else {
                            // Error (403, 404, 500, dll)
                            Swal.fire({
                                title: 'Gagal!',
                                text: result.data.message || 'Terjadi kesalahan saat menghapus data.',
                                icon: 'error'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan pada sistem.',
                            icon: 'error'
                        });
                    });
                }
            });
        }
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
