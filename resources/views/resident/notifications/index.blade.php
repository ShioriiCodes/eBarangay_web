<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-[#0038A8]">Notifications</h2></x-slot>
    <div class="space-y-3">
        @if (session('success'))
            <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                {{ session('success') }}
            </div>
        @endif
        @forelse($notifications as $notification)
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm {{ $notification->is_read ? 'opacity-80' : '' }}">
                <p class="font-semibold">{{ $notification->title }}</p>
                <p class="text-sm text-slate-600">{{ $notification->message }}</p>
                <div class="mt-3 flex items-center justify-between text-xs text-slate-500">
                    <span>{{ $notification->created_at?->diffForHumans() }}</span>
                    @if (! $notification->is_read)
                        <form method="POST" action="{{ route('resident.notifications.read', $notification) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="font-semibold text-[#0038A8] hover:underline">Mark as read</button>
                        </form>
                    @else
                        <span class="rounded-full bg-slate-100 px-2 py-0.5">Read</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">No notifications yet.</div>
        @endforelse
        <div>{{ $notifications->links() }}</div>
    </div>
</x-app-layout>
