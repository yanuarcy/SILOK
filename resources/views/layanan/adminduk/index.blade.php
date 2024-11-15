@extends('Template.template')

@vite('resources/sass/layanan/adminduk/index.scss')

@push('style')

<link href="{{ Vite::asset('resources/css/style.css') }}" rel="stylesheet">

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

    <div class="container">
        <h1 class="text-center mb-2">Layanan Adminduk SILOK</h1>
        <p class="text-center mb-5">Sistem Informasi Layanan Online Kelurahan (SILOK) menyediakan berbagai layanan administrasi kependudukan secara efisien dan mudah diakses.</p>

        <div class="alert alert-info mb-4" role="alert">
            <h4 class="alert-heading">Selamat Datang di Layanan Adminduk SILOK!</h4>
            <p>Di bawah ini Anda akan menemukan berbagai jenis layanan yang kami sediakan. Setiap layanan dirancang untuk memudahkan proses administrasi kependudukan Anda. Silakan pilih layanan yang Anda butuhkan.</p>
            <hr>
            <p class="mb-0">Jika Anda memerlukan bantuan, jangan ragu untuk menghubungi tim kami melalui fitur Konsultasi.</p>
        </div>

        <div class="row g-4">
            @foreach ($layananList as $key => $layanan)
                <div class="col-md-4">
                    <a href="{{ route('Adminduk.show', $layanan->slug) }}" class="text-decoration-none">
                        <div class="layanan-item">
                            <div class="layanan-image-wrapper">
                                <img src="{{ asset('img/layanan/' . $layanan['image']) }}" alt="{{ $layanan['title'] }}" class="layanan-image">
                            </div>
                            <h5>{{ $layanan['title'] }}</h5>
                            <p>{{ $layanan['description'] }}</p>
                            <small class="text-muted">{{ $layanan['small'] }}</small>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-5">
            <p class="lead mb-3">SILOK hadir untuk memudahkan warga dalam mengakses layanan kelurahan secara efisien dan transparan.</p>
            <p class="mb-4">Belum yakin tentang persyaratan yang dibutuhkan? Jangan khawatir!</p>
            <a href="#persyaratan" class="btn btn-outline-primary">Cek Persyaratan Pemohon</a>
        </div>
    </div>

    @include('layouts.footer')

</div>

@endsection
