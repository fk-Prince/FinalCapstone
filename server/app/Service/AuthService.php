<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

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

    public function register(array $payload)
    {
        $exists = $this->userRepository->findByField('email', $payload['email']);
        if ($exists) {
            return response()->json([
                'status'  => false,
                'message' => __('Email already exists.'),
            ]);
        }

        $payload['password'] = Hash::make($payload['password']);
        $user = $this->userRepository->create($payload);

        return response()->json([
            'status'  => true,
            'message' => __('Registration successful.'),
            'user' => $user
        ]);
    }

    public function google()
    {
        /** @var \Laravel\Socialite\Two\GoogleProvider $driver */
        $driver = Socialite::driver('google');
        $redirectUrl = $driver->stateless()->redirect()->getTargetUrl();

        return response()->json([
            'url' => $redirectUrl
        ]);
    }

    public function googleCallback()
    {
        /** @var \Laravel\Socialite\Two\GoogleProvider $driver */
        $driver = Socialite::driver('google');
        $googleUser = $driver->stateless()->user();

        $user = $this->userRepository->findByField('email', $googleUser->getEmail());
        $clientUrl = config('app.client_url');

        if (!$user) {
            $user = User::create([
                'first_name' => explode(' ', $googleUser->getName())[0],
                'last_name'  => explode(' ', $googleUser->getName())[1] ?? '',
                'email'      => $googleUser->getEmail(),
                'uuid'       => $googleUser->getId(),
                'avatar'     => $googleUser->getAvatar(),
                'provider'   => 'google',
            ]);
        }

        if ($user->provider !== 'google') {
            $user->update([
                'provider' => 'google',
                'uuid'     => $googleUser->getId(),
                'avatar'   => $googleUser->getAvatar(),
            ]);
        }


        $token = $user->createToken('auth-token')->plainTextToken;
        $clientUrl = config('app.client_url');
        $redirectUrl = $clientUrl . '/auth/success?token=' . urlencode($token);

        return redirect()->away($redirectUrl);
    }
}
