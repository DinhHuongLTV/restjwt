<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
}
