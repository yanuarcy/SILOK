<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Perpu extends Model
{
    use HasFactory;

    protected $table = 'perpu';

    protected $fillable = [
        'nomor_peraturan',
        'tahun',
        'judul',
        'tentang',
        'deskripsi',
        'file_pdf',
        'ukuran_file',
        'tanggal_penetapan',
        'tanggal_upload',
        'jenis_peraturan',
        'status',
        'urutan_tampil',
        'is_active',
        'tags',
        'download_count'
    ];

    protected $casts = [
        'tanggal_penetapan' => 'date',
        'tanggal_upload' => 'date',
        'is_active' => 'boolean',
        'tags' => 'array',
        'download_count' => 'integer',
        'ukuran_file' => 'integer'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'Published');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan_tampil', 'asc')
                    ->orderBy('tanggal_penetapan', 'desc');
    }

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis_peraturan', $jenis);
    }

    public function scopeByTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    // Accessors
    public function getFileUrlAttribute()
    {
        if ($this->file_pdf) {
            return asset('storage/perpu/' . $this->file_pdf);
        }
        return null;
    }

    public function getPdfUrlAttribute()
    {
        return asset('storage/perpu/' . $this->file_pdf);
    }

    public function getFormattedFileSizeAttribute()
    {
        if (!$this->ukuran_file) return '-';

        $bytes = $this->ukuran_file;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 1) . ' ' . $units[$i];
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'Published' => '<span class="badge badge-success">Published</span>',
            'Draft' => '<span class="badge badge-warning">Draft</span>',
            'Archived' => '<span class="badge badge-secondary">Archived</span>'
        ];

        return $badges[$this->status] ?? '<span class="badge badge-secondary">Unknown</span>';
    }

    public function getActiveBadgeAttribute()
    {
        return $this->is_active
            ? '<span class="badge badge-success">Aktif</span>'
            : '<span class="badge badge-danger">Non-Aktif</span>';
    }

    public function getJenisColorAttribute()
    {
        $colors = [
            'Peraturan Walikota' => 'primary',
            'Peraturan Daerah' => 'success',
            'Keputusan Walikota' => 'info',
            'Instruksi Walikota' => 'warning',
            'Surat Edaran Walikota' => 'secondary',
            'Peraturan Menteri' => 'danger',
            'Undang-Undang' => 'dark',
            'Peraturan Pemerintah' => 'light',
            'Lainnya' => 'secondary'
        ];

        return $colors[$this->jenis_peraturan] ?? 'secondary';
    }

    public function getJenisBadgeAttribute()
    {
        return '<span class="badge badge-' . $this->jenis_color . '">' . $this->jenis_peraturan . '</span>';
    }

    public function getFullTitleAttribute()
    {
        return $this->jenis_peraturan . ' Nomor ' . $this->nomor_peraturan . ' Tahun ' . $this->tahun;
    }

    // Methods
    public function incrementDownload()
    {
        $this->increment('download_count');
    }

    public static function getJenisOptions()
    {
        return [
            'Peraturan Walikota',
            'Peraturan Daerah',
            'Keputusan Walikota',
            'Instruksi Walikota',
            'Surat Edaran Walikota',
            'Peraturan Menteri',
            'Undang-Undang',
            'Peraturan Pemerintah',
            'Lainnya'
        ];
    }

    public static function getYearOptions()
    {
        return self::select('tahun')
                  ->distinct()
                  ->orderBy('tahun', 'desc')
                  ->pluck('tahun')
                  ->toArray();
    }

    public function getRouteKeyName()
    {
        return 'id';
    }

    // File handling
    public function deleteFile()
    {
        if ($this->file_pdf && Storage::disk('public')->exists('perpu/' . $this->file_pdf)) {
            Storage::disk('public')->delete('perpu/' . $this->file_pdf);
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($perpu) {
            $perpu->deleteFile();
        });
    }
}
