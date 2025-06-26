@extends('Template.template')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
@endpush

@section('Dashboard')
    @include('admin.dashboard.header')
    @include('admin.dashboard.sidebar')

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Tambah Pegawai</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('Pegawai.index') }}">Data Pegawai</a>
                    </div>
                    <div class="breadcrumb-item">Tambah Pegawai</div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Form Tambah Pegawai</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('Pegawai.store') }}" method="POST" id="createForm">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Pilih User/Pegawai <span class="text-danger">*</span></label>
                                            <select class="form-control @error('user_id') is-invalid @enderror" name="user_id">
                                                <option value="">Pilih User/Pegawai</option>
                                                @foreach($availableUsers as $user)
                                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }} ({{ $user->role }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('user_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Media Sosial Section -->
                                        <div class="form-group">
                                            <label>Media Sosial</label>
                                            <div id="media-sosial-container">
                                                <div class="media-sosial-item mb-2">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <select class="form-control" name="media_sosial[0][platform]">
                                                                <option value="">Pilih Platform</option>
                                                                <option value="Facebook">Facebook</option>
                                                                <option value="Twitter">Twitter</option>
                                                                <option value="Instagram">Instagram</option>
                                                                <option value="LinkedIn">LinkedIn</option>
                                                                <option value="YouTube">YouTube</option>
                                                                <option value="WhatsApp">WhatsApp</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="url"
                                                                class="form-control"
                                                                name="media_sosial[0][url]"
                                                                placeholder="https://...">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" class="btn btn-danger btn-sm remove-media" style="display: none;">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" id="add-media-sosial" class="btn btn-success btn-sm mt-2">
                                                <i class="fas fa-plus"></i> Tambah Media Sosial
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Jabatan <span class="text-danger">*</span></label>
                                            <select class="form-control @error('jabatan') is-invalid @enderror" name="jabatan">
                                                <option value="">Pilih Jabatan</option>
                                                @foreach($jabatanOptions as $jabatan)
                                                    <option value="{{ $jabatan }}" {{ old('jabatan') == $jabatan ? 'selected' : '' }}>
                                                        {{ $jabatan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('jabatan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Urutan Tampil</label>
                                            <input type="number"
                                                   class="form-control @error('urutan_tampil') is-invalid @enderror"
                                                   name="urutan_tampil"
                                                   value="{{ old('urutan_tampil') }}"
                                                   min="1"
                                                   placeholder="Urutan tampil di website">
                                            @error('urutan_tampil')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Kosongkan untuk otomatis urutan terakhir</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
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
                                </div>                                </div>

                                <div class="card-footer text-right border-0 pt-3">
                                    <button type="submit" class="btn btn-primary" id="saveBtn">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                    <a href="{{ route('Pegawai.index') }}" class="btn btn-secondary">
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
            let mediaIndex = 1;

            // Add media sosial
            $('#add-media-sosial').click(function() {
                const newMedia = `
                    <div class="media-sosial-item mb-2">
                        <div class="row">
                            <div class="col-md-4">
                                <select class="form-control" name="media_sosial[${mediaIndex}][platform]">
                                    <option value="">Pilih Platform</option>
                                    <option value="Facebook">Facebook</option>
                                    <option value="Twitter">Twitter</option>
                                    <option value="Instagram">Instagram</option>
                                    <option value="LinkedIn">LinkedIn</option>
                                    <option value="YouTube">YouTube</option>
                                    <option value="WhatsApp">WhatsApp</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="url"
                                       class="form-control"
                                       name="media_sosial[${mediaIndex}][url]"
                                       placeholder="https://...">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger btn-sm remove-media">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                $('#media-sosial-container').append(newMedia);
                mediaIndex++;
                updateRemoveButtons();
            });

            // Remove media sosial
            $(document).on('click', '.remove-media', function() {
                $(this).closest('.media-sosial-item').remove();
                updateRemoveButtons();
            });

            // Update remove buttons visibility
            function updateRemoveButtons() {
                const items = $('.media-sosial-item');
                if (items.length > 1) {
                    $('.remove-media').show();
                } else {
                    $('.remove-media').hide();
                }
            }

            // Validasi media sosial sebelum submit
            function validateMediaSosial() {
                let invalidCount = 0; // Hitung yang invalid saja
                let invalidMessages = [];

                $('.media-sosial-item').each(function(index) {
                    const platform = $(this).find('select').val();
                    const url = $(this).find('input[type="url"]').val().trim();

                    // Jika platform dipilih tapi URL kosong
                    if (platform && !url) {
                        invalidCount++;
                        invalidMessages.push(`Field ${index + 1}: Platform "${platform}" dipilih tapi URL kosong`);
                    }
                    // Jika URL diisi tapi platform tidak dipilih
                    else if (!platform && url) {
                        invalidCount++;
                        invalidMessages.push(`Field ${index + 1}: URL diisi tapi platform tidak dipilih`);
                    }
                    // Jika keduanya kosong = VALID (tidak perlu action apapun)
                    // Jika keduanya terisi = VALID (tidak perlu action apapun)
                });

                // Jika ada yang invalid, tampilkan SweetAlert
                if (invalidCount > 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Media Sosial Tidak Lengkap!',
                        html: `
                            <div style="text-align: left;">
                                <p>Terdapat ${invalidCount} field yang tidak lengkap:</p>
                                <ul style="margin: 10px 0; padding-left: 20px;">
                                    ${invalidMessages.map(msg => `<li>${msg}</li>`).join('')}
                                </ul>
                                <p><strong>Solusi:</strong> Lengkapi platform dan URL, atau kosongkan keduanya.</p>
                            </div>
                        `,
                        confirmButtonText: 'OK',
                        width: '500px'
                    });
                    return false; // Invalid
                }

                return true; // Valid - semua field kosong atau lengkap
            }

            $('#createForm').on('submit', function(e) {
                e.preventDefault();

                // Validasi media sosial terlebih dahulu
                if (!validateMediaSosial()) {
                    return;
                }

                const form = $(this);
                const btn = $('#saveBtn');

                // Reset form state
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                btn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if(response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.href = "{{ route('Pegawai.index') }}";
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
                                xhr.responseJSON.message || 'Terjadi kesalahan saat menyimpan data.',
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
