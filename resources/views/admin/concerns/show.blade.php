<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-[#0038A8]">Concern {{ $concern->concern_number }}</h2>
            <a href="{{ route('admin.concerns.index') }}" class="text-sm font-medium text-[#0038A8] hover:underline">← Back to concerns</a>
        </div>
    </x-slot>

    <div class="space-y-4">
        @if (session('success'))
            <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-900">Concern Details</h3>
            @php
                $statusClasses = match($concern->status) {
                    'pending' => 'bg-amber-100 text-amber-800',
                    'reviewing' => 'bg-blue-100 text-blue-800',
                    'resolved' => 'bg-green-100 text-green-800',
                    'rejected' => 'bg-red-100 text-red-800',
                    default => 'bg-slate-100 text-slate-700',
                };
            @endphp
            <div class="mt-4 grid gap-4 md:grid-cols-2">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Resident</p>
                    <p class="mt-1 font-medium text-slate-800">{{ $concern->user?->name }} ({{ $concern->user?->email }})</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Status</p>
                    <p class="mt-1">
                        <span class="rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $statusClasses }}">
                            {{ str_replace('_', ' ', $concern->status) }}
                        </span>
                    </p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Subject</p>
                    <p class="mt-1 text-slate-800">{{ $concern->subject }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Message</p>
                    <p class="mt-1 text-slate-800">{{ $concern->message }}</p>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-900">Update Concern Status</h3>
            <form method="POST" action="{{ route('admin.concerns.status', $concern) }}" class="mt-4 grid gap-4 md:grid-cols-3">
                @csrf
                @method('PATCH')
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Status</label>
                    <select name="status" class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]">
                        @php
                            $transitions = [
                                'pending' => ['pending', 'reviewing', 'rejected'],
                                'reviewing' => ['reviewing', 'resolved', 'rejected'],
                                'resolved' => ['resolved'],
                                'rejected' => ['rejected'],
                            ];
                            $availableStatuses = $transitions[$concern->status] ?? [$concern->status];
                        @endphp
                        @foreach ($availableStatuses as $status)
                            <option value="{{ $status }}" @selected(old('status', $concern->status) === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Response / Remarks</label>
                    <textarea name="response" rows="3" class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]" placeholder="Required when rejecting a concern.">{{ old('response', $concern->response) }}</textarea>
                </div>
                <div class="md:col-span-3">
                    <button type="submit" class="rounded-xl bg-[#0038A8] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#002f8d]">Update Concern</button>
                </div>
            </form>
            <p class="mt-3 text-xs text-slate-500">Handled by: {{ $concern->handledBy?->name ?? '—' }} · Handled at: {{ $concern->handled_at?->format('M d, Y h:i A') ?? '—' }}</p>
        </section>
    </div>
</x-app-layout>
