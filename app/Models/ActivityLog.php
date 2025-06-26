<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'subject_type',
        'subject_id',
        'description',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that performed the activity
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the subject model (polymorphic relationship)
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope to get activities for a specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get activities by action
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope to get recent activities
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Get the icon for the activity based on action
     */
    public function getIconAttribute()
    {
        $icons = [
            'login' => 'fas fa-sign-in-alt',
            'logout' => 'fas fa-sign-out-alt',
            'create' => 'fas fa-plus-circle',
            'update' => 'fas fa-edit',
            'delete' => 'fas fa-trash-alt',
            'approve' => 'fas fa-check-circle',
            'reject' => 'fas fa-times-circle',
            'auto_approve' => 'fas fa-check-double',
            'view' => 'fas fa-eye',
            'download' => 'fas fa-download',
            'upload' => 'fas fa-upload',
            'profile_update' => 'fas fa-user-edit',
            'password_change' => 'fas fa-key',
            'submission' => 'fas fa-paper-plane',
            'comment' => 'fas fa-comment-alt',
        ];

        return $icons[$this->action] ?? 'fas fa-circle';
    }

    /**
     * Get the color class for the activity based on action
     * PERBAIKAN: Tambahkan return statement
     */
    public function getColorAttribute()
    {
        $colors = [
            'login' => 'bg-success',
            'logout' => 'bg-warning',
            'create' => 'bg-primary',
            'update' => 'bg-info',
            'delete' => 'bg-danger',
            'approve' => 'bg-success',
            'reject' => 'bg-danger',
            'auto_approve' => 'bg-info',
            'view' => 'bg-secondary',
            'download' => 'bg-info',
            'upload' => 'bg-primary',
            'profile_update' => 'bg-info',
            'password_change' => 'bg-warning',
            'submission' => 'bg-primary',
            'comment' => 'bg-secondary',
        ];

        // PERBAIKAN: Return nilai yang sesuai
        return $colors[$this->action] ?? 'bg-primary';
    }

    /**
     * Get link to the subject if applicable
     */
    public function getSubjectLink()
    {
        if (!$this->subject_type || !$this->subject_id) {
            return null;
        }

        try {
            $routes = [
                'App\Models\UserApplication' => 'user-applications.show',
                'App\Models\User' => 'users.show',
                'App\Models\Puntadewa' => 'puntadewa.show',
                'App\Models\SuratPengantar' => 'surat-pengantar.show',
                'App\Models\Psu' => 'psu.show',
                'App\Models\DataKependudukan' => 'data-kependudukan.show',
                'App\Models\Spesimen' => 'spesimen.show'
            ];

            $routeName = $routes[$this->subject_type] ?? null;

            if ($routeName && \Route::has($routeName)) {
                return route($routeName, $this->subject_id);
            }
        } catch (\Exception $e) {
            \Log::error('Error generating subject link: ' . $e->getMessage());
            return null;
        }

        return null;
    }

    /**
     * Get human readable time difference
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Static method to log activity
     */
    public static function log($action, $description, $subject = null, $properties = null)
    {
        $userId = auth()->id();

        if (!$userId) {
            return null;
        }

        $data = [
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];

        if ($subject) {
            $data['subject_type'] = get_class($subject);
            $data['subject_id'] = $subject->id;
        }

        return static::create($data);
    }
}
