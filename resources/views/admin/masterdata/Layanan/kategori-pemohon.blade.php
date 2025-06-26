@extends('Template.template')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet"
        href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">

    <link rel="stylesheet"
        href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">

    <link rel="stylesheet"
        href="{{ asset('library/owl.carousel/dist/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('library/owl.carousel/dist/assets/owl.theme.default.min.css') }}">

    <link rel="stylesheet"
        href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">

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
                <h1>Data Kategori Pemohon</h1>
                @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'Operator'))
                    <div class="section-header-button">
                        <a href="{{ route('applicant-types.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Kategori
                        </a>
                    </div>
                @endif
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="applicant-type-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Type</th>
                                            <th>Title</th>
                                            <th>Image</th>
                                            <th>Created At</th>
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
    <script src="{{ asset('library/popper.js/dist/umd/popper.js') }}"></script>
    <script src="{{ asset('library/tooltip.js/dist/umd/tooltip.js') }}"></script>
    <script src="{{ asset('library/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('library/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('js/stisla.js') }}"></script>

    <!-- JS Libraies -->
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.indonesia.js') }}"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>


    <script src="{{ asset('library/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.js') }}"></script>
    <script src="{{ asset('library/owl.carousel/dist/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/index-0.js') }}"></script>
    {{-- <script src="{{ asset('js/page/index.js') }}"></script> --}}
    <script src="{{ asset('js/page/modules-chartjs.js') }}"></script>

    <script>
        $(document).ready(function() {
            const isAuthorized = {{ auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'Operator') ? 'true' : 'false' }};

            const table = $('#applicant-type-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('applicant-types.data') }}",
                    type: 'GET',
                    error: function (xhr, error, thrown) {
                        console.log('DataTables error:', error);
                        console.log('XHR:', xhr);
                        console.log('Thrown:', thrown);
                        console.log('Response:', xhr.responseText);

                        // Show error in the table
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            $('#registration-table tbody').html(`
                                <tr>
                                    <td colspan="6" class="text-center text-danger">
                                        Error: ${xhr.responseJSON.error}
                                    </td>
                                </tr>
                            `);
                        }
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'type', name: 'type'},
                    {data: 'title', name: 'title'},
                    {data: 'image', name: 'image', orderable: false, searchable: false},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false, visible: isAuthorized}
                ],
                order: [[0, 'asc']],
                responsive: true,
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
                    emptyTable: `
                        <div class="text-center my-5">
                            <div class="mb-3">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                            </div>
                            <h6 class="fw-semibold text-muted">Belum ada Data</h6>
                            <p class="text-muted small">Data kategori pemohon akan muncul di sini</p>
                        </div>
                    `,
                    zeroRecords: `
                        <div class="text-center my-5">
                            <div class="mb-3">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                            </div>
                            <h6 class="fw-semibold text-muted">Belum ada Data</h6>
                            <p class="text-muted small">Data kategori pemohon akan muncul di sini</p>
                        </div>
                    `,
                    paginate: {
                        first: 'First',
                        last: 'Last',
                        next: 'Next',
                        previous: 'Previous'
                    },
                    infoEmpty: '',
                    lengthMenu: '_MENU_ entries per page',
                    search: 'Search:'
                }
            });

            // Delete handler
            $('#applicant-type-table').on('click', '.btn-delete', function(e) {
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
                                    'Layanan has been deleted.',
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
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
