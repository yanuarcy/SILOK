{{-- @vite('resources/sass/layout/teams-carousel.scss')

<!-- Team Start -->
<div class="container-xxl py-6">
    <div class="container teams-section">
        <div class="mx-auto text-center wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <div class="d-inline-block border rounded-pill text-primary px-4 mb-3">Kepegawaian</div>
            <h2 class="mb-5">Pegawai Struktural Kelurahan Jemur Wonosari</h2>
        </div>

            <div class="row g-4">
                <div class="owl-carousel teams-carousel equal-height-carousel">
                        <div class="team-item">
                            <h5>MOHAMAD YASIN, SH</h5>
                            <p class="mb-4">Lurah</p>
                            <img class="img-fluid rounded-circle w-100 mb-4" src="{{ Vite::asset('resources/images/Pegawai/Lurah.png') }}" alt="">
                            <div class="d-flex justify-content-center">
                                <a class="btn btn-square text-primary bg-white m-1" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-square text-primary bg-white m-1" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-square text-primary bg-white m-1" href=""><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                        <div class="team-item">
                            <h5>NARULITA ARIYANI, SH, M.H</h5>
                            <p class="mb-4">Sekretaris Kelurahan</p>
                            <img class="img-fluid rounded-circle w-100 mb-4" src="{{ Vite::asset('resources/images/Pegawai/Sekretaris.png') }}" alt="">
                            <div class="d-flex justify-content-center">
                                <a class="btn btn-square text-primary bg-white m-1" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-square text-primary bg-white m-1" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-square text-primary bg-white m-1" href=""><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                        <div class="team-item">
                            <h5>EMA INDRA DWI NURCAHYO, SE</h5>
                            <p class="mb-4">Seksi Pemerintahan dan Pelayanan Publik</p>
                            <img class="img-fluid rounded-circle w-100 mb-4" src="{{ Vite::asset('resources/images/Pegawai/Kasi-Layanan.png') }}" alt="">
                            <div class="d-flex justify-content-center">
                                <a class="btn btn-square text-primary bg-white m-1" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-square text-primary bg-white m-1" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-square text-primary bg-white m-1" href=""><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                        <div class="team-item">
                            <h5>KUTIK KUSTIATI, SS</h5>
                            <p class="mb-4">Seksi Kesejahteraan Rakyat dan Peremkonomian</p>
                            <img class="img-fluid rounded-circle w-100 mb-4" src="{{ Vite::asset('resources/images/Pegawai/Kasi-Kesejahteraan.png') }}" alt="">
                            <div class="d-flex justify-content-center">
                                <a class="btn btn-square text-primary bg-white m-1" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-square text-primary bg-white m-1" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-square text-primary bg-white m-1" href=""><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                        <div class="team-item">
                            <h5>RATNA WIJAYAWATI KUSUMANINGSIH, S.H</h5>
                            <p class="mb-4">Seksi Ketentraman, Ketertiban dan Pembangunan</p>
                            <img class="img-fluid rounded-circle w-100 mb-4" src="{{ Vite::asset('resources/images/Pegawai/Kasi-Ketentraman.png') }}" alt="">
                            <div class="d-flex justify-content-center">
                                <a class="btn btn-square text-primary bg-white m-1" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-square text-primary bg-white m-1" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-square text-primary bg-white m-1" href=""><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('kepegawaian') }}" class="btnReadMore rounded-pill px-5">Read More</a>
            </div>
    </div>
</div>
<!-- Team End --> --}}


@vite('resources/sass/layout/teams-carousel.scss')

<!-- Team Start -->
<div class="container-xxl py-6">
    <div class="container teams-section">
        <div class="mx-auto text-center wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <div class="d-inline-block border rounded-pill text-primary px-4 mb-3">Kepegawaian</div>
            <h2 class="mb-5">Pegawai Struktural Kelurahan Jemur Wonosari</h2>
        </div>

        <div class="row g-4">
            <div class="owl-carousel teams-carousel equal-height-carousel">
                @forelse($pegawaiStruktural as $pegawai)
                    <div class="team-item">
                        <h5>{{ $pegawai->user->name ?? '-' }}</h5>
                        <p class="mb-4">{{ $pegawai->jabatan }}</p>

                        <!-- Gambar Pegawai -->
                        @if($pegawai->user && $pegawai->user->image)
                            @php
                                $imagePaths = [
                                    'storage/images/pegawai/' . $pegawai->user->image,
                                    'images/pegawai/' . $pegawai->user->image,
                                    'storage/' . $pegawai->user->image,
                                    $pegawai->user->image
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
                                     alt="{{ $pegawai->user->name }}"
                                     style="max-width: 150px; max-height: 150px; object-fit: cover; padding: 15px; border: 1px solid #00B98E;">
                            @else
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white mx-auto mb-4"
                                     style="width: 150px; height: 150px; font-size: 48px; font-weight: bold; padding: 15px; border: 1px solid #00B98E;">
                                    {{ strtoupper(substr($pegawai->user->name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                        @else
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white mx-auto mb-4"
                                 style="width: 150px; height: 150px; font-size: 48px; font-weight: bold; padding: 15px; border: 1px solid #00B98E;">
                                {{ strtoupper(substr($pegawai->user->name ?? 'U', 0, 1)) }}
                            </div>
                        @endif

                        <!-- Media Sosial -->
                        <div class="d-flex justify-content-center">
                            @if($pegawai->media_sosial && count($pegawai->media_sosial) > 0)
                                @foreach($pegawai->media_sosial_links as $media)
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
                                <!-- Default placeholder social media -->
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
                @empty
                    <div class="team-item">
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-users fa-3x text-muted opacity-50"></i>
                            </div>
                            <h5 class="text-muted">Belum ada Data Pegawai Struktural</h5>
                            <p class="text-muted">Data pegawai sedang dalam proses pembaruan.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        @if($pegawaiStruktural->count() > 0)
            <div class="text-center mt-4">
                <a href="{{ route('kepegawaian') }}" class="btnReadMore rounded-pill px-5">Read More</a>
            </div>
        @endif
    </div>
</div>
<!-- Team End -->

<style>
/* Team item styling untuk carousel */
.team-item {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
    padding: 30px 20px;
    text-align: center;
    transition: all 0.3s ease;
    border: 1px solid #f0f0f0;
    margin: 0 10px;
    height: auto;
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
    min-height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.team-item p {
    color: #666;
    font-weight: 500;
    font-size: 0.95rem;
    min-height: 40px;
}

.team-item img {
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

/* Responsive adjustments */
@media (max-width: 768px) {
    .team-item {
        padding: 20px 15px;
        margin: 0 5px;
    }

    .team-item img, .team-item > div {
        width: 120px !important;
        height: 120px !important;
    }

    .team-item h5 {
        font-size: 1rem;
        min-height: 40px;
    }
}

/* Owl Carousel equal height */
.equal-height-carousel .owl-item {
    display: flex;
}

.equal-height-carousel .team-item {
    width: 100%;
    display: flex;
    flex-direction: column;
}
</style>
