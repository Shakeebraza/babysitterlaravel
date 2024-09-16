<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentMail extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','email_type','message_name', 'language'];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function User(){
        return $this->belongsTo('App\Models\User','user_id');
    }

}
