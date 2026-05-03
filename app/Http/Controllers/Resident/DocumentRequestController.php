<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreResidentDocumentRequest;
use App\Models\ActivityLog;
use App\Support\DocumentRequestCatalog;
use App\Models\DocumentRequest;
use App\Models\DocumentStatusHistory;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DocumentRequestController extends Controller
{
    public function create(): View
    {
        return view('resident.requests.create', [
            'formCatalog' => DocumentRequestCatalog::forFrontend(old()),
        ]);
    }

    public function store(StoreResidentDocumentRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $formFields = $validated['form_fields'] ?? [];
        if (! is_array($formFields)) {
            $formFields = [];
        }

        $subtype = DocumentRequestCatalog::canonicalSubtype(
            $validated['document_type'],
            isset($validated['request_subtype']) && is_string($validated['request_subtype']) ? $validated['request_subtype'] : null
        );

        $requestData = [
            'category' => $validated['document_type'],
            'purpose' => $validated['purpose'],
        ];
        if ($subtype !== null) {
            $requestData['subtype'] = $subtype;
        }
        if ($formFields !== []) {
            $requestData['fields'] = $formFields;
        }
        $notes = isset($validated['additional_details']) ? trim((string) $validated['additional_details']) : '';
        if ($notes !== '') {
            $requestData['additional_notes'] = $notes;
        }

        $documentRequest = $request->user()->documentRequests()->create([
            'request_number' => $this->generateRequestNumber(),
            'document_type' => $validated['document_type'],
            'request_subtype' => $subtype,
            'purpose' => $validated['purpose'],
            'request_data' => $requestData,
            'status' => 'pending',
        ]);

        DocumentStatusHistory::create([
            'document_request_id' => $documentRequest->id,
            'changed_by' => $request->user()->id,
            'old_status' => null,
            'new_status' => 'pending',
            'remarks' => 'Request submitted by resident.',
        ]);

        Notification::create([
            'user_id' => $request->user()->id,
            'title' => 'Document Request Submitted',
            'message' => "Your request {$documentRequest->request_number} has been submitted and is now pending review.",
            'type' => 'document_request',
            'related_type' => DocumentRequest::class,
            'related_id' => $documentRequest->id,
        ]);

        User::query()
            ->where(function ($query): void {
                $query->where('role', 'admin')
                    ->orWhere('role', 'staff');
            })
            ->where('status', 'active')
            ->pluck('id')
            ->each(function (int $adminOrStaffId) use ($documentRequest): void {
                Notification::create([
                    'user_id' => $adminOrStaffId,
                    'title' => 'New document request submitted',
                    'message' => "Request {$documentRequest->request_number} is waiting for review.",
                    'type' => 'admin_document_request_alert',
                    'related_type' => DocumentRequest::class,
                    'related_id' => $documentRequest->id,
                ]);
            });

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'document_request_submitted',
            'description' => "Submitted {$documentRequest->document_type} request ({$documentRequest->request_number}).",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('resident.requests.index')->with('success', 'Document request submitted successfully.');
    }

    public function index(): View
    {
        $requests = DocumentRequest::query()
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('resident.requests.index', [
            'requests' => $requests,
            'documentTypeLabels' => DocumentRequestCatalog::allTypeLabels(),
        ]);
    }

    public function show(DocumentRequest $documentRequest): View
    {
        abort_if($documentRequest->user_id !== Auth::id(), 403);

        $documentRequest->load(['statusHistories.changedBy']);

        return view('resident.requests.show', [
            'documentRequest' => $documentRequest,
            'documentTypeLabel' => DocumentRequestCatalog::labelFor($documentRequest->document_type),
            'requestSubtypeLabel' => DocumentRequestCatalog::subtypeLabelFor(
                $documentRequest->document_type,
                $documentRequest->request_subtype
            ),
            'statusFlow' => $documentRequest->status === 'rejected'
                ? ['pending', 'under_review', 'rejected']
                : ['pending', 'under_review', 'approved', 'ready_for_printing', 'ready_for_claiming', 'completed'],
        ]);
    }

    private function generateRequestNumber(): string
    {
        $today = Carbon::today();
        $todayCode = $today->format('Ymd');

        $sequence = DocumentRequest::query()
            ->whereDate('created_at', '=', $today, 'and')
            ->count() + 1;

        return sprintf('EBRGY-%s-%04d', $todayCode, $sequence);
    }
}
