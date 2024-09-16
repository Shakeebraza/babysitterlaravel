<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    use HasFactory;

    protected $fillable = ['group_id','member_email','member_id','status'];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function Member(){
        return $this->belongsTo('App\Models\User','member_id');
    }

    public function Group(){
        return $this->belongsTo('App\Models\Group','group_id');
    }
}
