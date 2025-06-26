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

        .alert-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #f8f9fa 100%);
            color: #856404;
            border-left: 4px solid #ffc107;
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

        .signature-validation-error {
            border: 2px solid #dc3545 !important;
            background-color: #f8d7da !important;
        }

        .current-signature {
            border: 2px solid #28a745;
            border-radius: 8px;
            padding: 15px;
            background-color: #f8fff9;
            margin-bottom: 15px;
            text-align: center;
        }

        .current-signature img {
            max-width: 100%;
            max-height: 150px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .edit-indicator {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
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

        .breadcrumb-modern {
            background: transparent;
            padding: 0;
            margin-bottom: 20px;
        }

        .breadcrumb-modern .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
            color: #6c757d;
            font-weight: bold;
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
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-modern">
                        <li class="breadcrumb-item">
                            <a href="{{ route('surat-pengantar.index') }}">Data Surat Pengantar</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('surat-pengantar.show', $suratPengantar->id) }}">{{ $suratPengantar->nomor_surat }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between w-100">
                                <div>
                                    <h4>Edit Surat Pengantar/Keterangan</h4>
                                    <small class="text-muted">{{ $suratPengantar->nomor_surat }}</small>
                                </div>
                                <div>
                                    <span class="badge badge-info">{{ $suratPengantar->status_text }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Edit indicator -->
                            <div class="edit-indicator">
                                <i class="fas fa-edit"></i> Mode Edit - Anda sedang mengedit dokumen yang sudah ada
                            </div>

                            @if(!$suratPengantar->canBeEdited())
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Peringatan:</strong> Dokumen ini tidak dapat diedit karena sudah diproses. Status saat ini: {{ $suratPengantar->status_text }}
                            </div>
                            @endif

                            <form id="editForm">
                                @csrf
                                @method('PUT')

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
                                            <label>Nama Lengkap <span class="required">*</span></label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="nama_lengkap"
                                                   value="{{ $suratPengantar->nama_lengkap }}"
                                                   placeholder="Nama lengkap pemohon">
                                        </div>

                                        <div class="form-group">
                                            <label>Alamat <span class="required">*</span></label>
                                            <textarea class="form-control"
                                                      name="alamat"
                                                      rows="3"
                                                      placeholder="Alamat lengkap">{{ $suratPengantar->alamat }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Pekerjaan <span class="required">*</span></label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="pekerjaan"
                                                   value="{{ $suratPengantar->pekerjaan }}"
                                                   placeholder="Pekerjaan">
                                        </div>

                                        <div class="form-group">
                                            <label>Jenis Kelamin <span class="required">*</span></label>
                                            <select class="form-control" name="jenis_kelamin">
                                                <option value="">Pilih Jenis Kelamin</option>
                                                <option value="L" {{ $suratPengantar->jenis_kelamin === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                                <option value="P" {{ $suratPengantar->jenis_kelamin === 'P' ? 'selected' : '' }}>Perempuan</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Tempat Lahir <span class="required">*</span></label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="tempat_lahir"
                                                   value="{{ $suratPengantar->tempat_lahir }}"
                                                   placeholder="Tempat lahir">
                                        </div>

                                        <div class="form-group">
                                            <label>Tanggal Lahir <span class="required">*</span></label>
                                            <input type="date"
                                                   class="form-control"
                                                   name="tanggal_lahir"
                                                   value="{{ $suratPengantar->tanggal_lahir ? $suratPengantar->tanggal_lahir->format('Y-m-d') : '' }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Agama <span class="required">*</span></label>
                                            <select class="form-control" name="agama">
                                                <option value="">Pilih Agama</option>
                                                <option value="Islam" {{ $suratPengantar->agama === 'Islam' ? 'selected' : '' }}>Islam</option>
                                                <option value="Kristen" {{ $suratPengantar->agama === 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                                <option value="Katolik" {{ $suratPengantar->agama === 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                                <option value="Hindu" {{ $suratPengantar->agama === 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                                <option value="Buddha" {{ $suratPengantar->agama === 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                                <option value="Konghucu" {{ $suratPengantar->agama === 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Status Perkawinan <span class="required">*</span></label>
                                            <select class="form-control" name="status_perkawinan">
                                                <option value="">Pilih Status</option>
                                                <option value="Belum Kawin" {{ $suratPengantar->status_perkawinan === 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                                                <option value="Kawin" {{ $suratPengantar->status_perkawinan === 'Kawin' ? 'selected' : '' }}>Kawin</option>
                                                <option value="Cerai Hidup" {{ $suratPengantar->status_perkawinan === 'Cerai Hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                                                <option value="Cerai Mati" {{ $suratPengantar->status_perkawinan === 'Cerai Mati' ? 'selected' : '' }}>Cerai Mati</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Kewarganegaraan <span class="required">*</span></label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="kewarganegaraan"
                                                   value="{{ $suratPengantar->kewarganegaraan }}"
                                                   placeholder="Kewarganegaraan">
                                        </div>

                                        <div class="form-group">
                                            <label>Nomor KK/KTP <span class="required">*</span></label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="nomor_kk"
                                                   value="{{ $suratPengantar->nomor_kk }}"
                                                   placeholder="Nomor KK atau KTP"
                                                   maxlength="20">
                                        </div>

                                        <div class="form-group">
                                            <label>Status Dokumen</label>
                                            <input type="text"
                                                   class="form-control"
                                                   value="{{ $suratPengantar->status_text }}"
                                                   readonly>
                                        </div>

                                        <div class="form-group">
                                            <label>Nomor Surat</label>
                                            <input type="text"
                                                   class="form-control"
                                                   value="{{ $suratPengantar->nomor_surat }}"
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
                                                <label>RT <span class="required">*</span></label>
                                                <select name="rt" class="form-control" required>
                                                    <option value="">Pilih RT</option>
                                                    @foreach($availableRT as $rt)
                                                        <option value="{{ $rt }}" {{ $suratPengantar->rt == $rt ? 'selected' : '' }}>RT {{ $rt }}</option>
                                                    @endforeach
                                                </select>
                                                <small class="form-text text-muted">Pilih RT sesuai dengan lokasi Anda</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>RW <span class="required">*</span></label>
                                                <select name="rw" class="form-control" required>
                                                    <option value="">Pilih RW</option>
                                                    @foreach($availableRW as $rw)
                                                        <option value="{{ $rw }}" {{ $suratPengantar->rw == $rw ? 'selected' : '' }}>RW {{ $rw }}</option>
                                                    @endforeach
                                                </select>
                                                <small class="form-text text-muted">Pilih RW sesuai dengan lokasi Anda</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <strong>Perhatian:</strong> Jika Anda mengubah RT/RW, maka proses persetujuan akan dimulai ulang dari awal.
                                    </div>
                                </div>

                                {{-- Keperluan Surat --}}
                                <div class="section-divider">
                                    <div class="row">
                                        <div class="col-12">
                                            <h5 class="text-primary">
                                                <i class="fas fa-clipboard-list"></i> Keperluan Surat
                                            </h5>
                                            <hr>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Tujuan <span class="required">*</span></label>
                                                <textarea class="form-control"
                                                          name="tujuan"
                                                          rows="3"
                                                          placeholder="Jelaskan tujuan surat pengantar ini">{{ $suratPengantar->tujuan }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Keperluan <span class="required">*</span></label>
                                                <textarea class="form-control"
                                                          name="keperluan"
                                                          rows="3"
                                                          placeholder="Jelaskan keperluan surat pengantar ini">{{ $suratPengantar->keperluan }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Keterangan Lain (Opsional)</label>
                                                <textarea class="form-control"
                                                          name="keterangan_lain"
                                                          rows="3"
                                                          placeholder="Keterangan tambahan jika ada">{{ $suratPengantar->keterangan_lain }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Tanda Tangan Digital Pemohon --}}
                                <div class="signature-section">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-signature"></i> Tanda Tangan Digital Pemohon
                                    </h6>

                                    <!-- Current Signature Display -->
                                    @if($suratPengantar->hasPemohonSignature())
                                    <div class="current-signature">
                                        <h6><i class="fas fa-check-circle text-success mr-2"></i>Tanda Tangan Saat Ini</h6>
                                        <img src="{{ $suratPengantar->ttd_pemohon_url }}" alt="TTD Pemohon Saat Ini">
                                        <p class="mt-2 mb-0"><small class="text-success">Sudah ditandatangani</small></p>
                                    </div>
                                    @endif

                                    <div class="row justify-content-center">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label>
                                                    @if($suratPengantar->hasPemohonSignature())
                                                        Perbarui Tanda Tangan Pemohon (Opsional)
                                                    @else
                                                        Tanda Tangan Pemohon <span class="required">*</span>
                                                    @endif
                                                </label>
                                                <canvas id="signaturePadPemohon"
                                                        class="signature-pad"
                                                        width="500"
                                                        height="250"></canvas>
                                                <div class="signature-controls">
                                                    <button type="button" class="btn btn-secondary btn-sm" id="clearSignaturePemohon">
                                                        <i class="fas fa-eraser"></i> Hapus Tanda Tangan
                                                    </button>
                                                </div>
                                                <small class="form-text text-muted">
                                                    @if($suratPengantar->hasPemohonSignature())
                                                        Buat tanda tangan baru untuk mengganti yang lama
                                                    @else
                                                        Gunakan mouse atau touch untuk membuat tanda tangan pemohon
                                                    @endif
                                                </small>
                                                <input type="hidden" name="ttd_pemohon" id="ttdPemohonInput">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Catatan:</strong>
                                        <ul class="small mb-0 mt-2">
                                            @if($suratPengantar->hasPemohonSignature())
                                                <li>Tanda tangan sudah ada, buat tanda tangan baru jika ingin mengubah</li>
                                                <li>Jika tidak membuat tanda tangan baru, tanda tangan lama akan tetap digunakan</li>
                                            @else
                                                <li>Tanda tangan pemohon wajib diisi</li>
                                            @endif
                                            <li>Pastikan tanda tangan sudah sesuai sebelum menyimpan</li>
                                            <li>Gunakan mouse atau sentuh layar untuk membuat tanda tangan</li>
                                            <li>Tanda tangan akan disertakan dalam dokumen resmi</li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="card-footer text-right border-0 pt-3">
                                    @if($suratPengantar->canBeEdited())
                                        <button type="submit" class="btn btn-primary" id="updateBtn">
                                            <i class="fas fa-save"></i> Simpan Perubahan
                                        </button>
                                    @endif
                                    <a href="{{ route('surat-pengantar.show', $suratPengantar->id) }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Kembali
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
        // Global variables for signature pads
        let signaturePadPemohon;

        $(document).ready(function() {
            // Initialize signature pads
            initializeSignaturePads();

            // Form submission
            $('#editForm').on('submit', function(e) {
                e.preventDefault();

                @if(!$suratPengantar->canBeEdited())
                    Swal.fire('Error', 'Dokumen ini tidak dapat diedit karena sudah diproses.', 'error');
                    return;
                @endif

                // Validate required fields
                if (!validateForm()) {
                    return;
                }

                const form = $(this);
                const btn = $('#updateBtn');

                // Reset form state
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                $('.signature-validation-error').removeClass('signature-validation-error');

                btn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

                // Get signature data
                // Hanya kirim TTD jika signature pad tidak kosong (ada TTD baru)
                let ttdPemohon = '';
                if (!signaturePadPemohon.isEmpty()) {
                    ttdPemohon = signaturePadPemohon.toDataURL();
                }
                $('#ttdPemohonInput').val(ttdPemohon);

                // Create FormData
                const formData = new FormData(this);

                console.log('TTD Pemohon value:', ttdPemohon ? 'Ada TTD baru' : 'Gunakan TTD lama');

                $.ajax({
                    url: "{{ route('surat-pengantar.update', $suratPengantar->id) }}",
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if(response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 3000
                            }).then(() => {
                                window.location.href = "{{ route('surat-pengantar.show', $suratPengantar->id) }}";
                            });
                        }
                    },
                    error: function(xhr) {
                        btn.html('<i class="fas fa-save"></i> Simpan Perubahan').prop('disabled', false);

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

            console.log('✅ SuratPengantar Edit form initialized successfully');
        });

        // Initialize signature pads
        function initializeSignaturePads() {
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
            };

            console.log('✅ Signature pad initialized');
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
                { name: 'tujuan', label: 'Tujuan' },
                { name: 'keperluan', label: 'Keperluan' },
                { name: 'rt', label: 'RT' },
                { name: 'rw', label: 'RW' }
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

            // Check signature pemohon (required if no existing signature or if updating)
            @if(!$suratPengantar->hasPemohonSignature())
            if (signaturePadPemohon.isEmpty()) {
                errors.push('Tanda tangan pemohon wajib diisi');
                $('#signaturePadPemohon').addClass('signature-validation-error');
                isValid = false;
            } else {
                $('#signaturePadPemohon').removeClass('signature-validation-error');
            }
            @else
            // If existing signature, validation is optional (can keep old signature)
            $('#signaturePadPemohon').removeClass('signature-validation-error');
            @endif

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
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
