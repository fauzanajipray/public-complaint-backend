<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\ComplaintController as AdminComplaintController;
use App\Http\Controllers\Admin\UserController;
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
});
