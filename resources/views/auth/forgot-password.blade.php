<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-900">Forgot your password?</h2>
        <p class="mt-2 text-sm text-slate-600">
            Enter your registered email address and we will send you a password reset link.
        </p>
    </div>

    <x-auth-session-status class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" x-data="{ loading: false }" @submit="loading = true">
        @csrf

        <div>
            <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Email Address</label>
            <input id="email" class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-[#0038A8] focus:ring-[#0038A8]" type="email" name="email" value="{{ old('email') }}" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="mt-6 flex items-center justify-between">
            <a class="text-sm font-medium text-blue-600 hover:text-blue-700 hover:underline focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-md" href="{{ route('login') }}">
                Back to login
            </a>
            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-[#0038A8] px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#CE1126] focus:outline-none focus:ring-2 focus:ring-[#0038A8] focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60" x-bind:disabled="loading">
                <span x-show="!loading">Send reset link</span>
                <span x-show="loading">Sending...</span>
            </button>
        </div>
    </form>
</x-guest-layout>
