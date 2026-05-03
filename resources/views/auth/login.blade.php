<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-900">Sign in to your account</h2>
        <p class="mt-2 text-sm text-slate-600">
            Welcome to eBarangay Portal
        </p>
    </div>

    <x-auth-session-status class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700" :status="session('status')" />

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <p class="font-semibold">Please review the following:</p>
            <ul class="mt-2 list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" x-data="{ showPassword: false, loading: false }" @submit="loading = true">
        @csrf

        <div>
            <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Email Address</label>
            <input id="email" class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-[#0038A8] focus:ring-[#0038A8]" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="mt-4">
            <label for="password" class="mb-2 block text-sm font-medium text-slate-700">Password</label>
            <div class="relative">
                <input id="password" class="w-full rounded-xl border-slate-300 px-4 py-3 pr-14 text-sm shadow-sm focus:border-[#0038A8] focus:ring-[#0038A8]"
                    x-bind:type="showPassword ? 'text' : 'password'"
                    name="password"
                    required autocomplete="current-password" />
                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 px-4 text-xs font-semibold text-slate-500 hover:text-[#0038A8]">
                    <span x-text="showPassword ? 'Hide' : 'Show'"></span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center text-sm text-slate-600">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-[#0038A8] shadow-sm focus:ring-[#0038A8]" name="remember">
                <span class="ms-2">Remember me</span>
            </label>
        </div>

        <div class="mt-6 flex items-center justify-between">
            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-blue-600 hover:text-blue-700 hover:underline focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-md" href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            @endif

            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-[#0038A8] px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#CE1126] focus:outline-none focus:ring-2 focus:ring-[#0038A8] focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60" x-bind:disabled="loading">
                <span x-show="!loading">Log in</span>
                <span x-show="loading">Signing in...</span>
            </button>
        </div>

        <p class="mt-6 text-center text-sm text-slate-600">
            New to eBarangay?
            <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:underline">Create an account</a>
        </p>
    </form>
</x-guest-layout>
