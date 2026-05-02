<?php

namespace App\Http\Controllers;

use App\Models\Concern;
use App\Models\Announcement;
use App\Models\DocumentRequest;
use App\Support\DocumentRequestCatalog;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function redirect(): RedirectResponse
    {
        $route = in_array(Auth::user()?->role, ['admin', 'staff'], true)
            ? 'admin.dashboard'
            : 'resident.dashboard';

        return redirect()->route($route);
    }

    public function resident(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $requestsQuery = $user->documentRequests();

        return view('resident.dashboard', [
            'stats' => [
                'total_requests' => (clone $requestsQuery)->count(),
                'pending_requests' => (clone $requestsQuery)->whereIn('status', ['pending', 'under_review'])->count(),
                'approved_requests' => (clone $requestsQuery)->whereIn('status', ['approved', 'ready_for_printing', 'ready_for_claiming'])->count(),
                'completed_requests' => (clone $requestsQuery)->where('status', 'completed')->count(),
            ],
            'recentRequests' => $user->documentRequests()->latest()->take(5)->get(),
            'documentTypeLabels' => DocumentRequestCatalog::allTypeLabels(),
            'latestAnnouncements' => Announcement::query()
                ->publishedForResidents()
                ->latest('published_at')
                ->take(3)
                ->get(),
        ]);
    }

    public function admin(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'total_residents' => User::query()->where('role', 'resident')->count(),
                'open_requests' => DocumentRequest::query()->whereIn('status', ['pending', 'under_review'], 'and', false)->count(),
                'open_concerns' => Concern::query()->whereIn('status', ['pending', 'reviewing'], 'and', false)->count(),
                'unread_notifications' => Notification::query()->where('is_read', '=', false)->count(),
                'published_announcements' => Announcement::query()->where('status', '=', 'published')->count(),
            ],
        ]);
    }
}
