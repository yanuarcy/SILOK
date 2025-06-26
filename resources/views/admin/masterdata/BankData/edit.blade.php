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

        .current-files {
            margin-bottom: 20px;
        }

        .current-file-item {
            display: flex;
            align-items: center;
            padding: 10px;
            background-color: #e8f5e8;
            border-radius: 6px;
            margin-bottom: 8px;
            border-left: 4px solid #28a745;
        }

        .current-file-item.video {
            background-color: #ffe6e6;
            border-left-color: #dc3545;
        }

        .current-file-item .file-preview {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 15px;
        }

        .current-file-item .file-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border-radius: 4px;
            margin-right: 15px;
            font-size: 1.5rem;
        }

        .current-file-item .file-details {
            flex: 1;
        }

        .current-file-item .file-name {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 2px;
        }

        .current-file-item .file-info {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .current-file-item .remove-current-file {
            background: none;
            border: none;
            color: #dc3545;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 5px;
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

        .section-divider {
            border-top: 2px solid #e9ecef;
            margin: 30px 0;
            padding-top: 20px;
        }
    </style>
@endpush

@section('Dashboard')
    @include('admin.dashboard.header')
    @include('admin.dashboard.sidebar')

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Bank Data</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('admin.masterdata.BankData.index') }}">Data Bank Data</a>
                    </div>
                    <div class="breadcrumb-item">Edit Bank Data</div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Form Edit Bank Data</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.masterdata.BankData.update', $bankData->id) }}" method="POST" id="editForm" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Jenis Bank Data <span class="text-danger">*</span></label>
                                            <select class="form-control @error('jenis_bank_data') is-invalid @enderror" name="jenis_bank_data" id="jenis_bank_data">
                                                <option value="">Pilih Jenis Bank Data</option>
                                                @foreach($jenisOptions as $key => $jenis)
                                                    @if(in_array($key, $allowedJenis))
                                                        <option value="{{ $key }}" {{ old('jenis_bank_data', $bankData->jenis_bank_data) == $key ? 'selected' : '' }}>
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
                                                            <option value="{{ $rw }}" {{ old('nomor_rw', $bankData->nomor_rw) == $rw ? 'selected' : '' }}>
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
                                                            <option value="{{ $rt }}" {{ old('nomor_rt', $bankData->nomor_rt) == $rt ? 'selected' : '' }}>
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
                                                   value="{{ old('judul_kegiatan', $bankData->judul_kegiatan) }}"
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
                                                      placeholder="Jelaskan detail kegiatan yang dilakukan...">{{ old('deskripsi', $bankData->deskripsi) }}</textarea>
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
                                                           value="{{ old('tanggal_kegiatan', $bankData->tanggal_kegiatan->format('Y-m-d')) }}">
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
                                                           value="{{ old('lokasi', $bankData->lokasi) }}"
                                                           placeholder="Contoh: Balai RW 05">
                                                    @error('lokasi')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Current Files -->
                                        @if(($bankData->files_foto && count($bankData->files_foto) > 0) || ($bankData->files_video && count($bankData->files_video) > 0))
                                            <div class="current-files">
                                                <h6><i class="fas fa-folder-open text-info"></i> File Saat Ini</h6>

                                                @if($bankData->files_foto && count($bankData->files_foto) > 0)
                                                    <div class="mb-3">
                                                        <small class="text-muted">Foto ({{ count($bankData->files_foto) }})</small>
                                                        @foreach($bankData->files_foto as $index => $foto)
                                                            <div class="current-file-item" data-type="foto" data-index="{{ $index }}">
                                                                <img src="{{ Storage::url($foto) }}" class="file-preview" alt="Foto {{ $index + 1 }}">
                                                                <div class="file-details">
                                                                    <div class="file-name">Foto {{ $index + 1 }}</div>
                                                                    <div class="file-info">{{ basename($foto) }}</div>
                                                                </div>
                                                                <button type="button" class="remove-current-file" data-type="foto" data-index="{{ $index }}">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                @if($bankData->files_video && count($bankData->files_video) > 0)
                                                    <div class="mb-3">
                                                        <small class="text-muted">Video ({{ count($bankData->files_video) }})</small>
                                                        @foreach($bankData->files_video as $index => $video)
                                                            <div class="current-file-item video" data-type="video" data-index="{{ $index }}">
                                                                <div class="file-icon">
                                                                    <i class="fas fa-video text-danger"></i>
                                                                </div>
                                                                <div class="file-details">
                                                                    <div class="file-name">Video {{ $index + 1 }}</div>
                                                                    <div class="file-info">{{ basename($video) }}</div>
                                                                </div>
                                                                <button type="button" class="remove-current-file" data-type="video" data-index="{{ $index }}">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="section-divider">
                                                <h6><i class="fas fa-plus text-success"></i> Tambah File Baru</h6>
                                            </div>
                                        @endif

                                        <!-- Upload Foto -->
                                        <div class="upload-section">
                                            <h6><i class="fas fa-images text-primary"></i> Upload Foto Tambahan</h6>
                                            <div class="file-upload-wrapper" id="fotoUploadWrapper">
                                                <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                                <p class="text-muted mb-2">Drag & Drop foto atau klik untuk pilih</p>
                                                <small class="text-muted">Format: JPG, PNG, GIF | Maksimal 10MB per file</small>
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
                                            <h6><i class="fas fa-video text-danger"></i> Upload Video Tambahan</h6>
                                            <div class="file-upload-wrapper" id="videoUploadWrapper">
                                                <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                                <p class="text-muted mb-2">Drag & Drop video atau klik untuk pilih</p>
                                                <small class="text-muted">Format: MP4, AVI, MOV, WMV | Maksimal 100MB per file</small>
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
                                                <option value="Published" {{ old('status', $bankData->status) == 'Published' ? 'selected' : '' }}>Published</option>
                                                <option value="Draft" {{ old('status', $bankData->status) == 'Draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="Archived" {{ old('status', $bankData->status) == 'Archived' ? 'selected' : '' }}>Archived</option>
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
                                                   value="{{ old('urutan_tampil', $bankData->urutan_tampil) }}"
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
                                                       {{ old('is_active', $bankData->is_active) ? 'checked' : '' }}>
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
                                           value="{{ old('tags', $bankData->tags ? implode(',', $bankData->tags) : '') }}"
                                           data-role="tagsinput"
                                           placeholder="Ketik tag dan tekan Enter">
                                    @error('tags')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Contoh: gotong-royong, kebersihan, lingkungan</small>
                                </div>

                                <!-- Hidden input for removed files -->
                                <input type="hidden" name="removed_files" id="removedFiles" value="">

                                <div class="card-footer text-right border-0 pt-3">
                                    <button type="submit" class="btn btn-primary" id="saveBtn">
                                        <i class="fas fa-save"></i> Update
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
            let removedFiles = [];

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

            // Remove current file handler
            $('.remove-current-file').on('click', function() {
                const type = $(this).data('type');
                const index = $(this).data('index');

                // Add to removed files list
                removedFiles.push({
                    type: type,
                    index: index
                });

                // Update hidden input
                $('#removedFiles').val(JSON.stringify(removedFiles));

                // Remove the file item visually
                $(this).closest('.current-file-item').fadeOut(300, function() {
                    $(this).remove();
                });
            });

            // FOTO UPLOAD HANDLERS
            $('#fotoUploadWrapper').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('#files_foto')[0].click();
            });

            $('#files_foto').on('change', function(e) {
                handleFileSelect(this.files, 'foto');
            });

            // Drag and drop for foto
            $('#fotoUploadWrapper').on('dragover dragenter', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).addClass('dragover');
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

                const files = e.originalEvent.dataTransfer.files;
                if (files.length > 0) {
                    handleFileSelect(files, 'foto');
                }
            });

            // VIDEO UPLOAD HANDLERS
            $('#videoUploadWrapper').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
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

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];

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
            }

            // Remove new file handler
            $(document).on('click', '.remove-file', function() {
                const type = $(this).data('type');
                const index = $(this).data('index');

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
            $('#editForm').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                const btn = $('#saveBtn');

                // Reset form state
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                btn.html('<i class="fas fa-spinner fa-spin"></i> Updating...').prop('disabled', true);

                // Create FormData for file upload
                const formData = new FormData(this);

                // Manually append tags if using tagsinput
                const tags = $('.inputtags').val();
                if (tags) {
                    formData.set('tags', tags);
                }

                // Append removed files data
                formData.set('removed_files', $('#removedFiles').val());

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
                                window.location.href = "{{ route('admin.masterdata.BankData.index') }}";
                            });
                        }
                    },
                    error: function(xhr) {
                        btn.html('<i class="fas fa-save"></i> Update').prop('disabled', false);

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
                                xhr.responseJSON?.message || 'Terjadi kesalahan saat mengupdate data.',
                                'error'
                            );
                        }
                    }
                });
            });

            // Preview current images on hover
            $('.current-file-item img').on('mouseenter', function() {
                $(this).css('transform', 'scale(1.1)');
            }).on('mouseleave', function() {
                $(this).css('transform', 'scale(1)');
            });

            // Add confirmation for removing current files
            $('.remove-current-file').on('click', function(e) {
                e.preventDefault();
                const button = $(this);
                const fileName = button.closest('.current-file-item').find('.file-name').text();

                Swal.fire({
                    title: 'Hapus File?',
                    text: `Apakah Anda yakin ingin menghapus ${fileName}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const type = button.data('type');
                        const index = button.data('index');

                        // Add to removed files list
                        removedFiles.push({
                            type: type,
                            index: index
                        });

                        // Update hidden input
                        $('#removedFiles').val(JSON.stringify(removedFiles));

                        // Remove the file item visually
                        button.closest('.current-file-item').fadeOut(300, function() {
                            $(this).remove();
                        });

                        Swal.fire(
                            'Terhapus!',
                            'File berhasil dihapus.',
                            'success'
                        );
                    }
                });
            });
        });
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
