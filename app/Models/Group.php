<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id','name','status'];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public function GroupMembers(){
        return $this->hasMany('App\Models\GroupMember','group_id');
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function($group) {
            $group->GroupMembers->each(function($groupMember) {
                $groupMember->delete();
            });
        });
    }
}
