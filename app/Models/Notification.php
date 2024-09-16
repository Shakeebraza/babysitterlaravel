<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['sender','receiver','event','request_id','title','notification','is_read'];

    protected $hidden = ['created_at','updated_at'];



}
