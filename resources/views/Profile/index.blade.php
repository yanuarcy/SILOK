@extends('Template.template')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css" rel="stylesheet">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">

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

        .rt-rw-highlight {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%) !important;
            border: 2px solid #ffc107 !important;
            border-radius: 12px !important;
            padding: 15px !important;
            position: relative !important;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3) !important;
        }

        .rt-rw-highlight::before {
            content: "WAJIB DIISI";
            position: absolute;
            top: -8px;
            right: 10px;
            background: #ffc107;
            color: #212529;
            font-size: 10px;
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .rt-rw-highlight label {
            font-weight: bold !important;
            color: #856404 !important;
            text-shadow: 1px 1px 2px rgba(255,255,255,0.8) !important;
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        @keyframes enhanced-pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.8);
                transform: scale(1);
            }
            25% {
                transform: scale(1.02);
            }
            70% {
                box-shadow: 0 0 0 15px rgba(255, 193, 7, 0);
                transform: scale(1);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(255, 193, 7, 0);
                transform: scale(1);
            }
        }

        .select-container {
            position: relative;
            width: 100%;
            z-index: 1000;
        }

        .select-container select {
            transition: all 0.3s ease;
        }

        .select-container select:focus {
            border-color: #6777ef !important;
            box-shadow: 0 0 0 0.2rem rgba(103, 119, 239, 0.25) !important;
        }

        .select-container select:disabled {
            background-color: #f8f9fa !important;
            color: #6c757d !important;
            cursor: not-allowed !important;
        }

        .ketua-rw-section {
            border-left: 4px solid #28a745;
            padding-left: 15px;
        }

        .ketua-rt-section {
            border-left: 4px solid #17a2b8;
            padding-left: 15px;
        }

        /* Tooltip for disabled options */
        .disabled-option {
            color: #6c757d !important;
            background-color: #f8f9fa !important;
            cursor: not-allowed !important;
        }

        /* Success state after selection */
        .rt-rw-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%) !important;
            border: 2px solid #28a745 !important;
            border-radius: 12px !important;
            padding: 15px !important;
            position: relative !important;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3) !important;
        }

        .rt-rw-success::before {
            content: "✓ LENGKAP";
            position: absolute;
            top: -8px;
            right: 10px;
            background: #28a745;
            color: white;
            font-size: 10px;
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .rt-rw-success label {
            font-weight: bold !important;
            color: #155724 !important;
        }

        /* Loading state for selects */
        .select-loading {
            position: relative;
        }

        .select-loading::after {
            content: '';
            position: absolute;
            right: 30px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #6777ef;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: translateY(-50%) rotate(0deg); }
            100% { transform: translateY(-50%) rotate(360deg); }
        }

        select.form-control {
            white-space: normal;
            min-height: 38px;
        }

        select.form-control option {
            padding: 8px;
            white-space: normal;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
            overflow: visible;
        }

        /* Recent Activities Scrollbar Styles - VERTICAL SCROLL */
        .activities-container {
            max-height: 400px; /* Fixed height to show about 4 activities */
            overflow-y: auto; /* Vertical scroll when content exceeds height */
            overflow-x: hidden; /* Hide horizontal overflow */
            padding-right: 5px; /* Space for scrollbar */
        }

        .activities-list {
            width: 100%;
        }

        /* Custom scrollbar styling */
        .activities-container::-webkit-scrollbar {
            width: 6px;
        }

        .activities-container::-webkit-scrollbar-track {
            background: #f8f9fa;
            border-radius: 3px;
        }

        .activities-container::-webkit-scrollbar-thumb {
            background: #dee2e6;
            border-radius: 3px;
        }

        .activities-container::-webkit-scrollbar-thumb:hover {
            background: #adb5bd;
        }

        /* Activity items styling - always vertical display */
        .activities-list .media {
            display: block;
            width: 100%;
            margin-bottom: 15px;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 15px;
            transition: background-color 0.2s ease;
        }

        .activities-list .media:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .activities-list .media:hover {
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 10px;
            margin: -5px -10px 10px -10px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .activities-container {
                max-height: 350px;
            }

            .rt-rw-highlight {
                margin-bottom: 20px;
            }

            .rt-rw-highlight::before {
                font-size: 9px;
                padding: 1px 6px;
            }

            .select-container {
                margin-bottom: 10px;
            }
        }

        /* Custom scrollbar for select dropdowns */
        select option {
            padding: 10px;
            margin: 2px 0;
            border-radius: 4px;
        }

        /* Enhanced info alert styling */
        .alert-info {
            border-left: 4px solid #17a2b8;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-color: #17a2b8;
        }

        .alert-info h6 {
            color: #0c5460;
            margin-bottom: 10px;
        }

        .alert-info p {
            color: #0c5460;
            /* margin-bottom: 10px; */
        }

        .alert-info ol {
            padding-left: 20px;
        }

        .alert-info ol li {
            margin-bottom: 5px;
            color: #0c5460;
        }

        /* Status indicators */
        .status-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 5px;
        }

        .status-required { background-color: #dc3545; }
        .status-optional { background-color: #6c757d; }
        .status-completed { background-color: #28a745; }
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
                        <div class="row mt-sm-4">
                            <div class="col-lg-12 col-md-12 col-12 col-sm-12">
                                <div class="card profile-widget">
                                    <div class="profile-widget-header">
                                        <img alt="image" src="{{ asset(Auth::user()->image ?? 'img/avatar/avatar-1.png') }}"
                                        class="rounded-circle profile-widget-picture">
                                        <div class="d-flex justify-content-end align-items-center mb-2">
                                            {{-- <h6 class="mb-0">Statistik Dokumen</h6> --}}
                                            <button type="button" class="btn btn-sm btn-outline-primary" id="refresh-stats" title="Refresh Statistik">
                                                <i class="fas fa-sync-alt"></i> Refresh Data
                                            </button>
                                        </div>
                                        <!-- Grid Statistik 2x2 -->
                                        <div class="row no-gutters">
                                            <div class="col-6 mb-3">
                                                <div class="profile-widget-item text-center">
                                                    <div class="profile-widget-item-label text-black-50 small" style="font-weight: bold;">Pengajuan Surat</div>
                                                    <div class="profile-widget-item-value"
                                                        style="background-color: #6777ef; margin: 0 10px; padding: 8px; border-radius: 12px; color: black; font-weight: bold; font-size: 18px;"
                                                        id="total-pengajuan">
                                                        {{ $userStats['total_pengajuan'] }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6 mb-3">
                                                <div class="profile-widget-item text-center">
                                                    <div class="profile-widget-item-label text-black-50 small" style="font-weight: bold;">Selesai</div>
                                                    <div class="profile-widget-item-value"
                                                        style="background-color: #28a745; margin: 0 10px; padding: 8px; border-radius: 12px; color: black; font-weight: bold; font-size: 18px;"
                                                        id="total-selesai">
                                                        {{ $userStats['selesai'] }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="profile-widget-item text-center">
                                                    <div class="profile-widget-item-label text-black-50 small" style="font-weight: bold;">Proses</div>
                                                    <div class="profile-widget-item-value"
                                                        style="background-color: #ffc107; margin: 0 10px; padding: 8px; border-radius: 12px; color: #212529; font-weight: bold; font-size: 18px;"
                                                        id="total-proses">
                                                        {{ $userStats['proses'] }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="profile-widget-item text-center">
                                                    <div class="profile-widget-item-label text-black-50 small" style="font-weight: bold;">Ditolak</div>
                                                    <div class="profile-widget-item-value"
                                                        style="background-color: #dc3545; margin: 0 10px; padding: 8px; border-radius: 12px; color: white; font-weight: bold; font-size: 18px;"
                                                        id="total-ditolak">
                                                        {{ $userStats['ditolak'] }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="profile-widget-description">
                                        <div class="profile-widget-name"><h5>( {{ Auth::user()->role }} {{ Auth::user()->role === 'Ketua RT' ? Auth::user()->rt : (Auth::user()->role === 'Ketua RW' ? Auth::user()->rw : '') }}  )</h5></div>
                                        <div class="profile-widget-name">{{ Auth::user()->name }}
                                            <div class="text-muted d-inline font-weight-normal">
                                                <div class="slash"></div> {{ Auth::user()->pekerjaan }}
                                            </div>
                                        </div>
                                        {!! Auth::user()->description !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-sm-4">
                            <div class="col-lg-12 col-md-12 col-12 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Recent Activities</h4>
                                        <div class="card-header-action">
                                            <button type="button" class="btn btn-sm btn-outline-primary" id="refresh-activities" title="Refresh Activities">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <!-- Activities Container with Fixed Height and Scroll -->
                                        <div class="activities-container">
                                            <ul class="list-unstyled list-unstyled-border activities-list" id="activities-list">
                                                <!-- Activities will be loaded here -->
                                                <li class="media" id="loading-activities">
                                                    <div class="media-body text-center">
                                                        <i class="fas fa-spinner fa-spin"></i> Loading activities...
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>

                                        <!-- View All Button Container -->
                                        <div class="pt-3 pb-1 text-center border-top" id="view-all-container" style="display: none;">
                                            <!-- Button will be dynamically updated based on user role -->
                                        </div>

                                        <!-- No Activities Message -->
                                        <div class="pt-1 pb-1 text-center" id="no-activities" style="display: none;">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                                <p>Belum ada aktivitas terbaru</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

                                        <!-- RT/RW Section with Highlighting -->
                                        @php
                                            $isRTRWRole = in_array(Auth::user()->role, ['Ketua RT', 'Ketua RW']);
                                            $needsRWHighlight = $isRTRWRole && empty(Auth::user()->rw);
                                            $needsRTHighlight = Auth::user()->role === 'Ketua RT' && empty(Auth::user()->rt);
                                        @endphp

                                        <div class="form-group col-md-3 {{ $needsRWHighlight ? 'rt-rw-highlight pulse-animation' : '' }}" id="rw-section">
                                            <label>RW
                                                @if($isRTRWRole)<span class="text-danger">*</span>@endif
                                            </label>
                                            <p class="profile-text">{{ Auth::user()->rw ? 'RW ' . Auth::user()->rw : '-' }}</p>
                                            <div class="profile-input d-none">
                                                <div class="select-container">
                                                    <select name="rw" id="rw-select" class="form-control">
                                                        <option value="">Pilih RW</option>
                                                        @if(isset($availableRW))
                                                            @foreach($availableRW as $rw)
                                                                <option value="{{ $rw['value'] }}"
                                                                    data-rt-count="{{ $rw['rt_count'] }}"
                                                                    {{ Auth::user()->rw == $rw['value'] ? 'selected' : '' }}>
                                                                    {{ $rw['label'] }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3 {{ $needsRTHighlight ? 'rt-rw-highlight pulse-animation' : '' }}" id="rt-section">
                                            <label>RT
                                                @if(Auth::user()->role === 'Ketua RT')<span class="text-danger">*</span>@endif
                                            </label>
                                            <p class="profile-text">{{ Auth::user()->rt ? 'RT ' . Auth::user()->rt : '-' }}</p>
                                            <div class="profile-input d-none">
                                                <div class="select-container">
                                                    <select name="rt" id="rt-select" class="form-control" disabled>
                                                        <option value="">Pilih RT</option>
                                                        <!-- RT options will be loaded dynamically based on RW selection -->
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($isRTRWRole)
                                        <div class="row profile-input d-none">
                                            <div class="col-12">
                                                <div class="alert alert-info">
                                                    <h6><i class="fas fa-info-circle"></i> Informasi Penting</h6>
                                                    @if(Auth::user()->role === 'Ketua RW')
                                                        <p class="mb-2">Sebagai <strong>Ketua RW</strong>, Anda wajib mengisi informasi RW untuk dapat melakukan proses persetujuan dokumen-dokumen pelayanan.</p>
                                                        <p class="mb-0"><small><strong>Catatan:</strong> Setiap RW hanya dapat dipimpin oleh satu Ketua RW. RW yang sudah dipilih oleh Ketua RW lain tidak akan muncul dalam pilihan.</small></p>
                                                    @elseif(Auth::user()->role === 'Ketua RT')
                                                        <p class="mb-2">Sebagai <strong>Ketua RT</strong>, Anda wajib mengisi informasi RW dan RT untuk dapat melakukan proses persetujuan dokumen-dokumen pelayanan.</p>
                                                        <p class="mb-1"><small><strong>Langkah:</strong></small></p>
                                                        <ol class="mb-2" style="font-size: 0.9em;">
                                                            <li>Pilih RW terlebih dahulu</li>
                                                            <li>Kemudian pilih RT yang tersedia di RW tersebut</li>
                                                        </ol>
                                                        <p class="mb-0"><small><strong>Catatan:</strong> Setiap kombinasi RW-RT hanya dapat dipimpin oleh satu Ketua RT. RW yang sudah dipimpin oleh Ketua RW tidak dapat dipilih oleh Ketua RT.</small></p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif

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

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/index-0.js') }}"></script>
    <script src="{{ asset('js/page/modules-chartjs.js') }}"></script>

    <script>
        $(document).ready(function() {
            let select2Initialized = false;
            let summernoteInitialized = false;

            // Check if we need to auto-open edit mode and focus on RT/RW
            const urlParams = new URLSearchParams(window.location.search);
            const focusField = urlParams.get('focus');

            if (focusField && (focusField === 'rt' || focusField === 'rw')) {
                // Auto-open edit mode
                setTimeout(() => {
                    $('#btn-edit').click();

                    // Focus on the appropriate field after edit mode is opened
                    setTimeout(() => {
                        if (focusField === 'rw') {
                            $('#rw-select').focus();
                            // Scroll to the RW section
                            $('html, body').animate({
                                scrollTop: $('#rw-section').offset().top - 100
                            }, 1000);
                        } else if (focusField === 'rt') {
                            // For RT focus, we need to ensure RW is selected first
                            const currentRW = $('#rw-select').val();
                            if (currentRW) {
                                $('#rt-select').focus();
                                $('html, body').animate({
                                    scrollTop: $('#rt-section').offset().top - 100
                                }, 1000);
                            } else {
                                // Focus on RW first if not selected
                                $('#rw-select').focus();
                                $('html, body').animate({
                                    scrollTop: $('#rw-section').offset().top - 100
                                }, 1000);

                                // Show alert
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Pilih RW Terlebih Dahulu',
                                    text: 'Silakan pilih RW terlebih dahulu sebelum memilih RT.',
                                    confirmButtonText: 'Mengerti'
                                });
                            }
                        }
                    }, 500);
                }, 300);
            }

            // RW Selection Change Handler
            $('#rw-select').on('change', function() {
                const selectedRW = $(this).val();
                const userRole = '{{ Auth::user()->role }}';

                // Clear RT selection when RW changes
                $('#rt-select').empty().append('<option value="">Pilih RT</option>').prop('disabled', true);

                if (selectedRW) {
                    // Load available RT for selected RW
                    loadRTByRW(selectedRW);

                    // Remove highlighting from RW section if user is Ketua RW
                    if (userRole === 'Ketua RW') {
                        $('#rw-section').removeClass('rt-rw-highlight pulse-animation');
                    }

                    // Show RT section highlighting if user is Ketua RT and RT not selected
                    if (userRole === 'Ketua RT' && !$('#rt-select').val()) {
                        $('#rt-section').addClass('rt-rw-highlight pulse-animation');
                    }
                } else {
                    // Reset RT section highlighting
                    $('#rt-section').removeClass('rt-rw-highlight pulse-animation');
                }
            });

            // RT Selection Change Handler
            $('#rt-select').on('change', function() {
                const selectedRT = $(this).val();
                const userRole = '{{ Auth::user()->role }}';

                if (selectedRT && userRole === 'Ketua RT') {
                    // Remove highlighting from RT section
                    $('#rt-section').removeClass('rt-rw-highlight pulse-animation');
                }
            });

            // Function to load RT options based on selected RW
            function loadRTByRW(rwValue) {
                $.ajax({
                    url: "{{ route('profile.getRtByRw') }}",
                    method: 'GET',
                    data: { rw: rwValue },
                    beforeSend: function() {
                        $('#rt-select').prop('disabled', true);
                        $('#rt-select').empty().append('<option value="">Loading...</option>');
                    },
                    success: function(response) {
                        $('#rt-select').empty().append('<option value="">Pilih RT</option>');

                        if (response.success && response.data.length > 0) {
                            response.data.forEach(function(rt) {
                                $('#rt-select').append(new Option(rt.label, rt.value));
                            });
                            $('#rt-select').prop('disabled', false);

                            // Set current RT value if exists
                            const currentRT = '{{ Auth::user()->rt }}';
                            if (currentRT) {
                                $('#rt-select').val(currentRT);
                            }
                        } else {
                            $('#rt-select').append('<option value="">Tidak ada RT tersedia</option>');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading RT:', xhr);
                        $('#rt-select').empty().append('<option value="">Error loading RT</option>');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal memuat data RT',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                });
            }

            // Initialize RW-RT on edit mode
            function initializeRwRt() {
                const currentRW = '{{ Auth::user()->rw }}';
                const currentRT = '{{ Auth::user()->rt }}';

                // Set current RW if exists
                if (currentRW) {
                    $('#rw-select').val(currentRW).trigger('change');

                    // After RW is set, load and set RT
                    if (currentRT) {
                        setTimeout(() => {
                            loadRTByRW(currentRW);
                            setTimeout(() => {
                                $('#rt-select').val(currentRT);
                            }, 500);
                        }, 100);
                    }
                }
            }

            // Function to update stats
            function updateUserStats() {
                $.ajax({
                    url: "{{ route('profile.stats') }}",
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            $('#total-pengajuan').text(response.data.total_pengajuan);
                            $('#total-selesai').text(response.data.selesai);
                            $('#total-proses').text(response.data.proses);
                            $('#total-ditolak').text(response.data.ditolak);
                        }
                    },
                    error: function(xhr) {
                        console.log('Error updating stats:', xhr);
                    }
                });
            }

            // Update stats every 30 seconds
            setInterval(updateUserStats, 30000);

            // Update stats when page becomes visible (user switches back to tab)
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    updateUserStats();
                }
            });

            // Update stats when window gains focus
            $(window).on('focus', function() {
                updateUserStats();
            });

            $('#refresh-stats').on('click', function() {
                const btn = $(this);
                const icon = btn.find('i');

                // Add loading animation
                icon.addClass('fa-spin');
                btn.prop('disabled', true);

                $.ajax({
                    url: "{{ route('profile.stats') }}",
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            $('#total-pengajuan').text(response.data.total_pengajuan);
                            $('#total-selesai').text(response.data.selesai);
                            $('#total-proses').text(response.data.proses);
                            $('#total-ditolak').text(response.data.ditolak);

                            // Show success feedback
                            Swal.fire({
                                icon: 'success',
                                title: 'Statistik Diperbarui',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal memperbarui statistik',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    },
                    complete: function() {
                        // Remove loading animation
                        icon.removeClass('fa-spin');
                        btn.prop('disabled', false);
                    }
                });
            });

            loadRecentActivities();

            // Refresh activities button
            $('#refresh-activities').on('click', function() {
                loadRecentActivities();
            });

            // Auto refresh activities every 60 seconds
            setInterval(function() {
                loadRecentActivities();
            }, 60000);

            function loadRecentActivities() {
                const btn = $('#refresh-activities');
                const icon = btn.find('i');

                // Add loading animation
                icon.addClass('fa-spin');
                btn.prop('disabled', true);

                $.ajax({
                    url: "{{ route('profile.activities') }}",
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            updateActivitiesDisplay(response.data);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading activities:', xhr);
                    },
                    complete: function() {
                        // Remove loading animation
                        icon.removeClass('fa-spin');
                        btn.prop('disabled', false);
                    }
                });
            }

            function updateActivitiesDisplay(activities) {
                const activitiesList = $('#activities-list');
                const loadingActivities = $('#loading-activities');
                const viewAllContainer = $('#view-all-container');
                const noActivities = $('#no-activities');

                // Hide loading
                loadingActivities.hide();

                if (activities.length === 0) {
                    activitiesList.empty();
                    viewAllContainer.hide();
                    noActivities.show();
                    return;
                }

                // Clear existing activities
                activitiesList.empty();
                noActivities.hide();

                // Add activities (vertical display with scrollbar if more than container height)
                activities.forEach(function(activity) {
                    const activityHtml = createActivityHtml(activity);
                    activitiesList.append(activityHtml);
                });

                // Update View All button based on user role
                updateViewAllButton();

                // Show view all button
                viewAllContainer.show();
            }

            function updateViewAllButton() {
                const userRole = '{{ Auth::user()->role }}';
                const viewAllContainer = $('#view-all-container');

                let buttonHtml = '';

                if (userRole === 'user') {
                    // Regular users go to user-applications (their unified application view)
                    buttonHtml = `
                        <a href="{{ route('user-applications.index') }}" class="btn btn-primary btn-lg btn-round">
                            <i class="fas fa-list"></i> View All Permohonan
                        </a>
                    `;
                } else if (['Ketua RT', 'Ketua RW'].includes(userRole)) {
                    // RT/RW can access both their personal applications and approval tasks
                    buttonHtml = `
                        <div class="btn-group" role="group">
                            <a href="{{ route('user-applications.index') }}" class="btn btn-primary btn-lg btn-round">
                                <i class="fas fa-user"></i> Permohonan Saya
                            </a>
                            <a href="{{ route('user-applications.index-all') }}" class="btn btn-outline-primary btn-lg btn-round ml-2">
                                <i class="fas fa-tasks"></i> All Applications
                            </a>
                        </div>
                    `;
                } else if (['Front Office', 'Back Office', 'Lurah'].includes(userRole)) {
                    // These roles only deal with all applications for approval, not personal applications
                    buttonHtml = `
                        <a href="{{ route('user-applications.index-all') }}" class="btn btn-primary btn-lg btn-round">
                            <i class="fas fa-tasks"></i> View All Applications
                        </a>
                    `;
                } else {
                    // Admin and other roles see all applications
                    buttonHtml = `
                        <a href="{{ route('user-applications.index-all') }}" class="btn btn-primary btn-lg btn-round">
                            <i class="fas fa-list"></i> View All Applications
                        </a>
                    `;
                }

                viewAllContainer.html(buttonHtml);
            }

            function createActivityHtml(activity) {
                const userRole = '{{ Auth::user()->role }}';
                let actionColor = 'text-primary';
                let actionIcon = 'fa-file-alt';

                // Set colors and icons based on action
                if (activity.action === 'menyetujui') {
                    actionColor = 'text-success';
                    actionIcon = 'fa-check-circle';
                } else if (activity.action === 'menolak') {
                    actionColor = 'text-danger';
                    actionIcon = 'fa-times-circle';
                } else if (activity.action === 'mengajukan') {
                    actionColor = 'text-info';
                    actionIcon = 'fa-paper-plane';
                } else if (activity.action === 'status') {
                    actionColor = 'text-warning';
                    actionIcon = 'fa-clock';
                }

                let description = '';

                if (userRole === 'user') {
                    // For regular users
                    if (activity.action === 'mengajukan') {
                        description = `
                            <span class="${actionColor}">
                                <i class="fas ${actionIcon}"></i>
                                Anda mengajukan
                            </span>
                            permohonan ${activity.subject} (${activity.nomor_surat})
                        `;
                    } else if (activity.action === 'status') {
                        description = `
                            <span class="${actionColor}">
                                <i class="fas ${actionIcon}"></i>
                                Status terkini:
                            </span>
                            ${activity.subject} - ${activity.note}
                        `;
                    } else {
                        description = `
                            <span class="${actionColor}">
                                <i class="fas ${actionIcon}"></i>
                                ${activity.action.charAt(0).toUpperCase() + activity.action.slice(1)}
                            </span>
                            permohonan ${activity.subject} (${activity.nomor_surat})
                            ${activity.level ? 'oleh ' + activity.level : ''}
                        `;

                        if (activity.note) {
                            description += `<br><small class="text-muted">"${activity.note}"</small>`;
                        }
                    }
                } else {
                    // For RT/RW/Admin
                    if (activity.action === 'mengajukan') {
                        description = `
                            <span class="${actionColor}">
                                <i class="fas ${actionIcon}"></i>
                                Permohonan baru
                            </span>
                            ${activity.subject} dari
                            <strong>${activity.pemohon || 'Pemohon'}</strong>
                        `;
                    } else {
                        description = `
                            <span class="${actionColor}">
                                <i class="fas ${actionIcon}"></i>
                                ${activity.action.charAt(0).toUpperCase() + activity.action.slice(1)}
                            </span>
                            permohonan ${activity.subject} dari
                            <strong>${activity.pemohon || 'Pemohon'}</strong>
                            ${activity.level ? ' oleh ' + activity.level : ''}
                        `;

                        if (activity.note) {
                            description += `<br><small class="text-muted">"${activity.note}"</small>`;
                        }
                    }
                }

                // Add status badge if needed
                let statusBadge = '';
                if (activity.status) {
                    const statusColors = {
                        'pending_rt': 'warning',
                        'approved_rt': 'info',
                        'rejected_rt': 'danger',
                        'pending_rw': 'warning',
                        'approved_rw': 'info',
                        'rejected_rw': 'danger',
                        'pending_kelurahan': 'warning',
                        'approved_kelurahan': 'success',
                        'rejected_kelurahan': 'danger',
                        'completed': 'success'
                    };

                    const badgeColor = statusColors[activity.status] || 'secondary';
                    statusBadge = `<br><span class="badge badge-${badgeColor} mt-1">${activity.status.replace('_', ' ').toUpperCase()}</span>`;
                }

                return `
                    <li class="media">
                        <img class="rounded-circle mr-3"
                            width="50"
                            src="{{ asset('${activity.avatar}') }}"
                            alt="avatar"
                            onerror="this.src='{{ asset('img/avatar/avatar-1.png') }}'">
                        <div class="media-body">
                            <div class="text-primary float-right">${activity.time_human}</div>
                            <div class="media-title">${activity.actor.name}</div>
                            <span class="text-small text-muted">
                                ${description}
                                ${statusBadge}
                            </span>
                        </div>
                    </li>
                `;
            }

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
                    loadInitialData();
                    initializeRwRt();

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
                        Swal.fire('Error', 'Gagal memuat data provinsi', 'error');
                    }
                });
            }

            // Event handlers untuk select
            $('#provinsi').on('change', function() {
                const provinsiId = $(this).val();
                const provinsiText = $(this).find("option:selected").text();
                $('#provinsi-text').val(provinsiText);

                if(provinsiId && provinsiId !== '1') {
                    loadKota(provinsiId);
                } else if (provinsiId === '1') {
                    return;
                } else {
                    resetDropdowns('kota');
                }
            });

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
                        Swal.fire('Error', 'Gagal memuat data kota', 'error');
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
                        Swal.fire('Error', 'Gagal memuat data kecamatan', 'error');
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
                        Swal.fire('Error', 'Gagal memuat data kelurahan', 'error');
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
                    if (provinsiText && provinsiText !== '-') {
                        await loadProvinsi();

                        // Cari provinsi yang cocok
                        const provinsiOption = $('#provinsi option').filter(function() {
                            return $(this).text().toLowerCase() === provinsiText.toLowerCase();
                        });

                        if (provinsiOption.length) {
                            $('#provinsi').val(provinsiOption.val()).trigger('change');

                            // Load dan set kota jika ada
                            if (kotaText && kotaText !== '-') {
                                await loadKota(provinsiOption.val());
                                const kotaOption = $('#kota option').filter(function() {
                                    return $(this).text().toLowerCase() === kotaText.toLowerCase();
                                });

                                if (kotaOption.length) {
                                    $('#kota').val(kotaOption.val()).trigger('change');

                                    // Load dan set kecamatan jika ada
                                    if (kecamatanText && kecamatanText !== '-') {
                                        await loadKecamatan(kotaOption.val());
                                        const kecamatanOption = $('#kecamatan option').filter(function() {
                                            return $(this).text().toLowerCase() === kecamatanText.toLowerCase();
                                        });

                                        if (kecamatanOption.length) {
                                            $('#kecamatan').val(kecamatanOption.val()).trigger('change');

                                            // Load dan set kelurahan jika ada
                                            if (kelurahanText && kelurahanText !== '-') {
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
                    Swal.fire('Error', 'Gagal memuat data alamat', 'error');
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

            // Form submission
            $('#form-profile').on('submit', function(e) {
                const userRole = '{{ Auth::user()->role }}';
                const selectedRW = $('#rw-select').val();
                const selectedRT = $('#rt-select').val();

                // Validate required fields for specific roles
                if (userRole === 'Ketua RW' && !selectedRW) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'RW Wajib Diisi',
                        text: 'Sebagai Ketua RW, Anda wajib mengisi informasi RW.',
                        confirmButtonText: 'Mengerti'
                    });
                    $('#rw-select').focus();
                    return false;
                }

                if (userRole === 'Ketua RT' && (!selectedRW || !selectedRT)) {
                    e.preventDefault();
                    let message = 'Sebagai Ketua RT, Anda wajib mengisi informasi ';
                    if (!selectedRW) {
                        message += 'RW terlebih dahulu.';
                        $('#rw-select').focus();
                    } else if (!selectedRT) {
                        message += 'RT.';
                        $('#rt-select').focus();
                    }

                    Swal.fire({
                        icon: 'warning',
                        title: 'Data Tidak Lengkap',
                        text: message,
                        confirmButtonText: 'Mengerti'
                    });
                    return false;
                }

                // Continue with normal form submission
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('Profile.update', Auth::id()) }}",
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

                                // Redirect to clean URL without parameters
                                if (response.redirect_url) {
                                    window.location.href = response.redirect_url;
                                } else {
                                    // Fallback: reload with clean URL
                                    window.location.href = '{{ route("Profile.index") }}';
                                }
                            });
                        }
                    },
                    error: function(xhr) {
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

            console.log('✅ Profile page initialized successfully');
        });
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
