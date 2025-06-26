<?php

namespace App\Http\Controllers;

use App\Models\SuratPengantar;
use App\Models\User;
use App\Models\UserApplication;
use App\Models\DataKependudukan;
use App\Models\Spesimen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use DataTables;
use PDF;
use Carbon\Carbon;

class SuratPengantarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('SuratPengantar.index', [
            'type_menu' => 'surat-pengantar',
            'pageTitle' => 'Data Surat Pengantar/Keterangan'
        ]);
    }

    /**
     * Get data for DataTables
     */
    public function getData()
    {
        $query = SuratPengantar::with(['user', 'approverRT', 'approverRW'])
                ->orderBy('created_at', 'desc');

        // Filter berdasarkan role
        $userRole = Auth::user()->role;

        if ($userRole === 'user') {
            $query->byUser(Auth::id());
        } elseif ($userRole === 'Ketua RT') {
            // FIXED: Explicit string casting to ensure proper comparison
            $userRT = (string) Auth::user()->rt;
            $userRW = (string) Auth::user()->rw;

            // Debug logging (remove in production)
            \Log::info('Ketua RT getData filtering', [
                'user_id' => Auth::id(),
                'user_rt' => $userRT,
                'user_rw' => $userRW,
                'rt_type' => gettype($userRT),
                'rw_type' => gettype($userRW)
            ]);

            $query->where('rt', '=', $userRT)
                ->where('rw', '=', $userRW)
                ->whereIn('status', ['pending_rt', 'approved_rt', 'pending_rw', 'approved_rw', 'rejected_rt', 'rejected_rw']);

            // Debug: Log the actual SQL query (remove in production)
            \Log::info('Ketua RT SQL Query', [
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);

        } elseif ($userRole === 'Ketua RW') {
            // FIXED: Explicit string casting
            $userRW = (string) Auth::user()->rw;

            $query->where('rw', '=', $userRW)
                ->whereIn('status', ['approved_rt', 'approved_rw', 'rejected_rw']);
        } elseif (in_array($userRole, ['Front Office', 'Operator', 'admin'])) {
            // Front Office & Operator: Lihat semua data
        } else {
            // SEMUA ROLE LAIN TIDAK BISA AKSES DATA
            $query->where('id', 0);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('file_info', function ($row) {
                return '<div class="d-flex align-items-center">
                            <i class="fas fa-file-pdf text-danger mr-2"></i>
                            <div>
                                <div class="font-weight-bold">' . $row->nomor_surat . '</div>
                                <small class="text-muted">' . $row->nama_lengkap . '</small>
                            </div>
                        </div>';
            })
            ->addColumn('nomor_judul', function ($row) {
                $html = '<div class="font-weight-bold">' . $row->nomor_surat . '</div>';
                $html .= '<div class="text-muted">' . \Str::limit($row->keperluan, 50) . '</div>';
                if (Auth::user()->role !== 'user') {
                    $html .= '<small class="text-info">Pemohon: ' . $row->user->name . '</small>';
                }
                return $html;
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

                // Step 1: Submitted
                $html .= '<span class="badge ' . ($progress['submitted'] ? 'badge-success' : 'badge-secondary') . ' mr-1">
                            <i class="fas fa-file-upload"></i> Diajukan
                        </span>';

                // Step 2: RT Approved
                $html .= '<span class="badge ' . ($progress['rt_approved'] ? 'badge-success' : 'badge-secondary') . ' mr-1">
                            <i class="fas fa-check"></i> RT
                        </span>';

                // Step 3: RW Approved
                $html .= '<span class="badge ' . ($progress['rw_approved'] ? 'badge-success' : 'badge-secondary') . '">
                            <i class="fas fa-check-double"></i> RW
                        </span>';

                $html .= '</div>';
                return $html;
            })
            ->addColumn('actions', function ($row) {
                $buttons = [];
                $userRole = Auth::user()->role;

                // View button
                $buttons[] = '<a href="' . route('surat-pengantar.show', $row->id) . '"
                                class="btn btn-info btn-sm mb-1"
                                title="Lihat Detail">
                                <i class="fas fa-eye"></i> Detail
                            </a>';

                // Edit button - hanya untuk pemohon dan status pending_rt
                if ($userRole === 'user' && $row->user_id === Auth::id() && $row->canBeEdited()) {
                    $buttons[] = '<a href="' . route('surat-pengantar.edit', $row->id) . '"
                                    class="btn btn-warning btn-sm mb-1"
                                    title="Edit">
                                    <i class="fas fa-pencil-alt"></i> Edit
                                </a>';
                }

                // Preview PDF button
                if ($row->canPreviewPDF()) {
                    $buttons[] = '<a href="' . route('surat-pengantar.preview-pdf', $row->id) . '"
                                    class="btn btn-secondary btn-sm mb-1"
                                    title="Preview PDF"
                                    target="_blank">
                                    <i class="fas fa-eye"></i> Preview
                                </a>';
                }

                // Download PDF button
                if ($row->canDownloadPDF()) {
                    $buttons[] = '<a href="' . route('surat-pengantar.download-pdf', $row->id) . '"
                                    class="btn btn-success btn-sm mb-1"
                                    title="Download PDF">
                                    <i class="fas fa-download"></i> Download
                                </a>';
                }

                // RT Approve/Reject buttons
                if ($userRole === 'Ketua RT' && $row->canBeApprovedByRT() && Auth::user()->rt == $row->rt && Auth::user()->rw == $row->rw) {
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

                // RW Approve/Reject buttons
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

                // Delete button
                if (($userRole === 'user' && $row->user_id === Auth::id() && $row->canBeEdited()) ||
                    in_array($userRole, ['admin'])) {
                    $buttons[] = '<form action="' . route('surat-pengantar.destroy', $row->id) . '"
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
            })
            ->rawColumns(['file_info', 'nomor_judul', 'status', 'workflow', 'actions'])
            ->make(true);
    }

    /**
     * Get summary statistics
     */
    public function getSummary()
    {
        try {
            $baseQuery = SuratPengantar::query();

            // Filter berdasarkan role
            $userRole = Auth::user()->role;

            if ($userRole === 'user') {
                $baseQuery->where('user_id', Auth::id());
            } elseif ($userRole === 'Ketua RT') {
                $userRT = Auth::user()->rt;
                $userRW = Auth::user()->rw;
                $baseQuery->where('rt', $userRT)->where('rw', $userRW);
            } elseif ($userRole === 'Ketua RW') {
                $userRW = Auth::user()->rw;
                $baseQuery->where('rw', $userRW);
            }

            $summary = [
                'total_pengajuan' => (clone $baseQuery)->count(),
                'pending_rt' => (clone $baseQuery)->where('status', 'pending_rt')->count(),
                'pending_rw' => (clone $baseQuery)->whereIn('status', ['approved_rt'])->count(),
                'approved_pengajuan' => (clone $baseQuery)->where('status', 'approved_rw')->count(),
                'rejected_pengajuan' => (clone $baseQuery)->whereIn('status', ['rejected_rt', 'rejected_rw'])->count(),
            ];

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
     * Get available RT based on selected RW for Surat Pengantar form
     */
    public function getRtByRwForSurat(Request $request)
    {
        $selectedRw = $request->rw;

        // RW-RT mapping
        $rwRtMapping = [
            '01' => 6, '02' => 7, '03' => 10, '04' => 8, '05' => 10,
            '06' => 4, '07' => 4, '08' => 3, '09' => 8, '10' => 3,
        ];

        $availableRT = [];
        $rtCount = $rwRtMapping[$selectedRw] ?? 0;

        // Generate RT options for the selected RW
        for ($i = 1; $i <= $rtCount; $i++) {
            $rt = $i == 10 ? '10' : sprintf('%02d', $i);
            $availableRT[] = [
                'value' => $rt,
                'label' => 'RT ' . $rt
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $availableRT
        ]);
    }

    /**
     * Validate RW-RT combination for Surat Pengantar
     */
    public function validateRwRt(Request $request)
    {
        $rw = $request->input('rw');
        $rt = $request->input('rt');

        // RT-RW mapping
        $rwRtMapping = [
            '01' => 6, '02' => 7, '03' => 10, '04' => 8, '05' => 10,
            '06' => 4, '07' => 4, '08' => 3, '09' => 8, '10' => 3,
        ];

        $maxRT = $rwRtMapping[$rw] ?? 0;
        $rtNumber = (int) ltrim($rt, '0');
        $isValid = $rtNumber >= 1 && $rtNumber <= $maxRT;

        $message = $isValid
            ? 'Kombinasi RW-RT valid'
            : "RT {$rt} tidak tersedia di RW {$rw}. RT yang tersedia: 01-{$maxRT}";

        return response()->json([
            'success' => true,
            'valid' => $isValid,
            'message' => $message
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        // RW-RT mapping yang sama seperti di profile
        $rwRtMapping = [
            '01' => 6,  // RW 01 has RT 01-06
            '02' => 7,  // RW 02 has RT 01-07
            '03' => 10, // RW 03 has RT 01-10
            '04' => 8,  // RW 04 has RT 01-08
            '05' => 10, // RW 05 has RT 01-10
            '06' => 4,  // RW 06 has RT 01-04
            '07' => 4,  // RW 07 has RT 01-04
            '08' => 3,  // RW 08 has RT 01-03
            '09' => 8,  // RW 09 has RT 01-08
            '10' => 3,  // RW 10 has RT 01-03
        ];

        // Generate available RW
        $availableRW = [];
        foreach ($rwRtMapping as $rw => $rtCount) {
            $availableRW[] = [
                'value' => $rw,
                'label' => 'RW ' . $rw,
                'rt_count' => $rtCount
            ];
        }

        // Generate available RT (semua RT 01-10, nanti dibatasi di JS)
        $availableRT = [];
        for ($i = 1; $i <= 10; $i++) {
            $rt = $i == 10 ? '10' : sprintf('%02d', $i);
            $availableRT[] = [
                'value' => $rt,
                'label' => 'RT ' . $rt
            ];
        }

        return view('SuratPengantar.create', [
            'type_menu' => 'surat-pengantar',
            'pageTitle' => 'Tambah Surat Pengantar/Keterangan',
            'user' => $user,
            'availableRT' => $availableRT,
            'availableRW' => $availableRW,
            'rwRtMapping' => $rwRtMapping
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'alamat' => 'required|string',
            'pekerjaan' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|string|max:100',
            'status_perkawinan' => 'required|string|max:100',
            'kewarganegaraan' => 'required|string|max:100',
            'nomor_kk' => 'required|string|max:20',
            'tujuan' => 'required|string',
            'keperluan' => 'required|string',
            'keterangan_lain' => 'nullable|string',
            'rt' => 'required|string|max:3',
            'rw' => 'required|string|max:3',
            'ttd_pemohon' => 'required|string', // Base64 signature
            // 'ttd_pemilik' => 'nullable|string', // Base64 signature (optional)
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Process and save signatures
            $ttdPemohonPath = null;
            $ttdPemilikPath = null;

            // Save pemohon signature (required)
            if ($request->ttd_pemohon) {
                $ttdPemohonPath = $this->saveSignature($request->ttd_pemohon, 'pemohon');
            }

            // Save pemilik signature (optional)
            // if ($request->ttd_pemilik && !$this->isEmptySignature($request->ttd_pemilik)) {
            //     $ttdPemilikPath = $this->saveSignature($request->ttd_pemilik, 'pemilik');
            // }

            // Create SuratPengantar record
            $suratPengantar = SuratPengantar::create([
                'nomor_surat' => SuratPengantar::generateNomorSurat(),
                'user_id' => Auth::id(),
                'nama_lengkap' => $request->nama_lengkap,
                'nik' => $request->nomor_kk, // Map nomor_kk to nik
                'alamat' => $request->alamat,
                'pekerjaan' => $request->pekerjaan,
                'jenis_kelamin' => $request->gender,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'agama' => $request->agama,
                'status_perkawinan' => $request->status_perkawinan,
                'kewarganegaraan' => $request->kewarganegaraan,
                'nomor_kk' => $request->nomor_kk,
                'tujuan' => $request->tujuan,
                'keperluan' => $request->keperluan,
                'keterangan_lain' => $request->keterangan_lain,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'status' => 'pending_rt',
                'ttd_pemohon' => $ttdPemohonPath,
                // 'ttd_pemilik' => $ttdPemilikPath,
            ]);

            // Sync to UserApplication
            $this->syncToUserApplication($suratPengantar);

            return response()->json([
                'success' => true,
                'message' => 'Surat Pengantar berhasil diajukan dengan nomor: ' . $suratPengantar->nomor_surat
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating SuratPengantar: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
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
                throw new \Exception('Failed to decode signature data');
            }

            // Generate filename
            $fileName = 'signature_' . $type . '_' . time() . '_' . uniqid() . '.png';
            $filePath = 'surat_pengantar/signatures/' . $fileName;

            // Save to storage
            Storage::disk('public')->put($filePath, $signature);

            return $filePath;
        } catch (\Exception $e) {
            Log::error('Error saving signature: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Check if signature is empty (just a blank canvas)
     */
    private function isEmptySignature($signatureData)
    {
        // Check if signature data is just a blank canvas
        if (empty($signatureData)) {
            return true;
        }

        // If signature is just the initial canvas data, consider it empty
        $blankCanvasSignatures = [
            'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8/5+hHgAHggJ/PchI7wAAAABJRU5ErkJggg==',
            // Add other known blank canvas signatures here
        ];

        return in_array($signatureData, $blankCanvasSignatures);
    }

    /**
     * Display the specified resource.
     */
    public function show(SuratPengantar $suratPengantar)
    {
        $userRole = Auth::user()->role;

        // Check authorization
        if ($userRole === 'user' && $suratPengantar->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data ini.');
        }

        // Check authorization untuk RT
        if ($userRole === 'Ketua RT') {
            if (Auth::user()->rt != $suratPengantar->rt || Auth::user()->rw != $suratPengantar->rw) {
                abort(403, 'Anda hanya dapat melihat data dari RT ' . Auth::user()->rt . ' RW ' . Auth::user()->rw . '.');
            }
        }

        // Check authorization untuk RW
        if ($userRole === 'Ketua RW') {
            if (Auth::user()->rw != $suratPengantar->rw) {
                abort(403, 'Anda hanya dapat melihat data dari RW Anda.');
            }
        }

        $suratPengantar->load(['user', 'approverRT', 'approverRW']);

        // Check if user can approve
        $canApproveRT = (
            $userRole === 'Ketua RT'
            && $suratPengantar->canBeApprovedByRT()
            && Auth::user()->rt == $suratPengantar->rt
            && Auth::user()->rw == $suratPengantar->rw
        );
        $canApproveRW = (
            $userRole === 'Ketua RW'
            && $suratPengantar->canBeApprovedByRW()
            && Auth::user()->rw == $suratPengantar->rw
        );

        return view('SuratPengantar.show', [
            'type_menu' => 'surat-pengantar',
            'pageTitle' => 'Detail Surat Pengantar/Keterangan',
            'suratPengantar' => $suratPengantar,
            'canApproveRT' => $canApproveRT,
            'canApproveRW' => $canApproveRW
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SuratPengantar $suratPengantar)
    {
        // Check authorization
        if (Auth::user()->role === 'user' && $suratPengantar->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit data ini.');
        }

        if (!$suratPengantar->canBeEdited()) {
            return redirect()->route('surat-pengantar.index')
                           ->with('error', 'Data tidak dapat diedit karena sudah diproses.');
        }

        // Dapatkan data RT dan RW yang tersedia
        $rtRwOptions = DataKependudukan::select('total_rt', 'total_rw')->first();

        $availableRT = [];
        $availableRW = [];

        if ($rtRwOptions) {
            for ($i = 1; $i <= $rtRwOptions->total_rt; $i++) {
                $availableRT[] = sprintf('%02d', $i);
            }

            for ($i = 1; $i <= $rtRwOptions->total_rw; $i++) {
                $availableRW[] = sprintf('%02d', $i);
            }
        }

        return view('SuratPengantar.edit', [
            'type_menu' => 'surat-pengantar',
            'pageTitle' => 'Edit Surat Pengantar/Keterangan',
            'suratPengantar' => $suratPengantar,
            'availableRT' => $availableRT,
            'availableRW' => $availableRW
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SuratPengantar $suratPengantar)
    {
        // Check authorization
        if (Auth::user()->role === 'user' && $suratPengantar->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk mengedit data ini.'
            ], 403);
        }

        if (!$suratPengantar->canBeEdited()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak dapat diedit karena sudah diproses.'
            ], 400);
        }

        // Validasi berbeda tergantung apakah sudah ada TTD atau belum
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
            'tujuan' => 'required|string',
            'keperluan' => 'required|string',
            'keterangan_lain' => 'nullable|string',
            'rt' => 'required|string|max:3',
            'rw' => 'required|string|max:3',
        ];

        // TTD pemohon hanya wajib jika belum ada TTD sebelumnya
        if (!$suratPengantar->hasPemohonSignature()) {
            $validationRules['ttd_pemohon'] = 'required|string';
        } else {
            $validationRules['ttd_pemohon'] = 'nullable|string';
        }

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updateData = $request->only([
                'nama_lengkap', 'alamat', 'pekerjaan', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
                'agama', 'status_perkawinan', 'kewarganegaraan', 'nomor_kk', 'tujuan', 'keperluan',
                'keterangan_lain', 'rt', 'rw'
            ]);

            // Map nomor_kk to nik as well
            $updateData['nik'] = $request->nomor_kk;

            // Handle TTD Pemohon
            if ($request->has('ttd_pemohon') && !empty($request->ttd_pemohon)) {
                // Ada TTD baru, hapus yang lama dan simpan yang baru
                if ($suratPengantar->ttd_pemohon && Storage::disk('public')->exists($suratPengantar->ttd_pemohon)) {
                    Storage::disk('public')->delete($suratPengantar->ttd_pemohon);
                }
                $updateData['ttd_pemohon'] = $this->saveSignature($request->ttd_pemohon, 'pemohon');
            }
            // Jika tidak ada TTD baru dan sudah ada TTD lama, maka TTD lama tetap digunakan
            // Tidak perlu mengubah field ttd_pemohon di database

            // Reset status ke pending_rt jika RT/RW berubah dan sudah pernah diproses
            $originalRT = $suratPengantar->rt;
            $originalRW = $suratPengantar->rw;
            $newRT = $request->rt;
            $newRW = $request->rw;

            if (($originalRT != $newRT || $originalRW != $newRW) &&
                !in_array($suratPengantar->status, ['pending_rt'])) {

                // Reset approval data
                $updateData['status'] = 'pending_rt';
                $updateData['ttd_rt'] = null;
                $updateData['stempel_rt'] = null;
                $updateData['approved_rt_at'] = null;
                $updateData['approved_rt_by'] = null;
                $updateData['catatan_rt'] = null;
                $updateData['ttd_rw'] = null;
                $updateData['stempel_rw'] = null;
                $updateData['approved_rw_at'] = null;
                $updateData['approved_rw_by'] = null;
                $updateData['catatan_rw'] = null;
                $updateData['file_pdf'] = null;

                Log::info("RT/RW changed for SuratPengantar ID {$suratPengantar->id}, resetting approval status");
            }

            $suratPengantar->update($updateData);

            // Sync to UserApplication
            $this->syncToUserApplication($suratPengantar);

            $message = 'Data Surat Pengantar berhasil diperbarui.';
            if (($originalRT != $newRT || $originalRW != $newRW) &&
                !in_array($suratPengantar->status, ['pending_rt'])) {
                $message .= ' Karena RT/RW berubah, proses persetujuan akan dimulai ulang dari awal.';
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating SuratPengantar: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SuratPengantar $suratPengantar)
    {
        $userRole = Auth::user()->role;

        // Check authorization
        if ($userRole === 'user' && $suratPengantar->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data ini.'
            ], 403);
        }

        // Only allow deletion if still pending_rt untuk user, atau admin roles
        if ($userRole === 'user' && !$suratPengantar->canBeEdited()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak dapat dihapus karena sudah diproses.'
            ], 400);
        }

        try {
            // Delete corresponding UserApplication record first
            UserApplication::where('reference_id', $suratPengantar->id)
                          ->where('reference_table', 'surat_pengantar')
                          ->delete();

            // Delete signature files if exist
            if ($suratPengantar->ttd_pemohon && Storage::disk('public')->exists($suratPengantar->ttd_pemohon)) {
                Storage::disk('public')->delete($suratPengantar->ttd_pemohon);
            }

            if ($suratPengantar->ttd_pemilik && Storage::disk('public')->exists($suratPengantar->ttd_pemilik)) {
                Storage::disk('public')->delete($suratPengantar->ttd_pemilik);
            }

            // Delete PDF file if exists
            if ($suratPengantar->file_pdf && Storage::disk('public')->exists($suratPengantar->file_pdf)) {
                Storage::disk('public')->delete($suratPengantar->file_pdf);
            }

            $suratPengantar->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data Surat Pengantar berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting SuratPengantar: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get TTD and Stempel from Spesimen table
     */
    private function getSpesimenData($jabatan, $rt = null, $rw = null)
    {
        $query = \App\Models\Spesimen::where('jabatan', $jabatan)
                                    ->where('status', 'Aktif')
                                    ->where('is_active', true);

        if ($jabatan === 'Ketua RT' && $rt) {
            $query->where('rt', $rt)
                ->where('rw', $rw);
        } elseif ($jabatan === 'Ketua RW' && $rw) {
            $query->where('rw', $rw);
        }

        return $query->first();
    }

    /**
     * Get RT TTD and Stempel for current approval
     */
    public function getRTSpesimen(Request $request, SuratPengantar $suratPengantar)
    {
        if (Auth::user()->role !== 'Ketua RT') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        if (Auth::user()->rt != $suratPengantar->rt || Auth::user()->rw != $suratPengantar->rw) {
            return response()->json([
                'success' => false,
                'message' => 'Anda hanya dapat mengakses data dari RT ' . Auth::user()->rt . ' RW ' . Auth::user()->rw . '.'
            ], 403);
        }

        $spesimen = $this->getSpesimenData('Ketua RT', $suratPengantar->rt, $suratPengantar->rw);

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
                'nomor_rt' => $suratPengantar->rt
            ]
        ]);
    }

    /**
     * Get RW TTD and Stempel for current approval
     */
    public function getRWSpesimen(Request $request, SuratPengantar $suratPengantar)
    {
        if (Auth::user()->role !== 'Ketua RW') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $spesimen = $this->getSpesimenData('Ketua RW', null, $suratPengantar->rw);

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

    /**
     * Approve by RT
     */
    public function approveRT(Request $request, SuratPengantar $suratPengantar)
    {
        if (Auth::user()->role !== 'Ketua RT') {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menyetujui sebagai RT.'
            ], 403);
        }

        // Check if RT matches
        if (Auth::user()->rt != $suratPengantar->rt || Auth::user()->rw != $suratPengantar->rw) {
            return response()->json([
                'success' => false,
                'message' => 'Anda hanya dapat menyetujui data dari RT ' . Auth::user()->rt . ' RW ' . Auth::user()->rw . '.'
            ], 403);
        }

        if (!$suratPengantar->canBeApprovedByRT()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak dapat disetujui pada tahap ini.'
            ], 400);
        }

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
            // Get TTD and Stempel from Spesimen
            $spesimen = $this->getSpesimenData('Ketua RT', $suratPengantar->rt, $suratPengantar->rw);

            if (!$spesimen) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data spesimen TTD/Stempel RT tidak ditemukan. Silakan hubungi admin untuk mengupload spesimen.'
                ], 404);
            }

            // Simpan PATH file spesimen saja (bukan base64)
            $ttdRTPath = $spesimen->file_ttd;
            $stempelRTPath = $spesimen->file_stempel;

            $suratPengantar->update([
                'status' => 'approved_rt',
                'ttd_rt' => $ttdRTPath,
                'stempel_rt' => $stempelRTPath,
                'catatan_rt' => $request->catatan_rt,
                'approved_rt_at' => now(),
                'approved_rt_by' => Auth::id(),
            ]);

            // Sync to UserApplication
            $this->syncToUserApplication($suratPengantar);

            return response()->json([
                'success' => true,
                'message' => 'Surat Pengantar berhasil disetujui oleh RT.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error approving RT: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject by RT
     */
    public function rejectRT(Request $request, SuratPengantar $suratPengantar)
    {
        if (Auth::user()->role !== 'Ketua RT') {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menolak sebagai RT.'
            ], 403);
        }

        // Check if RT matches
        if (Auth::user()->rt != $suratPengantar->rt || Auth::user()->rw != $suratPengantar->rw) {
            return response()->json([
                'success' => false,
                'message' => 'Anda hanya dapat mengakses data dari RT ' . Auth::user()->rt . ' RW ' . Auth::user()->rw . '.'
            ], 403);
        }

        if (!$suratPengantar->canBeApprovedByRT()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak dapat ditolak pada tahap ini.'
            ], 400);
        }

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
            $suratPengantar->update([
                'status' => 'rejected_rt',
                'catatan_rt' => $request->catatan_rt,
                'approved_rt_at' => now(),
                'approved_rt_by' => Auth::id(),
            ]);

            // Sync to UserApplication
            $this->syncToUserApplication($suratPengantar);

            return response()->json([
                'success' => true,
                'message' => 'Surat Pengantar telah ditolak oleh RT.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error rejecting RT: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve by RW
     */
    public function approveRW(Request $request, SuratPengantar $suratPengantar)
    {
        if (Auth::user()->role !== 'Ketua RW') {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menyetujui sebagai RW.'
            ], 403);
        }

        // Check if RW matches
        if (Auth::user()->rw != $suratPengantar->rw) {
            return response()->json([
                'success' => false,
                'message' => 'Anda hanya dapat menyetujui data dari RW Anda.'
            ], 403);
        }

        if (!$suratPengantar->canBeApprovedByRW()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak dapat disetujui pada tahap ini.'
            ], 400);
        }

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
            // Get TTD and Stempel from Spesimen
            $spesimen = $this->getSpesimenData('Ketua RW', null, $suratPengantar->rw);

            if (!$spesimen) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data spesimen TTD/Stempel RW tidak ditemukan. Silakan hubungi admin untuk mengupload spesimen.'
                ], 404);
            }

            // Simpan PATH file spesimen saja (bukan base64)
            $ttdRWPath = $spesimen->file_ttd;
            $stempelRWPath = $spesimen->file_stempel;

            $suratPengantar->update([
                'status' => 'approved_rw',
                'ttd_rw' => $ttdRWPath,
                'stempel_rw' => $stempelRWPath,
                'catatan_rw' => $request->catatan_rw,
                'approved_rw_at' => now(),
                'approved_rw_by' => Auth::id(),
            ]);

            // Generate final PDF
            $this->generatePDF($suratPengantar);

            // Sync to UserApplication
            $this->syncToUserApplication($suratPengantar);

            return response()->json([
                'success' => true,
                'message' => 'Surat Pengantar berhasil disetujui oleh RW. PDF siap untuk diunduh.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error approving RW: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject by RW
     */
    public function rejectRW(Request $request, SuratPengantar $suratPengantar)
    {
        if (Auth::user()->role !== 'Ketua RW') {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menolak sebagai RW.'
            ], 403);
        }

        // Check if RW matches
        if (Auth::user()->rw != $suratPengantar->rw) {
            return response()->json([
                'success' => false,
                'message' => 'Anda hanya dapat menolak data dari RW Anda.'
            ], 403);
        }

        if (!$suratPengantar->canBeApprovedByRW()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak dapat ditolak pada tahap ini.'
            ], 400);
        }

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
            $suratPengantar->update([
                'status' => 'rejected_rw',
                'catatan_rw' => $request->catatan_rw,
                'approved_rw_at' => now(),
                'approved_rw_by' => Auth::id(),
            ]);

            // Sync to UserApplication
            $this->syncToUserApplication($suratPengantar);

            return response()->json([
                'success' => true,
                'message' => 'Surat Pengantar telah ditolak oleh RW.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error rejecting RW: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate PDF
     */
    private function generatePDF(SuratPengantar $suratPengantar)
    {
        try {
            $pdf = PDF::loadView('SuratPengantar.PDF', compact('suratPengantar'));

            // Clean nomor surat for filename - remove / and \ characters
            $cleanNomorSurat = str_replace(['/', '\\'], '_', $suratPengantar->nomor_surat);
            $fileName = 'surat_pengantar_' . $cleanNomorSurat . '_' . time() . '.pdf';

            $filePath = 'surat_pengantar/pdf/' . $fileName;
            Storage::disk('public')->put($filePath, $pdf->output());
            $suratPengantar->update(['file_pdf' => $filePath]);

            return $filePath;
        } catch (\Exception $e) {
            Log::error('Error generating PDF: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    /**
     * Preview PDF (bisa dilihat kapan saja)
     */
    public function previewPDF(SuratPengantar $suratPengantar)
    {
        try {
            $pdf = PDF::loadView('SuratPengantar.PDF', compact('suratPengantar'));

            // Clean nomor surat for filename
            $cleanNomorSurat = str_replace(['/', '\\'], '_', $suratPengantar->nomor_surat);
            $fileName = 'preview_surat_pengantar_' . $cleanNomorSurat . '.pdf';

            return $pdf->stream($fileName);
        } catch (\Exception $e) {
            Log::error('Error previewing PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat preview PDF: ' . $e->getMessage());
        }
    }

    /**
     * Download PDF (hanya jika sudah fully approved)
     */
    public function downloadPDF(SuratPengantar $suratPengantar)
    {
        // Check authorization
        if (Auth::user()->role === 'user' && $suratPengantar->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengunduh data ini.');
        }

        if (!$suratPengantar->canDownloadPDF()) {
            return redirect()->back()
                           ->with('error', 'PDF hanya dapat diunduh setelah disetujui oleh RT dan RW.');
        }

        try {
            if (!$suratPengantar->file_pdf || !Storage::disk('public')->exists($suratPengantar->file_pdf)) {
                // Regenerate PDF if not exists
                $this->generatePDF($suratPengantar);
            }

            // Clean nomor surat for download filename
            $cleanNomorSurat = str_replace(['/', '\\'], '_', $suratPengantar->nomor_surat);
            $downloadName = 'SURAT_PENGANTAR_' . $cleanNomorSurat . '.pdf';

            // Track download in UserApplication
            $userApp = UserApplication::where('reference_id', $suratPengantar->id)
                                    ->where('reference_table', 'surat_pengantar')
                                    ->first();
            if ($userApp) {
                $userApp->increment('download_count');
            }

            return Storage::disk('public')->download($suratPengantar->file_pdf, $downloadName);
        } catch (\Exception $e) {
            Log::error('Error downloading PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengunduh PDF: ' . $e->getMessage());
        }
    }

    /**
     * Sync data to UserApplication table
     */
    private function syncToUserApplication(SuratPengantar $suratPengantar)
    {
        try {
            // Check if UserApplication record already exists
            $userApplication = UserApplication::where('reference_id', $suratPengantar->id)
                                             ->where('reference_table', 'surat_pengantar')
                                             ->first();

            // Prepare data for UserApplication
            $data = [
                'nomor_surat' => $suratPengantar->nomor_surat,
                'user_id' => $suratPengantar->user_id,
                'jenis_permohonan' => 'SURAT PENGANTAR',
                'judul_permohonan' => 'Surat Pengantar/Keterangan',
                'deskripsi_permohonan' => $suratPengantar->keperluan,
                'nama_pemohon' => $suratPengantar->nama_lengkap,
                'nik' => $suratPengantar->nik,
                'rt' => $suratPengantar->rt,
                'rw' => $suratPengantar->rw,
                'status' => $suratPengantar->status,
                'approved_rt_at' => $suratPengantar->approved_rt_at,
                'approved_rt_by' => $suratPengantar->approved_rt_by,
                'catatan_rt' => $suratPengantar->catatan_rt,
                'approved_rw_at' => $suratPengantar->approved_rw_at,
                'approved_rw_by' => $suratPengantar->approved_rw_by,
                'catatan_rw' => $suratPengantar->catatan_rw,
                'file_pdf' => $suratPengantar->file_pdf,
                'reference_id' => $suratPengantar->id,
                'reference_table' => 'surat_pengantar',
                'metadata' => [
                    'alamat' => $suratPengantar->alamat,
                    'pekerjaan' => $suratPengantar->pekerjaan,
                    'jenis_kelamin' => $suratPengantar->jenis_kelamin,
                    'tempat_lahir' => $suratPengantar->tempat_lahir,
                    'tanggal_lahir' => $suratPengantar->tanggal_lahir,
                    'agama' => $suratPengantar->agama,
                    'status_perkawinan' => $suratPengantar->status_perkawinan,
                    'kewarganegaraan' => $suratPengantar->kewarganegaraan,
                    'nomor_kk' => $suratPengantar->nomor_kk,
                    'tujuan' => $suratPengantar->tujuan,
                    'keterangan_lain' => $suratPengantar->keterangan_lain,
                    'ttd_pemohon' => $suratPengantar->ttd_pemohon,
                    'ttd_pemilik' => $suratPengantar->ttd_pemilik,
                ]
            ];

            if ($userApplication) {
                // Update existing record
                $userApplication->update($data);
                Log::info("Updated UserApplication for SuratPengantar ID: {$suratPengantar->id}");
            } else {
                // Create new record
                UserApplication::create($data);
                Log::info("Created UserApplication for SuratPengantar ID: {$suratPengantar->id}");
            }

        } catch (\Exception $e) {
            // Log error but don't fail the main operation
            Log::error("Error syncing SuratPengantar ID {$suratPengantar->id} to UserApplication: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
        }
    }

    /**
     * Sync all existing SuratPengantar data to UserApplication
     */
    public function syncAllToUserApplication()
    {
        try {
            $suratPengantarRecords = SuratPengantar::all();
            $synced = 0;
            $errors = 0;

            foreach ($suratPengantarRecords as $suratPengantar) {
                try {
                    $this->syncToUserApplication($suratPengantar);
                    $synced++;
                } catch (\Exception $e) {
                    $errors++;
                    Log::error("Error syncing SuratPengantar ID {$suratPengantar->id}: " . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Sync completed. Synced: {$synced}, Errors: {$errors}",
                'data' => [
                    'synced' => $synced,
                    'errors' => $errors,
                    'total' => $suratPengantarRecords->count()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error during sync: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check data integrity between SuratPengantar and UserApplication
     */
    public function checkDataIntegrity()
    {
        try {
            $suratPengantarCount = SuratPengantar::count();
            $userAppCount = UserApplication::where('jenis_permohonan', 'SURAT_PENGANTAR')->count();

            $missingInUserApp = SuratPengantar::whereNotIn('id',
                UserApplication::where('jenis_permohonan', 'SURAT_PENGANTAR')
                               ->pluck('reference_id')
            )->count();

            $orphanedUserApp = UserApplication::where('jenis_permohonan', 'SURAT_PENGANTAR')
                              ->whereNotIn('reference_id', SuratPengantar::pluck('id'))
                              ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'surat_pengantar_count' => $suratPengantarCount,
                    'user_application_count' => $userAppCount,
                    'missing_in_user_app' => $missingInUserApp,
                    'orphaned_user_app' => $orphanedUserApp,
                    'is_synced' => ($suratPengantarCount === $userAppCount && $missingInUserApp === 0 && $orphanedUserApp === 0)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking data integrity: ' . $e->getMessage()
            ], 500);
        }
    }
}
