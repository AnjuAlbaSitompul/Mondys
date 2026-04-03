<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loading extends Model
{
    protected $fillable = [
        'surat_jalan',
        'driver_id',
        'co_driver_id',
        'outlet_id',
        'loading_start',
        'loading_end',
        'created_by',
        'updated_by',
    ];

    // relasi
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function details()
    {
        return $this->hasMany(LoadingDetail::class);
    }

    // kalau mau langsung ke barang
    public function barangs()
    {
        return $this->belongsToMany(Barang::class, 'loading_details');
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function coDriver()
    {
        return $this->belongsTo(User::class, 'co_driver_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
