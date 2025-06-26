<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DataKependudukan extends Model
{
    use HasFactory;

    protected $table = 'data_kependudukan';

    protected $fillable = [
        'total_penduduk',
        'total_kk',
        'total_rw',
        'total_rt',
        'usia_0_17',
        'usia_18_35',
        'usia_36_55',
        'usia_56_plus',
        'laki_laki',
        'perempuan',
        'sd_sederajat',
        'smp_sederajat',
        'sma_sederajat',
        'diploma_s1_plus',
        'periode_data',
        'keterangan',
        'is_active',
        'last_updated'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_updated' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Scope untuk mendapatkan data aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk mendapatkan data terbaru
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('last_updated', 'desc');
    }

    /**
     * Get current active data
     */
    public static function getCurrentData()
    {
        return self::active()->latest()->first();
    }

    /**
     * Get age groups with labels
     */
    public function getAgeGroups()
    {
        return [
            '0-17 tahun' => $this->usia_0_17,
            '18-35 tahun' => $this->usia_18_35,
            '36-55 tahun' => $this->usia_36_55,
            '56+ tahun' => $this->usia_56_plus,
        ];
    }

    /**
     * Get gender distribution
     */
    public function getGenderDistribution()
    {
        return [
            'Laki-laki' => $this->laki_laki,
            'Perempuan' => $this->perempuan,
        ];
    }

    /**
     * Get education levels
     */
    public function getEducationLevels()
    {
        return [
            'SD/Sederajat' => $this->sd_sederajat,
            'SMP/Sederajat' => $this->smp_sederajat,
            'SMA/Sederajat' => $this->sma_sederajat,
            'Diploma/S1+' => $this->diploma_s1_plus,
        ];
    }

    /**
     * Get formatted last updated
     */
    public function getFormattedLastUpdatedAttribute()
    {
        return $this->last_updated->format('d M Y');
    }

    /**
     * Get formatted periode
     */
    public function getFormattedPeriodeAttribute()
    {
        if ($this->periode_data) {
            return Carbon::createFromFormat('Y-m', $this->periode_data)->format('F Y');
        }
        return 'Tidak diketahui';
    }

    /**
     * Validasi konsistensi data
     */
    public function validateDataConsistency()
    {
        $errors = [];

        // Validasi total berdasarkan usia
        $totalByAge = $this->usia_0_17 + $this->usia_18_35 + $this->usia_36_55 + $this->usia_56_plus;
        if ($totalByAge != $this->total_penduduk) {
            $errors[] = "Total penduduk tidak sesuai dengan jumlah berdasarkan usia ($totalByAge vs {$this->total_penduduk})";
        }

        // Validasi total berdasarkan jenis kelamin
        $totalByGender = $this->laki_laki + $this->perempuan;
        if ($totalByGender != $this->total_penduduk) {
            $errors[] = "Total penduduk tidak sesuai dengan jumlah berdasarkan jenis kelamin ($totalByGender vs {$this->total_penduduk})";
        }

        // Validasi total berdasarkan pendidikan (boleh kurang karena mungkin ada yang belum sekolah)
        $totalByEducation = $this->sd_sederajat + $this->smp_sederajat + $this->sma_sederajat + $this->diploma_s1_plus;
        if ($totalByEducation > $this->total_penduduk) {
            $errors[] = "Total berdasarkan pendidikan melebihi total penduduk ($totalByEducation vs {$this->total_penduduk})";
        }

        return $errors;
    }

    /**
     * Auto-calculate totals based on demographics
     */
    public function autoCalculateTotals()
    {
        // Update total penduduk berdasarkan usia (sebagai sumber utama)
        $this->total_penduduk = $this->usia_0_17 + $this->usia_18_35 + $this->usia_36_55 + $this->usia_56_plus;

        // Update last_updated
        $this->last_updated = now();
    }

    /**
     * Get statistics array for dashboard
     */
    public function getStatistics()
    {
        return [
            'total_penduduk' => $this->total_penduduk,
            'total_kk' => $this->total_kk,
            'total_rw' => $this->total_rw,
            'total_rt' => $this->total_rt,
            'last_updated' => $this->formatted_last_updated,
            'periode' => $this->formatted_periode
        ];
    }

    /**
     * Get demographic data for display
     */
    public function getDemographicData()
    {
        return [
            'age_groups' => $this->getAgeGroups(),
            'gender' => $this->getGenderDistribution(),
            'education' => $this->getEducationLevels()
        ];
    }
}
