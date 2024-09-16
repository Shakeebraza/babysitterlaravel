<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRequestKids extends Model
{
    use HasFactory;

    protected $fillable = ['request_id','kids_id'];

    protected $hidden = ['created_at','updated_at'];

    public function Kids(){
        return $this->belongsTo('App\Models\Kids','kids_id');
    }
}
