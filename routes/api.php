<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\User\ComplaintController as UserComplaintController;
use App\Http\Controllers\Api\Staff\ComplaintController as StaffComplaintController;
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
Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('logout', [AuthController::class, 'logout']);
    
    // make route group prefix user
    Route::group(['prefix' => 'user', 'middleware' => 'role.api:user'], function () {
        Route::resource('complaints', UserComplaintController::class);
    });
    
    // make route group prefix staff
    Route::group(['prefix' => 'staff', 'middleware' => 'role.api:staff'], function () {
        Route::resource('complaints', StaffComplaintController::class);
    });

});

Route::post('login', [AuthController::class, 'login']);

Route::post('register', [AuthController::class, 'register']);
