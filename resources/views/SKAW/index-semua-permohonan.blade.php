{{-- resources/views/Skaw/index-semua-permohonan.blade.php --}}
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
        .summary-card.butuh-action { border-left-color: #dc3545; }
        .summary-card.sedang-proses { border-left-color: #ffc107; }
        .summary-card.selesai { border-left-color: #28a745; }

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
                <h1>Semua Permohonan SKAW</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('Dashboard.General') }}">Dashboard</a>
                    </div>
                    <div class="breadcrumb-item">SKAW</div>
                    <div class="breadcrumb-item active">Semua Permohonan</div>
                </div>
            </div>

            {{-- Summary Cards --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card summary-card total border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-file-alt fa-2x text-primary mb-2"></i>
                            <h6 class="card-title text-muted">Total Permohonan</h6>
                            <h4 class="text-primary mb-0" id="totalPermohonanCount">-</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card summary-card butuh-action border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-exclamation-triangle fa-2x text-danger mb-2"></i>
                            <h6 class="card-title text-muted">Butuh Action</h6>
                            <h4 class="text-danger mb-0" id="butuhActionCount">-</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card summary-card sedang-proses border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                            <h6 class="card-title text-muted">Sedang Proses</h6>
                            <h4 class="text-warning mb-0" id="sedangProsesCount">-</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card summary-card selesai border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                            <h6 class="card-title text-muted">Selesai</h6>
                            <h4 class="text-success mb-0" id="selesaiCount">-</h4>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Role-specific Action Panel --}}
            @if($userRole === 'Front Office')
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0"><i class="fas fa-tasks mr-2"></i>Action Panel - Front Office</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Tugas Anda:</strong></p>
                                        <ul class="small">
                                            <li>Review dan approve permohonan SKAW yang masuk</li>
                                            <li>Generate tanda terima dan draft SKAW</li>
                                            <li>Membuat jadwal sidang untuk pemohon</li>
                                            <li>Generate daftar sidang dalam bentuk PDF</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Status yang Perlu Action:</strong></p>
                                        <ul class="small">
                                            <li><span class="badge badge-primary">Submitted</span> - Perlu review dan approval</li>
                                            <li><span class="badge badge-warning">SKAW Generated</span> - Perlu dibuat jadwal sidang</li>
                                        </ul>
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
                        <div class="card border-info">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="fas fa-tasks mr-2"></i>Action Panel - Back Office</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Tugas Anda:</strong></p>
                                        <ul class="small">
                                            <li>Upload evidence foto sidang (setelah hari H sidang)</li>
                                            <li>Generate PDF evidence bukti sidang</li>
                                            <li>Scan dan upload berkas SKAW yang sudah ditandatangani</li>
                                            <li>Upload SKAW final yang sudah lengkap untuk rekapitulasi</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Status yang Perlu Action:</strong></p>
                                        <ul class="small">
                                            <li><span class="badge badge-primary">Jadwal Sidang Created</span> - Tunggu hari H untuk upload evidence</li>
                                            <li><span class="badge badge-success">Camat Approved</span> - Perlu upload SKAW final</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($userRole === 'Lurah')
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="fas fa-user-tie mr-2"></i>Action Panel - Lurah</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Tugas Anda:</strong></p>
                                        <ul class="small">
                                            <li>Review evidence sidang yang sudah diupload Back Office</li>
                                            <li>Approve sebagai tanda tracking bahwa berkas sudah diproses TTD basah</li>
                                            <li>Berikan catatan jika diperlukan</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Status yang Perlu Action:</strong></p>
                                        <ul class="small">
                                            <li><span class="badge badge-warning">Evidence Uploaded</span> - Perlu review dan approval</li>
                                        </ul>
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
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-user-check mr-2"></i>Action Panel - Camat</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Tugas Anda:</strong></p>
                                        <ul class="small">
                                            <li>Final approval setelah Lurah menyetujui</li>
                                            <li>Approve sebagai tanda tracking bahwa berkas sudah diproses TTD basah Camat</li>
                                            <li>Berikan catatan jika diperlukan</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Status yang Perlu Action:</strong></p>
                                        <ul class="small">
                                            <li><span class="badge badge-success">Lurah Approved</span> - Perlu final approval</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between w-100">
                                <div>
                                    <h4>Data SKAW (Surat Keterangan Ahli Waris)</h4>
                                    <small class="text-muted" id="skawStatusSummary">Loading...</small>
                                </div>
                                <div>
                                    <button class="btn btn-outline-primary btn-sm mr-1" onclick="refreshSkawData()" title="Refresh Data">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="dataSkaw-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>File Info</th>
                                            <th>Nomor Surat</th>
                                            <th>Pemohon</th>
                                            <th>Pewaris</th>
                                            <th>Tanggal</th>
                                            <th>Status</th>
                                            <th>Progress</th>
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
            Copyright &copy; 2025 <div class="bullet"></div> SKAW System
        </div>
        <div class="footer-right">
            v1.0.0
        </div>
    </footer>

    {{-- Include Modal Components Based on Role --}}
    @if($userRole === 'Front Office')
        @include('Skaw.modals.front-office-approve')
        @include('Skaw.modals.create-jadwal-sidang')
    @endif

    @if($userRole === 'Back Office')
        @include('Skaw.modals.upload-evidence')
        @include('Skaw.modals.upload-skaw-final')
    @endif

    @if($userRole === 'Lurah')
        @include('Skaw.modals.lurah-approve')
    @endif

    @if($userRole === 'Camat')
        @include('Skaw.modals.camat-approve')
    @endif
@endsection

@push('scripts')
    <!-- General JS Scripts -->
    <script src="{{ asset('library/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('library/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>

    <script>
        let table;
        const userRole = '{{ $userRole }}';

        $(document).ready(function() {
            // Initialize DataTable
            table = $('#dataSkaw-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('skaw.getData') }}",
                    data: function(d) {
                        d.view_type = 'semua_permohonan';
                    },
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
                    { data: 'nomor_surat', name: 'nomor_surat' },
                    { data: 'pemohon_info', name: 'nama_lengkap' },
                    { data: 'pewaris_info', name: 'pewaris_nama_lengkap' },
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
                order: [[5, 'desc']], // Sort by tanggal desc
                language: {
                    searchPlaceholder: "Cari data...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    emptyTable: `
                        <div class="text-center my-5">
                            <div class="mb-3">
                                <i class="fas fa-file-signature fs-1 text-muted"></i>
                            </div>
                            <h6 class="fw-semibold text-muted">Belum ada Data SKAW</h6>
                            <p class="text-muted small">Data SKAW akan muncul di sini setelah ada yang mengajukan</p>
                        </div>
                    `,
                    zeroRecords: `
                        <div class="text-center my-5">
                            <div class="mb-3">
                                <i class="fas fa-file-signature fs-1 text-muted"></i>
                            </div>
                            <h6 class="fw-semibold text-muted">Data tidak ditemukan</h6>
                            <p class="text-muted small">Coba ubah kata kunci pencarian</p>
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

            // Delete handler
            $('#dataSkaw-table').on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                const name = $(this).data('name');

                Swal.fire({
                    title: `Apakah anda yakin ingin menghapus\nSKAW ${name}?`,
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
                                    xhr.responseJSON?.message || 'Terjadi kesalahan saat menghapus data.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            // Front Office Approve Handler
            $('#dataSkaw-table').on('click', '.btn-approve-front-office', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                $('#frontOfficeApproveModal').modal('show');
                $('#frontOfficeApproveForm').data('id', id);
                $('#frontOfficeApproveModal .modal-title').text(`Approve SKAW - ${name}`);
            });

            // Create Jadwal Sidang Handler
            $('#dataSkaw-table').on('click', '.btn-create-jadwal', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                $('#createJadwalSidangModal').modal('show');
                $('#createJadwalSidangForm').data('id', id);
                $('#createJadwalSidangModal .modal-title').text(`Buat Jadwal Sidang - ${name}`);

                // Set default date (tomorrow)
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                $('#tanggalSidang').val(tomorrow.toISOString().split('T')[0]);
            });

            // Upload Evidence Handler
            $('#dataSkaw-table').on('click', '.btn-upload-evidence', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                $('#uploadEvidenceModal').modal('show');
                $('#uploadEvidenceForm').data('id', id);
                $('#uploadEvidenceModal .modal-title').text(`Upload Evidence Sidang - ${name}`);
            });

            // Lurah Approve Handler
            $('#dataSkaw-table').on('click', '.btn-approve-lurah', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                $('#lurahApproveModal').modal('show');
                $('#lurahApproveForm').data('id', id);
                $('#lurahApproveModal .modal-title').text(`Approve Lurah - ${name}`);
            });

            // Camat Approve Handler
            $('#dataSkaw-table').on('click', '.btn-approve-camat', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                $('#camatApproveModal').modal('show');
                $('#camatApproveForm').data('id', id);
                $('#camatApproveModal .modal-title').text(`Approve Camat - ${name}`);
            });

            // Upload SKAW Final Handler
            $('#dataSkaw-table').on('click', '.btn-upload-final', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                $('#uploadSkawFinalModal').modal('show');
                $('#uploadSkawFinalForm').data('id', id);
                $('#uploadSkawFinalModal .modal-title').text(`Upload SKAW Final - ${name}`);
            });
        });

        // Function untuk load summary data
        function loadSummaryData() {
            $.ajax({
                url: "{{ route('skaw.getSummary') }}",
                type: 'GET',
                data: { view_type: 'semua_permohonan' },
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
            $('#totalPermohonanCount').text(data.total_permohonan || 0);
            $('#butuhActionCount').text(data.butuh_action || 0);
            $('#sedangProsesCount').text(data.sedang_proses || 0);
            $('#selesaiCount').text(data.selesai || 0);

            // Update status summary
            let statusText = `Total: ${data.total_permohonan || 0} | Butuh Action: ${data.butuh_action || 0} | Proses: ${data.sedang_proses || 0} | Selesai: ${data.selesai || 0}`;
            $('#skawStatusSummary').text(statusText);
        }

        // Function untuk refresh data manual
        function refreshSkawData() {
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

        // Handle Front Office Approve Form Submit
        $(document).on('submit', '#frontOfficeApproveForm', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const formData = new FormData(this);

            $.ajax({
                url: `skaw/${id}/front-office-approve`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.success) {
                        $('#frontOfficeApproveModal').modal('hide');
                        table.ajax.reload();
                        loadSummaryData();
                        Swal.fire('Success!', response.message, 'success');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error!', xhr.responseJSON?.message || 'Terjadi kesalahan.', 'error');
                }
            });
        });

        // Handle Create Jadwal Sidang Form Submit
        $(document).on('submit', '#createJadwalSidangForm', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const formData = new FormData(this);

            $.ajax({
                url: `skaw/${id}/create-jadwal-sidang`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.success) {
                        $('#createJadwalSidangModal').modal('hide');
                        table.ajax.reload();
                        loadSummaryData();
                        Swal.fire('Success!', response.message, 'success');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error!', xhr.responseJSON?.message || 'Terjadi kesalahan.', 'error');
                }
            });
        });

        // Handle Upload Evidence Form Submit
        $(document).on('submit', '#uploadEvidenceForm', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const formData = new FormData(this);

            $.ajax({
                url: `skaw/${id}/upload-evidence`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.success) {
                        $('#uploadEvidenceModal').modal('hide');
                        table.ajax.reload();
                        loadSummaryData();
                        Swal.fire('Success!', response.message, 'success');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error!', xhr.responseJSON?.message || 'Terjadi kesalahan.', 'error');
                }
            });
        });

        // Handle Lurah Approve Form Submit
        $(document).on('submit', '#lurahApproveForm', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const formData = new FormData(this);

            $.ajax({
                url: `skaw/${id}/lurah-approve`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.success) {
                        $('#lurahApproveModal').modal('hide');
                        table.ajax.reload();
                        loadSummaryData();
                        Swal.fire('Success!', response.message, 'success');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error!', xhr.responseJSON?.message || 'Terjadi kesalahan.', 'error');
                }
            });
        });

        // Handle Camat Approve Form Submit
        $(document).on('submit', '#camatApproveForm', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const formData = new FormData(this);

            $.ajax({
                url: `skaw/${id}/camat-approve`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.success) {
                        $('#camatApproveModal').modal('hide');
                        table.ajax.reload();
                        loadSummaryData();
                        Swal.fire('Success!', response.message, 'success');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error!', xhr.responseJSON?.message || 'Terjadi kesalahan.', 'error');
                }
            });
        });

        // Handle Upload SKAW Final Form Submit
        $(document).on('submit', '#uploadSkawFinalForm', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const formData = new FormData(this);

            $.ajax({
                url: `skaw/${id}/upload-skaw-final`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.success) {
                        $('#uploadSkawFinalModal').modal('hide');
                        table.ajax.reload();
                        loadSummaryData();
                        Swal.fire('Success!', response.message, 'success');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error!', xhr.responseJSON?.message || 'Terjadi kesalahan.', 'error');
                }
            });
        });
    </script>

    <!-- Include SweetAlert2 for notifications -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
