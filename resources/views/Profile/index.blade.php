@extends('Template.template')

{{-- @section('title', 'General Dashboard') --}}

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet"
        href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">

    <link rel="stylesheet"
        href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">

    <link rel="stylesheet"
        href="{{ asset('library/bootstrap-social/assets/css/bootstrap.css') }}">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <link rel="stylesheet"
        href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css" rel="stylesheet">

    <!-- Template CSS -->
    <link rel="stylesheet"
        href="{{ asset('css/style.css') }}">
    <link rel="stylesheet"
        href="{{ asset('css/components.css') }}">

    <style>
        .profile-widget-picture {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .profile-text {
            margin-bottom: 0;
            padding: 0.5rem 0;
        }

        /* Perbaikan untuk select yang terpotong */
        select.form-control {
            /* height: auto !important; Override height default */
            white-space: normal; /* Memungkinkan text wrap */
            min-height: 38px; /* Minimum height untuk select */
        }

        .select-container {
            position: relative;
            width: 100%;
            z-index: 1000; /* Memastikan dropdown muncul di atas element lain */
        }

        /* Memastikan dropdown options tidak terpotong */
        select.form-control option {
            padding: 8px;
            white-space: normal;
        }

        /* Memperbaiki container form-group */
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
            overflow: visible; /* Memungkinkan dropdown keluar dari container */
        }
        /* */

        .select2-container {
            width: 100% !important;
        }

        .select2-selection {
            height: 42px !important;
            line-height: 42px !important;
        }

        .select2-container--default .select2-selection--single {
            border: 1px solid #e4e6fc !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 42px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px !important;
        }
    </style>
@endpush


@section('Dashboard')

    @include('admin.dashboard.header')
    @include('admin.dashboard.sidebar')

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Profile</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('Dashboard.General') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Profile</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Hi, {{ Auth::user()->name }}!</h2>
                <p class="section-lead">
                    Kelola informasi profil Anda pada halaman ini.
                </p>

                <div class="row mt-sm-4">
                    <!-- Card Profile -->
                    <div class="col-12 col-md-12 col-lg-5">
                        <div class="card profile-widget">
                            <div class="profile-widget-header">
                                <img alt="image" src="{{ asset(Auth::user()->image ?? 'img/avatar/avatar-1.png') }}"
                                    class="rounded-circle profile-widget-picture">
                                <div class="profile-widget-items">
                                    <div class="profile-widget-item">
                                        <div class="profile-widget-item-label">Pengajuan Surat</div>
                                        <div class="profile-widget-item-value">12</div>
                                    </div>
                                    <div class="profile-widget-item">
                                        <div class="profile-widget-item-label">Selesai</div>
                                        <div class="profile-widget-item-value">10</div>
                                    </div>
                                    <div class="profile-widget-item">
                                        <div class="profile-widget-item-label">Proses</div>
                                        <div class="profile-widget-item-value">2</div>
                                    </div>
                                </div>
                            </div>
                            <div class="profile-widget-description">
                                <div class="profile-widget-name">{{ Auth::user()->name }}<div
                                        class="text-muted d-inline font-weight-normal">
                                        <div class="slash"></div> {{ Auth::user()->pekerjaan }}
                                    </div>
                                </div>
                                {!! Auth::user()->description !!}
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Profile -->
                    <div class="col-12 col-md-12 col-lg-7">
                        <div class="card">
                            <div class="card-header">
                                <h4>Informasi Profile</h4>
                                <div class="card-header-action">
                                    <a href="#" class="btn btn-icon btn-primary" id="btn-edit"><i class="fas fa-edit"></i></a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="form-profile" action="{{ route('Profile.update', Auth::id()) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>NIK</label>
                                            <p class="profile-text">{{ Auth::user()->nik ?? '-' }}</p>
                                            <input type="text" name="nik" class="form-control profile-input d-none" value="{{ Auth::user()->nik }}" maxlength="16">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Nama Lengkap</label>
                                            <p class="profile-text">{{ Auth::user()->name }}</p>
                                            <input type="text" name="name" class="form-control profile-input d-none" value="{{ Auth::user()->name }}" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Email</label>
                                            <p class="profile-text">{{ Auth::user()->email }}</p>
                                            <input type="email" name="email" class="form-control profile-input d-none" value="{{ Auth::user()->email }}" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>No. Telepon</label>
                                            <p class="profile-text">{{ Auth::user()->telp ?? '-' }}</p>
                                            <input type="tel" name="telp" class="form-control profile-input d-none" value="{{ Auth::user()->telp }}">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label>Tempat Lahir</label>
                                            <p class="profile-text">{{ Auth::user()->tempat_lahir ?? '-' }}</p>
                                            <input type="text" name="tempat_lahir" class="form-control profile-input d-none" value="{{ Auth::user()->tempat_lahir }}">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Tanggal Lahir</label>
                                            <p class="profile-text">{{ Auth::user()->tanggal_lahir ? date('d/m/Y', strtotime(Auth::user()->tanggal_lahir)) : '-' }}</p>
                                            <input type="date" name="tanggal_lahir" class="form-control profile-input d-none" value="{{ Auth::user()->tanggal_lahir }}">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Jenis Kelamin</label>
                                            <p class="profile-text">{{ Auth::user()->gender == 'L' ? 'Laki-laki' : (Auth::user()->gender == 'P' ? 'Perempuan' : '-') }}</p>
                                            <select name="gender" class="form-control profile-input d-none">
                                                <option value="">Pilih Jenis Kelamin</option>
                                                <option value="L" {{ Auth::user()->gender == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                                <option value="P" {{ Auth::user()->gender == 'P' ? 'selected' : '' }}>Perempuan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Provinsi</label>
                                            <p class="profile-text">{{ Auth::user()->provinsi ?? '-' }}</p>
                                            <div class="profile-input d-none">
                                                <select name="provinsi" id="provinsi" class="form-control select2" placeholder="Pilih Provinsi">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                            <input type="hidden" name="provinsi_text" id="provinsi-text" value="{{ Auth::user()->provinsi }}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Kode Pos</label>
                                            <p class="profile-text">{{ Auth::user()->kode_pos ?? '-' }}</p>
                                            <input type="text" name="kode_pos" class="form-control profile-input d-none" value="{{ Auth::user()->kode_pos }}" maxlength="5">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Kota/Kabupaten</label>
                                            <p class="profile-text">{{ Auth::user()->kota ?? '-' }}</p>
                                            <div class="profile-input d-none">
                                                <select name="kota" id="kota" class="form-control select2" placeholder="Pilih Kota/Kabupaten" disabled>
                                                    <option value="">Pilih Kota/Kabupaten</option>
                                                </select>
                                            </div>
                                            <input type="hidden" name="kota_text" id="kota-text" value="{{ Auth::user()->kota }}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Kecamatan</label>
                                            <p class="profile-text">{{ Auth::user()->kecamatan ?? '-' }}</p>
                                            <div class="profile-input d-none">
                                                <select name="kecamatan" id="kecamatan" class="form-control select2" placeholder="Pilih Kecamatan" disabled>
                                                    <option value="">Pilih Kecamatan</option>
                                                </select>
                                            </div>
                                            <input type="hidden" name="kecamatan_text" id="kecamatan-text" value="{{ Auth::user()->kecamatan }}">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Kelurahan</label>
                                            <p class="profile-text">{{ Auth::user()->kelurahan ?? '-' }}</p>
                                            <div class="profile-input d-none">
                                                <select name="kelurahan" id="kelurahan" class="form-control select2" placeholder="Pilih Kelurahan" disabled>
                                                    <option value="">Pilih Kelurahan</option>
                                                </select>
                                            </div>
                                            <input type="hidden" name="kelurahan_text" id="kelurahan-text" value="{{ Auth::user()->kelurahan }}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>RW</label>
                                            <p class="profile-text">{{ Auth::user()->rw ?? '-' }}</p>
                                            <input type="text" name="rw" class="form-control profile-input d-none" value="{{ Auth::user()->rw }}" maxlength="3">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>RT</label>
                                            <p class="profile-text">{{ Auth::user()->rt ?? '-' }}</p>
                                            <input type="text" name="rt" class="form-control profile-input d-none" value="{{ Auth::user()->rt }}" maxlength="3">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label>Alamat Lengkap</label>
                                            <p class="profile-text">{{ Auth::user()->address ?? '-' }}</p>
                                            <textarea name="address" class="form-control profile-input d-none" rows="3">{{ Auth::user()->address }}</textarea>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Agama</label>
                                            <p class="profile-text">{{ Auth::user()->agama ?? '-' }}</p>
                                            <div class="select-container">
                                                <select name="agama" class="form-control profile-input d-none">
                                                    <option value="">Pilih Agama</option>
                                                    <option value="Islam" {{ Auth::user()->agama == 'Islam' ? 'selected' : '' }}>Islam</option>
                                                    <option value="Kristen" {{ Auth::user()->agama == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                                    <option value="Katolik" {{ Auth::user()->agama == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                                    <option value="Hindu" {{ Auth::user()->agama == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                                    <option value="Buddha" {{ Auth::user()->agama == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                                    <option value="Konghucu" {{ Auth::user()->agama == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Status Perkawinan</label>
                                            <p class="profile-text">{{ Auth::user()->status_perkawinan ?? '-' }}</p>
                                            <div class="select-container">
                                                <select name="status_perkawinan" class="form-control profile-input d-none">
                                                    <option value="">Pilih Status</option>
                                                    <option value="Belum Kawin" {{ Auth::user()->status_perkawinan == 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                                                    <option value="Kawin" {{ Auth::user()->status_perkawinan == 'Kawin' ? 'selected' : '' }}>Kawin</option>
                                                    <option value="Cerai Hidup" {{ Auth::user()->status_perkawinan == 'Cerai Hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                                                    <option value="Cerai Mati" {{ Auth::user()->status_perkawinan == 'Cerai Mati' ? 'selected' : '' }}>Cerai Mati</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label>Pekerjaan</label>
                                            <p class="profile-text">{{ Auth::user()->pekerjaan ?? '-' }}</p>
                                            <input type="text" name="pekerjaan" class="form-control profile-input d-none" value="{{ Auth::user()->pekerjaan }}">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label>Foto Profile</label>
                                            <p class="profile-text">{{ Auth::user()->image ? 'Sudah diupload' : 'Belum upload foto' }}</p>
                                            <input type="file" name="image" class="form-control profile-input d-none">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label>Deskripsi</label>
                                            <p class="profile-text">{!! Auth::user()->description ?? '-' !!}</p>
                                            <textarea name="description" class="form-control summernote-simple profile-input d-none" rows="4">{{ Auth::user()->description }}</textarea>
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
            Copyright &copy; 2018 <div class="bullet"></div> Design By <a href="https://nauval.in/">Muhamad
                Nauval Azhar</a>
        </div>
        <div class="footer-right">
            2.3.0
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

    <!-- JS Libraies -->
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.indonesia.js') }}"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script src="{{ asset('library/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.js') }}"></script>
    <script src="{{ asset('library/owl.carousel/dist/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>


    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/index-0.js') }}"></script>
    {{-- <script src="{{ asset('js/page/index.js') }}"></script> --}}
    <script src="{{ asset('js/page/modules-chartjs.js') }}"></script>


    <script>
        $(document).ready(function() {
            // Toggle edit mode
            // Inisialisasi Select2
            // initializeSelect2();
            let select2Initialized = false;
            let summernoteInitialized = false;

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

                if (!isEditMode) {
                    // Masuk mode edit
                    initializeSelect2();
                    // loadProvinsi();
                    loadInitialData();

                    // Initialize summernote hanya jika belum diinisialisasi
                    if (!summernoteInitialized) {
                        $('.summernote-simple').summernote({
                            dialogsInBody: true,
                            minHeight: 150,
                            toolbar: [
                                ['style', ['bold', 'italic', 'underline', 'clear']],
                                ['font', ['strikethrough']],
                                ['para', ['paragraph']]
                            ]
                        });
                        summernoteInitialized = true;
                    }
                } else {
                    // Keluar mode edit
                    if (summernoteInitialized) {
                        $('.summernote-simple').summernote('destroy');
                        summernoteInitialized = false;
                    }
                }
            });

            // Inisialisasi Select2
            function initializeSelect2() {
                // Inisialisasi untuk semua select
                $('#provinsi, #kota, #kecamatan, #kelurahan').each(function() {
                    const $select = $(this);
                    const savedValue = $(`#${$select.attr('id')}-text`).val();

                    $(this).select2({
                        theme: 'bootstrap4',
                        width: '100%',
                        placeholder: 'Pilih...',
                        allowClear: true
                    });

                    // Jika ada nilai tersimpan, tambahkan sebagai option dan pilih
                    if (savedValue && savedValue !== '-') {
                        const newOption = new Option(savedValue, '1', true, true);
                        $select.append(newOption).trigger('change');
                        $select.prop('disabled', false);
                    }
                });
            }

            // Cancel edit
            $('#btn-cancel').click(function() {
                $('.profile-text').toggleClass('d-none');
                $('.profile-input').toggleClass('d-none');
                $('#form-buttons').toggleClass('d-none');
                $('#btn-edit').toggleClass('btn-primary btn-secondary');
                $('#btn-edit').find('i').toggleClass('fa-edit fa-times');

                // Reset semua select2
                // resetAllDropdowns();
                // Destroy summernote saat cancel
                if (summernoteInitialized) {
                    $('.summernote-simple').summernote('destroy');
                    summernoteInitialized = false;
                }

                // Reset form atau reload data awal jika diperlukan
                loadInitialData();
            });

            // Fungsi untuk load data provinsi
            function loadProvinsi() {
                $.ajax({
                    url: "{{ route('get.provinsi') }}",
                    method: 'GET',
                    beforeSend: function() {
                        $('#provinsi').prop('disabled', true);
                    },
                    success: function(response) {
                        $('#provinsi').empty().append('<option value="">Pilih Provinsi</option>');

                        if (Array.isArray(response)) {
                            response.forEach(function(item) {
                                const option = new Option(item.name, item.id, false, false);
                                $('#provinsi').append(option);
                            });
                        }

                        $('#provinsi').prop('disabled', false).trigger('change');

                        // Jika ada nilai yang tersimpan
                        const savedProvinsi = $('#provinsi-text').val();
                        if (savedProvinsi) {
                            const existingOption = $('#provinsi option').filter(function() {
                                return $(this).text().toLowerCase() === savedProvinsi.toLowerCase();
                            });

                            if (existingOption.length) {
                                $('#provinsi').val(existingOption.val()).trigger('change');
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading provinces:', error);
                        swal('Error', 'Gagal memuat data provinsi', 'error');
                    }
                });
            }

            // Event handlers untuk select
            $('#provinsi').on('change', function() {
                const provinsiId = $(this).val();
                const provinsiText = $(this).find("option:selected").text();
                $('#provinsi-text').val(provinsiText);

                if(provinsiId && provinsiId !== '1') { // '1' adalah value default kita untuk data dari DB
                    loadKota(provinsiId);
                } else if (provinsiId === '1') {
                    // Jika ini adalah nilai dari database, jangan reset dropdown lain
                    return;
                } else {
                    resetDropdowns('kota');
                }
            });

            // Tambahkan event handler untuk kota
            $('#kota').on('change', function() {
                const kotaId = $(this).val();
                const kotaText = $(this).find("option:selected").text();
                $('#kota-text').val(kotaText);

                if(kotaId && kotaId !== '1') {
                    loadKecamatan(kotaId);
                } else if (kotaId === '1') {
                    return;
                } else {
                    resetDropdowns('kecamatan');
                }
            });

            // Tambahkan event handler untuk kecamatan
            $('#kecamatan').on('change', function() {
                const kecamatanId = $(this).val();
                const kecamatanText = $(this).find("option:selected").text();
                $('#kecamatan-text').val(kecamatanText);

                if(kecamatanId && kecamatanId !== '1') {
                    loadKelurahan(kecamatanId);
                } else if (kecamatanId === '1') {
                    return;
                } else {
                    resetDropdowns('kelurahan');
                }
            });

            // Event handler untuk kelurahan
            $('#kelurahan').on('change', function() {
                const kelurahanText = $(this).find("option:selected").text();
                $('#kelurahan-text').val(kelurahanText);
            });

            // Fungsi untuk load data kota
            function loadKota(provinsiId) {
                $.ajax({
                    url: "{{ route('get.kota') }}",
                    method: 'GET',
                    data: { provinsi_id: provinsiId },
                    beforeSend: function() {
                        $('#kota').prop('disabled', true);
                    },
                    success: function(response) {
                        $('#kota').empty().append('<option value="">Pilih Kota/Kabupaten</option>');

                        response.forEach(function(item) {
                            $('#kota').append(new Option(item.name, item.id));
                        });

                        $('#kota').prop('disabled', false);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading cities:', error);
                        swal('Error', 'Gagal memuat data kota', 'error');
                    }
                });
            }

            // Fungsi untuk load data kecamatan
            function loadKecamatan(kotaId) {
                $.ajax({
                    url: "{{ route('get.kecamatan') }}",
                    method: 'GET',
                    data: { kota_id: kotaId },
                    beforeSend: function() {
                        $('#kecamatan').prop('disabled', true);
                    },
                    success: function(response) {
                        $('#kecamatan').empty().append('<option value="">Pilih Kecamatan</option>');

                        response.forEach(function(item) {
                            $('#kecamatan').append(new Option(item.name, item.id));
                        });

                        $('#kecamatan').prop('disabled', false);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading districts:', error);
                        swal('Error', 'Gagal memuat data kecamatan', 'error');
                    }
                });
            }

            // Fungsi untuk load data kelurahan
            function loadKelurahan(kecamatanId) {
                $.ajax({
                    url: "{{ route('get.kelurahan') }}",
                    method: 'GET',
                    data: { kecamatan_id: kecamatanId },
                    beforeSend: function() {
                        $('#kelurahan').prop('disabled', true);
                    },
                    success: function(response) {
                        $('#kelurahan').empty().append('<option value="">Pilih Kelurahan</option>');

                        response.forEach(function(item) {
                            $('#kelurahan').append(new Option(item.name, item.id));
                        });

                        $('#kelurahan').prop('disabled', false);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading villages:', error);
                        swal('Error', 'Gagal memuat data kelurahan', 'error');
                    }
                });
            }

            // Fungsi untuk load data awal
            async function loadInitialData() {
                try {
                    // Ambil nilai yang tersimpan
                    const provinsiText = $('#provinsi-text').val();
                    const kotaText = $('#kota-text').val();
                    const kecamatanText = $('#kecamatan-text').val();
                    const kelurahanText = $('#kelurahan-text').val();

                    // Load provinsi
                    await loadProvinsi();

                    // Jika ada provinsi tersimpan
                    if (provinsiText) {
                        // Cari provinsi yang cocok
                        const provinsiOption = $('#provinsi option').filter(function() {
                            return $(this).text().toLowerCase() === provinsiText.toLowerCase();
                        });

                        if (provinsiOption.length) {
                            $('#provinsi').val(provinsiOption.val()).trigger('change');

                            // Load dan set kota jika ada
                            if (kotaText) {
                                await loadKota(provinsiOption.val());
                                const kotaOption = $('#kota option').filter(function() {
                                    return $(this).text().toLowerCase() === kotaText.toLowerCase();
                                });

                                if (kotaOption.length) {
                                    $('#kota').val(kotaOption.val()).trigger('change');

                                    // Load dan set kecamatan jika ada
                                    if (kecamatanText) {
                                        await loadKecamatan(kotaOption.val());
                                        const kecamatanOption = $('#kecamatan option').filter(function() {
                                            return $(this).text().toLowerCase() === kecamatanText.toLowerCase();
                                        });

                                        if (kecamatanOption.length) {
                                            $('#kecamatan').val(kecamatanOption.val()).trigger('change');

                                            // Load dan set kelurahan jika ada
                                            if (kelurahanText) {
                                                await loadKelurahan(kecamatanOption.val());
                                                const kelurahanOption = $('#kelurahan option').filter(function() {
                                                    return $(this).text().toLowerCase() === kelurahanText.toLowerCase();
                                                });

                                                if (kelurahanOption.length) {
                                                    $('#kelurahan').val(kelurahanOption.val()).trigger('change');
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                } catch (error) {
                    console.error('Error loading initial data:', error);
                    swal('Error', 'Gagal memuat data alamat', 'error');
                }
            }

            // Fungsi untuk reset semua dropdown
            function resetDropdowns(startFrom) {
                const dropdowns = ['kota', 'kecamatan', 'kelurahan'];
                const startIndex = dropdowns.indexOf(startFrom);

                if (startIndex !== -1) {
                    for (let i = startIndex; i < dropdowns.length; i++) {
                        const $select = $(`#${dropdowns[i]}`);
                        const savedValue = $(`#${dropdowns[i]}-text`).val();

                        if (!savedValue || savedValue === '-') {
                            $select.empty()
                                .append('<option value="">Pilih...</option>')
                                .prop('disabled', true)
                                .trigger('change');
                            $(`#${dropdowns[i]}-text`).val('');
                        }
                    }
                }
            }

            $('#form-profile').submit(function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('Profile.update', Auth::id()) }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        // Disable submit button
                        $('button[type="submit"]').prop('disabled', true);
                        // Show loading state if desired
                        Swal.fire({
                            title: 'Loading...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                        // Enable submit button
                        $('button[type="submit"]').prop('disabled', false);

                        if (response.status === 'success') {
                            // Destroy summernote sebelum reload
                            if (summernoteInitialized) {
                                $('.summernote-simple').summernote('destroy');
                                summernoteInitialized = false;
                            }

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
                        // Enable submit button
                        $('button[type="submit"]').prop('disabled', false);

                        let message = 'Terjadi kesalahan saat memperbarui profile';
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
