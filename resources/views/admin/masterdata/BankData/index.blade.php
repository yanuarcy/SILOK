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
            align-items: center;
            gap: 10px;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .dataTables_wrapper {
                padding: 0 10px;
            }

            .table-responsive {
                font-size: 0.875rem;
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
                <h1>Data Bank Data</h1>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            @auth
                                <div class="d-flex justify-content-between w-100">
                                    <h4>Data Bank Data {{ auth()->user()->role === 'admin' ? 'Semua' : (auth()->user()->role === 'Operator' ? 'Kelurahan' : (auth()->user()->role === 'ketua RW' ? 'RW ' . auth()->user()->nomor_rw : 'RT ' . auth()->user()->nomor_rt)) }}</h4>
                                    <button class="btn btn-primary" onclick="createBankData()">
                                        <i class="fas fa-plus"></i> Tambah Bank Data
                                    </button>
                                </div>
                            @endauth
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="bankdata-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>File Info</th>
                                            <th>Kegiatan & Deskripsi</th>
                                            <th>Jenis</th>
                                            <th>Wilayah</th>
                                            <th>Tanggal</th>
                                            <th>Status</th>
                                            <th>Views</th>
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
@endsection

@push('scripts')
    <!-- General JS Scripts -->
    <script src="{{ asset('library/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('library/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            const table = $('#bankdata-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.masterdata.BankData.data') }}",
                    error: function(xhr, error, thrown) {
                        console.log('DataTables error:', {xhr, error, thrown});
                        console.log('Response:', xhr.responseJSON);
                    }
                },
                columns: [
                    {
                        data: null,
                        className: 'text-center',
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'file_info',
                        name: 'files_foto',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kegiatan_info',
                        name: 'judul_kegiatan',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'jenis_badge',
                        name: 'jenis_bank_data',
                        className: 'text-center'
                    },
                    {
                        data: 'wilayah',
                        name: 'nomor_rw',
                        className: 'text-center'
                    },
                    {
                        data: 'tanggal_kegiatan',
                        name: 'tanggal_kegiatan',
                        className: 'text-center',
                        render: function(data, type, row) {
                            if (data) {
                                const date = new Date(data);
                                return date.toLocaleDateString('id-ID');
                            }
                            return '-';
                        }
                    },
                    {
                        data: 'status_info',
                        name: 'status',
                        className: 'text-center'
                    },
                    {
                        data: 'view_info',
                        name: 'view_count',
                        orderable: true,
                        searchable: false,
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
                order: [[5, 'desc']], // Sort by tanggal_kegiatan desc
                language: {
                    searchPlaceholder: "Cari bank data...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    emptyTable: `
                        <div class="text-center my-5">
                            <div class="mb-3">
                                <i class="fas fa-database fs-1 text-muted"></i>
                            </div>
                            <h6 class="fw-semibold text-muted">Belum ada Data Bank Data</h6>
                            <p class="text-muted small">Data bank data akan muncul di sini</p>
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

            // Delete handler
            $('#bankdata-table').on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                const name = $(this).data('name');

                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: `Are you sure want to delete`,
                    text: `${name}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
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
                                table.ajax.reload();
                                swalWithBootstrapButtons.fire(
                                    'Deleted!',
                                    'Data bank data has been deleted.',
                                    'success'
                                );
                            },
                            error: function(xhr) {
                                swalWithBootstrapButtons.fire(
                                    'Error!',
                                    'Something went wrong while deleting.',
                                    'error'
                                );
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire(
                            'Cancelled',
                            'Your data is safe :)',
                            'error'
                        );
                    }
                });
            });
        });

        function createBankData() {
            window.location.href = "{{ route('admin.masterdata.BankData.create') }}";
        }
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
