<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    private const ALLOWED_PROVIDERS = ['github', 'google'];

    public function redirect(string $provider): RedirectResponse
    {
        abort_unless(in_array($provider, self::ALLOWED_PROVIDERS, true), 404);

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider): RedirectResponse
    {
        abort_unless(in_array($provider, self::ALLOWED_PROVIDERS, true), 404);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Throwable) {
            return redirect()->route('login')->withErrors([
                'social_auth' => ucfirst($provider) . ' Login fehlgeschlagen. Bitte versuche es erneut.',
            ]);
        }

        $email = $socialUser->getEmail() ?: $provider . '_' . $socialUser->getId() . '@users.local';
        $name = $socialUser->getName() ?: $socialUser->getNickname() ?: ucfirst($provider) . ' User';

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make(Str::random(40)),
            ]
        );

        if (empty($user->name) && ! empty($name)) {
            $user->name = $name;
            $user->save();
        }

        Auth::login($user, true);

        return redirect()->intended(route('home'));
    }
}
