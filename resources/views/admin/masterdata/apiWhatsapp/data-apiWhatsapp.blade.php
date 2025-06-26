@extends('Template.template')

{{-- @section('title', 'General Dashboard') --}}

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet"
        href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">

    <link rel="stylesheet"
        href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">

    <link rel="stylesheet"
        href="{{ asset('library/owl.carousel/dist/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('library/owl.carousel/dist/assets/owl.theme.default.min.css') }}">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Template CSS -->
    <link rel="stylesheet"
        href="{{ asset('css/style.css') }}">
    <link rel="stylesheet"
        href="{{ asset('css/components.css') }}">

    <style>
        /* .card-body {
            padding: 25px 15px;
        } */

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

        /* Enhanced quota display styles */
        .quota-display {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .quota-bar {
            width: 60px;
            height: 8px;
            background-color: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
        }

        .quota-fill {
            height: 100%;
            transition: width 0.3s ease;
        }

        .quota-high { background-color: #28a745; }
        .quota-medium { background-color: #17a2b8; }
        .quota-low { background-color: #ffc107; }
        .quota-critical { background-color: #fd7e14; }
        .quota-empty { background-color: #dc3545; }

        .quota-number {
            font-weight: 600;
            min-width: 60px;
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
        .summary-card.active { border-left-color: #28a745; }
        .summary-card.quota { border-left-color: #ffc107; }
        .summary-card.warning { border-left-color: #dc3545; }

        /* Button enhancements */
        .btn-topup {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: white;
        }

        .btn-topup:hover {
            background-color: #138496;
            border-color: #117a8b;
            color: white;
        }

        .btn-auto-switch {
            background-color: #6f42c1;
            border-color: #6f42c1;
            color: white;
        }

        .btn-auto-switch:hover {
            background-color: #5a32a3;
            border-color: #512d89;
            color: white;
        }

        /* Warning alert styles */
        .quota-warning-alert {
            border-left: 4px solid #ffc107;
            background-color: #fff3cd;
        }

        .quota-critical-alert {
            border-left: 4px solid #dc3545;
            background-color: #f8d7da;
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
            padding: 0 15px; /* Spacing kanan kiri */
        }

        /* Styling untuk DataTables controls */
        .dataTables_wrapper .dataTables_length select {
            /* padding: 5px 10px;
            border-radius: 4px;
            border: 1px solid #e9ecef; */
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
            padding: 20px 0 !important; /* Spacing atas bawah */
            margin-top: 15px !important;
            border-top: 1px solid #e9ecef;
        }

        .dataTables_wrapper .dataTables_info {
            padding: 20px 0 !important;
            margin-top: 15px !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            /* padding: 5px 10px;
            margin: 0 2px;
            border-radius: 4px;
            border: 1px solid #e9ecef; */
            margin: 0 5px !important;
            padding: 5px 12px !important;
            border-radius: 4px !important;
            border: 1px solid #dee2e6 !important;
            background: white !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            /* background: #6777ef;
            color: white !important;
            border: 1px solid #6777ef; */
            background: #0d6efd !important;
            color: white !important;
            border-color: #0d6efd !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f8f9fa !important;
            color: #0d6efd !important;
            border-color: #0d6efd !important;
        }

        /* Pagination container specific styling */
        .dataTables_wrapper .bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
            padding: 0 15px;
        }

        /* Info text styling */
        .dataTables_info {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .btn-group {
            padding: 0 15px; /* Spacing kanan kiri */
            gap: 10px; /* Jarak antar button */
        }

        .btn-group .btn {
            border-radius: 4px !important;
            padding: 8px 20px;
            transition: all 0.2s ease;
        }

        .btn-group .btn.active {
            background-color: #0d6efd;
            color: white;
            box-shadow: 0 2px 4px rgba(13, 110, 253, 0.2);
        }

        .table thead th {
            vertical-align: middle;
        }
        .btn-call {
            background-color: #198754;
            color: white;
            border: none;
            padding: 0.375rem 0.75rem;
            border-radius: 0.25rem;
        }
        .btn-end-call {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 0.375rem 0.75rem;
            border-radius: 0.25rem;
        }
        .btn-disabled {
            background-color: #6c757d;
            cursor: not-allowed;
        }

        /* Styling untuk empty state message */
        .dataTables_empty {
            /* padding: 60px !important; */
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

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .dataTables_wrapper {
                padding: 0 10px;
            }

            .btn-group {
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
            </div>

            {{-- Summary Cards --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card summary-card total border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-server fa-2x text-primary mb-2"></i>
                            <h6 class="card-title text-muted">Total APIs</h6>
                            <h4 class="text-primary mb-0" id="totalApisCount">-</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card summary-card active border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                            <h6 class="card-title text-muted">Active APIs</h6>
                            <h4 class="text-success mb-0" id="activeApisCount">-</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card summary-card quota border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-coins fa-2x text-warning mb-2"></i>
                            <h6 class="card-title text-muted">Total Quota</h6>
                            <h4 class="text-warning mb-0" id="totalQuotaCount">-</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card summary-card quota border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-battery-three-quarters fa-2x text-info mb-2"></i>
                            <h6 class="card-title text-muted">Active Quota</h6>
                            <h4 class="text-info mb-0" id="activeQuotaCount">-</h4>
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
                                    <h4>Data API Whatsapp</h4>
                                    <small class="text-muted" id="apiStatusSummary">Loading...</small>
                                </div>
                                <div>
                                    <button class="btn btn-outline-primary btn-sm mr-1" onclick="refreshApiData()" title="Refresh Data">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                    <button class="btn btn-auto-switch btn-sm mr-1" onclick="autoSwitchToMaxQuota()" title="Auto Switch ke Quota Terbanyak">
                                        <i class="fas fa-random"></i> Auto Switch
                                    </button>
                                    <button class="btn btn-icon btn-info mr-1" data-toggle="tooltip" title="Informasi Token API Whatsapp" id="infoButton">
                                        <i style="font-size: 14px;" class="fas fa-question-circle"></i>
                                    </button>
                                    <a href="{{ route('ApiWhatsapp.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Tambah Data
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="dataApiWhatsapp-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Owner Whatsapp</th>
                                            <th>No Whatsapp</th>
                                            <th>Status</th>
                                            <th>Quota</th>
                                            <th>Subscribe</th>
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


    <script>
        let table;

        $(document).ready(function() {
            // Initialize DataTable dengan kolom quota yang enhanced
            table = $('#dataApiWhatsapp-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('ApiWhatsapp.getData') }}",
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
                    { data: 'name', name: 'name' },
                    { data: 'whatsapp_number', name: 'whatsapp_number' },
                    { data: 'status', name: 'status' },
                    {
                        data: 'quota_display',
                        name: 'quota',
                        className: 'text-center',
                        orderable: true,
                        searchable: false
                    },
                    { data: 'subscribe', name: 'subscription_date' },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                order: [[0, 'asc']],
                language: {
                    searchPlaceholder: "Cari data...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    emptyTable: `
                        <div class="text-center my-5">
                            <div class="mb-3">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                            </div>
                            <h6 class="fw-semibold text-muted">Belum ada Data</h6>
                            <p class="text-muted small">Data API Whatsapp akan muncul di sini</p>
                        </div>
                    `,
                    zeroRecords: `
                        <div class="text-center my-5">
                            <div class="mb-3">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                            </div>
                            <h6 class="fw-semibold text-muted">Belum ada Data</h6>
                            <p class="text-muted small">Data API Whatsapp akan muncul di sini</p>
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
            loadSummaryData();

            // Auto refresh setiap 30 detik
            setInterval(function() {
                table.ajax.reload(null, false);
                loadSummaryData();
            }, 30000);

            // Info button handler
            $('#infoButton').click(function() {
                Swal.fire({
                    title: 'Informasi Data API Whatsapp',
                    icon: 'info',
                    html: `
                        <div class="text-left">
                            <p>Data API ini berisikan daftar Token API yang sudah terdaftar pada Fonnte.com dan sudah di connect-kan dengan nomor yang terdaftar.</p>
                            <p>Panduan penggunaan:</p>
                            <ul>
                                <li>Copy token dari akun Fonnte.com Anda</li>
                                <li>Paste token tersebut ke dalam form Tambah Data</li>
                                <li>Isi nama owner Whatsapp dan informasi lainnya</li>
                                <li><strong>Quota menunjukkan jumlah pesan tersisa yang dapat dikirim</strong></li>
                                <li>Akun berbayar/subscribe dapat mengirim hingga 10.000 pesan/bulan</li>
                                <li>Akun free hanya dapat mengirim hingga 1.000 pesan saja</li>
                                <li>Status menunjukkan apakah token API aktif atau tidak</li>
                                <li>Hanya satu token yang dapat aktif pada satu waktu</li>
                                <li><strong>Quota akan otomatis berkurang setiap pesan terkirim</strong></li>
                                <li><strong>Gunakan tombol "Top Up" untuk menambah quota</strong></li>
                            </ul>
                            <p>Untuk informasi lebih lanjut, silakan hubungi Operator.</p>
                        </div>
                    `,
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#3085d6',
                });
            });

            // Activate handler
            $('#dataApiWhatsapp-table').on('click', '.btn-activate', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const quota = $(this).data('quota');
                const btn = $(this);

                Swal.fire({
                    title: 'Aktivasi API WhatsApp',
                    html: `Aktifkan API <strong>${name}</strong>?<br>
                           <small class="text-muted">Quota tersedia: ${quota.toLocaleString()}</small>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Aktifkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        activateApi(id, btn);
                    }
                });
            });

            // Top Up handler
            $('#dataApiWhatsapp-table').on('click', '.btn-topup', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const number = $(this).data('number');
                const quota = $(this).data('quota');

                showTopUpModal(id, name, number, quota);
            });

            // Delete handler
            $('#dataApiWhatsapp-table').on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                const name = $(this).data('name');

                Swal.fire({
                    title: `Apakah anda yakin ingin menghapus\n${name}?`,
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: form.attr('action'),
                            method: 'DELETE',
                            data: form.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if(response.success) {
                                    table.ajax.reload();
                                    loadSummaryData();
                                    Swal.fire(
                                        'Terhapus!',
                                        response.message,
                                        'success'
                                    );
                                }
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'Terjadi kesalahan saat menghapus data.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });

        // Function untuk aktivasi API
        function activateApi(id, btn) {
            // Add spinner to button
            const originalText = btn.html();
            btn.html('<i class="fas fa-spinner fa-spin"></i>');
            btn.prop('disabled', true);

            $.ajax({
                url: `/api-whatsapp/${id}/toggle-active`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.success) {
                        // Reload table data
                        table.ajax.reload();
                        loadSummaryData();

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            timer: 3000,
                            showConfirmButton: false
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan saat mengubah status',
                    });
                },
                complete: function() {
                    // Reset button
                    btn.html(originalText);
                    btn.prop('disabled', false);
                }
            });
        }

        // Function untuk menampilkan modal top up
        function showTopUpModal(id, name, number, currentQuota) {
            Swal.fire({
                title: 'Top Up Quota',
                html: `
                    <div class="text-left">
                        <div class="form-group mb-3">
                            <label class="form-label">Nama API:</label>
                            <input type="text" class="form-control" value="${name}" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Nomor WhatsApp:</label>
                            <input type="text" class="form-control" value="${number}" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Quota Saat Ini:</label>
                            <input type="text" class="form-control" value="${currentQuota.toLocaleString()}" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label for="additionalQuota" class="form-label">Tambah Quota:</label>
                            <input type="number" class="form-control" id="additionalQuota" min="1" max="50000" placeholder="Masukkan jumlah quota">
                            <div class="form-text">Minimum: 1, Maximum: 50,000</div>
                        </div>
                    </div>
                `,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#17a2b8',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-plus"></i> Top Up',
                cancelButtonText: 'Batal',
                preConfirm: () => {
                    const additionalQuota = document.getElementById('additionalQuota').value;
                    if (!additionalQuota || additionalQuota < 1) {
                        Swal.showValidationMessage('Masukkan jumlah quota yang valid');
                        return false;
                    }
                    if (additionalQuota > 50000) {
                        Swal.showValidationMessage('Maksimal top up adalah 50,000');
                        return false;
                    }
                    return additionalQuota;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    executeTopUp(id, result.value);
                }
            });
        }

        // Function untuk eksekusi top up
        function executeTopUp(id, additionalQuota) {
            $.ajax({
                url: `/api-whatsapp/${id}/top-up`,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    additional_quota: additionalQuota
                },
                success: function(response) {
                    if (response.success) {
                        let message = response.message;
                        if (response.data.auto_activated) {
                            message += '<br><small class="text-success">API telah diaktifkan otomatis.</small>';
                        }

                        Swal.fire({
                            title: 'Top Up Berhasil!',
                            html: message,
                            icon: 'success',
                            timer: 4000,
                            showConfirmButton: false
                        });

                        // Reload table dan summary
                        table.ajax.reload();
                        loadSummaryData();
                    } else {
                        Swal.fire({
                            title: 'Top Up Gagal!',
                            text: response.message,
                            icon: 'error'
                        });
                    }
                },
                error: function(xhr) {
                    let message = 'Terjadi kesalahan saat top up';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        title: 'Error!',
                        text: message,
                        icon: 'error'
                    });
                }
            });
        }

        // Function untuk load summary data
        function loadSummaryData() {
            $.ajax({
                url: '/api-whatsapp/summary',
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
            $('#totalApisCount').text(data.total_apis);
            $('#activeApisCount').text(data.active_apis);
            $('#totalQuotaCount').text(data.total_quota.toLocaleString());
            $('#activeQuotaCount').text(data.active_quota.toLocaleString());

            // Update status summary
            let statusText = `Total: ${data.total_apis} APIs | Active: ${data.active_apis} | Total Quota: ${data.total_quota.toLocaleString()}`;
            $('#apiStatusSummary').text(statusText);

            // Show warning jika ada quota rendah
            if (data.low_quota_apis > 0 || data.zero_quota_apis > 0) {
                showQuotaWarning(data);
            }
        }

        // Function untuk menampilkan warning quota
        function showQuotaWarning(data) {
            let warningMessage = '';
            let alertClass = 'quota-warning-alert';

            if (data.zero_quota_apis > 0) {
                warningMessage += `⚠️ ${data.zero_quota_apis} API tanpa quota. `;
                alertClass = 'quota-critical-alert';
            }
            if (data.low_quota_apis > 0) {
                warningMessage += `⚠️ ${data.low_quota_apis} API quota rendah.`;
            }

            if (warningMessage && !$('#quotaWarning').length) {
                let alertHtml = `
                    <div id="quotaWarning" class="alert ${alertClass} alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        ${warningMessage}
                        <button type="button" class="btn btn-warning btn-sm ml-2" onclick="autoSwitchToMaxQuota()">
                            <i class="fas fa-random"></i> Auto Switch
                        </button>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
                $('.section-header').after(alertHtml);
            }
        }

        // Function untuk auto switch ke quota terbanyak
        function autoSwitchToMaxQuota() {
            Swal.fire({
                title: 'Auto Switch API',
                text: 'Switch ke API dengan quota terbanyak?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6f42c1',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Switch!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/api-whatsapp/auto-switch-max',
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: 'Switch Berhasil!',
                                    text: response.message,
                                    icon: 'success',
                                    timer: 3000,
                                    showConfirmButton: false
                                });

                                // Reload table dan summary
                                table.ajax.reload();
                                loadSummaryData();

                                // Remove warning
                                $('#quotaWarning').remove();
                            } else {
                                Swal.fire({
                                    title: 'Switch Gagal!',
                                    text: response.message,
                                    icon: 'error'
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat auto switch',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        }

        // Function untuk refresh data manual
        function refreshApiData() {
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

        function createLoket() {
            window.location.href = "{{ route('ApiWhatsapp.create') }}";
        }
    </script>


    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
