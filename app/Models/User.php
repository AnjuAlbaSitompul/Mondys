<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'role',
        'password',
        'location_id',
        'outlet_id',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
        ];
    }
    public function pickLists()
    {
        return $this->hasMany(PickList::class, 'picker_id');
    }
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    public function claims()
    {
        return $this->hasMany(Claim::class, 'claimed_by');
    }

    public function approvedClaims()
    {
        return $this->hasMany(Claim::class, 'approved_by');
    }

    public function deliveries()
    {
        return $this->hasMany(Delivering::class, 'driver_id');
    }

    public function ratings()
    {
        return $this->hasMany(DriverRating::class, 'driver_id');
    }
}
