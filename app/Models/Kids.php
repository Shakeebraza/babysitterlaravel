<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kids extends Model
{
    use HasFactory, SoftDeletes;

    public $appends = ['month','year','day'];
    protected $fillable = ['user_id','name','date_of_birth','status'];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public function getMonthAttribute(): string{
        $birthdate = Carbon::parse($this->date_of_birth);
        $currentDate = Carbon::now();
        $age = $birthdate->diff($currentDate);
        $months = $age->format('%m');
        $kidsMonth = $months . ' ' . trans_choice('messages.month', $months);
        return $kidsMonth;
    }
    public function getYearAttribute(): string{
        $birthdate = Carbon::parse($this->date_of_birth);
        $currentDate = Carbon::now();
        $age = $birthdate->diff($currentDate);
        $years = $age->format('%y');
        $kidsYear = '';
        if($years > 0){
            $kidsYear = $years . ' ' . trans_choice('messages.year', $years);
        }
        return $kidsYear;
    }
    public function getDayAttribute(): string{
        $birthdate = Carbon::parse($this->date_of_birth);
        $currentDate = Carbon::now();
        $age = $birthdate->diff($currentDate);
        $days = $age->format('%a');
        $kidsDay = '';
        if($days > 0){
            $kidsDay = $age->format('%a');
        }
        return $kidsDay;
    }
}
