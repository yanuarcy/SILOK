<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Antarmuka extends Model
{
    use HasFactory;

    protected $table = 'antarmuka';
    protected $primaryKey = 'id_antarmuka';

    protected $fillable = [
        'keterangan',
        'nama',
        'durasi_video',
        'sumber',
        'volume',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'durasi_video' => 'integer',
        'volume' => 'integer'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 0);
    }

    // Methods
    public function activate()
    {
        // Deactivate all other videos
        self::where('id_antarmuka', '!=', $this->id_antarmuka)
            ->update(['status' => 0]);

        // Activate this video
        $this->update(['status' => 1]);
    }

    public function deactivate()
    {
        $this->update(['status' => 0]);
    }

    /**
     * Get video URL based on source type
     */
    public function getVideoUrlAttribute()
    {
        if ($this->sumber === 'upload' && $this->video_path) {
            return asset('storage/' . $this->video_path);
        }

        return $this->attributes['video_url'] ?? null;
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->durasi_video) {
            return null;
        }

        $minutes = floor($this->durasi_video / 60);
        $seconds = $this->durasi_video % 60;

        return $minutes > 0 ? "{$minutes}m {$seconds}s" : "{$seconds}s";
    }

    /**
     * Get status badge
     */
    public function getStatusBadgeAttribute()
    {
        return $this->status ?
            '<span class="badge badge-success">Aktif</span>' :
            '<span class="badge badge-secondary">Tidak Aktif</span>';
    }

    /**
     * Get source badge
     */
    public function getSourceBadgeAttribute()
    {
        $badges = [
            'upload' => '<span class="badge badge-primary">Upload</span>',
            'youtube' => '<span class="badge badge-danger">YouTube</span>',
            'vimeo' => '<span class="badge badge-info">Vimeo</span>',
            'url' => '<span class="badge badge-warning">URL</span>'
        ];

        return $badges[$this->sumber] ?? '<span class="badge badge-secondary">Unknown</span>';
    }

    /**
     * Get formatted volume display
     */
    public function getFormattedVolumeAttribute()
    {
        return $this->volume . '%';
    }

    public function getYouTubeId()
    {
        // Extract YouTube video ID from various URL formats
        preg_match('/(?:youtube\.com\/embed\/|youtu\.be\/|youtube\.com\/watch\?v=)([^&\n?#]+)/', $this->sumber, $matches);
        return $matches[1] ?? null;
    }

    public function getEmbedUrl()
    {
        $videoId = $this->getYouTubeId();
        if ($videoId) {
            return "https://www.youtube.com/embed/{$videoId}";
        }
        return $this->sumber;
    }

    public static function getActiveVideo()
    {
        return static::active()->first();
    }
}
