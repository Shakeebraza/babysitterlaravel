<?php

namespace App\Models;

use App\Models\Enums\AcceptedRequestStatus;
use App\Models\Enums\RequestStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRequest extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','title','description','from_date','to_date','max_amount', 'group_visibility', 'public_visibility',
        'address_type','address','street','zip','city','country_code','latitude','longitude','status', 'awarded'];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'group_visibility' => 'boolean',
        'public_visibility' => 'boolean',
    ];

    public function setGroupVisibilityAttribute($value)
    {
        $booleanValue = filter_var($value, FILTER_VALIDATE_BOOLEAN);
        $this->attributes['group_visibility'] = $booleanValue ? 1 : 0;
    }

    public function setPublicVisibilityAttribute($value)
    {
        $booleanValue = filter_var($value, FILTER_VALIDATE_BOOLEAN);
        $this->attributes['public_visibility'] = $booleanValue ? 1 : 0;
    }

    const ADDRESSTYPE1 = 'home';
    const ADDRESSTYPE2 = 'other';

    public static $addressType = [
        self::ADDRESSTYPE1 => 'Home',
        self::ADDRESSTYPE2 => 'Other',
    ];

    public function getMaxApplies()
    {
        return $this->Groups()
            ->with('GroupMembers')
            ->get()
            ->pluck('GroupMembers')
            ->flatten()
            ->pluck('member_id')
            ->filter(function ($value) {
                return !is_null($value);
            })
            ->unique()
            ->count();
    }

    public function dismissedRequestsFromGroupMembers()
    {
        $groupMemberIds = $this->Groups()
            ->with('GroupMembers')
            ->get()
            ->pluck('GroupMembers')
            ->flatten()
            ->pluck('member_id')
            ->filter(function ($value) {
                return !is_null($value);
            })
            ->unique()
            ->toArray();

        return $this->hasMany('App\Models\AcceptedRequest', 'request_id')
            ->where('request_status', RequestStatus::DISMISS->value)
            ->whereIn('user_id', $groupMemberIds);
    }

    public function duration(){
        $fromDate = Carbon::parse($this->from_date);
        $toDate = Carbon::parse($this->to_date);

        $diffDays = $fromDate->diffInDays($toDate);
        $diffHrs = $fromDate->diffInHours($toDate) % 24;

        $daysTranslation = trans_choice('messages.day', $diffDays);
        $hrsTranslation = trans_choice('messages.hour', $diffHrs);

        if ($diffDays > 0 && $diffHrs > 0) {
            return "$diffDays $daysTranslation $diffHrs $hrsTranslation";
        } elseif ($diffDays == 0) {
            return "$diffHrs $hrsTranslation";
        } elseif ($diffDays > 0 && $diffHrs == 0) {
            return "$diffDays $daysTranslation";
        }
    }

    public function AcceptedRequest(){
        return $this->hasMany('App\Models\AcceptedRequest','request_id')->where('request_status', RequestStatus::APPLIED->value);
    }

    public function AllAcceptedRequest(){
        return $this->hasMany('App\Models\AcceptedRequest','request_id');
    }

    public function ConfirmedRequest(){
        return $this->hasMany('App\Models\AcceptedRequest','request_id')->whereIn('status',[AcceptedRequestStatus::REWARDED->value]);
    }

    public function User(){
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function RequestGroups(){
        return $this->hasMany('App\Models\UserRequestGroup','request_id');
    }

    public function Groups(){
        return $this->belongsToMany(Group::class,'user_request_groups', 'request_id', 'group_id');
    }

    public function RequestKids(){
        return $this->hasMany('App\Models\UserRequestKids','request_id');
    }

    public function Kids(){
        return $this->belongsToMany(Kids::class,'user_request_kids', 'request_id', 'kids_id');
    }

    public function RequestNotifications(){
        return $this->hasMany('App\Models\Notification','request_id');
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function($request) {
            $request->AllAcceptedRequest->each(function($acceptedRequest) {
                $acceptedRequest->delete();
            });
            $request->RequestGroups->each(function($requestGroups) {
                $requestGroups->delete();
            });
            $request->RequestKids->each(function($requestKids) {
                $requestKids->delete();
            });
            $request->RequestNotifications->each(function($requestNotification) {
                $requestNotification->delete();
            });
        });
    }
}
