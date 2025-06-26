{{-- resources/views/Skaw/index-daftar-sidang.blade.php --}}
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
        .table-striped thead th {
            background-color: #f4f6f9;
            color: #34395e;
            font-weight: 600;
            padding: 15px;
            border-bottom: 2px solid #e9ecef;
        }

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

        .summary-card {
            transition: all 0.2s ease;
            border-left: 4px solid transparent;
        }

        .summary-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .summary-card.hari-ini { border-left-color: #007bff; }
        .summary-card.minggu-ini { border-left-color: #17a2b8; }
        .summary-card.bulan-ini { border-left-color: #28a745; }
        .summary-card.total { border-left-color: #6f42c1; }

        .jadwal-card {
            border-left: 4px solid #007bff;
            margin-bottom: 15px;
        }

        .jadwal-card.today {
            border-left-color: #dc3545;
            background-color: #fff5f5;
        }

        .jadwal-card.tomorrow {
            border-left-color: #ffc107;
            background-color: #fffbf0;
        }

        .jadwal-card.upcoming {
            border-left-color: #28a745;
            background-color: #f8fff8;
        }

        .evidence-status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .evidence-status.can-upload {
            background-color: #d4edda;
            color: #155724;
        }

        .evidence-status.waiting {
            background-color: #fff3cd;
            color: #856404;
        }

        .evidence-status.uploaded {
            background-color: #cce5ff;
            color: #004085;
        }

        .calendar-widget {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .calendar-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 8px 8px 0 0;
        }

        .calendar-body {
            padding: 15px;
        }

        .date-highlight {
            background-color: #007bff;
            color: white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 2px;
        }
    </style>
@endpush

@section('Dashboard')
    @include('admin.dashboard.header')
    @include('admin.dashboard.sidebar')

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Daftar Sidang SKAW</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('Dashboard.General') }}">Dashboard</a>
                    </div>
                    <div class="breadcrumb-item">SKAW</div>
                    <div class="breadcrumb-item active">Daftar Sidang</div>
                </div>
            </div>

            {{-- Summary Cards --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card summary-card hari-ini border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-day fa-2x text-primary mb-2"></i>
                            <h6 class="card-title text-muted">Sidang Hari Ini</h6>
                            <h4 class="text-primary mb-0" id="sidangHariIniCount">-</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card summary-card minggu-ini border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-week fa-2x text-info mb-2"></i>
                            <h6 class="card-title text-muted">Minggu Ini</h6>
                            <h4 class="text-info mb-0" id="sidangMingguIniCount">-</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card summary-card bulan-ini border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-alt fa-2x text-success mb-2"></i>
                            <h6 class="card-title text-muted">Bulan Ini</h6>
                            <h4 class="text-success mb-0" id="sidangBulanIniCount">-</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card summary-card total border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-gavel fa-2x text-purple mb-2"></i>
                            <h6 class="card-title text-muted">Total Sidang</h6>
                            <h4 class="text-purple mb-0" id="totalSidangCount">-</h4>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Jadwal Sidang Mendatang --}}
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Jadwal Sidang Mendatang</h4>
                            <div class="card-header-action">
                                <button class="btn btn-outline-primary btn-sm" onclick="refreshJadwalSidang()">
                                    <i class="fas fa-sync-alt"></i> Refresh
                                </button>
                            </div>
                        </div>
                        <div class="card-body" id="jadwalSidangContainer">
                            <!-- Jadwal sidang akan dimuat via AJAX -->
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="calendar-widget">
                        <div class="calendar-header text-center">
                            <h6 class="mb-0">
                                <i class="fas fa-calendar mr-2"></i>
                                <span id="currentMonth">{{ date('F Y') }}</span>
                            </h6>
                        </div>
                        <div class="calendar-body">
                            <div id="miniCalendar">
                                <!-- Mini calendar akan dimuat via JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Data Table --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between w-100">
                                <div>
                                    <h4>Data Sidang SKAW</h4>
                                    <small class="text-muted" id="sidangStatusSummary">Loading...</small>
                                </div>
                                <div>
                                    <button class="btn btn-outline-primary btn-sm mr-1" onclick="refreshSidangData()" title="Refresh Data">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                    <button class="btn btn-success btn-sm" onclick="generateDaftarSidang()" title="Generate Daftar Sidang PDF">
                                        <i class="fas fa-file-pdf"></i> Generate PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="dataSidang-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nomor SKAW</th>
                                            <th>Pemohon</th>
                                            <th>Pewaris</th>
                                            <th>Jadwal Sidang</th>
                                            <th>Tempat</th>
                                            <th>Status Evidence</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info Panel --}}
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-info">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Informasi Sidang SKAW</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-primary">Tentang Sidang SKAW:</h6>
                                    <ul class="small">
                                        <li>Sidang dilaksanakan setelah Front Office membuat jadwal</li>
                                        <li>Pemohon akan diberitahu jadwal sidang melalui tanda terima</li>
                                        <li>Evidence foto sidang hanya bisa diupload setelah hari H sidang</li>
                                        <li>Back Office bertanggung jawab mengupload evidence sidang</li>
                                        <li>Setelah evidence diupload, proses lanjut ke approval Lurah</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-primary">Status Evidence:</h6>
                                    <ul class="small">
                                        <li><span class="evidence-status waiting">Menunggu</span> - Belum tiba hari H sidang</li>
                                        <li><span class="evidence-status can-upload">Dapat Upload</span> - Sudah hari H, bisa upload evidence</li>
                                        <li><span class="evidence-status uploaded">Sudah Upload</span> - Evidence sudah diupload</li>
                                    </ul>
                                </div>
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
            table = $('#dataSidang-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('skaw.getData') }}",
                    data: function(d) {
                        d.view_type = 'daftar_sidang';
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
                    { data: 'nomor_surat', name: 'nomor_surat' },
                    { data: 'pemohon_info', name: 'nama_lengkap' },
                    { data: 'pewaris_info', name: 'pewaris_nama_lengkap' },
                    {
                        data: 'jadwal_sidang',
                        name: 'tanggal_sidang',
                        render: function(data, type, row) {
                            if (row.tanggal_sidang && row.jam_sidang) {
                                const tanggal = new Date(row.tanggal_sidang).toLocaleDateString('id-ID');
                                const jam = row.jam_sidang;
                                return `<div class="font-weight-bold">${tanggal}</div><small class="text-muted">${jam}</small>`;
                            }
                            return '-';
                        }
                    },
                    {
                        data: 'tempat_sidang',
                        name: 'tempat_sidang',
                        render: function(data, type, row) {
                            return data || 'Kelurahan';
                        }
                    },
                    {
                        data: 'evidence_status',
                        name: 'evidence_status',
                        orderable: false,
                        render: function(data, type, row) {
                            const today = new Date();
                            const sidangDate = new Date(row.tanggal_sidang);

                            if (row.status === 'evidence_uploaded' || row.status === 'lurah_approved' || row.status === 'camat_approved' || row.status === 'completed') {
                                return '<span class="evidence-status uploaded">Sudah Upload</span>';
                            } else if (today >= sidangDate) {
                                return '<span class="evidence-status can-upload">Dapat Upload</span>';
                            } else {
                                const daysDiff = Math.ceil((sidangDate - today) / (1000 * 60 * 60 * 24));
                                return `<span class="evidence-status waiting">Menunggu (${daysDiff} hari)</span>`;
                            }
                        }
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            let buttons = '';

                            // Detail button
                            buttons += `<a href="/skaw/${row.id}" class="btn btn-info btn-sm mb-1" title="Lihat Detail">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>`;

                            // Preview Daftar Sidang button
                            if (row.file_daftar_sidang) {
                                buttons += `<a href="/skaw/${row.id}/preview-daftar-sidang" target="_blank"
                                               class="btn btn-secondary btn-sm mb-1" title="Preview Daftar Sidang">
                                                <i class="fas fa-file-pdf"></i> Daftar Sidang
                                            </a>`;
                            }

                            // Upload Evidence button (for Back Office)
                            if (userRole === 'Back Office' || userRole === 'admin') {
                                const today = new Date();
                                const sidangDate = new Date(row.tanggal_sidang);
                                const canUpload = today >= sidangDate &&
                                                 (row.status === 'jadwal_sidang_created' || row.status === 'sidang_selesai');

                                if (canUpload) {
                                    buttons += `<button type="button" class="btn btn-warning btn-sm btn-upload-evidence mb-1"
                                                       data-id="${row.id}" data-name="${row.nama_lengkap}"
                                                       title="Upload Evidence Sidang">
                                                    <i class="fas fa-camera"></i> Upload Evidence
                                                </button>`;
                                }
                            }

                            return `<div class="d-flex flex-column gap-1">${buttons}</div>`;
                        }
                    }
                ],
                order: [[4, 'asc']], // Sort by jadwal sidang asc
                language: {
                    searchPlaceholder: "Cari data...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    emptyTable: `
                        <div class="text-center my-5">
                            <div class="mb-3">
                                <i class="fas fa-gavel fs-1 text-muted"></i>
                            </div>
                            <h6 class="fw-semibold text-muted">Belum ada Jadwal Sidang</h6>
                            <p class="text-muted small">Jadwal sidang akan muncul setelah Front Office membuatnya</p>
                        </div>
                    `,
                    zeroRecords: `
                        <div class="text-center my-5">
                            <div class="mb-3">
                                <i class="fas fa-gavel fs-1 text-muted"></i>
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

            // Load initial data
            loadSummaryData();
            loadJadwalSidangMendatang();
            generateMiniCalendar();

            // Auto refresh setiap 30 detik
            setInterval(function() {
                table.ajax.reload(null, false);
                loadSummaryData();
                loadJadwalSidangMendatang();
            }, 30000);

            // Upload Evidence Handler
            $('#dataSidang-table').on('click', '.btn-upload-evidence', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                // Show upload evidence modal (akan dibuat terpisah)
                showUploadEvidenceModal(id, name);
            });
        });

        // Function untuk load summary data
        function loadSummaryData() {
            $.ajax({
                url: "{{ route('skaw.getSidangSummary') }}",
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        updateSidangSummaryDisplay(response.data);
                    }
                },
                error: function(xhr) {
                    console.error('Error loading sidang summary:', xhr);
                }
            });
        }

        // Function untuk update tampilan summary
        function updateSidangSummaryDisplay(data) {
            $('#sidangHariIniCount').text(data.hari_ini || 0);
            $('#sidangMingguIniCount').text(data.minggu_ini || 0);
            $('#sidangBulanIniCount').text(data.bulan_ini || 0);
            $('#totalSidangCount').text(data.total || 0);

            // Update status summary
            let statusText = `Hari ini: ${data.hari_ini || 0} | Minggu ini: ${data.minggu_ini || 0} | Bulan ini: ${data.bulan_ini || 0} | Total: ${data.total || 0}`;
            $('#sidangStatusSummary').text(statusText);
        }

        // Function untuk load jadwal sidang mendatang
        function loadJadwalSidangMendatang() {
            $.ajax({
                url: "{{ route('skaw.getJadwalSidangMendatang') }}",
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        displayJadwalSidangMendatang(response.data);
                    }
                },
                error: function(xhr) {
                    console.error('Error loading jadwal sidang mendatang:', xhr);
                    $('#jadwalSidangContainer').html(`
                        <div class="text-center text-muted">
                            <i class="fas fa-exclamation-triangle"></i>
                            Error loading jadwal sidang
                        </div>
                    `);
                }
            });
        }

        // Function untuk display jadwal sidang mendatang
        function displayJadwalSidangMendatang(jadwalList) {
            if (!jadwalList || jadwalList.length === 0) {
                $('#jadwalSidangContainer').html(`
                    <div class="text-center text-muted">
                        <i class="fas fa-calendar-times fa-2x mb-2"></i>
                        <p>Tidak ada jadwal sidang mendatang</p>
                    </div>
                `);
                return;
            }

            let html = '';
            const today = new Date().toDateString();
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const tomorrowStr = tomorrow.toDateString();

            jadwalList.forEach(function(jadwal) {
                const sidangDate = new Date(jadwal.tanggal_sidang);
                const sidangDateStr = sidangDate.toDateString();

                let cardClass = 'jadwal-card upcoming';
                let statusIcon = 'fas fa-calendar';
                let statusText = 'Mendatang';

                if (sidangDateStr === today) {
                    cardClass = 'jadwal-card today';
                    statusIcon = 'fas fa-clock';
                    statusText = 'Hari Ini';
                } else if (sidangDateStr === tomorrowStr) {
                    cardClass = 'jadwal-card tomorrow';
                    statusIcon = 'fas fa-calendar-day';
                    statusText = 'Besok';
                }

                html += `
                    <div class="card ${cardClass}">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center">
                                        <i class="${statusIcon} text-primary mr-2"></i>
                                        <div>
                                            <h6 class="mb-0">${jadwal.nama_lengkap}</h6>
                                            <small class="text-muted">${jadwal.nomor_surat}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-right">
                                    <div class="font-weight-bold">${sidangDate.toLocaleDateString('id-ID')}</div>
                                    <small class="text-muted">${jadwal.jam_sidang} | ${jadwal.tempat_sidang || 'Kelurahan'}</small>
                                    <br>
                                    <span class="badge badge-${statusText === 'Hari Ini' ? 'danger' : statusText === 'Besok' ? 'warning' : 'success'} badge-sm">
                                        ${statusText}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            $('#jadwalSidangContainer').html(html);
        }

        // Function untuk generate mini calendar
        function generateMiniCalendar() {
            const today = new Date();
            const currentMonth = today.getMonth();
            const currentYear = today.getFullYear();

            // Implementasi sederhana mini calendar
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
            const firstDay = new Date(currentYear, currentMonth, 1).getDay();

            let calendarHtml = '<div class="mini-calendar"><table class="table table-sm"><tr>';

            // Header hari
            const dayNames = ['M', 'S', 'S', 'R', 'K', 'J', 'S'];
            dayNames.forEach(day => {
                calendarHtml += `<th class="text-center">${day}</th>`;
            });
            calendarHtml += '</tr><tr>';

            // Empty cells untuk hari sebelum tanggal 1
            for (let i = 0; i < firstDay; i++) {
                calendarHtml += '<td></td>';
            }

            // Tanggal-tanggal
            for (let day = 1; day <= daysInMonth; day++) {
                if ((firstDay + day - 1) % 7 === 0 && day > 1) {
                    calendarHtml += '</tr><tr>';
                }

                const isToday = day === today.getDate();
                const cellClass = isToday ? 'date-highlight' : '';

                calendarHtml += `<td class="text-center"><div class="${cellClass}">${day}</div></td>`;
            }

            calendarHtml += '</tr></table></div>';
            $('#miniCalendar').html(calendarHtml);
        }

        // Function untuk refresh data manual
        function refreshSidangData() {
            table.ajax.reload();
            loadSummaryData();

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

        // Function untuk refresh jadwal sidang
        function refreshJadwalSidang() {
            loadJadwalSidangMendatang();

            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                icon: 'info',
                title: 'Jadwal diperbarui'
            });
        }

        // Function untuk generate daftar sidang PDF
        function generateDaftarSidang() {
            window.open("{{ route('skaw.generateDaftarSidangPDF') }}", '_blank');
        }

        // Function untuk show upload evidence modal
        function showUploadEvidenceModal(id, name) {
            // Implementasi modal upload evidence
            Swal.fire({
                title: `Upload Evidence Sidang`,
                html: `
                    <div class="text-left">
                        <p><strong>Pemohon:</strong> ${name}</p>
                        <div class="form-group">
                            <label>Upload Foto Evidence Sidang:</label>
                            <input type="file" id="evidenceFiles" class="form-control" multiple accept="image/*">
                            <small class="text-muted">Pilih beberapa foto evidence sidang</small>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Upload',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#28a745',
                preConfirm: () => {
                    const files = document.getElementById('evidenceFiles').files;
                    if (files.length === 0) {
                        Swal.showValidationMessage('Pilih minimal 1 foto evidence');
                        return false;
                    }
                    return files;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    uploadEvidenceSidang(id, result.value);
                }
            });
        }

        // Function untuk upload evidence sidang
        function uploadEvidenceSidang(id, files) {
            const formData = new FormData();
            for (let i = 0; i < files.length; i++) {
                formData.append('evidence_photos[]', files[i]);
            }

            $.ajax({
                url: `/skaw/${id}/upload-evidence`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.success) {
                        table.ajax.reload();
                        loadSummaryData();
                        Swal.fire('Success!', response.message, 'success');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error!', xhr.responseJSON?.message || 'Terjadi kesalahan.', 'error');
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
