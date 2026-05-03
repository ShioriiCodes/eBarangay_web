<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'eBarangay') }} - Authentication</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @include('partials.vite-assets')
    </head>
    <body class="min-h-screen bg-white font-sans text-slate-800 antialiased md:h-screen md:overflow-hidden">
        <div class="grid min-h-screen w-full grid-cols-1 md:h-screen md:grid-cols-2">
            <div class="min-h-[26rem] w-full md:min-h-0 md:h-full md:overflow-hidden">
                @include('partials.portal-showcase')
            </div>

            <main class="flex min-h-0 w-full items-center justify-center bg-white px-4 py-8 sm:px-8 md:h-full md:overflow-y-auto md:px-10 md:py-10">
                <div class="w-full max-w-lg">
                    <div class="mb-5 text-center md:hidden">
                        <h2 class="text-2xl font-bold text-[#0038A8]">eBarangay</h2>
                        <p class="mt-1 text-xs text-slate-500">Barangay Alfonso XIII, Quezon, Palawan</p>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70 sm:p-8">
                        {{ $slot }}
                    </div>

                    <p class="mt-5 text-center text-xs text-slate-500">
                        &copy; {{ date('Y') }} eBarangay. All rights reserved.
                    </p>
                </div>
            </main>
        </div>
    </body>
</html>
