<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivering extends Model
{
    protected $fillable = [
        'loading_id',
        'start_at',
        'clock_in',
        'clock_out',
        'driver_id'
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',

    ];

    // RELATION
    public function loading()
    {
        return $this->belongsTo(Loading::class);
    }
}
