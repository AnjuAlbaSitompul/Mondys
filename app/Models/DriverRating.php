<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverRating extends Model
{
    protected $fillable = [
        'driver_id',
        'rating',
        'feedback'
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}
