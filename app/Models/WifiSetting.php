<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WifiSetting extends Model
{
    protected $fillable = ['ssid', 'password'];
    public $timestamps = false;
}
