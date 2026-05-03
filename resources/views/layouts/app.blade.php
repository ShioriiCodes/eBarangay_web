<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @include('partials.vite-assets')
    </head>
    <body x-data="{ sidebarOpen: false }" class="bg-[#F8FAFC] font-sans antialiased text-[#111827]">
        <div class="min-h-screen">
            <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-slate-900/40 md:hidden" @click="sidebarOpen = false" x-cloak></div>

            <aside class="fixed inset-y-0 left-0 z-50 w-72 -translate-x-full border-r border-slate-200 bg-white transition-transform duration-300 md:translate-x-0" :class="{ 'translate-x-0': sidebarOpen }">
                <div class="flex h-16 items-center justify-between border-b border-slate-200 px-5">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-[#0038A8] text-xs font-bold text-white">eB</span>
                        <span class="font-semibold text-[#0038A8]">eBarangay</span>
                    </a>
                    <button type="button" class="rounded-md p-2 text-slate-500 md:hidden" @click="sidebarOpen = false" aria-label="Close sidebar">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M6 6l12 12M18 6 6 18"/>
                        </svg>
                    </button>
                </div>
                <div class="px-4 py-4">
                    @include('layouts.sidebar')
                </div>
                <div class="absolute inset-x-4 bottom-4 rounded-lg border border-[#CE1126]/30 bg-[#CE1126]/10 px-3 py-2 text-xs text-[#991b1b]">
                    Alfonso XIII, Quezon, Palawan
                </div>
            </aside>

            <div class="md:pl-72">
                <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/95 backdrop-blur">
                    <div class="flex min-h-[76px] items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
                        <div class="flex items-center gap-3">
                            <button type="button" class="rounded-lg border border-slate-300 px-2.5 py-1.5 text-slate-600 md:hidden" @click="sidebarOpen = true" aria-label="Open sidebar">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M4 7h16M4 12h16M4 17h16"/>
                                </svg>
                            </button>
                            <div>
                                @isset($header)
                                    {{ $header }}
                                @else
                                    <h1 class="text-lg font-semibold text-[#0038A8]">Dashboard</h1>
                                @endisset
                            </div>
                        </div>
                        @include('layouts.navigation')
                    </div>
                </header>

                <main class="min-w-0 space-y-6 px-4 py-6 sm:px-6 lg:px-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
        @stack('scripts')
    </body>
</html>
