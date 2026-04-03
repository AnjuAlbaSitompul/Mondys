<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardingList extends Model
{
    protected $fillable = [
        'barang_id',
        'code_boarding',
        'qty',
        'koli',
        'created_by',
        'updated_by',
        'outlet_id',
        'boarding_start',
        'boarding_end'
    ];

    // Relasi ke Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    // Relasi ke User
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke Outlet
    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }
}
