<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pegawai;
use App\Models\DataSkm;
use App\Http\Controllers\ProfileController;




class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $cookieToken = $request->cookie('remember_token');
        $dbToken = $user ? $user->remember_token : null;
        $loggedInDuration = $user ? $user->getLoggedInDuration() : null;

        // Check if user needs profile update
        $needsProfileUpdate = false;
        $profileRole = null;

        if ($user && in_array($user->role, ['Ketua RT', 'Ketua RW'])) {
            $needsProfileUpdate = !ProfileController::isProfileComplete($user);
            $profileRole = $user->role;

            // Store in session for JavaScript access
            if ($needsProfileUpdate) {
                session([
                    'needs_profile_update' => true,
                    'profile_role' => $profileRole
                ]);
            }
        }

        // Ambil hanya pegawai struktural (jabatan tertentu)
        $jabatanStruktural = [
            'Lurah',
            'Sekretaris Kelurahan',
            'Seksi Pemerintahan dan Pelayanan Publik',
            'Seksi Kesejahteraan Rakyat dan Perekonomian',
            'Seksi Ketentraman, Ketertiban dan Pembangunan'
        ];

        $pegawaiStruktural = Pegawai::with('user')
            ->whereIn('jabatan', $jabatanStruktural)
            ->where('is_active', true)
            ->ordered() // Menggunakan scope yang sudah ada di model
            ->get();

        // Data untuk testimonial
        $testimonials = DataSkm::with('user')
            ->activeTestimonials()
            ->limit(10)
            ->get();

        // Hitung data untuk satisfaction index
        $sangatPuas = DataSkm::where('tingkat_kepuasan', 'Sangat Puas')->count();
        $puas = DataSkm::where('tingkat_kepuasan', 'Puas')->count();
        $kurangPuas = DataSkm::where('tingkat_kepuasan', 'Kurang Puas')->count();
        $tidakPuas = DataSkm::where('tingkat_kepuasan', 'Tidak Puas')->count();

        $statistics = [
            'total_surveys' => DataSkm::count(),
            'positive_surveys' => DataSkm::whereIn('tingkat_kepuasan', ['Sangat Puas', 'Puas'])->count(),
            'satisfaction_index' => $this->calculateSatisfactionIndex($sangatPuas, $puas, $kurangPuas, $tidakPuas)
        ];

        $statistics['satisfaction_percentage'] = $statistics['total_surveys'] > 0
            ? round(($statistics['positive_surveys'] / $statistics['total_surveys']) * 100, 1)
            : 0;

        return view('app.index', compact(
            'user',
            'cookieToken',
            'dbToken',
            'loggedInDuration',
            'pegawaiStruktural',
            'testimonials',
            'statistics',
            'needsProfileUpdate',
            'profileRole'
        ));
    }

    private function calculateSatisfactionIndex($sangatPuas, $puas, $tidakPuas)
    {
        $total = $sangatPuas + $puas + $tidakPuas;
        if ($total == 0) return 0;

        // Weighted calculation: Sangat Puas = 4, Puas = 3, Tidak Puas = 1
        $weightedScore = ($sangatPuas * 4) + ($puas * 3) + ($tidakPuas * 1);
        $maxPossibleScore = $total * 4;

        return round(($weightedScore / $maxPossibleScore) * 100, 1);
    }

    function getTestimonialAvatar($testimonial) {
        // Jika ada relasi user dan ada image
        if ($testimonial->user && $testimonial->user->image) {
            $imagePaths = [
                'storage/images/pegawai/' . $testimonial->user->image,
                'images/pegawai/' . $testimonial->user->image,
                'storage/' . $testimonial->user->image,
                $testimonial->user->image
            ];

            foreach ($imagePaths as $path) {
                if (file_exists(public_path($path))) {
                    return '<img class="img-fluid flex-shrink-0 rounded-circle" src="' . asset($path) . '" style="width: 50px; height: 50px; object-fit: cover;" alt="' . $testimonial->nama . '">';
                }
            }
        }

        // Fallback ke initial huruf pertama nama
        $initial = strtoupper(substr($testimonial->nama ?? 'U', 0, 1));
        return '<div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 50px; height: 50px; font-size: 18px; font-weight: bold;">' . $initial . '</div>';
    }
}
