<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AuthController;
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

Route::get('admin', [AdminController::class, 'index'])->middleware(['is_admin', 'is_verify_email']);


Route::get('register', [AuthController::class, 'register']);

Route::post('register', [AuthController::class, 'postRegister']);

Route::get('login', [AuthController::class, 'login'])->name('login');

Route::post('login', [AuthController::class, 'postLogin']);

Route::post('logout', [AuthController::class, 'logout']);

Route::get('forgot-password', [AuthController::class, 'login'])->name('forgot-password');

Route::get('account/verify/{token}', [AuthController::class, 'verifyAccount'])->name('user.verify'); 

Route::middleware(['is_admin', 'is_verify_email'])->group(function () {

    Route::group(['middleware' => 'role:admin', 'prefix' => 'admin', 'as' => 'admin.'], function() {
        Route::resource('complaint', \App\Http\Controllers\Admin\ComplaintController::class)
            ->only(['index', 'show', 'destroy']);
    });

    Route::group(['middleware' => 'role:user', 'prefix' => 'user', 'as' => 'user.'], function() {
        Route::resource('complaint', \App\Http\Controllers\User\ComplaintController::class)
            ->only(['index']);
    });

});
