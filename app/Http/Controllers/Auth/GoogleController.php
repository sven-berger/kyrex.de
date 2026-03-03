<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->getId())->first();

            if (!$user && $googleUser->getEmail()) {
                $user = User::where('email', $googleUser->getEmail())->first();
            }

            if ($user) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'name' => $user->name ?: ($googleUser->getName() ?: 'Google User'),
                ]);
            } else {
                $user = User::create([
                    'name' => $googleUser->getName() ?: 'Google User',
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => Hash::make(Str::random(40)),
                ]);
            }

            Auth::login($user, true);

            return redirect()->intended('/');
        } catch (Throwable $e) {
            Log::error('Google login failed', ['error' => $e->getMessage()]);
            return redirect('/')->with('error', 'Google-Login fehlgeschlagen.');
        }
    }
}
