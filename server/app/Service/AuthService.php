<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(array $payload)
    {

        $user = $this->userRepository->findByField('email', $payload['email']);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => __('User not found'),
            ], 404);
        }

        if (!Hash::check($payload['password'], $user->password)) {
            return response()->json([
                'status' => false,
                'message' => __('Incorrect credentials'),
            ], 401);
        }

        Auth::login($user);
        request()->session()->regenerate();
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => __('Login successful'),
            'user' => $user->load('roles'),
            'token' => $token
        ], 200);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return response()->json([
            'status' => true,
            'message' => __('Logged out successfully')
        ])->withCookie(cookie()->forget('laravel-session'))
            ->withCookie(cookie()->forget('XSRF-TOKEN'));;
    }
}
