{{-- resources/views/layouts/testimonial.blade.php --}}
@php
// Helper function untuk avatar
function getTestimonialAvatar($testimonial) {
    // Jika ada relasi user dan ada image
    if ($testimonial->user && $testimonial->user->image) {
        $imagePaths = [
            'storage/images/pegawai/' . $testimonial->user->image,
            'images/pegawai/' . $testimonial->user->image,
            'storage/' . $testimonial->user->image,
            $testimonial->user->image
        ];

        foreach ($imagePaths as $path) {
            if (file_exists(public_path($path))) {
                return '<img class="img-fluid flex-shrink-0 rounded-circle" src="' . asset($path) . '" style="width: 50px; height: 50px; object-fit: cover;" alt="' . $testimonial->nama . '">';
            }
        }
    }

    // Fallback ke initial huruf pertama nama
    $initial = strtoupper(substr($testimonial->nama ?? 'U', 0, 1));
    return '<div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 50px; height: 50px; font-size: 18px; font-weight: bold;">' . $initial . '</div>';
}
@endphp

<style>
    .testimonial-item {
        background: #fff;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .testimonial-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .testimonial-carousel .owl-nav {
        margin-top: 30px;
        text-align: center;
    }

    .testimonial-carousel .owl-nav button {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        margin: 0 10px;
        color: #6c757d;
        transition: all 0.3s ease;
    }

    .testimonial-carousel .owl-nav button:hover {
        background: #007bff;
        color: white;
        border-color: #007bff;
    }

    .testimonial-carousel .owl-dots {
        text-align: center;
        margin-top: 20px;
    }

    .testimonial-carousel .owl-dots .owl-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #dee2e6;
        margin: 0 5px;
        transition: all 0.3s ease;
    }

    .testimonial-carousel .owl-dots .owl-dot.active {
        background: #007bff;
    }
</style>

<!-- Testimonial Start -->
<div class="container-xxl py-6">
    <div class="container">
        <div class="mx-auto text-center wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <div class="d-inline-block border rounded-pill text-primary px-4 mb-3">Kepuasan Masyarakat</div>
            <h2 class="mb-5">Indeks Kepuasan Masyarakat!</h2>

            {{-- Statistics Summary --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="text-center">
                        <h4 class="text-primary">{{ $statistics['total_surveys'] ?? 0 }}</h4>
                        <small class="text-muted">Total Survey</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <h4 class="text-success">{{ $statistics['satisfaction_percentage'] ?? 0 }}%</h4>
                        <small class="text-muted">Tingkat Kepuasan</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <h4 class="text-info">{{ $statistics['satisfaction_index'] ?? 0 }}%</h4>
                        <small class="text-muted">Indeks Kepuasan</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Testimonial Carousel --}}
        <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.1s" id="testimonial-container">
            @forelse($testimonials ?? [] as $testimonial)
            <div class="testimonial-item rounded p-4">
                <i class="fa fa-quote-left fa-2x text-primary mb-3"></i>
                <p>{{ $testimonial->kritik_saran }}</p>
                <div class="d-flex align-items-center">
                    {!! getTestimonialAvatar($testimonial) !!}
                    <div class="ps-3">
                        <h6 class="mb-1">{{ $testimonial->nama }}</h6>
                        <small>{{ $testimonial->user ? $testimonial->user->name : 'Warga' }}</small>
                        <div class="mt-1">
                            @if($testimonial->tingkat_kepuasan == 'Sangat Puas')
                                <span class="badge bg-success">{{ $testimonial->tingkat_kepuasan }}</span>
                            @elseif($testimonial->tingkat_kepuasan == 'Puas')
                                <span class="badge bg-primary">{{ $testimonial->tingkat_kepuasan }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <small class="text-muted mt-2 d-block">{{ $testimonial->created_at->format('d M Y') }}</small>
            </div>
            @empty
            {{-- Default testimonial jika belum ada data --}}
            <div class="testimonial-item rounded p-4">
                <i class="fa fa-quote-left fa-2x text-primary mb-3"></i>
                <p>Belum ada testimonial dari masyarakat. Silakan isi survey kepuasan untuk memberikan masukan.</p>
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 50px; height: 50px; font-size: 18px; font-weight: bold;">A</div>
                    <div class="ps-3">
                        <h6 class="mb-1">Admin Kelurahan</h6>
                        <small>{{ getOrganizationName() ?? 'Kelurahan' }}</small>
                    </div>
                </div>
            </div>
            @endforelse
        </div>

        {{-- Call to Action --}}
        <div class="text-center mt-5 wow fadeInUp" data-wow-delay="0.3s">
            <h5 class="mb-3">Berikan Masukan Anda!</h5>
            <p class="text-muted mb-4">Bantu kami meningkatkan pelayanan dengan memberikan penilaian dan saran</p>
            <a href="{{ route('skm.create') }}" class="btn btn-primary btn-lg rounded-pill px-5">
                <i class="fa fa-comment-dots me-2"></i>Isi Survey Kepuasan
            </a>
        </div>
    </div>
</div>
<!-- Testimonial End -->

{{-- Auto-refresh testimonial --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto refresh testimonial setiap 2 menit
    setInterval(function() {
        refreshTestimonials();
    }, 120000); // 2 menit
});

function refreshTestimonials() {
    fetch('/api/testimonials')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                updateTestimonialCarousel(data.data);
                updateStatistics(data.statistics);
            }
        })
        .catch(error => {
            console.log('Error refreshing testimonials:', error);
        });
}

function updateTestimonialCarousel(testimonials) {
    const container = document.getElementById('testimonial-container');

    // Clear existing items
    container.innerHTML = '';

    // Add new testimonials
    testimonials.forEach(testimonial => {
        // Generate avatar HTML
        let avatarHTML = '';
        if (testimonial.user_image) {
            avatarHTML = `<img class="img-fluid flex-shrink-0 rounded-circle" src="${testimonial.user_image}" style="width: 50px; height: 50px; object-fit: cover;" alt="${testimonial.nama}">`;
        } else {
            const initial = testimonial.nama.charAt(0).toUpperCase();
            avatarHTML = `<div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 50px; height: 50px; font-size: 18px; font-weight: bold;">${initial}</div>`;
        }

        const testimonialHTML = `
            <div class="testimonial-item rounded p-4">
                <i class="fa fa-quote-left fa-2x text-primary mb-3"></i>
                <p>${testimonial.kritik_saran}</p>
                <div class="d-flex align-items-center">
                    ${avatarHTML}
                    <div class="ps-3">
                        <h6 class="mb-1">${testimonial.nama}</h6>
                        <small>${testimonial.user_name}</small>
                        <div class="mt-1">
                            ${getBadgeHTML(testimonial.tingkat_kepuasan)}
                        </div>
                    </div>
                </div>
                <small class="text-muted mt-2 d-block">${testimonial.tanggal}</small>
            </div>
        `;
        container.innerHTML += testimonialHTML;
    });

    // Reinitialize carousel if using Owl Carousel
    if (typeof $.fn.owlCarousel !== 'undefined') {
        $('.testimonial-carousel').trigger('destroy.owl.carousel');
        $('.testimonial-carousel').owlCarousel({
            autoplay: true,
            smartSpeed: 1000,
            center: true,
            margin: 24,
            dots: true,
            loop: true,
            nav: false,
            responsive: {
                0: { items: 1 },
                768: { items: 2 },
                992: { items: 3 }
            }
        });
    }
}

function updateStatistics(stats) {
    // Update statistics if elements exist
    if (stats) {
        const totalElement = document.querySelector('.text-primary');
        const satisfactionElement = document.querySelector('.text-success');
        const indexElement = document.querySelector('.text-info');

        if (totalElement) totalElement.textContent = stats.total_surveys;
        if (satisfactionElement) satisfactionElement.textContent = stats.satisfaction_percentage + '%';
        if (indexElement) indexElement.textContent = stats.satisfaction_index + '%';
    }
}

function getBadgeHTML(tingkatKepuasan) {
    if (tingkatKepuasan === 'Sangat Puas') {
        return '<span class="badge bg-success">Sangat Puas</span>';
    } else if (tingkatKepuasan === 'Puas') {
        return '<span class="badge bg-primary">Puas</span>';
    }
    return '';
}
</script>
