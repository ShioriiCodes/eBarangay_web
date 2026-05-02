@php
    $u = auth()->user();
    $isBarangayOfficial = in_array($u->role, ['admin', 'staff'], true);
    $composed = trim(collect([$u->first_name, $u->middle_name, $u->last_name, $u->suffix])->filter()->implode(' '));
    $displayName = $isBarangayOfficial ? $u->name : ($composed !== '' ? $composed : $u->name);
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-[#0038A8]">Profile Settings</h2>
    </x-slot>

    <div class="space-y-6">
        <section class="rounded-2xl bg-gradient-to-r from-[#0038A8] via-[#174bb7] to-[#0038A8] p-6 text-white shadow-lg shadow-blue-100">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    <span class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-white text-lg font-bold text-[#0038A8]">
                        {{ strtoupper(substr($displayName, 0, 1)) }}
                    </span>
                    <div>
                        <h3 class="text-2xl font-bold">{{ $displayName }}</h3>
                        <p class="text-sm text-blue-100">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <span class="inline-flex rounded-full bg-white/20 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white ring-1 ring-white/30">
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                    <span class="inline-flex rounded-full bg-[#CE1126]/90 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white">
                        {{ ucfirst(auth()->user()->status ?? 'active') }}
                    </span>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-900">Personal Information</h3>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Name</p>
                    <p class="mt-1 font-medium text-slate-800">{{ $displayName }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Email</p>
                    <p class="mt-1 font-medium text-slate-800">{{ auth()->user()->email }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Contact Number</p>
                    <p class="mt-1 font-medium text-slate-800">{{ auth()->user()->contact_number ?: 'Not set' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Address</p>
                    <p class="mt-1 font-medium text-slate-800">{{ auth()->user()->address ?: 'Not set' }}</p>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @if ($isBarangayOfficial)
                @include('profile.partials.update-profile-information-form-barangay')
            @else
                @include('profile.partials.update-profile-information-form')
            @endif
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @include('profile.partials.update-password-form')
        </section>

        <section class="rounded-2xl border border-red-200 bg-red-50/40 p-6 shadow-sm">
            @include('profile.partials.delete-user-form')
        </section>
    </div>
</x-app-layout>
