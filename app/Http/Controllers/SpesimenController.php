<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Spesimen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Services\RemoveBgService;

class SpesimenController extends Controller
{
    protected $removeBgService;

    public function __construct(RemoveBgService $removeBgService)
    {
        $this->removeBgService = $removeBgService;
    }

    public function index()
    {
        $type_menu = "master-data";
        return view('admin.masterdata.Spesimen.index', compact('type_menu'));
    }

    public function data()
    {
        $user = Auth::user();
        $query = Spesimen::with(['user', 'creator'])->select('spesimen.*');

        // Filter berdasarkan role user
        if ($user->role === 'Ketua RW') {
            $query->where('rw', $user->rw);
        } elseif ($user->role === 'Ketua RT') {
            $query->where('rt', $user->rt)
                  ->where('rw', $user->rw);
        }
        // Admin bisa melihat semua

        return DataTables::of($query)
            ->addColumn('file_info', function ($spesimen) {
                $files = [];
                if ($spesimen->file_ttd) {
                    $files[] = '<i class="fas fa-signature text-primary"></i> TTD';
                }
                if ($spesimen->file_stempel) {
                    $files[] = '<i class="fas fa-stamp text-success"></i> Stempel';
                }

                return '<div class="file-info">' . implode('<br>', $files) . '</div>';
            })
            ->addColumn('pejabat_info', function ($spesimen) {
                return '<div>
                    <div class="fw-bold">' . $spesimen->nama_pejabat . '</div>
                    <div class="text-muted small">' . $spesimen->user->name . '</div>
                    <div class="text-info small">' . ($spesimen->keterangan ? \Illuminate\Support\Str::limit($spesimen->keterangan, 50) : '-') . '</div>
                </div>';
            })
            ->addColumn('jabatan_badge', function ($spesimen) {
                return $spesimen->jabatan_badge;
            })
            ->addColumn('wilayah', function ($spesimen) {
                return $spesimen->wilayah_lengkap;
            })
            ->addColumn('status_info', function ($spesimen) {
                return '<div>
                    ' . $spesimen->status_badge . '
                    <br>
                    ' . $spesimen->active_badge . '
                </div>';
            })
            ->addColumn('actions', function ($spesimen) {
                $user = Auth::user();
                $canEdit = $spesimen->canBeEditedBy($user);

                $actions = '<div class="btn-group" role="group">';

                // View files button
                if ($spesimen->file_ttd || $spesimen->file_stempel) {
                    $actions .= '<a href="' . route('admin.masterdata.Spesimen.show', $spesimen->id) . '"
                                class="btn btn-sm btn-info" title="Lihat File">
                                <i class="fas fa-eye"></i>
                            </a>';
                }

                if ($canEdit) {
                    // Edit button
                    $actions .= '<a href="' . route('admin.masterdata.Spesimen.edit', $spesimen->id) . '"
                                class="btn btn-sm btn-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>';

                    // Delete button
                    $actions .= '<form method="POST"
                                action="' . route('admin.masterdata.Spesimen.destroy', $spesimen->id) . '"
                                style="display: inline;">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="button" class="btn btn-sm btn-danger btn-delete"
                                        data-name="' . $spesimen->nama_pejabat . '" title="Hapus"
                                        onclick="deleteSpesimen('. $spesimen->id.' )">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>';
                }

                $actions .= '</div>';

                return $actions;
            })
            ->rawColumns(['file_info', 'pejabat_info', 'jabatan_badge', 'status_info', 'actions'])
            ->make(true);
    }

    public function create()
    {
        $user = Auth::user();
        $type_menu = "master-data";

        // Get users with Ketua RT or Ketua RW role
        $pejabatOptions = User::whereIn('role', ['Ketua RT', 'Ketua RW', 'Front Office', 'Back Office', 'Lurah'])
            ->select('id', 'name', 'role', 'rt', 'rw')
            ->get();

        // Filter berdasarkan role user yang login
        if ($user->role === 'Ketua RW') {
            $pejabatOptions = $pejabatOptions->where('rw', $user->rw);
        } elseif ($user->role === 'Ketua RT') {
            $pejabatOptions = $pejabatOptions->where('rt', $user->rt)
                                           ->where('rw', $user->rw);
        }

        $jabatanOptions = Spesimen::getJabatanOptions();
        $statusOptions = Spesimen::getStatusOptions();
        $rwOptions = Spesimen::getRWOptions();
        $rtOptions = Spesimen::getRTOptions();

        return view('admin.masterdata.Spesimen.create', compact(
            'pejabatOptions', 'jabatanOptions', 'statusOptions', 'rwOptions', 'rtOptions', 'type_menu'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Validasi
        $rules = [
            'user_id' => 'required|exists:users,id',
            'nama_pejabat' => 'required|string|max:255',
            'jabatan' => 'required|in:Ketua RT,Ketua RW,Front Office,Back Office,Lurah',
            'rt' => 'required|string|max:10',
            'rw' => 'required|string|max:10',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'is_active' => 'boolean',
            'urutan_tampil' => 'nullable|integer|min:0',
            'file_ttd' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120', // 5MB
            'file_stempel' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120', // 5MB
        ];

        if ($request->jabatan === 'Ketua RT') {
            $rules['rt'] = 'required|string|max:10';
        } else if ($request->jabatan === 'Ketua RW') {
            $rules['rt'] = 'nullable|string|max:10';
        } else if ($request->jabatan === 'Front Office') {
            $rules['rw'] = 'nullable|string|max:10';
            $rules['rt'] = 'nullable|string|max:10';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Check authorization
        $selectedUser = User::find($request->user_id);
        if (!$this->canManageUser($user, $selectedUser)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk mengelola data pejabat ini.'
            ], 403);
        }

        // Process file uploads
        $fileTtd = null;
        $fileStempel = null;

        if ($request->hasFile('file_ttd')) {
            // Upload original file
            $originalPath = $request->file('file_ttd')->store('spesimen/ttd', 'public');

            // Remove background if service is configured
            if ($this->removeBgService->isConfigured()) {
                $fileTtd = $this->removeBgService->removeBackground($originalPath);
            } else {
                $fileTtd = $originalPath;
                \Log::warning('Remove.bg service not configured, using original image');
            }
        }

        if ($request->hasFile('file_stempel')) {
            // Upload original file
            $originalPath = $request->file('file_stempel')->store('spesimen/stempel', 'public');

            // Remove background if service is configured
            if ($this->removeBgService->isConfigured()) {
                $fileStempel = $this->removeBgService->removeBackground($originalPath);
            } else {
                $fileStempel = $originalPath;
                \Log::warning('Remove.bg service not configured, using original image');
            }
        }

        // Prepare data
        $data = $request->except(['file_ttd', 'file_stempel']);
        $data['file_ttd'] = $fileTtd;
        $data['file_stempel'] = $fileStempel;
        $data['created_by'] = $user->id;
        $data['is_active'] = $request->has('is_active');

        // Set nomor from selected user if not admin
        if ($user->role !== 'admin') {
            $data['rw'] = $selectedUser->rw;
            if ($request->jabatan === 'Ketua RT') {
                $data['rt'] = $selectedUser->rt;
            }
        }

        $spesimen = Spesimen::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Data spesimen berhasil ditambahkan.',
            'data' => $spesimen
        ]);
    }

    public function show($id)
    {
        $user = Auth::user();
        $spesimen = Spesimen::with(['user', 'creator'])->findOrFail($id);

        if (!$spesimen->canBeAccessedBy($user)) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data ini.');
        }

        $type_menu = 'master-data';
        return view('admin.masterdata.Spesimen.show', compact('spesimen', 'type_menu'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $spesimen = Spesimen::with(['user'])->findOrFail($id);
        $type_menu = 'master-data';

        if (!$spesimen->canBeEditedBy($user)) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit data ini.');
        }

        // Get users with Ketua RT or Ketua RW role
        $pejabatOptions = User::whereIn('role', ['Ketua RT', 'Ketua RW', 'Front Office', 'Back Office', 'Lurah'])
            ->select('id', 'name', 'role', 'rt', 'rw')
            ->get();

        // Filter berdasarkan role user yang login
        if ($user->role === 'Ketua RW') {
            $pejabatOptions = $pejabatOptions->where('rw', $user->rw);
        } elseif ($user->role === 'Ketua RT') {
            $pejabatOptions = $pejabatOptions->where('rt', $user->rt)
                                           ->where('rw', $user->rw);
        }

        $jabatanOptions = Spesimen::getJabatanOptions();
        $statusOptions = Spesimen::getStatusOptions();
        $rwOptions = Spesimen::getRWOptions();
        $rtOptions = Spesimen::getRTOptions();

        return view('admin.masterdata.Spesimen.edit', compact(
            'spesimen', 'pejabatOptions', 'jabatanOptions', 'statusOptions', 'rwOptions', 'rtOptions', 'type_menu'
        ));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $spesimen = Spesimen::findOrFail($id);

        if (!$spesimen->canBeEditedBy($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk mengedit data ini.'
            ], 403);
        }

        // Validasi sama seperti store
        $rules = [
            'user_id' => 'required|exists:users,id',
            'nama_pejabat' => 'required|string|max:255',
            'jabatan' => 'required|in:Ketua RT,Ketua RW,Front Office,Back Office,Lurah',
            'rt' => 'required|string|max:10',
            'rw' => 'required|string|max:10',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'is_active' => 'boolean',
            'urutan_tampil' => 'nullable|integer|min:0',
            'file_ttd' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'file_stempel' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ];

        if ($request->jabatan === 'Ketua RT') {
            $rules['rt'] = 'required|string|max:10';
        } else if ($request->jabatan === 'Ketua RW') {
            $rules['rt'] = 'nullable|string|max:10';
        } else if ($request->jabatan === 'Front Office') {
            $rules['rw'] = 'nullable|string|max:10';
            $rules['rt'] = 'nullable|string|max:10';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Process file uploads with background removal
        $fileTtd = $spesimen->file_ttd;
        $fileStempel = $spesimen->file_stempel;

        if ($request->hasFile('file_ttd')) {
            // Delete old file
            if ($fileTtd && Storage::disk('public')->exists($fileTtd)) {
                Storage::disk('public')->delete($fileTtd);
            }

            // Upload and process new file
            $originalPath = $request->file('file_ttd')->store('spesimen/ttd', 'public');

            if ($this->removeBgService->isConfigured()) {
                $fileTtd = $this->removeBgService->removeBackground($originalPath);
            } else {
                $fileTtd = $originalPath;
                \Log::warning('Remove.bg service not configured, using original image');
            }
        }

        if ($request->hasFile('file_stempel')) {
            // Delete old file
            if ($fileStempel && Storage::disk('public')->exists($fileStempel)) {
                Storage::disk('public')->delete($fileStempel);
            }

            // Upload and process new file
            $originalPath = $request->file('file_stempel')->store('spesimen/stempel', 'public');

            if ($this->removeBgService->isConfigured()) {
                $fileStempel = $this->removeBgService->removeBackground($originalPath);
            } else {
                $fileStempel = $originalPath;
                \Log::warning('Remove.bg service not configured, using original image');
            }
        }

        // Prepare data
        $data = $request->except(['file_ttd', 'file_stempel']);
        $data['file_ttd'] = $fileTtd;
        $data['file_stempel'] = $fileStempel;
        $data['updated_by'] = $user->id;
        $data['is_active'] = $request->has('is_active');

        $spesimen->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Data spesimen berhasil diperbarui.',
            'data' => $spesimen
        ]);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $spesimen = Spesimen::findOrFail($id);

        if (!$spesimen->canBeEditedBy($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data ini.'
            ], 403);
        }

        $spesimen->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data spesimen berhasil dihapus.'
        ]);
    }

    // Helper method untuk check authorization
    private function canManageUser($currentUser, $targetUser)
    {
        if ($currentUser->role === 'admin') {
            return true;
        }

        if ($currentUser->role === 'Ketua RW') {
            return $targetUser->rw == $currentUser->rw;
        }

        if ($currentUser->role === 'Ketua RT') {
            return $targetUser->rt == $currentUser->rt &&
                   $targetUser->rw == $currentUser->rw;
        }

        if ($currentUser->role === 'Front Office') {
            return $targetUser->user_id == $currentUser->user_id;
        }

        return false;
    }

    // API method untuk mendapatkan user berdasarkan jabatan
    public function getUsersByJabatan(Request $request)
    {
        $jabatan = $request->get('jabatan');
        $user = Auth::user();

        $query = User::where('role', $jabatan)
                    ->select('id', 'name', 'role', 'rt', 'rw');

        // Filter berdasarkan role user yang login
        if ($user->role === 'Ketua RW') {
            $query->where('rw', $user->rw);
        } elseif ($user->role === 'Ketua RT') {
            $query->where('rt', $user->rt)
                  ->where('rw', $user->rw);
        }

        $users = $query->get();

        return response()->json($users);
    }
}
