<?php

namespace App\Http\Controllers;

use App\Models\BarangaySetting;
use App\Models\DocumentRequest;
use App\Support\CertificatePresenter;
use App\Support\DocumentRequestCatalog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentPrintController extends Controller
{
    public function preview(DocumentRequest $documentRequest): Response
    {
        $this->authorizeDocument($documentRequest);

        if ($documentRequest->status !== 'approved') {
            abort(403, 'Document preview is only available after approval.');
        }

        $html = $this->renderCertificateHtml($documentRequest);

        return response($html, 200)->header('Content-Type', 'text/html; charset=UTF-8');
    }

    public function downloadPdf(DocumentRequest $documentRequest): Response
    {
        $this->authorizeDocument($documentRequest);

        if (! in_array($documentRequest->status, ['approved', 'ready_for_printing', 'ready_for_claiming', 'completed'], true)) {
            abort(403, 'Document download is only available for approved requests.');
        }

        $html = $this->renderCertificateHtml($documentRequest);
        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait');

        $slugParts = [$documentRequest->request_number, $documentRequest->document_type];
        if (filled($documentRequest->request_subtype)) {
            $slugParts[] = $documentRequest->request_subtype;
        }
        $filename = Str::slug(implode('-', $slugParts)).'.pdf';

        return $pdf->download($filename);
    }

    private function authorizeDocument(DocumentRequest $documentRequest): void
    {
        $user = request()->user();
        if ($user === null || ! in_array($user->role, ['admin', 'staff'], true)) {
            abort(403, 'Only barangay staff may generate official documents.');
        }
    }

    private function renderCertificateHtml(DocumentRequest $documentRequest): string
    {
        $view = DocumentRequestCatalog::resolvePrintView($documentRequest);

        $documentRequest->loadMissing(['user.residentProfile']);

        $settings = BarangaySetting::current();
        $resident = $documentRequest->user;
        $presenter = new CertificatePresenter($resident, $resident->residentProfile);
        $logoDataUri = $this->logoDataUri($settings->logo_path);

        return view($view, [
            'settings' => $settings,
            'request' => $documentRequest,
            'resident' => $resident,
            'presenter' => $presenter,
            'logoDataUri' => $logoDataUri,
        ])->render();
    }

    private function logoDataUri(?string $relativePath): ?string
    {
        if ($relativePath === null || $relativePath === '' || ! Storage::disk('public')->exists($relativePath)) {
            return null;
        }

        $binary = Storage::disk('public')->get($relativePath);
        $ext = Str::lower(pathinfo($relativePath, PATHINFO_EXTENSION));
        $mime = match ($ext) {
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            default => 'application/octet-stream',
        };

        return 'data:'.$mime.';base64,'.base64_encode($binary);
    }
}
