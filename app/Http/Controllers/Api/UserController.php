<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FullUserResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserDocument;
use App\Services\MessageService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    protected UserService $userService;
    protected MessageService $messageService;

    public function __construct(UserService $userService, MessageService $messageService)
    {
        $this->userService = $userService;
        $this->messageService = $messageService;
    }

    public function getProfile(Request $request){
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
            }
            $response["result"] = 1;
            $response["user"] = new FullUserResource($user);
            $response["message"] = trans('messages.success');
            return response($response, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function getProfileById(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'user_id' => 'required',
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

            $user = User::where("id", $request['user_id'])->first();

            if (empty($user)) {
                $response["result"] = 0;
                $response["message"] = trans('messages.user_not_found');
                return response($response, 200);
            }
            $response["result"] = 1;
            $response["user"] = new UserResource($user);
            $response["message"] = trans('messages.success');
            return response($response, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function updateProfile(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'session_token' => 'required',
                'first_name' => 'required',
                'surname' => 'required',
                'date_of_birth' => 'required',
                'phone' => 'required|numeric',
                'image' => 'nullable|mimes:jpeg,jpg,bmp,png',
            ]);

            if ($validator->fails()) {
                $response["result"] = "0";
                $response["message"] = implode(' ', $validator->errors()->all());
                return response($response, 200);
            }
            $user = $this->getUser($request['session_token']);

            if(empty($user)) {
                $response["result"] = 0;
                $response["message"] = trans('messages.user_not_found');
                return response($response, 200);
            }
            $input = $request->all();
            $updateFields = array();
            if($photo = $request->file('image')){
                if (!empty($user['image']) && file_exists('public/'.$user['image'])) {
                    unlink('public/'.$user['image']);
                }
                $updateFields['image'] = $this->image($photo,'users','profile');
            }
            $updateFields['profile_type'] = $input['profile_type'] ?? 'both';
            $updateFields['first_name'] = $input['first_name'];
            $updateFields['surname'] = $input['surname'];
            $updateFields['phone'] = preg_replace('/\s+/', '',$request['phone']);
            $updateFields['date_of_birth'] = $input['date_of_birth'];
            $updateFields['aboutme'] = $input['aboutme'];
            $updateFields['language'] = $input['language'];

            $updateFields = $this->setUserAddressField($input, $updateFields);

            $user->update($updateFields);

            try{
                $sent = $this->messageService->sendWelcomeEmail($user);
                if($sent){
                    Log::info("Mail Sent==> Welcome Mail " . $user->email);
                }
            }catch(Exception $e){
                Log::error('Mail Error==>'.$e->getMessage());
            }

            $response["result"] = 1;
            $response["user"] = new FullUserResource($user);
            $response["message"] = trans('messages.profile_update_success');
            return response($response, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function fileUpload(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'session_token' => 'required',
                //'type' => 'required',
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
            }else{
                if ($request->hasFile('user_file')) {
                    UserDocument::where('user_id',$user['id'])->delete();
                    foreach ($request->file('user_file') as $image) {
                        $input['user_id'] = $user['id'];
                        $input['document'] = $this->image($image,'users','document');
                        $input['document_name'] = $request['document_name'];
                        UserDocument::create($input);
                    }
                    $input1['identify'] = 1;
                    $user->update($input1);
                }

                $response["result"] = 1;
                $response["message"] = trans('messages.document_uploaded_success');
                return response($response, 200);
            }
        }catch(Exception $e){
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }


    public function getDocument(Request $request){
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

            if(empty($user)){
                $response["result"] = 0;
                $response["message"] = trans('messages.user_not_found');
                return response($response, 200);
            }

            $userDocuments = UserDocument::where('user_id',$user['id'])->get();
            if(count($userDocuments) == 0) {
                // process not started yet
                $response["result"] = 0;
                $response["identify"] = $user['identify'];
                $response["address_needed"] = $user['address'] == null || $user['address'] == '';
                $response["message"] = trans('messages.document_not_found');
                return response($response, 200);
            }

            foreach ($userDocuments as $list){
                $list['document'] = !empty($list['document']) ? url($list['document']) : '';
            }
            $response["result"] = 1;
            $response["message"] = trans('messages.success');;
            $response["identify"] = $user['identify'];
            $response["userDocuments"] = $userDocuments;
            return response($response, 200);
        }catch(Exception $e){
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function searchMember(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'search' => 'required',
                'searchType' => 'required',
                'session_token' => 'required',
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

            $searchType = $request->input('searchType', 'email');

            $query = User::query();

            if ($searchType == 'phone') {
                $query->where('phone', '=', preg_replace('/\s+/', '',$request['search']));
            } else {
                $query->where('email', '=', $request['search']);
            }
            $users = $query->get();

            if(count($users) == 0) {
                $response["result"] = 0;
                $response["message"] = trans('messages.member_not_found');
                return response($response, 200);
            }

            $response["result"] = 1;
            $response["message"] = trans('messages.success');
            $response["members"] = UserResource::collection($users);
            return response($response, 200);
        }catch(Exception $e){
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }
}
