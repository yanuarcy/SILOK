<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubLayanan extends Model
{
    protected $table = 'sub_layanan';

    protected $fillable = [
        'layanan_id', 'slug', 'title', 'image', 'has_items'
    ];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }

    public function items()
    {
        return $this->hasMany(LayananItem::class);
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
