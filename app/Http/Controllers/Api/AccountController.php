<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FullUserResource;
use App\Http\Resources\RequestResource;
use App\Mail\VerificationMail;
use App\Models\DeviceToken;
use App\Models\Enums\NotificationEvent;
use App\Models\GroupMember;
use App\Models\Kids;
use App\Models\MultiLogin;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserRequest;
use App\Models\UserRequestGroup;
use App\Models\UserRequestKids;
use App\Services\FeedItemService;
use App\Services\MessageService;
use App\Services\UserService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{

    protected UserService $userService;
    protected MessageService $messageService;
    protected FeedItemService $feedItemService;

    public function __construct(UserService $userService,
                                MessageService $messageService,
                                FeedItemService $feedItemService)
    {
        $this->userService = $userService;
        $this->messageService = $messageService;
        $this->feedItemService = $feedItemService;
    }

    public function login(Request  $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'email' => 'required',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                $response["result"] = "0";
                $response["message"] = implode(' ', $validator->errors()->all());
                return response($response, 200);
            }

            $user = User::where("email", $request['email'])->first();

            if (empty($user)) {
                $response["result"] = 0;
                $response["message"] = trans('messages.email_password_incorrect');
                return response($response, 200);
            }
            if (Hash::check($request['password'],$user['password']) == false) {
                $response["result"] = 0;
                $response["message"] = trans('messages.email_password_incorrect');
                return response($response, 200);
            }
            $unreadNotification = Notification::where('receiver',$user['id'])->where('is_read',0)->count();
            $newLogin = $this->userService->createMultiLogin("password", $user->id);
            if ($user['status'] == 'inactive') {
                $response["result"] = 9;
                $response["unreadNotification"] = $unreadNotification;
                $response["user"] = new FullUserResource($user);
                $response['session_token'] = $newLogin->session_token;
                $response["message"] = trans('messages.account_inactive');
                return response($response, 200);
            }

            $response["result"] = 1;
            $response["unreadNotification"] = $unreadNotification;
            $response["user"] = new FullUserResource($user);
            $response['session_token'] = $newLogin->session_token;
            $response["message"] = trans('messages.login_successfully');
            return response($response, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function register(Request $request){
        app()->setLocale($request['language']);
        try{
            $customMessages = [
                'email.unique' => trans('messages.registration_mail_already_used'),
            ];
            $validator = Validator::make($request->post(), [
                'first_name' => 'required',
                'surname' => 'required',
                'password' => 'required|confirmed|min:6',
                'phone' => 'required',
                'date_of_birth' => 'required|date',
                'social_type' => 'required',
                'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            ], $customMessages);

            if ($validator->fails()) {
                $response["result"] = 0;
                $response["status"] = 0; //remove after migration
                $response["message"] = implode(' ', $validator->errors()->all());
                return response($response, 200);
            }

            $input['first_name'] = $request['first_name'];
            $input['surname'] = $request['surname'];
            $input['password'] = $request['password'];
            $input['email'] = $request['email'];
            $input['phone'] = preg_replace('/\s+/', '',$request['phone']);
            $input['date_of_birth'] = $request['date_of_birth'];
            $input['language'] = $request['language'];
            $input['profile_type'] = $request['profile_type'];
            $input['role'] = 'user';
            $input['status'] = 'inactive';
            $input['email_verification_code'] = $this->randomCode();
            $user = User::create($input);

            if(empty($user)) {
                $response["result"] = 0;
                $response["status"] = 0; //remove after migration
                $response["message"] = trans('messages.registration_fail');
                return response($response, 200);
            }
            try{
                Mail::to($user->email)->send(new VerificationMail($user->first_name ,$user->email_verification_code));
                Log::info("Mail Sent==> Verification Code " . $user->email);
            }catch(Exception $e){
                Log::error('Mail Error==>'.$e->getMessage());
            }

            $response["result"] = 1;
            $response["message"] = trans('messages.registration_success');
            return response($response, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function socialSignup(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'email' => 'required|email',
                'social_type' => 'required',//apple or google or facebook
            ]);

            if ($validator->fails()) {
                $response["result"] = "0";
                $response["message"] = implode(' ', $validator->errors()->all());
                Log::warning("Login failed - validation error", ["request" => $request->all(), "response" => $response]);
                return response($response, 200);
            }

            $user = User::where('email',$request['email'])->first();
            if(!empty($user)){
                //user exist
                if ($user['status'] == 'inactive') {
                    // set status to active, because a social login is kind a verification
                    $this->userService->setUserVerified($user);
                }
                $message = trans('messages.login_successfully');
                $registration = false;
            }else {
                //create user
                $user['email'] = $request['email'];
                $user['first_name'] = $request['first_name'];
                $user['surname'] = $request['surname'];
                $user['language'] = $request['language'];
                $user['email_verified_at'] = Carbon::now();
                $user['status'] = 'active';
                $user = User::create($user);

                $this->addInGroup($user->email, $user);
                $message = trans('messages.registration_success');
                $registration = true;
            }
            if(empty($user)) {
                $response["result"] = 0;
                $response["message"] = trans('messages.registration_fail');
                Log::warning("Login failed", ["request" => $request->all(), "response" => $response]);
                return response($response, 200);
            }
            $newLogin = $this->userService->createMultiLogin($request['social_type'], $user->id);

            $response["result"] = 1;
            $response["user"] = new FullUserResource($user);
            $response["session_token"] = $newLogin->session_token;
            $response["message"] = $message;
            $response["registration"] = $registration;
            Log::info("Login successful", ["request" => $request->all(), "response" => $response]);
            return response($response, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.registration_fail');
            return response($response, 500);
        }
    }

    public function forgotPassword(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'email' => 'required',
            ]);

            if ($validator->fails()) {
                $response["result"] = "0";
                $response["message"] = implode(' ', $validator->errors()->all());
                return response($response, 200);
            }

            $user = User::where("email", $request['email'])->first();

            if (empty($user)) {
                $response["result"] = 0;
                $response["message"] = trans('messages.user_not_found');
                return response($response, 200);
            }else {
                $code = $this->randomCode();
                $user->fp_code = $code;
                $user->save();

                $this->sendMail($user, trans('messages.password_reset_title'),'forgot_password_mail_template');

                $response["result"] = 1;
                $response["user"]['id'] = $user['id'];
                $response["message"] = trans('messages.forgot_password_reset_success');
                return response($response, 200);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function resetPassword(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                //'session_token' => 'required',
                'password' => 'required',
                'fp_code' => 'required',
            ]);

            if ($validator->fails()) {
                $response["result"] = "0";
                $response["message"] = implode(' ', $validator->errors()->all());
                return response($response, 200);
            }

            $user = User::where("fp_code", $request['fp_code'])->first();;

            if (empty($user)) {
                $response["result"] = 0;
                $response["message"] = trans('messages.user_not_found');
                return response($response, 200);
            }else {
                $userCheck = User::where("id", $user['id'])->where("fp_code", $request['fp_code'])->first();
                if (empty($userCheck)) {
                    $response["result"] = 0;
                    $response["message"] = trans('messages.forgot_password_wrong_verification_code');
                    return response($response, 200);
                } else {
                    $input = $request->all();
                    $input['fp_code'] = '';
                    $user->update($input);

                    $response["result"] = 1;
                    $response["message"] = trans('messages.reset_password_success');
                    return response($response, 200);
                }
            }
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function changePassword(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'session_token' => 'required',
                'old_password' => 'required',
                'password' => 'required|confirmed|min:6',
            ]);

            if ($validator->fails()) {
                $response["result"] = "0";
                $response["message"] = implode(' ', $validator->errors()->all());
                return response($response, 200);
            }

            $user = $this->getUser($request['session_token']);;
            if (empty($user)) {
                $response["result"] = 0;
                $response["message"] = trans('messages.user_not_found');
                return response($response, 200);
            }

            if (Hash::check($request['old_password'],$user['password']) == false) {
                $response["result"] = 0;
                $response["message"] = trans('messages.change_password_password_invalid');
                return response($response, 200);
            }
            $input = $request->all();
            $user->update($input);
            $response["result"] = 1;
            $response["message"] = trans('messages.reset_password_success');
            return response($response, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function resendVerifyCode(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'session_token' => 'required',
            ]);

            if ($validator->fails()) {
                $response["result"] = "0";
                $response["message"] = implode(' ', $validator->errors()->all());
                return response($response, 200);
            }

            $user = $this->getUser($request['session_token'], true);
            if (empty($user)) {
                $response["result"] = 0;
                $response["message"] = trans('messages.user_not_found');
                return response($response, 200);
            }

            $input['email_verification_code'] = $this->randomCode();
            $user->update($input);

            Mail::to($user->email)->send(new VerificationMail($user->first_name ,$user->email_verification_code));

            $response["result"] = 1;
            $response["message"] = trans('messages.verification_code_resent');
            return response($response, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function emailVerify(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'session_token' => 'required',
                'code' => 'required',
            ]);

            if ($validator->fails()) {
                $response["result"] = "0";
                $response["message"] = implode(' ', $validator->errors()->all());
                return response($response, 200);
            }

            $user = $this->getUser($request['session_token'], true);

            if (empty($user)) {
                $response["result"] = 0;
                $response["message"] = trans('messages.user_not_found');
                return response($response, 200);
            }

            if ($request['code'] != $user['email_verification_code']) {
                $response["result"] = 0;
                $response["message"] = trans('messages.code_is_invalid');
                return response($response, 200);
            }

            $this->userService->setUserVerified($user);

            $this->addInGroup($user->email,$user);

            try{
                $sent = $this->messageService->sendWelcomeEmail($user);
                if($sent){
                    Log::info("Mail Sent==> Welcome Mail " . $user->email);
                }
            }catch(Exception $e){
                Log::error('Mail Error==>'.$e->getMessage());
            }

            $response["result"] = 1;
            $response["message"] = trans('messages.email_is_successfully_verified');
            /*
             * Remove after migration
             */
            $user->session_token = $request['session_token'];
            $response["user"] = new FullUserResource($user);
            /*
             * Remove after migration - END
             */
            return response($response, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function deleteAccount(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'session_token' => 'required',
            ]);

            if ($validator->fails()) {
                $response["result"] = "0";
                $response["message"] = implode(' ', $validator->errors()->all());
                return response($response, 200);
            }

            $user = $this->getUser($request['session_token']);

            if (empty($user)) {
                $response["result"] = 0;
                $response["message"] = trans('messages.user_not_found');
                return response($response, 200);
            }else {
                if (!empty($user['image']) && file_exists('public/'.$user['image'])) {
                    unlink('public/'.$user['image']);
                }
                $user->delete();

                $this->feedItemService->removeFeedItemsForUser($user->id);

                $response["result"] = 1;
                $response["message"] = trans('messages.your_account_has_been_deleted_successfully');
                return response($response, 200);
            }
        }catch(Exception $e){
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function updateDeviceDetails(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'session_token' => 'required',
                'device_type' => 'required',
                'token' => 'required',
            ]);

            if ($validator->fails()) {
                $response["result"] = "0";
                $response["message"] = implode(' ', $validator->errors()->all());
                Log::warning("Push update failed - validation", ["request" => $request->all(), "response" => $response]);
                return response($response, 200);
            }

            $user = $this->getUser($request['session_token'], true);

            if (empty($user)){
                $response["result"] = "0";
                // temporary update app
                //$response["message"] = trans('messages.user_not_found');
                $response["message"] = trans('messages.update_app');
                Log::warning("Push update failed - user", ["request" => $request->all(), "response" => $response]);
                return response($response, 200);
            }

            $userDevice = DeviceToken::where('user_id',$user->id)->where('device_key',$request['device_key'])->first();
            if(empty($userDevice)){
                //can be removed after a time, there are some old entries without device_key
                $userDevice = DeviceToken::where('user_id',$user->id)->where('device_token',$request['device_token'])->first();
                if(empty($userDevice)) {
                    $deviceToken['user_id'] = $user->id;
                    $deviceToken['device_type'] = $request['device_type'];
                    $deviceToken['device_token'] = $request['token'];
                    $deviceToken['device_key'] = $request['device_key'];
                    $deviceToken['app_version'] = $request['app_version'];
                    $deviceToken['last_home_call'] = Carbon::now();
                    DeviceToken::create($deviceToken);
                } else {
                    $update = array();
                    $update['device_key'] = $request['device_key'];
                    $update['app_version'] = $request['app_version'];
                    $update['last_home_call'] = Carbon::now();
                    $userDevice->update($update);
                }
            } else {
                $update = array();
                $update['last_home_call'] = Carbon::now();
                $userDevice->update($update);
            }
            $response["result"] = 1;
            $response["message"] = trans('messages.token_updated_successfully');
            Log::info("Push updated", ["request" => $request->all(), "response" => $response]);
            return response($response, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function logout(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'session_token' => 'required',
            ]);

            if ($validator->fails()) {
                $response["result"] = "0";
                $response["message"] = implode(' ', $validator->errors()->all());
                return response($response, 200);
            }

            $userSession = MultiLogin::where('session_token',$request['session_token'])->first();

            if(empty($userSession)) {
                $response["result"] = 0;
                $response["message"] = trans('messages.user_not_found');
                return response($response, 200);
            }
            if (empty($request["device_key"])) {
                Log::warning("No device_token sent, remove all to be sure.");
                #search all DeviceToken from user and delete them
                /*
                 * Remove after migration - START
                 */
                DeviceToken::where('user_id', $userSession->user_id)->delete();
                /*
                 * Remove after migration - END
                 */
            } else {
                # search all Device Token with device_key from user and delete them
                DeviceToken::where('user_id', $userSession->user_id)
                    ->where('device_key', $request["device_key"])
                    ->delete();
            }

            $userSession->delete();
            $response["result"] = 1;
            $response["message"] = trans('messages.logout_successfully');
            return response($response, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function addInGroup($email, User $user)
    {
        $groupMembers = GroupMember::where('member_email', strtolower($email))->where('status', 0)->get();
        if (!empty($user)) {
            if (count($groupMembers) > 0) {
                foreach ($groupMembers as $groupMember) {
                    $in['member_id'] = $user->id;
                    $in['status'] = 1;
                    $groupMember->update($in);

                    /*Notification Code Start*/
                    $users = User::where('id', $groupMember->user_id)->get(); //should be only one
                    $this->messageService->createNotification($user->id, $users,
                        NotificationEvent::INVITEE_HAVE_BEEN_ADDED,
                        'messages.invitee_title',
                        array(),
                        'messages.invitee_message',
                        ['invitee_name' => $user->first_name . ' ' . $user->surname]);
                    /*Notification Code End*/
                }
            }
        }
    }

}
