<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    /** @use HasFactory<\Database\Factories\BarangFactory> */
    use HasFactory;

    protected $fillable = [
        'status',
        'type',
        'sjcode',
        'id_outlet',
        'boxqty',
        'desc'
    ];
    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'id_outlet', 'codeOutlet');
    }

    public function pickList()
    {
        return $this->hasOne(PickList::class);
    }
}
