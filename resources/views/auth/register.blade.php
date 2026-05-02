<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-900">Create resident account</h2>
        <p class="mt-2 text-sm text-slate-600">
            Register to request official barangay documents and track your submissions online.
        </p>
    </div>

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

    <form method="POST" action="{{ route('register') }}" x-data="{ showPassword: false, showConfirm: false, loading: false }" @submit="loading = true">
        @csrf

        <div>
            <label for="name" class="mb-2 block text-sm font-medium text-slate-700">Full Name</label>
            <input id="name" class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-[#0038A8] focus:ring-[#0038A8]" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="mt-4">
            <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Email Address</label>
            <input id="email" class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-[#0038A8] focus:ring-[#0038A8]" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="contact_number" class="mb-2 block text-sm font-medium text-slate-700">Contact Number</label>
                <input id="contact_number" class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-[#0038A8] focus:ring-[#0038A8]" type="text" name="contact_number" value="{{ old('contact_number') }}" autocomplete="tel" />
                <x-input-error :messages="$errors->get('contact_number')" class="mt-2 text-sm text-red-600" />
            </div>
            <div>
                <label for="birthdate" class="mb-2 block text-sm font-medium text-slate-700">Birthdate</label>
                <input id="birthdate" class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-[#0038A8] focus:ring-[#0038A8]" type="date" name="birthdate" value="{{ old('birthdate') }}" />
                <x-input-error :messages="$errors->get('birthdate')" class="mt-2 text-sm text-red-600" />
            </div>
        </div>

        <div class="mt-4">
            <label for="address" class="mb-2 block text-sm font-medium text-slate-700">Address</label>
            <input id="address" class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-[#0038A8] focus:ring-[#0038A8]" type="text" name="address" value="{{ old('address') }}" autocomplete="street-address" />
            <x-input-error :messages="$errors->get('address')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="mt-4">
            <label for="gender" class="mb-2 block text-sm font-medium text-slate-700">Gender</label>
            <select id="gender" name="gender" class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-[#0038A8] focus:ring-[#0038A8]">
                <option value="">Select gender</option>
                <option value="male" @selected(old('gender') === 'male')>Male</option>
                <option value="female" @selected(old('gender') === 'female')>Female</option>
                <option value="other" @selected(old('gender') === 'other')>Other</option>
            </select>
            <x-input-error :messages="$errors->get('gender')" class="mt-2 text-sm text-red-600" />
        </div>

        <input type="hidden" name="role" value="resident">

        <div class="mt-4">
            <label for="password" class="mb-2 block text-sm font-medium text-slate-700">Password</label>
            <div class="relative">
                <input id="password" class="w-full rounded-xl border-slate-300 px-4 py-3 pr-14 text-sm shadow-sm focus:border-[#0038A8] focus:ring-[#0038A8]"
                    x-bind:type="showPassword ? 'text' : 'password'"
                    name="password"
                    required autocomplete="new-password" />
                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 px-4 text-xs font-semibold text-slate-500 hover:text-[#0038A8]">
                    <span x-text="showPassword ? 'Hide' : 'Show'"></span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="mt-4">
            <label for="password_confirmation" class="mb-2 block text-sm font-medium text-slate-700">Confirm Password</label>
            <div class="relative">
                <input id="password_confirmation" class="w-full rounded-xl border-slate-300 px-4 py-3 pr-14 text-sm shadow-sm focus:border-[#0038A8] focus:ring-[#0038A8]"
                    x-bind:type="showConfirm ? 'text' : 'password'"
                    name="password_confirmation"
                    required autocomplete="new-password" />
                <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 px-4 text-xs font-semibold text-slate-500 hover:text-[#0038A8]">
                    <span x-text="showConfirm ? 'Hide' : 'Show'"></span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="mt-4">
            <label class="inline-flex items-start gap-2 text-sm text-slate-600">
                <input type="checkbox" name="terms" value="1" class="mt-0.5 rounded border-slate-300 text-[#0038A8] focus:ring-[#0038A8]" {{ old('terms') ? 'checked' : '' }} required>
                <span>I confirm that the information provided is correct and I agree to the eBarangay service terms.</span>
            </label>
            <x-input-error :messages="$errors->get('terms')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="mt-6 flex items-center justify-between">
            <a class="text-sm font-medium text-blue-600 hover:text-blue-700 hover:underline focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-md" href="{{ route('login') }}">
                Back to login
            </a>

            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-[#0038A8] px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#CE1126] focus:outline-none focus:ring-2 focus:ring-[#0038A8] focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60" x-bind:disabled="loading">
                <span x-show="!loading">Register</span>
                <span x-show="loading">Creating account...</span>
            </button>
        </div>
    </form>
</x-guest-layout>
