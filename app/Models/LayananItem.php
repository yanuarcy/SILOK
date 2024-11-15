<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LayananItem extends Model
{
    protected $fillable = [
        'sub_layanan_id', 'slug', 'title', 'image'
    ];

    public function subLayanan()
    {
        return $this->belongsTo(SubLayanan::class);
    }

    public function registrationOptions()
    {
        return $this->morphMany(RegistrationOption::class, 'registrationable');
    }
}
