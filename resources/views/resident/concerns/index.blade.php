<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-[#0038A8]">My Concerns</h2></x-slot>
    <div class="space-y-4">
        @if (session('success'))
            <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex items-center justify-between gap-3">
            <p class="text-sm text-slate-500">Track your submitted concerns and barangay responses.</p>
            <a href="{{ route('resident.concerns.create') }}" class="rounded-xl bg-[#0038A8] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#CE1126]">
                Submit New Concern
            </a>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead class="border-b border-slate-200 text-slate-500">
                        <tr>
                            <th class="px-4 py-3">Concern #</th>
                            <th class="px-4 py-3">Subject</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Submitted</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($concerns as $concern)
                            <tr class="border-b border-slate-100">
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $concern->concern_number }}</td>
                                <td class="px-4 py-3">{{ $concern->subject }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $statusClasses = match($concern->status) {
                                            'pending' => 'bg-amber-100 text-amber-800',
                                            'reviewing' => 'bg-blue-100 text-blue-800',
                                            'resolved' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            default => 'bg-slate-100 text-slate-700',
                                        };
                                    @endphp
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $statusClasses }}">
                                        {{ str_replace('_', ' ', $concern->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">{{ $concern->created_at?->format('M d, Y h:i A') }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('resident.concerns.show', $concern) }}" class="rounded-lg border border-[#0038A8] px-3 py-1.5 text-xs font-semibold text-[#0038A8] hover:bg-[#0038A8] hover:text-white">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-slate-500">No concerns submitted yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-200 px-4 py-3">
                {{ $concerns->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
