@extends('Template.template')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">

    <style>
        .form-control:focus {
            border-color: #6777ef;
            box-shadow: 0 0 0 0.2rem rgba(103, 119, 239, 0.25);
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .section-divider {
            border-top: 2px solid #e9ecef;
            margin: 30px 0 20px 0;
            padding-top: 20px;
        }

        .required {
            color: #dc3545;
        }

        .rt-rw-section {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .form-group label {
            font-weight: 600;
            color: #34395e;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .form-control:hover {
            border-color: #6777ef;
        }

        .btn {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
        }

        .alert {
            border-radius: 8px;
            border: none;
        }

        .alert-info {
            background: linear-gradient(135deg, #e3f2fd 0%, #f8f9fa 100%);
            color: #1976d2;
            border-left: 4px solid #2196f3;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #f8f9fa 100%);
            color: #856404;
            border-left: 4px solid #ffc107;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        /* Signature Pad Styles */
        .signature-section {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .signature-pad {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            background-color: white;
            cursor: crosshair;
            display: block;
            margin: 0 auto;
        }

        .signature-controls {
            margin-top: 10px;
            text-align: center;
        }

        .signature-controls button {
            margin: 0 5px;
        }

        .signature-validation-error {
            border: 2px solid #dc3545 !important;
            background-color: #f8d7da !important;
        }

        .psu-type-section {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .psu-internal-indicator {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 12px;
            margin-bottom: 15px;
            text-align: center;
        }

        .psu-external-indicator {
            background: linear-gradient(135deg, #6f42c1 0%, #5a2d91 100%);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 12px;
            margin-bottom: 15px;
            text-align: center;
        }

        .existing-signature {
            border: 2px solid #28a745;
            border-radius: 8px;
            padding: 10px;
            background-color: #d4edda;
            text-align: center;
            margin-bottom: 10px;
        }

        .existing-signature img {
            max-width: 200px;
            max-height: 100px;
            border: 1px solid #28a745;
            border-radius: 4px;
            background-color: white;
        }

        .edit-warning {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }

        .current-status-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            color: #0c5460;
            border: 1px solid #bee5eb;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
@endpush

@section('Dashboard')
    @include('admin.dashboard.header')
    @include('admin.dashboard.sidebar')

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ $pageTitle }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('psu.index') }}">Data PSU</a>
                    </div>
                    <div class="breadcrumb-item">
                        <a href="{{ route('psu.show', $psu->id) }}">Detail PSU</a>
                    </div>
                    <div class="breadcrumb-item">Edit PSU</div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <!-- Current Status Info -->
                    <div class="current-status-info">
                        <div class="d-flex align-items-center">
                            <div class="mr-3">
                                <i class="fas fa-info-circle fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Status Saat Ini: {!! $psu->status_badge !!}</h6>
                                <small>
                                    <strong>Nomor Surat:</strong> {{ $psu->nomor_surat }} |
                                    <strong>Dibuat:</strong> {{ $psu->formatted_created_date }} |
                                    <strong>Jenis:</strong> {{ $psu->ditujukan_kepada_display }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Warning -->
                    <div class="edit-warning">
                        <div class="d-flex align-items-center">
                            <div class="mr-3">
                                <i class="fas fa-exclamation-triangle fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Perhatian - Mode Edit</h6>
                                <p class="mb-0 small">
                                    Perubahan pada data PSU ini akan mengubah informasi yang sudah tersimpan.
                                    Jika mengubah RT/RW/Bulan, nomor surat akan regenerasi otomatis.
                                    @if($psu->status !== 'pending_rt' && $psu->status !== 'auto_approved')
                                        <br><strong>Jika mengubah "Ditujukan Kepada", proses persetujuan akan dimulai ulang.</strong>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>Edit PSU (Permohonan Surat Umum)</h4>
                            <div class="card-header-action">
                                <a href="{{ route('psu.show', $psu->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Lihat Detail
                                </a>
                                @if($psu->canPreviewPDF())
                                <a href="{{ route('psu.preview-pdf', $psu->id) }}" class="btn btn-secondary btn-sm" target="_blank">
                                    <i class="fas fa-file-pdf"></i> Preview PDF
                                </a>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="editForm">
                                @csrf
                                @method('PUT')

                                {{-- Data Pemohon --}}
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="text-primary">
                                            <i class="fas fa-user"></i> Data Pemohon
                                        </h5>
                                        <hr>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Lengkap <span class="required">*</span></label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="nama_lengkap"
                                                   value="{{ $psu->nama_lengkap }}"
                                                   placeholder="Nama lengkap pemohon">
                                        </div>

                                        <div class="form-group">
                                            <label>Alamat <span class="required">*</span></label>
                                            <textarea class="form-control"
                                                      name="alamat"
                                                      rows="3"
                                                      placeholder="Alamat lengkap">{{ $psu->alamat }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Pekerjaan <span class="required">*</span></label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="pekerjaan"
                                                   value="{{ $psu->pekerjaan }}"
                                                   placeholder="Pekerjaan">
                                        </div>

                                        <div class="form-group">
                                            <label>Jenis Kelamin <span class="required">*</span></label>
                                            <select class="form-control" name="jenis_kelamin">
                                                <option value="">Pilih Jenis Kelamin</option>
                                                <option value="L" {{ $psu->jenis_kelamin === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                                <option value="P" {{ $psu->jenis_kelamin === 'P' ? 'selected' : '' }}>Perempuan</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Tempat Lahir <span class="required">*</span></label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="tempat_lahir"
                                                   value="{{ $psu->tempat_lahir }}"
                                                   placeholder="Tempat lahir">
                                        </div>

                                        <div class="form-group">
                                            <label>Tanggal Lahir <span class="required">*</span></label>
                                            <input type="date"
                                                   class="form-control"
                                                   name="tanggal_lahir"
                                                   value="{{ $psu->tanggal_lahir ? $psu->tanggal_lahir->format('Y-m-d') : '' }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Agama <span class="required">*</span></label>
                                            <select class="form-control" name="agama">
                                                <option value="">Pilih Agama</option>
                                                <option value="Islam" {{ $psu->agama === 'Islam' ? 'selected' : '' }}>Islam</option>
                                                <option value="Kristen" {{ $psu->agama === 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                                <option value="Katolik" {{ $psu->agama === 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                                <option value="Hindu" {{ $psu->agama === 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                                <option value="Buddha" {{ $psu->agama === 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                                <option value="Konghucu" {{ $psu->agama === 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Status Perkawinan <span class="required">*</span></label>
                                            <select class="form-control" name="status_perkawinan">
                                                <option value="">Pilih Status</option>
                                                <option value="Belum Kawin" {{ $psu->status_perkawinan === 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                                                <option value="Kawin" {{ $psu->status_perkawinan === 'Kawin' ? 'selected' : '' }}>Kawin</option>
                                                <option value="Cerai Hidup" {{ $psu->status_perkawinan === 'Cerai Hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                                                <option value="Cerai Mati" {{ $psu->status_perkawinan === 'Cerai Mati' ? 'selected' : '' }}>Cerai Mati</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Kewarganegaraan <span class="required">*</span></label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="kewarganegaraan"
                                                   value="{{ $psu->kewarganegaraan }}"
                                                   placeholder="Kewarganegaraan">
                                        </div>

                                        <div class="form-group">
                                            <label>Nomor KK/KTP <span class="required">*</span></label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="nomor_kk"
                                                   value="{{ $psu->nomor_kk }}"
                                                   placeholder="Nomor KK atau KTP"
                                                   maxlength="20">
                                        </div>

                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email"
                                                   class="form-control"
                                                   value="{{ $psu->user->email ?? '' }}"
                                                   readonly>
                                        </div>

                                        <div class="form-group">
                                            <label>No. Telepon</label>
                                            <input type="text"
                                                   class="form-control"
                                                   value="{{ $psu->user->telp ?? '' }}"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>

                                {{-- RT/RW Selection --}}
                                <div class="rt-rw-section">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-map-marker-alt"></i> Lokasi RT/RW
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>RW <span class="required">*</span></label>
                                                <select name="rw" id="rw-select-psu" class="form-control" required>
                                                    <option value="">Pilih RW</option>
                                                    @foreach($availableRW as $rw)
                                                        <option value="{{ $rw['value'] }}"
                                                            data-rt-count="{{ $rw['rt_count'] }}"
                                                            {{ $psu->rw === $rw['value'] ? 'selected' : '' }}>
                                                            {{ $rw['label'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="form-text text-muted">Pilih RW sesuai dengan lokasi Anda</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>RT <span class="required">*</span></label>
                                                <select name="rt" id="rt-select-psu" class="form-control" required>
                                                    <option value="">Pilih RT</option>
                                                    {{-- RT options akan diisi via JavaScript --}}
                                                </select>
                                                <small class="form-text text-muted">Pilih RT sesuai dengan lokasi Anda</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <strong>Perhatian:</strong> Mengubah RT/RW akan menyebabkan nomor surat regenerasi otomatis.
                                    </div>
                                </div>

                                {{-- Jenis PSU dan Tujuan --}}
                                <div class="psu-type-section">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-file-signature"></i> Jenis dan Tujuan PSU
                                    </h6>

                                    <!-- PSU Type Indicator -->
                                    <div id="psuTypeIndicator"></div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Ditujukan Kepada <span class="required">*</span></label>
                                                <select name="ditujukan_kepada" id="ditujukanKepada" class="form-control" required>
                                                    <option value="">Pilih Tujuan</option>
                                                    <option value="warga_rt" {{ $psu->ditujukan_kepada === 'warga_rt' ? 'selected' : '' }}>Warga RT (Internal)</option>
                                                    <option value="warga_rw" {{ $psu->ditujukan_kepada === 'warga_rw' ? 'selected' : '' }}>Warga RW (Internal)</option>
                                                    <option value="rt" {{ $psu->ditujukan_kepada === 'rt' ? 'selected' : '' }}>RT (Persetujuan)</option>
                                                    <option value="rw" {{ $psu->ditujukan_kepada === 'rw' ? 'selected' : '' }}>RW (Persetujuan)</option>
                                                    <option value="kelurahan" {{ $psu->ditujukan_kepada === 'kelurahan' ? 'selected' : '' }}>Kelurahan (Persetujuan)</option>
                                                </select>
                                                <small class="form-text text-muted">Pilih siapa yang menjadi tujuan PSU ini</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Bulan <span class="required">*</span></label>
                                                <select name="bulan" class="form-control" required>
                                                    <option value="">Pilih Bulan</option>
                                                    @for($i = 1; $i <= 12; $i++)
                                                        <option value="{{ $i }}" {{ $psu->bulan == $i ? 'selected' : '' }}>
                                                            {{ \Carbon\Carbon::create()->month($i)->locale('id')->monthName }}
                                                        </option>
                                                    @endfor
                                                </select>
                                                <small class="form-text text-muted">Bulan untuk nomor surat</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nama Ketua RT</label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="nama_ketua_rt"
                                                       value="{{ $psu->nama_ketua_rt }}"
                                                       placeholder="Nama Ketua RT (opsional)">
                                                <small class="form-text text-muted">Opsional - jika tidak diisi akan menggunakan default</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nama Ketua RW</label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="nama_ketua_rw"
                                                       value="{{ $psu->nama_ketua_rw }}"
                                                       placeholder="Nama Ketua RW (opsional)">
                                                <small class="form-text text-muted">Opsional - jika tidak diisi akan menggunakan default</small>
                                            </div>
                                        </div>
                                    </div>

                                    @if($psu->status !== 'pending_rt' && $psu->status !== 'auto_approved')
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <strong>Peringatan:</strong> Mengubah "Ditujukan Kepada" akan membuat proses persetujuan dimulai ulang dan data persetujuan sebelumnya akan dihapus.
                                    </div>
                                    @endif
                                </div>

                                {{-- Detail Surat --}}
                                <div class="section-divider">
                                    <div class="row">
                                        <div class="col-12">
                                            <h5 class="text-primary">
                                                <i class="fas fa-clipboard-list"></i> Detail Surat
                                            </h5>
                                            <hr>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Sifat Surat <span class="required">*</span></label>
                                                <select name="sifat" class="form-control" required>
                                                    <option value="">Pilih Sifat</option>
                                                    <option value="Biasa" {{ $psu->sifat === 'Biasa' ? 'selected' : '' }}>Biasa</option>
                                                    <option value="Penting" {{ $psu->sifat === 'Penting' ? 'selected' : '' }}>Penting</option>
                                                    <option value="Segera" {{ $psu->sifat === 'Segera' ? 'selected' : '' }}>Segera</option>
                                                    <option value="Rahasia" {{ $psu->sifat === 'Rahasia' ? 'selected' : '' }}>Rahasia</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Hal <span class="required">*</span></label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="hal"
                                                       value="{{ $psu->hal }}"
                                                       placeholder="Perihal/subjek surat"
                                                       required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Tujuan Internal</label>
                                                <select name="tujuan_internal" class="form-control">
                                                    <option value="">Pilih Tujuan Internal</option>
                                                    <option value="rt" {{ $psu->tujuan_internal === 'rt' ? 'selected' : '' }}>RT</option>
                                                    <option value="rw" {{ $psu->tujuan_internal === 'rw' ? 'selected' : '' }}>RW</option>
                                                    <option value="kelurahan" {{ $psu->tujuan_internal === 'kelurahan' ? 'selected' : '' }}>Kelurahan</option>
                                                    <option value="kecamatan" {{ $psu->tujuan_internal === 'kecamatan' ? 'selected' : '' }}>Kecamatan</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Tujuan Eksternal</label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="tujuan_eksternal"
                                                       value="{{ $psu->tujuan_eksternal }}"
                                                       placeholder="Nama instansi/perorangan eksternal">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Isi Surat <span class="required">*</span></label>
                                                <textarea class="form-control"
                                                          name="isi_surat"
                                                          rows="5"
                                                          placeholder="Isi lengkap surat PSU"
                                                          required>{{ $psu->isi_surat }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>File Lampiran (Opsional)</label>
                                                @if($psu->file_lampiran && count($psu->file_lampiran) > 0)
                                                    <div class="mb-2">
                                                        <small class="text-muted">File lampiran saat ini:</small>
                                                        <ul class="small">
                                                            @foreach($psu->file_lampiran as $index => $file)
                                                                <li>
                                                                    <a href="{{ Storage::url($file) }}" target="_blank">
                                                                        File {{ $index + 1 }} - {{ basename($file) }}
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                                <input type="file"
                                                       class="form-control"
                                                       name="file_lampiran[]"
                                                       multiple
                                                       accept=".pdf,.jpg,.jpeg,.png">
                                                <small class="form-text text-muted">
                                                    Format: PDF, JPG, JPEG, PNG. Maksimal 2MB per file. Bisa pilih beberapa file.
                                                    File baru akan ditambahkan ke file yang sudah ada.
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Tanda Tangan Digital Pemohon --}}
                                <div class="signature-section">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-signature"></i> Tanda Tangan Digital
                                    </h6>

                                    <div class="row justify-content-center">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label>Tanda Tangan Pemohon</label>

                                                @if($psu->hasPemohonSignature())
                                                    <div class="existing-signature">
                                                        <small class="text-success d-block mb-2">
                                                            <i class="fas fa-check-circle"></i> Tanda tangan tersimpan
                                                        </small>
                                                        <img src="{{ Storage::url($psu->ttd_pemohon) }}" alt="Tanda Tangan Pemohon">
                                                        <div class="mt-2">
                                                            <button type="button" class="btn btn-warning btn-sm" id="changeSignature">
                                                                <i class="fas fa-edit"></i> Ubah Tanda Tangan
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endif

                                                <div id="signatureSection" style="{{ $psu->hasPemohonSignature() ? 'display: none;' : '' }}">
                                                    <canvas id="signaturePadPemohon"
                                                            class="signature-pad"
                                                            width="500"
                                                            height="250"></canvas>
                                                    <div class="signature-controls">
                                                        <button type="button" class="btn btn-secondary btn-sm" id="clearSignaturePemohon">
                                                            <i class="fas fa-eraser"></i> Hapus Tanda Tangan
                                                        </button>
                                                        @if($psu->hasPemohonSignature())
                                                            <button type="button" class="btn btn-info btn-sm" id="cancelChangeSignature">
                                                                <i class="fas fa-times"></i> Batal Ubah
                                                            </button>
                                                        @endif
                                                    </div>
                                                    <small class="form-text text-muted">Gunakan mouse atau touch untuk membuat tanda tangan pemohon</small>
                                                </div>

                                                <input type="hidden" name="ttd_pemohon" id="ttdPemohonInput">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Catatan:</strong>
                                        <ul class="small mb-0 mt-2">
                                            @if($psu->hasPemohonSignature())
                                                <li>Tanda tangan sudah tersimpan, Anda dapat mengubahnya jika diperlukan</li>
                                            @else
                                                <li>Tanda tangan pemohon wajib diisi</li>
                                            @endif
                                            <li>Pastikan tanda tangan sudah sesuai sebelum menyimpan</li>
                                            <li>Gunakan mouse atau sentuh layar untuk membuat tanda tangan</li>
                                            <li>Tanda tangan akan disertakan dalam dokumen resmi</li>
                                        </ul>
                                    </div>
                                </div>

                                {{-- Catatan --}}
                                <div class="section-divider">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="card-title">Catatan:</h6>
                                                    <ul class="small text-muted">
                                                        <li>Pastikan semua data telah diisi dengan benar</li>
                                                        <li>Perubahan akan langsung tersimpan dan mengubah data yang sudah ada</li>
                                                        <li>Jika mengubah RT/RW/Bulan, nomor surat akan regenerasi otomatis</li>
                                                        @if($psu->status !== 'pending_rt' && $psu->status !== 'auto_approved')
                                                            <li class="text-warning"><strong>Mengubah "Ditujukan Kepada" akan memulai proses persetujuan dari awal</strong></li>
                                                        @endif
                                                        <li>Anda dapat melihat status permohonan di halaman daftar PSU</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer text-right border-0 pt-3">
                                    <button type="submit" class="btn btn-primary" id="saveBtn">
                                        <i class="fas fa-save"></i> Simpan Perubahan
                                    </button>
                                    <a href="{{ route('psu.show', $psu->id) }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Batal
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- General JS Scripts -->
    <script src="{{ asset('library/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('library/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Signature Pad -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js"></script>

    <script>
        // Global variables for signature pad
        let signaturePadPemohon;
        let originalDitujukanKepada = '{{ $psu->ditujukan_kepada }}';
        let hasExistingSignature = {{ $psu->hasPemohonSignature() ? 'true' : 'false' }};

        $(document).ready(function() {
            // Initialize signature pad
            initializeSignaturePad();

            // Initialize RW-RT for PSU
            initializePsuRwRt();

            // Initialize PSU type handling
            initializePsuTypeHandling();

            // Initialize signature change handlers
            initializeSignatureHandlers();

            // Form submission
            $('#editForm').on('submit', function(e) {
                e.preventDefault();

                // Validate required fields
                if (!validateForm()) {
                    return;
                }

                const form = $(this);
                const btn = $('#saveBtn');

                // Reset form state
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                $('.signature-validation-error').removeClass('signature-validation-error');

                btn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

                // Get signature data if signature section is visible
                if ($('#signatureSection').is(':visible') && !signaturePadPemohon.isEmpty()) {
                    $('#ttdPemohonInput').val(signaturePadPemohon.toDataURL());
                }

                // Create FormData
                const formData = new FormData(this);

                // Check if workflow will change
                const currentDitujukanKepada = $('[name="ditujukan_kepada"]').val();
                if (originalDitujukanKepada !== currentDitujuanKepada &&
                    '{{ $psu->status }}' !== 'pending_rt' &&
                    '{{ $psu->status }}' !== 'auto_approved') {

                    Swal.fire({
                        title: 'Konfirmasi Perubahan Workflow',
                        html: `
                            <div class="text-left">
                                <p>Anda mengubah "Ditujukan Kepada" dari <strong>${getDisplayText(originalDitujuanKepada)}</strong>
                                ke <strong>${getDisplayText(currentDitujuanKepada)}</strong>.</p>
                                <hr>
                                <p class="text-warning"><strong>Perhatian:</strong></p>
                                <ul class="small">
                                    <li>Proses persetujuan akan dimulai ulang</li>
                                    <li>Data persetujuan sebelumnya akan dihapus</li>
                                    <li>Status akan kembali ke pending</li>
                                </ul>
                            </div>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Lanjutkan',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#f39c12',
                        cancelButtonColor: '#6c757d'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            submitForm(formData, btn);
                        } else {
                            btn.html('<i class="fas fa-save"></i> Simpan Perubahan').prop('disabled', false);
                        }
                    });
                } else {
                    submitForm(formData, btn);
                }
            });

            // Set initial PSU type indicator
            $('#ditujukanKepada').trigger('change');

            console.log('✅ PSU Edit form initialized successfully');
        });

        function submitForm(formData, btn) {
            $.ajax({
                url: "{{ route('psu.update', $psu->id) }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if(response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            window.location.href = "{{ route('psu.show', $psu->id) }}";
                        });
                    }
                },
                error: function(xhr) {
                    btn.html('<i class="fas fa-save"></i> Simpan Perubahan').prop('disabled', false);

                    if(xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessages = [];

                        Object.keys(errors).forEach(key => {
                            const field = $(`[name="${key}"]`);
                            field.addClass('is-invalid');

                            if (!field.siblings('.invalid-feedback').length) {
                                field.after(`<div class="invalid-feedback">${errors[key][0]}</div>`);
                            }

                            errorMessages.push(errors[key][0]);
                        });

                        // Show validation errors in SweetAlert
                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            html: errorMessages.map((msg, index) => `${index + 1}. ${msg}`).join('<br>'),
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan data.',
                            'error'
                        );
                    }
                }
            });
        }

        function getDisplayText(value) {
            const mapping = {
                'warga_rt': 'Warga RT (Internal)',
                'warga_rw': 'Warga RW (Internal)',
                'rt': 'RT (Persetujuan)',
                'rw': 'RW (Persetujuan)',
                'kelurahan': 'Kelurahan (Persetujuan)'
            };
            return mapping[value] || value;
        }

        function initializePsuRwRt() {
            // RW Change Handler
            $('#rw-select-psu').on('change', function() {
                const selectedRW = $(this).val();
                const rtSelect = $('#rt-select-psu');

                // Clear RT selection
                rtSelect.empty().append('<option value="">Pilih RT</option>').prop('disabled', true);

                if (selectedRW) {
                    // Get RT count from selected option
                    const rtCount = $(this).find('option:selected').data('rt-count');

                    // Generate RT options for selected RW
                    for (let i = 1; i <= rtCount; i++) {
                        const rt = i === 10 ? '10' : i.toString().padStart(2, '0');
                        const label = `RT ${rt}`;
                        rtSelect.append(`<option value="${rt}">${label}</option>`);
                    }

                    // Enable RT select
                    rtSelect.prop('disabled', false);

                    // Set current RT value
                    const currentRT = '{{ $psu->rt }}';
                    if (currentRT) {
                        rtSelect.val(currentRT);
                    }
                }
            });

            // Initialize with current values
            $('#rw-select-psu').trigger('change');

            console.log('✅ PSU RW-RT initialized');
        }

        function initializePsuTypeHandling() {
            $('#ditujukanKepada').on('change', function() {
                const selectedValue = $(this).val();
                const indicator = $('#psuTypeIndicator');

                if (selectedValue === 'warga_rt' || selectedValue === 'warga_rw') {
                    // PSU Internal
                    indicator.removeClass('psu-external-indicator')
                             .addClass('psu-internal-indicator')
                             .html('<i class="fas fa-check-double"></i> PSU Internal - Langsung Selesai (Auto Approved)')
                             .show();
                } else if (selectedValue === 'rt' || selectedValue === 'rw' || selectedValue === 'kelurahan') {
                    // PSU External
                    indicator.removeClass('psu-internal-indicator')
                             .addClass('psu-external-indicator')
                             .html('<i class="fas fa-file-signature"></i> PSU Eksternal - Memerlukan Persetujuan')
                             .show();
                } else {
                    indicator.hide();
                }
            });

            console.log('✅ PSU Type handling initialized');
        }

        function initializeSignatureHandlers() {
            // Change signature button
            $('#changeSignature').on('click', function() {
                $('.existing-signature').hide();
                $('#signatureSection').show();
                signaturePadPemohon.clear();
            });

            // Cancel change signature button
            $('#cancelChangeSignature').on('click', function() {
                $('#signatureSection').hide();
                $('.existing-signature').show();
                signaturePadPemohon.clear();
                $('#ttdPemohonInput').val('');
            });

            console.log('✅ Signature handlers initialized');
        }

        // Initialize signature pad
        function initializeSignaturePad() {
            const canvasPemohon = document.getElementById('signaturePadPemohon');

            signaturePadPemohon = new SignaturePad(canvasPemohon, {
                backgroundColor: 'rgba(255,255,255,0)',
                penColor: 'rgb(0, 0, 0)',
                velocityFilterWeight: 0.7,
                minWidth: 0.5,
                maxWidth: 2.5,
                throttle: 16,
                minPointDistance: 3,
            });

            // Clear signature button
            $('#clearSignaturePemohon').on('click', function() {
                signaturePadPemohon.clear();
                $('#ttdPemohonInput').val('');
                $('#signaturePadPemohon').removeClass('signature-validation-error');
            });

            // Auto-save signature when changed
            signaturePadPemohon.onEnd = function() {
                $('#ttdPemohonInput').val(signaturePadPemohon.toDataURL());
                $('#signaturePadPemohon').removeClass('signature-validation-error');
            };

            console.log('✅ Signature pad initialized');
        }

        function validateForm() {
            let isValid = true;
            const errors = [];
            const requiredFields = [
                { name: 'nama_lengkap', label: 'Nama Lengkap' },
                { name: 'alamat', label: 'Alamat' },
                { name: 'pekerjaan', label: 'Pekerjaan' },
                { name: 'jenis_kelamin', label: 'Jenis Kelamin' },
                { name: 'tempat_lahir', label: 'Tempat Lahir' },
                { name: 'tanggal_lahir', label: 'Tanggal Lahir' },
                { name: 'agama', label: 'Agama' },
                { name: 'status_perkawinan', label: 'Status Perkawinan' },
                { name: 'kewarganegaraan', label: 'Kewarganegaraan' },
                { name: 'nomor_kk', label: 'Nomor KK/KTP' },
                { name: 'rt', label: 'RT' },
                { name: 'rw', label: 'RW' },
                { name: 'ditujukan_kepada', label: 'Ditujukan Kepada' },
                { name: 'bulan', label: 'Bulan' },
                { name: 'sifat', label: 'Sifat Surat' },
                { name: 'hal', label: 'Hal' },
                { name: 'isi_surat', label: 'Isi Surat' }
            ];

            // Check required fields
            requiredFields.forEach(field => {
                const value = $(`[name="${field.name}"]`).val();
                if (!value || value.trim() === '') {
                    errors.push(`${field.label} wajib diisi`);
                    $(`[name="${field.name}"]`).addClass('is-invalid');
                    isValid = false;
                } else {
                    $(`[name="${field.name}"]`).removeClass('is-invalid');
                }
            });

            // Check signature only if changing signature or no existing signature
            if ($('#signatureSection').is(':visible')) {
                if (signaturePadPemohon.isEmpty()) {
                    errors.push('Tanda tangan pemohon wajib diisi');
                    $('#signaturePadPemohon').addClass('signature-validation-error');
                    isValid = false;
                } else {
                    $('#signaturePadPemohon').removeClass('signature-validation-error');
                }
            } else if (!hasExistingSignature) {
                errors.push('Tanda tangan pemohon wajib diisi');
                isValid = false;
            }

            // Show validation errors with SweetAlert
            if (!isValid) {
                let errorMessage = 'Mohon lengkapi data berikut:\n\n';
                errors.forEach((error, index) => {
                    errorMessage += `${index + 1}. ${error}\n`;
                });

                Swal.fire({
                    icon: 'error',
                    title: 'Data Belum Lengkap',
                    text: errorMessage,
                    confirmButtonText: 'OK'
                });

                // Scroll to first error
                const firstError = $('.is-invalid, .signature-validation-error').first();
                if (firstError.length) {
                    $('html, body').animate({
                        scrollTop: firstError.offset().top - 100
                    }, 500);
                }
            }

            return isValid;
        }
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
