<section>
    <header>
        <h2 class="text-lg font-semibold text-[#0038A8]">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-slate-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6" x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <div class="relative mt-1">
                <x-text-input id="update_password_current_password" name="current_password" x-bind:type="showCurrent ? 'text' : 'password'" class="block w-full pr-14" autocomplete="current-password" />
                <button type="button" class="absolute inset-y-0 right-0 px-3 text-xs font-semibold text-slate-500 hover:text-[#0038A8]" @click="showCurrent = !showCurrent">
                    <span x-text="showCurrent ? 'Hide' : 'Show'"></span>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <div class="relative mt-1">
                <x-text-input id="update_password_password" name="password" x-bind:type="showNew ? 'text' : 'password'" class="block w-full pr-14" autocomplete="new-password" />
                <button type="button" class="absolute inset-y-0 right-0 px-3 text-xs font-semibold text-slate-500 hover:text-[#0038A8]" @click="showNew = !showNew">
                    <span x-text="showNew ? 'Hide' : 'Show'"></span>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <div class="relative mt-1">
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" x-bind:type="showConfirm ? 'text' : 'password'" class="block w-full pr-14" autocomplete="new-password" />
                <button type="button" class="absolute inset-y-0 right-0 px-3 text-xs font-semibold text-slate-500 hover:text-[#0038A8]" @click="showConfirm = !showConfirm">
                    <span x-text="showConfirm ? 'Hide' : 'Show'"></span>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
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
