<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Loket extends Model
{
    use HasFactory;

    protected $table = 'lokets';

    protected $fillable = [
        'user_id',
        'loket_number',
        'call_status',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'call_status' => 'standby',
        'is_active' => true,
    ];

    /**
     * Get the user that owns the loket.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the session data for this loket's user.
     */
    public function session()
    {
        return $this->hasOneThrough(
            Session::class,
            User::class,
            'id',        // Foreign key on users table...
            'user_id',   // Foreign key on sessions table...
            'user_id',   // Local key on lokets table...
            'id'         // Local key on users table...
        );
    }

    /**
     * Get formatted last activity time.
     */
    public function getLastActivityAttribute()
    {
        $session = $this->session()->latest('last_activity')->first();

        if (!$session || !$session->last_activity) {
            return null;
        }

        // return Carbon::parse($session->last_activity)->format('d M Y H:i');
        return Carbon::parse($session->last_activity)
        ->setTimezone('Asia/Jakarta')
        ->format('d M Y H:i');
    }

    /**
     * Check if user is currently online.
     */
    public function isOnline()
    {
        $session = $this->session()->latest('last_activity')->first();

        if (!$session || !$session->last_activity) {
            return false;
        }

        return Carbon::parse($session->last_activity)->diffInMinutes(now()) < 5;
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // static::updating(function ($loket) {
        //     if ($loket->isOnline()) {
        //         $loket->status = 'online';
        //     } else {
        //         $loket->status = 'offline';
        //     }
        // });
    }
}
