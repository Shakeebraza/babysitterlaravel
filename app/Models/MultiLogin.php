<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultiLogin extends Model
{
    use HasFactory;

    protected $fillable = ['social_type','user_id','session_token','last_login'];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
