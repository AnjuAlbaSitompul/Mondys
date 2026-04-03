<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoadingDetail extends Model
{
    protected $fillable = [
        'loading_id',
        'barang_id'
    ];

    public function loading()
    {
        return $this->belongsTo(Loading::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
