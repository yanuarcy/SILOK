@extends('Template.template')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">

    <style>
        .form-control:focus {
            border-color: #6777ef;
            box-shadow: 0 0 0 0.2rem rgba(103, 119, 239, 0.25);
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .section-divider {
            border-top: 2px solid #e9ecef;
            margin: 30px 0 20px 0;
            padding-top: 20px;
        }

        .required {
            color: #dc3545;
        }

        .rt-rw-section {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .form-group label {
            font-weight: 600;
            color: #34395e;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .form-control:hover {
            border-color: #6777ef;
        }

        .btn {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
        }

        .alert {
            border-radius: 8px;
            border: none;
        }

        .alert-info {
            background: linear-gradient(135deg, #e3f2fd 0%, #f8f9fa 100%);
            color: #1976d2;
            border-left: 4px solid #2196f3;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .auto-fill-indicator {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 12px;
            margin-bottom: 15px;
            text-align: center;
            animation: fadeIn 0.5s ease-in;
        }

        .manual-fill-indicator {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: black;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 12px;
            margin-bottom: 15px;
            text-align: center;
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .profile-preview-table {
            font-size: 13px;
        }

        .profile-preview-table td {
            padding: 4px 8px;
            border: 1px solid #dee2e6;
        }

        .data-anak-section {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            position: relative;
        }

        .remove-anak-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1;
        }

        .add-anak-btn {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border: none;
            color: white;
            padding: 8px 15px;
            border-radius: 6px;
            font-size: 12px;
            margin-top: 10px;
        }

        .add-anak-btn:hover {
            background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
            color: white;
        }

        .file-upload-section {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .file-input-group {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            background: white;
        }

        .file-input-group label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
            display: block;
        }

        .file-input-group .form-text {
            font-size: 11px;
            color: #6c757d;
        }

        .section-header {
            background: linear-gradient(135deg, #6777ef 0%, #5a67d8 100%);
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .section-header h5 {
            margin: 0;
            font-weight: 600;
        }

        .data-pewaris-section, .data-saksi-section {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .saksi-section {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
        }

        .saksi-header {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #212529;
            padding: 8px 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-weight: 600;
        }
    </style>
@endpush

@section('Dashboard')
    @include('admin.dashboard.header')
    @include('admin.dashboard.sidebar')

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Buat Permohonan SKAW</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('skaw.permohonan-saya') }}">SKAW</a>
                    </div>
                    <div class="breadcrumb-item">Buat Permohonan</div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Form SKAW (Surat Keterangan Ahli Waris)</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-info btn-sm" id="showTipsBtn">
                                    <i class="fas fa-lightbulb"></i> Tips Penggunaan
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="createSkawForm" enctype="multipart/form-data">
                                @csrf

                                {{-- Section 1: Data Pemohon --}}
                                <div class="section-header">
                                    <h5><i class="fas fa-user"></i> 1. Data Pemohon</h5>
                                </div>

                                <!-- Auto-fill indicator -->
                                <div id="autoFillIndicator" class="auto-fill-indicator" style="display: none;">
                                    <i class="fas fa-magic"></i> Data pemohon berhasil diisi otomatis dari profile Anda
                                </div>

                                <!-- Manual fill indicator -->
                                <div id="manualFillIndicator" class="manual-fill-indicator" style="display: none;">
                                    <i class="fas fa-edit"></i> Data profile tidak lengkap, silakan isi manual
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Lengkap <span class="required">*</span></label>
                                            <input type="text" class="form-control" name="nama_lengkap"
                                                   value="{{ $user->name ?? '' }}" placeholder="Nama lengkap pemohon" required>
                                        </div>

                                        <div class="form-group">
                                            <label>NIK <span class="required">*</span></label>
                                            <input type="text" class="form-control" name="nik"
                                                   value="{{ $user->nik ?? '' }}" placeholder="NIK pemohon" maxlength="16" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Alamat <span class="required">*</span></label>
                                            <textarea class="form-control" name="alamat" rows="3"
                                                      placeholder="Alamat lengkap" required>{{ $user->address ?? '' }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Pekerjaan <span class="required">*</span></label>
                                            <input type="text" class="form-control" name="pekerjaan"
                                                   value="{{ $user->pekerjaan ?? '' }}" placeholder="Pekerjaan" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Jenis Kelamin <span class="required">*</span></label>
                                            <select class="form-control" name="jenis_kelamin" required>
                                                <option value="">Pilih Jenis Kelamin</option>
                                                <option value="L" {{ ($user->gender ?? '') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                                <option value="P" {{ ($user->gender ?? '') === 'P' ? 'selected' : '' }}>Perempuan</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Tempat Lahir <span class="required">*</span></label>
                                            <input type="text" class="form-control" name="tempat_lahir"
                                                   value="{{ $user->tempat_lahir ?? '' }}" placeholder="Tempat lahir" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tanggal Lahir <span class="required">*</span></label>
                                            <input type="date" class="form-control" name="tanggal_lahir"
                                                   value="{{ $user->tanggal_lahir ?? '' }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Agama <span class="required">*</span></label>
                                            <select class="form-control" name="agama" required>
                                                <option value="">Pilih Agama</option>
                                                <option value="Islam" {{ ($user->agama ?? '') === 'Islam' ? 'selected' : '' }}>Islam</option>
                                                <option value="Kristen" {{ ($user->agama ?? '') === 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                                <option value="Katolik" {{ ($user->agama ?? '') === 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                                <option value="Hindu" {{ ($user->agama ?? '') === 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                                <option value="Buddha" {{ ($user->agama ?? '') === 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                                <option value="Konghucu" {{ ($user->agama ?? '') === 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Status Perkawinan <span class="required">*</span></label>
                                            <select class="form-control" name="status_perkawinan" required>
                                                <option value="">Pilih Status</option>
                                                <option value="Belum Kawin" {{ ($user->status_perkawinan ?? '') === 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                                                <option value="Kawin" {{ ($user->status_perkawinan ?? '') === 'Kawin' ? 'selected' : '' }}>Kawin</option>
                                                <option value="Cerai Hidup" {{ ($user->status_perkawinan ?? '') === 'Cerai Hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                                                <option value="Cerai Mati" {{ ($user->status_perkawinan ?? '') === 'Cerai Mati' ? 'selected' : '' }}>Cerai Mati</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Kewarganegaraan <span class="required">*</span></label>
                                            <input type="text" class="form-control" name="kewarganegaraan"
                                                   value="{{ $user->kewarganegaraan ?? 'Indonesia' }}" placeholder="Kewarganegaraan" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Nomor KK <span class="required">*</span></label>
                                            <input type="text" class="form-control" name="nomor_kk"
                                                   value="{{ $user->nomor_kk ?? '' }}" placeholder="Nomor Kartu Keluarga" maxlength="16" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" class="form-control" name="email"
                                                   value="{{ $user->email ?? '' }}" placeholder="Email" required>
                                        </div>

                                        <div class="form-group">
                                            <label>No. Telepon</label>
                                            <input type="text" class="form-control" name="no_telepon"
                                                   value="{{ $user->telp ?? '' }}" placeholder="Nomor telepon">
                                        </div>
                                    </div>
                                </div>

                                {{-- RT/RW Selection --}}
                                <div class="rt-rw-section">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-map-marker-alt"></i> Lokasi RT/RW
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>RW <span class="required">*</span></label>
                                                <select name="rw" id="rw-select-skaw" class="form-control" required>
                                                    <option value="">Pilih RW</option>
                                                    <option value="01">RW 01</option>
                                                    <option value="02">RW 02</option>
                                                    <option value="03">RW 03</option>
                                                    <option value="04">RW 04</option>
                                                    <option value="05">RW 05</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>RT <span class="required">*</span></label>
                                                <select name="rt" id="rt-select-skaw" class="form-control" required disabled>
                                                    <option value="">Pilih RT</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Data Khusus SKAW --}}
                                <div class="section-divider">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nomor Akta Perkawinan <span class="required">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-info text-white">KW</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="nomor_akta_perkawinan"
                                                        id="nomor_akta_perkawinan"
                                                        placeholder="xxxx-KW-ddmmyyyy-xxxx"
                                                        pattern="[0-9]{4}-KW-[0-9]{8}-[0-9]{4}"
                                                        title="Format: xxxx-KW-ddmmyyyy-xxxx (contoh: 3671-KW-05112023-0006)"
                                                        required>
                                                </div>
                                                <small class="form-text text-muted">Format: xxxx-KW-ddmmyyyy-xxxx</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Tanggal Terbit Akta Perkawinan <span class="required">*</span></label>
                                                <input type="date" class="form-control" name="tanggal_terbit_akta_perkawinan" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Jumlah Anak <span class="required">*</span></label>
                                                <select class="form-control" name="jumlah_anak" id="jumlah_anak" required>
                                                    <option value="">Pilih Jumlah Anak</option>
                                                    <option value="0">0 (Tidak ada anak)</option>
                                                    <option value="1">1 Anak</option>
                                                    <option value="2">2 Anak</option>
                                                    <option value="3">3 Anak</option>
                                                    <option value="4">4 Anak</option>
                                                    <option value="5">5 Anak</option>
                                                    <option value="6">6 Anak</option>
                                                    <option value="7">7 Anak</option>
                                                    <option value="8">8 Anak</option>
                                                    <option value="9">9 Anak</option>
                                                    <option value="10">10 Anak</option>
                                                </select>
                                                <small class="form-text text-muted">Pilih jumlah anak dari pewaris</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- Bisa ditambahkan field lain atau kosong -->
                                        </div>
                                    </div>
                                </div>

                                {{-- Data Anak (Dynamic) --}}
                                <div id="data-anak-container">
                                    <div class="section-header">
                                        <h5><i class="fas fa-child"></i> Data Anak</h5>
                                    </div>
                                    <div id="data-anak-list">
                                        <!-- Data anak akan ditambahkan di sini secara dinamis -->
                                    </div>
                                </div>

                                {{-- Section 2: Data Pewaris --}}
                                <div class="section-header">
                                    <h5><i class="fas fa-user-tie"></i> 2. Data Pewaris (Yang Meninggal)</h5>
                                </div>

                                <div class="data-pewaris-section">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>NIK Pewaris <span class="required">*</span></label>
                                                <input type="text" class="form-control" name="pewaris_nik"
                                                       placeholder="NIK pewaris" maxlength="16" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Nama Lengkap Pewaris <span class="required">*</span></label>
                                                <input type="text" class="form-control" name="pewaris_nama_lengkap"
                                                       placeholder="Nama lengkap pewaris" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Gelar (jika ada)</label>
                                                <input type="text" class="form-control" name="pewaris_gelar"
                                                       placeholder="Gelar pewaris (opsional)">
                                            </div>

                                            <div class="form-group">
                                                <label>Tempat Lahir <span class="required">*</span></label>
                                                <input type="text" class="form-control" name="pewaris_tempat_lahir"
                                                       placeholder="Tempat lahir pewaris" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Tanggal Lahir <span class="required">*</span></label>
                                                <input type="date" class="form-control" name="pewaris_tanggal_lahir" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Tempat Tinggal Terakhir <span class="required">*</span></label>
                                                <textarea class="form-control" name="pewaris_tempat_tinggal_terakhir"
                                                          rows="3" placeholder="Alamat tempat tinggal terakhir pewaris" required></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label>Tanggal Kematian <span class="required">*</span></label>
                                                <input type="date" class="form-control" name="pewaris_tanggal_kematian" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Tempat Kematian <span class="required">*</span></label>
                                                <input type="text" class="form-control" name="pewaris_tempat_kematian"
                                                       placeholder="Tempat kematian pewaris" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Nomor Akta Kematian <span class="required">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-danger text-white">KM</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="pewaris_nomor_akta_kematian"
                                                        id="nomor_akta_kematian"
                                                        placeholder="xxxx-KM-ddmmyyyy-xxxx"
                                                        pattern="[0-9]{4}-KM-[0-9]{8}-[0-9]{4}"
                                                        title="Format: xxxx-KM-ddmmyyyy-xxxx (contoh: 3578-KM-29072024-0001)"
                                                        required>
                                                </div>
                                                <small class="form-text text-muted">Format: xxxx-KM-ddmmyyyy-xxxx</small>
                                            </div>

                                            <div class="form-group">
                                                <label>Tanggal Terbit Akta Kematian <span class="required">*</span></label>
                                                <input type="date" class="form-control" name="pewaris_tanggal_terbit_akta_kematian" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Section 3: Data Saksi (2 Saksi) --}}
                                <div class="section-header">
                                    <h5><i class="fas fa-users"></i> 3. Data Saksi</h5>
                                </div>

                                <div class="data-saksi-section">
                                    {{-- Saksi 1 --}}
                                    <div class="saksi-section">
                                        <div class="saksi-header">
                                            <i class="fas fa-user"></i> Saksi 1
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nama Lengkap Saksi 1 <span class="required">*</span></label>
                                                    <input type="text" class="form-control" name="saksi1_nama_lengkap"
                                                           placeholder="Nama lengkap saksi 1" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Gelar (jika ada)</label>
                                                    <input type="text" class="form-control" name="saksi1_gelar"
                                                           placeholder="Gelar saksi 1 (opsional)">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label>Alamat Saksi 1 <span class="required">*</span></label>
                                                    <textarea class="form-control" name="saksi1_alamat"
                                                              rows="3" placeholder="Alamat lengkap saksi 1" required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Saksi 2 --}}
                                    <div class="saksi-section">
                                        <div class="saksi-header">
                                            <i class="fas fa-user"></i> Saksi 2
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nama Lengkap Saksi 2 <span class="required">*</span></label>
                                                    <input type="text" class="form-control" name="saksi2_nama_lengkap"
                                                           placeholder="Nama lengkap saksi 2" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Gelar (jika ada)</label>
                                                    <input type="text" class="form-control" name="saksi2_gelar"
                                                           placeholder="Gelar saksi 2 (opsional)">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label>Alamat Saksi 2 <span class="required">*</span></label>
                                                    <textarea class="form-control" name="saksi2_alamat"
                                                              rows="3" placeholder="Alamat lengkap saksi 2" required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Section 4: File Persyaratan SKAW --}}
                                <div class="section-header">
                                    <h5><i class="fas fa-folder-open"></i> 4. File Persyaratan SKAW</h5>
                                </div>

                                <div class="file-upload-section">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Catatan:</strong> Upload file dalam format PDF, JPG, JPEG, atau PNG. Maksimal ukuran file 2MB per file.
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <!-- File 1-6 -->
                                            <div class="file-input-group">
                                                <label>1. KTP/Kartu Keluarga/Dokumen Kependudukan Pewaris Lainnya <span class="required">*</span></label>
                                                <input type="file" class="form-control" name="files[ktp_pewaris]"
                                                       accept=".pdf,.jpg,.jpeg,.png" required>
                                                <small class="form-text">Format: PDF, JPG, JPEG, PNG | Max: 2MB</small>
                                            </div>

                                            <div class="file-input-group">
                                                <label>2. Akta Kematian Pewaris <span class="required">*</span></label>
                                                <input type="file" class="form-control" name="files[akta_kematian]"
                                                       accept=".pdf,.jpg,.jpeg,.png" required>
                                                <small class="form-text">Format: PDF, JPG, JPEG, PNG | Max: 2MB</small>
                                            </div>

                                            <div class="file-input-group">
                                                <label>3. Akta Kelahiran/Copy Kartu Keluarga Pewaris <span class="required">*</span></label>
                                                <input type="file" class="form-control" name="files[akta_kelahiran_pewaris]"
                                                       accept=".pdf,.jpg,.jpeg,.png" required>
                                                <small class="form-text">Format: PDF, JPG, JPEG, PNG | Max: 2MB</small>
                                            </div>

                                            <div class="file-input-group">
                                                <label>4. Akta Kematian Ahli Waris (apabila Ahli Waris meninggal dunia)</label>
                                                <input type="file" class="form-control" name="files[akta_kematian_ahli_waris]"
                                                       accept=".pdf,.jpg,.jpeg,.png">
                                                <small class="form-text">Format: PDF, JPG, JPEG, PNG | Max: 2MB</small>
                                            </div>

                                            <div class="file-input-group">
                                                <label>5. Akta Kelahiran Ahli Waris <span class="required">*</span></label>
                                                <input type="file" class="form-control" name="files[akta_kelahiran_ahli_waris]"
                                                       accept=".pdf,.jpg,.jpeg,.png" required>
                                                <small class="form-text">Format: PDF, JPG, JPEG, PNG | Max: 2MB</small>
                                            </div>

                                            <div class="file-input-group">
                                                <label>6. KTP Ahli Waris <span class="required">*</span></label>
                                                <input type="file" class="form-control" name="files[ktp_ahli_waris]"
                                                       accept=".pdf,.jpg,.jpeg,.png" required>
                                                <small class="form-text">Format: PDF, JPG, JPEG, PNG | Max: 2MB</small>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <!-- File 7-11 -->
                                            <div class="file-input-group">
                                                <label>7. Kartu Keluarga Ahli Waris <span class="required">*</span></label>
                                                <input type="file" class="form-control" name="files[kk_ahli_waris]"
                                                       accept=".pdf,.jpg,.jpeg,.png" required>
                                                <small class="form-text">Format: PDF, JPG, JPEG, PNG | Max: 2MB</small>
                                            </div>

                                            <div class="file-input-group">
                                                <label>8. KTP 2 (dua) Orang Saksi <span class="required">*</span></label>
                                                <input type="file" class="form-control" name="files[ktp_saksi]"
                                                       accept=".pdf,.jpg,.jpeg,.png" required>
                                                <small class="form-text">Format: PDF, JPG, JPEG, PNG | Max: 2MB</small>
                                            </div>

                                            <div class="file-input-group">
                                                <label>9. Surat Pengantar dari Ketua RT dengan diketahui oleh Ketua RW <span class="required">*</span></label>
                                                <input type="file" class="form-control" name="files[surat_pengantar_rt]"
                                                       accept=".pdf,.jpg,.jpeg,.png" required>
                                                <small class="form-text">Format: PDF, JPG, JPEG, PNG | Max: 2MB</small>
                                            </div>

                                            <div class="file-input-group">
                                                <label>10. Surat Pernyataan Para Ahli Waris sesuai dengan Silsilah Keluarga yang Menyatakan sebagai Ahli Waris dan Ditandatangani oleh Para Ahli Waris dan 2 (dua) Orang Saksi serta Dibubuhi Meterai <span class="required">*</span></label>
                                                <input type="file" class="form-control" name="files[surat_pernyataan_ahli_waris]"
                                                       accept=".pdf,.jpg,.jpeg,.png" required>
                                                <small class="form-text">Format: PDF, JPG, JPEG, PNG | Max: 2MB</small>
                                            </div>

                                            <div class="file-input-group">
                                                <label>11. Surat Pernyataan Kebenaran Semua Kelengkapan Dokumen Menjadi Tanggung Jawab Pemohon
                                                    <a href="#" class="text-success ml-2" id="downloadTemplateBtn">
                                                        <i class="fas fa-download"></i> (Download Template)
                                                    </a> <span class="required">*</span>
                                                </label>
                                                <input type="file" class="form-control" name="files[surat_pernyataan_kebenaran]"
                                                       accept=".pdf,.jpg,.jpeg,.png" required>
                                                <small class="form-text">Format: PDF, JPG, JPEG, PNG | Max: 2MB</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="alert alert-warning mt-3">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <strong>Penting:</strong>
                                        <ul class="small mb-0 mt-2">
                                            <li>Pastikan semua file dapat dibaca dengan jelas</li>
                                            <li>File yang tidak dapat dibaca akan diminta untuk diupload ulang</li>
                                            <li>Gunakan scanner atau foto dengan kualitas baik</li>
                                            <li>File yang ditandai <span class="required">*</span> wajib diupload</li>
                                        </ul>
                                    </div>
                                </div>

                                {{-- Submit Section --}}
                                <div class="section-divider">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="card-title">Catatan Penting:</h6>
                                                    <ul class="small text-muted">
                                                        <li>Pastikan semua data telah diisi dengan benar dan lengkap</li>
                                                        <li>Data anak akan otomatis muncul sesuai jumlah yang diisi</li>
                                                        <li>File persyaratan yang wajib harus diupload semua</li>
                                                        <li>Permohonan SKAW akan diproses melalui tahapan Front Office → Back Office → Lurah → Camat</li>
                                                        <li>Anda dapat memantau status permohonan di halaman "Permohonan Saya"</li>
                                                        <li>Draft otomatis tersimpan setiap 3 detik untuk menghindari kehilangan data</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer text-right border-0 pt-3">
                                    <button type="submit" name="submit_type" value="draft" class="btn btn-secondary mr-2" id="saveDraftBtn">
                                        <i class="fas fa-save"></i> Simpan Draft
                                    </button>
                                    <button type="submit" name="submit_type" value="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-paper-plane"></i> Ajukan Permohonan
                                    </button>
                                    <a href="{{ route('skaw.permohonan-saya') }}" class="btn btn-light ml-2">
                                        <i class="fas fa-times"></i> Batal
                                    </a>
                                </div>
                            </form>
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


    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Initialize form
            initializeSkawForm();

            // Check and auto-fill profile data
            setTimeout(checkAndAutoFillProfile, 500);

            // Initialize auto-save
            bindAutoSaveEvents();
            bindBeforeUnloadWarning();

            // Load draft
            setTimeout(loadSkawDraft, 1000);

            // Tips button
            $('#showTipsBtn').on('click', showTipsDialog);

            // Download template button
            $('#downloadTemplateBtn').on('click', downloadTemplate);

            // Form submission
            $('#createSkawForm').on('submit', handleFormSubmit);

            console.log('✅ SKAW Create form initialized successfully');
        });

        function initializeSkawForm() {
            // Initialize RW-RT handling
            initializeRwRt();

            // Initialize dynamic data anak
            initializeDataAnak();

            console.log('✅ SKAW form components initialized');
        }

        function initializeRwRt() {
            $('#rw-select-skaw').on('change', function() {
                const selectedRW = $(this).val();
                const rtSelect = $('#rt-select-skaw');

                rtSelect.empty().append('<option value="">Pilih RT</option>').prop('disabled', true);

                if (selectedRW) {
                    // Generate RT options (1-10 untuk setiap RW)
                    for (let i = 1; i <= 10; i++) {
                        const rt = i === 10 ? '10' : i.toString().padStart(2, '0');
                        rtSelect.append(`<option value="${rt}">RT ${rt}</option>`);
                    }
                    rtSelect.prop('disabled', false);
                }
            });

            // Set user's RW/RT if available
            const userRW = '{{ $user->rw ?? "" }}';
            const userRT = '{{ $user->rt ?? "" }}';

            if (userRW) {
                $('#rw-select-skaw').val(userRW).trigger('change');
                if (userRT) {
                    setTimeout(() => $('#rt-select-skaw').val(userRT), 100);
                }
            }
        }

        function initializeDataAnak() {
            let anakCounter = 0;

            $('#jumlah_anak').on('change', function() {
                const jumlah = parseInt($(this).val()) || 0;
                const container = $('#data-anak-list');

                // Clear existing anak forms
                container.empty();
                anakCounter = 0;

                // Create forms for each anak
                for (let i = 1; i <= jumlah; i++) {
                    addAnakForm(i);
                }

                // Show/hide container
                if (jumlah > 0) {
                    $('#data-anak-container').show();
                } else {
                    $('#data-anak-container').hide();
                }
            });

            function addAnakForm(number) {
                anakCounter++;
                const anakForm = `
                    <div class="data-anak-section" id="anak-${anakCounter}">
                        <div class="row">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-child"></i> Data Anak ke-${number}
                                </h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Lengkap <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="data_anak[${anakCounter-1}][nama_lengkap]"
                                           placeholder="Nama lengkap anak" required>
                                </div>
                                <div class="form-group">
                                    <label>Tempat Lahir <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="data_anak[${anakCounter-1}][tempat_lahir]"
                                           placeholder="Tempat lahir anak" required>
                                </div>
                                <div class="form-group">
                                    <label>Tanggal Lahir <span class="required">*</span></label>
                                    <input type="date" class="form-control" name="data_anak[${anakCounter-1}][tanggal_lahir]" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jenis Kelamin <span class="required">*</span></label>
                                    <select class="form-control" name="data_anak[${anakCounter-1}][jenis_kelamin]" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Alamat <span class="required">*</span></label>
                                    <textarea class="form-control" name="data_anak[${anakCounter-1}][alamat]"
                                              rows="4" placeholder="Alamat anak" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                $('#data-anak-list').append(anakForm);
            }

            // Hide container initially
            $('#data-anak-container').hide();
        }

        function checkAndAutoFillProfile() {
            const userData = @json($user);

            const hasCompleteProfile = userData.name && userData.address && userData.pekerjaan &&
                                     userData.gender && userData.tempat_lahir && userData.tanggal_lahir &&
                                     userData.agama && userData.status_perkawinan && userData.nik;

            if (hasCompleteProfile) {
                Swal.fire({
                    title: 'Auto-Fill Data Pemohon',
                    html: `
                        <div class="text-left">
                            <p><strong>Data pemohon akan diisi otomatis dari profile Anda:</strong></p>
                            <hr>
                            <table class="table table-sm profile-preview-table">
                                <tr><td><strong>Nama:</strong></td><td>${userData.name || '-'}</td></tr>
                                <tr><td><strong>NIK:</strong></td><td>${userData.nik || '-'}</td></tr>
                                <tr><td><strong>Alamat:</strong></td><td>${userData.address ? userData.address.substring(0, 50) + '...' : '-'}</td></tr>
                                <tr><td><strong>Pekerjaan:</strong></td><td>${userData.pekerjaan || '-'}</td></tr>
                                <tr><td><strong>Jenis Kelamin:</strong></td><td>${userData.gender || '-'}</td></tr>
                                <tr><td><strong>Tempat Lahir:</strong></td><td>${userData.tempat_lahir || '-'}</td></tr>
                                <tr><td><strong>Tanggal Lahir:</strong></td><td>${userData.tanggal_lahir || '-'}</td></tr>
                                <tr><td><strong>Agama:</strong></td><td>${userData.agama || '-'}</td></tr>
                            </table>
                            <hr>
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                Data dapat diubah setelah di-isi otomatis jika diperlukan.
                            </small>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Isi Otomatis',
                    cancelButtonText: 'Tidak, Isi Manual',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fillProfileData(userData);
                        showAutoFillIndicator();
                        Swal.fire({
                            icon: 'success',
                            title: 'Data Berhasil Diisi',
                            text: 'Data pemohon berhasil diisi otomatis dari profile Anda.',
                            timer: 2500,
                            showConfirmButton: false
                        });
                    } else {
                        showManualFillIndicator();
                    }
                });
            } else {
                showManualFillIndicator();
            }
        }

        function fillProfileData(userData) {
            if (userData.name) $('[name="nama_lengkap"]').val(userData.name);
            if (userData.nik) $('[name="nik"]').val(userData.nik);
            if (userData.address) $('[name="alamat"]').val(userData.address);
            if (userData.pekerjaan) $('[name="pekerjaan"]').val(userData.pekerjaan);
            if (userData.gender) $('[name="jenis_kelamin"]').val(userData.gender);
            if (userData.tempat_lahir) $('[name="tempat_lahir"]').val(userData.tempat_lahir);
            if (userData.tanggal_lahir) $('[name="tanggal_lahir"]').val(userData.tanggal_lahir);
            if (userData.agama) $('[name="agama"]').val(userData.agama);
            if (userData.status_perkawinan) $('[name="status_perkawinan"]').val(userData.status_perkawinan);
            if (userData.kewarganegaraan) $('[name="kewarganegaraan"]').val(userData.kewarganegaraan);
            if (userData.nomor_kk) $('[name="nomor_kk"]').val(userData.nomor_kk);
            if (userData.email) $('[name="email"]').val(userData.email);
            if (userData.telp) $('[name="no_telepon"]').val(userData.telp);
        }

        function showAutoFillIndicator() {
            $('#autoFillIndicator').show();
            $('#manualFillIndicator').hide();
            setTimeout(() => $('#autoFillIndicator').fadeOut(), 5000);
        }

        function showManualFillIndicator() {
            $('#manualFillIndicator').show();
            $('#autoFillIndicator').hide();
            setTimeout(() => $('#manualFillIndicator').fadeOut(), 5000);
        }

        function showTipsDialog() {
            Swal.fire({
                icon: 'info',
                title: 'Tips Penggunaan Form SKAW',
                html: `
                    <div class="text-left">
                        <strong>Fitur yang tersedia:</strong><br>
                        • <kbd>Ctrl+S</kbd> untuk menyimpan<br>
                        • Auto-save draft setiap 3 detik<br>
                        • Auto-fill data dari profile<br>
                        • Dynamic form untuk data anak<br>
                        • Draft otomatis dimuat saat membuka form<br><br>

                        <strong>Data yang diperlukan:</strong><br>
                        • Data pemohon (auto-fill dari profile)<br>
                        • Data pewaris (yang meninggal dunia)<br>
                        • Data 2 orang saksi<br>
                        • 11 file persyaratan wajib<br><br>

                        <strong>Proses SKAW:</strong><br>
                        • Submit → Front Office Review<br>
                        • Tanda Terima & Draft SKAW dibuat<br>
                        • Jadwal Sidang → Sidang Selesai<br>
                        • Upload Evidence → Approval Lurah & Camat<br>
                        • SKAW Final tersedia untuk diunduh<br><br>

                        <strong>Data Anak:</strong><br>
                        • Form otomatis muncul sesuai jumlah anak<br>
                        • Setiap anak: Nama, Tempat/Tanggal Lahir, JK, Alamat<br>
                        • Jika tidak ada anak, isi 0 (nol)
                    </div>
                `,
                confirmButtonText: 'Mengerti',
                width: '600px'
            });
        }

        function downloadTemplate() {
            // Placeholder for template download
            Swal.fire({
                icon: 'info',
                title: 'Download Template',
                text: 'Template surat pernyataan kebenaran akan segera tersedia untuk diunduh.',
                confirmButtonText: 'OK'
            });
        }

        function handleFormSubmit(e) {
            e.preventDefault();

            if (!validateSkawForm()) {
                return;
            }

            const submitType = e.originalEvent.submitter.value;
            const isSubmit = submitType === 'submit';
            const btn = isSubmit ? $('#submitBtn') : $('#saveDraftBtn');

            btn.html(`<i class="fas fa-spinner fa-spin"></i> ${isSubmit ? 'Mengajukan...' : 'Menyimpan...'}`).prop('disabled', true);

            const formData = new FormData(this);
            formData.append('submit_type', submitType);

            $.ajax({
                url: "{{ route('skaw.store') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        localStorage.removeItem('skaw_draft');

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            window.location.href = "{{ route('skaw.permohonan-saya') }}";
                        });
                    }
                },
                error: function(xhr) {
                    btn.html(`<i class="fas ${isSubmit ? 'fa-paper-plane' : 'fa-save'}"></i> ${isSubmit ? 'Ajukan Permohonan' : 'Simpan Draft'}`).prop('disabled', false);

                    if (xhr.status === 422) {
                        showValidationErrors(xhr.responseJSON.errors);
                    } else {
                        Swal.fire('Error!', xhr.responseJSON?.message || 'Terjadi kesalahan.', 'error');
                    }
                }
            });
        }

        function validateSkawForm() {
            let isValid = true;
            const errors = [];

            // Reset previous validation states
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            // Required fields validation
            const requiredFields = [
                'nama_lengkap', 'nik', 'alamat', 'pekerjaan', 'jenis_kelamin',
                'tempat_lahir', 'tanggal_lahir', 'agama', 'status_perkawinan',
                'kewarganegaraan', 'nomor_kk', 'email', 'rt', 'rw', 'nomor_akta_perkawinan',
                'tanggal_terbit_akta_perkawinan', 'jumlah_anak',
                'pewaris_nik', 'pewaris_nama_lengkap', 'pewaris_tempat_lahir',
                'pewaris_tanggal_lahir', 'pewaris_tempat_tinggal_terakhir',
                'pewaris_tanggal_kematian', 'pewaris_tempat_kematian',
                'pewaris_nomor_akta_kematian', 'pewaris_tanggal_terbit_akta_kematian',
                'saksi1_nama_lengkap', 'saksi1_alamat',
                'saksi2_nama_lengkap', 'saksi2_alamat'
            ];

            requiredFields.forEach(field => {
                const element = $(`[name="${field}"]`);
                if (!element.val() || element.val().trim() === '') {
                    element.addClass('is-invalid');
                    errors.push(`${field.replace(/_/g, ' ')} wajib diisi`);
                    isValid = false;
                }
            });

            // Validate data anak if any
            const jumlahAnak = parseInt($('#jumlah_anak').val());
            if (isNaN(jumlahAnak) || jumlahAnak < 0 || jumlahAnak > 10) {
                $('#jumlah_anak').addClass('is-invalid');
                errors.push('Jumlah anak harus dipilih (0-10)');
                isValid = false;
            }

            // Validate required files
            const requiredFiles = [
                'ktp_pewaris', 'akta_kematian', 'akta_kelahiran_pewaris',
                'akta_kelahiran_ahli_waris', 'ktp_ahli_waris', 'kk_ahli_waris',
                'ktp_saksi', 'surat_pengantar_rt', 'surat_pernyataan_ahli_waris',
                'surat_pernyataan_kebenaran'
            ];

            requiredFiles.forEach(file => {
                const element = $(`[name="files[${file}]"]`);
                if (!element[0].files.length) {
                    element.addClass('is-invalid');
                    errors.push(`File ${file.replace(/_/g, ' ')} wajib diupload`);
                    isValid = false;
                }
            });

            if (!isValid) {
                showValidationErrors({ general: errors });
            }

            return isValid;
        }

        function showValidationErrors(errors) {
            let errorMessages = [];

            Object.keys(errors).forEach(key => {
                if (key === 'general') {
                    errorMessages = errorMessages.concat(errors[key]);
                } else {
                    const field = $(`[name="${key}"]`);
                    field.addClass('is-invalid');
                    if (!field.siblings('.invalid-feedback').length) {
                        field.after(`<div class="invalid-feedback">${errors[key][0]}</div>`);
                    }
                    errorMessages.push(errors[key][0]);
                }
            });

            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: errorMessages.map((msg, index) => `${index + 1}. ${msg}`).join('<br>'),
                confirmButtonText: 'OK'
            });

            // Scroll to first error
            const firstError = $('.is-invalid').first();
            if (firstError.length) {
                $('html, body').animate({
                    scrollTop: firstError.offset().top - 100
                }, 500);
            }
        }

        // Auto-save functionality
        let autoSaveTimer;
        function autoSaveSkawDraft() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(() => {
                const formData = gatherSkawFormData();
                if (formData.nama_lengkap || formData.pewaris_nama_lengkap) {
                    localStorage.setItem('skaw_draft', JSON.stringify(formData));
                    console.log('📝 SKAW Draft auto-saved');
                }
            }, 3000);
        }

        function gatherSkawFormData() {
            const data = {};

            // Gather all form data
            $('#createSkawForm').find('input, select, textarea').each(function() {
                const name = $(this).attr('name');
                const value = $(this).val();
                if (name && value) {
                    data[name] = value;
                }
            });

            return data;
        }

        function loadSkawDraft() {
            const draft = localStorage.getItem('skaw_draft');
            if (draft) {
                try {
                    const data = JSON.parse(draft);

                    Swal.fire({
                        title: 'Draft Ditemukan',
                        text: 'Ditemukan draft SKAW yang belum disimpan. Apakah Anda ingin memuat draft tersebut?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Muat Draft',
                        cancelButtonText: 'Tidak, Mulai Baru'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Object.keys(data).forEach(key => {
                                if (data[key]) {
                                    $(`[name="${key}"]`).val(data[key]);
                                }
                            });

                            // Trigger change events
                            $('#rw-select-skaw').trigger('change');
                            $('#jumlah_anak').trigger('change');

                            Swal.fire('Draft Dimuat', 'Draft berhasil dimuat ke form.', 'success');
                        } else {
                            localStorage.removeItem('skaw_draft');
                        }
                    });
                } catch (e) {
                    localStorage.removeItem('skaw_draft');
                }
            }
        }

        // Auto-format nomor akta perkawinan
        $('#nomor_akta_perkawinan').on('input', function() {
            let value = $(this).val().replace(/[^0-9]/g, ''); // Hanya angka

            if (value.length <= 4) {
                // Format: xxxx
                $(this).val(value);
            } else if (value.length <= 12) {
                // Format: xxxx-KW-ddmmyyyy
                let part1 = value.substring(0, 4);
                let part2 = value.substring(4, 12);
                $(this).val(part1 + '-KW-' + part2);
            } else {
                // Format: xxxx-KW-ddmmyyyy-xxxx
                let part1 = value.substring(0, 4);
                let part2 = value.substring(4, 12);
                let part3 = value.substring(12, 16);
                $(this).val(part1 + '-KW-' + part2 + '-' + part3);
            }
        });

        // Prevent non-numeric input
        $('#nomor_akta_perkawinan').on('keypress', function(e) {
            // Allow backspace, delete, tab, escape, enter
            if ([8, 9, 27, 13, 46].indexOf(e.keyCode) !== -1 ||
                // Allow Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                (e.keyCode === 65 && e.ctrlKey === true) ||
                (e.keyCode === 67 && e.ctrlKey === true) ||
                (e.keyCode === 86 && e.ctrlKey === true) ||
                (e.keyCode === 88 && e.ctrlKey === true)) {
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });

        // Auto-format nomor akta kematian
        $('#nomor_akta_kematian').on('input', function() {
            let value = $(this).val().replace(/[^0-9]/g, ''); // Hanya angka

            if (value.length <= 4) {
                $(this).val(value);
            } else if (value.length <= 12) {
                let part1 = value.substring(0, 4);
                let part2 = value.substring(4, 12);
                $(this).val(part1 + '-KM-' + part2);
            } else {
                let part1 = value.substring(0, 4);
                let part2 = value.substring(4, 12);
                let part3 = value.substring(12, 16);
                $(this).val(part1 + '-KM-' + part2 + '-' + part3);
            }
        });

        $('#nomor_akta_kematian').on('keypress', function(e) {
            if ([8, 9, 27, 13, 46].indexOf(e.keyCode) !== -1 ||
                (e.keyCode === 65 && e.ctrlKey === true) ||
                (e.keyCode === 67 && e.ctrlKey === true) ||
                (e.keyCode === 86 && e.ctrlKey === true) ||
                (e.keyCode === 88 && e.ctrlKey === true)) {
                return;
            }
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });

        function bindAutoSaveEvents() {
            $(document).on('input change keyup paste', 'input, textarea, select', function() {
                autoSaveSkawDraft();
            });
        }

        function bindBeforeUnloadWarning() {
            let formChanged = false;

            $(document).on('input change', 'input, textarea, select', function() {
                formChanged = true;
            });

            $(window).on('beforeunload', function(e) {
                if (formChanged) {
                    const formData = gatherSkawFormData();
                    if (formData.nama_lengkap || formData.pewaris_nama_lengkap) {
                        localStorage.setItem('skaw_draft', JSON.stringify(formData));
                    }
                    return 'Data akan tersimpan sebagai draft.';
                }
            });

            $('#createSkawForm').on('submit', function() {
                formChanged = false;
                $(window).off('beforeunload');
            });
        }
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
