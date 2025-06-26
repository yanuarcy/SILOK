@php
    $RouteSaatIni = Route::currentRouteName();
    include_once app_path('Helpers/GeneralSettings.php');
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    @if(getSetting('site_favicon'))
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . getSetting('site_favicon')) }}">
    @endif
    <title>PSU - {{ $psu->nomor_surat }}</title>
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
            margin-bottom: 25px;
        }

        .kop-surat {
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .kop-title {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.2;
            margin-bottom: 5px;
        }

        .kop-address {
            font-size: 12px;
            font-style: italic;
        }

        .surat-info {
            position: relative;
            height: 120px; /* sesuaikan tinggi */
            margin-bottom: 25px;
            font-size: 12px;
        }

        .surat-left {
            position: absolute;
            left: 0;
            top: 0;
            width: 48%;
            /* border: 1px dashed blue; */
        }

        .surat-right {
            position: absolute;
            right: 0;
            top: 0;
            width: 48%;
            /* border: 1px dashed green; */
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            display: table;
        }

        .info-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .info-table .label {
            width: 80px;
        }

        .info-table .colon {
            width: 20px;
            text-align: center;
        }

        .info-table .value {
            text-align: left;
        }

        .content {
            text-align: justify;
            margin-bottom: 30px;
        }

        .content p {
            margin-bottom: 15px;
            text-indent: 0;
        }

        /* Layout tanda tangan di pojok kanan */
        .signatures {
            position: relative;
            height: 150px; /* sesuaikan tinggi sesuai kebutuhan */
            margin-top: 40px;
        }

        .signature-section {
            position: absolute;
            right: 0;
            top: 0;
            text-align: center;
            width: 250px;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 15px;
            font-size: 12px;
            line-height: 1.3;
        }

        /* Container untuk TTD dan Stempel yang overlap */
        .signature-stamp-container {
            position: relative;
            height: 100px;
            margin: 15px 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* TTD layer */
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

        .signature-name {
            margin-top: 20px;
            font-weight: normal;
            font-size: 12px;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        .mb-10 {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Header Kop Surat --}}
        <div class="header">
            <div class="kop-surat">
                @if($psu->ditujukan_kepada === 'kelurahan' || $psu->getFinalApprovalLevel() === 'kelurahan')
                <div class="kop-title">RUKUN WARGA {{ sprintf('%02d', $psu->rw) }}</div>
                <div class="kop-title">KELURAHAN {{ getOrganizationName() }}</div>
                @elseif($psu->ditujukan_kepada === 'rw' || $psu->getFinalApprovalLevel() === 'rw')
                <div class="kop-title">RUKUN TETANGGA {{ sprintf('%02d', $psu->rt) }}</div>
                <div class="kop-title">RUKUN WARGA {{ sprintf('%02d', $psu->rw) }}</div>
                <div class="kop-title">KELURAHAN {{ getOrganizationName() }}</div>
                @else
                <div class="kop-title">RUKUN TETANGGA {{ sprintf('%02d', $psu->rt) }}</div>
                <div class="kop-title">RUKUN WARGA {{ sprintf('%02d', $psu->rw) }}</div>
                <div class="kop-title">KELURAHAN {{ getOrganizationName() }}</div>
                @endif
                <div class="kop-address">(alamat sekretariat)</div>
            </div>
        </div>

        {{-- Informasi Surat --}}
        <div class="surat-info">
            <div class="surat-left">
                <table class="info-table">
                    <tr>
                        <td class="label">Nomor</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $psu->nomor_surat }}</td>
                    </tr>
                    <tr>
                        <td class="label">Sifat</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $psu->sifat }}</td>
                    </tr>
                    <tr>
                        <td class="label">Lampiran</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $psu->file_lampiran ? count($psu->file_lampiran) . ' berkas' : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Hal</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $psu->hal }}</td>
                    </tr>
                </table>
            </div>
            <div class="surat-right">
                <table class="info-table">
                    <tr>
                        <td class="label"></td>
                        <td class="colon"></td>
                        <td class="value">Surabaya, {{ now()->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="label"></td>
                        <td class="colon"></td>
                        <td class="value">Kepada Yth.</td>
                    </tr>
                    <tr>
                        <td class="label"></td>
                        <td class="colon"></td>
                        <td class="value font-bold">
                            @if($psu->isPSUInternal())
                                {{-- PSU Internal - tujuan ke warga --}}
                                @if($psu->ditujukan_kepada === 'warga_rt')
                                    Warga RT {{ sprintf('%02d', $psu->rt) }} RW {{ sprintf('%02d', $psu->rw) }}
                                @elseif($psu->ditujukan_kepada === 'warga_rw')
                                    Warga RW {{ sprintf('%02d', $psu->rw) }}
                                @endif
                            @else
                                {{-- PSU External - tujuan ke pejabat --}}
                                @if($psu->tujuan_internal)
                                    @switch($psu->tujuan_internal)
                                        @case('rt')
                                            {{ $psu->nama_ketua_rt ?: 'Ketua RT ' . sprintf('%02d', $psu->rt) }}
                                            @break
                                        @case('rw')
                                            {{ $psu->nama_ketua_rw ?: 'Ketua RW ' . sprintf('%02d', $psu->rw) }}
                                            @break
                                        @case('kelurahan')
                                            LURAH {{ getOrganizationName() }}
                                            @break
                                        @case('kecamatan')
                                            CAMAT {{ getOrganizationDistrict() }}
                                            @break
                                    @endswitch
                                @else
                                    {{ $psu->tujuan_eksternal ?: 'Penerima Surat' }}
                                @endif
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label"></td>
                        <td class="colon"></td>
                        <td class="value">Surabaya</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Isi Surat --}}
        <div class="content">
            {{-- Tampilkan isi surat langsung dari input user --}}
            <p>{{ $psu->isi_surat }}</p>

            @if($psu->isPSUInternal())
                <p>Demikian atas perhatian dan kerjasamanya disampaikan terima kasih.</p>
            @else
                <p>Demikian pemberitahuan kami buat, Atas perhatiannya diucapkan terimakasih</p>
            @endif
        </div>

        {{-- Tanda Tangan di pojok kanan --}}
        <div class="signatures">
            <div class="signature-section">
                @if($psu->isPSUInternal())
                    {{-- PSU Internal --}}
                    <div class="signature-title">
                        @if($psu->ditujukan_kepada === 'warga_rt')
                            Ketua RT {{ sprintf('%02d', $psu->rt) }}
                        @elseif($psu->ditujukan_kepada === 'warga_rw')
                            Ketua RW {{ sprintf('%02d', $psu->rw) }}
                        @endif
                    </div>

                    <div class="signature-stamp-container">
                        @if($psu->ditujukan_kepada === 'warga_rt' && $psu->ttd_rt)
                            <div class="ttd-signature">
                                @php
                                    if (!str_starts_with($psu->ttd_rt, 'data:image/')) {
                                        $ttdPath = storage_path('app/public/' . $psu->ttd_rt);
                                        if (file_exists($ttdPath)) {
                                            $ttdBase64 = 'data:image/' . pathinfo($ttdPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($ttdPath));
                                        } else {
                                            $ttdBase64 = null;
                                        }
                                    } else {
                                        $ttdBase64 = $psu->ttd_rt;
                                    }
                                @endphp
                                @if($ttdBase64)
                                    <img src="{{ $ttdBase64 }}" alt="TTD Ketua RT">
                                @endif
                            </div>

                            @if($psu->stempel_rt)
                                <div class="stamp-overlay">
                                    @php
                                        if (!str_starts_with($psu->stempel_rt, 'data:image/')) {
                                            $stempelPath = storage_path('app/public/' . $psu->stempel_rt);
                                            if (file_exists($stempelPath)) {
                                                $stempelBase64 = 'data:image/' . pathinfo($stempelPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($stempelPath));
                                            } else {
                                                $stempelBase64 = null;
                                            }
                                        } else {
                                            $stempelBase64 = $psu->stempel_rt;
                                        }
                                    @endphp
                                    @if($stempelBase64)
                                        <img src="{{ $stempelBase64 }}" alt="Stempel RT">
                                    @endif
                                </div>
                            @endif
                        @endif

                        @if($psu->ditujukan_kepada === 'warga_rw' && $psu->ttd_rw)
                            <div class="ttd-signature">
                                @php
                                    if (!str_starts_with($psu->ttd_rw, 'data:image/')) {
                                        $ttdPath = storage_path('app/public/' . $psu->ttd_rw);
                                        if (file_exists($ttdPath)) {
                                            $ttdBase64 = 'data:image/' . pathinfo($ttdPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($ttdPath));
                                        } else {
                                            $ttdBase64 = null;
                                        }
                                    } else {
                                        $ttdBase64 = $psu->ttd_rw;
                                    }
                                @endphp
                                @if($ttdBase64)
                                    <img src="{{ $ttdBase64 }}" alt="TTD Ketua RW">
                                @endif
                            </div>

                            @if($psu->stempel_rw)
                                <div class="stamp-overlay">
                                    @php
                                        if (!str_starts_with($psu->stempel_rw, 'data:image/')) {
                                            $stempelPath = storage_path('app/public/' . $psu->stempel_rw);
                                            if (file_exists($stempelPath)) {
                                                $stempelBase64 = 'data:image/' . pathinfo($stempelPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($stempelPath));
                                            } else {
                                                $stempelBase64 = null;
                                            }
                                        } else {
                                            $stempelBase64 = $psu->stempel_rw;
                                        }
                                    @endphp
                                    @if($stempelBase64)
                                        <img src="{{ $stempelBase64 }}" alt="Stempel RW">
                                    @endif
                                </div>
                            @endif
                        @endif
                    </div>

                    <div class="signature-name">
                        @if($psu->ditujukan_kepada === 'warga_rt')
                            ({{ $psu->nama_ketua_rt ?: ('Ketua RT ' . sprintf('%02d', $psu->rt)) }})
                        @elseif($psu->ditujukan_kepada === 'warga_rw')
                            ({{ $psu->nama_ketua_rw ?: ('Ketua RW ' . sprintf('%02d', $psu->rw)) }})
                        @endif
                    </div>

                @elseif($psu->getFinalApprovalLevel() === 'rw')
                    {{-- PSU External level RW --}}
                    <div class="signature-title">
                        Ketua RT RW {{ sprintf('%02d', $psu->rw) }}
                    </div>

                    <div class="signature-stamp-container">
                        @if($psu->ttd_rw)
                            <div class="ttd-signature">
                                @php
                                    if (!str_starts_with($psu->ttd_rw, 'data:image/')) {
                                        $ttdPath = storage_path('app/public/' . $psu->ttd_rw);
                                        if (file_exists($ttdPath)) {
                                            $ttdBase64 = 'data:image/' . pathinfo($ttdPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($ttdPath));
                                        } else {
                                            $ttdBase64 = null;
                                        }
                                    } else {
                                        $ttdBase64 = $psu->ttd_rw;
                                    }
                                @endphp
                                @if($ttdBase64)
                                    <img src="{{ $ttdBase64 }}" alt="TTD RW">
                                @endif
                            </div>
                        @endif

                        @if($psu->stempel_rw)
                            <div class="stamp-overlay">
                                @php
                                    if (!str_starts_with($psu->stempel_rw, 'data:image/')) {
                                        $stempelPath = storage_path('app/public/' . $psu->stempel_rw);
                                        if (file_exists($stempelPath)) {
                                            $stempelBase64 = 'data:image/' . pathinfo($stempelPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($stempelPath));
                                        } else {
                                            $stempelBase64 = null;
                                        }
                                    } else {
                                        $stempelBase64 = $psu->stempel_rw;
                                    }
                                @endphp
                                @if($stempelBase64)
                                    <img src="{{ $stempelBase64 }}" alt="Stempel RW">
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="signature-name">
                        ({{ $psu->approverRW->name ?? '...................................' }})
                    </div>

                @elseif($psu->getFinalApprovalLevel() === 'kelurahan')
                    {{-- PSU External level Kelurahan --}}
                    <div class="signature-title">
                        Lurah {{ getOrganizationName() }}
                    </div>

                    <div class="signature-stamp-container">
                        @if($psu->ttd_kelurahan)
                            <div class="ttd-signature">
                                @php
                                    if (!str_starts_with($psu->ttd_kelurahan, 'data:image/')) {
                                        $ttdPath = storage_path('app/public/' . $psu->ttd_kelurahan);
                                        if (file_exists($ttdPath)) {
                                            $ttdBase64 = 'data:image/' . pathinfo($ttdPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($ttdPath));
                                        } else {
                                            $ttdBase64 = null;
                                        }
                                    } else {
                                        $ttdBase64 = $psu->ttd_kelurahan;
                                    }
                                @endphp
                                @if($ttdBase64)
                                    <img src="{{ $ttdBase64 }}" alt="TTD Kelurahan">
                                @endif
                            </div>
                        @endif

                        @if($psu->stempel_kelurahan)
                            <div class="stamp-overlay">
                                @php
                                    if (!str_starts_with($psu->stempel_kelurahan, 'data:image/')) {
                                        $stempelPath = storage_path('app/public/' . $psu->stempel_kelurahan);
                                        if (file_exists($stempelPath)) {
                                            $stempelBase64 = 'data:image/' . pathinfo($stempelPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($stempelPath));
                                        } else {
                                            $stempelBase64 = null;
                                        }
                                    } else {
                                        $stempelBase64 = $psu->stempel_kelurahan;
                                    }
                                @endphp
                                @if($stempelBase64)
                                    <img src="{{ $stempelBase64 }}" alt="Stempel Kelurahan">
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="signature-name">
                        ({{ $psu->getFinalApproverName() }})
                    </div>
                @endif
            </div>
        </div>

        {{-- Footer Information for Preview/Status --}}
        @if(!$psu->isPSUInternal() && !in_array($psu->status, ['approved_rt', 'approved_rw', 'approved_kelurahan', 'auto_approved']))
        <div style="margin-top: 20px; padding: 10px; background-color: #f8f9fa; border: 1px solid #dee2e6; font-size: 10px; text-align: center;">
            <strong>PERHATIAN:</strong><br>
            Dokumen ini adalah preview. PDF resmi hanya dapat diunduh setelah mendapat persetujuan sesuai workflow.<br>
            Status saat ini: {{ $psu->status_text }}
            @if(in_array($psu->status, ['rejected_rt', 'rejected_rw', 'rejected_kelurahan']))
                <br><strong style="color: #721c24;">DOKUMEN DITOLAK</strong>
                @if($psu->status === 'rejected_rt' && $psu->catatan_rt)
                    <br>Alasan penolakan RT: {{ $psu->catatan_rt }}
                @endif
                @if($psu->status === 'rejected_rw' && $psu->catatan_rw)
                    <br>Alasan penolakan RW: {{ $psu->catatan_rw }}
                @endif
                @if($psu->status === 'rejected_kelurahan' && $psu->catatan_kelurahan)
                    <br>Alasan penolakan Kelurahan: {{ $psu->catatan_kelurahan }}
                @endif
            @endif
        </div>
        @endif

        {{-- Document Information Footer --}}
        <div style="margin-top: 40px; border-top: 1px solid #000; padding-top: 10px; font-size: 10px;">
            <strong>Informasi Dokumen:</strong><br>
            Nomor Surat: {{ $psu->nomor_surat }}<br>
            Tanggal Pengajuan: {{ $psu->formatted_created_date }}<br>
            Jenis: PSU ({{ $psu->ditujukan_kepada_display }})<br>
            @if($psu->approved_rt_at)
                Disetujui RT: {{ $psu->formatted_approved_rt_date }} oleh {{ $psu->approverRT->name ?? 'Ketua RT' }}<br>
            @endif
            @if($psu->approved_rw_at)
                Disetujui RW: {{ $psu->formatted_approved_rw_date }} oleh {{ $psu->approverRW->name ?? 'Ketua RW' }}<br>
            @endif
            @if($psu->approved_kelurahan_at)
                Disetujui Kelurahan: {{ $psu->formatted_approved_kelurahan_date }} oleh {{ $psu->approverKelurahan->name ?? 'Lurah' }}<br>
            @endif
            Pemohon: {{ $psu->nama_lengkap }} (RT {{ sprintf('%02d', $psu->rt) }} / RW {{ sprintf('%02d', $psu->rw) }})<br>
        </div>
    </div>
</body>
</html>
