<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BankData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AdminBankDataController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('cekrole:admin|operator|ketua_rw|ketua_rt');
    // }

    public function index()
    {
        $type_menu = "master-data";
        return view('admin.masterdata.BankData.index', compact('type_menu'));
    }

    public function data()
    {
        $user = Auth::user();
        $query = BankData::with(['creator'])->select('bank_data.*');

        // Filter berdasarkan role user
        if ($user->role === 'Ketua RW') {
            $query->where('jenis_bank_data', 'RT')
                  ->where('nomor_rw', $user->rw);
                //   ->where('jenis_bank_data', 'RT');
        } elseif ($user->role === 'Ketua RT') {
            $query->where('jenis_bank_data', 'RT')
                  ->where('nomor_rt', $user->rt)
                  ->where('nomor_rw', $user->rw);
        } elseif ($user->role === 'Operator') {
            $query->where('jenis_bank_data', 'Kelurahan');
        }
        // Admin bisa melihat semua

        return DataTables::of($query)
            ->addColumn('file_info', function ($bankData) {
                return '<div class="file-info">
                    <i class="fas fa-images text-primary"></i>
                    <span class="ms-2">' . $bankData->file_info . '</span>
                </div>';
            })
            ->addColumn('kegiatan_info', function ($bankData) {
                return '<div>
                    <div class="fw-bold">' . $bankData->judul_kegiatan . '</div>
                    <div class="text-muted small">' . Str::limit($bankData->deskripsi, 50) . '</div>
                    <div class="text-info small"><i class="fas fa-map-marker-alt"></i> ' . ($bankData->lokasi ?? '-') . '</div>
                </div>';
            })
            ->addColumn('jenis_badge', function ($bankData) {
                return $bankData->jenis_badge;
            })
            ->addColumn('wilayah', function ($bankData) {
                return $bankData->wilayah_lengkap;
            })
            ->addColumn('status_info', function ($bankData) {
                return '<div>
                    ' . $bankData->status_badge . '
                    <br>
                    ' . $bankData->active_badge . '
                </div>';
            })
            ->addColumn('view_info', function ($bankData) {
                return '<div class="text-center">
                    <span class="badge bg-info">' . number_format($bankData->view_count) . ' views</span>
                </div>';
            })
            ->addColumn('actions', function ($bankData) {
                $user = Auth::user();
                $canEdit = $bankData->canBeEditedBy($user);

                $actions = '<div class="btn-group" role="group">';

                // View button
                $actions .= '<a href="' . route('admin.masterdata.BankData.show', $bankData->id) . '"
                            class="btn btn-sm btn-info" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </a>';

                if ($canEdit) {
                    // Edit button
                    $actions .= '<a href="' . route('admin.masterdata.BankData.edit', $bankData->id) . '"
                                class="btn btn-sm btn-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>';

                    // Delete button
                    $actions .= '<form method="POST"
                                action="' . route('admin.masterdata.BankData.destroy', $bankData->id) . '"
                                style="display: inline;">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="button" class="btn btn-sm btn-danger btn-delete"
                                        data-name="' . $bankData->judul_kegiatan . '" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>';
                }

                $actions .= '</div>';

                return $actions;
            })
            ->rawColumns(['file_info', 'kegiatan_info', 'jenis_badge', 'status_info', 'view_info', 'actions'])
            ->make(true);
    }

    public function create()
    {
        $user = Auth::user();
        $type_menu = "master-data";

        // Tentukan jenis yang bisa dibuat berdasarkan role
        $allowedJenis = [];
        if ($user->role === 'admin') {
            $allowedJenis = ['Kelurahan', 'RW', 'RT'];
        } elseif ($user->role === 'Operator') {
            $allowedJenis = ['Kelurahan'];
        } elseif ($user->role === 'Ketua RW') {
            $allowedJenis = ['RW'];
        } elseif ($user->role === 'Ketua RT') {
            $allowedJenis = ['RT'];
        }

        $jenisOptions = BankData::getJenisOptions();
        $rwOptions = BankData::getRWOptions();
        $rtOptions = BankData::getRTOptions();

        return view('admin.masterdata.BankData.create', compact(
            'jenisOptions', 'rwOptions', 'rtOptions', 'allowedJenis', 'type_menu'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Validasi dasar
        $rules = [
            'judul_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'jenis_bank_data' => 'required|in:Kelurahan,RW,RT',
            'tanggal_kegiatan' => 'required|date',
            'lokasi' => 'nullable|string|max:255',
            'status' => 'required|in:Published,Draft,Archived',
            'is_active' => 'boolean',
            'urutan_tampil' => 'nullable|integer|min:0',
            'tags' => 'nullable|string',
            'files_foto.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB
            'files_video.*' => 'nullable|mimes:mp4,avi,mov,wmv|max:102400', // 100MB
        ];

        // Validasi berdasarkan jenis
        if ($request->jenis_bank_data === 'RW') {
            $rules['nomor_rw'] = 'required|string|max:10';
        } elseif ($request->jenis_bank_data === 'RT') {
            $rules['nomor_rt'] = 'required|string|max:10';
            $rules['nomor_rw'] = 'required|string|max:10';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Cek authorization berdasarkan role
        if (!$this->canCreateJenis($user, $request->jenis_bank_data)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk membuat jenis bank data ini.'
            ], 403);
        }

        // Proses upload files
        $filesFoto = [];
        $filesVideo = [];

        if ($request->hasFile('files_foto')) {
            foreach ($request->file('files_foto') as $file) {
                $path = $file->store('bank-data/foto', 'public');
                $filesFoto[] = $path;
            }
        }

        if ($request->hasFile('files_video')) {
            foreach ($request->file('files_video') as $file) {
                $path = $file->store('bank-data/video', 'public');
                $filesVideo[] = $path;
            }
        }

        // Siapkan data
        $data = $request->except(['files_foto', 'files_video']);
        $data['files_foto'] = !empty($filesFoto) ? $filesFoto : null;
        $data['files_video'] = !empty($filesVideo) ? $filesVideo : null;
        $data['created_by'] = $user->id;
        $data['is_active'] = $request->has('is_active');

        // Process tags
        if ($request->filled('tags')) {
            $tags = array_map('trim', explode(',', $request->tags));
            $data['tags'] = array_filter($tags);
        }

        // Set nomor sesuai role user jika tidak admin
        if ($user->role === 'Ketua RW' && $request->jenis_bank_data === 'RW') {
            $data['nomor_rw'] = $user->rw;
        } elseif ($user->role === 'Ketua RT' && $request->jenis_bank_data === 'RT') {
            $data['nomor_rt'] = $user->rt;
            $data['nomor_rw'] = $user->rw;
        }

        $bankData = BankData::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Data bank data berhasil ditambahkan.',
            'data' => $bankData
        ]);
    }

    public function show($id)  // Ganti dari BankData $bankData ke $id
    {
        $user = Auth::user();
        $type_menu = 'master-data';

        // Debug: Cek ID yang diterima
        \Log::info('Show method called with ID: ' . $id);

        // Ambil data manual dengan findOrFail
        try {
            $bankData = BankData::findOrFail($id);
            \Log::info('Bank data found:', [
                'id' => $bankData->id,
                'judul' => $bankData->judul_kegiatan,
                'jenis' => $bankData->jenis_bank_data,
                'rw' => $bankData->nomor_rw
            ]);
        } catch (\Exception $e) {
            \Log::error('Bank data not found: ' . $e->getMessage());
            abort(404, 'Data tidak ditemukan');
        }

        // Cek akses user
        if (!$bankData->canBeAccessedBy($user)) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data ini.');
        }

        // Increment views count
        // $bankData->increment('views_count');

        return view('admin.masterdata.BankData.show', compact('bankData', 'type_menu'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $bankData = BankData::findOrFail($id);
        $type_menu = 'master-data';

        if (!$bankData->canBeEditedBy($user)) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit data ini.');
        }

        $jenisOptions = BankData::getJenisOptions();
        $rwOptions = BankData::getRWOptions();
        $rtOptions = BankData::getRTOptions();

        // Tentukan jenis yang bisa dipilih berdasarkan role
        $allowedJenis = [];
        if ($user->role === 'admin') {
            $allowedJenis = ['Kelurahan', 'RW', 'RT'];
        } elseif ($user->role === 'Operator') {
            $allowedJenis = ['Kelurahan'];
        } elseif ($user->role === 'Ketua RW') {
            $allowedJenis = ['RW'];
        } elseif ($user->role === 'Ketua RT') {
            $allowedJenis = ['RT'];
        }

        return view('admin.masterdata.BankData.edit', compact(
            'bankData', 'jenisOptions', 'rwOptions', 'rtOptions', 'allowedJenis', 'type_menu'
        ));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $bankData = BankData::findOrFail($id);

        if (!$bankData->canBeEditedBy($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk mengedit data ini.'
            ], 403);
        }

        // Validasi sama seperti store
        $rules = [
            'judul_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'jenis_bank_data' => 'required|in:Kelurahan,RW,RT',
            'tanggal_kegiatan' => 'required|date',
            'lokasi' => 'nullable|string|max:255',
            'status' => 'required|in:Published,Draft,Archived',
            'is_active' => 'boolean',
            'urutan_tampil' => 'nullable|integer|min:0',
            'tags' => 'nullable|string',
            'files_foto.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'files_video.*' => 'nullable|mimes:mp4,avi,mov,wmv|max:102400',
        ];

        if ($request->jenis_bank_data === 'RW') {
            $rules['nomor_rw'] = 'required|string|max:10';
        } elseif ($request->jenis_bank_data === 'RT') {
            $rules['nomor_rt'] = 'required|string|max:10';
            $rules['nomor_rw'] = 'required|string|max:10';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Proses upload files baru
        $filesFoto = $bankData->files_foto ?? [];
        $filesVideo = $bankData->files_video ?? [];

        if ($request->hasFile('files_foto')) {
            foreach ($request->file('files_foto') as $file) {
                $path = $file->store('bank-data/foto', 'public');
                $filesFoto[] = $path;
            }
        }

        if ($request->hasFile('files_video')) {
            foreach ($request->file('files_video') as $file) {
                $path = $file->store('bank-data/video', 'public');
                $filesVideo[] = $path;
            }
        }

        // Siapkan data
        $data = $request->except(['files_foto', 'files_video']);
        $data['files_foto'] = !empty($filesFoto) ? $filesFoto : null;
        $data['files_video'] = !empty($filesVideo) ? $filesVideo : null;
        $data['updated_by'] = $user->id;
        $data['is_active'] = $request->has('is_active');

        // Process tags
        if ($request->filled('tags')) {
            $tags = array_map('trim', explode(',', $request->tags));
            $data['tags'] = array_filter($tags);
        } else {
            $data['tags'] = null;
        }

        $bankData->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Data bank data berhasil diperbarui.',
            'data' => $bankData
        ]);
    }

    public function destroy(BankData $bankData)
    {
        $user = Auth::user();

        if (!$bankData->canBeEditedBy($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data ini.'
            ], 403);
        }

        // Hapus files dari storage
        if ($bankData->files_foto) {
            foreach ($bankData->files_foto as $file) {
                Storage::disk('public')->delete($file);
            }
        }

        if ($bankData->files_video) {
            foreach ($bankData->files_video as $file) {
                Storage::disk('public')->delete($file);
            }
        }

        $bankData->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data bank data berhasil dihapus.'
        ]);
    }

    private function canCreateJenis($user, $jenis)
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($jenis === 'Kelurahan' && $user->role === 'Operator') {
            return true;
        }

        if ($jenis === 'RW' && $user->role === 'Ketua RW') {
            return true;
        }

        if ($jenis === 'RT' && $user->role === 'Ketua RT') {
            return true;
        }

        return false;
    }
}
