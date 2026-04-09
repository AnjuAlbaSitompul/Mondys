<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoadingDetail extends Model
{
    protected $fillable = [
        'loading_id',
        'boarding_list_id',
        'koli',
        'box'
    ];

    public function loading()
    {
        return $this->belongsTo(Loading::class);
    }

    public function boardingList()
    {
        return $this->belongsTo(BoardingList::class);
    }
}
