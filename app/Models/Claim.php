<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $fillable = [
        'barang_id',
        'claimed_by',
        'desc',
        'approved',
        'approved_by',
        'approved_at'
    ];

    // relation ke barang
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    // relation ke user (yang claim)
    public function creator()
    {
        return $this->belongsTo(User::class, 'claimed_by');
    }

    // relation ke user (yang mengapprove)
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
