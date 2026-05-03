<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-xl font-semibold text-[#0038A8]">Request {{ $documentRequest->request_number }}</h2>
            <a href="{{ route('admin.requests.index') }}" class="text-sm font-medium text-[#0038A8] hover:underline">← Back to list</a>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if (session('success'))
            <div class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
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

        <section class="grid gap-4 lg:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-2">
                <h3 class="text-lg font-semibold text-slate-900">Resident</h3>
                <dl class="mt-4 grid gap-3 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Name</dt>
                        <dd class="font-medium text-slate-800">{{ $documentRequest->user?->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Email</dt>
                        <dd class="font-medium text-slate-800">{{ $documentRequest->user?->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Contact</dt>
                        <dd class="font-medium text-slate-800">{{ $documentRequest->user?->contact_number ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Address</dt>
                        <dd class="font-medium text-slate-800">{{ $documentRequest->user?->address ?: '—' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900">Current Status</h3>
                @php
                    $badge = match ($documentRequest->status) {
                        'pending' => 'bg-amber-100 text-amber-800',
                        'under_review' => 'bg-blue-100 text-blue-800',
                        'approved', 'ready_for_printing', 'ready_for_claiming' => 'bg-green-100 text-green-800',
                        'rejected' => 'bg-red-100 text-red-800',
                        'completed' => 'bg-slate-200 text-slate-800',
                        default => 'bg-slate-100 text-slate-700',
                    };
                @endphp
                <p class="mt-3">
                    <span class="rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $badge }}">
                        {{ str_replace('_', ' ', $documentRequest->status) }}
                    </span>
                </p>
                @if ($documentRequest->status === 'approved')
                    <a href="{{ route('documents.preview', $documentRequest) }}" target="_blank" class="mt-4 inline-flex w-full items-center justify-center rounded-xl bg-[#0038A8] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#002f8d]">
                        Preview certificate
                    </a>
                @endif
                @if (in_array($documentRequest->status, ['approved', 'ready_for_printing', 'ready_for_claiming', 'completed'], true))
                    <a href="{{ route('documents.download-pdf', $documentRequest) }}" class="mt-2 inline-flex w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-800 hover:border-[#0038A8] hover:text-[#0038A8]">
                        Download PDF
                    </a>
                @endif
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-900">Request Details</h3>
            <dl class="mt-4 grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Request category</dt>
                    <dd class="font-medium text-slate-800">{{ $documentTypeLabel }}</dd>
                </div>
                @if (filled($documentRequest->request_subtype))
                    <div class="sm:col-span-2">
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Form sub-type</dt>
                        <dd class="font-medium text-slate-800">{{ $requestSubtypeLabel ?? str_replace('_', ' ', $documentRequest->request_subtype) }}</dd>
                    </div>
                @endif
                <div class="sm:col-span-2">
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Purpose</dt>
                    <dd class="text-slate-800">{{ $documentRequest->purpose }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Structured submission data</dt>
                    <dd class="mt-2 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-800">
                        @include('admin.requests.partials.request-data-summary', ['documentRequest' => $documentRequest])
                    </dd>
                </div>
            </dl>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-900">Remarks (visible to resident)</h3>
            <form method="POST" action="{{ route('admin.requests.remarks', $documentRequest) }}" class="mt-4 space-y-3">
                @csrf
                @method('PATCH')
                <textarea name="remarks" rows="4" class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]" placeholder="Enter official remarks or instructions for the resident.">{{ old('remarks', $documentRequest->remarks) }}</textarea>
                <button type="submit" class="rounded-xl bg-[#0038A8] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#002f8d]">Save Remarks</button>
            </form>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-900">Update Status</h3>
            <p class="mt-1 text-sm text-slate-600">Workflow advances one step at a time. Rejecting requires remarks.</p>
            <form method="POST" action="{{ route('admin.requests.status', $documentRequest) }}" class="mt-4 grid gap-3 md:grid-cols-3">
                @csrf
                @method('PATCH')
                <div class="md:col-span-1">
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Next Status</label>
                    <select name="status" class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]">
                        @php
                            $next = match ($documentRequest->status) {
                                'pending' => 'under_review',
                                'under_review' => 'approved',
                                'approved' => 'ready_for_printing',
                                'ready_for_printing' => 'ready_for_claiming',
                                'ready_for_claiming' => 'completed',
                                default => null,
                            };
                        @endphp
                        @if ($next)
                            <option value="{{ $next }}">{{ str_replace('_', ' ', $next) }}</option>
                        @endif
                        @if (! in_array($documentRequest->status, ['completed', 'rejected'], true))
                            <option value="rejected">Rejected</option>
                        @endif
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Remarks</label>
                    <textarea name="remarks" rows="3" class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]" placeholder="Optional notes (required if rejecting).">{{ old('remarks', $documentRequest->remarks) }}</textarea>
                </div>
                <div class="md:col-span-3">
                    <button type="submit" class="rounded-xl bg-[#0038A8] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#002f8d]">Apply Status Update</button>
                </div>
            </form>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-900">Release Workflow Actions</h3>
            <p class="mt-1 text-sm text-slate-600">Use these actions after approval and document preparation to complete the release lifecycle.</p>

            @if (in_array($documentRequest->status, ['approved', 'ready_for_printing', 'ready_for_claiming'], true))
                @php
                    $releaseAction = match ($documentRequest->status) {
                        'approved' => ['next' => 'ready_for_printing', 'label' => 'Mark as Ready for Printing', 'hint' => 'Confirm the request is queued for official printing.'],
                        'ready_for_printing' => ['next' => 'ready_for_claiming', 'label' => 'Mark as Ready for Claiming', 'hint' => 'Confirm resident can claim the document from the barangay office.'],
                        'ready_for_claiming' => ['next' => 'completed', 'label' => 'Mark as Completed', 'hint' => 'Use only after successful claiming and release confirmation.'],
                    };
                @endphp
                <form method="POST" action="{{ route('admin.requests.status', $documentRequest) }}" class="mt-4 space-y-3">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="{{ $releaseAction['next'] }}">
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Release Remarks (optional)</label>
                        <textarea name="remarks" rows="3" class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]" placeholder="Add release notes visible to resident.">{{ old('remarks', $documentRequest->remarks) }}</textarea>
                    </div>
                    <p class="text-xs text-slate-500">{{ $releaseAction['hint'] }}</p>
                    <button type="submit" class="rounded-xl bg-[#0038A8] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#002f8d]">
                        {{ $releaseAction['label'] }}
                    </button>
                </form>
            @elseif ($documentRequest->status === 'completed')
                <div class="mt-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    This request has already been completed and released.
                </div>
            @else
                <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                    Release actions become available once this request is approved.
                </div>
            @endif
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-900">Important Dates</h3>
            <dl class="mt-4 grid gap-3 sm:grid-cols-3">
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Submitted</dt>
                    <dd class="font-medium text-slate-800">{{ $documentRequest->created_at?->format('M d, Y h:i A') }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Reviewed</dt>
                    <dd class="font-medium text-slate-800">{{ $documentRequest->reviewed_at?->format('M d, Y h:i A') ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Completed</dt>
                    <dd class="font-medium text-slate-800">{{ $documentRequest->completed_at?->format('M d, Y h:i A') ?? '—' }}</dd>
                </div>
            </dl>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-900">Status Timeline</h3>
            <ol class="mt-4 space-y-3">
                @foreach ($statusFlow as $idx => $status)
                    @php
                        $activeIndex = array_search($documentRequest->status, $statusFlow, true);
                        $isDone = $activeIndex !== false && $idx < $activeIndex;
                        $isCurrent = $documentRequest->status === $status;
                    @endphp
                    <li class="flex gap-3">
                        <span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full {{ $isCurrent ? 'bg-[#CE1126]' : ($isDone ? 'bg-[#0038A8]' : 'bg-slate-300') }}"></span>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ str_replace('_', ' ', $status) }}</p>
                            @if ($isCurrent)
                                <p class="text-xs text-slate-500">Current stage</p>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ol>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-900">Status History</h3>
            <div class="mt-4 space-y-3">
                @php
                    $histories = $documentRequest->statusHistories->sortByDesc('created_at');
                @endphp
                @forelse ($histories as $history)
                    <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 text-sm">
                        <p class="font-semibold text-slate-800">
                            {{ $history->old_status ? str_replace('_', ' ', $history->old_status).' → ' : '' }}{{ str_replace('_', ' ', $history->new_status) }}
                        </p>
                        <p class="text-xs text-slate-500">{{ $history->created_at?->format('M d, Y h:i A') }} · {{ $history->changedBy?->name ?? 'System' }}</p>
                        @if ($history->remarks)
                            <p class="mt-2 text-slate-700">{{ $history->remarks }}</p>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No history entries yet.</p>
                @endforelse
            </div>
        </section>
    </div>
</x-app-layout>
