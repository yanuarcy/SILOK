{{-- resources/views/Psu/index-semua-permohonan.blade.php --}}
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
                <h1>Semua Permohonan PSU</h1>
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
                            <h6 class="card-title text-muted">Selesai Diproses</h6>
                            <h4 class="text-success mb-0" id="selesaiDiprosesCount">-</h4>
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

    <!-- Include Modal Components Based on Role -->
    {{-- @if($userRole === 'Ketua RT')
        @include('Psu.modals.approve-rt')
        @include('Psu.modals.reject-rt')
    @endif

    @if($userRole === 'Ketua RW')
        @include('Psu.modals.approve-rw')
        @include('Psu.modals.reject-rw')
    @endif

    @if(in_array($userRole, ['Front Office', 'Lurah', 'Back Office']))
        @include('Psu.modals.receive-kelurahan')
        @include('Psu.modals.process-lurah')
        @include('Psu.modals.process-back-office')
    @endif --}}

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
        // let lurahSpecimenData = null;

        $(document).ready(function() {
            // Initialize DataTable
            table = $('#dataPsu-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('psu.getData') }}",
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

            // RT Approval Handler
            $('#dataPsu-table').on('click', '.btn-approve-rt', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                $('#approveRTModal').modal('show');
                $('#approveRTForm').data('id', id);
                $('#approveRTModal .modal-title').text(`Approve Permohonan - ${name}`);

                // Load RT Spesimen data
                loadRTSpesimen(id);
            });

            // RT Rejection Handler
            $('#dataPsu-table').on('click', '.btn-reject-rt', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                $('#rejectRTModal').modal('show');
                $('#rejectRTForm').data('id', id);
                $('#rejectRTModal .modal-title').text(`Reject Permohonan - ${name}`);
            });

            // RW Approval Handler
            $('#dataPsu-table').on('click', '.btn-approve-rw', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                $('#approveRWModal').modal('show');
                $('#approveRWForm').data('id', id);
                $('#approveRWModal .modal-title').text(`Approve Permohonan - ${name}`);

                // Load RW Spesimen data
                loadRWSpesimen(id);
            });

            // RW Rejection Handler
            $('#dataPsu-table').on('click', '.btn-reject-rw', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                $('#rejectRWModal').modal('show');
                $('#rejectRWForm').data('id', id);
                $('#rejectRWModal .modal-title').text(`Reject Permohonan - ${name}`);
            });

            // Kelurahan Receive Handler
            $('#dataPsu-table').on('click', '.btn-receive-kelurahan', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                console.log('Receive Kelurahan clicked for ID:', id); // Debug log

                // Set modal data SEBELUM show modal
                $('#receiveKelurahanModal').data('psu-id', id);
                $('#receiveKelurahanForm').data('id', id);

                // Load PSU details terlebih dahulu
                loadPsuDetailsForReceive(id);

                loadFrontOfficeSpesimen(id);

                // Kemudian show modal
                $('#receiveKelurahanModal').modal('show');
                $('#receiveKelurahanModal .modal-title').text(`Terima di Kelurahan - ${name}`);
            });

            // Lurah Process Handler
            $('#dataPsu-table').on('click', '.btn-process-lurah', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                console.log('Process Lurah clicked for ID:', id);

                // Set modal data SEBELUM show modal
                $('#processLurahModal').data('psu-id', id);
                $('#processLurahForm').data('id', id);

                // Load PSU details terlebih dahulu
                loadPsuDetailsForLurah(id);

                // Load Lurah spesimen (TTD dan Stempel)
                loadLurahSpesimen(id);

                // Kemudian show modal
                $('#processLurahModal').modal('show');
                $('#processLurahModal .modal-title').text(`Proses Disposisi Lurah - ${name}`);
            });

            // Back Office Approve Handler (Final Step)
            $('#dataPsu-table').on('click', '.btn-approve-back-office', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                console.log('Approve Back Office clicked for ID:', id); // Debug log

                // Set modal data SEBELUM show modal
                $('#approveBackOfficeModal').data('psu-id', id);
                $('#approveBackOfficeForm').data('id', id);

                // Load PSU details terlebih dahulu
                loadPsuDetailsForBackOffice(id);

                // Kemudian show modal
                $('#approveBackOfficeModal').modal('show');
                $('#approveBackOfficeModal .modal-title').text(`Approve Final Back Office - ${name}`);
            });

            // Kelurahan Final Approval Handler (untuk PSU dengan level_akhir = kelurahan)
            $('#dataPsu-table').on('click', '.btn-approve-kelurahan', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                $('#approveKelurahanModal').modal('show');
                $('#approveKelurahanForm').data('id', id);
                $('#approveKelurahanModal .modal-title').text(`Approve Permohonan - ${name}`);

                // Load Kelurahan Spesimen data
                loadKelurahanSpesimen(id);
            });

            // Kelurahan Rejection Handler
            $('#dataPsu-table').on('click', '.btn-reject-kelurahan', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                $('#rejectKelurahanModal').modal('show');
                $('#rejectKelurahanForm').data('id', id);
                $('#rejectKelurahanModal .modal-title').text(`Reject Permohonan - ${name}`);
            });

            function loadPsuDetailsForReceive(psuId) {
                console.log('Loading PSU details for receive, ID:', psuId); // Debug log

                $.ajax({
                    url: `/psu/${psuId}`,
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log('PSU details loaded:', response); // Debug log

                        if (response && response.nomor_surat) {
                            $('#receiveNomorSurat').val(response.nomor_surat);
                            $('#receiveNamaPemohon').val(response.nama_lengkap);
                            $('#receiveHal').val(response.hal);
                        } else {
                            console.error('Invalid response format:', response);
                            Swal.fire('Error', 'Format response tidak valid', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading PSU details:', {xhr, status, error});
                        console.error('Response text:', xhr.responseText);

                        Swal.fire('Error', 'Gagal memuat detail PSU: ' + (xhr.responseJSON?.message || error), 'error');
                    }
                });
            }

            function loadPsuDetailsForLurah(psuId) {
                console.log('Loading PSU details for lurah, ID:', psuId); // Debug log

                $.ajax({
                    url: `/psu/${psuId}`,
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log('PSU details loaded for lurah:', response); // Debug log

                        if (response && response.nomor_surat) {
                            $('#processLurahNomorSurat').val(response.nomor_surat);
                            $('#processLurahNamaPemohon').val(response.nama_lengkap);
                            $('#processLurahHal').val(response.hal);
                        } else {
                            console.error('Invalid response format:', response);
                            Swal.fire('Error', 'Format response tidak valid', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading PSU details for lurah:', {xhr, status, error});
                        Swal.fire('Error', 'Gagal memuat detail PSU: ' + (xhr.responseJSON?.message || error), 'error');
                    }
                });
            }

            function loadPsuDetailsForBackOffice(psuId) {
                console.log('Loading PSU details for back office, ID:', psuId); // Debug log

                $.ajax({
                    url: `/psu/${psuId}`,
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log('PSU details loaded for back office:', response); // Debug log

                        if (response && response.nomor_surat) {
                            $('#backOfficeNomorSurat').val(response.nomor_surat);
                            $('#backOfficeNamaPemohon').val(response.nama_lengkap);
                            $('#backOfficeHal').val(response.hal);
                        } else {
                            console.error('Invalid response format:', response);
                            Swal.fire('Error', 'Format response tidak valid', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading PSU details for back office:', {xhr, status, error});
                        Swal.fire('Error', 'Gagal memuat detail PSU: ' + (xhr.responseJSON?.message || error), 'error');
                    }
                });
            }

            // Function untuk load RT Spesimen
            function loadRTSpesimen(psuId) {
                $.ajax({
                    url: `/psu/${psuId}/get-rt-spesimen`,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success && response.data) {
                            // Show TTD RT
                            if (response.data.ttd_rt) {
                                $('#ttd-rt-preview').html(`
                                    <img src="${response.data.ttd_rt}" class="img-fluid" alt="TTD RT" style="max-height: 180px;">
                                    <input type="hidden" name="ttd_rt_url" value="${response.data.ttd_rt}">
                                `);
                            } else {
                                $('#ttd-rt-preview').html(`
                                    <div class="text-center text-danger">
                                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                        <br>TTD RT tidak ditemukan
                                    </div>
                                `);
                            }

                            // Show Stempel RT
                            if (response.data.stempel_rt) {
                                $('#stempel-rt-preview').html(`
                                    <img src="${response.data.stempel_rt}" class="img-fluid" alt="Stempel RT" style="max-height: 180px;">
                                    <input type="hidden" name="stempel_rt_url" value="${response.data.stempel_rt}">
                                `);
                            } else {
                                $('#stempel-rt-preview').html(`
                                    <div class="text-center text-danger">
                                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                        <br>Stempel RT tidak ditemukan
                                    </div>
                                `);
                            }
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading RT spesimen:', xhr);
                        $('#ttd-rt-preview, #stempel-rt-preview').html(`
                            <div class="text-center text-danger">
                                <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                <br>Error memuat data spesimen
                            </div>
                        `);
                    }
                });
            }

            // Function untuk load RW Spesimen
            function loadRWSpesimen(psuId) {
                $.ajax({
                    url: `/psu/${psuId}/get-rw-spesimen`,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success && response.data) {
                            // Show TTD RW
                            if (response.data.ttd_rw) {
                                $('#ttd-rw-preview').html(`
                                    <img src="${response.data.ttd_rw}" class="img-fluid" alt="TTD RW" style="max-height: 180px;">
                                    <input type="hidden" name="ttd_rw_url" value="${response.data.ttd_rw}">
                                `);
                            } else {
                                $('#ttd-rw-preview').html(`
                                    <div class="text-center text-danger">
                                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                        <br>TTD RW tidak ditemukan
                                    </div>
                                `);
                            }

                            // Show Stempel RW
                            if (response.data.stempel_rw) {
                                $('#stempel-rw-preview').html(`
                                    <img src="${response.data.stempel_rw}" class="img-fluid" alt="Stempel RW" style="max-height: 180px;">
                                    <input type="hidden" name="stempel_rw_url" value="${response.data.stempel_rw}">
                                `);
                            } else {
                                $('#stempel-rw-preview').html(`
                                    <div class="text-center text-danger">
                                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                        <br>Stempel RW tidak ditemukan
                                    </div>
                                `);
                            }
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading RW spesimen:', xhr);
                        $('#ttd-rw-preview, #stempel-rw-preview').html(`
                            <div class="text-center text-danger">
                                <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                <br>Error memuat data spesimen
                            </div>
                        `);
                    }
                });
            }

            function showSpesimenError(message) {
                const errorHtml = `
                    <div class="text-center text-danger">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                        <br>${message}
                    </div>
                `;
                $('#ttd-front-office-preview, #stempel-kelurahan-receive-preview').html(errorHtml);
            }

            function loadFrontOfficeSpesimen(psuId) {
                console.log('Loading Front Office spesimen for ID:', psuId);

                $.ajax({
                    url: `/psu/${psuId}/get-front-office-spesimen`,
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log('Front Office spesimen response:', response);

                        if (response.success) {
                            // Update TTD Front Office preview
                            if (response.data.ttd_front_office) {
                                $('#ttd-front-office-preview').html(`
                                    <img src="${response.data.ttd_front_office}" alt="TTD Front Office" style="max-width: 100%; max-height: 180px;">
                                    <div class="mt-2 text-center">
                                        <small class="text-muted">${response.data.nama_pejabat_front_office || 'Front Office'}</small>
                                    </div>
                                    <input type="hidden" name="ttd_front_office_url" value="${response.data.ttd_front_office}">
                                `);
                            } else {
                                $('#ttd-front-office-preview').html(`
                                    <div class="text-center text-warning">
                                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                        <br>TTD Front Office belum diupload
                                        <br><small class="text-muted">Silakan hubungi admin untuk mengupload TTD Front Office</small>
                                    </div>
                                `);
                            }

                            // Update Stempel Kelurahan preview
                            if (response.data.stempel_kelurahan) {
                                $('#stempel-kelurahan-receive-preview').html(`
                                    <img src="${response.data.stempel_kelurahan}" alt="Stempel Kelurahan" style="max-width: 100%; max-height: 180px;">
                                    <div class="mt-2 text-center">
                                        <small class="text-muted">Stempel Kelurahan</small>
                                    </div>
                                    <input type="hidden" name="stempel_kelurahan_url" value="${response.data.stempel_kelurahan}">
                                `);
                            } else {
                                $('#stempel-kelurahan-receive-preview').html(`
                                    <div class="text-center text-warning">
                                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                        <br>Stempel Kelurahan belum diupload
                                        <br><small class="text-muted">Silakan hubungi admin untuk mengupload Stempel Kelurahan</small>
                                    </div>
                                `);
                            }

                            // Show debug info in console if available
                            if (response.debug) {
                                console.log('Debug info:', response.debug);
                                console.log('Available jabatan:', response.debug.available_jabatan);
                            }

                            // Show messages if any
                            if (response.message && response.message.length > 0) {
                                console.warn('Spesimen warnings:', response.message);
                            }

                        } else {
                            console.error('Invalid response format:', response);
                            showSpesimenError('Error memuat data spesimen');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading Front Office spesimen:', {xhr, status, error});
                        console.error('Response text:', xhr.responseText);

                        let errorMessage = 'Error memuat data spesimen';
                        if (xhr.status === 404) {
                            errorMessage = 'Route tidak ditemukan. Pastikan route sudah terdaftar.';
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        showSpesimenError(errorMessage);
                    }
                });
            }

            // Function untuk show error spesimen Lurah
            function showLurahSpesimenError(message) {
                const errorHtml = `
                    <div class="text-center text-danger">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                        <br>${message}
                    </div>
                `;
                $('#ttd-lurah-preview, #stempel-lurah-preview').html(errorHtml);
            }

            // Load SignaturePad library if not loaded
            if (typeof SignaturePad === 'undefined') {
                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js';
                document.head.appendChild(script);
            }

            // Function untuk load Lurah spesimen (TTD dan Stempel)
            function loadLurahSpesimen(psuId) {
                console.log('Loading Lurah spesimen for ID:', psuId);

                $.ajax({
                    url: `/psu/${psuId}/get-lurah-spesimen`,
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log('Lurah spesimen response:', response);

                        if (response.success) {
                            lurahSpecimenData = response.data;

                            // Update TTD Lurah preview
                            if (response.data.ttd_lurah) {
                                $('#ttd-lurah-preview').html(`
                                    <img src="${response.data.ttd_lurah}" alt="TTD Lurah" style="max-width: 100%; max-height: 180px;">
                                    <div class="mt-2 text-center">
                                        <small class="text-muted">${response.data.nama_pejabat || 'Lurah'}</small>
                                    </div>
                                    <input type="hidden" name="ttd_lurah_url" value="${response.data.ttd_lurah}">
                                `);
                            } else {
                                $('#ttd-lurah-preview').html(`
                                    <div class="text-center text-warning">
                                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                        <br>TTD Lurah belum diupload
                                        <br><small class="text-muted">Gunakan tanda tangan manual di bawah</small>
                                    </div>
                                `);
                            }

                            // Update Stempel Kelurahan preview
                            if (response.data.stempel_kelurahan) {
                                $('#stempel-lurah-preview').html(`
                                    <img src="${response.data.stempel_kelurahan}" alt="Stempel Kelurahan" style="max-width: 100%; max-height: 180px;">
                                    <div class="mt-2 text-center">
                                        <small class="text-muted">Stempel Kelurahan</small>
                                    </div>
                                    <input type="hidden" name="stempel_kelurahan_url" value="${response.data.stempel_kelurahan}">
                                `);
                            } else {
                                $('#stempel-lurah-preview').html(`
                                    <div class="text-center text-warning">
                                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                        <br>Stempel Kelurahan belum diupload
                                        <br><small class="text-muted">Silakan hubungi admin untuk mengupload stempel</small>
                                    </div>
                                `);
                            }
                        } else {
                            console.error('Invalid spesimen response format:', response);
                            showLurahSpesimenError('Error memuat data spesimen');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading Lurah spesimen:', {xhr, status, error});
                        console.error('Response text:', xhr.responseText);

                        let errorMessage = 'Error memuat data spesimen';
                        if (xhr.status === 404) {
                            errorMessage = 'Route spesimen Lurah tidak ditemukan. Pastikan route sudah terdaftar.';
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        showLurahSpesimenError(errorMessage);
                    }
                });
            }

            // Function untuk load Kelurahan Spesimen
            function loadKelurahanSpesimen(psuId) {
                $.ajax({
                    url: `/psu/${psuId}/get-kelurahan-spesimen`,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success && response.data) {
                            // Show TTD Kelurahan
                            if (response.data.ttd_kelurahan) {
                                $('#ttd-kelurahan-preview').html(`
                                    <img src="${response.data.ttd_kelurahan}" class="img-fluid" alt="TTD Kelurahan" style="max-height: 180px;">
                                    <input type="hidden" name="ttd_kelurahan_url" value="${response.data.ttd_kelurahan}">
                                `);
                            } else {
                                $('#ttd-kelurahan-preview').html(`
                                    <div class="text-center text-danger">
                                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                        <br>TTD Kelurahan tidak ditemukan
                                    </div>
                                `);
                            }

                            // Show Stempel Kelurahan
                            if (response.data.stempel_kelurahan) {
                                $('#stempel-kelurahan-preview').html(`
                                    <img src="${response.data.stempel_kelurahan}" class="img-fluid" alt="Stempel Kelurahan" style="max-height: 180px;">
                                    <input type="hidden" name="stempel_kelurahan_url" value="${response.data.stempel_kelurahan}">
                                `);
                            } else {
                                $('#stempel-kelurahan-preview').html(`
                                    <div class="text-center text-danger">
                                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                        <br>Stempel Kelurahan tidak ditemukan
                                    </div>
                                `);
                            }
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading Kelurahan spesimen:', xhr);
                        $('#ttd-kelurahan-preview, #stempel-kelurahan-preview').html(`
                            <div class="text-center text-danger">
                                <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                <br>Error memuat data spesimen
                            </div>
                        `);
                    }
                });
            }

        });

        // Function untuk load summary data
        function loadSummaryData() {
            $.ajax({
                url: "{{ route('psu.getSummary') }}",
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
            $('#selesaiDiprosesCount').text(data.selesai_diproses || 0);

            // Update status summary
            let statusText = `Total: ${data.total_permohonan || 0} | Butuh Action: ${data.butuh_action || 0} | Proses: ${data.sedang_proses || 0} | Selesai: ${data.selesai_diproses || 0}`;
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

        // Reset modal saat ditutup
        $('#approveRTModal').on('hidden.bs.modal', function() {
            $('#ttd-rt-preview, #stempel-rt-preview').html(`
                <div class="text-center text-muted">
                    <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                    <br>Memuat spesimen...
                </div>
            `);
            $('#catatan_rt').val('');
        });

        $('#approveRWModal').on('hidden.bs.modal', function() {
            $('#ttd-rw-preview, #stempel-rw-preview').html(`
                <div class="text-center text-muted">
                    <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                    <br>Memuat spesimen...
                </div>
            `);
            $('#catatan_rw').val('');
        });

        $('#approveKelurahanModal').on('hidden.bs.modal', function() {
            $('#ttd-kelurahan-preview, #stempel-kelurahan-preview').html(`
                <div class="text-center text-muted">
                    <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                    <br>Memuat spesimen...
                </div>
            `);
            $('#catatan_kelurahan').val('');
        });

        $('#receiveKelurahanModal').on('hidden.bs.modal', function() {
            $(this).find('form')[0]?.reset();
            $('#confirmReceive').prop('checked', false);

            // Clear loaded data
            $('#receiveNomorSurat').val('');
            $('#receiveNamaPemohon').val('');
            $('#receiveHal').val('');
            $('#catatanFrontOffice').val('');

            console.log('Receive modal reset'); // Debug log
        });

        // Modal hidden event - reset form
        $('#processLurahModal').on('hidden.bs.modal', function() {
            $(this).find('form')[0]?.reset();

            // Clear signature pad
            if (signaturePadLurah) {
                signaturePadLurah.clear();
            }

            // Reset radio buttons
            $('input[name="instruksi_arahan"]').prop('checked', false);
            $('#confirmProcessLurah').prop('checked', false);
            $('#catatanLurah').val('');

            // Clear loaded data
            $('#processLurahNomorSurat').val('');
            $('#processLurahNamaPemohon').val('');
            $('#processLurahHal').val('');

            // Reset spesimen previews
            $('#ttd-lurah-preview, #stempel-lurah-preview').html(`
                <div class="text-center text-muted">
                    <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                    <br>Memuat spesimen...
                </div>
            `);

            console.log('Process Lurah modal reset');
        });

        $('#approveBackOfficeModal').on('hidden.bs.modal', function() {
            $(this).find('form')[0]?.reset();
            $('#confirmBackOffice').prop('checked', false);
            $('#confirmNotification').prop('checked', true);

            // Clear loaded data
            $('#backOfficeNomorSurat').val('');
            $('#backOfficeNamaPemohon').val('');
            $('#backOfficeHal').val('');
            $('#catatanBackOffice').val('');

            console.log('Back Office modal reset'); // Debug log
        });

        // Handle RT Approve Form Submit
        $(document).on('submit', '#approveRTForm', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const formData = new FormData(this);

            $.ajax({
                url: `psu/${id}/approve-rt`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.success) {
                        $('#approveRTModal').modal('hide');
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

        // Handle RT Reject Form Submit
        $(document).on('submit', '#rejectRTForm', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const formData = new FormData(this);

            $.ajax({
                url: `psu/${id}/reject-rt`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.success) {
                        $('#rejectRTModal').modal('hide');
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

        // Handle RW Approve Form Submit
        $(document).on('submit', '#approveRWForm', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const formData = new FormData(this);

            $.ajax({
                url: `psu/${id}/approve-rw`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.success) {
                        $('#approveRWModal').modal('hide');
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

        // Handle RW Reject Form Submit
        $(document).on('submit', '#rejectRWForm', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const formData = new FormData(this);

            $.ajax({
                url: `psu/${id}/reject-rw`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.success) {
                        $('#rejectRWModal').modal('hide');
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

        // Handle Kelurahan Receive Form Submit
        $(document).on('submit', '#receiveKelurahanForm', function(e) {
            e.preventDefault();
            const id = $(this).data('id');

            console.log('Submitting receive form for ID:', id); // Debug log

            if (!id) {
                Swal.fire('Error', 'ID PSU tidak ditemukan', 'error');
                return;
            }

            // Validate confirmation checkbox
            if (!$('#confirmReceive').is(':checked')) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Konfirmasi Diperlukan',
                    text: 'Harap centang kotak konfirmasi sebelum melanjutkan.'
                });
                return;
            }

            const formData = new FormData(this);
            const submitBtn = $('#receiveKelurahanBtn');
            const originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...');

            $.ajax({
                url: `/psu/${id}/receive-kelurahan`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Receive kelurahan response:', response); // Debug log

                    if(response.success) {
                        $('#receiveKelurahanModal').modal('hide');
                        table.ajax.reload();
                        loadSummaryData();

                        // Show success with document links if available
                        let successHtml = response.message;
                        if (response.data?.tanda_terima_url || response.data?.disposisi_url) {
                            successHtml += '<div class="mt-3">';
                            if (response.data.tanda_terima_url) {
                                successHtml += `<a href="${response.data.tanda_terima_url}" target="_blank" class="btn btn-info btn-sm mr-2">
                                    <i class="fas fa-receipt"></i> Lihat Tanda Terima
                                </a>`;
                            }
                            if (response.data.disposisi_url) {
                                successHtml += `<a href="${response.data.disposisi_url}" target="_blank" class="btn btn-warning btn-sm">
                                    <i class="fas fa-clipboard-list"></i> Lihat Disposisi
                                </a>`;
                            }
                            successHtml += '</div>';
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            html: successHtml,
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error submitting receive kelurahan:', {xhr, status, error});
                    console.error('Response text:', xhr.responseText);

                    const message = xhr.responseJSON?.message || 'Terjadi kesalahan saat memproses data.';
                    Swal.fire('Error!', message, 'error');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Handle Lurah Process Form Submit
        $(document).on('submit', '#processLurahForm', function(e) {
            e.preventDefault();
            const id = $(this).data('id');

            console.log('Submitting lurah process form for ID:', id);

            if (!id) {
                Swal.fire('Error', 'ID PSU tidak ditemukan', 'error');
                return;
            }

            // Validate instruksi/arahan
            const instruksiArahan = $('input[name="instruksi_arahan"]:checked').val();
            if (!instruksiArahan) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Instruksi/Arahan Diperlukan',
                    text: 'Harap pilih salah satu instruksi/arahan sebelum melanjutkan.'
                });
                return;
            }

            // Validate TTD (spesimen or manual or fallback)
            const hasSpecimenTTD = lurahSpecimenData && lurahSpecimenData.ttd_lurah;
            const hasManualSignature = signaturePadLurah && !signaturePadLurah.isEmpty();

            // Allow fallback to Front Office TTD if neither spesimen nor manual is available
            if (!hasSpecimenTTD && !hasManualSignature) {
                console.log('No TTD Lurah spesimen or manual signature, will use Front Office TTD as fallback');
            }

            // Validate confirmation checkbox
            if (!$('#confirmProcessLurah').is(':checked')) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Konfirmasi Diperlukan',
                    text: 'Harap centang kotak konfirmasi sebelum melanjutkan.'
                });
                return;
            }

            // Prepare form data
            const formData = new FormData(this);

            // Add instruksi/arahan
            formData.append('instruksi_arahan', instruksiArahan);

            // Add signature data (manual or spesimen)
            if (hasManualSignature) {
                formData.append('ttd_lurah_manual', signaturePadLurah.toDataURL());
                formData.append('use_manual_signature', 'true');
            } else if (hasSpecimenTTD) {
                formData.append('ttd_lurah_spesimen', lurahSpecimenData.ttd_lurah);
                formData.append('use_manual_signature', 'false');
            }
            // If neither, the backend will use Front Office TTD as fallback

            // Add stempel data
            if (lurahSpecimenData && lurahSpecimenData.stempel_kelurahan) {
                formData.append('stempel_kelurahan_url', lurahSpecimenData.stempel_kelurahan);
            }

            const submitBtn = $('#processLurahBtn');
            const originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...');

            $.ajax({
                url: `/psu/${id}/process-lurah`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Process lurah response:', response);

                    if(response.success) {
                        $('#processLurahModal').modal('hide');

                        // Reload table if exists
                        if (typeof table !== 'undefined' && table.ajax) {
                            table.ajax.reload();
                        }

                        // Reload summary if exists
                        if (typeof loadSummaryData === 'function') {
                            loadSummaryData();
                        }

                        let successMessage = response.message;
                        if (response.data && response.data.ttd_source) {
                            const sourceText = {
                                'manual': 'Menggunakan tanda tangan manual',
                                'spesimen': 'Menggunakan TTD spesimen Lurah',
                                'fallback': 'Menggunakan TTD Front Office sebagai fallback'
                            };
                            successMessage += `\n\n${sourceText[response.data.ttd_source] || ''}`;
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Disposisi Berhasil!',
                            text: successMessage,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Reload page jika di detail view
                            if (window.location.pathname.includes('/psu/')) {
                                window.location.reload();
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error submitting lurah process:', {xhr, status, error});
                    const message = xhr.responseJSON?.message || 'Terjadi kesalahan saat memproses disposisi.';
                    Swal.fire('Error!', message, 'error');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

         // Handle Back Office Approve Form Submit (Final Step)
        $(document).on('submit', '#approveBackOfficeForm', function(e) {
            e.preventDefault();
            const id = $(this).data('id');

            console.log('Submitting back office form for ID:', id); // Debug log

            if (!id) {
                Swal.fire('Error', 'ID PSU tidak ditemukan', 'error');
                return;
            }

            // Validate confirmation checkbox
            if (!$('#confirmBackOffice').is(':checked')) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Konfirmasi Diperlukan',
                    text: 'Harap centang kotak konfirmasi sebelum melanjutkan.'
                });
                return;
            }

            const formData = new FormData(this);
            const submitBtn = $('#approveBackOfficeBtn');
            const originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...');

            $.ajax({
                url: `/psu/${id}/process-back-office`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Back office response:', response); // Debug log

                    if(response.success) {
                        $('#approveBackOfficeModal').modal('hide');
                        table.ajax.reload();
                        loadSummaryData();

                        // Show completion success
                        Swal.fire({
                            icon: 'success',
                            title: 'PSU Selesai!',
                            html: `
                                <p>${response.message}</p>
                                <div class="text-muted mt-2">
                                    <i class="fas fa-check-circle text-success"></i>
                                    Seluruh workflow PSU telah diselesaikan
                                </div>
                            `,
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error submitting back office:', {xhr, status, error});
                    const message = xhr.responseJSON?.message || 'Terjadi kesalahan saat menyelesaikan PSU.';
                    Swal.fire('Error!', message, 'error');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Handle Kelurahan Final Approve Form Submit
        $(document).on('submit', '#approveKelurahanForm', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const formData = new FormData(this);

            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Processing...');

            $.ajax({
                url: `/psu/${id}/approve-kelurahan`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.success) {
                        $('#approveKelurahanModal').modal('hide');
                        table.ajax.reload();
                        loadSummaryData();
                        Swal.fire('Success!', response.message, 'success');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error!', xhr.responseJSON?.message || 'Terjadi kesalahan.', 'error');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Handle Kelurahan Reject Form Submit
        $(document).on('submit', '#rejectKelurahanForm', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const formData = new FormData(this);

            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Processing...');

            $.ajax({
                url: `/psu/${id}/reject-kelurahan`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.success) {
                        $('#rejectKelurahanModal').modal('hide');
                        table.ajax.reload();
                        loadSummaryData();
                        Swal.fire('Success!', response.message, 'success');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error!', xhr.responseJSON?.message || 'Terjadi kesalahan.', 'error');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Handle Back Office Process Form Submit
        $(document).on('submit', '#processBackOfficeForm', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const formData = new FormData(this);

            $.ajax({
                url: `psu/${id}/process-back-office`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.success) {
                        $('#processBackOfficeModal').modal('hide');
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
