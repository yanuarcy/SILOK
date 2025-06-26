@extends('Template.template')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">

    <!-- Leaflet CSS for Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        .form-control:focus {
            border-color: #6777ef;
            box-shadow: 0 0 0 0.2rem rgba(103, 119, 239, 0.25);
        }

        .file-upload-wrapper {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
            cursor: pointer;
        }

        .file-upload-wrapper:hover {
            border-color: #6777ef;
            background-color: #f4f6f9;
        }

        .file-upload-wrapper.dragover {
            border-color: #6777ef;
            background-color: #e8ecff;
        }

        .file-info {
            display: none;
            margin-top: 15px;
            padding: 15px;
            background-color: #e8f5e8;
            border-radius: 8px;
            border-left: 4px solid #28a745;
        }

        .signature-pad {
            border: 2px solid #dee2e6;
            border-radius: 8px;
            background-color: white;
        }

        #map {
            height: 250px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }

        .location-info {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
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
                        <a href="{{ route('puntadewa.index') }}">Data PUNTADEWA</a>
                    </div>
                    <div class="breadcrumb-item">Tambah PUNTADEWA</div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Form Pernyataan Tempat Tinggal Penduduk Non Permanen (PUNTADEWA)</h4>
                        </div>
                        <div class="card-body">
                            <form id="createForm" enctype="multipart/form-data">
                                @csrf

                                {{-- Data Pemohon --}}
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="text-primary">
                                            <i class="fas fa-user"></i> Data Pemohon
                                        </h5>
                                        <hr>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Pemohon <span class="required">*</span></label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="nama_pemohon"
                                                   value="{{ $user->name }}"
                                                   placeholder="Nama lengkap pemohon">
                                        </div>

                                        <div class="form-group">
                                            <label>NIK <span class="required">*</span></label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="nik"
                                                   value="{{ $user->nik ?? '' }}"
                                                   placeholder="Nomor Induk Kependudukan"
                                                   maxlength="16">
                                        </div>

                                        <div class="form-group">
                                            <label>Alamat Asal <span class="required">*</span></label>
                                            <textarea class="form-control"
                                                      name="alamat_asal"
                                                      rows="3"
                                                      placeholder="Alamat lengkap tempat tinggal sebelumnya">{{ $user->alamat_lengkap ?? '' }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Upload KK Asal <span class="required">*</span></label>
                                            <div class="file-upload-wrapper" id="fileUploadWrapper">
                                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                                <h6 class="text-muted">Drag & Drop file KK atau klik untuk pilih</h6>
                                                <p class="text-muted small">Format: PDF, JPG, JPEG, PNG - Maksimal 2MB</p>
                                                <input type="file"
                                                       class="form-control-file d-none"
                                                       name="file_kk_asal"
                                                       id="file_kk_asal"
                                                       accept=".pdf,.jpg,.jpeg,.png">
                                            </div>
                                            <div class="file-info" id="fileInfo">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-file fa-2x me-3"></i>
                                                    <div>
                                                        <div class="fw-bold" id="fileName"></div>
                                                        <div class="text-muted small" id="fileSize"></div>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-outline-danger ms-auto" id="removeFile">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email"
                                                   class="form-control"
                                                   name="email"
                                                   value="{{ $user->email }}"
                                                   placeholder="Email pemohon"
                                                   readonly>
                                        </div>

                                        <div class="form-group">
                                            <label>No. Telepon</label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="no_telepon"
                                                   value="{{ $user->telp ?? '' }}"
                                                   placeholder="Nomor telepon pemohon"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>

                                {{-- RT/RW Selection --}}
                                <div class="rt-rw-section">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-map-marker-alt"></i> Lokasi RT/RW Tempat Tinggal
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>RT <span class="required">*</span></label>
                                                <select name="rt" class="form-control" required>
                                                    <option value="">Pilih RT</option>
                                                    @foreach($availableRT as $rt)
                                                        <option value="{{ $rt }}">RT {{ $rt }}</option>
                                                    @endforeach
                                                </select>
                                                <small class="form-text text-muted">Pilih RT sesuai dengan lokasi tempat tinggal Anda</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>RW <span class="required">*</span></label>
                                                <select name="rw" class="form-control" required>
                                                    <option value="">Pilih RW</option>
                                                    @foreach($availableRW as $rw)
                                                        <option value="{{ $rw }}">RW {{ $rw }}</option>
                                                    @endforeach
                                                </select>
                                                <small class="form-text text-muted">Pilih RW sesuai dengan lokasi tempat tinggal Anda</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Penting:</strong> Pilihan RT dan RW akan menentukan siapa yang akan menyetujui permohonan PUNTADEWA Anda. Pastikan Anda memilih sesuai dengan lokasi tempat tinggal yang sebenarnya.
                                    </div>
                                </div>

                                {{-- Alasan Tinggal --}}
                                <div class="section-divider">
                                    <div class="row">
                                        <div class="col-12">
                                            <h5 class="text-primary">
                                                <i class="fas fa-clipboard-list"></i> Alasan Tinggal di Surabaya
                                            </h5>
                                            <hr>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Memang Benar yang bersangkutan Bertempat tinggal di alamat sebagai berikut <span class="required">*</span></label>
                                                <textarea class="form-control"
                                                          name="alasan_tinggal"
                                                          rows="3"
                                                          placeholder="Jelaskan alamat tempat tinggal di Surabaya"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Bekerja --}}
                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="text-info">1. Bekerja :</h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nama Perusahaan / Wiraswasta</label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="nama_perusahaan"
                                                       placeholder="Nama perusahaan tempat bekerja">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Alamat Perusahaan</label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="alamat_perusahaan"
                                                       placeholder="Alamat lengkap perusahaan">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Sekolah --}}
                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="text-info">2. Sekolah :</h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nama Sekolah / Perguruan Tinggi</label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="nama_sekolah"
                                                       placeholder="Nama sekolah / universitas">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Alamat Sekolah/Perguruan Tinggi</label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="alamat_sekolah"
                                                       placeholder="Alamat lengkap sekolah">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Kesehatan --}}
                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="text-info">3. Kesehatan :</h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nama Rumah Sakit / Klinik</label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="nama_rumah_sakit"
                                                       placeholder="Nama rumah sakit / klinik">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Alamat Rumah Sakit</label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="alamat_rumah_sakit"
                                                       placeholder="Alamat lengkap rumah sakit">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Alasan Lainnya --}}
                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="text-info">4. Alasan Lainnya :</h6>
                                            <div class="form-group">
                                                <textarea class="form-control"
                                                          name="alasan_lainnya"
                                                          rows="3"
                                                          placeholder="Jelaskan alasan lainnya jika ada"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Data Penjamin --}}
                                <div class="section-divider">
                                    <div class="row">
                                        <div class="col-12">
                                            <h5 class="text-primary">
                                                <i class="fas fa-user-shield"></i> Data Penjamin (Pemilik Rumah Kost/Kontrakan/Sejenisnya)
                                            </h5>
                                            <hr>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nama Penjamin <span class="required">*</span></label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="nama_penjamin"
                                                       placeholder="Nama lengkap penjamin">
                                            </div>

                                            <div class="form-group">
                                                <label>NIK Penjamin <span class="required">*</span></label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="nik_penjamin"
                                                       placeholder="NIK penjamin"
                                                       maxlength="16">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Alamat Penjamin <span class="required">*</span></label>
                                                <textarea class="form-control"
                                                          name="alamat_penjamin"
                                                          rows="3"
                                                          placeholder="Alamat lengkap penjamin"></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label>No. Telepon Penjamin <span class="required">*</span></label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="no_telp_penjamin"
                                                       placeholder="Nomor telepon penjamin">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Lokasi --}}
                                <div class="section-divider">
                                    <div class="row">
                                        <div class="col-12">
                                            <h5 class="text-primary">
                                                <i class="fas fa-map-marker-alt"></i> Lokasi Tempat Tinggal
                                            </h5>
                                            <hr>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Koordinat Lokasi</label>
                                                <input type="text"
                                                       id="koordinat"
                                                       class="form-control"
                                                       placeholder="0.000000, 0.000000"
                                                       readonly>
                                                <input type="hidden" name="latitude" id="latitude">
                                                <input type="hidden" name="longitude" id="longitude">
                                                <small class="form-text text-muted">
                                                    Klik "Dapatkan Lokasi" untuk mengambil koordinat otomatis
                                                </small>
                                            </div>

                                            <div class="form-group">
                                                <button type="button" class="btn btn-info btn-sm" onclick="getCurrentLocation()">
                                                    <i class="fas fa-crosshairs"></i> Dapatkan Lokasi
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm ml-2" onclick="clearLocation()">
                                                    <i class="fas fa-times"></i> Hapus
                                                </button>
                                            </div>

                                            <div class="form-group">
                                                <label>Alamat Lokasi</label>
                                                <textarea class="form-control"
                                                          name="alamat_lokasi"
                                                          id="alamat_lokasi"
                                                          rows="3"
                                                          placeholder="Alamat akan otomatis terisi setelah mendapatkan lokasi"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Peta Lokasi</label>
                                                <div id="map"></div>
                                                <div class="location-info" id="locationInfo" style="display: none;">
                                                    <small class="text-muted">
                                                        <i class="fas fa-info-circle"></i>
                                                        Lokasi berhasil ditentukan. Anda dapat menyesuaikan posisi marker dengan mengklik pada peta.
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Tanda Tangan --}}
                                <div class="section-divider">
                                    <div class="row">
                                        <div class="col-12">
                                            <h5 class="text-primary">
                                                <i class="fas fa-signature"></i> Tanda Tangan Digital
                                            </h5>
                                            <hr>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Tanda Tangan Pemohon <span class="required">*</span></label>
                                                <canvas id="signaturePadPemohon"
                                                        class="signature-pad"
                                                        width="400"
                                                        height="200"
                                                        style="width: 100%; border: 2px solid #dee2e6; border-radius: 8px;"></canvas>
                                                <div class="mt-2">
                                                    <button type="button" class="btn btn-sm btn-secondary" onclick="clearSignaturePemohon()">
                                                        <i class="fas fa-eraser"></i> Hapus Tanda Tangan
                                                    </button>
                                                </div>
                                                <small class="form-text text-muted">
                                                    Gunakan mouse atau touch untuk membuat tanda tangan pemohon
                                                </small>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Tanda Tangan Pemilik Kost/Kontrakan <span class="required">*</span></label>
                                                <canvas id="signaturePadPemilikKost"
                                                        class="signature-pad"
                                                        width="400"
                                                        height="200"
                                                        style="width: 100%; border: 2px solid #dee2e6; border-radius: 8px;"></canvas>
                                                <div class="mt-2">
                                                    <button type="button" class="btn btn-sm btn-secondary" onclick="clearSignaturePemilikKost()">
                                                        <i class="fas fa-eraser"></i> Hapus Tanda Tangan
                                                    </button>
                                                </div>
                                                <small class="form-text text-muted">
                                                    Gunakan mouse atau touch untuk membuat tanda tangan pemilik kost/kontrakan
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="card-title">Catatan:</h6>
                                                    <ul class="small text-muted">
                                                        <li>Pastikan semua data telah diisi dengan benar</li>
                                                        <li>File KK harus berformat PDF, JPG, JPEG, atau PNG</li>
                                                        <li>Maksimal ukuran file adalah 2MB</li>
                                                        <li>Kedua tanda tangan digital wajib diisi</li>
                                                        <li>Lokasi akan membantu verifikasi alamat</li>
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
                                    <a href="{{ route('puntadewa.index') }}" class="btn btn-secondary">
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

    <!-- Leaflet JS for Map -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Signature Pad -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

    <script>
        let map;
        let marker;
        let signaturePadPemohon;
        let signaturePadPemilikKost;
        let selectedFile = null;

        $(document).ready(function() {
            // Initialize map
            initMap();

            // Initialize signature pads
            initSignaturePads();

            // Initialize auto-save and draft functionality
            bindAutoSaveEvents();
            bindBeforeUnloadWarning();

            // Load draft after a short delay
            setTimeout(loadDraft, 1000);

            // File upload handlers
            $('#fileUploadWrapper').on('click', function(e) {
                // Don't prevent default or stop propagation
                $('#file_kk_asal')[0].click();
            });

            $('#file_kk_asal').on('change', function(e) {
                if (this.files.length > 0) {
                    handleFileSelect(this.files[0]);
                }
            });

            // Drag and drop functionality
            $('#fileUploadWrapper').on('dragover dragenter', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).addClass('dragover');
            });

            $('#fileUploadWrapper').on('dragleave dragend', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('dragover');
            });

            $('#fileUploadWrapper').on('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('dragover');

                const files = e.originalEvent.dataTransfer.files;
                if (files.length > 0) {
                    // Set files to input element
                    const dt = new DataTransfer();
                    dt.items.add(files[0]);
                    $('#file_kk_asal')[0].files = dt.files;

                    // Handle file selection
                    handleFileSelect(files[0]);
                }
            });

            $('#removeFile').on('click', function() {
                selectedFile = null;
                $('#file_kk_asal').val('');
                $('#fileInfo').hide();
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

                btn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

                // Create FormData
                const formData = new FormData(this);

                // Add signatures
                if (!signaturePadPemohon.isEmpty()) {
                    formData.append('ttd_pemohon', signaturePadPemohon.toDataURL());
                }

                if (!signaturePadPemilikKost.isEmpty()) {
                    formData.append('ttd_pemilik_kost', signaturePadPemilikKost.toDataURL());
                }

                $.ajax({
                    url: "{{ route('puntadewa.store') }}",
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if(response.success) {
                            // Clear draft after successful submission
                            localStorage.removeItem('puntadewa_draft');

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                window.location.href = "{{ route('puntadewa.index') }}";
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

            // Show keyboard shortcuts hint
            setTimeout(() => {
                if (!localStorage.getItem('puntadewa_shortcuts_shown')) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Tips Penggunaan',
                        html: `
                            <div class="text-left">
                                <strong>Fitur yang tersedia:</strong><br>
                                • <kbd>Ctrl+S</kbd> untuk menyimpan<br>
                                • Auto-save draft setiap 3 detik<br>
                                • Draft otomatis dimuat saat membuka form<br>
                                • Validasi otomatis untuk semua field wajib
                            </div>
                        `,
                        confirmButtonText: 'Mengerti',
                        timer: 5000,
                        timerProgressBar: true
                    });
                    localStorage.setItem('puntadewa_shortcuts_shown', 'true');
                }
            }, 2000);

            console.log('✅ PUNTADEWA Create form with auto-save initialized successfully');
        });

        function initMap() {
            // Initialize map centered on Surabaya
            map = L.map('map').setView([-7.2575, 112.7521], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Add click event to map
            map.on('click', function(e) {
                setLocation(e.latlng.lat, e.latlng.lng);
            });
        }

        function initSignaturePads() {
            // Pemohon signature pad
            const canvasPemohon = document.getElementById('signaturePadPemohon');
            signaturePadPemohon = new SignaturePad(canvasPemohon, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)'
            });

            // Pemilik Kost signature pad
            const canvasPemilikKost = document.getElementById('signaturePadPemilikKost');
            signaturePadPemilikKost = new SignaturePad(canvasPemilikKost, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)'
            });

            // Resize canvas when window resizes
            window.addEventListener('resize', resizeCanvases);
            resizeCanvases();
        }

        function resizeCanvases() {
            // Resize pemohon canvas
            const canvasPemohon = document.getElementById('signaturePadPemohon');
            const ratioPemohon = Math.max(window.devicePixelRatio || 1, 1);
            canvasPemohon.width = canvasPemohon.offsetWidth * ratioPemohon;
            canvasPemohon.height = canvasPemohon.offsetHeight * ratioPemohon;
            canvasPemohon.getContext('2d').scale(ratioPemohon, ratioPemohon);
            signaturePadPemohon.clear();

            // Resize pemilik kost canvas
            const canvasPemilikKost = document.getElementById('signaturePadPemilikKost');
            const ratioPemilikKost = Math.max(window.devicePixelRatio || 1, 1);
            canvasPemilikKost.width = canvasPemilikKost.offsetWidth * ratioPemilikKost;
            canvasPemilikKost.height = canvasPemilikKost.offsetHeight * ratioPemilikKost;
            canvasPemilikKost.getContext('2d').scale(ratioPemilikKost, ratioPemilikKost);
            signaturePadPemilikKost.clear();
        }

        function clearSignaturePemohon() {
            if (signaturePadPemohon) {
                signaturePadPemohon.clear();
            }
        }

        function clearSignaturePemilikKost() {
            if (signaturePadPemilikKost) {
                signaturePadPemilikKost.clear();
            }
        }

        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    setLocation(lat, lng);
                }, function(error) {
                    Swal.fire('Error', 'Tidak dapat mengakses lokasi. Pastikan izin lokasi telah diberikan.', 'error');
                });
            } else {
                Swal.fire('Error', 'Geolocation tidak didukung oleh browser ini.', 'error');
            }
        }

        function setLocation(lat, lng) {
            $('#latitude').val(lat);
            $('#longitude').val(lng);
            $('#koordinat').val(lat.toFixed(6) + ', ' + lng.toFixed(6));

            // Update map
            if (marker) {
                map.removeLayer(marker);
            }

            marker = L.marker([lat, lng]).addTo(map);
            map.setView([lat, lng], 16);

            // Show location info
            $('#locationInfo').show();

            // Reverse geocoding to get address
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(response => response.json())
                .then(data => {
                    if (data.display_name) {
                        $('#alamat_lokasi').val(data.display_name);
                    }
                })
                .catch(error => {
                    console.log('Reverse geocoding error:', error);
                });
        }

        function clearLocation() {
            $('#latitude').val('');
            $('#longitude').val('');
            $('#koordinat').val('');
            $('#alamat_lokasi').val('');
            $('#locationInfo').hide();

            if (marker) {
                map.removeLayer(marker);
                marker = null;
            }

            map.setView([-7.2575, 112.7521], 13);
        }

        function handleFileSelect(file) {
            // Validate file type
            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
            if (!allowedTypes.includes(file.type)) {
                Swal.fire('Error', 'Hanya file PDF, JPG, JPEG, dan PNG yang diperbolehkan.', 'error');
                $('#file_kk_asal').val('');
                return;
            }

            // Validate file size (2MB)
            if (file.size > 2097152) {
                Swal.fire('Error', 'Ukuran file terlalu besar. Maksimal 2MB.', 'error');
                $('#file_kk_asal').val('');
                return;
            }

            selectedFile = file;
            $('#fileName').text(file.name);
            $('#fileSize').text(formatFileSize(file.size));
            $('#fileInfo').show();
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        }

        function validateForm() {
            let isValid = true;
            const errors = [];
            const requiredFields = [
                { name: 'nama_pemohon', label: 'Nama Pemohon' },
                { name: 'nik', label: 'NIK' },
                { name: 'alamat_asal', label: 'Alamat Asal' },
                { name: 'alasan_tinggal', label: 'Alamat Tempat Tinggal' },
                { name: 'nama_penjamin', label: 'Nama Penjamin' },
                { name: 'nik_penjamin', label: 'NIK Penjamin' },
                { name: 'alamat_penjamin', label: 'Alamat Penjamin' },
                { name: 'no_telp_penjamin', label: 'No. Telepon Penjamin' }
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

            // Check file upload
            if (!$('#file_kk_asal')[0].files.length) {
                errors.push('File KK Asal wajib diupload');
                isValid = false;
            }

            // Check signatures
            if (signaturePadPemohon.isEmpty()) {
                errors.push('Tanda tangan pemohon wajib diisi');
                isValid = false;
            }

            if (signaturePadPemilikKost.isEmpty()) {
                errors.push('Tanda tangan pemilik kost/kontrakan wajib diisi');
                isValid = false;
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
                    nama_pemohon: $('[name="nama_pemohon"]').val(),
                    nik: $('[name="nik"]').val(),
                    alamat_asal: $('[name="alamat_asal"]').val(),
                    alasan_tinggal: $('[name="alasan_tinggal"]').val(),
                    nama_perusahaan: $('[name="nama_perusahaan"]').val(),
                    alamat_perusahaan: $('[name="alamat_perusahaan"]').val(),
                    nama_sekolah: $('[name="nama_sekolah"]').val(),
                    alamat_sekolah: $('[name="alamat_sekolah"]').val(),
                    nama_rumah_sakit: $('[name="nama_rumah_sakit"]').val(),
                    alamat_rumah_sakit: $('[name="alamat_rumah_sakit"]').val(),
                    alasan_lainnya: $('[name="alasan_lainnya"]').val(),
                    nama_penjamin: $('[name="nama_penjamin"]').val(),
                    nik_penjamin: $('[name="nik_penjamin"]').val(),
                    alamat_penjamin: $('[name="alamat_penjamin"]').val(),
                    no_telp_penjamin: $('[name="no_telp_penjamin"]').val(),
                    alamat_lokasi: $('[name="alamat_lokasi"]').val(),
                    latitude: $('[name="latitude"]').val(),
                    longitude: $('[name="longitude"]').val()
                };

                // Save file information (file name and size, not the actual file data)
                const fileInput = $('#file_kk_asal')[0];
                if (fileInput.files.length > 0) {
                    const file = fileInput.files[0];
                    formData.file_info = {
                        name: file.name,
                        size: file.size,
                        type: file.type,
                        lastModified: file.lastModified
                    };
                    console.log('📎 File info saved:', formData.file_info);
                }

                // Save signature data (base64)
                if (signaturePadPemohon && !signaturePadPemohon.isEmpty()) {
                    try {
                        formData.ttd_pemohon = signaturePadPemohon.toDataURL();
                        console.log('✍️ Signature pemohon saved (length:', formData.ttd_pemohon.length, ')');
                    } catch (e) {
                        console.log('❌ Error saving pemohon signature:', e);
                    }
                }

                if (signaturePadPemilikKost && !signaturePadPemilikKost.isEmpty()) {
                    try {
                        formData.ttd_pemilik_kost = signaturePadPemilikKost.toDataURL();
                        console.log('✍️ Signature pemilik kost saved (length:', formData.ttd_pemilik_kost.length, ')');
                    } catch (e) {
                        console.log('❌ Error saving pemilik kost signature:', e);
                    }
                }

                console.log('📊 Form data collected:', formData);

                // Only save if there's substantial content
                if (formData.nama_pemohon || formData.alamat_asal || formData.nama_penjamin) {
                    try {
                        // Check localStorage size limit (usually 5-10MB)
                        const dataString = JSON.stringify(formData);
                        const dataSize = new Blob([dataString]).size;

                        console.log('💾 Draft size:', formatFileSize(dataSize));

                        if (dataSize > 5242880) { // 5MB limit
                            // If too large, save without signatures
                            const formDataWithoutSignatures = { ...formData };
                            delete formDataWithoutSignatures.ttd_pemohon;
                            delete formDataWithoutSignatures.ttd_pemilik_kost;

                            localStorage.setItem('puntadewa_draft', JSON.stringify(formDataWithoutSignatures));
                            console.log('⚠️ Draft saved without signatures (too large)');

                            showDraftNotification('Draft tersimpan (tanpa tanda tangan)', 'warning');
                        } else {
                            localStorage.setItem('puntadewa_draft', JSON.stringify(formData));
                            console.log('📝 PUNTADEWA Draft auto-saved successfully!');

                            showDraftNotification('Draft tersimpan lengkap', 'success');
                        }
                    } catch (e) {
                        console.log('❌ Error saving draft:', e);
                        // Try saving without signatures if error occurs
                        const formDataWithoutSignatures = { ...formData };
                        delete formDataWithoutSignatures.ttd_pemohon;
                        delete formDataWithoutSignatures.ttd_pemilik_kost;

                        try {
                            localStorage.setItem('puntadewa_draft', JSON.stringify(formDataWithoutSignatures));
                            console.log('📝 Fallback: Draft saved without signatures');
                            showDraftNotification('Draft tersimpan (tanpa tanda tangan)', 'warning');
                        } catch (fallbackError) {
                            console.log('❌ Fallback also failed:', fallbackError);
                            showDraftNotification('Gagal menyimpan draft', 'error');
                        }
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
            const draft = localStorage.getItem('puntadewa_draft');
            if (draft) {
                try {
                    const data = JSON.parse(draft);

                    Swal.fire({
                        title: 'Draft Ditemukan',
                        text: 'Ditemukan draft PUNTADEWA yang belum disimpan. Apakah Anda ingin memuat draft tersebut?',
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
                                if (key === 'file_info') {
                                    // Show file info but can't restore actual file
                                    if (data[key]) {
                                        $('#fileInfo .fw-bold').text(data[key].name + ' (dari draft)');
                                        $('#fileInfo .text-muted').text(formatFileSize(data[key].size) + ' - File perlu diupload ulang');
                                        $('#fileInfo').show();
                                        console.log('📎 File info restored from draft:', data[key]);
                                    }
                                } else if (key === 'ttd_pemohon') {
                                    // Restore signature pemohon
                                    if (data[key] && signaturePadPemohon) {
                                        try {
                                            signaturePadPemohon.fromDataURL(data[key]);
                                            console.log('✍️ Signature pemohon restored from draft');
                                        } catch (e) {
                                            console.log('❌ Error restoring pemohon signature:', e);
                                        }
                                    }
                                } else if (key === 'ttd_pemilik_kost') {
                                    // Restore signature pemilik kost
                                    if (data[key] && signaturePadPemilikKost) {
                                        try {
                                            signaturePadPemilikKost.fromDataURL(data[key]);
                                            console.log('✍️ Signature pemilik kost restored from draft');
                                        } catch (e) {
                                            console.log('❌ Error restoring pemilik kost signature:', e);
                                        }
                                    }
                                } else if (data[key]) {
                                    // Restore regular form fields
                                    $(`[name="${key}"]`).val(data[key]);
                                }
                            });

                            // Set location if exists
                            if (data.latitude && data.longitude) {
                                setLocation(parseFloat(data.latitude), parseFloat(data.longitude));
                            }

                            let message = 'Draft berhasil dimuat ke form.';
                            if (data.ttd_pemohon || data.ttd_pemilik_kost) {
                                message += ' Termasuk tanda tangan digital.';
                            }
                            if (data.file_info) {
                                message += ' File perlu diupload ulang.';
                            }

                            Swal.fire({
                                icon: 'success',
                                title: 'Draft Dimuat',
                                text: message,
                                timer: 2500,
                                showConfirmButton: false
                            });
                        } else {
                            localStorage.removeItem('puntadewa_draft');
                        }
                    });
                } catch (e) {
                    console.log('❌ Error parsing draft:', e);
                    localStorage.removeItem('puntadewa_draft');
                }
            }
        }

        // Bind auto-save events to form inputs
        function bindAutoSaveEvents() {
            console.log('🔗 Binding auto-save events...');

            // Bind to all form inputs
            $('input[type="text"], input[type="email"], input[type="number"], textarea, select').on('input change keyup paste', function() {
                console.log('📝 Form input detected, triggering auto-save...');
                autoSaveDraft();
            });

            // Also bind to signature pads (when they change)
            $(document).on('pointerup mouseup touchend', '#signaturePadPemohon, #signaturePadPemilikKost', function() {
                console.log('✍️ Signature pad changed, triggering auto-save...');
                autoSaveDraft();
            });

            // Bind to file upload
            $('#file_kk_asal').on('change', function() {
                console.log('📎 File uploaded, triggering auto-save...');
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
                        // Save draft before leaving
                        const formData = {
                            nama_pemohon: $('[name="nama_pemohon"]').val(),
                            nik: $('[name="nik"]').val(),
                            alamat_asal: $('[name="alamat_asal"]').val(),
                            alasan_tinggal: $('[name="alasan_tinggal"]').val(),
                            nama_perusahaan: $('[name="nama_perusahaan"]').val(),
                            alamat_perusahaan: $('[name="alamat_perusahaan"]').val(),
                            nama_sekolah: $('[name="nama_sekolah"]').val(),
                            alamat_sekolah: $('[name="alamat_sekolah"]').val(),
                            nama_rumah_sakit: $('[name="nama_rumah_sakit"]').val(),
                            alamat_rumah_sakit: $('[name="alamat_rumah_sakit"]').val(),
                            alasan_lainnya: $('[name="alasan_lainnya"]').val(),
                            nama_penjamin: $('[name="nama_penjamin"]').val(),
                            nik_penjamin: $('[name="nik_penjamin"]').val(),
                            alamat_penjamin: $('[name="alamat_penjamin"]').val(),
                            no_telp_penjamin: $('[name="no_telp_penjamin"]').val(),
                            alamat_lokasi: $('[name="alamat_lokasi"]').val(),
                            latitude: $('[name="latitude"]').val(),
                            longitude: $('[name="longitude"]').val()
                        };

                        // Add signatures if available
                        if (signaturePadPemohon && !signaturePadPemohon.isEmpty()) {
                            formData.ttd_pemohon = signaturePadPemohon.toDataURL();
                        }
                        if (signaturePadPemilikKost && !signaturePadPemilikKost.isEmpty()) {
                            formData.ttd_pemilik_kost = signaturePadPemilikKost.toDataURL();
                        }

                        // Add file info if available
                        const fileInput = $('#file_kk_asal')[0];
                        if (fileInput.files.length > 0) {
                            const file = fileInput.files[0];
                            formData.file_info = {
                                name: file.name,
                                size: file.size,
                                type: file.type,
                                lastModified: file.lastModified
                            };
                        }

                        if (formData.nama_pemohon || formData.alamat_asal || formData.nama_penjamin) {
                            localStorage.setItem('puntadewa_draft', JSON.stringify(formData));
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
                        nama_pemohon: $('[name="nama_pemohon"]').val(),
                        nik: $('[name="nik"]').val(),
                        alamat_asal: $('[name="alamat_asal"]').val(),
                        alasan_tinggal: $('[name="alasan_tinggal"]').val(),
                        nama_perusahaan: $('[name="nama_perusahaan"]').val(),
                        alamat_perusahaan: $('[name="alamat_perusahaan"]').val(),
                        nama_sekolah: $('[name="nama_sekolah"]').val(),
                        alamat_sekolah: $('[name="alamat_sekolah"]').val(),
                        nama_rumah_sakit: $('[name="nama_rumah_sakit"]').val(),
                        alamat_rumah_sakit: $('[name="alamat_rumah_sakit"]').val(),
                        alasan_lainnya: $('[name="alasan_lainnya"]').val(),
                        nama_penjamin: $('[name="nama_penjamin"]').val(),
                        nik_penjamin: $('[name="nik_penjamin"]').val(),
                        alamat_penjamin: $('[name="alamat_penjamin"]').val(),
                        no_telp_penjamin: $('[name="no_telp_penjamin"]').val(),
                        alamat_lokasi: $('[name="alamat_lokasi"]').val(),
                        latitude: $('[name="latitude"]').val(),
                        longitude: $('[name="longitude"]').val()
                    };

                    if (formData.nama_pemohon || formData.alamat_asal || formData.nama_penjamin) {
                        localStorage.setItem('puntadewa_draft', JSON.stringify(formData));
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
                localStorage.removeItem('puntadewa_draft');
                $(window).off('beforeunload');
            });

            // Handle explicit navigation buttons with SweetAlert
            $('.btn-secondary, .breadcrumb-item a').on('click', function(e) {
                if (formChanged && !isNavigatingAway) {
                    e.preventDefault();
                    const href = $(this).attr('href') || "{{ route('puntadewa.index') }}";
                    showLeaveConfirmation((shouldLeave) => {
                        if (shouldLeave) {
                            window.location.href = href;
                        }
                    });
                }
            });
        }
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
