<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\RequestController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

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

Route::post('groupMember',['middleware' => 'cors' , GroupController::class,'groupMember']);
Route::post('searchMember',['middleware' => 'cors' , UserController::class,'searchMember']);
Route::get('translation',[ ApiController::class,'translation']);
Route::get('translation2',[ ApiController::class,'translation2']);
Route::post('newRequest',['middleware' => 'cors' , RequestController::class,'newRequest_v2']);
Route::post('editRequest',['middleware' => 'cors' , RequestController::class,'editRequest_v2']);
