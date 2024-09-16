<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedItem extends Model
{
    use HasFactory;

    const SUBSCRIPTION = "SUBSCRIPTION";
    const RECOMMENDATION = "RECOMMENDATION";

    # done: If set, the item is considered done, i.e., it won't be notified anymore.
    #       This should correspond with either notified or removed being set.
    #       We'll use this additional flag to have a more efficient index (instead
    #       of having one on `notified` and `removed`).
    protected $fillable = ['user_id','request_id','type','notified', 'removed', 'done'];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function User(){
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function UserRequest(){
        return $this->belongsTo('App\Models\UserRequest','request_id');
    }

}
