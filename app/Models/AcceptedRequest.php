<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcceptedRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id','user_id','request_status','status','awarded_by','description', 'payment_type','amount'
    ];

    protected $hidden = [
        'updated_at'
    ];

    const FREE = 1;
    const AMOUNT = 2;

    public function User(){
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function UserRequest(){
        return $this->belongsTo('App\Models\UserRequest','request_id');
    }
}
