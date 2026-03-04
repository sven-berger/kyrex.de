<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kyrex.de') | Kyrex.de</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="min-h-dvh bg-slate-200 text-slate-700"
    x-data="{ leftSidebarOpen: false, rightSidebarOpen: false }"
    x-bind:class="(leftSidebarOpen || rightSidebarOpen) ? 'overflow-hidden' : ''"
    @keydown.escape.window="leftSidebarOpen = false; rightSidebarOpen = false">
    <div class="min-h-dvh p-2 sm:p-3">
        <div class="mx-auto flex min-h-[calc(100dvh-1rem)] flex-col gap-2 sm:min-h-[calc(100dvh-1.5rem)]">
            @include('layouts.ui.header')
            <main class="flex min-h-0 flex-1 flex-col gap-2 lg:flex-row lg:items-stretch">
                <div class="lg:hidden">
                    <x-ui.content>
                        <div class="flex gap-2 text-sm">
                            <button
                                type="button"
                                class="flex-1 rounded-xl border border-slate-300 px-3 py-2 text-slate-700 hover:bg-slate-100"
                                @click="leftSidebarOpen = true">
                                Linke Seitenleiste
                            </button>
                            @unless(request()->routeIs('acp*'))
                            <button
                                type="button"
                                class="flex-1 rounded-xl border border-slate-300 px-3 py-2 text-slate-700 hover:bg-slate-100"
                                @click="rightSidebarOpen = true">
                                Rechte Seitenleiste
                            </button>
                            @endunless
                        </div>
                    </x-ui.content>
                </div>

                <aside class="hidden w-65 shrink-0 flex-col gap-2 lg:flex">
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
                <aside class="hidden w-65 shrink-0 flex-col gap-2 lg:flex">
                    @if (request()->routeIs('wissensportal*'))
                    @include('layouts.sidebarRight.sidebarRightWissensportal')
                    @else
                    @include('layouts.sidebarRight.sidebarRightIndex')
                    @endif
                </aside>
                @endunless

                <div
                    class="fixed inset-0 z-40 bg-slate-900/40 lg:hidden"
                    x-show="leftSidebarOpen"
                    x-transition.opacity
                    x-cloak
                    @click="leftSidebarOpen = false"></div>
                <aside
                    class="fixed inset-y-0 left-0 z-50 flex w-[85%] max-w-sm flex-col gap-2 overflow-y-auto border-r border-slate-300 bg-white p-3 shadow-sm lg:hidden"
                    x-show="leftSidebarOpen"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="-translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="-translate-x-full"
                    x-cloak>
                    <div class="flex items-center justify-between rounded-lg border border-slate-300 bg-white px-3 py-2">
                        <h2 class="text-sm font-semibold text-slate-900">Linke Seitenleiste</h2>
                        <button type="button" class="rounded-md border border-slate-300 px-2 py-1 text-slate-600 hover:bg-slate-100" @click="leftSidebarOpen = false">Schließen</button>
                    </div>

                    @if (request()->routeIs('wissensportal*'))
                    @include('layouts.sidebarLeft.sidebarLeftWissensportal')
                    @elseif(request()->routeIs('acp*'))
                    @include('layouts.sidebarLeft.sidebarLeftACP')
                    @else
                    @include('layouts.sidebarLeft.sidebarLeftIndex')
                    @endif
                    @include('layouts.sidebarLeft.sidebarLeftLogin')
                </aside>

                @unless(request()->routeIs('acp*'))
                <div
                    class="fixed inset-0 z-40 bg-slate-900/40 lg:hidden"
                    x-show="rightSidebarOpen"
                    x-transition.opacity
                    x-cloak
                    @click="rightSidebarOpen = false"></div>
                <aside
                    class="fixed inset-y-0 right-0 z-50 flex w-[85%] max-w-sm flex-col gap-2 overflow-y-auto border-l border-slate-300 bg-white p-3 shadow-sm lg:hidden"
                    x-show="rightSidebarOpen"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="translate-x-full"
                    x-cloak>
                    <div class="flex items-center justify-between rounded-lg border border-slate-300 bg-white px-3 py-2">
                        <h2 class="text-sm font-semibold text-slate-900">Rechte Seitenleiste</h2>
                        <button type="button" class="rounded-md border border-slate-300 px-2 py-1 text-slate-600 hover:bg-slate-100" @click="rightSidebarOpen = false">Schließen</button>
                    </div>

                    @if (request()->routeIs('wissensportal*'))
                    @include('layouts.sidebarRight.sidebarRightWissensportal')
                    @else
                    @include('layouts.sidebarRight.sidebarRightIndex')
                    @endif
                </aside>
                @endunless
            </main>

            <x-ui.content>
                @include('layouts.ui.footer')
            </x-ui.content>
        </div>
    </div>
</body>

</html>