<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>eBarangay Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#F8FAFC] text-slate-800 antialiased">
    <div class="grid min-h-screen grid-cols-1 md:grid-cols-2">
        @include('partials.portal-showcase')

        <section class="flex items-center justify-center bg-white px-6 py-10 sm:px-10">
            <div class="w-full max-w-md">
                <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/70">
                    <div class="mb-6">
                        <div class="inline-flex items-center gap-2 rounded-xl bg-[#F8FAFC] px-3 py-2">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-md bg-[#CE1126] text-xs font-bold text-white">e</span>
                            <span class="text-sm font-semibold text-[#0038A8]">eBarangay Portal</span>
                        </div>
                        <h2 class="mt-4 text-3xl font-bold text-slate-900">Run your barangay with confidence.</h2>
                        <p class="mt-2 text-sm text-slate-600">
                            A web-based e-governance system for document requests, concern management, and resident services.
                        </p>
                    </div>

                    <div class="space-y-3">
                        <a href="{{ route('login') }}" class="inline-flex w-full items-center justify-center rounded-xl bg-[#CE1126] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#b80f22] focus:outline-none focus:ring-2 focus:ring-[#CE1126] focus:ring-offset-2">
                            Log in
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex w-full items-center justify-center rounded-xl bg-[#0038A8] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#002f8d] focus:outline-none focus:ring-2 focus:ring-[#0038A8] focus:ring-offset-2">
                            Create an account
                        </a>
                        <a href="{{ route('password.request') }}" class="inline-flex w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-[#0038A8] hover:text-[#0038A8]">
                            Forgot password?
                        </a>
                    </div>

                    <p class="mt-6 text-center text-xs text-slate-500">
                        Secure, role-based access for residents and administrators.
                    </p>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
