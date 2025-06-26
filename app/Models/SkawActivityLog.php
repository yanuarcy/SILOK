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

class SkawActivityLog extends Model
{
    use HasFactory;

    protected $table = 'skaw_activity_logs';

    protected $fillable = [
        'skaw_id',
        'user_id',
        'action',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function skaw(): BelongsTo
    {
        return $this->belongsTo(Skaw::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
