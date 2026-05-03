<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConcernRequest;
use App\Models\ActivityLog;
use App\Models\Concern;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ConcernController extends Controller
{
    public function create(): View
    {
        return view('resident.concerns.create');
    }

    public function index(): View
    {
        $concerns = Concern::query()
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('resident.concerns.index', compact('concerns'));
    }

    public function show(Concern $concern): View
    {
        abort_if($concern->user_id !== Auth::id(), 403);

        return view('resident.concerns.show', compact('concern'));
    }

    public function store(StoreConcernRequest $request): RedirectResponse
    {
        $concern = $request->user()->concerns()->create([
            'concern_number' => $this->generateConcernNumber(),
            'subject' => $request->validated('subject'),
            'message' => $request->validated('message'),
            'status' => 'pending',
        ]);

        Notification::create([
            'user_id' => $request->user()->id,
            'title' => 'Concern Submitted',
            'message' => "Your concern {$concern->concern_number} has been submitted and is now pending review.",
            'type' => 'concern_submission',
            'related_type' => Concern::class,
            'related_id' => $concern->id,
        ]);

        User::query()
            ->where(function ($query): void {
                $query->where('role', 'admin')
                    ->orWhere('role', 'staff');
            })
            ->where('status', 'active')
            ->pluck('id')
            ->each(function (int $adminOrStaffId) use ($concern): void {
                Notification::create([
                    'user_id' => $adminOrStaffId,
                    'title' => 'New concern submitted',
                    'message' => "Concern {$concern->concern_number} needs attention.",
                    'type' => 'admin_concern_alert',
                    'related_type' => Concern::class,
                    'related_id' => $concern->id,
                ]);
            });

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'concern_submitted',
            'description' => "Submitted concern {$concern->concern_number}.",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('resident.concerns.index')->with('success', 'Concern submitted successfully.');
    }

    private function generateConcernNumber(): string
    {
        $today = Carbon::today();
        $todayCode = $today->format('Ymd');

        $sequence = Concern::query()
            ->whereDate('created_at', '=', $today, 'and')
            ->count() + 1;

        return sprintf('CONCERN-%s-%04d', $todayCode, $sequence);
    }
}
