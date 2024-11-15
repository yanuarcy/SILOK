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
                <h1>Data Loket</h1>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between w-100">
                                <h4>Data Loket</h4>
                                <button class="btn btn-primary" onclick="createLoket()">
                                    <i class="fas fa-plus"></i> Tambah Loket
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="lokets-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Front Office</th>
                                            <th>Loket</th>
                                            <th>Status</th>
                                            <th>Status Pemanggilan</th>
                                            <th>Status Aktif</th>
                                            <th>Terakhir Aktif</th>
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
            const table = $('#lokets-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('lokets.data') }}",
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
                    { data: 'user.name', name: 'user.name' },
                    { data: 'loket_number', name: 'loket_number' },
                    { data: 'status', name: 'status' },
                    { data: 'call_status', name: 'call_status' },
                    { data: 'is_active', name: 'is_active' },
                    { data: 'last_activity', name: 'last_activity' },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ],
                columnDefs: [
                    { className: 'text-center', targets: [0, 2, 3, 4, 5, 7] }
                ],
                order: [[2, 'asc']],  // Sort by loket_number
                language: {
                    // processing: '<i class="fas fa-spinner fa-spin fa-2x fa-fw"></i>',
                    searchPlaceholder: "Cari data...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    emptyTable: `
                        <div class="text-center my-5">
                            <div class="mb-3">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                            </div>
                            <h6 class="fw-semibold text-muted">Belum ada Data</h6>
                            <p class="text-muted small">Data loket akan muncul di sini</p>
                        </div>
                    `,
                    zeroRecords: `
                        <div class="text-center my-5">
                            <div class="mb-3">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                            </div>
                            <h6 class="fw-semibold text-muted">Belum ada Data</h6>
                            <p class="text-muted small">Data loket akan muncul di sini</p>
                        </div>
                    `,
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    // paginate: {
                    //     first: '<i class="fas fa-angle-double-left"></i>',
                    //     last: '<i class="fas fa-angle-double-right"></i>',
                    //     previous: '<i class="fas fa-angle-left"></i>',
                    //     next: '<i class="fas fa-angle-right"></i>'
                    // }
                    paginate: {
                        first: 'First',
                        last: 'Last',
                        next: 'Next',
                        previous: 'Previous'
                    },
                }
            });

            // Delete handler
            $('#lokets-table    ').on('click', '.btn-delete', function(e) {
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
                    title: `Are you sure want to delete\n${name}?`,
                    text: "You won't be able to revert this!",
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
                            success: function() {
                                table.ajax.reload();
                                swalWithBootstrapButtons.fire(
                                    'Deleted!',
                                    'Data loket has been deleted.',
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

        function createLoket() {
            window.location.href = "{{ route('lokets.create') }}";
        }
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
