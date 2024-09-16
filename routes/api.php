<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\KidController;
use App\Http\Controllers\Api\RequestController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('login',['middleware' => 'cors' , AccountController::class,'login']);
Route::post('register',['middleware' => 'cors' , AccountController::class,'register']);
Route::post('socialSignup',['middleware' => 'cors' , AccountController::class,'socialSignup']);
Route::post('getProfile',['middleware' => 'cors' , UserController::class,'getProfile']);
Route::post('languages',['middleware' => 'cors' , ApiController::class,'languages']);
Route::post('getProfileById',['middleware' => 'cors' , UserController::class,'getProfileById']);
Route::post('updateProfile',['middleware' => 'cors' , UserController::class,'updateProfile']);
Route::post('fileUpload',['middleware' => 'cors' , UserController::class,'fileUpload']);
Route::post('changePassword',['middleware' => 'cors' , AccountController::class,'changePassword']);
Route::post('resendVerifyCode',['middleware' => 'cors' , AccountController::class,'resendVerifyCode']);
Route::post('forgotPassword',['middleware' => 'cors' , AccountController::class,'forgotPassword']);
Route::post('resetPassword',['middleware' => 'cors' , AccountController::class,'resetPassword']);
Route::post('newGroup',['middleware' => 'cors' , GroupController::class,'newGroup']);
Route::post('newRequest',['middleware' => 'cors' , RequestController::class,'newRequest']);
Route::post('myRequests',['middleware' => 'cors' , RequestController::class,'myRequests']);
Route::post('myGroups',['middleware' => 'cors' , GroupController::class,'myGroups']);
Route::post('emailVerify',['middleware' => 'cors' , AccountController::class,'emailVerify']);
Route::post('addFeedback',['middleware' => 'cors' , ApiController::class,'addFeedback']);
Route::post('deleteGroup',['middleware' => 'cors' , GroupController::class,'deleteGroup']);
Route::post('editRequest',['middleware' => 'cors' , RequestController::class,'editRequest']);
Route::post('addKids',['middleware' => 'cors' , KidController::class,'addKids']);
Route::post('editKids',['middleware' => 'cors' , KidController::class,'editKids']);
Route::post('deleteKids',['middleware' => 'cors' , KidController::class,'deleteKids']);
Route::post('myKids',['middleware' => 'cors' , KidController::class,'myKids']);
Route::post('requestDetails',['middleware' => 'cors' , RequestController::class,'requestDetails']);
Route::post('requestDelete',['middleware' => 'cors' , RequestController::class,'requestDelete']);
Route::post('getDocument',['middleware' => 'cors' , UserController::class,'getDocument']);
Route::post('searchMember',['middleware' => 'cors' , ApiController::class,'searchMember']); //deprecated
Route::post('addToList',['middleware' => 'cors' , GroupController::class,'addToList']);
Route::post('groupMember',['middleware' => 'cors' , ApiController::class,'groupMember']); //deprecated
Route::post('deleteMember',['middleware' => 'cors' , GroupController::class,'deleteMember']);
Route::post('sendInvitation',['middleware' => 'cors' , GroupController::class,'sendInvitation']);
Route::post('forMeRequests',['middleware' => 'cors' , RequestController::class,'forMeRequests']);
Route::post('acceptedRequest',['middleware' => 'cors' , RequestController::class,'acceptedRequest']);
Route::post('updateAwardedBy',['middleware' => 'cors' , RequestController::class,'updateAwardedBy']);
Route::post('myAppliedRequestList',['middleware' => 'cors' , RequestController::class,'myAppliedRequestList']);
Route::post('deleteAppliedRequest',['middleware' => 'cors' , RequestController::class,'deleteAppliedRequest']);
Route::post('deleteAccount',['middleware' => 'cors' , AccountController::class,'deleteAccount']);
Route::post('updateDeviceDetails',['middleware' => 'cors' , AccountController::class,'updateDeviceDetails']);
Route::post('nearMeRequests',['middleware' => 'cors' , RequestController::class,'nearMeRequests']);
Route::get('translation',[ ApiController::class,'readTranslationFile']);
Route::post('testPush',['middleware' => 'cors' , ApiController::class,'testPush']);
Route::post('myNotification',['middleware' => 'cors' , ApiController::class,'myNotification']);
Route::post('notificationDetail',['middleware' => 'cors' , ApiController::class,'notificationDetail']);
Route::post('unreadNotification',['middleware' => 'cors' , ApiController::class,'unreadNotification']);
Route::post('memberInGroup',['middleware' => 'cors' , GroupController::class,'memberInGroup']);
Route::post('groupInvitation',['middleware' => 'cors' , GroupController::class,'groupInvitation']);
Route::post('logout',['middleware' => 'cors' , AccountController::class,'logout']);
