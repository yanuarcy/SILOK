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
                    {{-- Home --}}
                    <li class="breadcrumb-item">
                        <a class="text-white" href="{{ route('Adminduk') }}" title="Home">
                            <i class="fas fa-home" style="font-size: 20px"></i>
                        </a>
                    </li>

                    {{-- Layanan --}}
                    @if($layananType)
                        <li class="breadcrumb-item">
                            <a class="text-white" href="{{ route('Adminduk.show', $layananType) }}">
                                {{ $layanan->title ?? $layananType }}
                            </a>
                        </li>
                    @endif

                    {{-- Sub Layanan --}}
                    @if($subLayananType)
                        <li class="breadcrumb-item">
                            <a class="text-white" href="{{ route('Adminduk.showSubLayanan', [$layananType, $subLayananType]) }}">
                                {{ $subLayanan->title ?? $subLayananType }}
                            </a>
                        </li>
                    @endif

                    {{-- Item Type --}}
                    @if($itemType)
                        <li class="breadcrumb-item text-white active" aria-current="page">
                            {{ $itemType }}
                        </li>
                    @endif
                </ol>
            </nav>
        </div>
    </div>
    <div class="container">
        <div class="row g-4 mt-5 justify-content-center">
            @foreach ($options as $option)
                <div class="col-md-4">
                    @php
                        $routeParams = [
                            'layanan' => $layananType,
                            'subLayanan' => $subLayananType ?? 'none',
                            'itemType' => $itemType ?? 'none',
                            'registrationType' => $option->type
                        ];
                    @endphp

                    <a href="{{ route('Adminduk.showApplicantTypes', $routeParams) }}" class="text-decoration-none">
                        <div class="card">
                            <img src="{{ asset('img/layanan/' . $option->image) }}"
                                 class="card-img-top-sub"
                                 alt="{{ $option->title }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $option->title }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        {{-- <a href="{{ route('Adminduk.showSubLayanan', [$layananType, $subLayananType]) }}" class="btn btn-primary mt-4">Kembali</a> --}}
    </div>

    @include('layouts.footer')

</div>

@endsection
