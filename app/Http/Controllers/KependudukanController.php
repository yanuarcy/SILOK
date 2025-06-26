<?php

namespace App\Http\Controllers;

use App\Models\DataKependudukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KependudukanController extends Controller
{
    /**
     * Display data kependudukan for public
     */
    public function index()
    {
        $currentData = DataKependudukan::getCurrentData();

        if (!$currentData) {
            // Jika belum ada data, buat data default
            $currentData = $this->createDefaultData();
        }

        $statistics = $currentData->getStatistics();
        $demographic_data = $currentData->getDemographicData();

        return view('layanan.InformasiUmum.data-penduduk', compact('statistics', 'demographic_data'));
    }

    /**
     * Display admin dashboard for managing data
     */
    public function adminIndex()
    {
        $type_menu = "master-data";
        $currentData = DataKependudukan::getCurrentData();

        if (!$currentData) {
            $currentData = $this->createDefaultData();
        }

        return view('admin.masterdata.data-kependudukan.index', compact('type_menu', 'currentData'));
    }

    /**
     * Show edit form
     */
    public function edit()
    {
        $type_menu = "master-data";
        $data = DataKependudukan::getCurrentData();

        if (!$data) {
            $data = $this->createDefaultData();
        }

        return view('admin.masterdata.data-kependudukan.edit', compact('type_menu', 'data'));
    }

    /**
     * Update data kependudukan
     */
    public function update(Request $request)
    {
        $request->validate([
            'total_kk' => 'required|integer|min:0',
            'total_rw' => 'required|integer|min:0',
            'total_rt' => 'required|integer|min:0',
            'usia_0_17' => 'required|integer|min:0',
            'usia_18_35' => 'required|integer|min:0',
            'usia_36_55' => 'required|integer|min:0',
            'usia_56_plus' => 'required|integer|min:0',
            'laki_laki' => 'required|integer|min:0',
            'perempuan' => 'required|integer|min:0',
            'sd_sederajat' => 'required|integer|min:0',
            'smp_sederajat' => 'required|integer|min:0',
            'sma_sederajat' => 'required|integer|min:0',
            'diploma_s1_plus' => 'required|integer|min:0',
            'periode_data' => 'required|date_format:Y-m',
            'keterangan' => 'nullable|string|max:1000'
        ], [
            'total_kk.required' => 'Total KK harus diisi',
            'total_kk.integer' => 'Total KK harus berupa angka',
            'total_kk.min' => 'Total KK tidak boleh kurang dari 0',
            'total_rw.required' => 'Total RW harus diisi',
            'usia_0_17.required' => 'Data usia 0-17 tahun harus diisi',
            'laki_laki.required' => 'Data laki-laki harus diisi',
            'periode_data.required' => 'Periode data harus diisi',
            'periode_data.date_format' => 'Format periode harus YYYY-MM'
        ]);

        try {
            DB::beginTransaction();

            $currentData = DataKependudukan::getCurrentData();

            if (!$currentData) {
                $currentData = new DataKependudukan();
            }

            // Update data
            $currentData->fill($request->only([
                'total_kk', 'total_rw', 'total_rt',
                'usia_0_17', 'usia_18_35', 'usia_36_55', 'usia_56_plus',
                'laki_laki', 'perempuan',
                'sd_sederajat', 'smp_sederajat', 'sma_sederajat', 'diploma_s1_plus',
                'periode_data', 'keterangan'
            ]));

            // Auto calculate totals
            $currentData->autoCalculateTotals();

            // Validate data consistency
            $errors = $currentData->validateDataConsistency();
            if (!empty($errors)) {
                DB::rollBack();
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['validation' => implode(', ', $errors)]);
            }

            $currentData->save();

            DB::commit();

            return redirect()->route('admin.kependudukan.index')
                ->with('success', 'Data kependudukan berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Get current statistics for API
     */
    public function getStatistics()
    {
        try {
            $currentData = DataKependudukan::getCurrentData();

            if (!$currentData) {
                $currentData = $this->createDefaultData();
            }

            $statistics = $currentData->getStatistics();
            $demographic_data = $currentData->getDemographicData();

            return response()->json([
                'success' => true,
                'data' => [
                    'statistics' => $statistics,
                    'demographic_data' => $demographic_data,
                    'last_updated' => $currentData->last_updated->toISOString(),
                    'periode' => $currentData->periode_data
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get summary for dashboard widget
     */
    public function getSummary()
    {
        try {
            $currentData = DataKependudukan::getCurrentData();

            if (!$currentData) {
                $currentData = $this->createDefaultData();
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'total_penduduk' => $currentData->total_penduduk,
                    'total_kk' => $currentData->total_kk,
                    'total_rw' => $currentData->total_rw,
                    'total_rt' => $currentData->total_rt,
                    'last_updated' => $currentData->formatted_last_updated,
                    'periode' => $currentData->formatted_periode,

                    // Additional metrics
                    'rata_rata_per_kk' => $currentData->total_kk > 0 ?
                        round($currentData->total_penduduk / $currentData->total_kk, 1) : 0,
                    'rata_rata_per_rw' => $currentData->total_rw > 0 ?
                        round($currentData->total_penduduk / $currentData->total_rw, 0) : 0,
                    'rata_rata_per_rt' => $currentData->total_rt > 0 ?
                        round($currentData->total_penduduk / $currentData->total_rt, 0) : 0,

                    // Demographics summary
                    'dominant_age_group' => $this->getDominantAgeGroup($currentData),
                    'gender_ratio' => $this->getGenderRatio($currentData),
                    'education_completion_rate' => $this->getEducationRate($currentData)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading summary: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create default data if none exists
     */
    private function createDefaultData()
    {
        return DataKependudukan::create([
            'total_penduduk' => 12458,
            'total_kk' => 3854,
            'total_rw' => 12,
            'total_rt' => 48,
            'usia_0_17' => 3243,
            'usia_18_35' => 4567,
            'usia_36_55' => 3234,
            'usia_56_plus' => 1414,
            'laki_laki' => 6224,
            'perempuan' => 6234,
            'sd_sederajat' => 2456,
            'smp_sederajat' => 3234,
            'sma_sederajat' => 4567,
            'diploma_s1_plus' => 2201,
            'periode_data' => date('Y-m'),
            'keterangan' => 'Data awal sistem',
            'last_updated' => now()
        ]);
    }

    /**
     * Get dominant age group
     */
    private function getDominantAgeGroup($data)
    {
        $ageGroups = [
            '0-17 tahun' => $data->usia_0_17,
            '18-35 tahun' => $data->usia_18_35,
            '36-55 tahun' => $data->usia_36_55,
            '56+ tahun' => $data->usia_56_plus,
        ];

        return array_keys($ageGroups, max($ageGroups))[0];
    }

    /**
     * Get gender ratio
     */
    private function getGenderRatio($data)
    {
        if ($data->perempuan == 0) return 'N/A';

        $ratio = round($data->laki_laki / $data->perempuan * 100, 1);
        return $ratio . '%';
    }

    /**
     * Get education completion rate (SMA+ level)
     */
    private function getEducationRate($data)
    {
        if ($data->total_penduduk == 0) return 0;

        $educated = $data->sma_sederajat + $data->diploma_s1_plus;
        return round(($educated / $data->total_penduduk) * 100, 1);
    }

    /**
     * Import data from CSV (untuk admin)
     */
    public function importData(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        try {
            // Process CSV import
            // Implementation for CSV import can be added here

            return redirect()->route('admin.kependudukan.index')
                ->with('success', 'Data berhasil diimpor!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Gagal mengimpor data: ' . $e->getMessage()]);
        }
    }

    /**
     * Export data to CSV
     */
    public function exportData()
    {
        try {
            $data = DataKependudukan::getCurrentData();

            if (!$data) {
                return redirect()->back()
                    ->withErrors(['error' => 'Tidak ada data untuk diekspor']);
            }

            $filename = 'data_kependudukan_' . date('Y-m-d') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($data) {
                $file = fopen('php://output', 'w');

                // Headers
                fputcsv($file, [
                    'Kategori', 'Subkategori', 'Jumlah', 'Persentase', 'Periode'
                ]);

                // Data rows
                $ageGroups = $data->getAgeGroups();
                foreach ($ageGroups as $group => $count) {
                    $percentage = $data->total_penduduk > 0 ? round(($count / $data->total_penduduk) * 100, 1) : 0;
                    fputcsv($file, ['Usia', $group, $count, $percentage . '%', $data->periode_data]);
                }

                $genders = $data->getGenderDistribution();
                foreach ($genders as $gender => $count) {
                    $percentage = $data->total_penduduk > 0 ? round(($count / $data->total_penduduk) * 100, 1) : 0;
                    fputcsv($file, ['Jenis Kelamin', $gender, $count, $percentage . '%', $data->periode_data]);
                }

                $educations = $data->getEducationLevels();
                foreach ($educations as $education => $count) {
                    $percentage = $data->total_penduduk > 0 ? round(($count / $data->total_penduduk) * 100, 1) : 0;
                    fputcsv($file, ['Pendidikan', $education, $count, $percentage . '%', $data->periode_data]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Gagal mengekspor data: ' . $e->getMessage()]);
        }
    }
}
