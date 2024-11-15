@extends('Template.template')

{{-- @section('title', 'General Dashboard') --}}

@push('style')
    <!-- General CSS Files -->
    <link rel="stylesheet"
        href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">

    <!-- CSS Libraries -->
    <link rel="stylesheet"
        href="{{ asset('library/bootstrap-social/bootstrap-social.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet"
        href="{{ asset('css/style.css') }}">
    <link rel="stylesheet"
        href="{{ asset('css/components.css') }}">
@endpush


@section('Content')

<div id="app">
    <section class="section">
        <div class="d-flex align-items-stretch flex-wrap">
            <div class="col-lg-4 col-md-6 col-12 order-lg-1 min-vh-100 order-2 bg-white">
                <div class="m-3 p-4">
                    <img src="{{ asset('img/Logo-Silok2.png') }}"
                        alt="logo"
                        width="100"
                        height="90"
                        class="shadow-light rounded-circle mb-5 mt-2">
                    <h4 class="text-dark font-weight-normal">Daftar <span class="font-weight-bold">Silok</span>
                    </h4>
                    <p class="text-muted">Daftarkan diri Anda untuk mengakses layanan kelurahan secara online.</p>
                    <form method="POST"
                        action="{{ route('register') }}"
                        class="needs-validation"
                        novalidate="">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input id="name"
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                name="name"
                                value="{{ old('name') }}"
                                tabindex="1"
                                required
                                autocomplete="name"
                                autofocus>
                            <div class="invalid-feedback">
                                Mohon isi nama anda
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email"
                                type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                name="email"
                                value="{{ old('email') }}"
                                tabindex="1"
                                required
                                autocomplete="email"
                                autofocus>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input id="username"
                                type="text"
                                class="form-control @error('username') is-invalid @enderror"
                                name="username"
                                value="{{ old('username') }}"
                                tabindex="1"
                                required
                                autocomplete="username"
                                autofocus>
                            <div class="invalid-feedback">
                                Mohon isi username anda
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="d-block">
                                <label for="telp"
                                    class="control-label">Nomer Handphone / Ponsel</label>
                            </div>
                            <input id="telp"
                                type="telp"
                                class="form-control @error('telp') is-invalid @enderror"
                                name="telp"
                                value="{{ old('telp') }}"
                                tabindex="2"
                                required>
                            <div class="invalid-feedback">
                                Mohon isi nomer handphone anda
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="d-block">
                                <label for="password"
                                    class="control-label">Password</label>
                            </div>
                            <div class="input-group">
                                <input id="password"
                                       type="password"
                                       class="form-control pwstrength @error('password') is-invalid @enderror"
                                       data-indicator="pwindicator"
                                       name="password"
                                       tabindex="2"
                                       required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div id="pwindicator"
                                class="pwindicator">
                                <div class="bar"></div>
                                <div class="label"></div>
                            </div>
                            <small class="form-text text-muted">
                                Password harus mengandung huruf besar, huruf kecil, angka, dan simbol. Min 8 kata
                            </small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox"
                                    name="terms"
                                    class="custom-control-input"
                                    tabindex="3"
                                    id="terms">
                                <label class="custom-control-label"
                                    for="terms">Saya menyetujui syarat dan ketentuan SILOK</label>
                            </div>
                        </div>

                        <div class="form-group text-right">
                            <button type="submit"
                                id="registerButton"
                                class="btn btn-primary btn-lg btn-icon icon-right"
                                tabindex="4">
                                Daftar
                            </button>
                        </div>

                        <div class="mt-5 text-center">
                            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
                        </div>
                    </form>

                    <div class="text-small mt-5 text-center">
                        Copyright &copy; 2024 Silok
                        <div class="mt-2">
                            <a href="#">Kebijakan Privasi</a>
                            <div class="bullet"></div>
                            <a href="#">Syarat dan Ketentuan</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-12 order-lg-2 min-vh-100 background-walk-y position-relative overlay-gradient-bottom order-1"
                data-background="{{ asset('img/unsplash/Surabaya.jpg') }}">
                <div class="absolute-bottom-left index-2">
                    <div class="text-white p-5 pb-2">
                        <div class="mb-5 pb-3">
                            <?php
                                date_default_timezone_set('Asia/Jakarta'); // Ganti dengan timezone yang sesuai
                                function getGreeting() {
                                    $hour = (int)date('H'); // Get current hour in 24-hour format

                                    if ($hour >= 5 && $hour < 12) {
                                        return "Good Morning";
                                    } elseif ($hour >= 12 && $hour < 17) {
                                        return "Good Afternoon";
                                    } elseif ($hour >= 17 && $hour < 21) {
                                        return "Good Evening";
                                    } else {
                                        return "Good Night";
                                    }
                                }
                            ?>
                            <h1 class="display-4 font-weight-bold mb-2" style="color: white;"><?php echo getGreeting(); ?></h1>
                            <h5 class="font-weight-normal text-muted-transparent">Surabaya, Indonesia</h5>
                        </div>
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
    <script src="{{ asset('library/popper.js/dist/umd/popper.js') }}"></script>
    <script src="{{ asset('library/tooltip.js/dist/umd/tooltip.js') }}"></script>
    <script src="{{ asset('library/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('library/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('js/stisla.js') }}"></script>

    <!-- JS Libraies -->
    <script src="{{ asset('library/jquery.pwstrength/jquery.pwstrength.min.js') }}"></script>


    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/auth-register.js') }}"></script>

    <!-- Custom JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.querySelector('form');
            const loginButton = document.getElementById('registerButton');
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#password');

            togglePassword.addEventListener('click', function (e) {
                // toggle the type attribute
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                // toggle the eye slash icon
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });

            loginForm.addEventListener('submit', function(e) {
                // Mencegah form dari submit default
                e.preventDefault();

                // Mengubah tombol menjadi state progress
                loginButton.classList.add('btn-progress');
                loginButton.disabled = true;

                // Mengirim form
                this.submit();
            });
        });
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
