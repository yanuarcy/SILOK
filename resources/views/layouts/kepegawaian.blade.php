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
                <a href="{{ route('Pegawai') }}" class="btnReadMore rounded-pill px-5">Read More</a>
            </div>
    </div>
</div>
<!-- Team End -->
