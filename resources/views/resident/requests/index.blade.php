<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#0038A8]">My Requests</h2>
    </x-slot>

    <div class="rounded-2xl bg-white p-6 shadow-sm">
        @if (session('success'))
            <div class="mb-4 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4 flex items-center justify-between gap-3">
            <p class="text-sm text-slate-500">View and track all your submitted document requests.</p>
            <a href="{{ route('resident.requests.create') }}" class="rounded-xl bg-[#0038A8] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#CE1126]">New Request</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[860px] text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500">
                        <th class="py-2 pr-3">Request #</th>
                        <th class="py-2 pr-3">Category</th>
                        <th class="py-2 pr-3">Purpose</th>
                        <th class="py-2 pr-3">Status</th>
                        <th class="py-2 pr-3">Date Requested</th>
                        <th class="py-2 pr-3">Remarks</th>
                        <th class="py-2 text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $request)
                        <tr class="border-b border-slate-100">
                            <td class="py-3 pr-3 font-medium text-slate-700">{{ $request->request_number }}</td>
                            <td class="py-3 pr-3">{{ $documentTypeLabels[$request->document_type] ?? str_replace('_', ' ', $request->document_type) }}</td>
                            <td class="py-3 pr-3">{{ \Illuminate\Support\Str::limit($request->purpose, 40) }}</td>
                            <td class="py-3 pr-3">
                                @php
                                    $statusClasses = match($request->status) {
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
                                <span class="rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $statusClasses }}">
                                    {{ str_replace('_', ' ', $request->status) }}
                                </span>
                            </td>
                            <td class="py-3 pr-3">{{ $request->created_at?->format('M d, Y h:i A') }}</td>
                            <td class="py-3 pr-3">{{ $request->remarks ? \Illuminate\Support\Str::limit($request->remarks, 30) : '—' }}</td>
                            <td class="py-3 text-right">
                                <a href="{{ route('resident.requests.show', $request) }}" class="rounded-lg border border-[#0038A8] px-3 py-1.5 text-xs font-semibold text-[#0038A8] transition hover:bg-[#0038A8] hover:text-white">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-slate-500">No requests yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $requests->links() }}</div>
    </div>
</x-app-layout>
