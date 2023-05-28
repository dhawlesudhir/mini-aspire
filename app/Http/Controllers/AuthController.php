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

    public static function registerCustomer(Request $request)
    {
        $form = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required|string'
        ]);

        $form['password'] = Hash::make($form['password']);

        $user = User::create($form);

        return $user->id;
    }


    public function register(Request $request)
    {
        $form = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required|string'
        ]);

        $form['password'] = Hash::make($form['password']);

        $user = User::create($form);

        $token = $user->createToken('miniaspiretoken')->plainTextToken;

        $response = ['user' => $user, 'token' => $token];
        return response($response, 201);
    }
}
