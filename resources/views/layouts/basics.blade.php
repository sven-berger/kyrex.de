<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kyrex.de') | Kyrex.de</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-dvh bg-slate-200 text-slate-700">
    <div class="min-h-dvh p-2 sm:p-3">
        <div class="mx-auto flex min-h-[calc(100dvh-1rem)] flex-col gap-2 sm:min-h-[calc(100dvh-1.5rem)]">
            @include('layouts.ui.header')
            <main class="flex min-h-0 flex-1 items-stretch gap-2">
                <aside class="flex w-65 shrink-0 flex-col gap-2">
                    @if (request()->routeIs('wissensportal*'))
                    @include('layouts.sidebarLeft.sidebarLeftWissensportal')
                    @elseif(request()->routeIs('acp*'))
                    @include('layouts.sidebarLeft.sidebarLeftACP')
                    @else
                    @include('layouts.sidebarLeft.sidebarLeftIndex')
                    @endif
                    @include('layouts.sidebarLeft.sidebarLeftLogin')
                </aside>

                <div class="flex flex-1 flex-col gap-2 ">
                    <div class="rounded-2xl p-4 border border-slate-300 bg-white text-xl flex justify-center shadow-sm">
                        @yield('title')
                    </div>
                    @yield('content')
                </div>

                @unless(request()->routeIs('acp*'))
                <aside class=" flex w-44 shrink-0 flex-col gap-2">
                    @if (request()->routeIs('wissensportal*'))
                    @include('layouts.sidebarRight.sidebarRightWissensportal')
                    @else
                    @include('layouts.sidebarRight.sidebarRightIndex')
                    @endif
                </aside>
                @endunless
            </main>

            <footer class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-[15px] text-slate-500 shadow-sm">
                @include('layouts.ui.footer')
            </footer>
        </div>
    </div>
</body>

</html>