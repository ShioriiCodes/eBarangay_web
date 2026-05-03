<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-blue-700">Notifications</h2></x-slot>
    <div class="space-y-3">
        @forelse($notifications as $notification)
            <div class="rounded-xl bg-white p-4 shadow">
                <p class="font-semibold">{{ $notification->title }}</p>
                <p class="text-sm text-slate-600">{{ $notification->message }}</p>
            </div>
        @empty
            <div class="rounded-xl bg-white p-4 shadow">No notifications yet.</div>
        @endforelse
        <div>{{ $notifications->links() }}</div>
    </div>
</x-app-layout>
