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
                    <h4 class="text-dark font-weight-normal">Selamat Datang di <span class="font-weight-bold">Silok</span>
                    </h4>
                    <p class="text-muted">Silakan masuk untuk mengakses layanan atau daftar jika belum memiliki akun.</p>
                    {{-- @if(config('app.debug'))
                        <div class="alert alert-info">
                            <strong>Debug Info:</strong><br>
                            Cookie Token: {{ $cookieToken ?? 'Not set' }}<br>
                            DB Token: {{ $dbToken ?? 'Not found' }}<br>
                            User Email: {{ $userEmail ?? 'Not found' }}
                        </div>
                    @endif --}}
                    <form method="POST"
                        action="{{ route('login') }}"
                        class="needs-validation"
                        novalidate="">
                        @csrf
                        @if(session('script'))
                            {!! session('script') !!}
                        @endif
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
                            <div class="d-block">
                                <label for="password"
                                    class="control-label">Password</label>
                            </div>
                            <div class="input-group">
                                <input id="password"
                                       type="password"
                                       class="form-control @error('password') is-invalid @enderror"
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
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox"
                                    name="remember"
                                    class="custom-control-input"
                                    tabindex="3"
                                    id="remember-me">
                                <label class="custom-control-label"
                                    for="remember-me">Ingat Saya</label>
                            </div>
                        </div>

                        <div class="form-group text-right">
                            <a href="{{ route('password.request') }}"
                                class="float-left mt-3">
                                Lupa Password?
                            </a>
                            {{-- <a href="#" class="btn btn-secondary">Kembali</a> --}}
                            <a href="{{ route('home') }}"
                                class="btn btn-outline-secondary btn-lg btn-icon icon-right"
                                tabindex="4">
                                Kembali
                            </a>
                            <button type="submit"
                                id="loginButton"
                                class="btn btn-primary btn-lg btn-icon icon-right"
                                tabindex="4">
                                Masuk
                            </button>
                        </div>

                        <div class="mt-5 text-center">
                            Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
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

    <!-- Page Specific JS File -->

    <!-- Custom JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.querySelector('form');
            const loginButton = document.getElementById('loginButton');
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
