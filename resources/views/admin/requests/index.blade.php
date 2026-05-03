<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#0038A8]">Document Requests</h2>
    </x-slot>

    <div class="space-y-6">
        @if (session('success'))
            <div class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
                {{ session('success') }}
            </div>
        @endif

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="GET" action="{{ route('admin.requests.index') }}" class="grid gap-4 md:grid-cols-12">
                <div class="md:col-span-5">
                    <label for="q" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Search</label>
                    <input
                        id="q"
                        name="q"
                        value="{{ $filters['q'] }}"
                        placeholder="Request number or resident name"
                        class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]"
                    />
                </div>
                <div class="md:col-span-3">
                    <label for="status" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Status</label>
                    <select id="status" name="status" class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]">
                        <option value="">All statuses</option>
                        @foreach (['pending','under_review','approved','ready_for_printing','ready_for_claiming','completed','rejected'] as $st)
                            <option value="{{ $st }}" @selected($filters['status'] === $st)>{{ str_replace('_', ' ', $st) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-3">
                    <label for="document_type" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Category</label>
                    <select id="document_type" name="document_type" class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]">
                        <option value="">All types</option>
                        @foreach ($documentTypeLabels as $dt => $label)
                            <option value="{{ $dt }}" @selected($filters['document_type'] === $dt)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2 md:col-span-1">
                    <button type="submit" class="w-full rounded-xl bg-[#0038A8] px-3 py-2.5 text-sm font-semibold text-white hover:bg-[#002f8d]">Go</button>
                </div>
            </form>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="hidden overflow-x-auto md:block">
                <table class="w-full min-w-[900px] text-left text-sm">
                    <thead class="border-b border-slate-200 text-slate-500">
                        <tr>
                            <th class="px-4 py-3">Request #</th>
                            <th class="px-4 py-3">Resident</th>
                            <th class="px-4 py-3">Document Type</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Date Submitted</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($requests as $request)
                            @php
                                $badge = match ($request->status) {
                                    'pending' => 'bg-amber-100 text-amber-800',
                                    'under_review' => 'bg-blue-100 text-blue-800',
                                    'approved', 'ready_for_printing', 'ready_for_claiming' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    'completed' => 'bg-slate-200 text-slate-800',
                                    default => 'bg-slate-100 text-slate-700',
                                };
                            @endphp
                            <tr class="border-b border-slate-100">
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $request->request_number }}</td>
                                <td class="px-4 py-3">{{ $request->user?->name }}</td>
                                <td class="px-4 py-3">{{ $documentTypeLabels[$request->document_type] ?? str_replace('_', ' ', $request->document_type) }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $badge }}">
                                        {{ str_replace('_', ' ', $request->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">{{ $request->created_at?->format('M d, Y h:i A') }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.requests.show', $request) }}" class="rounded-lg border border-[#0038A8] px-3 py-1.5 text-xs font-semibold text-[#0038A8] hover:bg-[#0038A8] hover:text-white">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-slate-500">No document requests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="space-y-3 p-4 md:hidden">
                @forelse ($requests as $request)
                    @php
                        $badge = match ($request->status) {
                            'pending' => 'bg-amber-100 text-amber-800',
                            'under_review' => 'bg-blue-100 text-blue-800',
                            'approved', 'ready_for_printing', 'ready_for_claiming' => 'bg-green-100 text-green-800',
                            'rejected' => 'bg-red-100 text-red-800',
                            'completed' => 'bg-slate-200 text-slate-800',
                            default => 'bg-slate-100 text-slate-700',
                        };
                    @endphp
                    <div class="rounded-xl border border-slate-200 p-4">
                        <p class="text-xs text-slate-500">{{ $request->request_number }}</p>
                        <p class="mt-1 font-semibold text-slate-900">{{ $request->user?->name }}</p>
                        <p class="mt-1 text-sm text-slate-600">{{ $documentTypeLabels[$request->document_type] ?? str_replace('_', ' ', $request->document_type) }}</p>
                        <div class="mt-3 flex items-center justify-between">
                            <span class="rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-wide {{ $badge }}">
                                {{ str_replace('_', ' ', $request->status) }}
                            </span>
                            <a href="{{ route('admin.requests.show', $request) }}" class="text-xs font-semibold text-[#0038A8]">View</a>
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl border border-slate-200 p-6 text-center text-slate-500">No document requests found.</div>
                @endforelse
            </div>

            <div class="border-t border-slate-200 px-4 py-3">
                {{ $requests->links() }}
            </div>
        </section>
    </div>
</x-app-layout>
