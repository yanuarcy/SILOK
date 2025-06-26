<?php

namespace App\Http\Controllers;

use App\Models\BankData;
use Illuminate\Http\Request;

class BankDataController extends Controller
{
    public function index(Request $request)
    {
        $jenisOptions = BankData::getJenisOptions();
        $tahunOptions = BankData::getYearOptions();

        $query = BankData::published()->active()->with(['creator']);

        // Apply search filter - improved to match Perpu system
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul_kegiatan', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");

                // Search in JSON tags field
                if (is_string($search)) {
                    $q->orWhereRaw('JSON_SEARCH(tags, "one", ?) IS NOT NULL', ["%{$search}%"]);
                }
            });
        }

        // Apply jenis filter
        if ($request->filled('jenis')) {
            $query->where('jenis_bank_data', $request->jenis);
        }

        // Apply tahun filter
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_kegiatan', $request->tahun);
        }

        // Apply RW filter (optional additional filter)
        if ($request->filled('rw')) {
            $query->where('nomor_rw', $request->rw);
        }

        // Apply RT filter (optional additional filter)
        if ($request->filled('rt')) {
            $query->where('nomor_rt', $request->rt);
        }

        // Get bank data dengan paginasi dan urutan yang tepat
        $bankData = $query->orderBy('tanggal_kegiatan', 'desc')
                         ->orderBy('urutan_tampil', 'asc')
                         ->paginate(12);

        // Get statistics - optimized queries
        $stats = [
            'total_kelurahan' => BankData::published()->active()->where('jenis_bank_data', 'Kelurahan')->count(),
            'total_rw' => BankData::published()->active()->where('jenis_bank_data', 'RW')->count(),
            'total_rt' => BankData::published()->active()->where('jenis_bank_data', 'RT')->count(),
            'total_files' => BankData::published()->active()->get()->sum(function($item) {
                $fotoCount = $item->files_foto ? count($item->files_foto) : 0;
                $videoCount = $item->files_video ? count($item->files_video) : 0;
                return $fotoCount + $videoCount;
            }),
        ];

        // Get recent activities
        $recentActivities = BankData::published()
            ->active()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('layanan.BankData.index', compact(
            'bankData', 'jenisOptions', 'tahunOptions', 'stats', 'recentActivities'
        ));
    }

    public function show($id)
    {
        $bankData = BankData::published()
            ->active()
            ->with(['creator'])
            ->findOrFail($id);

        // Increment view count
        $bankData->incrementViewCount();

        // Get related bank data (same jenis or same area)
        $relatedQuery = BankData::published()
            ->active()
            ->where('id', '!=', $bankData->id);

        if ($bankData->jenis_bank_data === 'RT') {
            $relatedQuery->where(function($q) use ($bankData) {
                $q->where('nomor_rt', $bankData->nomor_rt)
                  ->orWhere('nomor_rw', $bankData->nomor_rw);
            });
        } elseif ($bankData->jenis_bank_data === 'RW') {
            $relatedQuery->where('nomor_rw', $bankData->nomor_rw);
        } else {
            $relatedQuery->where('jenis_bank_data', $bankData->jenis_bank_data);
        }

        $relatedBankData = $relatedQuery->orderBy('tanggal_kegiatan', 'desc')
                                       ->limit(6)
                                       ->get();

        return view('layanan.BankData.show', compact('bankData', 'relatedBankData'));
    }

    public function getByJenis($jenis)
    {
        $bankData = BankData::published()
            ->active()
            ->byJenis($jenis)
            ->orderBy('tanggal_kegiatan', 'desc')
            ->paginate(12);

        $jenisName = BankData::getJenisOptions()[$jenis] ?? $jenis;

        return view('layanan.bankdata.by-jenis', compact('bankData', 'jenis', 'jenisName'));
    }

    public function getByRW($nomor_rw)
    {
        $bankData = BankData::published()
            ->active()
            ->byRW($nomor_rw)
            ->orderBy('tanggal_kegiatan', 'desc')
            ->paginate(12);

        return view('layanan.bankdata.by-rw', compact('bankData', 'nomor_rw'));
    }

    public function getByRT($nomor_rt, $nomor_rw)
    {
        $bankData = BankData::published()
            ->active()
            ->byRT($nomor_rt)
            ->byRW($nomor_rw)
            ->orderBy('tanggal_kegiatan', 'desc')
            ->paginate(12);

        return view('layanan.bankdata.by-rt', compact('bankData', 'nomor_rt', 'nomor_rw'));
    }

    /**
     * Search API endpoint for AJAX requests
     */
    public function search(Request $request)
    {
        $query = BankData::published()->active();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('judul_kegiatan', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");

                // Search in JSON tags
                if (is_string($search)) {
                    $q->orWhereRaw('JSON_SEARCH(tags, "one", ?) IS NOT NULL', ["%{$search}%"]);
                }
            });
        }

        $results = $query->select('id', 'judul_kegiatan', 'jenis_bank_data', 'tanggal_kegiatan')
                        ->orderBy('tanggal_kegiatan', 'desc')
                        ->limit(10)
                        ->get();

        return response()->json($results);
    }

    /**
     * Get suggestions for autocomplete
     */
    public function suggestions(Request $request)
    {
        $term = $request->get('term', '');

        if (strlen($term) < 2) {
            return response()->json([]);
        }

        $suggestions = BankData::published()
            ->active()
            ->where('judul_kegiatan', 'like', "%{$term}%")
            ->select('judul_kegiatan')
            ->distinct()
            ->limit(5)
            ->pluck('judul_kegiatan')
            ->toArray();

        return response()->json($suggestions);
    }
}
