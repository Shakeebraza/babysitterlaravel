<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRequestGroup extends Model
{
    use HasFactory;

    protected $fillable = ['request_id','group_id'];

    protected $hidden = ['created_at','updated_at'];

    public function Groups(){
        return $this->belongsTo('App\Models\Group','group_id');
    }
}
