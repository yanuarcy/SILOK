<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GeneralSetting extends Model
{
    use HasFactory;

    protected $table = 'generalsetting';

    protected $fillable = [
        'site_title',
        'organization_name',
        'organization_address',
        'site_name',
        'site_logo',
        'site_favicon'
    ];

    /**
     * Get first setting record or create if not exists
     */
    public static function getSettings()
    {
        $setting = self::first();

        if (!$setting) {
            $setting = self::create([
                'site_title' => 'SILOK - Sistem Informasi Kelurahan',
                'organization_name' => 'Kelurahan Jemurwonosari',
                'organization_address' => 'Jl. Jemursari VIII No. 49, Wonocolo, Surabaya, Jawa Timur 60237',
                'site_name' => 'SILOK'
            ]);
        }

        return $setting;
    }
}
