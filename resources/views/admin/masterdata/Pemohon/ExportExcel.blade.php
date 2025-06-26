<table>
    <!-- ✅ TITLE ROW -->
    <tr>
        <td colspan="12" style="text-align: center; font-weight: bold; font-size: 16px; color: #2c5aa0; background-color: #f8f9fa; padding: 10px;">
            DATA PEMOHON ANTRIAN KELURAHAN JEMURWONOSARI
        </td>
    </tr>

    <!-- ✅ SUBTITLE ROW -->
    <tr>
        <td colspan="12" style="text-align: center; font-size: 12px; color: #666; padding: 5px;">
            Kelurahan Digital - Sistem Manajemen Antrian
        </td>
    </tr>

    <!-- ✅ PERIOD ROW -->
    <tr>
        <td colspan="12" style="text-align: center; font-size: 12px; font-weight: bold; padding: 8px; background-color: #e9ecef;">
            📅 Periode: {{ $start_date->format('d F Y') }}
            @if(!$start_date->isSameDay($end_date))
                s/d {{ $end_date->format('d F Y') }}
            @endif
        </td>
    </tr>

    <!-- ✅ STATS CARDS TITLES -->
    <tr>
        <td colspan="3" style="text-align: center; font-weight: bold; background-color: #6366f1; color: white; padding: 8px;">
            👥 Total Pemohon
        </td>
        <td colspan="3" style="text-align: center; font-weight: bold; background-color: #10b981; color: white; padding: 8px;">
            ✓ Terlayani
        </td>
        <td colspan="3" style="text-align: center; font-weight: bold; background-color: #ef4444; color: white; padding: 8px;">
            ⏳ Belum Terlayani
        </td>
        <td colspan="3" style="text-align: center; font-weight: bold; background-color: #f59e0b; color: white; padding: 8px;">
            🕒 Tanggal Cetak
        </td>
    </tr>

    <!-- ✅ STATS CARDS VALUES -->
    <tr>
        <td colspan="3" style="text-align: center; font-weight: bold; font-size: 20px; background-color: #6366f1; color: white; padding: 10px;">
            {{ $data->count() }}
        </td>
        <td colspan="3" style="text-align: center; font-weight: bold; font-size: 20px; background-color: #10b981; color: white; padding: 10px;">
            {{ $data->where('status', '1')->count() }}
        </td>
        <td colspan="3" style="text-align: center; font-weight: bold; font-size: 20px; background-color: #ef4444; color: white; padding: 10px;">
            {{ $data->where('status', '0')->count() }}
        </td>
        <td colspan="3" style="text-align: center; font-weight: bold; font-size: 12px; background-color: #f59e0b; color: white; padding: 10px;">
            {{ $generated_at->format('d/m/Y H:i') }}
        </td>
    </tr>

    <!-- ✅ EMPTY ROW FOR SPACING -->
    <tr>
        <td colspan="12" style="height: 10px;"></td>
    </tr>

    <!-- ✅ TABLE HEADERS -->
    <tr style="background-color: #374151; color: white; font-weight: bold; text-align: center;">
        <td style="padding: 10px; border: 1px solid #374151;">NO</td>
        <td style="padding: 10px; border: 1px solid #374151;">TANGGAL</td>
        <td style="padding: 10px; border: 1px solid #374151;">NAMA</td>
        <td style="padding: 10px; border: 1px solid #374151;">NO WHATSAPP</td>
        <td style="padding: 10px; border: 1px solid #374151;">ALAMAT</td>
        <td style="padding: 10px; border: 1px solid #374151;">JENIS LAYANAN</td>
        <td style="padding: 10px; border: 1px solid #374151;">KETERANGAN</td>
        <td style="padding: 10px; border: 1px solid #374151;">JENIS ANTRIAN</td>
        <td style="padding: 10px; border: 1px solid #374151;">JENIS PENGIRIMAN</td>
        <td style="padding: 10px; border: 1px solid #374151;">STATUS</td>
        <td style="padding: 10px; border: 1px solid #374151;">DILAYANI OLEH</td>
        <td style="padding: 10px; border: 1px solid #374151;">TANGGAL DILAYANI</td>
    </tr>

    <!-- ✅ DATA ROWS -->
    @if($data->count() > 0)
        @foreach($data as $index => $item)
        <tr style="{{ ($index % 2 == 0) ? 'background-color: #f8f9fa;' : 'background-color: white;' }}">
            <td style="text-align: center; padding: 8px; border: 1px solid #dee2e6;">{{ $index + 1 }}</td>
            <td style="text-align: center; padding: 8px; border: 1px solid #dee2e6;">{{ $item->tanggal->format('Y-m-d H:i:s') }}</td>
            <td style="padding: 8px; border: 1px solid #dee2e6; font-weight: bold;">{{ $item->nama }}</td>
            <td style="text-align: center; padding: 8px; border: 1px solid #dee2e6;">{{ $item->no_whatsapp ?: '-' }}</td>
            <td style="padding: 8px; border: 1px solid #dee2e6;">{{ $item->alamat }}</td>
            <td style="text-align: center; padding: 8px; border: 1px solid #dee2e6;">{{ $item->jenis_layanan }}</td>
            <td style="padding: 8px; border: 1px solid #dee2e6;">{{ $item->keterangan }}</td>
            <td style="text-align: center; padding: 8px; border: 1px solid #dee2e6;
                background-color: {{ $item->jenis_antrian === 'Online' ? '#cce5ff' : '#e2e6ea' }};
                color: {{ $item->jenis_antrian === 'Online' ? '#0056b3' : '#495057' }};">
                {{ $item->jenis_antrian }}
            </td>
            <td style="text-align: center; padding: 8px; border: 1px solid #dee2e6;">{{ $item->jenis_pengiriman ?: '-' }}</td>
            <td style="text-align: center; padding: 8px; border: 1px solid #dee2e6;
                background-color: {{ $item->status === '1' ? '#d4edda' : '#f8d7da' }};
                color: {{ $item->status === '1' ? '#155724' : '#721c24' }}; font-weight: bold;">
                {{ $item->status === '1' ? 'TERLAYANI' : 'BELUM TERLAYANI' }}
            </td>
            <td style="text-align: center; padding: 8px; border: 1px solid #dee2e6;">{{ $item->dilayani_oleh ?: 'NULL' }}</td>
            <td style="text-align: center; padding: 8px; border: 1px solid #dee2e6;">
                @if($item->tanggal_dilayani)
                    {{ Carbon\Carbon::parse($item->tanggal_dilayani)->format('Y-m-d H:i:s') }}
                @else
                    NULL
                @endif
            </td>
        </tr>
        @endforeach
    @else
        <!-- ✅ NO DATA ROW -->
        <tr>
            <td colspan="12" style="text-align: center; padding: 20px; font-style: italic; color: #6c757d; background-color: #f8f9fa;">
                📋 Tidak ada data pemohon untuk periode yang dipilih
            </td>
        </tr>
    @endif

    <!-- ✅ EMPTY ROW FOR SPACING -->
    <tr>
        <td colspan="12" style="height: 15px;"></td>
    </tr>

    <!-- ✅ SUMMARY STATISTICS SECTION -->
    <tr>
        <td colspan="12" style="font-weight: bold; font-size: 14px; color: #2c5aa0; padding: 10px; background-color: #f8f9fa;">
            📊 RINGKASAN STATISTIK LENGKAP
        </td>
    </tr>

    <!-- ✅ GENERAL STATS -->
    <tr>
        <td colspan="12" style="padding: 8px; border: 1px solid #dee2e6;">
            <strong>Berdasarkan Jenis Antrian:</strong>
            Online: {{ $data->where('jenis_antrian', 'Online')->count() }} pemohon |
            Offline: {{ $data->where('jenis_antrian', 'Offline')->count() }} pemohon
        </td>
    </tr>
    <tr>
        <td colspan="12" style="padding: 8px; border: 1px solid #dee2e6;">
            <strong>Tingkat Pelayanan:</strong>
            Persentase Terlayani: {{ $data->count() > 0 ? round(($data->where('status', '1')->count() / $data->count()) * 100, 1) : 0 }}% |
            Rata-rata per hari: {{ $data->count() }} pemohon
        </td>
    </tr>

    <!-- ✅ SERVICE TYPE BREAKDOWN -->
    <tr>
        <td colspan="12" style="font-weight: bold; padding: 8px; background-color: #e9ecef;">
            📋 Detail Berdasarkan Jenis Layanan:
        </td>
    </tr>
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
    <tr>
        <td colspan="12" style="padding: 6px 15px; border: 1px solid #dee2e6;">
            • <strong>{{ $layanan }}</strong>: {{ $stats['total'] }} pemohon
            (Terlayani: {{ $stats['terlayani'] }}, Belum: {{ $stats['belum'] }})
            @if($stats['keterangan'])
                <br>&nbsp;&nbsp;Keterangan: {{ Str::limit($stats['keterangan'], 100) }}
            @endif
        </td>
    </tr>
    @endforeach

    <!-- ✅ FOOTER INFO -->
    <tr>
        <td colspan="12" style="height: 10px;"></td>
    </tr>
    <tr>
        <td colspan="6" style="padding: 8px; font-size: 10px; color: #6c757d;">
            🏢 <strong>Kelurahan Digital Jemurwonosari</strong><br>
            Sistem Manajemen Antrian Pelayanan<br>
            © {{ date('Y') }} - Semua hak cipta dilindungi
        </td>
        <td colspan="6" style="padding: 8px; font-size: 10px; color: #6c757d; text-align: right;">
            📄 Laporan digenerate otomatis<br>
            🕒 {{ $generated_at->format('l, d F Y \p\u\k\u\l H:i:s') }}<br>
            👤 User: {{ auth()->user()->name ?? 'System' }}
        </td>
    </tr>
</table>
