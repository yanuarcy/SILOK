<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $type_menu = "master-data";
        return view('admin.Pegawai.data-pegawai', compact('type_menu'));
    }

    /**
     * Get data for DataTables
     */
    public function data()
    {
        $pegawai = Pegawai::with('user')->select('pegawai.*');

        return DataTables::of($pegawai)
            ->addColumn('nama_lengkap', function ($row) {
                return $row->nama_lengkap;
            })
            ->addColumn('foto', function ($row) {
                if ($row->user && $row->user->image) {
                    // Cek beberapa kemungkinan lokasi file
                    $imagePaths = [
                        'storage/images/pegawai/' . $row->user->image,
                        'images/pegawai/' . $row->user->image,
                        'storage/' . $row->user->image,
                        $row->user->image
                    ];

                    foreach ($imagePaths as $path) {
                        if (file_exists(public_path($path))) {
                            return '<img src="' . asset($path) . '" alt="' . $row->nama_lengkap . '" class="rounded-circle" width="40" height="40" style="object-fit: cover;">';
                        }
                    }
                }

                // Fallback ke initial huruf pertama nama
                $initial = strtoupper(substr($row->nama_lengkap ?? 'U', 0, 1));
                return '<div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white" style="width: 40px; height: 40px; font-size: 18px; font-weight: bold;">' . $initial . '</div>';
            })
            ->addColumn('media_sosial_badges', function ($row) {
                $badges = '';
                if ($row->media_sosial && is_array($row->media_sosial) && count($row->media_sosial) > 0) {
                    $badgeStyles = [
                        'Facebook' => 'background-color: #1877F2; color: white; border: none;',
                        'Twitter' => 'background-color: #1DA1F2; color: white; border: none;',
                        'Instagram' => 'background: linear-gradient(45deg, #405DE6, #5851DB, #833AB4, #C13584, #E1306C, #FD1D1D, #F56040, #F77737, #FCAF45, #FFDC80); color: white; border: none;',
                        'LinkedIn' => 'background-color: #0077B5; color: white; border: none;',
                        'YouTube' => 'background-color: #FF0000; color: white; border: none;',
                        'WhatsApp' => 'background-color: #25D366; color: white; border: none;'
                    ];

                    foreach ($row->media_sosial as $media) {
                        if (!empty($media['platform']) && !empty($media['url'])) {
                            $platform = $media['platform'];
                            $style = $badgeStyles[$platform] ?? 'background-color: #6c757d; color: white; border: none;';
                            $badges .= '<span class="badge mr-1 mb-1" style="' . $style . ' padding: 4px 8px; font-size: 11px; border-radius: 12px;">' . $platform . '</span>';
                        }
                    }
                }

                return $badges ?: '<span class="text-muted">-</span>';
            })
            ->addColumn('status_aktif_badge', function ($row) {
                return $row->status_aktif_badge;
            })
            ->addColumn('actions', function ($row) {
                $editBtn = '<a href="' . route('Pegawai.edit', $row->id) . '" class="btn btn-sm btn-warning me-2">
                    <i class="fas fa-edit"></i>
                </a>';

                $deleteBtn = '<form action="' . route('Pegawai.destroy', $row->id) . '" method="POST" style="display: inline;">
                    ' . csrf_field() . '
                    ' . method_field('DELETE') . '
                    <button type="button" class="btn btn-sm btn-danger btn-delete" data-name="' . $row->nama_lengkap . '">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>';

                return $editBtn . $deleteBtn;
            })
            ->rawColumns(['foto', 'media_sosial_badges', 'status_aktif_badge', 'actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $type_menu = "master-data";
        // Ambil users yang tidak memiliki role 'User' dan belum terdaftar sebagai pegawai
        $availableUsers = User::whereNotIn('role', ['User'])
            ->whereNotIn('id', function($query) {
                $query->select('user_id')->from('pegawai');
            })
            ->orderBy('name')
            ->get();

        $jabatanOptions = [
            'Lurah',
            'Sekretaris Kelurahan',
            'Seksi Pemerintahan dan Pelayanan Publik',
            'Seksi Kesejahteraan Rakyat dan Perekonomian',
            'Seksi Ketentraman, Ketertiban dan Pembangunan',
            'Staff',
            'Tenaga Kontrak / OS'
        ];

        return view('admin.Pegawai.create-pegawai', compact('availableUsers', 'jabatanOptions', 'type_menu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id|unique:pegawai,user_id',
            'jabatan' => 'required|string|max:255',
            'urutan_tampil' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'media_sosial' => 'nullable|array',
            'media_sosial.*.platform' => 'required_with:media_sosial.*.url|string',
            'media_sosial.*.url' => 'required_with:media_sosial.*.platform|url'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Set urutan tampil otomatis jika tidak diisi
        if (!$request->urutan_tampil) {
            $maxUrutan = Pegawai::max('urutan_tampil');
            $request->merge(['urutan_tampil' => $maxUrutan + 1]);
        }

        // Process media sosial data
        $mediaSosial = [];
        if ($request->has('media_sosial')) {
            foreach ($request->media_sosial as $media) {
                if (!empty($media['platform']) && !empty($media['url'])) {
                    $mediaSosial[] = [
                        'platform' => $media['platform'],
                        'url' => $media['url']
                    ];
                }
            }
        }

        $data = $request->all();
        $data['media_sosial'] = $mediaSosial;

        $pegawai = Pegawai::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Data pegawai berhasil ditambahkan.',
            'data' => $pegawai
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pegawai $pegawai)
    {
        $type_menu = "master-data";
        // Ambil users yang tidak memiliki role 'User' dan belum terdaftar sebagai pegawai (kecuali pegawai yang sedang diedit)
        $availableUsers = User::whereNotIn('role', ['User'])
            ->where(function($query) use ($pegawai) {
                $query->whereNotIn('id', function($subQuery) {
                    $subQuery->select('user_id')->from('pegawai');
                })
                ->orWhere('id', $pegawai->user_id);
            })
            ->orderBy('name')
            ->get();

        $jabatanOptions = [
            'Lurah',
            'Sekretaris Kelurahan',
            'Seksi Pemerintahan dan Pelayanan Publik',
            'Seksi Kesejahteraan Rakyat dan Perekonomian',
            'Seksi Ketentraman, Ketertiban dan Pembangunan',
            'Staff',
            'Tenaga Kontrak / OS'
        ];

        return view('admin.Pegawai.edit-pegawai', compact('pegawai', 'availableUsers', 'jabatanOptions', 'type_menu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pegawai $pegawai)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id|unique:pegawai,user_id,' . $pegawai->id,
            'jabatan' => 'required|string|max:255',
            'urutan_tampil' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'media_sosial' => 'nullable|array',
            'media_sosial.*.platform' => 'required_with:media_sosial.*.url|string',
            'media_sosial.*.url' => 'required_with:media_sosial.*.platform|url'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Process media sosial data
        $mediaSosial = [];
        if ($request->has('media_sosial')) {
            foreach ($request->media_sosial as $media) {
                if (!empty($media['platform']) && !empty($media['url'])) {
                    $mediaSosial[] = [
                        'platform' => $media['platform'],
                        'url' => $media['url']
                    ];
                }
            }
        }

        $data = $request->all();
        $data['media_sosial'] = $mediaSosial;

        $pegawai->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Data pegawai berhasil diperbarui.',
            'data' => $pegawai
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pegawai $pegawai)
    {
        try {
            $pegawai->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data pegawai berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data pegawai.'
            ], 500);
        }
    }

    /**
     * Method untuk menampilkan pegawai di halaman frontend
     */
    public function publicIndex()
    {
        $pegawai = Pegawai::with('user')
            ->active()
            ->ordered()
            ->get();

        return view('app.pegawai', compact('pegawai'));
    }
}
