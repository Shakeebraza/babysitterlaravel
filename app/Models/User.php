<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'first_name','surname','email','email_verification_code','password','gender','language','profile_type','date_of_birth','address','street',
        'zip','city','country_code','latitude','longitude','phone','image','role','status','identify','document_rejection_reason','aboutme',
        'fp_code'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($password){
        $this->attributes['password'] = bcrypt($password);
    }

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public static $status = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'In Active',
    ];

    const GENDERMALE = 'male';
    const GENDERFEMALE = 'female';

    public static $gender = [
        self::GENDERMALE => 'Male',
        self::GENDERFEMALE => 'Female',
    ];

    const PARENT = 'parent';
    const BABYSITTER = 'babysitter';
    const BOTH = 'both';

    public static $profile_type = [
        self::PARENT => 'Parent',
        self::BABYSITTER => 'Babysitter',
        self::BOTH => 'Both',
    ];

    public function Groups(){
        return $this->hasMany('App\Models\Group','user_id');
    }

    public function Kids(){
        return $this->hasMany('App\Models\Kids','user_id');
    }

    public function MemberInGroup(){
        return $this->hasMany('App\Models\GroupMember','member_id');
    }

    public function Documents(){
        return $this->hasMany('App\Models\UserDocument','user_id');
    }

    public function UserRequest(){
        return $this->hasMany('App\Models\UserRequest','user_id');
    }

    public function AppliedRequest(){
        return $this->hasMany('App\Models\AcceptedRequest','user_id');
    }

    public function AcceptedRequest(){
        return $this->hasMany('App\Models\AcceptedRequest','awarded_by');
    }

    public function MultiLogin(){
        return $this->hasMany('App\Models\MultiLogin','user_id');
    }

    public function DeviceToken(){
        return $this->hasMany('App\Models\DeviceToken','user_id');
    }

    public function notificationSetting()
    {
        return $this->hasOne(NotificationSetting::class, 'user_id');
    }

    /**
     * @return bool
     */
    public function isProfileExtended() {
        return $this->social_type != "password" && !empty($this->first_name) && !empty($this->surname);
    }

    /**
     * @return bool
     */
    public function isVerified() {
        return $this->social_type == "password" && !empty($this->email_verified_at);
    }

    /**
     * @return bool
     */
    public function isProfileComplete() {
        return !empty($this->address);
    }

    /**
     * @return bool
     */
    public function hasImage() {
        return !empty($this->image);
    }

    /**
     * @return bool
     */
    public function isIdentified() {
        return $this->identify == 2;
    }

    /**
     * @return int
     */
    public function calculateUsageLevel() {
        $level = 10;

        if ($this->isProfileExtended() || $this->isVerified()) {
            $level += 10;
        }

        if ($this->isProfileComplete()) {
            $level += 10;
        }

        if ($this->hasImage()) {
            $level += 10;
        }

        if ($this->isIdentified()) {
            $level += 20;
        }

        if ($this->Groups()->count() > 0) {
            $level += 20;
        }

        if ($this->UserRequest()->count() > 0) {
            $level += 20;
        }

        return $level;
    }

    protected static function boot() {
        parent::boot();

        static::created(function ($user) {
            $user->notificationSetting()->create();
        });

        static::deleting(function($user) {
            $user->Groups->each(function($group) {
                $group->delete();
            });
            $user->Kids->each(function($kids) {
                $kids->delete();
            });
            $user->MemberInGroup->each(function($group) {
                $group->delete();
            });
            $user->Documents->each(function($document) {
                $document->delete();
            });
            //TODO do not remove user requests and applies with specific status and without information
            $user->UserRequest->each(function($userRequest) {
                $userRequest->delete();
            });
            $user->AppliedRequest->each(function($appliedRequest) {
                $appliedRequest->delete();
            });
            $user->MultiLogin->each(function($multiLogin) {
                $multiLogin->delete();
            });
            $user->DeviceToken->each(function($deviceToken) {
                $deviceToken->delete();
            });
        });
    }
}
