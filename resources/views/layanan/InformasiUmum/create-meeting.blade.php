@extends('Template.template')

@push('style')
    <link href="{{ Vite::asset('resources/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .create-meeting-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 80px 0;
            min-height: 85vh;
        }

        .form-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .form-card {
            background: white;
            border-radius: 25px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: none;
            position: relative;
        }

        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #20c997 0%, #17a2b8 50%, #6f42c1 100%);
        }

        .form-header {
            background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
            color: white;
            padding: 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .form-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 10px,
                rgba(255,255,255,0.05) 10px,
                rgba(255,255,255,0.05) 20px
            );
            animation: shimmer 20s linear infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }

        .form-header h2 {
            margin: 0;
            font-weight: 800;
            font-size: 2rem;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .form-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 1.1rem;
            position: relative;
            z-index: 1;
        }

        .form-body {
            padding: 50px;
            background: white;
        }

        .form-group {
            margin-bottom: 30px;
            position: relative;
        }

        .form-label {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 12px;
            display: block;
            font-size: 1rem;
        }

        .form-label i {
            color: #20c997;
            margin-right: 8px;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 15px 20px;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: 100%;
            background: #fafbfc;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
        }

        .form-control:focus {
            border-color: #20c997;
            box-shadow: 0 0 0 4px rgba(32, 201, 151, 0.1), inset 0 1px 3px rgba(0,0,0,0.05);
            outline: none;
            background: white;
            transform: translateY(-1px);
        }

        .form-control.is-invalid {
            border-color: #e74c3c;
            box-shadow: 0 0 0 4px rgba(231, 76, 60, 0.1);
        }

        .invalid-feedback {
            display: block;
            color: #e74c3c;
            font-size: 0.875rem;
            margin-top: 8px;
            font-weight: 500;
        }

        .btn-primary {
            background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
            border: none;
            padding: 18px 45px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            color: white;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(32, 201, 151, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(32, 201, 151, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            border: none;
            padding: 18px 45px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            color: white;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }

        .btn-secondary:hover {
            color: white;
            text-decoration: none;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
        }

        .generate-link-btn {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 25px;
            margin-top: 12px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            font-weight: 600;
            box-shadow: 0 3px 10px rgba(0, 123, 255, 0.3);
        }

        .generate-link-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
        }

        .form-buttons {
            text-align: center;
            margin-top: 40px;
            gap: 20px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .input-group {
            position: relative;
            display: flex;
            align-items: stretch;
            width: 100%;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-radius: 12px;
            overflow: hidden;
        }

        .input-group-text {
            background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
            border: 2px solid #20c997;
            color: white;
            padding: 15px 20px;
            border-radius: 12px 0 0 12px;
            font-weight: 700;
            border-right: none;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 12px 12px 0;
            background: white;
        }

        /* Select2 Styles */
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--multiple {
            border: 2px solid #e9ecef !important;
            border-radius: 12px !important;
            min-height: 55px !important;
            padding: 10px !important;
            background: #fafbfc !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%) !important;
            border: none !important;
            border-radius: 20px !important;
            color: white !important;
            font-size: 13px !important;
            padding: 8px 15px !important;
            margin: 4px !important;
            font-weight: 600 !important;
        }

        .select2-dropdown {
            border: 2px solid #20c997 !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
        }

        /* Simple Date Picker */
        .simple-datepicker {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            background: white;
            border: 2px solid #20c997;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            padding: 20px;
            min-width: 300px;
            display: none;
        }

        .datepicker-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .datepicker-nav {
            background: #20c997;
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        .datepicker-nav:hover {
            background: #17a2b8;
        }

        .datepicker-month-year {
            font-weight: 700;
            color: #2c3e50;
            font-size: 16px;
        }

        .datepicker-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }

        .datepicker-day-header {
            text-align: center;
            font-weight: 600;
            color: #6c757d;
            padding: 8px;
            font-size: 12px;
        }

        .datepicker-day {
            text-align: center;
            padding: 10px 8px;
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .datepicker-day:hover {
            background: rgba(32, 201, 151, 0.1);
            color: #20c997;
        }

        .datepicker-day.active {
            background: #20c997;
            color: white;
        }

        .datepicker-day.disabled {
            color: #ccc;
            cursor: not-allowed;
        }

        /* Simple Time Picker */
        .simple-timepicker {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            background: white;
            border: 2px solid #20c997;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            padding: 20px;
            display: none;
            min-width: 200px;
        }

        .time-display {
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .time-controls {
            display: flex;
            justify-content: space-around;
        }

        .time-control {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .time-btn {
            background: #20c997;
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            margin: 5px 0;
            transition: all 0.3s ease;
        }

        .time-btn:hover {
            background: #17a2b8;
            transform: scale(1.1);
        }

        .time-label {
            font-weight: 600;
            color: #6c757d;
            font-size: 12px;
            margin: 5px 0;
        }

        .form-text {
            color: #6c757d;
            font-size: 13px;
            margin-top: 8px;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .form-body {
                padding: 30px 25px;
            }
            .form-buttons {
                flex-direction: column;
                align-items: center;
            }
            .btn-primary, .btn-secondary {
                width: 100%;
                margin-bottom: 15px;
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
                <h1 class="display-4 text-white mb-4">Buat Meeting Baru</h1>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('informasi-umum.index') }}" class="text-white">Informasi Umum</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('meeting.detail') }}" class="text-white">Detail Meeting</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page">Buat Meeting</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header End -->

        <!-- Form Content Start -->
        <div class="create-meeting-section">
            <div class="form-container">
                <div class="form-card">
                    <div class="form-header">
                        <h2><i class="bi bi-plus-circle me-3"></i>Buat Meeting Baru</h2>
                        <p class="mb-0">Isi form di bawah untuk membuat meeting baru</p>
                    </div>

                    <div class="form-body">
                        <form action="{{ route('meeting.store') }}" method="POST" id="createMeetingForm">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="meeting_title" class="form-label">
                                            <i class="bi bi-journal-text"></i>Judul Meeting *
                                        </label>
                                        <input type="text"
                                               class="form-control @error('meeting_title') is-invalid @enderror"
                                               id="meeting_title"
                                               name="meeting_title"
                                               value="{{ old('meeting_title') }}"
                                               placeholder="Contoh: Rapat Koordinasi RW 05"
                                               required>
                                        @error('meeting_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="meeting_date" class="form-label">
                                            <i class="bi bi-calendar3"></i>Tanggal Meeting *
                                        </label>
                                        <input type="text"
                                               class="form-control datepicker @error('meeting_date') is-invalid @enderror"
                                               id="meeting_date"
                                               name="meeting_date"
                                               value="{{ old('meeting_date') }}"
                                               placeholder="Pilih tanggal meeting"
                                               required>
                                        @error('meeting_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="meeting_time" class="form-label">
                                            <i class="bi bi-clock"></i>Waktu Meeting *
                                        </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-clock"></i>
                                                </div>
                                            </div>
                                            <input type="text"
                                                   class="form-control timepicker @error('meeting_time') is-invalid @enderror"
                                                   id="meeting_time"
                                                   name="meeting_time"
                                                   value="{{ old('meeting_time', '19:00') }}"
                                                   placeholder="Pilih waktu meeting"
                                                   required>
                                        </div>
                                        @error('meeting_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="meet_link" class="form-label">
                                            <i class="bi bi-link-45deg"></i>Link Google Meet *
                                        </label>
                                        <input type="url"
                                               class="form-control @error('meet_link') is-invalid @enderror"
                                               id="meet_link"
                                               name="meet_link"
                                               value="{{ old('meet_link') }}"
                                               placeholder="https://meet.google.com/xxx-xxxx-xxx"
                                               required>
                                        <button type="button" class="generate-link-btn" onclick="generateMeetLink()">
                                            <i class="bi bi-magic"></i> Generate Sample Link
                                        </button>
                                        <div class="form-text">Contoh: https://meet.google.com/abc-defg-hij</div>
                                        @error('meet_link')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="participants" class="form-label">
                                            <i class="bi bi-people"></i>Peserta Meeting *
                                        </label>
                                        <select class="form-control select2 @error('participants') is-invalid @enderror"
                                                id="participants"
                                                name="participants[]"
                                                multiple="multiple"
                                                required>
                                            @for($i = 1; $i <= 10; $i++)
                                                <option value="RW {{ sprintf('%02d', $i) }}"
                                                    {{ in_array('RW ' . sprintf('%02d', $i), old('participants', [])) ? 'selected' : '' }}>
                                                    RW {{ sprintf('%02d', $i) }} (Ketua RW)
                                                </option>
                                            @endfor
                                            @for($i = 1; $i <= 63; $i++)
                                                @php
                                                    $rw = ceil($i / 6.3);
                                                    $rtValue = 'RT ' . sprintf('%02d', $i);
                                                @endphp
                                                <option value="{{ $rtValue }}"
                                                    {{ in_array($rtValue, old('participants', [])) ? 'selected' : '' }}>
                                                    RT {{ sprintf('%02d', $i) }} RW {{ sprintf('%02d', min($rw, 10)) }} (Ketua RT)
                                                </option>
                                            @endfor
                                            <option value="Semua RW" {{ in_array('Semua RW', old('participants', [])) ? 'selected' : '' }}>
                                                Semua Ketua RW
                                            </option>
                                            <option value="Semua RT" {{ in_array('Semua RT', old('participants', [])) ? 'selected' : '' }}>
                                                Semua Ketua RT
                                            </option>
                                            <option value="Perangkat Kelurahan" {{ in_array('Perangkat Kelurahan', old('participants', [])) ? 'selected' : '' }}>
                                                Perangkat Kelurahan
                                            </option>
                                        </select>
                                        <div class="form-text">Pilih peserta yang akan diundang ke meeting</div>
                                        @error('participants')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description" class="form-label">
                                    <i class="bi bi-chat-left-text"></i>Deskripsi Meeting (Opsional)
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description"
                                          name="description"
                                          rows="4"
                                          placeholder="Agenda atau deskripsi singkat meeting...">{{ old('description') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-buttons">
                                <a href="{{ route('meeting.detail') }}" class="btn-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali
                                </a>
                                <button type="button" class="btn-primary" id="submitBtn" onclick="confirmSubmit()">
                                    <i class="bi bi-check-circle me-2"></i>Buat Meeting
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Form Content End -->

        @include('layouts.footer')
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('library/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('library/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hide spinner
            setTimeout(() => {
                document.getElementById('spinner').classList.remove('show');
            }, 500);

            // Initialize Select2
            $('.select2').select2({
                placeholder: "Pilih peserta meeting...",
                allowClear: true,
                width: '100%',
                closeOnSelect: false
            });

            // Initialize Date Picker
            $('.datepicker').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                minDate: moment().add(1, 'day'),
                locale: {
                    format: 'YYYY-MM-DD',
                    firstDay: 1,
                    daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                    monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
                }
            });

            // Set default date to tomorrow
            const tomorrow = moment().add(1, 'day');
            $('#meeting_date').val(tomorrow.format('YYYY-MM-DD'));

            // Initialize Time Picker
            // $('.timepicker').timepicker({
            //     showInputs: false,
            //     showMeridian: false,
            //     defaultTime: '19:00',
            //     minuteStep: 15,
            //     template: 'modal'
            // });

            // Validate time format (HH:MM)
            const timeRegex = /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/;
            if (!timeRegex.test(time)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Format Waktu Salah',
                    text: 'Gunakan format HH:MM (contoh: 19:00)',
                    confirmButtonColor: '#e74c3c'
                });
                document.getElementById('meeting_time').focus();
                return;
            }

            // Show errors if any
            @if($errors->any())
                let errorList = '';
                @foreach($errors->all() as $error)
                    errorList += '• {{ $error }}\n';
                @endforeach

                Swal.fire({
                    icon: 'error',
                    title: 'Ada Kesalahan!',
                    text: errorList,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#e74c3c'
                });
            @endif
        });

        // Form Validation
        function confirmSubmit() {
            const title = document.getElementById('meeting_title').value.trim();
            const date = document.getElementById('meeting_date').value;
            const time = document.getElementById('meeting_time').value;
            const link = document.getElementById('meet_link').value.trim();
            const participants = $('#participants').val();

            // Validate fields
            if (!title) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Belum Lengkap',
                    text: 'Mohon isi judul meeting terlebih dahulu',
                    confirmButtonColor: '#ffc107'
                });
                return;
            }

            if (!date) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Belum Lengkap',
                    text: 'Mohon pilih tanggal meeting terlebih dahulu',
                    confirmButtonColor: '#ffc107'
                });
                return;
            }

            if (!time) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Belum Lengkap',
                    text: 'Mohon pilih waktu meeting terlebih dahulu',
                    confirmButtonColor: '#ffc107'
                });
                return;
            }

            if (!link) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Belum Lengkap',
                    text: 'Mohon isi link Google Meet terlebih dahulu',
                    confirmButtonColor: '#ffc107'
                });
                return;
            }

            if (!participants || participants.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peserta Belum Dipilih',
                    text: 'Mohon pilih minimal satu peserta meeting',
                    confirmButtonColor: '#ffc107'
                });
                return;
            }

            if (!link.includes('meet.google.com')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Link Tidak Valid',
                    text: 'Gunakan link Google Meet yang valid',
                    confirmButtonColor: '#e74c3c'
                });
                return;
            }

            // Check date is in future
            const meetingDate = new Date(date);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (meetingDate <= today) {
                Swal.fire({
                    icon: 'error',
                    title: 'Tanggal Tidak Valid',
                    text: 'Tanggal meeting harus setelah hari ini',
                    confirmButtonColor: '#e74c3c'
                });
                return;
            }

            // Format date for display
            const dateObj = new Date(date);
            const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                               'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

            const formattedDate = `${dayNames[dateObj.getDay()]}, ${dateObj.getDate()} ${monthNames[dateObj.getMonth()]} ${dateObj.getFullYear()}`;

            // Show confirmation
            Swal.fire({
                title: 'Konfirmasi Pembuatan Meeting',
                html: `
                    <div style="text-align: left; background: #f8f9fa; padding: 20px; border-radius: 10px; margin: 15px 0;">
                        <p><strong>📝 Judul:</strong> ${title}</p>
                        <p><strong>📅 Tanggal:</strong> ${formattedDate}</p>
                        <p><strong>⏰ Waktu:</strong> ${time} WIB</p>
                        <p><strong>👥 Peserta:</strong> ${participants.length} orang</p>
                        <p><strong>🔗 Link:</strong> ${link.substring(0, 40)}...</p>
                    </div>
                    <p><strong>Apakah data sudah benar?</strong></p>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#20c997',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Buat Meeting!',
                cancelButtonText: 'Periksa Lagi'
            }).then((result) => {
                if (result.isConfirmed) {
                    submitForm();
                }
            });
        }

        function submitForm() {
            Swal.fire({
                title: 'Membuat Meeting...',
                html: `
                    <div style="text-align: center;">
                        <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;"></div>
                        <p>Sedang menyimpan data meeting</p>
                        <small class="text-muted">Mohon tunggu sebentar...</small>
                    </div>
                `,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false
            });

            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
            submitBtn.disabled = true;

            setTimeout(() => {
                document.getElementById('createMeetingForm').submit();
            }, 1000);
        }

        // Real-time validation
        document.getElementById('meeting_title').addEventListener('input', function() {
            if (this.classList.contains('is-invalid') && this.value.trim() !== '') {
                this.classList.remove('is-invalid');
            }
        });

        document.getElementById('meet_link').addEventListener('input', function() {
            if (this.classList.contains('is-invalid') && this.value.includes('meet.google.com')) {
                this.classList.remove('is-invalid');
            }
        });

        $('#participants').on('change', function() {
            const selected = $(this).val();
            if ($(this).hasClass('is-invalid') && selected && selected.length > 0) {
                $(this).removeClass('is-invalid');
            }
        });
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
