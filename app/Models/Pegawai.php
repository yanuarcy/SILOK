<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';

    protected $fillable = [
        'user_id',
        'jabatan',
        'media_sosial',
        'is_active',
        'urutan_tampil'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'media_sosial' => 'array'
    ];

    /**
     * Relationship dengan User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Scope untuk pegawai aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk mengurutkan berdasarkan urutan tampil
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan_tampil', 'asc')->orderBy('jabatan', 'asc');
    }

    /**
     * Accessor untuk mendapatkan nama lengkap dengan gelar
     */
    public function getNamaLengkapAttribute()
    {
        return $this->user ? $this->user->name : '-';
    }

    /**
     * Accessor untuk mendapatkan foto pegawai
     */
    public function getFotoAttribute()
    {
        if ($this->user && $this->user->image) {
            // Cek jika file exists di public path
            if (file_exists(public_path('storage/images/pegawai/' . $this->user->image))) {
                return asset('storage/images/pegawai/' . $this->user->image);
            } elseif (file_exists(public_path('images/pegawai/' . $this->user->image))) {
                return asset('images/pegawai/' . $this->user->image);
            }
        }
        return asset('images/default-avatar.png');
    }

    /**
     * Get media sosial dengan icon
     */
    public function getMediaSosialLinksAttribute()
    {
        $mediaSosial = $this->media_sosial ?? [];
        $links = [];

        foreach ($mediaSosial as $media) {
            $icon = '';
            $platform = strtolower($media['platform'] ?? '');

            switch ($platform) {
                case 'facebook':
                    $icon = 'fab fa-facebook-f';
                    break;
                case 'twitter':
                    $icon = 'fab fa-twitter';
                    break;
                case 'instagram':
                    $icon = 'fab fa-instagram';
                    break;
                case 'linkedin':
                    $icon = 'fab fa-linkedin-in';
                    break;
                case 'youtube':
                    $icon = 'fab fa-youtube';
                    break;
                case 'whatsapp':
                    $icon = 'fab fa-whatsapp';
                    break;
                default:
                    $icon = 'fas fa-link';
            }

            $links[] = [
                'platform' => $media['platform'] ?? '',
                'url' => $media['url'] ?? '',
                'icon' => $icon
            ];
        }

        return $links;
    }

    /**
     * Accessor untuk status aktif dengan badge
     */
    public function getStatusAktifBadgeAttribute()
    {
        return $this->is_active
            ? '<span class="badge badge-success">Aktif</span>'
            : '<span class="badge badge-danger">Tidak Aktif</span>';
    }

    /**
     * Method untuk mendapatkan hierarki jabatan
     */
    public static function getHierarki()
    {
        return [
            'Lurah' => 1,
            'Sekretaris Kelurahan' => 2,
            'Seksi Pemerintahan dan Pelayanan Publik' => 3,
            'Seksi Kesejahteraan Rakyat dan Perekonomian' => 4,
            'Seksi Ketentraman, Ketertiban dan Pembangunan' => 5,
            'Staff' => 6,
            'Tenaga Kontrak / OS' => 7,
        ];
    }
}
