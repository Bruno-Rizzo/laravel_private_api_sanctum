<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $credentials['email'])->first();
        if(!$user){
            return ApiResponse::error('Invalid credentials', 401);
        }

        if(!Hash::check($credentials['password'], $user->password)){
            return ApiResponse::error('Invalid credentials', 401);
        }

        return ApiResponse::success([
            'token' => $user->createToken($user->name)->plainTextToken
        ]);
    }
}
