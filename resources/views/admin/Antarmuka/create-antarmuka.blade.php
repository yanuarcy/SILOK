@extends('Template.template')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">

    <style>
        .form-group label {
            font-weight: 600;
            color: #34395e;
        }

        .form-control {
            border-radius: 6px;
            border: 1px solid #e4e6fc;
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
            border-color: #6777ef;
            box-shadow: 0 0 0 0.2rem rgba(103, 119, 239, 0.25);
        }

        .custom-control-label {
            font-weight: 500;
            color: #34395e;
        }

        .url-preview {
            background: #f8f9fa;
            padding: 0.75rem;
            border-radius: 6px;
            border: 1px solid #e9ecef;
            margin-top: 0.5rem;
            word-break: break-all;
        }

        .source-examples {
            background: #e7f3ff;
            padding: 1rem;
            border-radius: 6px;
            border-left: 4px solid #007bff;
            margin-top: 0.5rem;
        }

        .source-examples h6 {
            color: #0056b3;
            margin-bottom: 0.5rem;
        }

        .source-examples ul {
            margin: 0;
            padding-left: 1.5rem;
        }

        .source-examples li {
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
        }

        /* Upload Area Styling */
        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 6px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .upload-area:hover {
            border-color: #6777ef;
            background-color: #f8f9ff;
        }

        .upload-area.dragover {
            border-color: #6777ef;
            background-color: #f0f3ff;
        }

        .file-info {
            background: #f8f9fa;
            padding: 0.75rem;
            border-radius: 6px;
            border: 1px solid #e9ecef;
            margin-top: 0.5rem;
        }

        .video-preview {
            max-width: 100%;
            max-height: 200px;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        /* Volume Control Styling */
        .volume-control-group {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 6px;
            border: 1px solid #e9ecef;
            margin-bottom: 1rem;
        }

        .volume-slider {
            width: 100%;
            height: 6px;
            border-radius: 3px;
            background: #ddd;
            outline: none;
            opacity: 0.7;
            transition: opacity 0.2s;
        }

        .volume-slider:hover {
            opacity: 1;
        }

        .volume-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #6777ef;
            cursor: pointer;
        }

        .volume-slider::-moz-range-thumb {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #6777ef;
            cursor: pointer;
            border: none;
        }

        .volume-display {
            font-weight: 600;
            color: #6777ef;
            font-size: 1.1rem;
        }
    </style>
@endpush

@section('Dashboard')
    @include('admin.dashboard.header')
    @include('admin.dashboard.sidebar')

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Tambah Antarmuka</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('Antarmuka.index') }}">Data Antarmuka</a>
                    </div>
                    <div class="breadcrumb-item">Tambah Antarmuka</div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Form Tambah Video Antarmuka</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('Antarmuka.store') }}" method="POST" enctype="multipart/form-data" id="createForm">
                                @csrf

                                <div class="form-group">
                                    <label>Keterangan <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('keterangan') is-invalid @enderror"
                                           name="keterangan"
                                           value="{{ old('keterangan') }}"
                                           placeholder="Masukkan keterangan video">
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Nama Video <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('nama') is-invalid @enderror"
                                           name="nama"
                                           value="{{ old('nama') }}"
                                           placeholder="Masukkan nama video">
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Volume Control -->
                                <div class="form-group">
                                    <label>Volume Video <span class="text-muted">(0-100%)</span></label>
                                    <div class="volume-control-group">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-volume-down text-muted mr-3"></i>
                                            <input type="range"
                                                   class="volume-slider mr-3"
                                                   name="volume"
                                                   id="volume"
                                                   min="0"
                                                   max="100"
                                                   value="{{ old('volume', 50) }}">
                                            <span class="volume-display" id="volume-display">{{ old('volume', 50) }}%</span>
                                        </div>
                                        <small class="form-text text-muted mt-2">
                                            Atur volume video dari 0% (silent) hingga 100% (maksimal)
                                        </small>
                                    </div>
                                </div>

                                <!-- Sumber Video Selection -->
                                <div class="form-group">
                                    <label>Sumber Video <span class="text-danger">*</span></label>
                                    <select class="form-control @error('sumber_type') is-invalid @enderror" name="sumber_type" id="sumber_type">
                                        <option value="">Pilih Sumber Video</option>
                                        <option value="upload" {{ old('sumber_type') == 'upload' ? 'selected' : '' }}>Upload File</option>
                                        <option value="youtube" {{ old('sumber_type') == 'youtube' ? 'selected' : '' }}>YouTube</option>
                                        <option value="vimeo" {{ old('sumber_type') == 'vimeo' ? 'selected' : '' }}>Vimeo</option>
                                        <option value="url" {{ old('sumber_type') == 'url' ? 'selected' : '' }}>URL Eksternal</option>
                                    </select>
                                    @error('sumber_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Upload File Section -->
                                <div class="form-group" id="upload-section" style="display: none;">
                                    <label>Upload Video File <span class="text-danger">*</span></label>
                                    <div class="upload-area" id="upload-area">
                                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                        <h6>Klik atau drag & drop video file di sini</h6>
                                        <p class="text-muted">Format yang didukung: MP4, AVI, MOV, WMV (Max: 100MB)</p>
                                    </div>
                                    <input type="file"
                                           class="form-control @error('video_file') is-invalid @enderror"
                                           name="video_file"
                                           id="video_file"
                                           accept="video/*"
                                           style="display: none;">
                                    <div id="file-info" class="file-info" style="display: none;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-file-video text-primary"></i>
                                                <span id="file-name" class="ml-2"></span>
                                                <small class="text-muted ml-2" id="file-size"></small>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger" id="remove-file">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('video_file')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- URL Section -->
                                <div class="form-group" id="url-section" style="display: none;">
                                    <label id="url-label">URL Video <span class="text-danger">*</span></label>
                                    <input type="url"
                                           class="form-control @error('sumber') is-invalid @enderror"
                                           name="sumber"
                                           value="{{ old('sumber') }}"
                                           id="video_url"
                                           placeholder="https://www.youtube.com/watch?v=...">
                                    <small class="form-text text-muted" id="url-help">
                                        Masukkan URL video dari YouTube, Vimeo, atau sumber lainnya
                                    </small>
                                    @error('sumber')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <!-- URL Preview -->
                                    <div id="url-preview" class="url-preview" style="display: none;">
                                        <strong>Preview URL:</strong>
                                        <div id="preview-text" class="mt-1"></div>
                                        <div id="source-type" class="mt-2"></div>
                                    </div>
                                </div>

                                <!-- Source Examples - Show based on selected type -->
                                <div class="source-examples" id="source-examples" style="display: none;">
                                    <h6><i class="fas fa-info-circle"></i> <span id="example-title">Contoh URL yang Didukung:</span></h6>
                                    <ul id="example-list">
                                        <li><strong>YouTube:</strong> https://www.youtube.com/watch?v=VIDEO_ID</li>
                                        <li><strong>Vimeo:</strong> https://vimeo.com/VIDEO_ID</li>
                                        <li><strong>File Langsung:</strong> https://example.com/video.mp4</li>
                                        <li><strong>Google Drive:</strong> https://drive.google.com/file/d/FILE_ID/view</li>
                                    </ul>
                                </div>

                                <div class="form-group">
                                    <label>Durasi Video (detik)</label>
                                    <input type="number"
                                           class="form-control @error('durasi_video') is-invalid @enderror"
                                           name="durasi_video"
                                           value="{{ old('durasi_video') }}"
                                           placeholder="Contoh: 120"
                                           min="1">
                                    <small class="form-text text-muted">Opsional - durasi video dalam detik</small>
                                    @error('durasi_video')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               id="status"
                                               name="status"
                                               value="1"
                                               {{ old('status', true) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="status">Status Aktif</label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Hanya satu video yang bisa aktif pada satu waktu.
                                        Mengaktifkan video ini akan menonaktifkan video lainnya.
                                    </small>
                                </div>

                                <!-- Video Preview for new upload -->
                                <div class="form-group" id="preview-section" style="display: none;">
                                    <label>Preview Video</label>
                                    <div id="video-preview"></div>
                                </div>

                                <div class="card-footer text-right border-0 pt-3">
                                    <button type="submit" class="btn btn-primary" id="saveBtn">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                    <a href="{{ route('Antarmuka.index') }}" class="btn btn-secondary">
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
            console.log('🚀 Create Antarmuka Form initialized');

            // Volume control handler
            $('#volume').on('input', function() {
                const value = $(this).val();
                $('#volume-display').text(value + '%');
            });

            // Handle sumber video change
            $('#sumber_type').on('change', function() {
                const value = $(this).val();
                console.log('🔄 Sumber type changed to:', value);

                // Hide all sections
                $('#upload-section, #url-section, #preview-section, #source-examples').hide();

                if (value === 'upload') {
                    $('#upload-section').show();
                    updateUploadExamples();
                    console.log('📁 Upload section shown');
                } else if (['youtube', 'vimeo', 'url'].includes(value)) {
                    $('#url-section').show();
                    $('#source-examples').show();
                    updateUrlLabels(value);
                    console.log('🔗 URL section shown for:', value);
                }
            });

            // Update upload examples
            function updateUploadExamples() {
                $('#example-title').text('Format Video yang Didukung:');
                $('#example-list').html(`
                    <li><strong>MP4:</strong> Format paling umum dan direkomendasikan</li>
                    <li><strong>AVI:</strong> Format video Windows</li>
                    <li><strong>MOV:</strong> Format video Apple QuickTime</li>
                    <li><strong>WMV:</strong> Format video Windows Media</li>
                `);
                $('#source-examples').show();
            }

            // Update URL labels based on source
            function updateUrlLabels(source) {
                const labels = {
                    'youtube': {
                        label: 'URL YouTube',
                        placeholder: 'https://www.youtube.com/watch?v=...',
                        help: 'Masukkan URL video YouTube',
                        examples: `
                            <li><strong>Video Normal:</strong> https://www.youtube.com/watch?v=VIDEO_ID</li>
                            <li><strong>Video Pendek:</strong> https://youtu.be/VIDEO_ID</li>
                            <li><strong>Playlist:</strong> https://www.youtube.com/watch?v=VIDEO_ID&list=PLAYLIST_ID</li>
                        `
                    },
                    'vimeo': {
                        label: 'URL Vimeo',
                        placeholder: 'https://vimeo.com/...',
                        help: 'Masukkan URL video Vimeo',
                        examples: `
                            <li><strong>Video Normal:</strong> https://vimeo.com/VIDEO_ID</li>
                            <li><strong>Video Private:</strong> https://vimeo.com/VIDEO_ID/PASSWORD</li>
                        `
                    },
                    'url': {
                        label: 'URL Video',
                        placeholder: 'https://example.com/video.mp4',
                        help: 'Masukkan URL langsung ke file video',
                        examples: `
                            <li><strong>File MP4:</strong> https://example.com/video.mp4</li>
                            <li><strong>Google Drive:</strong> https://drive.google.com/file/d/FILE_ID/view</li>
                            <li><strong>Dropbox:</strong> https://www.dropbox.com/s/FILE_ID/video.mp4</li>
                        `
                    }
                };

                if (labels[source]) {
                    $('#url-label').html(labels[source].label + ' <span class="text-danger">*</span>');
                    $('#video_url').attr('placeholder', labels[source].placeholder);
                    $('#url-help').text(labels[source].help);

                    $('#example-title').text(`Contoh ${labels[source].label}:`);
                    $('#example-list').html(labels[source].examples);
                }
            }

            // =============================================
            // FIXED FILE UPLOAD HANDLING
            // =============================================
            const $uploadArea = $('#upload-area');
            const $fileInput = $('#video_file');
            const $fileInfo = $('#file-info');

            // FIXED: Click handler untuk upload area
            $uploadArea.on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('📁 Upload area clicked - triggering file input');
                $fileInput[0].click(); // Use direct DOM method
            });

            // Drag & Drop handlers
            $uploadArea.on('dragover', function(e) {
                e.preventDefault();
                $(this).addClass('dragover');
                console.log('📁 Dragover detected');
            });

            $uploadArea.on('dragleave', function(e) {
                e.preventDefault();
                $(this).removeClass('dragover');
            });

            $uploadArea.on('drop', function(e) {
                e.preventDefault();
                $(this).removeClass('dragover');
                console.log('📁 File dropped');

                const files = e.originalEvent.dataTransfer.files;
                if (files.length > 0) {
                    $fileInput[0].files = files;
                    handleFileSelect(files[0]);
                }
            });

            // File input change handler
            $fileInput.on('change', function(e) {
                console.log('📁 File input changed:', this.files.length);
                if (this.files.length > 0) {
                    handleFileSelect(this.files[0]);
                }
            });

            // Handle file selection
            function handleFileSelect(file) {
                console.log('📁 File selected:', {
                    name: file.name,
                    size: file.size,
                    type: file.type
                });

                // Validate file type
                if (!file.type.startsWith('video/')) {
                    alert('❌ Please select a valid video file.');
                    $fileInput.val('');
                    return;
                }

                // Validate file size (100MB = 104857600 bytes)
                if (file.size > 104857600) {
                    alert('❌ File size too large. Maximum 100MB allowed.');
                    $fileInput.val('');
                    return;
                }

                // Update UI
                $('#file-name').text(file.name);
                $('#file-size').text(formatFileSize(file.size));
                $uploadArea.hide();
                $fileInfo.show();

                // Create video preview
                const url = URL.createObjectURL(file);
                $('#video-preview').html(`
                    <video class="video-preview" controls>
                        <source src="${url}" type="${file.type}">
                        Your browser does not support the video tag.
                    </video>
                `);
                $('#preview-section').show();

                console.log('✅ File preview created');
            }

            // Remove file handler
            $('#remove-file').on('click', function() {
                console.log('🗑️ Removing selected file');
                $fileInput.val('');
                $uploadArea.show();
                $fileInfo.hide();
                $('#preview-section').hide();

                // Revoke object URL to free memory
                const video = $('#video-preview video')[0];
                if (video && video.src) {
                    URL.revokeObjectURL(video.src);
                }
            });

            // Format file size
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            // =============================================
            // URL PREVIEW FUNCTIONALITY
            // =============================================
            $('#video_url').on('input', function() {
                const url = $(this).val().trim();

                if (url) {
                    $('#preview-text').text(url);

                    // Detect source type
                    let sourceType = '';
                    let badgeClass = '';

                    if (url.includes('youtube.com') || url.includes('youtu.be')) {
                        sourceType = 'YouTube';
                        badgeClass = 'badge-danger';
                    } else if (url.includes('vimeo.com')) {
                        sourceType = 'Vimeo';
                        badgeClass = 'badge-info';
                    } else if (url.includes('drive.google.com')) {
                        sourceType = 'Google Drive';
                        badgeClass = 'badge-success';
                    } else if (url.match(/\.(mp4|avi|mov|wmv)$/i)) {
                        sourceType = 'Direct Video File';
                        badgeClass = 'badge-primary';
                    } else {
                        sourceType = 'External URL';
                        badgeClass = 'badge-warning';
                    }

                    $('#source-type').html(`
                        <span class="badge ${badgeClass}">${sourceType}</span>
                    `);

                    $('#url-preview').show();
                } else {
                    $('#url-preview').hide();
                }
            });

            // =============================================
            // FORM SUBMISSION
            // =============================================
            $('#createForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const btn = $('#saveBtn');

                console.log('📤 Form submission started');

                // Validate based on source type
                const sourceType = $('#sumber_type').val();
                if (!sourceType) {
                    alert('❌ Please select video source type');
                    return;
                }

                if (sourceType === 'upload') {
                    if (!$fileInput[0].files.length) {
                        alert('❌ Please select a video file to upload');
                        return;
                    }
                } else {
                    if (!$('#video_url').val().trim()) {
                        alert('❌ Please enter video URL');
                        return;
                    }
                }

                // Reset form state
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                btn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

                // Create FormData for file upload
                const formData = new FormData(this);

                // Debug form data
                console.log('📋 Form data contents:');
                for (let pair of formData.entries()) {
                    if (pair[1] instanceof File) {
                        console.log(`${pair[0]}: File(${pair[1].name}, ${pair[1].size} bytes)`);
                    } else {
                        console.log(`${pair[0]}: ${pair[1]}`);
                    }
                }

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log('✅ Form submitted successfully:', response);

                        if(response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.href = "{{ route('Antarmuka.index') }}";
                            });
                        } else {
                            // Handle redirect response
                            window.location.href = "{{ route('Antarmuka.index') }}";
                        }
                    },
                    error: function(xhr) {
                        console.error('❌ Form submission error:', xhr);
                        btn.html('<i class="fas fa-save"></i> Simpan').prop('disabled', false);

                        if(xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            console.log('📋 Validation errors:', errors);

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

            // Initialize URL preview if there's an old value
            if ($('#video_url').val()) {
                $('#video_url').trigger('input');
            }

            // Initialize based on old value
            if ($('#sumber_type').val()) {
                $('#sumber_type').trigger('change');
            }

            console.log('✅ Create Antarmuka Form ready');
        });
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
