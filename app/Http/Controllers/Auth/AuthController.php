<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Rules\Password;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserVerify;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    protected $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function register(){
        return view('auth.register');
    }

    public function postRegister(Request $request){
        $requests = $request->all();
        $validator = Validator::make($requests, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => ['required', 'string', new Password, 'confirmed'],
        ]);
        try {
            if(!$validator->fails()){
                $requests['password'] = Hash::make($requests['password']);
                $requests['role_id'] = 2;
                $user = $this->userRepository->create($requests);
                $user = $this->userRepository->requestOtp($user);
                return redirect()->route('register.otp-verification', [
                    'email' => $user->email,
                ])->with('status', __('auth.register.success'));
            } else {
                return redirect()->back()->withInput()->withErrors($validator);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('auth.register.failed'));
        }
    }

    public function registerOtpVerification(Request $request){
        $user = $this->userRepository->findByEmail($request->email);
        if($user){
            return view('auth.otp-verification', compact('user'));
        } else {
            return redirect()->route('register')->with('error', __('auth.register.failed'));
        }
    }

    public function postRegisterOtpVerification(Request $request){
        $validator = Validator::make($request->all(), [
            'otp' => ['required', 'string', 'max:4'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        try {
            if(!$validator->fails()){
                $user = $this->userRepository->verifyOtp($request->email, $request->otp);
                if($user){
                    return redirect()->route('login')->with('status', __('auth.register.verify.success'));
                } else {
                    return redirect()->route('register.otp-verification', [
                        'email' => $request->email,
                    ])->with('status', __('auth.register.verify.failed'));
                }
            } else {
                return redirect()->back()->withInput()->withErrors($validator);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('auth.register.verify.failed'));
        }
    }

    public function registerResendOtp(Request $request){
        $user = $this->userRepository->findByEmail($request->email);
        if($user){
            $user = $this->userRepository->requestOtp($user);
            return redirect()->route('register.otp-verification', [
                'email' => $user->email,
            ])->with('status', __('auth.register.resend.success'));
        } else {
            return redirect()->route('register')->with('error', __('auth.register.resend.failed'));
        }
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
                        Session::put('admin_avatar', $data->detail->avatar);
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
            if($user->is_email_verified != 1) {
                $verifyUser->user->is_email_verified = 1;
                $verifyUser->user->email_verified_at = now();
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
