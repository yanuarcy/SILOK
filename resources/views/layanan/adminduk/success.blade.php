@extends('Template.template')

@vite('resources/sass/layanan/adminduk/registration_form.scss')
@vite('resources/sass/layanan/adminduk/show.scss')

@push('style')

    <link href="{{ Vite::asset('resources/css/style.css') }}" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #F3F6F8;
            margin: 0;
            padding: 0;
        }

        .success-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            padding: 40px 30px;
            max-width: 450px;
            width: 100%;
            text-align: center;
            position: relative;
            overflow: hidden;
            margin: auto;
        }

        .success-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #20c997, #17a2b8, #6f42c1);
        }

        .header-section {
            margin-bottom: 30px;
        }

        .success-icon {
            background: linear-gradient(135deg, #20c997, #17a2b8);
            color: white;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 35px;
            animation: successIconAnimation 2s ease-out, bounce 2s infinite 2s;
            box-shadow: 0 10px 30px rgba(32, 201, 151, 0.3);
            position: relative;
        }

        .success-icon::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: rgba(32, 201, 151, 0.3);
            animation: ripple 2s ease-out;
        }

        .success-icon i {
            animation: checkmarkDraw 1.5s ease-out 0.5s both;
            transform-origin: center;
        }

        @keyframes successIconAnimation {
            0% {
                transform: scale(0) rotate(-180deg);
                opacity: 0;
            }
            30% {
                transform: scale(0.3) rotate(-90deg);
                opacity: 0.3;
            }
            60% {
                transform: scale(1.2) rotate(0deg);
                opacity: 0.8;
            }
            80% {
                transform: scale(0.9) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: scale(1) rotate(0deg);
                opacity: 1;
            }
        }

        @keyframes ripple {
            0% {
                transform: scale(0);
                opacity: 1;
            }
            50% {
                transform: scale(2);
                opacity: 0.3;
            }
            100% {
                transform: scale(3);
                opacity: 0;
            }
        }

        @keyframes checkmarkDraw {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            50% {
                transform: scale(1.2);
                opacity: 0.8;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }

        .title {
            color: #2d3748;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #718096;
            font-size: 16px;
            margin-bottom: 30px;
        }

        .antrian-card {
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
            border: 3px solid #20c997;
            border-radius: 15px;
            padding: 30px 20px;
            margin: 25px 0;
            position: relative;
            animation: slideUp 0.8s ease-out 0.3s both;
        }

        @keyframes slideUp {
            0% {
                transform: translateY(30px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .antrian-label {
            color: #4a5568;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .antrian-number {
            color: #20c997;
            font-size: 64px;
            font-weight: 900;
            line-height: 1;
            margin: 10px 0;
            text-shadow: 0 4px 8px rgba(32, 201, 151, 0.2);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .antrian-desc {
            color: #2d3748;
            font-size: 18px;
            font-weight: 600;
            margin-top: 10px;
        }

        .info-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
            animation: fadeIn 1s ease-out 0.6s both;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #4a5568;
            font-weight: 600;
            font-size: 14px;
        }

        .info-value {
            color: #2d3748;
            font-weight: 500;
            font-size: 14px;
            text-align: right;
        }

        .badge-status {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-status.offline {
            background: linear-gradient(135deg, #6c757d, #495057);
        }

        .alert-info {
            background: linear-gradient(135deg, #e3f2fd, #f3e5f5);
            border: none;
            border-radius: 12px;
            color: #1565c0;
            font-size: 14px;
            margin: 20px 0;
            animation: fadeIn 1s ease-out 0.9s both;
        }

        .btn-back {
            background: linear-gradient(135deg, #20c997, #17a2b8);
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            font-size: 16px;
            padding: 15px 40px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            animation: fadeIn 1s ease-out 1.2s both;
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(32, 201, 151, 0.3);
            color: white;
        }

        .btn-back:active {
            transform: translateY(0);
        }

        .floating-decoration {
            position: absolute;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(32, 201, 151, 0.1), rgba(23, 162, 184, 0.1));
            top: -50px;
            right: -50px;
            z-index: -1;
        }

        .floating-decoration:nth-child(2) {
            width: 60px;
            height: 60px;
            bottom: -30px;
            left: -30px;
            top: auto;
            right: auto;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        }

        @media (max-width: 480px) {
            .success-container {
                padding: 30px 20px;
                margin: 10px;
            }

            .antrian-number {
                font-size: 48px;
            }

            .title {
                font-size: 24px;
            }
        }

        .timer-text {
            color: #718096;
            font-size: 12px;
            margin-top: 10px;
        }

        /* Antrian Status Section */
        .antrian-status-section {
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 25px 20px;
            margin: 25px 0;
            animation: fadeIn 1s ease-out 0.8s both;
        }

        .status-title {
            color: #2d3748;
            font-size: 18px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
        }

        .status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .status-item {
            background: white;
            border-radius: 12px;
            padding: 15px 10px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .status-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .status-item.current {
            border-left: 4px solid #28a745;
        }

        .status-item.waiting {
            border-left: 4px solid #ffc107;
        }

        .status-item.position {
            border-left: 4px solid #17a2b8;
        }

        .status-icon {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            color: #6c757d;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 16px;
        }

        .status-item.current .status-icon {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .status-item.waiting .status-icon {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
            color: white;
        }

        .status-item.position .status-icon {
            background: linear-gradient(135deg, #17a2b8, #20c997);
            color: white;
        }

        .status-label {
            color: #6c757d;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .status-number {
            color: #2d3748;
            font-size: 24px;
            font-weight: 900;
            line-height: 1;
        }

        .estimated-time {
            background: rgba(23, 162, 184, 0.1);
            color: #17a2b8;
            padding: 12px 15px;
            border-radius: 10px;
            text-align: center;
            font-size: 14px;
            font-weight: 500;
        }

        .loading-dots {
            animation: loadingDots 1.5s infinite;
        }

        @keyframes loadingDots {
            0%, 20% {
                color: rgba(45, 55, 72, 0.3);
            }
            50% {
                color: rgba(45, 55, 72, 1);
            }
            80%, 100% {
                color: rgba(45, 55, 72, 0.3);
            }
        }
    </style>

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
    <div class="success-container">
        <div class="floating-decoration"></div>
        <div class="floating-decoration"></div>

        <div class="header-section">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            <h1 class="title">Daftar Antrian</h1>
            <p class="subtitle">Pendaftaran Anda telah berhasil diproses</p>
        </div>

        <div class="antrian-card">
            <div class="antrian-label">ANTRIAN</div>
            <div class="antrian-number">{{ session('antrian_data.no_antrian', 'A1') }}</div>
            <div class="antrian-desc">{{ session('antrian_data.keterangan', 'Pengambilan KTP') }}</div>
        </div>

        @if(session('antrian_data'))
        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Nama</span>
                <span class="info-value">{{ session('antrian_data.nama') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Jenis Layanan</span>
                <span class="info-value">{{ session('antrian_data.jenis_layanan') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal</span>
                <span class="info-value">{{ session('antrian_data.tanggal') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Jam Daftar</span>
                <span class="info-value">{{ session('antrian_data.jam') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="info-value">
                    <span class="badge-status {{ session('antrian_data.jenis_antrian') === 'Online' ? '' : 'offline' }}">
                        {{ session('antrian_data.jenis_antrian') }}
                    </span>
                </span>
            </div>
        </div>

        <!-- Informasi Antrian Sekarang -->
        <div class="antrian-status-section">
            <h5 class="status-title">
                <i class="fas fa-clock me-2"></i>
                Status Antrian Hari Ini
            </h5>

            <div class="status-grid">
                <div class="status-item current">
                    <div class="status-icon">
                        <i class="fas fa-play"></i>
                    </div>
                    <div class="status-content">
                        <div class="status-label">Antrian Sekarang</div>
                        <div class="status-number" id="current-queue">-</div>
                    </div>
                </div>

                <div class="status-item waiting">
                    <div class="status-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="status-content">
                        <div class="status-label">Antrian Menunggu</div>
                        <div class="status-number" id="waiting-queue">-</div>
                    </div>
                </div>

                <div class="status-item position">
                    <div class="status-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="status-content">
                        <div class="status-label">Posisi Anda</div>
                        <div class="status-number" id="your-position">-</div>
                    </div>
                </div>
            </div>

            <div class="estimated-time">
                <i class="fas fa-hourglass-half me-2"></i>
                <span>Perkiraan waktu tunggu: <strong id="estimated-wait">-</strong></span>
            </div>
        </div>
        @endif

        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Penting:</strong> Screenshot halaman ini sebagai bukti pendaftaran. Simpan nomor antrian Anda dan datang sesuai waktu yang telah ditentukan.
        </div>

        <a href="{{ route('Adminduk') }}" class="btn-back">
            <i class="fas fa-arrow-left me-2"></i>
            Kembali
        </a>

        <div class="timer-text">
            Halaman ini akan otomatis kembali ke beranda dalam <span id="countdown">30</span> detik
        </div>

        <!-- Catatan untuk Screenshot -->
        <div class="screenshot-note" style="position: fixed; bottom: 10px; right: 10px; background: rgba(0,0,0,0.8); color: white; padding: 8px 12px; border-radius: 8px; font-size: 12px; z-index: 1000;">
            <i class="fas fa-camera me-1"></i>
            Screenshot halaman ini sebagai bukti pendaftaran
        </div>
    </div>

    @include('layouts.footer')

</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Countdown timer
        let countdown = 30;
        const countdownElement = document.getElementById('countdown');

        function updateCountdown() {
            if (countdown > 0) {
                countdownElement.textContent = countdown;
                document.title = `(${countdown}s) Pendaftaran Berhasil - Layanan Adminduk`;
                countdown--;
                setTimeout(updateCountdown, 1000);
            } else {
                window.location.href = "{{ route('Adminduk') }}";
            }
        }

        updateCountdown();

        // Fetch antrian status
        async function fetchAntrianStatus() {
            try {
                const response = await fetch('/adminduk/stats');
                const data = await response.json();

                if (data.success) {
                    const stats = data.data;
                    const yourAntrianNumber = '{{ session("antrian_data.no_antrian", "A1") }}';
                    const yourNumber = parseInt(yourAntrianNumber.replace('A', ''));

                    // Update antrian sekarang
                    document.getElementById('current-queue').textContent =
                        stats.antrian_sekarang || 'Belum ada';

                    // Update antrian menunggu (total yang belum dipanggil)
                    document.getElementById('waiting-queue').textContent = stats.antrian_menunggu;

                    // Calculate posisi user
                    let yourPosition = 'Selesai';
                    let waitMinutes = 0;

                    if (stats.antrian_sekarang) {
                        const currentNumber = parseInt(stats.antrian_sekarang.replace('A', ''));

                        if (yourNumber > currentNumber) {
                            // User masih menunggu
                            yourPosition = `${yourNumber - currentNumber} antrian lagi`;
                            waitMinutes = (yourNumber - currentNumber) * 5; // 5 menit per antrian
                        } else if (yourNumber === currentNumber) {
                            // User sedang dilayani
                            yourPosition = 'Sedang dilayani';
                            waitMinutes = 0;
                        } else {
                            // User sudah selesai
                            yourPosition = 'Selesai';
                            waitMinutes = 0;
                        }
                    } else {
                        // Belum ada yang dipanggil, hitung dari urutan
                        yourPosition = `${yourNumber} antrian lagi`;
                        waitMinutes = yourNumber * 5;
                    }

                    document.getElementById('your-position').textContent = yourPosition;

                    // Update estimasi waktu tunggu
                    const estimatedTime = waitMinutes > 0 ?
                        `${waitMinutes} menit` :
                        yourPosition === 'Sedang dilayani' ? 'Sedang dilayani' : 'Selesai';
                    document.getElementById('estimated-wait').textContent = estimatedTime;

                } else {
                    throw new Error('Failed to fetch data');
                }
            } catch (error) {
                console.error('Error fetching antrian status:', error);
                // Show error state instead of loading dots
                document.getElementById('current-queue').textContent = 'Error';
                document.getElementById('waiting-queue').textContent = 'Error';
                document.getElementById('your-position').textContent = 'Error';
                document.getElementById('estimated-wait').textContent = 'Error';

                // Retry after 10 seconds
                setTimeout(fetchAntrianStatus, 10000);
            }
        }

        // Initial fetch
        fetchAntrianStatus();

        // Auto refresh every 30 seconds
        setInterval(fetchAntrianStatus, 30000);

        // Add some interactive effects
        document.querySelector('.antrian-card').addEventListener('click', function() {
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });

        // Replay success icon animation on click
        document.querySelector('.success-icon').addEventListener('click', function() {
            this.style.animation = 'none';
            this.querySelector('i').style.animation = 'none';
            setTimeout(() => {
                this.style.animation = 'successIconAnimation 2s ease-out';
                this.querySelector('i').style.animation = 'checkmarkDraw 1.5s ease-out 0.5s both';
            }, 10);
        });

        // Add bounce effect to screenshot note
        const screenshotNote = document.querySelector('.screenshot-note');
        if (screenshotNote) {
            setTimeout(() => {
                screenshotNote.style.animation = 'bounce 1s ease-out 3 alternate';
            }, 3000);
        }

        // CSS for bounce animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes bounce {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-10px); }
            }
        `;
        document.head.appendChild(style);
    </script>
@endpush
