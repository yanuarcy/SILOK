@extends('Template.template')

@push('style')
<link href="{{ Vite::asset('resources/css/style.css') }}" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .rating-card {
        border: 2px solid #e9ecef;
        border-radius: 15px;
        padding: 20px;
        margin: 10px 0;
        transition: all 0.3s ease;
        cursor: pointer;
        background: #fff;
        position: relative;
        overflow: hidden;
    }

    .rating-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .rating-card.selected {
        border-color: #20c997;
        background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
        color: white;
        box-shadow: 0 8px 25px rgba(32, 201, 151, 0.3);
    }

    .rating-card .rating-icon {
        font-size: 3rem;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }

    .rating-card.selected .rating-icon {
        transform: scale(1.1);
        animation: bounce 0.6s ease;
    }

    .rating-card input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .survey-form {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .form-floating-custom {
        position: relative;
        margin-bottom: 25px;
    }

    .form-floating-custom .form-control {
        border: 2px solid #e9ecef;
        border-radius: 15px;
        padding: 20px 15px 8px 15px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: rgba(255,255,255,0.9);
    }

    .form-floating-custom .form-control:focus {
        border-color: #20c997;
        box-shadow: 0 0 0 0.2rem rgba(32, 201, 151, 0.25);
        background: #fff;
    }

    .form-floating-custom label {
        position: absolute;
        top: 50%;
        left: 15px;
        transform: translateY(-50%);
        color: #6c757d;
        font-size: 16px;
        transition: all 0.3s ease;
        pointer-events: none;
        background: transparent;
        padding: 0 5px;
    }

    .form-floating-custom .form-control:focus + label,
    .form-floating-custom .form-control:not(:placeholder-shown) + label {
        top: 0;
        font-size: 12px;
        color: #20c997;
        background: #fff;
        padding: 0 8px;
        border-radius: 10px;
    }

    .btn-submit {
        background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
        border: none;
        border-radius: 15px;
        padding: 15px 30px;
        font-size: 18px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(32, 201, 151, 0.3);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(32, 201, 151, 0.4);
        background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
    }

    .survey-header {
        background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
        color: white;
        padding: 30px;
        border-radius: 20px 20px 0 0;
        margin: -40px -40px 30px -40px;
        text-align: center;
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: scale(1.1) translateY(0); }
        40% { transform: scale(1.1) translateY(-10px); }
        60% { transform: scale(1.1) translateY(-5px); }
    }

    .step-indicator {
        display: flex;
        justify-content: center;
        margin-bottom: 30px;
    }

    .step {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 10px;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .step.active {
        background: #20c997;
        color: white;
        transform: scale(1.1);
    }

    .step.completed {
        background: #28a745;
        color: white;
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

    <!-- SKM Start -->
    <div class="container-xxl py-6" style="background: linear-gradient(135deg, #f8f9fa 0%, #e3f2fd 100%);">
        <div class="container mt-5">
            <div class="mx-auto text-center wow fadeInUp" data-wow-delay="0.1s" style="max-width: 700px;">
                <div class="d-inline-block border rounded-pill text-primary px-4 mb-3" style="background: rgba(32, 201, 151, 0.1); border-color: #20c997;">
                    <i class="fa fa-smile-beam me-2"></i>Survey Kepuasan Masyarakat
                </div>
                <h2 class="mb-3" style="background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    Indeks Kepuasan Masyarakat
                </h2>
                <h4 class="mb-4">{{ getOrganizationName() }}</h4>
                <p class="text-muted">Berikan penilaian Anda untuk membantu kami memberikan pelayanan yang lebih baik</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="survey-form">
                        <div class="survey-header">
                            <h4 class="mb-2"><i class="fas fa-star me-2"></i>Formulir Penilaian</h4>
                            <p class="mb-0">Suara Anda sangat berarti bagi kami</p>
                        </div>

                        <!-- Step Indicator -->
                        <div class="step-indicator">
                            <div class="step active" id="step1">1</div>
                            <div class="step" id="step2">2</div>
                            <div class="step" id="step3">3</div>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 15px;">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Oops!</strong> Ada beberapa kesalahan:
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('skm.store') }}" method="POST" id="skmForm">
                            @csrf

                            <!-- Step 1: Data Diri -->
                            <div class="step-content" id="stepContent1">
                                <h5 class="mb-4 text-center"><i class="fas fa-user me-2 text-primary"></i>Data Diri</h5>

                                <div class="form-floating-custom">
                                    <input type="text"
                                           class="form-control @error('nama') is-invalid @enderror"
                                           id="nama"
                                           name="nama"
                                           value="{{ old('nama') }}"
                                           placeholder=" ">
                                    <label for="nama"><i class="fas fa-user me-2"></i>Nama Lengkap</label>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-floating-custom">
                                    <textarea class="form-control @error('alamat') is-invalid @enderror"
                                              placeholder=" "
                                              id="alamat"
                                              name="alamat"
                                              style="height: 120px; resize: none;">{{ old('alamat') }}</textarea>
                                    <label for="alamat"><i class="fas fa-map-marker-alt me-2"></i>Alamat Lengkap</label>
                                    @error('alamat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="text-center">
                                    <button type="button" class="btn btn-primary btn-lg" onclick="nextStep(2)" style="border-radius: 15px; padding: 12px 30px;">
                                        Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 2: Rating -->
                            <div class="step-content" id="stepContent2" style="display: none;">
                                <h5 class="mb-4 text-center"><i class="fas fa-star me-2 text-warning"></i>Berikan Penilaian Anda</h5>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="rating-card" onclick="selectRating('Sangat Puas', this)">
                                            <div class="text-center">
                                                <div class="rating-icon">
                                                    <i class="fas fa-grin-hearts text-success"></i>
                                                </div>
                                                <h5 class="mb-2">Sangat Puas</h5>
                                                <p class="mb-0 small">Pelayanan sangat memuaskan</p>
                                            </div>
                                            <input type="radio" name="tingkat_kepuasan" value="Sangat Puas" {{ old('tingkat_kepuasan') == 'Sangat Puas' ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="rating-card" onclick="selectRating('Puas', this)">
                                            <div class="text-center">
                                                <div class="rating-icon">
                                                    <i class="fas fa-smile text-primary"></i>
                                                </div>
                                                <h5 class="mb-2">Puas</h5>
                                                <p class="mb-0 small">Pelayanan sudah baik</p>
                                            </div>
                                            <input type="radio" name="tingkat_kepuasan" value="Puas" {{ old('tingkat_kepuasan') == 'Puas' ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="rating-card" onclick="selectRating('Tidak Puas', this)">
                                            <div class="text-center">
                                                <div class="rating-icon">
                                                    <i class="fas fa-frown text-warning"></i>
                                                </div>
                                                <h5 class="mb-2">Tidak Puas</h5>
                                                <p class="mb-0 small">Perlu perbaikan</p>
                                            </div>
                                            <input type="radio" name="tingkat_kepuasan" value="Tidak Puas" {{ old('tingkat_kepuasan') == 'Tidak Puas' ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>

                                @error('tingkat_kepuasan')
                                    <div class="text-danger text-center mt-3">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror

                                <div class="text-center mt-4">
                                    <button type="button" class="btn btn-outline-secondary me-3" onclick="prevStep(1)" style="border-radius: 15px;">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali
                                    </button>
                                    <button type="button" class="btn btn-primary btn-lg" onclick="nextStep(3)" style="border-radius: 15px; padding: 12px 30px;">
                                        Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 3: Kritik & Saran -->
                            <div class="step-content" id="stepContent3" style="display: none;">
                                <h5 class="mb-4 text-center"><i class="fas fa-comments me-2 text-info"></i>Kritik & Saran</h5>

                                <div class="form-floating-custom">
                                    <textarea class="form-control @error('kritik_saran') is-invalid @enderror"
                                              placeholder=" "
                                              id="kritik_saran"
                                              name="kritik_saran"
                                              style="height: 180px; resize: none;">{{ old('kritik_saran') }}</textarea>
                                    <label for="kritik_saran"><i class="fas fa-pen me-2"></i>Berikan kritik dan saran Anda untuk pelayanan yang lebih baik</label>
                                    @error('kritik_saran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="text-center">
                                    <button type="button" class="btn btn-outline-secondary me-3" onclick="prevStep(2)" style="border-radius: 15px;">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali
                                    </button>
                                    <button type="button" class="btn-submit btn-lg" onclick="confirmSubmit()">
                                        <i class="fas fa-paper-plane me-2"></i>Kirim Survey
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- SKM End -->

    @include('layouts.footer')
</div>

<script>
function selectRating(value, element) {
    // Remove selected class from all cards
    document.querySelectorAll('.rating-card').forEach(card => {
        card.classList.remove('selected');
    });

    // Add selected class to clicked card
    element.classList.add('selected');

    // Check the radio button
    element.querySelector('input[type="radio"]').checked = true;
}

function nextStep(step) {
    // Validate current step
    if (step === 2) {
        const nama = document.getElementById('nama').value;
        const alamat = document.getElementById('alamat').value;
        if (!nama || !alamat) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Belum Lengkap',
                text: 'Mohon lengkapi data diri terlebih dahulu',
                confirmButtonText: 'OK',
                confirmButtonColor: '#20c997'
            });
            return;
        }
    }

    if (step === 3) {
        const rating = document.querySelector('input[name="tingkat_kepuasan"]:checked');
        if (!rating) {
            Swal.fire({
                icon: 'warning',
                title: 'Penilaian Belum Dipilih',
                text: 'Mohon pilih tingkat kepuasan terlebih dahulu',
                confirmButtonText: 'OK',
                confirmButtonColor: '#20c997'
            });
            return;
        }
    }

    // Hide all step contents
    document.querySelectorAll('.step-content').forEach(content => {
        content.style.display = 'none';
    });

    // Show target step content
    document.getElementById('stepContent' + step).style.display = 'block';

    // Update step indicators
    document.querySelectorAll('.step').forEach((stepEl, index) => {
        stepEl.classList.remove('active');
        if (index + 1 < step) {
            stepEl.classList.add('completed');
        } else {
            stepEl.classList.remove('completed');
        }
    });

    document.getElementById('step' + step).classList.add('active');
}

function prevStep(step) {
    // Hide all step contents
    document.querySelectorAll('.step-content').forEach(content => {
        content.style.display = 'none';
    });

    // Show target step content
    document.getElementById('stepContent' + step).style.display = 'block';

    // Update step indicators
    document.querySelectorAll('.step').forEach((stepEl, index) => {
        stepEl.classList.remove('active');
        if (index + 1 < step) {
            stepEl.classList.add('completed');
        } else {
            stepEl.classList.remove('completed');
        }
    });

    document.getElementById('step' + step).classList.add('active');
}

function confirmSubmit() {
    // Validate final step
    const kritikSaran = document.getElementById('kritik_saran').value;
    if (!kritikSaran.trim()) {
        Swal.fire({
            icon: 'warning',
            title: 'Kritik & Saran Kosong',
            text: 'Mohon isi kritik dan saran terlebih dahulu',
            confirmButtonText: 'OK',
            confirmButtonColor: '#20c997'
        });
        return;
    }

    // Show confirmation dialog
    Swal.fire({
        title: 'Konfirmasi Pengiriman',
        text: 'Apakah Anda yakin ingin mengirim survey ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#20c997',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Kirim!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Mengirim Survey...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Submit the form
            document.getElementById('skmForm').submit();
        }
    });
}

// Initialize form
document.addEventListener('DOMContentLoaded', function() {
    // Check if there's an old rating value
    const oldRating = '{{ old("tingkat_kepuasan") }}';
    if (oldRating) {
        const ratingCard = document.querySelector(`input[value="${oldRating}"]`).closest('.rating-card');
        if (ratingCard) {
            ratingCard.classList.add('selected');
        }
    }

    // Show SweetAlert for errors if any
    @if($errors->any())
        let errorMessages = '';
        @foreach($errors->all() as $error)
            errorMessages += '• {{ $error }}\n';
        @endforeach

        Swal.fire({
            icon: 'error',
            title: 'Oops! Ada Kesalahan',
            text: errorMessages,
            confirmButtonText: 'OK',
            confirmButtonColor: '#20c997',
            customClass: {
                content: 'text-start'
            }
        });
    @endif
});
</script>
@endsection
