<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\ProfileUpdateController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FeedbackController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::get('logout', [LoginController::class,'logout']);
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');

    /*IMAGE UPLOAD IN SUMMER NOTE*/
    Route::post('image/upload', [ImageController::class,'upload_image']);

    Route::resource('profile_update', ProfileUpdateController::class);

    /* CUSTOMER MANAGEMENT */
    Route::post('users/assign', [UserController::class,'assign'])->name('users.assign');
    Route::post('users/unassign', [UserController::class,'unassign'])->name('users.unassign');

    Route::post('kids/assign', [UserController::class,'kidsAssign'])->name('kids.assign');
    Route::post('kids/unassign', [UserController::class,'kidsUnassign'])->name('kids.unassign');
    Route::post('kids/delete/{id}', [UserController::class,'kidsDelete'])->name('kids.delete');
    Route::post('kids/details/{id}', [UserController::class,'kidsDetail'])->name('kids.detail');
    Route::post('kids/updateDetails/{id}', [UserController::class,'kidsUpdateDetail'])->name('kids.updateDetail');

    Route::post('group/assign', [UserController::class,'groupAssign'])->name('group.assign');
    Route::post('group/unassign', [UserController::class,'groupUnassign'])->name('group.unassign');
    Route::post('group/delete/{id}', [UserController::class,'groupDelete'])->name('group.delete');
    Route::post('group/details/{id}', [UserController::class,'groupDetail'])->name('group.detail');
    Route::post('group/updateDetails/{id}', [UserController::class,'groupUpdateDetail'])->name('group.updateDetail');
    Route::post('userDocument/{id}', [UserController::class,'updateDocumentStatus'])->name('document.updateStatus');

    Route::post('userRequest/assign', [UserController::class,'userRequestAssign'])->name('userRequest.assign');
    Route::post('userRequest/unassign', [UserController::class,'userRequestUnassign'])->name('userRequest.unassign');
    Route::post('userRequest/delete/{id}', [UserController::class,'userRequestDelete'])->name('userRequest.delete');
    Route::post('userRequest/details/{id}', [UserController::class,'userRequestDetail'])->name('userRequest.detail');
    Route::post('userRequest/updateDetails/{id}', [UserController::class,'userRequestUpdateDetail'])->name('userRequest.updateDetail');

    Route::resource('users', UserController::class);
    Route::post('users/sendVerification', [UserController::class,'sendVerification'])->name('users.sendVerification');
    Route::post('users/welcome', [UserController::class,'sendWelcomeMail'])->name('users.welcome');

    Route::get('openIdentify/{id}', [UserController::class,'show'])->name('showDocument');
    Route::get('openIdentify', [DashboardController::class,'openIdentify'])->name('openIdentify');

    Route::get('users/kids/{id}', [UserController::class,'myKids'])->name('users.myKids');
    Route::get('users/group/{id}', [UserController::class,'myGroups'])->name('users.myGroups');
    Route::get('users/userRequest/{id}', [UserController::class,'myRequest'])->name('users.myRequest');

    /* FEEDBACK MANAGEMENT*/
    Route::resource('feedback', FeedbackController::class);

    Route::resource('trigger', AdminController::class);
    Route::post('/trigger-notifications', [AdminController::class, 'triggerNotifications'])
        ->name('admin.trigger.notifications');

    Auth::routes();
});


Route::get('/admin',[LoginController::class,'showAdminLoginForm'])->name('admin.login-view');
Route::post('/admin',[LoginController::class,'adminLogin'])->name('admin.login');

Route::get('locale/{locale}', function ($locale){
    \Illuminate\Support\Facades\Session::put('locale', $locale);
    return redirect()->back();
});

Route::get('/', [HomeController::class,'index'])->name('home.index');
// de
Route::prefix('de')->name('de.')->group(function () {
    Route::get('/home', [HomeController::class, 'home'])->name('home.index');
    Route::get('/privacy-policy', [HomeController::class, 'showPrivacyPolicy'])->name('privacyPolicy');
    Route::get('/gtc', [HomeController::class, 'showGtc'])->name('gtc');
});

// en
Route::prefix('en')->name('en.')->group(function () {
    Route::get('/home', [HomeController::class, 'home'])->name('home.index');
    Route::get('/privacy-policy', [HomeController::class, 'showPrivacyPolicy'])->name('privacyPolicy');
    Route::get('/gtc', [HomeController::class, 'showGtc'])->name('gtc');
});

Route::get('my-applies', [HomeController::class,'my_applies'])->name('my_applies');
Route::post('sendContactUs', [HomeController::class,'sendContactUs'])->name('sendContactUs');
Route::get('privacyPolicy', [HomeController::class, 'showPrivacyPolicy'])->name('privacyPolicy');
Route::get('gtc', [HomeController::class, 'showGtc'])->name('gtc');

//Route::group(['middleware' => 'web'], function () {
//    Route::get('logout', [LoginController::class,'logout']);
//    Route::auth();
//    Route::get('home/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//});
