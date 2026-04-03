<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    /** @use HasFactory<\Database\Factories\BarangFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'sjcode',
        'id_outlet',
        'updated_by',
        'jenis_barang_id',
        'type',
        'nama_barang'
    ];
    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'id_outlet', 'codeOutlet');
    }

    public function pickList()
    {
        return $this->hasOne(PickList::class);
    }

    public function jenisBarang()
    {
        return $this->belongsTo(JenisBarang::class, 'jenis_barang_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function boarding()
    {
        return $this->hasOne(BoardingList::class);
    }
}
