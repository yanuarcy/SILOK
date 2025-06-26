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

    <link rel="stylesheet"
        href="{{ asset('library/owl.carousel/dist/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('library/owl.carousel/dist/assets/owl.theme.default.min.css') }}">

    <link rel="stylesheet"
        href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Template CSS -->
    <link rel="stylesheet"
        href="{{ asset('css/style.css') }}">
    <link rel="stylesheet"
        href="{{ asset('css/components.css') }}">

    <style>
        .card-body {
            padding: 25px 15px;
        }

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

        /* Styling untuk progress bar */
        .progress {
            height: 4px !important;
            border-radius: 10px;
            background-color: #e9ecef;
            margin: 8px 0;
        }

        .progress-bar {
            border-radius: 10px;
        }

        .progress-bar.bg-success {
            background-color: #47c363 !important;
        }

        .progress-bar.bg-warning {
            background-color: #ffa426 !important;
        }

        /* Styling untuk badge status */
        .badge {
            padding: 5px 10px;
            font-weight: 500;
            border-radius: 15px;
        }

        .badge-success {
            background-color: #47c363;
            color: white;
        }

        .badge-info {
            background-color: #3abaf4;
            color: white;
        }

        .badge-warning {
            background-color: #ffa426;
            color: white;
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
                <h1>Front Office Dashboard</h1>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="far fa-user"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Jumlah Antrian</h4>
                            </div>
                            <div class="card-body" id="total-antrian">
                                0
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Antrian Sekarang</h4>
                            </div>
                            <div class="card-body" id="antrian-sekarang">
                                Belum ada
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Antrian Selanjutnya</h4>
                            </div>
                            <div class="card-body" id="antrian-selanjutnya">
                                Belum ada
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="far fa-user"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Sisa Antrian</h4>
                            </div>
                            <div class="card-body" id="sisa-antrian">
                                0
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Antrian</h4>
                            {{-- <input id="user-loket" value="{{ $UserLoket }}"> --}}
                        </div>
                        <div class="card-body">
                            {{-- Filter Buttons --}}
                            <div class="btn-group mb-3" role="group" aria-label="Status Filter">
                                <button id="btn-offline" type="button" class="btn btn-outline-primary active">Offline</button>
                                <button id="btn-online" type="button" class="btn btn-outline-primary">Online</button>
                            </div>

                            {{-- Warning Text (Hidden by default) --}}
                            <div id="online-warnings" style="display: none;">
                                <h6 class="text-danger">*Pencet tombol "End Call" ketika pemohon sudah selesai dihubungi via call.</h6>
                                <h6 class="text-danger">**Pencet tombol "Kirim Pesan" ketika proses layanan sudah selesai.</h6>
                            </div>

                            <div class="table-responsive mx-0">
                                <table class="table-striped table"
                                    id="tabel-antrian">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nama</th>
                                            <th>Nomer Whatsapp</th>
                                            <th>Alamat</th>
                                            <th>Jenis Layanan</th>
                                            <th>Keterangan</th>
                                            <th>Nomor Antrian</th>
                                            <th>Status</th>
                                            <th >Jenis Pengiriman</th>
                                            <th >Calling By</th>
                                            <th >Aksi</th>
                                            <th>Panggil</th>
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
    <script src="{{ asset('js/page/modules-datatables.js') }}"></script>


    <script>

        document.addEventListener('DOMContentLoaded', function() {

            // Fungsi untuk fetch dan update stats
            async function updateDashboardStats() {
                try {
                    const response = await fetch('/adminduk/stats');
                    const data = await response.json();

                    if (data.success) {
                        const stats = data.data;

                        // Update langsung tanpa efek visual
                        updateCardValue('total-antrian', stats.total_antrian || 0);
                        updateCardValue('antrian-sekarang', stats.antrian_sekarang || 'Belum ada');
                        updateCardValue('antrian-selanjutnya', stats.antrian_selanjutnya || 'Belum ada');
                        updateCardValue('sisa-antrian', stats.antrian_menunggu || 0);

                        // Reset error state jika sebelumnya error
                        resetErrorState();

                    } else {
                        console.error('Failed to fetch stats:', data.message);
                        showErrorState();
                    }
                } catch (error) {
                    console.error('Error fetching dashboard stats:', error);
                    showErrorState();
                }
            }

            // Fungsi helper untuk update nilai card (tanpa efek visual)
            function updateCardValue(elementId, value) {
                const element = document.getElementById(elementId);
                if (element) {
                    element.textContent = value;
                }
            }

            // Fungsi untuk menampilkan error state
            function showErrorState() {
                const cardIds = ['total-antrian', 'antrian-sekarang', 'antrian-selanjutnya', 'sisa-antrian'];
                cardIds.forEach(id => {
                    const element = document.getElementById(id);
                    if (element) {
                        element.textContent = 'Error';
                        element.style.color = '#dc3545';
                    }
                });
            }

            // Reset error state
            function resetErrorState() {
                const cardIds = ['total-antrian', 'antrian-sekarang', 'antrian-selanjutnya', 'sisa-antrian'];
                cardIds.forEach(id => {
                    const element = document.getElementById(id);
                    if (element) {
                        element.style.color = ''; // Reset ke warna default
                    }
                });
            }

            // Initial load saat halaman dibuka
            updateDashboardStats();

            // Real-time update setiap 1 detik
            setInterval(updateDashboardStats, 1000);

            // Manual refresh function (bisa dipanggil dari button jika diperlukan)
            window.refreshDashboard = function() {
                updateDashboardStats();
            };

            // Optional: Tampilkan status koneksi
            let isOnline = true;
            window.addEventListener('online', () => {
                isOnline = true;
                console.log('Connection restored');
                updateDashboardStats();
            });

            window.addEventListener('offline', () => {
                isOnline = false;
                console.log('Connection lost');
            });
        });

        $(document).ready(function() {
            console.log('Script running...'); // Debugging

            // Default jenis antrian
            var jenisAntrian = 'Offline';
            // var loket = "2";
            // var loket = @json($userLoket ?? '8');
            var loket = "{{ $userLoket ?? '1' }}"; // Ambil dari controller
            // var user = "{{ $userID ?? '4' }}"; // Ambil dari controller
            // var loket = document.getElementById('user-loket').value;
            // console.log('Loket from controller:', loket);
            // console.log('user id from user table:', user);

            // Function to mask the WhatsApp number
            function maskWhatsapp(number) {
                // Mask the number, keeping the first four digits visible
                return number.replace(/(\d{4})(\d{4})/, '$1********');
            }

            // Initialize DataTable
            var table = $('#tabel-antrian').DataTable({
                // processing: true,
                // serverSide: true,
                ajax: {
                    url: "{{ route('antrian.data') }}",
                    data: function(d) {
                        d.jenis_antrian = jenisAntrian;
                    }
                },
                columns: [
                    {data: 'id', visible: false},
                    {data: 'nama', width: '15%', className: 'text-start'},
                    {
                        data: 'no_whatsapp',
                        width: '15%',
                        className: 'text-start',
                        render: function(data, type, row) {
                            // var loketNumber = row['calling_by'].match(/\d+/); // Extract the number from 'Loket X'
                            var loketNumber = null;
                            if (row.calling_by && row.calling_by.match(/Loket (\d+)/)) {
                                loketNumber = row.calling_by.match(/Loket (\d+)/)[1];
                            }

                            if (data === null || data === '' || data === '-') {
                                return 'No Device';
                            }
                            if (jenisAntrian === 'Online') {
                                // Logic for masking WhatsApp number
                                // Show full number if current loket is handling this antrian OR if already served
                                if ((loketNumber && loketNumber === loket) || row.status === '1') {
                                    return data;
                                } else {
                                    return maskWhatsapp(data); // Display masked number otherwise
                                }
                                // return data.substring(0, 4) + '********';
                            } else {
                                return data;
                            }
                        }
                    },
                    {data: 'alamat', width: '20%', className: 'text-start'},
                    {data: 'jenis_layanan', width: '12%', className: 'text-start'},
                    {data: 'keterangan', width: '15%', className: 'text-start'},
                    {data: 'no_antrian', width: '8%', className: 'text-center'},
                    {data: 'status', visible: false},
                    {
                        data: 'jenis_pengiriman',
                        width: '10%',
                        className: 'text-center jenis-pengiriman-column',
                        visible: false
                    },
                    {
                        data: 'calling_by',
                        width: '10%',
                        className: 'text-center calling-by-column',
                        visible: false,
                        render: function(data, type, row) {
                            if (!data || data === '') {
                                return '<span class="badge bg-secondary">Standby</span>';
                            }

                            // Extract loket number
                            var loketMatch = data.match(/Loket (\d+)/);
                            if (loketMatch) {
                                var callingLoket = loketMatch[1];
                                if (callingLoket === loket) {
                                    return '<span class="badge bg-success">Loket ' + callingLoket + '</span>';
                                } else {
                                    return '<span class="badge bg-warning">Loket ' + callingLoket + '</span>';
                                }
                            }

                            return '<span class="badge bg-info">' + data + '</span>';
                        }
                    },
                    {
                        data: null,
                        width: '8%',
                        className: 'text-center aksi-column',
                        visible: false,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            if (row.status === '') {
                                return "-";
                            }

                            // JIKA SUDAH TERLAYANI (status = '1'), DISABLE TOMBOL CALL
                            if (row.status === '1') {
                                return '<button class="btn btn-secondary btn-sm" disabled>Selesai</button>';
                            }

                            // Extract loket number from calling_by field
                            var callingLoket = null;
                            if (row.calling_by && row.calling_by.match(/Loket (\d+)/)) {
                                callingLoket = row.calling_by.match(/Loket (\d+)/)[1];
                            }

                            // Check if current loket has any active call
                            if (!row.calling_by || row.calling_by === '') {
                                // No one is calling this antrian - can call
                                return '<button class="btn btn-success btn-sm btn-call" data-id="' + row.id + '" data-loket="' + loket + '">Call</button>';
                            } else if (callingLoket === loket) {
                                // Current loket is calling this antrian - can end call
                                return '<button class="btn btn-danger btn-sm btn-end-call" data-id="' + row.id + '">End Call</button>';
                            } else {
                                // Another loket is calling this antrian - disabled
                                return '<button class="btn btn-secondary btn-sm" disabled>On Call</button>';
                            }
                        }
                    },
                    {
                        data: null,
                        width: '8%',
                        className: 'text-center pr-4',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            if (row.status === '') return '-';

                            if (jenisAntrian === 'Online') {
                                if (row.status === '0') {
                                    return '<button class="btn btn-primary btn-sm panggil" data-id="' + row.id + '">Kirim Pesan</button>';
                                }
                                return '<button class="btn btn-secondary btn-sm" disabled>Terlayani</button>';
                            }

                            if (row.status === '0') {
                                return '<button class="btn btn-success btn-sm panggil" data-id="' + row.id + '"><i class="bi-mic-fill"></i> Panggil</button>';
                            }
                            return '<button class="btn btn-secondary btn-sm panggil" data-id="' + row.id + '"><i class="bi-mic-fill"></i> Panggil Lagi</button>';
                        }
                    }
                ],
                order: [[0, 'desc']],
                pageLength: 10,
                lengthChange: false,
                searching: false,
                autoWidth: false,
                responsive: true,
                language: {
                    emptyTable: `
                        <div class="text-center my-5">
                            <div class="mb-3">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                            </div>
                            <h6 class="fw-semibold text-muted">Belum ada Data</h6>
                            <p class="text-muted small">Data antrian akan muncul di sini</p>
                        </div>
                    `,
                    zeroRecords: `
                        <div class="text-center my-5">
                            <div class="mb-3">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                            </div>
                            <h6 class="fw-semibold text-muted">Belum ada Data</h6>
                            <p class="text-muted small">Data antrian akan muncul di sini</p>
                        </div>
                    `,
                    infoEmpty: ''
                }
            });

            // Toggle Online/Offline buttons
            $('#btn-offline').click(function() {
                jenisAntrian = 'Offline';
                table.column(8).visible(false);
                table.column(9).visible(false);
                table.column(10).visible(false);
                $(this).addClass('active');
                $('#btn-online').removeClass('active');
                $('#online-warnings').hide();
                table.ajax.reload();
            });

            $('#btn-online').click(function() {
                jenisAntrian = 'Online';
                table.column(8).visible(true);
                table.column(9).visible(true);
                table.column(10).visible(true);
                $(this).addClass('active');
                $('#btn-offline').removeClass('active');
                $('#online-warnings').show();
                table.ajax.reload();
            });

            // Handle Call/End Call button clicks
            // $('#tabel-antrian').on('click', '.toggle-call', function() {
            //     var data = table.row($(this).closest('tr')).data();
            //     var $btn = $(this);

            //     if ($btn.text() === 'Call') {
            //         $.ajax({
            //             url: "{{ route('antrian.call') }}",
            //             type: 'POST',
            //             data: {
            //                 id: data.id,
            //                 loket: loket,
            //                 _token: "{{ csrf_token() }}"
            //             },
            //             success: function(response) {
            //                 $btn.text('End Call').removeClass('btn-primary').addClass('btn-danger');
            //                 table.ajax.reload();
            //             }
            //         });
            //     } else if ($btn.text() === 'End Call') {
            //         $.ajax({
            //             url: "{{ route('antrian.end-call') }}",
            //             type: 'POST',
            //             data: {
            //                 id: data.id,
            //                 _token: "{{ csrf_token() }}"
            //             },
            //             success: function(response) {
            //                 table.ajax.reload();
            //             }
            //         });
            //     }
            // });

            $('#tabel-antrian').on('click', '.btn-call', function() {
                var data = table.row($(this).closest('tr')).data();
                var button = $(this);

                // Disable button to prevent double clicks
                button.prop('disabled', true);

                $.ajax({
                    url: "{{ route('antrian.call') }}",
                    type: 'POST',
                    data: {
                        id: data.id,
                        loket: loket,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            table.ajax.reload();
                            showNotification('success', 'Antrian berhasil dipanggil');
                        }
                    },
                    error: function(xhr) {
                        console.error('Call error:', xhr.responseText);
                        showNotification('error', 'Terjadi kesalahan saat memanggil antrian');
                    },
                    complete: function() {
                        button.prop('disabled', false);
                    }
                });
            });

            $('#tabel-antrian').on('click', '.btn-end-call', function() {
                var data = table.row($(this).closest('tr')).data();
                var button = $(this);

                button.prop('disabled', true);

                $.ajax({
                    url: "{{ route('antrian.end-call') }}",
                    type: 'POST',
                    data: {
                        id: data.id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            table.ajax.reload();
                            showNotification('success', 'Panggilan berhasil diakhiri');
                        }
                    },
                    error: function(xhr) {
                        console.error('End call error:', xhr.responseText);
                        showNotification('error', 'Terjadi kesalahan saat mengakhiri panggilan');
                    },
                    complete: function() {
                        button.prop('disabled', false);
                    }
                });
            });

            // Handle Panggil/Kirim Pesan button clicks
            // $('#tabel-antrian').on('click', '.panggil', function() {
            //     var data = table.row($(this).closest('tr')).data();

            //     if (jenisAntrian === 'Online') {
            //         $.ajax({
            //             url: "{{ route('antrian.kirim-pesan') }}",
            //             type: 'POST',
            //             data: {
            //                 id: data.id,
            //                 whatsapp: data.no_whatsapp,
            //                 nama: data.nama,
            //                 jenis_layanan: data.jenis_layanan,
            //                 keterangan: data.keterangan,
            //                 no_antrian: data.no_antrian,
            //                 _token: "{{ csrf_token() }}"
            //             },
            //             success: function(response) {
            //                 table.ajax.reload();
            //             }
            //         });
            //     } else {
            //         $.ajax({
            //             url: "{{ route('antrian.panggil') }}",
            //             type: 'POST',
            //             data: {
            //                 id: data.id,
            //                 no_antrian: data.no_antrian,
            //                 loket: loket,
            //                 _token: "{{ csrf_token() }}"
            //             },
            //             success: function(response) {
            //                 table.ajax.reload();
            //             }
            //         });
            //     }
            // });

            $('#tabel-antrian').on('click', '.panggil', function() {
                var data = table.row($(this).closest('tr')).data();
                var button = $(this);

                button.prop('disabled', true);

                if (jenisAntrian === 'Online') {
                    $.ajax({
                        url: "{{ route('antrian.kirim-pesan') }}",
                        type: 'POST',
                        data: {
                            id: data.id,
                            whatsapp: data.no_whatsapp,
                            nama: data.nama,
                            jenis_layanan: data.jenis_layanan,
                            keterangan: data.keterangan,
                            no_antrian: data.no_antrian,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                table.ajax.reload();
                                showNotification('success', response.message);
                            }
                        },
                        error: function(xhr) {
                            console.error('Kirim pesan error:', xhr.responseText);
                            showNotification('error', 'Terjadi kesalahan saat mengirim pesan');
                        },
                        complete: function() {
                            button.prop('disabled', false);
                        }
                    });
                } else {
                    $.ajax({
                        url: "{{ route('antrian.panggil') }}",
                        type: 'POST',
                        data: {
                            id: data.id,
                            no_antrian: data.no_antrian,
                            loket: loket,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                table.ajax.reload();
                                showNotification('success', 'Antrian berhasil dipanggil');
                            }
                        },
                        error: function(xhr) {
                            console.error('Panggil error:', xhr.responseText);
                            showNotification('error', 'Terjadi kesalahan saat memanggil antrian');
                        },
                        complete: function() {
                            button.prop('disabled', false);
                        }
                    });
                }
            });

            function showNotification(type, message) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: type === 'success' ? 'success' : 'error',
                        title: message,
                        timer: 3000,
                        showConfirmButton: false
                    });
                } else {
                    // Fallback to alert or console
                    if (type === 'success') {
                        console.log('Success:', message);
                    } else {
                        console.error('Error:', message);
                    }
                }
            }

            setInterval(function() {
                // $('#jumlah-antrian').load('get_jumlah_antrian.php').fadeIn("slow");
                // $('#antrian-sekarang').load('get_antrian_sekarang.php').fadeIn("slow");
                // // $('#antrian-selanjutnya').load('get_antrian_selanjutnya.php').fadeIn("slow");
                // $('#antrian-selanjutnya').load('get_antrian_selanjutnya.php', function(response) {
                // // Ambil elemen pertama dari response JSON dan tampilkan tanpa tanda kurung atau kutip
                // var nextQueue = JSON.parse(response)[0];
                // $('#antrian-selanjutnya').text(nextQueue);
                // }).fadeIn("slow");
                // $('#sisa-antrian').load('get_sisa_antrian.php').fadeIn("slow");
                table.ajax.reload(null, false);
            }, 1000);
        });
    </script>


    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
