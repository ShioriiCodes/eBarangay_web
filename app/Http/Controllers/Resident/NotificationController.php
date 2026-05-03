<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = Notification::query()
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('resident.notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification): RedirectResponse
    {
        abort_if($notification->user_id !== auth()->id(), 403);

        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return back()->with('success', 'Notification marked as read.');
    }
}
