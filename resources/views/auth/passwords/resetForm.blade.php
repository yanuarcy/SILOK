@extends('Template.template')

@push('style')

    {{-- <link href="{{ Vite::asset('resources/css/style.css') }}" rel="stylesheet"> --}}

    <!-- CSS Libraries -->

    <link rel="stylesheet"
        href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet"
        href="{{ asset('css/style.css') }}">
    <link rel="stylesheet"
        href="{{ asset('css/components.css') }}">

    <style>

    </style>
@endpush

@section('Content')
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="row">
                    <div
                        class="{{ Request::is('auth-register') ? 'col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2' : 'col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4' }}">
                        <!-- Header -->
                        <div class="login-brand">
                            <img src="{{ asset('img/Logo-Silok2.png') }}"
                                alt="logo"
                                width="100"
                                height="90"
                                class="bg-white rounded-circle">
                        </div>

                        <!-- Content -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4>Reset Password</h4>
                            </div>

                            <div class="card-body">
                                <p class="text-muted">Lupa kata sandi bukan masalah besar. Isi form ini, dan Anda akan kembali mengakses akun Anda dalam waktu singkat.</p>
                                <small class="form-text text-muted">
                                    Kata sandi harus terdiri dari minimal 8 karakter dan mengandung kombinasi huruf besar, huruf kecil, angka, dan simbol untuk keamanan optimal.
                                    <br>
                                    Contoh format: "Kl1k[Ini]" (ganti dengan kata-kata pilihan Anda)
                                    <br>
                                    Tips: Gunakan frasa yang mudah Anda ingat, lalu ganti beberapa huruf dengan angka dan simbol. Misalnya, "I love coffee!" bisa menjadi "1L0ve[C0ffee]!"
                                </small>
                                <form method="POST" action="{{ route('password.update') }}">
                                    @csrf

                                    <input type="hidden" name="token" value="{{ $token }}">
                                    <input type="hidden" name="email" value="{{ $email }}">

                                    <div class="form-group">
                                        <label for="password">New Password</label>
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

                                    </div>

                                    <div class="form-group">
                                        <label for="password-confirm">Confirm Password</label>
                                        <div class="input-group">
                                            <input id="password-confirm"
                                                type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password_confirmation"
                                                tabindex="2"
                                                required>

                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" id="togglePassword2">
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
                                        <button type="submit"
                                            id="resetButton"
                                            class="btn btn-primary btn-lg btn-block"
                                            tabindex="4">
                                            Reset Password
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="simple-footer">
                            Copyright &copy; Silok 2024
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('js/stisla.js') }}"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>

    <!-- Custom JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.querySelector('form');
            const resetButton = document.getElementById('resetButton');
            const togglePassword = document.querySelector('#togglePassword');
            const togglePassword2 = document.querySelector('#togglePassword2');
            const password = document.querySelector('#password');
            const password_confirm = document.querySelector('#password-confirm');

            togglePassword.addEventListener('click', function (e) {
                // toggle the type attribute
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                // toggle the eye slash icon
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });

            togglePassword2.addEventListener('click', function (e) {
                // toggle the type attribute
                const type = password_confirm.getAttribute('type') === 'password' ? 'text' : 'password';
                password_confirm.setAttribute('type', type);
                // toggle the eye slash icon
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });

            loginForm.addEventListener('submit', function(e) {
                // Mencegah form dari submit default
                e.preventDefault();

                // Mengubah tombol menjadi state progress
                resetButton.classList.add('btn-progress');
                resetButton.disabled = true;

                // Mengirim form
                this.submit();
            });
        });
    </script>

    <!-- JS Libraies -->
    <script src="{{ asset('library/jquery.pwstrength/jquery.pwstrength.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/auth-register.js') }}"></script>
@endpush
