<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\VerificationMail;
use App\Models\Enums\NotificationEvent;
use App\Models\Group;
use App\Models\Kids;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserDocument;
use App\Models\UserRequest;
use App\Models\UserRequestGroup;
use App\Services\MessageService;
use App\Services\UserService;
use Illuminate\Http\Request;
use DataTables;
use DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    protected MessageService $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->middleware('auth');
        $this->messageService = $messageService;
    }

    public function index(Request $request)
    {
        $data['menu'] = "Users";
        $data['search'] = $request['search'];

        if ($request->ajax()) {
            $data = User::with('Documents')->select();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($user) {
                    return $user->status;
                })
                ->addColumn('usage_level', function ($user) {
                    return $user->calculateUsageLevel() . '%';
                })
                ->addColumn('action', function ($user) {
                    $records['row'] = $user;
                    return view('admin.users.action',$records);
                })
                ->rawColumns(['status', 'usage_level', 'action'])
                ->make(true);
        }

        return view('admin.users.index', $data);
    }

    public function create()
    {
        $data['menu'] = "Users";
        return view("admin.users.create", $data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'surname' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'address' => 'required',
            'street' => 'required',
            'zip' => 'required',
            'city' => 'required',
            'phone' => 'required|numeric',
           // 'gender' => 'required',
            'date_of_birth' => 'required',
            'image' => 'mimes:jpeg,jpg,bmp,png',
            'status' => 'required',
        ]);

        $input = $request->all();
        $input['role'] = 'user';
        if($photo = $request->file('image')){
            $input['image'] = $this->image($photo,'users');
        }
        $user = User::create($input);

        \Session::flash('success', 'User has been inserted successfully!');
        return redirect()->route('users.index');
    }

    public function show($id)
    {
        $route =  \Request::route()->getName();
        $data['menu'] = $route == 'showDocument' ? 'Identification' : "Users";
        $data['documents'] = UserDocument::where('user_id',$id)->get();
        $data['user'] = User::where('id',$id)->first();
        return view("admin.users.document", $data);
    }

    public function edit($id)
    {
        $data['menu'] = "Users";
        $data['user'] = User::findorFail($id);
        return view('admin.users.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'surname' => 'required',
            'email' => ['required', Rule::unique('users')->ignore($id)->whereNull('deleted_at')],
            //'email' => 'required|email|unique:users,email,' . $id . ',id',
            'password' => 'nullable|confirmed|min:6',
            'address' => 'required',
            'street' => 'required',
            'zip' => 'required',
            'city' => 'required',
            'phone' => 'required|numeric',
            //'gender' => 'required',
            'date_of_birth' => 'required',
            'image' => 'nullable|mimes:jpeg,jpg,bmp,png',
            'status' => 'required',
        ]);

        if (empty($request['password'])) {
            unset($request['password']);
        }

        $input = $request->all();
        $user = User::findorFail($id);
        if($photo = $request->file('image')){
            if (!empty($user['image'])) {
                unlink($user['image']);
            }
            $input['image'] = $this->image($photo,'users');
        }
        $user->update($input);

        \Session::flash('success', 'User has been updated successfully!');
        return redirect()->route('users.index');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if (!empty($user)) {
            $file_path=storage_path('app/public/'.$user['image']);
            if (!empty($user['image']) && file_exists($file_path)) {
                unlink($file_path);
            }
            $user->delete();
            return 1;
        } else {
            return 0;
        }
    }

    public function assign(Request $request)
    {
        $users = User::findorFail($request['id']);
        $users['status'] = "active";
        $users->update($request->all());
    }

    public function unassign(Request $request)
    {
        $users = User::findorFail($request['id']);
        $users['status'] = "inactive";
        $users->update($request->all());
    }

    public function sendVerification(Request $request)
    {
        $user = User::findorFail($request['id']);
        Mail::to($user->email)->send(new VerificationMail($user->first_name ,$user->email_verification_code, $user->language ?? 'de'));
    }

    public function sendWelcomeMail(Request $request)
    {
        echo "Mails sent: " . $this->messageService->sendToEveryUserWelcomeMail();
    }

    public function myKids($id, Request $request){
        $data['menu'] = "Users";

        if ($request->ajax()) {
            $data = Kids::where('user_id',$id)->select();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return $row->status;
                })
                ->addColumn('age', function ($row) {
                    $ageCount = '';
                    if(!empty($row->year)){
                        $ageCount .= $row->year;
                    }
                    if(!empty($row->month)){
                        if(!empty($row->year)){
                            $ageCount .= ', ';
                        }
                        $ageCount .= $row->month;
                    }
                    return $ageCount;
                })
                ->addColumn('action', function ($row) {
                    return $row;
                })
                ->rawColumns(['age','status','action'])
                ->make(true);
        }
        $data['user'] = User::where('id',$id)->first();
        return view('admin.users.kids', $data);
    }

    public function kidsDetail($id){
        $kids = Kids::where('id',$id)->first();
        if(!empty($kids)){
            $data['status'] = 1;
            $data['name'] = $kids['name'];
            $data['dob'] = $kids['date_of_birth'];
        }else{
            $data['status'] = 0;
        }
        return $data;
    }

    public function kidsUpdateDetail($id, Request $request){
        $kids = Kids::where('id',$id)->first();
        if(!empty($kids)){
            $input = $request->all();
            $kids->update($input);
            return 1;
        }else{
            return 0;
        }
    }

    public function kidsAssign(Request $request)
    {
        $users = Kids::findorFail($request['id']);
        $users['status'] = "active";
        $users->update($request->all());
    }

    public function kidsUnassign(Request $request)
    {
        $users = Kids::findorFail($request['id']);
        $users['status'] = "inactive";
        $users->update($request->all());
    }

    public function kidsDelete($id)
    {
        $kids = Kids::findOrFail($id);
        if (!empty($kids)) {
            $kids->delete();
            return 1;
        } else {
            return 0;
        }
    }

    public function myGroups($id, Request $request){
        $data['menu'] = "Users";

        if ($request->ajax()) {
            $data = Group::where('user_id',$id)->select();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d');
                })
                ->addColumn('status', function ($row) {
                    return $row->status;
                })
                ->addColumn('action', function ($row) {
                    return $row;
                })
                ->rawColumns(['created_at','status','action'])
                ->make(true);
        }
        $data['user'] = User::where('id',$id)->first();
        return view('admin.users.group', $data);
    }

    public function groupDetail($id){
        $group = Group::where('id',$id)->first();
        if(!empty($group)){
            $data['status'] = 1;
            $data['name'] = $group['name'];
        }else{
            $data['status'] = 0;
        }
        return $data;
    }

    public function groupUpdateDetail($id, Request $request){
        $group = Group::where('id',$id)->first();
        if(!empty($group)){
            $input = $request->all();
            $group->update($input);
            return 1;
        }else{
            return 0;
        }
    }

    public function groupAssign(Request $request)
    {
        $group = Group::findorFail($request['id']);
        $group['status'] = "active";
        $group->update($request->all());
    }

    public function groupUnassign(Request $request)
    {
        $group = Group::findorFail($request['id']);
        $group['status'] = "inactive";
        $group->update($request->all());
    }

    public function groupDelete($id)
    {
        $group = Group::findOrFail($id);
        if (!empty($group)) {
            $group->delete();
            return 1;
        } else {
            return 0;
        }
    }

    public function myRequest($id, Request $request){
        $data['menu'] = "Users";

        if ($request->ajax()) {
            $data = UserRequest::where('user_id',$id)->select();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('from_date', function ($row) {
                    return date('Y-m-d h:i:s', strtotime($row->from_date));
                })
                ->addColumn('to_date', function ($row) {
                    return date('Y-m-d h:i:s', strtotime($row->to_date));
                })
                ->addColumn('group_visibility', function ($row) {
                    return ucfirst($row->group_visibility);
                })
                ->addColumn('public_visibility', function ($row) {
                    return ucfirst($row->public_visibility);
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d');
                })
                ->addColumn('status', function ($row) {
                    return $row->status;
                })
                ->addColumn('action', function ($row) {
                    return $row;
                })
                ->rawColumns(['from_date','to_date','group_visibility','public_visibility','status','created_at','action'])
                ->make(true);
        }
        $data['user'] = User::where('id',$id)->first();
        $data['group'] = Group::where('user_id',$id)->where('status','active')->pluck('name','id');
        return view('admin.users.userRequest', $data);
    }

    public function userRequestDetail($id){
        $userRequest = UserRequest::where('id',$id)->first();
        if(!empty($userRequest)){
            $group = Group::where('user_id',$userRequest['user_id'])->where('status','active')->select('name','id')->get();
            $data['status'] = 1;
            $data['userRequest'] = $userRequest;

            $gids = UserRequestGroup::where('request_id',$userRequest['id'])->pluck('group_id')->toArray();
            $select = '<select name="group_id[]" class="form-control select2" data-placeholder="Select Group" multiple style="width: 100%">';
            foreach ($group as $list){
                $selected = count($gids) > 0 && in_array($list['id'],$gids) ? 'selected' : '';
                $select .= '<option value="'.$list['id'].'" '.$selected.'>'.$list['name'].'</option>';
            }
            $select .= '</select>';
            $data['group'] = $select;
        }else{
            $data['status'] = 0;
        }
        return $data;
    }

    public function userRequestUpdateDetail($id, Request $request){
        $userRequest = UserRequest::where('id',$id)->first();
        if(!empty($userRequest)){
            $input = $request->all();
            //$input['group_id'] = !empty($request['group_id']) ? implode(',',$request['group_id']) : '';
            $userRequest->update($input);

            $gIds = !empty($request['group_id']) ? $request['group_id'] : [];
            if(count($gIds) > 0){
                UserRequestGroup::where('request_id',$userRequest['id'])->delete();
                foreach ($gIds as $gd){
                    $in['request_id'] = $userRequest['id'];
                    $in['group_id'] = $gd;
                    UserRequestGroup::create($in);
                }
            }
            return 1;
        }else{
            return 0;
        }
    }

    public function userRequestAssign(Request $request)
    {
        $userRequest = UserRequest::findorFail($request['id']);
        $userRequest['status'] = "active";
        $userRequest->update($request->all());
    }

    public function userRequestUnassign(Request $request)
    {
        $userRequest = UserRequest::findorFail($request['id']);
        $userRequest['status'] = "inactive";
        $userRequest->update($request->all());
    }

    public function userRequestDelete($id)
    {
        $userRequest = UserRequest::findOrFail($id);
        if (!empty($userRequest)) {
            $userRequest->delete();
            return 1;
        } else {
            return 0;
        }
    }

    public function updateDocumentStatus(Request $request, $id){
        $this->validate($request, [
            'identify' => 'required',
        ]);

        $input = $request->all();
        $user = User::findorFail($id);
        app()->setLocale($user->language ?: "de");

        $input['identify'] = $request['identify'] == 2 ? 2 : ($request['identify'] == 3 ? 3 : 1);
        $input['document_rejection_reason'] = $request['identify'] == 3 ? $request['document_rejection_reason'] : '';
        $user->update($input);

        //if($input['identify'] == 3){
            $documents = UserDocument::where('user_id',$id)->get();
            if(count($documents) >0){
                foreach ($documents as $doc){
                    $destinationPath = public_path($doc['document']);
                    if(file_exists($destinationPath)){
                        unlink($destinationPath);
                    }
                    $doc->delete();
                }
            }
        //}

        /*Notification Code Start*/
        $users = User::where('id',$id)->get();
        if($request['identify'] == 3){
            $body_key = 'messages.document_status_reject_msg';
        }else{
            $body_key = 'messages.document_status_msg';
        }
        $this->messageService->createNotification(Auth::user()->id,
            $users,
            NotificationEvent::UPDATE_DOCUMENT_STATUS,
            'messages.document_status_updated',
            array(),
            $body_key,
            array('reason', $request['document_rejection_reason']));
        /*Notification Code End*/

        \Session::flash('success', 'User document status has been updated successfully!');
        if($request['redirection'] == 'Users'){
            return redirect()->route('users.index');
        }else{
            return redirect()->route('openIdentify');
        }
    }
}
