@extends('Template.template')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">

    <style>
        /* Dashboard styling untuk kependudukan */
        .dashboard-card {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            border-radius: 15px;
            border: none;
            overflow: hidden;
            background: white;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        .metric-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
        }

        .metric-card * {
            position: relative;
            z-index: 2;
        }

        .metric-card.primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .metric-card.success { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .metric-card.info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .metric-card.warning { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }

        .metric-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            opacity: 0.9;
        }

        .metric-value {
            font-size: 2rem;
            font-weight: 700;
            margin: 0.5rem 0;
        }

        .metric-label {
            font-size: 1rem;
            opacity: 0.9;
        }

        .stat-item {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            background: #f8f9fa;
            padding-left: 1.5rem;
        }

        .stat-item:last-child {
            border-bottom: none;
        }

        .progress-modern {
            height: 8px;
            border-radius: 10px;
            background: #e9ecef;
            overflow: hidden;
        }

        .progress-modern .progress-bar {
            border-radius: 10px;
            transition: width 0.8s ease;
        }

        .section-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #667eea;
        }

        .btn-modern {
            border-radius: 25px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }

        .data-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .table-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
        }

        .alert-modern {
            border: none;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255,255,255,0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            z-index: 10;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .metric-card {
                padding: 1.5rem;
                margin-bottom: 1rem;
            }

            .metric-value {
                font-size: 1.5rem;
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
                <h1>Dashboard Data Kependudukan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('Dashboard.General') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Data Kependudukan</div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6">
                    <div class="metric-card primary">
                        <div class="metric-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="metric-value" id="totalPenduduk">{{ number_format($currentData->total_penduduk) }}</div>
                        <div class="metric-label">Total Penduduk</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="metric-card success">
                        <div class="metric-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <div class="metric-value" id="totalKK">{{ number_format($currentData->total_kk) }}</div>
                        <div class="metric-label">Kepala Keluarga</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="metric-card info">
                        <div class="metric-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="metric-value" id="totalRW">{{ $currentData->total_rw }}</div>
                        <div class="metric-label">Total RW</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="metric-card warning">
                        <div class="metric-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="metric-value" id="totalRT">{{ $currentData->total_rt }}</div>
                        <div class="metric-label">Total RT</div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="section-header">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <div>
                                <h4 class="mb-2">Kelola Data Kependudukan</h4>
                                <p class="text-muted mb-0">Terakhir diperbarui: {{ $currentData->formatted_last_updated }}</p>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary btn-modern" onclick="refreshData()" title="Refresh Data">
                                    <i class="fas fa-sync-alt"></i> Refresh
                                </button>
                                <button class="btn btn-info btn-modern" onclick="exportData()" title="Export Data">
                                    <i class="fas fa-download"></i> Export
                                </button>
                                <a href="{{ route('admin.kependudukan.edit') }}" class="btn btn-primary btn-modern">
                                    <i class="fas fa-edit"></i> Edit Data
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Demographic Data -->
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="card dashboard-card">
                        <div class="table-header">
                            <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Distribusi Usia</h5>
                        </div>
                        <div class="card-body p-0">
                            @foreach($currentData->getAgeGroups() as $group => $count)
                            <div class="stat-item">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-medium">{{ $group }}</span>
                                    <span class="badge bg-primary">{{ number_format($count) }}</span>
                                </div>
                                <div class="progress progress-modern">
                                    <div class="progress-bar bg-primary" style="width: {{ $currentData->total_penduduk > 0 ? ($count / $currentData->total_penduduk) * 100 : 0 }}%"></div>
                                </div>
                                <small class="text-muted">{{ $currentData->total_penduduk > 0 ? number_format(($count / $currentData->total_penduduk) * 100, 1) : 0 }}%</small>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-4">
                    <div class="card dashboard-card">
                        <div class="table-header">
                            <h5 class="mb-0"><i class="fas fa-venus-mars me-2"></i>Jenis Kelamin</h5>
                        </div>
                        <div class="card-body p-0">
                            @foreach($currentData->getGenderDistribution() as $gender => $count)
                            <div class="stat-item">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-medium">
                                        <i class="fas fa-{{ $gender == 'Laki-laki' ? 'mars' : 'venus' }} text-{{ $gender == 'Laki-laki' ? 'info' : 'danger' }} me-2"></i>
                                        {{ $gender }}
                                    </span>
                                    <span class="badge bg-{{ $gender == 'Laki-laki' ? 'info' : 'danger' }}">{{ number_format($count) }}</span>
                                </div>
                                <div class="progress progress-modern">
                                    <div class="progress-bar bg-{{ $gender == 'Laki-laki' ? 'info' : 'danger' }}" style="width: {{ $currentData->total_penduduk > 0 ? ($count / $currentData->total_penduduk) * 100 : 0 }}%"></div>
                                </div>
                                <small class="text-muted">{{ $currentData->total_penduduk > 0 ? number_format(($count / $currentData->total_penduduk) * 100, 1) : 0 }}%</small>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-4">
                    <div class="card dashboard-card">
                        <div class="table-header">
                            <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Tingkat Pendidikan</h5>
                        </div>
                        <div class="card-body p-0">
                            @foreach($currentData->getEducationLevels() as $education => $count)
                            <div class="stat-item">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-medium">{{ $education }}</span>
                                    <span class="badge bg-warning">{{ number_format($count) }}</span>
                                </div>
                                <div class="progress progress-modern">
                                    <div class="progress-bar bg-warning" style="width: {{ $currentData->total_penduduk > 0 ? ($count / $currentData->total_penduduk) * 100 : 0 }}%"></div>
                                </div>
                                <small class="text-muted">{{ $currentData->total_penduduk > 0 ? number_format(($count / $currentData->total_penduduk) * 100, 1) : 0 }}%</small>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Statistics -->
            <div class="row">
                <div class="col-12">
                    <div class="card dashboard-card">
                        <div class="table-header">
                            <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Statistik Tambahan</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3 mb-3">
                                    <div class="border-end">
                                        <h4 class="text-primary mb-1" id="avgPerKK">{{ $currentData->total_kk > 0 ? number_format($currentData->total_penduduk / $currentData->total_kk, 1) : 0 }}</h4>
                                        <p class="text-muted mb-0">Rata-rata per KK</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="border-end">
                                        <h4 class="text-success mb-1" id="avgPerRW">{{ $currentData->total_rw > 0 ? number_format($currentData->total_penduduk / $currentData->total_rw, 0) : 0 }}</h4>
                                        <p class="text-muted mb-0">Rata-rata per RW</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="border-end">
                                        <h4 class="text-info mb-1" id="avgPerRT">{{ $currentData->total_rt > 0 ? number_format($currentData->total_penduduk / $currentData->total_rt, 0) : 0 }}</h4>
                                        <p class="text-muted mb-0">Rata-rata per RT</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <h4 class="text-warning mb-1" id="genderRatio">{{ $currentData->perempuan > 0 ? number_format(($currentData->laki_laki / $currentData->perempuan) * 100, 1) : 0 }}%</h4>
                                    <p class="text-muted mb-0">Rasio L/P</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Validation Status -->
            @php
                $validationErrors = $currentData->validateDataConsistency();
            @endphp

            @if(!empty($validationErrors))
            <div class="row mt-4">
                <div class="col-12">
                    <div class="alert alert-warning alert-modern">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                            <div>
                                <h5 class="alert-heading">Peringatan Konsistensi Data!</h5>
                                <ul class="mb-0">
                                    @foreach($validationErrors as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <hr>
                                <p class="mb-0">Silakan <a href="{{ route('admin.kependudukan.edit') }}" class="alert-link">edit data</a> untuk memperbaiki inkonsistensi ini.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="row mt-4">
                <div class="col-12">
                    <div class="alert alert-success alert-modern">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle fa-2x me-3"></i>
                            <div>
                                <h5 class="alert-heading">Data Konsisten!</h5>
                                <p class="mb-0">Semua data kependudukan sudah sesuai dan konsisten. Tidak ada masalah yang ditemukan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Activity -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card dashboard-card">
                        <div class="table-header">
                            <h5 class="mb-0"><i class="fas fa-history me-2"></i>Informasi Terakhir</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Periode Data:</strong></td>
                                            <td>{{ $currentData->formatted_periode }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Terakhir Diperbarui:</strong></td>
                                            <td>{{ $currentData->last_updated->format('d M Y, H:i') }} WIB</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                <span class="badge bg-{{ $currentData->is_active ? 'success' : 'secondary' }}">
                                                    {{ $currentData->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    @if($currentData->keterangan)
                                    <div>
                                        <strong>Keterangan:</strong>
                                        <p class="text-muted mt-2">{{ $currentData->keterangan }}</p>
                                    </div>
                                    @else
                                    <div class="text-muted">
                                        <em>Tidak ada keterangan khusus</em>
                                    </div>
                                    @endif
                                </div>
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
    <script src="{{ asset('library/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Setup CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Auto refresh data every 5 minutes
            setInterval(function() {
                if (!document.hidden) {
                    refreshData(false); // Silent refresh
                }
            }, 300000);
        });

        function refreshData(showToast = true) {
            if (showToast) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    icon: 'info',
                    title: 'Memperbarui data...'
                });
            }

            $.ajax({
                url: '{{ route("api.kependudukan.summary") }}',
                type: 'GET',
                success: function(response) {
                    if (response.success && response.data) {
                        updateDashboardData(response.data);

                        if (showToast) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,
                                icon: 'success',
                                title: 'Data berhasil diperbarui!'
                            });
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error refreshing data:', error);

                    if (showToast) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            icon: 'error',
                            title: 'Gagal memperbarui data!'
                        });
                    }
                }
            });
        }

        function updateDashboardData(data) {
            // Update main metrics
            $('#totalPenduduk').text(data.total_penduduk.toLocaleString());
            $('#totalKK').text(data.total_kk.toLocaleString());
            $('#totalRW').text(data.total_rw);
            $('#totalRT').text(data.total_rt);

            // Update calculated values
            $('#avgPerKK').text(data.rata_rata_per_kk);
            $('#avgPerRW').text(data.rata_rata_per_rw);
            $('#avgPerRT').text(data.rata_rata_per_rt);
            $('#genderRatio').text(data.gender_ratio);
        }

        function exportData() {
            Swal.fire({
                title: 'Export Data Kependudukan',
                text: 'Apakah Anda yakin ingin mengexport data dalam format CSV?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Export!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Sedang memproses...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Start download
                    window.location.href = '{{ route("admin.kependudukan.export") }}';

                    // Close loading after a delay
                    setTimeout(() => {
                        Swal.close();
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            icon: 'success',
                            title: 'Data berhasil diexport!'
                        });
                    }, 2000);
                }
            });
        }

        // Animate numbers on page load
        function animateValue(element, start, end, duration) {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                const value = Math.floor(progress * (end - start) + start);
                element.textContent = value.toLocaleString();
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }

        // Initialize animations
        document.addEventListener('DOMContentLoaded', function() {
            // Animate metric values
            const metrics = [
                { id: 'totalPenduduk', value: {{ $currentData->total_penduduk }} },
                { id: 'totalKK', value: {{ $currentData->total_kk }} },
                { id: 'totalRW', value: {{ $currentData->total_rw }} },
                { id: 'totalRT', value: {{ $currentData->total_rt }} }
            ];

            metrics.forEach((metric, index) => {
                setTimeout(() => {
                    const element = document.getElementById(metric.id);
                    if (element && metric.value > 0) {
                        animateValue(element, 0, metric.value, 2000);
                    }
                }, index * 200);
            });
        });
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
