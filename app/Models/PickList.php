<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickList extends Model
{
    protected $fillable = [
        'barang_id',
        'picker_id',
        'status',
        'started_at',
        'finished_at',
        'created_by',
        'ended_by'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function picker()
    {
        return $this->belongsTo(User::class, 'picker_id', 'id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function ender()
    {
        return $this->belongsTo(User::class, 'ended_by');
    }
}
