<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RequestResource;
use App\Models\GroupMember;
use App\Models\Kids;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserRequest;
use App\Models\UserRequestGroup;
use App\Models\UserRequestKids;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class KidController extends Controller
{

    public function __construct()
    {
    }

    public function addKids(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'session_token' => 'required',
                'name' => 'required',
                'date_of_birth' => 'required',
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
                $input['user_id'] = $user['id'];
                Kids::create($input);

                $response["result"] = 1;
                $response["message"] = trans('messages.kids_added_successfully');
                return response($response, 200);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function editKids(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'session_token' => 'required',
                'kids_id' => 'required',
                'name' => 'required',
                'date_of_birth' => 'required',
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
                $kids = Kids::where('id',$request['kids_id'])->where('user_id',$user['id'])->first();
                if(empty($kids)){
                    $response["result"] = 0;
                    $response["message"] = trans('messages.kids_not_found');
                    return response($response, 200);
                }else{
                    $input = $request->all();
                    $kids->update($input);

                    $response["result"] = 1;
                    $response["message"] = trans('messages.kids_is_successfully_updated');
                    return response($response, 200);
                }
            }
        }catch(Exception $e){
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function deleteKids(Request $request){
        app()->setLocale($request['language']);
        try{
            $validator = Validator::make($request->post(), [
                'session_token' => 'required',
                'kids_id' => 'required',
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
                $kids = Kids::where('id',$request['kids_id'])->where('user_id',$user['id'])->first();
                if(empty($kids)){
                    $response["result"] = 0;
                    $response["message"] = "kids not found";
                    return response($response, 200);
                }else{
                    $kids->delete();

                    $response["result"] = 1;
                    $response["message"] = trans('messages.kids_is_successfully_deleted');
                    return response($response, 200);
                }
            }
        }catch(Exception $e){
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

    public function myKids(Request $request){
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
                $kids = Kids::where('user_id',$user['id'])->get();
                if(empty($kids)){
                    $response["result"] = 0;
                    $response["message"] = trans('messages.kids_not_found');
                    return response($response, 200);
                }else{
                    $response["result"] = 1;
                    $response["message"] = trans('messages.success');
                    $response["kids"] = $kids;
                    return response($response, 200);
                }
            }
        }catch(Exception $e){
            Log::error($e->getMessage(), ['exception' => $e]);
            $response["message"] = trans('messages.general_error');
            return response($response, 500);
        }
    }

}
