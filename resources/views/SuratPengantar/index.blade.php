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
                <h1>{{ $pageTitle }}</h1>
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
                    <div class="card summary-card approved border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                            <h6 class="card-title text-muted">Disetujui</h6>
                            <h4 class="text-success mb-0" id="approvedPengajuanCount">-</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card summary-card rejected border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
                            <h6 class="card-title text-muted">Ditolak</h6>
                            <h4 class="text-danger mb-0" id="rejectedPengajuanCount">-</h4>
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
                                    <h4>Data Surat Pengantar/Keterangan</h4>
                                    <small class="text-muted" id="suratPengantarStatusSummary">Loading...</small>
                                </div>
                                <div>
                                    <button class="btn btn-outline-primary btn-sm mr-1" onclick="refreshSuratPengantarData()" title="Refresh Data">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                    @if(Auth::user()->role === 'user' || Auth::user()->role === 'admin')
                                    <a href="{{ route('surat-pengantar.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Tambah Surat Pengantar
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="dataSuratPengantar-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>File Info</th>
                                            <th>Nomor & Judul</th>
                                            <th>Nama Pemohon</th>
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

    <!-- Modal for View Detail -->
    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">Detail Surat Pengantar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="viewModalBody">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Approve RT -->
    <div class="modal fade" id="approveRTModal" tabindex="-1" role="dialog" aria-labelledby="approveRTModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveRTModalLabel">Setujui Surat Pengantar - RT</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="approveRTForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanda Tangan Digital RT: <span class="text-danger">*</span></label>
                                    <div id="ttd-rt-preview" class="signature-display" style="min-height: 200px; border: 2px solid #dee2e6; border-radius: 8px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                        <span class="text-muted">Memuat TTD RT...</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Stempel RT: <span class="text-danger">*</span></label>
                                    <div id="stempel-rt-preview" class="signature-display" style="min-height: 200px; border: 2px solid #dee2e6; border-radius: 8px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                        <span class="text-muted">Memuat Stempel RT...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Catatan (Opsional):</label>
                            <textarea class="form-control" name="catatan_rt" rows="3" placeholder="Catatan persetujuan RT..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Setujui sebagai RT
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Approve RW -->
    <div class="modal fade" id="approveRWModal" tabindex="-1" role="dialog" aria-labelledby="approveRWModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveRWModalLabel">Setujui Surat Pengantar - RW</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="approveRWForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanda Tangan Digital RW: <span class="text-danger">*</span></label>
                                    <div id="ttd-rw-preview" class="signature-display" style="min-height: 200px; border: 2px solid #dee2e6; border-radius: 8px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                        <span class="text-muted">Memuat TTD RW...</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Stempel RW: <span class="text-danger">*</span></label>
                                    <div id="stempel-rw-preview" class="signature-display" style="min-height: 200px; border: 2px solid #dee2e6; border-radius: 8px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                        <span class="text-muted">Memuat Stempel RW...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Catatan (Opsional):</label>
                            <textarea class="form-control" name="catatan_rw" rows="3" placeholder="Catatan persetujuan RW..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Setujui sebagai RW
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Reject RT -->
    <div class="modal fade" id="rejectRTModal" tabindex="-1" role="dialog" aria-labelledby="rejectRTModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectRTModalLabel">Tolak Surat Pengantar - RT</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="rejectRTForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Alasan Penolakan RT: <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="catatan_rt" rows="4" placeholder="Masukkan alasan penolakan sebagai RT..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times"></i> Tolak sebagai RT
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Reject RW -->
    <div class="modal fade" id="rejectRWModal" tabindex="-1" role="dialog" aria-labelledby="rejectRWModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectRWModalLabel">Tolak Surat Pengantar - RW</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="rejectRWForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Alasan Penolakan RW: <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="catatan_rw" rows="4" placeholder="Masukkan alasan penolakan sebagai RW..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times"></i> Tolak sebagai RW
                        </button>
                    </div>
                </form>
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
        let rtSpecimenData = null;
        let rwSpecimenData = null;

        $(document).ready(function() {
            // Initialize DataTable
            table = $('#dataSuratPengantar-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('surat-pengantar.getData') }}",
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
                                <i class="fas fa-file-alt fs-1 text-muted"></i>
                            </div>
                            <h6 class="fw-semibold text-muted">Belum ada Data</h6>
                            <p class="text-muted small">Data Surat Pengantar akan muncul di sini</p>
                        </div>
                    `,
                    zeroRecords: `
                        <div class="text-center my-5">
                            <div class="mb-3">
                                <i class="fas fa-file-alt fs-1 text-muted"></i>
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

            // View handler
            $('#dataSuratPengantar-table').on('click', '.btn-view', function() {
                const id = $(this).data('id');
                viewDetail(id);
            });

            // RT Approve handler
            $('#dataSuratPengantar-table').on('click', '.btn-approve-rt', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                $('#approveRTModalLabel').text('Setujui Surat Pengantar - RT: ' + name);
                $('#approveRTForm').data('id', id);
                $('#approveRTModal').modal('show');

                // Clear form and load RT spesimen
                $('#approveRTForm')[0].reset();
                loadRTSpesimen(id);
            });

            // RT Reject handler
            $('#dataSuratPengantar-table').on('click', '.btn-reject-rt', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                $('#rejectRTModalLabel').text('Tolak Surat Pengantar - RT: ' + name);
                $('#rejectRTForm').data('id', id);
                $('#rejectRTModal').modal('show');

                // Clear form
                $('#rejectRTForm')[0].reset();
            });

            // RW Approve handler
            $('#dataSuratPengantar-table').on('click', '.btn-approve-rw', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                $('#approveRWModalLabel').text('Setujui Surat Pengantar - RW: ' + name);
                $('#approveRWForm').data('id', id);
                $('#approveRWModal').modal('show');

                // Clear form and load RW spesimen
                $('#approveRWForm')[0].reset();
                loadRWSpesimen(id);
            });

            // RW Reject handler
            $('#dataSuratPengantar-table').on('click', '.btn-reject-rw', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                $('#rejectRWModalLabel').text('Tolak Surat Pengantar - RW: ' + name);
                $('#rejectRWForm').data('id', id);
                $('#rejectRWModal').modal('show');

                // Clear form
                $('#rejectRWForm')[0].reset();
            });

            // Delete handler
            $('#dataSuratPengantar-table').on('click', '.btn-delete', function(e) {
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

            // RT Approve form submit
            $('#approveRTForm').on('submit', function(e) {
                e.preventDefault();

                const id = $(this).data('id');

                if (!rtSpecimenData || !rtSpecimenData.ttd_rt || !rtSpecimenData.stempel_rt) {
                    Swal.fire('Error', 'Data spesimen TTD/Stempel RT tidak lengkap. Silakan hubungi admin.', 'error');
                    return;
                }

                const formData = new FormData(this);
                formData.append('ttd_rt_url', rtSpecimenData.ttd_rt);
                formData.append('stempel_rt_url', rtSpecimenData.stempel_rt);

                submitApproval(`/surat-pengantar/${id}/approve-rt`, formData, '#approveRTModal');
            });

            // RT Reject form submit
            $('#rejectRTForm').on('submit', function(e) {
                e.preventDefault();

                const id = $(this).data('id');
                const formData = new FormData(this);

                submitApproval(`/surat-pengantar/${id}/reject-rt`, formData, '#rejectRTModal');
            });

            // RW Approve form submit
            $('#approveRWForm').on('submit', function(e) {
                e.preventDefault();

                const id = $(this).data('id');

                if (!rwSpecimenData || !rwSpecimenData.ttd_rw || !rwSpecimenData.stempel_rw) {
                    Swal.fire('Error', 'Data spesimen TTD/Stempel RW tidak lengkap. Silakan hubungi admin.', 'error');
                    return;
                }

                const formData = new FormData(this);
                formData.append('ttd_rw_url', rwSpecimenData.ttd_rw);
                formData.append('stempel_rw_url', rwSpecimenData.stempel_rw);

                submitApproval(`/surat-pengantar/${id}/approve-rw`, formData, '#approveRWModal');
            });

            // RW Reject form submit
            $('#rejectRWForm').on('submit', function(e) {
                e.preventDefault();

                const id = $(this).data('id');
                const formData = new FormData(this);

                submitApproval(`/surat-pengantar/${id}/reject-rw`, formData, '#rejectRWModal');
            });
        });

        // Load RT Spesimen Data
        function loadRTSpesimen(id) {
            console.log('🔄 Loading RT Spesimen for ID:', id);

            $.ajax({
                url: `/surat-pengantar/${id}/get-rt-spesimen`,
                method: 'GET',
                timeout: 10000,
                success: function(response) {
                    console.log('✅ RT Spesimen loaded successfully:', response);

                    if (response.success) {
                        const data = response.data;
                        rtSpecimenData = data;

                        // Update TTD preview
                        if (data.ttd_rt) {
                            $('#ttd-rt-preview').html(`
                                <img src="${data.ttd_rt}" alt="TTD RT" style="max-width: 100%; max-height: 180px;">
                                <div class="mt-2 text-center">
                                    <small class="text-muted">${data.nama_pejabat || 'Ketua RT'}</small>
                                </div>
                            `);
                        } else {
                            $('#ttd-rt-preview').html(`
                                <div class="text-warning text-center">
                                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                    <br>TTD RT belum diupload
                                </div>
                            `);
                        }

                        // Update Stempel preview
                        if (data.stempel_rt) {
                            $('#stempel-rt-preview').html(`
                                <img src="${data.stempel_rt}" alt="Stempel RT" style="max-width: 100%; max-height: 180px;">
                                <div class="mt-2 text-center">
                                    <small class="text-muted">Stempel RT</small>
                                </div>
                            `);
                        } else {
                            $('#stempel-rt-preview').html(`
                                <div class="text-warning text-center">
                                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                    <br>Stempel RT belum diupload
                                </div>
                            `);
                        }

                        // Show warning if either is missing
                        if (!data.ttd_rt || !data.stempel_rt) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Spesimen Tidak Lengkap',
                                text: 'TTD atau Stempel RT belum diupload. Silakan hubungi admin.',
                                confirmButtonText: 'OK'
                            });
                        }
                    } else {
                        handleSpesimenError('RT', response.message);
                    }
                },
                error: function(xhr) {
                    console.error('❌ Error loading RT Spesimen:', xhr);
                    handleSpesimenError('RT', 'Error memuat spesimen RT');

                    if (xhr.status === 404) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Data Spesimen Tidak Ditemukan',
                            text: 'Data spesimen TTD/Stempel RT tidak ditemukan. Silakan hubungi admin.',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        }

        // Load RW Spesimen Data
        function loadRWSpesimen(id) {
            console.log('🔄 Loading RW Spesimen for ID:', id);

            $.ajax({
                url: `/surat-pengantar/${id}/get-rw-spesimen`,
                method: 'GET',
                timeout: 10000,
                success: function(response) {
                    console.log('✅ RW Spesimen loaded successfully:', response);

                    if (response.success) {
                        const data = response.data;
                        rwSpecimenData = data;

                        // Update TTD preview
                        if (data.ttd_rw) {
                            $('#ttd-rw-preview').html(`
                                <img src="${data.ttd_rw}" alt="TTD RW" style="max-width: 100%; max-height: 180px;">
                                <div class="mt-2 text-center">
                                    <small class="text-muted">${data.nama_pejabat || 'Ketua RW'}</small>
                                </div>
                            `);
                        } else {
                            $('#ttd-rw-preview').html(`
                                <div class="text-warning text-center">
                                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                    <br>TTD RW belum diupload
                                </div>
                            `);
                        }

                        // Update Stempel preview
                        if (data.stempel_rw) {
                            $('#stempel-rw-preview').html(`
                                <img src="${data.stempel_rw}" alt="Stempel RW" style="max-width: 100%; max-height: 180px;">
                                <div class="mt-2 text-center">
                                    <small class="text-muted">Stempel RW</small>
                                </div>
                            `);
                        } else {
                            $('#stempel-rw-preview').html(`
                                <div class="text-warning text-center">
                                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                    <br>Stempel RW belum diupload
                                </div>
                            `);
                        }

                        // Show warning if either is missing
                        if (!data.ttd_rw || !data.stempel_rw) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Spesimen Tidak Lengkap',
                                text: 'TTD atau Stempel RW belum diupload. Silakan hubungi admin.',
                                confirmButtonText: 'OK'
                            });
                        }
                    } else {
                        handleSpesimenError('RW', response.message);
                    }
                },
                error: function(xhr) {
                    console.error('❌ Error loading RW Spesimen:', xhr);
                    handleSpesimenError('RW', 'Error memuat spesimen RW');

                    if (xhr.status === 404) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Data Spesimen Tidak Ditemukan',
                            text: 'Data spesimen TTD/Stempel RW tidak ditemukan. Silakan hubungi admin.',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        }

        // Handle spesimen error
        function handleSpesimenError(type, message) {
            const ttdId = type === 'RT' ? '#ttd-rt-preview' : '#ttd-rw-preview';
            const stempelId = type === 'RT' ? '#stempel-rt-preview' : '#stempel-rw-preview';

            $(ttdId).html(`
                <div class="text-danger text-center">
                    <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                    <br><small>${message}</small>
                </div>
            `);

            $(stempelId).html(`
                <div class="text-danger text-center">
                    <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                    <br><small>${message}</small>
                </div>
            `);
        }

        // Function untuk submit approval/rejection
        function submitApproval(url, formData, modalSelector) {
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.success) {
                        $(modalSelector).modal('hide');
                        table.ajax.reload();
                        loadSummaryData();
                        Swal.fire('Berhasil', response.message, 'success');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
                }
            });
        }

        // Function untuk load summary data
        function loadSummaryData() {
            $.ajax({
                url: "{{ route('surat-pengantar.getSummary') }}",
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
            $('#approvedPengajuanCount').text(data.approved_pengajuan);
            $('#rejectedPengajuanCount').text(data.rejected_pengajuan);

            // Update status summary
            let statusText = `Total: ${data.total_pengajuan} | RT: ${data.pending_rt} | RW: ${data.pending_rw} | Disetujui: ${data.approved_pengajuan} | Ditolak: ${data.rejected_pengajuan}`;
            $('#suratPengantarStatusSummary').text(statusText);
        }

        // Function untuk refresh data manual
        function refreshSuratPengantarData() {
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

        // Function untuk view detail
        function viewDetail(id) {
            $.ajax({
                url: `/surat-pengantar/${id}`,
                method: 'GET',
                success: function(response) {
                    $('#viewModalBody').html(response);
                    $('#viewModal').modal('show');
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Gagal memuat detail data', 'error');
                }
            });
        }
    </script>

    <!-- Include SweetAlert2 for notifications -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
