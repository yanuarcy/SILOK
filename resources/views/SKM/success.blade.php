@extends('Template.template')

@push('style')
<link href="{{ Vite::asset('resources/css/style.css') }}" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    <!-- Success Start -->
    <div class="container-xxl py-6">
        <div class="container mt-5">
            <div class="mx-auto text-center wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <div class="d-inline-block border rounded-pill text-success px-4 mb-3">Survey Berhasil</div>
                <h2 class="mb-5">Terima Kasih Atas Partisipasi Anda!</h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-7 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                        </div>
                        <h3 class="mb-3 text-success">Survey Kepuasan Masyarakat Berhasil Dikirim!</h3>
                        <p class="lead mb-4">Survey Kepuasan Masyarakat Anda telah berhasil tersimpan dalam sistem kami.</p>
                        <p class="text-muted mb-5">
                            Masukan Anda sangat berharga bagi kami untuk terus meningkatkan kualitas pelayanan
                            {{ getOrganizationName() }}. Kami akan menindaklanjuti setiap saran yang diberikan
                            untuk memberikan pelayanan yang lebih baik kepada masyarakat.
                        </p>

                        <div class="d-grid gap-2 d-md-block">
                            <a href="{{ url('/') }}" class="btn btn-primary btn-lg me-2">
                                <i class="fas fa-home me-2"></i>Kembali ke Beranda
                            </a>
                            <a href="{{ route('skm.create') }}" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-plus me-2"></i>Isi Survey Lagi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Success End -->

    @include('layouts.footer')
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show success message with SweetAlert
    Swal.fire({
        icon: 'success',
        title: 'Terima Kasih!',
        text: 'Survey Kepuasan Masyarakat Anda telah berhasil dikirim',
        confirmButtonText: 'OK',
        confirmButtonColor: '#20c997',
        timer: 5000,
        timerProgressBar: true,
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
    });
});
</script>
@endsection
