<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-[#0038A8]">Resident Records</h2></x-slot>
    <div class="space-y-4">
        @if (session('success'))
            <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                {{ session('success') }}
            </div>
        @endif

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="GET" action="{{ route('admin.resident-records') }}" class="grid gap-4 md:grid-cols-12">
                <div class="md:col-span-8">
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Search</label>
                    <input name="q" value="{{ $filters['q'] }}" placeholder="Name, email, or contact number" class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]">
                </div>
                <div class="md:col-span-3">
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Status</label>
                    <select name="status" class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#0038A8] focus:ring-[#0038A8]">
                        <option value="">All</option>
                        @foreach (['active', 'inactive', 'pending'] as $status)
                            <option value="{{ $status }}" @selected($filters['status'] === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end md:col-span-1">
                    <button type="submit" class="w-full rounded-xl bg-[#0038A8] px-3 py-2.5 text-sm font-semibold text-white hover:bg-[#002f8d]">Go</button>
                </div>
            </form>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left text-sm">
                    <thead class="border-b border-slate-200 text-slate-500">
                        <tr>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Contact</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Registered</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($residents as $resident)
                            <tr class="border-b border-slate-100">
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $resident->name }}</td>
                                <td class="px-4 py-3">{{ $resident->email }}</td>
                                <td class="px-4 py-3">{{ $resident->contact_number ?: '—' }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-700">{{ $resident->status }}</span>
                                </td>
                                <td class="px-4 py-3">{{ $resident->created_at?->format('M d, Y') }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.resident-records.show', $resident) }}" class="rounded-lg border border-[#0038A8] px-3 py-1.5 text-xs font-semibold text-[#0038A8] hover:bg-[#0038A8] hover:text-white">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-10 text-center text-slate-500">No resident records found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-200 px-4 py-3">{{ $residents->links() }}</div>
        </section>
    </div>
</x-app-layout>
