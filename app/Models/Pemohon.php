<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pemohon extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pemohon';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tanggal',
        'nama',
        'kode_pemohon',
        'no_whatsapp',
        'alamat',
        'jenis_layanan',
        'keterangan',
        'jenis_antrian',
        'jenis_pengiriman',
        'status',
        'dilayani_oleh',
        'tanggal_dilayani'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal' => 'datetime',
        'tanggal_dilayani' => 'datetime',
        'status' => 'string'
    ];

    /**
     * Get status badge untuk display
     */
    public function getStatusBadgeAttribute()
    {
        return $this->status === '1'
            ? '<span class="badge bg-success">Terlayani</span>'
            : '<span class="badge bg-secondary">Belum Terlayani</span>';
    }

    /**
     * Get formatted tanggal
     */
    public function getFormattedTanggalAttribute()
    {
        return $this->tanggal->format('Y-m-d H:i:s');
    }

    /**
     * Get formatted tanggal dilayani
     */
    public function getFormattedTanggalDilayaniAttribute()
    {
        return $this->tanggal_dilayani ? $this->tanggal_dilayani->format('Y-m-d H:i:s') : 'NULL';
    }

    /**
     * Scope untuk filter berdasarkan tanggal
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        // Parse tanggal jika masih string
        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate)->startOfDay();
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate)->endOfDay();
        }

        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan jenis layanan
     */
    public function scopeByJenisLayanan($query, $jenisLayanan)
    {
        return $query->where('jenis_layanan', $jenisLayanan);
    }

    /**
     * Scope untuk filter berdasarkan jenis antrian
     */
    public function scopeByJenisAntrian($query, $jenisAntrian)
    {
        return $query->where('jenis_antrian', $jenisAntrian);
    }

    /**
     * Relationship dengan User (yang melayani)
     */
    public function pelayan()
    {
        return $this->belongsTo(\App\Models\User::class, 'dilayani_oleh', 'name');
    }

    /**
     * Static method untuk membuat data pemohon dari antrian
     */
    public static function createFromAntrian($antrian, $kodePemohon = null)
    {
        return self::create([
            'tanggal' => $antrian->tanggal,
            'nama' => $antrian->nama,
            'kode_pemohon' => $kodePemohon ?: self::generateKodePemohon(),
            'no_whatsapp' => $antrian->no_whatsapp,
            'alamat' => $antrian->alamat,
            'jenis_layanan' => $antrian->jenis_layanan,
            'keterangan' => $antrian->keterangan,
            'jenis_antrian' => $antrian->jenis_antrian,
            'jenis_pengiriman' => $antrian->jenis_pengiriman,
            'status' => $antrian->status,
            'dilayani_oleh' => $antrian->status === '1' ? auth()->user()->name ?? 'System' : null,
            'tanggal_dilayani' => $antrian->status === '1' ? now() : null
        ]);
    }

    /**
     * Generate kode pemohon unik
     */
    public static function generateKodePemohon()
    {
        do {
            $kode = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
        } while (self::where('kode_pemohon', $kode)->exists());

        return $kode;
    }

    /**
     * Update status menjadi terlayani
     */
    public function markAsTerlayani($dilayaniOleh = null)
    {
        $this->update([
            'status' => '1',
            'dilayani_oleh' => $dilayaniOleh ?: auth()->user()->name ?? 'System',
            'tanggal_dilayani' => now()
        ]);
    }
}
