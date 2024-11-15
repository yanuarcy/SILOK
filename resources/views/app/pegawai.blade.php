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
                @foreach ([
                    ['name' => 'MOHAMAD YASIN, SH', 'position' => 'Lurah', 'image' => 'Lurah.png'],
                    ['name' => 'NARULITA ARIYANI, SH, M.H', 'position' => 'Sekretaris Kelurahan', 'image' => 'Sekretaris.png'],
                    ['name' => 'EMA INDRA DWI NURCAHYO, SE', 'position' => 'Seksi Pemerintahan dan Pelayanan Publik', 'image' => 'Kasi-Layanan.png'],
                    ['name' => 'KUTIK KUSTIATI, SS', 'position' => 'Seksi Kesejahteraan Rakyat dan Perekonomian', 'image' => 'Kasi-Kesejahteraan.png'],
                    ['name' => 'RATNA WIJAYAWATI KUSUMANINGSIH, S.H', 'position' => 'Seksi Ketentraman, Ketertiban dan Pembangunan', 'image' => 'Kasi-Ketentraman.png'],
                    ['name' => 'ABDUL AZIZ MUSLIM', 'position' => 'Staff', 'image' => 'staff-1.png'],
                    ['name' => 'WAHYU ABDILLAH', 'position' => 'Staff', 'image' => 'staff-2.png'],
                    ['name' => 'DANANG RUKMAROTO', 'position' => 'Staff', 'image' => 'staff-3.png'],
                    ['name' => 'AUDITA KURNIANINGRUM', 'position' => 'Tenaga Kontrak / OS', 'image' => 'OS-1.png'],
                    ['name' => 'CHAMIDAH', 'position' => 'Tenaga Kontrak / OS', 'image' => 'OS-2.png'],
                    ['name' => 'AGUS SANTOSO', 'position' => 'Tenaga Kontrak / OS', 'image' => 'OS-3.png'],
                    ['name' => 'MUCHAMAD SYAIFUL', 'position' => 'Tenaga Kontrak / OS', 'image' => 'OS-4.png'],
                    ['name' => 'IRWIN ARDYANSYAH', 'position' => 'Tenaga Kontrak / OS', 'image' => 'OS-5.png'],
                    ['name' => 'Tito Wiratama, S.Kom', 'position' => 'Tenaga Kontrak / OS', 'image' => 'OS-6.png'],
                    ['name' => 'Sutrastio, S.E.', 'position' => 'Tenaga Kontrak / OS', 'image' => 'OS-7.png'],
                    ['name' => 'ANDRI SRIWIJAYANTO', 'position' => 'Tenaga Kontrak / OS', 'image' => 'OS-8.png'],
                    ['name' => 'YANUAR PRIBADI', 'position' => 'Tenaga Kontrak / OS', 'image' => 'OS-9.png'],
                    ['name' => 'BAMBANG HERMANTO', 'position' => 'Tenaga Kontrak / OS', 'image' => 'OS-10.png'],
                    // Tambahkan 13 item lainnya di sini dengan format yang sama
                ] as $index => $employee)
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="{{ 0.1 + ($index % 4) * 0.2 }}s">
                        <div class="team-item d-flex flex-column h-100">
                            <h5>{{ $employee['name'] }}</h5>
                            <p class="mb-4">{{ $employee['position'] }}</p>
                            <div class="flex-grow-1 d-flex flex-column justify-content-center">
                                <img class="img-fluid rounded-circle w-100 mb-4" src="{{ Vite::asset('resources/images/Pegawai/' . $employee['image']) }}" alt="{{ $employee['name'] }}">
                            </div>
                            <div class="d-flex justify-content-center mt-auto">
                                <a class="btn btn-square text-primary bg-white m-1" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-square text-primary bg-white m-1" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-square text-primary bg-white m-1" href=""><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Team End -->

    @include('layouts.footer')

</div>
@endsection
