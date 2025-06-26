<?php

namespace App\Http\Controllers;

use App\Models\Puntadewa;
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

class PuntadewaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('Puntadewa.index', [
            'type_menu' => 'puntadewa',
            'pageTitle' => 'Data Pernyataan Tempat Tinggal Penduduk Non Permanen (PUNTADEWA)'
        ]);
    }

    /**
     * Get data for DataTables
     */
    public function getData()
    {
        $query = Puntadewa::with(['user', 'approverRT', 'approverRW'])
                 ->orderBy('created_at', 'desc');

        // Filter berdasarkan role - hanya role yang diizinkan
        $userRole = Auth::user()->role;

        if ($userRole === 'user') {
            $query->byUser(Auth::id());
        } elseif ($userRole === 'Ketua RT') {
            $userRT = Auth::user()->rt;
            $query->where('rt', $userRT)
                ->whereIn('status', ['pending_rt', 'approved_rt', 'pending_rw', 'approved_rw', 'rejected_rt', 'rejected_rw']);
        } elseif ($userRole === 'Ketua RW') {
            $userRW = Auth::user()->rw;
            $query->where('rw', $userRW)
                ->whereIn('status', ['approved_rt', 'approved_rw', 'rejected_rw']);
        } elseif (in_array($userRole, ['Front Office', 'Operator', 'admin'])) {
            // Front Office & Operator: Lihat semua data
        } else {
            // SEMUA ROLE LAIN TIDAK BISA AKSES DATA
            $query->where('id', 0); // Block semua role yang tidak didefinisikan
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('file_info', function ($row) {
                return '<div class="d-flex align-items-center">
                            <i class="fas fa-file-pdf text-danger mr-2"></i>
                            <div>
                                <div class="font-weight-bold">' . $row->nomor_surat . '</div>
                                <small class="text-muted">' . $row->nama_pemohon . '</small>
                            </div>
                        </div>';
            })
            ->addColumn('nomor_judul', function ($row) {
                $html = '<div class="font-weight-bold">' . $row->nomor_surat . '</div>';
                $html .= '<div class="text-muted">' . \Str::limit($row->alasan_tinggal, 50) . '</div>';
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
                $buttons[] = '<a href="' . route('puntadewa.show', $row->id) . '"
                                class="btn btn-info btn-sm mb-1"
                                title="Lihat Detail">
                                <i class="fas fa-eye"></i> Detail
                            </a>';

                // Edit button - hanya untuk pemohon dan status pending_rt
                if ($userRole === 'user' && $row->user_id === Auth::id() && $row->canBeEdited()) {
                    $buttons[] = '<a href="' . route('puntadewa.edit', $row->id) . '"
                                    class="btn btn-warning btn-sm mb-1"
                                    title="Edit">
                                    <i class="fas fa-pencil-alt"></i> Edit
                                </a>';
                }

                // Preview PDF button - bisa preview kapan saja
                if ($row->canPreviewPDF()) {
                    $buttons[] = '<a href="' . route('puntadewa.preview-pdf', $row->id) . '"
                                    class="btn btn-secondary btn-sm mb-1"
                                    title="Preview PDF"
                                    target="_blank">
                                    <i class="fas fa-eye"></i> Preview
                                </a>';
                }

                // Download PDF button - hanya jika sudah fully approved
                if ($row->canDownloadPDF()) {
                    $buttons[] = '<a href="' . route('puntadewa.download-pdf', $row->id) . '"
                                    class="btn btn-success btn-sm mb-1"
                                    title="Download PDF">
                                    <i class="fas fa-download"></i> Download
                                </a>';
                }

                // RT Approve/Reject buttons
                if ($userRole === 'Ketua RT' && $row->canBeApprovedByRT() && Auth::user()->rt == $row->rt) {
                    $buttons[] = '<button type="button" class="btn btn-success btn-sm btn-approve-rt mb-1"
                                    data-id="' . $row->id . '"
                                    data-name="' . $row->nama_pemohon . '"
                                    title="Setujui sebagai RT">
                                    <i class="fas fa-check"></i> Approve RT
                                </button>';

                    $buttons[] = '<button type="button" class="btn btn-danger btn-sm btn-reject-rt mb-1"
                                    data-id="' . $row->id . '"
                                    data-name="' . $row->nama_pemohon . '"
                                    title="Tolak sebagai RT">
                                    <i class="fas fa-times"></i> Reject RT
                                </button>';
                }

                // RW Approve/Reject buttons
                if ($userRole === 'Ketua RW' && $row->canBeApprovedByRW() && Auth::user()->rw == $row->rw) {
                    $buttons[] = '<button type="button" class="btn btn-success btn-sm btn-approve-rw mb-1"
                                    data-id="' . $row->id . '"
                                    data-name="' . $row->nama_pemohon . '"
                                    title="Setujui sebagai RW">
                                    <i class="fas fa-check"></i> Approve RW
                                </button>';

                    $buttons[] = '<button type="button" class="btn btn-danger btn-sm btn-reject-rw mb-1"
                                    data-id="' . $row->id . '"
                                    data-name="' . $row->nama_pemohon . '"
                                    title="Tolak sebagai RW">
                                    <i class="fas fa-times"></i> Reject RW
                                </button>';
                }

                // Delete button - hanya untuk data pending_rt atau admin/ketua dan yang mengajukan
                if (($userRole === 'user' && $row->user_id === Auth::id() && $row->canBeEdited()) ||
                    in_array($userRole, ['admin'])) {
                    $buttons[] = '<form action="' . route('puntadewa.destroy', $row->id) . '"
                                    method="POST" class="d-inline">
                                    ' . csrf_field() . '
                                    ' . method_field('DELETE') . '
                                    <button type="button" class="btn btn-danger btn-sm btn-delete mb-1"
                                            data-name="' . $row->nama_pemohon . '"
                                            title="Delete">
                                            <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>';
                }

                return '<div class="d-flex flex-column gap-1">' . implode('', $buttons) . '</div>';
            })
            ->rawColumns(['file_info', 'nomor_judul', 'jenis', 'status', 'workflow', 'actions'])
            ->make(true);
    }

    /**
     * Get summary statistics
     */
    public function getSummary()
    {
        try {
            $baseQuery = Puntadewa::query();

            // Filter berdasarkan role
            $userRole = Auth::user()->role;

            if ($userRole === 'user') {
                $baseQuery->where('user_id', Auth::id());
            } elseif ($userRole === 'Ketua RT') {
                $userRT = Auth::user()->rt;
                $baseQuery->where('rt', $userRT);
            } elseif ($userRole === 'Ketua RW') {
                $userRW = Auth::user()->rw;
                $baseQuery->where('rw', $userRW);
            }

            // Clone query untuk setiap perhitungan agar tidak saling mempengaruhi
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        // Dapatkan data RT dan RW yang tersedia
        $rtRwOptions = DataKependudukan::select('total_rt', 'total_rw')->first();

        $availableRT = [];
        $availableRW = [];

        if ($rtRwOptions) {
            // Generate options RT (1 sampai total_rt)
            for ($i = 1; $i <= $rtRwOptions->total_rt; $i++) {
                $availableRT[] = sprintf('%02d', $i);
            }

            // Generate options RW (1 sampai total_rw)
            for ($i = 1; $i <= $rtRwOptions->total_rw; $i++) {
                $availableRW[] = sprintf('%02d', $i);
            }
        }

        return view('Puntadewa.create', [
            'type_menu' => 'puntadewa',
            'pageTitle' => 'Tambah Pernyataan Tempat Tinggal Non Permanen',
            'user' => $user,
            'availableRT' => $availableRT,
            'availableRW' => $availableRW
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_pemohon' => 'required|string|max:255',
            'nik' => 'required|string|max:16',
            'alamat_asal' => 'required|string',
            'alasan_tinggal' => 'required|string',
            'rt' => 'required|string|max:3',
            'rw' => 'required|string|max:3',
            'nama_perusahaan' => 'nullable|string|max:255',
            'alamat_perusahaan' => 'nullable|string',
            'nama_sekolah' => 'nullable|string|max:255',
            'alamat_sekolah' => 'nullable|string',
            'nama_rumah_sakit' => 'nullable|string|max:255',
            'alamat_rumah_sakit' => 'nullable|string',
            'alasan_lainnya' => 'nullable|string',
            'nama_penjamin' => 'required|string|max:255',
            'nik_penjamin' => 'required|string|max:16',
            'alamat_penjamin' => 'required|string',
            'no_telp_penjamin' => 'required|string|max:15',
            'file_kk_asal' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ttd_pemohon' => 'required|string',
            'ttd_pemilik_kost' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'alamat_lokasi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Upload file KK
            $fileKK = $request->file('file_kk_asal');
            $fileKKName = 'kk_asal_' . time() . '_' . Auth::id() . '.' . $fileKK->getClientOriginalExtension();
            $fileKKPath = $fileKK->storeAs('puntadewa/kk_asal', $fileKKName, 'public');

            // Create Puntadewa record
            $puntadewa = Puntadewa::create([
                'nomor_surat' => Puntadewa::generateNomorSurat(),
                'user_id' => Auth::id(),
                'nama_pemohon' => $request->nama_pemohon,
                'nik' => $request->nik,
                'alamat_asal' => $request->alamat_asal,
                'alasan_tinggal' => $request->alasan_tinggal,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'nama_perusahaan' => $request->nama_perusahaan,
                'alamat_perusahaan' => $request->alamat_perusahaan,
                'nama_sekolah' => $request->nama_sekolah,
                'alamat_sekolah' => $request->alamat_sekolah,
                'nama_rumah_sakit' => $request->nama_rumah_sakit,
                'alamat_rumah_sakit' => $request->alamat_rumah_sakit,
                'alasan_lainnya' => $request->alasan_lainnya,
                'nama_penjamin' => $request->nama_penjamin,
                'nik_penjamin' => $request->nik_penjamin,
                'alamat_penjamin' => $request->alamat_penjamin,
                'no_telp_penjamin' => $request->no_telp_penjamin,
                'file_kk_asal' => $fileKKPath,
                'ttd_pemohon' => $request->ttd_pemohon,
                'ttd_pemilik_kost' => $request->ttd_pemilik_kost,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'alamat_lokasi' => $request->alamat_lokasi,
                'status' => 'pending_rt',
            ]);

            // Sync to UserApplication
            $this->syncToUserApplication($puntadewa);

            return response()->json([
                'success' => true,
                'message' => 'Permohonan PUNTADEWA berhasil diajukan dengan nomor: ' . $puntadewa->nomor_surat
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Puntadewa $puntadewa)
    {
        $userRole = Auth::user()->role;

        // Check authorization
        if ($userRole === 'user' && $puntadewa->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data ini.');
        }

        // Check authorization untuk RT
        if ($userRole === 'Ketua RT') {
            if (Auth::user()->rt != $puntadewa->rt) {
                abort(403, 'Anda hanya dapat melihat data dari RT Anda.');
            }
        }

        // Check authorization untuk RW
        if ($userRole === 'Ketua RW') {
            if (Auth::user()->rw != $puntadewa->rw) {
                abort(403, 'Anda hanya dapat melihat data dari RW Anda.');
            }
        }

        $puntadewa->load(['user', 'approverRT', 'approverRW']);

        // Check if user can approve
        $canApproveRT = ($userRole === 'Ketua RT' && $puntadewa->canBeApprovedByRT() && Auth::user()->rt == $puntadewa->rt);
        $canApproveRW = ($userRole === 'Ketua RW' && $puntadewa->canBeApprovedByRW() && Auth::user()->rw == $puntadewa->rw);

        return view('Puntadewa.show', [
            'type_menu' => 'puntadewa',
            'pageTitle' => 'Detail Pernyataan Tempat Tinggal Non Permanen',
            'puntadewa' => $puntadewa,
            'canApproveRT' => $canApproveRT,
            'canApproveRW' => $canApproveRW
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Puntadewa $puntadewa)
    {
        // Check authorization
        if (Auth::user()->role === 'user' && $puntadewa->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit data ini.');
        }

        if (!$puntadewa->canBeEdited()) {
            return redirect()->route('puntadewa.index')
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

        return view('puntadewa.edit', [
            'type_menu' => 'puntadewa',
            'pageTitle' => 'Edit Pernyataan Tempat Tinggal Non Permanen',
            'puntadewa' => $puntadewa,
            'availableRT' => $availableRT,
            'availableRW' => $availableRW
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Puntadewa $puntadewa)
    {
        // Check authorization
        if (Auth::user()->role === 'user' && $puntadewa->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk mengedit data ini.'
            ], 403);
        }

        if (!$puntadewa->canBeEdited()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak dapat diedit karena sudah diproses.'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'nama_pemohon' => 'required|string|max:255',
            'nik' => 'required|string|max:16',
            'alamat_asal' => 'required|string',
            'alasan_tinggal' => 'required|string',
            'rt' => 'required|string|max:3',
            'rw' => 'required|string|max:3',
            'nama_perusahaan' => 'nullable|string|max:255',
            'alamat_perusahaan' => 'nullable|string',
            'nama_sekolah' => 'nullable|string|max:255',
            'alamat_sekolah' => 'nullable|string',
            'nama_rumah_sakit' => 'nullable|string|max:255',
            'alamat_rumah_sakit' => 'nullable|string',
            'alasan_lainnya' => 'nullable|string',
            'nama_penjamin' => 'required|string|max:255',
            'nik_penjamin' => 'required|string|max:16',
            'alamat_penjamin' => 'required|string',
            'no_telp_penjamin' => 'required|string|max:15',
            'file_kk_asal' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ttd_pemohon' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'alamat_lokasi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updateData = $request->only([
                'nama_pemohon', 'nik', 'alamat_asal', 'alasan_tinggal', 'rt', 'rw',
                'nama_perusahaan', 'alamat_perusahaan', 'nama_sekolah', 'alamat_sekolah',
                'nama_rumah_sakit', 'alamat_rumah_sakit', 'alasan_lainnya',
                'nama_penjamin', 'nik_penjamin', 'alamat_penjamin', 'no_telp_penjamin',
                'ttd_pemohon', 'latitude', 'longitude', 'alamat_lokasi'
            ]);

            // Upload file KK baru jika ada
            if ($request->hasFile('file_kk_asal')) {
                // Delete old file
                if ($puntadewa->file_kk_asal && Storage::disk('public')->exists($puntadewa->file_kk_asal)) {
                    Storage::disk('public')->delete($puntadewa->file_kk_asal);
                }

                $fileKK = $request->file('file_kk_asal');
                $fileKKName = 'kk_asal_' . time() . '_' . Auth::id() . '.' . $fileKK->getClientOriginalExtension();
                $fileKKPath = $fileKK->storeAs('puntadewa/kk_asal', $fileKKName, 'public');
                $updateData['file_kk_asal'] = $fileKKPath;
            }

            $puntadewa->update($updateData);

            // Sync to UserApplication
            $this->syncToUserApplication($puntadewa);

            return response()->json([
                'success' => true,
                'message' => 'Data PUNTADEWA berhasil diperbarui.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Puntadewa $puntadewa)
    {
        $userRole = Auth::user()->role;

        // Check authorization - hanya yang mengajukan atau admin/ketua yang bisa hapus
        if ($userRole === 'user' && $puntadewa->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data ini.'
            ], 403);
        }

        // Only allow deletion if still pending_rt untuk user, atau admin/ketua roles
        if ($userRole === 'user' && !$puntadewa->canBeEdited()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak dapat dihapus karena sudah diproses.'
            ], 400);
        }

        try {
            // Delete corresponding UserApplication record first
            UserApplication::where('reference_id', $puntadewa->id)
                          ->where('reference_table', 'puntadewa')
                          ->delete();

            // Delete files
            if ($puntadewa->file_kk_asal && Storage::disk('public')->exists($puntadewa->file_kk_asal)) {
                Storage::disk('public')->delete($puntadewa->file_kk_asal);
            }

            if ($puntadewa->file_pdf && Storage::disk('public')->exists($puntadewa->file_pdf)) {
                Storage::disk('public')->delete($puntadewa->file_pdf);
            }

            $puntadewa->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data PUNTADEWA berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
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
    public function getRTSpesimen(Request $request, Puntadewa $puntadewa)
    {
        if (Auth::user()->role !== 'Ketua RT') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $spesimen = $this->getSpesimenData('Ketua RT', $puntadewa->rt, $puntadewa->rw);

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
                'nomor_rt' => $puntadewa->rt
            ]
        ]);
    }

    /**
     * Get RW TTD and Stempel for current approval
     */
    public function getRWSpesimen(Request $request, Puntadewa $puntadewa)
    {
        if (Auth::user()->role !== 'Ketua RW') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $spesimen = $this->getSpesimenData('Ketua RW', null, $puntadewa->rw);

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
    public function approveRT(Request $request, Puntadewa $puntadewa)
    {
        if (Auth::user()->role !== 'Ketua RT') {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menyetujui sebagai RT.'
            ], 403);
        }

        // Check if RT matches
        if (Auth::user()->rt != $puntadewa->rt) {
            return response()->json([
                'success' => false,
                'message' => 'Anda hanya dapat menyetujui data dari RT Anda.'
            ], 403);
        }

        if (!$puntadewa->canBeApprovedByRT()) {
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
            $spesimen = $this->getSpesimenData('Ketua RT', $puntadewa->rt, $puntadewa->rw);

            if (!$spesimen) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data spesimen TTD/Stempel RT tidak ditemukan. Silakan hubungi admin untuk mengupload spesimen.'
                ], 404);
            }

            // Simpan PATH file spesimen saja (bukan base64)
            $ttdRTPath = $spesimen->file_ttd;  // Path original file
            $stempelRTPath = $spesimen->file_stempel;  // Path original file

            $puntadewa->update([
                'status' => 'approved_rt',
                'ttd_rt' => $ttdRTPath,  // Simpan PATH file
                'stempel_rt' => $stempelRTPath,  // Simpan PATH file
                'catatan_rt' => $request->catatan_rt,
                'approved_rt_at' => now(),
                'approved_rt_by' => Auth::id(),
            ]);

            // Sync to UserApplication
            $this->syncToUserApplication($puntadewa);

            return response()->json([
                'success' => true,
                'message' => 'Permohonan PUNTADEWA berhasil disetujui oleh RT.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject by RT
     */
    public function rejectRT(Request $request, Puntadewa $puntadewa)
    {
        if (Auth::user()->role !== 'Ketua RT') {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menolak sebagai RT.'
            ], 403);
        }

        // Check if RT matches
        if (Auth::user()->rt != $puntadewa->rt) {
            return response()->json([
                'success' => false,
                'message' => 'Anda hanya dapat menolak data dari RT Anda.'
            ], 403);
        }

        if (!$puntadewa->canBeApprovedByRT()) {
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
            $puntadewa->update([
                'status' => 'rejected_rt',
                'catatan_rt' => $request->catatan_rt,
                'approved_rt_at' => now(),
                'approved_rt_by' => Auth::id(),
            ]);

            // Sync to UserApplication
            $this->syncToUserApplication($puntadewa);

            return response()->json([
                'success' => true,
                'message' => 'Permohonan PUNTADEWA telah ditolak oleh RT.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve by RW
     */
    public function approveRW(Request $request, Puntadewa $puntadewa)
    {
        if (Auth::user()->role !== 'Ketua RW') {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menyetujui sebagai RW.'
            ], 403);
        }

        // Check if RW matches
        if (Auth::user()->rw != $puntadewa->rw) {
            return response()->json([
                'success' => false,
                'message' => 'Anda hanya dapat menyetujui data dari RW Anda.'
            ], 403);
        }

        if (!$puntadewa->canBeApprovedByRW()) {
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
            $spesimen = $this->getSpesimenData('Ketua RW', null, $puntadewa->rw);

            if (!$spesimen) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data spesimen TTD/Stempel RW tidak ditemukan. Silakan hubungi admin untuk mengupload spesimen.'
                ], 404);
            }

            // Simpan PATH file spesimen saja (bukan base64)
            $ttdRWPath = $spesimen->file_ttd;  // Path original file
            $stempelRWPath = $spesimen->file_stempel;  // Path original file

            $puntadewa->update([
                'status' => 'approved_rw',
                'ttd_rw' => $ttdRWPath,  // Simpan PATH file
                'stempel_rw' => $stempelRWPath,  // Simpan PATH file
                'catatan_rw' => $request->catatan_rw,
                'approved_rw_at' => now(),
                'approved_rw_by' => Auth::id(),
            ]);

            // Generate final PDF
            $this->generatePDF($puntadewa);

            // Sync to UserApplication
            $this->syncToUserApplication($puntadewa);

            return response()->json([
                'success' => true,
                'message' => 'Permohonan PUNTADEWA berhasil disetujui oleh RW. PDF siap untuk diunduh.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject by RW
     */
    public function rejectRW(Request $request, Puntadewa $puntadewa)
    {
        if (Auth::user()->role !== 'Ketua RW') {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menolak sebagai RW.'
            ], 403);
        }

        // Check if RW matches
        if (Auth::user()->rw != $puntadewa->rw) {
            return response()->json([
                'success' => false,
                'message' => 'Anda hanya dapat menolak data dari RW Anda.'
            ], 403);
        }

        if (!$puntadewa->canBeApprovedByRW()) {
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
            $puntadewa->update([
                'status' => 'rejected_rw',
                'catatan_rw' => $request->catatan_rw,
                'approved_rw_at' => now(),
                'approved_rw_by' => Auth::id(),
            ]);

            // Sync to UserApplication
            $this->syncToUserApplication($puntadewa);

            return response()->json([
                'success' => true,
                'message' => 'Permohonan PUNTADEWA telah ditolak oleh RW.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate PDF
     */
    private function generatePDF(Puntadewa $puntadewa)
    {
        try {
            $pdf = PDF::loadView('Puntadewa.PDF', compact('puntadewa'));

            // Clean nomor surat for filename - remove / and \ characters
            $cleanNomorSurat = str_replace(['/', '\\'], '_', $puntadewa->nomor_surat);
            $fileName = 'puntadewa_' . $cleanNomorSurat . '_' . time() . '.pdf';

            $filePath = 'puntadewa/pdf/' . $fileName;
            Storage::disk('public')->put($filePath, $pdf->output());
            $puntadewa->update(['file_pdf' => $filePath]);

            return $filePath;
        } catch (\Exception $e) {
            Log::error('Error generating PDF: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Preview PDF (bisa dilihat kapan saja)
     */
    public function previewPDF(Puntadewa $puntadewa)
    {
        try {
            $pdf = PDF::loadView('Puntadewa.PDF', compact('puntadewa'));

            // Clean nomor surat for filename
            $cleanNomorSurat = str_replace(['/', '\\'], '_', $puntadewa->nomor_surat);
            $fileName = 'preview_puntadewa_' . $cleanNomorSurat . '.pdf';

            return $pdf->stream($fileName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat preview PDF: ' . $e->getMessage());
        }
    }

    /**
     * Download PDF (hanya jika sudah fully approved)
     */
    public function downloadPDF(Puntadewa $puntadewa)
    {
        // Check authorization
        if (Auth::user()->role === 'user' && $puntadewa->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengunduh data ini.');
        }

        if (!$puntadewa->canDownloadPDF()) {
            return redirect()->back()
                           ->with('error', 'PDF hanya dapat diunduh setelah disetujui oleh RT dan RW.');
        }

        try {
            if (!$puntadewa->file_pdf || !Storage::disk('public')->exists($puntadewa->file_pdf)) {
                // Regenerate PDF if not exists
                $this->generatePDF($puntadewa);
            }

            // Clean nomor surat for download filename
            $cleanNomorSurat = str_replace(['/', '\\'], '_', $puntadewa->nomor_surat);
            $downloadName = 'PUNTADEWA_' . $cleanNomorSurat . '.pdf';

            // Track download in UserApplication
            $userApp = UserApplication::where('reference_id', $puntadewa->id)
                                    ->where('reference_table', 'puntadewa')
                                    ->first();
            if ($userApp) {
                $userApp->increment('download_count');
            }

            return Storage::disk('public')->download($puntadewa->file_pdf, $downloadName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengunduh PDF: ' . $e->getMessage());
        }
    }

    /**
     * Sync data to UserApplication table
     * This method ensures PUNTADEWA data is always in sync with UserApplication
     */
    private function syncToUserApplication(Puntadewa $puntadewa)
    {
        try {
            // Check if UserApplication record already exists
            $userApplication = UserApplication::where('reference_id', $puntadewa->id)
                                             ->where('reference_table', 'puntadewa')
                                             ->first();

            // Prepare data for UserApplication
            $data = [
                'nomor_surat' => $puntadewa->nomor_surat,
                'user_id' => $puntadewa->user_id,
                'jenis_permohonan' => 'PUNTADEWA',
                'judul_permohonan' => 'Pernyataan Tempat Tinggal Non Permanen',
                'deskripsi_permohonan' => $puntadewa->alasan_tinggal,
                'nama_pemohon' => $puntadewa->nama_pemohon,
                'nik' => $puntadewa->nik,
                'rt' => $puntadewa->rt,
                'rw' => $puntadewa->rw,
                'status' => $puntadewa->status,
                'approved_rt_at' => $puntadewa->approved_rt_at,
                'approved_rt_by' => $puntadewa->approved_rt_by,
                'catatan_rt' => $puntadewa->catatan_rt,
                'approved_rw_at' => $puntadewa->approved_rw_at,
                'approved_rw_by' => $puntadewa->approved_rw_by,
                'catatan_rw' => $puntadewa->catatan_rw,
                'file_pdf' => $puntadewa->file_pdf,
                'reference_id' => $puntadewa->id,
                'reference_table' => 'puntadewa',
                'metadata' => [
                    'alamat_asal' => $puntadewa->alamat_asal,
                    'nama_penjamin' => $puntadewa->nama_penjamin,
                    'alamat_penjamin' => $puntadewa->alamat_penjamin,
                    'no_telp_penjamin' => $puntadewa->no_telp_penjamin,
                    'nama_perusahaan' => $puntadewa->nama_perusahaan,
                    'alamat_perusahaan' => $puntadewa->alamat_perusahaan,
                    'nama_sekolah' => $puntadewa->nama_sekolah,
                    'alamat_sekolah' => $puntadewa->alamat_sekolah,
                    'nama_rumah_sakit' => $puntadewa->nama_rumah_sakit,
                    'alamat_rumah_sakit' => $puntadewa->alamat_rumah_sakit,
                    'alasan_lainnya' => $puntadewa->alasan_lainnya,
                    'latitude' => $puntadewa->latitude,
                    'longitude' => $puntadewa->longitude,
                    'alamat_lokasi' => $puntadewa->alamat_lokasi,
                ]
            ];

            if ($userApplication) {
                // Update existing record
                $userApplication->update($data);
                Log::info("Updated UserApplication for PUNTADEWA ID: {$puntadewa->id}");
            } else {
                // Create new record
                UserApplication::create($data);
                Log::info("Created UserApplication for PUNTADEWA ID: {$puntadewa->id}");
            }

        } catch (\Exception $e) {
            // Log error but don't fail the main operation
            Log::error("Error syncing PUNTADEWA ID {$puntadewa->id} to UserApplication: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
        }
    }

    /**
     * Sync all existing PUNTADEWA data to UserApplication
     * This is a utility method for initial setup or data repair
     */
    public function syncAllToUserApplication()
    {
        try {
            $puntadewaRecords = Puntadewa::all();
            $synced = 0;
            $errors = 0;

            foreach ($puntadewaRecords as $puntadewa) {
                try {
                    $this->syncToUserApplication($puntadewa);
                    $synced++;
                } catch (\Exception $e) {
                    $errors++;
                    Log::error("Error syncing PUNTADEWA ID {$puntadewa->id}: " . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Sync completed. Synced: {$synced}, Errors: {$errors}",
                'data' => [
                    'synced' => $synced,
                    'errors' => $errors,
                    'total' => $puntadewaRecords->count()
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
     * Check data integrity between PUNTADEWA and UserApplication
     */
    public function checkDataIntegrity()
    {
        try {
            $puntadewaCount = Puntadewa::count();
            $userAppCount = UserApplication::where('jenis_permohonan', 'PUNTADEWA')->count();

            $missingInUserApp = Puntadewa::whereNotIn('id',
                UserApplication::where('jenis_permohonan', 'PUNTADEWA')
                               ->pluck('reference_id')
            )->count();

            $orphanedUserApp = UserApplication::where('jenis_permohonan', 'PUNTADEWA')
                              ->whereNotIn('reference_id', Puntadewa::pluck('id'))
                              ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'puntadewa_count' => $puntadewaCount,
                    'user_application_count' => $userAppCount,
                    'missing_in_user_app' => $missingInUserApp,
                    'orphaned_user_app' => $orphanedUserApp,
                    'is_synced' => ($puntadewaCount === $userAppCount && $missingInUserApp === 0 && $orphanedUserApp === 0)
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
