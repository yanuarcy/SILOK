<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantType extends Model
{
    protected $fillable = [
        'registration_option_id',
        'type',
        'title',
        'image'
    ];

    /**
     * Get the registration option that owns the applicant type.
     */
    public function registrationOption()
    {
        return $this->belongsTo(RegistrationOption::class);
    }
}
