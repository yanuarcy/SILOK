<?php

namespace App\Http\Controllers;

use App\Models\UserApplication;
use App\Models\Psu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use DataTables;
use PDF;
use Carbon\Carbon;
use Exception;

class SuratMasukPsuController extends Controller
{
    /**
     * Display surat masuk PSU untuk user
     */
    public function index(Request $request)
    {
        return view('Psu.SuratMasuk.index', [
            'type_menu' => 'psu',
            'pageTitle' => 'Surat Masuk PSU',
        ]);
    }

    /**
     * Get data untuk DataTables
     */
    public function getData(Request $request)
    {
        try {
            $user = Auth::user();
            $userId = $user->id;
            $userRT = $user->rt;
            $userRW = $user->rw;
            $filterPeriod = $request->get('filter_period', 'all');

            // Query langsung dari tabel PSU berdasarkan target
            $query = Psu::with(['user']) // Load pembuat PSU
                ->where('status', 'completed') // Hanya yang sudah selesai
                ->whereIn('ditujukan_kepada', ['warga_rt', 'warga_rw']) // Hanya PSU Internal
                ->where(function($q) use ($userId, $userRT, $userRW, $user) {
                    // Target individual - user ID atau nama sesuai
                    $q->where(function($subQ) use ($userId, $user) {
                        $subQ->where('target_type', 'individual')
                             ->where(function($targetQ) use ($userId, $user) {
                                 $targetQ->where('target_warga_id', $userId) // Target by user ID
                                         ->orWhere('target_warga_id', $user->custom_id ?? '') // Target by custom ID
                                         ->orWhere('target_warga_name', $user->name); // Target by name
                             });
                    })
                    // Target semua RT - user ada di RT yang sama
                    ->orWhere(function($subQ) use ($userRT, $userRW) {
                        $subQ->where('target_type', 'semua_rt')
                             ->where('target_rt', $userRT)
                             ->where('target_rw', $userRW);
                    })
                    // Target semua RW - user ada di RW yang sama
                    ->orWhere(function($subQ) use ($userRW) {
                        $subQ->where('target_type', 'semua_rw')
                             ->where('target_rw', $userRW);
                    });
                })
                ->orderBy('created_at', 'desc');

            // Apply period filter
            switch ($filterPeriod) {
                case 'today':
                    $query->whereDate('created_at', now()->toDateString());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [
                        now()->startOfWeek(),
                        now()->endOfWeek()
                    ]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'all':
                default:
                    // No additional filter
                    break;
            }

            // Debug logging
            Log::info('SuratMasukPsu query for user', [
                'user_id' => $userId,
                'user_rt' => $userRT,
                'user_rw' => $userRW,
                'query_count' => $query->count()
            ]);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('nomor_judul', function ($row) {
                    return $this->buildNomorJudulColumn($row);
                })
                ->addColumn('pengirim', function ($row) {
                    return $this->buildPengirimColumn($row);
                })
                ->addColumn('tanggal', function ($row) {
                    try {
                        return $row->created_at ? $row->created_at->format('d/m/Y H:i') : '-';
                    } catch (\Exception $e) {
                        return '-';
                    }
                })
                ->addColumn('status', function ($row) {
                    return '<span class="badge badge-success">Diterima</span>';
                })
                ->addColumn('actions', function ($row) {
                    return $this->buildActionsColumn($row);
                })
                ->rawColumns(['nomor_judul', 'pengirim', 'status', 'actions'])
                ->make(true);

        } catch (Exception $e) {
            Log::error('Error in Surat Masuk PSU getData: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error loading data: ' . $e->getMessage(),
                'data' => [],
                'draw' => intval($request->get('draw')),
                'recordsTotal' => 0,
                'recordsFiltered' => 0
            ], 500);
        }
    }

    /**
     * Build nomor judul column
     */
    private function buildNomorJudulColumn($row)
    {
        $html = '<div class="font-weight-bold text-primary">' . ($row->nomor_surat ?? 'No Number') . '</div>';
        $html .= '<div class="text-muted">' . \Str::limit($row->hal ?? 'No Subject', 50) . '</div>';
        $html .= '<small class="badge badge-info">PSU Internal</small>';

        // Tambahan info target
        if ($row->target_type === 'individual') {
            $html .= '<br><small class="text-muted"><i class="fas fa-user"></i> Target: ' . ($row->target_warga_name ?? 'Individual') . '</small>';
        } elseif ($row->target_type === 'semua_rt') {
            $html .= '<br><small class="text-muted"><i class="fas fa-users"></i> Untuk RT ' . sprintf('%02d', $row->target_rt) . '</small>';
        } elseif ($row->target_type === 'semua_rw') {
            $html .= '<br><small class="text-muted"><i class="fas fa-users"></i> Untuk RW ' . sprintf('%02d', $row->target_rw) . '</small>';
        }

        return $html;
    }

    /**
     * Build pengirim column
     */
    private function buildPengirimColumn($row)
    {
        $creator = $row->user; // Pembuat PSU
        $creatorRole = $creator->role ?? 'Unknown';
        $creatorName = $creator->name ?? ($row->nama_lengkap ?? 'Unknown');

        $roleDisplay = [
            'Ketua RT' => 'Ketua RT',
            'Ketua RW' => 'Ketua RW',
            'user' => 'Warga'
        ];

        $roleText = $roleDisplay[$creatorRole] ?? $creatorRole;
        $iconClass = $creatorRole === 'Ketua RT' ? 'fa-user-tie text-primary' :
                    ($creatorRole === 'Ketua RW' ? 'fa-user-crown text-warning' : 'fa-user text-info');

        $html = '<div class="font-weight-bold">';
        $html .= '<i class="fas ' . $iconClass . ' mr-1"></i>';
        $html .= $creatorName . '</div>';
        $html .= '<small class="text-muted">' . $roleText . '</small>';

        // Info RT/RW pembuat
        if ($creator && $creator->rt && $creator->rw) {
            $html .= '<br><small class="badge badge-secondary">RT ' . sprintf('%02d', $creator->rt) . ' / RW ' . sprintf('%02d', $creator->rw) . '</small>';
        }

        return $html;
    }

    /**
     * Build actions column
     */
    private function buildActionsColumn($row)
    {
        $buttons = [];

        // Preview button
        $buttons[] = '<a href="' . route('surat-masuk.psu.preview', $row->id) . '"
                        class="btn btn-info btn-sm mb-1"
                        title="Preview Surat"
                        target="_blank">
                        <i class="fas fa-eye"></i> Preview
                    </a>';

        // Download button
        if ($row->file_pdf && Storage::disk('public')->exists($row->file_pdf)) {
            $buttons[] = '<a href="' . route('surat-masuk.psu.download', $row->id) . '"
                            class="btn btn-success btn-sm mb-1"
                            title="Download PDF">
                            <i class="fas fa-download"></i> Download
                        </a>';
        }

        // Mark as read button (optional future feature)
        // $metadata = $row->metadata ?? [];
        // if (!isset($metadata['read_at'])) {
        //     $buttons[] = '<button type="button"
        //                     class="btn btn-outline-primary btn-sm mb-1 btn-mark-read"
        //                     data-id="' . $row->id . '"
        //                     title="Tandai Sudah Dibaca">
        //                     <i class="fas fa-check"></i> Tandai Dibaca
        //                 </button>';
        // }

        return '<div class="d-flex flex-column gap-1">' . implode('', $buttons) . '</div>';
    }

    /**
     * Preview PSU Internal
     */
    public function preview($psuId)
    {
        try {
            $psu = Psu::findOrFail($psuId);

            // Authorization check - pastikan user adalah target dari PSU ini
            if (!$this->isUserTargetOfPsu($psu, Auth::user())) {
                abort(403, 'Anda tidak memiliki akses untuk melihat surat ini.');
            }

            $pdf = PDF::loadView('Psu.PDF', compact('psu'));
            $cleanNomorSurat = str_replace(['/', '\\'], '_', $psu->nomor_surat);
            $fileName = 'surat_masuk_psu_' . $cleanNomorSurat . '.pdf';

            return $pdf->stream($fileName);
        } catch (Exception $e) {
            Log::error('Error previewing Surat Masuk PSU: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat preview PDF: ' . $e->getMessage());
        }
    }

    /**
     * Download PSU Internal
     */
    public function download($psuId)
    {
        try {
            $psu = Psu::findOrFail($psuId);

            // Authorization check
            if (!$this->isUserTargetOfPsu($psu, Auth::user())) {
                abort(403, 'Anda tidak memiliki akses untuk mengunduh surat ini.');
            }

            if (!$psu->file_pdf || !Storage::disk('public')->exists($psu->file_pdf)) {
                return redirect()->back()->with('error', 'File PDF tidak ditemukan.');
            }

            $cleanNomorSurat = str_replace(['/', '\\'], '_', $psu->nomor_surat);
            $downloadName = 'Surat_Masuk_PSU_' . $cleanNomorSurat . '.pdf';

            // Track download (increment download_count di PSU)
            $psu->increment('download_count');

            return Storage::disk('public')->download($psu->file_pdf, $downloadName);
        } catch (Exception $e) {
            Log::error('Error downloading Surat Masuk PSU: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengunduh PDF: ' . $e->getMessage());
        }
    }

    private function isUserTargetOfPsu(Psu $psu, User $user)
    {
        // PSU harus internal dan completed
        if (!in_array($psu->ditujukan_kepada, ['warga_rt', 'warga_rw']) || $psu->status !== 'completed') {
            return false;
        }

        // Cek berdasarkan target_type
        switch ($psu->target_type) {
            case 'individual':
                // Target individual - cek ID, custom_id, atau nama
                return $psu->target_warga_id == $user->id ||
                       $psu->target_warga_id == ($user->custom_id ?? '') ||
                       $psu->target_warga_name == $user->name;

            case 'semua_rt':
                // Target semua RT - cek RT dan RW yang sama
                return $psu->target_rt == $user->rt && $psu->target_rw == $user->rw;

            case 'semua_rw':
                // Target semua RW - cek RW yang sama
                return $psu->target_rw == $user->rw;

            default:
                return false;
        }
    }

    /**
     * Mark surat as read
     */
    public function markAsRead(UserApplication $userApplication)
    {
        try {
            // Authorization check
            if ($userApplication->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk surat ini.'
                ], 403);
            }

            $metadata = $userApplication->metadata ?? [];
            $metadata['read_at'] = now()->toISOString();
            $metadata['read_by'] = Auth::user()->name;

            $userApplication->update(['metadata' => $metadata]);

            return response()->json([
                'success' => true,
                'message' => 'Surat telah ditandai sebagai sudah dibaca.'
            ]);

        } catch (Exception $e) {
            Log::error('Error marking surat as read: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get summary statistics
     */
    public function getSummary(Request $request)
    {
        try {
            $user = Auth::user();
            $userId = $user->id;
            $userRT = $user->rt;
            $userRW = $user->rw;

            $baseQuery = Psu::where('status', 'completed')
                ->whereIn('ditujukan_kepada', ['warga_rt', 'warga_rw'])
                ->where(function($q) use ($userId, $userRT, $userRW, $user) {
                    $q->where(function($subQ) use ($userId, $user) {
                        $subQ->where('target_type', 'individual')
                             ->where(function($targetQ) use ($userId, $user) {
                                 $targetQ->where('target_warga_id', $userId)
                                         ->orWhere('target_warga_id', $user->custom_id ?? '')
                                         ->orWhere('target_warga_name', $user->name);
                             });
                    })
                    ->orWhere(function($subQ) use ($userRT, $userRW) {
                        $subQ->where('target_type', 'semua_rt')
                             ->where('target_rt', $userRT)
                             ->where('target_rw', $userRW);
                    })
                    ->orWhere(function($subQ) use ($userRW) {
                        $subQ->where('target_type', 'semua_rw')
                             ->where('target_rw', $userRW);
                    });
                });

            $summary = [
                'total_surat_masuk' => (clone $baseQuery)->count(),
                'bulan_ini' => (clone $baseQuery)->whereMonth('created_at', now()->month)
                                                 ->whereYear('created_at', now()->year)
                                                 ->count(),
                'minggu_ini' => (clone $baseQuery)->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count(),
                'hari_ini' => (clone $baseQuery)->whereDate('created_at', now()->toDateString())
                                               ->count(),
                'belum_dibaca' => 0, // Simplified - tidak track read status untuk sekarang
            ];

            return response()->json([
                'success' => true,
                'data' => $summary
            ]);

        } catch (Exception $e) {
            Log::error('Error getting Surat Masuk PSU summary: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting summary: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get detail surat masuk for modal/popup
     */
    public function getDetail(UserApplication $userApplication)
    {
        try {
            // Authorization check
            if ($userApplication->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk melihat surat ini.'
                ], 403);
            }

            // Check if this is PSU masuk
            if (!$userApplication->isPsuMasuk()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Surat ini bukan PSU Internal.'
                ], 404);
            }

            // Load PSU data
            $psu = Psu::find($userApplication->reference_id);
            if (!$psu) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data PSU tidak ditemukan.'
                ], 404);
            }

            $metadata = $userApplication->metadata ?? [];

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $userApplication->id,
                    'nomor_surat' => $userApplication->nomor_surat,
                    'judul' => $userApplication->deskripsi_permohonan,
                    'isi_surat' => $psu->isi_surat,
                    'hal' => $psu->hal,
                    'sifat' => $psu->sifat,
                    'pengirim' => [
                        'nama' => $metadata['psu_creator_name'] ?? $userApplication->nama_pemohon,
                        'role' => $metadata['psu_creator_role'] ?? 'Unknown',
                        'rt' => $metadata['creator_rt'] ?? null,
                        'rw' => $metadata['creator_rw'] ?? null,
                    ],
                    'target_info' => [
                        'type' => $metadata['target_type'] ?? 'individual',
                        'rt' => $metadata['target_rt'] ?? null,
                        'rw' => $metadata['target_rw'] ?? null,
                    ],
                    'tanggal_diterima' => $userApplication->created_at->format('d F Y H:i'),
                    'read_at' => isset($metadata['read_at']) ? Carbon::parse($metadata['read_at'])->format('d F Y H:i') : null,
                    'download_count' => $userApplication->download_count ?? 0,
                    'file_pdf' => $userApplication->file_pdf ? Storage::url($userApplication->file_pdf) : null,
                ]
            ]);

        } catch (Exception $e) {
            Log::error('Error getting Surat Masuk PSU detail: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics for dashboard widget
     */
    public function getStats(Request $request)
    {
        try {
            $userId = Auth::id();

            $baseQuery = UserApplication::where('user_id', $userId)
                                      ->where('jenis_permohonan', 'PSU')
                                      ->whereJsonContains('metadata->is_surat_masuk', true);

            // Get recent surat masuk (last 7 days)
            $recentSuratMasuk = (clone $baseQuery)
                ->where('created_at', '>=', now()->subDays(7))
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(['nomor_surat', 'deskripsi_permohonan', 'created_at', 'metadata']);

            // Get unread count
            $unreadCount = (clone $baseQuery)
                ->whereJsonDoesntContain('metadata->read_at', null)
                ->count();

            // Get monthly trend
            $monthlyTrend = [];
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $count = (clone $baseQuery)
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count();

                $monthlyTrend[] = [
                    'month' => $month->format('M Y'),
                    'count' => $count
                ];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'total_surat_masuk' => (clone $baseQuery)->count(),
                    'unread_count' => $unreadCount,
                    'recent_surat_masuk' => $recentSuratMasuk,
                    'monthly_trend' => $monthlyTrend
                ]
            ]);

        } catch (Exception $e) {
            Log::error('Error getting Surat Masuk PSU stats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting stats: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get unread count for notifications
     */
    public function getUnreadCount(Request $request)
    {
        try {
            // Untuk sekarang return 0, bisa dikembangkan nanti jika perlu track read status
            return response()->json([
                'success' => true,
                'data' => [
                    'unread_count' => 0
                ]
            ]);

        } catch (Exception $e) {
            Log::error('Error getting unread count: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting unread count: ' . $e->getMessage()
            ], 500);
        }
    }
}
