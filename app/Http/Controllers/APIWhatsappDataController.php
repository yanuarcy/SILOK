<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\APIWhatsappData;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;



class APIWhatsappDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.masterdata.apiWhatsapp.data-apiWhatsapp', [
            'type_menu' => 'master-data',
            'pageTitle' => 'Data API Whatsapp' // Tambahan untuk title halaman
        ]);
    }

    public function getData()
    {
        $whatsappOwners = APIWhatsappData::query();

        return DataTables::of($whatsappOwners)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                // If quota is 0, display inactive status regardless of database value
                if ($row->quota <= 0) {
                    return '<span class="badge bg-danger">Inactive (No Quota)</span>';
                }
                return $row->status_badge;
            })
            ->addColumn('quota_display', function ($row) {
                $quotaClass = 'text-success';
                $quotaIcon = 'fas fa-battery-full';
                $barClass = 'quota-high';

                if ($row->quota <= 0) {
                    $quotaClass = 'text-danger';
                    $quotaIcon = 'fas fa-battery-empty';
                    $barClass = 'quota-empty';
                } elseif ($row->quota <= 50) {
                    $quotaClass = 'text-danger';
                    $quotaIcon = 'fas fa-battery-quarter';
                    $barClass = 'quota-critical';
                } elseif ($row->quota <= 200) {
                    $quotaClass = 'text-warning';
                    $quotaIcon = 'fas fa-battery-half';
                    $barClass = 'quota-low';
                } elseif ($row->quota <= 500) {
                    $quotaClass = 'text-info';
                    $quotaIcon = 'fas fa-battery-three-quarters';
                    $barClass = 'quota-medium';
                }

                // Calculate percentage for progress bar (max 1000 for calculation)
                $percentage = min(($row->quota / 1000) * 100, 100);

                return '<div class="quota-display">
                            <div class="quota-bar">
                                <div class="quota-fill ' . $barClass . '" style="width: ' . $percentage . '%"></div>
                            </div>
                            <span class="' . $quotaClass . ' quota-number">
                                <i class="' . $quotaIcon . ' me-1"></i>' .
                                number_format($row->quota) .
                            '</span>
                        </div>';
            })
            ->addColumn('subscribe', function ($row) {
                return $row->subscription_date_formatted;
            })
            ->addColumn('actions', function ($row) {
                $buttons = [];

                // Activate button - hanya aktif jika ada quota
                if ($row->quota > 0 && $row->status !== 'active') {
                    $buttons[] = '<button type="button" class="btn btn-sm btn-success btn-activate me-1"
                                    data-id="' . $row->id . '"
                                    data-name="' . $row->name . '"
                                    data-quota="' . $row->quota . '"
                                    title="Activate API">
                                    <i class="fas fa-check"></i>
                                </button>';
                } elseif ($row->status === 'active') {
                    $buttons[] = '<button type="button" class="btn btn-sm btn-success me-1"
                                    disabled
                                    title="Currently Active">
                                    <i class="fas fa-check-circle"></i>
                                </button>';
                } else {
                    $buttons[] = '<button type="button" class="btn btn-sm btn-secondary me-1"
                                    disabled
                                    title="No quota available">
                                    <i class="fas fa-times"></i>
                                </button>';
                }

                // Top Up button
                $buttons[] = '<button type="button" class="btn btn-sm btn-info btn-topup me-1"
                                data-id="' . $row->id . '"
                                data-name="' . $row->name . '"
                                data-number="' . $row->whatsapp_number . '"
                                data-quota="' . $row->quota . '"
                                title="Top Up Quota">
                                <i class="fas fa-plus"></i>
                            </button>';

                // Edit button
                $buttons[] = '<a href="' . route('ApiWhatsapp.edit', $row->id) . '"
                                class="btn btn-warning btn-sm me-1"
                                title="Edit">
                                <i class="fas fa-pencil-alt"></i>
                            </a>';

                // Delete button
                $buttons[] = '<form action="' . route('ApiWhatsapp.destroy', $row->id) . '"
                                method="POST" class="d-inline">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="button" class="btn btn-sm btn-danger btn-delete"
                                        data-name="' . $row->name . '"
                                        title="Delete">
                                        <i class="fas fa-trash"></i>
                                </button>
                            </form>';

                return '<div class="btn-group" role="group">' . implode('', $buttons) . '</div>';
            })
            ->rawColumns(['status', 'quota_display', 'actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.masterdata.apiWhatsapp.create-data-apiWhatsapp', [
            'type_menu' => 'master-data',
            'pageTitle' => 'Tambah Data API Whatsapp' // Tambahan untuk title halaman
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|unique:whatsapp_api_owners,whatsapp_number',
            'token' => 'required|string|max:200',
            'status' => 'required|in:active,inactive',
            'quota' => 'required|integer|min:0',
            'subscription_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        APIWhatsappData::create($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data API Whatsapp Owner berhasil ditambahkan'
            ]);
        }

        return redirect()
            ->route('masterdata.loket')
            ->with('success', 'Data API Whatsapp Owner berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $whatsappOwners = APIWhatsappData::findOrFail($id);
        return view('admin.masterdata.apiWhatsapp.edit-data-apiWhatsapp', [
            'type_menu' => 'master-data',
            'whatsappOwner' => $whatsappOwners,
            'pageTitle' => 'Edit Data API Whatsapp' // Tambahan untuk title halaman
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $whatsappOwner = APIWhatsappData::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|unique:whatsapp_api_owners,whatsapp_number,' . $id,
            'status' => 'required|in:active,inactive',
            'quota' => 'required|integer|min:0',
            'subscription_date' => 'nullable|date',
            'token' => 'required|string|min:10|unique:whatsapp_api_owners,token,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();

        // If quota is 0, force inactive status
        if (intval($data['quota']) <= 0) {
            $data['status'] = 'inactive';
        }

        // If this owner is set to active, deactivate all others
        if ($data['status'] === 'active' && $whatsappOwner->status !== 'active') {
            APIWhatsappData::where('id', '!=', $id)
                ->where('status', 'active')
                ->update(['status' => 'inactive']);
        }

        $whatsappOwner->update($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data API Whatsapp Owner berhasil diperbarui'
            ]);
        }

        return redirect()
            ->route('masterdata.loket')
            ->with('success', 'Data API Whatsapp Owner berhasil diperbarui');
    }

    /**
     * Top up quota untuk API tertentu
     */
    public function topUpQuota(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'additional_quota' => 'required|integer|min:1|max:50000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $api = APIWhatsappData::findOrFail($id);
            $oldQuota = $api->quota;
            $additionalQuota = $request->additional_quota;

            $api->quota += $additionalQuota;

            // Jika quota bertambah dan statusnya inactive karena quota habis,
            // tanyakan apakah mau diaktifkan
            $shouldActivate = false;
            if ($api->quota > 0 && $api->status === 'inactive') {
                // Check if no other API is active
                $hasActiveApi = APIWhatsappData::where('id', '!=', $id)
                    ->where('status', 'active')
                    ->exists();

                if (!$hasActiveApi) {
                    $api->status = 'active';
                    $shouldActivate = true;
                }
            }

            $api->save();

            $message = "Quota berhasil ditambah dari " . number_format($oldQuota) .
                    " menjadi " . number_format($api->quota);

            if ($shouldActivate) {
                $message .= " dan status diaktifkan.";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'old_quota' => $oldQuota,
                    'new_quota' => $api->quota,
                    'added_quota' => $additionalQuota,
                    'status' => $api->status,
                    'auto_activated' => $shouldActivate
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error topping up quota: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get quota usage statistics
     */
    public function getQuotaUsageStats(Request $request)
    {
        try {
            $period = $request->get('period', '7'); // default 7 days
            $startDate = now()->subDays($period);

            // Untuk mendapatkan usage stats, kita perlu tracking table atau log
            // Sementara ini kita hitung berdasarkan data yang ada

            $apis = APIWhatsappData::all();
            $stats = [];

            foreach ($apis as $api) {
                $stats[] = [
                    'id' => $api->id,
                    'name' => $api->name,
                    'whatsapp_number' => $api->whatsapp_number,
                    'current_quota' => $api->quota,
                    'status' => $api->status,
                    'subscription_date' => $api->subscription_date_formatted,
                    'quota_percentage' => $api->quota > 0 ?
                        ($api->quota / max($api->quota, 1000)) * 100 : 0 // Asumsi max quota 1000
                ];
            }

            return response()->json([
                'success' => true,
                'period_days' => $period,
                'data' => $stats,
                'summary' => [
                    'total_active_quota' => $apis->where('status', 'active')->sum('quota'),
                    'total_apis' => $apis->count(),
                    'low_quota_apis' => $apis->where('quota', '<=', 100)->where('quota', '>', 0)->count(),
                    'exhausted_apis' => $apis->where('quota', '<=', 0)->count()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting quota usage stats: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check quota status for all WhatsApp APIs
     */
    public function checkQuotaStatus()
    {
        try {
            $apis = APIWhatsappData::orderBy('status', 'desc')
                                ->orderBy('quota', 'desc')
                                ->get();

            $summary = [
                'total_apis' => $apis->count(),
                'active_apis' => $apis->where('status', 'active')->count(),
                'apis_with_quota' => $apis->where('quota', '>', 0)->count(),
                'total_quota' => $apis->sum('quota'),
                'active_quota' => $apis->where('status', 'active')->sum('quota')
            ];

            return response()->json([
                'success' => true,
                'summary' => $summary,
                'apis' => $apis->map(function($api) {
                    return [
                        'id' => $api->id,
                        'name' => $api->name,
                        'whatsapp_number' => $api->whatsapp_number,
                        'status' => $api->status,
                        'quota' => $api->quota,
                        'subscription_date' => $api->subscription_date_formatted,
                        'quota_status' => $api->quota > 0 ? 'Available' : 'Exhausted'
                    ];
                })
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking quota status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $loket = APIWhatsappData::findOrFail($id);
            $loket->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data API Whatsapp Owner berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus Data API Whatsapp Owner'
            ], 500);
        }
    }

    public function toggleActive(Request $request, $id)
    {
        try {
            $whatsappOwner = APIWhatsappData::findOrFail($id);

            // Check if quota is zero
            if ($whatsappOwner->quota <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Quota untuk nomor whatsapp {$whatsappOwner->whatsapp_number} telah habis. Top up quota terlebih dahulu."
                ], 400);
            }

            // Deactivate all other owners
            APIWhatsappData::where('id', '!=', $id)
                ->update(['status' => 'inactive']);

            // Activate the selected owner
            $whatsappOwner->status = 'active';
            $whatsappOwner->save();

            return response()->json([
                'success' => true,
                'message' => "API {$whatsappOwner->name} berhasil diaktifkan dengan quota " . number_format($whatsappOwner->quota),
                'data' => [
                    'id' => $whatsappOwner->id,
                    'name' => $whatsappOwner->name,
                    'whatsapp_number' => $whatsappOwner->whatsapp_number,
                    'token' => $whatsappOwner->token,
                    'quota' => $whatsappOwner->quota,
                    'status' => $whatsappOwner->status
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get summary data untuk dashboard
     */
    public function getSummary()
    {
        try {
            $summary = [
                'total_apis' => APIWhatsappData::count(),
                'active_apis' => APIWhatsappData::where('status', 'active')->count(),
                'apis_with_quota' => APIWhatsappData::where('quota', '>', 0)->count(),
                'total_quota' => APIWhatsappData::sum('quota'),
                'active_quota' => APIWhatsappData::where('status', 'active')->sum('quota'),
                'low_quota_apis' => APIWhatsappData::where('quota', '>', 0)
                                                ->where('quota', '<=', 50)
                                                ->count(),
                'zero_quota_apis' => APIWhatsappData::where('quota', '<=', 0)->count()
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
     * Auto switch ke API dengan quota terbanyak
     */
    public function autoSwitchToMaxQuota()
    {
        try {
            // Cari API dengan quota terbanyak
            $bestApi = APIWhatsappData::where('quota', '>', 0)
                ->orderBy('quota', 'desc')
                ->first();

            if (!$bestApi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada API dengan quota tersedia'
                ]);
            }

            // Nonaktifkan semua API
            APIWhatsappData::query()->update(['status' => 'inactive']);

            // Aktifkan API dengan quota terbanyak
            $bestApi->status = 'active';
            $bestApi->save();

            return response()->json([
                'success' => true,
                'message' => "Berhasil switch ke API {$bestApi->name} dengan quota " . number_format($bestApi->quota),
                'data' => [
                    'api_name' => $bestApi->name,
                    'quota' => $bestApi->quota,
                    'whatsapp_number' => $bestApi->whatsapp_number
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error during auto switch: ' . $e->getMessage()
            ], 500);
        }
    }
}
