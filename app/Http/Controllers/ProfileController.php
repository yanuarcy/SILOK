<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\DataKependudukan;
use App\Models\User;
use App\Models\Puntadewa;
use App\Models\UserApplication;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get available RT and RW options based on data_kependudukan and current usage
        $availableRT = $this->getAvailableRT();
        $availableRW = $this->getAvailableRW();

        // Get available RW and RT with validation for roles
        $rwRtData = $this->getAvailableRwRt();

        // Get user statistics
        $userStats = $this->getUserStatistics();

        return view('profile.index', [
            'type_menu' => 'profile',
            'availableRW' => $rwRtData['availableRW'],
            'rwRtMapping' => $rwRtData['rwRtMapping'],
            'userStats' => $userStats,
            'currentUserRW' => Auth::user()->rw,
            'currentUserRT' => Auth::user()->rt,
        ]);
    }

    private function getAvailableRwRt()
    {
        // Get total RW and RT from DataKependudukan
        $rtRwOptions = DataKependudukan::select('total_rt', 'total_rw')->first();

        // Define RW-RT mapping based on your requirements
        $rwRtMapping = [
            '01' => 6,  // RW 01 has 6 RT (01-06)
            '02' => 7,  // RW 02 has 7 RT (01-07)
            '03' => 10, // RW 03 has 10 RT (01-10)
            '04' => 8,  // RW 04 has 8 RT (01-08)
            '05' => 10, // RW 05 has 10 RT (01-10)
            '06' => 4,  // RW 06 has 4 RT (01-04)
            '07' => 4,  // RW 07 has 4 RT (01-04)
            '08' => 3,  // RW 08 has 3 RT (01-03)
            '09' => 8,  // RW 09 has 8 RT (01-08)
            '10' => 3,  // RW 10 has 3 RT (01-03)
        ];

        $availableRW = [];
        $currentUser = Auth::user();

        // Get occupied RW/RT for Ketua RW and Ketua RT (excluding current user)
        $occupiedRW = [];
        $occupiedRT = [];

        if (in_array($currentUser->role, ['Ketua RT', 'Ketua RW'])) {
            // Get occupied RW by other Ketua RW
            $occupiedRW = User::where('role', 'Ketua RW')
                ->where('id', '!=', $currentUser->id)
                ->whereNotNull('rw')
                ->pluck('rw')
                ->toArray();

            // Get occupied RT by other Ketua RT (with their RW)
            $occupiedRTData = User::where('role', 'Ketua RT')
                ->where('id', '!=', $currentUser->id)
                ->whereNotNull('rt')
                ->whereNotNull('rw')
                ->select('rw', 'rt')
                ->get();

            foreach ($occupiedRTData as $data) {
                $occupiedRT[$data->rw][] = $data->rt;
            }
        }

        // Generate available RW options
        foreach ($rwRtMapping as $rw => $rtCount) {
            // Format: RW 01, RW 02, ..., RW 10
            $rwLabel = "RW " . $rw;

            // For Ketua RW, exclude occupied RW
            if ($currentUser->role === 'Ketua RW' && in_array($rw, $occupiedRW) && $currentUser->rw !== $rw) {
                continue;
            }

            // For Ketua RT, allow all RW (including those occupied by Ketua RW)
            // They just can't choose the same RT as other Ketua RT in that RW

            $availableRW[] = [
                'value' => $rw,
                'label' => $rwLabel,
                'rt_count' => $rtCount
            ];
        }

        return [
            'availableRW' => $availableRW,
            'rwRtMapping' => $rwRtMapping,
            'occupiedRT' => $occupiedRT
        ];
    }

    // New AJAX endpoint to get RT based on selected RW
    public function getRtByRw(Request $request)
    {
        $selectedRw = $request->rw;
        $currentUser = Auth::user();

        // RW-RT mapping
        $rwRtMapping = [
            '01' => 6, '02' => 7, '03' => 10, '04' => 8, '05' => 10,
            '06' => 4, '07' => 4, '08' => 3, '09' => 8, '10' => 3,
        ];

        $availableRT = [];
        $rtCount = $rwRtMapping[$selectedRw] ?? 0;

        // Get occupied RT for this RW by other Ketua RT
        $occupiedRT = [];
        if (in_array($currentUser->role, ['Ketua RT', 'Ketua RW'])) {
            $occupiedRT = User::where('role', 'Ketua RT')
                ->where('id', '!=', $currentUser->id)
                ->where('rw', $selectedRw)
                ->whereNotNull('rt')
                ->pluck('rt')
                ->toArray();
        }

        // Generate RT options
        for ($i = 1; $i <= $rtCount; $i++) {
            // Format: RT 01, RT 02, ..., RT 10 (no leading zero for 10)
            $rt = $i == 10 ? '10' : sprintf('%02d', $i);
            $rtLabel = "RT " . $rt;

            // For Ketua RT, exclude occupied RT
            if ($currentUser->role === 'Ketua RT' && in_array($rt, $occupiedRT) && $currentUser->rt !== $rt) {
                continue;
            }

            $availableRT[] = [
                'value' => $rt,
                'label' => $rtLabel
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $availableRT
        ]);
    }

    /**
     * Get user statistics for profile widget
     */
    private function getUserStatistics()
    {
        $userId = Auth::id();
        $userRole = Auth::user()->role;

        // Initialize stats
        $stats = [
            'total_pengajuan' => 0,
            'selesai' => 0,
            'proses' => 0,
            'ditolak' => 0
        ];

        // Get statistics based on user role
        if ($userRole === 'user') {
            // For regular users - use UserApplication data for their own applications
            $stats['total_pengajuan'] = UserApplication::byUser($userId)->count();
            $stats['selesai'] = UserApplication::byUser($userId)
                               ->whereIn('status', ['approved_rw', 'approved_kelurahan', 'completed'])
                               ->count();
            $stats['proses'] = UserApplication::byUser($userId)
                              ->whereIn('status', ['pending_rt', 'approved_rt', 'pending_rw', 'approved_rw', 'pending_kelurahan'])
                              ->count();
            $stats['ditolak'] = UserApplication::byUser($userId)
                               ->whereIn('status', ['rejected_rt', 'rejected_rw', 'rejected_kelurahan'])
                               ->count();
        }
        elseif (in_array($userRole, ['Ketua RT', 'Ketua RW'])) {
            // For RT/RW - show BOTH their own applications AND applications needing their approval

            // Their own applications as users
            $ownStats = [
                'total_pengajuan' => UserApplication::byUser($userId)->count(),
                'selesai' => UserApplication::byUser($userId)
                           ->whereIn('status', ['approved_rw', 'approved_kelurahan', 'completed'])
                           ->count(),
                'proses' => UserApplication::byUser($userId)
                          ->whereIn('status', ['pending_rt', 'approved_rt', 'pending_rw', 'approved_rw', 'pending_kelurahan'])
                          ->count(),
                'ditolak' => UserApplication::byUser($userId)
                           ->whereIn('status', ['rejected_rt', 'rejected_rw', 'rejected_kelurahan'])
                           ->count()
            ];

            // Applications in their area that need approval
            $approvalStats = [
                'total_pengajuan' => 0,
                'selesai' => 0,
                'proses' => 0,
                'ditolak' => 0
            ];

            if ($userRole === 'Ketua RT') {
                $userRT = Auth::user()->rt;
                if ($userRT) {
                    // Only count applications from OTHER users in their RT
                    $approvalStats['total_pengajuan'] = UserApplication::where('rt', $userRT)
                                                       ->where('user_id', '!=', $userId)
                                                       ->count();
                    $approvalStats['selesai'] = UserApplication::where('rt', $userRT)
                                               ->where('user_id', '!=', $userId)
                                               ->whereIn('status', ['approved_rw', 'approved_kelurahan', 'completed'])
                                               ->count();
                    $approvalStats['proses'] = UserApplication::where('rt', $userRT)
                                              ->where('user_id', '!=', $userId)
                                              ->whereIn('status', ['pending_rt', 'approved_rt', 'pending_rw', 'approved_rw', 'pending_kelurahan'])
                                              ->count();
                    $approvalStats['ditolak'] = UserApplication::where('rt', $userRT)
                                               ->where('user_id', '!=', $userId)
                                               ->whereIn('status', ['rejected_rt', 'rejected_rw', 'rejected_kelurahan'])
                                               ->count();
                }
            }
            elseif ($userRole === 'Ketua RW') {
                $userRW = Auth::user()->rw;
                if ($userRW) {
                    // Only count applications from OTHER users in their RW
                    $approvalStats['total_pengajuan'] = UserApplication::where('rw', $userRW)
                                                       ->where('user_id', '!=', $userId)
                                                       ->count();
                    $approvalStats['selesai'] = UserApplication::where('rw', $userRW)
                                               ->where('user_id', '!=', $userId)
                                               ->whereIn('status', ['approved_rw', 'approved_kelurahan', 'completed'])
                                               ->count();
                    $approvalStats['proses'] = UserApplication::where('rw', $userRW)
                                              ->where('user_id', '!=', $userId)
                                              ->whereIn('status', ['pending_rt', 'approved_rt', 'pending_rw', 'approved_rw', 'pending_kelurahan'])
                                              ->count();
                    $approvalStats['ditolak'] = UserApplication::where('rw', $userRW)
                                               ->where('user_id', '!=', $userId)
                                               ->whereIn('status', ['rejected_rt', 'rejected_rw', 'rejected_kelurahan'])
                                               ->count();
                }
            }

            // Combine own stats with approval stats
            $stats['total_pengajuan'] = $ownStats['total_pengajuan'] + $approvalStats['total_pengajuan'];
            $stats['selesai'] = $ownStats['selesai'] + $approvalStats['selesai'];
            $stats['proses'] = $ownStats['proses'] + $approvalStats['proses'];
            $stats['ditolak'] = $ownStats['ditolak'] + $approvalStats['ditolak'];
        }
        elseif (in_array($userRole, ['admin', 'Front Office', 'Back Office', 'Lurah', 'Operator'])) {
            // For admin/staff roles - count all documents from UserApplication
            $stats['total_pengajuan'] = UserApplication::count();
            $stats['selesai'] = UserApplication::whereIn('status', ['approved_rw', 'approved_kelurahan', 'completed'])->count();
            $stats['proses'] = UserApplication::whereIn('status', ['pending_rt', 'approved_rt', 'pending_rw', 'approved_rw', 'pending_kelurahan'])->count();
            $stats['ditolak'] = UserApplication::whereIn('status', ['rejected_rt', 'rejected_rw', 'rejected_kelurahan'])->count();
        }

        return $stats;
    }

    /**
     * API endpoint to get real-time user statistics
     */
    public function getUserStats()
    {
        try {
            $stats = $this->getUserStatistics();

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting user stats: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Get recent activities for profile widget
     */
    public function getRecentActivities()
    {
        try {
            $userId = Auth::id();
            $userRole = Auth::user()->role;

            $activities = [];

            if ($userRole === 'user') {
                // For regular users - show their own application activities
                $recentApplications = UserApplication::with(['approverRT', 'approverRW', 'approverKelurahan'])
                                    ->byUser($userId)
                                    ->where('updated_at', '>', now()->subDays(30)) // Extend to 30 days
                                    ->orderBy('updated_at', 'desc')
                                    ->limit(10) // Get more records
                                    ->get();

                foreach ($recentApplications as $app) {
                    // Activity for application submission (NEW)
                    if ($app->created_at && $app->created_at > now()->subDays(30)) {
                        $activities[] = [
                            'time' => $app->created_at,
                            'time_human' => $app->created_at->diffForHumans(),
                            'actor' => $app->user ?? Auth::user(),
                            'action' => 'mengajukan',
                            'subject' => $app->jenis_permohonan,
                            'nomor_surat' => $app->nomor_surat,
                            'note' => null,
                            'level' => 'Permohonan Baru',
                            'avatar' => $app->user->image ?? 'img/avatar/avatar-1.png',
                            'status' => $app->status
                        ];
                    }

                    // Activity for RT approval
                    if ($app->approved_rt_at && $app->approved_rt_at > now()->subDays(30) && $app->approverRT) {
                        $activities[] = [
                            'time' => $app->approved_rt_at,
                            'time_human' => $app->approved_rt_at->diffForHumans(),
                            'actor' => $app->approverRT,
                            'action' => $app->status === 'rejected_rt' ? 'menolak' : 'menyetujui',
                            'subject' => $app->jenis_permohonan,
                            'nomor_surat' => $app->nomor_surat,
                            'note' => $app->catatan_rt,
                            'level' => 'RT ' . $app->rt,
                            'avatar' => $app->approverRT->image ?? 'img/avatar/avatar-1.png',
                            'status' => $app->status
                        ];
                    }

                    // Activity for RW approval
                    if ($app->approved_rw_at && $app->approved_rw_at > now()->subDays(30) && $app->approverRW) {
                        $activities[] = [
                            'time' => $app->approved_rw_at,
                            'time_human' => $app->approved_rw_at->diffForHumans(),
                            'actor' => $app->approverRW,
                            'action' => $app->status === 'rejected_rw' ? 'menolak' : 'menyetujui',
                            'subject' => $app->jenis_permohonan,
                            'nomor_surat' => $app->nomor_surat,
                            'note' => $app->catatan_rw,
                            'level' => 'RW ' . $app->rw,
                            'avatar' => $app->approverRW->image ?? 'img/avatar/avatar-2.png',
                            'status' => $app->status
                        ];
                    }

                    // Activity for Kelurahan approval
                    if ($app->approved_kelurahan_at && $app->approved_kelurahan_at > now()->subDays(30) && $app->approverKelurahan) {
                        $activities[] = [
                            'time' => $app->approved_kelurahan_at,
                            'time_human' => $app->approved_kelurahan_at->diffForHumans(),
                            'actor' => $app->approverKelurahan,
                            'action' => $app->status === 'rejected_kelurahan' ? 'menolak' : 'menyetujui',
                            'subject' => $app->jenis_permohonan,
                            'nomor_surat' => $app->nomor_surat,
                            'note' => $app->catatan_kelurahan,
                            'level' => 'Kelurahan',
                            'avatar' => $app->approverKelurahan->image ?? 'img/avatar/avatar-3.png',
                            'status' => $app->status
                        ];
                    }

                    // If no specific activities but application exists, show current status
                    if (empty($activities) || count($activities) === 0) {
                        $activities[] = [
                            'time' => $app->updated_at,
                            'time_human' => $app->updated_at->diffForHumans(),
                            'actor' => Auth::user(),
                            'action' => 'status',
                            'subject' => $app->jenis_permohonan,
                            'nomor_surat' => $app->nomor_surat,
                            'note' => $this->getStatusText($app->status),
                            'level' => 'Status Terkini',
                            'avatar' => Auth::user()->image ?? 'img/avatar/avatar-1.png',
                            'status' => $app->status
                        ];
                    }
                }
            } else {
                // For RT/RW/Admin roles - show activities in their area
                $query = UserApplication::with(['user', 'approverRT', 'approverRW', 'approverKelurahan']);

                if ($userRole === 'Ketua RT') {
                    $query->where('rt', Auth::user()->rt);
                } elseif ($userRole === 'Ketua RW') {
                    $query->where('rw', Auth::user()->rw);
                }

                $recentApplications = $query->where('updated_at', '>', now()->subDays(30))
                                        ->orderBy('updated_at', 'desc')
                                        ->limit(15)
                                        ->get();

                foreach ($recentApplications as $app) {
                    // Activity for new applications
                    if ($app->created_at && $app->created_at > now()->subDays(30)) {
                        $activities[] = [
                            'time' => $app->created_at,
                            'time_human' => $app->created_at->diffForHumans(),
                            'actor' => $app->user,
                            'action' => 'mengajukan',
                            'subject' => $app->jenis_permohonan,
                            'nomor_surat' => $app->nomor_surat,
                            'note' => null,
                            'level' => 'Permohonan Baru',
                            'avatar' => $app->user->image ?? 'img/avatar/avatar-1.png',
                            'pemohon' => $app->user->name,
                            'status' => $app->status
                        ];
                    }

                    // Activity for RT approval
                    if ($app->approved_rt_at && $app->approved_rt_at > now()->subDays(30) && $app->approverRT) {
                        $activities[] = [
                            'time' => $app->approved_rt_at,
                            'time_human' => $app->approved_rt_at->diffForHumans(),
                            'actor' => $app->approverRT,
                            'action' => $app->status === 'rejected_rt' ? 'menolak' : 'menyetujui',
                            'subject' => $app->jenis_permohonan,
                            'nomor_surat' => $app->nomor_surat,
                            'note' => $app->catatan_rt,
                            'level' => 'RT ' . $app->rt,
                            'avatar' => $app->approverRT->image ?? 'img/avatar/avatar-1.png',
                            'pemohon' => $app->user->name,
                            'status' => $app->status
                        ];
                    }

                    // Activity for RW approval
                    if ($app->approved_rw_at && $app->approved_rw_at > now()->subDays(30) && $app->approverRW) {
                        $activities[] = [
                            'time' => $app->approved_rw_at,
                            'time_human' => $app->approved_rw_at->diffForHumans(),
                            'actor' => $app->approverRW,
                            'action' => $app->status === 'rejected_rw' ? 'menolak' : 'menyetujui',
                            'subject' => $app->jenis_permohonan,
                            'nomor_surat' => $app->nomor_surat,
                            'note' => $app->catatan_rw,
                            'level' => 'RW ' . $app->rw,
                            'avatar' => $app->approverRW->image ?? 'img/avatar/avatar-2.png',
                            'pemohon' => $app->user->name,
                            'status' => $app->status
                        ];
                    }
                }
            }

            // Sort by time descending
            usort($activities, function($a, $b) {
                return $b['time'] <=> $a['time'];
            });

            // Take only 5 most recent for profile widget
            $activities = array_slice($activities, 0, 4);

            return response()->json([
                'success' => true,
                'data' => $activities
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting activities: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper function to get status text in Indonesian
     */
    private function getStatusText($status)
    {
        $statusTexts = [
            'pending_rt' => 'Menunggu persetujuan RT',
            'approved_rt' => 'Disetujui RT, menunggu RW',
            'rejected_rt' => 'Ditolak oleh RT',
            'pending_rw' => 'Menunggu persetujuan RW',
            'approved_rw' => 'Disetujui RW, menunggu Kelurahan',
            'rejected_rw' => 'Ditolak oleh RW',
            'pending_kelurahan' => 'Menunggu persetujuan Kelurahan',
            'approved_kelurahan' => 'Disetujui Kelurahan',
            'rejected_kelurahan' => 'Ditolak oleh Kelurahan',
            'completed' => 'Permohonan selesai'
        ];

        return $statusTexts[$status] ?? 'Status tidak dikenal';
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Base validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'nik' => 'nullable|string|size:16',
            'telp' => 'nullable|string|max:20',
            'gender' => 'nullable|in:L,P',
            'address' => 'nullable|string',
            'rt' => 'nullable|string|max:3',
            'rw' => 'nullable|string|max:3',
            'kelurahan' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'kota' => 'nullable|string|max:255',
            'provinsi' => 'nullable|string|max:255',
            'kode_pos' => 'nullable|string|max:5',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'status_perkawinan' => 'nullable|in:Belum Kawin,Kawin,Cerai Hidup,Cerai Mati',
            'pekerjaan' => 'nullable|string|max:255',
            'agama' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240'
        ];

        // Base error messages
        $messages = [
            'name.required' => 'Nama lengkap harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'nik.size' => 'NIK harus 16 digit',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'image.max' => 'Ukuran gambar maksimal 10MB'
        ];

        // Role-specific validation for RW
        if ($user->role === 'Ketua RW') {
            $rules['rw'] = [
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    // Check if RW is already taken by another Ketua RW
                    $exists = User::where('role', 'Ketua RW')
                        ->where('id', '!=', $user->id)
                        ->where('rw', $value)
                        ->exists();

                    if ($exists) {
                        $fail('RW ' . $value . ' sudah digunakan oleh Ketua RW lain.');
                    }
                }
            ];

            $messages['rw.required'] = 'RW wajib diisi untuk Ketua RW';
        }

        // Role-specific validation for RT
        if ($user->role === 'Ketua RT') {
            $rules['rw'] = 'required';
            $rules['rt'] = [
                'required',
                function ($attribute, $value, $fail) use ($request, $user) {
                    // Validate RW-RT mapping first
                    $rwRtMapping = [
                        '01' => 6, '02' => 7, '03' => 10, '04' => 8, '05' => 10,
                        '06' => 4, '07' => 4, '08' => 3, '09' => 8, '10' => 3,
                    ];

                    $selectedRW = $request->rw;
                    $maxRT = $rwRtMapping[$selectedRW] ?? 0;
                    $rtNumber = (int) ltrim($value, '0');

                    if ($rtNumber < 1 || $rtNumber > $maxRT) {
                        $fail('RT ' . $value . ' tidak tersedia di RW ' . $selectedRW . '. RT yang tersedia: 01-' . sprintf('%02d', $maxRT) . '.');
                        return;
                    }

                    // Check if RT in this RW is already taken by another Ketua RT
                    $exists = User::where('role', 'Ketua RT')
                        ->where('id', '!=', $user->id)
                        ->where('rw', $selectedRW)
                        ->where('rt', $value)
                        ->exists();

                    if ($exists) {
                        $fail('RT ' . $value . ' di RW ' . $selectedRW . ' sudah digunakan oleh Ketua RT lain.');
                    }

                    // NOTE: Removed the check for RW occupied by Ketua RW
                    // Ketua RT CAN choose the same RW as Ketua RW
                }
            ];

            $messages['rw.required'] = 'RW wajib diisi untuk Ketua RT';
            $messages['rt.required'] = 'RT wajib diisi untuk Ketua RT';
        }

        // Validate the request
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Handle image upload if present
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($user->image && file_exists(public_path($user->image))) {
                    unlink(public_path($user->image));
                }

                // Upload new image
                $image = $request->file('image');
                $imageName = time() . '_' . $user->id . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('img/profileUser'), $imageName);
                $user->image = 'img/profileUser/' . $imageName;
            }

            // Update user data
            $user->name = $request->name;
            $user->email = $request->email;
            $user->nik = $request->nik;
            $user->telp = $request->telp;
            $user->gender = $request->gender;
            $user->address = $request->address;
            $user->rt = $request->rt;
            $user->rw = $request->rw;

            // Use text values for location fields if available, otherwise use the original values
            $user->kelurahan = $request->kelurahan_text ?: $request->kelurahan;
            $user->kecamatan = $request->kecamatan_text ?: $request->kecamatan;
            $user->kota = $request->kota_text ?: $request->kota;
            $user->provinsi = $request->provinsi_text ?: $request->provinsi;

            $user->kode_pos = $request->kode_pos;
            $user->tempat_lahir = $request->tempat_lahir;
            $user->tanggal_lahir = $request->tanggal_lahir;
            $user->status_perkawinan = $request->status_perkawinan;
            $user->pekerjaan = $request->pekerjaan;
            $user->agama = $request->agama;

            // Clean description but preserve basic formatting
            $user->description = $request->description ? strip_tags($request->description, '<p><br><strong><em><ul><ol><li>') : null;

            $user->save();

            // Clear session flag if exists
            session()->forget('needs_profile_update');

            // Log the successful update for role-specific users
            if (in_array($user->role, ['Ketua RT', 'Ketua RW'])) {
                \Log::info('Profile updated for ' . $user->role, [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'rw' => $user->rw,
                    'rt' => $user->rt,
                    'updated_at' => now()
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Profile berhasil diperbarui',
                'redirect_url' => route('Profile.index'),
                'user' => [
                    'rw' => $user->rw,
                    'rt' => $user->rt,
                    'role' => $user->role
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Profile update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui profile: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available RT options
     */
    private function getAvailableRT()
    {
        $dataKependudukan = DataKependudukan::first();
        $totalRT = $dataKependudukan ? $dataKependudukan->total_rt : 10;

        // Get used RT numbers by other users (excluding current user)
        $usedRT = User::where('role', 'Ketua RT')
                     ->where('id', '!=', Auth::id())
                     ->whereNotNull('rt')
                     ->pluck('rt')
                     ->toArray();

        $availableRT = [];
        for ($i = 1; $i <= $totalRT; $i++) {
            $rtNumber = sprintf('%02d', $i);
            if (!in_array($rtNumber, $usedRT)) {
                $availableRT[] = [
                    'value' => $rtNumber,
                    'label' => "RT {$rtNumber}"
                ];
            }
        }

        // Add current user's RT if exists
        $currentUser = Auth::user();
        if ($currentUser->rt) {
            // Check if current RT is not in available list
            $currentRTExists = collect($availableRT)->where('value', $currentUser->rt)->count() > 0;
            if (!$currentRTExists) {
                array_unshift($availableRT, [
                    'value' => $currentUser->rt,
                    'label' => "RT {$currentUser->rt} (Current)"
                ]);
            }
        }

        return $availableRT;
    }

    /**
     * Get available RW options
     */
    private function getAvailableRW()
    {
        $dataKependudukan = DataKependudukan::first();
        $totalRW = $dataKependudukan ? $dataKependudukan->total_rw : 63;

        // Get used RW numbers by other users (excluding current user)
        $usedRW = User::where('role', 'Ketua RW')
                     ->where('id', '!=', Auth::id())
                     ->whereNotNull('rw')
                     ->pluck('rw')
                     ->toArray();

        $availableRW = [];
        for ($i = 1; $i <= $totalRW; $i++) {
            $rwNumber = sprintf('%02d', $i);
            if (!in_array($rwNumber, $usedRW)) {
                $availableRW[] = [
                    'value' => $rwNumber,
                    'label' => "RW {$rwNumber}"
                ];
            }
        }

        // Add current user's RW if exists
        $currentUser = Auth::user();
        if ($currentUser->rw) {
            // Check if current RW is not in available list
            $currentRWExists = collect($availableRW)->where('value', $currentUser->rw)->count() > 0;
            if (!$currentRWExists) {
                array_unshift($availableRW, [
                    'value' => $currentUser->rw,
                    'label' => "RW {$currentUser->rw} (Current)"
                ]);
            }
        }

        return $availableRW;
    }

    /**
     * Check if user profile is complete for RT/RW roles
     */
    public static function isProfileComplete($user)
    {
        if (!in_array($user->role, ['Ketua RT', 'Ketua RW'])) {
            return true; // Not applicable for other roles
        }

        $requiredFields = ['name', 'email', 'telp'];

        if ($user->role === 'Ketua RT') {
            $requiredFields[] = 'rt';
        }

        if ($user->role === 'Ketua RW') {
            $requiredFields[] = 'rw';
        }

        foreach ($requiredFields as $field) {
            if (empty($user->$field)) {
                return false;
            }
        }

        return true;
    }
}
