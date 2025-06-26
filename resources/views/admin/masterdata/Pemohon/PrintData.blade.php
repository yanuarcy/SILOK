@php
    include_once app_path('Helpers/GeneralSettings.php');
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Data Antrian Pemohon - {{ getSiteTitle() }}</title>
    <style>
        @page {
            margin: 15mm;
            size: A4 landscape;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            width: 100%;
        }

        .container {
            width: 100%;
            max-width: none;
            padding: 0;
        }

        /* ✅ HEADER */
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #2c5aa0;
        }

        .header h1 {
            font-size: 24px;
            color: #2c5aa0;
            font-weight: bold;
            margin-bottom: 8px;
            letter-spacing: 1.5px;
        }

        .header .subtitle {
            font-size: 16px;
            color: #666;
            margin-bottom: 10px;
        }

        .header .period {
            font-size: 14px;
            color: #333;
            font-weight: 600;
            background: #f8f9fa;
            padding: 10px 20px;
            border-radius: 8px;
            display: inline-block;
            border: 1px solid #dee2e6;
        }

        /* ✅ STATS CARDS UNTUK PRINT */
        .stats-container {
            width: 100%;
            margin-bottom: 25px;
            overflow: hidden;
        }

        .stats-row {
            width: 100%;
            display: table;
            table-layout: fixed;
            border-spacing: 15px 0;
        }

        .stats-card {
            display: table-cell;
            width: 25%;
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
            height: 70px;
            vertical-align: top;
        }

        .stats-card-inner {
            display: table;
            width: 100%;
            height: 70px;
        }

        .stats-card .icon-box {
            display: table-cell;
            width: 70px;
            height: 70px;
            vertical-align: middle;
            text-align: center;
            color: white;
            font-size: 20px;
            font-weight: bold;
        }

        .stats-card.total .icon-box { background: #6366f1; }
        .stats-card.terlayani .icon-box { background: #10b981; }
        .stats-card.belum .icon-box { background: #ef4444; }
        .stats-card.generated .icon-box { background: #f59e0b; }

        .stats-card .content {
            display: table-cell;
            padding: 10px 15px;
            vertical-align: middle;
        }

        .stats-card .title {
            font-size: 10px;
            color: #666;
            font-weight: 600;
            margin-bottom: 5px;
            line-height: 1.1;
        }

        .stats-card .value {
            font-size: 18px;
            font-weight: bold;
            line-height: 1;
            color: #333;
        }

        .stats-card.generated .value {
            font-size: 10px;
            font-weight: 600;
            line-height: 1.2;
        }

        /* ✅ TABLE PRINT STYLING */
        .table-section {
            margin-top: 20px;
        }

        .table-container {
            width: 100%;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #dee2e6;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            background: white;
        }

        th {
            background: #374151;
            color: white;
            padding: 10px 6px;
            text-align: center;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border: 1px solid #374151;
            white-space: normal;
            line-height: 1.2;
            word-wrap: break-word;
        }

        td {
            padding: 8px 6px;
            border: 1px solid #dee2e6;
            vertical-align: top;
            font-size: 9px;
            line-height: 1.3;
            word-wrap: break-word;
            white-space: normal;
            overflow-wrap: break-word;
        }

        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tbody tr:nth-child(odd) {
            background-color: white;
        }

        .text-center { text-align: center; }
        .text-left { text-align: left; }

        /* ✅ BADGES UNTUK PRINT */
        .status-badge {
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-block;
            min-width: 60px;
        }

        .status-terlayani {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-belum {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .jenis-badge {
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 8px;
            font-weight: 600;
            display: inline-block;
            min-width: 50px;
        }

        .jenis-online {
            background-color: #cce5ff;
            color: #0056b3;
            border: 1px solid #b3d9ff;
        }

        .jenis-offline {
            background-color: #e2e6ea;
            color: #495057;
            border: 1px solid #ced4da;
        }

        /* ✅ COLUMN WIDTHS */
        .col-no { width: 3%; }
        .col-tanggal { width: 9%; }
        .col-nama { width: 12%; }
        .col-whatsapp { width: 8%; }
        .col-alamat { width: 16%; }
        .col-layanan { width: 10%; }
        .col-keterangan { width: 12%; }
        .col-antrian { width: 7%; }
        .col-pengiriman { width: 7%; }
        .col-status { width: 7%; }
        .col-dilayani { width: 6%; }
        .col-tanggal-dilayani { width: 3%; }

        /* ✅ NO DATA STATE */
        .no-data {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
            background: #f8f9fa;
            border-radius: 8px;
            margin: 20px 0;
            border: 2px dashed #dee2e6;
        }

        .no-data h3 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #6c757d;
        }

        /* ✅ SUMMARY SECTION */
        .summary-section {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            font-size: 11px;
            page-break-inside: avoid;
        }

        .summary-title {
            font-size: 14px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 10px;
        }

        /* ✅ FOOTER */
        .footer {
            margin-top: 25px;
            padding-top: 15px;
            border-top: 2px solid #dee2e6;
            font-size: 10px;
            color: #6c757d;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        /* ✅ PRINT SPECIFIC */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                font-size: 11px;
            }

            .page-break {
                page-break-before: always;
            }

            .no-break {
                page-break-inside: avoid;
            }

            th {
                font-size: 8px;
            }

            td {
                font-size: 8px;
            }

            /* ✅ Hide elements yang tidak perlu saat print */
            .no-print {
                display: none !important;
            }
        }

        /* ✅ PRINT BUTTON STYLING */
        .print-controls {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: white;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border: 1px solid #dee2e6;
        }

        .print-btn {
            background: #6366f1;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            margin-right: 5px;
        }

        .print-btn:hover {
            background: #5856eb;
        }

        .close-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
        }

        .close-btn:hover {
            background: #5a6169;
        }

        @media screen {
            body {
                background: #f5f5f5;
                padding: 20px;
            }
            .container {
                background: white;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                max-width: 1400px;
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>
    <!-- ✅ PRINT CONTROLS (HIDDEN SAAT PRINT) -->
    <div class="print-controls no-print">
        <button class="print-btn" onclick="window.print()">
            🖨️ Print
        </button>
        <button class="close-btn" onclick="window.close()">
            ✖️ Close
        </button>
    </div>

    <div class="container">
        <!-- ✅ HEADER -->
        <div class="header">
            <h1>DATA PEMOHON ANTRIAN KELURAHAN JEMURWONOSARI</h1>
            <p class="subtitle">Kelurahan Digital - Sistem Manajemen Antrian</p>
            <div class="period">
                📅 Periode: {{ $start_date->format('d F Y') }}
                @if(!$start_date->isSameDay($end_date))
                    s/d {{ $end_date->format('d F Y') }}
                @endif
            </div>
        </div>

        <!-- ✅ STATS CARDS -->
        <div class="stats-container">
            <div class="stats-row">
                <div class="stats-card total">
                    <div class="stats-card-inner">
                        <div class="icon-box">👥</div>
                        <div class="content">
                            <div class="title">Total Pemohon</div>
                            <div class="value">{{ $data->count() }}</div>
                        </div>
                    </div>
                </div>
                <div class="stats-card terlayani">
                    <div class="stats-card-inner">
                        <div class="icon-box">✓</div>
                        <div class="content">
                            <div class="title">Terlayani</div>
                            <div class="value">{{ $data->where('status', '1')->count() }}</div>
                        </div>
                    </div>
                </div>
                <div class="stats-card belum">
                    <div class="stats-card-inner">
                        <div class="icon-box">⏳</div>
                        <div class="content">
                            <div class="title">Belum Terlayani</div>
                            <div class="value">{{ $data->where('status', '0')->count() }}</div>
                        </div>
                    </div>
                </div>
                <div class="stats-card generated">
                    <div class="stats-card-inner">
                        <div class="icon-box">🕒</div>
                        <div class="content">
                            <div class="title">Tanggal Cetak</div>
                            <div class="value">{{ $generated_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($data->count() > 0)
        <!-- ✅ TABEL DATA -->
        <div class="table-section">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th class="col-no">NO</th>
                            <th class="col-tanggal">TANGGAL</th>
                            <th class="col-nama">NAMA</th>
                            <th class="col-whatsapp">NO WHATSAPP</th>
                            <th class="col-alamat">ALAMAT</th>
                            <th class="col-layanan">JENIS LAYANAN</th>
                            <th class="col-keterangan">KETERANGAN</th>
                            <th class="col-antrian">JENIS ANTRIAN</th>
                            <th class="col-pengiriman">JENIS PENGIRIMAN</th>
                            <th class="col-status">STATUS</th>
                            <th class="col-dilayani">DILAYANI OLEH</th>
                            <th class="col-tanggal-dilayani">TANGGAL DILAYANI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $index => $item)
                        <tr>
                            <td class="text-center"><strong>{{ $index + 1 }}</strong></td>
                            <td class="text-center">{{ $item->tanggal->format('Y-m-d H:i:s') }}</td>
                            <td class="text-left"><strong>{{ $item->nama }}</strong></td>
                            <td class="text-center">{{ $item->no_whatsapp ?: '-' }}</td>
                            <td class="text-left">{{ $item->alamat }}</td>
                            <td class="text-center">{{ $item->jenis_layanan }}</td>
                            <td class="text-left">{{ $item->keterangan }}</td>
                            <td class="text-center">
                                <span class="jenis-badge {{ $item->jenis_antrian === 'Online' ? 'jenis-online' : 'jenis-offline' }}">
                                    {{ $item->jenis_antrian }}
                                </span>
                            </td>
                            <td class="text-center">{{ $item->jenis_pengiriman ?: '-' }}</td>
                            <td class="text-center">
                                @if($item->status === '1')
                                    <span class="status-badge status-terlayani">Terlayani</span>
                                @else
                                    <span class="status-badge status-belum">Belum Terlayani</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $item->dilayani_oleh ?: 'NULL' }}</td>
                            <td class="text-center">
                                @if($item->tanggal_dilayani)
                                    {{ Carbon\Carbon::parse($item->tanggal_dilayani)->format('Y-m-d H:i:s') }}
                                @else
                                    NULL
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ✅ SUMMARY STATISTICS -->
        <div class="summary-section no-break">
            <div class="summary-title">📊 Ringkasan Statistik Lengkap</div>

            <div style="margin-bottom: 10px;">
                <strong>Berdasarkan Jenis Antrian:</strong>
                Online: <strong>{{ $data->where('jenis_antrian', 'Online')->count() }}</strong> pemohon |
                Offline: <strong>{{ $data->where('jenis_antrian', 'Offline')->count() }}</strong> pemohon<br>

                <strong>Tingkat Pelayanan:</strong>
                Persentase Terlayani: <strong>{{ $data->count() > 0 ? round(($data->where('status', '1')->count() / $data->count()) * 100, 1) : 0 }}%</strong> |
                Rata-rata per hari: <strong>{{ $data->count() }}</strong> pemohon
            </div>

            <div style="margin-bottom: 10px;">
                <strong>📋 Detail Berdasarkan Jenis Layanan:</strong><br>
                @php
                    $layananStats = $data->groupBy('jenis_layanan')->map(function($items, $layanan) {
                        return [
                            'total' => $items->count(),
                            'terlayani' => $items->where('status', '1')->count(),
                            'belum' => $items->where('status', '0')->count(),
                            'keterangan' => $items->pluck('keterangan')->filter()->unique()->implode(', ')
                        ];
                    });
                @endphp

                @foreach($layananStats as $layanan => $stats)
                    • <strong>{{ $layanan }}</strong>: {{ $stats['total'] }} pemohon
                    (Terlayani: {{ $stats['terlayani'] }}, Belum: {{ $stats['belum'] }})
                    @if($stats['keterangan'])
                        <br>&nbsp;&nbsp;Keterangan: <em>{{ Str::limit($stats['keterangan'], 80) }}</em>
                    @endif
                    <br>
                @endforeach
            </div>

            @if(isset($filters) && (isset($filters['status']) || isset($filters['jenis_antrian']) || isset($filters['jenis_layanan'])))
            <div style="border-top: 1px solid #dee2e6; padding-top: 8px; margin-top: 10px;">
                <strong>🔍 Filter yang Diterapkan:</strong>
                @if(isset($filters['status']) && $filters['status'] !== '')
                    Status: <strong>{{ $filters['status'] === '1' ? 'Terlayani' : 'Belum Terlayani' }}</strong> |
                @endif
                @if(isset($filters['jenis_antrian']) && $filters['jenis_antrian'] !== '')
                    Jenis Antrian: <strong>{{ $filters['jenis_antrian'] }}</strong> |
                @endif
                @if(isset($filters['jenis_layanan']) && $filters['jenis_layanan'] !== '')
                    Jenis Layanan: <strong>{{ $filters['jenis_layanan'] }}</strong>
                @endif
            </div>
            @endif
        </div>
        @else
        <!-- ✅ NO DATA STATE -->
        <div class="no-data">
            <h3>Tidak Ada Data</h3>
            <p>Tidak ada data pemohon untuk periode yang dipilih.</p>
            <p style="margin-top: 10px;">
                <strong>Periode:</strong> {{ $start_date->format('d F Y') }}
                @if(!$start_date->isSameDay($end_date))
                    s/d {{ $end_date->format('d F Y') }}
                @endif
            </p>
        </div>
        @endif

        <!-- ✅ FOOTER -->
        <div class="footer no-break">
            <div>
                <strong>🏢 Kelurahan Digital Jemurwonosari</strong><br>
                Sistem Manajemen Antrian Pelayanan<br>
                © {{ date('Y') }} - Semua hak cipta dilindungi
            </div>
            <div style="text-align: right;">
                📄 Laporan digenerate otomatis<br>
                🕒 {{ $generated_at->format('l, d F Y \p\u\k\u\l H:i:s') }}<br>
                👤 User: {{ auth()->user()->name ?? 'System' }}
            </div>
        </div>
    </div>

    <script>
        // ✅ AUTO PRINT SAAT HALAMAN LOAD (OPSIONAL)
        // window.onload = function() {
        //     window.print();
        // }

        // ✅ CLOSE WINDOW SETELAH PRINT (OPSIONAL)
        window.onafterprint = function() {
            // window.close();
        }
    </script>
</body>
</html>
