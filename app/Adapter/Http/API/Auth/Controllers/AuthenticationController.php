<?php

namespace App\Adapter\Http\API\Auth\Controllers;

use App\Core\Auth\Actions\AuthenticationAction;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AuthenticationController extends Controller
{
    protected AuthenticationAction $action;

    public function __construct()
    {
        $this->action = new AuthenticationAction();
    }

    // Register
    public function register(Request $request)
    {
        $dto = new \App\Core\Auth\Dto\RegisterDto(
            $request->name,
            $request->email,
            $request->password
        );

        $user = $this->action->register($dto);

        if($user instanceof \App\Models\User){
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer'
            ], 201);
        }

        return response()->json(['error' => 'Registration failed'], 400);
    }

    // Login
    public function login(Request $request)
    {
        $dto = new \App\Core\Auth\Dto\LoginDto(
            $request->email,
            $request->password
        );

        $user = $this->action->login($dto);

        if($user instanceof \App\Models\User){
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer'
            ], 200);
        }

        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    // Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
