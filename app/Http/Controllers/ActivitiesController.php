<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\UserApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ActivitiesController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $perPage = $request->get('per_page', 20);
        $action = $request->get('action');
        $date = $request->get('date');

        // Build query based on user role
        $query = ActivityLog::with(['user', 'subject'])
                        ->orderBy('created_at', 'desc');

        // PERBAIKAN: Role-based activity visibility dengan security yang ketat
        if ($user->role === 'user') {
            // User hanya melihat:
            // 1. Activities yang mereka lakukan sendiri
            // 2. Activities yang terkait dengan aplikasi/dokumen mereka
            $query->where(function($q) use ($user) {
                $q->where('user_id', $user->id) // Activities user sendiri
                ->orWhere(function($subQ) use ($user) {
                    // Activities terkait aplikasi user (approval, reject, dll)
                    $subQ->where('subject_type', 'App\Models\UserApplication')
                        ->whereHas('subject', function($appQ) use ($user) {
                            $appQ->where('user_id', $user->id);
                        });
                })
                ->orWhere(function($subQ) use ($user) {
                    // Activities terkait Puntadewa user
                    $subQ->where('subject_type', 'App\Models\Puntadewa')
                        ->whereHas('subject', function($puntQ) use ($user) {
                            $puntQ->where('user_id', $user->id);
                        });
                });
            });

        } elseif ($user->role === 'Ketua RT') {
            // Ketua RT hanya melihat:
            // 1. Activities sendiri
            // 2. Activities terkait dokumen di RT mereka (bukan personal activities user lain)
            $query->where(function($q) use ($user) {
                $q->where('user_id', $user->id) // Activities sendiri
                ->orWhere(function($subQ) use ($user) {
                    // Activities terkait aplikasi di RT yang sama
                    $subQ->where('subject_type', 'App\Models\UserApplication')
                        ->whereHas('subject', function($appQ) use ($user) {
                            $appQ->where('rt', $user->rt);
                        });
                })
                ->orWhere(function($subQ) use ($user) {
                    // Activities terkait Puntadewa di RT yang sama
                    $subQ->where('subject_type', 'App\Models\Puntadewa')
                        ->whereHas('subject', function($puntQ) use ($user) {
                            $puntQ->where('rt', $user->rt);
                        });
                });
            });

            // TAMBAHAN: Filter out personal activities (login, logout, profile update) dari user lain
            $query->where(function($q) use ($user) {
                $q->where('user_id', $user->id) // Allow own activities
                ->orWhereNotIn('action', ['login', 'logout', 'profile_update']); // Block personal activities of others
            });

        } elseif ($user->role === 'Ketua RW') {
            // Ketua RW hanya melihat:
            // 1. Activities sendiri
            // 2. Activities terkait dokumen di RW mereka (bukan personal activities user lain)
            $query->where(function($q) use ($user) {
                $q->where('user_id', $user->id) // Activities sendiri
                ->orWhere(function($subQ) use ($user) {
                    // Activities terkait aplikasi di RW yang sama
                    $subQ->where('subject_type', 'App\Models\UserApplication')
                        ->whereHas('subject', function($appQ) use ($user) {
                            $appQ->where('rw', $user->rw);
                        });
                })
                ->orWhere(function($subQ) use ($user) {
                    // Activities terkait Puntadewa di RW yang sama
                    $subQ->where('subject_type', 'App\Models\Puntadewa')
                        ->whereHas('subject', function($puntQ) use ($user) {
                            $puntQ->where('rw', $user->rw);
                        });
                });
            });

            // TAMBAHAN: Filter out personal activities dari user lain
            $query->where(function($q) use ($user) {
                $q->where('user_id', $user->id) // Allow own activities
                ->orWhereNotIn('action', ['login', 'logout', 'profile_update']); // Block personal activities
            });

        } elseif (in_array($user->role, ['Front Office', 'Back Office', 'Operator'])) {
            // Staff Office hanya melihat:
            // 1. Activities sendiri
            // 2. Activities terkait dokumen/aplikasi (approval, create, update dokumen)
            // 3. TIDAK MELIHAT login/logout/profile update user lain
            $query->where(function($q) use ($user) {
                $q->where('user_id', $user->id) // Activities sendiri
                ->orWhere(function($subQ) {
                    // Activities terkait dokumen/aplikasi saja
                    $subQ->whereIn('subject_type', [
                        'App\Models\UserApplication',
                        'App\Models\Puntadewa'
                    ])
                    ->whereNotIn('action', ['login', 'logout', 'profile_update']);
                });
            });

        } elseif (in_array($user->role, ['Lurah', 'Camat'])) {
            // Lurah/Camat melihat:
            // 1. Activities sendiri
            // 2. Activities terkait dokumen/aplikasi yang memerlukan approval mereka
            // 3. TIDAK MELIHAT personal activities user lain
            $query->where(function($q) use ($user) {
                $q->where('user_id', $user->id) // Activities sendiri
                ->orWhere(function($subQ) {
                    // Activities terkait dokumen level kelurahan
                    $subQ->whereIn('subject_type', [
                        'App\Models\UserApplication',
                        'App\Models\Puntadewa'
                    ])
                    ->whereIn('action', ['create', 'update', 'approve', 'reject']) // Hanya aktivitas dokumen
                    ->whereNotIn('action', ['login', 'logout', 'profile_update']);
                });
            });

        } elseif ($user->role === 'admin') {
            // Admin melihat:
            // 1. Activities sendiri
            // 2. Activities terkait sistem/dokumen (BUKAN personal activities user lain)
            // 3. Activities yang memerlukan monitoring sistem
            $query->where(function($q) use ($user) {
                $q->where('user_id', $user->id) // Activities admin sendiri
                ->orWhere(function($subQ) {
                    // Activities terkait dokumen dan sistem
                    $subQ->whereIn('action', [
                        'create', 'update', 'approve', 'reject', 'delete',
                        'download', 'upload', 'view'
                    ])
                    ->orWhereIn('subject_type', [
                        'App\Models\UserApplication',
                        'App\Models\Puntadewa',
                        'App\Models\DataKependudukan'
                    ]);
                });
            });

            // TAMBAHAN: Admin TIDAK melihat login/logout/profile_update user lain
            $query->where(function($q) use ($user) {
                $q->where('user_id', $user->id) // Allow admin's own activities
                ->orWhereNotIn('action', ['login', 'logout', 'profile_update']); // Block personal activities of others
            });

        } else {
            // Role lain yang tidak didefinisikan - hanya melihat activities sendiri
            $query->where('user_id', $user->id);
        }

        // Apply filters
        if ($action) {
            $query->where('action', $action);
        }

        if ($date) {
            $query->whereDate('created_at', $date);
        }

        $activities = $query->paginate($perPage);

        // Group activities by date
        $groupedActivities = $activities->groupBy(function($activity) {
            return $activity->created_at->format('Y-m-d');
        });

        // Get available actions for filter - only actions visible to current user
        $availableActions = ActivityLog::select('action')
                                    ->distinct()
                                    // Apply same filter as main query for actions
                                    ->when($user->role !== 'admin', function($q) use ($user) {
                                        if (in_array($user->role, ['Front Office', 'Back Office', 'Operator', 'Lurah', 'Camat'])) {
                                            $q->where(function($subQ) use ($user) {
                                                $subQ->where('user_id', $user->id)
                                                    ->orWhereNotIn('action', ['login', 'logout', 'profile_update']);
                                            });
                                        }
                                    })
                                    ->pluck('action')
                                    ->mapWithKeys(function($action) {
                                        return [$action => $this->getActionDisplayName($action)];
                                    });

        return view('activities.index', [
            'type_menu' => 'activities',
            'activities' => $activities,
            'groupedActivities' => $groupedActivities,
            'availableActions' => $availableActions,
            'currentAction' => $action,
            'currentDate' => $date,
        ]);
    }

    /**
     * Get display name for action
     */
    private function getActionDisplayName($action)
    {
        $displayNames = [
            'login' => 'Login',
            'logout' => 'Logout',
            'create' => 'Membuat Data',
            'update' => 'Memperbarui Data',
            'delete' => 'Menghapus Data',
            'approve' => 'Menyetujui',
            'reject' => 'Menolak',
            'view' => 'Melihat Halaman',
            'download' => 'Download',
            'upload' => 'Upload',
            'profile_update' => 'Update Profile',
            'password_change' => 'Ganti Password',
            'submission' => 'Pengajuan',
            'comment' => 'Komentar',
        ];

        return $displayNames[$action] ?? ucfirst($action);
    }

    /**
     * Get activity details (Ajax) - PERBAIKAN UNTUK ANALISIS
     */
    public function show($id)
    {
        try {
            Log::info('Fetching activity details for ID: ' . $id);

            $activity = ActivityLog::with(['user', 'subject'])->find($id);

            if (!$activity) {
                Log::warning('Activity not found with ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Activity not found'
                ], 404);
            }

            // Check permissions
            $user = Auth::user();
            if ($user->role === 'user') {
                // User hanya bisa lihat activities yang terkait dengannya
                $canView = false;

                if ($activity->user_id === $user->id) {
                    $canView = true;
                } elseif ($activity->subject_type === 'App\Models\UserApplication' &&
                         $activity->subject && $activity->subject->user_id === $user->id) {
                    $canView = true;
                } elseif ($activity->subject_type === 'App\Models\Puntadewa' &&
                         $activity->subject && $activity->subject->user_id === $user->id) {
                    $canView = true;
                }

                if (!$canView) {
                    Log::warning('Unauthorized access attempt by user ID: ' . $user->id . ' for activity ID: ' . $id);
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized access'
                    ], 403);
                }
            }

            // Pastikan user relationship ada
            if (!$activity->user) {
                Log::error('Activity user relationship missing for activity ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Activity user data not found'
                ], 500);
            }

            // PERBAIKAN: Generate analisis yang user-friendly
            $analysis = $this->generateActivityAnalysis($activity);

            $responseData = [
                'id' => $activity->id,
                'action' => $activity->action,
                'description' => $activity->description,
                'user' => [
                    'name' => $activity->user->name ?? 'Unknown User',
                    'email' => $activity->user->email ?? 'No Email',
                    'role' => $activity->user->role ?? 'No Role',
                    'avatar' => $activity->user->image ?? 'img/avatar/avatar-1.png',
                ],
                'subject_type' => $activity->subject_type,
                'subject_id' => $activity->subject_id,
                'subject_link' => $activity->getSubjectLink(),
                'properties' => $activity->properties ?? [],
                'analysis' => $analysis, // TAMBAHAN: Analisis user-friendly
                'ip_address' => $activity->ip_address,
                'user_agent' => $activity->user_agent,
                'created_at' => $activity->created_at->format('d/m/Y H:i:s'),
                'time_ago' => $activity->time_ago,
                'icon' => $activity->icon,
                'color' => $activity->color,
            ];

            Log::info('Activity details fetched successfully for ID: ' . $id);

            return response()->json([
                'success' => true,
                'data' => $responseData
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching activity details: ' . $e->getMessage(), [
                'activity_id' => $id,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Generate user-friendly activity analysis - BARU
     */
    private function generateActivityAnalysis($activity)
    {
        $analysis = [
            'summary' => '',
            'details' => [],
            'impact' => '',
            'next_steps' => []
        ];

        $action = $activity->action;
        $subject = $activity->subject;
        $properties = $activity->properties ?? [];

        switch ($action) {
            // TAMBAHAN: Case untuk tanda terima generated
            case 'tanda_terima_generated':
                $analysis['summary'] = 'Surat Tanda Terima telah dibuat untuk Anda';
                $analysis['details'] = [
                    'Nomor Agenda' => $properties['nomor_agenda'] ?? 'N/A',
                    'Dibuat oleh' => $properties['generated_by'] ?? 'Front Office',
                    'File Tersedia' => $properties['can_view_file'] ? 'Ya, dapat diunduh' : 'Tidak',
                    'Waktu Dibuat' => $activity->created_at->format('d/m/Y H:i:s'),
                    'Status Dokumen' => 'Diterima di Kelurahan'
                ];
                $analysis['impact'] = 'Dokumen Anda telah diterima oleh Kelurahan dan proses dimulai';
                $analysis['next_steps'] = [
                    'Download dan simpan tanda terima sebagai bukti',
                    'Tunggu proses disposisi dari Lurah',
                    'Pantau status dokumen secara berkala',
                    'File tanda terima dapat dilihat di halaman detail dokumen'
                ];
                break;

            // TAMBAHAN: Case untuk receive kelurahan
            case 'receive_kelurahan':
                $analysis['summary'] = 'Dokumen diterima di Front Office Kelurahan';
                $analysis['details'] = [
                    'Nomor Agenda' => $properties['nomor_agenda'] ?? 'N/A',
                    'Diterima oleh' => $properties['received_by'] ?? 'Front Office',
                    'Tanda Terima' => $properties['tanda_terima_generated'] ? 'Sudah dibuat' : 'Belum dibuat',
                    'Disposisi' => $properties['disposisi_generated'] ? 'Sudah dibuat' : 'Belum dibuat',
                    'File Tanda Terima' => $properties['tanda_terima_file'] ? 'Tersedia untuk diunduh' : 'Tidak tersedia',
                    'File Disposisi' => $properties['disposisi_file'] ? 'Tersedia untuk Lurah' : 'Tidak tersedia',
                    'Catatan' => $properties['catatan_front_office'] ?? 'Tidak ada catatan khusus'
                ];
                $analysis['impact'] = 'Dokumen masuk ke sistem Kelurahan dan mendapat nomor agenda resmi';
                $analysis['next_steps'] = [
                    'Tanda terima otomatis dibuat untuk pemohon',
                    'Disposisi kosong dibuat untuk Lurah',
                    'Menunggu Lurah mengisi dan menandatangani disposisi',
                    'Dokumen akan diproses sesuai prosedur standar'
                ];
                break;

            // TAMBAHAN: Case untuk process lurah
            case 'process_lurah':
                $analysis['summary'] = 'Lurah telah memproses disposisi dokumen';
                $analysis['details'] = [
                    'Diproses oleh' => $properties['processed_by'] ?? 'Lurah',
                    'Diteruskan ke' => $properties['diteruskan_kepada'] ?? 'Back Office',
                    'TTD Disposisi' => $properties['ttd_saved'] ? 'Sudah ditandatangani' : 'Belum ditandatangani',
                    'File Disposisi Signed' => $properties['disposisi_signed_file'] ? 'Tersedia' : 'Tidak tersedia',
                    'Catatan Lurah' => $properties['catatan_lurah'] ?? 'Tidak ada catatan khusus',
                    'Waktu Proses' => $activity->created_at->format('d/m/Y H:i:s')
                ];
                $analysis['impact'] = 'Disposisi telah ditandatangani dan diteruskan untuk pemrosesan final';
                $analysis['next_steps'] = [
                    'Disposisi yang sudah ditandatangani dapat dilihat',
                    'Dokumen diteruskan ke Back Office untuk finalisasi',
                    'Menunggu pemrosesan akhir dari Back Office',
                    'Dokumen akan segera diselesaikan dan siap diunduh'
                ];
                break;

            // TAMBAHAN: Case untuk complete back office
            case 'complete_psu':
                $analysis['summary'] = 'Dokumen PSU telah diselesaikan oleh Back Office';
                $analysis['details'] = [
                    'Diselesaikan oleh' => $properties['completed_by'] ?? 'Back Office',
                    'PDF Final' => $properties['final_pdf_generated'] ? 'Sudah dibuat' : 'Belum dibuat',
                    'Workflow' => $properties['workflow_completed'] ? 'Selesai' : 'Belum selesai',
                    'Catatan Back Office' => $properties['catatan_back_office'] ?? 'Tidak ada catatan khusus',
                    'Waktu Selesai' => $activity->created_at->format('d/m/Y H:i:s')
                ];
                $analysis['impact'] = 'Seluruh proses PSU telah selesai dan dokumen siap digunakan';
                $analysis['next_steps'] = [
                    'Download dokumen PSU final yang sudah lengkap',
                    'Dokumen telah memiliki semua tanda tangan dan stempel',
                    'Simpan dokumen sebagai arsip pribadi',
                    'Dokumen dapat digunakan untuk keperluan resmi'
                ];
                break;

            case 'login':
                $analysis['summary'] = 'User berhasil masuk ke sistem';
                $analysis['details'] = [
                    'Waktu Login' => $activity->created_at->format('d/m/Y H:i:s'),
                    'IP Address' => $activity->ip_address ?? 'N/A',
                    'Browser' => $this->parseBrowser($activity->user_agent ?? ''),
                    'Platform' => $this->parsePlatform($activity->user_agent ?? ''),
                    'Status' => 'Berhasil masuk ke sistem'
                ];
                $analysis['impact'] = 'User dapat mengakses sistem dan melakukan aktivitas';
                $analysis['next_steps'] = [
                    'Lanjutkan menggunakan fitur sistem',
                    'Periksa status dokumen yang sudah diajukan',
                    'Logout ketika selesai menggunakan sistem'
                ];
                break;

            case 'logout':
                $analysis['summary'] = 'User keluar dari sistem';
                $analysis['details'] = [
                    'Waktu Logout' => $activity->created_at->format('d/m/Y H:i:s'),
                    'Durasi Session' => 'Otomatis dihitung sistem',
                    'Status' => 'Session berakhir dengan aman'
                ];
                $analysis['impact'] = 'Session berakhir, akses ke sistem ditutup untuk keamanan';
                $analysis['next_steps'] = [
                    'Login kembali jika ingin menggunakan sistem',
                    'Data dan progress tersimpan dengan aman'
                ];
                break;

            case 'profile_update':
                $analysis['summary'] = 'Profile pengguna telah diperbarui';

                // Analisis perubahan dari properties dengan security filtering
                if (isset($properties['old']) && isset($properties['new'])) {
                    $changes = [];
                    foreach ($properties['new'] as $field => $newValue) {
                        $oldValue = $properties['old'][$field] ?? 'kosong';

                        // PERBAIKAN: Filter data sensitif
                        if ($this->isSensitiveField($field)) {
                            $changes[$this->getSecureFieldName($field)] = $this->getSecureFieldDescription($field, $oldValue, $newValue);
                            continue;
                        }

                        $fieldNames = [
                            'name' => 'Nama',
                            'email' => 'Email',
                            'telp' => 'No. Telepon',
                            'address' => 'Alamat',
                            'rt' => 'RT',
                            'rw' => 'RW',
                            'pekerjaan' => 'Pekerjaan',
                            'gender' => 'Jenis Kelamin',
                            'tanggal_lahir' => 'Tanggal Lahir'
                        ];

                        $fieldName = $fieldNames[$field] ?? ucfirst($field);
                        $changes[$fieldName] = ($oldValue ?: '-') . ' → ' . ($newValue ?: '-');
                    }
                    $analysis['details'] = $changes;
                }

                $analysis['impact'] = 'Data profile telah diperbarui dalam sistem';
                $analysis['next_steps'] = [
                    'Verifikasi perubahan data di halaman profile',
                    'Informasi terbaru akan digunakan untuk dokumen selanjutnya'
                ];
                break;

            case 'create':
            case 'submission':
                if ($subject && $subject instanceof \App\Models\Psu) {
                    $analysis['summary'] = 'Pemohon mengajukan permohonan PSU baru';
                    $analysis['details'] = [
                        'Jenis Permohonan' => 'Permohonan Surat Umum (PSU)',
                        'Nomor Surat' => $subject->nomor_surat ?? 'N/A',
                        'Nama Pemohon' => $subject->nama_lengkap ?? 'N/A',
                        'RT/RW' => ($subject->rt ?? '') . '/' . ($subject->rw ?? ''),
                        'Ditujukan Kepada' => $subject->ditujukan_kepada_display ?? 'N/A',
                        'Perihal' => $subject->hal ?? 'N/A',
                        'Status Awal' => $subject->isPSUInternal() ? 'Auto Approved' : 'Menunggu persetujuan RT',
                        'Waktu Pengajuan' => $activity->created_at->format('d/m/Y H:i:s')
                    ];

                    if ($subject->isPSUInternal()) {
                        $analysis['impact'] = 'PSU Internal langsung disetujui dan siap diunduh';
                        $analysis['next_steps'] = [
                            'Download dokumen PSU yang sudah jadi',
                            'Dokumen dapat langsung digunakan',
                            'Tidak perlu menunggu proses persetujuan'
                        ];
                    } else {
                        $analysis['impact'] = 'Dokumen baru masuk ke antrian persetujuan RT';
                        $analysis['next_steps'] = [
                            'Menunggu review dari Ketua RT',
                            'RT akan melakukan verifikasi berkas dalam 1-3 hari kerja',
                            'Pantau status di halaman permohonan'
                        ];
                    }
                } elseif ($subject && $subject instanceof \App\Models\Puntadewa) {
                    $analysis['summary'] = 'Pemohon mengajukan permohonan PUNTADEWA baru';
                    $analysis['details'] = [
                        'Jenis Permohonan' => 'Pernyataan Tempat Tinggal Non Permanen',
                        'Nomor Surat' => $subject->nomor_surat ?? 'N/A',
                        'Nama Pemohon' => $subject->nama_pemohon ?? 'N/A',
                        'RT/RW' => ($subject->rt ?? '') . '/' . ($subject->rw ?? ''),
                        'Status Awal' => 'Menunggu persetujuan RT',
                        'Waktu Pengajuan' => $activity->created_at->format('d/m/Y H:i:s')
                    ];
                    $analysis['impact'] = 'Dokumen baru masuk ke antrian persetujuan RT';
                    $analysis['next_steps'] = [
                        'Menunggu review dari Ketua RT',
                        'RT akan melakukan verifikasi berkas dalam 1-3 hari kerja'
                    ];
                } else {
                    $analysis['summary'] = 'Data baru telah dibuat dalam sistem';
                    $analysis['details'] = [
                        'Jenis Data' => class_basename($subject) ?? 'Unknown',
                        'Waktu Dibuat' => $activity->created_at->format('d/m/Y H:i:s'),
                        'Dibuat oleh' => $activity->user->name ?? 'Unknown'
                    ];
                    $analysis['impact'] = 'Penambahan data ke dalam database';
                    $analysis['next_steps'] = ['Data dapat digunakan untuk proses selanjutnya'];
                }
                break;

            case 'update':
                // Cek apakah ini update profile atau update dokumen
                if ($subject && $subject instanceof \App\Models\User) {
                    // Ini sebenarnya login, bukan update profile
                    if (isset($properties['old']['updated_at']) &&
                        !isset($properties['old']['name']) &&
                        !isset($properties['old']['email'])) {

                        $analysis['summary'] = 'User masuk ke sistem (login)';
                        $analysis['details'] = [
                            'Waktu Login' => $activity->created_at->format('d/m/Y H:i:s'),
                            'IP Address' => $activity->ip_address ?? 'N/A',
                            'Browser' => $this->parseBrowser($activity->user_agent ?? ''),
                            'Status' => 'Berhasil login'
                        ];
                        $analysis['impact'] = 'User dapat mengakses sistem';
                        $analysis['next_steps'] = ['Mulai menggunakan fitur sistem'];
                        break;
                    }

                    // Ini benar-benar update profile
                    $analysis['summary'] = 'Data profile pengguna diperbarui';
                    if (isset($properties['old']) && isset($properties['new'])) {
                        $changes = [];
                        foreach ($properties['new'] as $field => $newValue) {
                            if ($field === 'updated_at') continue;

                            $oldValue = $properties['old'][$field] ?? 'kosong';

                            // PERBAIKAN: Filter data sensitif
                            if ($this->isSensitiveField($field)) {
                                $changes[$this->getSecureFieldName($field)] = $this->getSecureFieldDescription($field, $oldValue, $newValue);
                                continue;
                            }

                            $changes[ucfirst($field)] = ($oldValue ?: '-') . ' → ' . ($newValue ?: '-');
                        }
                        $analysis['details'] = $changes;
                    }

                } elseif ($subject && $subject instanceof \App\Models\Psu) {
                    $analysis['summary'] = 'Data PSU telah diperbarui';

                    // Analisis perubahan dari properties
                    if (isset($properties['old']) && isset($properties['new'])) {
                        $changes = [];
                        foreach ($properties['new'] as $field => $newValue) {
                            $oldValue = $properties['old'][$field] ?? 'kosong';

                            if ($field === 'status') {
                                $changes['Status'] = $this->getStatusText($oldValue) . ' → ' . $this->getStatusText($newValue);
                            } elseif ($field === 'approved_rt_at' && $newValue) {
                                $changes['Waktu Persetujuan RT'] = 'Disetujui pada ' . date('d/m/Y H:i', strtotime($newValue));
                            } elseif ($field === 'approved_rw_at' && $newValue) {
                                $changes['Waktu Persetujuan RW'] = 'Disetujui pada ' . date('d/m/Y H:i', strtotime($newValue));
                            } elseif ($field === 'approved_kelurahan_at' && $newValue) {
                                $changes['Waktu Persetujuan Kelurahan'] = 'Disetujui pada ' . date('d/m/Y H:i', strtotime($newValue));
                            } elseif ($field === 'received_kelurahan_at' && $newValue) {
                                $changes['Waktu Diterima Kelurahan'] = 'Diterima pada ' . date('d/m/Y H:i', strtotime($newValue));
                            } elseif ($field === 'processed_lurah_at' && $newValue) {
                                $changes['Waktu Proses Lurah'] = 'Diproses pada ' . date('d/m/Y H:i', strtotime($newValue));
                            }
                        }
                        $analysis['details'] = $changes;
                    }

                    $analysis['impact'] = 'Status dokumen telah berubah';
                    $analysis['next_steps'] = $this->getNextStepsForStatus($subject->status ?? '');
                }
                break;

            case 'approve':
                $analysis['summary'] = 'Permohonan telah disetujui';
                $analysis['details'] = [
                    'Disetujui oleh' => $activity->user->name . ' (' . $activity->user->role . ')',
                    'Waktu Persetujuan' => $activity->created_at->format('d/m/Y H:i:s'),
                    'Status Dokumen' => 'Disetujui dan dilanjutkan'
                ];

                if (isset($properties['catatan']) && $properties['catatan']) {
                    $analysis['details']['Catatan'] = $properties['catatan'];
                }
                if (isset($properties['level']) && $properties['level']) {
                    $analysis['details']['Level Persetujuan'] = strtoupper($properties['level']);
                }

                $analysis['impact'] = 'Dokumen telah disetujui dan melanjutkan ke tahap selanjutnya dalam workflow';
                $analysis['next_steps'] = [
                    'Dokumen otomatis diteruskan ke tahap berikutnya',
                    'Pantau progress di halaman detail dokumen',
                    'Tunggu notifikasi untuk tahap selanjutnya'
                ];
                break;

            case 'reject':
                $analysis['summary'] = 'Permohonan telah ditolak';
                $analysis['details'] = [
                    'Ditolak oleh' => $activity->user->name . ' (' . $activity->user->role . ')',
                    'Waktu Penolakan' => $activity->created_at->format('d/m/Y H:i:s'),
                    'Status Dokumen' => 'Ditolak dan proses dihentikan'
                ];

                if (isset($properties['catatan']) && $properties['catatan']) {
                    $analysis['details']['Alasan Penolakan'] = $properties['catatan'];
                }
                if (isset($properties['level']) && $properties['level']) {
                    $analysis['details']['Ditolak oleh'] = strtoupper($properties['level']);
                }

                $analysis['impact'] = 'Proses permohonan dihentikan karena tidak memenuhi persyaratan';
                $analysis['next_steps'] = [
                    'Perbaiki dokumen sesuai dengan catatan penolakan',
                    'Ajukan ulang permohonan dengan perbaikan yang diperlukan',
                    'Hubungi petugas terkait untuk konsultasi lebih lanjut'
                ];
                break;

            case 'auto_approve':
                $analysis['summary'] = 'Dokumen disetujui otomatis oleh sistem';
                $analysis['details'] = [
                    'Jenis Persetujuan' => 'Auto Approval',
                    'Waktu Persetujuan' => $activity->created_at->format('d/m/Y H:i:s'),
                    'Alasan' => 'PSU Internal tidak memerlukan persetujuan manual',
                    'Status' => 'Langsung selesai'
                ];
                $analysis['impact'] = 'Dokumen langsung selesai dan siap digunakan';
                $analysis['next_steps'] = [
                    'Download dokumen yang sudah jadi',
                    'Dokumen dapat langsung digunakan untuk keperluan resmi',
                    'Simpan sebagai arsip pribadi'
                ];
                break;

            case 'view':
                $analysis['summary'] = 'Halaman atau dokumen telah dilihat';
                $analysis['details'] = [
                    'Halaman/Dokumen' => $activity->description,
                    'Waktu Akses' => $activity->created_at->format('d/m/Y H:i:s'),
                    'Jenis Aktivitas' => 'Viewing/Browsing'
                ];
                $analysis['impact'] = 'Aktivitas browsing normal, tidak ada perubahan pada data';
                $analysis['next_steps'] = [
                    'Lanjutkan navigasi sesuai kebutuhan'
                ];
                break;

            case 'download':
                $analysis['summary'] = 'File atau dokumen telah diunduh';
                $analysis['details'] = [
                    'File/Dokumen' => $activity->description,
                    'Waktu Download' => $activity->created_at->format('d/m/Y H:i:s'),
                    'Status' => 'Berhasil diunduh'
                ];

                if (isset($properties['file_type'])) {
                    $analysis['details']['Jenis File'] = strtoupper($properties['file_type']);
                }
                if (isset($properties['file_size'])) {
                    $analysis['details']['Ukuran File'] = $properties['file_size'];
                }

                $analysis['impact'] = 'Dokumen telah diakses dan disimpan oleh user';
                $analysis['next_steps'] = [
                    'File tersimpan di perangkat Anda',
                    'Gunakan file sesuai kebutuhan',
                    'Simpan sebagai backup jika diperlukan'
                ];
                break;

            case 'upload':
                $analysis['summary'] = 'File telah diupload ke sistem';
                $analysis['details'] = [
                    'File' => $activity->description,
                    'Waktu Upload' => $activity->created_at->format('d/m/Y H:i:s'),
                    'Status' => 'Berhasil diupload'
                ];

                if (isset($properties['file_type'])) {
                    $analysis['details']['Jenis File'] = strtoupper($properties['file_type']);
                }
                if (isset($properties['file_size'])) {
                    $analysis['details']['Ukuran File'] = $properties['file_size'];
                }

                $analysis['impact'] = 'File berhasil disimpan dalam sistem';
                $analysis['next_steps'] = [
                    'File dapat digunakan dalam proses selanjutnya',
                    'Verifikasi file telah terupload dengan benar'
                ];
                break;

            case 'delete':
                $analysis['summary'] = 'Data telah dihapus dari sistem';
                $analysis['details'] = [
                    'Data yang dihapus' => $activity->description,
                    'Dihapus oleh' => $activity->user->name . ' (' . $activity->user->role . ')',
                    'Waktu Penghapusan' => $activity->created_at->format('d/m/Y H:i:s'),
                    'Status' => 'Berhasil dihapus'
                ];
                $analysis['impact'] = 'Data telah dihapus permanen dari sistem';
                $analysis['next_steps'] = [
                    'Data tidak dapat dikembalikan',
                    'Buat data baru jika diperlukan'
                ];
                break;

            case 'comment':
                $analysis['summary'] = 'Komentar atau catatan telah ditambahkan';
                $analysis['details'] = [
                    'Ditambahkan oleh' => $activity->user->name . ' (' . $activity->user->role . ')',
                    'Waktu' => $activity->created_at->format('d/m/Y H:i:s'),
                    'Dokumen' => $activity->description
                ];

                if (isset($properties['comment'])) {
                    $analysis['details']['Isi Komentar'] = $properties['comment'];
                }

                $analysis['impact'] = 'Catatan tambahan tersedia untuk referensi';
                $analysis['next_steps'] = [
                    'Baca komentar untuk informasi tambahan',
                    'Tanggapi jika diperlukan'
                ];
                break;

            case 'password_change':
                $analysis['summary'] = 'Password telah diubah';
                $analysis['details'] = [
                    'Waktu Perubahan' => $activity->created_at->format('d/m/Y H:i:s'),
                    'Status' => 'Password berhasil diperbarui',
                    'Keamanan' => 'Password lama tidak dapat digunakan lagi'
                ];
                $analysis['impact'] = 'Keamanan akun telah ditingkatkan dengan password baru';
                $analysis['next_steps'] = [
                    'Gunakan password baru untuk login selanjutnya',
                    'Simpan password dengan aman',
                    'Jangan bagikan password kepada orang lain'
                ];
                break;

            default:
                $analysis['summary'] = 'Aktivitas ' . ucfirst($action) . ' telah dilakukan';
                $analysis['details'] = [
                    'Jenis Aktivitas' => ucfirst(str_replace('_', ' ', $action)),
                    'Waktu' => $activity->created_at->format('d/m/Y H:i:s'),
                    'Dilakukan oleh' => $activity->user->name ?? 'Sistem'
                ];

                if ($activity->description) {
                    $analysis['details']['Deskripsi'] = $activity->description;
                }

                $analysis['impact'] = 'Aktivitas tercatat dalam sistem';
                $analysis['next_steps'] = [
                    'Periksa detail aktivitas jika diperlukan'
                ];
        }

        return $analysis;
    }

    /**
     * Check if field contains sensitive data - BARU
     */
    private function isSensitiveField($field)
    {
        $sensitiveFields = [
            'password',
            'remember_token',
            'email_verified_at',
            'two_factor_secret',
            'two_factor_recovery_codes',
            'api_token',
            'access_token',
            'refresh_token',
            'secret',
            'key',
            'hash'
        ];

        return in_array($field, $sensitiveFields) ||
            str_contains($field, 'password') ||
            str_contains($field, 'token') ||
            str_contains($field, 'secret') ||
            str_contains($field, 'key');
    }

    /**
     * Get secure field name for display - BARU
     */
    private function getSecureFieldName($field)
    {
        $secureNames = [
            'remember_token' => 'Remember Token',
            'password' => 'Password',
            'email_verified_at' => 'Email Verification',
            'two_factor_secret' => 'Two Factor Authentication',
            'api_token' => 'API Token',
            'access_token' => 'Access Token'
        ];

        return $secureNames[$field] ?? 'Security Token';
    }

    /**
     * Get secure field description without exposing sensitive data - BARU
     */
    private function getSecureFieldDescription($field, $oldValue, $newValue)
    {
        switch ($field) {
            case 'remember_token':
                if (empty($oldValue) && !empty($newValue)) {
                    return 'Tidak ada → Token baru dibuat (untuk "Remember Me")';
                } elseif (!empty($oldValue) && !empty($newValue)) {
                    return 'Token lama → Token baru (diperbarui untuk keamanan)';
                } elseif (!empty($oldValue) && empty($newValue)) {
                    return 'Token ada → Token dihapus (logout dari semua device)';
                }
                return 'Token diperbarui untuk keamanan';

            case 'password':
                return 'Password lama → Password baru (diubah untuk keamanan)';

            case 'email_verified_at':
                if (empty($oldValue) && !empty($newValue)) {
                    return 'Belum terverifikasi → Email terverifikasi';
                }
                return 'Status verifikasi email diperbarui';

            case 'two_factor_secret':
                if (empty($oldValue) && !empty($newValue)) {
                    return 'Tidak ada → 2FA diaktifkan';
                } elseif (!empty($oldValue) && empty($newValue)) {
                    return '2FA aktif → 2FA dinonaktifkan';
                }
                return '2FA diperbarui';

            default:
                if (empty($oldValue) && !empty($newValue)) {
                    return 'Tidak ada → Token keamanan baru dibuat';
                } elseif (!empty($oldValue) && !empty($newValue)) {
                    return 'Token lama → Token baru (diperbarui)';
                } elseif (!empty($oldValue) && empty($newValue)) {
                    return 'Token ada → Token dihapus';
                }
                return 'Token keamanan diperbarui';
        }
    }

    /**
     * Parse browser from user agent - HELPER BARU
     */
    private function parseBrowser($userAgent)
    {
        if (strpos($userAgent, 'Chrome') !== false) {
            return 'Google Chrome';
        } elseif (strpos($userAgent, 'Firefox') !== false) {
            return 'Mozilla Firefox';
        } elseif (strpos($userAgent, 'Safari') !== false) {
            return 'Safari';
        } elseif (strpos($userAgent, 'Edge') !== false) {
            return 'Microsoft Edge';
        } else {
            return 'Browser Lain';
        }
    }

    /**
     * Parse platform from user agent - HELPER BARU
     */
    private function parsePlatform($userAgent)
    {
        if (strpos($userAgent, 'Windows') !== false) {
            return 'Windows';
        } elseif (strpos($userAgent, 'Mac') !== false) {
            return 'macOS';
        } elseif (strpos($userAgent, 'Linux') !== false) {
            return 'Linux';
        } elseif (strpos($userAgent, 'Android') !== false) {
            return 'Android';
        } elseif (strpos($userAgent, 'iPhone') !== false) {
            return 'iOS';
        } else {
            return 'Platform Lain';
        }
    }

    /**
     * Get status text in Indonesian
     */
    private function getStatusText($status)
    {
        $statusTexts = [
            'pending_rt' => 'Menunggu RT',
            'approved_rt' => 'Disetujui RT',
            'rejected_rt' => 'Ditolak RT',
            'pending_rw' => 'Menunggu RW',
            'approved_rw' => 'Disetujui RW',
            'rejected_rw' => 'Ditolak RW',
            'pending_kelurahan' => 'Menunggu Kelurahan',
            'approved_kelurahan' => 'Disetujui Kelurahan',
            'rejected_kelurahan' => 'Ditolak Kelurahan',
            'processing_lurah' => 'Diproses Lurah',
            'processed_lurah' => 'Selesai Diproses Lurah',
            'processing_back_office' => 'Diproses Back Office',
            'completed' => 'Selesai',
            'auto_approved' => 'Auto Approved'
        ];

        return $statusTexts[$status] ?? $status;
    }

    /**
     * Get next steps based on status
     */
    private function getNextStepsForStatus($status)
    {
        $nextSteps = [
            'pending_rt' => [
                'Menunggu review dari Ketua RT',
                'RT akan verifikasi berkas dalam 1-3 hari kerja',
                'Pantau status di halaman dokumen'
            ],
            'approved_rt' => [
                'Dokumen diteruskan ke RW',
                'Menunggu persetujuan dari Ketua RW',
                'Proses berlanjut otomatis'
            ],
            'approved_rw' => [
                'Dokumen diteruskan ke Kelurahan',
                'Menunggu persetujuan dari pejabat Kelurahan',
                'Tahap akhir persetujuan'
            ],
            'rejected_rt' => [
                'Perbaiki dokumen sesuai catatan RT',
                'Ajukan ulang setelah perbaikan',
                'Hubungi Ketua RT untuk konsultasi'
            ],
            'rejected_rw' => [
                'Perbaiki dokumen sesuai catatan RW',
                'Ajukan ulang setelah perbaikan',
                'Hubungi Ketua RW untuk konsultasi'
            ],
            'rejected_kelurahan' => [
                'Perbaiki dokumen sesuai catatan Kelurahan',
                'Ajukan ulang setelah perbaikan',
                'Hubungi Front Office untuk bantuan'
            ],
            'pending_kelurahan' => [
                'Menunggu persetujuan Kelurahan',
                'Download tanda terima jika tersedia',
                'Pantau progress secara berkala'
            ],
            'processing_lurah' => [
                'Lurah sedang memproses disposisi',
                'Tunggu instruksi selanjutnya',
                'Disposisi akan ditandatangani segera'
            ],
            'processed_lurah' => [
                'Disposisi Lurah telah selesai',
                'Menunggu finalisasi dari Back Office',
                'Dokumen akan segera selesai'
            ],
            'processing_back_office' => [
                'Back Office sedang finalisasi dokumen',
                'Dokumen akan segera selesai',
                'Siap untuk diunduh'
            ],
            'completed' => [
                'Dokumen telah selesai diproses',
                'Download dokumen final',
                'Simpan sebagai arsip pribadi'
            ]
        ];

        return $nextSteps[$status] ?? ['Tidak ada aksi selanjutnya yang diperlukan'];
    }

    /**
     * Delete activity (Admin only)
     */
    public function destroy($id)
    {
        try {
            // Only admin can delete activities
            if (!in_array(Auth::user()->role, ['admin', 'Super Admin'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $activity = ActivityLog::find($id);

            if (!$activity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Activity not found'
                ], 404);
            }

            $activity->delete();

            Log::info('Activity deleted by user ID: ' . Auth::id() . ', Activity ID: ' . $id);

            return response()->json([
                'success' => true,
                'message' => 'Activity deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting activity: ' . $e->getMessage(), [
                'activity_id' => $id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete activity'
            ], 500);
        }
    }

    /**
     * Get activity statistics (simplified version)
     */
    public function stats()
    {
        $user = Auth::user();

        $query = ActivityLog::query();

        // Filter by user role
        if ($user->role === 'user') {
            $query->where('user_id', $user->id);
        } elseif (in_array($user->role, ['Ketua RT', 'Ketua RW'])) {
            $query->where(function($q) use ($user) {
                $q->where('user_id', $user->id);

                if ($user->role === 'Ketua RT' && $user->rt) {
                    $q->orWhereHas('user', function($userQuery) use ($user) {
                        $userQuery->where('rt', $user->rt);
                    });
                }

                if ($user->role === 'Ketua RW' && $user->rw) {
                    $q->orWhereHas('user', function($userQuery) use ($user) {
                        $userQuery->where('rw', $user->rw);
                    });
                }
            });
        }

        $stats = [
            'total_today' => $query->clone()->whereDate('created_at', today())->count(),
            'total_week' => $query->clone()->where('created_at', '>=', now()->subWeek())->count(),
            'total_month' => $query->clone()->where('created_at', '>=', now()->subMonth())->count(),
            'total_all' => $query->clone()->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
