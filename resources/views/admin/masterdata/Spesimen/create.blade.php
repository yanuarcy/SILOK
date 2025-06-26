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

        .file-upload-wrapper {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
            cursor: pointer;
            margin-bottom: 15px;
        }

        .file-upload-wrapper:hover {
            border-color: #6777ef;
            background-color: #f4f6f9;
        }

        .file-upload-wrapper.dragover {
            border-color: #6777ef;
            background-color: #e8ecff;
        }

        .file-preview {
            display: none;
            margin-top: 15px;
        }

        .file-item {
            display: flex;
            align-items: center;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 6px;
            margin-bottom: 8px;
            border-left: 4px solid #28a745;
        }

        .file-item .file-icon {
            font-size: 1.5rem;
            margin-right: 10px;
        }

        .file-item .file-details {
            flex: 1;
        }

        .file-item .file-name {
            font-weight: 500;
            color: #2c3e50;
        }

        .file-item .file-size {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .file-item .remove-file {
            background: none;
            border: none;
            color: #dc3545;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 5px;
        }

        .upload-section {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #fdfdfd;
        }

        .upload-section h6 {
            color: #495057;
            margin-bottom: 15px;
        }

        .upload-info {
            background: #e8f4fd;
            border: 1px solid #bee5eb;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .upload-info h6 {
            color: #0c5460;
            margin-bottom: 10px;
        }

        .upload-info ul {
            margin: 0;
            padding-left: 20px;
        }

        .upload-info ul li {
            color: #0c5460;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .user-info {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            border-left: 4px solid #6777ef;
            margin-bottom: 10px;
        }

        .user-info .user-name {
            font-weight: 600;
            color: #2c3e50;
        }

        .user-info .user-role {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .user-info .user-wilayah {
            font-size: 0.85rem;
            color: #495057;
        }
    </style>
@endpush

@section('Dashboard')
    @include('admin.dashboard.header')
    @include('admin.dashboard.sidebar')

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Tambah Spesimen TTD & Stempel</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('admin.masterdata.Spesimen.index') }}">Spesimen TTD & Stempel</a>
                    </div>
                    <div class="breadcrumb-item">Tambah Spesimen</div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Form Tambah Spesimen TTD & Stempel</h4>
                        </div>
                        <div class="card-body">
                            <!-- Upload Info -->
                            <div class="upload-info">
                                <h6><i class="fas fa-info-circle me-2"></i>Informasi Upload File</h6>
                                <ul>
                                    <li><strong>TTD:</strong> Format JPG, PNG, GIF, SVG - Maksimal 5MB per file</li>
                                    <li><strong>Stempel:</strong> Format JPG, PNG, GIF, SVG - Maksimal 5MB per file</li>
                                    <li>Minimal upload 1 file (TTD atau Stempel)</li>
                                    <li><strong>Background Removal:</strong> Background gambar akan dihapus otomatis menggunakan AI</li>
                                    <li>File hasil akan disimpan dalam format PNG dengan background transparan</li>
                                </ul>
                            </div>

                            <form action="{{ route('admin.masterdata.Spesimen.store') }}" method="POST" id="createForm" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Pilih Pejabat <span class="text-danger">*</span></label>
                                            <select class="form-control @error('user_id') is-invalid @enderror" name="user_id" id="user_id">
                                                <option value="">Pilih Pejabat</option>
                                                @foreach($pejabatOptions as $pejabat)
                                                    <option value="{{ $pejabat->id }}"
                                                            data-role="{{ $pejabat->role }}"
                                                            data-rt="{{ $pejabat->rt }}"
                                                            data-rw="{{ $pejabat->rw }}"
                                                            {{ old('user_id') == $pejabat->id ? 'selected' : '' }}>
                                                        {{ $pejabat->name }} - {{ $pejabat->role }}
                                                        @if ($pejabat->role === 'Ketua RT')
                                                            (RT {{ $pejabat->rt }} RW {{ $pejabat->rw }})
                                                        @elseif ($pejabat->role === 'Ketua RW')
                                                            (RW {{ $pejabat->rw }})

                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('user_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div id="user-info-display" style="display: none;">
                                            <div class="user-info">
                                                <div class="user-name" id="display-name"></div>
                                                <div class="user-role" id="display-role"></div>
                                                <div class="user-wilayah" id="display-wilayah"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Nama Pejabat <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   class="form-control @error('nama_pejabat') is-invalid @enderror"
                                                   name="nama_pejabat"
                                                   id="nama_pejabat"
                                                   value="{{ old('nama_pejabat') }}"
                                                   placeholder="Contoh: Budi Santoso, S.Sos">
                                            @error('nama_pejabat')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Jabatan <span class="text-danger">*</span></label>
                                            <select class="form-control @error('jabatan') is-invalid @enderror" name="jabatan" id="jabatan">
                                                <option value="">Pilih Jabatan</option>
                                                @foreach($jabatanOptions as $key => $jabatan)
                                                    <option value="{{ $key }}" {{ old('jabatan') == $key ? 'selected' : '' }}>
                                                        {{ $jabatan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('jabatan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row" id="wilayah-fields">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nomor RW <span id="rw-required" class="text-danger" style="display: none;">*</span></label>
                                                    <select class="form-control @error('rw') is-invalid @enderror" name="rw" id="rw">
                                                        <option value="">Pilih RW</option>
                                                        @foreach($rwOptions as $rw)
                                                            <option value="{{ $rw }}" {{ old('rw') == $rw ? 'selected' : '' }}>
                                                                RW {{ $rw }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('rw')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="rt-field">
                                                <div class="form-group">
                                                    <label>Nomor RT <span id="rt-required" class="text-danger" style="display: none;">*</span></label>
                                                    <select class="form-control @error('rt') is-invalid @enderror" name="rt" id="rt">
                                                        <option value="">Pilih RT</option>
                                                        @foreach($rtOptions as $rt)
                                                            <option value="{{ $rt }}" {{ old('rt') == $rt ? 'selected' : '' }}>
                                                                RT {{ $rt }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('rt')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Keterangan</label>
                                            <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                                      name="keterangan"
                                                      rows="3"
                                                      placeholder="Keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                                            @error('keterangan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Status <span class="text-danger">*</span></label>
                                                    <select class="form-control @error('status') is-invalid @enderror" name="status">
                                                        <option value="Aktif" {{ old('status', 'Aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                                        <option value="Tidak Aktif" {{ old('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                                    </select>
                                                    @error('status')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Urutan Tampil</label>
                                                    <input type="number"
                                                           class="form-control @error('urutan_tampil') is-invalid @enderror"
                                                           name="urutan_tampil"
                                                           value="{{ old('urutan_tampil') }}"
                                                           min="0"
                                                           placeholder="Urutan tampil">
                                                    @error('urutan_tampil')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="form-text text-muted">Kosongkan untuk otomatis urutan terakhir</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <div class="custom-control custom-switch mt-4">
                                                        <input type="checkbox"
                                                               class="custom-control-input"
                                                               id="is_active"
                                                               name="is_active"
                                                               value="1"
                                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="is_active">Status Aktif</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Upload TTD -->
                                        <div class="upload-section">
                                            <h6><i class="fas fa-signature text-primary"></i> Upload Tanda Tangan (TTD)</h6>
                                            <div class="file-upload-wrapper" id="ttdUploadWrapper">
                                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                                <h6 class="text-muted">Drag & Drop file TTD atau klik untuk pilih</h6>
                                                <p class="text-muted small">Format: JPG, PNG, GIF, SVG | Maksimal 5MB</p>
                                                <input type="file"
                                                       class="form-control-file d-none @error('file_ttd') is-invalid @enderror"
                                                       name="file_ttd"
                                                       id="file_ttd"
                                                       accept="image/*">
                                            </div>
                                            <div class="file-preview" id="ttdPreview"></div>
                                            @error('file_ttd')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Upload Stempel -->
                                        <div class="upload-section">
                                            <h6><i class="fas fa-stamp text-success"></i> Upload Stempel</h6>
                                            <div class="file-upload-wrapper" id="stempelUploadWrapper">
                                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                                <h6 class="text-muted">Drag & Drop file Stempel atau klik untuk pilih</h6>
                                                <p class="text-muted small">Format: JPG, PNG, GIF, SVG | Maksimal 5MB</p>
                                                <input type="file"
                                                       class="form-control-file d-none @error('file_stempel') is-invalid @enderror"
                                                       name="file_stempel"
                                                       id="file_stempel"
                                                       accept="image/*">
                                            </div>
                                            <div class="file-preview" id="stempelPreview"></div>
                                            @error('file_stempel')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer text-right border-0 pt-3">
                                    <button type="submit" class="btn btn-primary" id="saveBtn">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                    <a href="{{ route('admin.masterdata.Spesimen.index') }}" class="btn btn-secondary">
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

    <script>
        $(document).ready(function() {
            let selectedFileTtd = null;
            let selectedFileStempel = null;

            // Handle user selection change
            $('#user_id').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const userId = $(this).val();
                console.log(userId)

                if (userId) {
                    const role = selectedOption.data('role');
                    const rt = selectedOption.data('rt');
                    const rw = selectedOption.data('rw');
                    const name = selectedOption.text().split(' - ')[0];

                    // Show user info
                    $('#display-name').text(name);
                    $('#display-role').text(role);

                    let wilayah = '';
                    if (role === 'Ketua RT') {
                        wilayah = `RT ${rt} RW ${rw}`;
                    } else if (role === 'Ketua RW') {
                        wilayah = `RW ${rw}`;
                    } else {
                        wilayah = 'Kelurahan'
                    }
                    $('#display-wilayah').text(wilayah);
                    $('#user-info-display').show();

                    // Auto fill form
                    $('#jabatan').val(role);
                    $('#rw').val(rw);

                    if (role === 'Ketua RT') {
                        $('#rt').val(rt);
                        $('#rt-required').show();
                        $('#rw-required').show();
                    } else if (role === 'Ketua RW') {
                        // PERBAIKAN: Jika Ketua RW punya data RT, isi juga
                        console.log(`Rt = ${rt}`);
                        $('#rt').val(rt);
                        if (rt && rt !== '' && rt !== null) {
                            $('#rt').val(rt);
                        } else {
                            $('#rt').val(''); // Kosongkan jika tidak ada
                        }
                        $('#rw-required').show();
                        $('#rt-required').hide(); // RT tidak wajib untuk Ketua RW
                    } else {
                        $('#rt-required').hide(); // RT tidak wajib untuk selain Ketua RT/RW
                        $('#rw-required').hide(); // RW tidak wajib untuk selain Ketua RT/RW
                    }

                    // AUTO FILL NAMA PEJABAT - PERBAIKAN INI
                    $('#nama_pejabat').val(name);

                    // Auto fill nama pejabat
                    // if (!$('#nama_pejabat').val()) {
                    //     $('#nama_pejabat').val(name);
                    // }

                    // Trigger jabatan change
                    $('#jabatan').trigger('change');
                } else {
                    $('#user-info-display').hide();
                    // Reset form
                    $('#jabatan').val('');
                    $('#rw').val('');
                    $('#rt').val('');
                    $('#nama_pejabat').val('');
                    $('#rt-required').hide();
                }
            });

            // Handle jabatan change
            $('#jabatan').on('change', function() {
                const jabatan = $(this).val();

                if (jabatan === 'Ketua RT') {
                    $('#rt-required').show();
                    if (!$('#rt').val()) {
                        $('#rt').focus();
                    }
                } else {
                    $('#rt-required').hide();
                    $('#rt').val('');
                }
            });

            // TTD UPLOAD HANDLERS
            $('#ttdUploadWrapper').on('click', function(e) {
                // e.preventDefault();
                // e.stopPropagation();
                $('#file_ttd')[0].click();
            });

            $('#file_ttd').on('change', function(e) {
                handleFileSelect(this.files[0], 'ttd');
            });

            // Drag and drop for TTD
            $('#ttdUploadWrapper').on('dragover dragenter', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).addClass('dragover');
            });

            $('#ttdUploadWrapper').on('dragleave dragend', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('dragover');
            });

            $('#ttdUploadWrapper').on('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('dragover');

                const files = e.originalEvent.dataTransfer.files;
                if (files.length > 0) {
                    handleFileSelect(files[0], 'ttd');
                }
            });

            // STEMPEL UPLOAD HANDLERS
            $('#stempelUploadWrapper').on('click', function(e) {
                // e.preventDefault();
                // e.stopPropagation();
                $('#file_stempel')[0].click();
            });

            $('#file_stempel').on('change', function(e) {
                handleFileSelect(this.files[0], 'stempel');
            });

            // Drag and drop for Stempel
            $('#stempelUploadWrapper').on('dragover dragenter', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).addClass('dragover');
            });

            $('#stempelUploadWrapper').on('dragleave dragend', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('dragover');
            });

            $('#stempelUploadWrapper').on('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('dragover');

                const files = e.originalEvent.dataTransfer.files;
                if (files.length > 0) {
                    handleFileSelect(files[0], 'stempel');
                }
            });

            function handleFileSelect(file, type) {
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml'];
                const maxSize = 5242880; // 5MB

                if (!file) return;

                // Validate file type
                if (!allowedTypes.includes(file.type)) {
                    Swal.fire('Error', `File ${file.name} bukan format gambar yang valid.`, 'error');
                    return;
                }

                // Validate file size
                if (file.size > maxSize) {
                    Swal.fire('Error', `File ${file.name} terlalu besar. Maksimal 5MB.`, 'error');
                    return;
                }

                if (type === 'ttd') {
                    selectedFileTtd = file;
                    updateFilePreview(file, 'ttd');
                } else {
                    selectedFileStempel = file;
                    updateFilePreview(file, 'stempel');
                }
            }

            function updateFilePreview(file, type) {
                const previewContainer = type === 'ttd' ? '#ttdPreview' : '#stempelPreview';
                const iconClass = type === 'ttd' ? 'fas fa-signature text-primary' : 'fas fa-stamp text-success';

                $(previewContainer).empty();

                if (file) {
                    $(previewContainer).show();

                    const fileItem = $(`
                        <div class="file-item">
                            <i class="${iconClass} file-icon"></i>
                            <div class="file-details">
                                <div class="file-name">${file.name}</div>
                                <div class="file-size">${formatFileSize(file.size)}</div>
                            </div>
                            <button type="button" class="remove-file" data-type="${type}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `);

                    $(previewContainer).append(fileItem);

                    // Add image preview
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = $('<img>')
                                .attr('src', e.target.result)
                                .css({
                                    'max-width': '60px',
                                    'max-height': '60px',
                                    'object-fit': 'cover',
                                    'border-radius': '4px',
                                    'margin-right': '10px'
                                });
                            fileItem.find('.file-icon').replaceWith(img);
                        };
                        reader.readAsDataURL(file);
                    }
                } else {
                    $(previewContainer).hide();
                }
            }

            // Remove file handler
            $(document).on('click', '.remove-file', function() {
                const type = $(this).data('type');

                if (type === 'ttd') {
                    selectedFileTtd = null;
                    $('#file_ttd').val('');
                    $('#ttdPreview').hide();
                } else {
                    selectedFileStempel = null;
                    $('#file_stempel').val('');
                    $('#stempelPreview').hide();
                }
            });

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 B';
                const k = 1024;
                const sizes = ['B', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
            }

            // Form submission
            $('#createForm').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                const btn = $('#saveBtn');

                // Check if at least one file is selected
                if (!selectedFileTtd && !selectedFileStempel) {
                    Swal.fire('Error', 'Silakan pilih minimal satu file TTD atau Stempel.', 'error');
                    return;
                }

                // Show processing message
                Swal.fire({
                    title: 'Sedang Memproses...',
                    html: 'Mengunggah file dan menghapus background...<br><small>Proses ini mungkin membutuhkan waktu beberapa detik</small>',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Reset form state
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                btn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

                // Create FormData for file upload
                const formData = new FormData(this);

                $.ajax({
                    url: form.attr('action'),
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
                                timer: 1500
                            }).then(() => {
                                window.location.href = "{{ route('admin.masterdata.Spesimen.index') }}";
                            });
                        }
                    },
                    error: function(xhr) {
                        btn.html('<i class="fas fa-save"></i> Simpan').prop('disabled', false);

                        if(xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            Object.keys(errors).forEach(key => {
                                $(`[name="${key}"]`)
                                    .addClass('is-invalid')
                                    .after(`<div class="invalid-feedback">${errors[key][0]}</div>`);
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

            // Auto-save draft functionality (optional)
            let autoSaveTimer;
            function autoSaveDraft() {
                clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(() => {
                    const formData = {
                        user_id: $('[name="user_id"]').val(),
                        nama_pejabat: $('[name="nama_pejabat"]').val(),
                        jabatan: $('[name="jabatan"]').val(),
                        rw: $('[name="rw"]').val(),
                        rt: $('[name="rt"]').val(),
                        keterangan: $('[name="keterangan"]').val(),
                    };

                    if (formData.user_id && formData.nama_pejabat) {
                        localStorage.setItem('spesimen_draft', JSON.stringify(formData));
                        console.log('📝 Draft auto-saved');
                    }
                }, 5000); // Auto-save after 5 seconds of inactivity
            }

            // Load draft on page load
            function loadDraft() {
                const draft = localStorage.getItem('spesimen_draft');
                if (draft) {
                    const data = JSON.parse(draft);

                    Swal.fire({
                        title: 'Draft Ditemukan',
                        text: 'Ditemukan draft yang belum disimpan. Apakah Anda ingin memuat draft tersebut?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Muat Draft',
                        cancelButtonText: 'Tidak, Mulai Baru'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Object.keys(data).forEach(key => {
                                $(`[name="${key}"]`).val(data[key]);
                            });
                            $('#user_id').trigger('change');

                            Swal.fire('Draft Dimuat', 'Draft berhasil dimuat ke form.', 'success');
                        } else {
                            localStorage.removeItem('spesimen_draft');
                        }
                    });
                }
            }

            // Bind auto-save events
            $('input, textarea, select').on('input change', autoSaveDraft);

            // Load draft on page load
            setTimeout(loadDraft, 1000);

            // Clear draft on successful submit
            $('#createForm').on('submit', function() {
                localStorage.removeItem('spesimen_draft');
            });

            // Keyboard shortcuts
            $(document).on('keydown', function(e) {
                // Ctrl+S to save
                if ((e.ctrlKey || e.metaKey) && e.which === 83) {
                    e.preventDefault();
                    $('#createForm').submit();
                }
            });

            // Trigger user change on page load if value exists
            if ($('#user_id').val()) {
                $('#user_id').trigger('change');
            }

            console.log('✅ Spesimen Create form initialized successfully');
        });
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
