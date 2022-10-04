<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\ComplaintController as AdminComplaintController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Api\Auth\AuthFacebookController;
use App\Http\Controllers\Auth\AuthGoogleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () { return view('welcome'); });

Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'postRegister']);
Route::get('register/otp-verification', [AuthController::class, 'registerOtpVerification'])->name('register.otp-verification');
Route::post('register/otp-verification', [AuthController::class, 'postRegisterOtpVerification'])->name('register.otp-verification');
Route::get('register/resend-otp', [AuthController::class, 'registerResendOtp'])->name('register.resend-otp');
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postLogin']);
Route::post('logout', [AuthController::class, 'logout']);
Route::get('forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
Route::post('forgot-password', [AuthController::class, 'postForgotPassword'])->name('forgot-password');
Route::get('reset-password', [AuthController::class, 'resetPassword'])->name('user.reset-password');
Route::post('reset-password', [AuthController::class, 'postResetPassword'])->name('user.reset-password');
Route::get('account/verify/{token}', [AuthController::class, 'verifyAccount'])->name('user.verify'); 
Route::get('reset-password/verify/{token}', [AuthController::class, 'resetPassword'])->name('reset-password.verify'); 

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'is_admin'], function() {
    
    Route::get('/', [AdminController::class, 'index']);
    
    Route::resource('complaint', \App\Http\Controllers\Admin\ComplaintController::class)
        ->only(['index', 'show', 'destroy']);
    Route::post('complaint/confirm/{id}', [AdminComplaintController::class, 'confirm'])->name('complaint.confirm');
    Route::post('complaint/reject/{id}', [AdminComplaintController::class, 'reject'])->name('complaint.reject');
    
    Route::resource('user', UserController::class)
        ->only(['index', 'show', 'edit', 'update']);
    Route::put('user/setting/{id}', [UserController::class, 'setting'])->name('user.setting');

    Route::resource('position', PositionController::class);

    Route::resource('profile', ProfileController::class)
        ->only(['index', 'update']);
    
});

// Socialite Facebook
// Route::get('login/facebook', [AuthFacebookController::class, 'redirect'])->name('login.facebook');
// Route::get('login/facebook/callback', [AuthFacebookController::class, 'callback'])->name('login.facebook.callback');

// Socialite Google
Route::get('login/google', [AuthGoogleController::class, 'redirect'])->name('login.google');
Route::get('login/google/callback', [AuthGoogleController::class, 'callback'])->name('login.google.callback');