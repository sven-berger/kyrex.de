@auth
@include('layouts.sidebarLeft.sidebarLeftUser')
@else
<x-ui.box title="Anmelden" class="mt-auto">
    <form method="POST" action="{{ route('login') }}" class="p-3 space-y-2">
        @csrf

        <label class="mb-1 block text-slate-700 text-[13px]" for="email">E-Mail</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
            class="w-full rounded border border-slate-300 bg-slate-100 px-2 py-1 text-[13px]">

        <div>
            <label class="mb-1 block text-slate-700 text-[13px]" for="password">Passwort</label>
            <input id="password" name="password" type="password" required
                class="w-full rounded border border-slate-300 bg-slate-100 px-2 py-1 text-[13px]">
        </div>

        <label class="flex items-center gap-2 text-xs text-slate-700">
            <input type="checkbox" name="remember" class="h-3 w-3 rounded border-slate-300">
            Eingeloggt bleiben
        </label>

        @if ($errors->has('email') || $errors->has('password') || $errors->has('social_auth'))
        <p class="text-xs text-red-600">
            {{ $errors->first('email') ?: ($errors->first('password') ?: $errors->first('social_auth')) }}
        </p>
        @endif

        <div class="mt-4 space-y-2">
            <button type="submit"
                class="w-full border border-blue-400 rounded-xl bg-blue-600 p-2 text-[13px] font-semibold text-white">
                Anmelden
            </button>

            <a href="{{ route('password.request') }}"
                class="block w-full border border-red-400 rounded-xl bg-red-600 p-2 text-center text-[13px] font-semibold text-white">
                Passwort vergessen
            </a>

            <a href="{{ route('register') }}"
                class="block w-full border border-green-400 rounded-xl bg-green-600 p-2 text-center text-[13px] font-semibold text-white">
                Registrieren
            </a>

            <hr class="my-4 border-slate-300">

            <a href="{{ route('google.redirect') }}"
                class="block w-full border border-gray-200 rounded-xl bg-gray-100 p-2 text-center text-[13px] font-semibold text-black">
                <img src="/images/thirdPartyLogin/google.png" alt="Google Logo" class="inline-block mr-2">
                Mit Google anmelden
            </a>

            <a href="{{ route('github.redirect') }}"
                class="block w-full border border-gray-200 rounded-xl bg-gray-100 p-2 text-center text-[13px] font-semibold text-black">
                <img src="/images/thirdPartyLogin/github.png" alt="GitHub Logo" class="inline-block mr-2">
                Mit GitHub anmelden
            </a>
        </div>
    </form>
</x-ui.box>
@endauth