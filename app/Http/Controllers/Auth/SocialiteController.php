<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    public function redirect(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            
            // Find user by provider and provider_id or create a new one
            $user = User::firstOrCreate(
                [
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId()
                ],
                [
                    'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                    'email' => $socialUser->getEmail(),
                    'password' => Hash::make(Str::random(24)),
                    'avatar' => $socialUser->getAvatar(),
                ]
            );
            
            // Login user
            Auth::login($user, true);
            
            // Redirect to dashboard
            return redirect()->route('dashboard');
            
        } catch (\Exception $e) {
            // If something goes wrong, redirect to login with error
            return redirect()->route('login')
                ->withErrors(['error' => 'Authentication failed. Please try again.']);
        }
    }

    public function loginOptions()
    {
        return Inertia::render('Auth/SocialLogin');
    }
}