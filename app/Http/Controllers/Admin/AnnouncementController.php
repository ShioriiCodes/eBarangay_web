<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Announcement;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(Request $request): View
    {
        $query = Announcement::query()->with(['creator', 'updater']);

        if ($search = $request->string('q')->trim()->toString()) {
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('content', 'like', '%'.$search.'%');
            });
        }

        if ($status = $request->string('status')->trim()->toString()) {
            $query->where('status', $status);
        }

        if ($priority = $request->string('priority')->trim()->toString()) {
            $query->where('priority', $priority);
        }

        $announcements = $query->latest()->paginate(12)->withQueryString();

        return view('admin.announcements.index', [
            'announcements' => $announcements,
            'filters' => [
                'q' => $request->string('q')->toString(),
                'status' => $request->string('status')->toString(),
                'priority' => $request->string('priority')->toString(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'min:10'],
            'category' => ['nullable', 'string', 'max:100'],
            'priority' => ['required', 'in:normal,important,urgent'],
            'status' => ['required', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after:today'],
        ]);

        $announcement = DB::transaction(function () use ($request, $validated) {
            $publishedAt = $validated['published_at'] ?? null;
            if ($validated['status'] === 'published' && blank($publishedAt)) {
                $publishedAt = now();
            }

            $announcement = Announcement::query()->create([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'category' => $validated['category'] ?? null,
                'priority' => $validated['priority'],
                'status' => $validated['status'],
                'published_at' => $publishedAt,
                'expires_at' => $validated['expires_at'] ?? null,
                'created_by' => $request->user()->id,
                'updated_by' => $request->user()->id,
            ]);

            ActivityLog::create([
                'user_id' => $request->user()->id,
                'action' => 'announcement_created',
                'description' => "Created announcement: {$announcement->title}.",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            if ($announcement->status === 'published') {
                $this->notifyResidentsOfAnnouncement($announcement);
            }

            return $announcement;
        });

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement saved.');
    }

    public function edit(Announcement $announcement): View
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'min:10'],
            'category' => ['nullable', 'string', 'max:100'],
            'priority' => ['required', 'in:normal,important,urgent'],
            'status' => ['required', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after:today'],
        ]);

        $wasPublished = $announcement->status === 'published';
        $oldPriority = $announcement->priority;

        DB::transaction(function () use ($request, $announcement, $validated, $wasPublished, $oldPriority): void {
            $publishedAt = $validated['published_at'] ?? $announcement->published_at;
            if ($validated['status'] === 'published' && blank($publishedAt)) {
                $publishedAt = now();
            }

            $announcement->fill([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'category' => $validated['category'] ?? null,
                'priority' => $validated['priority'],
                'status' => $validated['status'],
                'published_at' => $publishedAt,
                'expires_at' => $validated['expires_at'] ?? null,
                'updated_by' => $request->user()->id,
            ]);
            $announcement->save();

            ActivityLog::create([
                'user_id' => $request->user()->id,
                'action' => 'announcement_updated',
                'description' => "Updated announcement: {$announcement->title}.",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $newlyPublished = ! $wasPublished && $announcement->status === 'published';
            $priorityEscalated = $announcement->status === 'published'
                && in_array($announcement->priority, ['important', 'urgent'], true)
                && $oldPriority === 'normal';

            if ($newlyPublished || $priorityEscalated) {
                $this->notifyResidentsOfAnnouncement($announcement);
            }
        });

        return redirect()
            ->route('admin.announcements.edit', $announcement)
            ->with('success', 'Announcement updated.');
    }

    public function archive(Request $request, Announcement $announcement): RedirectResponse
    {
        $announcement->update([
            'status' => 'archived',
            'updated_by' => $request->user()->id,
        ]);

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'announcement_archived',
            'description' => "Archived announcement: {$announcement->title}.",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement archived.');
    }

    public function destroy(Request $request, Announcement $announcement): RedirectResponse
    {
        abort_unless($request->user()?->role === 'admin', 403);

        $title = $announcement->title;
        Announcement::query()->whereKey($announcement->id)->delete();

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'announcement_deleted',
            'description' => "Deleted announcement: {$title}.",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement deleted.');
    }

    private function notifyResidentsOfAnnouncement(Announcement $announcement): void
    {
        User::query()
            ->where('role', 'resident')
            ->where('status', 'active')
            ->pluck('id')
            ->each(function (int $residentId) use ($announcement): void {
                Notification::create([
                    'user_id' => $residentId,
                    'title' => 'New Barangay Announcement',
                    'message' => $announcement->title,
                    'type' => 'announcement',
                    'related_type' => Announcement::class,
                    'related_id' => $announcement->id,
                ]);
            });
    }
}
