<?php

namespace App\Services;

use App\Models\MultiLogin;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class UserService
{

    public function createMultiLogin($social_type, $userId){
        $multiLogin["social_type"] = $social_type;
        $multiLogin["user_id"] = $userId;
        $multiLogin["session_token"] = $this->randomToken();
        $multiLogin["last_login"] = Carbon::now();
        return MultiLogin::create($multiLogin);
    }

    public function setUserVerified(User $user){
        $user->update([
            'email_verified_at' => Carbon::now(),
            'status' => 'active',
        ]);
    }

    private function randomToken(){
        $token = Str::random(30);
        $userToken = MultiLogin::where('session_token',$token)->first();
        if(!empty($userToken)){
            return $this->randomToken();
        }else{
            return $token;
        }
    }

}
