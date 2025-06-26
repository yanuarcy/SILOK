@extends('Template.template')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">

    <style>
        .form-section {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .form-section:hover {
            box-shadow: 0 12px 35px rgba(0,0,0,0.15);
        }

        .section-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            margin: 0;
        }

        .section-header h5 {
            margin: 0;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: white;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .input-group {
            position: relative;
        }

        .input-group-text {
            background: #667eea;
            color: white;
            border: none;
            border-radius: 10px 0 0 10px;
        }

        .btn-modern {
            border-radius: 25px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }

        .btn-primary.btn-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .btn-secondary.btn-modern {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        }

        .btn-success.btn-modern {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .progress-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin: 1rem 0;
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

        .data-preview {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 1rem;
        }

        .preview-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }

        .preview-item:last-child {
            border-bottom: none;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255,255,255,0.9);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .spinner-modern {
            width: 3rem;
            height: 3rem;
            border: 0.25em solid #f3f3f3;
            border-top: 0.25em solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Real-time validation */
        .field-valid {
            border-color: #28a745 !important;
            background-color: #f8fff9 !important;
        }

        .field-invalid {
            border-color: #dc3545 !important;
            background-color: #fff5f5 !important;
        }

        .auto-calculated {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%) !important;
            font-weight: 600;
            color: #856404;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .form-section {
                margin-bottom: 1rem;
            }

            .btn-modern {
                width: 100%;
                margin-bottom: 0.5rem;
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
                <h1>Edit Data Kependudukan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('Dashboard.General') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('admin.kependudukan.index') }}">Data Kependudukan</a></div>
                    <div class="breadcrumb-item active">Edit</div>
                </div>
            </div>

            <form action="{{ route('admin.kependudukan.update') }}" method="POST" id="kependudukanForm">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="form-section">
                    <div class="section-header">
                        <h5><i class="fas fa-info-circle me-2"></i>Informasi Dasar</h5>
                    </div>
                    <div class="p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="periode_data" class="form-label">Periode Data</label>
                                    <input type="month"
                                           class="form-control @error('periode_data') is-invalid @enderror"
                                           id="periode_data"
                                           name="periode_data"
                                           value="{{ old('periode_data', $data->periode_data) }}"
                                           required>
                                    @error('periode_data')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                              id="keterangan"
                                              name="keterangan"
                                              rows="3"
                                              placeholder="Keterangan tambahan (opsional)">{{ old('keterangan', $data->keterangan) }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Administrative Data -->
                <div class="form-section">
                    <div class="section-header">
                        <h5><i class="fas fa-building me-2"></i>Data Administratif</h5>
                    </div>
                    <div class="p-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="total_kk" class="form-label">Total Kepala Keluarga</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-home"></i></span>
                                        <input type="number"
                                               class="form-control @error('total_kk') is-invalid @enderror"
                                               id="total_kk"
                                               name="total_kk"
                                               value="{{ old('total_kk', $data->total_kk) }}"
                                               min="0"
                                               required
                                               onchange="calculateAverages()">
                                    </div>
                                    @error('total_kk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="total_rw" class="form-label">Total RW</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <input type="number"
                                               class="form-control @error('total_rw') is-invalid @enderror"
                                               id="total_rw"
                                               name="total_rw"
                                               value="{{ old('total_rw', $data->total_rw) }}"
                                               min="0"
                                               required
                                               onchange="calculateAverages()">
                                    </div>
                                    @error('total_rw')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="total_rt" class="form-label">Total RT</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                        <input type="number"
                                               class="form-control @error('total_rt') is-invalid @enderror"
                                               id="total_rt"
                                               name="total_rt"
                                               value="{{ old('total_rt', $data->total_rt) }}"
                                               min="0"
                                               required
                                               onchange="calculateAverages()">
                                    </div>
                                    @error('total_rt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Total Penduduk</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-users"></i></span>
                                        <input type="text"
                                               class="form-control auto-calculated"
                                               id="total_penduduk_display"
                                               value="{{ number_format($data->total_penduduk) }}"
                                               readonly>
                                    </div>
                                    <small class="text-muted">Dihitung otomatis dari data usia</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Age Demographics -->
                <div class="form-section">
                    <div class="section-header">
                        <h5><i class="fas fa-chart-bar me-2"></i>Demografis Berdasarkan Usia</h5>
                    </div>
                    <div class="p-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="usia_0_17" class="form-label">Usia 0-17 Tahun</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-child"></i></span>
                                        <input type="number"
                                               class="form-control @error('usia_0_17') is-invalid @enderror"
                                               id="usia_0_17"
                                               name="usia_0_17"
                                               value="{{ old('usia_0_17', $data->usia_0_17) }}"
                                               min="0"
                                               required
                                               onchange="calculateTotals()">
                                    </div>
                                    @error('usia_0_17')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="usia_18_35" class="form-label">Usia 18-35 Tahun</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="number"
                                               class="form-control @error('usia_18_35') is-invalid @enderror"
                                               id="usia_18_35"
                                               name="usia_18_35"
                                               value="{{ old('usia_18_35', $data->usia_18_35) }}"
                                               min="0"
                                               required
                                               onchange="calculateTotals()">
                                    </div>
                                    @error('usia_18_35')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="usia_36_55" class="form-label">Usia 36-55 Tahun</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                        <input type="number"
                                               class="form-control @error('usia_36_55') is-invalid @enderror"
                                               id="usia_36_55"
                                               name="usia_36_55"
                                               value="{{ old('usia_36_55', $data->usia_36_55) }}"
                                               min="0"
                                               required
                                               onchange="calculateTotals()">
                                    </div>
                                    @error('usia_36_55')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="usia_56_plus" class="form-label">Usia 56+ Tahun</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user-clock"></i></span>
                                        <input type="number"
                                               class="form-control @error('usia_56_plus') is-invalid @enderror"
                                               id="usia_56_plus"
                                               name="usia_56_plus"
                                               value="{{ old('usia_56_plus', $data->usia_56_plus) }}"
                                               min="0"
                                               required
                                               onchange="calculateTotals()">
                                    </div>
                                    @error('usia_56_plus')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Age Distribution Preview -->
                        <div class="progress-section">
                            <h6 class="mb-3">Distribusi Usia</h6>
                            <div id="ageDistribution">
                                <!-- Will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gender Demographics -->
                <div class="form-section">
                    <div class="section-header">
                        <h5><i class="fas fa-venus-mars me-2"></i>Demografis Berdasarkan Jenis Kelamin</h5>
                    </div>
                    <div class="p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="laki_laki" class="form-label">Laki-laki</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-mars text-info"></i></span>
                                        <input type="number"
                                               class="form-control @error('laki_laki') is-invalid @enderror"
                                               id="laki_laki"
                                               name="laki_laki"
                                               value="{{ old('laki_laki', $data->laki_laki) }}"
                                               min="0"
                                               required
                                               onchange="validateGenderTotal()">
                                    </div>
                                    @error('laki_laki')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="perempuan" class="form-label">Perempuan</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-venus text-danger"></i></span>
                                        <input type="number"
                                               class="form-control @error('perempuan') is-invalid @enderror"
                                               id="perempuan"
                                               name="perempuan"
                                               value="{{ old('perempuan', $data->perempuan) }}"
                                               min="0"
                                               required
                                               onchange="validateGenderTotal()">
                                    </div>
                                    @error('perempuan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Gender Distribution Preview -->
                        <div class="progress-section">
                            <h6 class="mb-3">Distribusi Jenis Kelamin</h6>
                            <div id="genderDistribution">
                                <!-- Will be populated by JavaScript -->
                            </div>
                            <div id="genderValidation" class="mt-2"></div>
                        </div>
                    </div>
                </div>

                <!-- Education Demographics -->
                <div class="form-section">
                    <div class="section-header">
                        <h5><i class="fas fa-graduation-cap me-2"></i>Demografis Berdasarkan Pendidikan</h5>
                    </div>
                    <div class="p-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sd_sederajat" class="form-label">SD/Sederajat</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-book"></i></span>
                                        <input type="number"
                                               class="form-control @error('sd_sederajat') is-invalid @enderror"
                                               id="sd_sederajat"
                                               name="sd_sederajat"
                                               value="{{ old('sd_sederajat', $data->sd_sederajat) }}"
                                               min="0"
                                               required
                                               onchange="validateEducationTotal()">
                                    </div>
                                    @error('sd_sederajat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="smp_sederajat" class="form-label">SMP/Sederajat</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-book-open"></i></span>
                                        <input type="number"
                                               class="form-control @error('smp_sederajat') is-invalid @enderror"
                                               id="smp_sederajat"
                                               name="smp_sederajat"
                                               value="{{ old('smp_sederajat', $data->smp_sederajat) }}"
                                               min="0"
                                               required
                                               onchange="validateEducationTotal()">
                                    </div>
                                    @error('smp_sederajat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sma_sederajat" class="form-label">SMA/Sederajat</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user-graduate"></i></span>
                                        <input type="number"
                                               class="form-control @error('sma_sederajat') is-invalid @enderror"
                                               id="sma_sederajat"
                                               name="sma_sederajat"
                                               value="{{ old('sma_sederajat', $data->sma_sederajat) }}"
                                               min="0"
                                               required
                                               onchange="validateEducationTotal()">
                                    </div>
                                    @error('sma_sederajat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="diploma_s1_plus" class="form-label">Diploma/S1+</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-graduation-cap"></i></span>
                                        <input type="number"
                                               class="form-control @error('diploma_s1_plus') is-invalid @enderror"
                                               id="diploma_s1_plus"
                                               name="diploma_s1_plus"
                                               value="{{ old('diploma_s1_plus', $data->diploma_s1_plus) }}"
                                               min="0"
                                               required
                                               onchange="validateEducationTotal()">
                                    </div>
                                    @error('diploma_s1_plus')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Education Distribution Preview -->
                        <div class="progress-section">
                            <h6 class="mb-3">Distribusi Pendidikan</h6>
                            <div id="educationDistribution">
                                <!-- Will be populated by JavaScript -->
                            </div>
                            <div id="educationValidation" class="mt-2"></div>
                        </div>
                    </div>
                </div>

                <!-- Data Preview -->
                <div class="form-section">
                    <div class="section-header">
                        <h5><i class="fas fa-eye me-2"></i>Preview Data</h5>
                    </div>
                    <div class="p-4">
                        <div class="data-preview">
                            <h6 class="mb-3">Ringkasan Data yang Akan Disimpan</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="preview-item">
                                        <span>Total Penduduk:</span>
                                        <strong id="previewTotalPenduduk">0</strong>
                                    </div>
                                    <div class="preview-item">
                                        <span>Total KK:</span>
                                        <strong id="previewTotalKK">0</strong>
                                    </div>
                                    <div class="preview-item">
                                        <span>Rata-rata per KK:</span>
                                        <strong id="previewAvgKK">0</strong>
                                    </div>
                                    <div class="preview-item">
                                        <span>Rasio L/P:</span>
                                        <strong id="previewGenderRatio">0%</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="preview-item">
                                        <span>Total RW:</span>
                                        <strong id="previewTotalRW">0</strong>
                                    </div>
                                    <div class="preview-item">
                                        <span>Total RT:</span>
                                        <strong id="previewTotalRT">0</strong>
                                    </div>
                                    <div class="preview-item">
                                        <span>Kelompok Usia Dominan:</span>
                                        <strong id="previewDominantAge">-</strong>
                                    </div>
                                    <div class="preview-item">
                                        <span>Tingkat Pendidikan Tinggi:</span>
                                        <strong id="previewHighEducation">0%</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-section">
                    <div class="p-4">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.kependudukan.index') }}" class="btn btn-secondary btn-modern">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <div>
                                <button type="button" class="btn btn-success btn-modern me-2" onclick="validateAllData()">
                                    <i class="fas fa-check-circle me-2"></i>Validasi Data
                                </button>
                                <button type="submit" class="btn btn-primary btn-modern" id="submitBtn">
                                    <i class="fas fa-save me-2"></i>Simpan Data
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="text-center">
            <div class="spinner-modern mb-3"></div>
            <h5>Memproses data...</h5>
            <p class="text-muted">Mohon tunggu sebentar</p>
        </div>
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

            // Initialize calculations
            calculateTotals();
            calculateAverages();
            updatePreview();

            // Real-time validation
            $('input[type="number"]').on('input', function() {
                validateField(this);
            });

            // Form submission
            $('#kependudukanForm').on('submit', function(e) {
                e.preventDefault();

                if (validateAllData()) {
                    submitForm();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Data Tidak Valid!',
                        text: 'Mohon periksa kembali data yang diinput.',
                    });
                }
            });
        });

        function calculateTotals() {
            const usia0_17 = parseInt($('#usia_0_17').val()) || 0;
            const usia18_35 = parseInt($('#usia_18_35').val()) || 0;
            const usia36_55 = parseInt($('#usia_36_55').val()) || 0;
            const usia56_plus = parseInt($('#usia_56_plus').val()) || 0;

            const totalPenduduk = usia0_17 + usia18_35 + usia36_55 + usia56_plus;

            $('#total_penduduk_display').val(totalPenduduk.toLocaleString());

            updateAgeDistribution(totalPenduduk);
            validateGenderTotal();
            validateEducationTotal();
            calculateAverages();
            updatePreview();
        }

        function calculateAverages() {
            const totalPenduduk = getTotalPenduduk();
            const totalKK = parseInt($('#total_kk').val()) || 0;
            const totalRW = parseInt($('#total_rw').val()) || 0;
            const totalRT = parseInt($('#total_rt').val()) || 0;

            // Update preview
            updatePreview();
        }

        function getTotalPenduduk() {
            const usia0_17 = parseInt($('#usia_0_17').val()) || 0;
            const usia18_35 = parseInt($('#usia_18_35').val()) || 0;
            const usia36_55 = parseInt($('#usia_36_55').val()) || 0;
            const usia56_plus = parseInt($('#usia_56_plus').val()) || 0;

            return usia0_17 + usia18_35 + usia36_55 + usia56_plus;
        }

        function updateAgeDistribution(total) {
            const usia0_17 = parseInt($('#usia_0_17').val()) || 0;
            const usia18_35 = parseInt($('#usia_18_35').val()) || 0;
            const usia36_55 = parseInt($('#usia_36_55').val()) || 0;
            const usia56_plus = parseInt($('#usia_56_plus').val()) || 0;

            const groups = [
                { label: '0-17 tahun', value: usia0_17, color: 'primary' },
                { label: '18-35 tahun', value: usia18_35, color: 'success' },
                { label: '36-55 tahun', value: usia36_55, color: 'info' },
                { label: '56+ tahun', value: usia56_plus, color: 'warning' }
            ];

            let html = '';
            groups.forEach(group => {
                const percentage = total > 0 ? (group.value / total * 100).toFixed(1) : 0;
                html += `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>${group.label}</span>
                        <span class="badge bg-${group.color}">${group.value.toLocaleString()} (${percentage}%)</span>
                    </div>
                    <div class="progress progress-modern mb-3">
                        <div class="progress-bar bg-${group.color}" style="width: ${percentage}%"></div>
                    </div>
                `;
            });

            $('#ageDistribution').html(html);
        }

        function validateGenderTotal() {
            const totalPenduduk = getTotalPenduduk();
            const lakiLaki = parseInt($('#laki_laki').val()) || 0;
            const perempuan = parseInt($('#perempuan').val()) || 0;
            const totalGender = lakiLaki + perempuan;

            let html = '';
            let validationHtml = '';

            if (totalPenduduk > 0) {
                const lakiPercentage = (lakiLaki / totalPenduduk * 100).toFixed(1);
                const perempuanPercentage = (perempuan / totalPenduduk * 100).toFixed(1);

                html += `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Laki-laki</span>
                        <span class="badge bg-info">${lakiLaki.toLocaleString()} (${lakiPercentage}%)</span>
                    </div>
                    <div class="progress progress-modern mb-3">
                        <div class="progress-bar bg-info" style="width: ${lakiPercentage}%"></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Perempuan</span>
                        <span class="badge bg-danger">${perempuan.toLocaleString()} (${perempuanPercentage}%)</span>
                    </div>
                    <div class="progress progress-modern">
                        <div class="progress-bar bg-danger" style="width: ${perempuanPercentage}%"></div>
                    </div>
                `;

                if (totalGender !== totalPenduduk) {
                    validationHtml = `
                        <div class="alert alert-warning">
                            <small><i class="fas fa-exclamation-triangle"></i>
                            Total jenis kelamin (${totalGender.toLocaleString()}) tidak sama dengan total penduduk (${totalPenduduk.toLocaleString()})
                            </small>
                        </div>
                    `;
                    $('#laki_laki, #perempuan').addClass('field-invalid').removeClass('field-valid');
                } else {
                    validationHtml = `
                        <div class="alert alert-success">
                            <small><i class="fas fa-check"></i> Data jenis kelamin sudah sesuai</small>
                        </div>
                    `;
                    $('#laki_laki, #perempuan').addClass('field-valid').removeClass('field-invalid');
                }
            }

            $('#genderDistribution').html(html);
            $('#genderValidation').html(validationHtml);
            updatePreview();
        }

        function validateEducationTotal() {
            const totalPenduduk = getTotalPenduduk();
            const sd = parseInt($('#sd_sederajat').val()) || 0;
            const smp = parseInt($('#smp_sederajat').val()) || 0;
            const sma = parseInt($('#sma_sederajat').val()) || 0;
            const diploma = parseInt($('#diploma_s1_plus').val()) || 0;
            const totalEducation = sd + smp + sma + diploma;

            let html = '';
            let validationHtml = '';

            if (totalPenduduk > 0) {
                const educations = [
                    { label: 'SD/Sederajat', value: sd, color: 'primary' },
                    { label: 'SMP/Sederajat', value: smp, color: 'success' },
                    { label: 'SMA/Sederajat', value: sma, color: 'info' },
                    { label: 'Diploma/S1+', value: diploma, color: 'warning' }
                ];

                educations.forEach(edu => {
                    const percentage = (edu.value / totalPenduduk * 100).toFixed(1);
                    html += `
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>${edu.label}</span>
                            <span class="badge bg-${edu.color}">${edu.value.toLocaleString()} (${percentage}%)</span>
                        </div>
                        <div class="progress progress-modern mb-2">
                            <div class="progress-bar bg-${edu.color}" style="width: ${percentage}%"></div>
                        </div>
                    `;
                });

                if (totalEducation > totalPenduduk) {
                    validationHtml = `
                        <div class="alert alert-danger">
                            <small><i class="fas fa-times"></i>
                            Total pendidikan (${totalEducation.toLocaleString()}) melebihi total penduduk (${totalPenduduk.toLocaleString()})
                            </small>
                        </div>
                    `;
                    $('#sd_sederajat, #smp_sederajat, #sma_sederajat, #diploma_s1_plus').addClass('field-invalid').removeClass('field-valid');
                } else {
                    validationHtml = `
                        <div class="alert alert-success">
                            <small><i class="fas fa-check"></i> Data pendidikan valid (${((totalEducation/totalPenduduk)*100).toFixed(1)}% dari total penduduk)</small>
                        </div>
                    `;
                    $('#sd_sederajat, #smp_sederajat, #sma_sederajat, #diploma_s1_plus').addClass('field-valid').removeClass('field-invalid');
                }
            }

            $('#educationDistribution').html(html);
            $('#educationValidation').html(validationHtml);
            updatePreview();
        }

        function updatePreview() {
            const totalPenduduk = getTotalPenduduk();
            const totalKK = parseInt($('#total_kk').val()) || 0;
            const totalRW = parseInt($('#total_rw').val()) || 0;
            const totalRT = parseInt($('#total_rt').val()) || 0;
            const lakiLaki = parseInt($('#laki_laki').val()) || 0;
            const perempuan = parseInt($('#perempuan').val()) || 0;

            // Update preview values
            $('#previewTotalPenduduk').text(totalPenduduk.toLocaleString());
            $('#previewTotalKK').text(totalKK.toLocaleString());
            $('#previewTotalRW').text(totalRW);
            $('#previewTotalRT').text(totalRT);

            // Calculate averages
            const avgKK = totalKK > 0 ? (totalPenduduk / totalKK).toFixed(1) : 0;
            $('#previewAvgKK').text(avgKK + ' jiwa');

            // Gender ratio
            const genderRatio = perempuan > 0 ? ((lakiLaki / perempuan) * 100).toFixed(1) : 0;
            $('#previewGenderRatio').text(genderRatio + '%');

            // Dominant age group
            const ageGroups = {
                '0-17 tahun': parseInt($('#usia_0_17').val()) || 0,
                '18-35 tahun': parseInt($('#usia_18_35').val()) || 0,
                '36-55 tahun': parseInt($('#usia_36_55').val()) || 0,
                '56+ tahun': parseInt($('#usia_56_plus').val()) || 0
            };

            const dominantAge = Object.keys(ageGroups).reduce((a, b) => ageGroups[a] > ageGroups[b] ? a : b);
            $('#previewDominantAge').text(dominantAge);

            // High education percentage
            const sma = parseInt($('#sma_sederajat').val()) || 0;
            const diploma = parseInt($('#diploma_s1_plus').val()) || 0;
            const highEducation = totalPenduduk > 0 ? (((sma + diploma) / totalPenduduk) * 100).toFixed(1) : 0;
            $('#previewHighEducation').text(highEducation + '%');
        }

        function validateField(field) {
            const value = parseInt(field.value) || 0;

            if (value >= 0) {
                $(field).removeClass('field-invalid').addClass('field-valid');
            } else {
                $(field).removeClass('field-valid').addClass('field-invalid');
            }
        }

        function validateAllData() {
            const totalPenduduk = getTotalPenduduk();
            const lakiLaki = parseInt($('#laki_laki').val()) || 0;
            const perempuan = parseInt($('#perempuan').val()) || 0;
            const totalGender = lakiLaki + perempuan;

            const sd = parseInt($('#sd_sederajat').val()) || 0;
            const smp = parseInt($('#smp_sederajat').val()) || 0;
            const sma = parseInt($('#sma_sederajat').val()) || 0;
            const diploma = parseInt($('#diploma_s1_plus').val()) || 0;
            const totalEducation = sd + smp + sma + diploma;

            let errors = [];

            if (totalPenduduk === 0) {
                errors.push('Total penduduk tidak boleh 0');
            }

            if (totalGender !== totalPenduduk) {
                errors.push(`Total jenis kelamin (${totalGender.toLocaleString()}) harus sama dengan total penduduk (${totalPenduduk.toLocaleString()})`);
            }

            if (totalEducation > totalPenduduk) {
                errors.push(`Total pendidikan (${totalEducation.toLocaleString()}) tidak boleh melebihi total penduduk (${totalPenduduk.toLocaleString()})`);
            }

            if (errors.length > 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal!',
                    html: '<ul class="text-left">' + errors.map(err => `<li>${err}</li>`).join('') + '</ul>'
                });
                return false;
            }

            Swal.fire({
                icon: 'success',
                title: 'Validasi Berhasil!',
                text: 'Semua data sudah valid dan konsisten.',
                timer: 2000,
                showConfirmButton: false
            });

            return true;
        }

        function submitForm() {
            $('#loadingOverlay').css('display', 'flex');
            $('#submitBtn').prop('disabled', true);

            // Submit the form
            document.getElementById('kependudukanForm').submit();
        }
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>

@endpush
