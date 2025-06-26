<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BankData extends Model
{
    use HasFactory;

    protected $table = 'bank_data';

    protected $fillable = [
        'judul_kegiatan',
        'deskripsi',
        'jenis_bank_data',
        'nomor_rw',
        'nomor_rt',
        'tanggal_kegiatan',
        'lokasi',
        'files_foto',
        'files_video',
        'status',
        'is_active',
        'view_count',
        'tags',
        'urutan_tampil',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'tanggal_kegiatan' => 'date',
        'files_foto' => 'array',
        'files_video' => 'array',
        'tags' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Accessors
    public function getJenisBadgeAttribute()
    {
        $badges = [
            'Kelurahan' => '<span class="badge bg-primary">Kelurahan</span>',
            'RW' => '<span class="badge bg-success">RW ' . ($this->nomor_rw ?? '') . '</span>',
            'RT' => '<span class="badge bg-warning text-dark">RT ' . ($this->nomor_rt ?? '') . '</span>',
        ];

        return $badges[$this->jenis_bank_data] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'Published' => '<span class="badge bg-success">Published</span>',
            'Draft' => '<span class="badge bg-warning text-dark">Draft</span>',
            'Archived' => '<span class="badge bg-secondary">Archived</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    public function getActiveBadgeAttribute()
    {
        return $this->is_active
            ? '<span class="badge bg-success">Aktif</span>'
            : '<span class="badge bg-danger">Tidak Aktif</span>';
    }

    public function getTotalFilesAttribute()
    {
        $fotoCount = $this->files_foto ? count($this->files_foto) : 0;
        $videoCount = $this->files_video ? count($this->files_video) : 0;
        return $fotoCount + $videoCount;
    }

    public function getFotoCountAttribute()
    {
        return $this->files_foto ? count($this->files_foto) : 0;
    }

    public function getVideoCountAttribute()
    {
        return $this->files_video ? count($this->files_video) : 0;
    }

    public function getFileInfoAttribute()
    {
        $fotoCount = $this->foto_count;
        $videoCount = $this->video_count;

        $info = [];
        if ($fotoCount > 0) {
            $info[] = $fotoCount . ' foto';
        }
        if ($videoCount > 0) {
            $info[] = $videoCount . ' video';
        }

        return implode(', ', $info) ?: 'Tidak ada file';
    }

    public function getFirstImageAttribute()
    {
        if ($this->files_foto && count($this->files_foto) > 0) {
            return Storage::url($this->files_foto[0]);
        }

        return asset('images/default-placeholder.jpg');
    }

    public function getWilayahLengkapAttribute()
    {
        if ($this->jenis_bank_data === 'RT') {
            return "RT {$this->nomor_rt} RW {$this->nomor_rw}";
        } elseif ($this->jenis_bank_data === 'RW') {
            return "RT {$this->nomor_rt} RW {$this->nomor_rw}";
        }

        return 'Kelurahan Jemurwonosari';
    }

    public function getTanggalKegiatanFormattedAttribute()
    {
        return $this->tanggal_kegiatan ? $this->tanggal_kegiatan->format('d F Y') : 'Tanggal tidak diset';
    }

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at ? $this->created_at->format('d F Y H:i') : 'Tidak tersedia';
    }

    public function getUpdatedAtFormattedAttribute()
    {
        return $this->updated_at ? $this->updated_at->format('d F Y H:i') : 'Tidak tersedia';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'Published');
    }

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis_bank_data', $jenis);
    }

    public function scopeByRW($query, $nomor_rw)
    {
        return $query->where('nomor_rw', $nomor_rw);
    }

    public function scopeByRT($query, $nomor_rt)
    {
        return $query->where('nomor_rt', $nomor_rt);
    }

    public function scopeByTahun($query, $tahun)
    {
        return $query->whereYear('tanggal_kegiatan', $tahun);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('judul_kegiatan', 'like', "%{$search}%")
              ->orWhere('deskripsi', 'like', "%{$search}%")
              ->orWhere('lokasi', 'like', "%{$search}%");
        });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('tanggal_kegiatan', 'desc')
                    ->orderBy('urutan_tampil', 'asc');
    }

    // Static methods
    public static function getJenisOptions()
    {
        return [
            'Kelurahan' => 'Bank Data Kelurahan',
            'RW' => 'Bank Data RW',
            'RT' => 'Bank Data RT',
        ];
    }

    public static function getYearOptions()
    {
        return self::selectRaw('YEAR(tanggal_kegiatan) as tahun')
            ->published()
            ->active()
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();
    }


    public static function getRWOptions()
    {
        // Mengambil range RW dari 1 sampai maksimum total_rw di tabel
        $maxRW = \DB::table('data_kependudukan')->max('total_rw') ?? 20;

        $rw_list = [];
        for ($i = 1; $i <= $maxRW; $i++) {
            $rw_list[sprintf('%02d', $i)] = sprintf('%02d', $i);
        }
        return $rw_list;
    }

    public static function getRTOptions($rw = null)
    {
        // Jika RW spesifik dipilih, ambil maksimum RT untuk RW tersebut
        if ($rw !== null) {
            $maxRT = \DB::table('data_kependudukan')
                ->where('total_rw', intval($rw))
                ->max('total_rt') ?? 20;
        } else {
            // Jika tidak ada RW spesifik, ambil maksimum RT secara keseluruhan
            $maxRT = \DB::table('data_kependudukan')->max('total_rt') ?? 20;
        }

        $rt_list = [];
        for ($i = 1; $i <= $maxRT; $i++) {
            $rt_list[sprintf('%02d', $i)] = sprintf('%02d', $i);
        }
        return $rt_list;
    }

    // Helper methods
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function canBeAccessedBy($user)
    {
        // Admin bisa akses semua
        if ($user->role == 'admin') {
            return true;
        }

        // Operator bisa akses data Kelurahan
        if ($user->role == 'Operator' && $this->jenis_bank_data === 'Kelurahan') {
            return true;
        }

        // Ketua RW bisa akses:
        if ($user->role == 'Ketua RW') {
            // 1. Data Kelurahan (semua Ketua RW bisa lihat)
            if ($this->jenis_bank_data === 'Kelurahan') {
                return true;
            }

            // 2. Data RW sesuai dengan RW mereka
            if ($this->jenis_bank_data === 'RW') {
                return $user->rw == $this->nomor_rw;
            }

            // 3. Data RT yang ada di RW mereka
            if ($this->jenis_bank_data === 'RT') {
                return $user->rw == $this->nomor_rw;
            }
        }

        // Ketua RT bisa akses:
        if ($user->role == 'Ketua RT') {
            // 1. Data Kelurahan
            if ($this->jenis_bank_data === 'Kelurahan') {
                return true;
            }

            // 2. Data RW sesuai dengan RW mereka
            if ($this->jenis_bank_data === 'RW') {
                return $user->rw == $this->nomor_rw;
            }

            // 3. Data RT sesuai dengan RT dan RW mereka
            if ($this->jenis_bank_data === 'RT') {
                return $user->rt == $this->nomor_rt && $user->rw == $this->nomor_rw;
            }
        }

        return false;
    }

    public function canBeEditedBy($user)
    {
        // return $this->created_by === $user->id || $user->role == 'admin';

        // Admin bisa edit semua
        if ($user->role == 'admin') {
            return true;
        }

        // User yang membuat data bisa edit
        if (isset($this->created_by) && $this->created_by == $user->id) {
            return true;
        }

        // Operator bisa edit data Kelurahan
        if ($user->role == 'Operator' && $this->jenis_bank_data === 'Kelurahan') {
            return true;
        }

        // Ketua RW bisa edit data di wilayahnya
        // if ($user->role == 'Ketua RW') {
        //     if ($this->jenis_bank_data === 'RW' && $user->rw == $this->nomor_rw) {
        //         return true;
        //     } elseif ($this->jenis_bank_data === 'RT' && $user->rw == $this->nomor_rw) {
        //         return true;
        //     }
        // }

        // // Ketua RT bisa edit data RT mereka
        // if ($user->role == 'Ketua RT' && $this->jenis_bank_data === 'RT') {
        //     if ($user->rt == $this->nomor_rt && $user->rw == $this->nomor_rw) {
        //         return true;
        //     }
        // }

        return false;
    }
}
