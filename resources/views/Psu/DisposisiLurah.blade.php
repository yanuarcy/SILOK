{{-- resources/views/Psu/DisposisiLurah.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lembar Disposisi - {{ $nomor_agenda }}</title>
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
            font-size: 11px;
        }

        .header-right {
            float: right;
            width: 40%;
            font-weight: bold;
            font-size: 11px;
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
            border: 1px dotted black;
            padding: 5px;
        }

        .signature-area {
            margin-top: 20px;
            text-align: right;
            padding-right: 50px;
        }

        .disposisi-section {
            border-bottom: 1px solid black;
        }

        .catatan-section {
            min-height: 100px;
        }

        .dotted-line {
            border-bottom: 1px dotted black;
            display: inline-block;
            min-width: 100px;
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
                    <div class="checkbox-item">
                        <span class="checkbox"></span> Back Office
                    </div>
                    <div class="checkbox-item">
                        <span class="checkbox"></span> Sekretariat
                    </div>
                    <div class="checkbox-item">
                        <span class="checkbox"></span> Bagian Administrasi
                    </div>
                    <div class="checkbox-item">
                        Dsrtnya <span class="dotted-line" style="width: 120px;"></span>
                    </div>
                </div>
            </div>
            <div class="form-cell form-cell-right">
                <div class="field-label">Dengan hormat harap :</div>
                <div class="checkbox-group">
                    <div class="checkbox-item">
                        <span class="checkbox"></span> Tanggapan dan Saran
                    </div>
                    <div class="checkbox-item">
                        <span class="checkbox"></span> Proses lebih lanjut
                    </div>
                    <div class="checkbox-item">
                        <span class="checkbox"></span> Koordinasi/konfirmasikan
                    </div>
                    <div class="checkbox-item">
                        <span class="checkbox"></span> <span class="dotted-line" style="width: 150px;"></span>
                        <br><span style="margin-left: 20px;" class="dotted-line"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 4: Catatan -->
        <div class="form-row catatan-section">
            <div class="form-cell" style="width: 100%;">
                <div class="field-label">Catatan :</div>
                <div style="min-height: 120px; padding: 10px;">
                    <!-- Space untuk catatan Lurah -->
                    <div style="height: 20px; border-bottom: 1px dotted #ccc; margin: 5px 0;"></div>
                    <div style="height: 20px; border-bottom: 1px dotted #ccc; margin: 5px 0;"></div>
                    <div style="height: 20px; border-bottom: 1px dotted #ccc; margin: 5px 0;"></div>
                    <div style="height: 20px; border-bottom: 1px dotted #ccc; margin: 5px 0;"></div>
                    <div style="height: 20px; border-bottom: 1px dotted #ccc; margin: 5px 0;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Signature Area -->
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

    <!-- Footer metadata -->
    <div style="position: absolute; bottom: 10px; left: 10px; font-size: 8px; color: #666;">
        Generated: {{ now()->format('d/m/Y H:i:s') }} | Agenda: {{ $nomor_agenda }} | Form: Kosong
    </div>
</body>
</html>
