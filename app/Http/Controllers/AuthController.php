<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(StoreUserRequest $request)
    {
        try {
            $data = $request->validated();

            $user = new User();
            $user->fill($data);
            $user->role_id = Role::where('name', 'agent')->first()->id;
            $user->save();

            $token = Auth::login($user);

            return response()->json([
                $user->toResource(),
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ],
            ], 201);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => 'An error occurred when trying to create the user!',
            ], 400);
        }
    }

    public function login(LoginRequest $request) {
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            'user' => $user->toResource(),
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ],
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out!',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user()->toResource(),
            'authorization' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ],
        ]);
    }
}
