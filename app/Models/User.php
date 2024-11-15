<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;
use Carbon\CarbonInterval;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'username',
        'telp',
        'email_verified_at',
        'password',
        'role',
    ];

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function getLoggedInDuration()
    {
        $session = Session::where('user_id', $this->id)
                        ->latest('login_at')
                        ->first();

        if ($session && $session->login_at) {
            $duration = Carbon::parse($session->login_at)->diffInSeconds(now());
            return CarbonInterval::seconds($duration)->cascade()->forHumans(['parts' => 1, 'short' => true]);
        }

        return 'just now';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'created_at' => 'datetime'
        ];
    }
}
