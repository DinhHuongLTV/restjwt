<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    public function sendEmailVerification(Request $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return [
                'message' => 'Already verified',
            ];
        }

        $request->user()->sendEmailVerificationNotification();
        return [
            'message' => 'Verification link sent!',
        ];
    }

    public function verify(EmailVerificationRequest $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return [
                'message' => 'Already verified',
            ];
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return [
            'message' => 'Email verified ok',
        ];
    }
}
