<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateConcernStatusRequest;
use App\Models\ActivityLog;
use App\Models\Concern;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConcernController extends Controller
{
    public function index(Request $request): View
    {
        $query = Concern::query()->with('user');

        if ($search = $request->string('q')->trim()->toString()) {
            $query->where(function ($q) use ($search): void {
                $q->where('concern_number', 'like', '%'.$search.'%')
                    ->orWhere('subject', 'like', '%'.$search.'%')
                    ->orWhereHas('user', function ($uq) use ($search): void {
                        $uq->where('name', 'like', '%'.$search.'%');
                    });
            });
        }

        if ($status = $request->string('status')->trim()->toString()) {
            $query->where('status', $status);
        }

        $concerns = $query->latest()->paginate(10)->withQueryString();

        return view('admin.concerns.index', [
            'concerns' => $concerns,
            'filters' => [
                'q' => $request->string('q')->toString(),
                'status' => $request->string('status')->toString(),
            ],
        ]);
    }

    public function show(Concern $concern): View
    {
        $concern->load(['user', 'handledBy']);

        return view('admin.concerns.show', compact('concern'));
    }

    public function updateStatus(UpdateConcernStatusRequest $request, Concern $concern): RedirectResponse
    {
        $validated = $request->validated();

        $concern->update([
            'status' => $validated['status'],
            'response' => $validated['response'] ?? $concern->response,
            'handled_by' => $request->user()->id,
            'handled_at' => in_array($validated['status'], ['resolved', 'rejected'], true) ? now() : null,
        ]);

        Notification::create([
            'user_id' => $concern->user_id,
            'title' => 'Concern status updated',
            'message' => $this->buildConcernStatusMessage($concern->concern_number, $concern->status),
            'type' => 'concern_status',
            'related_type' => Concern::class,
            'related_id' => $concern->id,
        ]);

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'concern_status_updated',
            'description' => "Updated concern {$concern->concern_number} to {$concern->status}.",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.concerns.show', $concern)->with('success', 'Concern updated successfully.');
    }

    private function buildConcernStatusMessage(string $concernNumber, string $status): string
    {
        return match ($status) {
            'reviewing' => "Your concern {$concernNumber} is now under review.",
            'resolved' => "Your concern {$concernNumber} has been resolved.",
            'rejected' => "Your concern {$concernNumber} was reviewed and marked as rejected.",
            default => "Your concern {$concernNumber} is now: {$status}.",
        };
    }
}
