@php
    $notificationItems = $topbarNotifications->map(fn ($notification) => [
        'id' => $notification->id,
        'title' => $notification->title,
        'message' => $notification->message,
        'type' => $notification->type,
        'is_read' => (bool) $notification->is_read,
        'relative_time' => $notification->relative_time,
        'link' => $notification->link,
    ])->values();
@endphp

<div class="flex items-center gap-2 sm:gap-3">
    <div
        x-data="notificationCenter({
            items: @js($notificationItems),
            unread: {{ $topbarUnreadCount }},
            csrf: '{{ csrf_token() }}',
            markReadUrlBase: '{{ url('/notifications') }}',
            markAllUrl: '{{ route('notifications.mark-all-read') }}',
            latestUrl: '{{ route('notifications.latest') }}'
        })"
        x-init="initPolling()"
        x-on:keydown.escape.window="open = false"
        class="relative"
    >
        <button
            @click="open = !open"
            class="relative inline-flex h-11 w-11 items-center justify-center rounded-xl border border-blue-100 bg-white text-slate-600 shadow-sm transition hover:bg-blue-50"
            aria-label="Open notifications"
            title="Notifications"
        >
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M15 18H9m9-2H6l1.5-2V10a4.5 4.5 0 0 1 9 0v4L18 16z"/>
                <path d="M10 18a2 2 0 0 0 4 0"/>
            </svg>
            <span
                x-show="unreadCount > 0"
                x-text="unreadCount > 99 ? '99+' : unreadCount"
                class="absolute -right-1.5 -top-1.5 inline-flex min-h-5 min-w-5 items-center justify-center rounded-full bg-[#CE1126] px-1 text-[10px] font-bold text-white"
                x-cloak
            ></span>
        </button>

        <div
            x-show="open"
            x-transition
            @click.outside="open = false"
            class="absolute right-0 z-50 mt-2 w-[22rem] rounded-2xl border border-slate-200 bg-white shadow-xl"
            x-cloak
        >
            <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                <div class="text-sm font-semibold text-slate-800">
                    <span x-text="unreadCount"></span> unread
                </div>
                <button
                    type="button"
                    @click="markAllAsRead"
                    :disabled="unreadCount === 0 || loading"
                    class="text-xs font-semibold text-[#0038A8] transition hover:underline disabled:cursor-not-allowed disabled:text-slate-400"
                >
                    Mark all as read
                </button>
            </div>

            <div class="max-h-96 overflow-y-auto p-2">
                <template x-if="notifications.length === 0">
                    <div class="rounded-xl px-3 py-8 text-center text-sm text-slate-500">No notifications yet.</div>
                </template>

                <template x-for="notification in notifications" :key="notification.id">
                    <button
                        type="button"
                        @click="openNotification(notification)"
                        class="mb-1 w-full rounded-xl border px-3 py-2 text-left transition hover:bg-slate-50"
                        :class="[
                            notification.is_read ? 'border-transparent bg-white' : 'border-blue-100 bg-blue-50/50',
                            freshIds.has(notification.id) ? 'ring-1 ring-[#0038A8]/30' : ''
                        ]"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm text-slate-900" :class="notification.is_read ? 'font-medium' : 'font-semibold'" x-text="notification.title"></p>
                                <p x-show="notification.message" class="mt-0.5 text-xs text-slate-600" x-text="notification.message"></p>
                                <p class="mt-1 text-[11px] text-slate-500" x-text="notification.relative_time"></p>
                            </div>
                            <span
                                x-show="!notification.is_read"
                                class="mt-1 inline-flex h-2.5 w-2.5 rounded-full bg-[#0038A8]"
                                aria-hidden="true"
                            ></span>
                        </div>
                    </button>
                </template>
            </div>
        </div>
    </div>

    <div x-data="{ open: false }" class="relative">
        <button @click="open = !open" class="inline-flex items-center gap-3 rounded-2xl border border-blue-100 bg-white px-4 py-2.5 text-left shadow-sm hover:bg-slate-50">
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#0038A8] text-xs font-bold text-white">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </span>
            <span class="hidden sm:block">
                <span class="block text-base font-semibold leading-tight text-slate-800">{{ auth()->user()->name }}</span>
                <span class="mt-1 inline-flex rounded-full bg-blue-50 px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-[#0038A8]">
                    {{ ucfirst(auth()->user()->role) }}
                </span>
            </span>
            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="m5 7 5 6 5-6"/>
            </svg>
        </button>

        <div x-show="open" x-transition @click.outside="open = false" class="absolute right-0 z-50 mt-2 w-48 rounded-xl border border-slate-200 bg-white p-2 shadow-lg" x-cloak>
            <a href="{{ route('profile.edit') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-blue-50">Profile Settings</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="mt-1 block w-full rounded-lg px-3 py-2 text-left text-sm text-[#CE1126] hover:bg-red-50">Logout</button>
            </form>
        </div>
    </div>
</div>

<script>
    function notificationCenter(config) {
        return {
            open: false,
            loading: false,
            notifications: config.items ?? [],
            unreadCount: Number(config.unread ?? 0),
            csrf: config.csrf,
            markReadUrlBase: config.markReadUrlBase,
            markAllUrl: config.markAllUrl,
            latestUrl: config.latestUrl,
            poller: null,
            freshIds: new Set(),
            initPolling() {
                this.pollLatest();
                this.poller = window.setInterval(() => {
                    if (document.visibilityState !== 'visible') {
                        return;
                    }
                    this.pollLatest();
                }, 5000);
                window.addEventListener('beforeunload', () => this.stopPolling(), { once: true });
            },
            stopPolling() {
                if (this.poller) {
                    window.clearInterval(this.poller);
                    this.poller = null;
                }
            },
            async pollLatest() {
                try {
                    const response = await fetch(this.latestUrl, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });
                    if (!response.ok) {
                        return;
                    }
                    const data = await response.json();
                    const incoming = Array.isArray(data.notifications) ? data.notifications : [];
                    const currentIds = new Set(this.notifications.map((item) => item.id));
                    const newIds = incoming
                        .filter((item) => !currentIds.has(item.id))
                        .map((item) => item.id);
                    this.notifications = incoming;
                    this.unreadCount = Number(data.unreadCount ?? 0);
                    this.freshIds = new Set(newIds);
                    if (newIds.length > 0) {
                        window.setTimeout(() => {
                            this.freshIds = new Set();
                        }, 1800);
                    }
                } catch (error) {
                    // Silent fail to keep polling non-blocking.
                }
            },
            async openNotification(notification) {
                const targetUrl = notification.link;
                if (!notification.is_read) {
                    try {
                        const response = await fetch(`${this.markReadUrlBase}/${notification.id}/read`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': this.csrf,
                            },
                            body: JSON.stringify({}),
                        });
                        if (response.ok) {
                            const data = await response.json();
                            notification.is_read = true;
                            this.unreadCount = Number(data.unreadCount ?? 0);
                        }
                    } catch (error) {
                        // Navigate even if request fails.
                    }
                }

                if (targetUrl) {
                    window.location.href = targetUrl;
                } else {
                    this.open = false;
                }
            },
            async markAllAsRead() {
                if (this.unreadCount === 0 || this.loading) {
                    return;
                }
                this.loading = true;
                try {
                    const response = await fetch(this.markAllUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': this.csrf,
                        },
                        body: JSON.stringify({}),
                    });
                    if (!response.ok) {
                        return;
                    }
                    const data = await response.json();
                    this.unreadCount = Number(data.unreadCount ?? 0);
                    this.notifications = this.notifications.map((item) => ({ ...item, is_read: true }));
                    this.freshIds = new Set();
                } finally {
                    this.loading = false;
                }
            },
        };
    }
</script>
