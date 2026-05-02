<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = Notification::query()
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('admin.notifications.index', compact('notifications'));
    }
}
