<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#0038A8]">Resident Dashboard</h2>
    </x-slot>

    <div class="space-y-6">
        <section class="rounded-2xl bg-gradient-to-r from-[#0038A8] via-[#174bb7] to-[#0038A8] p-6 text-white shadow-lg shadow-blue-100">
            <div class="mb-3 inline-flex rounded-full bg-[#CE1126]/90 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white">
                Barangay Resident Portal
            </div>
            <h3 class="text-2xl font-bold">Welcome, {{ auth()->user()->greetingFirstName() }}</h3>
            <p class="mt-2 text-sm text-blue-100">
                Manage your barangay requests and services online.
            </p>
            <div class="mt-4 h-1 w-20 rounded-full bg-[#CE1126]"></div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <a href="{{ route('resident.requests.create') }}" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-[#0038A8] hover:shadow">
                <div class="text-[#0038A8]">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M8 3h8l5 5v13H3V3h5z"/>
                        <path d="M16 3v6h5"/>
                    </svg>
                </div>
                <p class="mt-2 text-xs font-semibold uppercase tracking-wide text-[#0038A8]">Quick Action</p>
                <h4 class="mt-1 text-lg font-semibold text-slate-900">Request Document</h4>
            </a>
            <a href="{{ route('resident.requests.index') }}" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-[#0038A8] hover:shadow">
                <div class="text-[#0038A8]">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M3 6h5l2 2h11v10a2 2 0 0 1-2 2H3z"/>
                        <path d="M3 6V4a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2"/>
                    </svg>
                </div>
                <p class="mt-2 text-xs font-semibold uppercase tracking-wide text-[#0038A8]">Quick Action</p>
                <h4 class="mt-1 text-lg font-semibold text-slate-900">My Requests</h4>
            </a>
            <a href="{{ route('resident.concerns.create') }}" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-[#CE1126] hover:shadow">
                <div class="text-[#CE1126]">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M4 16V6a2 2 0 0 1 2-2h12v9a2 2 0 0 1-2 2H8l-4 4v-3z"/>
                        <path d="M10 8h4"/>
                        <path d="M10 12h6"/>
                    </svg>
                </div>
                <p class="mt-2 text-xs font-semibold uppercase tracking-wide text-[#CE1126]">Quick Action</p>
                <h4 class="mt-1 text-lg font-semibold text-slate-900">Submit Concern</h4>
            </a>
            <a href="{{ route('resident.notifications.index') }}" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-[#0038A8] hover:shadow">
                <div class="text-[#0038A8]">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M15 18H9m9-2H6l1.5-2V10a4.5 4.5 0 0 1 9 0v4L18 16z"/>
                        <path d="M10 18a2 2 0 0 0 4 0"/>
                    </svg>
                </div>
                <p class="mt-2 text-xs font-semibold uppercase tracking-wide text-[#0038A8]">Quick Action</p>
                <h4 class="mt-1 text-lg font-semibold text-slate-900">Notifications</h4>
            </a>
        </section>

        @if ($latestAnnouncements->isNotEmpty())
            <section class="rounded-2xl border border-[#0038A8]/20 bg-white p-5 shadow-sm">
                <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="text-lg font-semibold text-[#0038A8]">Latest Barangay Announcements</h3>
                    <a href="{{ route('resident.announcements.index') }}" class="text-sm font-semibold text-[#0038A8] hover:underline">View all</a>
                </div>
                <div class="grid gap-3 md:grid-cols-3">
                    @foreach ($latestAnnouncements as $announcement)
                        @php
                            $priorityClasses = match ($announcement->priority) {
                                'urgent' => 'bg-red-100 text-red-800',
                                'important' => 'bg-amber-100 text-amber-800',
                                default => 'bg-slate-100 text-slate-700',
                            };
                        @endphp
                        <a href="{{ route('resident.announcements.show', $announcement) }}" class="rounded-xl border border-slate-200 p-4 transition hover:border-[#0038A8] hover:shadow">
                            <div class="flex items-start justify-between gap-2">
                                <p class="text-sm font-semibold text-slate-900">{{ $announcement->title }}</p>
                                <span class="shrink-0 rounded-full px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide {{ $priorityClasses }}">{{ $announcement->priority }}</span>
                            </div>
                            <p class="mt-2 line-clamp-2 text-xs text-slate-600">{{ \Illuminate\Support\Str::limit(strip_tags($announcement->content), 120) }}</p>
                            <p class="mt-2 text-[11px] text-slate-500">{{ $announcement->published_at?->format('M d, Y') ?? '—' }}</p>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-2 text-slate-700">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M4 19h16"/>
                        <path d="M7 16V9"/>
                        <path d="M12 16V5"/>
                        <path d="M17 16v-3"/>
                    </svg>
                </div>
                <p class="text-sm text-slate-500">Total Requests</p>
                <p class="mt-1 text-3xl font-bold text-slate-900">{{ $stats['total_requests'] }}</p>
            </div>
            <div class="rounded-xl border border-amber-200 bg-white p-5 shadow-sm">
                <div class="mb-2 text-amber-500">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M7 3h10M7 21h10"/>
                        <path d="M8 3v4l4 4-4 4v6"/>
                        <path d="M16 3v4l-4 4 4 4v6"/>
                    </svg>
                </div>
                <p class="text-sm text-slate-500">Pending Requests</p>
                <p class="mt-1 text-3xl font-bold text-amber-500">{{ $stats['pending_requests'] }}</p>
            </div>
            <div class="rounded-xl border border-blue-200 bg-white p-5 shadow-sm">
                <div class="mb-2 text-[#0038A8]">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="m8.5 12.5 2.5 2.5 4.5-5"/>
                    </svg>
                </div>
                <p class="text-sm text-slate-500">Approved Requests</p>
                <p class="mt-1 text-3xl font-bold text-[#0038A8]">{{ $stats['approved_requests'] }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-2 text-slate-700">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M3 6h5l2 2h11v10a2 2 0 0 1-2 2H3z"/>
                        <path d="M3 6V4a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2"/>
                    </svg>
                </div>
                <p class="text-sm text-slate-500">Completed Requests</p>
                <p class="mt-1 text-3xl font-bold text-slate-700">{{ $stats['completed_requests'] }}</p>
            </div>
        </section>

        <section class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Recent Document Requests</h3>
                <a href="{{ route('resident.requests.index') }}" class="text-sm font-medium text-[#0038A8] hover:underline">View all</a>
            </div>

            <div class="hidden overflow-x-auto md:block">
                <table class="w-full min-w-[620px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500">
                            <th class="py-2 pr-3">Request #</th>
                            <th class="py-2 pr-3">Document Type</th>
                            <th class="py-2 pr-3">Status</th>
                            <th class="py-2 pr-3">Date Requested</th>
                            <th class="py-2 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentRequests as $request)
                            <tr class="border-b border-slate-100">
                                <td class="py-3 pr-3 font-medium text-slate-700">{{ $request->request_number }}</td>
                                <td class="py-3 pr-3">{{ $documentTypeLabels[$request->document_type] ?? str_replace('_', ' ', $request->document_type) }}</td>
                                <td class="py-3 pr-3">
                                    @php
                                        $statusClasses = match($request->status) {
                                            'pending' => 'bg-amber-100 text-amber-700',
                                            'under_review' => 'bg-blue-100 text-blue-700',
                                            'approved', 'ready_for_printing', 'ready_for_claiming' => 'bg-green-100 text-green-700',
                                            'rejected' => 'bg-red-100 text-red-700',
                                            'completed' => 'bg-slate-200 text-slate-700',
                                            default => 'bg-slate-100 text-slate-700',
                                        };
                                    @endphp
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $statusClasses }}">
                                        {{ str_replace('_', ' ', $request->status) }}
                                    </span>
                                </td>
                                <td class="py-3 pr-3">{{ $request->created_at?->format('M d, Y') }}</td>
                                <td class="py-3 text-right">
                                    <a href="{{ route('resident.requests.show', $request) }}" class="rounded-lg border border-[#0038A8] px-3 py-1.5 text-xs font-semibold text-[#0038A8] transition hover:bg-[#0038A8] hover:text-white">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-500">No document requests yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="space-y-3 md:hidden">
                @forelse ($recentRequests as $request)
                    @php
                        $statusClasses = match($request->status) {
                            'pending' => 'bg-amber-100 text-amber-700',
                            'under_review' => 'bg-blue-100 text-blue-700',
                            'approved', 'ready_for_printing', 'ready_for_claiming' => 'bg-green-100 text-green-700',
                            'rejected' => 'bg-red-100 text-red-700',
                            'completed' => 'bg-slate-200 text-slate-700',
                            default => 'bg-slate-100 text-slate-700',
                        };
                    @endphp
                    <div class="rounded-xl border border-slate-200 p-4">
                        <p class="text-xs text-slate-500">{{ $request->request_number }}</p>
                        <p class="mt-1 font-semibold text-slate-900">{{ $documentTypeLabels[$request->document_type] ?? str_replace('_', ' ', $request->document_type) }}</p>
                        <div class="mt-3 flex items-center justify-between">
                            <span class="rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-wide {{ $statusClasses }}">
                                {{ str_replace('_', ' ', $request->status) }}
                            </span>
                            <a href="{{ route('resident.requests.show', $request) }}" class="text-xs font-semibold text-[#0038A8]">View</a>
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl border border-slate-200 p-5 text-sm text-slate-500">No document requests yet.</div>
                @endforelse
            </div>
        </section>

        <section class="rounded-2xl border border-[#0038A8]/20 bg-white p-5 shadow-sm">
            <h3 class="text-lg font-semibold text-[#0038A8]">Helpful Information</h3>
            <ul class="mt-3 space-y-2 text-sm text-slate-700">
                <li>Office hours: Monday to Friday, 8:00 AM to 5:00 PM.</li>
                <li>Please bring a valid ID when claiming approved documents.</li>
                <li>Documents requiring signature are released only after authorized signatory approval.</li>
            </ul>
        </section>
    </div>
</x-app-layout>
