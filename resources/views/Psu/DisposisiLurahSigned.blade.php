{{-- resources/views/Psu/DisposisiLurahSigned.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Disposisi Lurah - {{ $nomor_agenda }}</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12px;
            margin: 20px;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header-left {
            float: left;
            width: 40%;
            font-weight: bold;
        }

        .header-right {
            float: right;
            width: 40%;
            font-weight: bold;
        }

        .clear {
            clear: both;
        }

        .form-container {
            border: 2px solid black;
            margin-top: 40px;
            padding: 0;
        }

        .form-title {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            padding: 10px;
            border-bottom: 2px solid black;
            margin: 0;
            letter-spacing: 3px;
        }

        .form-row {
            display: table;
            width: 100%;
            border-bottom: 1px solid black;
        }

        .form-row:last-child {
            border-bottom: none;
        }

        .form-cell {
            display: table-cell;
            padding: 8px;
            vertical-align: top;
            border-right: 1px solid black;
        }

        .form-cell:last-child {
            border-right: none;
        }

        .form-cell-left {
            width: 50%;
        }

        .form-cell-right {
            width: 50%;
        }

        .field-label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .field-value {
            min-height: 20px;
            border-bottom: 1px dotted black;
            margin-bottom: 5px;
            padding-bottom: 2px;
        }

        .checkbox-group {
            margin: 5px 0;
        }

        .checkbox-item {
            margin: 3px 0;
        }

        .checkbox {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid black;
            margin-right: 8px;
            vertical-align: middle;
        }

        .checkbox.checked {
            background-color: black;
        }

        .sifat-checkboxes {
            margin: 5px 0;
        }

        .sifat-checkboxes .checkbox-item {
            display: inline-block;
            margin-right: 15px;
        }

        .large-field {
            min-height: 60px;
            /* border: 1px dotted black; */
            padding: 5px;
        }

        .disposisi-section {
            border-bottom: 1px solid black;
        }

        .catatan-section {
            min-height: 100px;
        }

        .filled-catatan {
            /* background-color: #f9f9f9; */
            /* padding: 10px; */
            /* border: 1px solid #ddd; */
            /* border-radius: 3px; */
            white-space: pre-wrap;
            line-height: 1.6;
        }

        .diteruskan-checked {
            background-color: #333;
            color: white;
            padding: 2px 4px;
            border-radius: 2px;
        }

        /* Signature section - SAMA SEPERTI TANDA TERIMA */
        .signatures {
            margin-top: 40px;
            text-align: right;
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
        }

        /* TTD layer */
        .ttd-signature {
            position: absolute;
            z-index: 2;
            top: 10px;
            left: 90%;
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
            right: 10px;
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

        @media print {
            body { margin: 15px; }
            .form-container { break-inside: avoid; }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-left">
            LAMBANG<br>
            DAERAH
        </div>
        <div class="header-right">
            KOP NASKAH DINAS<br>
            PERANGKAT DAERAH
        </div>
        <div class="clear"></div>
    </div>

    <!-- Form Disposisi -->
    <div class="form-container">
        <!-- Title -->
        <div class="form-title">
            LEMBAR DISPOSISI
        </div>

        <!-- Row 1: Surat dari & Diterima -->
        <div class="form-row">
            <div class="form-cell form-cell-left">
                <div class="field-label">Surat dari :</div>
                <div class="field-value">{{ $surat_dari ?? $psu->nama_lengkap }}</div>

                <div class="field-label" style="margin-top: 10px;">No. Surat :</div>
                <div class="field-value">{{ $nomor_surat ?? $psu->nomor_surat }}</div>

                <div class="field-label" style="margin-top: 10px;">Tgl. Surat :</div>
                <div class="field-value">{{ $tanggal_surat ?? $psu->created_at->format('d/m/Y') }}</div>
            </div>
            <div class="form-cell form-cell-right">
                <div class="field-label">Diterima Tgl :</div>
                <div class="field-value">{{ $tanggal_disposisi }}</div>

                <div class="field-label" style="margin-top: 10px;">No. Agenda :</div>
                <div class="field-value">{{ $nomor_agenda }}</div>

                <div class="field-label" style="margin-top: 10px;">Sifat :</div>
                <div class="sifat-checkboxes">
                    <div class="checkbox-item">
                        <span class="checkbox {{ ($psu->sifat ?? '') == 'Biasa' ? 'checked' : '' }}"></span>
                        Biasa
                    </div>
                    <div class="checkbox-item">
                        <span class="checkbox {{ ($psu->sifat ?? '') == 'Penting' ? 'checked' : '' }}"></span>
                        Penting
                    </div>
                    <div class="checkbox-item">
                        <span class="checkbox {{ ($psu->sifat ?? '') == 'Segera' ? 'checked' : '' }}"></span>
                        Segera
                    </div>
                    <div class="checkbox-item">
                        <span class="checkbox {{ ($psu->sifat ?? '') == 'Rahasia' ? 'checked' : '' }}"></span>
                        Rahasia
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Perihal -->
        <div class="form-row">
            <div class="form-cell" style="width: 100%;">
                <div class="field-label">Perihal :</div>
                <div class="large-field">{{ $perihal ?? $psu->hal }}</div>
            </div>
        </div>

        <!-- Row 3: Diteruskan kepada & Dengan hormat harap -->
        <div class="form-row disposisi-section">
            <div class="form-cell form-cell-left">
                <div class="field-label">Diteruskan kepada Sdr. :</div>
                <div class="checkbox-group">
                    @php
                        $diteruskanKepada = $diteruskan_kepada ?? 'Back Office';
                        $isBackOffice = $diteruskanKepada === 'Back Office';
                        $isSekretariat = $diteruskanKepada === 'Sekretariat';
                        $isBagianLain = !in_array($diteruskanKepada, ['Back Office', 'Sekretariat', 'Bagian Administrasi']);
                    @endphp

                    <div class="checkbox-item">
                        <span class="checkbox {{ $isBackOffice ? 'checked' : '' }}"></span>
                        @if($isBackOffice)
                            <span class="diteruskan-checked">Back Office</span>
                        @else
                            Back Office
                        @endif
                    </div>
                    <div class="checkbox-item">
                        <span class="checkbox {{ $isSekretariat ? 'checked' : '' }}"></span>
                        @if($isSekretariat)
                            <span class="diteruskan-checked">Sekretariat</span>
                        @else
                            Sekretariat
                        @endif
                    </div>
                    <div class="checkbox-item">
                        <span class="checkbox"></span> Bagian Administrasi
                    </div>
                    <div class="checkbox-item">
                        <span class="checkbox {{ $isBagianLain ? 'checked' : '' }}"></span>
                        @if($isBagianLain)
                            Dsrtnya <u>{{ $diteruskanKepada }}</u>
                        @else
                            Dsrtnya <span style="border-bottom: 1px dotted black; display: inline-block; width: 100px;"></span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-cell form-cell-right">
                <div class="field-label">Dengan hormat harap :</div>
                <div class="checkbox-group">
                    @php
                        // Parse instruksi dari catatan lurah jika ada
                        $catatanLurah = $catatan_lurah ?? '';
                        $hasTanggapan = str_contains(strtolower($catatanLurah), 'tanggapan');
                        $hasProsesLanjut = str_contains(strtolower($catatanLurah), 'proses');
                        $hasKoordinasi = str_contains(strtolower($catatanLurah), 'koordinasi');
                    @endphp

                    <div class="checkbox-item">
                        <span class="checkbox {{ $hasTanggapan ? 'checked' : '' }}"></span> Tanggapan dan Saran
                    </div>
                    <div class="checkbox-item">
                        <span class="checkbox {{ $hasProsesLanjut ? 'checked' : 'checked' }}"></span> Proses lebih lanjut
                    </div>
                    <div class="checkbox-item">
                        <span class="checkbox {{ $hasKoordinasi ? 'checked' : '' }}"></span> Koordinasi/konfirmasikan
                    </div>
                    <div class="checkbox-item">
                        <span class="checkbox"></span> <span style="border-bottom: 1px dotted black; display: inline-block; width: 150px;"></span>
                        <br><span style="margin-left: 20px; border-bottom: 1px dotted black; display: inline-block; width: 150px;"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 4: Catatan (Filled) -->
        <div class="form-row catatan-section">
            <div class="form-cell" style="width: 100%;">
                <div class="field-label">Catatan :</div>
                <div class="filled-catatan">
                    {{ $catatan_lurah ?? 'Mohon diproses sesuai ketentuan yang berlaku. Terima kasih.' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Signature Section - SAMA PERSIS SEPERTI TANDA TERIMA -->
    <div class="signatures">
        <div class="signature-section">
            <div class="signature-header">
                Kelurahan Surabaya, {{ $tanggal_disposisi }}
                <br>Lurah
            </div>

            <!-- Container untuk TTD dan Stempel yang menyatu -->
            <div class="signature-container">
                <!-- TTD Layer -->
                @if(isset($ttd_lurah) && $ttd_lurah)
                    <div class="ttd-signature">
                        @php
                            $ttdBase64 = null;
                            if (!str_starts_with($ttd_lurah, 'data:image/')) {
                                if (str_starts_with($ttd_lurah, '/storage/')) {
                                    $ttdPath = storage_path('app/public/' . str_replace('/storage/', '', $ttd_lurah));
                                } else {
                                    $ttdPath = storage_path('app/public/' . $ttd_lurah);
                                }

                                if (file_exists($ttdPath)) {
                                    $ttdBase64 = 'data:image/' . pathinfo($ttdPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($ttdPath));
                                }
                            } else {
                                $ttdBase64 = $ttd_lurah;
                            }
                        @endphp
                        @if($ttdBase64)
                            <img src="{{ $ttdBase64 }}" alt="TTD Lurah">
                        @endif
                    </div>
                @endif

                <!-- Stempel Layer -->
                @if(isset($psu->metadata['stempel_kelurahan_disposisi']) && $psu->metadata['stempel_kelurahan_disposisi'])
                    <div class="stamp-overlay">
                        @php
                            $stempelKelurahan = $psu->metadata['stempel_kelurahan_disposisi'];
                            $stempelBase64 = null;

                            if (!str_starts_with($stempelKelurahan, 'data:image/')) {
                                if (str_starts_with($stempelKelurahan, '/storage/')) {
                                    $stempelPath = storage_path('app/public/' . str_replace('/storage/', '', $stempelKelurahan));
                                } else {
                                    $stempelPath = storage_path('app/public/' . $stempelKelurahan);
                                }

                                if (file_exists($stempelPath)) {
                                    $stempelBase64 = 'data:image/' . pathinfo($stempelPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($stempelPath));
                                }
                            } else {
                                $stempelBase64 = $stempelKelurahan;
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
                ({{ $lurah_name ?? 'Lurah' }})
            </div>
        </div>
    </div>

    <!-- Metadata untuk tracking -->
    <div style="position: absolute; bottom: 10px; left: 10px; font-size: 8px; color: #666;">
        Ditandatangani: {{ $tanggal_disposisi }} | Agenda: {{ $nomor_agenda }} | Diteruskan ke: {{ $diteruskan_kepada ?? 'Back Office' }}
    </div>
</body>
</html>
