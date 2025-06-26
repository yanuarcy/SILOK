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
        /* .otp-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .otp-title {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        } */
        .otp-input {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .otp-input input {
            width: 40px;
            height: 40px;
            margin: 0 5px;
            text-align: center;
            font-size: 24px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .verify-btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .verify-btn:hover {
            background-color: #0056b3;
        }
    </style>
@endpush

@section('Content')
{{-- <div class="container-xxl bg-white p-0">
    <div class="otp-container">
        <h2 class="otp-title">OTP Verification</h2>
        <form method="POST" action="{{ route('otp.verify', $id) }}">
            @csrf
            <div class="otp-input">
                <input type="text" name="otp[]" maxlength="1" pattern="[0-9]" required autofocus>
                <input type="text" name="otp[]" maxlength="1" pattern="[0-9]" required>
                <input type="text" name="otp[]" maxlength="1" pattern="[0-9]" required>
                <input type="text" name="otp[]" maxlength="1" pattern="[0-9]" required>
                <input type="text" name="otp[]" maxlength="1" pattern="[0-9]" required>
                <input type="text" name="otp[]" maxlength="1" pattern="[0-9]" required>
            </div>
            <button type="submit" class="verify-btn">Verify OTP</button>
        </form>
    </div>
</div> --}}
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
                            <h4>OTP Verification</h4>
                        </div>

                        <div class="card-body">
                            <p class="">Kode OTP telah dikirim ke email <b>{{ $email }}</b> dan silahkan buka bagian spam jika tidak ada email masuk. Silakan masukkan kode OTP di bawah ini.</p>
                            <p>Kode OTP akan kadaluarsa dalam 1 menit.</p>
                            <form id="otpForm" method="POST" action="{{ isset($id) ? route('otp.verify', $id) : route('register.verify-otp', $email) }}">
                                @csrf
                                <div class="otp-input">
                                    <input type="text" name="otp1" maxlength="1" pattern="[0-9]" required autofocus>
                                    <input type="text" name="otp2" maxlength="1" pattern="[0-9]" required>
                                    <input type="text" name="otp3" maxlength="1" pattern="[0-9]" required>
                                    <input type="text" name="otp4" maxlength="1" pattern="[0-9]" required>
                                    <input type="text" name="otp5" maxlength="1" pattern="[0-9]" required>
                                    <input type="text" name="otp6" maxlength="1" pattern="[0-9]" required>
                                </div>
                                {{-- <button type="submit" class="verify-btn">Verify OTP</button> --}}
                            </form>
                            @if(isset($id))
                                <p class="mt-3">Tidak menerima kode OTP? <a href="#" id="resendOtp" data-id="{{ $id }}">Kirim ulang kode OTP Login</a></p>
                            @else
                                <p class="mt-3">Tidak menerima kode OTP? <a href="#" id="resendOtp" data-email="{{ $email }}">Kirim ulang kode OTP Register</a></p>
                            @endif
                            <p id="timer">Waktu tersisa: <span id="countdown">60</span> detik</p>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.otp-input input');
            const form = document.getElementById('otpForm');
            const resendLink = document.getElementById('resendOtp');
            const timerElement = document.getElementById('countdown');
            const timerContainer = document.getElementById('timer');
            let countdownInterval;

            let timeLeft = {{ $timeLeft }}; // Get the time left from the server
            let showResendLink = {{ $showResendLink ? 'true' : 'false' }}; // Get the resend link state from the server

            function startCountdown() {
                if (timeLeft > 0) {
                    resendLink.style.display = 'none';
                    timerContainer.style.display = 'block';

                    countdownInterval = setInterval(() => {
                        timeLeft--;
                        timerElement.textContent = timeLeft;

                        if (timeLeft <= 0) {
                            clearInterval(countdownInterval);
                            resendLink.style.display = 'inline';
                            timerContainer.style.display = 'none';

                            Swal.fire({
                                title: 'Perhatian',
                                text: 'Kode OTP telah expired. Silakan minta kode baru.',
                                icon: 'warning',
                                showConfirmButton: true
                            });
                        }
                    }, 1000);
                } else {
                    resendLink.style.display = 'inline';
                    timerContainer.style.display = 'none';
                }
            }

            if (showResendLink) {
                resendLink.style.display = 'inline';
                timerContainer.style.display = 'none';
            } else {
                startCountdown();
            }

            inputs.forEach((input, index) => {
                input.addEventListener('input', function() {
                    if (this.value.length === this.maxLength) {
                        if (index < inputs.length - 1) {
                            inputs[index + 1].focus();
                        } else {
                            form.submit(); // Auto-submit when the last input is filled
                        }
                    }
                });

                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !this.value) {
                        if (index > 0) {
                            inputs[index - 1].focus();
                        }
                    }
                });
            });

            resendLink.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.id;
                const email = this.dataset.email;
                console.log(email);
                let url = id ? '{{ route("otp.resend", ":id") }}'.replace(':id', id)
                            : '{{ route("register.resend-otp", ":email") }}'.replace(':email', email);

                // Tampilkan loading alert tanpa timer
                Swal.fire({
                    title: 'Harap tunggu',
                    html: 'Sedang mengirim kode OTP baru...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {

                        // Close loading alert
                        Swal.close();

                        // Tampilkan success alert
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Kode OTP baru telah dikirim ke email Anda.',
                        });
                        timeLeft = 60; // Reset to 60 seconds after resend
                        startCountdown();
                        resendLink.style.display = 'none';
                        document.getElementById('timer').style.display = 'block';
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Gagal mengirim ulang OTP. Silakan coba lagi.',
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan. Silakan coba lagi.',
                    });
                });
            });
        });
        </script>
@endpush
