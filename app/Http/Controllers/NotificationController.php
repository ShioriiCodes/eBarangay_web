<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function latest(Request $request): JsonResponse
    {
        $notifications = Notification::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'notifications' => $notifications->map(fn (Notification $notification) => [
                'id' => $notification->id,
                'title' => $notification->title,
                'message' => $notification->message,
                'type' => $notification->type,
                'is_read' => (bool) $notification->is_read,
                'created_at' => optional($notification->created_at)?->toISOString(),
                'relative_time' => $notification->created_at?->diffForHumans() ?? '',
                'link' => $notification->link,
            ])->values(),
            'unreadCount' => $notifications->where('is_read', false)->count(),
        ]);
    }

    public function markAsRead(Notification $notification, Request $request): JsonResponse
    {
        if ((int) $notification->user_id !== (int) $request->user()->id) {
            return response()->json(['ok' => false, 'message' => 'Forbidden.'], 403);
        }

        if (! $notification->is_read) {
            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return response()->json([
            'ok' => true,
            'unreadCount' => Notification::query()
                ->where('user_id', $request->user()->id)
                ->where('is_read', false)
                ->count(),
        ]);
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        Notification::query()
            ->where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'ok' => true,
            'unreadCount' => 0,
        ]);
    }
}
