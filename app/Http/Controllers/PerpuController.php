<?php

namespace App\Http\Controllers;

use App\Models\Perpu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PerpuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $type_menu = "master-data";
        return view('admin.masterdata.Perpu.index', compact('type_menu'));
    }

    /**
     * Get data for DataTables
     */
    public function data()
    {
        $perpu = Perpu::select('perpu.*');

        return DataTables::of($perpu)
            ->addColumn('full_title', function ($row) {
                return $row->full_title;
            })
            ->addColumn('file_info', function ($row) {
                $fileExists = Storage::disk('public')->exists('perpu/' . $row->file_pdf);
                $icon = $fileExists ? 'fas fa-file-pdf text-danger' : 'fas fa-file-pdf text-muted';

                return '<div class="d-flex align-items-center">
                    <i class="' . $icon . ' fa-2x me-2"></i>
                    <div>
                        <div class="fw-bold">' . $row->file_pdf . '</div>
                        <small class="text-muted">' . $row->formatted_file_size . '</small>
                    </div>
                </div>';
            })
            ->addColumn('jenis_badge', function ($row) {
                return $row->jenis_badge;
            })
            ->addColumn('status_badge', function ($row) {
                return $row->status_badge;
            })
            ->addColumn('active_badge', function ($row) {
                return $row->active_badge;
            })
            ->addColumn('download_info', function ($row) {
                return '<div class="text-center">
                    <span class="badge badge-info">' . number_format($row->download_count) . '</span>
                    <br><small class="text-muted">downloads</small>
                </div>';
            })
            ->addColumn('actions', function ($row) {
                $viewBtn = '<a href="' . route('admin.Perpu.show', $row->id) . '" class="btn btn-sm btn-info me-1" title="Lihat PDF">
                    <i class="fas fa-eye"></i>
                </a>';

                $editBtn = '<a href="' . route('admin.Perpu.edit', $row->id) . '" class="btn btn-sm btn-warning me-1" title="Edit">
                    <i class="fas fa-edit"></i>
                </a>';

                $downloadBtn = '<a href="' . route('admin.Perpu.download', $row->id) . '" class="btn btn-sm btn-success me-1" title="Download">
                    <i class="fas fa-download"></i>
                </a>';

                $deleteBtn = '<form action="' . route('admin.Perpu.destroy', $row->id) . '" method="POST" style="display: inline;">
                    ' . csrf_field() . '
                    ' . method_field('DELETE') . '
                    <button type="button" class="btn btn-sm btn-danger btn-delete" data-name="' . $row->full_title . '" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>';

                return $viewBtn . $editBtn . $downloadBtn . $deleteBtn;
            })
            ->rawColumns(['file_info', 'jenis_badge', 'status_badge', 'active_badge', 'download_info', 'actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $type_menu = "master-data";
        $jenisOptions = Perpu::getJenisOptions();
        $tahunOptions = range(date('Y'), 2000);

        return view('admin.masterdata.Perpu.create', compact('type_menu', 'jenisOptions', 'tahunOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor_peraturan' => 'required|string|max:50',
            'tahun' => 'required|digits:4|max:' . (date('Y') + 1),
            'judul' => 'required|string|max:500',
            'tentang' => 'required|string',
            'deskripsi' => 'nullable|string',
            'file_pdf' => 'required|file|mimes:pdf|max:51200', // 50MB in KB
            'tanggal_penetapan' => 'required|date',
            'jenis_peraturan' => 'required|in:' . implode(',', Perpu::getJenisOptions()),
            'status' => 'required|in:Draft,Published,Archived',
            'urutan_tampil' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'tags' => 'nullable|string'
        ], [
            'file_pdf.required' => 'File PDF wajib diupload',
            'file_pdf.mimes' => 'File harus berformat PDF',
            'file_pdf.max' => 'Ukuran file maksimal 50MB',
            'nomor_peraturan.required' => 'Nomor peraturan wajib diisi',
            'tahun.required' => 'Tahun wajib diisi',
            'tahun.digits' => 'Tahun harus 4 digit',
            'judul.required' => 'Judul wajib diisi',
            'tentang.required' => 'Tentang wajib diisi'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Handle file upload
            if (!$request->hasFile('file_pdf')) {
                return response()->json([
                    'success' => false,
                    'message' => 'File PDF tidak ditemukan dalam request.'
                ], 422);
            }

            $file = $request->file('file_pdf');

            // Validate file
            if (!$file->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'File PDF tidak valid atau rusak.'
                ], 422);
            }

            // Generate unique filename
            $fileName = time() . '_' . uniqid() . '_' . Str::slug($request->judul) . '.pdf';

            // Store file in storage/app/public/perpu
            $filePath = $file->storeAs('perpu', $fileName, 'public');

            if (!$filePath) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan file PDF.'
                ], 500);
            }

            // Get file size
            $fileSize = $file->getSize();

            // Set urutan tampil otomatis jika tidak diisi
            $urutanTampil = $request->urutan_tampil;
            if (!$urutanTampil) {
                $maxUrutan = Perpu::max('urutan_tampil') ?? 0;
                $urutanTampil = $maxUrutan + 1;
            }

            // Process tags - handle both array and string
            $tags = [];
            if ($request->tags) {
                if (is_string($request->tags)) {
                    $tags = array_map('trim', explode(',', $request->tags));
                    $tags = array_filter($tags); // Remove empty values
                } elseif (is_array($request->tags)) {
                    $tags = $request->tags;
                }
            }

            // Prepare data for saving
            $data = [
                'nomor_peraturan' => $request->nomor_peraturan,
                'tahun' => $request->tahun,
                'judul' => $request->judul,
                'tentang' => $request->tentang,
                'deskripsi' => $request->deskripsi,
                'file_pdf' => $fileName,
                'ukuran_file' => $fileSize,
                'tanggal_penetapan' => $request->tanggal_penetapan,
                'tanggal_upload' => now(),
                'jenis_peraturan' => $request->jenis_peraturan,
                'status' => $request->status,
                'urutan_tampil' => $urutanTampil,
                'is_active' => $request->has('is_active') ? true : false,
                'tags' => $tags,
                'download_count' => 0
            ];

            $perpu = Perpu::create($data);

            // Verify file was actually saved
            if (!Storage::disk('public')->exists('perpu/' . $fileName)) {
                // If file doesn't exist, delete the database record
                $perpu->delete();
                return response()->json([
                    'success' => false,
                    'message' => 'File tidak tersimpan dengan benar di storage.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data peraturan berhasil ditambahkan.',
                'data' => $perpu
            ]);

        } catch (\Exception $e) {
            // Clean up uploaded file if database save fails
            if (isset($fileName) && Storage::disk('public')->exists('perpu/' . $fileName)) {
                Storage::disk('public')->delete('perpu/' . $fileName);
            }

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // $perpu = $data_perpu;
        $perpu = Perpu::findOrFail($id);
        $type_menu = "master-data";

        // Check if file exists
        $fileExists = Storage::disk('public')->exists('perpu/' . $perpu->file_pdf);

        if (!$fileExists) {
            return redirect()->route('admin.Perpu.index')
                           ->with('error', 'File PDF tidak ditemukan.');
        }

        return view('admin.masterdata.Perpu.show', compact('perpu', 'type_menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $perpu = Perpu::findOrFail($id);
        $type_menu = "master-data";
        $jenisOptions = Perpu::getJenisOptions();
        $tahunOptions = range(date('Y'), 2000);

        return view('admin.masterdata.Perpu.edit', compact('perpu', 'type_menu', 'jenisOptions', 'tahunOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // $perpu = $data_perpu;
        $perpu = Perpu::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nomor_peraturan' => 'required|string|max:50',
            'tahun' => 'required|digits:4|max:' . (date('Y') + 1),
            'judul' => 'required|string|max:500',
            'tentang' => 'required|string',
            'deskripsi' => 'nullable|string',
            'file_pdf' => 'nullable|file|mimes:pdf|max:50048', // Optional on update
            'tanggal_penetapan' => 'required|date',
            'jenis_peraturan' => 'required|in:' . implode(',', Perpu::getJenisOptions()),
            'status' => 'required|in:Draft,Published,Archived',
            'urutan_tampil' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'tags' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->all();

            // Handle file upload if new file provided
            if ($request->hasFile('file_pdf')) {
                // Delete old file
                $perpu->deleteFile();

                // Upload new file
                $file = $request->file('file_pdf');
                $fileName = time() . '_' . Str::slug($request->judul) . '.pdf';
                $filePath = $file->storeAs('perpu', $fileName, 'public');

                $data['file_pdf'] = $fileName;
                $data['ukuran_file'] = $file->getSize();
            }

            // Process tags
            $tags = [];
            if ($request->tags) {
                $tags = array_map('trim', explode(',', $request->tags));
            }
            $data['tags'] = $tags;

            $perpu->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Data peraturan berhasil diperbarui.',
                'data' => $perpu
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Perpu $data_perpu)
    {
        $perpu = $data_perpu;
        try {
            $perpu->delete(); // Will trigger deleteFile() in model boot method

            return response()->json([
                'success' => true,
                'message' => 'Data peraturan berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data peraturan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download PDF file
     */
    public function download(Perpu $perpu)
    {
        $filePath = 'perpu/' . $perpu->file_pdf;

        if (!Storage::disk('public')->exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        // Increment download count
        $perpu->incrementDownload();

        return Storage::disk('public')->download($filePath, $perpu->full_title . '.pdf');
    }

    /**
     * Public listing for frontend
     */
    public function publicIndex(Request $request)
    {
        $perpu = Perpu::active()
                     ->published()
                     ->ordered();

        // Filter by jenis if provided
        if ($request->jenis) {
            $perpu->byJenis($request->jenis);
        }

        // Filter by tahun if provided
        if ($request->tahun) {
            $perpu->byTahun($request->tahun);
        }

        // Search functionality
        if ($request->search) {
            $search = $request->search;
            $perpu->where(function($query) use ($search) {
                $query->where('judul', 'like', "%{$search}%")
                      ->orWhere('tentang', 'like', "%{$search}%")
                      ->orWhere('nomor_peraturan', 'like', "%{$search}%")
                      ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        $perpu = $perpu->paginate(12);

        $jenisOptions = Perpu::getJenisOptions();
        $tahunOptions = Perpu::getYearOptions();

        return view('layanan.Perpu.index', compact('perpu', 'jenisOptions', 'tahunOptions'));
    }

    /**
     * Show PDF in frontend
     */
    public function publicShow(Perpu $perpu)
    {
        if (!$perpu->is_active || $perpu->status !== 'Published') {
            abort(404);
        }

        $fileExists = Storage::disk('public')->exists('perpu/' . $perpu->file_pdf);

        if (!$fileExists) {
            abort(404, 'File PDF tidak ditemukan.');
        }

        return view('layanan.Perpu.show', compact('perpu'));
    }

    public function viewPdf($id)
    {
        $perpu = Perpu::findOrFail($id);

        // Path ke file PDF di storage
        $filePath = storage_path('app/public/perpu/' . $perpu->file_pdf);

        // Cek apakah file ada
        if (!file_exists($filePath)) {
            abort(404, 'File PDF tidak ditemukan');
        }

        // Return response dengan headers yang tepat untuk PDF
        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $perpu->full_title . '.pdf"'
        ]);
    }
}
