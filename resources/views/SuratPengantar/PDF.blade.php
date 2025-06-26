@php
    $RouteSaatIni = Route::currentRouteName();
    include_once app_path('Helpers/GeneralSettings.php');

@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>SURAT PENGANTAR - {{ $suratPengantar->nomor_surat }}</title>
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
            text-align: left;
            margin-bottom: 20px;
        }

        .rt-rw-info {
            font-size: 12px;
            /* margin-bottom: 15px; */
        }

        .kelurahan-info {
            font-size: 12px;
            margin-bottom: 25px;
        }

        .title {
            font-size: 15.6px;
            font-weight: bold;
            text-decoration: underline;
            text-align: center;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .nomor-surat {
            text-align: center;
            font-size: 12px;
            margin-bottom: 32px;
        }

        .content {
            text-align: justify;
        }

        .section {
            margin-bottom: 15px;
        }

        .section-title {
            font-weight: normal;
            margin-bottom: 8px;
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
            margin: 10px 0;
        }

        .signature-image {
            height: 60px;
            margin: 10px 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .signature-image img {
            max-height: 100px;
            max-width: 240px;
        }

        .signature-name {
            height: 18px;
            margin-top: 30px;
            font-size: 13px;
        }

        .signature-number {
            margin-top: 10px;
            font-size: 10px;
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

        .approval-name {
            height: 18px;
            margin-top: 10px;
            font-size: 11px;
        }

        /* Container for signature and stamp overlay */
        .signature-stamp-container {
            position: relative;
            height: 95px;
            margin: 10px 0;
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
            transform: translateY(-20%);
        }

        .stamp-overlay img {
            max-height: 100px;
            max-width: 200px;
            filter: contrast(1.3) saturate(1.2);
        }

        /* RW Signature section - positioned at center bottom */
        .rw-signature-section {
            margin-top: 20px;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .rw-signature-container {
            text-align: center;
            width: 300px;
            margin: 0 auto;
        }

        .date-format {
            font-size: 12px;
        }

        /* Keterangan lain section */
        .keterangan-section {
            margin: 15px 0;
        }

        .keterangan-content {
            margin-left: 15px;
            border-bottom: 1px dotted #000;
            min-height: 16px;
            padding-bottom: 2px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="rt-rw-info">
                RT : {{ sprintf('%02d', $suratPengantar->rt) }} &nbsp;&nbsp;&nbsp;&nbsp; RW : {{ sprintf('%02d', $suratPengantar->rw) }}
            </div>
            <div class="kelurahan-info">
                KELURAHAN : {{ getOrganizationName() }}
            </div>
        </div>

        <div class="content">
            <div class="title">
                SURAT PENGANTAR / KETERANGAN
            </div>
            <div class="nomor-surat">
                No. {{ $suratPengantar->nomor_surat }}
            </div>

            <div class="section">
                <div class="section-title" style="font-weight: bold;">Yang bertanda tangan di bawah ini, menerangkan :</div>

                <table class="form-table">
                    <tr>
                        <td class="label">Nama Lengkap</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $suratPengantar->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <td class="label">Alamat</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $suratPengantar->alamat }}</td>
                    </tr>
                    <tr>
                        <td class="label">Pekerjaan</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $suratPengantar->pekerjaan }}</td>
                    </tr>
                    <tr>
                        <td class="label">Jenis Kelamin</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $suratPengantar->jenis_kelamin }}</td>
                    </tr>
                    <tr>
                        <td class="label">Tempat / tgl. lahir</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $suratPengantar->tempat_lahir }}, {{ \Carbon\Carbon::parse($suratPengantar->tanggal_lahir)->format('d-m-Y') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Agama</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $suratPengantar->agama }}</td>
                    </tr>
                    <tr>
                        <td class="label">Kawin / tidak kawin</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $suratPengantar->status_perkawinan }}</td>
                    </tr>
                    <tr>
                        <td class="label">Kewarganegaraan</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $suratPengantar->kewarganegaraan }}</td>
                    </tr>
                    <tr>
                        <td class="label">Nomor KK / KTP</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $suratPengantar->nomor_kk }}</td>
                    </tr>
                    <tr>
                        <td class="label">Tujuan</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $suratPengantar->tujuan }}</td>
                    </tr>
                    <tr>
                        <td class="label">Keperluan</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $suratPengantar->keperluan }}</td>
                    </tr>
                </table>
            </div>

            <div class="keterangan-section">
                <div class="section-title" style="font-weight: bold;">Keterangan lain-lain :</div>
                <div class="keterangan-content">
                    {{ $suratPengantar->keterangan_lain ?? '' }}
                </div>
            </div>

            <div class="section" style="margin-top: 15px;">
                <div class="section-title">Demikian agar mendapat bantuan seperlunya</div>
            </div>

            <div class="signatures">
                <div class="date-location">
                    Surabaya, {{ now()->format('d F Y') }}
                </div>

                <!-- Layout: TTD Pemohon (kiri) dan TTD RT (kanan) -->
                <table class="signature-table">
                    <tr>
                        <td>
                            <div class="signature-title">Tanda tangan<br>Yang bersangkutan</div>
                            @if($suratPengantar->ttd_pemohon)
                                <div class="signature-image">
                                    @php
                                        if (!str_starts_with($suratPengantar->ttd_pemohon, 'data:image/')) {
                                            $ttdPemohonPath = storage_path('app/public/' . $suratPengantar->ttd_pemohon);
                                            if (file_exists($ttdPemohonPath)) {
                                                $ttdPemohonBase64 = 'data:image/' . pathinfo($ttdPemohonPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($ttdPemohonPath));
                                            } else {
                                                $ttdPemohonBase64 = null;
                                            }
                                        } else {
                                            $ttdPemohonBase64 = $suratPengantar->ttd_pemohon;
                                        }
                                    @endphp
                                    @if($ttdPemohonBase64)
                                        <img src="{{ $ttdPemohonBase64 }}" alt="Tanda Tangan Pemohon">
                                    @endif
                                </div>
                            @else
                                <div class="signature-space"></div>
                            @endif
                            <div class="signature-name">({{ $suratPengantar->nama_lengkap }})</div>
                            {{-- <div class="signature-number">No. .......................................</div> --}}
                        </td>
                        <td>
                            <div class="signature-title">Mengetahui<br>Ketua RT {{ sprintf('%02d', $suratPengantar->rt) }}</div>

                            <!-- Container for overlapping signature and stamp RT -->
                            <div class="signature-stamp-container">
                                <!-- Signature Layer -->
                                @if($suratPengantar->ttd_rt)
                                    <div class="ttd-signature">
                                        @php
                                            if (!str_starts_with($suratPengantar->ttd_rt, 'data:image/')) {
                                                $ttdRTPath = storage_path('app/public/' . $suratPengantar->ttd_rt);
                                                if (file_exists($ttdRTPath)) {
                                                    $ttdRTBase64 = 'data:image/' . pathinfo($ttdRTPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($ttdRTPath));
                                                } else {
                                                    $ttdRTBase64 = null;
                                                }
                                            } else {
                                                $ttdRTBase64 = $suratPengantar->ttd_rt;
                                            }
                                        @endphp
                                        @if($ttdRTBase64)
                                            <img src="{{ $ttdRTBase64 }}" alt="TTD RT">
                                        @endif
                                    </div>
                                @endif

                                <!-- Stamp Layer (overlapping) -->
                                @if($suratPengantar->stempel_rt)
                                    <div class="stamp-overlay">
                                        @php
                                            if (!str_starts_with($suratPengantar->stempel_rt, 'data:image/')) {
                                                $stempelRTPath = storage_path('app/public/' . $suratPengantar->stempel_rt);
                                                if (file_exists($stempelRTPath)) {
                                                    $stempelRTBase64 = 'data:image/' . pathinfo($stempelRTPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($stempelRTPath));
                                                } else {
                                                    $stempelRTBase64 = null;
                                                }
                                            } else {
                                                $stempelRTBase64 = $suratPengantar->stempel_rt;
                                            }
                                        @endphp
                                        @if($stempelRTBase64)
                                            <img src="{{ $stempelRTBase64 }}" alt="Stempel RT">
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="approval-name">({{ $suratPengantar->approverRT->name ?? '...................................' }})</div>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- TTD RW di bagian tengah bawah -->
            <div class="rw-signature-section">
                <div class="rw-signature-container">
                    <div class="approval-title" style="text-align: center;"><strong>Mengetahui<br>Ketua RW {{ sprintf('%02d', $suratPengantar->rw) }}</strong></div>

                    <!-- Container for overlapping signature and stamp RW -->
                    <div class="signature-stamp-container" style="margin: 0 auto;">
                        <!-- Signature Layer -->
                        @if($suratPengantar->ttd_rw)
                            <div class="ttd-signature">
                                @php
                                    if (!str_starts_with($suratPengantar->ttd_rw, 'data:image/')) {
                                        $ttdRWPath = storage_path('app/public/' . $suratPengantar->ttd_rw);
                                        if (file_exists($ttdRWPath)) {
                                            $ttdRWBase64 = 'data:image/' . pathinfo($ttdRWPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($ttdRWPath));
                                        } else {
                                            $ttdRWBase64 = null;
                                        }
                                    } else {
                                        $ttdRWBase64 = $suratPengantar->ttd_rw;
                                    }
                                @endphp
                                @if($ttdRWBase64)
                                    <img src="{{ $ttdRWBase64 }}" alt="TTD RW">
                                @endif
                            </div>
                        @endif

                        <!-- Stamp Layer RW -->
                        @if($suratPengantar->stempel_rw)
                            <div class="stamp-overlay">
                                @php
                                    if (!str_starts_with($suratPengantar->stempel_rw, 'data:image/')) {
                                        $stempelRWPath = storage_path('app/public/' . $suratPengantar->stempel_rw);
                                        if (file_exists($stempelRWPath)) {
                                            $stempelRWBase64 = 'data:image/' . pathinfo($stempelRWPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($stempelRWPath));
                                        } else {
                                            $stempelRWBase64 = null;
                                        }
                                    } else {
                                        $stempelRWBase64 = $suratPengantar->stempel_rw;
                                    }
                                @endphp
                                @if($stempelRWBase64)
                                    <img src="{{ $stempelRWBase64 }}" alt="Stempel RW">
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="approval-name" style="text-align: center;">({{ $suratPengantar->approverRW->name ?? '...................................' }})</div>
                </div>
            </div>

            <!-- Footer Information for Preview/Status -->
            @if($suratPengantar->status !== 'approved_rw')
            <div style="margin-top: 20px; padding: 10px; background-color: #f8f9fa; border: 1px solid #dee2e6; font-size: 10px; text-align: center;">
                <strong>PERHATIAN:</strong><br>
                Dokumen ini adalah preview. PDF resmi hanya dapat diunduh setelah mendapat persetujuan lengkap dari RT dan RW.<br>
                Status saat ini: {{ $suratPengantar->status_text }}
                @if(in_array($suratPengantar->status, ['rejected_rt', 'rejected_rw']))
                    <br><strong style="color: #721c24;">DOKUMEN DITOLAK</strong>
                    @if($suratPengantar->status === 'rejected_rt' && $suratPengantar->catatan_rt)
                        <br>Alasan penolakan RT: {{ $suratPengantar->catatan_rt }}
                    @endif
                    @if($suratPengantar->status === 'rejected_rw' && $suratPengantar->catatan_rw)
                        <br>Alasan penolakan RW: {{ $suratPengantar->catatan_rw }}
                    @endif
                @endif
            </div>
            @endif

            <!-- Document Information Footer -->
            <div style="margin-top: 20px; border-top: 1px solid #000; padding-top: 10px; font-size: 10px;">
                <strong>Informasi Dokumen:</strong><br>
                Nomor Surat: {{ $suratPengantar->nomor_surat }}<br>
                Tanggal Pengajuan: {{ $suratPengantar->formatted_created_date }}<br>
                @if($suratPengantar->approved_rt_at)
                    Disetujui RT: {{ $suratPengantar->formatted_approved_rt_date }} oleh {{ $suratPengantar->approverRT->name ?? 'Ketua RT' }}<br>
                @endif
                @if($suratPengantar->approved_rw_at)
                    Disetujui RW: {{ $suratPengantar->formatted_approved_rw_date }} oleh {{ $suratPengantar->approverRW->name ?? 'Ketua RW' }}<br>
                @endif
                @if($suratPengantar->alamat_lokasi)
                    Lokasi: {{ $suratPengantar->alamat_lokasi }}<br>
                @endif
                @if($suratPengantar->latitude && $suratPengantar->longitude)
                    Koordinat: {{ $suratPengantar->latitude }}, {{ $suratPengantar->longitude }}<br>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
