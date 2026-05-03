<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#0038A8]">Request Document</h2>
    </x-slot>

    <div class="rounded-2xl bg-white p-6 shadow-sm">
        @if (session('success'))
            <div class="mb-4 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                {{ session('success') }}
            </div>
        @endif

        <p class="mb-6 text-sm text-slate-600">
            Choose a <strong>certificate</strong> or a <strong>request / inquiry form</strong> category. Official layouts for form categories will be connected later; your answers are stored in
            <code class="rounded bg-slate-100 px-1 text-xs">request_data</code> for staff and future PDF generation.
        </p>

        <div x-data="documentRequestForm(@js($formCatalog))">
            <form method="POST" action="{{ route('resident.requests.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="document_type" class="mb-2 block text-sm font-medium text-slate-700">Category</label>
                    <select
                        id="document_type"
                        name="document_type"
                        x-model="docType"
                        class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]"
                        required
                    >
                        <option value="">Select category</option>
                        <optgroup label="Certificates">
                            @foreach ($formCatalog['categories'] as $c)
                                @if (($c['group'] ?? 'forms') === 'certificates')
                                    <option value="{{ $c['key'] }}" @selected(old('document_type') === $c['key'])>{{ $c['label'] }}</option>
                                @endif
                            @endforeach
                        </optgroup>
                        <optgroup label="Requests / inquiries (templates pending)">
                            @foreach ($formCatalog['categories'] as $c)
                                @if (($c['group'] ?? 'forms') === 'forms')
                                    <option value="{{ $c['key'] }}" @selected(old('document_type') === $c['key'])>{{ $c['label'] }}</option>
                                @endif
                            @endforeach
                        </optgroup>
                    </select>
                    @error('document_type')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div x-show="needsSubtypePicker" x-cloak>
                    <label for="request_subtype" class="mb-2 block text-sm font-medium text-slate-700">Form sub-type</label>
                    <select
                        id="request_subtype"
                        name="request_subtype"
                        x-model="subtype"
                        class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]"
                        :required="needsSubtypePicker"
                    >
                        <option value="">Select sub-type</option>
                        <template x-for="s in (currentCategory?.subtypes ?? [])" :key="s.key">
                            <option :value="s.key" x-text="s.label"></option>
                        </template>
                    </select>
                    @error('request_subtype')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <template x-if="!needsSubtypePicker && (currentCategory?.subtypes?.length === 1)">
                    <input type="hidden" name="request_subtype" :value="currentCategory.subtypes[0].key">
                </template>

                <template x-for="field in activeFields" :key="field.name">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700" x-text="field.label"></label>
                        <template x-if="field.type === 'textarea'">
                            <textarea
                                class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]"
                                rows="4"
                                :name="'form_fields[' + field.name + ']'"
                                x-model="formFields[field.name]"
                                :maxlength="field.max"
                            ></textarea>
                        </template>
                        <template x-if="field.type !== 'textarea'">
                            <input
                                type="text"
                                class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]"
                                :name="'form_fields[' + field.name + ']'"
                                x-model="formFields[field.name]"
                                :maxlength="field.max"
                            />
                        </template>
                    </div>
                </template>

                <div>
                    <label for="purpose" class="mb-2 block text-sm font-medium text-slate-700">Purpose</label>
                    <textarea
                        id="purpose"
                        name="purpose"
                        x-model="purpose"
                        rows="4"
                        class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]"
                        placeholder="State why you need this document or request."
                        required
                    ></textarea>
                    @error('purpose')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="additional_details" class="mb-2 block text-sm font-medium text-slate-700">Additional notes (optional)</label>
                    <textarea
                        id="additional_details"
                        name="additional_details"
                        x-model="additional_details"
                        rows="3"
                        class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]"
                        placeholder="Anything else staff should know."
                    ></textarea>
                    @error('additional_details')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                @foreach ($errors->keys() as $errKey)
                    @if (str_starts_with($errKey, 'form_fields.'))
                        @foreach ($errors->get($errKey) as $m)
                            <p class="text-sm text-red-600">{{ $m }}</p>
                        @endforeach
                    @endif
                @endforeach

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('resident.requests.index') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-400">Cancel</a>
                    <button type="submit" class="rounded-xl bg-[#0038A8] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#CE1126]">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
