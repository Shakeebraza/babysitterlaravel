<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','document_name','document'];

    protected $hidden = ['created_at','updated_at'];

    const APPROVED1 = 2;
    const APPROVED2 = 3;

    public static $approvedType = [
        self::APPROVED1 => 'Approved',
        self::APPROVED2 => 'Rejected',
    ];
}
