<?php

namespace App\Http\Controllers;

use App\Models\Loket;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;


class LoketController extends Controller
{
    public function index()
    {
        // Ambil front office yang belum memiliki loket
        $frontOfficeUsers = User::where('role', 'front_office')
            ->whereNotIn('id', function($query) {
                $query->select('user_id')
                    ->from('lokets')
                    ->whereNotNull('user_id');
            })
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        // Ambil nomor loket yang tersedia
        $availableLoketNumbers = $this->getAvailableLoketNumbers();

        // Cek koneksi ke view
        // dd($frontOfficeUsers, $availableLoketNumbers);

        return view('admin.masterdata.Loket.data-loket', [
            'type_menu' => 'master-data',
            'frontOfficeUsers' => $frontOfficeUsers,
            'availableLoketNumbers' => $availableLoketNumbers,
            'pageTitle' => 'Data Loket' // Tambahan untuk title halaman
        ]);
    }

    public function getData()
    {
        try {
            $lokets = Loket::with(['user' => function($query) {
                    $query->select('id', 'name');
                }])
                ->select('lokets.*')
                ->leftJoin('sessions', function($join) {
                    $join->on('lokets.user_id', '=', 'sessions.user_id')
                        ->where('sessions.last_activity', '>', now()->subMinutes(5)->timestamp);
                });

            return DataTables::of($lokets)
                ->addIndexColumn()
                ->editColumn('user.name', function ($loket) {
                    return $loket->user ? $loket->user->name : '-';
                })
                ->editColumn('loket_number', function ($loket) {
                    return "Loket " . $loket->loket_number;
                })
                ->editColumn('status', function ($loket) {
                    // Cek status berdasarkan sessions
                    // $isOnline = $loket->getLastActivityAttribute() !== null;
                    $isOnline = $loket->isOnline();
                    $statusClass = $isOnline ? 'success' : 'secondary';
                    $status = $isOnline ? 'Online' : 'Offline';
                    $loket->status = $status;
                    $loket->save();
                    return '<span class="badge bg-' . $statusClass . '">' . $status . '</span>';
                })
                ->editColumn('call_status', function ($loket) {
                    $callStatusClass = $loket->call_status === 'calling' ? 'primary' : 'secondary';
                    return '<span class="badge bg-' . $callStatusClass . '">' . ucfirst($loket->call_status) . '</span>';
                })
                ->editColumn('is_active', function ($loket) {
                    // Cek apakah user tidak aktif lebih dari 30 hari
                    $lastSeen = Cache::get('user-last-seen.'.$loket->user_id);

                    if ($lastSeen) {
                        $timestamp = is_object($lastSeen) ? $lastSeen->timestamp : strtotime($lastSeen);
                        $now = time();
                        $diffDays = ($now - $timestamp) / 86400;

                        // Jika tidak aktif lebih dari 30 hari, update status is_active di database
                        if ($diffDays > 30 && $loket->is_active) {
                            $loket->is_active = false;
                            $loket->save();
                        }
                    }

                    // Tampilkan status setelah pengecekan
                    $activeClass = $loket->is_active ? 'success' : 'danger';
                    return '<span class="badge bg-' . $activeClass . '">' .
                        ($loket->is_active ? 'Aktif' : 'Nonaktif') . '</span>';
                })
                ->editColumn('last_activity', function ($loket) {
                    // $lastActivity = $loket->getLastActivityAttribute();
                    // return $lastActivity ? '-' : $lastActivity;

                    // Cek status online dari cache
                    $isOnline = Cache::has('user-online.'.$loket->user_id);

                    if ($isOnline) {
                        return '<span class="badge bg-success rounded-pill">Online</span>';
                    }

                    // Ambil last_seen dari cache - jika tidak ada, tampilkan "Belum pernah login"
                    $lastSeen = Cache::get('user-last-seen.'.$loket->user_id);
                    if (!$lastSeen) {
                        return '<span class="badge bg-secondary rounded-pill">Belum pernah login</span>';
                    }

                    // Konversi waktu terakhir dilihat ke dalam format yang sederhana tanpa error
                    $timestamp = is_object($lastSeen) ? $lastSeen->timestamp : strtotime($lastSeen);
                    $now = time();
                    $diff = $now - $timestamp;

                    // Format tampilan waktu
                    if ($diff < 60) {
                        $time = $diff . ' detik yang lalu';
                    } elseif ($diff < 3600) {
                        $time = floor($diff / 60) . ' menit yang lalu';
                    } elseif ($diff < 86400) {
                        $time = floor($diff / 3600) . ' jam yang lalu';
                    } else {
                        $time = floor($diff / 86400) . ' hari yang lalu';
                    }

                    return '<span class="badge bg-danger rounded-pill">' . $time . '</span>';
                })
                ->addColumn('actions', function($loket) {
                    return '
                        <div class="d-flex justify-content-center gap-2">
                            <a href="'.route('Loket.edit', $loket->id).'" class="btn btn-warning btn-sm">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <form action="'.route('Loket.destroy', $loket->id).'" method="POST" class="delete-form">
                                '.csrf_field().'
                                '.method_field('DELETE').'
                                <button type="submit" class="btn btn-danger btn-sm btn-delete" data-name="'.$loket->user->name.'">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    ';
                })
                ->rawColumns(['status', 'call_status', 'is_active', 'actions', 'last_activity'])
                ->make(true);
        } catch (\Exception $e) {
            \Log::error('Loket DataTables Error: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        // Ambil front office yang belum memiliki loket
        $frontOfficeUsers = User::where('role', 'Front Office')
            ->whereNotIn('id', function($query) {
                $query->select('user_id')
                    ->from('lokets')
                    ->whereNotNull('user_id');
            })
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        // Ambil nomor loket yang tersedia
        $availableLoketNumbers = $this->getAvailableLoketNumbers();

        // Jika request ajax, return json
        if (request()->ajax()) {
            return response()->json([
                'frontOfficeUsers' => $frontOfficeUsers,
                'availableLoketNumbers' => $availableLoketNumbers
            ]);
        }

        return view('admin.masterdata.Loket.create-loket', [
            'type_menu' => 'master-data',
            'frontOfficeUsers' => $frontOfficeUsers,
            'availableLoketNumbers' => $availableLoketNumbers
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => [
                    'required',
                    'exists:users,id',
                    'unique:lokets,user_id'
                ],
                'loket_number' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:8',
                    'unique:lokets,loket_number'
                ],
                'is_active' => 'boolean'
            ], [
                'user_id.required' => 'Front Office harus dipilih',
                'user_id.exists' => 'Front Office tidak valid',
                'user_id.unique' => 'Front Office ini sudah ditugaskan ke loket lain',
                'loket_number.required' => 'Nomor Loket harus dipilih',
                'loket_number.integer' => 'Nomor Loket harus berupa angka',
                'loket_number.min' => 'Nomor Loket minimal 1',
                'loket_number.max' => 'Nomor Loket maksimal 8',
                'loket_number.unique' => 'Nomor loket ini sudah digunakan'
            ]);

            DB::beginTransaction();

            $loket = Loket::create([
                'user_id' => $validated['user_id'],
                'loket_number' => $validated['loket_number'],
                'call_status' => 'standby',
                'is_active' => $request->boolean('is_active', true)
            ]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Loket berhasil ditambahkan'
                ]);
            }

            return redirect()
                ->route('masterdata.loket')
                ->with('success', 'Loket berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan loket: ' . $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan loket: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $loket = Loket::findOrFail($id);

        // Ambil semua front office termasuk yang sedang digunakan di loket ini
        $frontOfficeUsers = User::where('role', 'Front Office')
            ->where(function($query) use ($loket) {
                $query->whereNotIn('id', function($subquery) use ($loket) {
                    $subquery->select('user_id')
                            ->from('lokets')
                            ->where('id', '!=', $loket->id)
                            ->whereNotNull('user_id');
                })
                ->orWhere('id', $loket->user_id);
            })
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        // Ambil available loket numbers dan tambahkan current number
        $availableLoketNumbers = $this->getAvailableLoketNumbers();
        if (!in_array($loket->loket_number, $availableLoketNumbers)) {
            $availableLoketNumbers[] = $loket->loket_number;
            sort($availableLoketNumbers);
        }

        if (request()->ajax()) {
            return response()->json([
                'loket' => $loket,
                'users' => $frontOfficeUsers
            ]);
        }

        return view('admin.masterdata.Loket.edit-loket', [
            'type_menu' => 'master-data',
            'loket' => $loket,
            'frontOfficeUsers' => $frontOfficeUsers,
            'availableLoketNumbers' => $availableLoketNumbers
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $loket = Loket::findOrFail($id);

            $validated = $request->validate([
                'user_id' => 'required|exists:users,id|unique:lokets,user_id,' . $id,
                'loket_number' => 'required|integer|min:1|max:8|unique:lokets,loket_number,' . $id,
                'is_active' => 'boolean'
            ], [
                'user_id.unique' => 'Front Office ini sudah ditugaskan ke loket lain',
                'loket_number.unique' => 'Nomor loket ini sudah digunakan'
            ]);

            DB::beginTransaction();

            $loket->update([
                'user_id' => $validated['user_id'],
                'loket_number' => $validated['loket_number'],
                'is_active' => $request->boolean('is_active', true)
            ]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Loket berhasil diperbarui'
                ]);
            }

            return redirect()
                ->route('lokets.index')
                ->with('success', 'Loket berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui loket: ' . $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui loket: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $loket = Loket::findOrFail($id);
            $loket->delete();

            return response()->json([
                'success' => true,
                'message' => 'Loket berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus loket'
            ], 500);
        }
    }

    private function getAvailableLoketNumbers($excludeLoketId = null)
    {
        $query = Loket::query();

        if ($excludeLoketId) {
            $query->where('id', '!=', $excludeLoketId);
        }

        $usedNumbers = $query->pluck('loket_number')->toArray();
        $allNumbers = range(1, 8);

        return array_values(array_diff($allNumbers, $usedNumbers));
    }
}
