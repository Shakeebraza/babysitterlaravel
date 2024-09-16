<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RequestResource;
use App\Http\Resources\UserResource;
use App\Models\Feedback;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserRequest;
use App\Services\UserService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function languages(Request $request){
        app()->setLocale($request['language']);
        $response["result"] = 1;
        $response["languages"] = [
            array("code"=> "de", "name" => "Deutsch"),
            array("code" => "en", "name" => "English")
        ];
        return response($response, 200);
    }

    public function addFeedback(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                 'session_token' => 'required',
                'subject' => 'required',
                'description' => 'required',
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
            }else {
                $input = $request->all();
                $input['user_id'] = $user->id;
                Feedback::create($input);

                $response["result"] = 1;
                $response["message"] = trans('messages.feedback_added_successfully');
                return response($response, 200);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function searchMember(Request $request){
        /*
         * Deprecated use v2 in UserController
         */
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'search' => 'required',
                //'session_token' => 'required', //after migration check user
            ]);

            if ($validator->fails()) {
                $response["result"] = "0";
                $response["message"] = implode(' ', $validator->errors()->all());
                return response($response, 200);
            }

            if (isset($request['session_token'])) { //remove if after migration and add to validator
                $user = $this->getUser($request['session_token']);
                if (empty($user)) {
                    $response["result"] = 0;
                    $response["message"] = trans('messages.user_not_found');
                    return response($response, 200);
                }
            }

            $users = User::orWhere('first_name','like','%'.$request['search'].'%')->orWhere('surname','like','%'.$request['search'].'%')
                ->orWhere('email','like','%'.$request['search'].'%')->select('id','first_name','surname','email')->get();

            if(count($users)>0){
                $response["result"] = 1;
                $response["message"] = trans('messages.success');
                $response["members"] = UserResource::collection($users);
                return response($response, 200);
            }else{
                $response["result"] = 0;
                $response["message"] = trans('messages.member_not_found');
                return response($response, 200);
            }
        }catch(Exception $e){
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function groupMember(Request $request){
        /*
         * Deprecated use v2 in GroupController
         */
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'session_token' => 'required',
                'group_id' => 'required',
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

            $group = Group::where('id',$request['group_id'])->where('user_id',$user['id'])->first();
            if (empty($group)) {
                $response["result"] = 0;
                $response["message"] = trans('messages.group_not_found');
                return response($response, 200);
            }

            $groupMembers = GroupMember::with('Member')->where('group_id',$request['group_id'])
                ->where('status',1)->get();

            if(count($groupMembers) == 0){
                $response["result"] = 0;
                $response["message"] = trans('messages.member_not_found');
                return response($response, 200);
            }

            $groupMemberArray = [];
            $ii = 0;
            foreach ($groupMembers as $groupMember){
                $member = $groupMember->Member;
                if(!empty($member)) {
                    $groupMemberArray[$ii] = new UserResource($member);
                    $groupMemberArray[$ii]['r_id'] = $groupMember['id'];
                    $ii++;
                }
            }
            $response["result"] = 1;
            $response["message"] = trans('messages.success');
            $response["members"] = $groupMemberArray;
            return response($response, 200);
        }catch(Exception $e){
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function readTranslationFile(){
        try{
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST');
            header("Access-Control-Allow-Headers: X-Requested-With");
            echo $translationContent = file_get_contents(public_path().'/assets/website/translations/translations.json');
            exit;
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function translation(){
        try{
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST');
            header("Access-Control-Allow-Headers: X-Requested-With");
            echo $translationContent = file_get_contents(public_path().'/assets/website/translations/translations_v3.json');
            exit;
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function translation2(){
        try{
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST');
            header("Access-Control-Allow-Headers: X-Requested-With");
            echo $translationContent = file_get_contents(public_path().'/assets/website/translations/translations_v2.json');
            exit;
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function testPush(){
        $token = 'dsJE6XN7Tye--TsHoTPXLp:APA91bHJG_DBRT0SBGbKHg5F-FgOWUKRyVtSX0pQTo_z348XTKI1vdfq6oxZIobzxjBPWejaysEEuyvb06sUXZvK4SS3Ewt0eLI5S2uTgWQXxOBkyAu9ADFluX-9KcftNjUkJZ5TiQzt';

        $firebaseToken = User::where('token',$token)->pluck('token')->all();
        $SERVER_API_KEY = 'AAAAAIsg_Is:APA91bGr_5dl1SxnDTiO1UPRjk48BHEWMCT0NFv1tNLaxKQ3Kaz9HNR2DOZkiQxJ1sS9AE07MYWyClTWCnho-QaGaTIA8Q6pdYaZS8_Af12-_VdefzrxIGOPlW0b13_CtJ4pVoS0VjDM';
        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => 'Test Push',
                "body" => 'This is a test push notification',
            ]
        ];

        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
        dd($response);
    }

    public function myNotification(Request $request){
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

            $user = $this->getUser($request['session_token']);;

            if (empty($user)) {
                $response["result"] = 0;
                $response["message"] = trans('messages.user_not_found');
                return response($response, 200);
            }

            $date = Carbon::today()->subDays(7);

            $notifications = Notification::where('receiver',$user['id'])
                ->where(function ($query) use ($date) {
                    $query->where('created_at', '>=', $date)
                        ->orWhere('is_read', 0);
                })
                ->orderBy('is_read','ASC')
                ->orderBy('created_at','DESC')->get();
            if(empty($notifications)){
                $response["result"] = 0;
                $response["message"] = trans('messages.data_not_found');
                return response($response, 200);
            }

            foreach ($notifications as $list){
                $createdAt = Carbon::parse($list['created_at']);
                $list['date'] = $createdAt->locale($request['language'])->isoFormat('MMM D YYYY HH:mm:ss'); // TODO format in app
            }

            $response["result"] = 1;
            $response["message"] = trans('messages.success');
            $response["notification"] = $notifications;
            return response($response, 200);
        }catch(Exception $e){
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function notificationDetail(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                 'session_token' => 'required',
                'notification_id' => 'required',
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

            $notification = Notification::where('id',$request['notification_id'])->where('receiver',$user['id'])->first();

            if(empty($notification)){
                $response["result"] = 0;
                $response["message"] = trans('messages.notification_not_found');
                return response($response, 200);
            }

            $input['is_read'] = 1;
            $notification->update($input);

            $userRequest = UserRequest::with('User')->where('id',$notification['request_id'])->first();
            if ($userRequest !== null) {
                $notification['userRequest'] = new RequestResource($userRequest);
            }

            $response["result"] = 1;
            $response["notification"] = $notification;
            $response["message"] = trans('messages.success');
            return response($response, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function unreadNotification(Request $request){
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

            $user = $this->getUser($request['session_token']);;

            if (empty($user)) {
                $response["result"] = 0;
                $response["message"] = trans('messages.user_not_found');
                return response($response, 200);
            }else {
                $unreadNotification = Notification::where('receiver',$user['id'])->where('is_read',0)->count();

                $response["result"] = 1;
                $response["unreadNotification"] = $unreadNotification;
                $response["message"] = trans('messages.success');
                return response($response, 200);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

}
