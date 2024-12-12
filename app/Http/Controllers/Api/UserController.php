<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $accessToken = $user->tokens()->latest()->first();
        $accessToken->expires_at = now()->addHours(3);
        $accessToken->save();

        return response()->json([
            'status' => true,
            'message' => 'Data created',
            'data' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'token' => $token
                ]
        ], 201);
    }

    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('auth_token')->plainTextToken;

            $accessToken = $user->tokens()->latest()->first();
            $accessToken->expires_at = now()->addHours(3);
            $accessToken->save();

            return response()->json([
                'status' => true,
                'message' => 'Login success',
                'data' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'token' => $token
                    ]
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Login failed',
            ], 401);
        }
    }
}
