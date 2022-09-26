<?php

namespace App\Repositories;

use App\Models\User;
use App\Services\SendEmailService;

class UserRepository
{
    protected $sendEmailService;

    public function __construct()
    {
        $this->sendEmailService = new SendEmailService();
    }

    public function create($data)
    {
        return User::create($data);
    }

    public function findByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function requestOtp($user)
    {
        $otpNumber = rand(1000, 9999);
        if ($user) {
            $user->otp = $otpNumber;
            $user->otp_expired_at = now()->addMinutes(5);
            $user->save();
            $this->sendEmailService->sendOtp($user->email, $otpNumber);
            return $user;
        }
        return null;
    }

    public function verifyOTP($email, $otp){
        $user = User::where('email', $email)->first();
        if($user){
            if($user->otp == $otp){
                if(now()->isBefore($user->otp_expired_at)){
                    $user->is_email_verified = 1;
                    $user->email_verified_at = now();
                    $user->save();
                    $this->sendEmailService->sendWelcome($user->email, $user->name);
                    return $user;
                } 
            }
        }
        return null;
    }

    public function resendOTP($email){
        $user = User::where('email', $email)->first();
        if($user){
            $user->otp = null;
            $user->otp_expired_at = null;
            $user->save();
            $user = $this->requestOtp($user);
            return $user;
        }
        return null;
    }
    
}