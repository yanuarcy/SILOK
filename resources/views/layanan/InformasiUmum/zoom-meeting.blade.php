@extends('Template.template')

@vite('resources/sass/app/meeting-detail.scss')

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

    <!-- Page Header Start -->
    <div class="container-xxl py-6 bg-primary mb-5">
        <div class="container text-center py-6">
            <h1 class="display-4 text-white mb-4">Detail Meeting Aktif</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('informasi-umum.index') }}" class="text-white">Informasi Umum</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('informasi-umum.zoom-meeting') }}" class="text-white">Zoom Meeting</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">Detail Meeting</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Meeting Detail Content Start -->
    <div class="container-xxl py-6">
        <div class="container">
            <!-- Access Verification -->
            <div class="row g-4 mb-5">
                <div class="col-12">
                    <div class="alert alert-info wow fadeInUp" data-wow-delay="0.1s">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle me-3 fa-2x"></i>
                            <div>
                                <h5 class="alert-heading mb-1">Akses Terbatas</h5>
                                <p class="mb-0">Halaman ini hanya dapat diakses oleh pengurus RW/RT yang terdaftar. Pastikan Anda memiliki hak akses sebelum bergabung dalam meeting.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Meetings -->
            <div class="row g-4 mb-5">
                <div class="col-12">
                    <div class="card meeting-list wow fadeInUp" data-wow-delay="0.1s">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0"><i class="bi bi-camera-video me-2"></i>Meeting Aktif & Terjadwal</h5>
                        </div>
                        <div class="card-body">
                            @forelse($active_meetings as $meeting)
                            <div class="meeting-item mb-4 p-4 border rounded">
                                <div class="row align-items-center">
                                    <div class="col-lg-8">
                                        <h5 class="mb-2">{{ $meeting['title'] }}</h5>
                                        <div class="meeting-info">
                                            <p class="mb-1"><i class="bi bi-calendar3 text-primary me-2"></i><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($meeting['date'])->format('d F Y') }}</p>
                                            <p class="mb-1"><i class="bi bi-clock text-primary me-2"></i><strong>Waktu:</strong> {{ $meeting['time'] }}</p>
                                            <p class="mb-1"><i class="bi bi-people text-primary me-2"></i><strong>Peserta:</strong> {{ $meeting['participants'] }}</p>
                                            <p class="mb-0">
                                                <span class="badge bg-{{ $meeting['status'] == 'active' ? 'success' : 'info' }}">
                                                    {{ $meeting['status'] == 'active' ? 'Meeting Berlangsung' : 'Terjadwal' }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 text-lg-end">
                                        @if($meeting['status'] == 'active')
                                        <a href="{{ $meeting['meet_link'] }}" target="_blank" class="btn btn-success btn-lg mb-2">
                                            <i class="bi bi-camera-video me-2"></i>Join Meeting
                                        </a>
                                        <br>
                                        <small class="text-muted">Klik untuk bergabung via Google Meet</small>
                                        @else
                                        <button class="btn btn-secondary btn-lg" disabled>
                                            <i class="bi bi-clock me-2"></i>Belum Dimulai
                                        </button>
                                        <br>
                                        <small class="text-muted">Meeting akan aktif sesuai jadwal</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-5">
                                <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 text-muted">Tidak Ada Meeting Aktif</h5>
                                <p class="text-muted">Saat ini tidak ada meeting yang sedang berlangsung atau terjadwal.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Meeting Schedule Overview -->
            <div class="row g-4 mb-5">
                <div class="col-lg-8 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="card schedule-overview">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title mb-0"><i class="bi bi-calendar-week me-2"></i>Jadwal Rapat Rutin</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Jenis Rapat</th>
                                            <th>Jadwal</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($meeting_schedule as $schedule)
                                        <tr>
                                            <td>{{ $schedule['title'] }}</td>
                                            <td>{{ $schedule['schedule'] }}</td>
                                            <td>
                                                <span class="badge bg-{{ $schedule['status_class'] }}">
                                                    {{ $schedule['status'] }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($schedule['status_class'] == 'success')
                                                <span class="text-success"><i class="bi bi-check-circle me-1"></i>Tersedia</span>
                                                @else
                                                <span class="text-muted">Menunggu</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="card instructions">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="card-title mb-0"><i class="bi bi-lightbulb me-2"></i>Petunjuk Meeting</h5>
                        </div>
                        <div class="card-body">
                            <div class="instruction-list">
                                <div class="instruction-item mb-3">
                                    <i class="bi bi-1-circle text-primary me-2"></i>
                                    <span>Pastikan koneksi internet stabil</span>
                                </div>
                                <div class="instruction-item mb-3">
                                    <i class="bi bi-2-circle text-primary me-2"></i>
                                    <span>Siapkan microphone dan kamera</span>
                                </div>
                                <div class="instruction-item mb-3">
                                    <i class="bi bi-3-circle text-primary me-2"></i>
                                    <span>Join 5 menit sebelum waktu</span>
                                </div>
                                <div class="instruction-item mb-3">
                                    <i class="bi bi-4-circle text-primary me-2"></i>
                                    <span>Gunakan nama lengkap dan jabatan</span>
                                </div>
                                <div class="instruction-item">
                                    <i class="bi bi-5-circle text-primary me-2"></i>
                                    <span>Mute microphone saat tidak berbicara</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Technical Support -->
            <div class="row g-4 mb-5">
                <div class="col-12">
                    <div class="card tech-support wow fadeInUp" data-wow-delay="0.1s">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <h5 class="card-title"><i class="bi bi-headset me-2"></i>Bantuan Teknis</h5>
                                    <p class="card-text mb-0">Mengalami kesulitan bergabung dalam meeting? Tim teknis kami siap membantu Anda mengatasi masalah koneksi atau akses meeting.</p>
                                </div>
                                <div class="col-lg-4 text-lg-end">
                                    <a href="tel:+6231-123456" class="btn btn-outline-primary me-2">
                                        <i class="bi bi-telephone me-2"></i>Call Support
                                    </a>
                                    <a href="https://wa.me/6281234567890" target="_blank" class="btn btn-success">
                                        <i class="bi bi-whatsapp me-2"></i>WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Create Meeting (for RT/RW Leaders) -->
            @can('create-meeting')
            <div class="row g-4 mb-5">
                <div class="col-12">
                    <div class="card create-meeting wow fadeInUp" data-wow-delay="0.1s">
                        <div class="card-header bg-success text-white">
                            <h5 class="card-title mb-0"><i class="bi bi-plus-circle me-2"></i>Buat Meeting Baru</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-4">Sebagai Ketua RT/RW, Anda dapat membuat meeting baru dengan memasukkan link Google Meet.</p>
                            <form action="{{ route('meeting.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="meeting_title" class="form-label">Judul Meeting</label>
                                            <input type="text" class="form-control" id="meeting_title" name="meeting_title" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="meeting_date" class="form-label">Tanggal</label>
                                            <input type="date" class="form-control" id="meeting_date" name="meeting_date" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="meeting_time" class="form-label">Waktu</label>
                                            <input type="time" class="form-control" id="meeting_time" name="meeting_time" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="meet_link" class="form-label">Link Google Meet</label>
                                            <input type="url" class="form-control" id="meet_link" name="meet_link" placeholder="https://meet.google.com/xxx-xxxx-xxx" required>
                                            <div class="form-text">Contoh: https://meet.google.com/ayv-wwbh-zrh</div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="participants" class="form-label">Peserta</label>
                                            <input type="text" class="form-control" id="participants" name="participants" placeholder="RW 01, RT 01-04" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Deskripsi (Opsional)</label>
                                            <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-plus-circle me-2"></i>Buat Meeting
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endcan

            <!-- Recent Meeting History -->
            <div class="row g-4 mb-5">
                <div class="col-12">
                    <div class="card meeting-history wow fadeInUp" data-wow-delay="0.1s">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="card-title mb-0"><i class="bi bi-clock-history me-2"></i>Riwayat Meeting Terakhir</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Judul Meeting</th>
                                            <th>Peserta</th>
                                            <th>Durasi</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>10 Jun 2025</td>
                                            <td>Rapat Koordinasi RW 01</td>
                                            <td>12 orang</td>
                                            <td>1 jam 30 menit</td>
                                            <td><span class="badge bg-success">Selesai</span></td>
                                        </tr>
                                        <tr>
                                            <td>03 Jun 2025</td>
                                            <td>Rapat RT Bulanan RW 02</td>
                                            <td>8 orang</td>
                                            <td>45 menit</td>
                                            <td><span class="badge bg-success">Selesai</span></td>
                                        </tr>
                                        <tr>
                                            <td>27 Mei 2025</td>
                                            <td>Rapat Koordinasi Kelurahan</td>
                                            <td>25 orang</td>
                                            <td>2 jam</td>
                                            <td><span class="badge bg-success">Selesai</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Meeting Detail Content End -->

    @include('layouts.newsletter')
    @include('layouts.footer')
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-refresh meeting status every 2 minutes
        setInterval(function() {
            fetch('{{ route("api.informasi-umum.meeting-schedule") }}')
                .then(response => response.json())
                .then(data => {
                    console.log('Meeting status updated');
                    // Update UI if needed
                })
                .catch(error => console.error('Error:', error));
        }, 120000); // 2 minutes

        // Countdown timer for upcoming meetings
        const meetingItems = document.querySelectorAll('.meeting-item');
        meetingItems.forEach(item => {
            const meetingDate = item.dataset.meetingDate;
            const meetingTime = item.dataset.meetingTime;

            if (meetingDate && meetingTime) {
                // Add countdown logic here if needed
            }
        });

        // Validate Google Meet URL format
        const meetLinkInput = document.getElementById('meet_link');
        if (meetLinkInput) {
            meetLinkInput.addEventListener('blur', function() {
                const url = this.value;
                const meetPattern = /^https:\/\/meet\.google\.com\/[a-z]{3}-[a-z]{4}-[a-z]{3}$/;

                if (url && !meetPattern.test(url)) {
                    this.classList.add('is-invalid');
                    if (!document.querySelector('.invalid-feedback')) {
                        const feedback = document.createElement('div');
                        feedback.className = 'invalid-feedback';
                        feedback.textContent = 'Format link Google Meet tidak valid. Contoh: https://meet.google.com/abc-defg-hij';
                        this.parentNode.appendChild(feedback);
                    }
                } else {
                    this.classList.remove('is-invalid');
                    const feedback = document.querySelector('.invalid-feedback');
                    if (feedback) {
                        feedback.remove();
                    }
                }
            });
        }
    });
</script>
@endpush

@endsection
