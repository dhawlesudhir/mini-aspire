<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $fields['email'])->first();
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response(['error' => 'invalid credential'], 401);
        }

        $token = $user->createToken('miniaspiretoken')->plainTextToken;

        $response = ['user' => $user, 'token' => $token];
        return response($response, 201);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response(['message' => 'logged out'], 200);
    }
}
