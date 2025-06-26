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

        .video-preview {
            max-width: 100%;
            max-height: 200px;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .current-video {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 6px;
            border: 1px solid #e9ecef;
            margin-bottom: 1rem;
        }

        .file-info {
            background: #f8f9fa;
            padding: 0.75rem;
            border-radius: 6px;
            border: 1px solid #e9ecef;
            margin-top: 0.5rem;
        }

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

        .badge-current {
            background-color: #17a2b8;
            color: white;
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
                <h1>Edit Antarmuka</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('Antarmuka.index') }}">Data Antarmuka</a>
                    </div>
                    <div class="breadcrumb-item">Edit Antarmuka</div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Form Edit Video Antarmuka</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('Antarmuka.update', $antarmuka->id_antarmuka) }}" method="POST" enctype="multipart/form-data" id="editForm">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label>Keterangan <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('keterangan') is-invalid @enderror"
                                           name="keterangan"
                                           value="{{ old('keterangan', $antarmuka->keterangan) }}"
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
                                           value="{{ old('nama', $antarmuka->nama) }}"
                                           placeholder="Masukkan nama video">
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Current Video Info -->
                                <div class="form-group">
                                    <label>Video Saat Ini</label>
                                    <div class="current-video">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                @php
                                                    $currentSumber = 'url'; // default
                                                    if (filter_var($antarmuka->sumber, FILTER_VALIDATE_URL)) {
                                                        if (strpos($antarmuka->sumber, 'youtube.com') !== false || strpos($antarmuka->sumber, 'youtu.be') !== false) {
                                                            $currentSumber = 'youtube';
                                                        } elseif (strpos($antarmuka->sumber, 'vimeo.com') !== false) {
                                                            $currentSumber = 'vimeo';
                                                        } else {
                                                            $currentSumber = 'url';
                                                        }
                                                    } else {
                                                        $currentSumber = 'upload';
                                                    }
                                                @endphp
                                                <span class="badge badge-current">{{ ucfirst($currentSumber) }}</span>
                                                <strong class="ml-2">{{ $antarmuka->nama }}</strong>
                                            </div>
                                            @if($antarmuka->durasi_video)
                                                <small class="text-muted">{{ $antarmuka->durasi_video }} detik</small>
                                            @endif
                                        </div>

                                        @if($currentSumber === 'upload' && $antarmuka->video_path)
                                            <video class="video-preview" controls>
                                                <source src="{{ asset('storage/' . $antarmuka->video_path) }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        @else
                                            <div class="mt-2">
                                                <i class="fas fa-link text-primary"></i>
                                                <a href="{{ $antarmuka->sumber }}" target="_blank" class="ml-2">
                                                    {{ Str::limit($antarmuka->sumber, 60) }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
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
                                                   value="{{ old('volume', $antarmuka->volume ?? 50) }}">
                                            <span class="volume-display" id="volume-display">{{ old('volume', $antarmuka->volume ?? 50) }}%</span>
                                        </div>
                                        <small class="form-text text-muted mt-2">
                                            Atur volume video dari 0% (silent) hingga 100% (maksimal)
                                        </small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Sumber Video <span class="text-danger">*</span></label>
                                    <select class="form-control @error('sumber_type') is-invalid @enderror" name="sumber_type" id="sumber">
                                        <option value="">Pilih Sumber Video</option>
                                        <option value="upload" {{ old('sumber_type', $currentSumber) == 'upload' ? 'selected' : '' }}>Upload File</option>
                                        <option value="youtube" {{ old('sumber_type', $currentSumber) == 'youtube' ? 'selected' : '' }}>YouTube</option>
                                        <option value="vimeo" {{ old('sumber_type', $currentSumber) == 'vimeo' ? 'selected' : '' }}>Vimeo</option>
                                        <option value="url" {{ old('sumber_type', $currentSumber) == 'url' ? 'selected' : '' }}>URL Eksternal</option>
                                    </select>
                                    @error('sumber_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Upload File Section -->
                                <div class="form-group" id="upload-section" style="display: none;">
                                    <label>Upload Video File Baru</label>
                                    <div class="upload-area" id="upload-area">
                                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                        <h6>Klik atau drag & drop video file di sini</h6>
                                        <p class="text-muted">Format yang didukung: MP4, AVI, MOV, WMV (Max: 100MB)</p>
                                        <p class="text-warning"><small>Kosongkan jika tidak ingin mengubah file video</small></p>
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
                                           value="{{ old('sumber', $antarmuka->sumber) }}"
                                           id="video_url"
                                           placeholder="https://...">
                                    <small class="form-text text-muted" id="url-help">
                                        Masukkan URL video
                                    </small>
                                    @error('sumber')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Durasi Video (detik)</label>
                                    <input type="number"
                                           class="form-control @error('durasi_video') is-invalid @enderror"
                                           name="durasi_video"
                                           value="{{ old('durasi_video', $antarmuka->durasi_video) }}"
                                           placeholder="Contoh: 120"
                                           min="1">
                                    <small class="form-text text-muted">Kosongkan jika akan dideteksi otomatis</small>
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
                                               {{ old('status', $antarmuka->status) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="status">Status Aktif</label>
                                    </div>
                                </div>

                                <!-- Video Preview for new upload -->
                                <div class="form-group" id="preview-section" style="display: none;">
                                    <label>Preview Video Baru</label>
                                    <div id="video-preview"></div>
                                </div>

                                <div class="card-footer text-right border-0 pt-3">
                                    <button type="submit" class="btn btn-primary" id="saveBtn">
                                        <i class="fas fa-save"></i> Update
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
            // Volume control handler
            $('#volume').on('input', function() {
                const value = $(this).val();
                $('#volume-display').text(value + '%');
            });

            // Handle sumber video change
            $('#sumber').on('change', function() {
                const value = $(this).val();

                // Hide all sections
                $('#upload-section, #url-section, #preview-section').hide();

                if (value === 'upload') {
                    $('#upload-section').show();
                } else if (['youtube', 'vimeo', 'url'].includes(value)) {
                    $('#url-section').show();
                    updateUrlLabels(value);
                }
            });

            // Update URL labels based on source
            function updateUrlLabels(source) {
                const labels = {
                    'youtube': {
                        label: 'URL YouTube',
                        placeholder: 'https://www.youtube.com/watch?v=...',
                        help: 'Masukkan URL video YouTube'
                    },
                    'vimeo': {
                        label: 'URL Vimeo',
                        placeholder: 'https://vimeo.com/...',
                        help: 'Masukkan URL video Vimeo'
                    },
                    'url': {
                        label: 'URL Video',
                        placeholder: 'https://example.com/video.mp4',
                        help: 'Masukkan URL langsung ke file video'
                    }
                };

                if (labels[source]) {
                    $('#url-label').html(labels[source].label + ' <span class="text-danger">*</span>');
                    $('#video_url').attr('placeholder', labels[source].placeholder);
                    $('#url-help').text(labels[source].help);
                }
            }

            // FIXED: File upload handling
            const $uploadArea = $('#upload-area');
            const $fileInput = $('#video_file');
            const $fileInfo = $('#file-info');

            // FIX: Make sure click works properly
            $uploadArea.on('click', function(e) {
                e.preventDefault();
                console.log('Upload area clicked'); // Debug
                $fileInput.trigger('click'); // Use trigger instead of click()
            });

            $uploadArea.on('dragover', function(e) {
                e.preventDefault();
                $(this).addClass('dragover');
            });

            $uploadArea.on('dragleave', function(e) {
                e.preventDefault();
                $(this).removeClass('dragover');
            });

            $uploadArea.on('drop', function(e) {
                e.preventDefault();
                $(this).removeClass('dragover');

                const files = e.originalEvent.dataTransfer.files;
                if (files.length > 0) {
                    $fileInput[0].files = files;
                    handleFileSelect(files[0]);
                }
            });

            $fileInput.on('change', function(e) {
                console.log('File input changed'); // Debug
                if (this.files.length > 0) {
                    handleFileSelect(this.files[0]);
                }
            });

            function handleFileSelect(file) {
                console.log('File selected:', file.name); // Debug

                if (file.type.startsWith('video/')) {
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
                } else {
                    alert('Please select a valid video file.');
                    $fileInput.val('');
                }
            }

            $('#remove-file').on('click', function() {
                $fileInput.val('');
                $uploadArea.show();
                $fileInfo.hide();
                $('#preview-section').hide();
            });

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            // Form submission
            $('#editForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const btn = $('#saveBtn');

                // Reset form state
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                btn.html('<i class="fas fa-spinner fa-spin"></i> Mengupdate...').prop('disabled', true);

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
                                window.location.href = "{{ route('Antarmuka.index') }}";
                            });
                        }
                    },
                    error: function(xhr) {
                        btn.html('<i class="fas fa-save"></i> Update').prop('disabled', false);

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
                                xhr.responseJSON.message || 'Terjadi kesalahan saat mengupdate data.',
                                'error'
                            );
                        }
                    }
                });
            });

            // Initialize based on current value
            $('#sumber').trigger('change');
        });
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
