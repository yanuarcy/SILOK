@extends('Template.template')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">

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

        .file-item.video {
            border-left-color: #dc3545;
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

        .bootstrap-tagsinput {
            width: 100%;
            min-height: 38px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 6px;
            background-color: white;
        }

        .bootstrap-tagsinput .tag {
            background-color: #6777ef;
            color: white;
            border-radius: 12px;
            padding: 2px 8px;
            margin: 2px;
            font-size: 12px;
        }

        .bootstrap-tagsinput input {
            border: none;
            outline: none;
            background: transparent;
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
    </style>
@endpush

@section('Dashboard')
    @include('admin.dashboard.header')
    @include('admin.dashboard.sidebar')

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Tambah Bank Data</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('admin.masterdata.BankData.index') }}">Data Bank Data</a>
                    </div>
                    <div class="breadcrumb-item">Tambah Bank Data</div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Form Tambah Bank Data</h4>
                        </div>
                        <div class="card-body">
                            <!-- Upload Info -->
                            <div class="upload-info">
                                <h6><i class="fas fa-info-circle me-2"></i>Informasi Upload File</h6>
                                <ul>
                                    <li><strong>Foto:</strong> Format JPG, PNG, GIF - Maksimal 10MB per file</li>
                                    <li><strong>Video:</strong> Format MP4, AVI, MOV, WMV - Maksimal 100MB per file</li>
                                    <li>Minimal upload 1 file (foto atau video)</li>
                                    <li>Pastikan file tidak mengandung konten yang melanggar norma</li>
                                </ul>
                            </div>

                            <form action="{{ route('admin.masterdata.BankData.store') }}" method="POST" id="createForm" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Jenis Bank Data <span class="text-danger">*</span></label>
                                            <select class="form-control @error('jenis_bank_data') is-invalid @enderror" name="jenis_bank_data" id="jenis_bank_data">
                                                <option value="">Pilih Jenis Bank Data</option>
                                                @foreach($jenisOptions as $key => $jenis)
                                                    @if(in_array($key, $allowedJenis))
                                                        <option value="{{ $key }}" {{ old('jenis_bank_data') == $key ? 'selected' : '' }}>
                                                            {{ $jenis }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('jenis_bank_data')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row" id="wilayah-fields" style="display: none;">
                                            <div class="col-md-6" id="rw-field">
                                                <div class="form-group">
                                                    <label>Nomor RW <span class="text-danger">*</span></label>
                                                    <select class="form-control @error('nomor_rw') is-invalid @enderror" name="nomor_rw" id="nomor_rw">
                                                        <option value="">Pilih RW</option>
                                                        @foreach($rwOptions as $rw)
                                                            <option value="{{ $rw }}" {{ old('nomor_rw', auth()->user()->nomor_rw ?? '') == $rw ? 'selected' : '' }}>
                                                                RW {{ $rw }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('nomor_rw')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="rt-field">
                                                <div class="form-group">
                                                    <label>Nomor RT <span class="text-danger">*</span></label>
                                                    <select class="form-control @error('nomor_rt') is-invalid @enderror" name="nomor_rt" id="nomor_rt">
                                                        <option value="">Pilih RT</option>
                                                        @foreach($rtOptions as $rt)
                                                            <option value="{{ $rt }}" {{ old('nomor_rt', auth()->user()->nomor_rt ?? '') == $rt ? 'selected' : '' }}>
                                                                RT {{ $rt }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('nomor_rt')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Judul Kegiatan <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   class="form-control @error('judul_kegiatan') is-invalid @enderror"
                                                   name="judul_kegiatan"
                                                   value="{{ old('judul_kegiatan') }}"
                                                   placeholder="Contoh: Gotong Royong Membersihkan Lingkungan">
                                            @error('judul_kegiatan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Deskripsi Kegiatan <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                                                      name="deskripsi"
                                                      rows="4"
                                                      placeholder="Jelaskan detail kegiatan yang dilakukan...">{{ old('deskripsi') }}</textarea>
                                            @error('deskripsi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Tanggal Kegiatan <span class="text-danger">*</span></label>
                                                    <input type="date"
                                                           class="form-control @error('tanggal_kegiatan') is-invalid @enderror"
                                                           name="tanggal_kegiatan"
                                                           value="{{ old('tanggal_kegiatan') }}">
                                                    @error('tanggal_kegiatan')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Lokasi Kegiatan</label>
                                                    <input type="text"
                                                           class="form-control @error('lokasi') is-invalid @enderror"
                                                           name="lokasi"
                                                           value="{{ old('lokasi') }}"
                                                           placeholder="Contoh: Balai RW 05">
                                                    @error('lokasi')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Upload Foto -->
                                        <div class="upload-section">
                                            <h6><i class="fas fa-images text-primary"></i> Upload Foto Kegiatan</h6>
                                            <div class="file-upload-wrapper" id="fotoUploadWrapper">
                                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                                <h6 class="text-muted">Drag & Drop foto atau klik untuk pilih</h6>
                                                <p class="text-muted small">Format: JPG, PNG, GIF | Maksimal 10MB per file</p>
                                                <input type="file"
                                                       class="form-control-file d-none @error('files_foto.*') is-invalid @enderror"
                                                       name="files_foto[]"
                                                       id="files_foto"
                                                       accept="image/*"
                                                       multiple>
                                            </div>
                                            <div class="file-preview" id="fotoPreview"></div>
                                            @error('files_foto.*')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Upload Video -->
                                        <div class="upload-section">
                                            <h6><i class="fas fa-video text-danger"></i> Upload Video Kegiatan</h6>
                                            <div class="file-upload-wrapper" id="videoUploadWrapper">
                                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                                <h6 class="text-muted">Drag & Drop video atau klik untuk pilih</h6>
                                                <p class="text-muted small">Format: MP4, AVI, MOV, WMV | Maksimal 100MB per file</p>
                                                <input type="file"
                                                       class="form-control-file d-none @error('files_video.*') is-invalid @enderror"
                                                       name="files_video[]"
                                                       id="files_video"
                                                       accept="video/*"
                                                       multiple>
                                            </div>
                                            <div class="file-preview" id="videoPreview"></div>
                                            @error('files_video.*')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Status <span class="text-danger">*</span></label>
                                            <select class="form-control @error('status') is-invalid @enderror" name="status">
                                                <option value="Published" {{ old('status', 'Published') == 'Published' ? 'selected' : '' }}>Published</option>
                                                <option value="Draft" {{ old('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="Archived" {{ old('status') == 'Archived' ? 'selected' : '' }}>Archived</option>
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

                                <div class="form-group">
                                    <label>Tags/Kategori</label>
                                    <input type="text"
                                           class="form-control inputtags @error('tags') is-invalid @enderror"
                                           name="tags"
                                           value="{{ old('tags') }}"
                                           data-role="tagsinput"
                                           placeholder="Ketik tag dan tekan Enter">
                                    @error('tags')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Contoh: gotong-royong, kebersihan, lingkungan</small>
                                </div>

                                <div class="card-footer text-right border-0 pt-3">
                                    <button type="submit" class="btn btn-primary" id="saveBtn">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                    <a href="{{ route('admin.masterdata.BankData.index') }}" class="btn btn-secondary">
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
    <script src="{{ asset('library/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            let selectedFilesFoto = [];
            let selectedFilesVideo = [];

            // Initialize bootstrap tagsinput
            $('.inputtags').tagsinput({
                allowDuplicates: false,
                confirmKeys: [13, 44], // Enter and comma
                trimValue: true
            });

            // Prevent form submission when pressing Enter in tags input
            $('.bootstrap-tagsinput input').on('keydown', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                }
            });

            // Handle jenis bank data change
            $('#jenis_bank_data').on('change', function() {
                const jenis = $(this).val();
                const wilayahFields = $('#wilayah-fields');
                const rwField = $('#rw-field');
                const rtField = $('#rt-field');

                if (jenis === 'RW') {
                    wilayahFields.show();
                    rwField.show();
                    rtField.hide();
                    $('#nomor_rt').val('');
                } else if (jenis === 'RT') {
                    wilayahFields.show();
                    rwField.show();
                    rtField.show();
                } else {
                    wilayahFields.hide();
                    $('#nomor_rw').val('');
                    $('#nomor_rt').val('');
                }
            });

            // Trigger change on page load
            $('#jenis_bank_data').trigger('change');

            // FOTO UPLOAD HANDLERS
            $('#fotoUploadWrapper').on('click', function(e) {
                // e.preventDefault();
                // e.stopPropagation();
                console.log('📁 Upload area clicked - triggering file input');
                $('#files_foto')[0].click();
            });

            $('#files_foto').on('change', function(e) {
                console.log('📁 File input changed:', this.files.length);
                if (this.files.length > 0) {
                    handleFileSelect(this.files, 'foto');
                }
            });

            // Drag and drop for foto
            $('#fotoUploadWrapper').on('dragover dragenter', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).addClass('dragover');
                console.log('📁 Dragover detected');
            });

            $('#fotoUploadWrapper').on('dragleave dragend', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('dragover');
            });

            $('#fotoUploadWrapper').on('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('dragover');
                console.log('📁 File dropped');

                const files = e.originalEvent.dataTransfer.files;
                if (files.length > 0) {
                    handleFileSelect(files, 'foto');
                }
            });

            // VIDEO UPLOAD HANDLERS
            $('#videoUploadWrapper').on('click', function(e) {
                // e.preventDefault();
                // e.stopPropagation();
                $('#files_video')[0].click();
            });

            $('#files_video').on('change', function(e) {
                handleFileSelect(this.files, 'video');
            });

            // Drag and drop for video
            $('#videoUploadWrapper').on('dragover dragenter', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).addClass('dragover');
            });

            $('#videoUploadWrapper').on('dragleave dragend', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('dragover');
            });

            $('#videoUploadWrapper').on('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('dragover');

                const files = e.originalEvent.dataTransfer.files;
                if (files.length > 0) {
                    handleFileSelect(files, 'video');
                }
            });

            function handleFileSelect(files, type) {
                const allowedTypes = type === 'foto'
                    ? ['image/jpeg', 'image/png', 'image/jpg', 'image/gif']
                    : ['video/mp4', 'video/avi', 'video/mov', 'video/wmv'];

                const maxSize = type === 'foto' ? 10485760 : 104857600; // 10MB for foto, 100MB for video
                const selectedFiles = type === 'foto' ? selectedFilesFoto : selectedFilesVideo;

                console.log(`📁 Processing ${files.length} ${type} files`);

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];

                    console.log(`📁 Processing file: ${file.name}, type: ${file.type}, size: ${file.size}`);

                    // Validate file type
                    if (!allowedTypes.includes(file.type)) {
                        Swal.fire('Error', `File ${file.name} bukan format ${type} yang valid.`, 'error');
                        continue;
                    }

                    // Validate file size
                    if (file.size > maxSize) {
                        const maxSizeMB = type === 'foto' ? '10MB' : '100MB';
                        Swal.fire('Error', `File ${file.name} terlalu besar. Maksimal ${maxSizeMB}.`, 'error');
                        continue;
                    }

                    selectedFiles.push(file);
                    console.log(`✅ File ${file.name} added successfully`);
                }

                updateFilePreview(type);
                updateFileInput(type);
            }

            function updateFilePreview(type) {
                const selectedFiles = type === 'foto' ? selectedFilesFoto : selectedFilesVideo;
                const previewContainer = type === 'foto' ? '#fotoPreview' : '#videoPreview';
                const iconClass = type === 'foto' ? 'fas fa-image text-success' : 'fas fa-video text-danger';

                $(previewContainer).empty();

                if (selectedFiles.length > 0) {
                    $(previewContainer).show();

                    selectedFiles.forEach((file, index) => {
                        const fileItem = $(`
                            <div class="file-item ${type}">
                                <i class="${iconClass} file-icon"></i>
                                <div class="file-details">
                                    <div class="file-name">${file.name}</div>
                                    <div class="file-size">${formatFileSize(file.size)}</div>
                                </div>
                                <button type="button" class="remove-file" data-type="${type}" data-index="${index}">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `);
                        $(previewContainer).append(fileItem);
                    });

                    console.log(`📁 Preview updated for ${type}: ${selectedFiles.length} files`);
                } else {
                    $(previewContainer).hide();
                }
            }

            function updateFileInput(type) {
                const selectedFiles = type === 'foto' ? selectedFilesFoto : selectedFilesVideo;
                const inputId = type === 'foto' ? '#files_foto' : '#files_video';

                // Create new FileList
                const dt = new DataTransfer();
                selectedFiles.forEach(file => dt.items.add(file));
                $(inputId)[0].files = dt.files;

                console.log(`📁 File input updated for ${type}: ${selectedFiles.length} files`);
            }

            // Remove file handler
            $(document).on('click', '.remove-file', function() {
                const type = $(this).data('type');
                const index = $(this).data('index');

                console.log(`🗑️ Removing ${type} file at index ${index}`);

                if (type === 'foto') {
                    selectedFilesFoto.splice(index, 1);
                } else {
                    selectedFilesVideo.splice(index, 1);
                }

                updateFilePreview(type);
                updateFileInput(type);
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
                if (selectedFilesFoto.length === 0 && selectedFilesVideo.length === 0) {
                    Swal.fire('Error', 'Silakan pilih minimal satu file foto atau video.', 'error');
                    return;
                }

                // Reset form state
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                btn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

                // Create FormData for file upload
                const formData = new FormData(this);

                // Manually append tags if using tagsinput
                const tags = $('.inputtags').val();
                if (tags) {
                    formData.set('tags', tags);
                }

                console.log('📝 Submitting form with data:', {
                    foto_files: selectedFilesFoto.length,
                    video_files: selectedFilesVideo.length,
                    tags: tags
                });

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log('✅ Success response:', response);
                        if(response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.href = "{{ route('admin.masterdata.BankData.index') }}";
                            });
                        }
                    },
                    error: function(xhr) {
                        console.log('❌ Error response:', xhr);
                        btn.html('<i class="fas fa-save"></i> Simpan').prop('disabled', false);

                        if(xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            Object.keys(errors).forEach(key => {
                                if (key === 'tags') {
                                    $('.bootstrap-tagsinput').addClass('is-invalid');
                                    $('.bootstrap-tagsinput').after(`<div class="invalid-feedback d-block">${errors[key][0]}</div>`);
                                } else {
                                    $(`[name="${key}"]`)
                                        .addClass('is-invalid')
                                        .after(`<div class="invalid-feedback">${errors[key][0]}</div>`);
                                }
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

            // File validation on select
            function validateFiles(files, type) {
                const allowedTypes = type === 'foto'
                    ? ['image/jpeg', 'image/png', 'image/jpg', 'image/gif']
                    : ['video/mp4', 'video/avi', 'video/mov', 'video/wmv'];

                const maxSize = type === 'foto' ? 10485760 : 104857600;
                const errors = [];

                for (let file of files) {
                    if (!allowedTypes.includes(file.type)) {
                        errors.push(`${file.name}: Format file tidak didukung`);
                    }
                    if (file.size > maxSize) {
                        const maxSizeMB = type === 'foto' ? '10MB' : '100MB';
                        errors.push(`${file.name}: Ukuran file melebihi ${maxSizeMB}`);
                    }
                }

                return errors;
            }

            // Show file validation errors
            function showValidationErrors(errors) {
                if (errors.length > 0) {
                    let errorMessage = 'Terdapat kesalahan pada file berikut:\n\n';
                    errors.forEach(error => {
                        errorMessage += `• ${error}\n`;
                    });

                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi File Gagal',
                        text: errorMessage,
                        confirmButtonText: 'OK'
                    });
                    return true;
                }
                return false;
            }

            // Preview images before upload
            function previewImage(file, container) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = $('<img>')
                        .attr('src', e.target.result)
                        .css({
                            'max-width': '100px',
                            'max-height': '100px',
                            'object-fit': 'cover',
                            'border-radius': '4px',
                            'margin-right': '10px'
                        });

                    container.find('.file-icon').replaceWith(img);
                };
                reader.readAsDataURL(file);
            }

            // Enhanced file preview with thumbnails
            function updateFilePreviewWithThumbnails(type) {
                const selectedFiles = type === 'foto' ? selectedFilesFoto : selectedFilesVideo;
                const previewContainer = type === 'foto' ? '#fotoPreview' : '#videoPreview';
                const iconClass = type === 'foto' ? 'fas fa-image text-success' : 'fas fa-video text-danger';

                $(previewContainer).empty();

                if (selectedFiles.length > 0) {
                    $(previewContainer).show();

                    selectedFiles.forEach((file, index) => {
                        const fileItem = $(`
                            <div class="file-item ${type}" data-index="${index}">
                                <i class="${iconClass} file-icon"></i>
                                <div class="file-details">
                                    <div class="file-name">${file.name}</div>
                                    <div class="file-size">${formatFileSize(file.size)}</div>
                                    <div class="file-type text-muted">${file.type}</div>
                                </div>
                                <button type="button" class="remove-file" data-type="${type}" data-index="${index}" title="Hapus file">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `);

                        $(previewContainer).append(fileItem);

                        // Add thumbnail for images
                        if (type === 'foto') {
                            previewImage(file, fileItem);
                        }
                    });

                    // Update file counter
                    const counter = selectedFiles.length;
                    const label = type === 'foto' ? 'Upload Foto Kegiatan' : 'Upload Video Kegiatan';
                    $(`#${type}UploadWrapper`).siblings('h6').html(`<i class="fas fa-${type === 'foto' ? 'images' : 'video'} text-${type === 'foto' ? 'primary' : 'danger'}"></i> ${label} (${counter} file${counter > 1 ? 's' : ''})`);
                } else {
                    $(previewContainer).hide();
                    // Reset label
                    const label = type === 'foto' ? 'Upload Foto Kegiatan' : 'Upload Video Kegiatan';
                    $(`#${type}UploadWrapper`).siblings('h6').html(`<i class="fas fa-${type === 'foto' ? 'images' : 'video'} text-${type === 'foto' ? 'primary' : 'danger'}"></i> ${label}`);
                }
            }

            // Replace the existing updateFilePreview function
            window.updateFilePreview = updateFilePreviewWithThumbnails;

            // Auto-save draft functionality (optional)
            let autoSaveTimer;
            function autoSaveDraft() {
                clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(() => {
                    const formData = {
                        judul_kegiatan: $('[name="judul_kegiatan"]').val(),
                        deskripsi: $('[name="deskripsi"]').val(),
                        jenis_bank_data: $('[name="jenis_bank_data"]').val(),
                        tanggal_kegiatan: $('[name="tanggal_kegiatan"]').val(),
                        lokasi: $('[name="lokasi"]').val(),
                    };

                    if (formData.judul_kegiatan && formData.deskripsi) {
                        localStorage.setItem('bank_data_draft', JSON.stringify(formData));
                        console.log('📝 Draft auto-saved');
                    }
                }, 5000); // Auto-save after 5 seconds of inactivity
            }

            // Load draft on page load
            function loadDraft() {
                const draft = localStorage.getItem('bank_data_draft');
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
                            $('#jenis_bank_data').trigger('change');

                            Swal.fire('Draft Dimuat', 'Draft berhasil dimuat ke form.', 'success');
                        } else {
                            localStorage.removeItem('bank_data_draft');
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
                localStorage.removeItem('bank_data_draft');
            });

            // Enhanced form validation
            function validateForm() {
                let isValid = true;
                const errors = [];

                // Validate required fields
                const requiredFields = {
                    'jenis_bank_data': 'Jenis Bank Data',
                    'judul_kegiatan': 'Judul Kegiatan',
                    'deskripsi': 'Deskripsi Kegiatan',
                    'tanggal_kegiatan': 'Tanggal Kegiatan',
                    'status': 'Status'
                };

                Object.keys(requiredFields).forEach(field => {
                    const value = $(`[name="${field}"]`).val();
                    if (!value || value.trim() === '') {
                        errors.push(`${requiredFields[field]} wajib diisi`);
                        isValid = false;
                    }
                });

                // Validate conditional fields
                const jenis = $('[name="jenis_bank_data"]').val();
                if (jenis === 'RW' && !$('[name="nomor_rw"]').val()) {
                    errors.push('Nomor RW wajib diisi untuk jenis RW');
                    isValid = false;
                }
                if (jenis === 'RT') {
                    if (!$('[name="nomor_rw"]').val()) {
                        errors.push('Nomor RW wajib diisi untuk jenis RT');
                        isValid = false;
                    }
                    if (!$('[name="nomor_rt"]').val()) {
                        errors.push('Nomor RT wajib diisi untuk jenis RT');
                        isValid = false;
                    }
                }

                // Validate files
                if (selectedFilesFoto.length === 0 && selectedFilesVideo.length === 0) {
                    errors.push('Minimal satu file foto atau video harus diupload');
                    isValid = false;
                }

                // Show validation errors
                if (!isValid) {
                    showValidationErrors(errors);
                }

                return isValid;
            }

            // Progress indicator for file uploads
            function showUploadProgress() {
                Swal.fire({
                    title: 'Mengupload File...',
                    html: '<div class="progress"><div class="progress-bar" role="progressbar" style="width: 0%"></div></div>',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        // Simulate progress (in real app, you'd get actual progress)
                        let progress = 0;
                        const interval = setInterval(() => {
                            progress += Math.random() * 15;
                            if (progress >= 100) {
                                progress = 100;
                                clearInterval(interval);
                            }
                            $('.progress-bar').css('width', progress + '%');
                        }, 200);
                    }
                });
            }

            // Keyboard shortcuts
            $(document).on('keydown', function(e) {
                // Ctrl+S to save
                if ((e.ctrlKey || e.metaKey) && e.which === 83) {
                    e.preventDefault();
                    if (validateForm()) {
                        $('#createForm').submit();
                    }
                }

                // Ctrl+D to save as draft
                if ((e.ctrlKey || e.metaKey) && e.which === 68) {
                    e.preventDefault();
                    $('[name="status"]').val('Draft');
                    if (validateForm()) {
                        $('#createForm').submit();
                    }
                }
            });

            // Show keyboard shortcuts hint
            setTimeout(() => {
                if (!localStorage.getItem('shortcuts_shown')) {
                    const toast = $(`
                        <div class="toast-container position-fixed bottom-0 end-0 p-3">
                            <div class="toast" role="alert">
                                <div class="toast-header">
                                    <i class="fas fa-keyboard me-2"></i>
                                    <strong class="me-auto">Shortcut Keys</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                                </div>
                                <div class="toast-body">
                                    <small>
                                        • Ctrl+S: Simpan<br>
                                        • Ctrl+D: Simpan sebagai Draft
                                    </small>
                                </div>
                            </div>
                        </div>
                    `);

                    $('body').append(toast);
                    $('.toast').toast('show');

                    localStorage.setItem('shortcuts_shown', 'true');
                }
            }, 3000);

            console.log('✅ Bank Data Create form initialized successfully');
        });
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
