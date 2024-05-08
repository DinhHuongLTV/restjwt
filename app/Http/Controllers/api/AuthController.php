<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;
use Laravel\Sanctum\PersonalAccessToken;

// use Auth;

class AuthController extends Controller
{
    public function login(Request $request) {
        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];

        $credentials = $request->validate($rules);
        if (!Auth::attempt($credentials)) {
            $status = '403';
            $message = 'Create account first';
        } else {
            $status = '200';
            $message = 'Loged in successfully';
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
        }
        $response = [
            'status' => $status,
            'message' => $message,
            'token' => $token ?? 'No tokens',
        ];
        return $response;
    }

    public function getTokens(Request $request) {
        $user = $request->user();
        return $user->tokens;
    }

    public function deleteToken(Request $request) {
        $user = $request->user();
        return $user->currentAccessToken()->delete();
    }

    public function refreshToken(Request $request) {
        if ($request->header('authorization')) {
            $hashToken = $request->header('authorization');
            $hashToken = explode(' ', $hashToken)[1];
            $token = PersonalAccessToken::findToken($hashToken);
        }
        if ($token) {
            $tokenCreatedAt = $token->created_at;
            $tokenExpiredTime = Carbon::parse($tokenCreatedAt)->addMinutes(config('sanctum.expiration'));
            // return [$tokenCreatedAt, $tokenExpiredTime];
            if (Carbon::now() >= $tokenExpiredTime) {
                $user = User::find($token->tokenable_id);
                $user->tokens()->delete();

                $newToken = $user->createToken('auth_token')->plainTextToken;
                $response = [
                    'status' => '200',
                    'token' => $newToken,
                ];
            } else {
                $response = [
                    'status' => '400',
                    'title' => 'Valid token',
                ];
            }
        } else {
            $response = [
                'status' => '403',
                'title' => 'Unauthorizied',
            ];
        }
        return $response;
    }
}
