<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-xl font-semibold text-[#0038A8]">Request Details</h2>
            <a href="{{ route('resident.requests.index') }}" class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-400">
                Back to My Requests
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <section class="rounded-2xl bg-white p-6 shadow-sm">
            @php
                $statusClasses = match($documentRequest->status) {
                    'pending' => 'bg-amber-100 text-amber-800',
                    'under_review' => 'bg-blue-100 text-blue-800',
                    'approved' => 'bg-indigo-100 text-indigo-800',
                    'ready_for_printing' => 'bg-cyan-100 text-cyan-800',
                    'ready_for_claiming' => 'bg-green-100 text-green-800',
                    'completed' => 'bg-slate-200 text-slate-800',
                    'rejected' => 'bg-red-100 text-red-800',
                    default => 'bg-slate-100 text-slate-700',
                };
            @endphp
            <div class="mb-4 flex items-center justify-between gap-3">
                <h3 class="text-lg font-semibold text-slate-900">Current Status</h3>
                <span class="rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $statusClasses }}">
                    {{ str_replace('_', ' ', $documentRequest->status) }}
                </span>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Request Number</p>
                    <p class="mt-1 text-lg font-semibold text-slate-900">{{ $documentRequest->request_number }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Category</p>
                    <p class="mt-1 text-lg font-semibold text-slate-900">{{ $documentTypeLabel }}</p>
                </div>
                @if (filled($documentRequest->request_subtype))
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Form sub-type</p>
                        <p class="mt-1 text-lg font-semibold text-slate-900">{{ $requestSubtypeLabel ?? str_replace('_', ' ', $documentRequest->request_subtype) }}</p>
                    </div>
                @endif
                <div class="md:col-span-2">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Purpose</p>
                    <p class="mt-1 text-slate-700">{{ $documentRequest->purpose }}</p>
                </div>
                @if (! empty($documentRequest->request_data['additional_notes'] ?? null))
                    <div class="md:col-span-2">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Additional notes</p>
                        <p class="mt-1 text-slate-700">{{ $documentRequest->request_data['additional_notes'] }}</p>
                    </div>
                @elseif (! empty($documentRequest->request_data['additional_details'] ?? null))
                    <div class="md:col-span-2">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Additional Details / Notes</p>
                        <p class="mt-1 text-slate-700">{{ $documentRequest->request_data['additional_details'] }}</p>
                    </div>
                @endif
            </div>
        </section>

        <section class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-semibold text-slate-900">Status Timeline</h3>
            <div class="space-y-3">
                @forelse ($statusFlow as $status)
                    @php
                        $isCurrent = $documentRequest->status === $status;
                        $isPassed = array_search($status, $statusFlow, true) < array_search($documentRequest->status, $statusFlow, true);
                    @endphp
                    <div class="flex items-start gap-3">
                        <div class="mt-1 h-3 w-3 rounded-full {{ $isCurrent ? 'bg-[#CE1126]' : ($isPassed ? 'bg-[#0038A8]' : 'bg-slate-300') }}"></div>
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-wide {{ $isCurrent ? 'text-[#CE1126]' : 'text-slate-700' }}">
                                {{ str_replace('_', ' ', $status) }}
                            </p>
                            @if ($isCurrent)
                                <p class="text-xs text-slate-500">Current Status</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No timeline available.</p>
                @endforelse
            </div>
        </section>

        <section class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-semibold text-slate-900">Processing Details</h3>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Admin Remarks</p>
                    <p class="mt-1 text-slate-700">{{ $documentRequest->remarks ?: 'No remarks yet.' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Date Submitted</p>
                    <p class="mt-1 text-slate-700">{{ $documentRequest->created_at?->format('M d, Y h:i A') }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Date Reviewed</p>
                    <p class="mt-1 text-slate-700">{{ $documentRequest->reviewed_at?->format('M d, Y h:i A') ?? 'Not reviewed yet.' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Date Completed</p>
                    <p class="mt-1 text-slate-700">{{ $documentRequest->completed_at?->format('M d, Y h:i A') ?? 'Not completed yet.' }}</p>
                </div>
            </div>

            @if ($documentRequest->status === 'ready_for_claiming')
                <div class="mt-5 rounded-xl border border-[#0038A8]/20 bg-blue-50 px-4 py-3 text-sm text-slate-700">
                    <p class="font-semibold text-[#0038A8]">Claiming Instructions</p>
                    <p class="mt-1">Please visit the barangay office and bring a valid ID. The document must be signed/released by authorized barangay personnel.</p>
                </div>
            @endif

            @if ($documentRequest->status === 'completed')
                <div class="mt-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    Your request has been completed on {{ $documentRequest->completed_at?->format('M d, Y h:i A') ?? 'a recorded date' }}.
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
