<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GroupMemberResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResource;
use App\Models\Enums\NotificationEvent;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\Notification;
use App\Models\User;
use App\Services\FeedItemService;
use App\Services\MessageService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{

    protected MessageService $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function newGroup(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'session_token' => 'required',
                'name' => 'required',
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
                $group = Group::where('user_id',$user['id'])->where('name',$request['name'])->first();
                if(!empty($group)){
                    $response["result"] = 1;
                    $response["message"] = trans('messages.group_is_already_exist');
                    return response($response, 200);
                }else{
                    $input = $request->all();
                    $input['user_id'] = $user['id'];
                    $group = Group::create($input);

                    $response["result"] = 1;
                    $response["message"] = trans('messages.group_is_successfully_created');
                    $response["group"] = new GroupResource($group);
                    return response($response, 200);
                }
            }
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function myGroups(Request $request){
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
                $mygroups = Group::where('user_id',$user['id'])->get();
                if(empty($mygroups)){
                    $response["result"] = 0;
                    $response["message"] = trans('messages.data_not_found');
                    return response($response, 200);
                }else{
                    foreach ($mygroups as $group){
                        $groupMembers = GroupMember::with('Member')->where('group_id',$group['id'])->get();
                        $group['total_members'] = count($groupMembers);
                    }
                    $response["result"] = 1;
                    $response["message"] = trans('messages.success');
                    $response["myGroups"] = $mygroups;
                    return response($response, 200);
                }
            }
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function deleteGroup(Request $request){
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
            }else {
                $group = Group::where('id',$request['group_id'])->where('user_id',$user['id'])->first();
                if(empty($group)){
                    $response["result"] = 0;
                    $response["message"] = trans('messages.group_not_found');
                    return response($response, 200);
                }else{
                    $group->delete();
                    $response["result"] = 1;
                    $response["message"] = trans('messages.group_deleted_successfully');
                    return response($response, 200);
                }
            }
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function groupMember(Request $request){
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

            $groupMembers = GroupMember::with('Member')
                ->leftJoin('users', 'group_members.member_id', '=', 'users.id')
                ->where('group_members.group_id', $group->id)
                ->orderBy('group_members.status', 'desc')
                ->orderBy('users.first_name')
                ->orderBy('users.surname')
                ->orderBy('group_members.created_at')
                ->select('group_members.*')
                ->get();

            if(count($groupMembers) == 0){
                $response["result"] = 0;
                $response["message"] = trans('messages.member_not_found');
                return response($response, 200);
            }

            $response["result"] = 1;
            $response["message"] = trans('messages.success');
            $response["members"] = GroupMemberResource::collection($groupMembers);
            return response($response, 200);
        }catch(Exception $e){
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }


    public function addToList(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'session_token' => 'required',
                'group_id' => 'required',
                'member_id' => 'required',
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

            $member = User::where('id',$request['member_id'])->where('role','user')->first();
            if(empty($member)){
                $response["result"] = 0;
                $response["message"] = trans('messages.member_not_found');
                return response($response, 200);
            }

            if($user['id'] == $request['member_id']){
                $response["result"] = 0;
                $response["message"] =trans('messages.you_can_not_add_your_self_to_a_group');
                return response($response, 200);
            }

            $membershipSameGroup = GroupMember::where('group_id', $group->id)->where('member_id',$request['member_id'])->first();

            if(!empty($membershipSameGroup)){
                if ($membershipSameGroup['status'] == 1) {
                    $response["result"] = 0;
                    $response["message"] = trans('messages.this_member_is_already_added');
                    return response($response, 200);
                } else {
                    $response["result"] = 0;
                    $response["message"] = trans('messages.this_member_is_already_invited');
                    return response($response, 200);
                }
            }

            $highestMembership = GroupMember::where('member_id', $member->id)
                ->whereHas('Group', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->orderBy('status', 'desc')
                ->first();
            if(!empty($highestMembership)) {
                $input = $request->all();
                $input['member_email'] = strtolower($member['email']);
                $input['member_id'] = $member['id'];
                $input['status'] = $highestMembership['status'] == 1 ? 1 : 0;
                GroupMember::create($input);

                $response["result"] = 1;
                $response["message"] = $highestMembership['status'] == 1 ? trans('messages.member_added_successfully') : trans('messages.invitation_send_successfully');
                return response($response, 200);
            }

            $input = $request->all();
            $input['member_email'] = strtolower($member['email']);
            $input['member_id'] = $member['id'];
            $input['status'] = 0;
            GroupMember::create($input);

            /*Notification Code Start*/
            $users = User::where('id',$member['id'])->get();
            $this->messageService->createNotification(
                $user['id'],
                $users,
                NotificationEvent::NEW_GROUP_INVITATION,
                'messages.group_invitation_title',
                array(),
                'messages.group_invitation_message',
                ['sender_name'=>$user['first_name'].' '.$user['surname']]);
            /*Notification Code End*/

            $response["result"] = 1;
            $response["message"] = trans('messages.invitation_send_successfully');
            return response($response, 200);
        }catch(Exception $e){
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function deleteMember(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'session_token' => 'required',
                'id' => 'required',
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
            }else{
                $groupMember = GroupMember::where('id',$request['id'])->first();

                if(empty($groupMember)){
                    $response["result"] = 0;
                    $response["message"] = trans('messages.member_not_found');
                    return response($response, 200);
                }else{
                    $gIds = Group::where('user_id',$user['id'])->pluck('id');
                    $allGroupMember = GroupMember::whereIn('group_id',$gIds)->where('member_id',$groupMember['member_id'])->delete();

                    $response["result"] = 1;
                    $response["message"] = trans('messages.member_deleted_successfully');
                    return response($response, 200);
                }
            }
        }catch(Exception $e){
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function sendInvitation(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'session_token' => 'required',
                'group_id' => 'required',
                'member_email' => 'required',
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
                $response["message"] = trans('messages.data_not_found');
                return response($response, 200);
            }

            $groupMember = GroupMember::where('group_id',$request['group_id'])
                ->where('member_email',strtolower($request['member_email']))->first();

            if(!empty($groupMember)){
                if($groupMember['member_id'] == 0){
                    $response["result"] = 0;
                    $response["message"] = trans('messages.invitation_already_send');
                    return response($response, 200);
                }else{
                    $response["result"] = 0;
                    $response["message"] = trans('messages.member_already_added');
                    return response($response, 200);
                }
            }
            $input = $request->all();
            $input['member_email'] = strtolower($request['member_email']);
            $input['member_id'] = null;
            $input['status'] = 0;
            GroupMember::create($input);

            $data['email'] = $request['member_email'];
            $data['group_name'] = $group['name'];
            $data['language'] = $request['language'];
            $data['inviter'] = $user['first_name'].' '.$user['surname'];

            $this->sendMail($data, trans('messages.invitationSubject', ['inviter' => $data['inviter']]),'invitation');

            $response["result"] = 1;
            $response["message"] = trans('messages.invitation_send_successfully');
            return response($response, 200);
        }catch(Exception $e){
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }


    public function memberInGroup(Request $request){
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
            }else{
                $groups = [];
                $requestedGroups = [];
                $inGroupLines = DB::table('group_members')
                    ->leftJoin('groups', 'group_members.group_id', '=', 'groups.id')
                    ->leftJoin('users', 'groups.user_id', '=', 'users.id')
                    ->selectRaw('MAX(group_members.group_id) as max_group_id, users.first_name, users.surname')
                    ->where('group_members.status', '=', 1) // already accepted
                    ->where('group_members.member_id', '=', $user['id'])
                    ->groupBy('users.first_name', 'users.surname')
                    ->get();

                foreach($inGroupLines as $inGroupLine){
                    $groups[] = [
                        'id' => $inGroupLine->max_group_id,
                        'inviteeName' => $inGroupLine->first_name . ' ' . $inGroupLine->surname
                    ];
                }

                $requestedGroupLines = DB::table('group_members')
                    ->leftJoin('groups', 'group_members.group_id', '=', 'groups.id')
                    ->leftJoin('users', 'groups.user_id', '=', 'users.id')
                    ->selectRaw('MAX(group_members.group_id) as max_group_id, users.first_name, users.surname')
                    ->where('group_members.status', '=', 0) // requested
                    ->where('group_members.member_id', '=', $user['id'])
                    ->groupBy('users.first_name', 'users.surname')
                    ->get();

                foreach($requestedGroupLines as $requestedGroupLine){
                    $requestedGroups[] = [
                        'id' => $requestedGroupLine->max_group_id,
                        'inviteeName' => $requestedGroupLine->first_name . ' ' . $requestedGroupLine->surname
                    ];
                }

                $response["result"] = 1;
                $response["message"] = trans('messages.success');
                $response["inGroups"] = $groups;
                $response["requestedGroups"] = $requestedGroups;
                return response($response, 200);
            }
        }catch(Exception $e){
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function groupInvitation(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'session_token' => 'required',
                'group_id' => 'required',
                'type' => 'required',
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
            }else{
                $groupMember = GroupMember::with('Group')->where('group_id',$request['group_id'])
                    ->where('member_id',$user['id'])->first();

                if(empty($groupMember)){
                    $response["result"] = 0;
                    $response["message"] = trans('messages.group_request_not_found');
                    return response($response, 200);
                } else {
                    if($request['type'] == 1){
                        if($groupMember['status'] == 1){
                            $response["result"] = 0;
                            $response["message"] = trans('messages.member_already_added');
                            return response($response, 200);
                        }else{
                            $input['status'] = 1;
                            $groupMember->update($input);
                        }
                        $gIds = Group::where('user_id', $groupMember->Group->user_id)->pluck('id');
                        $allGroupMember = GroupMember::whereIn('group_id',$gIds)->where('member_id',$user['id'])->get();
                        if(count($allGroupMember)>0){
                            foreach($allGroupMember as $list){
                                $input['status'] = 1;
                                $list->update($input);
                            }
                        }

                        /*Notification Code Start*/
                        $users = User::where('id', $groupMember->Group->user_id)->get();
                        $this->messageService->createNotification(
                            $user['id'],
                            $users,
                            NotificationEvent::INVITEE_HAVE_BEEN_ADDED,
                            'messages.invitee_title',
                            array(),
                            'messages.invitee_message',
                            ['invitee_name'=>$user['first_name'].' '.$user['surname']]);
                        /*Notification Code End*/

                        $response["result"] = 1;
                        $response["message"] = trans('messages.group_request_accepted');
                        return response($response, 200);
                    } else {
                        $groupOwner = $groupMember->Group->user_id;
                        $gIds = Group::where('user_id', $groupOwner)->pluck('id');
                        $groupMember->delete();

                        GroupMember::whereIn('group_id',$gIds)->where('member_id',$user['id'])->delete();

                        /*Notification Code Start*/
                        $users = User::where('id',$groupOwner)->get();
                        $this->messageService->createNotification(
                            $user['id'],
                            $users,
                            NotificationEvent::GROUP_INVITATION_REJECTED,
                            'messages.invitee_rejected_title',
                            array(),
                            'messages.invitee_rejected_message',
                            ['invitee_name'=>$user['first_name'].' '.$user['surname']]);
                        /*Notification Code End*/

                        $response["result"] = 1;
                        $response["message"] = trans('messages.group_request_rejected');
                        return response($response, 200);
                    }
                }
            }
        }catch(Exception $e){
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }
}
