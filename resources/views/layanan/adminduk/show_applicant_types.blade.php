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
                    <li class="breadcrumb-item">
                        <a class="text-white" href="{{ route('Adminduk.show', $layananType) }}">
                            {{ $layanan->title ?? Str::title(str_replace('_', ' ', $layananType)) }}
                        </a>
                    </li>

                    {{-- Sub Layanan jika ada --}}
                    @if(isset($subLayananType) && $subLayananType !== 'none')
                        <li class="breadcrumb-item">
                            <a class="text-white" href="{{ route('Adminduk.showSubLayanan', [$layananType, $subLayananType]) }}">
                                {{ $subLayanan->title ?? Str::title(str_replace('_', ' ', $subLayananType)) }}
                            </a>
                        </li>
                    @endif

                    {{-- Item Type jika ada --}}
                    @if(isset($itemType) && $itemType !== 'none')
                        <li class="breadcrumb-item">
                            <a class="text-white" href="{{ route('Adminduk.showRegistrationOptions', [$layananType, $subLayananType ?? 'none', $itemType]) }}">
                                {{ Str::title(str_replace('-', ' ', $itemType)) }}
                            </a>
                        </li>
                    @endif

                    {{-- Registration Option --}}
                    <li class="breadcrumb-item active text-white">
                        {{ $registrationOption->title }}
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="container">
        {{-- <h1>{{ $item['title'] }}</h1> --}}
        {{-- <h1>{{ $registrationOption['title'] }}</h1> --}}

        <div class="row g-4 mt-5 justify-content-center">
            @foreach ($applicantTypes as $type)
                <div class="col-md-4">
                    <a href="{{ route('Adminduk.showRegistrationForm', [
                        'layanan' => $layananType,
                        'subLayanan' => $subLayananType ?? 'none',
                        'itemType' => $itemType ?? 'none',
                        'registrationType' => $registrationOption->type,
                        'applicantType' => $type->type
                    ]) }}" class="text-decoration-none">
                        <div class="card">
                            <img src="{{ asset('img/layanan/' . $type->image) }}"
                                 class="card-img-top-sub"
                                 alt="{{ $type->title }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $type->title }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        {{-- <a href="{{ route('Adminduk.showRegistrationOptions', [$layananType, $subLayananType, $itemType]) }}" class="btn btn-primary mt-4">Kembali ke {{ $itemType }}</a> --}}
    </div>

    @include('layouts.footer')

</div>
@endsection
