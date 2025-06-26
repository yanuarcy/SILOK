<?php

namespace App\Http\Controllers;

use App\Models\Antarmuka;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class AntarmukaController extends Controller
{
    public function index()
    {
        $type_menu = "master-data";
        return view('admin.Antarmuka.data-antarmuka', compact('type_menu'));
    }

    public function getData()
    {
        $antarmukas = Antarmuka::select([
            'id_antarmuka',
            'keterangan',
            'nama',
            'durasi_video',
            'sumber',
            'volume',
            'status',
            'created_at'
        ])->orderBy('created_at', 'desc');

        return DataTables::of($antarmukas)
            ->addColumn('preview', function ($antarmuka) {
                $sourceType = $this->getSourceType($antarmuka->sumber);

                if ($sourceType === 'upload') {
                    // For uploaded files, create preview button
                    $videoUrl = asset($antarmuka->sumber);
                    return '<a href="' . $videoUrl . '" target="_blank" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-play"></i> Play
                            </a>';
                } else {
                    // For URL sources
                    return '<a href="' . $antarmuka->sumber . '" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-external-link-alt"></i> View
                            </a>';
                }
            })
            ->addColumn('volume_control', function ($antarmuka) {
                return '
                    <div class="volume-control-wrapper d-flex align-items-center justify-content-center" style="min-width: 140px;">
                        <i class="fas fa-volume-down text-muted me-2" style="font-size: 14px;"></i>
                        <div class="volume-slider-container position-relative">
                            <input type="range"
                                   class="volume-range-slider"
                                   data-video-id="' . $antarmuka->id_antarmuka . '"
                                   min="0"
                                   max="100"
                                   value="' . ($antarmuka->volume ?? 50) . '"
                                   style="width: 80px; height: 6px;">
                        </div>
                        <span class="volume-percentage ms-2 fw-bold text-primary" style="min-width: 35px; font-size: 12px;">
                            ' . ($antarmuka->volume ?? 50) . '%
                        </span>
                    </div>';
            })
            ->addColumn('status', function ($antarmuka) {
                if ($antarmuka->status) {
                    return '<span class="badge badge-success">Aktif</span>';
                } else {
                    return '<span class="badge badge-secondary">Tidak Aktif</span>';
                }
            })
            ->addColumn('durasi_video', function ($antarmuka) {
                if ($antarmuka->durasi_video) {
                    $minutes = floor($antarmuka->durasi_video / 60);
                    $seconds = $antarmuka->durasi_video % 60;
                    return $minutes > 0 ? "{$minutes}m {$seconds}s" : "{$seconds}s";
                }
                return '-';
            })
            ->addColumn('sumber_type', function ($antarmuka) {
                $sourceType = $this->getSourceType($antarmuka->sumber);

                switch ($sourceType) {
                    case 'youtube':
                        return '<span class="badge badge-danger">YouTube</span>';
                    case 'vimeo':
                        return '<span class="badge badge-info">Vimeo</span>';
                    case 'upload':
                        return '<span class="badge badge-primary">Upload</span>';
                    default:
                        return '<span class="badge badge-warning">URL</span>';
                }
            })
            ->addColumn('actions', function ($antarmuka) {
                $editUrl = route('Antarmuka.edit', $antarmuka->id_antarmuka);
                $deleteUrl = route('Antarmuka.destroy', $antarmuka->id_antarmuka);

                $actions = '<div class="btn-group" role="group">';

                $actions .= '<a href="' . $editUrl . '" class="btn btn-sm btn-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>';

                if ($antarmuka->status) {
                    $deactivateUrl = route('antarmuka.deactivate', $antarmuka->id_antarmuka);
                    $actions .= '<form action="' . $deactivateUrl . '" method="POST" style="display: inline;">
                                    ' . csrf_field() . '
                                    <button type="submit" class="btn btn-sm btn-secondary" title="Deactivate">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </form>';
                } else {
                    $activateUrl = route('antarmuka.activate', $antarmuka->id_antarmuka);
                    $actions .= '<form action="' . $activateUrl . '" method="POST" style="display: inline;">
                                    ' . csrf_field() . '
                                    <button type="submit" class="btn btn-sm btn-success" title="Activate">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </form>';
                }

                $actions .= '<form action="' . $deleteUrl . '" method="POST" style="display: inline;">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="button" class="btn btn-sm btn-danger btn-delete"
                                        data-name="' . $antarmuka->nama . '" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>';

                $actions .= '</div>';

                return $actions;
            })
            ->rawColumns(['preview', 'volume_control', 'status', 'sumber_type', 'actions'])
            ->make(true);
    }

    public function updateVolume(Request $request, $id)
    {
        try {
            $request->validate([
                'volume' => 'required|integer|min:0|max:100'
            ]);

            $antarmuka = Antarmuka::findOrFail($id);
            $oldVolume = $antarmuka->volume;

            $antarmuka->update(['volume' => $request->volume]);

            Log::info('Antarmuka volume updated', [
                'id' => $antarmuka->id_antarmuka,
                'nama' => $antarmuka->nama,
                'old_volume' => $oldVolume,
                'new_volume' => $request->volume
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Volume berhasil diperbarui',
                'data' => [
                    'id' => $antarmuka->id_antarmuka,
                    'volume' => $request->volume,
                    'formatted_volume' => $request->volume . '%'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating volume', [
                'id' => $id,
                'volume' => $request->volume ?? null,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui volume: ' . $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        $type_menu = "master-data";
        return view('admin.Antarmuka.create-antarmuka', compact('type_menu'));
    }

    public function store(Request $request)
    {
        Log::info('Store request started', [
            'sumber_type' => $request->sumber_type,
            'has_file' => $request->hasFile('video_file'),
            'file_info' => $request->hasFile('video_file') ? [
                'name' => $request->file('video_file')->getClientOriginalName(),
                'size' => $request->file('video_file')->getSize(),
                'mime' => $request->file('video_file')->getMimeType()
            ] : null
        ]);

        // Enhanced validation
        $rules = [
            'keterangan' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'durasi_video' => 'nullable|integer|min:1',
            'volume' => 'nullable|integer|min:0|max:100',
            'sumber_type' => 'required|in:upload,youtube,vimeo,url',
            'status' => 'nullable|boolean'
        ];

        // Conditional validation based on source type
        if ($request->sumber_type === 'upload') {
            $rules['video_file'] = 'required|file|mimes:mp4,avi,mov,wmv|max:102400'; // 100MB max
        } else {
            $rules['sumber'] = 'required|url';
        }

        $request->validate($rules);

        // Handle different source types
        $sumberValue = null;

        if ($request->sumber_type === 'upload') {
            if (!$request->hasFile('video_file')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Video file is required for upload source'
                ], 422);
            }

            try {
                $file = $request->file('video_file');

                // Generate unique filename
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                // Store file in public/storage/videos
                $filePath = $file->storeAs('videos', $fileName, 'public');

                // Store relative path for database
                $sumberValue = 'storage/' . $filePath;

                Log::info('File uploaded successfully', [
                    'original_name' => $file->getClientOriginalName(),
                    'stored_name' => $fileName,
                    'path' => $sumberValue,
                    'size' => $file->getSize()
                ]);

            } catch (\Exception $e) {
                Log::error('File upload failed', [
                    'error' => $e->getMessage(),
                    'file' => $request->hasFile('video_file') ? $request->file('video_file')->getClientOriginalName() : null
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload file: ' . $e->getMessage()
                ], 500);
            }
        } else {
            // For URL sources (youtube, vimeo, url)
            $sumberValue = $request->sumber;
        }

        try {
            $antarmuka = Antarmuka::create([
                'keterangan' => $request->keterangan,
                'nama' => $request->nama,
                'durasi_video' => $request->durasi_video,
                'sumber' => $sumberValue,
                'volume' => $request->volume ?? 50,
                'status' => $request->has('status') ? 1 : 0
            ]);

            // If this video is set as active, deactivate others
            if ($antarmuka->status) {
                Antarmuka::where('id_antarmuka', '!=', $antarmuka->id_antarmuka)
                         ->update(['status' => 0]);
            }

            Log::info('Antarmuka created successfully', [
                'id' => $antarmuka->id_antarmuka,
                'nama' => $antarmuka->nama,
                'sumber_type' => $request->sumber_type,
                'sumber' => $sumberValue,
                'volume' => $antarmuka->volume
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Video antarmuka berhasil ditambahkan',
                    'data' => [
                        'id' => $antarmuka->id_antarmuka,
                        'nama' => $antarmuka->nama,
                        'sumber_type' => $request->sumber_type
                    ]
                ]);
            }

            return redirect()->route('antarmuka.index')
                ->with('success', 'Video antarmuka berhasil ditambahkan');

        } catch (\Exception $e) {
            Log::error('Database save failed', [
                'error' => $e->getMessage(),
                'sumber' => $sumberValue
            ]);

            // Delete uploaded file if database save fails
            if ($request->sumber_type === 'upload' && $sumberValue) {
                $filePath = str_replace('storage/', '', $sumberValue);
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to save to database: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Antarmuka $antarmuka)
    {
        $type_menu = "master-data";
        return view('admin.antarmuka.show', compact('antarmuka', 'type_menu'));
    }

    public function edit($id)
    {
        $antarmuka = Antarmuka::findOrFail($id);
        $type_menu = "master-data";
        return view('admin.Antarmuka.edit-antarmuka', compact('antarmuka', 'type_menu'));
    }

    public function update(Request $request, $id)
    {
        $antarmuka = Antarmuka::findOrFail($id);

        Log::info('Update request started', [
            'id' => $id,
            'sumber_type' => $request->sumber_type,
            'current_sumber' => $antarmuka->sumber,
            'has_file' => $request->hasFile('video_file')
        ]);

        // Enhanced validation
        $rules = [
            'keterangan' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'durasi_video' => 'nullable|integer|min:1',
            'volume' => 'nullable|integer|min:0|max:100',
            'sumber_type' => 'required|in:upload,youtube,vimeo,url',
            'status' => 'nullable|boolean'
        ];

        // Conditional validation
        if ($request->sumber_type === 'upload') {
            $rules['video_file'] = 'nullable|file|mimes:mp4,avi,mov,wmv|max:102400';
        } else {
            $rules['sumber'] = 'required|url';
        }

        $request->validate($rules);

        // Handle file upload for update
        $sumberValue = $antarmuka->sumber; // Keep existing value by default

        if ($request->sumber_type === 'upload') {
            if ($request->hasFile('video_file')) {
                try {
                    // Delete old file if exists and it's an uploaded file
                    if ($antarmuka->sumber && !filter_var($antarmuka->sumber, FILTER_VALIDATE_URL)) {
                        $oldFilePath = str_replace('storage/', '', $antarmuka->sumber);
                        if (Storage::disk('public')->exists($oldFilePath)) {
                            Storage::disk('public')->delete($oldFilePath);
                            Log::info('Old file deleted', ['path' => $oldFilePath]);
                        }
                    }

                    // Upload new file
                    $file = $request->file('video_file');
                    $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $filePath = $file->storeAs('videos', $fileName, 'public');
                    $sumberValue = 'storage/' . $filePath;

                    Log::info('New file uploaded', [
                        'original_name' => $file->getClientOriginalName(),
                        'stored_name' => $fileName,
                        'path' => $sumberValue
                    ]);

                } catch (\Exception $e) {
                    Log::error('File upload failed during update', [
                        'error' => $e->getMessage(),
                        'file' => $file->getClientOriginalName()
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to upload file: ' . $e->getMessage()
                    ], 500);
                }
            }
            // If no new file uploaded, keep existing file
        } else {
            // For URL sources, delete old uploaded file if exists
            if ($antarmuka->sumber && !filter_var($antarmuka->sumber, FILTER_VALIDATE_URL)) {
                $oldFilePath = str_replace('storage/', '', $antarmuka->sumber);
                if (Storage::disk('public')->exists($oldFilePath)) {
                    Storage::disk('public')->delete($oldFilePath);
                    Log::info('Old uploaded file deleted when switching to URL source', ['path' => $oldFilePath]);
                }
            }
            $sumberValue = $request->sumber;
        }

        try {
            $antarmuka->update([
                'keterangan' => $request->keterangan,
                'nama' => $request->nama,
                'durasi_video' => $request->durasi_video,
                'sumber' => $sumberValue,
                'volume' => $request->volume ?? $antarmuka->volume,
                'status' => $request->has('status') ? 1 : 0
            ]);

            // If this video is set as active, deactivate others
            if ($antarmuka->status) {
                Antarmuka::where('id_antarmuka', '!=', $antarmuka->id_antarmuka)
                         ->update(['status' => 0]);
            }

            Log::info('Antarmuka updated successfully', [
                'id' => $antarmuka->id_antarmuka,
                'nama' => $antarmuka->nama,
                'sumber_type' => $request->sumber_type,
                'sumber' => $sumberValue,
                'volume' => $antarmuka->volume
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Video antarmuka berhasil diperbarui'
                ]);
            }

            return redirect()->route('antarmuka.index')
                ->with('success', 'Video antarmuka berhasil diperbarui');

        } catch (\Exception $e) {
            Log::error('Database update failed', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update database: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $antarmuka = Antarmuka::findOrFail($id);

        try {
            // Delete uploaded file if exists
            if ($antarmuka->sumber && !filter_var($antarmuka->sumber, FILTER_VALIDATE_URL)) {
                $filePath = str_replace('storage/', '', $antarmuka->sumber);
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                    Log::info('Video file deleted', ['file_path' => $filePath]);
                }
            }

            $antarmuka->delete();

            Log::info('Antarmuka deleted successfully', [
                'id' => $antarmuka->id_antarmuka,
                'nama' => $antarmuka->nama
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Video antarmuka berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            Log::error('Delete failed', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete: ' . $e->getMessage()
            ], 500);
        }
    }

    public function activate($id)
    {
        $antarmuka = Antarmuka::findOrFail($id);

        // Deactivate all others first
        Antarmuka::where('id_antarmuka', '!=', $id)->update(['status' => 0]);

        // Activate this one
        $antarmuka->update(['status' => 1]);

        Log::info('Antarmuka activated', ['id' => $antarmuka->id_antarmuka, 'nama' => $antarmuka->nama]);

        return redirect()->route('antarmuka.index')
            ->with('success', "Video '{$antarmuka->nama}' telah diaktifkan");
    }

    public function deactivate($id)
    {
        $antarmuka = Antarmuka::findOrFail($id);
        $antarmuka->update(['status' => 0]);

        Log::info('Antarmuka deactivated', ['id' => $antarmuka->id_antarmuka, 'nama' => $antarmuka->nama]);

        return redirect()->route('antarmuka.index')
            ->with('success', "Video '{$antarmuka->nama}' telah dinonaktifkan");
    }

    /**
     * Determine source type from URL/path
     */
    private function getSourceType($sumber)
    {
        if (strpos($sumber, 'youtube.com') !== false || strpos($sumber, 'youtu.be') !== false) {
            return 'youtube';
        } elseif (strpos($sumber, 'vimeo.com') !== false) {
            return 'vimeo';
        } elseif (filter_var($sumber, FILTER_VALIDATE_URL)) {
            return 'url';
        } else {
            return 'upload';
        }
    }
}
