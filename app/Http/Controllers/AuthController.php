<?php

namespace App\Http\Controllers;

use App\Helpers\Rules\Password;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserVerify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(){
        return view('auth.register');
    }

    public function postRegister(Request $request){
        
        $requests = $request->all();
        Validator::make($requests, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => ['required', 'string', new Password, 'confirmed'],
        ])->validate();

        $requests['password'] = Hash::make($requests['password']);
        $requests['role_id'] = 2;

        $user = User::create($requests);
        if ($user) {

            $token = Str::random(64);
        
            UserVerify::create([
                'user_id' => $user->id, 
                'token' => $token
            ]);

            UserDetail::create([
                'user_id' => $user->id
            ]);

            Mail::send('email.emailVerificationEmail', ['token' => $token], function($message) use($request){
                $message->to($request->email);
                $message->subject('Email Verification Mail');
            });

            return redirect('login')->with('status', 'Anda perlu mengkonfirmasi akun Anda. Kami telah mengirimkan kode aktivasi, silakan periksa email Anda!');
        }
        return redirect('register')->with('status', 'Gagal mendaftar!');
    }

    public function login(){
        return view('auth.login');
    }

    public function postLogin(Request $request){
        
        $requests   = $request->all();
        $data       = User::where('email', $requests['email'])->first();
        if ($data) {
            if (Hash::check($requests['password'], $data->password)) {
                if ($data->is_email_verified) {
                    if($data->role_id == 1){
                        Session::put('admin_id', $data->id);
                        Session::put('admin_name', $data->name);
                        Session::put('admin_email', $data->email);
                        Session::put('admin_role_id', $data->role_id);
                        return redirect('admin')->with('status', 'Selamat datang '.$data->name);
                    }
                    return redirect('login')->with('status', 'Anda tidak memiliki akses!');
                }
                return redirect('login')->with('status', 'Akun Anda belum diverifikasi, 
                silakan periksa email Anda!');
            }
            return redirect('login')->with('status', 'Password salah!');
        }
        return redirect('login')->with('status', 'Email tidak ditemukan!');
    }

    public function logout(){
        Session::flush();
        return redirect('login')->with('status', 'Berhasil logout!');
    }

    public function verifyAccount($token){

        $verifyUser = UserVerify::where('token', $token)->first();
        $message = 'Mohon maaf, emailmu tidak bisa di identifikasi!';
        if(!is_null($verifyUser) ){
            $user = $verifyUser->user;

            if(!$user->is_email_verified) {
                $verifyUser->user->is_email_verified = 1;
                $verifyUser->user->save();
                $message = "Selamat, email verifikasi sukses!";
            } else {
                $message = "Email anda sudah terverifikasi!";
            }
        }
        return redirect()->route('login')->with('status', $message);
    }

    public function forgotPassword(){
        return view('auth.forgot-password');
    }

    public function postForgotPassword(Request $request){
        $requests = $request->all();
        Validator::make($requests, [
            'email' => ['required', 'string', 'email', 'max:255'],
        ])->validate();

        $user = User::where('email', $requests['email'])->first();
        if ($user) {
            $verifyUser = UserVerify::where('user_id', $user->id)->first();
            if (!$verifyUser) {
                return redirect('login')->with('status', 'Akun Anda belum diverifikasi, silakan periksa email Anda!');
            }
            $token = $verifyUser['token'];

            Mail::send('email.forgotPasswordEmail', ['token' => $token], function($message) use($request){
                $message->to($request->email);
                $message->subject('Atur Ulang Password');
            });

            return redirect('login')->with('status', 'Kami telah mengirimkan email untuk reset password, silakan periksa email Anda!');
        }
        return redirect('forgot-password')->with('status', 'Email tidak ditemukan!');
    }

    public function resetPassword($token){
        $verifyUser = UserVerify::where('token', $token)->first();
    
        $message = 'Mohon maaf, emailmu tidak bisa di identifikasi!';
        if(!is_null($verifyUser) ){
            $user = User::where('id', $verifyUser->user_id)->first()->toArray();
            return view('auth.reset-password', compact('user'));
        }
        return redirect('login')->with('status', $message);
    }

    public function postResetPassword(Request $request){
        $requests = $request->all();
        Validator::make($requests, [
            'password' => ['required', 'string', new Password, 'confirmed'],
        ])->validate();

        $user = User::find($requests['user_id']);
        $user->password = Hash::make($requests['password']);
        $user->save();

        return redirect('login')->with('status', 'Password berhasil diubah!');
    }

}
