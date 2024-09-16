<?php

namespace App\Http\Controllers;

use App\Models\Enums\NotificationEvent;
use App\Models\GroupMember;
use App\Models\MultiLogin;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /*public function image($photo, $path){
        $root = 'public/uploads/'.$path;
        $name = Str::random(20).".".$photo->getClientOriginalExtension();
        if (!file_exists($root)) {
            mkdir($root, 0777, true);
        }
        $photo->move($root,$name);
        return 'uploads/'.$path."/".$name;
    }*/

    public function image($photo, $path, $type, $size = 256)
    {
        if ($type == 'profile') {
            $name = Str::random(20) . "." . $photo->getClientOriginalExtension();
            $destinationPath = 'public/uploads/' . $path;
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            //$img = Image::configure(['driver' => 'imagick'])->make($photo);
            $img = Image::make($photo);
            $img->resize($size, $size, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($destinationPath . '/' . $name);

            return 'uploads/' . $path . "/" . $name;
        } else {
            $root = 'public/uploads/' . $path;
            $name = Str::random(20) . "." . $photo->getClientOriginalExtension();
            if (!file_exists($root)) {
                mkdir($root, 0777, true);
            }
            $photo->move($root, $name);
            return 'uploads/' . $path . "/" . $name;
        }
    }

    public function setUserAddressField($input, $updateFields = array())
    {
        $updateFields['address'] = $input['address'];
        $updateFields['street'] = $input['street'];
        $updateFields['zip'] = $input['zip'];
        $updateFields['city'] = $input['city'];
        $updateFields['country_code'] = $input['country_code'] ?? '';
        $updateFields['latitude'] = $input['latitude'];
        $updateFields['longitude'] = $input['longitude'];
        return $updateFields;
    }

    public function randomCode()
    {
        $code = rand(1000, 9999);
        $user = User::where('email_verification_code', $code)->first();
        if (!empty($user)) {
            return $this->randomCode();
        } else {
            return $code;
        }
    }

    public function sendMail($user, $subject, $template, $to_contact = false)
    {
        $returnCode = 1;
        $data['data'] = $user;
        $email = $to_contact ? 'contact@babysitter-app.com' : $user['email'];
        try {
            Mail::send('mail_template/' . $template, (array)$data, function ($message) use ($email, $subject) {
                $message->from('no-reply@babysitter-app.com');
                $message->to($email);
                $message->subject($subject);
            });
            Log::info('Mail Sent==>' . $template . " " . $email);
        } catch (Exception $e) {
            Log::error('Mail Error==>' . $e->getMessage());
            $returnCode = 0;
        }
        return $returnCode;
    }

    public function getUser($session_token, $inactiveAllowed = false)
    {
        $userSession = MultiLogin::where('session_token', $session_token)->first();
        if (!empty($userSession)) {
            if ($inactiveAllowed) {
                $user = User::where('id', $userSession['user_id'])->first();
            } else {
                $user = User::where('id', $userSession['user_id'])->where('status', 'active')->first();
            }
            if (!empty($user)) {
                return $user;
            }
        }
        return null;
    }
}
