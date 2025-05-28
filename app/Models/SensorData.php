<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class SensorData extends Model
{
    protected $fillable = [
        'kelembapan_tanah', 'suhu', 'gas_karbon', 'gas_metana',
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}
}

