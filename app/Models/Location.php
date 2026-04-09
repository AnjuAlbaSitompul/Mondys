<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'name',
        'address',
        'is_active'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
