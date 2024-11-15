<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Session extends Model
{
    protected $table = 'sessions';
    protected $guarded = [];
    protected $fillable = [
        'id', 'user_id', 'ip_address', 'user_agent', 'payload', 'login_at', 'last_activity'
    ];
    public $incrementing = false;
    protected $keyType = 'string';

    protected $casts = [
        'last_activity' => 'integer',
        'login_at' => 'datetime',
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    protected $dates = ['last_activity', 'login_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Mutator untuk menyimpan last_activity sebagai integer timestamp
    public function setLastActivityAttribute($value)
    {
        $this->attributes['last_activity'] = $value instanceof Carbon ? $value->timestamp : $value;
    }

    // Accessor untuk mengubah integer timestamp menjadi Carbon instance (opsional)
    public function getLastActivityAttribute($value)
    {
        return Carbon::createFromTimestamp($value);
    }
}
