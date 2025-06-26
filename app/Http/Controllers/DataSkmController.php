<?php

namespace App\Http\Controllers;

use App\Models\DataSkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Services\EnhancedSentimentAnalyzer;

class DataSkmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $type_menu = "master-data";
        return view('admin.data-skm.data-skm', compact('type_menu'));
    }

    /**
     * Get data for DataTables
     */
    public function data()
    {
        $query = DataSkm::with('user')->orderBy('created_at', 'desc');

        return DataTables::of($query)
            ->addColumn('nama_user', function ($row) {
                return $row->user ? $row->user->name : '-';
            })
            ->addColumn('tingkat_kepuasan_badge', function ($row) {
                $badges = [
                    'Sangat Puas' => '<span class="badge bg-success">Sangat Puas</span>',
                    'Puas' => '<span class="badge bg-primary">Puas</span>',
                    'Tidak Puas' => '<span class="badge bg-warning">Tidak Puas</span>',
                ];
                return $badges[$row->tingkat_kepuasan] ?? '<span class="badge bg-secondary">-</span>';
            })
            ->addColumn('status_badge', function ($row) {
                if ($row->status === 'active') {
                    return '<span class="badge bg-success">Ditampilkan</span>';
                } else {
                    return '<span class="badge bg-secondary">Tidak Ditampilkan</span>';
                }
            })
            ->addColumn('tanggal_formatted', function ($row) {
                return $row->created_at->format('d/m/Y H:i');
            })
            ->addColumn('kritik_saran_short', function ($row) {
                return \Str::limit($row->kritik_saran, 50);
            })
            ->addColumn('sentiment_badge', function ($row) {
                $sentiment = $this->analyzeSentiment($row->kritik_saran);
                $badges = [
                    'positive' => '<span class="badge bg-success"><i class="fas fa-smile"></i> Positif</span>',
                    'negative' => '<span class="badge bg-danger"><i class="fas fa-frown"></i> Negatif</span>',
                    'neutral' => '<span class="badge bg-warning"><i class="fas fa-meh"></i> Netral</span>',
                ];
                return $badges[$sentiment] ?? '<span class="badge bg-secondary">-</span>';
            })
            ->addColumn('actions', function ($row) {
                $toggleStatus = $row->status === 'active' ? 'inactive' : 'active';
                $toggleColor = $row->status === 'active' ? 'warning' : 'success';
                $toggleIcon = $row->status === 'active' ? 'fa-eye-slash' : 'fa-eye';
                $toggleText = $row->status === 'active' ? 'Sembunyikan dari Testimonial' : 'Tampilkan di Testimonial';

                return '
                    <div class="btn-group" role="group">
                        <button type="button"
                                class="btn btn-info btn-sm btn-detail"
                                data-id="' . $row->id . '"
                                title="Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button"
                                class="btn btn-' . $toggleColor . ' btn-sm btn-toggle-status"
                                data-id="' . $row->id . '"
                                data-status="' . $toggleStatus . '"
                                title="' . $toggleText . '">
                            <i class="fas ' . $toggleIcon . '"></i>
                        </button>
                    </div>
                ';
            })
            ->rawColumns(['tingkat_kepuasan_badge', 'status_badge', 'sentiment_badge', 'actions'])
            ->make(true);
    }

    /**
     * Get summary statistics for dashboard
     */
    public function getSummary()
    {
        try {
            $totalSurveys = DataSkm::count();
            $activeSurveys = DataSkm::where('status', 'active')->count();

            // Satisfaction statistics
            $sangatPuas = DataSkm::where('tingkat_kepuasan', 'Sangat Puas')->count();
            $puas = DataSkm::where('tingkat_kepuasan', 'Puas')->count();
            $tidakPuas = DataSkm::where('tingkat_kepuasan', 'Tidak Puas')->count();

            // Calculate percentages
            $positiveCount = $sangatPuas + $puas;
            $positivePercentage = $totalSurveys > 0 ? round(($positiveCount / $totalSurveys) * 100, 1) : 0;
            $negativePercentage = $totalSurveys > 0 ? round(($tidakPuas / $totalSurveys) * 100, 1) : 0;

            // Sentiment analysis from kritik_saran
            $surveys = DataSkm::select('kritik_saran')->get();
            $sentimentStats = $this->calculateSentimentStats($surveys);

            // Recent surveys (last 7 days)
            $recentSurveys = DataSkm::where('created_at', '>=', now()->subDays(7))->count();

            // Monthly trend (last 6 months)
            $monthlyTrend = DataSkm::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_surveys' => $totalSurveys,
                    'active_surveys' => $activeSurveys,
                    'sangat_puas' => $sangatPuas,
                    'puas' => $puas,
                    'tidak_puas' => $tidakPuas,
                    'positive_percentage' => $positivePercentage,
                    'negative_percentage' => $negativePercentage,
                    'sentiment_stats' => $sentimentStats,
                    'recent_surveys' => $recentSurveys,
                    'monthly_trend' => $monthlyTrend,
                    'satisfaction_index' => $this->calculateSatisfactionIndex($sangatPuas, $puas, $tidakPuas)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading summary data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate satisfaction index
     */
    private function calculateSatisfactionIndex($sangatPuas, $puas, $tidakPuas)
    {
        $total = $sangatPuas + $puas + $tidakPuas;
        if ($total == 0) return 0;

        // Weighted calculation: Sangat Puas = 4, Puas = 3, Tidak Puas = 1
        $weightedScore = ($sangatPuas * 4) + ($puas * 3) + ($tidakPuas * 1);
        $maxPossibleScore = $total * 4;

        return round(($weightedScore / $maxPossibleScore) * 100, 1);
    }

    /**
     * Calculate sentiment statistics from kritik_saran
     */
    private function calculateSentimentStats($surveys)
    {
        $positive = 0;
        $negative = 0;
        $neutral = 0;

        foreach ($surveys as $survey) {
            $sentiment = $this->analyzeSentiment($survey->kritik_saran);
            switch ($sentiment) {
                case 'positive':
                    $positive++;
                    break;
                case 'negative':
                    $negative++;
                    break;
                default:
                    $neutral++;
                    break;
            }
        }

        $total = $positive + $negative + $neutral;

        return [
            'positive' => $positive,
            'negative' => $negative,
            'neutral' => $neutral,
            'positive_percentage' => $total > 0 ? round(($positive / $total) * 100, 1) : 0,
            'negative_percentage' => $total > 0 ? round(($negative / $total) * 100, 1) : 0,
            'neutral_percentage' => $total > 0 ? round(($neutral / $total) * 100, 1) : 0,
        ];
    }

    /**
     * Simple sentiment analysis for Indonesian text
     */
    // private function analyzeSentiment($text)
    // {
    //     $text = strtolower($text);

    //     // Positive keywords in Indonesian
    //     $positiveWords = [
    //         'bagus', 'baik', 'memuaskan', 'sangat', 'excellent', 'luar biasa', 'sempurna',
    //         'ramah', 'cepat', 'responsif', 'profesional', 'berkualitas', 'mantap',
    //         'terima kasih', 'puas', 'senang', 'suka', 'recommended', 'terbaik',
    //         'pelayanan baik', 'top', 'oke', 'bagus sekali', 'maksimal'
    //     ];

    //     // Negative keywords in Indonesian
    //     $negativeWords = [
    //         'buruk', 'jelek', 'lambat', 'lama', 'tidak', 'kurang', 'gagal',
    //         'error', 'rusak', 'bermasalah', 'kecewa', 'mengecewakan', 'payah',
    //         'tidak puas', 'tidak baik', 'tidak bagus', 'tidak memuaskan',
    //         'perlu diperbaiki', 'harus', 'jangan', 'salah'
    //     ];

    //     $positiveScore = 0;
    //     $negativeScore = 0;

    //     foreach ($positiveWords as $word) {
    //         if (strpos($text, $word) !== false) {
    //             $positiveScore++;
    //         }
    //     }

    //     foreach ($negativeWords as $word) {
    //         if (strpos($text, $word) !== false) {
    //             $negativeScore++;
    //         }
    //     }

    //     if ($positiveScore > $negativeScore) {
    //         return 'positive';
    //     } elseif ($negativeScore > $positiveScore) {
    //         return 'negative';
    //     } else {
    //         return 'neutral';
    //     }
    // }

    private function analyzeSentiment($text)
    {
        $analyzer = new EnhancedSentimentAnalyzer();
        $result = $analyzer->analyzeSentiment($text);
        return $result['sentiment']; // 'positive', 'negative', 'neutral'
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('SKM.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'tingkat_kepuasan' => 'required|in:Sangat Puas,Puas,Tidak Puas',
            'kritik_saran' => 'required|string'
        ], [
            'nama.required' => 'Nama harus diisi',
            'alamat.required' => 'Alamat harus diisi',
            'tingkat_kepuasan.required' => 'Tingkat kepuasan harus dipilih',
            'kritik_saran.required' => 'Kritik dan saran harus diisi'
        ]);

        // Auto determine status based on satisfaction and sentiment
        $status = $this->autoActivateStatus($request->tingkat_kepuasan, $request->kritik_saran);

        DataSkm::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'tingkat_kepuasan' => $request->tingkat_kepuasan,
            'kritik_saran' => $request->kritik_saran,
            'status' => $status
        ]);

        return redirect()->route('skm.success');
    }

    /**
     * Auto activate status based on satisfaction level and sentiment
     */
    private function autoActivateStatus($tingkatKepuasan, $kritikSaran)
    {
        // Only auto-activate if satisfaction is positive
        if (!in_array($tingkatKepuasan, ['Sangat Puas', 'Puas'])) {
            return 'inactive';
        }

        // Check sentiment of kritik_saran
        $sentiment = $this->analyzeSentiment($kritikSaran);

        // Auto-activate if both satisfaction and sentiment are positive
        if (in_array($tingkatKepuasan, ['Sangat Puas', 'Puas']) && $sentiment === 'positive') {
            // Check if we don't exceed the testimonial limit (e.g., max 10 active)
            $activeCount = DataSkm::where('status', 'active')->count();
            if ($activeCount < 10) {
                return 'active';
            }
        }

        return 'inactive';
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $dataSkm = DataSkm::with('user')->findOrFail($id);
            $sentiment = $this->analyzeSentiment($dataSkm->kritik_saran);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $dataSkm->id,
                    'nama' => $dataSkm->nama,
                    'alamat' => $dataSkm->alamat,
                    'tingkat_kepuasan' => $dataSkm->tingkat_kepuasan,
                    'kritik_saran' => $dataSkm->kritik_saran,
                    'status' => $dataSkm->status,
                    'sentiment' => $sentiment,
                    'tanggal' => $dataSkm->created_at->format('d/m/Y H:i:s'),
                    'user' => $dataSkm->user ? $dataSkm->user->name : 'User tidak ditemukan'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Show success page after SKM submission
     */
    public function success()
    {
        return view('skm.success');
    }

    /**
     * Get positive testimonials for carousel with limit
     */
    public function testimonials()
    {
        $testimonials = DataSkm::with('user')
            ->activeTestimonials()
            ->limit(10)
            ->get();

        $statistics = [
            'total_surveys' => DataSkm::count(),
            'positive_surveys' => DataSkm::whereIn('tingkat_kepuasan', ['Sangat Puas', 'Puas'])->count(),
            'satisfaction_index' => $this->calculateSatisfactionIndex()
        ];

        $statistics['satisfaction_percentage'] = $statistics['total_surveys'] > 0
            ? round(($statistics['positive_surveys'] / $statistics['total_surveys']) * 100, 1)
            : 0;

        return view('layouts.testimonial', compact('testimonials', 'statistics'));
    }

    /**
     * API untuk get testimonials
     */
    public function getTestimonialsApi()
    {
        $testimonials = DataSkm::with('user')
            ->activeTestimonials()
            ->limit(10)
            ->get()
            ->map(function($item) {
                // Logic untuk avatar
                $userImage = null;
                if ($item->user && $item->user->image) {
                    $imagePaths = [
                        'storage/images/pegawai/' . $item->user->image,
                        'images/pegawai/' . $item->user->image,
                        'storage/' . $item->user->image,
                        $item->user->image
                    ];

                    foreach ($imagePaths as $path) {
                        if (file_exists(public_path($path))) {
                            $userImage = asset($path);
                            break;
                        }
                    }
                }

                return [
                    'id' => $item->id,
                    'nama' => $item->nama,
                    'kritik_saran' => $item->kritik_saran, // atau $item->short_testimonial jika ada accessor
                    'tingkat_kepuasan' => $item->tingkat_kepuasan,
                    'user_name' => $item->user ? $item->user->name : 'Warga',
                    'tanggal' => $item->created_at->format('d M Y'),
                    'user_image' => $userImage // akan null jika tidak ada foto
                ];
            });

        $sangatPuas = DataSkm::where('tingkat_kepuasan', 'Sangat Puas')->count();
        $puas = DataSkm::where('tingkat_kepuasan', 'Puas')->count();
        $kurangPuas = DataSkm::where('tingkat_kepuasan', 'Kurang Puas')->count();
        $tidakPuas = DataSkm::where('tingkat_kepuasan', 'Tidak Puas')->count();

        return response()->json([
            'success' => true,
            'data' => $testimonials,
            'statistics' => [
                'total_surveys' => DataSkm::count(),
                'satisfaction_percentage' => $this->getSatisfactionPercentage(),
                'satisfaction_index' => $this->calculateSatisfactionIndex($sangatPuas, $puas, $kurangPuas, $tidakPuas)
            ]
        ]);
    }

    /**
     * Get satisfaction percentage
     */
    private function getSatisfactionPercentage()
    {
        $total = DataSkm::count();
        $positive = DataSkm::whereIn('tingkat_kepuasan', ['Sangat Puas', 'Puas'])->count();

        return $total > 0 ? round(($positive / $total) * 100, 1) : 0;
    }

    /**
     * Toggle status of SKM data
     */
    public function toggleStatus(Request $request, DataSkm $dataSkm)
    {
        $newStatus = $dataSkm->status === 'active' ? 'inactive' : 'active';

        // Check testimonial limit when activating
        if ($newStatus === 'active') {
            $activeCount = DataSkm::where('status', 'active')->count();
            if ($activeCount >= 10) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maksimal 10 testimonial yang dapat ditampilkan. Nonaktifkan testimonial lain terlebih dahulu.'
                ]);
            }
        }

        $dataSkm->status = $newStatus;
        $dataSkm->save();

        $message = $newStatus === 'active'
            ? 'Survey berhasil ditampilkan di testimonial.'
            : 'Survey berhasil disembunyikan dari testimonial.';

        return response()->json([
            'success' => true,
            'message' => $message,
            'new_status' => $dataSkm->status
        ]);
    }

    /**
     * Bulk toggle testimonial status
     */
    public function bulkToggleTestimonial(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'action' => 'required|in:activate,deactivate'
        ]);

        try {
            $ids = $request->ids;
            $action = $request->action;

            if ($action === 'activate') {
                // Check limit
                $currentActive = DataSkm::where('status', 'active')->count();
                $toActivate = count($ids);

                if ($currentActive + $toActivate > 10) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak dapat mengaktifkan semua item. Maksimal 10 testimonial.'
                    ]);
                }

                DataSkm::whereIn('id', $ids)->update(['status' => 'active']);
                $message = 'Berhasil menampilkan ' . count($ids) . ' testimonial.';
            } else {
                DataSkm::whereIn('id', $ids)->update(['status' => 'inactive']);
                $message = 'Berhasil menyembunyikan ' . count($ids) . ' testimonial.';
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
