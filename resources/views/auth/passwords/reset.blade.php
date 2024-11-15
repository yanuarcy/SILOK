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
                                <h4>Forgot Password</h4>
                            </div>

                            <div class="card-body">
                                <p class="text-muted">We will send a link to reset your password</p>
                                <form method="POST" action="{{ route('password.email') }}">
                                    @csrf

                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input id="email"
                                            type="email"
                                            class="form-control"
                                            name="email"
                                            tabindex="1"
                                            required
                                            autofocus>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit"
                                            id="forgotButton"
                                            class="btn btn-primary btn-lg btn-block"
                                            tabindex="4">
                                            Forgot Password
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
            const forgotButton = document.getElementById('forgotButton');

            loginForm.addEventListener('submit', function(e) {
                // Mencegah form dari submit default
                e.preventDefault();

                // Mengubah tombol menjadi state progress
                forgotButton.classList.add('btn-progress');
                forgotButton.disabled = true;

                // Mengirim form
                this.submit();
            });
        });
    </script>
@endpush
