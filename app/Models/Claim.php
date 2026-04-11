<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $fillable = [
        'barang_id',
        'claimed_by',
        'desc'
    ];

    // relation ke barang
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    // relation ke user (yang claim)
    public function user()
    {
        return $this->belongsTo(User::class, 'claimed_by');
    }
}
