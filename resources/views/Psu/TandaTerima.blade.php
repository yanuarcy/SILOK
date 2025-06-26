<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Tanda Terima - {{ $nomor_agenda ?? 'AG.001/KEL/06/2025' }}</title>
    <style>
        @page {
            margin: 0.99in 1in 0.19in 0.95in;
            size: Legal;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            line-height: 1.5;
            color: #000;
        }

        .container {
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid black;
            padding-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 16px;
            font-weight: bold;
        }

        .header p {
            margin: 5px 0;
            font-size: 11px;
        }

        .document-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 30px 0;
            text-decoration: underline;
        }

        .nomor-agenda {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .content {
            margin: 20px 0;
            position: relative;
        }

        .table-info {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .table-info td {
            padding: 8px;
            vertical-align: top;
            border: 1px solid black;
        }

        .table-info .label {
            width: 30%;
            font-weight: bold;
            background-color: #f5f5f5;
        }

        .table-info .colon {
            width: 5%;
            text-align: center;
        }

        .table-info .value {
            width: 65%;
        }

        /* Signature section - FIXED: align right dengan 1 signature saja */
        .signatures {
            margin-top: 40px;
            align-items: right;
        }

        .signature-section {
            display: inline-block;
            text-align: right;
            width: 280px;
        }

        .signature-header {
            font-weight: bold;
            margin-bottom: 15px;
            font-size: 12px;
            line-height: 1.3;
        }

        /* Container untuk TTD dan Stempel yang menyatu */
        .signature-container {
            position: relative;
            height: 100px;
            margin: 15px 0;
            display: flex;
            /* justify-content: center;
            align-items: center; */
        }

        /* TTD layer */
        .ttd-signature {
            position: absolute;
            z-index: 2;
            top: 10px;
            left: 95%;
            transform: translateX(-50%);
        }

        .ttd-signature img {
            max-height: 70px;
            max-width: 180px;
            filter: contrast(1.2) brightness(0.9);
        }

        /* Stempel layer - overlap dengan TTD */
        .stamp-overlay {
            position: absolute;
            z-index: 1;
            top: 10px;
            right: 15px;
            transform: translateY(-20%);
        }

        .stamp-overlay img {
            max-height: 100px;
            max-width: 200px;
            filter: contrast(1.3) saturate(1.2);
        }

        /* Nama dengan tanda kurung */
        .signature-name {
            margin-top: 20px;
            font-weight: bold;
            font-size: 12px;
        }

        /* Info section di bawah tabel */
        .info-section {
            margin-top: 50px;
            display: flex;
            gap: 40px;
            justify-content: space-between;
        }

        .info-left {
            flex: 2;
            max-width: 65%;
        }

        .info-right {
            flex: 1;
            max-width: 35%;
        }

        .info-item {
            margin-bottom: 10px;
            font-size: 11px;
            line-height: 1.4;
        }

        .info-item strong {
            font-weight: bold;
            color: #333;
        }

        .note-box {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 3px;
        }

        .note-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
            font-size: 12px;
        }

        .note-list {
            margin: 0;
            padding-left: 18px;
            font-size: 11px;
            line-height: 1.5;
        }

        .note-list li {
            margin-bottom: 5px;
        }

        .watermark {
            position: fixed;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 88px;
            color: rgba(0, 0, 0, 0.05);
            z-index: -1;
            font-weight: bold;
            pointer-events: none;
        }

        /* Footer */
        .footer {
            margin-top: 60px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .signatures {
                text-align: center;
            }

            .signature-section {
                width: 100%;
            }

            .info-section {
                flex-direction: column;
                gap: 20px;
                margin-top: 50px;
            }

            .info-left,
            .info-right {
                max-width: 100%;
            }
        }

        @media print {
            body {
                margin: 15px;
            }

            .watermark {
                display: block;
                color: rgba(0, 0, 0, 0.08);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Watermark -->
        <div class="watermark">TANDA TERIMA PSU</div>

        <!-- Header Kelurahan -->
        <div class="header">
            <h1>Pemerintah Kota Surabaya</h1>
            <h2>Kelurahan {{ $kelurahan ?? 'Kelurahan Surabaya' }}</h2>
            <p>Alamat: Jl. Kelurahan No. 123, Surabaya</p>
            <p>Telepon: (031) 1234567 | Email: kelurahan@surabaya.go.id</p>
        </div>

        <!-- Document Title -->
        <div class="document-title">
            Surat Tanda Terima
        </div>

        <!-- Nomor Agenda -->
        <div class="nomor-agenda">
            Nomor: {{ $nomor_agenda ?? 'AG.001/KEL/06/2025' }}
        </div>

        <!-- Content -->
        <div class="content">
            <p>Dengan ini menyatakan bahwa telah menerima berkas permohonan dari:</p>

            <!-- Table Info Pemohon -->
            <table class="table-info">
                <tr>
                    <td class="label">Nama Pemohon</td>
                    <td class="colon">:</td>
                    <td class="value">{{ $psu->nama_lengkap ?? 'John Doe' }}</td>
                </tr>
                <tr>
                    <td class="label">NIK/No. KK</td>
                    <td class="colon">:</td>
                    <td class="value">{{ $psu->nomor_kk ?? '1234567890123456' }}</td>
                </tr>
                <tr>
                    <td class="label">Alamat</td>
                    <td class="colon">:</td>
                    <td class="value">{{ $psu->alamat ?? 'Jl. Contoh No. 123, Surabaya' }}</td>
                </tr>
                <tr>
                    <td class="label">RT/RW</td>
                    <td class="colon">:</td>
                    <td class="value">{{ sprintf('%02d/%02d', $psu->rt ?? 1, $psu->rw ?? 1) }}</td>
                </tr>
                <tr>
                    <td class="label">Jenis Permohonan</td>
                    <td class="colon">:</td>
                    <td class="value">PSU (Permohonan Surat Umum)</td>
                </tr>
                <tr>
                    <td class="label">Nomor Surat</td>
                    <td class="colon">:</td>
                    <td class="value">{{ $psu->nomor_surat ?? '001/PSU/RT01/RW01/VI/2025' }}</td>
                </tr>
                <tr>
                    <td class="label">Perihal</td>
                    <td class="colon">:</td>
                    <td class="value">{{ $psu->hal ?? 'Permohonan Surat Keterangan' }}</td>
                </tr>
                <tr>
                    <td class="label">Tanggal Diterima</td>
                    <td class="colon">:</td>
                    <td class="value">{{ $tanggal_terima ?? '22 June 2025' }} pukul {{ $jam_terima ?? '11:00' }} WIB</td>
                </tr>
                <tr>
                    <td class="label">Diterima oleh</td>
                    <td class="colon">:</td>
                    <td class="value">{{ $petugas ?? 'yamuarcy' }} ({{ $jabatan_petugas ?? 'Front Office' }})</td>
                </tr>
            </table>
        </div>

        <!-- Signature Section - FIXED: align right dengan signature tunggal -->
        <div class="signatures">
            <div class="signature-section">
                <div class="signature-header">
                    {{ $kelurahan ?? 'Kelurahan Surabaya' }}, {{ $tanggal_terima ?? '22 June 2025' }}
                    <br>Petugas Penerima
                </div>

                <!-- Container untuk TTD dan Stempel yang menyatu -->
                <div class="signature-container">
                    <!-- TTD Layer -->
                    @if($ttd_front_office ?? false)
                        <div class="ttd-signature">
                            @php
                                $ttdBase64 = null;
                                if (!str_starts_with($ttd_front_office, 'data:image/')) {
                                    if (str_starts_with($ttd_front_office, '/storage/')) {
                                        $ttdPath = storage_path('app/public/' . str_replace('/storage/', '', $ttd_front_office));
                                    } else {
                                        $ttdPath = storage_path('app/public/' . $ttd_front_office);
                                    }

                                    if (file_exists($ttdPath)) {
                                        $ttdBase64 = 'data:image/' . pathinfo($ttdPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($ttdPath));
                                    }
                                } else {
                                    $ttdBase64 = $ttd_front_office;
                                }
                            @endphp
                            @if($ttdBase64)
                                <img src="{{ $ttdBase64 }}" alt="TTD Front Office">
                            @endif
                        </div>
                    @endif

                    <!-- Stempel Layer -->
                    @if($stempel_kelurahan ?? false)
                        <div class="stamp-overlay">
                            @php
                                $stempelBase64 = null;
                                if (!str_starts_with($stempel_kelurahan, 'data:image/')) {
                                    if (str_starts_with($stempel_kelurahan, '/storage/')) {
                                        $stempelPath = storage_path('app/public/' . str_replace('/storage/', '', $stempel_kelurahan));
                                    } else {
                                        $stempelPath = storage_path('app/public/' . $stempel_kelurahan);
                                    }

                                    if (file_exists($stempelPath)) {
                                        $stempelBase64 = 'data:image/' . pathinfo($stempelPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($stempelPath));
                                    }
                                } else {
                                    $stempelBase64 = $stempel_kelurahan;
                                }
                            @endphp
                            @if($stempelBase64)
                                <img src="{{ $stempelBase64 }}" alt="Stempel Kelurahan">
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Nama dengan tanda kurung -->
                <div class="signature-name">
                    ({{ $front_office_name ?? $petugas ?? 'yamuarcy' }})
                    <br>{{ $jabatan_petugas ?? 'Front Office' }}
                </div>
            </div>
        </div>

        <!-- Info Section 2 Kolom di bawah -->
        <div class="info-section">
            <!-- Kolom Kiri: Catatan Penting -->
            <div class="info-left">
                <div class="note-box">
                    <div class="note-title">Catatan Penting:</div>
                    <ul class="note-list">
                        <li>Berkas telah diterima dan akan diproses sesuai prosedur yang berlaku</li>
                        <li>Proses persetujuan akan dilakukan melalui tahapan disposisi Lurah</li>
                        <li>Pemohon akan dihubungi jika diperlukan kelengkapan berkas tambahan</li>
                        <li>Estimasi waktu proses: 3-7 hari kerja</li>
                        <li>Simpan tanda terima ini sebagai bukti pengajuan</li>
                    </ul>
                </div>
            </div>

            <!-- Kolom Kanan: Info Status -->
            <div class="info-right">
                <div class="info-item">
                    <strong>Status Berkas:</strong><br>
                    Lengkap dan diterima
                </div>
                <div class="info-item">
                    <strong>Tahap Selanjutnya:</strong><br>
                    Disposisi Lurah
                </div>
                <div class="info-item">
                    <strong>Kode Tracking:</strong><br>
                    PSU-{{ substr($psu->nomor_surat ?? '02/2025', -8) }}
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Dokumen ini dibuat secara elektronik dan sah tanpa tanda tangan basah</p>
            <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }} WIB</p>
        </div>
    </div>
</body>
</html>
