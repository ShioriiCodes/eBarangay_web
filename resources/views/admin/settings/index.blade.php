<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-[#0038A8]">Barangay settings</h2>
            <p class="mt-1 text-sm text-slate-600">Official information used on certificates and PDFs. One record for the whole system.</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl space-y-6">
        @if (session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">1. Barangay information</h3>
                <div class="mt-4 grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="barangay_name" class="mb-1.5 block text-sm font-medium text-slate-700">Barangay name</label>
                        <input id="barangay_name" name="barangay_name" type="text" value="{{ old('barangay_name', $setting->barangay_name) }}" required
                            class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-[#0038A8] focus:ring-[#0038A8]" />
                    </div>
                    <div>
                        <label for="municipality" class="mb-1.5 block text-sm font-medium text-slate-700">Municipality / city</label>
                        <input id="municipality" name="municipality" type="text" value="{{ old('municipality', $setting->municipality) }}" required
                            class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-[#0038A8] focus:ring-[#0038A8]" />
                    </div>
                    <div>
                        <label for="province" class="mb-1.5 block text-sm font-medium text-slate-700">Province</label>
                        <input id="province" name="province" type="text" value="{{ old('province', $setting->province) }}" required
                            class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-[#0038A8] focus:ring-[#0038A8]" />
                    </div>
                    <div class="sm:col-span-2">
                        <label for="office_address" class="mb-1.5 block text-sm font-medium text-slate-700">Office address</label>
                        <textarea id="office_address" name="office_address" rows="3"
                            class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-[#0038A8] focus:ring-[#0038A8]">{{ old('office_address', $setting->office_address) }}</textarea>
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">2. Officials</h3>
                <div class="mt-4 grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label for="captain_name" class="mb-1.5 block text-sm font-medium text-slate-700">Punong barangay / captain</label>
                        <input id="captain_name" name="captain_name" type="text" value="{{ old('captain_name', $setting->captain_name) }}"
                            class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-[#0038A8] focus:ring-[#0038A8]" />
                    </div>
                    <div>
                        <label for="secretary_name" class="mb-1.5 block text-sm font-medium text-slate-700">Barangay secretary</label>
                        <input id="secretary_name" name="secretary_name" type="text" value="{{ old('secretary_name', $setting->secretary_name) }}"
                            class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-[#0038A8] focus:ring-[#0038A8]" />
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">3. Contact information</h3>
                <div class="mt-4 grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label for="contact_number" class="mb-1.5 block text-sm font-medium text-slate-700">Contact number</label>
                        <input id="contact_number" name="contact_number" type="text" value="{{ old('contact_number', $setting->contact_number) }}"
                            class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-[#0038A8] focus:ring-[#0038A8]" />
                    </div>
                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700">Office email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $setting->email) }}"
                            class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-[#0038A8] focus:ring-[#0038A8]" />
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">4. Barangay seal / logo</h3>
                <p class="mt-1 text-xs text-slate-500">PNG or JPG, up to 2&nbsp;MB. Shown on certificate headers.</p>

                @if ($setting->logo_path)
                    <div class="mt-4 flex flex-wrap items-end gap-4">
                        <img src="{{ asset('storage/'.$setting->logo_path) }}" alt="Current logo" class="h-20 w-auto rounded-lg border border-slate-200 bg-white object-contain p-1" />
                        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                            <input type="checkbox" name="remove_logo" value="1" class="rounded border-slate-300 text-[#0038A8] focus:ring-[#0038A8]" @checked(old('remove_logo')) />
                            Remove current logo
                        </label>
                    </div>
                @endif

                <div class="mt-4">
                    <label for="logo" class="mb-1.5 block text-sm font-medium text-slate-700">Upload new image</label>
                    <input id="logo" name="logo" type="file" accept="image/*"
                        class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-[#0038A8] hover:file:bg-blue-100" />
                </div>
            </section>

            <div class="flex flex-wrap items-center gap-3">
                <button type="submit" class="rounded-xl bg-[#0038A8] px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#002f8d] focus:outline-none focus:ring-2 focus:ring-[#0038A8] focus:ring-offset-2">
                    Save settings
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
