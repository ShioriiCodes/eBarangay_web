<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateDocumentRequestRemarksRequest;
use App\Http\Requests\UpdateDocumentRequestStatusRequest;
use App\Models\ActivityLog;
use App\Models\DocumentRequest;
use App\Models\DocumentStatusHistory;
use App\Support\DocumentRequestCatalog;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DocumentRequestController extends Controller
{
    public function index(Request $request): View
    {
        $query = DocumentRequest::query()->with('user');

        if ($search = $request->string('q')->trim()->toString()) {
            $query->where(function ($q) use ($search): void {
                $q->where('request_number', 'like', '%'.$search.'%')
                    ->orWhereHas('user', function ($uq) use ($search): void {
                        $uq->where('name', 'like', '%'.$search.'%');
                    });
            });
        }

        if ($status = $request->string('status')->trim()->toString()) {
            $query->where('status', $status);
        }

        if ($type = $request->string('document_type')->trim()->toString()) {
            $query->where('document_type', $type);
        }

        $requests = $query->latest()->paginate(12)->withQueryString();

        return view('admin.requests.index', [
            'requests' => $requests,
            'documentTypeLabels' => DocumentRequestCatalog::allTypeLabels(),
            'filters' => [
                'q' => $request->string('q')->toString(),
                'status' => $request->string('status')->toString(),
                'document_type' => $request->string('document_type')->toString(),
            ],
        ]);
    }

    public function show(DocumentRequest $documentRequest): View
    {
        $documentRequest->load(['user', 'reviewedBy', 'statusHistories.changedBy']);

        return view('admin.requests.show', [
            'documentRequest' => $documentRequest,
            'documentTypeLabel' => DocumentRequestCatalog::labelFor($documentRequest->document_type),
            'requestSubtypeLabel' => DocumentRequestCatalog::subtypeLabelFor(
                $documentRequest->document_type,
                $documentRequest->request_subtype
            ),
            'statusFlow' => [
                'pending',
                'under_review',
                'approved',
                'ready_for_printing',
                'ready_for_claiming',
                'completed',
                'rejected',
            ],
        ]);
    }

    public function updateStatus(UpdateDocumentRequestStatusRequest $request, DocumentRequest $documentRequest): RedirectResponse
    {
        $validated = $request->validated();
        $newStatus = $validated['status'];
        $remarks = $validated['remarks'] ?? null;

        DB::transaction(function () use ($request, $documentRequest, $newStatus, $remarks): void {
            $oldStatus = $documentRequest->status;

            if ($oldStatus === $newStatus) {
                throw ValidationException::withMessages([
                    'status' => 'Select a different status to update this request.',
                ]);
            }

            $this->assertAllowedTransition($oldStatus, $newStatus);

            $documentRequest->status = $newStatus;
            if (! is_null($remarks) && $remarks !== '') {
                $documentRequest->remarks = $remarks;
            }

            if ($newStatus === 'under_review' && ! $documentRequest->reviewed_at) {
                $documentRequest->reviewed_by = $request->user()->id;
                $documentRequest->reviewed_at = now();
            }

            if (in_array($newStatus, ['under_review', 'approved', 'ready_for_printing', 'ready_for_claiming', 'completed', 'rejected'], true)) {
                $documentRequest->reviewed_by = $request->user()->id;
                if (! $documentRequest->reviewed_at) {
                    $documentRequest->reviewed_at = now();
                }
            }

            if ($newStatus === 'approved' && $oldStatus !== 'approved') {
                $documentRequest->approved_at = now();
            }

            if ($newStatus === 'completed') {
                $documentRequest->completed_at = now();
            } elseif ($oldStatus === 'completed' && $newStatus !== 'completed') {
                $documentRequest->completed_at = null;
            }

            if ($newStatus === 'rejected') {
                $documentRequest->completed_at = null;
            }

            $documentRequest->save();

            DocumentStatusHistory::create([
                'document_request_id' => $documentRequest->id,
                'changed_by' => $request->user()->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'remarks' => $remarks,
            ]);

            Notification::create([
                'user_id' => $documentRequest->user_id,
                'title' => 'Document request updated',
                'message' => $this->buildResidentStatusMessage($documentRequest->request_number, $newStatus),
                'type' => 'document_request_status',
                'related_type' => DocumentRequest::class,
                'related_id' => $documentRequest->id,
            ]);

            ActivityLog::create([
                'user_id' => $request->user()->id,
                'action' => 'document_request_status_updated',
                'description' => "Updated request {$documentRequest->request_number} from {$oldStatus} to {$newStatus}.",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        });

        return redirect()
            ->route('admin.requests.show', $documentRequest)
            ->with('success', 'Request status updated.');
    }

    public function updateRemarks(UpdateDocumentRequestRemarksRequest $request, DocumentRequest $documentRequest): RedirectResponse
    {
        $remarks = $request->validated('remarks');

        DB::transaction(function () use ($request, $documentRequest, $remarks): void {
            $currentStatus = $documentRequest->status;

            $documentRequest->remarks = $remarks;
            $documentRequest->reviewed_by = $request->user()->id;
            if (! $documentRequest->reviewed_at) {
                $documentRequest->reviewed_at = now();
            }
            $documentRequest->save();

            DocumentStatusHistory::create([
                'document_request_id' => $documentRequest->id,
                'changed_by' => $request->user()->id,
                'old_status' => $currentStatus,
                'new_status' => $currentStatus,
                'remarks' => 'Remarks updated: '.$remarks,
            ]);

            Notification::create([
                'user_id' => $documentRequest->user_id,
                'title' => 'Document request remarks updated',
                'message' => "Official remarks were updated for request {$documentRequest->request_number}.",
                'type' => 'document_request_remarks',
                'related_type' => DocumentRequest::class,
                'related_id' => $documentRequest->id,
            ]);

            ActivityLog::create([
                'user_id' => $request->user()->id,
                'action' => 'document_request_remarks_updated',
                'description' => "Updated remarks for request {$documentRequest->request_number}.",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        });

        return redirect()
            ->route('admin.requests.show', $documentRequest)
            ->with('success', 'Remarks saved.');
    }

    private function assertAllowedTransition(string $from, string $to): void
    {
        $order = [
            'pending' => 0,
            'under_review' => 1,
            'approved' => 2,
            'ready_for_printing' => 3,
            'ready_for_claiming' => 4,
            'completed' => 5,
        ];

        if ($to === 'rejected') {
            if ($from === 'completed') {
                throw ValidationException::withMessages([
                    'status' => 'Completed requests cannot be rejected.',
                ]);
            }
            return;
        }

        if ($from === 'rejected') {
            throw ValidationException::withMessages([
                'status' => 'Rejected requests cannot be moved forward unless recreated as a new request.',
            ]);
        }

        if (! array_key_exists($from, $order) || ! array_key_exists($to, $order)) {
            throw ValidationException::withMessages([
                'status' => 'Invalid status transition.',
            ]);
        }

        if ($order[$to] < $order[$from]) {
            throw ValidationException::withMessages([
                'status' => 'You cannot move this request backwards in the workflow.',
            ]);
        }

        if ($order[$to] - $order[$from] !== 1) {
            throw ValidationException::withMessages([
                'status' => 'Status updates must follow the next step in the workflow.',
            ]);
        }
    }

    private function buildResidentStatusMessage(string $requestNumber, string $newStatus): string
    {
        return match ($newStatus) {
            'ready_for_printing' => "Your request {$requestNumber} is ready for printing.",
            'ready_for_claiming' => "Your document for request {$requestNumber} is ready for claiming.",
            'completed' => "Your document request {$requestNumber} has been completed.",
            default => "Your request {$requestNumber} is now: ".str_replace('_', ' ', $newStatus).'.',
        };
    }
}
