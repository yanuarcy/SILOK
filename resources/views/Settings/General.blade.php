@extends('Template.template')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">

    <style>
        .profile-text {
            margin-bottom: 0;
            padding: 0.5rem 0;
        }

        .image-preview {
            max-width: 200px;
            max-height: 100px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
        }

        .favicon-preview {
            max-width: 32px;
            max-height: 32px;
            margin-top: 10px;
        }

        .current-image {
            margin-bottom: 10px;
        }
    </style>
@endpush

@section('Dashboard')
    @include('admin.dashboard.header')
    @include('admin.dashboard.sidebar')

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>General Settings</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('Dashboard.General') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Settings</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Pengaturan Umum</h2>
                <p class="section-lead">
                    Kelola informasi umum website pada halaman ini.
                </p>

                <div class="row mt-sm-4">
                    <div class="col-md-4">
                        @include('layouts.jumpToSettings')
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h4>Informasi Website</h4>
                                <div class="card-header-action">
                                    <a href="#" class="btn btn-icon btn-primary" id="btn-edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="form-settings" action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Judul Website</label>
                                            <p class="profile-text">{{ $settings->site_title ?? '-' }}</p>
                                            <input type="text"
                                                name="site_title"
                                                class="form-control profile-input d-none"
                                                value="{{ $settings->site_title }}"
                                                required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Nama Kelurahan</label>
                                            <p class="profile-text">{{ $settings->organization_name ?? '-' }}</p>
                                            <input type="text"
                                                name="organization_name"
                                                class="form-control profile-input d-none"
                                                value="{{ $settings->organization_name }}"
                                                required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label>Alamat Kelurahan</label>
                                            <p class="profile-text">{{ $settings->organization_address ?? '-' }}</p>
                                            <textarea name="organization_address"
                                                class="form-control profile-input d-none"
                                                rows="3"
                                                required>{{ $settings->organization_address }}</textarea>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label>Nama Website</label>
                                            <p class="profile-text">{{ $settings->site_name ?? '-' }}</p>
                                            <input type="text"
                                                name="site_name"
                                                class="form-control profile-input d-none"
                                                value="{{ $settings->site_name }}"
                                                placeholder="Contoh: SILOK">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Logo Website</label>
                                            <p class="profile-text">
                                                @if($settings->site_logo)
                                                    <div class="current-image">
                                                        <img src="{{ asset('storage/' . $settings->site_logo) }}"
                                                             alt="Logo"
                                                             class="image-preview">
                                                    </div>
                                                @else
                                                    Belum ada logo
                                                @endif
                                            </p>
                                            <div class="profile-input d-none">
                                                @if($settings->site_logo)
                                                    <div class="current-image">
                                                        <label class="form-label">Logo Saat Ini:</label><br>
                                                        <img src="{{ asset('storage/' . $settings->site_logo) }}"
                                                             alt="Current Logo"
                                                             class="image-preview">
                                                    </div>
                                                @endif
                                                <input type="file"
                                                    name="site_logo"
                                                    class="form-control"
                                                    accept="image/*">
                                                <small class="form-text text-muted">
                                                    Max: 2MB. Format: JPG, PNG, GIF, SVG
                                                </small>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Favicon</label>
                                            <p class="profile-text">
                                                @if($settings->site_favicon)
                                                    <div class="current-image">
                                                        <img src="{{ asset('storage/' . $settings->site_favicon) }}"
                                                             alt="Favicon"
                                                             class="favicon-preview">
                                                    </div>
                                                @else
                                                    Belum ada favicon
                                                @endif
                                            </p>
                                            <div class="profile-input d-none">
                                                @if($settings->site_favicon)
                                                    <div class="current-image">
                                                        <label class="form-label">Favicon Saat Ini:</label><br>
                                                        <img src="{{ asset('storage/' . $settings->site_favicon) }}"
                                                             alt="Current Favicon"
                                                             class="favicon-preview">
                                                    </div>
                                                @endif
                                                <input type="file"
                                                    name="site_favicon"
                                                    class="form-control"
                                                    accept="image/*,.ico">
                                                <small class="form-text text-muted">
                                                    Max: 1MB. Format: ICO, PNG, JPG (32x32px)
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-footer text-right d-none" id="form-buttons">
                                        <button type="button" class="btn btn-secondary" id="btn-cancel">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <div class="footer-left">
            Copyright &copy; {{ date('Y') }} <div class="bullet"></div>
            {{ $settings->organization_name ?? 'Kelurahan' }}
        </div>
        <div class="footer-right">
            1.0.0
        </div>
    </footer>
@endsection

@push('scripts')
    <!-- General JS Scripts -->
    <script src="{{ asset('library/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('library/popper.js/dist/umd/popper.js') }}"></script>
    <script src="{{ asset('library/tooltip.js/dist/umd/tooltip.js') }}"></script>
    <script src="{{ asset('library/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('library/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('js/stisla.js') }}"></script>

    <!-- JS Libraries -->
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Toggle edit mode
            $('#btn-edit').click(function(e) {
                e.preventDefault();
                const isEditMode = $(this).hasClass('btn-secondary');

                // Toggle classes untuk tampilan
                $('.profile-text').toggleClass('d-none');
                $('.profile-input').toggleClass('d-none');
                $('#form-buttons').toggleClass('d-none');
                $(this).toggleClass('btn-primary btn-secondary');
                $(this).find('i').toggleClass('fa-edit fa-times');
            });

            // Cancel edit
            $('#btn-cancel').click(function() {
                $('.profile-text').removeClass('d-none');
                $('.profile-input').addClass('d-none');
                $('#form-buttons').addClass('d-none');
                $('#btn-edit').removeClass('btn-secondary').addClass('btn-primary');
                $('#btn-edit').find('i').removeClass('fa-times').addClass('fa-edit');

                // Reset form
                $('#form-settings')[0].reset();
            });

            // Handle form submission
            $('#form-settings').submit(function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('settings.update') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('button[type="submit"]').prop('disabled', true);
                        Swal.fire({
                            title: 'Loading...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                        $('button[type="submit"]').prop('disabled', false);

                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                // Exit edit mode
                                $('.profile-text').removeClass('d-none');
                                $('.profile-input').addClass('d-none');
                                $('#form-buttons').addClass('d-none');
                                $('#btn-edit').removeClass('btn-secondary').addClass('btn-primary');
                                $('#btn-edit').find('i').removeClass('fa-times').addClass('fa-edit');

                                // Reload page to show updated data
                                window.location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        $('button[type="submit"]').prop('disabled', false);

                        let message = 'Terjadi kesalahan saat memperbarui settings';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: message
                        });
                    }
                });
            });
        });
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
