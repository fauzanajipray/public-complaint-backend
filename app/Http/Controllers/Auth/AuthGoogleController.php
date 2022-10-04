<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthGoogleController extends Controller
{
    const PROVIDER = 'google';
    protected $userRepository;

    public function __construct(){
        $this->userRepository = new UserRepository();
    }

    public function redirect(){
        return Socialite::driver(static::PROVIDER)->redirect();
    }

    public function callback(){
        $googleUser = Socialite::driver(static::PROVIDER)->user();
        $user = User::where('email', $googleUser->email)->first();
        $userOauth = User::with('oauth')->whereHas('oauth', function($query) use ($googleUser){
            $query->where('provider', static::PROVIDER)
                ->where('provider_id', $googleUser->getId());
        })->first();
        if(!$user){
            $user = $this->userRepository->create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => Hash::make(Str::random(12)),
                'role_id' => 2,
                'email_verified_at' => now(),
                'email_verified' => 1,
            ], $googleUser->avatar);
            
            $user->oauth()->create([
                'provider' => static::PROVIDER,
                'provider_id' => $googleUser->getId(),
            ]);

            if($user->role_id == 1){
                Session::put('admin_id', $user->id);
                Session::put('admin_name', $user->name);
                Session::put('admin_email', $user->email);
                Session::put('admin_role_id', $user->role_id);
                return redirect('admin')->with('status', 'Selamat datang '.$user->name);
            }
            return redirect('login')->with('status', 'Anda tidak memiliki akses!');
        } else {
            if(!$userOauth){
                $user->oauth()->create([
                    'provider' => static::PROVIDER,
                    'provider_id' => $googleUser->getId(),
                ]);
            }
            if($user->is_email_verified == 2){
                $user->is_email_verified = 1;
                $user->email_verified_at = now();
                $user->save();
            }
            if($user->role_id == 1){
                Session::put('admin_id', $user->id);
                Session::put('admin_name', $user->name);
                Session::put('admin_email', $user->email);
                Session::put('admin_role_id', $user->role_id);
                Session::put('admin_avatar', $user->detail->avatar);
                return redirect('admin')->with('status', 'Selamat datang '.$user->name);
            }
            return redirect('login')->with('status', 'Anda tidak memiliki akses!');
        }
    }
}
