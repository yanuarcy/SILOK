@extends('Template.template')

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

    <!-- Contact Start -->
    <div class="container-xxl py-6">
        <div class="container mt-5">
            <div class="mx-auto text-center wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <div class="d-inline-block border rounded-pill text-primary px-4 mb-3">Hubungi Kami</div>
                <h2 class="mb-5">Layanan Pengaduan dan Informasi Kelurahan Jemur Wonosari</h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-7 wow fadeInUp" data-wow-delay="0.3s">
                    <p class="text-center mb-4">Silakan gunakan formulir di bawah ini untuk mengirimkan pertanyaan, pengaduan, atau permintaan informasi. Kami akan merespons secepat mungkin pada jam kerja (Senin-Jumat, 08.00-15.00 WIB).</p>
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" placeholder="Nama Lengkap">
                                    <label for="name">Nama Lengkap</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" placeholder="Email">
                                    <label for="email">Email</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="subject" placeholder="Subject">
                                    <label for="subject">Subject</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Tinggalkan pesan disini" id="message" style="height: 150px"></textarea>
                                    <label for="message">Pesan</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3" type="submit">Kirim Pesan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->

    @include('layouts.footer')

</div>

@endsection
