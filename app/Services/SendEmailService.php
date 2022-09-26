<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;

class SendEmailService
{
    public function send($email, $subject, $view, $data)
    {
        Mail::send($view, $data, function ($message) use ($email, $subject) {
            $message->to($email)->subject($subject);
        });
    }

    public function sendOtp($email, $otp)
    {
        $subject = __('email.otp.subject');
        $view = 'email.otp';
        $data = [ 'otp' => $otp ];
        $this->send($email, $subject, $view, $data);
    }

    public function sendWelcome($email, $name)
    {
        $subject = __('email.welcome.subject');
        $view = 'email.welcome';
        $data = [ 'name' => $name ];
        $this->send($email, $subject, $view, $data);
    }
}