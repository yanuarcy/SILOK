<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $table = 'layanan';

    protected $fillable = [
        'slug', 'kode_layanan', 'title', 'image', 'description', 'small', 'has_sub_layanan'
    ];

    protected $casts = [
        'has_sub_layanan' => 'boolean'
    ];

    public function subLayanans()
    {
        return $this->hasMany(SubLayanan::class);
    }

    public function registrationOptions()
    {
        return $this->morphMany(RegistrationOption::class, 'registrationable');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
