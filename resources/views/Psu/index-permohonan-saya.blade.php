{{-- resources/views/Psu/index-permohonan-saya.blade.php --}}
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
        }

        .summary-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .summary-card.total { border-left-color: #007bff; }
        .summary-card.auto-approved { border-left-color: #17a2b8; }
        .summary-card.approved { border-left-color: #28a745; }
        .summary-card.pending { border-left-color: #ffc107; }
        .summary-card.rejected { border-left-color: #dc3545; }

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
                <h1>Permohonan PSU Saya</h1>
            </div>

            {{-- Summary Cards --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card summary-card total border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-file-alt fa-2x text-primary mb-2"></i>
                            <h6 class="card-title text-muted">Total Pengajuan</h6>
                            <h4 class="text-primary mb-0" id="totalPengajuanCount">-</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card summary-card auto-approved border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-check-double fa-2x text-info mb-2"></i>
                            <h6 class="card-title text-muted">Auto Approved</h6>
                            <h4 class="text-info mb-0" id="autoApprovedCount">-</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card summary-card pending border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                            <h6 class="card-title text-muted">Sedang Proses</h6>
                            <h4 class="text-warning mb-0" id="sedangProsesCount">-</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card summary-card approved border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                            <h6 class="card-title text-muted">Selesai</h6>
                            <h4 class="text-success mb-0" id="selesaiCount">-</h4>
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
                                    <h4>Data PSU (Permohonan Surat Umum)</h4>
                                    <small class="text-muted" id="psuStatusSummary">Loading...</small>
                                </div>
                                <div>
                                    <button class="btn btn-outline-primary btn-sm mr-1" onclick="refreshPsuData()" title="Refresh Data">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                    @if(in_array($userRole, ['user', 'Ketua RT', 'Ketua RW']))
                                        <a href="{{ route('psu.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Tambah PSU
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="dataPsu-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>File Info</th>
                                            <th>Nomor & Judul</th>
                                            <th>Target/Pemohon</th>
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

    <!-- Include Modal Components -->
    @include('Psu.partials.modal')
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
            table = $('#dataPsu-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('psu.getData') }}",
                    data: function(d) {
                        d.view_type = 'permohonan_saya';
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
                    { data: 'nomor_judul', name: 'nomor_surat' },
                    { data: 'nama_lengkap', name: 'nama_lengkap', orderable: false },
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
                    searchPlaceholder: "Cari data...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    emptyTable: `
                        <div class="text-center my-5">
                            <div class="mb-3">
                                <i class="fas fa-file-signature fs-1 text-muted"></i>
                            </div>
                            <h6 class="fw-semibold text-muted">Belum ada Data</h6>
                            <p class="text-muted small">Data PSU akan muncul di sini</p>
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
            $('#dataPsu-table').on('click', '.btn-delete', function(e) {
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
                                    xhr.responseJSON?.message || 'Terjadi kesalahan saat menghapus data.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });

        // Function untuk load summary data
        function loadSummaryData() {
            $.ajax({
                url: "{{ route('psu.getSummary') }}",
                type: 'GET',
                data: { view_type: 'permohonan_saya' },
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
            $('#totalPengajuanCount').text(data.total_pengajuan || 0);
            $('#autoApprovedCount').text(data.auto_approved || 0);
            $('#sedangProsesCount').text(data.sedang_proses || 0);
            $('#selesaiCount').text(data.selesai || 0);

            // Update status summary
            let statusText = `Total: ${data.total_pengajuan || 0} | Auto: ${data.auto_approved || 0} | Proses: ${data.sedang_proses || 0} | Selesai: ${data.selesai || 0}`;
            $('#psuStatusSummary').text(statusText);
        }

        // Function untuk refresh data manual
        function refreshPsuData() {
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
    </script>

    <!-- Include SweetAlert2 for notifications -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
