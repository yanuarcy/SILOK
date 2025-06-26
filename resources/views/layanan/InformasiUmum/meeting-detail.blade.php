@extends('Template.template')

@vite('resources/sass/app/meeting-detail.scss')

@push('style')
    <link href="{{ Vite::asset('resources/css/style.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .meeting-detail-section {
            background: #f8f9fa;
            padding: 60px 0;
            min-height: 80vh;
        }

        .detail-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .active-meetings-container {
            max-height: 500px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .active-meetings-container::-webkit-scrollbar {
            width: 6px;
        }

        .active-meetings-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .active-meetings-container::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
            border-radius: 10px;
        }

        .active-meetings-container::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
        }

        .access-alert {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            border: none;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 40px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .access-alert .alert-content {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .access-alert .alert-icon {
            color: #0c5460;
            font-size: 3rem;
            flex-shrink: 0;
        }

        .access-alert h5 {
            color: #0c5460;
            font-weight: 700;
            margin-bottom: 8px;
            font-size: 1.3rem;
        }

        .access-alert p {
            color: #0c5460;
            margin: 0;
            font-size: 1rem;
            line-height: 1.5;
        }

        .main-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
            overflow: hidden;
            border: 1px solid #e9ecef;
        }

        .card-header-custom {
            background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
            color: white;
            padding: 25px 30px;
            font-weight: 700;
            font-size: 1.2rem;
            border: none;
        }

        .card-header-custom i {
            margin-right: 12px;
            font-size: 1.3rem;
        }

        .card-body-custom {
            padding: 40px;
        }

        .meeting-active-item {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 25px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .meeting-active-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
        }

        .meeting-active-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border-color: #20c997;
        }

        .meeting-active-item:last-child {
            margin-bottom: 0;
        }

        .meeting-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .meeting-details {
            margin-bottom: 20px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            font-size: 1rem;
        }

        .detail-item:last-child {
            margin-bottom: 0;
        }

        .detail-icon {
            color: #20c997;
            margin-right: 12px;
            width: 20px;
            font-size: 1.1rem;
        }

        .detail-label {
            font-weight: 600;
            margin-right: 8px;
            color: #495057;
        }

        .detail-value {
            color: #6c757d;
        }

        .meeting-status-badge {
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
            margin-bottom: 20px;
        }

        .status-active {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 2px solid #b8daff;
        }

        .status-scheduled {
            background: linear-gradient(135deg, #cce7ff 0%, #b8daff 100%);
            color: #004085;
            border: 2px solid #80bdff;
        }

        .status-ready {
            background: linear-gradient(135deg, #99fb22 0%, #e8ff16 100%);
            color: #000000;
            border: 2px solid #80bdff;
        }

        .btn-join-meeting {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 15px 35px;
            border: none;
            border-radius: 30px;
            font-weight: 700;
            font-size: 1.1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-join-meeting:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(40, 167, 69, 0.4);
            color: white;
            background: linear-gradient(135deg, #218838 0%, #17a2b8 100%);
        }

        .btn-join-meeting i {
            font-size: 1.2rem;
        }

        .btn-waiting {
            background: #6c757d;
            color: white;
            padding: 15px 35px;
            border: none;
            border-radius: 30px;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: not-allowed;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .empty-meetings {
            text-align: center;
            padding: 80px 20px;
            color: #6c757d;
        }

        .empty-meetings i {
            font-size: 5rem;
            margin-bottom: 25px;
            color: #dee2e6;
        }

        .empty-meetings h5 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #495057;
        }

        .empty-meetings p {
            font-size: 1.1rem;
            max-width: 400px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .schedule-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        @media (max-width: 768px) {
            .schedule-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }
        }

        .schedule-table-wrapper {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .schedule-table {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
        }

        .schedule-table thead {
            background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
        }

        .schedule-table thead th {
            color: white;
            font-weight: 700;
            padding: 20px 15px;
            text-align: left;
            border: none;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .schedule-table tbody td {
            padding: 18px 15px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
            font-size: 0.95rem;
        }

        .schedule-table tbody tr:last-child td {
            border-bottom: none;
        }

        .schedule-table tbody tr:hover {
            background: #f8f9fa;
        }

        .table-status-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table-status-badge.aktif {
            background: #d4edda;
            color: #155724;
        }

        .table-status-badge.terjadwal {
            background: #cce7ff;
            color: #004085;
        }

        .table-status-badge.pending {
            background: #fff3cd;
            color: #856404;
        }

        .instructions-card {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .instructions-title {
            color: #856404;
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .instruction-step {
            display: flex;
            align-items: center;
            margin-bottom: 18px;
            padding: 15px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }

        .instruction-step:hover {
            transform: translateX(5px);
        }

        .instruction-step:last-child {
            margin-bottom: 0;
        }

        .step-number {
            background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-right: 15px;
            font-size: 14px;
            flex-shrink: 0;
        }

        .step-text {
            font-weight: 600;
            color: #495057;
            font-size: 0.95rem;
        }

        .support-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 40px;
        }

        @media (max-width: 768px) {
            .support-section {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }

        .support-card {
            background: linear-gradient(135deg, #e7f3ff 0%, #cce7ff 100%);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .support-icon {
            font-size: 3rem;
            color: #007bff;
            margin-bottom: 20px;
        }

        .support-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #004085;
            margin-bottom: 15px;
        }

        .support-text {
            color: #004085;
            margin-bottom: 25px;
            line-height: 1.6;
        }

        .support-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-support {
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .btn-outline-primary {
            border: 2px solid #007bff;
            color: #007bff;
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: #007bff;
            color: white;
            transform: translateY(-2px);
        }

        .btn-success {
            background: #28a745;
            color: white;
            border: 2px solid #28a745;
        }

        .btn-success:hover {
            background: #218838;
            border-color: #218838;
            color: white;
            transform: translateY(-2px);
        }

        .history-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .history-title {
            color: #495057;
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
        }

        .history-table th,
        .history-table td {
            padding: 15px 10px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
            font-size: 0.9rem;
        }

        .history-table th {
            background: #6c757d;
            color: white;
            font-weight: 700;
        }

        .history-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.8);
        }

        .history-status {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 10px;
            font-weight: 700;
            background: #d4edda;
            color: #155724;
            text-transform: uppercase;
        }

        .table-responsive {
            max-height: 200px;
            overflow-y: auto;
        }

        /* Header button style */
        .card-header-custom .btn-light {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            font-weight: 600;
            border-radius: 20px;
            padding: 8px 20px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .card-header-custom .btn-light:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.2);
            text-decoration: none;
        }

        /* Alert untuk success message */
        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: 1px solid #c3e6cb;
            color: #155724;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Auto-refresh indicator */
        .auto-refresh-indicator {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
            color: white;
            padding: 10px 15px;
            border-radius: 25px;
            font-size: 12px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 1050;
        }

        .auto-refresh-indicator.show {
            opacity: 1;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .active-meetings-container {
                max-height: 400px;
                padding-right: 5px;
            }

            .meeting-active-item {
                padding: 20px;
                margin-bottom: 15px;
            }

            .meeting-active-item::before {
                width: 3px;
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

        <!-- Page Header Start -->
        <div class="container-xxl py-6 bg-primary mb-5">
            <div class="container text-center py-6">
                <h1 class="display-4 text-white mb-4">Detail Meeting Aktif</h1>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('informasi-umum.index') }}" class="text-white">Informasi Umum</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page">Detail Meeting</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header End -->

        <!-- Meeting Detail Content Start -->
        <div class="meeting-detail-section">
            <div class="detail-container">
                <!-- Success Alert -->
                @if(session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i>
                    {{ session('success') }}
                </div>
                @endif

                <!-- Access Alert -->
                <div class="access-alert wow fadeInUp" data-wow-delay="0.1s">
                    <div class="alert-content">
                        <i class="bi bi-info-circle alert-icon"></i>
                        <div>
                            <h5>Akses Terbatas</h5>
                            <p>Halaman ini hanya dapat diakses oleh pengurus RW/RT yang terdaftar. Pastikan Anda memiliki hak akses sebelum bergabung dalam meeting.</p>
                        </div>
                    </div>
                </div>

                <!-- Active Meetings -->
                <div class="main-card wow fadeInUp" data-wow-delay="0.1s">
                    <div class="card-header-custom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-camera-video"></i>Meeting Aktif & Terjadwal
                            </div>
                            @auth
                                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'Operator' || auth()->user()->role === 'Ketua RT' || auth()->user()->role === 'Ketua RW')
                                    <a href="{{ route('meeting.create') }}" class="btn-light btn-sm">
                                        <i class="bi bi-plus-circle"></i>Tambah Meeting
                                    </a>
                                @else
                                    <button class="btn-light btn-sm" onclick="unauthorizedAlert()">
                                        <i class="bi bi-plus-circle"></i>Tambah Meeting
                                    </button>
                                @endif
                            @endauth
                            <script>
                                function unauthorizedAlert() {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Tidak Diizinkan',
                                        text: 'Hanya Ketua RT dan Ketua RW yang bisa menambahkan data meeting.',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            </script>
                        </div>
                    </div>
                    <div class="card-body-custom">
                        <div class="active-meetings-container">
                            @forelse($active_meetings as $meeting)
                            <div class="meeting-active-item">
                                <div class="row align-items-center">
                                    <div class="col-lg-8">
                                        <h5 class="meeting-title">{{ $meeting['title'] }}</h5>
                                        <div class="meeting-details">
                                            <div class="detail-item">
                                                <i class="bi bi-calendar3 detail-icon"></i>
                                                <span class="detail-label">Tanggal:</span>
                                                <span class="detail-value">{{ $meeting['formatted_date'] }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-clock detail-icon"></i>
                                                <span class="detail-label">Waktu:</span>
                                                <span class="detail-value">{{ $meeting['time'] }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-people detail-icon"></i>
                                                <span class="detail-label">Peserta:</span>
                                                <span class="detail-value">{{ $meeting['participants'] }}</span>
                                            </div>
                                        </div>
                                        <span class="meeting-status-badge {{ $meeting['status_class'] ?? 'status-scheduled' }}" id="status-{{ $meeting['id'] }}">
                                            {{ $meeting['status_label'] ?? 'Terjadwal' }}
                                        </span>
                                    </div>
                                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                                        @if($meeting['can_join'])
                                            <a href="{{ $meeting['meet_link'] }}" target="_blank" class="btn-join-meeting">
                                                <i class="bi bi-camera-video"></i>Join Meeting
                                            </a>
                                            <br><br>
                                            <small class="text-muted">Klik untuk bergabung via Google Meet</small>
                                        @else
                                            <button class="btn-waiting" disabled>
                                                <i class="bi bi-clock"></i>Akses Ditolak
                                            </button>
                                            <br><br>
                                            <small class="text-muted">Anda tidak terdaftar sebagai peserta meeting ini.</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="empty-meetings">
                                <i class="bi bi-calendar-x"></i>
                                <h5>Tidak Ada Meeting Aktif</h5>
                                <p>Saat ini tidak ada meeting yang sedang berlangsung atau terjadwal.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Schedule & Instructions -->
                <div class="schedule-grid">
                    <!-- Jadwal Rapat Rutin -->
                    <div class="wow fadeInUp" data-wow-delay="0.1s">
                        <div class="schedule-table-wrapper">
                            <table class="schedule-table">
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
                                            <span class="table-status-badge {{ strtolower($schedule['status']) }}">
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

                    <!-- Petunjuk Meeting -->
                    <div class="wow fadeInUp" data-wow-delay="0.3s">
                        <div class="instructions-card">
                            <h6 class="instructions-title">
                                <i class="bi bi-lightbulb"></i>Petunjuk Meeting
                            </h6>
                            <div class="instruction-step">
                                <div class="step-number">1</div>
                                <span class="step-text">Pastikan koneksi internet stabil</span>
                            </div>
                            <div class="instruction-step">
                                <div class="step-number">2</div>
                                <span class="step-text">Siapkan microphone dan kamera</span>
                            </div>
                            <div class="instruction-step">
                                <div class="step-number">3</div>
                                <span class="step-text">Join 5 menit sebelum waktu</span>
                            </div>
                            <div class="instruction-step">
                                <div class="step-number">4</div>
                                <span class="step-text">Gunakan nama lengkap dan jabatan</span>
                            </div>
                            <div class="instruction-step">
                                <div class="step-number">5</div>
                                <span class="step-text">Mute microphone saat tidak berbicara</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Support & History -->
                <div class="support-section">
                    <!-- Technical Support -->
                    <div class="support-card wow fadeInUp" data-wow-delay="0.1s">
                        <i class="bi bi-headset support-icon"></i>
                        <h6 class="support-title">Bantuan Teknis</h6>
                        <p class="support-text">Mengalami kesulitan bergabung dalam meeting? Tim teknis kami siap membantu Anda mengatasi masalah koneksi atau akses meeting.</p>
                        <div class="support-buttons">
                            <a href="tel:+6231-123456" class="btn-support btn-outline-primary">
                                <i class="bi bi-telephone"></i>Call Support
                            </a>
                            <a href="https://wa.me/6281234567890" target="_blank" class="btn-support btn-success">
                                <i class="bi bi-whatsapp"></i>WhatsApp
                            </a>
                        </div>
                    </div>

                    <!-- Meeting History -->
                    <div class="history-card wow fadeInUp" data-wow-delay="0.3s">
                        <h6 class="history-title">
                            <i class="bi bi-clock-history"></i>Riwayat Meeting Terakhir
                        </h6>
                        <div class="table-responsive">
                            <table class="history-table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Judul Meeting</th>
                                        <th>Peserta</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($completed_meetings as $meeting)
                                    <tr>
                                        <td>{{ $meeting['date'] }}</td>
                                        <td>{{ $meeting['title'] }}</td>
                                        <td>{{ $meeting['participants'] }}</td>
                                        <td><span class="history-status">{{ $meeting['status'] }}</span></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            <i class="bi bi-clock-history me-2"></i>Belum ada riwayat meeting
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Meeting Detail Content End -->

        @include('layouts.newsletter')
        @include('layouts.footer')
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hide spinner when page loads
            const spinner = document.getElementById('spinner');
            if (spinner) {
                spinner.classList.remove('show');
            }

            // Global variables
            let updateInterval;
            let apiUpdateInterval;

            // Initialize real-time updates
            initializeRealTimeUpdates();

            // Ensure all badges are visible on page load
            ensureBadgesExist();

            // Ensure badges exist and are visible
            function ensureBadgesExist() {
                const meetingItems = document.querySelectorAll('[data-meeting-datetime]');

                meetingItems.forEach(item => {
                    const meetingId = item.querySelector('[id^="button-container-"]')?.id.replace('button-container-', '');
                    if (!meetingId) return;

                    let statusElement = document.getElementById(`status-${meetingId}`);

                    // Create badge if it doesn't exist
                    if (!statusElement) {
                        statusElement = document.createElement('span');
                        statusElement.id = `status-${meetingId}`;
                        statusElement.className = 'meeting-status-badge status-scheduled';
                        statusElement.textContent = 'Terjadwal';

                        // Insert badge after meeting details
                        const meetingDetails = item.querySelector('.meeting-details');
                        if (meetingDetails) {
                            meetingDetails.insertAdjacentElement('afterend', statusElement);
                        }
                    }

                    // Ensure badge is visible
                    statusElement.style.display = 'inline-block';
                });
            }

            const meetingItems = document.querySelectorAll('[data-meeting-datetime]');

            meetingItems.forEach(item => {
                const meetingDateTime = item.getAttribute('data-meeting-datetime');
                if (!meetingDateTime) return;

                const meetingId = item.querySelector('[id^="status-"]')?.id.replace('status-', '');
                if (!meetingId) return;

                updateSingleMeeting(meetingId, meetingDateTime, item);
            });


            // function unauthorizedAlert() {
            //     Swal.fire({
            //         icon: 'error',
            //         title: 'Tidak Diizinkan',
            //         text: 'Hanya Ketua RT dan Ketua RW yang bisa menambahkan data meeting.',
            //         confirmButtonText: 'OK'
            //     });
            // }


            // Update meeting statuses using both frontend logic and API data
            function updateMeetingStatuses() {
                const meetingItems = document.querySelectorAll('[data-meeting-datetime]');

                meetingItems.forEach(item => {
                    const meetingDateTime = item.getAttribute('data-meeting-datetime');
                    if (!meetingDateTime) return;

                    const meetingId = item.querySelector('[id^="status-"]')?.id.replace('status-', '');
                    if (!meetingId) return;

                    updateSingleMeeting(meetingId, meetingDateTime, item);
                });
            }

            // Update single meeting item
            function updateSingleMeeting(meetingId, meetingDateTime, item) {
                const now = new Date();
                const meetingTime = new Date(meetingDateTime);
                const tenMinutesBefore = new Date(meetingTime.getTime() - 10 * 60 * 1000);
                const fortyFiveMinutesAfter = new Date(meetingTime.getTime() + 45 * 60 * 1000);

                const statusElement = document.getElementById(`status-${meetingId}`);
                const buttonContainer = document.getElementById(`button-container-${meetingId}`);

                if (!statusElement || !buttonContainer) return;

                // Get meeting link from existing DOM
                const existingLink = item.querySelector('a[href*="meet.google.com"]');
                const meetLink = existingLink ? existingLink.href : '#';

                let newStatus = '';
                let newStatusClass = '';
                let buttonHtml = '';

                // Determine status and button based on current time
                if (now < tenMinutesBefore) {
                    // Belum Dimulai
                    newStatus = 'Belum Dimulai';
                    newStatusClass = 'status-scheduled';

                    const timeUntilMinutes = Math.ceil((tenMinutesBefore - now) / (1000 * 60));
                    const timeUntilText = formatTimeUntil(timeUntilMinutes);

                    buttonHtml = `
                        <button class="btn-waiting" disabled>
                            <i class="bi bi-clock"></i>Belum Dimulai
                        </button>
                        <br><br>
                        <div class="countdown-timer" id="countdown-${meetingId}">
                            <i class="bi bi-hourglass-split"></i>
                            <span>Tersedia dalam ${timeUntilText}</span>
                        </div>
                    `;
                } else if (now >= tenMinutesBefore && now < meetingTime) {
                    // Siap Dimulai (10 menit sebelum)
                    newStatus = 'Siap Dimulai';
                    newStatusClass = 'status-ready';

                    buttonHtml = `
                        <a href="${meetLink}" target="_blank" class="btn-ready">
                            <i class="bi bi-door-open"></i>Siap Bergabung
                        </a>
                        <br><br>
                        <small class="text-warning">⏰ Meeting akan dimulai sebentar lagi</small>
                    `;
                } else if (now >= meetingTime && now < fortyFiveMinutesAfter) {
                    // Sedang Berlangsung
                    newStatus = 'Sedang Berlangsung';
                    newStatusClass = 'status-active';

                    buttonHtml = `
                        <a href="${meetLink}" target="_blank" class="btn-join-meeting">
                            <i class="bi bi-camera-video"></i>Join Meeting
                        </a>
                        <br><br>
                        <small class="text-success">🔴 Meeting sedang berlangsung</small>
                    `;
                } else {
                    // Terlewat
                    newStatus = 'Terlewat';
                    newStatusClass = 'status-scheduled';

                    buttonHtml = `
                        <button class="btn-waiting" disabled>
                            <i class="bi bi-x-circle"></i>Terlewat
                        </button>
                        <br><br>
                        <small class="text-muted">Meeting telah berakhir</small>
                    `;
                }

                // Update status badge if changed
                if (statusElement.textContent.trim() !== newStatus) {
                    statusElement.textContent = newStatus;
                    statusElement.className = `meeting-status-badge ${newStatusClass}`;

                    // Add transition effect
                    statusElement.style.transform = 'scale(1.1)';
                    setTimeout(() => {
                        statusElement.style.transform = 'scale(1)';
                    }, 200);
                }

                // Update button container if changed
                const currentButtonHtml = buttonContainer.innerHTML.replace(/\s+/g, ' ').trim();
                const newButtonHtml = buttonHtml.replace(/\s+/g, ' ').trim();

                if (currentButtonHtml !== newButtonHtml) {
                    buttonContainer.style.opacity = '0.5';
                    setTimeout(() => {
                        buttonContainer.innerHTML = buttonHtml;
                        buttonContainer.style.opacity = '1';

                        // Re-attach event listeners for new buttons
                        attachButtonEventListeners();
                    }, 150);
                }
            }

            // Format time until joinable
            function formatTimeUntil(minutes) {
                if (minutes <= 0) return 'sebentar lagi';
                if (minutes < 60) return `${minutes} menit`;

                const hours = Math.floor(minutes / 60);
                const remainingMinutes = minutes % 60;

                if (remainingMinutes === 0) return `${hours} jam`;
                return `${hours} jam ${remainingMinutes} menit`;
            }

            // Fetch updated data from API and merge with frontend logic
            function updateFromAPI() {
                fetch('{{ route("api.meeting.update-status") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data && data.data.active_meetings) {
                        updateMeetingsFromAPIData(data.data.active_meetings);
                        showRefreshIndicator('Data meeting diperbarui dari server');
                    }
                })
                .catch(error => {
                    console.error('API Update Error:', error);
                    showRefreshIndicator('Gagal memperbarui data', 'error');
                });
            }

            // Update meetings using API data
            function updateMeetingsFromAPIData(apiMeetings) {
                apiMeetings.forEach(meeting => {
                    const item = document.querySelector(`[data-meeting-datetime*="${meeting.id}"]`) ||
                                document.querySelector(`#status-${meeting.id}`)?.closest('[data-meeting-datetime]');

                    if (item) {
                        // Update the data attribute with fresh datetime
                        if (meeting.meeting_datetime) {
                            item.setAttribute('data-meeting-datetime', meeting.meeting_datetime);
                        }

                        // Update using the latest data
                        updateSingleMeeting(meeting.id, meeting.meeting_datetime || item.getAttribute('data-meeting-datetime'), item);
                    }
                });
            }

            // Initialize all update intervals
            function initializeRealTimeUpdates() {
                // Update frontend logic every 15 seconds
                updateMeetingStatuses();
                updateInterval = setInterval(updateMeetingStatuses, 15000);

                // Update from API every 2 minutes
                apiUpdateInterval = setInterval(updateFromAPI, 120000);

                // Also update from API after 30 seconds (initial sync)
                setTimeout(updateFromAPI, 30000);
            }

            // Stop all intervals (useful for cleanup)
            function stopRealTimeUpdates() {
                if (updateInterval) clearInterval(updateInterval);
                if (apiUpdateInterval) clearInterval(apiUpdateInterval);
            }

            // Attach event listeners to buttons
            function attachButtonEventListeners() {
                // Join meeting buttons
                document.querySelectorAll('.btn-join-meeting, .btn-ready').forEach(button => {
                    button.removeEventListener('click', handleButtonClick); // Remove existing
                    button.addEventListener('click', handleButtonClick);
                });
            }

            // Handle button clicks with visual feedback
            function handleButtonClick(event) {
                const button = event.target.closest('.btn-join-meeting, .btn-ready');
                if (button) {
                    // Visual feedback
                    button.style.transform = 'scale(0.95)';
                    button.style.opacity = '0.8';

                    setTimeout(() => {
                        button.style.transform = 'scale(1)';
                        button.style.opacity = '1';
                    }, 150);

                    // Optional: Track meeting join
                    console.log('User joining meeting:', button.href);
                }
            }

            // Show refresh indicator
            function showRefreshIndicator(message = 'Memperbarui data meeting...', type = 'success') {
                let indicator = document.querySelector('.auto-refresh-indicator');
                if (!indicator) {
                    indicator = document.createElement('div');
                    indicator.className = 'auto-refresh-indicator';
                    document.body.appendChild(indicator);
                }

                const iconClass = type === 'error' ? 'bi-exclamation-triangle' : 'bi-arrow-clockwise';
                const bgColor = type === 'error' ? 'linear-gradient(135deg, #dc3545 0%, #c82333 100%)' :
                                                'linear-gradient(135deg, #20c997 0%, #17a2b8 100%)';

                indicator.innerHTML = `<i class="bi ${iconClass} me-2"></i>${message}`;
                indicator.style.background = bgColor;
                indicator.classList.add('show');

                setTimeout(() => {
                    indicator.classList.remove('show');
                    setTimeout(() => {
                        if (indicator.parentNode) {
                            indicator.parentNode.removeChild(indicator);
                        }
                    }, 300);
                }, type === 'error' ? 4000 : 2000);
            }

            // Auto-hide success alert after 5 seconds
            const successAlert = document.querySelector('.alert-success');
            if (successAlert) {
                setTimeout(function() {
                    successAlert.style.opacity = '0';
                    setTimeout(function() {
                        successAlert.remove();
                    }, 300);
                }, 5000);
            }

            // Initialize button event listeners
            attachButtonEventListeners();

            // Smooth animations for meeting items
            const meetingItems = document.querySelectorAll('.meeting-active-item, .instruction-step');
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            meetingItems.forEach(item => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(20px)';
                item.style.transition = 'all 0.6s ease';
                observer.observe(item);
            });

            // Cleanup on page unload
            window.addEventListener('beforeunload', stopRealTimeUpdates);

            // Handle visibility change (pause updates when tab is hidden)
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    stopRealTimeUpdates();
                } else {
                    initializeRealTimeUpdates();
                }
            });

            // Manual refresh button (if exists)
            const refreshButton = document.querySelector('#manual-refresh');
            if (refreshButton) {
                refreshButton.addEventListener('click', function() {
                    updateMeetingStatuses();
                    updateFromAPI();
                    showRefreshIndicator('Memperbarui data...');
                });
            }
        });
    </script>
@endpush

