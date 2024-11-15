@extends('Template.template')

@vite('resources/sass/layanan/adminduk/registration_form.scss')
@vite('resources/sass/layanan/adminduk/show.scss')

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

                    <form action="#" method="POST">
                        @csrf

                        @if($isNewApplicant)
                            {{-- Form untuk pemohon baru --}}
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama" required>
                            </div>

                            <div class="mb-3">
                                <label for="whatsapp" class="form-label">No Whatsapp</label>
                                <input type="text" class="form-control" id="whatsapp" name="whatsapp" placeholder="Masukkan No Whatsapp" required>
                                <small class="text-danger">*Jika tidak memiliki no whatsapp, silahkan isi "-"</small>
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
                            <button type="submit" class="btn btn-primary">Daftar</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="form-footer text-center mt-3">
                {{-- <a href="{{ route('Adminduk.showRegistrationOptions', [$layananType, $subLayananType, $itemType]) }}" class="btn btn-primary">Kembali</a> --}}
            </div>
        </div>
    </div>

    @include('layouts.footer')

</div>
@endsection
