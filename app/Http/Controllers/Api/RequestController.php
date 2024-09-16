<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RequestResource;
use App\Models\AcceptedRequest;
use App\Models\Enums\KidsType;
use App\Models\Enums\NotificationEvent;
use App\Models\FeedItem;
use App\Models\GroupMember;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserRequest;
use App\Models\UserRequestGroup;
use App\Models\UserRequestKids;
use App\Services\FeedItemService;
use App\Services\MessageService;
use App\Services\RecommendationService;
use App\Services\SubscriptionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RequestController extends Controller
{
    protected SubscriptionService $subscriptionService;
    protected RecommendationService $recommendationService;
    protected FeedItemService $feedItemService;
    protected MessageService $messageService;

    public function __construct(SubscriptionService $subscriptionService,
                                RecommendationService $recommendationService,
                                FeedItemService $feedItemService,
                                MessageService $messageService)
    {
        $this->subscriptionService = $subscriptionService;
        $this->recommendationService = $recommendationService;
        $this->feedItemService = $feedItemService;
        $this->messageService = $messageService;
    }


    //deprecated
    public function newRequest(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'session_token' => 'required',
                'title' => 'required',
                'from_date' => 'required',
                'to_date' => 'required',
                'visibility' => 'required',
                'address_type' => 'required',
            ]);

            if ($validator->fails()) {
                $response["result"] = "0";
                $response["message"] = implode(' ', $validator->errors()->all());
                return response($response, 422);
            }

            $user = $this->getUser($request['session_token']);;
            if (empty($user)) {
                $response["result"] = 0;
                $response["message"] = trans('messages.user_not_found');
                return response($response, 200);
            }

            $input = $request->all();
            //$kids = Kids::where('user_id',$user['id'])->pluck('id')->toArray();
            $input['user_id'] = $user['id'];

            if ($input["visibility"] == 'public'){
                $input["public_visibility"]=true;
                $input["group_visibility"]=false;
            } else {
                $input["public_visibility"]=false;
                $input["group_visibility"]=true;
            }

            $insertedRequest = UserRequest::create($input);

            /*Add User Request Group & Kids Code Start*/
            $gIds = (isset($request['group_id']) && $request['group_id'] != 'null') ? explode(',',$request['group_id']) : [];
            $kIds = (isset($request['kids_id']) && $request['kids_id'] != 'null') ? explode(',',$request['kids_id']) : [];

            if(count($gIds) > 0){
                UserRequestGroup::where('request_id',$insertedRequest['id'])->delete();
                foreach ($gIds as $gd){
                    $in['request_id'] = $insertedRequest['id'];
                    $in['group_id'] = $gd;
                    UserRequestGroup::create($in);
                }
            }

            if(count($kIds) > 0){
                UserRequestKids::where('request_id',$insertedRequest['id'])->delete();
                foreach ($kIds as $kd){
                    $in1['request_id'] = $insertedRequest['id'];
                    $in1['kids_id'] = $kd;
                    UserRequestKids::create($in1);
                }
            }

            /*Add User Request Group & Kids Code End*/

            $logRequest = [
                'timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
                'user_request' => json_encode($insertedRequest, true),
                'message' => trans('messages.request_is_successfully_created'),
                'variables' => json_encode($request->all(), true),
            ];
            Log::info("User Request Created: ==> ".json_encode($logRequest, true));

            if($request['address_type'] == 'home'){
                $userFields = $this->setUserAddressField($input);
                $user->update($userFields);
            }

            /*Send Push Notification Code Start*/
            if(!empty($insertedRequest)){
                //only send push notifications if groups are assigned
                if(count($gIds) > 0) {
                    $uId = GroupMember::whereIn('group_id', $gIds)->pluck('member_id');
                    $allUser = User::whereIn('id', $uId)->where('status', 'active')->get();

                    if (count($allUser) > 0) {
                        $this->messageService->createNotification(
                            $user['id'],
                            $allUser,
                            NotificationEvent::CREATE_A_REQUEST_FOR_A_SPECIFIC_GROUP,
                            'messages.added_title',
                            array(),
                            'messages.added_message',
                            array(),
                            $insertedRequest);
                    }
                }
            }
            /*Send Push Notification Code End*/

            $response["result"] = 1;
            $response["message"] = trans('messages.request_is_successfully_created');
            return response($response, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function newRequest_v2(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'session_token' => 'required',
                'title' => 'required',
                'from_date' => 'required',
                'to_date' => 'required',
                'group_visibility' => 'required',
                'public_visibility' => 'required',
                'address_type' => 'required',
            ]);

            if ($validator->fails()) {
                $response["result"] = "0";
                $response["message"] = implode(' ', $validator->errors()->all());
                return response($response, 422);
            }

            $user = $this->getUser($request['session_token']);;
            if (empty($user)) {
                $response["result"] = 0;
                $response["message"] = trans('messages.user_not_found');
                return response($response, 200);
            }

            $input = $request->all();
            //$kids = Kids::where('user_id',$user['id'])->pluck('id')->toArray();
            $input['user_id'] = $user['id'];

            $insertedRequest = UserRequest::create($input);

            /*Add User Request Group & Kids Code Start*/
            $gIds = (isset($request['group_id']) && $request['group_id'] != 'null') ? explode(',',$request['group_id']) : [];
            $kIds = (isset($request['kids_id']) && $request['kids_id'] != 'null') ? explode(',',$request['kids_id']) : [];

            if(count($gIds) > 0){
                UserRequestGroup::where('request_id',$insertedRequest['id'])->delete();
                foreach ($gIds as $gd){
                    $in['request_id'] = $insertedRequest['id'];
                    $in['group_id'] = $gd;
                    UserRequestGroup::create($in);
                }
            }

            if(count($kIds) > 0){
                UserRequestKids::where('request_id',$insertedRequest['id'])->delete();
                foreach ($kIds as $kd){
                    $in1['request_id'] = $insertedRequest['id'];
                    $in1['kids_id'] = $kd;
                    UserRequestKids::create($in1);
                }
            }

            /*Add User Request Group & Kids Code End*/

            $logRequest = [
                'timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
                'user_request' => json_encode($insertedRequest, true),
                'message' => trans('messages.request_is_successfully_created'),
                'variables' => json_encode($request->all(), true),
            ];
            Log::info("User Request Created: ==> ".json_encode($logRequest, true));

            if($request['address_type'] == 'home'){
                $userFields = $this->setUserAddressField($input);
                $user->update($userFields);
            }

            /*Send Push Notification Code Start*/
            if(!empty($insertedRequest)){
                //only send push notifications if groups are assigned
                if(count($gIds) > 0) {
                    $uId = GroupMember::whereIn('group_id', $gIds)->pluck('member_id');
                    $allUser = User::whereIn('id', $uId)->where('status', 'active')->get();

                    if (count($allUser) > 0) {
                        $this->messageService->createNotification(
                            $user['id'],
                            $allUser,
                            NotificationEvent::CREATE_A_REQUEST_FOR_A_SPECIFIC_GROUP,
                            'messages.added_title',
                            array(),
                            'messages.added_message',
                            array(),
                            $insertedRequest);
                    }
                }
                if ($request->public_visibility === 1){
                    $this->recommendationService->processRequest($insertedRequest);
                    $this->subscriptionService->processRequest($insertedRequest);
                }
            }
            /*Send Push Notification Code End*/

            $response["result"] = 1;
            $response["message"] = trans('messages.request_is_successfully_created');
            return response($response, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    //deprecated
    public function editRequest(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'session_token' => 'required',
                'request_id' => 'required',
                'title' => 'required',
                //'description' => 'required',
                'from_date' => 'required',
                'to_date' => 'required',
                'visibility' => 'required',
                'address_type' => 'required',
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
            $userRequest = UserRequest::where('id',$request['request_id'])->where('user_id',$user['id'])->first();
            if(empty($userRequest)){
                $response["result"] = 0;
                $response["message"] = trans('messages.request_not_found');
                return response($response, 200);
            }

            $input = $request->all();

            /*Add User Request Group & Kids Code Start*/
            $gIds = (isset($request['group_id']) && $request['group_id'] != 'null') ? explode(',',$request['group_id']) : [];
            $kIds = (isset($request['kids_id']) && $request['kids_id'] != 'null') ? explode(',',$request['kids_id']) : [];

            $currentGroupIds = $userRequest->Groups()->pluck('group_id')->toArray();
            $groupsToDelete = array_diff($currentGroupIds, $gIds);
            $groupsToAdd = array_diff($gIds, $currentGroupIds);

            $userRequest->RequestGroups()->whereIn('group_id', $groupsToDelete)->delete();

            foreach ($groupsToAdd as $groupId){
                $userRequest->RequestGroups()->create([
                    'group_id' => $groupId
                ]);
            }

            $currentKidsIds = $userRequest->Kids()->pluck('kids_id')->toArray();
            $kidsToDelete = array_diff($currentKidsIds, $kIds);
            $kidsToAdd = array_diff($kIds, $currentKidsIds);

            $userRequest->RequestKids()->whereIn('kids_id', $kidsToDelete)->delete();
            foreach ($kidsToAdd as $kidsId) {
                $userRequest->RequestKids()->create([
                    'kids_id' => $kidsId
                ]);
            }
            /*Add User Request Group & Kids Code End*/

            if ($input["visibility"] == 'public'){
                $input["public_visibility"]=true;
                $input["group_visibility"]=false;
            } else {
                $input["public_visibility"]=true;
                $input["group_visibility"]=true;
            }

            $userRequest->update($input);

            $logRequest = [
                'timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
                'user_request' => json_encode($userRequest, true),
                'message' => trans('messages.request_is_successfully_updated'),
                'variables' => json_encode($request->all(), true),
            ];
            Log::info("User Request Updated: ==> ".json_encode($logRequest, true));

            $response["result"] = 1;
            $response["message"] = trans('messages.request_is_successfully_updated');
            return response($response, 200);
        }catch(Exception $e){
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function editRequest_v2(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'session_token' => 'required',
                'request_id' => 'required',
                'title' => 'required',
                //'description' => 'required',
                'from_date' => 'required',
                'to_date' => 'required',
                'group_visibility' => 'required',
                'public_visibility' => 'required',
                'address_type' => 'required',
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
            $userRequest = UserRequest::where('id',$request['request_id'])->where('user_id',$user['id'])->first();
            if(empty($userRequest)){
                $response["result"] = 0;
                $response["message"] = trans('messages.request_not_found');
                return response($response, 200);
            }

            $input = $request->all();

            /*Add User Request Group & Kids Code Start*/
            $gIds = (isset($request['group_id']) && $request['group_id'] != 'null') ? explode(',',$request['group_id']) : [];
            $kIds = (isset($request['kids_id']) && $request['kids_id'] != 'null') ? explode(',',$request['kids_id']) : [];

            $currentGroupIds = $userRequest->Groups()->pluck('group_id')->toArray();
            $groupsToDelete = array_diff($currentGroupIds, $gIds);
            $groupsToAdd = array_diff($gIds, $currentGroupIds);

            $userRequest->RequestGroups()->whereIn('group_id', $groupsToDelete)->delete();

            foreach ($groupsToAdd as $groupId){
                $userRequest->RequestGroups()->create([
                    'group_id' => $groupId
                ]);
            }

            $currentKidsIds = $userRequest->Kids()->pluck('kids_id')->toArray();
            $kidsToDelete = array_diff($currentKidsIds, $kIds);
            $kidsToAdd = array_diff($kIds, $currentKidsIds);

            $userRequest->RequestKids()->whereIn('kids_id', $kidsToDelete)->delete();
            foreach ($kidsToAdd as $kidsId) {
                $userRequest->RequestKids()->create([
                    'kids_id' => $kidsId
                ]);
            }
            /*Add User Request Group & Kids Code End*/
            $userRequest->update($input);

            $logRequest = [
                'timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
                'user_request' => json_encode($userRequest, true),
                'message' => trans('messages.request_is_successfully_updated'),
                'variables' => json_encode($request->all(), true),
            ];
            Log::info("User Request Updated: ==> ".json_encode($logRequest, true));

            if ($input['public_visibility']){
                $this->recommendationService->processRequest($userRequest);
                $this->subscriptionService->processRequest($userRequest);
            }

            $response["result"] = 1;
            $response["message"] = trans('messages.request_is_successfully_updated');
            return response($response, 200);
        }catch(Exception $e){
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function requestDetails(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'session_token' => 'required',
                'request_id' => 'required',
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

            $request = UserRequest::with('Kids', 'Groups')->where('id',$request['request_id'])->first();
            if(empty($request)){
                $response["result"] = 0;
                $response["message"] = trans('messages.data_not_found');
                return response($response, 200);
            }

            $response["result"] = 1;
            $response["message"] = trans('messages.success');
            $response["myRequests"] = (new RequestResource($request))->setResourceRequester($user);
            return response($response, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function requestDelete(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'session_token' => 'required',
                'request_id' => 'required',
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
            }

            $myrequest = UserRequest::where('id',$request['request_id'])->where('user_id',$user['id'])->first();
            if(empty($myrequest)){
                $response["result"] = 0;
                $response["message"] = trans('messages.data_not_found');
                return response($response, 200);
            }

            $logRequest = [
                'timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
                'user_request' => json_encode($myrequest, true),
                'message' => trans('messages.request_deleted_successfully'),
                'variables' => json_encode($request->all(), true),
            ];
            Log::info("User Request Delete: ==> ".json_encode($logRequest, true));

            $this->feedItemService->removeFeedItemsByRequest($myrequest->id);

            $myrequest->delete();

            $response["result"] = 1;
            $response["message"] = trans('messages.request_deleted_successfully');
            return response($response, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function myRequests(Request $request){
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

                $past_date = date('Y-m-d H:i', strtotime("-7 days"));

                $myRequests = UserRequest::with('User', 'AcceptedRequest', 'AcceptedRequest.User', 'ConfirmedRequest', 'ConfirmedRequest.User', 'Groups', 'Kids',)
                    ->where('user_id',$user['id'])
                    ->where('to_date', '>', $past_date)
                    ->orderBy('from_date','DESC')->get();

                if(empty($myRequests)) {
                    $response["result"] = 0;
                    $response["message"] = trans('messages.data_not_found');
                    return response($response, 200);
                }

                $response["result"] = 1;
                $response["message"] = trans('messages.success');
                $response["myRequests"] = $myRequests->map(function ($request) use ($user) {
                    return (new RequestResource($request))->setResourceRequester($user);
                });
                return response($response, 200);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function forMeRequests(Request $request){
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
            //$user = User::with('Groups')->where("id", $user['id'])->first();

            if (empty($user)) {
                $response["result"] = 0;
                $response["message"] = trans('messages.user_not_found');
                return response($response, 200);
            }

            $now = date('Y-m-d H:i');
            $user_id = $user->id;

            // Step 1: Collect group IDs where the user is a member
            $groupIds = GroupMember::where('member_id', $user_id)->pluck('group_id');

            // Step 2: Collect UserRequest IDs that belong to those groups and meet additional criteria
            $requestIds = UserRequestGroup::whereIn('group_id', $groupIds)
                ->pluck('request_id');

            // Step 3: Retrieve UserRequests matching the collected IDs
            // and meet the additional criteria, excluding those created by the user himself or already accepted
            $myRequests = UserRequest::with('User', 'Kids', 'Groups')->where('user_id', '!=', $user_id)
                ->whereIn('id', $requestIds)
                ->where('group_visibility', true)
                ->where('status', 'active')
                ->where('awarded', 0)
                ->where('from_date', '>', $now)
                ->whereDoesntHave('AllAcceptedRequest', function($query) use ($user_id) {
                    // Exclude requests that the user has already accepted
                    $query->where('user_id', $user_id);
                })
                ->orderBy('from_date', 'ASC') // youngest first
                ->get();

            if(empty($myRequests)){
                $response["result"] = 0;
                $response["message"] = trans('messages.data_not_found');
                return response($response, 200);
            }

            $response["result"] = 1;
            $response["message"] = trans('messages.success');
            $response["myRequests"] = $myRequests->map(function ($request) use ($user) {
                return (new RequestResource($request))->setResourceRequester($user);
            });
            return response($response, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }


    public function acceptedRequest(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'session_token' => 'required',
                'request_id' => 'required',
                'request_status' => 'required',
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
                //$data = $request->all();
                //AcceptedRequest::where('request_id',$request['request_id'])->where('user_id',$user['id'])->update($data);

                $inputAccepted = [];
                $inputAccepted['request_id'] = $request['request_id'];
                $inputAccepted['user_id'] = $user['id'];
                $inputAccepted['request_status'] = $request['request_status'];
                $inputAccepted['payment_type'] = $request['payment_type'];
                $inputAccepted['amount'] = $request['amount'];
                $inputAccepted['description'] = $request['description'];

                $acceptedRequest = AcceptedRequest::create($inputAccepted);

                /*Notification Code Start*/
                $requestedUser = User::where('id',$user['id'])->first();

                $userRequest = UserRequest::where('id',$request['request_id'])->first();
                $users = User::where('id',$userRequest['user_id'])->get();
                $title_key = $request['request_status']==1 ? 'messages.request_accepted_title' : 'messages.request_declined_title';
                $body_key = $request['request_status']==1 ? 'messages.request_accepted_message' : 'messages.request_declined_message';

                $this->messageService->createNotification(
                    $user['id'],
                    $users,
                    NotificationEvent::REQUEST_ACCEPTED,
                    $title_key,
                    array(),
                    $body_key,
                    ['apply_name' => $user['first_name'].' '.$user['surname']],
                    UserRequest::find($request['request_id']));
                /*Notification Code End*/

                $response["result"] = 1;
                if($request['request_status']==1){
                    $response["message"] = trans('messages.request_is_successfully_accepted');
                } else {
                    $response["message"] = trans('messages.request_is_successfully_decline');
                }

                $logRequest = [
                    'timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
                    'user_request' => json_encode($acceptedRequest, true),
                    'message' => $response["message"],
                    'variables' => json_encode($request->all(), true),
                ];
                Log::info("User Request Declined: ==> ".json_encode($logRequest, true));

                return response($response, 200);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function updateAwardedBy(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'session_token' => 'required',
                'accepted_requests_id' => 'required',
                'approve' => 'required',
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
                $acceptedRequest = AcceptedRequest::where("id", $request['accepted_requests_id'])->first();

                if (empty($acceptedRequest)) {
                    $response["result"] = 0;
                    $response["message"] = trans('messages.accepted_request_not_found');
                    return response($response, 200);
                }else {
                    if($request['approve']==1){
                        $input = $request->all();
                        $input["status"] = 1;
                        $input["awarded_by"] = $user['id'];
                        $acceptedRequest->update($input);

                        $logRequest = [
                            'timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
                            'user_request' => json_encode($acceptedRequest, true),
                            'message' => trans('messages.request_awarded_is_successfully'),
                            'variables' => json_encode($request->all(), true),
                        ];
                        Log::info("User Applied Accepted: ==> ".json_encode($logRequest, true));

                        $user_request = UserRequest::find($acceptedRequest['request_id']);

                        /*Notification Code Start*/
                        $users = User::where('id',$acceptedRequest['user_id'])->get();
                        $this->messageService->createNotification(
                            $user['id'],
                            $users,
                            NotificationEvent::REWARD_A_APPLICATION,
                            'messages.request_awarded_title',
                            array(),
                            'messages.request_awarded_message',
                            array(),
                            $user_request);
                        /*Notification Code End*/

                        AcceptedRequest::where('id','!=',$request['accepted_requests_id'])
                            ->where('request_id',$acceptedRequest['request_id'])
                            ->update(['status'=>2]);

                        $uIds = AcceptedRequest::where('id','!=',$request['accepted_requests_id'])
                            ->where('request_id',$acceptedRequest['request_id'])->pluck('user_id');

                        /*Notification Code Start*/
                        $users = User::whereIn('id',$uIds)->get();
                        $this->messageService->createNotification(
                            $user['id'],
                            $users,
                            NotificationEvent::REJECT_A_APPLICATION,
                            'messages.request_rejected_title',
                            array(),
                            'messages.request_rejected_message',
                            array(),
                            $user_request);
                        /*Notification Code End*/

                        $data['awarded'] = 1;
                        UserRequest::where('id',$acceptedRequest['request_id'])->update($data);

                        $response["result"] = 1;
                        $response["message"] = trans('messages.request_awarded_is_successfully');
                        return response($response, 200);
                    } else {
                        $input = $request->all();
                        $input["status"] = 2;
                        $input["awarded_by"] = $user['id'];
                        $acceptedRequest->update($input);

                        $user_request = UserRequest::find($acceptedRequest['request_id']);

                        /*Notification Code Start*/
                        $users = User::where('id',$acceptedRequest['user_id'])->get();
                        $this->messageService->createNotification(
                            $user['id'],
                            $users,
                            NotificationEvent::REJECT_A_APPLICATION,
                            'messages.request_rejected_title',
                            array(),
                            'messages.request_rejected_message',
                            array(),
                            $user_request);
                        /*Notification Code End*/

                        $logRequest = [
                            'timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
                            'user_request' => json_encode($acceptedRequest, true),
                            'message' => trans('messages.request_disapproved_is_successfully'),
                            'variables' => json_encode($request->all(), true),
                        ];
                        Log::info("User Applied Rejected: ==> ".json_encode($logRequest, true));

                        //AcceptedRequest::where('id',$request['accepted_requests_id'])->delete();

                        $response["result"] = 1;
                        $response["message"] = trans('messages.request_disapproved_is_successfully');
                        return response($response, 200);
                    }
                }
            }
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function myAppliedRequestList(Request $request){
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
            $past_date = date('Y-m-d H:i', strtotime("-7 days"));

            $user = $this->getUser($request['session_token']);
            if(empty($user)){
                $response["result"] = 0;
                $response["message"] = trans('messages.user_not_found');
                return response($response, 200);
            }

            $acceptedRequests = AcceptedRequest::join('user_requests', 'accepted_requests.request_id', '=', 'user_requests.id')
                ->with(['UserRequest' => function($query) {
                    $query->with(['User', 'Kids']);
                }])
                ->where("accepted_requests.user_id", '=', $user['id'])
                ->where('user_requests.to_date', '>', $past_date)
                ->select('accepted_requests.*', 'user_requests.to_date', 'user_requests.from_date')
                ->orderBy('user_requests.from_date', 'DESC')
                ->get();

            if (empty($acceptedRequests)) {
                $response["result"] = 0;
                $response["message"] = trans('messages.accepted_request_not_found');
                return response($response, 200);
            }

            $acceptedRequestArray = [];
            foreach ($acceptedRequests as $acceptedRequest){
                $list = [];
                $list['id'] = $acceptedRequest->id;
                $list['status'] = $acceptedRequest->status;
                $list['request_status'] = $acceptedRequest->request_status;
                $list['request'] = new RequestResource($acceptedRequest->UserRequest);
                $acceptedRequestArray[] = $list;
            }

            $response["result"] = 1;
            $response["message"] = trans('messages.success');
            $response["myApplies"] = $acceptedRequestArray;
            return response($response, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function deleteAppliedRequest(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'id' => 'required',
            ]);

            if ($validator->fails()) {
                $response["result"] = "0";
                $response["message"] = implode(' ', $validator->errors()->all());
                return response($response, 200);
            }

            $user = $this->getUser($request['session_token']);
            if(empty($user)){
                $response["result"] = 0;
                $response["message"] = trans('messages.user_not_found');
                return response($response, 200);
            }

            $acceptedRequest = AcceptedRequest::where('id', $request['id'])->where('user_id', $user->id)->first();
            if(empty($acceptedRequest)){
                $response["result"] = 0;
                $response["message"] = trans('messages.request_not_found');
                return response($response, 200);
            }

            $logRequest = [
                'timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
                'user_request' => json_encode($acceptedRequest, true),
                'message' => trans('messages.request_deleted_is_successfully'),
                'variables' => json_encode($request->all(), true),
            ];
            Log::info("Delete Applied Request: ==> " . json_encode($logRequest, true));
            $acceptedRequest->delete();

            /*Notification Code Start*/
            $userRequest = UserRequest::where('id', $acceptedRequest['request_id'])->first();
            $users = User::where('id', $userRequest['user_id'])->get();
            $this->messageService->createNotification(
                $user['id'],
                $users,
                NotificationEvent::DELETE_A_REQUEST,
                'messages.request_deleted_title',
                array(),
                'messages.request_deleted_message',
                ['invitee_name' => $user['first_name'] . ' ' . $user['surname']],
                $userRequest);
            /*Notification Code End*/

            $response["result"] = 1;
            $response["message"] = trans('messages.request_deleted_is_successfully');
            return response($response, 200);

        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function nearMeRequests(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'session_token' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
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

            $lat1 = $request['latitude'];
            $lon1 = $request['longitude'];
            $distance = $request['distance'];
            $price_range = $request['price_range'];
            $kids_type = $request['kids_type'];

            $now = date('Y-m-d H:i');

            $user_id = $user->id;
            $myRequestQuery = UserRequest::with('User', 'Kids', 'Groups')->where('user_id', '!=', $user_id)
                ->where('awarded', 0)
                ->where('status', 'active')
                ->where('public_visibility', true)
                ->where('from_date', '>', $now)
                ->whereDoesntHave('AllAcceptedRequest', function($query) use ($user_id) {
                    // Exclude requests that the user has already accepted
                    $query->where('user_id', $user_id);
                });

            if (!empty($price_range)) {
                $myRequestQuery->where('max_amount', '>=', $price_range);
            }

            $myRequests = $myRequestQuery->get();

            if(empty($myRequests)){
                $response["result"] = 0;
                $response["message"] = trans('messages.data_not_found');
                return response($response, 200);
            }

            $myRequestArray = [];
            foreach ($myRequests as $myRequest){
                if(!empty($distance)) {

                    $lat2 = $myRequest['latitude'];
                    $lon2 = $myRequest['longitude'];

                    $theta = $lon1 - $lon2;
                    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
                    $dist = acos($dist);
                    $dist = rad2deg($dist);
                    $miles = $dist * 60 * 1.1515;

                    $distanceKM = number_format((float)($miles * 1.609344), 2, '.', '');


                    if ($distanceKM > $distance) {
                        continue;
                    }
                }

                if(!empty($kids_type)){
                    $kids_available = 0;
                    $kids = $myRequest->Kids;
                    if(!empty($kids)){
                        foreach ($kids as $kid) {
                            if($kids_type == KidsType::NEWBORN->value){
                                if($kid->day < 365){
                                    $kids_available = 1;
                                }
                            } else if($kids_type == KidsType::TODDLER->value){
                                if($kid->day > 365 && $kid->day < 1460){
                                    $kids_available = 1;
                                }
                            } else if($kids_type == KidsType::PRE_SCHOOL->value){
                                if($kid->day > 1460 && $kid->day< 2190){
                                    $kids_available = 1;
                                }
                            } else if($kids_type == KidsType::SCHOOL->value){
                                if($kid->day > 2190){
                                    $kids_available = 1;
                                }
                            }
                        }

                        if($kids_available==0){
                            continue;
                        }
                    }
                }

                $myRequestArray[] = new RequestResource($myRequest);
            }

            if(empty($myRequestArray)){
                $response["result"] = 0;
                $response["message"] = trans('messages.data_not_found');
                return response($response, 200);
            }

            $response["result"] = 1;
            $response["message"] = trans('messages.success');
            $response["myRequests"] = $myRequestArray;
            return response($response, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

}
