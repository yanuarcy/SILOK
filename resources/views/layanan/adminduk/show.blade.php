@extends('Template.template')

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
                    {{-- <li class="breadcrumb-item"><a class="text-white" href="{{ route('home') }}">Home</a></li> --}}
                    <li class="breadcrumb-item">
                        <a class="text-white" href="{{ route('Adminduk') }}" title="Home">
                            <i class="fas fa-home" style="font-size: 20px"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item  active" aria-current="page">{{ $layananType }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="container">
        {{-- <h1>{{ $layanan['title'] }}</h1>
        <p>{{ $layanan['description'] }}</p> --}}
        {{-- <small class="text-muted">{{ $layanan['small'] }}</small> --}}

        <div class="row g-4 mt-4 justify-content-center">
            @foreach ($layanan['subLayanans'] as $subKey => $subLayanan)
                <div class="col-md-4">
                    <a href="{{ route('Adminduk.showSubLayanan', ['layanan' => $layananType, 'subLayanan' => $subLayanan['slug']]) }}" class="text-decoration-none">
                        <div class="card">
                            <img src="{{ asset('img/layanan/' . $subLayanan['image']) }}" class="card-img-top" alt="{{ $subLayanan['title'] }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $subLayanan['title'] }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <a href="{{ route('Adminduk') }}" class="btn btn-primary mt-4">Kembali ke Daftar Layanan</a>
    </div>

    @include('layouts.footer')

</div>
@endsection
