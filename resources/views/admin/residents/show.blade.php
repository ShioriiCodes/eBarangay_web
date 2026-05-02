<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-[#0038A8]">Resident Profile</h2>
            <a href="{{ route('admin.resident-records') }}" class="text-sm font-medium text-[#0038A8] hover:underline">← Back to records</a>
        </div>
    </x-slot>

    <div class="space-y-4">
        @if (session('success'))
            <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">{{ session('success') }}</div>
        @endif

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-900">{{ $resident->name }}</h3>
            <p class="text-sm text-slate-600">{{ $resident->email }}</p>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-900">Update Resident Details</h3>
            <form method="POST" action="{{ route('admin.resident-records.update', $resident) }}" class="mt-4 grid gap-4 md:grid-cols-2">
                @csrf
                @method('PATCH')
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Name</label>
                    <input name="name" value="{{ old('name', $resident->name) }}" class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Email</label>
                    <input name="email" type="email" value="{{ old('email', $resident->email) }}" class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Contact Number</label>
                    <input name="contact_number" value="{{ old('contact_number', $resident->contact_number) }}" class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Status</label>
                    <select name="status" class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]">
                        @foreach (['active', 'inactive', 'pending'] as $status)
                            <option value="{{ $status }}" @selected(old('status', $resident->status) === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Address</label>
                    <input name="address" value="{{ old('address', $resident->address) }}" class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]">
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="rounded-xl bg-[#0038A8] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#002f8d]">Save Changes</button>
                </div>
            </form>
        </section>

        <section class="rounded-2xl border border-red-200 bg-red-50 p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-[#CE1126]">Archive / Deactivate</h3>
            <p class="mt-1 text-sm text-slate-700">Set this resident account to inactive status.</p>
            <form method="POST" action="{{ route('admin.resident-records.deactivate', $resident) }}" class="mt-4">
                @csrf
                @method('PATCH')
                <button type="submit" class="rounded-xl border border-[#CE1126] px-4 py-2 text-sm font-semibold text-[#CE1126] hover:bg-red-100">Deactivate Account</button>
            </form>
        </section>
    </div>
</x-app-layout>
