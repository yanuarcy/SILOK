<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PUNTADEWA - {{ $puntadewa->nomor_surat }}</title>
    <style>
        @page {
            margin: 0.99in 1in 0.19in 0.95in;
            size: Legal;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            color: #000;
        }

        .container {
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .title {
            font-size: 15.6px;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 32px;
            line-height: 1.3;
        }

        .content {
            text-align: justify;
        }

        .section {
            /* margin-bottom: 15px; */
        }

        .section-title {
            font-weight: bold;
            /* margin-bottom: 4px; */
        }

        /* Table for aligned form fields */
        .form-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            margin-left: 15px;
        }

        .form-table td {
            padding: 2px 0;
            vertical-align: top;
            border: none;
        }

        .form-table .label {
            width: 205px;
            padding-right: 10px;
        }

        .form-table .colon {
            width: 15px;
            text-align: left;
        }

        .form-table .value {
            border-bottom: 1px dotted #000;
            min-height: 16px;
            padding-bottom: 1px;
        }

        /* Reason section styling */
        .reason-section {
            margin: 15px 0;
        }

        .reason-item {
            /* margin-bottom: 8px; */
            margin-left: 15px;
        }

        .reason-label {
            font-weight: bold;
            /* margin-bottom: 3px; */
        }

        .reason-detail {
            margin-left: 15px;
            /* margin-bottom: 2px; */
        }

        .reason-detail-table {
            width: 100%;
            border-collapse: collapse;
            /* margin-bottom: 3px; */
        }

        .reason-detail-table td {
            padding: 1px 0;
            border: none;
            vertical-align: top;
        }

        .reason-detail-table .detail-label {
            width: 200px;
            padding-left: 15px;
        }

        .reason-detail-table .detail-colon {
            width: 15px;
        }

        .reason-detail-table .detail-value {
            border-bottom: 1px dotted #000;
            min-height: 14px;
        }

        /* Signature section */
        .signatures {
            margin-top: 25px;
        }

        .date-location {
            text-align: right;
            margin-bottom: 15px;
            font-size: 12px;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 15px;
            border: none;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 11px;
            line-height: 1.3;
        }

        .signature-space {
            height: 60px;
            /* margin: 10px 0; */
        }

        .signature-image {
            height: 60px;
            margin: 10px 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .signature-image img {
            max-height: 60px;
            max-width: 200px;
        }

        .signature-name {
            /* border-bottom: 1px dotted #000; */
            height: 18px;
            margin-top: 10px;
            font-size: 13px;
        }

        /* Approval section */
        .approval-section {
            margin-top: 30px;
        }

        .approval-table {
            width: 100%;
            border-collapse: collapse;
        }

        .approval-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 15px;
            border: none;
        }

        .approval-title {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 11px;
        }

        .ttd-space {
            height: 20px;
            /* margin: 8px 0; */
        }

        .ttd-image {
            height: 50px;
            margin: 8px 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .ttd-image img {
            max-height: 50px;
            max-width: 120px;
        }

        .ttd-label {
            font-size: 10px;
            margin: 5px 0;
        }

        .stempel-space {
            height: 40px;
            /* margin: 8px 0; */
        }

        .stempel-image {
            height: 40px;
            /* margin: 8px 0; */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .stempel-image img {
            max-height: 40px;
            max-width: 120px;
        }

        .stempel-label {
            font-size: 10px;
            /* margin: 5px 0; */
            color: #666;
        }

        .approval-name {
            /* border-bottom: 1px dotted #000; */
            height: 18px;
            /* margin-top: 10px; */
            font-size: 11px;
        }

        /* Address section styling */
        .address-section {
            /* margin: 15px 0; */
            /* padding: 10px 0; */
        }

        .address-content {
            border-bottom: 1px dotted #000;
            min-height: 16px;
            padding-bottom: 2px;
        }

        .address-content2 {
            border-bottom: 1px dotted #000;
            min-height: 16px;
            padding-bottom: 2px;
        }

        /* Notes section */
        .notes {
            margin-top: 10px;
            font-size: 10px;
        }

        /* Container for signature and stamp overlay */
        .signature-stamp-container {
            position: relative;
            height: 120px;
            /* margin: 10px 0; */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Signature styling */
        .ttd-signature {
            position: absolute;
            z-index: 2;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
        }

        .ttd-signature img {
            max-height: 70px;
            max-width: 180px;
            filter: contrast(1.2) brightness(0.9);
        }

        /* Stamp styling - positioned to overlap with signature */
        .stamp-overlay {
            position: absolute;
            z-index: 1;
            top: 10px;
            right: 55px;
            /* opacity: 0.85; */
            transform: translateY(-20%);
        }

        .stamp-overlay img {
            max-height: 100px;
            max-width: 200px;
            filter: contrast(1.3) saturate(1.2);
        }

        /* Alternative positioning classes */
        .stamp-bottom-left {
            top: 50px;
            left: 20px;
            right: auto;
        }

        .stamp-center {
            top: 45px;
            left: 50%;
            right: auto;
            transform: translateX(-50%);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="title">
                SURAT PERNYATAAN / SURAT KETERANGAN TEMPAT TINGGAL PENDUDUK NON<br>
                PERMANEN DI ISI LENGKAP OLEH PEMILIK RUMAH KOST / KONTRAKAN /SEJENISNYA
            </div>
        </div>

        <div class="content">
            <div class="section">
                <div class="section-title">Yang bertanda tangan dibawah ini :</div>

                <table class="form-table">
                    <tr>
                        <td class="label">1. Nama pemilik kost / kontrakan</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $puntadewa->nama_penjamin }}</td>
                    </tr>
                    <tr>
                        <td class="label">2. Alamat</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $puntadewa->alamat_penjamin }}</td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <div class="section-title">Menyatakan dengan sebenarnya bahwa :</div>

                <table class="form-table">
                    <tr>
                        <td class="label">Nama</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $puntadewa->nama_pemohon }}</td>
                    </tr>
                    <tr>
                        <td class="label">NIK</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $puntadewa->nik }}</td>
                    </tr>
                    <tr>
                        <td class="label">Alamat Asal</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $puntadewa->alamat_asal }}</td>
                    </tr>
                </table>
            </div>

            <div class="reason-section">
                <div class="section-title">Alasan tinggal di Surabaya :</div>

                <div class="reason-item">
                    <div class="reason-label">1. Bekerja:</div>
                    <table class="reason-detail-table">
                        <tr>
                            <td class="detail-label">- Nama Perusahaan / Wiraswasta</td>
                            <td class="detail-colon">:</td>
                            <td class="detail-value">{{ $puntadewa->nama_perusahaan ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="detail-label">- Alamat Perusahaan</td>
                            <td class="detail-colon">:</td>
                            <td class="detail-value">{{ $puntadewa->alamat_perusahaan ?? '' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="reason-item">
                    <div class="reason-label">2. Sekolah:</div>
                    <table class="reason-detail-table">
                        <tr>
                            <td class="detail-label">- Nama Sekolah / Perguruan Tinggi</td>
                            <td class="detail-colon">:</td>
                            <td class="detail-value">{{ $puntadewa->nama_sekolah ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="detail-label">- Alamat Sekolah / Perguruan Tinggi</td>
                            <td class="detail-colon">:</td>
                            <td class="detail-value">{{ $puntadewa->alamat_sekolah ?? '' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="reason-item">
                    <div class="reason-label">3. Kesehatan:</div>
                    <table class="reason-detail-table">
                        <tr>
                            <td class="detail-label">- Nama Rumah Sakit / Klinik</td>
                            <td class="detail-colon">:</td>
                            <td class="detail-value">{{ $puntadewa->nama_rumah_sakit ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="detail-label">- Alamat Rumah Sakit</td>
                            <td class="detail-colon">:</td>
                            <td class="detail-value">{{ $puntadewa->alamat_rumah_sakit ?? '' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="reason-item">
                    <div class="reason-label">4. Alasan Lainnya:</div>
                    <div class="reason-detail">
                        <div class="address-content">{{ $puntadewa->alasan_lainnya ?? '' }}</div>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="section-title">Memang Benar yang bersangkutan Bertempat tinggal dialamat sebagai berikut :</div>
                <div class="address-section">
                    <div class="address-content">{{ $puntadewa->alasan_tinggal }}</div>
                    <div class="address-content2"></div>
                </div>
            </div>

            <div class="section">
                <div class="section-title" style="margin-top: 10px;">Untuk itu kepada yang bersangkutan dapat di terbitkan Bukti Pendataan Penduduk Non Permanen</div>
            </div>

            <div class="section" style="margin-top: -10px;">
                <p>Demikian surat pernyataan /Surat Keterangan ini saya buat dengan sebenarnya dan penuh tanggung jawab</p>
            </div>

            <div class="signatures">
                <div class="date-location">
                    Surabaya, {{ now()->format('d F Y') }}
                </div>

                <table class="signature-table">
                    <tr>
                        <td>
                            <div class="signature-title">Ttd<br>Pemohon</div>
                            @if($puntadewa->ttd_pemohon)
                                <div class="signature-image">
                                    <img src="{{ $puntadewa->ttd_pemohon }}" alt="Tanda Tangan Pemohon">
                                </div>
                            @else
                                <div class="signature-space"></div>
                            @endif
                            <div class="signature-name">({{ $puntadewa->nama_pemohon }})</div>
                        </td>
                        <td>
                            <div class="signature-title">Ttd Pemilik Rumah Kost / Kontrakan<br>/ Sejenisnya</div>
                            @if($puntadewa->ttd_pemilik_kost)
                                <div class="signature-image">
                                    <img src="{{ $puntadewa->ttd_pemilik_kost }}" alt="Tanda Tangan Pemilik Kost">
                                </div>
                            @else
                                <div class="signature-space"></div>
                            @endif
                            <div class="signature-name">({{ $puntadewa->nama_penjamin }})</div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="approval-section">
                <table class="approval-table">
                    <tr>
                        <td>
                            <div class="approval-title"><strong>Mengetahui RT</strong></div>

                            <!-- Container for overlapping signature and stamp -->
                            <div class="signature-stamp-container">
                                <!-- Signature Layer -->
                                @if($puntadewa->ttd_rt)
                                    <div class="ttd-signature">
                                        @php
                                            if (!str_starts_with($puntadewa->ttd_rt, 'data:image/')) {
                                                $ttdRTPath = storage_path('app/public/' . $puntadewa->ttd_rt);
                                                if (file_exists($ttdRTPath)) {
                                                    $ttdRTBase64 = 'data:image/' . pathinfo($ttdRTPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($ttdRTPath));
                                                } else {
                                                    $ttdRTBase64 = null;
                                                }
                                            } else {
                                                $ttdRTBase64 = $puntadewa->ttd_rt;
                                            }
                                        @endphp
                                        @if($ttdRTBase64)
                                            <img src="{{ $ttdRTBase64 }}" alt="TTD RT">
                                        @endif
                                    </div>
                                @endif

                                <!-- Stamp Layer (overlapping) -->
                                @if($puntadewa->stempel_rt)
                                    <div class="stamp-overlay">
                                        @php
                                            if (!str_starts_with($puntadewa->stempel_rt, 'data:image/')) {
                                                $stempelRTPath = storage_path('app/public/' . $puntadewa->stempel_rt);
                                                if (file_exists($stempelRTPath)) {
                                                    $stempelRTBase64 = 'data:image/' . pathinfo($stempelRTPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($stempelRTPath));
                                                } else {
                                                    $stempelRTBase64 = null;
                                                }
                                            } else {
                                                $stempelRTBase64 = $puntadewa->stempel_rt;
                                            }
                                        @endphp
                                        @if($stempelRTBase64)
                                            <img src="{{ $stempelRTBase64 }}" alt="Stempel RT">
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="approval-name">({{ $puntadewa->approverRT->name ?? '...................................' }})</div>

                            {{-- @if($puntadewa->catatan_rt)
                                <div class="notes">
                                    <strong>Catatan RT:</strong><br>{{ $puntadewa->catatan_rt }}
                                </div>
                            @endif --}}
                        </td>
                        <td>
                            <div class="approval-title"><strong>Mengetahui RW</strong></div>

                            <!-- Container for overlapping signature and stamp -->
                            <div class="signature-stamp-container">
                                <!-- Signature Layer -->
                                @if($puntadewa->ttd_rw)
                                    <div class="ttd-signature">
                                        @php
                                            if (!str_starts_with($puntadewa->ttd_rw, 'data:image/')) {
                                                $ttdRWPath = storage_path('app/public/' . $puntadewa->ttd_rw);
                                                if (file_exists($ttdRWPath)) {
                                                    $ttdRWBase64 = 'data:image/' . pathinfo($ttdRWPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($ttdRWPath));
                                                } else {
                                                    $ttdRWBase64 = null;
                                                }
                                            } else {
                                                $ttdRWBase64 = $puntadewa->ttd_rw;
                                            }
                                        @endphp
                                        @if($ttdRWBase64)
                                            <img src="{{ $ttdRWBase64 }}" alt="TTD RW">
                                        @endif
                                    </div>
                                @endif

                                <!-- Stamp Layer (positioned differently for variety) -->
                                @if($puntadewa->stempel_rw)
                                    <div class="stamp-overlay">
                                        @php
                                            if (!str_starts_with($puntadewa->stempel_rw, 'data:image/')) {
                                                $stempelRWPath = storage_path('app/public/' . $puntadewa->stempel_rw);
                                                if (file_exists($stempelRWPath)) {
                                                    $stempelRWBase64 = 'data:image/' . pathinfo($stempelRWPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($stempelRWPath));
                                                } else {
                                                    $stempelRWBase64 = null;
                                                }
                                            } else {
                                                $stempelRWBase64 = $puntadewa->stempel_rw;
                                            }
                                        @endphp
                                        @if($stempelRWBase64)
                                            <img src="{{ $stempelRWBase64 }}" alt="Stempel RW">
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="approval-name">({{ $puntadewa->approverRW->name ?? '...................................' }})</div>

                            {{-- @if($puntadewa->catatan_rw)
                                <div class="notes">
                                    <strong>Catatan RW:</strong><br>{{ $puntadewa->catatan_rw }}
                                </div>
                            @endif --}}
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Footer Information for Preview/Status -->
            @if($puntadewa->status !== 'approved_rw')
            <div style="margin-top: 20px; padding: 10px; background-color: #f8f9fa; border: 1px solid #dee2e6; font-size: 10px; text-align: center;">
                <strong>PERHATIAN:</strong><br>
                Dokumen ini adalah preview. PDF resmi hanya dapat diunduh setelah mendapat persetujuan lengkap dari RT dan RW.<br>
                Status saat ini: {{ $puntadewa->status_text }}
                @if(in_array($puntadewa->status, ['rejected_rt', 'rejected_rw']))
                    <br><strong style="color: #721c24;">DOKUMEN DITOLAK</strong>
                    @if($puntadewa->status === 'rejected_rt' && $puntadewa->catatan_rt)
                        <br>Alasan penolakan RT: {{ $puntadewa->catatan_rt }}
                    @endif
                    @if($puntadewa->status === 'rejected_rw' && $puntadewa->catatan_rw)
                        <br>Alasan penolakan RW: {{ $puntadewa->catatan_rw }}
                    @endif
                @endif
            </div>
            @endif

            <!-- Document Information Footer -->
            <div style="margin-top: 20px; border-top: 1px solid #000; padding-top: 10px; font-size: 10px;">
                <strong>Informasi Dokumen:</strong><br>
                Nomor Surat: {{ $puntadewa->nomor_surat }}<br>
                Tanggal Pengajuan: {{ $puntadewa->formatted_created_date }}<br>
                @if($puntadewa->approved_rt_at)
                    Disetujui RT: {{ $puntadewa->formatted_approved_rt_date }} oleh {{ $puntadewa->approverRT->name ?? 'Ketua RT' }}<br>
                @endif
                @if($puntadewa->approved_rw_at)
                    Disetujui RW: {{ $puntadewa->formatted_approved_rw_date }} oleh {{ $puntadewa->approverRW->name ?? 'Ketua RW' }}<br>
                @endif
                @if($puntadewa->alamat_lokasi)
                    Lokasi: {{ $puntadewa->alamat_lokasi }}<br>
                @endif
                @if($puntadewa->latitude && $puntadewa->longitude)
                    Koordinat: {{ $puntadewa->latitude }}, {{ $puntadewa->longitude }}<br>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
