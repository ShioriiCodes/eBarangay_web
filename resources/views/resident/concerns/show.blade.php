<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-[#0038A8]">Concern Details</h2>
            <a href="{{ route('resident.concerns.index') }}" class="text-sm font-medium text-[#0038A8] hover:underline">Back to My Concerns</a>
        </div>
    </x-slot>

    <div class="space-y-4">
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @php
                $statusClasses = match($concern->status) {
                    'pending' => 'bg-amber-100 text-amber-800',
                    'reviewing' => 'bg-blue-100 text-blue-800',
                    'resolved' => 'bg-green-100 text-green-800',
                    'rejected' => 'bg-red-100 text-red-800',
                    default => 'bg-slate-100 text-slate-700',
                };
                $statusFlow = ['pending', 'reviewing', 'resolved'];
            @endphp
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Concern Number</p>
                    <p class="mt-1 font-semibold text-slate-900">{{ $concern->concern_number }}</p>
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
            <h3 class="text-lg font-semibold text-slate-900">Concern Timeline</h3>
            <div class="mt-4 space-y-3">
                @if ($concern->status === 'rejected')
                    <div class="flex items-start gap-3">
                        <div class="mt-1 h-3 w-3 rounded-full bg-red-500"></div>
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-wide text-red-700">rejected</p>
                            <p class="text-xs text-slate-500">Your concern was reviewed and marked as rejected.</p>
                        </div>
                    </div>
                @endif
                @foreach ($statusFlow as $status)
                    @php
                        $currentIndex = array_search($concern->status, $statusFlow, true);
                        $stepIndex = array_search($status, $statusFlow, true);
                        $isDone = $currentIndex !== false && $stepIndex < $currentIndex;
                        $isCurrent = $concern->status === $status;
                    @endphp
                    <div class="flex items-start gap-3">
                        <div class="mt-1 h-3 w-3 rounded-full {{ $isCurrent ? 'bg-[#CE1126]' : ($isDone ? 'bg-[#0038A8]' : 'bg-slate-300') }}"></div>
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-wide {{ $isCurrent ? 'text-[#CE1126]' : 'text-slate-700' }}">{{ $status }}</p>
                            @if ($isCurrent)
                                <p class="text-xs text-slate-500">Current status</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-900">Barangay Response</h3>
            <p class="mt-2 text-sm text-slate-700">{{ $concern->response ?: 'No official response yet.' }}</p>
            <p class="mt-3 text-xs text-slate-500">Handled at: {{ $concern->handled_at?->format('M d, Y h:i A') ?? 'Not yet handled.' }}</p>
        </section>
    </div>
</x-app-layout>
