<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Traits\Loggable;
use Carbon\Carbon;

class SkawFile extends Model
{
    use HasFactory;

    protected $table = 'skaw_files';

    protected $fillable = [
        'skaw_id',
        'file_type',
        'file_name',
        'file_path',
        'file_size',
        'mime_type',
        'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function skaw(): BelongsTo
    {
        return $this->belongsTo(Skaw::class);
    }

    public function getFileUrlAttribute()
    {
        return Storage::url($this->file_path);
    }

    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        if ($bytes == 0) return '0 B';

        $k = 1024;
        $sizes = ['B', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes) / log($k));

        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }

    /**
     * File type labels
     */
    public function getFileTypeLabel()
    {
        $labels = [
            'ktp_pewaris' => 'KTP/Kartu Keluarga Pewaris',
            'akta_kematian' => 'Akta Kematian Pewaris',
            'akta_kelahiran_pewaris' => 'Akta Kelahiran/Copy KK Pewaris',
            'akta_kematian_ahli_waris' => 'Akta Kematian Ahli Waris',
            'akta_kelahiran_ahli_waris' => 'Akta Kelahiran Ahli Waris',
            'ktp_ahli_waris' => 'KTP Ahli Waris',
            'kk_ahli_waris' => 'Kartu Keluarga Ahli Waris',
            'ktp_saksi' => 'KTP 2 Orang Saksi',
            'surat_pengantar_rt' => 'Surat Pengantar RT',
            'surat_pernyataan_ahli_waris' => 'Surat Pernyataan Ahli Waris',
            'surat_pernyataan_kebenaran' => 'Surat Pernyataan Kebenaran',
        ];

        return $labels[$this->file_type] ?? ucfirst(str_replace('_', ' ', $this->file_type));
    }

    public function getFormattedSize()
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    }

    public function getFileTypeLabelAttribute()
    {
        $labels = self::getFileTypeLabels();
        return $labels[$this->file_type] ?? $this->file_type;
    }
}
