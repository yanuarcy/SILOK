@extends('Template.template')

{{-- @vite('resources/sass/app/index.scss') --}}

@push('style')

    <link href="{{ Vite::asset('resources/css/style.css') }}" rel="stylesheet">
    <!-- CSS Libraries -->
    <link rel="stylesheet"
    href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet"
    href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link href="{{ Vite::asset('resources/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">



    {{-- <link rel="stylesheet"
    href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}"> --}}

    <!-- Template CSS -->
    {{-- <link rel="stylesheet"
    href="{{ asset('css/style.css') }}"> --}}
    {{-- <link rel="stylesheet"
    href="{{ asset('css/components.css') }}"> --}}

    <style>
        .nav-link-user {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            color: white !important;
            text-decoration: none;
        }

        .nav-link-user:hover, .nav-link-user:focus {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        .nav-link-user img {
            width: 32px;
            height: 32px;
            margin-right: 0.5rem;
        }

        .dropdown-menu {
            min-width: 200px;
            padding: 0.5rem 0;
            margin-top: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .dropdown-title {
            font-size: 10px !important;
            font-weight: 600 !important;
            padding: 0.5rem 1rem;
            color: #6c757d;
            text-transform: uppercase;
            font-family: "Nunito", "Segoe UI", arial;
            letter-spacing: 2px;
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            color: #212529;
            font-size: 14px;
        }

        .dropdown-item:hover, .dropdown-item:focus {
            background-color: #f8f9fa;
        }

        .dropdown-item i {
            width: 20px;
            text-align: center;
            margin-right: 0.5rem;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .dropdown-menu:not(.show) {
            display: none;
        }
    </style>

@endpush


@section('Content')
<div class="container-xxl bg-white p-0">
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->


    @include('layouts.nav')
    @include('layouts.hero')

    <!-- About Start -->
    <div class="container-xxl py-6">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow zoomIn" data-wow-delay="0.1s">
                    <img class="img-fluid" src="{{ Vite::asset('resources/images/img/about.png') }}">
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="d-inline-block border rounded-pill text-primary px-4 mb-3">Tentang Kami</div>
                    {{-- @if(config('app.debug'))
                        <div class="alert alert-info mt-4">
                            <strong>Debug Info:</strong><br>
                            User Email: {{ $user->email ?? 'Not found' }}<br>
                            Cookie Token: {{ $cookieToken ?? 'Not set' }}<br>
                            DB Token: {{ $dbToken ?? 'Not found' }}<br>
                        </div>
                    @endif --}}
                    <h2 class="mb-4">Sistem Informasi Layanan Online Kelurahan (SILOK)</h2>
                    <p class="mb-4">SILOK adalah sistem layanan inovatif yang dirancang untuk mempermudah akses informasi dan layanan administrasi kependudukan secara online di tingkat kelurahan. Kami berkomitmen untuk mewujudkan pelayanan publik yang efisien, efektif, dan gratis bagi seluruh warga.</p>
                    <div class="row g-3 mb-4">
                        <div class="col-12 d-flex">
                            <div class="flex-shrink-0 btn-lg-square rounded-circle bg-primary">
                                <i class="fas fa-info-circle text-white"></i>
                            </div>
                            <div class="ms-4">
                                <h6>Informasi Terintegrasi</h6>
                                <span>Akses cepat ke informasi layanan dan kegiatan RT, RW, dan LPMK.</span>
                            </div>
                        </div>
                        <div class="col-12 d-flex">
                            <div class="flex-shrink-0 btn-lg-square rounded-circle bg-primary">
                                <i class="fas fa-file-alt text-white"></i>
                            </div>
                            <div class="ms-4">
                                <h6>Administrasi Kependudukan Online</h6>
                                <span>Pengurusan dokumen kependudukan secara efektif dan tanpa biaya.</span>
                            </div>
                        </div>
                    </div>
                    <a class="btn btn-primary rounded-pill py-3 px-5 mt-2" href="{{ route('About') }}">Read More</a>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->

    @include('layouts.newsletter')
    @include('layouts.service')
    @include('layouts.features')
    @include('layouts.testimonial')
    @include('layouts.kepegawaian')

    @include('layouts.footer')

</div>

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
    <script src="{{ Vite::asset('resources/lib/owlcarousel/owl.carousel.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/index-0.js') }}"></script>

    @auth
        @if(session('needs_profile_update'))
            <script>
                $(document).ready(function() {
                    // Show profile update alert for RT/RW roles
                    const profileRole = '{{ session('profile_role') }}';
                    const roleText = profileRole === 'Ketua RT' ? 'RT' : 'RW';

                    Swal.fire({
                        title: 'Lengkapi Profil Anda',
                        html: `
                            <div class="text-left">
                                <p>Sebagai <strong>${profileRole}</strong>, Anda perlu melengkapi informasi profil terlebih dahulu.</p>
                                <p>Mohon isi informasi <strong>${roleText}</strong> pada profil Anda untuk dapat menggunakan sistem dengan optimal.</p>
                                <hr>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i>
                                    Informasi ini diperlukan untuk proses persetujuan dokumen PUNTADEWA
                                </small>
                            </div>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: '<i class="fas fa-user-edit"></i> Lengkapi Profil',
                        cancelButtonText: 'Nanti Saja',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#6c757d',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        customClass: {
                            confirmButton: 'btn btn-primary me-2',
                            cancelButton: 'btn btn-secondary'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect to profile page and focus on RT/RW input
                            window.location.href = '{{ route('Profile.index') }}?focus=' + roleText.toLowerCase();
                        }
                    });
                });
            </script>
        @endif
    @endauth

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
