<?php

namespace App\Http\Controllers;

use App\Helpers\Rules\Password;
use App\Models\User;
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
        
        // $requests['image'] = "";

        $user = User::create($requests);
        if ($user) {

            $token = Str::random(64);
        
            UserVerify::create([
                'user_id' => $user->id, 
                'token' => $token
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
        $cek        = Hash::check($requests['password'], $data->password);
        if ($cek) {
            Session::put('admin', $data->email);
            Session::put('admin_id', $data->id);
            return redirect('admin');
        }
        return redirect('login')->with('status', 'Gagal login!');
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

}
