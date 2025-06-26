<?php

namespace App\Http\Controllers;

use App\Models\Skaw;
use App\Models\SkawAnak;
use App\Models\SkawFile;
use App\Models\SkawActivityLog;
use App\Models\User;
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

class SkawController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userRole = Auth::user()->role;

        // Redirect based on role
        if (in_array($userRole, ['user', 'admin'])) {
            return redirect()->route('skaw.permohonan-saya');
        }

        if (in_array($userRole, ['Front Office', 'Back Office', 'Lurah', 'Camat'])) {
            return redirect()->route('skaw.semua-permohonan');
        }

        // Default fallback
        return redirect()->route('skaw.permohonan-saya');
    }

    /**
     * View 1: Permohonan Saya - Untuk user dan admin
     */
    public function permohonanSaya(Request $request)
    {
        return view('SKAW.index-permohonan-saya', [
            'type_menu' => 'skaw',
            'pageTitle' => 'Permohonan SKAW Saya',
            'userRole' => Auth::user()->role,
        ]);
    }

    /**
     * View 2: Semua Permohonan - Untuk Front Office, Back Office, Lurah, Camat
     */
    public function semuaPermohonan(Request $request)
    {
        $userRole = Auth::user()->role;

        return view('SKAW.index-semua-permohonan', [
            'type_menu' => 'skaw',
            'pageTitle' => 'Semua Permohonan SKAW - ' . $userRole,
            'userRole' => $userRole,
        ]);
    }

    /**
     * View 3: Daftar Sidang SKAW
     */
    public function daftarSidang(Request $request)
    {
        return view('SKAW.index-daftar-sidang', [
            'type_menu' => 'skaw',
            'pageTitle' => 'Daftar Sidang SKAW',
            'userRole' => Auth::user()->role,
        ]);
    }

    /**
     * View 4: Daftar Pemohon SKAW Telah Sidang
     */
    public function telahSidang(Request $request)
    {
        return view('SKAW.index-telah-sidang', [
            'type_menu' => 'skaw',
            'pageTitle' => 'SKAW Telah Sidang - Menunggu Approval',
            'userRole' => Auth::user()->role,
        ]);
    }

    /**
     * View 5: SKAW Jadi - Final documents
     */
    public function skawJadi(Request $request)
    {
        $userRole = Auth::user()->role;

        return view('SKAW.index-skaw-jadi', [
            'type_menu' => 'skaw',
            'pageTitle' => 'SKAW Jadi - Dokumen Final',
            'userRole' => $userRole,
        ]);
    }

    /**
     * Get data for DataTables
     */
    public function getData(Request $request)
    {
        try {
            $userRole = Auth::user()->role;
            $viewType = $request->get('view_type', 'permohonan_saya');

            $query = Skaw::with(['user', 'frontOfficeApprover', 'lurahApprover', 'camatApprover'])
                        ->orderBy('created_at', 'desc');

            // Apply filter based on view type and user role
            $this->applyViewFilter($query, $userRole, $viewType);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('file_info', function ($row) {
                    return $this->buildFileInfoColumn($row);
                })
                ->addColumn('nomor_surat', function ($row) {
                    return $this->buildNomorSuratColumn($row);
                })
                ->addColumn('pemohon_info', function ($row) {
                    return $this->buildPemohonInfoColumn($row);
                })
                ->addColumn('pewaris_info', function ($row) {
                    return $this->buildPewarisInfoColumn($row);
                })
                ->addColumn('tanggal', function ($row) {
                    return $this->buildTanggalColumn($row);
                })
                ->addColumn('status', function ($row) {
                    return $row->status_badge;
                })
                ->addColumn('workflow', function ($row) {
                    return $this->buildWorkflowColumn($row);
                })
                ->addColumn('actions', function ($row) use ($userRole, $viewType) {
                    return $this->buildActionsColumn($row, $userRole, $viewType);
                })
                ->rawColumns(['file_info', 'nomor_surat', 'pemohon_info', 'pewaris_info', 'status', 'workflow', 'actions'])
                ->make(true);

        } catch (Exception $e) {
            Log::error('Error in SKAW getData: ' . $e->getMessage());
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
     * Get summary statistics
     */
    public function getSummary(Request $request)
    {
        try {
            $userRole = Auth::user()->role;
            $viewType = $request->get('view_type', 'permohonan_saya');

            $baseQuery = Skaw::query();
            $this->applyViewFilter($baseQuery, $userRole, $viewType);

            if ($viewType === 'permohonan_saya') {
                $summary = [
                    'total_pengajuan' => (clone $baseQuery)->count(),
                    'draft' => (clone $baseQuery)->where('status', 'draft')->count(),
                    'sedang_proses' => (clone $baseQuery)->whereIn('status', [
                        'submitted', 'front_office_approved', 'skaw_generated',
                        'jadwal_sidang_created', 'sidang_selesai', 'evidence_uploaded',
                        'lurah_approved', 'camat_approved', 'skaw_final'
                    ])->count(),
                    'selesai' => (clone $baseQuery)->where('status', 'completed')->count(),
                ];
            } else {
                $summary = [
                    'total_permohonan' => (clone $baseQuery)->count(),
                    'butuh_action' => $this->getButuhActionCount($baseQuery, $userRole),
                    'sedang_proses' => $this->getSedangProsesCount($baseQuery, $userRole),
                    'selesai' => (clone $baseQuery)->where('status', 'completed')->count(),
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $summary
            ]);

        } catch (Exception $e) {
            Log::error('Error getting SKAW summary: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting summary: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        return view('SKAW.create', [
            'type_menu' => 'skaw',
            'pageTitle' => 'Buat Permohonan SKAW',
            'user' => $user,
            'fileTypeLabels' => SkawFile::getFileTypeLabels(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $this->validateSkawRequest($request);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $user = Auth::user();

            // Generate nomor surat
            $nomorSurat = Skaw::generateNomorSurat($request->rt, $request->rw);

            // Create main SKAW record
            $skawData = [
                'nomor_surat' => $nomorSurat,
                'user_id' => Auth::id(),

                // Data Pemohon (auto-fill from user)
                'nama_lengkap' => $request->nama_lengkap,
                'nik' => $request->nik,
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
                'email' => $request->email,
                'no_telepon' => $request->no_telepon,

                // Data khusus SKAW Pemohon
                'nomor_akta_perkawinan' => $request->nomor_akta_perkawinan,
                'tanggal_terbit_akta_perkawinan' => $request->tanggal_terbit_akta_perkawinan,
                'jumlah_anak' => $request->jumlah_anak ?? 0,

                // Data Pewaris
                'pewaris_nik' => $request->pewaris_nik,
                'pewaris_tempat_lahir' => $request->pewaris_tempat_lahir,
                'pewaris_tanggal_lahir' => $request->pewaris_tanggal_lahir,
                'pewaris_nama_lengkap' => $request->pewaris_nama_lengkap,
                'pewaris_gelar' => $request->pewaris_gelar,
                'pewaris_tempat_tinggal_terakhir' => $request->pewaris_tempat_tinggal_terakhir,
                'pewaris_tanggal_kematian' => $request->pewaris_tanggal_kematian,
                'pewaris_tempat_kematian' => $request->pewaris_tempat_kematian,
                'pewaris_nomor_akta_kematian' => $request->pewaris_nomor_akta_kematian,
                'pewaris_tanggal_terbit_akta_kematian' => $request->pewaris_tanggal_terbit_akta_kematian,

                // Data Saksi
                'data_saksi' => json_encode([
                    'saksi1' => [
                        'nama_lengkap' => $request->saksi1_nama_lengkap,
                        'gelar' => $request->saksi1_gelar,
                        'alamat' => $request->saksi1_alamat,
                    ],
                    'saksi2' => [
                        'nama_lengkap' => $request->saksi2_nama_lengkap,
                        'gelar' => $request->saksi2_gelar,
                        'alamat' => $request->saksi2_alamat,
                    ]
                ]),

                'status' => $request->submit_type === 'draft' ? 'draft' : 'submitted',
                'submitted_at' => $request->submit_type === 'submit' ? now() : null,
            ];

            $skaw = Skaw::create($skawData);

            // Save data anak if any
            if ($request->has('data_anak') && is_array($request->data_anak)) {
                foreach ($request->data_anak as $index => $anakData) {
                    if (!empty($anakData['nama_lengkap'])) {
                        SkawAnak::create([
                            'skaw_id' => $skaw->id,
                            'nama_lengkap' => $anakData['nama_lengkap'],
                            'tempat_lahir' => $anakData['tempat_lahir'],
                            'tanggal_lahir' => $anakData['tanggal_lahir'],
                            'jenis_kelamin' => $anakData['jenis_kelamin'],
                            'alamat' => $anakData['alamat'],
                            'urutan' => $index + 1,
                        ]);
                    }
                }
            }

            // Handle file uploads
            $this->handleFileUploads($request, $skaw);

            // Log activity
            $skaw->activityLogs()->create([
                'user_id' => Auth::id(),
                'action' => $request->submit_type === 'draft' ? 'draft_created' : 'submitted',
                'description' => $request->submit_type === 'draft' ?
                    'SKAW disimpan sebagai draft' :
                    'SKAW diajukan untuk diproses',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            $message = $request->submit_type === 'draft' ?
                'SKAW berhasil disimpan sebagai draft.' :
                'SKAW berhasil diajukan dengan nomor: ' . $skaw->nomor_surat;

            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('skaw.permohonan-saya')
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating SKAW: ' . $e->getMessage());
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
    public function show(Request $request, Skaw $skaw)
    {
        try {
            // Authorization check
            $this->authorizeAccess($skaw);

            // Load relationships
            $skaw->load([
                'user', 'anakList', 'files', 'activityLogs.user',
                'frontOfficeApprover', 'lurahApprover', 'camatApprover'
            ]);

            // Return JSON for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $skaw
                ]);
            }

            return view('SKAW.show', [
                'type_menu' => 'skaw',
                'pageTitle' => 'Detail SKAW',
                'skaw' => $skaw,
                'userRole' => Auth::user()->role,
            ]);

        } catch (Exception $e) {
            Log::error('Error in SKAW show method: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('skaw.permohonan-saya')
                        ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Skaw $skaw)
    {
        // Authorization check
        if (!$skaw->canBeEditedBy(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit SKAW ini.');
        }

        $skaw->load(['anakList', 'files']);

        return view('Skaw.edit', [
            'type_menu' => 'skaw',
            'pageTitle' => 'Edit SKAW',
            'skaw' => $skaw,
            'fileTypeLabels' => SkawFile::getFileTypeLabels(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Skaw $skaw)
    {
        if (!$skaw->canBeEditedBy(Auth::user())) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk mengedit SKAW ini.'
            ], 403);
        }

        $validator = $this->validateSkawRequest($request, $skaw);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Update main SKAW data
            $updateData = $request->only([
                'nama_lengkap', 'nik', 'alamat', 'pekerjaan', 'jenis_kelamin',
                'tempat_lahir', 'tanggal_lahir', 'agama', 'status_perkawinan',
                'kewarganegaraan', 'nomor_kk', 'rt', 'rw', 'email', 'no_telepon',
                'nomor_akta_perkawinan', 'tanggal_terbit_akta_kematian', 'jumlah_anak',
                'pewaris_nik', 'pewaris_tempat_lahir', 'pewaris_tanggal_lahir',
                'pewaris_nama_lengkap', 'pewaris_gelar', 'pewaris_tempat_tinggal_terakhir',
                'pewaris_tanggal_kematian', 'pewaris_tempat_kematian',
                'pewaris_nomor_akta_kematian', 'pewaris_tanggal_terbit_akta_kematian',
                'saksi_nama_lengkap', 'saksi_gelar', 'saksi_alamat'
            ]);

            // Handle status change
            if ($request->submit_type === 'submit' && $skaw->status === 'draft') {
                $updateData['status'] = 'submitted';
                $updateData['submitted_at'] = now();
            }

            $skaw->update($updateData);

            // Update data anak
            $this->updateDataAnak($request, $skaw);

            // Handle new file uploads
            $this->handleFileUploads($request, $skaw);

            // Log activity
            $skaw->activityLogs()->create([
                'user_id' => Auth::id(),
                'action' => 'updated',
                'description' => 'SKAW diperbarui',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            $message = $request->submit_type === 'submit' && $skaw->wasChanged('status') ?
                'SKAW berhasil diperbarui dan diajukan untuk diproses.' :
                'SKAW berhasil diperbarui.';

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating SKAW: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Skaw $skaw)
    {
        if (!$skaw->canBeEditedBy(Auth::user()) && Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus SKAW ini.'
            ], 403);
        }

        DB::beginTransaction();
        try {
            $skaw->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'SKAW berhasil dihapus.'
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting SKAW: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // ================================
    // WORKFLOW METHODS
    // ================================

    /**
     * Front Office Approval
     */
    public function frontOfficeApprove(Request $request, Skaw $skaw)
    {
        if (Auth::user()->role !== 'Front Office' && Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        if (!$skaw->canBeApprovedByFrontOffice()) {
            return response()->json([
                'success' => false,
                'message' => 'SKAW tidak dapat disetujui pada tahap ini.'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'nomor_register_kelurahan' => 'required|string|max:255',
            'front_office_notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Update status and generate documents
            $skaw->update([
                'status' => 'front_office_approved',
                'front_office_approved_at' => now(),
                'front_office_approved_by' => Auth::id(),
                'front_office_notes' => $request->front_office_notes,
                'nomor_register_kelurahan' => $request->nomor_register_kelurahan,
            ]);

            // Generate Tanda Terima and SKAW Draft
            $this->generateTandaTerima($skaw);
            $this->generateSkawDraft($skaw);

            // Update status to skaw_generated
            $skaw->update([
                'status' => 'skaw_generated',
                'skaw_generated_at' => now(),
            ]);

            // Log activity
            $skaw->activityLogs()->create([
                'user_id' => Auth::id(),
                'action' => 'front_office_approved',
                'description' => 'SKAW disetujui Front Office dan dokumen awal dibuat',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'SKAW berhasil disetujui. Tanda Terima dan SKAW Draft telah dibuat.'
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error in Front Office approval: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // ================================
    // PRIVATE HELPER METHODS
    // ================================

    private function applyViewFilter($query, $userRole, $viewType)
    {
        switch ($viewType) {
            case 'permohonan_saya':
                if ($userRole === 'admin') {
                    // Admin can see all
                } else {
                    $query->where('user_id', Auth::id());
                }
                break;

            case 'semua_permohonan':
                // Front Office, Back Office, Lurah, Camat can see all submitted SKAW
                $query->where('status', '!=', 'draft');
                break;

            case 'daftar_sidang':
                $query->whereIn('status', ['jadwal_sidang_created', 'sidang_selesai']);
                break;

            case 'telah_sidang':
                $query->whereIn('status', ['evidence_uploaded', 'lurah_approved', 'camat_approved']);
                break;

            case 'skaw_jadi':
                if ($userRole === 'user') {
                    $query->where('user_id', Auth::id())
                          ->whereIn('status', ['skaw_final', 'completed']);
                } else {
                    $query->whereIn('status', ['skaw_final', 'completed']);
                }
                break;
        }
    }

    private function getButuhActionCount($baseQuery, $userRole)
    {
        switch ($userRole) {
            case 'Front Office':
                return (clone $baseQuery)->where('status', 'submitted')->count();
            case 'Back Office':
                return (clone $baseQuery)->where('status', 'skaw_generated')->count() +
                       (clone $baseQuery)->where('status', 'camat_approved')->count();
            case 'Lurah':
                return (clone $baseQuery)->where('status', 'evidence_uploaded')->count();
            case 'Camat':
                return (clone $baseQuery)->where('status', 'lurah_approved')->count();
            default:
                return 0;
        }
    }

    private function getSedangProsesCount($baseQuery, $userRole)
    {
        return (clone $baseQuery)->whereIn('status', [
            'front_office_approved', 'skaw_generated', 'jadwal_sidang_created',
            'sidang_selesai', 'evidence_uploaded', 'lurah_approved', 'camat_approved', 'skaw_final'
        ])->count();
    }

    private function buildFileInfoColumn($row)
    {
        try {
            $statusIcon = $this->getStatusIcon($row->status);
            $statusColor = $this->getStatusColor($row->status);

            return '<div class="d-flex align-items-center">
                        <i class="fas ' . $statusIcon . ' ' . $statusColor . ' mr-2"></i>
                        <div>
                            <div class="font-weight-bold">' . ($row->nomor_surat ?? 'No Number') . '</div>
                            <small class="text-muted">SKAW</small>
                        </div>
                    </div>';
        } catch (Exception $e) {
            Log::error('Error building file info column for SKAW ID ' . ($row->id ?? 'unknown') . ': ' . $e->getMessage());
            return '<div class="d-flex align-items-center">
                        <i class="fas fa-file text-secondary mr-2"></i>
                        <div>
                            <div class="font-weight-bold">Error loading data</div>
                            <small class="text-muted">Please contact admin</small>
                        </div>
                    </div>';
        }
    }

    private function buildNomorSuratColumn($row)
    {
        try {
            $html = '<div class="font-weight-bold">' . ($row->nomor_surat ?? 'No Number') . '</div>';
            $html .= '<small class="text-muted">SKAW - Surat Keterangan Ahli Waris</small>';
            return $html;
        } catch (Exception $e) {
            Log::error('Error building nomor surat column: ' . $e->getMessage());
            return '<div class="font-weight-bold">Error loading data</div>';
        }
    }

    private function buildPemohonInfoColumn($row)
    {
        try {
            $html = '<div class="font-weight-bold">' . ($row->nama_lengkap ?? 'Unknown') . '</div>';
            $html .= '<small class="text-muted">NIK: ' . ($row->nik ?? '-') . '</small><br>';
            $html .= '<small class="text-muted">RT ' . sprintf('%02d', $row->rt ?? 0) . ' / RW ' . sprintf('%02d', $row->rw ?? 0) . '</small>';
            return $html;
        } catch (Exception $e) {
            Log::error('Error building pemohon info column: ' . $e->getMessage());
            return '<div class="font-weight-bold">Error loading data</div>';
        }
    }

    private function buildPewarisInfoColumn($row)
    {
        try {
            $html = '<div class="font-weight-bold">' . ($row->pewaris_nama_lengkap ?? 'Unknown') . '</div>';
            if ($row->pewaris_gelar) {
                $html .= '<small class="text-muted">' . $row->pewaris_gelar . '</small><br>';
            }
            $html .= '<small class="text-muted">NIK: ' . ($row->pewaris_nik ?? '-') . '</small>';
            return $html;
        } catch (Exception $e) {
            Log::error('Error building pewaris info column: ' . $e->getMessage());
            return '<div class="font-weight-bold">Error loading data</div>';
        }
    }

    private function buildTanggalColumn($row)
    {
        try {
            if (!$row->created_at) {
                return '-';
            }

            if (is_string($row->created_at)) {
                $carbon = Carbon::parse($row->created_at);
            } else {
                $carbon = $row->created_at;
            }

            return $carbon->format('d/m/Y H:i');
        } catch (Exception $e) {
            Log::error('Error formatting date for SKAW ID ' . ($row->id ?? 'unknown') . ': ' . $e->getMessage());
            return '-';
        }
    }

    private function buildWorkflowColumn($row)
    {
        try {
            $progress = $row->workflow_progress;
            $html = '<div class="workflow-steps">';

            // Step indicators
            $steps = [
                'submitted' => ['Diajukan', 'fa-file-upload'],
                'front_office_approved' => ['Front Office', 'fa-check'],
                'skaw_generated' => ['SKAW Draft', 'fa-file-alt'],
                'jadwal_sidang_created' => ['Jadwal Sidang', 'fa-calendar'],
                'sidang_completed' => ['Sidang', 'fa-gavel'],
                'evidence_uploaded' => ['Evidence', 'fa-camera'],
                'lurah_approved' => ['Lurah', 'fa-user-tie'],
                'camat_approved' => ['Camat', 'fa-user-check'],
                'completed' => ['Selesai', 'fa-check-circle'],
            ];

            foreach ($steps as $key => $step) {
                $badgeClass = $progress[$key] ? 'badge-success' : 'badge-secondary';
                $html .= '<span class="badge ' . $badgeClass . ' mr-1 mb-1">
                            <i class="fas ' . $step[1] . '"></i> ' . $step[0] . '
                        </span>';
            }

            $html .= '</div>';
            return $html;
        } catch (Exception $e) {
            Log::error('Error building workflow column: ' . $e->getMessage());
            return '<div class="workflow-steps">
                        <span class="badge badge-danger">
                            <i class="fas fa-exclamation-triangle"></i> Error
                        </span>
                    </div>';
        }
    }

    private function buildActionsColumn($row, $userRole, $viewType)
    {
        $buttons = [];

        // Detail button - always available
        $buttons[] = '<a href="' . route('skaw.show', $row->id) . '"
                        class="btn btn-info btn-sm mb-1"
                        title="Lihat Detail">
                        <i class="fas fa-eye"></i> Detail
                    </a>';

        // Edit button - only if can be edited
        if ($row->canBeEditedBy(Auth::user())) {
            $buttons[] = '<a href="' . route('skaw.edit', $row->id) . '"
                            class="btn btn-warning btn-sm mb-1"
                            title="Edit">
                            <i class="fas fa-pencil-alt"></i> Edit
                        </a>';
        }

        // Preview documents
        if ($row->file_tanda_terima) {
            $buttons[] = '<a href="' . route('skaw.preview-tanda-terima', $row->id) . '"
                            class="btn btn-info btn-sm mb-1"
                            title="Preview Tanda Terima"
                            target="_blank">
                            <i class="fas fa-receipt"></i> Tanda Terima
                        </a>';
        }

        if ($row->file_skaw_draft) {
            $buttons[] = '<a href="' . route('skaw.preview-draft', $row->id) . '"
                            class="btn btn-secondary btn-sm mb-1"
                            title="Preview SKAW Draft"
                            target="_blank">
                            <i class="fas fa-file-alt"></i> Draft SKAW
                        </a>';
        }

        if ($row->file_skaw_final) {
            $buttons[] = '<a href="' . route('skaw.preview-final', $row->id) . '"
                            class="btn btn-success btn-sm mb-1"
                            title="Download SKAW Final"
                            target="_blank">
                            <i class="fas fa-download"></i> SKAW Final
                        </a>';
        }

        // Action buttons based on role and status (existing code...)
        $buttons = array_merge($buttons, $this->getActionButtons($row, $userRole));

        // Delete button - only if can be deleted
        if ($row->canBeDeletedBy(Auth::user())) {
            $buttons[] = '<form action="' . route('skaw.destroy', $row->id) . '"
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

    private function getActionButtons($row, $userRole)
    {
        $buttons = [];

        switch ($userRole) {
            case 'Front Office':
                if ($row->canBeApprovedByFrontOffice()) {
                    $buttons[] = '<button type="button" class="btn btn-success btn-sm btn-approve-front-office mb-1"
                                    data-id="' . $row->id . '"
                                    data-name="' . $row->nama_lengkap . '"
                                    title="Approve Front Office">
                                    <i class="fas fa-check"></i> Approve
                                </button>';
                }

                if ($row->canCreateJadwalSidang()) {
                    $buttons[] = '<button type="button" class="btn btn-primary btn-sm btn-create-jadwal mb-1"
                                    data-id="' . $row->id . '"
                                    data-name="' . $row->nama_lengkap . '"
                                    title="Buat Jadwal Sidang">
                                    <i class="fas fa-calendar-plus"></i> Jadwal Sidang
                                </button>';
                }
                break;

            case 'Back Office':
                if ($row->canUploadEvidence()) {
                    $buttons[] = '<button type="button" class="btn btn-warning btn-sm btn-upload-evidence mb-1"
                                    data-id="' . $row->id . '"
                                    data-name="' . $row->nama_lengkap . '"
                                    title="Upload Evidence Sidang">
                                    <i class="fas fa-camera"></i> Upload Evidence
                                </button>';
                }

                if ($row->canUploadSkawFinal()) {
                    $buttons[] = '<button type="button" class="btn btn-success btn-sm btn-upload-final mb-1"
                                    data-id="' . $row->id . '"
                                    data-name="' . $row->nama_lengkap . '"
                                    title="Upload SKAW Final">
                                    <i class="fas fa-upload"></i> Upload Final
                                </button>';
                }
                break;

            case 'Lurah':
                if ($row->canBeApprovedByLurah()) {
                    $buttons[] = '<button type="button" class="btn btn-success btn-sm btn-approve-lurah mb-1"
                                    data-id="' . $row->id . '"
                                    data-name="' . $row->nama_lengkap . '"
                                    title="Approve Lurah">
                                    <i class="fas fa-user-tie"></i> Approve Lurah
                                </button>';
                }
                break;

            case 'Camat':
                if ($row->canBeApprovedByCamat()) {
                    $buttons[] = '<button type="button" class="btn btn-success btn-sm btn-approve-camat mb-1"
                                    data-id="' . $row->id . '"
                                    data-name="' . $row->nama_lengkap . '"
                                    title="Approve Camat">
                                    <i class="fas fa-user-check"></i> Approve Camat
                                </button>';
                }
                break;
        }

        return $buttons;
    }

    private function getStatusIcon($status)
    {
        $icons = [
            'draft' => 'fa-edit',
            'submitted' => 'fa-file-upload',
            'front_office_approved' => 'fa-check',
            'skaw_generated' => 'fa-file-alt',
            'jadwal_sidang_created' => 'fa-calendar',
            'sidang_selesai' => 'fa-gavel',
            'evidence_uploaded' => 'fa-camera',
            'lurah_approved' => 'fa-user-tie',
            'camat_approved' => 'fa-user-check',
            'skaw_final' => 'fa-file-pdf',
            'completed' => 'fa-check-circle',
        ];

        return $icons[$status] ?? 'fa-file';
    }

    private function getStatusColor($status)
    {
        $colors = [
            'draft' => 'text-secondary',
            'submitted' => 'text-primary',
            'front_office_approved' => 'text-info',
            'skaw_generated' => 'text-warning',
            'jadwal_sidang_created' => 'text-primary',
            'sidang_selesai' => 'text-info',
            'evidence_uploaded' => 'text-warning',
            'lurah_approved' => 'text-success',
            'camat_approved' => 'text-success',
            'skaw_final' => 'text-success',
            'completed' => 'text-success',
        ];

        return $colors[$status] ?? 'text-secondary';
    }

    private function validateSkawRequest(Request $request, Skaw $skaw = null)
    {
        $rules = [
            // Data Pemohon
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|max:20',
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
            'email' => 'required|email|max:255',
            'no_telepon' => 'nullable|string|max:20',

            // Data khusus SKAW
            'nomor_akta_perkawinan' => 'required|regex:/^[0-9]{4}-KW-[0-9]{8}-[0-9]{4}$/',
            'tanggal_terbit_akta_perkawinan' => 'required|date',
            'jumlah_anak' => 'required|integer|min:0|max:10',

            // Data Pewaris
            'pewaris_nik' => 'required|string|max:20',
            'pewaris_tempat_lahir' => 'required|string|max:255',
            'pewaris_tanggal_lahir' => 'required|date',
            'pewaris_nama_lengkap' => 'required|string|max:255',
            'pewaris_gelar' => 'nullable|string|max:100',
            'pewaris_tempat_tinggal_terakhir' => 'required|string',
            'pewaris_tanggal_kematian' => 'required|date',
            'pewaris_tempat_kematian' => 'required|string|max:255',
            'pewaris_nomor_akta_kematian' => 'required|regex:/^[0-9]{4}-KM-[0-9]{8}-[0-9]{4}$/',
            'pewaris_tanggal_terbit_akta_kematian' => 'required|date',

            // Data Saksi
            'saksi1_nama_lengkap' => 'required|string|max:255',
            'saksi1_gelar' => 'nullable|string|max:100',
            'saksi1_alamat' => 'required|string',
            'saksi2_nama_lengkap' => 'required|string|max:255',
            'saksi2_gelar' => 'nullable|string|max:100',
            'saksi2_alamat' => 'required|string',

            // Data Anak (dynamic)
            'data_anak.*.nama_lengkap' => 'required_with:data_anak.*.tempat_lahir|string|max:255',
            'data_anak.*.tempat_lahir' => 'required_with:data_anak.*.nama_lengkap|string|max:255',
            'data_anak.*.tanggal_lahir' => 'required_with:data_anak.*.nama_lengkap|date',
            'data_anak.*.jenis_kelamin' => 'required_with:data_anak.*.nama_lengkap|in:L,P',
            'data_anak.*.alamat' => 'required_with:data_anak.*.nama_lengkap|string',

            // Files
            'files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];

        return Validator::make($request->all(), $rules);
    }

    private function handleFileUploads(Request $request, Skaw $skaw)
    {
        if (!$request->hasFile('files')) {
            return;
        }

        $fileTypeLabels = SkawFile::getFileTypeLabels();

        foreach ($request->file('files') as $fileType => $file) {
            if ($file && $file->isValid()) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('skaw/requirements', $fileName, 'public');

                SkawFile::create([
                    'skaw_id' => $skaw->id,
                    'file_type' => $fileType,
                    'file_name' => $fileName,
                    'file_path' => $filePath,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'uploaded_at' => now(),
                ]);
            }
        }
    }

    private function updateDataAnak(Request $request, Skaw $skaw)
    {
        // Delete existing data anak
        $skaw->anakList()->delete();

        // Add new data anak
        if ($request->has('data_anak') && is_array($request->data_anak)) {
            foreach ($request->data_anak as $index => $anakData) {
                if (!empty($anakData['nama_lengkap'])) {
                    SkawAnak::create([
                        'skaw_id' => $skaw->id,
                        'nama_lengkap' => $anakData['nama_lengkap'],
                        'tempat_lahir' => $anakData['tempat_lahir'],
                        'tanggal_lahir' => $anakData['tanggal_lahir'],
                        'jenis_kelamin' => $anakData['jenis_kelamin'],
                        'alamat' => $anakData['alamat'],
                        'urutan' => $index + 1,
                    ]);
                }
            }
        }

        // Update jumlah_anak
        $skaw->update(['jumlah_anak' => $request->jumlah_anak ?? 0]);
    }

    private function authorizeAccess(Skaw $skaw)
    {
        $userRole = Auth::user()->role;

        if ($userRole === 'user' && $skaw->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk melihat SKAW ini.');
        }

        // Admin and office roles can access all
        if (in_array($userRole, ['admin', 'Front Office', 'Back Office', 'Lurah', 'Camat'])) {
            return;
        }
    }

    private function generateTandaTerima(Skaw $skaw)
    {
        try {
            // Generate PDF Tanda Terima
            $pdf = PDF::loadView('Skaw.TandaTerima', compact('skaw'));

            $fileName = 'tanda_terima_' . str_replace(['/', '\\'], '_', $skaw->nomor_surat) . '_' . time() . '.pdf';
            $filePath = 'skaw/tanda_terima/' . $fileName;

            Storage::disk('public')->put($filePath, $pdf->output());

            $skaw->update(['file_tanda_terima' => $filePath]);

            return $filePath;

        } catch (Exception $e) {
            Log::error('Error generating Tanda Terima: ' . $e->getMessage());
            throw $e;
        }
    }

    private function generateSkawDraft(Skaw $skaw)
    {
        try {
            // Generate PDF SKAW Draft
            $pdf = PDF::loadView('Skaw.SkawDraft', compact('skaw'));

            $fileName = 'skaw_draft_' . str_replace(['/', '\\'], '_', $skaw->nomor_surat) . '_' . time() . '.pdf';
            $filePath = 'skaw/draft/' . $fileName;

            Storage::disk('public')->put($filePath, $pdf->output());

            $skaw->update(['file_skaw_draft' => $filePath]);

            return $filePath;

        } catch (Exception $e) {
            Log::error('Error generating SKAW Draft: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Preview Tanda Terima
     */
    public function previewTandaTerima(Skaw $skaw)
    {
        try {
            $this->authorizeAccess($skaw);

            if (!$skaw->file_tanda_terima || !Storage::disk('public')->exists($skaw->file_tanda_terima)) {
                return redirect()->back()->with('error', 'Tanda Terima tidak ditemukan.');
            }

            $pdf = PDF::loadView('Skaw.TandaTerima', compact('skaw'));
            $cleanNomorSurat = str_replace(['/', '\\'], '_', $skaw->nomor_surat);
            $fileName = 'preview_tanda_terima_' . $cleanNomorSurat . '.pdf';

            return $pdf->stream($fileName);

        } catch (Exception $e) {
            Log::error('Error previewing Tanda Terima: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat preview Tanda Terima: ' . $e->getMessage());
        }
    }

    /**
     * Preview SKAW Draft
     */
    public function previewDraft(Skaw $skaw)
    {
        try {
            $this->authorizeAccess($skaw);

            if (!$skaw->file_skaw_draft || !Storage::disk('public')->exists($skaw->file_skaw_draft)) {
                return redirect()->back()->with('error', 'SKAW Draft tidak ditemukan.');
            }

            $pdf = PDF::loadView('Skaw.SkawDraft', compact('skaw'));
            $cleanNomorSurat = str_replace(['/', '\\'], '_', $skaw->nomor_surat);
            $fileName = 'preview_skaw_draft_' . $cleanNomorSurat . '.pdf';

            return $pdf->stream($fileName);

        } catch (Exception $e) {
            Log::error('Error previewing SKAW Draft: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat preview SKAW Draft: ' . $e->getMessage());
        }
    }
}
