<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-blue-700">Admin Dashboard</h2></x-slot>
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
        <div class="rounded-xl bg-white p-4 shadow">Residents: {{ $stats['total_residents'] }}</div>
        <div class="rounded-xl bg-white p-4 shadow">Open Requests: {{ $stats['open_requests'] }}</div>
        <div class="rounded-xl bg-white p-4 shadow">Open Concerns: {{ $stats['open_concerns'] }}</div>
        <div class="rounded-xl bg-white p-4 shadow">Unread Notifications: {{ $stats['unread_notifications'] }}</div>
        <div class="rounded-xl bg-white p-4 shadow">Published Announcements: {{ $stats['published_announcements'] }}</div>
    </div>
</x-app-layout>
