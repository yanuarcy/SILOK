<?php

namespace App\Http\Controllers;

use App\Models\DataSkm;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     * Menampilkan halaman testimonial dengan data dari database
     */
    public function index()
    {
        // Ambil testimonial aktif dengan sentimen positif
        $testimonials = DataSkm::with('user')
            ->where('status', 'active')  // Status testimonial aktif
            ->whereIn('tingkat_kepuasan', ['Sangat Puas', 'Puas'])  // Hanya yang puas
            ->orderBy('created_at', 'desc')
            ->limit(10)  // Maksimal 10 testimonial
            ->get();

        // Hitung statistik untuk header
        $statistics = $this->getTestimonialStatistics();

        return view('testimonial.index', compact('testimonials', 'statistics'));
    }

    /**
     * Get statistics untuk header testimonial
     */
    private function getTestimonialStatistics()
    {
        $totalSurveys = DataSkm::count();
        $positiveSurveys = DataSkm::whereIn('tingkat_kepuasan', ['Sangat Puas', 'Puas'])->count();
        $satisfactionIndex = $this->calculateSatisfactionIndex();

        return [
            'total_surveys' => $totalSurveys,
            'positive_surveys' => $positiveSurveys,
            'satisfaction_percentage' => $totalSurveys > 0 ? round(($positiveSurveys / $totalSurveys) * 100, 1) : 0,
            'satisfaction_index' => $satisfactionIndex
        ];
    }

    /**
     * Calculate satisfaction index
     */
    private function calculateSatisfactionIndex()
    {
        $sangatPuas = DataSkm::where('tingkat_kepuasan', 'Sangat Puas')->count();
        $puas = DataSkm::where('tingkat_kepuasan', 'Puas')->count();
        $tidakPuas = DataSkm::where('tingkat_kepuasan', 'Tidak Puas')->count();

        $total = $sangatPuas + $puas + $tidakPuas;

        if ($total == 0) return 0;

        // Weighted calculation: Sangat Puas = 4, Puas = 3, Tidak Puas = 1
        $weightedScore = ($sangatPuas * 4) + ($puas * 3) + ($tidakPuas * 1);
        $maxPossibleScore = $total * 4;

        return round(($weightedScore / $maxPossibleScore) * 100, 1);
    }

    /**
     * API endpoint untuk mengambil testimonial secara dynamic
     */
    public function getTestimonials()
    {
        $testimonials = DataSkm::with('user')
            ->where('status', 'active')
            ->whereIn('tingkat_kepuasan', ['Sangat Puas', 'Puas'])
            ->select('id', 'nama', 'kritik_saran', 'tingkat_kepuasan', 'created_at', 'user_id')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->nama,
                    'kritik_saran' => $item->kritik_saran,
                    'tingkat_kepuasan' => $item->tingkat_kepuasan,
                    'user_name' => $item->user ? $item->user->name : 'Anonymous',
                    'tanggal' => $item->created_at->format('d M Y'),
                    'avatar' => $this->generateAvatar($item->nama)
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $testimonials,
            'statistics' => $this->getTestimonialStatistics()
        ]);
    }

    /**
     * Generate avatar berdasarkan nama
     */
    private function generateAvatar($nama)
    {
        // Bisa diganti dengan logic yang lebih complex atau random avatar
        $avatars = [
            'testimonial-1.jpg',
            'testimonial-2.jpg',
            'testimonial-3.jpg',
            'testimonial-4.jpg'
        ];

        // Pilih avatar berdasarkan hash nama agar konsisten
        $index = abs(crc32($nama)) % count($avatars);
        return $avatars[$index];
    }
}

// 2. BLADE TEMPLATE
// Buat file: resources/views/testimonial/index.blade.php
