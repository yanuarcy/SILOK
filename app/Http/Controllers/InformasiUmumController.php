<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meeting;
use App\Models\DataKependudukan;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class InformasiUmumController extends Controller
{
    /**
     * Display the main Informasi Umum page
     */
    public function index(): View
    {
        $data = [
            'title' => 'Informasi Umum - SILOK',
            'meta_description' => 'Informasi umum Kelurahan Jemur Wonosari meliputi data penduduk, zoom meeting RW/RT, dan persyaratan pelayanan administrasi.',
            'statistics' => $this->getStatisticsData(),
            'meeting_schedule' => $this->getMeetingScheduleData(),
            'services_available' => $this->getServicesAvailable(),
        ];

        return view('layanan.InformasiUmum.index', $data);
    }

    /**
     * Display data penduduk page
     */
    public function dataPenduduk(): View
    {
        $data = [
            'title' => 'Data Penduduk - Informasi Umum',
            'statistics' => $this->getStatisticsData(),
            'demographic_data' => $this->getDemographicData(),
        ];

        return view('layanan.InformasiUmum.data-penduduk', $data);
    }

    /**
     * Display zoom meeting page
     */
    public function zoomMeeting(): View
    {
        $data = [
            'title' => 'Zoom Meeting RW/RT - Informasi Umum',
            'meeting_schedule' => $this->getMeetingScheduleData(),
            'meeting_access' => $this->getMeetingAccess(),
            'active_meetings' => $this->getActiveMeetings(),
        ];

        return view('layanan.InformasiUmum.zoom-meeting', $data);
    }

    private function getActiveMeetings(): array
    {
        $meetings = Meeting::active()
                          ->with('user')
                          ->orderBy('meeting_date')
                          ->orderBy('meeting_time')
                          ->take(3) // Limit to 3 for scroll functionality
                          ->get();

        return $meetings->map(function ($meeting) {
            return [
                'id' => $meeting->id,
                'title' => $meeting->title,
                'date' => $meeting->meeting_date->format('Y-m-d'),
                'time' => $meeting->meeting_time->format('H:i') . ' WIB',
                'meet_link' => $meeting->meet_link,
                'status' => $meeting->status,
                'status_label' => $meeting->status_label,
                'status_class' => $meeting->status_badge_class,
                'participants' => $meeting->participants_string,
                'description' => $meeting->description,
                'author' => $meeting->user->name ?? 'Unknown',
                'can_join' => $meeting->should_start,
                'is_active' => $meeting->is_active,
                'formatted_date' => $meeting->meeting_date->format('d F Y'),
            ];
        })->toArray();
    }


    /**
     * Display persyaratan page
     */
    public function persyaratan(): View
    {
        $data = [
            'title' => 'Persyaratan Pelayanan - Informasi Umum',
            'services_available' => $this->getServicesAvailable(),
            'office_hours' => $this->getOfficeHours(),
        ];

        return view('layanan.InformasiUmum.persyaratan-pelayanan', $data);
    }

    /**
     * Get statistics data via API
     */
    public function getStatistics(): JsonResponse
    {
        return response()->json($this->getStatisticsData());
    }

    /**
     * Get meeting schedule via API
     */
    public function getMeetingSchedule(): JsonResponse
    {
        return response()->json($this->getMeetingScheduleData());
    }

    /**
     * Private method to get statistics data
     */
    private function getStatisticsData(): array
    {
        try {
            $currentData = DataKependudukan::getCurrentData();

            if (!$currentData) {
                // Fallback to default data if no data exists
                return $this->getDefaultStatistics();
            }

            return [
                'total_penduduk' => $currentData->total_penduduk,
                'total_kk' => $currentData->total_kk,
                'total_rw' => $currentData->total_rw,
                'total_rt' => $currentData->total_rt,
                'last_updated' => $currentData->formatted_last_updated,
                'periode' => $currentData->formatted_periode,

                // Additional statistics for better display
                'rata_rata_per_kk' => $currentData->total_kk > 0 ?
                    round($currentData->total_penduduk / $currentData->total_kk, 1) : 0,
                'rata_rata_per_rw' => $currentData->total_rw > 0 ?
                    round($currentData->total_penduduk / $currentData->total_rw, 0) : 0,
                'rata_rata_per_rt' => $currentData->total_rt > 0 ?
                    round($currentData->total_penduduk / $currentData->total_rt, 0) : 0,

                // Demographic highlights
                'dominant_age_group' => $this->getDominantAgeGroup($currentData),
                'gender_ratio' => $this->getGenderRatio($currentData),
                'education_rate' => $this->getEducationRate($currentData),
            ];

        } catch (\Exception $e) {
            // Log error and return default data
            \Log::error('Error fetching kependudukan data: ' . $e->getMessage());
            return $this->getDefaultStatistics();
        }
    }

    /**
     * Fallback default statistics if database is empty
     */
    private function getDefaultStatistics(): array
    {
        return [
            'total_penduduk' => 12458,
            'total_kk' => 3854,
            'total_rw' => 12,
            'total_rt' => 48,
            'last_updated' => now()->format('d M Y'),
            'periode' => now()->format('F Y'),
            'rata_rata_per_kk' => 3.2,
            'rata_rata_per_rw' => 1038,
            'rata_rata_per_rt' => 260,
            'dominant_age_group' => '18-35 tahun',
            'gender_ratio' => '99.8%',
            'education_rate' => 54.4,
        ];
    }

    /**
     * Get dominant age group
     */
    private function getDominantAgeGroup($data): string
    {
        if (!$data) return '18-35 tahun';

        $ageGroups = [
            '0-17 tahun' => $data->usia_0_17,
            '18-35 tahun' => $data->usia_18_35,
            '36-55 tahun' => $data->usia_36_55,
            '56+ tahun' => $data->usia_56_plus,
        ];

        return array_keys($ageGroups, max($ageGroups))[0];
    }

    /**
     * Get gender ratio (L/P)
     */
    private function getGenderRatio($data): string
    {
        if (!$data || $data->perempuan == 0) return 'N/A';

        $ratio = round($data->laki_laki / $data->perempuan * 100, 1);
        return $ratio . '%';
    }

    /**
     * Get education rate (SMA+ level)
     */
    private function getEducationRate($data): float
    {
        if (!$data || $data->total_penduduk == 0) return 0;

        $educated = $data->sma_sederajat + $data->diploma_s1_plus;
        return round(($educated / $data->total_penduduk) * 100, 1);
    }

    /**
     * Refresh statistics data (for admin use)
     */
    public function refreshStatistics()
    {
        try {
            // Clear any cache if you're using cache
            \Cache::forget('informasi_umum_statistics');

            $statistics = $this->getStatisticsData();

            return response()->json([
                'success' => true,
                'message' => 'Statistics refreshed successfully',
                'data' => $statistics
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error refreshing statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Private method to get meeting schedule data (for regular meetings)
     */
    private function getMeetingScheduleData(): array
    {
        return [
            [
                'title' => 'Rapat Koordinasi RW',
                'schedule' => 'Setiap Senin, 19:00 WIB',
                'status' => 'Aktif',
                'status_class' => 'success'
            ],
            [
                'title' => 'Rapat RT Bulanan',
                'schedule' => 'Minggu ke-2 setiap bulan, 20:00 WIB',
                'status' => 'Terjadwal',
                'status_class' => 'info'
            ],
            [
                'title' => 'Rapat Koordinasi Kelurahan',
                'schedule' => 'Setiap Kamis, 14:00 WIB',
                'status' => 'Pending',
                'status_class' => 'warning'
            ]
        ];
    }

    /**
     * Private method to get meeting access data
     */
    private function getMeetingAccess(): array
    {
        return [
            'meeting_id' => '123-456-789',
            'password' => 'jwkelurahan2024',
            'access_note' => 'Akses meeting hanya untuk pengurus RW/RT yang terdaftar'
        ];
    }

    /**
     * Private method to get services available
     */
    private function getServicesAvailable(): array
    {
        return [
            'SKAW (Surat Keterangan Ahli Waris)',
            'KTP (Kartu Tanda Penduduk)',
            'KK (Kartu Keluarga)',
            'KIA (Kartu Identitas Anak)',
            'IKD (Identitas Kependudukan Digital)',
            'Akta Kelahiran',
            'SKT (Surat Keterangan Tempat Tinggal)',
            'Surat Pindah Datang'
        ];
    }

    /**
     * Private method to get office hours
     */
    private function getOfficeHours(): array
    {
        return [
            'Senin - Jumat: 08.00 - 15.00 WIB',
            'Sabtu: 08.00 - 12.00 WIB',
            'Minggu & Hari Libur: Tutup',
            'Layanan online 24/7'
        ];
    }

    /**
     * Private method to get demographic data
     */
    private function getDemographicData(): array
    {
        return [
            'age_groups' => [
                '0-17 tahun' => 3245,
                '18-35 tahun' => 4567,
                '36-55 tahun' => 3234,
                '56+ tahun' => 1412
            ],
            'gender' => [
                'Laki-laki' => 6234,
                'Perempuan' => 6224
            ],
            'education' => [
                'SD/Sederajat' => 2456,
                'SMP/Sederajat' => 3234,
                'SMA/Sederajat' => 4567,
                'Diploma/S1+' => 2201
            ]
        ];
    }
}
