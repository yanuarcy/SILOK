<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'meeting_date',
        'meeting_time',
        'meet_link',
        'participants',
        'description',
        'status',
        'started_at',
        'ended_at'
    ];

    protected $casts = [
        'meeting_date' => 'date',
        'meeting_time' => 'datetime:H:i',
        'started_at' => 'datetime',
        'ended_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['scheduled', 'active']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed')
                    ->latest('meeting_date')
                    ->latest('meeting_time');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    // Accessors
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'active' => 'Meeting Berlangsung',
            'scheduled' => 'Terjadwal',
            'completed' => 'Selesai',
            default => 'Tidak Diketahui'
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'active' => 'status-active',
            'scheduled' => 'status-scheduled',
            'completed' => 'status-completed',
            default => 'status-scheduled'
        };
    }

    public function getParticipantsStringAttribute(): string
    {
        if (empty($this->participants)) {
            return '0 peserta';
        }

        // Split by comma and count
        $participantArray = array_filter(explode(',', $this->participants));
        $count = count($participantArray);
        return $count . ' peserta';
    }

    // Helper method to get participants as array
    public function getParticipantsArrayAttribute(): array
    {
        if (empty($this->participants)) {
            return [];
        }

        return array_filter(array_map('trim', explode(',', $this->participants)));
    }

    /**
     * FIXED: Check if meeting can be joined (10 minutes before scheduled time)
     */
    public function getShouldStartAttribute(): bool
    {
        $now = Carbon::now();

        // Combine meeting date and time
        $meetingDateTime = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $this->meeting_date->format('Y-m-d') . ' ' . $this->meeting_time->format('H:i:s')
        );

        // Meeting bisa dimulai 10 menit sebelum jadwal
        $tenMinutesBefore = $meetingDateTime->copy()->subMinutes(10);

        // Return true if:
        // 1. Current time is >= 10 minutes before meeting time
        // 2. Current time is <= meeting time + 2 hours (meeting window)
        $meetingEnd = $meetingDateTime->copy()->addMinutes(2);

        return $now->greaterThanOrEqualTo($tenMinutesBefore) &&
               $now->lessThanOrEqualTo($meetingEnd);
    }

    /**
     * Check if meeting is currently active
     */
    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get time remaining until meeting can be joined
     */
    public function getTimeUntilJoinableAttribute(): ?string
    {
        if ($this->should_start) {
            return null;
        }

        $now = Carbon::now();
        $meetingDateTime = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $this->meeting_date->format('Y-m-d') . ' ' . $this->meeting_time->format('H:i:s')
        );

        $tenMinutesBefore = $meetingDateTime->copy()->subMinutes(10);

        if ($now->lessThan($tenMinutesBefore)) {
            return $now->diffForHumans($tenMinutesBefore, true);
        }

        return null;
    }

    /**
     * Get meeting status with time consideration
     */
    public function getCurrentStatusAttribute(): string
    {
        $now = Carbon::now();
        $meetingDateTime = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $this->meeting_date->format('Y-m-d') . ' ' . $this->meeting_time->format('H:i:s')
        );

        if ($this->status === 'completed') {
            return 'Selesai';
        }

        if ($this->status === 'active') {
            return 'Sedang Berlangsung';
        }

        // For scheduled meetings, check time
        $tenMinutesBefore = $meetingDateTime->copy()->subMinutes(10);
        $twoHoursAfter = $meetingDateTime->copy()->addMinutes(2);

        if ($now->lessThan($tenMinutesBefore)) {
            return 'Belum Dimulai';
        } elseif ($now->between($tenMinutesBefore, $meetingDateTime)) {
            return 'Siap Dimulai';
        } elseif ($now->between($meetingDateTime, $twoHoursAfter)) {
            return 'Sedang Berlangsung';
        } else {
            return 'Terlewat';
        }
    }

    // Methods
    public function updateStatus(): void
    {
        $now = Carbon::now();
        $meetingDateTime = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $this->meeting_date->format('Y-m-d') . ' ' . $this->meeting_time->format('H:i:s')
        );

        // Auto-complete meetings yang sudah lewat 45 menit setelah jadwal
        $fortyFiveMinutesAfter = $meetingDateTime->copy()->addMinutes(45);
        if (($this->status === 'active' || $this->status === 'scheduled') && $now->greaterThan($fortyFiveMinutesAfter)) {
            $this->update([
                'status' => 'completed',
                'ended_at' => $fortyFiveMinutesAfter
            ]);
            return;
        }

        // Auto-start meetings yang sudah waktunya (tepat pada jadwal)
        if ($this->status === 'scheduled' && $now->greaterThanOrEqualTo($meetingDateTime)) {
            $this->update([
                'status' => 'active',
                'started_at' => $meetingDateTime
            ]);
            return;
        }
    }

    /**
     * Check if meeting is within joining window
     */
    public function isJoinable(): bool
    {
        // Kalau meeting sudah selesai, langsung false
        if ($this->status === 'completed') {
            return false;
        }

        // Validasi waktu mulai
        if (!$this->should_start) {
            return false;
        }

        // Validasi berdasarkan peserta dan user yang login
        $user = Auth::user();

        // Pecah peserta dari string ke array
        $participants = explode(',', $this->participants); // contoh: ['RW 01', 'RT 02', 'RT 03']

        // Cek berdasarkan role user
        if ($user->role === 'Operator' || $user->role === 'Back Office' || $user->role === 'Front Office' || $user->role === 'Lurah' || $user->role === 'admin' && in_array('Perangkat Kelurahan', $participants)) {
            return true;
        }

        if ($user->role === 'Ketua RW' && in_array('RW ' . $user->rw, $participants)) {
            return true;
        }

        if ($user->role === 'Ketua RT' && in_array('RT ' . $user->rt, $participants)) {
            return true;
        }

        // Jika tidak cocok
        return false;
    }

    /**
     * Get formatted meeting datetime
     */
    public function getMeetingDateTimeAttribute(): Carbon
    {
        return Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $this->meeting_date->format('Y-m-d') . ' ' . $this->meeting_time->format('H:i:s')
        );
    }
}
