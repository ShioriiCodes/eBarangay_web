<section>
    <header>
        <h2 class="text-lg font-semibold text-[#0038A8]">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-slate-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="grid gap-5 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>
            <div>
                <x-input-label for="contact_number" :value="__('Contact Number')" />
                <x-text-input id="contact_number" name="contact_number" type="text" class="mt-1 block w-full" :value="old('contact_number', $user->contact_number)" />
                <x-input-error class="mt-2" :messages="$errors->get('contact_number')" />
            </div>
            <div>
                <x-input-label for="birthdate" :value="__('Birthdate')" />
                <x-text-input id="birthdate" name="birthdate" type="date" class="mt-1 block w-full" :value="old('birthdate', optional($user->birthdate)->format('Y-m-d'))" />
                <x-input-error class="mt-2" :messages="$errors->get('birthdate')" />
            </div>
            <div>
                <x-input-label for="gender" :value="__('Gender')" />
                <select id="gender" name="gender" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-[#0038A8] focus:ring-[#0038A8]">
                    <option value="">Select gender</option>
                    <option value="male" @selected(old('gender', $user->gender) === 'male')>Male</option>
                    <option value="female" @selected(old('gender', $user->gender) === 'female')>Female</option>
                    <option value="other" @selected(old('gender', $user->gender) === 'other')>Other</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('gender')" />
            </div>
            <div>
                <x-input-label for="civil_status" :value="__('Civil Status')" />
                <x-text-input id="civil_status" name="civil_status" type="text" class="mt-1 block w-full" :value="old('civil_status', $residentProfile?->civil_status)" />
                <x-input-error class="mt-2" :messages="$errors->get('civil_status')" />
            </div>
            <div>
                <x-input-label for="occupation" :value="__('Occupation')" />
                <x-text-input id="occupation" name="occupation" type="text" class="mt-1 block w-full" :value="old('occupation', $residentProfile?->occupation)" />
                <x-input-error class="mt-2" :messages="$errors->get('occupation')" />
            </div>
            <div>
                <x-input-label for="purok" :value="__('Purok')" />
                <x-text-input id="purok" name="purok" type="text" class="mt-1 block w-full" :value="old('purok', $residentProfile?->purok)" />
                <x-input-error class="mt-2" :messages="$errors->get('purok')" />
            </div>
            <div class="sm:col-span-2">
                <x-input-label for="address" :value="__('Address')" />
                <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $user->address)" />
                <x-input-error class="mt-2" :messages="$errors->get('address')" />
            </div>
            <div class="sm:col-span-2">
                <x-input-label for="name" :value="__('Full name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <p class="mt-1 text-xs text-slate-500">{{ __('Enter your complete name as it should appear on records (replaces separate first, middle, last, and suffix fields).') }}</p>
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
            <div>
                <x-input-label for="barangay" :value="__('Barangay')" />
                <x-text-input id="barangay" name="barangay" type="text" class="mt-1 block w-full" :value="old('barangay', $residentProfile?->barangay ?? 'Alfonso XIII')" />
                <x-input-error class="mt-2" :messages="$errors->get('barangay')" />
            </div>
            <div>
                <x-input-label for="municipality" :value="__('Municipality')" />
                <x-text-input id="municipality" name="municipality" type="text" class="mt-1 block w-full" :value="old('municipality', $residentProfile?->municipality ?? 'Quezon')" />
                <x-input-error class="mt-2" :messages="$errors->get('municipality')" />
            </div>
            <div class="sm:col-span-2">
                <x-input-label for="province" :value="__('Province')" />
                <x-text-input id="province" name="province" type="text" class="mt-1 block w-full" :value="old('province', $residentProfile?->province ?? 'Palawan')" />
                <x-input-error class="mt-2" :messages="$errors->get('province')" />
            </div>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3">
                <p class="text-sm text-amber-800">
                    {{ __('Your email address is unverified.') }}
                    <button form="send-verification" class="ml-1 font-semibold underline decoration-amber-600 underline-offset-2">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-sm font-medium text-blue-700">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-slate-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
