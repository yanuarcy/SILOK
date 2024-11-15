<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\MorphTo;
// use Illuminate\Database\Eloquent\Relations\HasMany;

class RegistrationOption extends Model
{
    protected $fillable = [
        'type',
        'title',
        'image'
    ];

    /**
     * Get the parent registrationable model (Layanan or SubLayanan).
     */
    public function registrationable()
    {
        return $this->morphTo();
    }

    /**
     * Get the applicant types for the registration option.
     */
    public function applicantTypes()
    {
        return $this->hasMany(ApplicantType::class);
    }
}
