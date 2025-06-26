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

class SkawAnak extends Model
{
    use HasFactory;

    protected $table = 'skaw_anak';

    protected $fillable = [
        'skaw_id',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'urutan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'urutan' => 'integer',
    ];

    public function skaw(): BelongsTo
    {
        return $this->belongsTo(Skaw::class);
    }

    public function getFormattedTanggalLahirAttribute()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->format('d/m/Y') : '-';
    }
}
