@extends('Template.template')

@vite('resources/sass/layanan/adminduk/registration_form.scss')
@vite('resources/sass/layanan/adminduk/show.scss')

@push('style')

    <link href="{{ Vite::asset('resources/css/style.css') }}" rel="stylesheet">
    {{-- <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}"> --}}

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

    <div class="container-xxl bg-primary page-header">
        <div class="container text-center">
            <h1 class="text-white animated zoomIn mb-3">Layanan Adminduk</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center">
                    {{-- Home --}}
                    <li class="breadcrumb-item">
                        <a class="text-white" href="{{ route('Adminduk') }}" title="Home">
                            <i class="fas fa-home" style="font-size: 20px"></i>
                        </a>
                    </li>

                    {{-- Layanan --}}
                    @if(isset($layananType))
                        <li class="breadcrumb-item">
                            <a class="text-white" href="{{ route('Adminduk.show', $layananType) }}">
                                {{ $layanan->title ?? Str::title(str_replace('_', ' ', $layananType)) }}
                            </a>
                        </li>
                    @endif

                    {{-- Sub Layanan --}}
                    @if(isset($subLayananType) && $subLayananType !== 'none')
                        <li class="breadcrumb-item">
                            <a class="text-white" href="{{ route('Adminduk.showSubLayanan', [$layananType, $subLayananType]) }}">
                                {{ $subLayanan->title ?? Str::title(str_replace('_', ' ', $subLayananType)) }}
                            </a>
                        </li>
                    @endif

                    {{-- Item Type --}}
                    @if(isset($itemType) && $itemType !== 'none')
                        <li class="breadcrumb-item">
                            <a class="text-white" href="{{ route('Adminduk.showRegistrationOptions', [$layananType, $subLayananType ?? 'none', $itemType]) }}">
                                {{ Str::title(str_replace('-', ' ', $itemType)) }}
                            </a>
                        </li>
                    @endif

                    {{-- Registration Type --}}
                    @if(isset($registrationType))
                        <li class="breadcrumb-item">
                            <a class="text-white" href="{{ route('Adminduk.showApplicantTypes', [
                                'layanan' => $layananType,
                                'subLayanan' => $subLayananType ?? 'none',
                                'itemType' => $itemType ?? 'none',
                                'registrationType' => $registrationType
                            ]) }}">
                                @php
                                    $registrationTypes = [
                                        'online' => 'Daftar Online',
                                        'balai_rw' => 'Daftar Di Balai RW',
                                        'kelurahan' => 'Daftar Di Kelurahan'
                                    ];
                                @endphp
                                {{ $registrationTypes[$registrationType] ?? 'Pendaftaran di Kelurahan' }}
                            </a>
                        </li>
                    @endif

                    {{-- Applicant Type --}}
                    @if(isset($applicantType))
                        <li class="breadcrumb-item active text-white" aria-current="page">
                            {{ $applicantType === 'baru' ? 'Pemohon Baru' : 'Pemohon Lama' }}
                        </li>
                    @endif
                </ol>
            </nav>
        </div>
    </div>
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="registration-form">
            <div class="card" >
                <div class="card-body " >
                    <h2 class="card-title text-center">Pendaftaran {{ $registrationTitle }}</h2>

                    <form action="{{ route('adminduk.registration.submit') }}" method="POST" id="registrationForm">
                        @csrf

                        {{-- Hidden fields untuk data dari breadcrumb --}}
                        <input type="hidden" name="layanan_slug" value="{{ $layanan->slug ?? '' }}">
                        <input type="hidden" name="sub_layanan_slug" value="{{ $subLayanan->slug ?? '' }}">
                        <input type="hidden" name="item_type" value="{{ $itemType ?? '' }}">
                        <input type="hidden" name="registration_type" value="{{ $registrationType ?? '' }}">
                        <input type="hidden" name="applicant_type" value="{{ $applicantType ?? '' }}">

                        @if($isNewApplicant)
                            {{-- Form untuk pemohon baru --}}
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama" required>
                            </div>

                            <div class="mb-3">
                                <label for="whatsapp" class="form-label">No Whatsapp</label>
                                <input type="text" class="form-control" id="whatsapp" name="whatsapp" placeholder="08xxxxxxxxxx atau ketik '-' jika tidak punya WhatsApp" required>
                                <small>
                                    <i class="fas fa-info-circle"></i>
                                    Format: 08xxxxxxxxxx, +628xxxxxxxxxx, atau isi "<strong>-</strong>" jika tidak memiliki WhatsApp
                                </small>
                            </div>

                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan Alamat" required></textarea>
                            </div>
                        @else
                            {{-- Form untuk pemohon lama --}}
                            <div class="mb-3">
                                <label for="kode_pemohon" class="form-label">Kode Pemohon</label>
                                <input type="text" class="form-control" id="kode_pemohon" name="kode_pemohon" placeholder="Masukkan Kode Pemohon" required>
                            </div>
                        @endif

                        {{-- Jenis pengiriman hanya muncul jika registrasi online --}}
                        @if($isOnlineRegistration)
                            <div class="mb-3">
                                <label for="jenis_pengiriman" class="form-label">Jenis Pengiriman Berkas</label>
                                <select class="form-select" id="jenis_pengiriman" name="jenis_pengiriman" required>
                                    <option value="" selected disabled>Pilih jenis pengiriman anda</option>
                                    @foreach($deliveryOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary" id="submitBtn">Daftar</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="form-footer text-center mt-3">
                {{-- <a href="{{ route('Adminduk.showRegistrationOptions', [$layananType, $subLayananType, $itemType]) }}" class="btn btn-primary">Kembali</a> --}}
                {{-- <a href="{{ route('Adminduk.showRegistrationOptions', [$layananType, $subLayananType ?? 'none', $itemType ?? 'none']) }}"
           class="btn btn-secondary">Kembali</a> --}}
            </div>
        </div>
    </div>

    @include('layouts.footer')

</div>
@endsection

@push('scripts')
        <!-- General JS Scripts -->
    <script src="{{ asset('library/jquery/dist/jquery.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            console.log('jQuery loaded and ready');

            // Validasi real-time WhatsApp
            $('#whatsapp').on('input', function() {
                validateWhatsApp($(this).val());
            });

            // Auto-format WhatsApp saat blur
            $('#whatsapp').on('blur', function() {
                let value = $(this).val().trim();

                if (value !== '-' && value !== '') {
                    let cleanValue = value.replace(/[^0-9+]/g, '');

                    if (cleanValue.startsWith('08') && cleanValue.length >= 10) {
                        $(this).val(cleanValue);
                    } else if (cleanValue.startsWith('628') && !cleanValue.startsWith('+628')) {
                        $(this).val('+' + cleanValue);
                    } else if (cleanValue.startsWith('+628')) {
                        $(this).val(cleanValue);
                    }

                    validateWhatsApp($(this).val());
                }
            });

            // Form submit dengan validasi WhatsApp saja
            $('#registrationForm').on('submit', function(e) {
                console.log('Form submit started');

                const whatsappInput = $('#whatsapp');

                // Hanya validasi WhatsApp untuk pemohon baru
                if (whatsappInput.length > 0) {
                    const whatsappValue = whatsappInput.val().trim();
                    if (!validateWhatsApp(whatsappValue)) {
                        console.log('WhatsApp validation failed');
                        e.preventDefault(); // Stop form submission
                        showWhatsAppError();
                        return false;
                    }
                }

                // Jika validasi berhasil, form akan submit normal ke controller
                console.log('Validation passed, submitting to controller...');

                // Optional: Show loading state
                const btn = $('#submitBtn');
                btn.html('<i class="fas fa-spinner fa-spin"></i> Mendaftar...').prop('disabled', true);

                // Form akan submit normal ke route controller
                return true;
            });

            function validateWhatsApp(value) {
                const whatsappInput = $('#whatsapp');
                if (!whatsappInput.length) return true;

                // Reset classes
                whatsappInput.removeClass('is-invalid is-valid');

                // Dash valid
                if (value === '-') {
                    whatsappInput.addClass('is-valid');
                    return true;
                }

                // Empty invalid
                if (!value || value.length === 0) {
                    whatsappInput.addClass('is-invalid');
                    return false;
                }

                // Length check
                const cleanNumber = value.replace(/[^0-9]/g, '');
                if (cleanNumber.length < 10 || cleanNumber.length > 15) {
                    whatsappInput.addClass('is-invalid');
                    return false;
                }

                // Format check
                const phoneRegex = /^(\+628|08)[0-9]{8,12}$/;
                if (!phoneRegex.test(value)) {
                    whatsappInput.addClass('is-invalid');
                    return false;
                }

                // Letter check
                if (/[a-zA-Z]/.test(value)) {
                    whatsappInput.addClass('is-invalid');
                    return false;
                }

                whatsappInput.addClass('is-valid');
                return true;
            }

            function showWhatsAppError() {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Nomor WhatsApp Tidak Valid!',
                        html: `
                            <div style="text-align: left;">
                                <p><strong>Format nomor WhatsApp yang benar:</strong></p>
                                <ul style="margin: 10px 0; padding-left: 20px;">
                                    <li><code>08xxxxxxxxxx</code> (contoh: 082257508081)</li>
                                    <li><code>+628xxxxxxxxxx</code> (contoh: +6282257508081)</li>
                                    <li><code>-</code> (jika tidak memiliki WhatsApp)</li>
                                </ul>
                                <p style="margin-top: 15px; color: #666;">
                                    <small>Nomor harus terdiri dari 10-15 digit angka</small>
                                </p>
                            </div>
                        `,
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#3085d6',
                        width: '450px'
                    }).then(() => {
                        // Reset button state
                        const btn = $('#submitBtn');
                        btn.html('Daftar').prop('disabled', false);
                    });
                } else {
                    alert('Nomor WhatsApp tidak valid!');
                    // Reset button state
                    const btn = $('#submitBtn');
                    btn.html('Daftar').prop('disabled', false);
                }
            }
        });
    </script>
@endpush
