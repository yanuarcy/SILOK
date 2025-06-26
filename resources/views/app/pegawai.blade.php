@extends('Template.template')

@vite('resources/sass/app/pegawai.scss')

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

    <!-- Team Start -->
    <div class="container-xxl py-6">
        <div class="container mt-5">
            <div class="mx-auto text-center wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <div class="d-inline-block border rounded-pill text-primary px-4 mb-3">Kepegawaian</div>
                <h2 class="mb-5">Pegawai Kelurahan Jemur Wonosari</h2>
            </div>
            <div class="row g-4 equal-height-row">
                @forelse ($pegawai as $index => $employee)
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="{{ 0.1 + ($index % 4) * 0.2 }}s">
                        <div class="team-item d-flex flex-column h-100">
                            <h5>{{ $employee->user->name ?? '-' }}</h5>
                            <p class="mb-4">{{ $employee->jabatan }}</p>
                            <div class="flex-grow-1 d-flex flex-column justify-content-center">
                                @if($employee->user && $employee->user->image)
                                    @php
                                        $imagePaths = [
                                            'storage/images/pegawai/' . $employee->user->image,
                                            'images/pegawai/' . $employee->user->image,
                                            'storage/' . $employee->user->image,
                                            $employee->user->image
                                        ];
                                        $imageFound = false;
                                        $imageUrl = '';

                                        foreach ($imagePaths as $path) {
                                            if (file_exists(public_path($path))) {
                                                $imageUrl = asset($path);
                                                $imageFound = true;
                                                break;
                                            }
                                        }
                                    @endphp

                                    @if($imageFound)
                                        <img class="img-fluid rounded-circle w-100 mb-4"
                                             src="{{ $imageUrl }}"
                                             alt="{{ $employee->user->name }}"
                                             style="max-width: 150px; max-height: 150px; object-fit: cover; padding: 15px; border: 4px solid #00B98E;">
                                    @else
                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white mx-auto mb-4"
                                             style="width: 150px; height: 150px; font-size: 48px; font-weight: bold; padding: 25px; border: 4px solid #00B98E;">
                                            {{ strtoupper(substr($employee->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                    @endif
                                @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white mx-auto mb-4"
                                         style="width: 150px; height: 150px; font-size: 48px; font-weight: bold; padding: 25px; border: 4px solid #00B98E;">
                                        {{ strtoupper(substr($employee->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            <!-- Informasi Tambahan (Opsional) -->
                            @if($employee->user && $employee->user->pekerjaan)
                                <div class="employee-details mb-3">
                                    <small class="text-muted d-block">{{ $employee->user->pekerjaan }}</small>
                                </div>
                            @endif

                            <div class="d-flex justify-content-center mt-auto">
                                @if($employee->media_sosial && count($employee->media_sosial) > 0)
                                    @foreach($employee->media_sosial_links as $media)
                                        @if(!empty($media['url']))
                                            <a class="btn btn-square text-primary bg-white m-1"
                                               href="{{ $media['url'] }}"
                                               target="_blank"
                                               title="{{ $media['platform'] }}">
                                                <i class="{{ $media['icon'] }}"></i>
                                            </a>
                                        @endif
                                    @endforeach
                                @else
                                    <!-- Default social media placeholders -->
                                    <a class="btn btn-square text-primary bg-white m-1" href="#" title="Facebook">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a class="btn btn-square text-primary bg-white m-1" href="#" title="Twitter">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                    <a class="btn btn-square text-primary bg-white m-1" href="#" title="LinkedIn">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-users fa-5x text-muted opacity-50"></i>
                            </div>
                            <h4 class="text-muted">Belum ada Data Pegawai</h4>
                            <p class="text-muted">Data pegawai sedang dalam proses pembaruan.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Informasi Tambahan -->
            @if($pegawai->count() > 0)
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="text-center">
                            <div class="d-inline-block bg-light rounded p-4">
                                <div class="row text-center">
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <div class="fw-bold text-primary h5 mb-1">{{ $pegawai->where('jabatan', 'Lurah')->count() }}</div>
                                        <small class="text-muted">Lurah</small>
                                    </div>
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <div class="fw-bold text-primary h5 mb-1">{{ $pegawai->whereIn('jabatan', ['Staff', 'Tenaga Kontrak / OS'])->count() }}</div>
                                        <small class="text-muted">Staff & Tenaga Kontrak</small>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="fw-bold text-primary h5 mb-1">{{ $pegawai->count() }}</div>
                                        <small class="text-muted">Total Pegawai</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- Team End -->

    @include('layouts.footer')

</div>

<style>
/* Custom styling untuk halaman pegawai */
.team-item {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
    padding: 30px 20px;
    text-align: center;
    transition: all 0.3s ease;
    border: 1px solid #f0f0f0;
}

.team-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
}

.team-item h5 {
    color: #333;
    font-weight: 600;
    margin-bottom: 10px;
    font-size: 1.1rem;
}

.team-item p {
    color: #666;
    font-weight: 500;
    font-size: 0.95rem;
}

.team-item img {
    max-width: 150px;
    max-height: 150px;
    object-fit: cover;
    padding: 15px;
    border: 1px solid #00B98E;
    transition: all 0.3s ease;
}

.team-item:hover img {
    border-color: #007bff;
}

.btn-square {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.btn-square:hover {
    background-color: #007bff !important;
    color: white !important;
    border-color: #007bff !important;
    transform: scale(1.1);
}

.employee-details {
    border-top: 1px solid #f0f0f0;
    padding-top: 15px;
    margin-top: 15px;
}

.employee-details small {
    line-height: 1.6;
}

/* Equal height untuk cards */
.equal-height-row {
    display: flex;
    flex-wrap: wrap;
}

.equal-height-row > [class*='col-'] {
    display: flex;
    flex-direction: column;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .team-item {
        padding: 20px 15px;
    }

    .team-item img {
        max-width: 120px;
        max-height: 120px;
    }

    .team-item h5 {
        font-size: 1rem;
    }
}

/* Loading spinner customization */
#spinner {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(5px);
}

/* Stats section styling */
.bg-light {
    background-color: #f8f9fa !important;
}

.bg-light .row > div {
    padding: 0 15px;
}

.bg-light .row > div:not(:last-child) {
    border-right: 1px solid #dee2e6;
}

@media (max-width: 768px) {
    .bg-light .row > div {
        border-right: none !important;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 15px;
    }

    .bg-light .row > div:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
}
</style>
@endsection
