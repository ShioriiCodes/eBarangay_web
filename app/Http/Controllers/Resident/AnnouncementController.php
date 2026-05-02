<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(Request $request): View
    {
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = Announcement::query()->publishedForResidents();

        if ($search = $request->string('q')->trim()->toString()) {
            $query->where('title', 'like', '%'.$search.'%');
        }

        if ($category = $request->string('category')->trim()->toString()) {
            $query->where('category', $category);
        }

        if ($priority = $request->string('priority')->trim()->toString()) {
            $query->where('priority', $priority);
        }

        $featured = (clone $query)
            ->whereIn('priority', ['urgent', 'important'])
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        $announcements = $query
            ->orderBy('published_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        $categories = Announcement::query()
            ->publishedForResidents()
            ->whereNotNull('category', 'and')
            ->select('category')
            ->distinct()
            ->orderBy('category', 'asc')
            ->pluck('category');

        return view('resident.announcements.index', [
            'featured' => $featured,
            'announcements' => $announcements,
            'categories' => $categories,
            'filters' => [
                'q' => $request->string('q')->toString(),
                'category' => $request->string('category')->toString(),
                'priority' => $request->string('priority')->toString(),
            ],
        ]);
    }

    public function show(Announcement $announcement): View
    {
        abort_unless($announcement->status === 'published', 404);
        abort_if($announcement->published_at && $announcement->published_at->isFuture(), 404);
        abort_if($announcement->expires_at && $announcement->expires_at->isPast(), 404);

        return view('resident.announcements.show', compact('announcement'));
    }
}
