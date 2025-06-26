<?php

namespace App\Http\Controllers;

use App\Models\UserApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;

class UserApplicationController extends Controller
{
    /**
     * Display a listing of user applications (for personal use)
     */
    public function index()
    {
        return view('Profile.user-application.index', [
            'type_menu' => 'profile',
            'pageTitle' => 'Permohonan Saya'
        ]);
    }

    /**
     * Display all applications for admin/staff (for approval process)
     */
    public function indexAll()
    {
        // Determine page title based on role
        $pageTitle = 'Semua Permohonan';
        if (Auth::user()->role === 'Ketua RT') {
            $pageTitle = 'Permohonan Area RT ' . Auth::user()->rt;
        } elseif (Auth::user()->role === 'Ketua RW') {
            $pageTitle = 'Permohonan Area RW ' . Auth::user()->rw;
        }

        return view('Profile.all-user-application.index', [
            'type_menu' => 'profile',
            'pageTitle' => $pageTitle
        ]);
    }

    /**
     * Get data for DataTables (user's own applications only)
     */
    public function getData()
    {
        $query = UserApplication::with(['user', 'approverRT', 'approverRW', 'approverKelurahan'])
                 ->byUser(Auth::id())
                 ->orderBy('created_at', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('file_info', function ($row) {
                return '<div class="d-flex align-items-center">
                            <i class="fas fa-file-pdf text-danger mr-2"></i>
                            <div>
                                <div class="font-weight-bold">' . $row->nomor_surat . '</div>
                                <small class="text-muted">' . $row->jenis_permohonan . '</small>
                            </div>
                        </div>';
            })
            ->addColumn('nomor_judul', function ($row) {
                $html = '<div class="font-weight-bold">' . $row->nomor_surat . '</div>';
                $html .= '<div class="text-muted">' . \Str::limit($row->judul_permohonan, 40) . '</div>';
                if ($row->deskripsi_permohonan) {
                    $html .= '<small class="text-info">' . \Str::limit($row->deskripsi_permohonan, 50) . '</small>';
                }
                return $html;
            })
            ->addColumn('jenis', function ($row) {
                return $row->jenis_badge;
            })
            ->addColumn('tanggal', function ($row) {
                return $row->formatted_created_date;
            })
            ->addColumn('status', function ($row) {
                return $row->status_badge;
            })
            ->addColumn('workflow', function ($row) {
                $progress = $row->workflow_progress;
                $html = '<div class="workflow-steps">';

                // PSU Internal - No workflow needed
                if ($progress['auto_approved']) {
                    $html .= '<span class="badge badge-success">
                                <i class="fas fa-check-circle"></i> Auto Approved
                             </span>';
                    $html .= '</div>';
                    $html .= '<div class="mt-1"><small class="text-success">PSU Internal - Langsung Selesai</small></div>';
                    return $html;
                }

                // Step 1: Submitted
                $html .= '<span class="badge ' . ($progress['submitted'] ? 'badge-success' : 'badge-secondary') . ' mr-1">
                            <i class="fas fa-file-upload"></i> Diajukan
                         </span>';

                // Step 2: RT Approved (only if needed)
                if ($progress['needs_rt']) {
                    $html .= '<span class="badge ' . ($progress['rt_approved'] ? 'badge-success' : 'badge-secondary') . ' mr-1">
                                <i class="fas fa-check"></i> RT
                             </span>';
                }

                // Step 3: RW Approved (only if needed)
                if ($progress['needs_rw']) {
                    $html .= '<span class="badge ' . ($progress['rw_approved'] ? 'badge-success' : 'badge-secondary') . ' mr-1">
                                <i class="fas fa-check-double"></i> RW
                             </span>';
                }

                // Step 4: Kelurahan Approved (only if needed)
                if ($progress['needs_kelurahan']) {
                    $html .= '<span class="badge ' . ($progress['kelurahan_approved'] ? 'badge-success' : 'badge-secondary') . '">
                                <i class="fas fa-university"></i> Kelurahan
                             </span>';
                }

                $html .= '</div>';

                // Add final level indicator
                $finalLevelText = strtoupper($progress['final_level']);
                if ($progress['final_level'] === 'auto_approved') {
                    $finalLevelText = 'AUTO APPROVED';
                }
                $html .= '<div class="mt-1"><small class="text-muted">Target: ' . $finalLevelText . '</small></div>';

                return $html;
            })
            ->addColumn('actions', function ($row) {
                $buttons = [];

                // Detail button - always available
                $buttons[] = '<a href="' . route('user-applications.show', $row->id) . '"
                                class="btn btn-info btn-sm mb-1"
                                title="Lihat Detail">
                                <i class="fas fa-eye"></i> Detail
                            </a>';

                // Preview PDF button - if available
                if ($row->canPreviewPDF()) {
                    $buttons[] = '<a href="' . route('user-applications.preview-pdf', $row->id) . '"
                                    class="btn btn-secondary btn-sm mb-1"
                                    title="Preview PDF"
                                    target="_blank">
                                    <i class="fas fa-eye"></i> Preview
                                </a>';
                }

                // Download PDF button - if fully approved
                if ($row->canDownloadPDF()) {
                    $buttons[] = '<a href="' . route('user-applications.download-pdf', $row->id) . '"
                                    class="btn btn-success btn-sm mb-1"
                                    title="Download PDF">
                                    <i class="fas fa-download"></i> Download
                                </a>';
                }

                return '<div class="d-flex flex-column gap-1">' . implode('', $buttons) . '</div>';
            })
            ->rawColumns(['file_info', 'nomor_judul', 'jenis', 'status', 'workflow', 'actions'])
            ->make(true);
    }

    /**
     * Get data for DataTables (all applications for admin/staff based on role)
     */
    public function getDataAll()
    {
        $query = UserApplication::with(['user', 'approverRT', 'approverRW', 'approverKelurahan']);

        // Filter based on user role
        $userRole = Auth::user()->role;
        if ($userRole === 'Ketua RT') {
            // RT hanya lihat permohonan di area RT dan RW mereka
            $query->where('rt', Auth::user()->rt)->where('rw', Auth::user()->rw);
        } elseif ($userRole === 'Ketua RW') {
            // RW hanya lihat permohonan di area RW mereka
            $query->where('rw', Auth::user()->rw);
        }
        // Admin, Front Office, Back Office, Lurah, Operator bisa lihat semua

        $query->orderBy('created_at', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('file_info', function ($row) {
                return '<div class="d-flex align-items-center">
                            <i class="fas fa-file-pdf text-danger mr-2"></i>
                            <div>
                                <div class="font-weight-bold">' . $row->nomor_surat . '</div>
                                <small class="text-muted">' . $row->jenis_permohonan . '</small>
                            </div>
                        </div>';
            })
            ->addColumn('pemohon', function ($row) {
                return '<div>
                            <div class="font-weight-bold">' . $row->nama_pemohon . '</div>
                            <small class="text-muted">' . $row->user->name . '</small>
                        </div>';
            })
            ->addColumn('nomor_judul', function ($row) {
                $html = '<div class="font-weight-bold">' . $row->nomor_surat . '</div>';
                $html .= '<div class="text-muted">' . \Str::limit($row->judul_permohonan, 40) . '</div>';
                return $html;
            })
            ->addColumn('jenis', function ($row) {
                return $row->jenis_badge;
            })
            ->addColumn('tanggal', function ($row) {
                return $row->formatted_created_date;
            })
            ->addColumn('status', function ($row) {
                return $row->status_badge;
            })
            ->addColumn('rt_rw', function ($row) {
                return $row->rt_rw_display;
            })
            ->addColumn('actions', function ($row) {
                $buttons = [];

                // Detail button
                $buttons[] = '<a href="' . route('user-applications.show', $row->id) . '"
                                class="btn btn-info btn-sm mb-1"
                                title="Lihat Detail">
                                <i class="fas fa-eye"></i> Detail
                            </a>';

                return '<div class="d-flex flex-column gap-1">' . implode('', $buttons) . '</div>';
            })
            ->rawColumns(['file_info', 'pemohon', 'nomor_judul', 'jenis', 'status', 'actions'])
            ->make(true);
    }

    /**
     * Get summary statistics for user's own applications
     */
    public function getSummary()
    {
        try {
            $userId = Auth::id();

            // Get all applications for this user
            $applications = UserApplication::byUser($userId)->get();

            $summary = [
                'total_pengajuan' => $applications->count(),
                'pending_rt' => 0,
                'pending_rw' => 0,
                'pending_kelurahan' => 0,
                'approved_pengajuan' => 0,
                'rejected_pengajuan' => 0,
            ];

            // Process each application based on its workflow requirements
            foreach ($applications as $app) {
                $finalLevel = $app->getFinalApprovalLevel();

                // Count rejections
                if ($app->isRejected()) {
                    $summary['rejected_pengajuan']++;
                    continue;
                }

                // Count approvals (reached final level)
                if ($app->isApproved()) {
                    $summary['approved_pengajuan']++;
                    continue;
                }

                // Count pending based on current status and final level needed
                switch ($app->status) {
                    case 'auto_approved':
                        $summary['approved_pengajuan']++;
                        break;

                    case 'pending_rt':
                        $summary['pending_rt']++;
                        break;

                    case 'approved_rt':
                        if ($finalLevel === 'rt') {
                            $summary['approved_pengajuan']++;
                        } elseif ($finalLevel === 'rw' || $finalLevel === 'kelurahan') {
                            $summary['pending_rw']++;
                        }
                        break;

                    case 'pending_rw':
                        $summary['pending_rw']++;
                        break;

                    case 'approved_rw':
                        if ($finalLevel === 'rw') {
                            $summary['approved_pengajuan']++;
                        } elseif ($finalLevel === 'kelurahan') {
                            $summary['pending_kelurahan']++;
                        }
                        break;

                    case 'pending_kelurahan':
                        $summary['pending_kelurahan']++;
                        break;

                    case 'approved_kelurahan':
                    case 'completed':
                        $summary['approved_pengajuan']++;
                        break;
                }
            }

            // Breakdown by application type
            $byJenis = UserApplication::byUser($userId)
                      ->selectRaw('jenis_permohonan, COUNT(*) as total')
                      ->groupBy('jenis_permohonan')
                      ->pluck('total', 'jenis_permohonan')
                      ->toArray();

            $summary['by_jenis'] = $byJenis;

            return response()->json([
                'success' => true,
                'data' => $summary
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting summary: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get summary statistics for all applications based on role access
     */
    public function getSummaryAll()
    {
        try {
            $query = UserApplication::query();

            // Filter based on user role
            $userRole = Auth::user()->role;
            if ($userRole === 'Ketua RT') {
                $query->where('rt', Auth::user()->rt)->where('rw', Auth::user()->rw);
            } elseif ($userRole === 'Ketua RW') {
                $query->where('rw', Auth::user()->rw);
            }
            // Admin, Front Office, Back Office, Lurah, Operator get all applications

            $applications = $query->get();

            $summary = [
                'total_pengajuan' => $applications->count(),
                'pending_rt' => 0,
                'pending_rw' => 0,
                'pending_kelurahan' => 0,
                'approved_pengajuan' => 0,
                'rejected_pengajuan' => 0,
            ];

            // Process each application based on its workflow requirements
            foreach ($applications as $app) {
                $finalLevel = $app->getFinalApprovalLevel();

                // Count rejections
                if ($app->isRejected()) {
                    $summary['rejected_pengajuan']++;
                    continue;
                }

                // Count approvals (reached final level)
                if ($app->isApproved()) {
                    $summary['approved_pengajuan']++;
                    continue;
                }

                // Count pending based on current status and final level needed
                switch ($app->status) {
                    case 'auto_approved':
                        $summary['approved_pengajuan']++;
                        break;

                    case 'pending_rt':
                        $summary['pending_rt']++;
                        break;

                    case 'approved_rt':
                        if ($finalLevel === 'rt') {
                            $summary['approved_pengajuan']++;
                        } elseif ($finalLevel === 'rw' || $finalLevel === 'kelurahan') {
                            $summary['pending_rw']++;
                        }
                        break;

                    case 'pending_rw':
                        $summary['pending_rw']++;
                        break;

                    case 'approved_rw':
                        if ($finalLevel === 'rw') {
                            $summary['approved_pengajuan']++;
                        } elseif ($finalLevel === 'kelurahan') {
                            $summary['pending_kelurahan']++;
                        }
                        break;

                    case 'pending_kelurahan':
                        $summary['pending_kelurahan']++;
                        break;

                    case 'approved_kelurahan':
                    case 'completed':
                        $summary['approved_pengajuan']++;
                        break;
                }
            }

            // Breakdown by application type for the filtered data
            $query2 = UserApplication::query();
            if ($userRole === 'Ketua RT') {
                $query2->where('rt', Auth::user()->rt)->where('rw', Auth::user()->rw);
            } elseif ($userRole === 'Ketua RW') {
                $query2->where('rw', Auth::user()->rw);
            }

            $byJenis = $query2->selectRaw('jenis_permohonan, COUNT(*) as total')
                             ->groupBy('jenis_permohonan')
                             ->pluck('total', 'jenis_permohonan')
                             ->toArray();

            $summary['by_jenis'] = $byJenis;

            return response()->json([
                'success' => true,
                'data' => $summary
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting summary: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource
     */
    public function show(UserApplication $userApplication)
    {
        // Check authorization - hanya pemohon yang bisa lihat (kecuali admin/staff)
        if (Auth::user()->role === 'user' && $userApplication->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data ini.');
        }

        $userApplication->load(['user', 'approverRT', 'approverRW', 'approverKelurahan']);

        // Get the source reference if available
        $sourceData = $userApplication->reference();

        return view('Profile.user-application.show', [
            'type_menu' => 'profile',
            'pageTitle' => 'Detail Permohonan - ' . $userApplication->nomor_surat,
            'application' => $userApplication,
            'sourceData' => $sourceData
        ]);
    }

    /**
     * Show by nomor surat
     */
    public function showByNomor($nomorSurat)
    {
        $userApplication = UserApplication::where('nomor_surat', $nomorSurat)->firstOrFail();

        // Check authorization
        if (Auth::user()->role === 'user' && $userApplication->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data ini.');
        }

        return redirect()->route('user-applications.show', $userApplication->id);
    }

    /**
     * Show by reference (jenis and reference_id)
     */
    public function showByReference($jenis, $referenceId)
    {
        $userApplication = UserApplication::where('jenis_permohonan', strtoupper($jenis))
                                         ->where('reference_id', $referenceId)
                                         ->firstOrFail();

        // Check authorization
        if (Auth::user()->role === 'user' && $userApplication->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data ini.');
        }

        return redirect()->route('user-applications.show', $userApplication->id);
    }

    /**
     * Preview PDF
     */
    public function previewPDF(UserApplication $userApplication)
    {
        // Check authorization
        if (Auth::user()->role === 'user' && $userApplication->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data ini.');
        }

        // Redirect to the source application's preview
        $sourceData = $userApplication->reference();

        if ($sourceData && $userApplication->jenis_permohonan === 'PUNTADEWA') {
            return redirect()->route('puntadewa.preview-pdf', $sourceData->id);
        }

        if ($sourceData && $userApplication->jenis_permohonan === 'PSU') {
            return redirect()->route('psu.preview-pdf', $sourceData->id);
        }

        if ($sourceData && $userApplication->jenis_permohonan === 'SKAW') {
            return redirect()->route('skaw.preview-pdf', $sourceData->id);
        }

        if ($sourceData && $userApplication->jenis_permohonan === 'SURAT_PENGANTAR') {
            return redirect()->route('surat-pengantar.preview-pdf', $sourceData->id);
        }

        if ($sourceData && $userApplication->jenis_permohonan === 'VERIFIKASI_DOMISILI') {
            return redirect()->route('verifikasi-domisili.preview-pdf', $sourceData->id);
        }

        // If source not found, return error
        return redirect()->back()->with('error', 'File tidak ditemukan.');
    }

    /**
     * Download PDF
     */
    public function downloadPDF(UserApplication $userApplication)
    {
        // Check authorization
        if (Auth::user()->role === 'user' && $userApplication->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengunduh data ini.');
        }

        if (!$userApplication->canDownloadPDF()) {
            return redirect()->back()
                           ->with('error', 'PDF belum tersedia untuk diunduh.');
        }

        // Update download count
        $userApplication->increment('download_count');

        // Redirect to the source application's download
        $sourceData = $userApplication->reference();

        if ($sourceData && $userApplication->jenis_permohonan === 'PUNTADEWA') {
            return redirect()->route('puntadewa.download-pdf', $sourceData->id);
        }

        if ($sourceData && $userApplication->jenis_permohonan === 'PSU') {
            return redirect()->route('psu.download-pdf', $sourceData->id);
        }

        if ($sourceData && $userApplication->jenis_permohonan === 'SKAW') {
            return redirect()->route('skaw.download-pdf', $sourceData->id);
        }

        if ($sourceData && $userApplication->jenis_permohonan === 'SURAT_PENGANTAR') {
            return redirect()->route('surat-pengantar.download-pdf', $sourceData->id);
        }

        if ($sourceData && $userApplication->jenis_permohonan === 'VERIFIKASI_DOMISILI') {
            return redirect()->route('verifikasi-domisili.download-pdf', $sourceData->id);
        }

        // If source not found, return error
        return redirect()->back()->with('error', 'File tidak ditemukan.');
    }

    /**
     * Get recent activities for user
     */
    public function getRecentActivities()
    {
        try {
            $userId = Auth::id();

            // Get recent applications with their latest updates
            $recentApplications = UserApplication::with(['approverRT', 'approverRW', 'approverKelurahan'])
                                 ->byUser($userId)
                                 ->orderBy('updated_at', 'desc')
                                 ->limit(10)
                                 ->get();

            $activities = [];

            foreach ($recentApplications as $app) {
                // Activity for RT approval
                if ($app->approved_rt_at && $app->approverRT) {
                    $activities[] = [
                        'time' => $app->approved_rt_at,
                        'time_human' => $app->approved_rt_at->diffForHumans(),
                        'actor' => $app->approverRT,
                        'action' => $app->status === 'rejected_rt' ? 'menolak' : 'menyetujui',
                        'subject' => $app->jenis_permohonan,
                        'nomor_surat' => $app->nomor_surat,
                        'note' => $app->catatan_rt,
                        'level' => 'RT ' . $app->rt,
                        'final_status' => $app->getFinalApprovalLevel() === 'rt' ? 'SELESAI' : 'LANJUT KE RW'
                    ];
                }

                // Activity for RW approval
                if ($app->approved_rw_at && $app->approverRW) {
                    $activities[] = [
                        'time' => $app->approved_rw_at,
                        'time_human' => $app->approved_rw_at->diffForHumans(),
                        'actor' => $app->approverRW,
                        'action' => $app->status === 'rejected_rw' ? 'menolak' : 'menyetujui',
                        'subject' => $app->jenis_permohonan,
                        'nomor_surat' => $app->nomor_surat,
                        'note' => $app->catatan_rw,
                        'level' => 'RW ' . $app->rw,
                        'final_status' => $app->getFinalApprovalLevel() === 'rw' ? 'SELESAI' : 'LANJUT KE KELURAHAN'
                    ];
                }

                // Activity for Kelurahan approval
                if ($app->approved_kelurahan_at && $app->approverKelurahan) {
                    $activities[] = [
                        'time' => $app->approved_kelurahan_at,
                        'time_human' => $app->approved_kelurahan_at->diffForHumans(),
                        'actor' => $app->approverKelurahan,
                        'action' => $app->status === 'rejected_kelurahan' ? 'menolak' : 'menyetujui',
                        'subject' => $app->jenis_permohonan,
                        'nomor_surat' => $app->nomor_surat,
                        'note' => $app->catatan_kelurahan,
                        'level' => 'Kelurahan',
                        'final_status' => 'SELESAI'
                    ];
                }
            }

            // Sort by time descending
            usort($activities, function($a, $b) {
                return $b['time'] <=> $a['time'];
            });

            // Take only 5 most recent
            $activities = array_slice($activities, 0, 5);

            return response()->json([
                'success' => true,
                'data' => $activities
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting activities: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get workflow statistics for dashboard
     */
    public function getWorkflowStats()
    {
        try {
            $userId = Auth::id();
            $applications = UserApplication::byUser($userId)->get();

            $stats = [
                'by_final_level' => [
                    'rt_only' => 0,
                    'rw_final' => 0,
                    'kelurahan_final' => 0
                ],
                'by_status_and_level' => [
                    'completed_rt' => 0,
                    'completed_rw' => 0,
                    'completed_kelurahan' => 0,
                    'pending_at_rt' => 0,
                    'pending_at_rw' => 0,
                    'pending_at_kelurahan' => 0
                ]
            ];

            foreach ($applications as $app) {
                $finalLevel = $app->getFinalApprovalLevel();

                // Count by final level
                switch ($finalLevel) {
                    case 'rt':
                        $stats['by_final_level']['rt_only']++;
                        break;
                    case 'rw':
                        $stats['by_final_level']['rw_final']++;
                        break;
                    case 'kelurahan':
                        $stats['by_final_level']['kelurahan_final']++;
                        break;
                }

                // Count by status and level
                if ($app->isApproved()) {
                    $stats['by_status_and_level']['completed_' . $finalLevel]++;
                } elseif ($app->isPending()) {
                    if (in_array($app->status, ['pending_rt'])) {
                        $stats['by_status_and_level']['pending_at_rt']++;
                    } elseif (in_array($app->status, ['approved_rt', 'pending_rw'])) {
                        $stats['by_status_and_level']['pending_at_rw']++;
                    } elseif (in_array($app->status, ['approved_rw', 'pending_kelurahan'])) {
                        $stats['by_status_and_level']['pending_at_kelurahan']++;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting workflow stats: ' . $e->getMessage()
            ], 500);
        }
    }
}
