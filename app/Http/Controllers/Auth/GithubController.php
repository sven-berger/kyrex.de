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

class GithubController extends Controller
{
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGithubCallback()
    {
        try {
            $githubUser = Socialite::driver('github')->user();

            $user = User::where('github_id', $githubUser->getId())->first();

            if (!$user && $githubUser->getEmail()) {
                $user = User::where('email', $githubUser->getEmail())->first();
            }

            if ($user) {
                $user->update([
                    'github_id' => $githubUser->getId(),
                    'name' => $user->name ?: ($githubUser->getNickname() ?: $githubUser->getName() ?: 'GitHub User'),
                ]);
            } else {
                $user = User::create([
                    'name' => $githubUser->getNickname() ?: $githubUser->getName() ?: 'GitHub User',
                    'email' => $githubUser->getEmail() ?: ('github_' . $githubUser->getId() . '@users.local'),
                    'github_id' => $githubUser->getId(),
                    'password' => Hash::make(Str::random(40)),
                ]);
            }

            Auth::login($user, true);

            return redirect()->intended('/');
        } catch (Throwable $e) {
            Log::error('GitHub login failed', ['error' => $e->getMessage()]);
            return redirect('/')->with('error', 'GitHub-Login fehlgeschlagen.');
        }
    }
}
