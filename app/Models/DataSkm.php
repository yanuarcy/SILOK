<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataSkm extends Model
{
    use HasFactory;

    protected $table = 'data_skm';

    protected $fillable = [
        'user_id',
        'nama',
        'alamat',
        'tingkat_kepuasan',
        'kritik_saran',
        'status'
    ];

    protected $casts = [
        'tingkat_kepuasan' => 'string',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * Get the user that owns the SKM data.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk mendapatkan data dengan tingkat kepuasan positif
     */
    public function scopePositive($query)
    {
        return $query->whereIn('tingkat_kepuasan', ['Sangat Puas', 'Puas']);
    }

    /**
     * Scope untuk mendapatkan data aktif (ditampilkan di testimonial)
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope untuk mendapatkan data tidak aktif
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope untuk mendapatkan data berdasarkan sentiment
     */
    public function scopeBySentiment($query, $sentiment)
    {
        // This would require implementing sentiment analysis
        // For now, we'll use a simple approach based on keywords
        $positiveKeywords = ['bagus', 'baik', 'memuaskan', 'sangat', 'excellent', 'luar biasa', 'sempurna', 'ramah', 'cepat', 'responsif', 'profesional', 'berkualitas', 'mantap', 'terima kasih', 'puas', 'senang', 'suka', 'recommended', 'terbaik', 'top', 'oke', 'maksimal'];
        $negativeKeywords = ['buruk', 'jelek', 'lambat', 'lama', 'tidak', 'kurang', 'gagal', 'error', 'rusak', 'bermasalah', 'kecewa', 'mengecewakan', 'payah', 'tidak puas', 'tidak baik', 'tidak bagus', 'tidak memuaskan', 'perlu diperbaiki', 'harus', 'jangan', 'salah'];

        switch ($sentiment) {
            case 'positive':
                $condition = implode('|', $positiveKeywords);
                return $query->where('kritik_saran', 'REGEXP', $condition);
            case 'negative':
                $condition = implode('|', $negativeKeywords);
                return $query->where('kritik_saran', 'REGEXP', $condition);
            case 'neutral':
                $positiveCondition = implode('|', $positiveKeywords);
                $negativeCondition = implode('|', $negativeKeywords);
                return $query->where('kritik_saran', 'NOT REGEXP', $positiveCondition)
                            ->where('kritik_saran', 'NOT REGEXP', $negativeCondition);
            default:
                return $query;
        }
    }

    /**
     * Scope untuk mendapatkan data terbaru
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Accessor untuk mendapatkan badge tingkat kepuasan
     */
    public function getTingkatKepuasanBadgeAttribute()
    {
        $badges = [
            'Sangat Puas' => '<span class="badge bg-success">Sangat Puas</span>',
            'Puas' => '<span class="badge bg-primary">Puas</span>',
            'Tidak Puas' => '<span class="badge bg-warning">Tidak Puas</span>',
        ];

        return $badges[$this->tingkat_kepuasan] ?? '<span class="badge bg-secondary">-</span>';
    }

    /**
     * Accessor untuk mendapatkan badge status
     */
    public function getStatusBadgeAttribute()
    {
        return $this->status === 'active'
            ? '<span class="badge bg-success">Ditampilkan</span>'
            : '<span class="badge bg-secondary">Tidak Ditampilkan</span>';
    }

    /**
     * Accessor untuk mendapatkan kritik saran yang dipendekkan
     */
    public function getKritikSaranShortAttribute()
    {
        return \Str::limit($this->kritik_saran, 50);
    }

    /**
     * Accessor untuk mendapatkan tanggal yang diformat
     */
    public function getTanggalFormattedAttribute()
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    /**
     * Accessor untuk mendapatkan sentiment badge
     */
    public function getSentimentBadgeAttribute()
    {
        $sentiment = $this->analyzeSentiment();
        $badges = [
            'positive' => '<span class="badge bg-success"><i class="fas fa-smile"></i> Positif</span>',
            'negative' => '<span class="badge bg-danger"><i class="fas fa-frown"></i> Negatif</span>',
            'neutral' => '<span class="badge bg-warning"><i class="fas fa-meh"></i> Netral</span>',
        ];
        return $badges[$sentiment] ?? '<span class="badge bg-secondary">-</span>';
    }

    /**
     * Check if SKM is positive (Sangat Puas or Puas)
     */
    public function isPositive()
    {
        return in_array($this->tingkat_kepuasan, ['Sangat Puas', 'Puas']);
    }

    /**
     * Check if SKM is active (displayed in testimonial)
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if SKM is eligible for auto-activation
     */
    public function isEligibleForAutoActivation()
    {
        return $this->isPositive() && $this->analyzeSentiment() === 'positive';
    }

    /**
     * Analyze sentiment of kritik_saran
     */
    public function analyzeSentiment()
    {
        $text = strtolower($this->kritik_saran);

        // Positive keywords in Indonesian
        $positiveWords = [
            'bagus', 'baik', 'memuaskan', 'sangat', 'excellent', 'luar biasa', 'sempurna',
            'ramah', 'cepat', 'responsif', 'profesional', 'berkualitas', 'mantap',
            'terima kasih', 'puas', 'senang', 'suka', 'recommended', 'terbaik',
            'pelayanan baik', 'top', 'oke', 'bagus sekali', 'maksimal'
        ];

        // Negative keywords in Indonesian
        $negativeWords = [
            'buruk', 'jelek', 'lambat', 'lama', 'tidak', 'kurang', 'gagal',
            'error', 'rusak', 'bermasalah', 'kecewa', 'mengecewakan', 'payah',
            'tidak puas', 'tidak baik', 'tidak bagus', 'tidak memuaskan',
            'perlu diperbaiki', 'harus', 'jangan', 'salah'
        ];

        $positiveScore = 0;
        $negativeScore = 0;

        foreach ($positiveWords as $word) {
            if (strpos($text, $word) !== false) {
                $positiveScore++;
            }
        }

        foreach ($negativeWords as $word) {
            if (strpos($text, $word) !== false) {
                $negativeScore++;
            }
        }

        if ($positiveScore > $negativeScore) {
            return 'positive';
        } elseif ($negativeScore > $positiveScore) {
            return 'negative';
        } else {
            return 'neutral';
        }
    }

    /**
     * Get satisfaction score (1-4 scale)
     */
    public function getSatisfactionScore()
    {
        $scores = [
            'Tidak Puas' => 1,
            'Puas' => 3,
            'Sangat Puas' => 4
        ];

        return $scores[$this->tingkat_kepuasan] ?? 0;
    }

    /**
     * Scope untuk testimonial aktif
     */
    public function scopeActiveTestimonials($query)
    {
        return $query->where('status', 'active')
                    ->whereIn('tingkat_kepuasan', ['Sangat Puas', 'Puas'])
                    ->orderBy('created_at', 'desc');
    }

     /**
     * Scope untuk testimonial dengan sentimen positif
     */
    public function scopePositiveTestimonials($query)
    {
        return $query->activeTestimonials()
                    ->where(function($q) {
                        $q->where('kritik_saran', 'like', '%bagus%')
                          ->orWhere('kritik_saran', 'like', '%baik%')
                          ->orWhere('kritik_saran', 'like', '%memuaskan%')
                          ->orWhere('kritik_saran', 'like', '%senang%')
                          ->orWhere('kritik_saran', 'like', '%puas%')
                          ->orWhere('kritik_saran', 'like', '%terima kasih%')
                          ->orWhere('kritik_saran', 'like', '%ramah%')
                          ->orWhere('kritik_saran', 'like', '%cepat%')
                          ->orWhere('kritik_saran', 'like', '%excellent%')
                          ->orWhere('kritik_saran', 'like', '%mantap%');
                    });
    }

    /**
     * Get avatar berdasarkan nama
     */
    public function getAvatarAttribute()
    {
        $avatars = [
            'testimonial-1.jpg',
            'testimonial-2.jpg'
        ];

        $index = abs(crc32($this->nama)) % count($avatars);
        return $avatars[$index];
    }

    /**
     * Get formatted date
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d M Y');
    }

    /**
     * Get short testimonial
     */
    public function getShortTestimonialAttribute()
    {
        return \Str::limit($this->kritik_saran, 150);
    }

    /**
     * Boot method untuk auto-activate eligible surveys
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            // Auto-activate if eligible and under limit
            if ($model->isEligibleForAutoActivation()) {
                $activeCount = static::where('status', 'active')->count();
                if ($activeCount < 10) {
                    $model->update(['status' => 'active']);
                }
            }
        });
    }
}
