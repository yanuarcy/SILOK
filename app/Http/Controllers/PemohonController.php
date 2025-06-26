<?php

namespace App\Http\Controllers;

use App\Exports\PemohonExport;
use App\Models\Pemohon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class PemohonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.masterdata.pemohon.data-pemohon', [
            'type_menu' => 'master-data',
            'pageTitle' => 'Data Pemohon'
        ]);
    }

    /**
     * Get data untuk DataTables
     */
    public function getData(Request $request)
    {
        try {
            $query = Pemohon::query();

            // ✅ Filter berdasarkan tanggal - LEBIH FLEKSIBEL
            if ($request->has('start_date') && $request->has('end_date') &&
                !empty($request->start_date) && !empty($request->end_date)) {

                $startDate = Carbon::parse($request->start_date)->startOfDay();
                $endDate = Carbon::parse($request->end_date)->endOfDay();

                \Log::info('Date filter applied', [
                    'start_date' => $startDate->toDateTimeString(),
                    'end_date' => $endDate->toDateTimeString()
                ]);

                $query->whereBetween('tanggal', [$startDate, $endDate]);
            }
            // ✅ Jika tidak ada filter tanggal, tampilkan SEMUA data (tidak hanya hari ini)
            // else {
            //     $query->whereDate('tanggal', Carbon::today());
            // }

            // ✅ Filter berdasarkan status
            if ($request->has('status') && $request->status !== '' && $request->status !== null) {
                $query->where('status', $request->status);
                \Log::info('Status filter applied: ' . $request->status);
            }

            // ✅ Filter berdasarkan jenis layanan
            if ($request->has('jenis_layanan') && $request->jenis_layanan !== '' && $request->jenis_layanan !== null) {
                $query->where('jenis_layanan', $request->jenis_layanan);
                \Log::info('Jenis layanan filter applied: ' . $request->jenis_layanan);
            }

            // ✅ Filter berdasarkan jenis antrian
            if ($request->has('jenis_antrian') && $request->jenis_antrian !== '' && $request->jenis_antrian !== null) {
                $query->where('jenis_antrian', $request->jenis_antrian);
                \Log::info('Jenis antrian filter applied: ' . $request->jenis_antrian);
            }

            // Debug: Log final query count
            $totalRecords = $query->count();
            \Log::info('Total records after filters: ' . $totalRecords);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('tanggal_formatted', function ($row) {
                    return $row->tanggal->format('Y-m-d H:i:s');
                })
                ->addColumn('status_badge', function ($row) {
                    return $row->status === '1'
                        ? '<span class="badge bg-success">Terlayani</span>'
                        : '<span class="badge bg-secondary">Belum Terlayani</span>';
                })
                ->addColumn('tanggal_dilayani_formatted', function ($row) {
                    return $row->tanggal_dilayani
                        ? $row->tanggal_dilayani->format('Y-m-d H:i:s')
                        : 'NULL';
                })
                ->rawColumns(['status_badge'])
                ->make(true);

        } catch (\Exception $e) {
            \Log::error('Error in PemohonController getData: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'error' => true,
                'message' => 'Error processing request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics untuk dashboard
     */
    public function getStatistics(Request $request)
    {
        try {
            $startDate = $request->get('start_date')
                ? Carbon::parse($request->get('start_date'))->startOfDay()
                : Carbon::today()->startOfDay();

            $endDate = $request->get('end_date')
                ? Carbon::parse($request->get('end_date'))->endOfDay()
                : Carbon::today()->endOfDay();

            $baseQuery = Pemohon::whereBetween('tanggal', [$startDate, $endDate]);
            $totalPemohon = $baseQuery->count();

            $stats = [
                'total_pemohon' => $totalPemohon,
                'terlayani' => Pemohon::whereBetween('tanggal', [$startDate, $endDate])
                    ->where('status', '1')->count(),
                'belum_terlayani' => Pemohon::whereBetween('tanggal', [$startDate, $endDate])
                    ->where('status', '0')->count(),
                'online' => Pemohon::whereBetween('tanggal', [$startDate, $endDate])
                    ->where('jenis_antrian', 'Online')->count(),
                'offline' => Pemohon::whereBetween('tanggal', [$startDate, $endDate])
                    ->where('jenis_antrian', 'Offline')->count(),
                'hari_ini' => Pemohon::whereDate('tanggal', Carbon::today())->count(),
            ];

            // Statistik per jenis layanan
            $layananStats = Pemohon::whereBetween('tanggal', [$startDate, $endDate])
                ->select('jenis_layanan', DB::raw('count(*) as total'))
                ->groupBy('jenis_layanan')
                ->get();

            // Statistik harian (7 hari terakhir)
            $dailyStats = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $count = Pemohon::whereDate('tanggal', $date)->count();
                $dailyStats[] = [
                    'date' => $date->format('Y-m-d'),
                    'total' => $count
                ];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'summary' => $stats,
                    'layanan_stats' => $layananStats,
                    'daily_stats' => $dailyStats
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export data to Excel
     */
    public function exportExcel(Request $request)
    {
        try {
            // ✅ PERBAIKAN: Parse tanggal dengan benar (sama seperti PDF)
            $startDate = $request->get('start_date')
                ? Carbon::parse($request->get('start_date'))->startOfDay()
                : Carbon::today()->startOfDay();

            $endDate = $request->get('end_date')
                ? Carbon::parse($request->get('end_date'))->endOfDay()
                : Carbon::today()->endOfDay();

            // ✅ PERBAIKAN: Gunakan query yang sama seperti PDF export
            if ($startDate->isSameDay($endDate)) {
                // Untuk hari yang sama
                $query = Pemohon::whereDate('tanggal', $startDate->format('Y-m-d'));
            } else {
                // Untuk range tanggal
                $query = Pemohon::whereBetween('tanggal', [$startDate, $endDate]);
            }

            // ✅ PERBAIKAN: Apply filter yang sama seperti di PDF export
            if ($request->has('status') && $request->status !== '' && $request->status !== null) {
                $query->where('status', $request->status);
            }

            if ($request->has('jenis_antrian') && $request->jenis_antrian !== '' && $request->jenis_antrian !== null) {
                $query->where('jenis_antrian', $request->jenis_antrian);
            }

            if ($request->has('jenis_layanan') && $request->jenis_layanan !== '' && $request->jenis_layanan !== null) {
                $query->where('jenis_layanan', $request->jenis_layanan);
            }

            $data = $query->orderBy('tanggal', 'desc')->get();

            // ✅ PERBAIKAN: Log untuk debugging
            \Log::info('Excel Export requested', [
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'total_records' => $data->count(),
                'filters' => [
                    'status' => $request->get('status'),
                    'jenis_antrian' => $request->get('jenis_antrian'),
                    'jenis_layanan' => $request->get('jenis_layanan')
                ]
            ]);

            // ✅ PERBAIKAN: Nama file yang lebih deskriptif
            $filename = 'laporan-pemohon-excel-' . $startDate->format('Y-m-d');
            if (!$startDate->isSameDay($endDate)) {
                $filename .= '-to-' . $endDate->format('Y-m-d');
            }

            // Tambahkan filter ke nama file jika ada
            if ($request->has('status') && $request->status !== '') {
                $filename .= '-status-' . ($request->status === '1' ? 'terlayani' : 'belum');
            }
            if ($request->has('jenis_antrian') && $request->jenis_antrian !== '') {
                $filename .= '-' . strtolower($request->jenis_antrian);
            }

            $filename .= '.xlsx';

            // ✅ PERBAIKAN: Pass data dan metadata ke export class
            return Excel::download(new PemohonExport($data, [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'generated_at' => now(),
                'filters' => [
                    'status' => $request->get('status'),
                    'jenis_antrian' => $request->get('jenis_antrian'),
                    'jenis_layanan' => $request->get('jenis_layanan')
                ]
            ]), $filename);

        } catch (\Exception $e) {
            \Log::error('Error exporting Excel: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return back()->with('error', 'Error exporting Excel: ' . $e->getMessage());
        }
    }

    /**
     * Export data to PDF
     */
    public function exportPdf(Request $request)
    {
        try {
            // ✅ PERBAIKAN: Parse tanggal dengan benar
            $startDate = $request->get('start_date')
                ? Carbon::parse($request->get('start_date'))->startOfDay()
                : Carbon::today()->startOfDay();

            $endDate = $request->get('end_date')
                ? Carbon::parse($request->get('end_date'))->endOfDay()
                : Carbon::today()->endOfDay();

            // ✅ PERBAIKAN: Gunakan query yang sama seperti getData dan getStatistics
            if ($startDate->isSameDay($endDate)) {
                // Untuk hari yang sama
                $query = Pemohon::whereDate('tanggal', $startDate->format('Y-m-d'));
            } else {
                // Untuk range tanggal
                $query = Pemohon::whereBetween('tanggal', [$startDate, $endDate]);
            }

            // ✅ PERBAIKAN: Apply filter yang sama seperti di getData jika ada
            if ($request->has('status') && $request->status !== '' && $request->status !== null) {
                $query->where('status', $request->status);
            }

            if ($request->has('jenis_antrian') && $request->jenis_antrian !== '' && $request->jenis_antrian !== null) {
                $query->where('jenis_antrian', $request->jenis_antrian);
            }

            if ($request->has('jenis_layanan') && $request->jenis_layanan !== '' && $request->jenis_layanan !== null) {
                $query->where('jenis_layanan', $request->jenis_layanan);
            }

            $data = $query->orderBy('tanggal', 'desc')->get();

            // ✅ PERBAIKAN: Log untuk debugging
            \Log::info('PDF Export requested', [
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'total_records' => $data->count(),
                'filters' => [
                    'status' => $request->get('status'),
                    'jenis_antrian' => $request->get('jenis_antrian'),
                    'jenis_layanan' => $request->get('jenis_layanan')
                ]
            ]);

            // ✅ PERBAIKAN: Konfigurasi PDF yang lebih baik
            $pdf = Pdf::loadView('admin.masterdata.Pemohon.ExportPDF', [
                'data' => $data,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'generated_at' => now(),
                'filters' => [
                    'status' => $request->get('status'),
                    'jenis_antrian' => $request->get('jenis_antrian'),
                    'jenis_layanan' => $request->get('jenis_layanan')
                ]
            ])
            ->setPaper('a4', 'landscape') // ✅ Landscape untuk tabel yang lebar
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
                'dpi' => 150,
                'defaultPaperSize' => 'a4',
                'chroot' => public_path(),
            ]);

            // ✅ PERBAIKAN: Nama file yang lebih deskriptif
            $filename = 'Laporan Data Pemohon Antrian Kelurahan Jemurwonosari-' . $startDate->format('Y-m-d');
            if (!$startDate->isSameDay($endDate)) {
                $filename .= '-to-' . $endDate->format('Y-m-d');
            }

            // Tambahkan filter ke nama file jika ada
            if ($request->has('status') && $request->status !== '') {
                $filename .= '-status-' . ($request->status === '1' ? 'terlayani' : 'belum');
            }
            if ($request->has('jenis_antrian') && $request->jenis_antrian !== '') {
                $filename .= '-' . strtolower($request->jenis_antrian);
            }

            $filename .= '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('Error exporting PDF: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return back()->with('error', 'Error exporting PDF: ' . $e->getMessage());
        }
    }

    public function previewPdf(Request $request)
    {
        try {
            // ✅ PERBAIKAN: Gunakan logika yang SAMA PERSIS dengan exportPdf
            $startDate = $request->get('start_date')
                ? Carbon::parse($request->get('start_date'))->startOfDay()
                : Carbon::today()->startOfDay();

            $endDate = $request->get('end_date')
                ? Carbon::parse($request->get('end_date'))->endOfDay()
                : Carbon::today()->endOfDay();

            // ✅ PERBAIKAN: Query yang sama persis
            if ($startDate->isSameDay($endDate)) {
                $query = Pemohon::whereDate('tanggal', $startDate->format('Y-m-d'));
            } else {
                $query = Pemohon::whereBetween('tanggal', [$startDate, $endDate]);
            }

            // ✅ PERBAIKAN: Apply filters dengan cara yang sama
            if ($request->has('status') && $request->status !== '' && $request->status !== null) {
                $query->where('status', $request->status);
            }

            if ($request->has('jenis_antrian') && $request->jenis_antrian !== '' && $request->jenis_antrian !== null) {
                $query->where('jenis_antrian', $request->jenis_antrian);
            }

            if ($request->has('jenis_layanan') && $request->jenis_layanan !== '' && $request->jenis_layanan !== null) {
                $query->where('jenis_layanan', $request->jenis_layanan);
            }

            $data = $query->orderBy('tanggal', 'desc')->get();

            // ✅ TAMBAHAN: Log untuk debugging preview
            \Log::info('PDF Preview requested', [
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'total_records' => $data->count(),
                'filters' => [
                    'status' => $request->get('status'),
                    'jenis_antrian' => $request->get('jenis_antrian'),
                    'jenis_layanan' => $request->get('jenis_layanan')
                ],
                'request_params' => $request->all()
            ]);

            $pdf = Pdf::loadView('admin.masterdata.Pemohon.ExportPDF', [
                'data' => $data,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'generated_at' => now(),
                'filters' => [
                    'status' => $request->get('status'),
                    'jenis_antrian' => $request->get('jenis_antrian'),
                    'jenis_layanan' => $request->get('jenis_layanan')
                ]
            ])
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
                'dpi' => 150,
                'defaultPaperSize' => 'a4'
            ]);

            return $pdf->stream('preview-laporan-pemohon.pdf');

        } catch (\Exception $e) {
            \Log::error('Error previewing PDF: ' . $e->getMessage());
            \Log::error('Request params: ' . json_encode($request->all()));
            return back()->with('error', 'Error previewing PDF: ' . $e->getMessage());
        }
    }

    public function printData(Request $request)
    {
        try {
            // ✅ PERBAIKAN: Parse tanggal dengan benar (sama seperti PDF)
            $startDate = $request->get('start_date')
                ? Carbon::parse($request->get('start_date'))->startOfDay()
                : Carbon::today()->startOfDay();

            $endDate = $request->get('end_date')
                ? Carbon::parse($request->get('end_date'))->endOfDay()
                : Carbon::today()->endOfDay();

            // ✅ PERBAIKAN: Gunakan query yang sama seperti PDF export
            if ($startDate->isSameDay($endDate)) {
                // Untuk hari yang sama
                $query = Pemohon::whereDate('tanggal', $startDate->format('Y-m-d'));
            } else {
                // Untuk range tanggal
                $query = Pemohon::whereBetween('tanggal', [$startDate, $endDate]);
            }

            // ✅ PERBAIKAN: Apply filter yang sama seperti di PDF export
            if ($request->has('status') && $request->status !== '' && $request->status !== null) {
                $query->where('status', $request->status);
            }

            if ($request->has('jenis_antrian') && $request->jenis_antrian !== '' && $request->jenis_antrian !== null) {
                $query->where('jenis_antrian', $request->jenis_antrian);
            }

            if ($request->has('jenis_layanan') && $request->jenis_layanan !== '' && $request->jenis_layanan !== null) {
                $query->where('jenis_layanan', $request->jenis_layanan);
            }

            $data = $query->orderBy('tanggal', 'desc')->get();

            // ✅ PERBAIKAN: Log untuk debugging
            \Log::info('Print requested', [
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'total_records' => $data->count(),
                'filters' => [
                    'status' => $request->get('status'),
                    'jenis_antrian' => $request->get('jenis_antrian'),
                    'jenis_layanan' => $request->get('jenis_layanan')
                ]
            ]);

            // ✅ Return view untuk print
            return view('admin.masterdata.Pemohon.PrintData', [
                'data' => $data,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'generated_at' => now(),
                'filters' => [
                    'status' => $request->get('status'),
                    'jenis_antrian' => $request->get('jenis_antrian'),
                    'jenis_layanan' => $request->get('jenis_layanan')
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error printing data: ' . $e->getMessage());
            return back()->with('error', 'Error printing data: ' . $e->getMessage());
        }
    }

    /**
     * Get filter options untuk dropdown
     */
    public function getFilterOptions()
    {
        try {
            $jenisLayanan = Pemohon::select('jenis_layanan')
                ->distinct()
                ->orderBy('jenis_layanan')
                ->pluck('jenis_layanan')
                ->filter(); // Remove empty values

            return response()->json([
                'success' => true,
                'data' => [
                    'jenis_layanan' => $jenisLayanan->values(), // Reset array keys
                    'jenis_antrian' => ['Online', 'Offline'],
                    'status' => [
                        ['value' => '0', 'label' => 'Belum Terlayani'],
                        ['value' => '1', 'label' => 'Terlayani']
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting filter options: ' . $e->getMessage()
            ], 500);
        }
    }
}
