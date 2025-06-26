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

        /* Signature Pad Styles */
        .signature-section {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .signature-pad {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            background-color: white;
            cursor: crosshair;
            display: block;
            margin: 0 auto;
        }

        .signature-controls {
            margin-top: 10px;
            text-align: center;
        }

        .signature-controls button {
            margin: 0 5px;
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

        .signature-validation-error {
            border: 2px solid #dc3545 !important;
            background-color: #f8d7da !important;
        }

        .psu-type-section {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .psu-indicator-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 12px 15px;
            border-radius: 8px;
            margin-top: 10px;
            font-size: 14px;
        }

        .psu-indicator-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 12px 15px;
            border-radius: 8px;
            margin-top: 10px;
            font-size: 14px;
        }

        .psu-indicator-success i,
        .psu-indicator-warning i {
            margin-right: 8px;
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
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('psu.index') }}">Data PSU</a>
                    </div>
                    <div class="breadcrumb-item">Tambah PSU</div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Form PSU (Permohonan Surat Umum)</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-info btn-sm" id="showTipsBtn">
                                    <i class="fas fa-lightbulb"></i> Tips Penggunaan
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="createForm">
                                @csrf

                                {{-- Data Pemohon --}}
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="text-primary">
                                            <i class="fas fa-user"></i> Data Pemohon
                                        </h5>
                                        <hr>

                                        <!-- Auto-fill indicator (hidden by default) -->
                                        <div id="autoFillIndicator" class="auto-fill-indicator" style="display: none;">
                                            <i class="fas fa-magic"></i> Data pemohon berhasil diisi otomatis dari profile Anda
                                        </div>

                                        <!-- Manual fill indicator (hidden by default) -->
                                        <div id="manualFillIndicator" class="manual-fill-indicator" style="display: none;">
                                            <i class="fas fa-edit"></i> Data profile tidak lengkap, silakan isi manual
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Lengkap <span class="required">*</span></label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="nama_lengkap"
                                                   value="{{ $user->name ?? '' }}"
                                                   placeholder="Nama lengkap pemohon">
                                        </div>

                                        <div class="form-group">
                                            <label>Alamat <span class="required">*</span></label>
                                            <textarea class="form-control"
                                                      name="alamat"
                                                      rows="3"
                                                      placeholder="Alamat lengkap">{{ $user->address ?? '' }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Pekerjaan <span class="required">*</span></label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="pekerjaan"
                                                   value="{{ $user->pekerjaan ?? '' }}"
                                                   placeholder="Pekerjaan">
                                        </div>

                                        <div class="form-group">
                                            <label>Jenis Kelamin <span class="required">*</span></label>
                                            <select class="form-control" name="jenis_kelamin">
                                                <option value="">Pilih Jenis Kelamin</option>
                                                <option value="L" {{ ($user->gender ?? '') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                                <option value="P" {{ ($user->gender ?? '') === 'P' ? 'selected' : '' }}>Perempuan</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Tempat Lahir <span class="required">*</span></label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="tempat_lahir"
                                                   value="{{ $user->tempat_lahir ?? '' }}"
                                                   placeholder="Tempat lahir">
                                        </div>

                                        <div class="form-group">
                                            <label>Tanggal Lahir <span class="required">*</span></label>
                                            <input type="date"
                                                   class="form-control"
                                                   name="tanggal_lahir"
                                                   value="{{ $user->tanggal_lahir ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Agama <span class="required">*</span></label>
                                            <select class="form-control" name="agama">
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
                                            <select class="form-control" name="status_perkawinan">
                                                <option value="">Pilih Status</option>
                                                <option value="Belum Kawin" {{ ($user->status_perkawinan ?? '') === 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                                                <option value="Kawin" {{ ($user->status_perkawinan ?? '') === 'Kawin' ? 'selected' : '' }}>Kawin</option>
                                                <option value="Cerai Hidup" {{ ($user->status_perkawinan ?? '') === 'Cerai Hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                                                <option value="Cerai Mati" {{ ($user->status_perkawinan ?? '') === 'Cerai Mati' ? 'selected' : '' }}>Cerai Mati</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Kewarganegaraan <span class="required">*</span></label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="kewarganegaraan"
                                                   value="{{ $user->kewarganegaraan ?? 'Indonesia' }}"
                                                   placeholder="Kewarganegaraan">
                                        </div>

                                        <div class="form-group">
                                            <label>Nomor KK/KTP <span class="required">*</span></label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="nomor_kk"
                                                   value="{{ $user->nik ?? '' }}"
                                                   placeholder="Nomor KK atau KTP"
                                                   maxlength="20">
                                        </div>

                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email"
                                                   class="form-control"
                                                   value="{{ $user->email ?? '' }}"
                                                   readonly>
                                        </div>

                                        <div class="form-group">
                                            <label>No. Telepon</label>
                                            <input type="text"
                                                   class="form-control"
                                                   value="{{ $user->telp ?? '' }}"
                                                   readonly>
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
                                                <select name="rw" id="rw-select-psu" class="form-control" required>
                                                    <option value="">Pilih RW</option>
                                                    @foreach($availableRW as $rw)
                                                        <option value="{{ $rw['value'] }}"
                                                            data-rt-count="{{ $rw['rt_count'] }}"
                                                            {{ ($user->rw ?? '') === $rw['value'] ? 'selected' : '' }}>
                                                            {{ $rw['label'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="form-text text-muted">Pilih RW sesuai dengan lokasi Anda</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>RT <span class="required">*</span></label>
                                                <select name="rt" id="rt-select-psu" class="form-control" required disabled>
                                                    <option value="">Pilih RT</option>
                                                    {{-- RT options akan diisi via JavaScript --}}
                                                </select>
                                                <small class="form-text text-muted">Pilih RT sesuai dengan lokasi Anda</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Penting:</strong> Pilihan RT dan RW akan menentukan siapa yang akan menyetujui permohonan PSU Anda. Pastikan Anda memilih sesuai dengan lokasi yang sebenarnya.
                                    </div>
                                </div>

                                {{-- Jenis PSU dan Tujuan --}}
                                <div class="psu-type-section">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-file-signature"></i> Jenis dan Tujuan PSU
                                    </h6>

                                    <!-- PSU Type Indicator -->
                                    <div id="psuTypeIndicator" style="display: none;"></div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Ditujukan Kepada <span class="required">*</span></label>
                                                <select name="ditujukan_kepada" id="ditujukanKepada" class="form-control" required>
                                                    <option value="">Pilih Tujuan</option>
                                                    @foreach($ditujukanKepadaOptions as $value => $label)
                                                        <option value="{{ $value }}">{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                                <small class="form-text text-muted">Pilih siapa yang menjadi tujuan PSU ini</small>

                                                <!-- PSU Type Indicator - Pastikan ada container ini -->
                                                <div id="psuTypeIndicator" class="mt-2" style="display: none;">
                                                    <span id="psuTypeText"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Bulan <span class="required">*</span></label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="bulan"
                                                       value="{{ date('n') }}"
                                                       readonly>
                                                <small class="form-text text-muted">Bulan saat ini (otomatis)</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- RT Selection Container (untuk Ketua RW yang pilih warga_rt) -->
                                        <div class="col-md-6" id="rtSelectionContainer" style="display: none;">
                                            <div class="form-group">
                                                <label>Pilih RT <span class="required">*</span></label>
                                                <select name="target_rt" id="targetRT" class="form-control">
                                                    <option value="">Pilih RT</option>
                                                    <!-- Will be populated by JavaScript -->
                                                </select>
                                                <small class="form-text text-muted">Pilih RT dalam RW Anda</small>
                                            </div>
                                        </div>

                                        <!-- RW Selection Container (jika diperlukan untuk kasus tertentu) -->
                                        <div class="col-md-6" id="rwSelectionContainer" style="display: none;">
                                            <div class="form-group">
                                                <label>Pilih RW <span class="required">*</span></label>
                                                <select name="target_rw" id="targetRW" class="form-control">
                                                    <option value="">Pilih RW</option>
                                                    <!-- Will be populated by JavaScript -->
                                                </select>
                                                <small class="form-text text-muted">Pilih RW tujuan</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- Warga Selection Container -->
                                        <div class="col-md-6" id="wargaSelectionContainer" style="display: none;">
                                            <div class="form-group">
                                                <label>Pilih Warga <span class="required">*</span></label>
                                                <select name="target_warga" id="targetWarga" class="form-control">
                                                    <option value="">Pilih Warga</option>
                                                    <!-- Will be populated by JavaScript based on RT/RW selection -->
                                                </select>
                                                <small class="form-text text-muted">Pilih warga yang akan menerima surat</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Info Container untuk menampilkan informasi tambahan -->
                                    <div class="row">
                                        <div class="col-12">
                                            <div id="psuInfoContainer" class="alert alert-info" style="display: none;">
                                                <i class="fas fa-info-circle"></i>
                                                <span id="psuInfoText"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Catatan Workflow:</strong>
                                        <ul class="small mb-0 mt-2">
                                            <li><strong>Warga RT/RW (Internal):</strong> Langsung selesai tanpa persetujuan</li>
                                            <li><strong>RT/RW/Kelurahan (External):</strong> Memerlukan proses approval</li>
                                            <li><strong>Nama ketua akan otomatis diambil saat approval sesuai tingkatan</strong></li>
                                        </ul>
                                    </div>
                                </div>

                                {{-- Detail Surat --}}
                                <div class="section-divider">
                                    <div class="row">
                                        <div class="col-12">
                                            <h5 class="text-primary">
                                                <i class="fas fa-clipboard-list"></i> Detail Surat
                                            </h5>
                                            <hr>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Sifat Surat <span class="required">*</span></label>
                                                <select name="sifat" class="form-control" required>
                                                    <option value="">Pilih Sifat</option>
                                                    <option value="Biasa">Biasa</option>
                                                    <option value="Penting">Penting</option>
                                                    <option value="Segera">Segera</option>
                                                    <option value="Rahasia">Rahasia</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Hal <span class="required">*</span></label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="hal"
                                                       placeholder="Perihal/subjek surat"
                                                       required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group" id="tujuanInternalField" style="display: none;">
                                                <label>Tujuan Internal</label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="tujuan_internal"
                                                       readonly>
                                                <small class="form-text text-muted">Otomatis berdasarkan "Ditujukan Kepada"</small>
                                            </div>

                                            <div class="form-group" id="tujuanEksternalField">
                                                <label>Tujuan Eksternal</label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="tujuan_eksternal"
                                                       placeholder="Nama instansi/perorangan tujuan surat">
                                                <small class="form-text text-muted">Isi nama instansi atau perorangan yang menjadi tujuan surat</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Isi Surat <span class="required">*</span></label>
                                                <textarea class="form-control"
                                                          name="isi_surat"
                                                          rows="5"
                                                          placeholder="Isi lengkap surat PSU"
                                                          required></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>File Lampiran (Opsional)</label>
                                                <input type="file"
                                                       class="form-control"
                                                       name="file_lampiran[]"
                                                       multiple
                                                       accept=".pdf,.jpg,.jpeg,.png">
                                                <small class="form-text text-muted">
                                                    Format: PDF, JPG, JPEG, PNG. Maksimal 2MB per file. Bisa pilih beberapa file.
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Tanda Tangan Digital Pemohon --}}
                                <div class="signature-section">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-signature"></i> Tanda Tangan Digital
                                    </h6>

                                    <div class="row justify-content-center">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label>Tanda Tangan Pemohon <span class="required">*</span></label>
                                                <canvas id="signaturePadPemohon"
                                                        class="signature-pad"
                                                        width="500"
                                                        height="250"></canvas>
                                                <div class="signature-controls">
                                                    <button type="button" class="btn btn-secondary btn-sm" id="clearSignaturePemohon">
                                                        <i class="fas fa-eraser"></i> Hapus Tanda Tangan
                                                    </button>
                                                </div>
                                                <small class="form-text text-muted">Gunakan mouse atau touch untuk membuat tanda tangan pemohon</small>
                                                <input type="hidden" name="ttd_pemohon" id="ttdPemohonInput">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Catatan:</strong>
                                        <ul class="small mb-0 mt-2">
                                            <li>Tanda tangan pemohon wajib diisi</li>
                                            <li>Pastikan tanda tangan sudah sesuai sebelum menyimpan</li>
                                            <li>Gunakan mouse atau sentuh layar untuk membuat tanda tangan</li>
                                            <li>Tanda tangan akan disertakan dalam dokumen resmi</li>
                                        </ul>
                                    </div>
                                </div>

                                {{-- Catatan --}}
                                <div class="section-divider">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="card-title">Catatan:</h6>
                                                    <ul class="small text-muted">
                                                        <li>Pastikan semua data telah diisi dengan benar</li>
                                                        <li>PSU Internal akan langsung selesai tanpa approval</li>
                                                        <li>PSU Eksternal memerlukan persetujuan sesuai tingkatan</li>
                                                        <li>Anda dapat melihat status permohonan di halaman daftar PSU</li>
                                                        <li>Tanda tangan digital akan disertakan dalam dokumen resmi</li>
                                                        <li>Data akan otomatis tersimpan sebagai draft setiap 3 detik</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer text-right border-0 pt-3">
                                    <button type="submit" class="btn btn-primary" id="saveBtn">
                                        <i class="fas fa-save"></i> Simpan Permohonan
                                    </button>
                                    <a href="{{ route('psu.index') }}" class="btn btn-secondary">
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

    <!-- Signature Pad -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js"></script>

    <script>
        window.psuData = {
            user: @json($user),
            ditujukanKepadaOptions: @json($ditujukanKepadaOptions),
            availableRT: @json($availableRT),
            availableRW: @json($availableRW),
            rwRtMapping: @json($rwRtMapping),
            ketuaRT: @json($ketuaRT),
            ketuaRW: @json($ketuaRW)
        };

        // Pass routes untuk AJAX calls
        window.routes = {
            store: "{{ route('psu.store') }}",
            index: "{{ route('psu.index') }}",
            getWargaByRT: "{{ route('psu.getWargaByRT') }}",
            getWargaByRW: "{{ route('psu.getWargaByRW') }}",
            getRTInRW: "{{ route('psu.getRTInRW') }}"
        };

        // Global variables for signature pad
        let signaturePadPemohon;

        $(document).ready(function() {
            // Initialize signature pad
            initializeSignaturePad();

            // Initialize RW-RT for PSU
            initializePsuRwRt();

            // Initialize PSU type handling
            initializePsuTypeHandling();

            // Check and auto-fill profile data
            setTimeout(checkAndAutoFillProfile, 500);

            // Initialize auto-save and draft functionality
            bindAutoSaveEvents();
            bindBeforeUnloadWarning();

            // Load draft after a short delay
            setTimeout(loadDraft, 1000);

            // Tips button
            $('#showTipsBtn').on('click', function() {
                showTipsDialog();
            });

            // Form submission
            $('#createForm').on('submit', function(e) {
                e.preventDefault();

                // Validate required fields
                if (!validateForm()) {
                    return;
                }

                const form = $(this);
                const btn = $('#saveBtn');

                // Reset form state
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                $('.signature-validation-error').removeClass('signature-validation-error');

                btn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

                // Get signature data
                const ttdPemohon = signaturePadPemohon.toDataURL();
                $('#ttdPemohonInput').val(ttdPemohon);

                // Create FormData
                const formData = new FormData(this);

                $.ajax({
                    url: "{{ route('psu.store') }}",
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if(response.success) {
                            // Clear draft after successful submission
                            localStorage.removeItem('psu_draft');

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                window.location.href = "{{ route('psu.index') }}";
                            });
                        }
                    },
                    error: function(xhr) {
                        btn.html('<i class="fas fa-save"></i> Simpan Permohonan').prop('disabled', false);

                        if(xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessages = [];

                            Object.keys(errors).forEach(key => {
                                const field = $(`[name="${key}"]`);
                                field.addClass('is-invalid');

                                if (!field.siblings('.invalid-feedback').length) {
                                    field.after(`<div class="invalid-feedback">${errors[key][0]}</div>`);
                                }

                                errorMessages.push(errors[key][0]);
                            });

                            // Show validation errors in SweetAlert
                            Swal.fire({
                                icon: 'error',
                                title: 'Validasi Gagal',
                                html: errorMessages.map((msg, index) => `${index + 1}. ${msg}`).join('<br>'),
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan data.',
                                'error'
                            );
                        }
                    }
                });
            });

            // Keyboard shortcuts
            $(document).on('keydown', function(e) {
                // Ctrl+S to save
                if ((e.ctrlKey || e.metaKey) && e.which === 83) {
                    e.preventDefault();
                    if (validateForm()) {
                        $('#createForm').submit();
                    }
                }
            });

            console.log('✅ PSU Create form initialized successfully');
        });

        function initializePsuRwRt() {
            // RW Change Handler
            $('#rw-select-psu').on('change', function() {
                const selectedRW = $(this).val();
                const rtSelect = $('#rt-select-psu');

                // Clear RT selection
                rtSelect.empty().append('<option value="">Pilih RT</option>').prop('disabled', true);

                if (selectedRW) {
                    // Get RT count from selected option
                    const rtCount = $(this).find('option:selected').data('rt-count');

                    // Generate RT options for selected RW
                    for (let i = 1; i <= rtCount; i++) {
                        const rt = i === 10 ? '10' : i.toString().padStart(2, '0');
                        const label = `RT ${rt}`;
                        rtSelect.append(`<option value="${rt}">${label}</option>`);
                    }

                    // Enable RT select
                    rtSelect.prop('disabled', false);
                }
            });

            // Initialize with user's current RW/RT if available
            const userRW = '{{ $user->rw ?? "" }}';
            const userRT = '{{ $user->rt ?? "" }}';

            if (userRW) {
                $('#rw-select-psu').val(userRW).trigger('change');

                if (userRT) {
                    setTimeout(() => {
                        $('#rt-select-psu').val(userRT);
                    }, 100);
                }
            }

            console.log('✅ PSU RW-RT initialized');
        }

        function initializePsuTypeHandling() {
            console.log('🚀 Initializing PSU Type Handling...');

            // Gunakan event delegation untuk memastikan event ter-capture
            $(document).on('change', '#ditujukanKepada', function() {
                const selectedValue = $(this).val();
                console.log('📝 Ditujukan Kepada changed to:', selectedValue);

                // Cari container untuk indicator
                let indicatorContainer = $('#psuTypeIndicator');

                // Jika tidak ada, buat baru
                if (indicatorContainer.length === 0) {
                    $(this).closest('.form-group').append('<div id="psuTypeIndicator" class="mt-2"></div>');
                    indicatorContainer = $('#psuTypeIndicator');
                }

                // Clear previous classes and content
                indicatorContainer.removeClass('alert-success alert-warning psu-indicator-success psu-indicator-warning');
                indicatorContainer.html('');

                // Set content based on selection
                if (selectedValue === 'warga_rt' || selectedValue === 'warga_rw') {
                    // PSU Internal
                    indicatorContainer.addClass('alert alert-success');
                    indicatorContainer.html('<i class="fas fa-check-circle"></i> <strong>PSU Internal</strong> - Langsung Selesai (Auto Approved)');
                    indicatorContainer.show();
                    console.log('✅ Showing Internal PSU indicator');

                } else if (selectedValue === 'rt' || selectedValue === 'rw' || selectedValue === 'kelurahan') {
                    // PSU External
                    indicatorContainer.addClass('alert alert-warning');
                    indicatorContainer.html('<i class="fas fa-clock"></i> <strong>PSU Eksternal</strong> - Memerlukan Persetujuan');
                    indicatorContainer.show();
                    console.log('⚠️ Showing External PSU indicator');

                } else {
                    // No selection
                    indicatorContainer.hide();
                    console.log('❌ Hiding PSU indicator');
                }

                // Handle dynamic fields
                handleDynamicFields(selectedValue);
            });

            console.log('✅ PSU Type handling initialized successfully');
        }

        function handleDynamicFields(selectedValue) {
            const user = window.psuData?.user;
            if (!user) return;

            console.log(`🔄 Handling dynamic fields for: ${selectedValue}, User role: ${user.role}`);

            // Hide all dynamic fields first
            hideAllDynamicFields();

            switch(selectedValue) {
                case 'warga_rt':
                    if (user.role === 'Ketua RT') {
                        // Ketua RT bisa langsung pilih warga di RT-nya sendiri
                        showWargaRTFields(user.rt, user.rw);
                    } else if (user.role === 'Ketua RW') {
                        // Ketua RW harus pilih RT dulu, baru bisa pilih warga
                        console.log('🎯 Ketua RW pilih warga_rt - showing RT selection first');
                        showRTSelectionForRW(user.rw);
                    }
                    break;

                case 'warga_rw':
                    if (user.role === 'Ketua RW') {
                        // Ketua RW bisa pilih semua warga di RW-nya langsung
                        showWargaRWFields(user.rw);
                    }
                    break;

                case 'rt':
                case 'rw':
                case 'kelurahan':
                    // PSU External - tidak perlu field tambahan
                    showPsuInfo('PSU External - akan memerlukan persetujuan sesuai tingkatan.');
                    break;
            }
        }

        function hideAllDynamicFields() {
            $('#rtSelectionContainer').hide();
            $('#rwSelectionContainer').hide();
            $('#wargaSelectionContainer').hide();

            // Clear selections
            $('#targetRT').val('');
            $('#targetWarga').empty().append('<option value="">Pilih Warga</option>');

            // Hide info
            hidePsuInfo();

            console.log('🔄 All dynamic fields hidden and reset');
        }

        function showWargaRTFields(rt, rw) {
            // Untuk Ketua RT - langsung show warga di RT-nya
            loadWargaByRT(rt, rw);
            $('#wargaSelectionContainer').show();

            // Show info
            showPsuInfo(`Menampilkan warga di RT ${rt}, RW ${rw}. Surat akan langsung disetujui (Internal PSU).`);
        }

        function showWargaRWFields(rw) {
            // Update label dengan RW yang dinamis
            $('label[for="targetWarga"]').text(`Pilih Warga dalam RW ${rw} *`);

            // Load warga dengan opsi "Semua Warga RW"
            loadWargaByRWWithAllOption(rw);
            $('#wargaSelectionContainer').show();

            // Show info
            showPsuInfo(`Menampilkan warga di RW ${rw}. Surat akan langsung disetujui (Internal PSU).`);
        }

        function showRTSelectionForRW(rw) {
            console.log(`🎯 showRTSelectionForRW called with RW: ${rw}`);

            const rwRtMapping = window.psuData?.rwRtMapping || {};
            const targetRT = $('#targetRT');
            const user = window.psuData?.user;

            // Clear and populate RT options
            targetRT.empty().append('<option value="">Pilih RT</option>');

            // Update label dengan RW yang dinamis
            $('label[for="targetRT"]').text(`Pilih RT dalam RW ${rw} *`);

            if (rwRtMapping[rw]) {
                const rtCount = rwRtMapping[rw];

                // Generate RT options based on count
                for (let i = 1; i <= rtCount; i++) {
                    const rt = i === 10 ? '10' : i.toString().padStart(2, '0');
                    targetRT.append(`<option value="${rt}">RT ${rt}</option>`);
                }

                console.log(`✅ Generated ${rtCount} RT options for RW ${rw}`);
            } else {
                // Fallback: gunakan availableRT semua
                const availableRT = window.psuData?.availableRT || [];
                availableRT.forEach(rt => {
                    const rtValue = rt.value || rt;
                    const rtLabel = rt.label || `RT ${rtValue}`;
                    targetRT.append(`<option value="${rtValue}">${rtLabel}</option>`);
                });
            }

            // Show RT selection container
            $('#rtSelectionContainer').show();
            $('#wargaSelectionContainer').hide();

            // Handle RT selection change
            targetRT.off('change').on('change', function() {
                const selectedRT = $(this).val();
                console.log(`🎯 RT selected: ${selectedRT}`);

                if (selectedRT) {
                    // Load warga berdasarkan RT yang dipilih
                    loadWargaByRTWithAllOption(selectedRT, rw);
                    $('#wargaSelectionContainer').show();

                    // Update label warga dengan RT yang dinamis
                    $('label[for="targetWarga"]').text(`Pilih Warga dalam RT ${selectedRT} *`);

                    showPsuInfo(`RT ${selectedRT} dipilih. Sekarang pilih warga yang akan menerima surat.`);
                } else {
                    $('#wargaSelectionContainer').hide();
                    showPsuInfo(`Pilih RT dalam RW ${rw} terlebih dahulu.`);
                }
            });

            showPsuInfo(`Pilih RT dalam RW ${rw} terlebih dahulu.`);
        }

        // Fungsi baru untuk load warga dengan opsi "Semua Warga"
        function loadWargaByRTWithAllOption(rt, rw) {
            const targetWarga = $('#targetWarga');
            targetWarga.empty().append('<option value="">Loading...</option>');

            $.ajax({
                url: `/psu/get-warga-by-rt`,
                method: 'POST',
                data: {
                    rt: rt,
                    rw: rw,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    targetWarga.empty().append('<option value="">Pilih Warga</option>');

                    // TAMBAHAN: Opsi "Semua Warga RT"
                    targetWarga.append(`<option value="semua_rt_${rt}">📋 Semua Warga RT ${rt}</option>`);

                    if (response.success && response.data.length > 0) {
                        // Separator
                        targetWarga.append('<option value="" disabled>────────────────</option>');

                        response.data.forEach(warga => {
                            targetWarga.append(`<option value="${warga.id}">👤 ${warga.name}</option>`);
                        });
                    } else {
                        targetWarga.append('<option value="" disabled>Tidak ada warga ditemukan</option>');
                    }

                    console.log(`✅ Loaded ${response.data?.length || 0} warga for RT ${rt} with "Semua Warga" option`);
                },
                error: function(xhr) {
                    targetWarga.empty().append('<option value="" disabled>Error loading data</option>');
                    console.error('Error loading warga by RT:', xhr.responseJSON?.message || xhr.statusText);
                }
            });
        }

        // Fungsi baru untuk load warga RW dengan opsi "Semua Warga"
        function loadWargaByRWWithAllOption(rw) {
            const targetWarga = $('#targetWarga');
            targetWarga.empty().append('<option value="">Loading...</option>');

            $.ajax({
                url: "{{ route('psu.getWargaByRW') }}",
                method: 'POST',
                data: {
                    rw: rw,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    targetWarga.empty().append('<option value="">Pilih Warga</option>');

                    // TAMBAHAN: Opsi "Semua Warga RW"
                    targetWarga.append(`<option value="semua_rw_${rw}">📋 Semua Warga RW ${rw}</option>`);

                    if (response.success && response.data.length > 0) {
                        // Group by RT untuk tampilan yang lebih rapi
                        const groupedByRT = {};
                        response.data.forEach(warga => {
                            if (!groupedByRT[warga.rt]) {
                                groupedByRT[warga.rt] = [];
                            }
                            groupedByRT[warga.rt].push(warga);
                        });

                        // Separator
                        targetWarga.append('<option value="" disabled>────────────────</option>');

                        // Tampilkan per RT
                        Object.keys(groupedByRT).sort().forEach(rt => {
                            // Header RT
                            targetWarga.append(`<option value="" disabled>═══ RT ${rt} ═══</option>`);

                            // Opsi semua warga RT ini
                            targetWarga.append(`<option value="semua_rt_${rt}">📋 Semua Warga RT ${rt}</option>`);

                            // Individual warga
                            groupedByRT[rt].forEach(warga => {
                                targetWarga.append(`<option value="${warga.id}">👤 ${warga.name}</option>`);
                            });
                        });
                    } else {
                        targetWarga.append('<option value="" disabled>Tidak ada warga ditemukan</option>');
                    }

                    console.log(`✅ Loaded warga for RW ${rw} with "Semua Warga" options`);
                },
                error: function(xhr) {
                    targetWarga.empty().append('<option value="" disabled>Error loading data</option>');
                    console.error('Error loading warga by RW:', xhr.responseJSON?.message || xhr.statusText);
                }
            });
        }

        function showPsuInfo(message) {
            $('#psuInfoText').text(message);
            $('#psuInfoContainer').show();
        }

        function hidePsuInfo() {
            $('#psuInfoContainer').hide();
        }

        // AJAX calls menggunakan routes yang sudah ada
        function loadWargaByRT(rt, rw) {
            const targetWarga = $('#targetWarga');
            targetWarga.empty().append('<option value="">Loading...</option>');

            $.ajax({
                url: `/psu/get-warga-by-rt`,
                method: 'POST',
                data: {
                    rt: rt,
                    rw: rw,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    targetWarga.empty().append('<option value="">Pilih Warga</option>');

                    if (response.success && response.data.length > 0) {
                        response.data.forEach(warga => {
                            targetWarga.append(`<option value="${warga.id}">${warga.name} (RT ${warga.rt}/RW ${warga.rw})</option>`);
                        });
                    } else {
                        targetWarga.append('<option value="" disabled>Tidak ada warga ditemukan</option>');
                    }
                },
                error: function(xhr) {
                    targetWarga.empty().append('<option value="" disabled>Error loading data</option>');
                    console.error('Error loading warga by RT:', xhr.responseJSON?.message || xhr.statusText);
                }
            });
        }

        function loadWargaByRW(rw) {
            const targetWarga = $('#targetWarga');
            targetWarga.empty().append('<option value="">Loading...</option>');

            $.ajax({
                url: "{{ route('psu.getWargaByRW') }}",
                method: 'POST',
                data: {
                    rw: rw,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    targetWarga.empty().append('<option value="">Pilih Warga</option>');

                    if (response.success && response.data.length > 0) {
                        response.data.forEach(warga => {
                            targetWarga.append(`<option value="${warga.id}">${warga.name} (RT ${warga.rt}/RW ${warga.rw})</option>`);
                        });
                    } else {
                        targetWarga.append('<option value="" disabled>Tidak ada warga ditemukan</option>');
                    }
                },
                error: function(xhr) {
                    targetWarga.empty().append('<option value="" disabled>Error loading data</option>');
                    console.error('Error loading warga by RW:', xhr.responseJSON?.message || xhr.statusText);
                }
            });
        }

        function loadRTInRW(rw) {
            const targetRT = $('#targetRT');
            targetRT.empty().append('<option value="">Loading...</option>');

            $.ajax({
                url: "{{ route('psu.getRTInRW') }}",
                method: 'POST',
                data: {
                    rw: rw,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    targetRT.empty().append('<option value="">Pilih RT</option>');

                    if (response.success && response.data.length > 0) {
                        response.data.forEach(rt => {
                            targetRT.append(`<option value="${rt}">RT ${rt}</option>`);
                        });
                    } else {
                        targetRT.append('<option value="" disabled>Tidak ada RT ditemukan</option>');
                    }
                },
                error: function(xhr) {
                    targetRT.empty().append('<option value="" disabled>Error loading data</option>');
                    console.error('Error loading RT in RW:', xhr.responseJSON?.message || xhr.statusText);
                }
            });
        }

        $('head').append(indicatorStyles);

        // Initialize signature pad
        function initializeSignaturePad() {
            const canvasPemohon = document.getElementById('signaturePadPemohon');

            signaturePadPemohon = new SignaturePad(canvasPemohon, {
                backgroundColor: 'rgba(255,255,255,0)',
                penColor: 'rgb(0, 0, 0)',
                velocityFilterWeight: 0.7,
                minWidth: 0.5,
                maxWidth: 2.5,
                throttle: 16,
                minPointDistance: 3,
            });

            // Clear signature button
            $('#clearSignaturePemohon').on('click', function() {
                signaturePadPemohon.clear();
                $('#ttdPemohonInput').val('');
                $('#signaturePadPemohon').removeClass('signature-validation-error');
            });

            // Auto-save signature when changed
            signaturePadPemohon.onEnd = function() {
                $('#ttdPemohonInput').val(signaturePadPemohon.toDataURL());
                $('#signaturePadPemohon').removeClass('signature-validation-error');
                autoSaveDraft();
            };

            console.log('✅ Signature pad initialized');
        }

        // Check and auto-fill profile data
        function checkAndAutoFillProfile() {
            const userData = @json($user);

            // Check if user has complete profile data
            const hasCompleteProfile = userData.name &&
                                     userData.address &&
                                     userData.pekerjaan &&
                                     userData.gender &&
                                     userData.tempat_lahir &&
                                     userData.tanggal_lahir &&
                                     userData.agama &&
                                     userData.status_perkawinan &&
                                     userData.nik;

            if (hasCompleteProfile) {
                // Show auto-fill confirmation
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
                    cancelButtonColor: '#6c757d',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Auto-fill all data
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
                // Show incomplete profile warning
                const missingFields = [];
                if (!userData.name) missingFields.push('Nama Lengkap');
                if (!userData.address) missingFields.push('Alamat');
                if (!userData.pekerjaan) missingFields.push('Pekerjaan');
                if (!userData.gender) missingFields.push('Jenis Kelamin');
                if (!userData.tempat_lahir) missingFields.push('Tempat Lahir');
                if (!userData.tanggal_lahir) missingFields.push('Tanggal Lahir');
                if (!userData.agama) missingFields.push('Agama');
                if (!userData.status_perkawinan) missingFields.push('Status Perkawinan');
                if (!userData.nik) missingFields.push('NIK');

                Swal.fire({
                    title: 'Profile Tidak Lengkap',
                    html: `
                        <div class="text-left">
                            <p><strong>Data profile Anda belum lengkap:</strong></p>
                            <hr>
                            <ul style="text-align: left;">
                                ${missingFields.map(field => `<li>${field}</li>`).join('')}
                            </ul>
                            <hr>
                            <p><small class="text-muted">
                                <i class="fas fa-edit"></i>
                                Silakan isi data secara manual atau lengkapi profile Anda terlebih dahulu.
                            </small></p>
                        </div>
                    `,
                    icon: 'warning',
                    confirmButtonText: 'OK, Isi Manual',
                    confirmButtonColor: '#ffc107'
                }).then(() => {
                    showManualFillIndicator();
                });
            }
        }

        // Fill form with profile data
        function fillProfileData(userData) {
            if (userData.name) $('[name="nama_lengkap"]').val(userData.name);
            if (userData.address) $('[name="alamat"]').val(userData.address);
            if (userData.pekerjaan) $('[name="pekerjaan"]').val(userData.pekerjaan);
            if (userData.gender) $('[name="jenis_kelamin"]').val(userData.gender);
            if (userData.tempat_lahir) $('[name="tempat_lahir"]').val(userData.tempat_lahir);
            if (userData.tanggal_lahir) $('[name="tanggal_lahir"]').val(userData.tanggal_lahir);
            if (userData.agama) $('[name="agama"]').val(userData.agama);
            if (userData.status_perkawinan) $('[name="status_perkawinan"]').val(userData.status_perkawinan);
            if (userData.kewarganegaraan) $('[name="kewarganegaraan"]').val(userData.kewarganegaraan);
            if (userData.nik) $('[name="nomor_kk"]').val(userData.nik);

            console.log('✅ Profile data auto-filled successfully');
        }

        // Show auto-fill indicator
        function showAutoFillIndicator() {
            $('#autoFillIndicator').show();
            $('#manualFillIndicator').hide();

            setTimeout(() => {
                $('#autoFillIndicator').fadeOut();
            }, 5000);
        }

        // Show manual fill indicator
        function showManualFillIndicator() {
            $('#manualFillIndicator').show();
            $('#autoFillIndicator').hide();

            setTimeout(() => {
                $('#manualFillIndicator').fadeOut();
            }, 5000);
        }

        // Show tips dialog
        function showTipsDialog() {
            Swal.fire({
                icon: 'info',
                title: 'Tips Penggunaan Form PSU',
                html: `
                    <div class="text-left">
                        <strong>Fitur yang tersedia:</strong><br>
                        • <kbd>Ctrl+S</kbd> untuk menyimpan<br>
                        • Auto-save draft setiap 3 detik<br>
                        • Auto-fill data dari profile<br>
                        • Draft otomatis dimuat saat membuka form<br>
                        • Tanda tangan digital untuk pemohon<br>
                        • Validasi otomatis untuk semua field wajib<br><br>

                        <strong>Jenis PSU:</strong><br>
                        • <strong>Internal:</strong> Untuk warga RT/RW sendiri, langsung selesai<br>
                        • <strong>Eksternal:</strong> Memerlukan persetujuan RT/RW/Kelurahan<br><br>

                        <strong>Tentang Tanda Tangan Digital:</strong><br>
                        • Tanda tangan pemohon wajib diisi<br>
                        • Gunakan mouse atau touch screen<br>
                        • Tanda tangan disimpan otomatis<br>
                        • Dapat dihapus dan dibuat ulang<br><br>

                        <strong>Auto-Save Draft:</strong><br>
                        • Data tersimpan otomatis setiap 3 detik<br>
                        • Draft dimuat saat buka form lagi<br>
                        • Termasuk tanda tangan digital<br>
                        • Draft terhapus setelah berhasil submit
                    </div>
                `,
                confirmButtonText: 'Mengerti',
                width: '600px'
            });
        }

        function validateForm() {
            let isValid = true;
            const errors = [];
            const requiredFields = [
                { name: 'nama_lengkap', label: 'Nama Lengkap' },
                { name: 'alamat', label: 'Alamat' },
                { name: 'pekerjaan', label: 'Pekerjaan' },
                { name: 'jenis_kelamin', label: 'Jenis Kelamin' },
                { name: 'tempat_lahir', label: 'Tempat Lahir' },
                { name: 'tanggal_lahir', label: 'Tanggal Lahir' },
                { name: 'agama', label: 'Agama' },
                { name: 'status_perkawinan', label: 'Status Perkawinan' },
                { name: 'kewarganegaraan', label: 'Kewarganegaraan' },
                { name: 'nomor_kk', label: 'Nomor KK/KTP' },
                { name: 'rt', label: 'RT' },
                { name: 'rw', label: 'RW' },
                { name: 'ditujukan_kepada', label: 'Ditujukan Kepada' },
                { name: 'bulan', label: 'Bulan' },
                { name: 'sifat', label: 'Sifat Surat' },
                { name: 'hal', label: 'Hal' },
                { name: 'isi_surat', label: 'Isi Surat' }
            ];

            // Check required fields
            requiredFields.forEach(field => {
                const value = $(`[name="${field.name}"]`).val();
                if (!value || value.trim() === '') {
                    errors.push(`${field.label} wajib diisi`);
                    $(`[name="${field.name}"]`).addClass('is-invalid');
                    isValid = false;
                } else {
                    $(`[name="${field.name}"]`).removeClass('is-invalid');
                }
            });

            // Validasi khusus untuk PSU Internal
            const ditujukanKepada = $('#ditujukanKepada').val();
            if (ditujukanKepada === 'warga_rt' || ditujukanKepada === 'warga_rw') {
                const targetWarga = $('#targetWarga').val();
                if (!targetWarga) {
                    errors.push('Target warga harus dipilih untuk PSU Internal');
                    $('#targetWarga').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#targetWarga').removeClass('is-invalid');
                }

                // Untuk Ketua RW yang pilih warga_rt, RT harus dipilih juga
                if (ditujukanKepada === 'warga_rt' && window.psuData.user.role === 'Ketua RW') {
                    const targetRT = $('#targetRT').val();
                    if (!targetRT) {
                        errors.push('RT harus dipilih terlebih dahulu');
                        $('#targetRT').addClass('is-invalid');
                        isValid = false;
                    } else {
                        $('#targetRT').removeClass('is-invalid');
                    }
                }
            }

            // Check signature pemohon (required)
            if (signaturePadPemohon.isEmpty()) {
                errors.push('Tanda tangan pemohon wajib diisi');
                $('#signaturePadPemohon').addClass('signature-validation-error');
                isValid = false;
            } else {
                $('#signaturePadPemohon').removeClass('signature-validation-error');
            }

            // Show validation errors with SweetAlert
            if (!isValid) {
                let errorMessage = 'Mohon lengkapi data berikut:\n\n';
                errors.forEach((error, index) => {
                    errorMessage += `${index + 1}. ${error}\n`;
                });

                Swal.fire({
                    icon: 'error',
                    title: 'Data Belum Lengkap',
                    text: errorMessage,
                    confirmButtonText: 'OK'
                });

                // Scroll to first error
                const firstError = $('.is-invalid, .signature-validation-error').first();
                if (firstError.length) {
                    $('html, body').animate({
                        scrollTop: firstError.offset().top - 100
                    }, 500);
                }
            }

            return isValid;
        }

        // Auto-save draft functionality
        let autoSaveTimer;
        function autoSaveDraft() {
            console.log('⏰ Auto-save triggered, setting timer...');
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(() => {
                console.log('💾 Executing auto-save...');

                const formData = {
                    nama_lengkap: $('[name="nama_lengkap"]').val(),
                    alamat: $('[name="alamat"]').val(),
                    pekerjaan: $('[name="pekerjaan"]').val(),
                    jenis_kelamin: $('[name="jenis_kelamin"]').val(),
                    tempat_lahir: $('[name="tempat_lahir"]').val(),
                    tanggal_lahir: $('[name="tanggal_lahir"]').val(),
                    agama: $('[name="agama"]').val(),
                    status_perkawinan: $('[name="status_perkawinan"]').val(),
                    kewarganegaraan: $('[name="kewarganegaraan"]').val(),
                    nomor_kk: $('[name="nomor_kk"]').val(),
                    rt: $('[name="rt"]').val(),
                    rw: $('[name="rw"]').val(),
                    ditujukan_kepada: $('[name="ditujukan_kepada"]').val(),
                    nama_ketua_rt: $('[name="nama_ketua_rt"]').val(),
                    nama_ketua_rw: $('[name="nama_ketua_rw"]').val(),
                    bulan: $('[name="bulan"]').val(),
                    sifat: $('[name="sifat"]').val(),
                    hal: $('[name="hal"]').val(),
                    isi_surat: $('[name="isi_surat"]').val(),
                    tujuan_internal: $('[name="tujuan_internal"]').val(),
                    tujuan_eksternal: $('[name="tujuan_eksternal"]').val(),
                    ttd_pemohon: signaturePadPemohon.isEmpty() ? '' : signaturePadPemohon.toDataURL()
                };

                console.log('📊 Form data collected:', formData);

                // Only save if there's substantial content
                if (formData.nama_lengkap || formData.alamat || formData.pekerjaan) {
                    try {
                        const dataString = JSON.stringify(formData);
                        const dataSize = new Blob([dataString]).size;

                        console.log('💾 Draft size:', formatFileSize(dataSize));

                        localStorage.setItem('psu_draft', JSON.stringify(formData));
                        console.log('📝 PSU Draft auto-saved successfully!');

                        showDraftNotification('Draft tersimpan', 'success');
                    } catch (e) {
                        console.log('❌ Error saving draft:', e);
                        showDraftNotification('Gagal menyimpan draft', 'error');
                    }
                } else {
                    console.log('❌ No substantial content to save');
                }
            }, 3000); // Auto-save after 3 seconds of inactivity
        }

        // Helper function to show draft notifications
        function showDraftNotification(message, type = 'success') {
            if (!$('.draft-indicator').length) {
                const colors = {
                    success: '#28a745',
                    warning: '#ffc107',
                    error: '#dc3545'
                };

                const textColors = {
                    success: 'white',
                    warning: 'black',
                    error: 'white'
                };

                $('body').append(`
                    <div class="draft-indicator position-fixed"
                         style="top: 20px; right: 20px; z-index: 9999;
                                background: ${colors[type]}; color: ${textColors[type]};
                                padding: 8px 15px; border-radius: 20px; font-size: 12px; opacity: 0.9;">
                        <i class="fas fa-save"></i> ${message}
                    </div>
                `);

                setTimeout(() => {
                    $('.draft-indicator').fadeOut(() => {
                        $('.draft-indicator').remove();
                    });
                }, 3000);
            }
        }

        // Load draft on page load
        function loadDraft() {
            const draft = localStorage.getItem('psu_draft');
            if (draft) {
                try {
                    const data = JSON.parse(draft);

                    Swal.fire({
                        title: 'Draft Ditemukan',
                        text: 'Ditemukan draft PSU yang belum disimpan. Apakah Anda ingin memuat draft tersebut?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Muat Draft',
                        cancelButtonText: 'Tidak, Mulai Baru',
                        confirmButtonColor: '#6777ef',
                        cancelButtonColor: '#dc3545'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Load all form data
                            Object.keys(data).forEach(key => {
                                if (data[key] && key !== 'ttd_pemohon') {
                                    // Restore regular form fields
                                    $(`[name="${key}"]`).val(data[key]);
                                }
                            });

                            // Trigger change events for dependent fields
                            $('#rw-select-psu').trigger('change');
                            $('#ditujukanKepada').trigger('change');

                            // Restore signatures
                            if (data.ttd_pemohon && data.ttd_pemohon !== '') {
                                const img = new Image();
                                img.onload = function() {
                                    const ctx = signaturePadPemohon._ctx;
                                    signaturePadPemohon.clear();
                                    ctx.drawImage(img, 0, 0);
                                };
                                img.src = data.ttd_pemohon;
                            }

                            Swal.fire({
                                icon: 'success',
                                title: 'Draft Dimuat',
                                text: 'Draft berhasil dimuat ke form.',
                                timer: 2500,
                                showConfirmButton: false
                            });
                        } else {
                            localStorage.removeItem('psu_draft');
                        }
                    });
                } catch (e) {
                    console.log('❌ Error parsing draft:', e);
                    localStorage.removeItem('psu_draft');
                }
            }
        }

        // Bind auto-save events to form inputs
        function bindAutoSaveEvents() {
            console.log('🔗 Binding auto-save events...');

            // Bind to all form inputs
            $('input[type="text"], input[type="email"], input[type="date"], textarea, select').on('input change keyup paste', function() {
                console.log('📝 Form input detected, triggering auto-save...');
                autoSaveDraft();
            });

            console.log('✅ Auto-save events bound successfully');
        }

        // Warning before leaving page with unsaved changes
        function bindBeforeUnloadWarning() {
            let formChanged = false;
            let isNavigatingAway = false;

            // More specific event binding
            $(document).on('input change keyup paste', 'input, textarea, select', function() {
                console.log('📝 Form changed detected for auto-save');
                formChanged = true;
                autoSaveDraft(); // Trigger auto-save immediately when form changes
            });

            // Custom navigation warning with SweetAlert
            function showLeaveConfirmation(callback) {
                if (!formChanged || isNavigatingAway) {
                    callback(true);
                    return;
                }

                Swal.fire({
                    title: 'Yakin ingin keluar?',
                    html: `
                        <div class="text-left">
                            <p>Anda memiliki perubahan yang belum disimpan.</p>
                            <p><strong>Data akan otomatis tersimpan sebagai draft.</strong></p>
                            <hr>
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                Draft akan dimuat kembali saat Anda membuka form ini lagi.
                            </small>
                        </div>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-sign-out-alt"></i> Ya, Keluar',
                    cancelButtonText: '<i class="fas fa-times"></i> Batal',
                    confirmButtonColor: '#f39c12',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Save draft before leaving including signatures
                        const formData = {
                            nama_lengkap: $('[name="nama_lengkap"]').val(),
                            alamat: $('[name="alamat"]').val(),
                            pekerjaan: $('[name="pekerjaan"]').val(),
                            jenis_kelamin: $('[name="jenis_kelamin"]').val(),
                            tempat_lahir: $('[name="tempat_lahir"]').val(),
                            tanggal_lahir: $('[name="tanggal_lahir"]').val(),
                            agama: $('[name="agama"]').val(),
                            status_perkawinan: $('[name="status_perkawinan"]').val(),
                            kewarganegaraan: $('[name="kewarganegaraan"]').val(),
                            nomor_kk: $('[name="nomor_kk"]').val(),
                            rt: $('[name="rt"]').val(),
                            rw: $('[name="rw"]').val(),
                            ditujukan_kepada: $('[name="ditujukan_kepada"]').val(),
                            nama_ketua_rt: $('[name="nama_ketua_rt"]').val(),
                            nama_ketua_rw: $('[name="nama_ketua_rw"]').val(),
                            bulan: $('[name="bulan"]').val(),
                            sifat: $('[name="sifat"]').val(),
                            hal: $('[name="hal"]').val(),
                            isi_surat: $('[name="isi_surat"]').val(),
                            tujuan_internal: $('[name="tujuan_internal"]').val(),
                            tujuan_eksternal: $('[name="tujuan_eksternal"]').val(),
                            ttd_pemohon: signaturePadPemohon.isEmpty() ? '' : signaturePadPemohon.toDataURL()
                        };

                        if (formData.nama_lengkap || formData.alamat || formData.pekerjaan) {
                            localStorage.setItem('psu_draft', JSON.stringify(formData));
                            console.log('💾 Final draft saved before navigation');
                        }

                        // Show saving notification
                        Swal.fire({
                            icon: 'success',
                            title: 'Draft Tersimpan',
                            text: 'Data berhasil disimpan sebagai draft.',
                            timer: 1500,
                            showConfirmButton: false,
                            timerProgressBar: true
                        }).then(() => {
                            isNavigatingAway = true;
                            formChanged = false;
                            callback(true);
                        });
                    } else {
                        callback(false);
                    }
                });
            }

            // Intercept navigation attempts
            $('a[href]:not([href="#"]):not([href^="javascript:"]):not([target="_blank"])').on('click', function(e) {
                if (formChanged && !isNavigatingAway) {
                    e.preventDefault();
                    const href = $(this).attr('href');
                    showLeaveConfirmation((shouldLeave) => {
                        if (shouldLeave) {
                            window.location.href = href;
                        }
                    });
                }
            });

            // Intercept back button and other navigation
            $(window).on('beforeunload', function(e) {
                if (formChanged && !isNavigatingAway) {
                    // Emergency save for browser navigation
                    const formData = {
                        nama_lengkap: $('[name="nama_lengkap"]').val(),
                        alamat: $('[name="alamat"]').val(),
                        pekerjaan: $('[name="pekerjaan"]').val(),
                        jenis_kelamin: $('[name="jenis_kelamin"]').val(),
                        tempat_lahir: $('[name="tempat_lahir"]').val(),
                        tanggal_lahir: $('[name="tanggal_lahir"]').val(),
                        agama: $('[name="agama"]').val(),
                        status_perkawinan: $('[name="status_perkawinan"]').val(),
                        kewarganegaraan: $('[name="kewarganegaraan"]').val(),
                        nomor_kk: $('[name="nomor_kk"]').val(),
                        rt: $('[name="rt"]').val(),
                        rw: $('[name="rw"]').val(),
                        ditujukan_kepada: $('[name="ditujukan_kepada"]').val(),
                        nama_ketua_rt: $('[name="nama_ketua_rt"]').val(),
                        nama_ketua_rw: $('[name="nama_ketua_rw"]').val(),
                        bulan: $('[name="bulan"]').val(),
                        sifat: $('[name="sifat"]').val(),
                        hal: $('[name="hal"]').val(),
                        isi_surat: $('[name="isi_surat"]').val(),
                        tujuan_internal: $('[name="tujuan_internal"]').val(),
                        tujuan_eksternal: $('[name="tujuan_eksternal"]').val(),
                        ttd_pemohon: signaturePadPemohon.isEmpty() ? '' : signaturePadPemohon.toDataURL()
                    };

                    if (formData.nama_lengkap || formData.alamat || formData.pekerjaan) {
                        localStorage.setItem('psu_draft', JSON.stringify(formData));
                        console.log('💾 Emergency draft saved before unload');
                    }

                    // Standard browser warning (can't use SweetAlert here)
                    const message = 'Data akan tersimpan sebagai draft.';
                    e.returnValue = message;
                    return message;
                }
            });

            // Handle form submission - disable warnings
            $('#createForm').on('submit', function() {
                formChanged = false;
                isNavigatingAway = true;
                localStorage.removeItem('psu_draft');
                $(window).off('beforeunload');
            });

            // Handle explicit navigation buttons with SweetAlert
            $('.btn-secondary, .breadcrumb-item a').on('click', function(e) {
                if (formChanged && !isNavigatingAway) {
                    e.preventDefault();
                    const href = $(this).attr('href') || "{{ route('psu.index') }}";
                    showLeaveConfirmation((shouldLeave) => {
                        if (shouldLeave) {
                            window.location.href = href;
                        }
                    });
                }
            });
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        }
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
