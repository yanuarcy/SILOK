<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Spesimen extends Model
{
    use HasFactory;

    protected $table = 'spesimen';

    protected $fillable = [
        'nama_pejabat',
        'jabatan',
        'rt',
        'rw',
        'file_ttd',
        'file_stempel',
        'keterangan',
        'status',
        'is_active',
        'urutan_tampil',
        'user_id',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByJabatan($query, $jabatan)
    {
        return $query->where('jabatan', $jabatan);
    }

    public function scopeByRW($query, $rw)
    {
        return $query->where('rw', $rw);
    }

    public function scopeByRT($query, $rt, $rw)
    {
        return $query->where('rt', $rt)->where('rw', $rw);
    }

    public function scopeByRTRW($query, $rt = null, $rw = null)
    {
        if ($rt) {
            $query->where('rt', $rt); // CORRECTED: Use rt instead of nomor_rt
        }
        if ($rw) {
            $query->where('rw', $rw); // CORRECTED: Use rw instead of nomor_rw
        }
        return $query;
    }

    // Accessors
    public function getJabatanBadgeAttribute()
    {
        $colors = [
            'Ketua RT' => 'warning',
            'Ketua RW' => 'info',
            'Front Office' => 'success',
            'Back Office' => 'primary',
            'Lurah' => 'danger'
        ];

        $color = $colors[$this->jabatan] ?? 'secondary';
        return '<span class="badge bg-' . $color . '">' . $this->jabatan . '</span>';
    }

    public function getStatusBadgeAttribute()
    {
        $colors = [
            'Aktif' => 'success',
            'Tidak Aktif' => 'danger',
        ];

        $color = $colors[$this->status] ?? 'secondary';
        return '<span class="badge bg-' . $color . '">' . $this->status . '</span>';
    }

    public function getActiveBadgeAttribute()
    {
        return $this->is_active
            ? '<span class="badge bg-success">Aktif</span>'
            : '<span class="badge bg-secondary">Non-Aktif</span>';
    }

    public function getWilayahLengkapAttribute()
    {
        if ($this->jabatan === 'Ketua RT') {
            return 'RT ' . $this->rt . ' RW ' . $this->rw;
        } else if ($this->jabatan === 'Ketua RW') {
            return 'RW ' . $this->rw;
        } else {
            return 'Kelurahan';
        }
    }

    public function getFileInfoAttribute()
    {
        $info = [];

        if ($this->file_ttd) {
            $info[] = 'TTD';
        }

        if ($this->file_stempel) {
            $info[] = 'Stempel';
        }

        return implode(', ', $info) ?: 'Tidak ada file';
    }

    // File URL methods
    public function getTtdUrlAttribute()
    {
        return $this->file_ttd ? Storage::url($this->file_ttd) : null;
    }

    public function getStempelUrlAttribute()
    {
        return $this->file_stempel ? Storage::url($this->file_stempel) : null;
    }

    // Authorization methods
    public function canBeAccessedBy($user)
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'Operator') {
            return true;
        }

        if ($user->role === 'Front Office') {
            return true;
        }

        if ($user->role === 'Back Office') {
            return true;
        }

        if ($user->role === 'Lurah') {
            return true;
        }

        if ($user->role === 'Ketua RW') {
            return $this->rw == $user->rw;
        }

        if ($user->role === 'Ketua RT') {
            return $this->rt == $user->rt && $this->rw == $user->rw;
        }

        return false;
    }

    public function canBeEditedBy($user)
    {
        if ($user->role === 'admin') {
            return true;
        }

        // User dapat mengedit data miliknya sendiri
        // if ($this->user_id == $user->id) {
        //     return true;
        // }

        // User yang membuat data bisa edit
        if (isset($this->created_by) && $this->created_by == $user->id) {
            return true;
        }

        if ($user->role == 'Operator') {
            return true;
        }

        // Ketua RW dapat mengedit data di wilayahnya
        // if ($user->role === 'Ketua RW' && $this->rw == $user->rw) {
        //     return true;
        // }

        return false;
    }

    // Static methods
    public static function getJabatanOptions()
    {
        return [
            'Ketua RT' => 'Ketua RT',
            'Ketua RW' => 'Ketua RW',
            'Front Office' => 'Front Office',
            'Back Office' => 'Back Office',
            'Lurah' => 'Lurah'
        ];
    }

    public static function getStatusOptions()
    {
        return [
            'Aktif' => 'Aktif',
            'Tidak Aktif' => 'Tidak Aktif',
        ];
    }

    public static function getRWOptions()
    {
        return ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10'];
    }

    public static function getRTOptions()
    {
        $options = [];
        for ($i = 1; $i <= 63; $i++) {
            $options[sprintf('%02d', $i)] = sprintf('%02d', $i);
        }
        return $options;
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (is_null($model->urutan_tampil)) {
                $model->urutan_tampil = static::max('urutan_tampil') + 1;
            }
        });

        static::deleting(function ($model) {
            // Delete files when model is deleted
            if ($model->file_ttd && Storage::disk('public')->exists($model->file_ttd)) {
                Storage::disk('public')->delete($model->file_ttd);
            }

            if ($model->file_stempel && Storage::disk('public')->exists($model->file_stempel)) {
                Storage::disk('public')->delete($model->file_stempel);
            }
        });
    }
}
