<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','device_type','device_token','device_key','app_version','last_home_call'];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
