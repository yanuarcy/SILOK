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
        }

        .file-upload-wrapper:hover {
            border-color: #6777ef;
            background-color: #f4f6f9;
        }

        .file-current {
            background-color: #e8f5e8;
            border: 2px solid #28a745;
        }

        .file-info {
            display: none;
            margin-top: 15px;
            padding: 15px;
            background-color: #e8f5e8;
            border-radius: 8px;
            border-left: 4px solid #28a745;
        }

        .current-file-display {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
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
    </style>
@endpush

@section('Dashboard')
    @include('admin.dashboard.header')
    @include('admin.dashboard.sidebar')

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Peraturan Perundang-undangan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('admin.Perpu.index') }}">Data Peraturan</a>
                    </div>
                    <div class="breadcrumb-item">Edit Peraturan</div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Edit Data Peraturan - {{ $perpu->full_title }}</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.Perpu.update', $perpu->id) }}" method="POST" id="editForm" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Jenis Peraturan <span class="text-danger">*</span></label>
                                            <select class="form-control @error('jenis_peraturan') is-invalid @enderror" name="jenis_peraturan">
                                                <option value="">Pilih Jenis Peraturan</option>
                                                @foreach($jenisOptions as $jenis)
                                                    <option value="{{ $jenis }}" {{ old('jenis_peraturan', $perpu->jenis_peraturan) == $jenis ? 'selected' : '' }}>
                                                        {{ $jenis }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('jenis_peraturan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nomor Peraturan <span class="text-danger">*</span></label>
                                                    <input type="text"
                                                           class="form-control @error('nomor_peraturan') is-invalid @enderror"
                                                           name="nomor_peraturan"
                                                           value="{{ old('nomor_peraturan', $perpu->nomor_peraturan) }}"
                                                           placeholder="Contoh: 55">
                                                    @error('nomor_peraturan')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Tahun <span class="text-danger">*</span></label>
                                                    <select class="form-control @error('tahun') is-invalid @enderror" name="tahun">
                                                        <option value="">Pilih Tahun</option>
                                                        @foreach($tahunOptions as $tahun)
                                                            <option value="{{ $tahun }}" {{ old('tahun', $perpu->tahun) == $tahun ? 'selected' : '' }}>
                                                                {{ $tahun }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('tahun')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Judul Peraturan <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   class="form-control @error('judul') is-invalid @enderror"
                                                   name="judul"
                                                   value="{{ old('judul', $perpu->judul) }}"
                                                   placeholder="Contoh: Standar Pelayanan Kelurahan">
                                            @error('judul')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Tentang <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('tentang') is-invalid @enderror"
                                                      name="tentang"
                                                      rows="3"
                                                      placeholder="Contoh: Standar Pelayanan Kelurahan di Lingkungan Pemerintah Kota Surabaya">{{ old('tentang', $perpu->tentang) }}</textarea>
                                            @error('tentang')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>File PDF</label>

                                            <!-- Current File Display -->
                                            <div class="current-file-display">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-file-pdf text-danger fa-2x me-3"></i>
                                                    <div class="flex-grow-1">
                                                        <div class="fw-bold">{{ $perpu->file_pdf }}</div>
                                                        <div class="text-muted small">{{ $perpu->formatted_file_size }}</div>
                                                        <div class="text-muted small">Upload: {{ $perpu->tanggal_upload->format('d/m/Y') }}</div>
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('admin.Perpu.show', $perpu->id) }}" class="btn btn-sm btn-info me-1" target="_blank">
                                                            <i class="fas fa-eye"></i> Lihat
                                                        </a>
                                                        <a href="{{ route('admin.Perpu.download', $perpu->id) }}" class="btn btn-sm btn-success">
                                                            <i class="fas fa-download"></i> Download
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Upload New File -->
                                            <div class="file-upload-wrapper" id="fileUploadWrapper">
                                                <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                                <h6 class="text-muted">Upload file PDF baru (opsional)</h6>
                                                <p class="text-muted small">Maksimal 50MB, format PDF</p>
                                                <input type="file"
                                                       class="form-control-file d-none @error('file_pdf') is-invalid @enderror"
                                                       name="file_pdf"
                                                       id="file_pdf"
                                                       accept=".pdf">
                                            </div>
                                            <div class="file-info" id="fileInfo">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-file-pdf text-danger fa-2x me-3"></i>
                                                    <div>
                                                        <div class="fw-bold" id="fileName"></div>
                                                        <div class="text-muted small" id="fileSize"></div>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-outline-danger ms-auto" id="removeFile">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @error('file_pdf')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Tanggal Penetapan <span class="text-danger">*</span></label>
                                            <input type="date"
                                                   class="form-control @error('tanggal_penetapan') is-invalid @enderror"
                                                   name="tanggal_penetapan"
                                                   value="{{ old('tanggal_penetapan', $perpu->tanggal_penetapan->format('Y-m-d')) }}">
                                            @error('tanggal_penetapan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Deskripsi</label>
                                            <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                                                      name="deskripsi"
                                                      rows="3"
                                                      placeholder="Deskripsi tambahan (opsional)">{{ old('deskripsi', $perpu->deskripsi) }}</textarea>
                                            @error('deskripsi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Status <span class="text-danger">*</span></label>
                                            <select class="form-control @error('status') is-invalid @enderror" name="status">
                                                <option value="Published" {{ old('status', $perpu->status) == 'Published' ? 'selected' : '' }}>Published</option>
                                                <option value="Draft" {{ old('status', $perpu->status) == 'Draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="Archived" {{ old('status', $perpu->status) == 'Archived' ? 'selected' : '' }}>Archived</option>
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
                                                   value="{{ old('urutan_tampil', $perpu->urutan_tampil) }}"
                                                   min="0"
                                                   placeholder="Urutan tampil">
                                            @error('urutan_tampil')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
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
                                                       {{ old('is_active', $perpu->is_active) ? 'checked' : '' }}>
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
                                           value="{{ old('tags', is_array($perpu->tags) ? implode(',', $perpu->tags) : '') }}"
                                           data-role="tagsinput"
                                           placeholder="Ketik tag dan tekan Enter">
                                    @error('tags')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Contoh: pelayanan, administrasi, kependudukan</small>
                                </div>

                                <div class="card-footer text-right border-0 pt-3">
                                    <button type="submit" class="btn btn-primary" id="saveBtn">
                                        <i class="fas fa-save"></i> Simpan Perubahan
                                    </button>
                                    <a href="{{ route('admin.Perpu.index') }}" class="btn btn-secondary">
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
            let selectedFile = null;

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

            // FIXED: File upload click handler
            $('#fileUploadWrapper').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('📁 Upload area clicked - triggering file input');
                $('#file_pdf')[0].click(); // Use direct DOM method
            });

            // File input change handler
            $('#file_pdf').on('change', function(e) {
                console.log('📁 File input changed:', this.files.length);
                if (this.files.length > 0) {
                    handleFileSelect(this.files[0]);
                }
            });

            // Drag and drop functionality
            $('#fileUploadWrapper').on('dragover dragenter', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).addClass('dragover');
                console.log('📁 Dragover detected');
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
                console.log('📁 File dropped');

                const files = e.originalEvent.dataTransfer.files;
                if (files.length > 0) {
                    // Manually set the files to the input
                    $('#file_pdf')[0].files = files;
                    handleFileSelect(files[0]);
                }
            });

            $('#removeFile').on('click', function() {
                console.log('🗑️ Removing selected file');
                selectedFile = null;
                $('#file_pdf').val('');
                $('#fileInfo').hide();
            });

            function handleFileSelect(file) {
                console.log('📁 File selected:', {
                    name: file.name,
                    size: file.size,
                    type: file.type
                });

                // Validate file type
                if (file.type !== 'application/pdf') {
                    Swal.fire('Error', 'Hanya file PDF yang diperbolehkan.', 'error');
                    $('#file_pdf').val('');
                    return;
                }

                // Validate file size (50MB = 52428800 bytes)
                if (file.size > 52428800) {
                    Swal.fire('Error', 'Ukuran file terlalu besar. Maksimal 50MB.', 'error');
                    $('#file_pdf').val('');
                    return;
                }

                selectedFile = file;
                $('#fileName').text(file.name);
                $('#fileSize').text(formatFileSize(file.size));
                $('#fileInfo').show();

                console.log('✅ File selected successfully');
            }

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

                btn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

                // Create FormData for file upload
                const formData = new FormData(this);

                // Manually append tags if using tagsinput
                const tags = $('.inputtags').val();
                if (tags) {
                    formData.set('tags', tags);
                }

                console.log('📝 Submitting form with data:', {
                    file: $('#file_pdf')[0].files[0]?.name,
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
                                window.location.href = "{{ route('admin.Perpu.index') }}";
                            });
                        }
                    },
                    error: function(xhr) {
                        console.log('❌ Error response:', xhr);
                        btn.html('<i class="fas fa-save"></i> Simpan Perubahan').prop('disabled', false);

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
        });
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
