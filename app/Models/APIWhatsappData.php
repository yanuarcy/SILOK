<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class APIWhatsappData extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'whatsapp_api_owners';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'whatsapp_number',
        'token',
        'status',
        'quota',
        'subscription_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'subscription_date' => 'date',
        'quota' => 'integer',
    ];

    /**
     * Get the formatted status for display.
     *
     * @return string
     */
    public function getStatusBadgeAttribute()
    {
        return $this->status === 'active'
            ? '<span class="badge bg-success">Active</span>'
            : '<span class="badge bg-danger">Inactive</span>';
    }

    /**
     * Get the formatted subscription date for display.
     *
     * @return string
     */
    public function getSubscriptionDateFormattedAttribute()
    {
        return $this->subscription_date ? $this->subscription_date->format('d M Y') : '-';
    }
}
