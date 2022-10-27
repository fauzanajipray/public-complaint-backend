<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthGoogleController extends Controller
{

    protected $userRepository;

    public function __construct(){
        $this->userRepository = new UserRepository();
    }

    public function redirect(){
        return Socialite::driver('google')->redirect();
    }

    public function callback(){
        $googleUser = Socialite::driver('google')->user();
        $user = User::where('email', $googleUser->email)->first();
        $avatar = $googleUser->avatar;
        if ($user) {
            $token = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'status' => 200,
                'message' => __('auth.login.success', ['name' => $user->name]),
                'user' => $user,
                'token' => $token,
                'errors' => null
            ], 200);
        } else {
            $requests = [
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'password' => Hash::make(Str::random(8)),
                'role_id' => 2,
                'login_type' => 'google',
            ];
            $user = $this->userRepository->create($requests, $avatar);
            $token = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'status' => 200,
                'message' => __('auth.login.success'),
                'user' => $user,
                'token' => $token,
                'errors' => null
            ], 200);
        }
    }
    
}
