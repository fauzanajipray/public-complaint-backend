<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Rules\Password;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserVerify;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{

    public function login(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => ['required','email','exists:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        
        if (!$validator->fails()){

            $user = User::where('email', $request->email)->first(); 

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 401,
                    'message' => __('auth.failed'),
                    'errors' => [
                        'password' => ['Password salah.']
                    ]
                ], 401);
            }

            if($user->is_email_verified == 0){
                return response()->json([
                    'status' => 401,
                    'message' => __('auth.verify_email'),
                    'errors' => null,
                ], 401);
            }

            $token = $user->createToken('token-name')->plainTextToken;

            return response()->json([
                'status' => 200,
                'message' => __('auth.login_success', ['name' => $user->name]),
                'user' => $user,
                'token' => $token,
                'errors' => null
            ], 200);
        }

        return response()->json([
            'status' => 401,
            'message' => __('auth.failed'),
            'errors' => $validator->errors()
        ], 401);
    }

    public function logout(Request $request){

        $user = $request->user();

        $user->tokens()->delete();

        return response()->json([
            'status' => 200,
            'message' => __('auth.logout'),
        ], 200);

    }

    public function register(Request $request){     

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
            'password' => ['required', 'string', new Password],
        ]);

        if (!$validator->fails()) {

            $requests['password'] = Hash::make($requests['password']);
            $requests['role_id'] = 2;

            $user = User::create($requests);
            if ($user) {

                $token = Str::random(64);
            
                UserVerify::create([
                    'user_id' => $user->id, 
                    'token' => $token
                ]);

                Mail::send('email.emailVerificationEmail', ['token' => $token], function($message) use($request){
                    $message->to($request->email);
                    $message->subject(__('account.verify.subject'));
                });

                return response()->json([
                    'status' => 200,
                    'message' => __('auth.register_success'),
                    'user' => $user,
                    'token' => $token,
                    'errors' => null
                ], 200);
            }
        }

        return response()->json([
            'status' => 401,
            'message' => __('auth.failed'),
            'errors' => $validator->errors()
        ], 401);

    }
}
