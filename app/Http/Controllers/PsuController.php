<?php

namespace App\Http\Controllers;

use App\Models\Psu;
use App\Models\User;
use App\Models\UserApplication;
use App\Models\Spesimen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use DataTables;
use PDF;
use Carbon\Carbon;
use Exception;

class PsuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userRole = Auth::user()->role;

        // Yang bisa mengajukan: user, Ketua RT, Ketua RW
        if (in_array($userRole, ['user', 'Ketua RT', 'Ketua RW'])) {
            return redirect()->route('psu.permohonan-saya');
        }

        // Yang approve/proses: Ketua RT, Ketua RW, Front Office, Lurah, Back Office, admin
        if (in_array($userRole, ['Ketua RT', 'Ketua RW', 'Front Office', 'Back Office', 'Lurah', 'Camat', 'admin'])) {
            return redirect()->route('psu.semua-permohonan');
        }

        // Default fallback ke permohonan saya
        return redirect()->route('psu.permohonan-saya');
    }

    /**
     * View 1: Permohonan Saya - Hanya pengajuan sesuai user_id
     */
    public function PermohonanSaya(Request $request)
    {
        return view('Psu.index-permohonan-saya', [
            'type_menu' => 'psu',
            'pageTitle' => 'Permohonan PSU Saya',
            'userRole' => Auth::user()->role,
            'showApprovalActions' => false
        ]);
    }

    /**
     * View 2: Semua Permohonan - Yang butuh approval/proses sesuai role
     */
    public function SemuaPermohonan(Request $request)
    {
        $userRole = Auth::user()->role;

        return view('Psu.index-semua-permohonan', [
            'type_menu' => 'psu',
            'pageTitle' => 'Semua Permohonan PSU - ' . $userRole,
            'userRole' => $userRole,
            'showApprovalActions' => true,
            'user' => Auth::user()
        ]);
    }

    /**
     * Get Ketua RT name based on RT and RW
     */
    public function getKetuaRT(Request $request)
    {
        try {
            $rt = $request->input('rt');
            $rw = $request->input('rw');

            $ketuaRT = User::where('role', 'Ketua RT')
                          ->where('rt', $rt)
                          ->where('rw', $rw)
                          ->where('is_active', true)
                          ->first();

            if ($ketuaRT) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'id' => $ketuaRT->id,
                        'name' => $ketuaRT->name,
                        'rt' => $ketuaRT->rt,
                        'rw' => $ketuaRT->rw
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Ketua RT tidak ditemukan'
                ], 404);
            }

        } catch (Exception $e) {
            Log::error('Error getting Ketua RT: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting Ketua RT: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Ketua RW name based on RW
     */
    public function getKetuaRW(Request $request)
    {
        try {
            $rw = $request->input('rw');

            $ketuaRW = User::where('role', 'Ketua RW')
                          ->where('rw', $rw)
                          ->where('is_active', true)
                          ->first();

            if ($ketuaRW) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'id' => $ketuaRW->id,
                        'name' => $ketuaRW->name,
                        'rw' => $ketuaRW->rw
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Ketua RW tidak ditemukan'
                ], 404);
            }

        } catch (Exception $e) {
            Log::error('Error getting Ketua RW: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting Ketua RW: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Build tanggal column with error handling
     */
    private function buildTanggalColumn($row)
    {
        try {
            if (!$row->created_at) {
                return '-';
            }

            // Handle both string and Carbon instances
            if (is_string($row->created_at)) {
                $carbon = \Carbon\Carbon::parse($row->created_at);
            } else {
                $carbon = $row->created_at;
            }

            return $carbon->format('d/m/Y H:i');
        } catch (\Exception $e) {
            Log::error('Error formatting date for PSU ID ' . ($row->id ?? 'unknown') . ': ' . $e->getMessage());
            return '-';
        }
    }

    public function getPsuData(Request $request, Psu $psu)
    {
        try {
            // Authorization check
            $this->authorizeAccess($psu);

            // Load relationships if needed
            $psu->load(['user', 'approverRT', 'approverRW', 'approverKelurahan']);

            // Return JSON response for AJAX
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'id' => $psu->id,
                        'nomor_surat' => $psu->nomor_surat,
                        'nama_lengkap' => $psu->nama_lengkap,
                        'nik' => $psu->nik,
                        'alamat' => $psu->alamat,
                        'rt' => $psu->rt,
                        'rw' => $psu->rw,
                        'hal' => $psu->hal,
                        'isi_surat' => $psu->isi_surat,
                        'sifat' => $psu->sifat,
                        'status' => $psu->status,
                        'ditujukan_kepada' => $psu->ditujukan_kepada,
                        'created_at' => $psu->created_at,
                        'bulan' => $psu->bulan,
                        'pekerjaan' => $psu->pekerjaan,
                        'jenis_kelamin' => $psu->jenis_kelamin,
                        'tempat_lahir' => $psu->tempat_lahir,
                        'tanggal_lahir' => $psu->tanggal_lahir,
                        'agama' => $psu->agama,
                        'status_perkawinan' => $psu->status_perkawinan,
                        'kewarganegaraan' => $psu->kewarganegaraan,
                        'nomor_kk' => $psu->nomor_kk,
                        'tujuan_internal' => $psu->tujuan_internal,
                        'tujuan_eksternal' => $psu->tujuan_eksternal,
                        'metadata' => $psu->metadata,
                        // User info
                        'user' => $psu->user ? [
                            'name' => $psu->user->name,
                            'email' => $psu->user->email,
                        ] : null,
                    ]
                ]);
            }

            // For regular web requests, return the show view
            return $this->show($psu);

        } catch (\Exception $e) {
            Log::error('Error getting PSU data: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('psu.index')
                        ->with('error', 'Terjadi kesalahan saat memuat data: ' . $e->getMessage());
        }
    }

    /**
     * Get data for DataTables
     */
    public function getData(Request $request)
    {
        try {
            $userRole = Auth::user()->role;
            $viewType = $request->get('view_type', 'permohonan_saya');

            $query = Psu::with(['user', 'approverRT', 'approverRW', 'approverKelurahan'])
                        ->orderBy('created_at', 'desc');

            // Apply filter berdasarkan view type
            $this->applyViewFilter($query, $userRole, $viewType);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('file_info', function ($row) {
                    return $this->buildFileInfoColumn($row);
                })
                ->addColumn('nomor_judul', function ($row) {
                    return $this->buildNomorJudulColumn($row);
                })
                ->addColumn('nama_lengkap', function ($row) {
                    return $this->buildNamaLengkapColumn($row);
                })
                ->addColumn('tanggal', function ($row) {
                    return $this->buildTanggalColumn($row);
                })
                ->addColumn('status', function ($row) {
                    try {
                        return $row->status_badge;
                    } catch (\Exception $e) {
                        Log::error('Error getting status badge for PSU ID ' . $row->id . ': ' . $e->getMessage());
                        return '<span class="badge badge-secondary">Unknown</span>';
                    }
                })
                ->addColumn('workflow', function ($row) {
                    return $this->buildWorkflowColumn($row);
                })
                ->addColumn('actions', function ($row) use ($userRole, $viewType) {
                    return $this->buildActionsColumn($row, $userRole, $viewType);
                })
                ->rawColumns(['file_info', 'nomor_judul', 'nama_lengkap', 'status', 'workflow', 'actions'])
                ->make(true);

        } catch (Exception $e) {
            Log::error('Error in PSU getData: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

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
     * Apply filter berdasarkan view type
     */
    private function applyViewFilter($query, $userRole, $viewType)
    {
        if ($viewType === 'permohonan_saya') {
            // View 1: Permohonan Saya - hanya user_id sendiri + PSU Internal yang ditargetkan
            $query->where(function($q) {
                $q->where('user_id', Auth::id()) // Pengajuan sendiri
                ;
            });

        } else {
            // View 2: Semua Permohonan - yang butuh approval/proses sesuai role
            switch ($userRole) {
                case 'Ketua RT':
                    $userRT = Auth::user()->rt;
                    $userRW = Auth::user()->rw;

                    // PSU yang butuh approval RT dari area yang sama + yang sudah diselesaikan oleh RT
                    $query->where('rt', $userRT)
                        ->where('rw', $userRW)
                        ->where(function($subQuery) {
                            $subQuery->whereIn('status', ['pending_rt', 'approved_rt'])
                                    ->orWhere(function($completedQuery) {
                                        // Termasuk yang completed dengan level_akhir RT
                                        $completedQuery->where('status', 'completed')
                                                    ->where('level_akhir', 'rt')
                                                    ->orWhere('level_akhir', 'rw')
                                                    ->orWhere('level_akhir', 'kelurahan');
                                    });
                        });
                    break;

                case 'Ketua RW':
                    $userRW = Auth::user()->rw;

                    // PSU yang butuh approval RW dari RW yang sama + yang sudah diselesaikan oleh RW
                    // TERMASUK yang dibuat oleh Ketua RT dengan tujuan Kelurahan (status approved_rt)
                    $query->where('rw', $userRW)
                        ->where(function($subQuery) {
                            $subQuery->whereIn('status', ['approved_rt', 'pending_rw', 'approved_rw'])
                                    ->orWhere(function($completedQuery) {
                                        // Termasuk yang completed dengan level_akhir RW atau Kelurahan
                                        $completedQuery->where('status', 'completed')
                                                    ->where('level_akhir', 'rw')
                                                    ->orWhere('level_akhir', 'kelurahan');
                                    });
                        });
                    break;

                case 'Front Office':
                    // PERBAIKAN: PSU yang sampai ke Kelurahan + yang sudah diselesaikan
                    // Termasuk yang dibuat langsung oleh Ketua RW dengan status approved_rw
                    $query->where(function($subQuery) {
                        $subQuery->whereIn('status', [
                            'approved_rw', 'pending_kelurahan', 'approved_kelurahan',
                            'processing_lurah', 'processed_lurah', 'processing_back_office'
                        ])->orWhere(function($completedQuery) {
                            // Termasuk yang completed dengan level_akhir Kelurahan
                            $completedQuery->where('status', 'completed')
                                        ->where('level_akhir', 'kelurahan');
                        });
                    });
                    break;

                case 'Lurah':
                    // Lurah hanya melihat PSU yang butuh workflow kelurahan
                    $query->where('level_akhir', 'kelurahan') // Hanya yang final di kelurahan
                        ->whereIn('status', [
                            'pending_kelurahan',     // Sudah diterima Front Office, menunggu disposisi Lurah
                            'processing_lurah',      // Sedang diproses Lurah
                            'processed_lurah',       // Sudah selesai disposisi Lurah
                            'processing_back_office', // Sedang diproses Back Office
                            'completed'              // Sudah selesai
                        ]);
                    break;

                case 'Back Office':
                    // PSU yang sudah diproses Lurah dan final di kelurahan
                    $query->where('level_akhir', 'kelurahan')
                        ->whereIn('status', [
                            'processed_lurah', 'processing_back_office', 'completed'
                        ]);
                    break;

                case 'Camat':
                case 'admin':
                    // Melihat semua (tidak ada filter tambahan)
                    break;

                default:
                    // Role lain - tidak ada data
                    $query->where('id', 0);
                    break;
            }
        }
    }

    /**
     * Get summary statistics
     */
    public function getSummary(Request $request)
    {
        try {
            $userRole = Auth::user()->role;
            $viewType = $request->get('view_type', 'permohonan_saya');

            $baseQuery = Psu::query();
            $this->applyViewFilter($baseQuery, $userRole, $viewType);

            if ($viewType === 'permohonan_saya') {
                // Summary untuk permohonan saya
                $summary = [
                    'total_pengajuan' => (clone $baseQuery)->count(),
                    'auto_approved' => (clone $baseQuery)->where('status', 'auto_approved')->count(),
                    'sedang_proses' => (clone $baseQuery)->whereIn('status', [
                        'pending_rt', 'approved_rt', 'pending_rw', 'approved_rw', 'pending_kelurahan',
                        'approved_kelurahan', 'processing_lurah', 'processed_lurah', 'processing_back_office'
                    ])->count(),
                    'selesai' => $this->getSelesaiCount($baseQuery),
                    'ditolak' => (clone $baseQuery)->whereIn('status', [
                        'rejected_rt', 'rejected_rw', 'rejected_kelurahan'
                    ])->count(),
                ];
            } else {
                // PERBAIKAN: Summary untuk semua permohonan (sesuai role)
                $summary = [
                    'total_permohonan' => (clone $baseQuery)->count(),
                    'butuh_action' => $this->getButuhActionCount($baseQuery, $userRole),
                    'sedang_proses' => $this->getSedangProsesCount($baseQuery, $userRole),
                    'selesai_diproses' => $this->getSelesaiDiprosesCount($baseQuery, $userRole),
                    'ditolak' => (clone $baseQuery)->whereIn('status', [
                        'rejected_rt', 'rejected_rw', 'rejected_kelurahan'
                    ])->count(),
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $summary
            ]);

        } catch (Exception $e) {
            Log::error('Error getting PSU summary: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting summary: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper methods untuk summary counts
     */
    private function getSelesaiCount($baseQuery)
    {
        return (clone $baseQuery)->where(function ($query) {
            $query->where('status', 'auto_approved')
                ->orWhere('status', 'completed')
                ->orWhere(function ($subQuery) {
                    $subQuery->where('status', 'approved_rt')
                            ->where('level_akhir', 'rt');
                })
                ->orWhere(function ($subQuery) {
                    $subQuery->where('status', 'approved_rw')
                            ->where('level_akhir', 'rw');
                })
                ->orWhere(function ($subQuery) {
                    $subQuery->where('status', 'approved_kelurahan')
                            ->where('level_akhir', 'kelurahan');
                });
        })->count();
    }

    private function getButuhActionCount($baseQuery, $userRole)
    {
        switch ($userRole) {
            case 'Ketua RT':
                return (clone $baseQuery)->where('status', 'pending_rt')->count();

            case 'Ketua RW':
                // PERBAIKAN: Termasuk yang approved_rt dengan level_akhir bukan 'rt'
                return (clone $baseQuery)->where(function($query) {
                    $query->where('status', 'approved_rt')
                        ->where('level_akhir', '!=', 'rt') // Yang butuh approval RW
                        ->orWhere('status', 'pending_rw');
                })->count();

            case 'Front Office':
                return (clone $baseQuery)->where('status', 'approved_rw')->count();

            case 'Lurah':
                return (clone $baseQuery)->where('status', 'pending_kelurahan')->count();

            case 'Back Office':
                return (clone $baseQuery)->where('status', 'processed_lurah')->count();

            default:
                return 0;
        }
    }

    private function getSedangProsesCount($baseQuery, $userRole)
    {
        switch ($userRole) {
            case 'Ketua RT':
            case 'Ketua RW':
                return (clone $baseQuery)->whereIn('status', [
                    'approved_rt', 'approved_rw', 'pending_kelurahan', 'approved_kelurahan',
                    'processing_lurah', 'processed_lurah', 'processing_back_office'
                ])->where(function ($query) {
                    // Hanya yang belum selesai berdasarkan level_akhir
                    $query->where(function ($subQuery) {
                        $subQuery->where('status', 'approved_rt')
                                ->where('level_akhir', '!=', 'rt');
                    })
                    ->orWhere(function ($subQuery) {
                        $subQuery->where('status', 'approved_rw')
                                ->where('level_akhir', '!=', 'rw');
                    })
                    ->orWhereIn('status', [
                        'pending_kelurahan', 'approved_kelurahan',
                        'processing_lurah', 'processed_lurah', 'processing_back_office'
                    ]);
                })->count();

            case 'Front Office':
                // Yang sedang diproses di Kelurahan level
                return (clone $baseQuery)->whereIn('status', [
                    'pending_kelurahan', 'processing_lurah', 'processed_lurah'
                ])->count();

            case 'Lurah':
                // PERBAIKAN: Yang sedang diproses Lurah atau menunggu Back Office
                return (clone $baseQuery)->whereIn('status', [
                    'processing_lurah', 'processed_lurah', 'processing_back_office'
                ])->count();

            case 'Back Office':
                // Yang sedang diproses Back Office
                return (clone $baseQuery)->where('status', 'processing_back_office')->count();

            default:
                return 0;
        }
    }

    private function getSedangProsesCountFixed($baseQuery, $userRole)
    {
        switch ($userRole) {
            case 'Ketua RT':
                // Untuk RT: yang approved_rt dan belum selesai di level RT (lanjut ke RW/Kelurahan)
                return (clone $baseQuery)->where(function ($query) {
                    $query->where('status', 'approved_rt')
                        ->where('level_akhir', '!=', 'rt'); // Yang lanjut ke RW/Kelurahan
                })->count();

            case 'Ketua RW':
                // Untuk RW: yang approved_rw dan belum selesai di level RW (lanjut ke Kelurahan)
                return (clone $baseQuery)->where(function ($query) {
                    $query->where('status', 'approved_rw')
                        ->where('level_akhir', '!=', 'rw'); // Yang lanjut ke Kelurahan
                })->count();

            case 'Front Office':
                // Yang sedang diproses di Kelurahan level
                return (clone $baseQuery)->whereIn('status', [
                    'pending_kelurahan', 'processing_lurah', 'processed_lurah'
                ])->count();

            case 'Lurah':
                // Yang sedang diproses Lurah
                return (clone $baseQuery)->whereIn('status', [
                    'processing_lurah', 'processed_lurah'
                ])->count();

            case 'Back Office':
                // Yang sedang diproses Back Office
                return (clone $baseQuery)->where('status', 'processing_back_office')->count();

            default:
                return 0;
        }
    }

    private function getSelesaiDiprosesCount($baseQuery, $userRole)
    {
        return (clone $baseQuery)->where(function ($query) {
            $query->where('status', 'completed')
                ->orWhere('status', 'auto_approved')
                ->orWhere(function ($subQuery) {
                    // PSU yang selesai di level RT (tujuan akhir RT)
                    $subQuery->where('status', 'approved_rt')
                            ->where('level_akhir', 'rt');
                })
                ->orWhere(function ($subQuery) {
                    // PSU yang selesai di level RW (tujuan akhir RW)
                    $subQuery->where('status', 'approved_rw')
                            ->where('level_akhir', 'rw');
                })
                ->orWhere(function ($subQuery) {
                    // PSU yang selesai di level Kelurahan (tujuan akhir Kelurahan)
                    $subQuery->where('status', 'approved_kelurahan')
                            ->where('level_akhir', 'kelurahan');
                });
        })->count();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        // Get RT-RW mapping
        $rwRtMapping = $this->getRwRtMapping();

        // Generate available options
        $availableRW = $this->generateAvailableRW($rwRtMapping);
        $availableRT = $this->generateAvailableRT();

        // Get leadership data
        $ketuaRT = User::where('role', 'Ketua RT')->get(['id', 'name', 'rt', 'rw']);
        $ketuaRW = User::where('role', 'Ketua RW')->get(['id', 'name', 'rw']);

        // Generate ditujukan kepada options based on user role
        $ditujukanKepadaOptions = $this->generateDitujukanKepadaOptions($user);

        return view('Psu.create', [
            'type_menu' => 'psu',
            'pageTitle' => 'Tambah PSU (Permohonan Surat Umum)',
            'user' => $user,
            'availableRT' => $availableRT,
            'availableRW' => $availableRW,
            'rwRtMapping' => $rwRtMapping,
            'ketuaRT' => $ketuaRT,
            'ketuaRW' => $ketuaRW,
            'ditujukanKepadaOptions' => $ditujukanKepadaOptions
        ]);
    }

    /**
     * Generate ditujukan kepada options based on user role
     */
    private function generateDitujukanKepadaOptions($user)
    {
        $options = [];

        switch ($user->role) {
            case 'user':
                // Warga hanya bisa mengajukan ke RT, RW, atau Kelurahan (External PSU)
                $options = [
                    'rt' => 'RT (Persetujuan)',
                    'rw' => 'RW (Persetujuan)',
                    'kelurahan' => 'Kelurahan (Persetujuan)'
                ];
                break;

            case 'Ketua RT':
                // Ketua RT bisa membuat untuk warga RT-nya sendiri (internal) + external ke RW/Kelurahan
                // TIDAK ADA opsi RT karena sesama Ketua RT tidak butuh persetujuan RT
                $options = [
                    'warga_rt' => "Warga RT {$user->rt} (Internal)",
                    'rw' => 'RW (Persetujuan)',
                    'kelurahan' => 'Kelurahan (Persetujuan)'
                ];
                break;

            case 'Ketua RW':
                // Ketua RW bisa membuat untuk warga RT dan RW-nya (internal) + external ke Kelurahan
                // TIDAK ADA opsi RW karena sesama Ketua RW tidak butuh persetujuan RW
                $options = [
                    'warga_rt' => "Warga RT dalam RW {$user->rw} (Internal)",
                    'warga_rw' => "Warga RW {$user->rw} (Internal)",
                    'kelurahan' => 'Kelurahan (Persetujuan)'
                ];
                break;

            default:
                // Default untuk role lain
                $options = [
                    'rt' => 'RT (Persetujuan)',
                    'rw' => 'RW (Persetujuan)',
                    'kelurahan' => 'Kelurahan (Persetujuan)'
                ];
                break;
        }

        return $options;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = $this->validatePsuRequest($request);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Determine PSU type and target info
            $psuTypeData = $this->determinePsuTypeAndTarget($request, $user);

            // Generate nomor surat
            $nomorSurat = Psu::generateNomorSurat(
                $request->rt,
                $request->rw,
                $request->bulan
            );

            // Process signature
            $ttdPemohonPath = null;
            if ($request->ttd_pemohon) {
                $ttdPemohonPath = $this->saveSignature($request->ttd_pemohon, 'pemohon');
            }

            // Handle file uploads
            $fileLampiran = $this->handleFileUploads($request);

            // Create PSU record dengan kolom target yang tepat
            $psuData = [
                'nomor_surat' => $nomorSurat,
                'user_id' => Auth::id(),

                // Data Pemohon
                'nama_lengkap' => $request->nama_lengkap,
                'nik' => $request->nomor_kk,
                'alamat' => $request->alamat,
                'pekerjaan' => $request->pekerjaan,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'agama' => $request->agama,
                'status_perkawinan' => $request->status_perkawinan,
                'kewarganegaraan' => $request->kewarganegaraan,
                'nomor_kk' => $request->nomor_kk,
                'rt' => $request->rt,
                'rw' => $request->rw,

                // Target Info
                'ditujukan_kepada' => $request->ditujukan_kepada,
                'target_type' => $psuTypeData['target_type'],
                'target_rt' => $psuTypeData['target_rt'],
                'target_rw' => $psuTypeData['target_rw'],
                'target_warga_id' => $psuTypeData['target_warga_id'],
                'target_warga_name' => $psuTypeData['target_warga_name'],
                'nama_ketua_rt' => $psuTypeData['nama_ketua_rt'],
                'nama_ketua_rw' => $psuTypeData['nama_ketua_rw'],

                // Detail Surat
                'bulan' => $request->bulan,
                'sifat' => $request->sifat,
                'hal' => $request->hal,
                'isi_surat' => $request->isi_surat,
                'tujuan_internal' => $psuTypeData['tujuan_internal'],
                'tujuan_eksternal' => $request->tujuan_eksternal,

                // Status & Level
                'status' => $psuTypeData['status'],
                'level_akhir' => $psuTypeData['level_akhir'],

                // Files
                'ttd_pemohon' => $ttdPemohonPath,
                'file_lampiran' => $fileLampiran,

                // Metadata
                'metadata' => $psuTypeData['metadata']
            ];

            // PERBAIKAN: Handle auto-approval untuk Ketua RT dan Ketua RW yang mengajukan ke Kelurahan
            if ($request->ditujukan_kepada === 'kelurahan') {
                if ($user->role === 'Ketua RW') {
                    // Auto approve RW dengan TTD Ketua RW
                    $spesimenRW = $this->getSpesimenData('Ketua RW', null, $user->rw);

                    if ($spesimenRW) {
                        $psuData['ttd_rw'] = $spesimenRW->file_ttd;
                        $psuData['stempel_rw'] = $spesimenRW->file_stempel;
                        $psuData['approved_rw_at'] = now();
                        $psuData['approved_rw_by'] = Auth::id();
                        $psuData['catatan_rw'] = 'Auto approved - Dibuat langsung oleh Ketua RW';
                    }
                } elseif ($user->role === 'Ketua RT') {
                    // Auto approve RT dengan TTD Ketua RT
                    $spesimenRT = $this->getSpesimenData('Ketua RT', $user->rt, $user->rw);

                    if ($spesimenRT) {
                        $psuData['ttd_rt'] = $spesimenRT->file_ttd;
                        $psuData['stempel_rt'] = $spesimenRT->file_stempel;
                        $psuData['approved_rt_at'] = now();
                        $psuData['approved_rt_by'] = Auth::id();
                        $psuData['catatan_rt'] = 'Auto approved - Dibuat langsung oleh Ketua RT';
                    }
                }
            }

            $psu = Psu::create($psuData);

            Log::info("Created PSU ID: {$psu->id} with target_type: {$psu->target_type}, status: {$psu->status}");

            // Auto-approve PSU Internal dan buat UserApplication untuk target
            if ($psuTypeData['status'] === 'completed') {
                Log::info("Auto-approving PSU Internal ID: {$psu->id}");
                $this->autoApprovePSUInternal($psu);
            }

            // Sync to UserApplication (untuk pembuat)
            $this->syncToUserApplication($psu);

            DB::commit();

            $message = 'PSU berhasil diajukan dengan nomor: ' . $psu->nomor_surat;
            if ($psuTypeData['status'] === 'completed') {
                $message .= ' (PSU Internal - Langsung Disetujui dan Dikirim ke Target Warga)';
            } elseif ($request->ditujukan_kepada === 'kelurahan') {
                if ($user->role === 'Ketua RW') {
                    $message .= ' (Langsung diteruskan ke Front Office Kelurahan)';
                } elseif ($user->role === 'Ketua RT') {
                    $message .= ' (Auto approved RT - Menunggu persetujuan Ketua RW)';
                }
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('psu.index')
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating PSU: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Psu $psu)
    {
        try {
            // Authorization check
            $this->authorizeAccess($psu);

            // Load relationships
            $psu->load(['user', 'approverRT', 'approverRW', 'approverKelurahan']);

            // Return JSON response for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'id' => $psu->id,
                    'nomor_surat' => $psu->nomor_surat,
                    'nama_lengkap' => $psu->nama_lengkap,
                    'nik' => $psu->nik,
                    'alamat' => $psu->alamat,
                    'rt' => $psu->rt,
                    'rw' => $psu->rw,
                    'hal' => $psu->hal,
                    'isi_surat' => $psu->isi_surat,
                    'sifat' => $psu->sifat,
                    'status' => $psu->status,
                    'ditujukan_kepada' => $psu->ditujukan_kepada,
                    'created_at' => $psu->created_at,
                    'bulan' => $psu->bulan,
                    'pekerjaan' => $psu->pekerjaan,
                    'jenis_kelamin' => $psu->jenis_kelamin,
                    'tempat_lahir' => $psu->tempat_lahir,
                    'tanggal_lahir' => $psu->tanggal_lahir,
                    'agama' => $psu->agama,
                    'status_perkawinan' => $psu->status_perkawinan,
                    'kewarganegaraan' => $psu->kewarganegaraan,
                    'nomor_kk' => $psu->nomor_kk,
                    'tujuan_internal' => $psu->tujuan_internal,
                    'tujuan_eksternal' => $psu->tujuan_eksternal,
                    'metadata' => $psu->metadata,
                    'user' => $psu->user ? [
                        'name' => $psu->user->name,
                        'email' => $psu->user->email,
                    ] : null,
                ]);
            }

            // Debug: log data PSU sebelum load relationships
            Log::info('PSU Data before relationships: ', $psu->toArray());

            // Debug: log workflow progress
            try {
                $workflowProgress = $psu->workflow_progress;
                Log::info('Workflow Progress: ', $workflowProgress);
            } catch (\Exception $e) {
                Log::error('Error getting workflow progress: ' . $e->getMessage());
            }

            // Check approval permissions
            $approvalPermissions = $this->getApprovalPermissions($psu);

            // Check kelurahan workflow permissions
            $kelurahanPermissions = $this->getKelurahanWorkflowPermissions($psu);

            return view('Psu.show', [
                'type_menu' => 'psu',
                'pageTitle' => 'Detail PSU (Permohonan Surat Umum)',
                'psu' => $psu,
                'canApproveRT' => $approvalPermissions['rt'],
                'canApproveRW' => $approvalPermissions['rw'],
                'canApproveKelurahan' => $approvalPermissions['kelurahan'],
                'canReceiveKelurahan' => $kelurahanPermissions['receive'],
                'canProcessLurah' => $kelurahanPermissions['process_lurah'],
                'canApproveBackOffice' => $kelurahanPermissions['approve_back_office']
            ]);

        } catch (\Exception $e) {
            Log::error('Error in PSU show method: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memuat data: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('psu.index')
                        ->with('error', 'Terjadi kesalahan saat memuat data: ' . $e->getMessage());
        }
    }

    private function getKelurahanWorkflowPermissions(Psu $psu): array
    {
        $user = Auth::user();
        $userRole = $user->role;

        // Initialize permissions
        $permissions = [
            'receive' => false,
            'process_lurah' => false,
            'approve_back_office' => false
        ];

        // Check if PSU is external type (yang butuh workflow kelurahan)
        $isPSUExternal = !$psu->isPSUInternal();

        if (!$isPSUExternal) {
            // PSU Internal tidak butuh workflow kelurahan
            return $permissions;
        }

        switch ($userRole) {
            case 'Front Office':
                // Front Office bisa receive kelurahan jika:
                // - PSU sudah approved RW tapi belum diterima kelurahan
                // - Status = approved_rw
                $permissions['receive'] = ($psu->status === 'approved_rw');
                break;

            case 'Lurah':
                // Lurah bisa process disposisi jika:
                // - PSU sudah diterima kelurahan tapi belum diproses lurah
                // - Status = pending_kelurahan atau received_kelurahan
                $permissions['process_lurah'] = in_array($psu->status, [
                    'pending_kelurahan',
                    'received_kelurahan'
                ]);
                break;

            case 'Back Office':
                // Back Office bisa approve final jika:
                // - PSU sudah diproses lurah tapi belum final approve
                // - Status = processing_lurah atau processed_lurah
                $permissions['approve_back_office'] = in_array($psu->status, [
                    'processing_lurah',
                    'processed_lurah'
                ]);
                break;

            case 'Operator':
                // Operator bisa semua action kelurahan jika diperlukan
                $permissions['receive'] = ($psu->status === 'approved_rw');
                $permissions['process_lurah'] = in_array($psu->status, [
                    'pending_kelurahan',
                    'received_kelurahan'
                ]);
                $permissions['approve_back_office'] = in_array($psu->status, [
                    'processing_lurah',
                    'processed_lurah'
                ]);
                break;

            default:
                // Role lain tidak punya permission kelurahan workflow
                break;
        }

        return $permissions;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Psu $psu)
    {
        // Authorization check
        if (Auth::user()->role === 'user' && $psu->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit data ini.');
        }

        if (!$psu->canBeEdited()) {
            return redirect()->route('psu.index')
                           ->with('error', 'Data tidak dapat diedit karena sudah diproses.');
        }

        // Get options data
        $rwRtMapping = $this->getRwRtMapping();
        $availableRW = $this->generateAvailableRW($rwRtMapping);
        $availableRT = $this->generateAvailableRT();
        $ketuaRT = User::where('role', 'Ketua RT')->get(['id', 'name', 'rt', 'rw']);
        $ketuaRW = User::where('role', 'Ketua RW')->get(['id', 'name', 'rw']);

        return view('Psu.edit', [
            'type_menu' => 'psu',
            'pageTitle' => 'Edit PSU (Permohonan Surat Umum)',
            'psu' => $psu,
            'availableRT' => $availableRT,
            'availableRW' => $availableRW,
            'rwRtMapping' => $rwRtMapping,
            'ketuaRT' => $ketuaRT,
            'ketuaRW' => $ketuaRW
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Psu $psu)
    {
        // Authorization check
        if (Auth::user()->role === 'user' && $psu->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk mengedit data ini.'
            ], 403);
        }

        if (!$psu->canBeEdited()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak dapat diedit karena sudah diproses.'
            ], 400);
        }

        $validator = $this->validatePsuUpdateRequest($request, $psu);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $updateData = $this->buildUpdateData($request, $psu);

            // Check if nomor surat needs regeneration
            if ($this->shouldRegenerateNomorSurat($request, $psu)) {
                $updateData['nomor_surat'] = Psu::generateNomorSurat(
                    $request->rt,
                    $request->rw,
                    $request->bulan
                );
            }

            // Handle workflow changes
            if ($this->hasWorkflowChanged($request, $psu)) {
                $updateData = array_merge($updateData, $this->resetApprovalData());
                Log::info("Workflow changed for PSU ID {$psu->id}, resetting approval status");
            }

            $psu->update($updateData);

            // Handle auto-approved status
            if (isset($updateData['status']) && $updateData['status'] === 'auto_approved') {
                $this->generatePDF($psu);
            }

            // Sync to UserApplication
            $this->syncToUserApplication($psu);

            DB::commit();

            $message = 'Data PSU berhasil diperbarui.';
            if ($this->hasWorkflowChanged($request, $psu)) {
                $message .= ' Karena target permohonan berubah, proses persetujuan akan dimulai ulang.';
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating PSU: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Psu $psu)
    {
        $userRole = Auth::user()->role;

        // Authorization check
        if ($userRole === 'user' && $psu->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data ini.'
            ], 403);
        }

        // Check if can be deleted
        if ($userRole === 'user' && !$psu->canBeEdited()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak dapat dihapus karena sudah diproses.'
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Delete corresponding UserApplication record
            UserApplication::where('reference_id', $psu->id)
                          ->where('reference_table', 'psu')
                          ->delete();

            // Delete associated files
            $this->deleteAssociatedFiles($psu);

            $psu->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data PSU berhasil dihapus.'
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting PSU: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Auto-approve PSU Internal dengan TTD otomatis
     */
    private function autoApprovePSUInternal(Psu $psu)
    {
        try {
            // Tentukan level berdasarkan pembuat
            $creatorRole = Auth::user()->role;

            if ($psu->ditujukan_kepada === 'warga_rt' && $creatorRole === 'Ketua RT') {
                // Ketua RT membuat untuk warga RT - auto approve dengan TTD RT
                $spesimenRT = $this->getSpesimenData('Ketua RT', $psu->rt, $psu->rw);

                if ($spesimenRT) {
                    $psu->update([
                        'ttd_rt' => $spesimenRT->file_ttd,
                        'stempel_rt' => $spesimenRT->file_stempel,
                        'approved_rt_at' => now(),
                        'approved_rt_by' => Auth::id(),
                        'status' => 'completed'
                    ]);
                }

            } elseif ($psu->ditujukan_kepada === 'warga_rw' && $creatorRole === 'Ketua RW') {
                // Ketua RW membuat untuk warga RW - auto approve dengan TTD RW
                $spesimenRW = $this->getSpesimenData('Ketua RW', null, $psu->rw);

                if ($spesimenRW) {
                    $psu->update([
                        'ttd_rw' => $spesimenRW->file_ttd,
                        'stempel_rw' => $spesimenRW->file_stempel,
                        'approved_rw_at' => now(),
                        'approved_rw_by' => Auth::id(),
                        'status' => 'completed'
                    ]);
                }
            }

            // Generate PDF untuk PSU Internal
            $this->generatePDF($psu);

            // PENTING: Create notifications untuk target warga
            $this->createPSUInternalNotifications($psu);

            Log::info("Auto-approved PSU Internal ID: {$psu->id} by {$creatorRole}");

        } catch (Exception $e) {
            Log::error('Error auto-approving PSU Internal: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create notifications untuk warga yang menerima PSU Internal
     */
    private function createPSUInternalNotifications(Psu $psu)
    {
        try {
            $createdCount = 0;

            Log::info("Creating PSU Internal notifications for PSU ID: {$psu->id}");
            Log::info("PSU target_type: {$psu->target_type}, target_warga_id: {$psu->target_warga_id}");

            // PERBAIKAN: Baca dari kolom database langsung
            if ($psu->target_type === 'individual' && $psu->target_warga_id) {
                // Target individual
                $targetUser = User::find($psu->target_warga_id);
                if ($targetUser) {
                    $this->createPSUInternalUserApplication($psu, $targetUser);
                    $createdCount++;
                    Log::info("Created UserApplication for individual target user ID: {$targetUser->id}");
                }
            } else {
                // Target berdasarkan type
                if ($psu->target_type === 'semua_rt' && $psu->target_rt && $psu->target_rw) {
                    // Target semua warga di RT
                    $wargaRT = User::where('role', 'user')
                                ->where('rt', $psu->target_rt)
                                ->where('rw', $psu->target_rw)
                                ->where('is_active', true)
                                ->get();

                    Log::info("Found {$wargaRT->count()} warga in RT {$psu->target_rt} RW {$psu->target_rw}");

                    foreach ($wargaRT as $warga) {
                        $this->createPSUInternalUserApplication($psu, $warga);
                        $createdCount++;
                    }
                }

                if ($psu->target_type === 'semua_rw' && $psu->target_rw) {
                    // Target semua warga di RW
                    $wargaRW = User::where('role', 'user')
                                ->where('rw', $psu->target_rw)
                                ->where('is_active', true)
                                ->get();

                    Log::info("Found {$wargaRW->count()} warga in RW {$psu->target_rw}");

                    foreach ($wargaRW as $warga) {
                        $this->createPSUInternalUserApplication($psu, $warga);
                        $createdCount++;
                    }
                }
            }

            Log::info("Created {$createdCount} UserApplication records for PSU Internal ID: {$psu->id}");

        } catch (Exception $e) {
            Log::error('Error creating PSU Internal notifications: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }
    }

    /**
     * Create UserApplication untuk warga penerima PSU Internal
     */
    private function createPSUInternalUserApplication(Psu $psu, User $targetUser)
    {
        try {
            // Check if already exists
            $exists = UserApplication::where('reference_id', $psu->id)
                                    ->where('reference_table', 'psu')
                                    ->where('user_id', $targetUser->id)
                                    ->exists();

            if (!$exists) {
                $creator = $psu->user;

                $userApp = UserApplication::create([
                    'nomor_surat' => $psu->nomor_surat,
                    'user_id' => $targetUser->id, // Target warga
                    'jenis_permohonan' => 'PSU',
                    'judul_permohonan' => 'PSU Internal - ' . $psu->hal,
                    'deskripsi_permohonan' => $psu->isi_surat,
                    'nama_pemohon' => $psu->nama_lengkap, // Pembuat asli
                    'nik' => $targetUser->nik ?? '',
                    'rt' => $targetUser->rt,
                    'rw' => $targetUser->rw,
                    'status' => 'completed',
                    'file_pdf' => $psu->file_pdf,
                    'reference_id' => $psu->id,
                    'reference_table' => 'psu',
                    'ditujukan_kepada' => $psu->ditujukan_kepada,
                    'level_akhir' => 'auto_approved',
                    'approved_rt_at' => $psu->approved_rt_at,
                    'approved_rt_by' => $psu->approved_rt_by,
                    'approved_rw_at' => $psu->approved_rw_at,
                    'approved_rw_by' => $psu->approved_rw_by,
                    'metadata' => [
                        'psu_type' => 'internal',
                        'psu_creator_role' => $creator->role ?? 'unknown',
                        'psu_creator_name' => $creator->name ?? 'unknown',
                        'creator_rt' => $creator->rt ?? null,
                        'creator_rw' => $creator->rw ?? null,
                        'received_as_target' => true,
                        'is_surat_masuk' => true, // FLAG PENTING untuk surat masuk
                        'target_user_id' => $targetUser->id,
                        'target_user_name' => $targetUser->name,
                        'created_for_user' => true,
                        // Data dari kolom database
                        'target_type' => $psu->target_type,
                        'target_rt' => $psu->target_rt,
                        'target_rw' => $psu->target_rw,
                    ]
                ]);

                Log::info("Created PSU Internal UserApplication ID: {$userApp->id} for user ID: {$targetUser->id}, PSU ID: {$psu->id}");
                return $userApp;
            } else {
                Log::info("UserApplication already exists for user ID: {$targetUser->id}, PSU ID: {$psu->id}");
                return null;
            }

        } catch (Exception $e) {
            Log::error("Error creating PSU Internal UserApplication for user ID: {$targetUser->id}, PSU ID: {$psu->id} - " . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return null;
        }
    }

    /**
     * Command untuk fix existing PSU yang sudah auto_approved tapi belum ada UserApplication
     */
    public function fixExistingPSUInternal()
    {
        try {
            // Find PSU yang auto_approved tapi belum ada UserApplication untuk target
            $autoApprovedPSU = Psu::where('status', 'completed')
                                ->where('level_akhir', 'auto_approved')
                                ->whereIn('ditujukan_kepada', ['warga_rt', 'warga_rw'])
                                ->get();

            $fixedCount = 0;

            foreach ($autoApprovedPSU as $psu) {
                Log::info("Fixing existing PSU Internal ID: {$psu->id}");

                // Check if UserApplication for targets already exist
                $existingCount = UserApplication::where('reference_id', $psu->id)
                                            ->where('reference_table', 'psu')
                                            ->whereJsonContains('metadata->is_surat_masuk', true)
                                            ->count();

                if ($existingCount === 0) {
                    // Belum ada UserApplication untuk target, buat sekarang
                    $this->createPSUInternalNotifications($psu);
                    $fixedCount++;
                    Log::info("Fixed PSU Internal ID: {$psu->id}");
                } else {
                    Log::info("PSU Internal ID: {$psu->id} already has {$existingCount} UserApplication records");
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Fixed {$fixedCount} PSU Internal records",
                'data' => [
                    'total_checked' => $autoApprovedPSU->count(),
                    'fixed_count' => $fixedCount
                ]
            ]);

        } catch (Exception $e) {
            Log::error('Error fixing existing PSU Internal: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fixing existing PSU Internal: ' . $e->getMessage()
            ], 500);
        }
    }

    // ========================================
    // SPESIMEN AND APPROVAL METHODS
    // ========================================

    /**
     * Get RT TTD and Stempel for approval
     */
    public function getRTSpesimen(Request $request, Psu $psu)
    {
        if (Auth::user()->role !== 'Ketua RT') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        if (Auth::user()->rt != $psu->rt || Auth::user()->rw != $psu->rw) {
            return response()->json([
                'success' => false,
                'message' => 'Anda hanya dapat mengakses data dari RT ' . Auth::user()->rt . ' RW ' . Auth::user()->rw . '.'
            ], 403);
        }

        $spesimen = $this->getSpesimenData('Ketua RT', $psu->rt, $psu->rw);

        if (!$spesimen) {
            return response()->json([
                'success' => false,
                'message' => 'Data spesimen TTD/Stempel RT tidak ditemukan. Silakan hubungi admin.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'ttd_rt' => $spesimen->file_ttd ? Storage::url($spesimen->file_ttd) : null,
                'stempel_rt' => $spesimen->file_stempel ? Storage::url($spesimen->file_stempel) : null,
                'nama_pejabat' => $spesimen->nama_pejabat,
                'nomor_rt' => $psu->rt
            ]
        ]);
    }

    /**
     * Get RW TTD and Stempel for approval
     */
    public function getRWSpesimen(Request $request, Psu $psu)
    {
        if (Auth::user()->role !== 'Ketua RW') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        if (Auth::user()->rw != $psu->rw) {
            return response()->json([
                'success' => false,
                'message' => 'Anda hanya dapat mengakses data dari RW Anda.'
            ], 403);
        }

        $spesimen = $this->getSpesimenData('Ketua RW', null, $psu->rw);

        if (!$spesimen) {
            return response()->json([
                'success' => false,
                'message' => 'Data spesimen TTD/Stempel RW tidak ditemukan. Silakan hubungi admin.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'ttd_rw' => $spesimen->file_ttd ? Storage::url($spesimen->file_ttd) : null,
                'stempel_rw' => $spesimen->file_stempel ? Storage::url($spesimen->file_stempel) : null,
                'nama_pejabat' => $spesimen->nama_pejabat
            ]
        ]);
    }

    public function getFrontOfficeSpesimen(Request $request, Psu $psu)
    {
        // Authorize: Only Front Office, Back Office, Lurah, and Operator can access
        if (!in_array(Auth::user()->role, ['Front Office', 'Back Office', 'Lurah', 'Operator'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Get Front Office TTD spesimen
        $frontOfficeSpesimen = $this->getSpesimenData('Front Office');

        // Get Kelurahan Stempel spesimen (karena stempel kelurahan digunakan oleh semua role kelurahan)
        $kelurahanSpesimen = $this->getSpesimenData('Front Office'); // atau bisa juga 'Kelurahan' tergantung setup

        // Validate TTD Front Office tersedia
        if (!$frontOfficeSpesimen || !$frontOfficeSpesimen->file_ttd) {
            return response()->json([
                'success' => false,
                'message' => 'Data spesimen TTD Front Office tidak ditemukan. Silakan hubungi admin untuk mengupload TTD Front Office.'
            ], 404);
        }

        // Validate Stempel Kelurahan tersedia
        if (!$kelurahanSpesimen || !$kelurahanSpesimen->file_stempel) {
            return response()->json([
                'success' => false,
                'message' => 'Data spesimen Stempel Kelurahan tidak ditemukan. Silakan hubungi admin untuk mengupload Stempel Kelurahan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'ttd_front_office' => $frontOfficeSpesimen->file_ttd ? Storage::url($frontOfficeSpesimen->file_ttd) : null,
                'nama_pejabat_front_office' => $frontOfficeSpesimen->nama_pejabat ?? 'Front Office',
                'stempel_kelurahan' => $kelurahanSpesimen->file_stempel ? Storage::url($kelurahanSpesimen->file_stempel) : null,
                'nama_kelurahan' => $kelurahanSpesimen->nama_pejabat ?? 'Kelurahan'
            ]
        ]);
    }

    public function getLurahSpesimen(Request $request, Psu $psu)
    {
        if (!in_array(Auth::user()->role, ['Lurah', 'admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Get Lurah TTD spesimen
        $lurahSpesimen = $this->getSpesimenData('Front Office');

        // Get Kelurahan Stempel spesimen (sama seperti yang digunakan di Front Office)
        $kelurahanSpesimen = $this->getSpesimenData('Front Office'); // atau bisa 'Kelurahan' tergantung setup

        $response = [
            'success' => true,
            'data' => []
        ];

        // TTD Lurah
        if ($lurahSpesimen && $lurahSpesimen->file_ttd) {
            $response['data']['ttd_lurah'] = Storage::url($lurahSpesimen->file_ttd);
            $response['data']['nama_pejabat'] = $lurahSpesimen->nama_pejabat ?? 'Lurah';
        } else {
            $response['data']['ttd_lurah'] = null;
            $response['data']['nama_pejabat'] = 'Lurah';
        }

        // Stempel Kelurahan
        if ($kelurahanSpesimen && $kelurahanSpesimen->file_stempel) {
            $response['data']['stempel_kelurahan'] = Storage::url($kelurahanSpesimen->file_stempel);
        } else {
            $response['data']['stempel_kelurahan'] = null;
        }

        // Debug info
        $response['debug'] = [
            'lurah_spesimen_found' => $lurahSpesimen ? true : false,
            'kelurahan_spesimen_found' => $kelurahanSpesimen ? true : false,
            'lurah_ttd_path' => $lurahSpesimen ? $lurahSpesimen->file_ttd : null,
            'kelurahan_stempel_path' => $kelurahanSpesimen ? $kelurahanSpesimen->file_stempel : null,
        ];

        return response()->json($response);
    }

    /**
     * Get Kelurahan TTD and Stempel for approval
     */
    public function getKelurahanSpesimen(Request $request, Psu $psu)
    {
        if (!in_array(Auth::user()->role, ['Lurah', 'Camat'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $spesimen = $this->getSpesimenData('Lurah');

        if (!$spesimen) {
            return response()->json([
                'success' => false,
                'message' => 'Data spesimen TTD/Stempel Kelurahan tidak ditemukan. Silakan hubungi admin.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'ttd_kelurahan' => $spesimen->file_ttd ? Storage::url($spesimen->file_ttd) : null,
                'stempel_kelurahan' => $spesimen->file_stempel ? Storage::url($spesimen->file_stempel) : null,
                'nama_pejabat' => $spesimen->nama_pejabat
            ]
        ]);
    }

    /**
     * Approve by RT
     */
    public function approveRT(Request $request, Psu $psu)
    {
        $validator = Validator::make($request->all(), [
            'ttd_rt_url' => 'required|string',
            'stempel_rt_url' => 'required|string',
            'catatan_rt' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            return $this->processApproval($psu, 'rt', $request->all());
        } catch (Exception $e) {
            Log::error('Error approving RT: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject by RT
     */
    public function rejectRT(Request $request, Psu $psu)
    {
        $validator = Validator::make($request->all(), [
            'catatan_rt' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            return $this->processRejection($psu, 'rt', $request->catatan_rt);
        } catch (Exception $e) {
            Log::error('Error rejecting RT: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve by RW
     */
    public function approveRW(Request $request, Psu $psu)
    {
        $validator = Validator::make($request->all(), [
            'ttd_rw_url' => 'required|string',
            'stempel_rw_url' => 'required|string',
            'catatan_rw' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            return $this->processApproval($psu, 'rw', $request->all());
        } catch (Exception $e) {
            Log::error('Error approving RW: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject by RW
     */
    public function rejectRW(Request $request, Psu $psu)
    {
        $validator = Validator::make($request->all(), [
            'catatan_rw' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            return $this->processRejection($psu, 'rw', $request->catatan_rw);
        } catch (Exception $e) {
            Log::error('Error rejecting RW: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve by Kelurahan
     */
    public function approveKelurahan(Request $request, Psu $psu)
    {
        $validator = Validator::make($request->all(), [
            'ttd_kelurahan_url' => 'required|string',
            'stempel_kelurahan_url' => 'required|string',
            'catatan_kelurahan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            return $this->processApproval($psu, 'kelurahan', $request->all());
        } catch (Exception $e) {
            Log::error('Error approving Kelurahan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject by Kelurahan
     */
    public function rejectKelurahan(Request $request, Psu $psu)
    {
        $validator = Validator::make($request->all(), [
            'catatan_kelurahan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            return $this->processRejection($psu, 'kelurahan', $request->catatan_kelurahan);
        } catch (Exception $e) {
            Log::error('Error rejecting Kelurahan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Receive PSU at Kelurahan (Front Office)
     * Creates Tanda Terima and Disposisi automatically
     */
    public function receiveKelurahan(Request $request, Psu $psu)
    {
        // Authorization check
        if (!in_array(Auth::user()->role, ['Front Office', 'admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk melakukan proses ini.'
            ], 403);
        }

        // Check if PSU can be received at Kelurahan
        if ($psu->status !== 'approved_rw') {
            return response()->json([
                'success' => false,
                'message' => 'PSU tidak dapat diterima. Status saat ini: ' . $psu->status_text
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'catatan_front_office' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Generate nomor agenda untuk Kelurahan
            $nomorAgendaKelurahan = $this->generateNomorAgendaKelurahan();

            // 1. Update PSU status - menggunakan field yang ada di schema
            $psu->update([
                'status' => 'pending_kelurahan',
                'received_kelurahan_at' => now(),
                'received_kelurahan_by' => Auth::id(),
                'metadata' => array_merge($psu->metadata ?? [], [
                    'catatan_front_office' => $request->catatan_front_office,
                    'nomor_agenda_kelurahan' => $nomorAgendaKelurahan,
                ]),
            ]);

            // 2. PERBAIKAN: Get spesimen data dengan benar
            $frontOfficeSpesimen = $this->getSpesimenData('Front Office');
            $kelurahanSpesimen = $this->getSpesimenData('Front Office'); // Atau bisa 'Kelurahan'

            if (!$frontOfficeSpesimen || !$frontOfficeSpesimen->file_ttd) {
                throw new Exception('Data spesimen TTD Front Office tidak ditemukan. Silakan hubungi admin untuk mengupload TTD Front Office.');
            }

            if (!$kelurahanSpesimen || !$kelurahanSpesimen->file_stempel) {
                throw new Exception('Data spesimen Stempel Kelurahan tidak ditemukan. Silakan hubungi admin untuk mengupload Stempel Kelurahan.');
            }

            // 3. Generate Surat Tanda Terima dengan spesimen data
            $tandaTerimaData = $this->generateTandaTerima(
                $psu,
                $nomorAgendaKelurahan,
                $frontOfficeSpesimen->file_ttd,
                $kelurahanSpesimen->file_stempel
            );

            // 2. Generate Surat Tanda Terima untuk Pemohon
            // $tandaTerimaData = $this->generateTandaTerima($psu, $nomorAgendaKelurahan);

            // 3. Generate Lembar Disposisi untuk Lurah
            $disposisiData = $this->generateDisposisiLurah($psu, $nomorAgendaKelurahan);

            // 4. PERBAIKAN: Log activity dengan detail yang lengkap
            $psu->logActivity('receive_kelurahan',
                "Front Office menerima PSU di Kelurahan: {$psu->nomor_surat}",
                [
                    'nomor_agenda' => $nomorAgendaKelurahan,
                    'catatan_front_office' => $request->catatan_front_office,
                    'received_by' => Auth::user()->name,
                    'tanda_terima_generated' => true,
                    'disposisi_generated' => true,
                    'tanda_terima_file' => $tandaTerimaData['file_path'] ?? null,
                    'disposisi_file' => $disposisiData['file_path'] ?? null,
                ]
            );

            // PERBAIKAN: Log activity untuk user pemohon tentang tanda terima
            if ($psu->user_id) {
                // Create activity for user about tanda terima
                \App\Models\ActivityLog::create([
                    'user_id' => $psu->user_id, // User pemohon
                    'action' => 'tanda_terima_generated',
                    'subject_type' => get_class($psu),
                    'subject_id' => $psu->id,
                    'description' => "Surat Tanda Terima telah dibuat untuk pengajuan PSU: {$psu->nomor_surat}",
                    'properties' => [
                        'nomor_agenda' => $nomorAgendaKelurahan,
                        'file_path' => $tandaTerimaData['file_path'] ?? null,
                        'file_url' => $tandaTerimaData['file_url'] ?? null,
                        'generated_by' => Auth::user()->name . ' (Front Office)',
                        'can_view_file' => true
                    ],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            }

            // 5. Sync to UserApplication
            $this->syncToUserApplication($psu);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'PSU berhasil diterima di Kelurahan. Tanda Terima dan Disposisi telah dibuat otomatis.',
                'data' => [
                    'nomor_agenda' => $nomorAgendaKelurahan,
                    'tanda_terima_url' => $tandaTerimaData['file_url'] ?? null,
                    'disposisi_url' => $disposisiData['file_url'] ?? null,
                ]
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error receiving PSU at Kelurahan: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process Lurah Disposisi
     * Lurah fills and signs the disposisi form
     */
    public function processLurah(Request $request, Psu $psu)
    {
        // Authorization check
        if (!in_array(Auth::user()->role, ['Lurah', 'admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk melakukan proses ini.'
            ], 403);
        }

        // Check if PSU can be processed by Lurah
        if ($psu->status !== 'pending_kelurahan') {
            return response()->json([
                'success' => false,
                'message' => 'PSU tidak dapat diproses. Status saat ini: ' . $psu->status_text
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'catatan_lurah' => 'required|string|max:2000',
            'instruksi_arahan' => 'required|string',
            'diteruskan_kepada' => 'required|string|in:Back Office,Sekretariat,Bagian lain',
            'ttd_lurah_spesimen' => 'nullable|string',
            'ttd_lurah_manual' => 'nullable|string',
            'stempel_kelurahan_url' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Get spesimen data untuk fallback TTD dan Stempel
            $frontOfficeSpesimen = $this->getSpesimenData('Front Office');

            // Handle TTD Lurah - prioritas: manual > spesimen > fallback
            $ttdLurahPath = null;

            if ($request->ttd_lurah_manual && $request->use_manual_signature === 'true') {
                // Use manual signature
                $ttdLurahPath = $this->saveSignature($request->ttd_lurah_manual, 'lurah_disposisi');
            } elseif ($request->ttd_lurah_spesimen) {
                // Use spesimen signature - copy to disposisi folder
                $originalPath = str_replace('/storage/', '', $request->ttd_lurah_spesimen);
                $fileName = 'disposisi_lurah_' . time() . '_' . uniqid() . '.png';
                $newPath = 'psu/signatures/' . $fileName;

                if (Storage::disk('public')->exists($originalPath)) {
                    Storage::disk('public')->copy($originalPath, $newPath);
                    $ttdLurahPath = $newPath;
                }
            } elseif ($frontOfficeSpesimen && $frontOfficeSpesimen->file_ttd) {
                // Fallback ke Front Office spesimen
                $fileName = 'disposisi_lurah_fallback_' . time() . '_' . uniqid() . '.png';
                $newPath = 'psu/signatures/' . $fileName;

                if (Storage::disk('public')->copy($frontOfficeSpesimen->file_ttd, $newPath)) {
                    $ttdLurahPath = $newPath;
                }
            }

            // Handle Stempel Kelurahan
            $stempelKelurahanPath = null;
            if ($request->stempel_kelurahan_url) {
                $stempelKelurahanPath = $request->stempel_kelurahan_url;
            } elseif ($frontOfficeSpesimen && $frontOfficeSpesimen->file_stempel) {
                $stempelKelurahanPath = Storage::url($frontOfficeSpesimen->file_stempel);
            }

            // Update PSU with Lurah's disposisi
            $psu->update([
                'status' => 'processing_lurah',
                'processed_lurah_at' => now(),
                'processed_lurah_by' => Auth::id(),
                'catatan_lurah' => $request->catatan_lurah,
                'metadata' => array_merge($psu->metadata ?? [], [
                    'ttd_lurah_disposisi' => $ttdLurahPath,
                    'instruksi_arahan' => $request->instruksi_arahan,
                    'diteruskan_kepada' => $request->diteruskan_kepada,
                    'stempel_kelurahan_disposisi' => $stempelKelurahanPath,
                    'disposisi_processed_at' => now()->toISOString(),
                    'use_manual_signature' => $request->use_manual_signature === 'true',
                    'signature_source' => $request->use_manual_signature === 'true' ? 'manual' :
                                    ($request->ttd_lurah_spesimen ? 'spesimen' : 'fallback'),
                ]),
            ]);

            // Generate signed disposisi document
            $signedDisposisiPath = $this->generateSignedDisposisi($psu);

            // Update status to processed_lurah (ready for Back Office)
            $psu->update(['status' => 'processed_lurah']);

            // Log activity dengan detail lengkap
            $psu->logActivity('process_lurah',
                "Lurah memproses disposisi untuk PSU: {$psu->nomor_surat}",
                [
                    'catatan_lurah' => $request->catatan_lurah,
                    'instruksi_arahan' => $request->instruksi_arahan,
                    'diteruskan_kepada' => $request->diteruskan_kepada,
                    'processed_by' => Auth::user()->name,
                    'ttd_saved' => $ttdLurahPath ? true : false,
                    'ttd_source' => $psu->metadata['signature_source'] ?? 'unknown',
                    'disposisi_signed_file' => $signedDisposisiPath,
                    'stempel_available' => $stempelKelurahanPath ? true : false,
                ]
            );

            // Sync to UserApplication
            $this->syncToUserApplication($psu);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Disposisi Lurah berhasil diproses dan diteruskan ke ' . $request->diteruskan_kepada . '.',
                'data' => [
                    'disposisi_signed_path' => $signedDisposisiPath,
                    'status' => $psu->status,
                    'ttd_source' => $psu->metadata['signature_source'] ?? 'unknown',
                ]
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error processing Lurah disposisi: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Final Processing by Back Office
     * Completes the entire PSU workflow
     */
    public function processBackOffice(Request $request, Psu $psu)
    {
        // Authorization check
        if (!in_array(Auth::user()->role, ['Back Office', 'admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk melakukan proses ini.'
            ], 403);
        }

        // Check if PSU can be processed by Back Office
        if ($psu->status !== 'processed_lurah') {
            return response()->json([
                'success' => false,
                'message' => 'PSU tidak dapat diproses. Status saat ini: ' . $psu->status_text
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'catatan_back_office' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // PERBAIKAN: Get TTD dan Stempel Kelurahan untuk final approval
            $metadata = $psu->metadata ?? [];

            // Prioritas TTD: 1) TTD Lurah dari disposisi, 2) Front Office spesimen
            $ttdKelurahan = null;
            $stempelKelurahan = null;

            // Cek apakah ada TTD Lurah dari disposisi
            if (isset($metadata['ttd_lurah_disposisi']) && Storage::disk('public')->exists($metadata['ttd_lurah_disposisi'])) {
                $ttdKelurahan = $metadata['ttd_lurah_disposisi'];
            } else {
                // Fallback ke Front Office spesimen
                $frontOfficeSpesimen = $this->getSpesimenData('Front Office');
                if ($frontOfficeSpesimen && $frontOfficeSpesimen->file_ttd) {
                    $ttdKelurahan = $frontOfficeSpesimen->file_ttd;
                }
            }

            // Untuk stempel, gunakan spesimen kelurahan
            $frontOfficeSpesimen = $this->getSpesimenData('Front Office');
            if ($frontOfficeSpesimen && $frontOfficeSpesimen->file_stempel) {
                $stempelKelurahan = $frontOfficeSpesimen->file_stempel;
            }

            // Update PSU to completed status dengan TTD dan Stempel Kelurahan
            $updateData = [
                'status' => 'completed',
                'processed_back_office_at' => now(),
                'processed_back_office_by' => Auth::id(),
                // PERBAIKAN: Simpan TTD dan Stempel Kelurahan untuk final PDF
                'ttd_kelurahan' => $ttdKelurahan,
                'stempel_kelurahan' => $stempelKelurahan,
                'approved_kelurahan_at' => now(), // Set waktu approval kelurahan
                'approved_kelurahan_by' => Auth::id(), // Set approver kelurahan
                'catatan_kelurahan' => 'Final approval oleh Back Office setelah proses Lurah',
                // Simpan catatan di metadata
                'metadata' => array_merge($metadata, [
                    'catatan_back_office' => $request->catatan_back_office,
                    'completed_at' => now()->toISOString(),
                    'final_approval_method' => 'back_office_kelurahan_workflow',
                    'ttd_source' => $ttdKelurahan ? 'lurah_disposisi_or_front_office' : 'none',
                    'stempel_source' => $stempelKelurahan ? 'front_office_spesimen' : 'none',
                ]),
            ];

            $psu->update($updateData);

            // Generate final PDF document with Kelurahan signature
            $this->generatePDF($psu);

            // Log completion activity
            $psu->logActivity('complete_psu',
                "Menyelesaikan PSU (Back Office): {$psu->nomor_surat}",
                [
                    'catatan_back_office' => $request->catatan_back_office,
                    'completed_by' => Auth::user()->name,
                    'final_pdf_generated' => true,
                    'workflow_completed' => true,
                    'ttd_kelurahan_added' => $ttdKelurahan ? true : false,
                    'stempel_kelurahan_added' => $stempelKelurahan ? true : false,
                    'approval_method' => 'kelurahan_workflow_back_office',
                ]
            );

            // Archive the documents (optional, simplified)
            $this->archiveDocuments($psu);

            // Sync to UserApplication
            $this->syncToUserApplication($psu);

            DB::commit();

            $message = 'PSU berhasil diselesaikan. Seluruh workflow telah selesai.';
            if ($ttdKelurahan) {
                $message .= ' TTD dan Stempel Kelurahan telah ditambahkan pada dokumen final.';
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error processing Back Office approval: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Back Office spesimen untuk final approval (Updated)
     */
    public function getBackOfficeSpesimen(Request $request, Psu $psu)
    {
        // Authorize: Only Back Office and admin can access
        if (!in_array(Auth::user()->role, ['Back Office', 'admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Get metadata untuk TTD Lurah dari disposisi
        $metadata = $psu->metadata ?? [];
        $ttdLurahDisposisi = $metadata['ttd_lurah_disposisi'] ?? null;

        // Get Front Office spesimen sebagai fallback dan untuk stempel
        $frontOfficeSpesimen = $this->getSpesimenData('Front Office');

        $response = [
            'success' => true,
            'data' => []
        ];

        // Prioritas TTD: 1) TTD Lurah dari disposisi, 2) Front Office spesimen
        if ($ttdLurahDisposisi && Storage::disk('public')->exists($ttdLurahDisposisi)) {
            $response['data']['ttd_kelurahan'] = Storage::url($ttdLurahDisposisi);
            $response['data']['ttd_source'] = 'lurah_disposisi';
            $response['data']['nama_pejabat'] = $psu->lurahProcessor->name ?? 'Lurah';
        } elseif ($frontOfficeSpesimen && $frontOfficeSpesimen->file_ttd) {
            $response['data']['ttd_kelurahan'] = Storage::url($frontOfficeSpesimen->file_ttd);
            $response['data']['ttd_source'] = 'front_office_spesimen';
            $response['data']['nama_pejabat'] = $frontOfficeSpesimen->nama_pejabat ?? 'Front Office';
        } else {
            $response['data']['ttd_kelurahan'] = null;
            $response['data']['ttd_source'] = 'none';
            $response['data']['nama_pejabat'] = 'Pejabat Kelurahan';
        }

        // Stempel Kelurahan dari Front Office spesimen
        if ($frontOfficeSpesimen && $frontOfficeSpesimen->file_stempel) {
            $response['data']['stempel_kelurahan'] = Storage::url($frontOfficeSpesimen->file_stempel);
        } else {
            $response['data']['stempel_kelurahan'] = null;
        }

        // Debug info
        $response['debug'] = [
            'ttd_lurah_disposisi_available' => $ttdLurahDisposisi ? true : false,
            'front_office_spesimen_available' => $frontOfficeSpesimen ? true : false,
            'ttd_lurah_disposisi_path' => $ttdLurahDisposisi,
            'front_office_ttd_path' => $frontOfficeSpesimen ? $frontOfficeSpesimen->file_ttd : null,
            'front_office_stempel_path' => $frontOfficeSpesimen ? $frontOfficeSpesimen->file_stempel : null,
        ];

        return response()->json($response);
    }

    /**
     * Generate nomor agenda untuk Kelurahan
     */
    private function generateNomorAgendaKelurahan()
    {
        $tahun = date('Y');
        $bulan = date('m');

        // Count existing agenda this month menggunakan metadata
        $lastNumber = Psu::whereYear('received_kelurahan_at', $tahun)
                         ->whereMonth('received_kelurahan_at', $bulan)
                         ->whereNotNull('received_kelurahan_at')
                         ->count();

        $noAgenda = sprintf('%03d', $lastNumber + 1);

        return "AG.{$noAgenda}/KEL/{$bulan}/{$tahun}";
    }

    public function previewTandaTerima(Psu $psu)
    {
        try {
            // Authorization check
            $this->authorizeAccess($psu);

            // Check if tanda terima exists
            if (!$psu->surat_tanda_terima || !Storage::disk('public')->exists($psu->surat_tanda_terima)) {
                return redirect()->back()->with('error', 'Surat Tanda Terima tidak ditemukan.');
            }

            // Get metadata for generating preview
            $metadata = $psu->metadata ?? [];
            $nomorAgenda = $metadata['nomor_agenda_kelurahan'] ?? 'AG.001/KEL/06/2025';

            // Get Front Office spesimen data
            $frontOfficeSpesimen = $this->getSpesimenData('Front Office');
            $kelurahanSpesimen = $this->getSpesimenData('Front Office'); // Atau 'Kelurahan' untuk stempel

            $tandaTerimaData = [
                'nomor_agenda' => $nomorAgenda,
                'tanggal_terima' => $psu->received_kelurahan_at ? $psu->received_kelurahan_at->format('d F Y') : now()->format('d F Y'),
                'jam_terima' => $psu->received_kelurahan_at ? $psu->received_kelurahan_at->format('H:i') : now()->format('H:i'),
                'psu' => $psu,
                'petugas' => $psu->frontOffice->name ?? 'yamuarcy',
                'kelurahan' => 'Kelurahan Surabaya',
                'ttd_front_office' => $frontOfficeSpesimen->file_ttd ?? null,
                'stempel_kelurahan' => $kelurahanSpesimen->file_stempel ?? null,
                'front_office_name' => $psu->frontOffice->name ?? 'yamuarcy',
                'jabatan_petugas' => 'Front Office'
            ];

            // Generate PDF preview
            $pdf = PDF::loadView('Psu.TandaTerima', $tandaTerimaData);

            // Clean nomor surat for filename
            $cleanNomorSurat = str_replace(['/', '\\'], '_', $psu->nomor_surat);
            $fileName = 'preview_tanda_terima_' . $cleanNomorSurat . '.pdf';

            return $pdf->stream($fileName);

        } catch (\Exception $e) {
            Log::error('Error previewing Tanda Terima: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat preview Tanda Terima: ' . $e->getMessage());
        }
    }

    /**
     * Generate Surat Tanda Terima untuk Pemohon
     */
    private function generateTandaTerima($psu, $nomorAgenda, $ttdFrontOfficePath = null, $stempelKelurahanPath = null)
    {
        try {
            // Get Front Office spesimen if not provided
            if (!$ttdFrontOfficePath || !$stempelKelurahanPath) {
                $frontOfficeSpesimen = $this->getSpesimenData('Front Office');
                $kelurahanSpesimen = $this->getSpesimenData('Front Office'); // Atau 'Kelurahan' untuk stempel

                // PERBAIKAN: Kirim path file, bukan URL
                $ttdFrontOfficePath = $ttdFrontOfficePath ?: $frontOfficeSpesimen->file_ttd ?? null;
                $stempelKelurahanPath = $stempelKelurahanPath ?: $kelurahanSpesimen->file_stempel ?? null;
            }

            $tandaTerimaData = [
                'nomor_agenda' => $nomorAgenda,
                'tanggal_terima' => now()->format('d F Y'),
                'jam_terima' => now()->format('H:i'),
                'psu' => $psu,
                'petugas' => Auth::user()->name,
                'kelurahan' => 'Kelurahan Surabaya',
                // PERBAIKAN: Kirim file path, bukan URL
                'ttd_front_office' => $ttdFrontOfficePath,
                'stempel_kelurahan' => $stempelKelurahanPath,
                'front_office_name' => Auth::user()->name,
                'jabatan_petugas' => 'Front Office'
            ];

            // Generate PDF Tanda Terima
            $pdf = PDF::loadView('Psu.TandaTerima', $tandaTerimaData);

            $fileName = 'tanda_terima_' . str_replace(['/', '\\'], '_', $psu->nomor_surat) . '_' . time() . '.pdf';
            $filePath = 'psu/tanda_terima/' . $fileName;

            Storage::disk('public')->put($filePath, $pdf->output());

            // Update PSU dengan file tanda terima dan status
            $psu->update([
                'surat_tanda_terima' => $filePath,
                'received_kelurahan_at' => now(),
                'status' => 'pending_kelurahan'
            ]);

            return [
                'file_path' => $filePath,
                'file_url' => Storage::url($filePath),
                'data' => $tandaTerimaData
            ];

        } catch (Exception $e) {
            Log::error('Error generating Tanda Terima: ' . $e->getMessage());
            throw $e;
        }
    }

    public function previewDisposisi(Psu $psu)
    {
        try {
            // Authorization check - hanya untuk Front Office, Lurah, Back Office
            if (!in_array(Auth::user()->role, ['Front Office', 'Lurah', 'Back Office', 'admin'])) {
                abort(403, 'Anda tidak memiliki akses untuk melihat disposisi ini.');
            }

            // Check if disposisi exists
            if (!$psu->surat_disposisi || !Storage::disk('public')->exists($psu->surat_disposisi)) {
                return redirect()->back()->with('error', 'Disposisi tidak ditemukan.');
            }

            // Get metadata for generating preview
            $metadata = $psu->metadata ?? [];
            $nomorAgenda = $metadata['nomor_agenda_kelurahan'] ?? 'AG.001/KEL/06/2025';

            // Check if this is signed disposisi or blank disposisi
            $isSignedDisposisi = $psu->hasSignedDisposisiLurah();

            if ($isSignedDisposisi) {
                // Generate signed disposisi preview
                $disposisiData = [
                    'nomor_agenda' => $nomorAgenda,
                    'tanggal_disposisi' => $psu->processed_lurah_at ? $psu->processed_lurah_at->format('d F Y') : now()->format('d F Y'),
                    'psu' => $psu,
                    'catatan_lurah' => $psu->catatan_lurah ?? 'Mohon diproses sesuai ketentuan yang berlaku. Terima kasih.',
                    'ttd_lurah' => $metadata['ttd_lurah_disposisi'] ?? null,
                    'diteruskan_kepada' => $metadata['diteruskan_kepada'] ?? 'Back Office',
                    'lurah_name' => $psu->lurahProcessor->name ?? 'Lurah',
                    'surat_dari' => $psu->nama_lengkap,
                    'nomor_surat' => $psu->nomor_surat,
                    'tanggal_surat' => $psu->created_at->format('d F Y'),
                    'perihal' => $psu->hal,
                ];

                // Generate PDF preview
                $pdf = PDF::loadView('Psu.DisposisiLurahSigned', $disposisiData);
            } else {
                // Generate blank disposisi preview
                $disposisiData = [
                    'nomor_agenda' => $nomorAgenda,
                    'tanggal_disposisi' => now()->format('d F Y'),
                    'psu' => $psu,
                    'dari' => 'Front Office Kelurahan',
                    'kepada' => 'Lurah',
                    'surat_dari' => $psu->nama_lengkap,
                    'nomor_surat' => $psu->nomor_surat,
                    'tanggal_surat' => $psu->created_at->format('d F Y'),
                    'perihal' => $psu->hal,
                    // Template checkbox options sesuai gambar
                    'options_diteruskan' => [
                        'Back Office',
                        'Sekretariat',
                        'Bagian Administrasi',
                        'Dsrtnya ……….'
                    ],
                    'options_hormat' => [
                        'Tanggapan dan Saran',
                        'Proses lebih lanjut',
                        'Koordinasi/konfirmasikan',
                        '……………………………….'
                    ]
                ];

                // Generate PDF preview
                $pdf = PDF::loadView('Psu.DisposisiLurah', $disposisiData);
            }

            // Clean nomor surat for filename
            $cleanNomorSurat = str_replace(['/', '\\'], '_', $psu->nomor_surat);
            $fileName = 'preview_disposisi_' . $cleanNomorSurat . '.pdf';

            return $pdf->stream($fileName);

        } catch (\Exception $e) {
            Log::error('Error previewing Disposisi: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat preview Disposisi: ' . $e->getMessage());
        }
    }

    /**
     * Generate Lembar Disposisi untuk Lurah (blank form)
     */
    private function generateDisposisiLurah($psu, $nomorAgenda)
    {
        try {
            $disposisiData = [
                'nomor_agenda' => $nomorAgenda,
                'tanggal_disposisi' => now()->format('d F Y'),
                'psu' => $psu,
                'dari' => 'Front Office Kelurahan',
                'kepada' => 'Lurah',
                'surat_dari' => $psu->nama_lengkap,
                'nomor_surat' => $psu->nomor_surat,
                'tanggal_surat' => $psu->created_at->format('d F Y'),
                'perihal' => $psu->hal,
                // Template checkbox options sesuai gambar
                'options_diteruskan' => [
                    'MMMMMMMMMMMMMMM',
                    'MMMMMMMMMMMMMMM',
                    'MMMMMMMMMMMMMMM',
                    'Dsrtnya ……….'
                ],
                'options_hormat' => [
                    'Tanggapan dan Saran',
                    'Proses lebih lanjut',
                    'Koordinasi/konfirmasikan',
                    '……………………………….'
                ]
            ];

            // Generate PDF Disposisi (blank form sesuai template)
            $pdf = PDF::loadView('Psu.DisposisiLurah', $disposisiData);

            $fileName = 'disposisi_lurah_' . str_replace(['/', '\\'], '_', $psu->nomor_surat) . '_' . time() . '.pdf';
            $filePath = 'psu/disposisi/' . $fileName;

            Storage::disk('public')->put($filePath, $pdf->output());

            // Update PSU dengan file disposisi - menggunakan field yang ada
            $psu->update(['surat_disposisi' => $filePath]);

            return [
                'file_path' => $filePath,
                'file_url' => Storage::url($filePath),
                'data' => $disposisiData
            ];

        } catch (Exception $e) {
            Log::error('Error generating Disposisi Lurah: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate signed disposisi after Lurah process
     */
    private function generateSignedDisposisi($psu)
    {
        try {
            $metadata = $psu->metadata ?? [];
            $nomor_agenda = $metadata['nomor_agenda_kelurahan'] ?? 'N/A';

            // Get spesimen data untuk TTD dan Stempel
            $frontOfficeSpesimen = $this->getSpesimenData('Front Office');

            $disposisiData = [
                'nomor_agenda' => $nomor_agenda,
                'tanggal_disposisi' => $psu->processed_lurah_at ? $psu->processed_lurah_at->format('d F Y') : now()->format('d F Y'),
                'psu' => $psu,
                'catatan_lurah' => $psu->catatan_lurah,
                'ttd_lurah' => $metadata['ttd_lurah_disposisi'] ?? ($frontOfficeSpesimen ? $frontOfficeSpesimen->file_ttd : null),
                'diteruskan_kepada' => $metadata['diteruskan_kepada'] ?? 'Back Office',
                'lurah_name' => Auth::user()->name ?? 'Lurah',
                'surat_dari' => $psu->nama_lengkap,
                'nomor_surat' => $psu->nomor_surat,
                'tanggal_surat' => $psu->created_at->format('d F Y'),
                'perihal' => $psu->hal,
            ];

            // Generate signed PDF Disposisi
            $pdf = PDF::loadView('Psu.DisposisiLurahSigned', $disposisiData);

            $fileName = 'disposisi_signed_' . str_replace(['/', '\\'], '_', $psu->nomor_surat) . '_' . time() . '.pdf';
            $filePath = 'psu/disposisi_signed/' . $fileName;

            Storage::disk('public')->put($filePath, $pdf->output());

            // Update metadata dengan signed disposisi
            $updatedMetadata = array_merge($metadata, [
                'file_disposisi_signed' => $filePath,
                // Ensure stempel is saved in metadata for the view
                'stempel_kelurahan_disposisi' => $frontOfficeSpesimen ? $frontOfficeSpesimen->file_stempel : null,
            ]);
            $psu->update(['metadata' => $updatedMetadata]);

            return $filePath;

        } catch (Exception $e) {
            Log::error('Error generating signed disposisi: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Archive documents for completed PSU (simplified)
     */
    private function archiveDocuments($psu)
    {
        try {
            $metadata = $psu->metadata ?? [];

            $archiveData = [
                'psu_id' => $psu->id,
                'nomor_surat' => $psu->nomor_surat,
                'nomor_agenda' => $metadata['nomor_agenda_kelurahan'] ?? null,
                'pemohon' => $psu->nama_lengkap,
                'files' => [
                    'tanda_terima' => $psu->surat_tanda_terima,
                    'disposisi' => $psu->surat_disposisi,
                    'disposisi_signed' => $metadata['file_disposisi_signed'] ?? null,
                    'psu_final' => $psu->file_pdf,
                ],
                'completed_at' => now(),
                'archived_by' => Auth::id(),
            ];

            // Update metadata dengan archive info
            $updatedMetadata = array_merge($metadata, [
                'archived' => true,
                'archive_data' => $archiveData,
                'archived_at' => now()->toISOString(),
            ]);

            $psu->update(['metadata' => $updatedMetadata]);

            Log::info("Documents archived for PSU ID: {$psu->id}");

        } catch (Exception $e) {
            Log::error('Error archiving documents: ' . $e->getMessage());
            // Don't throw, just log the error
        }
    }

    // ========================================
    // PDF METHODS
    // ========================================

    /**
     * Preview PDF
     */
    public function previewPDF(Psu $psu)
    {
        try {
            $pdf = PDF::loadView('Psu.PDF', compact('psu'));
            $cleanNomorSurat = str_replace(['/', '\\'], '_', $psu->nomor_surat);
            $fileName = 'preview_psu_' . $cleanNomorSurat . '.pdf';

            return $pdf->stream($fileName);
        } catch (Exception $e) {
            Log::error('Error previewing PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat preview PDF: ' . $e->getMessage());
        }
    }

    /**
     * Download PDF
     */
    public function downloadPDF(Psu $psu)
    {
        // Authorization check
        if (Auth::user()->role === 'user' && $psu->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengunduh data ini.');
        }

        if (!$psu->canDownloadPDF()) {
            return redirect()->back()
                           ->with('error', 'PDF hanya dapat diunduh setelah proses persetujuan selesai.');
        }

        try {
            if (!$psu->file_pdf || !Storage::disk('public')->exists($psu->file_pdf)) {
                $this->generatePDF($psu);
            }

            $cleanNomorSurat = str_replace(['/', '\\'], '_', $psu->nomor_surat);
            $downloadName = 'PSU_' . $cleanNomorSurat . '.pdf';

            // Track download
            $this->trackDownload($psu);

            return Storage::disk('public')->download($psu->file_pdf, $downloadName);
        } catch (Exception $e) {
            Log::error('Error downloading PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengunduh PDF: ' . $e->getMessage());
        }
    }

    // ========================================
    // ADMIN UTILITY METHODS
    // ========================================

    /**
     * Sync all existing PSU data to UserApplication
     */
    public function syncAllToUserApplication()
    {
        try {
            $psuRecords = Psu::all();
            $synced = 0;
            $errors = 0;

            foreach ($psuRecords as $psu) {
                try {
                    $this->syncToUserApplication($psu);
                    $synced++;
                } catch (Exception $e) {
                    $errors++;
                    Log::error("Error syncing PSU ID {$psu->id}: " . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Sync completed. Synced: {$synced}, Errors: {$errors}",
                'data' => [
                    'synced' => $synced,
                    'errors' => $errors,
                    'total' => $psuRecords->count()
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error during sync: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check data integrity between PSU and UserApplication
     */
    public function checkDataIntegrity()
    {
        try {
            $psuCount = Psu::count();
            $userAppCount = UserApplication::where('jenis_permohonan', 'PSU')->count();

            $missingInUserApp = Psu::whereNotIn('id',
                UserApplication::where('jenis_permohonan', 'PSU')
                               ->pluck('reference_id')
            )->count();

            $orphanedUserApp = UserApplication::where('jenis_permohonan', 'PSU')
                              ->whereNotIn('reference_id', Psu::pluck('id'))
                              ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'psu_count' => $psuCount,
                    'user_application_count' => $userAppCount,
                    'missing_in_user_app' => $missingInUserApp,
                    'orphaned_user_app' => $orphanedUserApp,
                    'is_synced' => ($psuCount === $userAppCount && $missingInUserApp === 0 && $orphanedUserApp === 0)
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking data integrity: ' . $e->getMessage()
            ], 500);
        }
    }

    // ========================================
    // PRIVATE HELPER METHODS
    // ========================================

    /**
     * Apply role-based filtering to query
     */
    private function applyRoleBasedFilter($query)
    {
        $userRole = Auth::user()->role;

        switch ($userRole) {
            case 'user':
                $query->where('user_id', Auth::id());
                break;

            case 'Ketua RT':
                $userRT = Auth::user()->rt;
                $userRW = Auth::user()->rw;
                $query->where('rt', $userRT)
                     ->where('rw', $userRW)
                     ->whereIn('status', [
                         'pending_rt', 'approved_rt', 'pending_rw',
                         'approved_rw', 'rejected_rt', 'rejected_rw'
                     ]);
                break;

            case 'Ketua RW':
                $userRW = Auth::user()->rw;
                $query->where('rw', $userRW)
                     ->whereIn('status', [
                         'approved_rt', 'approved_rw', 'rejected_rw',
                         'pending_kelurahan', 'approved_kelurahan', 'rejected_kelurahan'
                     ]);
                break;

            case 'Lurah':
            case 'Camat':
                $query->whereIn('status', [
                    'approved_rw', 'pending_kelurahan',
                    'approved_kelurahan', 'rejected_kelurahan'
                ]);
                break;

            case 'Front Office':
            case 'Operator':
            case 'admin':
                // No filter - can see all data
                break;

            default:
                // Unknown role - no access
                $query->where('id', 0);
                break;
        }
    }

    /**
     * Get approved count based on final approval level
     */
    private function getApprovedCount($baseQuery)
    {
        $approvedQuery = clone $baseQuery;

        return $approvedQuery->where(function ($query) {
            $query->where('status', 'auto_approved')
                  ->orWhere(function ($subQuery) {
                      $subQuery->where('status', 'approved_rt')
                               ->where('level_akhir', 'rt');
                  })
                  ->orWhere(function ($subQuery) {
                      $subQuery->where('status', 'approved_rw')
                               ->where('level_akhir', 'rw');
                  })
                  ->orWhere('status', 'approved_kelurahan');
        })->count();
    }

    /**
     * Build file info column for DataTable
     */
    private function buildFileInfoColumn($row)
    {
        try {
            $typeIcon = $row->isPSUInternal() ? 'fa-file-check' : 'fa-file-signature';
            $typeColor = $row->isPSUInternal() ? 'text-success' : 'text-primary';

            // PERBAIKAN: Tambahkan indikator workflow untuk kelurahan
            $workflowIndicators = '';
            if ($row->level_akhir === 'kelurahan') {
                $indicators = [];

                // Indikator Tanda Terima
                if ($row->hasBeenReceivedAtKelurahan()) {
                    $indicators[] = '<i class="fas fa-receipt text-success" title="Tanda Terima Tersedia"></i>';
                }

                // Indikator Disposisi
                if ($row->hasDisposisiLurah()) {
                    $indicators[] = '<i class="fas fa-clipboard-list text-info" title="Disposisi Lurah"></i>';
                }

                // Indikator Disposisi Signed
                if ($row->hasSignedDisposisiLurah()) {
                    $indicators[] = '<i class="fas fa-signature text-warning" title="Disposisi Ditandatangani"></i>';
                }

                if (!empty($indicators)) {
                    $workflowIndicators = '<div class="mt-1">' . implode(' ', $indicators) . '</div>';
                }
            }

            return '<div class="d-flex align-items-center">
                        <i class="fas ' . $typeIcon . ' ' . $typeColor . ' mr-2"></i>
                        <div>
                            <div class="font-weight-bold">' . ($row->nomor_surat ?? 'No Number') . '</div>
                            <small class="text-muted">' . ($row->nama_lengkap ?? 'Unknown') . '</small>
                            <br><small class="badge badge-info">' . ($row->ditujukan_kepada_display ?? 'Unknown') . '</small>
                            ' . $workflowIndicators . '
                        </div>
                    </div>';
        } catch (\Exception $e) {
            Log::error('Error building file info column for PSU ID ' . ($row->id ?? 'unknown') . ': ' . $e->getMessage());
            return '<div class="d-flex align-items-center">
                        <i class="fas fa-file text-secondary mr-2"></i>
                        <div>
                            <div class="font-weight-bold">Error loading data</div>
                            <small class="text-muted">Please contact admin</small>
                        </div>
                    </div>';
        }
    }

    /**
     * Build nomor judul column for DataTable
     */
    private function buildNomorJudulColumn($row)
    {
        try {
            $html = '<div class="font-weight-bold">' . ($row->nomor_surat ?? 'No Number') . '</div>';
            $html .= '<div class="text-muted">' . \Str::limit($row->hal ?? 'No Subject', 50) . '</div>';
            $html .= '<small class="badge badge-secondary">' . ($row->sifat ?? 'Unknown') . '</small>';

            if (Auth::user()->role !== 'user') {
                $userName = 'Unknown User';
                try {
                    $userName = $row->user->name ?? 'Unknown User';
                } catch (\Exception $e) {
                    Log::warning('Could not load user name for PSU ID ' . ($row->id ?? 'unknown'));
                }
                $html .= '<br><small class="text-info">Pemohon: ' . $userName . '</small>';
            }

            return $html;
        } catch (\Exception $e) {
            Log::error('Error building nomor judul column for PSU ID ' . ($row->id ?? 'unknown') . ': ' . $e->getMessage());
            return '<div class="font-weight-bold">Error loading data</div>';
        }
    }

    /**
     * Build nama lengkap column for DataTable
     */
    private function buildNamaLengkapColumn($row)
    {
        try {
            $html = '<div class="font-weight-bold">' . ($row->nama_lengkap ?? 'Unknown') . '</div>';
            $html .= '<small class="text-muted">RT ' . sprintf('%02d', $row->rt ?? 0) . ' / RW ' . sprintf('%02d', $row->rw ?? 0) . '</small>';
            return $html;
        } catch (\Exception $e) {
            Log::error('Error building nama lengkap column for PSU ID ' . ($row->id ?? 'unknown') . ': ' . $e->getMessage());
            return '<div class="font-weight-bold">Error loading data</div>';
        }
    }

    /**
     * Build workflow column for DataTable
     */
    private function buildWorkflowColumn($row)
    {
        try {
            $progress = $row->workflow_progress;
            $html = '<div class="workflow-steps">';

            // Auto approved case
            if ($progress['auto_approved']) {
                $html .= '<span class="badge badge-success">
                            <i class="fas fa-check-double"></i> Auto Approved
                        </span>';
                $html .= '</div>';
                return $html;
            }

            // Step 1: Submitted
            $html .= '<span class="badge ' . ($progress['submitted'] ? 'badge-success' : 'badge-secondary') . ' mr-1 mb-1">
                        <i class="fas fa-file-upload"></i> Diajukan
                    </span>';

            // Step 2: RT Approved (if needed)
            if ($progress['needs_rt']) {
                $rtBadgeClass = 'badge-secondary';
                if ($progress['rt_approved'] || ($row->status === 'completed' && $row->level_akhir === 'rt')) {
                    $rtBadgeClass = 'badge-success';
                }

                $html .= '<span class="badge ' . $rtBadgeClass . ' mr-1 mb-1">
                            <i class="fas fa-check"></i> RT
                        </span>';
            }

            // Step 3: RW Approved (if needed)
            if ($progress['needs_rw']) {
                $rwBadgeClass = 'badge-secondary';
                if ($progress['rw_approved'] || ($row->status === 'completed' && $row->level_akhir === 'rw')) {
                    $rwBadgeClass = 'badge-success';
                }

                $html .= '<span class="badge ' . $rwBadgeClass . ' mr-1 mb-1">
                            <i class="fas fa-check-double"></i> RW
                        </span>';
            }

            // Step 4: Kelurahan Workflow (if needed)
            if ($progress['needs_kelurahan']) {
                // Sub-step: Received at Kelurahan
                $receivedBadgeClass = 'badge-secondary';
                if ($row->hasBeenReceivedAtKelurahan()) {
                    $receivedBadgeClass = 'badge-info';
                }

                $html .= '<span class="badge ' . $receivedBadgeClass . ' mr-1 mb-1">
                            <i class="fas fa-inbox"></i> FO
                        </span>';

                // Sub-step: Lurah Disposisi
                $lurahBadgeClass = 'badge-secondary';
                if ($row->hasSignedDisposisiLurah()) {
                    $lurahBadgeClass = 'badge-warning';
                } elseif ($row->hasDisposisiLurah()) {
                    $lurahBadgeClass = 'badge-info';
                }

                $html .= '<span class="badge ' . $lurahBadgeClass . ' mr-1 mb-1">
                            <i class="fas fa-user-tie"></i> Lurah
                        </span>';

                // Sub-step: Back Office Final
                $backOfficeBadgeClass = 'badge-secondary';
                if ($row->status === 'completed' && $row->level_akhir === 'kelurahan') {
                    $backOfficeBadgeClass = 'badge-success';
                } elseif (in_array($row->status, ['processed_lurah', 'processing_back_office'])) {
                    $backOfficeBadgeClass = 'badge-warning';
                }

                $html .= '<span class="badge ' . $backOfficeBadgeClass . ' mr-1 mb-1">
                            <i class="fas fa-check-circle"></i> BO
                        </span>';
            }

            $html .= '</div>';
            return $html;
        } catch (\Exception $e) {
            Log::error('Error building workflow column for PSU ID ' . ($row->id ?? 'unknown') . ': ' . $e->getMessage());
            return '<div class="workflow-steps">
                        <span class="badge badge-danger">
                            <i class="fas fa-exclamation-triangle"></i> Error
                        </span>
                    </div>';
        }
    }

    /**
     * Build actions column for DataTable
     */
    private function buildActionsColumn($row, $userRole, $viewType)
    {
        $buttons = [];

        // View button
        $buttons[] = '<a href="' . route('psu.show', $row->id) . '"
                        class="btn btn-info btn-sm mb-1"
                        title="Lihat Detail">
                        <i class="fas fa-eye"></i> Detail
                    </a>';

        // Edit button
        if ($userRole === 'user' && $row->user_id === Auth::id() && $row->canBeEdited()) {
            $buttons[] = '<a href="' . route('psu.edit', $row->id) . '"
                            class="btn btn-warning btn-sm mb-1"
                            title="Edit">
                            <i class="fas fa-pencil-alt"></i> Edit
                        </a>';
        }

        // Preview PDF button
        if ($row->canPreviewPDF()) {
            $buttons[] = '<a href="' . route('psu.preview-pdf', $row->id) . '"
                            class="btn btn-secondary btn-sm mb-1"
                            title="Preview PDF"
                            target="_blank">
                            <i class="fas fa-eye"></i> Preview
                        </a>';
        }

        // Download PDF button
        if ($row->canDownloadPDF()) {
            $buttons[] = '<a href="' . route('psu.download-pdf', $row->id) . '"
                            class="btn btn-success btn-sm mb-1"
                            title="Download PDF">
                            <i class="fas fa-download"></i> Download
                        </a>';
        }

        // PERBAIKAN: Tanda Terima button (jika sudah ada file tanda terima)
        if ($row->surat_tanda_terima && Storage::disk('public')->exists($row->surat_tanda_terima)) {
            $buttons[] = '<a href="' . route('psu.preview-tanda-terima', $row->id) . '"
                            class="btn btn-info btn-sm mb-1"
                            title="Download Tanda Terima"
                            target="_blank">
                            <i class="fas fa-receipt"></i> Tanda Terima
                        </a>';
        }

        if (in_array($userRole, ['Front Office', 'Lurah', 'Back Office']) &&
            $row->surat_disposisi && Storage::disk('public')->exists($row->surat_disposisi)) {
            $buttons[] = '<a href="' . route('psu.preview-disposisi', $row->id) . '"
                            class="btn btn-warning btn-sm mb-1"
                            title="Preview Disposisi"
                            target="_blank">
                            <i class="fas fa-clipboard-list"></i> Disposisi
                        </a>';
        }

        // Approval buttons
        $buttons = array_merge($buttons, $this->getApprovalButtons($row, $userRole));

        // Delete button
        if (($userRole === 'user' && $row->user_id === Auth::id() && $row->canBeEdited()) ||
            in_array($userRole, ['admin'])) {
            $buttons[] = '<form action="' . route('psu.destroy', $row->id) . '"
                            method="POST" class="d-inline">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="button" class="btn btn-danger btn-sm btn-delete mb-1"
                                    data-name="' . $row->nama_lengkap . '"
                                    title="Delete">
                                    <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>';
        }

        return '<div class="d-flex flex-column gap-1">' . implode('', $buttons) . '</div>';
    }

    /**
     * Get approval buttons based on user role and PSU status
     */
    private function getApprovalButtons($row, $userRole)
    {
        $buttons = [];

        // RT Approve/Reject buttons
        if ($userRole === 'Ketua RT' && $row->canBeApprovedByRT() &&
            Auth::user()->rt == $row->rt && Auth::user()->rw == $row->rw) {

            $buttons[] = '<button type="button" class="btn btn-success btn-sm btn-approve-rt mb-1"
                            data-id="' . $row->id . '"
                            data-name="' . $row->nama_lengkap . '"
                            title="Setujui sebagai RT">
                            <i class="fas fa-check"></i> Approve RT
                        </button>';

            $buttons[] = '<button type="button" class="btn btn-danger btn-sm btn-reject-rt mb-1"
                            data-id="' . $row->id . '"
                            data-name="' . $row->nama_lengkap . '"
                            title="Tolak sebagai RT">
                            <i class="fas fa-times"></i> Reject RT
                        </button>';
        }

        // RW Approve/Reject buttons - PERBAIKAN: Termasuk yang dibuat oleh Ketua RT ke Kelurahan
        if ($userRole === 'Ketua RW' && $row->canBeApprovedByRW() && Auth::user()->rw == $row->rw) {
            $buttons[] = '<button type="button" class="btn btn-success btn-sm btn-approve-rw mb-1"
                            data-id="' . $row->id . '"
                            data-name="' . $row->nama_lengkap . '"
                            title="Setujui sebagai RW">
                            <i class="fas fa-check"></i> Approve RW
                        </button>';

            $buttons[] = '<button type="button" class="btn btn-danger btn-sm btn-reject-rw mb-1"
                            data-id="' . $row->id . '"
                            data-name="' . $row->nama_lengkap . '"
                            title="Tolak sebagai RW">
                            <i class="fas fa-times"></i> Reject RW
                        </button>';
        }

        // Front Office buttons - untuk yang level_akhir kelurahan
        if ($userRole === 'Front Office' && $row->level_akhir === 'kelurahan') {
            if ($row->status === 'approved_rw') {
                $buttons[] = '<button type="button" class="btn btn-primary btn-sm btn-receive-kelurahan mb-1"
                                data-id="' . $row->id . '"
                                data-name="' . $row->nama_lengkap . '"
                                title="Terima di Kelurahan">
                                <i class="fas fa-inbox"></i> Terima di Kelurahan
                            </button>';
            }
        }

        // Lurah buttons - untuk yang sudah diterima Front Office
        if ($userRole === 'Lurah' && $row->level_akhir === 'kelurahan') {
            if ($row->status === 'pending_kelurahan') {
                $buttons[] = '<button type="button" class="btn btn-warning btn-sm btn-process-lurah mb-1"
                                data-id="' . $row->id . '"
                                data-name="' . $row->nama_lengkap . '"
                                title="Proses Disposisi">
                                <i class="fas fa-user-tie"></i> Proses Disposisi
                            </button>';
            }
        }

        // Back Office buttons - untuk yang sudah diproses Lurah
        if ($userRole === 'Back Office' && $row->level_akhir === 'kelurahan') {
            if ($row->status === 'processed_lurah') {
                $buttons[] = '<button type="button" class="btn btn-success btn-sm btn-approve-back-office mb-1"
                                data-id="' . $row->id . '"
                                data-name="' . $row->nama_lengkap . '"
                                title="Proses Final">
                                <i class="fas fa-check-circle"></i> Proses Final
                            </button>';
            }
        }

        return $buttons;
    }

    /**
     * Get RT-RW mapping
     */
    private function getRwRtMapping()
    {
        return [
            '01' => 6,  '02' => 7,  '03' => 10, '04' => 8,  '05' => 10,
            '06' => 4,  '07' => 4,  '08' => 3,  '09' => 8,  '10' => 3,
        ];
    }

    /**
     * Generate available RW options
     */
    private function generateAvailableRW($rwRtMapping)
    {
        $availableRW = [];
        foreach ($rwRtMapping as $rw => $rtCount) {
            $availableRW[] = [
                'value' => $rw,
                'label' => 'RW ' . $rw,
                'rt_count' => $rtCount
            ];
        }
        return $availableRW;
    }

    /**
     * Generate available RT options
     */
    private function generateAvailableRT()
    {
        $availableRT = [];
        for ($i = 1; $i <= 10; $i++) {
            $rt = $i == 10 ? '10' : sprintf('%02d', $i);
            $availableRT[] = [
                'value' => $rt,
                'label' => 'RT ' . $rt
            ];
        }
        return $availableRT;
    }

    /**
     * Get warga by RT via AJAX (untuk PSU Internal)
     */
    public function getWargaByRT(Request $request)
    {
        try {
            $rt = $request->input('rt');
            $rw = $request->input('rw');

            if (!$rt || !$rw) {
                return response()->json([
                    'success' => false,
                    'message' => 'RT dan RW harus diisi'
                ], 400);
            }

            $warga = User::where('role', 'user')
                        ->where('rt', $rt)
                        ->where('rw', $rw)
                        ->select('id', 'name', 'rt', 'rw', 'address')
                        ->orderBy('name')
                        ->get();

            return response()->json([
                'success' => true,
                'data' => $warga
            ]);

        } catch (Exception $e) {
            Log::error('Error getting warga by RT: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading warga: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all warga in RW via AJAX (untuk PSU Internal)
     */
    public function getWargaByRW(Request $request)
    {
        try {
            $rw = $request->input('rw');

            if (!$rw) {
                return response()->json([
                    'success' => false,
                    'message' => 'RW harus diisi'
                ], 400);
            }

            $warga = User::where('role', 'user')
                        ->where('rw', $rw)
                        ->select('id', 'name', 'rt', 'rw', 'address')
                        ->orderBy('rt')
                        ->orderBy('name')
                        ->get();

            return response()->json([
                'success' => true,
                'data' => $warga
            ]);

        } catch (Exception $e) {
            Log::error('Error getting warga by RW: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading warga: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get RT list in specific RW via AJAX
     */
    public function getRTInRW(Request $request)
    {
        try {
            $rw = $request->input('rw');

            if (!$rw) {
                return response()->json([
                    'success' => false,
                    'message' => 'RW harus diisi'
                ], 400);
            }

            $rtList = User::where('rw', $rw)
                        ->whereNotNull('rt')
                        ->distinct()
                        ->pluck('rt')
                        ->sort()
                        ->values();

            return response()->json([
                'success' => true,
                'data' => $rtList
            ]);

        } catch (Exception $e) {
            Log::error('Error getting RT in RW: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading RT: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate PSU request
     */
    private function validatePsuRequest(Request $request)
    {
        $rules = [
            'nama_lengkap' => 'required|string|max:255',
            'alamat' => 'required|string',
            'pekerjaan' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|string|max:100',
            'status_perkawinan' => 'required|string|max:100',
            'kewarganegaraan' => 'required|string|max:100',
            'nomor_kk' => 'required|string|max:20',
            'rt' => 'required|string|max:3',
            'rw' => 'required|string|max:3',
            'ditujukan_kepada' => 'required|in:warga_rt,warga_rw,rt,rw,kelurahan',
            'target_warga' => 'nullable|exists:users,id', // Untuk PSU Internal
            'bulan' => 'required|integer|min:1|max:12',
            'sifat' => 'required|in:Penting,Biasa,Segera,Rahasia',
            'hal' => 'required|string',
            'isi_surat' => 'required|string',
            'tujuan_eksternal' => 'nullable|string',
            'ttd_pemohon' => 'required|string',
            'file_lampiran.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];

        // Additional validation untuk PSU Internal
        $validator = Validator::make($request->all(), $rules);

        $validator->after(function ($validator) use ($request) {
            // Validasi target_warga untuk PSU Internal
            if (in_array($request->ditujukan_kepada, ['warga_rt', 'warga_rw'])) {
                // Untuk PSU Internal, target_warga boleh kosong (berarti target semua warga)
                // Jika diisi, harus valid user ID
                if ($request->target_warga) {
                    $targetUser = User::find($request->target_warga);
                    if (!$targetUser) {
                        $validator->errors()->add('target_warga', 'Target warga tidak ditemukan.');
                    } elseif ($targetUser->role !== 'user') {
                        $validator->errors()->add('target_warga', 'Target harus merupakan warga (role user).');
                    }
                }
            }
        });

        return $validator;
    }

    /**
     * Validate PSU update request
     */
    private function validatePsuUpdateRequest(Request $request, Psu $psu)
    {
        $validationRules = [
            'nama_lengkap' => 'required|string|max:255',
            'alamat' => 'required|string',
            'pekerjaan' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|string|max:100',
            'status_perkawinan' => 'required|string|max:100',
            'kewarganegaraan' => 'required|string|max:100',
            'nomor_kk' => 'required|string|max:20',
            'rt' => 'required|string|max:3',
            'rw' => 'required|string|max:3',
            'ditujukan_kepada' => 'required|in:warga_rt,warga_rw,rt,rw,kelurahan',
            'nama_ketua_rt' => 'nullable|string|max:255',
            'nama_ketua_rw' => 'nullable|string|max:255',
            'bulan' => 'required|integer|min:1|max:12',
            'sifat' => 'required|in:Penting,Biasa,Segera,Rahasia',
            'hal' => 'required|string',
            'isi_surat' => 'required|string',
            'tujuan_internal' => 'nullable|in:rt,rw,kelurahan,kecamatan',
            'tujuan_eksternal' => 'nullable|string',
            'file_lampiran.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];

        // TTD pemohon hanya wajib jika belum ada TTD sebelumnya
        if (!$psu->hasPemohonSignature()) {
            $validationRules['ttd_pemohon'] = 'required|string';
        } else {
            $validationRules['ttd_pemohon'] = 'nullable|string';
        }

        return Validator::make($request->all(), $validationRules);
    }

    /**
     * Handle file uploads
     */
    private function handleFileUploads(Request $request)
    {
        $fileLampiran = [];
        if ($request->hasFile('file_lampiran')) {
            foreach ($request->file('file_lampiran') as $file) {
                $fileName = 'lampiran_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('psu/lampiran', $fileName, 'public');
                $fileLampiran[] = $filePath;
            }
        }
        return $fileLampiran;
    }

    /**
     * Determine status and level based on target
     */
    private function determineStatusAndLevel($ditujuanKepada)
    {
        if (in_array($ditujuanKepada, ['warga_rt', 'warga_rw'])) {
            return [
                'status' => 'auto_approved',
                'level_akhir' => 'auto_approved'
            ];
        }

        $levelMapping = [
            'rt' => 'rt',
            'rw' => 'rw',
            'kelurahan' => 'kelurahan'
        ];

        return [
            'status' => 'pending_rt',
            'level_akhir' => $levelMapping[$ditujuanKepada] ?? 'rt'
        ];
    }

    private function determinePsuTypeAndTarget($request, $user)
    {
        $data = [
            'status' => 'pending_rt',
            'level_akhir' => 'rt',
            'nama_ketua_rt' => null,
            'nama_ketua_rw' => null,
            'tujuan_internal' => null,
            'target_type' => null,
            'target_rt' => null,
            'target_rw' => null,
            'target_warga_id' => null,
            'target_warga_name' => null,
            'metadata' => null
        ];

        switch ($request->ditujukan_kepada) {
            case 'warga_rt':
                // PSU Internal RT - Auto Approved dengan TTD Ketua RT
                $data['status'] = 'completed';
                $data['level_akhir'] = 'auto_approved';
                $data['tujuan_internal'] = 'rt';

                // Get nama ketua RT
                if ($user->role === 'Ketua RT') {
                    $data['nama_ketua_rt'] = $user->name;
                } else {
                    $ketuaRT = User::where('role', 'Ketua RT')
                                ->where('rt', $request->rt)
                                ->where('rw', $request->rw)
                                ->first();
                    $data['nama_ketua_rt'] = $ketuaRT ? $ketuaRT->name : "Ketua RT {$request->rt}";
                }

                // Set data target
                if ($request->target_warga) {
                    $targetWarga = User::find($request->target_warga);
                    if ($targetWarga) {
                        $data['target_type'] = 'individual';
                        $data['target_rt'] = $targetWarga->rt;
                        $data['target_rw'] = $targetWarga->rw;
                        $data['target_warga_id'] = $targetWarga->id;
                        $data['target_warga_name'] = $targetWarga->name;

                        $data['metadata'] = [
                            'psu_type' => 'internal',
                            'creator_role' => $user->role,
                            'individual_target' => true
                        ];
                    }
                } else {
                    $data['target_type'] = 'semua_rt';
                    $data['target_rt'] = $request->rt;
                    $data['target_rw'] = $request->rw;

                    $data['metadata'] = [
                        'psu_type' => 'internal',
                        'creator_role' => $user->role,
                        'mass_target' => true
                    ];
                }
                break;

            case 'warga_rw':
                // PSU Internal RW - Auto Approved dengan TTD Ketua RW
                $data['status'] = 'completed';
                $data['level_akhir'] = 'auto_approved';
                $data['tujuan_internal'] = 'rw';

                // Get nama ketua RW
                if ($user->role === 'Ketua RW') {
                    $data['nama_ketua_rw'] = $user->name;
                } else {
                    $ketuaRW = User::where('role', 'Ketua RW')
                                ->where('rw', $request->rw)
                                ->first();
                    $data['nama_ketua_rw'] = $ketuaRW ? $ketuaRW->name : "Ketua RW {$request->rw}";
                }

                // Set data target
                if ($request->target_warga) {
                    $targetWarga = User::find($request->target_warga);
                    if ($targetWarga) {
                        $data['target_type'] = 'individual';
                        $data['target_rt'] = $targetWarga->rt;
                        $data['target_rw'] = $targetWarga->rw;
                        $data['target_warga_id'] = $targetWarga->id;
                        $data['target_warga_name'] = $targetWarga->name;

                        $data['metadata'] = [
                            'psu_type' => 'internal',
                            'creator_role' => $user->role,
                            'individual_target' => true
                        ];
                    }
                } else {
                    $data['target_type'] = 'semua_rw';
                    $data['target_rw'] = $request->rw;

                    $data['metadata'] = [
                        'psu_type' => 'internal',
                        'creator_role' => $user->role,
                        'mass_target' => true
                    ];
                }
                break;

            case 'rt':
                // PSU External to RT
                $data['status'] = 'pending_rt';
                $data['level_akhir'] = 'rt';
                $data['tujuan_internal'] = 'rt';

                $ketuaRT = User::where('role', 'Ketua RT')
                            ->where('rt', $user->rt)
                            ->where('rw', $user->rw)
                            ->first();
                $data['nama_ketua_rt'] = $ketuaRT ? $ketuaRT->name : "Ketua RT {$user->rt}";
                break;

            case 'rw':
                // PSU External to RW
                $data['status'] = 'pending_rt';
                $data['level_akhir'] = 'rw';
                $data['tujuan_internal'] = 'rw';

                $ketuaRW = User::where('role', 'Ketua RW')
                            ->where('rw', $user->rw)
                            ->first();
                $data['nama_ketua_rw'] = $ketuaRW ? $ketuaRW->name : "Ketua RW {$user->rw}";
                break;

            case 'kelurahan':
                // PERBAIKAN: PSU External to Kelurahan
                $data['level_akhir'] = 'kelurahan';
                $data['tujuan_internal'] = 'kelurahan';

                if ($user->role === 'Ketua RW') {
                    // Jika dibuat oleh Ketua RW, langsung masuk Front Office
                    $data['status'] = 'approved_rw'; // Langsung approved RW karena dibuat oleh Ketua RW

                    // Set approval RW otomatis
                    $data['metadata'] = [
                        'psu_type' => 'external',
                        'creator_role' => $user->role,
                        'auto_approved_rw' => true,
                        'auto_approved_reason' => 'Dibuat langsung oleh Ketua RW'
                    ];
                } elseif ($user->role === 'Ketua RT') {
                    // Jika dibuat oleh Ketua RT, auto approve RT dulu lalu pending RW
                    $data['status'] = 'approved_rt'; // Auto approve RT, lanjut ke RW

                    // Set approval RT otomatis
                    $data['metadata'] = [
                        'psu_type' => 'external',
                        'creator_role' => $user->role,
                        'auto_approved_rt' => true,
                        'auto_approved_reason' => 'Dibuat langsung oleh Ketua RT'
                    ];

                    // Get nama ketua RT dan RW
                    $data['nama_ketua_rt'] = $user->name;

                    $ketuaRW = User::where('role', 'Ketua RW')
                                ->where('rw', $user->rw)
                                ->first();
                    $data['nama_ketua_rw'] = $ketuaRW ? $ketuaRW->name : "Ketua RW {$user->rw}";
                } else {
                    // Jika dibuat oleh user biasa, masuk workflow normal RT → RW → Kelurahan
                    $data['status'] = 'pending_rt';
                }
                break;
        }

        return $data;
    }

    /**
     * Save signature to storage
     */
    private function saveSignature($signatureData, $type)
    {
        try {
            // Extract base64 data
            if (strpos($signatureData, 'data:image/png;base64,') === 0) {
                $signatureData = str_replace('data:image/png;base64,', '', $signatureData);
            }

            // Decode base64
            $signature = base64_decode($signatureData);

            if ($signature === false) {
                throw new Exception('Failed to decode signature data');
            }

            // Generate filename
            $fileName = 'signature_' . $type . '_' . time() . '_' . uniqid() . '.png';
            $filePath = 'psu/signatures/' . $fileName;

            // Save to storage
            Storage::disk('public')->put($filePath, $signature);

            return $filePath;
        } catch (Exception $e) {
            Log::error('Error saving signature: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Authorize access to PSU
     */
    private function authorizeAccess(Psu $psu)
    {
        $userRole = Auth::user()->role;

        if ($userRole === 'user' && $psu->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data ini.');
        }

        if ($userRole === 'Ketua RT') {
            if (Auth::user()->rt != $psu->rt || Auth::user()->rw != $psu->rw) {
                abort(403, 'Anda hanya dapat melihat data dari RT ' . Auth::user()->rt . ' RW ' . Auth::user()->rw . '.');
            }
        }

        if ($userRole === 'Ketua RW') {
            if (Auth::user()->rw != $psu->rw) {
                abort(403, 'Anda hanya dapat melihat data dari RW Anda.');
            }
        }
    }

    /**
     * Get approval permissions for user
     */
    private function getApprovalPermissions(Psu $psu)
    {
        $userRole = Auth::user()->role;

        return [
            'rt' => (
                $userRole === 'Ketua RT'
                && $psu->canBeApprovedByRT()
                && Auth::user()->rt == $psu->rt
                && Auth::user()->rw == $psu->rw
            ),
            'rw' => (
                $userRole === 'Ketua RW'
                && $psu->canBeApprovedByRW()
                && Auth::user()->rw == $psu->rw
            ),
            'kelurahan' => (
                in_array($userRole, ['Front Office', 'Back Office', 'Lurah', 'Camat'])
                && $psu->canBeApprovedByKelurahan()
            )
        ];
    }

    /**
     * Build update data for PSU
     */
    private function buildUpdateData(Request $request, Psu $psu)
    {
        $updateData = $request->only([
            'nama_lengkap', 'alamat', 'pekerjaan', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
            'agama', 'status_perkawinan', 'kewarganegaraan', 'nomor_kk', 'rt', 'rw',
            'ditujukan_kepada', 'nama_ketua_rt', 'nama_ketua_rw', 'bulan', 'sifat',
            'hal', 'isi_surat', 'tujuan_internal', 'tujuan_eksternal'
        ]);

        // Map nomor_kk to nik
        $updateData['nik'] = $request->nomor_kk;

        // Handle TTD Pemohon
        if ($request->has('ttd_pemohon') && !empty($request->ttd_pemohon)) {
            if ($psu->ttd_pemohon && Storage::disk('public')->exists($psu->ttd_pemohon)) {
                Storage::disk('public')->delete($psu->ttd_pemohon);
            }
            $updateData['ttd_pemohon'] = $this->saveSignature($request->ttd_pemohon, 'pemohon');
        }

        // Handle file lampiran
        if ($request->hasFile('file_lampiran')) {
            $fileLampiran = $psu->file_lampiran ?? [];
            foreach ($request->file('file_lampiran') as $file) {
                $fileName = 'lampiran_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('psu/lampiran', $fileName, 'public');
                $fileLampiran[] = $filePath;
            }
            $updateData['file_lampiran'] = $fileLampiran;
        }

        // Update status and level based on ditujukan_kepada
        $statusData = $this->determineStatusAndLevel($request->ditujukan_kepada);
        $updateData = array_merge($updateData, $statusData);

        return $updateData;
    }

    /**
     * Check if nomor surat should be regenerated
     */
    private function shouldRegenerateNomorSurat(Request $request, Psu $psu)
    {
        return ($psu->rt != $request->rt ||
                $psu->rw != $request->rw ||
                $psu->bulan != $request->bulan);
    }

    /**
     * Check if workflow has changed
     */
    private function hasWorkflowChanged(Request $request, Psu $psu)
    {
        return ($psu->ditujukan_kepada != $request->ditujukan_kepada &&
                !in_array($psu->status, ['pending_rt', 'auto_approved']));
    }

    /**
     * Reset approval data when workflow changes
     */
    private function resetApprovalData()
    {
        return [
            'ttd_rt' => null,
            'stempel_rt' => null,
            'approved_rt_at' => null,
            'approved_rt_by' => null,
            'catatan_rt' => null,
            'ttd_rw' => null,
            'stempel_rw' => null,
            'approved_rw_at' => null,
            'approved_rw_by' => null,
            'catatan_rw' => null,
            'ttd_kelurahan' => null,
            'stempel_kelurahan' => null,
            'approved_kelurahan_at' => null,
            'approved_kelurahan_by' => null,
            'catatan_kelurahan' => null,
            'file_pdf' => null,
        ];
    }

    /**
     * Delete associated files
     */
    private function deleteAssociatedFiles(Psu $psu)
    {
        $filesToDelete = [
            $psu->ttd_pemohon,
            $psu->file_pdf
        ];

        foreach ($filesToDelete as $file) {
            if ($file && Storage::disk('public')->exists($file)) {
                Storage::disk('public')->delete($file);
            }
        }

        if ($psu->file_lampiran && is_array($psu->file_lampiran)) {
            foreach ($psu->file_lampiran as $file) {
                if (Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            }
        }
    }

    /**
     * Get spesimen data for approval
     */
    private function getSpesimenData($jabatan, $rt = null, $rw = null)
    {
        $query = Spesimen::where('jabatan', $jabatan)
                         ->where('status', 'Aktif')
                         ->where('is_active', true);

        if ($jabatan === 'Ketua RT' && $rt) {
            $query->where('rt', $rt)->where('rw', $rw);
        } elseif ($jabatan === 'Ketua RW' && $rw) {
            $query->where('rw', $rw);
        }

        return $query->first();
    }

    /**
     * Process approval for RT/RW/Kelurahan
     */
    private function processApproval(Psu $psu, $level, $data)
    {
        // Authorization checks
        $this->checkApprovalAuthorization($psu, $level);

        // Get spesimen data
        $spesimen = $this->getSpesimenForApproval($psu, $level);

        if (!$spesimen) {
            return response()->json([
                'success' => false,
                'message' => "Data spesimen TTD/Stempel {$level} tidak ditemukan. Silakan hubungi admin."
            ], 404);
        }

        // Determine new status and prepare update data
        $updateData = $this->buildApprovalUpdateData($psu, $level, $spesimen, $data);

        DB::beginTransaction();
        try {
            $psu->update($updateData);

            // Generate PDF if final approval
            if ($this->isFinalApproval($psu, $level)) {
                $this->generatePDF($psu);
            }

            $this->syncToUserApplication($psu);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "PSU berhasil disetujui oleh " . strtoupper($level) . "."
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Process rejection for RT/RW/Kelurahan
     */
    private function processRejection(Psu $psu, $level, $catatan)
    {
        // Authorization checks
        $this->checkApprovalAuthorization($psu, $level);

        DB::beginTransaction();
        try {
            $psu->update([
                'status' => "rejected_{$level}",
                "catatan_{$level}" => $catatan,
                "approved_{$level}_at" => now(),
                "approved_{$level}_by" => Auth::id(),
            ]);

            $this->syncToUserApplication($psu);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "PSU telah ditolak oleh " . strtoupper($level) . "."
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Check approval authorization
     */
    private function checkApprovalAuthorization(Psu $psu, $level)
    {
        $userRole = Auth::user()->role;

        switch ($level) {
            case 'rt':
                if ($userRole !== 'Ketua RT') {
                    abort(403, 'Anda tidak memiliki akses untuk menyetujui sebagai RT.');
                }
                if (Auth::user()->rt != $psu->rt || Auth::user()->rw != $psu->rw) {
                    abort(403, 'Anda hanya dapat menyetujui data dari RT ' . Auth::user()->rt . ' RW ' . Auth::user()->rw . '.');
                }
                if (!$psu->canBeApprovedByRT()) {
                    abort(400, 'Data tidak dapat disetujui pada tahap ini.');
                }
                break;

            case 'rw':
                if ($userRole !== 'Ketua RW') {
                    abort(403, 'Anda tidak memiliki akses untuk menyetujui sebagai RW.');
                }
                if (Auth::user()->rw != $psu->rw) {
                    abort(403, 'Anda hanya dapat menyetujui data dari RW Anda.');
                }
                if (!$psu->canBeApprovedByRW()) {
                    abort(400, 'Data tidak dapat disetujui pada tahap ini.');
                }
                break;

            case 'kelurahan':
                if (!in_array($userRole, ['Lurah', 'Camat'])) {
                    abort(403, 'Anda tidak memiliki akses untuk menyetujui sebagai Kelurahan.');
                }
                if (!$psu->canBeApprovedByKelurahan()) {
                    abort(400, 'Data tidak dapat disetujui pada tahap ini.');
                }
                break;
        }
    }

    /**
     * Get spesimen for approval based on level
     */
    private function getSpesimenForApproval(Psu $psu, $level)
    {
        switch ($level) {
            case 'rt':
                return $this->getSpesimenData('Ketua RT', $psu->rt, $psu->rw);
            case 'rw':
                return $this->getSpesimenData('Ketua RW', null, $psu->rw);
            case 'kelurahan':
                return $this->getSpesimenData('Lurah');
            default:
                return null;
        }
    }

    /**
     * Build approval update data
     */
    private function buildApprovalUpdateData(Psu $psu, $level, $spesimen, $data)
    {
        $finalLevel = $psu->getFinalApprovalLevel();

        // PERBAIKAN: Set status yang tepat berdasarkan level akhir
        if ($finalLevel === $level) {
            // Ini adalah level akhir - set ke completed
            $newStatus = 'completed';
        } else {
            // Masih ada level selanjutnya
            switch ($level) {
                case 'rt':
                    $newStatus = $finalLevel === 'rw' ? 'pending_rw' : 'approved_rt';
                    break;
                case 'rw':
                    $newStatus = $finalLevel === 'kelurahan' ? 'approved_rw' : 'completed';
                    break;
                case 'kelurahan':
                    $newStatus = 'completed';
                    break;
                default:
                    $newStatus = "approved_{$level}";
            }
        }

        $updateData = [
            'status' => $newStatus,
            "ttd_{$level}" => $spesimen->file_ttd,
            "stempel_{$level}" => $spesimen->file_stempel,
            "catatan_{$level}" => $data["catatan_{$level}"] ?? null,
            "approved_{$level}_at" => now(),
            "approved_{$level}_by" => Auth::id(),
        ];

        return $updateData;
    }

    /**
     * Check if this is the final approval
     */
    private function isFinalApproval(Psu $psu, $level)
    {
        return $psu->getFinalApprovalLevel() === $level;
    }

    /**
     * Generate PDF for PSU
     */
    private function generatePDF(Psu $psu)
    {
        try {
            $pdf = PDF::loadView('Psu.PDF', compact('psu'));

            // Clean nomor surat for filename - remove / and \ characters
            $cleanNomorSurat = str_replace(['/', '\\'], '_', $psu->nomor_surat);
            $fileName = 'psu_' . $cleanNomorSurat . '_' . time() . '.pdf';

            $filePath = 'psu/pdf/' . $fileName;
            Storage::disk('public')->put($filePath, $pdf->output());
            $psu->update(['file_pdf' => $filePath]);

            return $filePath;
        } catch (Exception $e) {
            Log::error('Error generating PDF: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    /**
     * Track PDF download
     */
    private function trackDownload(Psu $psu)
    {
        try {
            $userApp = UserApplication::where('reference_id', $psu->id)
                                    ->where('reference_table', 'psu')
                                    ->first();
            if ($userApp) {
                $userApp->increment('download_count');
            }
        } catch (Exception $e) {
            Log::error('Error tracking download: ' . $e->getMessage());
            // Don't fail the download if tracking fails
        }
    }

    /**
     * Sync data to UserApplication table
     */
    private function syncToUserApplication(Psu $psu)
    {
        try {
            // Check if UserApplication record already exists
            $userApplication = UserApplication::where('reference_id', $psu->id)
                                             ->where('reference_table', 'psu')
                                             ->first();

            // Prepare data for UserApplication
            $data = [
                'nomor_surat' => $psu->nomor_surat,
                'user_id' => $psu->user_id,
                'jenis_permohonan' => 'PSU',
                'judul_permohonan' => 'Permohonan Surat Umum',
                'deskripsi_permohonan' => $psu->hal,
                'nama_pemohon' => $psu->nama_lengkap,
                'nik' => $psu->nik,
                'rt' => $psu->rt,
                'rw' => $psu->rw,
                'status' => $psu->status,
                'approved_rt_at' => $psu->approved_rt_at,
                'approved_rt_by' => $psu->approved_rt_by,
                'catatan_rt' => $psu->catatan_rt,
                'approved_rw_at' => $psu->approved_rw_at,
                'approved_rw_by' => $psu->approved_rw_by,
                'catatan_rw' => $psu->catatan_rw,
                'approved_kelurahan_at' => $psu->approved_kelurahan_at,
                'approved_kelurahan_by' => $psu->approved_kelurahan_by,
                'catatan_kelurahan' => $psu->catatan_kelurahan,
                'file_pdf' => $psu->file_pdf,
                'reference_id' => $psu->id,
                'reference_table' => 'psu',
                'ditujukan_kepada' => $psu->ditujukan_kepada,
                'level_akhir' => $psu->level_akhir,
                'metadata' => [
                    'alamat' => $psu->alamat,
                    'pekerjaan' => $psu->pekerjaan,
                    'jenis_kelamin' => $psu->jenis_kelamin,
                    'tempat_lahir' => $psu->tempat_lahir,
                    'tanggal_lahir' => $psu->tanggal_lahir,
                    'agama' => $psu->agama,
                    'status_perkawinan' => $psu->status_perkawinan,
                    'kewarganegaraan' => $psu->kewarganegaraan,
                    'nomor_kk' => $psu->nomor_kk,
                    'ditujukan_kepada' => $psu->ditujukan_kepada,
                    'nama_ketua_rt' => $psu->nama_ketua_rt,
                    'nama_ketua_rw' => $psu->nama_ketua_rw,
                    'bulan' => $psu->bulan,
                    'sifat' => $psu->sifat,
                    'isi_surat' => $psu->isi_surat,
                    'tujuan_internal' => $psu->tujuan_internal,
                    'tujuan_eksternal' => $psu->tujuan_eksternal,
                    'ttd_pemohon' => $psu->ttd_pemohon,
                    'file_lampiran' => $psu->file_lampiran,
                ]
            ];

            if ($userApplication) {
                // Update existing record
                $userApplication->update($data);
                Log::info("Updated UserApplication for PSU ID: {$psu->id}");
            } else {
                // Create new record
                UserApplication::create($data);
                Log::info("Created UserApplication for PSU ID: {$psu->id}");
            }

        } catch (Exception $e) {
            // Log error but don't fail the main operation
            Log::error("Error syncing PSU ID {$psu->id} to UserApplication: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
        }
    }
}
