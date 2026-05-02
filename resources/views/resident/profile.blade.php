<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#0038A8]">Resident Profile</h2>
    </x-slot>

    @php
        $user = auth()->user();
        $residentProfile = $user->residentProfile;
        $displayFirstName = $user->first_name ?: ($residentProfile?->first_name ?: 'Not set');
        $displayMiddleName = $user->middle_name ?: ($residentProfile?->middle_name ?: 'Not set');
        $displayLastName = $user->last_name ?: ($residentProfile?->last_name ?: 'Not set');
        $displaySuffix = $user->suffix ?: ($residentProfile?->suffix ?: 'Not set');
    @endphp

    <div class="space-y-6">
        <section class="rounded-2xl bg-gradient-to-r from-[#0038A8] via-[#174bb7] to-[#0038A8] p-6 text-white shadow-lg shadow-blue-100">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    <span class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-white text-lg font-bold text-[#0038A8]">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </span>
                    <div>
                        <h3 class="text-2xl font-bold">{{ $user->name }}</h3>
                        <p class="text-sm text-blue-100">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <span class="inline-flex rounded-full bg-white/20 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white ring-1 ring-white/30">
                        {{ ucfirst($user->role) }}
                    </span>
                    <span class="inline-flex rounded-full bg-[#CE1126]/90 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white">
                        {{ ucfirst($user->status ?? 'active') }}
                    </span>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <h3 class="text-lg font-semibold text-slate-900">Account Information</h3>
                <a href="{{ route('profile.edit') }}" class="rounded-xl bg-[#0038A8] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#002f8d]">
                    Edit in Profile Settings
                </a>
            </div>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Full Name</p>
                    <p class="mt-1 font-medium text-slate-800">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Email</p>
                    <p class="mt-1 font-medium text-slate-800">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Contact Number</p>
                    <p class="mt-1 font-medium text-slate-800">{{ $user->contact_number ?: 'Not set' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Birthdate</p>
                    <p class="mt-1 font-medium text-slate-800">{{ $user->birthdate?->format('M d, Y') ?? 'Not set' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Gender</p>
                    <p class="mt-1 font-medium text-slate-800">{{ $user->gender ? ucfirst($user->gender) : 'Not set' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Address</p>
                    <p class="mt-1 font-medium text-slate-800">{{ $user->address ?: 'Not set' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Member Since</p>
                    <p class="mt-1 font-medium text-slate-800">{{ $user->created_at?->format('M d, Y h:i A') ?? '—' }}</p>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-900">Resident Details</h3>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">First Name</p>
                    <p class="mt-1 font-medium text-slate-800">{{ $displayFirstName }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Middle Name</p>
                    <p class="mt-1 font-medium text-slate-800">{{ $displayMiddleName }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Last Name</p>
                    <p class="mt-1 font-medium text-slate-800">{{ $displayLastName }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Suffix</p>
                    <p class="mt-1 font-medium text-slate-800">{{ $displaySuffix }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Civil Status</p>
                    <p class="mt-1 font-medium text-slate-800">{{ $residentProfile?->civil_status ?: 'Not set' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Occupation</p>
                    <p class="mt-1 font-medium text-slate-800">{{ $residentProfile?->occupation ?: 'Not set' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Purok</p>
                    <p class="mt-1 font-medium text-slate-800">{{ $residentProfile?->purok ?: 'Not set' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Barangay</p>
                    <p class="mt-1 font-medium text-slate-800">{{ $residentProfile?->barangay ?: 'Not set' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Municipality</p>
                    <p class="mt-1 font-medium text-slate-800">{{ $residentProfile?->municipality ?: 'Not set' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Province</p>
                    <p class="mt-1 font-medium text-slate-800">{{ $residentProfile?->province ?: 'Not set' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Valid ID Type</p>
                    <p class="mt-1 font-medium text-slate-800">{{ $residentProfile?->valid_id_type ?: 'Not set' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Valid ID Number</p>
                    <p class="mt-1 font-medium text-slate-800">{{ $residentProfile?->valid_id_number ?: 'Not set' }}</p>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
