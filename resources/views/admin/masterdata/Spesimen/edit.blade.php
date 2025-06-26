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

        .section-divider {
            border-top: 2px solid #e9ecef;
            margin: 30px 0;
            padding-top: 20px;
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
                <h1>Edit Spesimen TTD & Stempel</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('admin.masterdata.Spesimen.index') }}">Spesimen TTD & Stempel</a>
                    </div>
                    <div class="breadcrumb-item">Edit Spesimen</div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Form Edit Spesimen TTD & Stempel</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.masterdata.Spesimen.update', $spesimen->id) }}" method="POST" id="editForm" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Pilih Pejabat <span class="text-danger">*</span></label>
                                            <select class="form-control @error('user_id') is-invalid @enderror" name="user_id" id="user_id">
                                                <option value="">Pilih Pejabat</option>
                                                @foreach($pejabatOptions as $pejabat)
                                                    <option value="{{ $pejabat->id }}"
                                                            data-role="{{ $pejabat->role }}"
                                                            data-rt="{{ $pejabat->nomor_rt }}"
                                                            data-rw="{{ $pejabat->nomor_rw }}"
                                                            {{ old('user_id', $spesimen->user_id) == $pejabat->id ? 'selected' : '' }}>
                                                        {{ $pejabat->name }} - {{ $pejabat->role }}
                                                        @if($pejabat->role === 'Ketua RT')
                                                            (RT {{ $pejabat->nomor_rt }} RW {{ $pejabat->nomor_rw }})
                                                        @else
                                                            (RW {{ $pejabat->nomor_rw }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('user_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div id="user-info-display">
                                            <div class="user-info">
                                                <div class="user-name">{{ $spesimen->user->name }}</div>
                                                <div class="user-role">{{ $spesimen->user->role }}</div>
                                                <div class="user-wilayah">{{ $spesimen->wilayah_lengkap }}</div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Nama Pejabat <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   class="form-control @error('nama_pejabat') is-invalid @enderror"
                                                   name="nama_pejabat"
                                                   value="{{ old('nama_pejabat', $spesimen->nama_pejabat) }}"
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
                                                    <option value="{{ $key }}" {{ old('jabatan', $spesimen->jabatan) == $key ? 'selected' : '' }}>
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
                                                    <select class="form-control @error('nomor_rw') is-invalid @enderror" name="nomor_rw" id="nomor_rw">
                                                        <option value="">Pilih RW</option>
                                                        @foreach($rwOptions as $rw)
                                                            <option value="{{ $rw }}" {{ old('nomor_rw', $spesimen->nomor_rw) == $rw ? 'selected' : '' }}>
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
                                                    <label>Nomor RT <span id="rt-required" class="text-danger" style="{{ $spesimen->jabatan === 'Ketua RT' ? '' : 'display: none;' }}">*</span></label>
                                                    <select class="form-control @error('nomor_rt') is-invalid @enderror" name="nomor_rt" id="nomor_rt">
                                                        <option value="">Pilih RT</option>
                                                        @foreach($rtOptions as $rt)
                                                            <option value="{{ $rt }}" {{ old('nomor_rt', $spesimen->nomor_rt) == $rt ? 'selected' : '' }}>
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
                                            <label>Keterangan</label>
                                            <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                                      name="keterangan"
                                                      rows="3"
                                                      placeholder="Keterangan tambahan (opsional)">{{ old('keterangan', $spesimen->keterangan) }}</textarea>
                                            @error('keterangan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Status <span class="text-danger">*</span></label>
                                                    <select class="form-control @error('status') is-invalid @enderror" name="status">
                                                        <option value="Aktif" {{ old('status', $spesimen->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                                        <option value="Tidak Aktif" {{ old('status', $spesimen->status) == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
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
                                                           value="{{ old('urutan_tampil', $spesimen->urutan_tampil) }}"
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
                                                               {{ old('is_active', $spesimen->is_active) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="is_active">Status Aktif</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Current Files -->
                                        @if($spesimen->file_ttd || $spesimen->file_stempel)
                                            <div class="current-files">
                                                <h6><i class="fas fa-folder-open text-info"></i> File Saat Ini</h6>

                                                @if($spesimen->file_ttd)
                                                    <div class="current-file-item" data-type="ttd">
                                                        <img src="{{ $spesimen->ttd_url }}" class="file-preview" alt="TTD">
                                                        <div class="file-details">
                                                            <div class="file-name">Tanda Tangan (TTD)</div>
                                                            <div class="file-info">{{ basename($spesimen->file_ttd) }}</div>
                                                        </div>
                                                        <button type="button" class="remove-current-file" data-type="ttd">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                @endif

                                                @if($spesimen->file_stempel)
                                                    <div class="current-file-item" data-type="stempel">
                                                        <img src="{{ $spesimen->stempel_url }}" class="file-preview" alt="Stempel">
                                                        <div class="file-details">
                                                            <div class="file-name">Stempel</div>
                                                            <div class="file-info">{{ basename($spesimen->file_stempel) }}</div>
                                                        </div>
                                                        <button type="button" class="remove-current-file" data-type="stempel">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="section-divider">
                                                <h6><i class="fas fa-plus text-success"></i> Upload File Baru</h6>
                                            </div>
                                        @endif

                                        <!-- Upload TTD -->
                                        <div class="upload-section">
                                            <h6><i class="fas fa-signature text-primary"></i> Upload Tanda Tangan (TTD) {{ $spesimen->file_ttd ? 'Pengganti' : '' }}</h6>
                                            <div class="file-upload-wrapper" id="ttdUploadWrapper">
                                                <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                                <p class="text-muted mb-2">Drag & Drop file TTD atau klik untuk pilih</p>
                                                <small class="text-muted">Format: JPG, PNG, GIF, SVG | Maksimal 5MB</small>
                                                <li><strong>Background Removal:</strong> Background gambar akan dihapus otomatis menggunakan AI</li>
                                                <li>File hasil akan disimpan dalam format PNG dengan background transparan</li>
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
                                            <h6><i class="fas fa-stamp text-success"></i> Upload Stempel {{ $spesimen->file_stempel ? 'Pengganti' : '' }}</h6>
                                            <div class="file-upload-wrapper" id="stempelUploadWrapper">
                                                <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                                <p class="text-muted mb-2">Drag & Drop file Stempel atau klik untuk pilih</p>
                                                <small class="text-muted">Format: JPG, PNG, GIF, SVG | Maksimal 5MB</small>
                                                <li><strong>Background Removal:</strong> Background gambar akan dihapus otomatis menggunakan AI</li>
                                                <li>File hasil akan disimpan dalam format PNG dengan background transparan</li>
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

                                <!-- Hidden input for removed files -->
                                <input type="hidden" name="removed_files" id="removedFiles" value="">

                                <div class="card-footer text-right border-0 pt-3">
                                    <button type="submit" class="btn btn-primary" id="saveBtn">
                                        <i class="fas fa-save"></i> Update
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
            let removedFiles = [];

            // Handle user selection change
            $('#user_id').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const userId = $(this).val();

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
                    } else {
                        wilayah = `RW ${rw}`;
                    }
                    $('.user-wilayah').text(wilayah);
                    $('.user-name').text(name);
                    $('.user-role').text(role);

                    // Auto fill form
                    $('#jabatan').val(role);
                    $('#nomor_rw').val(rw);

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

                    // Trigger jabatan change
                    $('#jabatan').trigger('change');
                }
            });

            // Handle jabatan change
            $('#jabatan').on('change', function() {
                const jabatan = $(this).val();

                if (jabatan === 'Ketua RT') {
                    $('#rt-required').show();
                } else {
                    $('#rt-required').hide();
                    $('#nomor_rt').val('');
                }
            });

            // Remove current file handler
            $('.remove-current-file').on('click', function(e) {
                e.preventDefault();
                const button = $(this);
                const type = button.data('type');
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
                        // Add to removed files list
                        removedFiles.push(type);

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

            // Remove new file handler
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
                                window.location.href = "{{ route('admin.masterdata.Spesimen.index') }}";
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

            // Keyboard shortcuts
            $(document).on('keydown', function(e) {
                // Ctrl+S to save
                if ((e.ctrlKey || e.metaKey) && e.which === 83) {
                    e.preventDefault();
                    $('#editForm').submit();
                }
            });

            // Trigger jabatan change on page load
            $('#jabatan').trigger('change');

            console.log('✅ Spesimen Edit form initialized successfully');
        });
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
