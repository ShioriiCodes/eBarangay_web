<?php

use App\Http\Controllers\Admin\ConcernController as AdminConcernController;
use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Admin\DocumentRequestController as AdminDocumentRequestController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\ResidentController as AdminResidentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentPrintController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Resident\ConcernController as ResidentConcernController;
use App\Http\Controllers\Resident\AnnouncementController as ResidentAnnouncementController;
use App\Http\Controllers\Resident\DocumentRequestController as ResidentDocumentRequestController;
use App\Http\Controllers\Resident\NotificationController as ResidentNotificationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('landing');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'redirect'])->name('dashboard');
    Route::get('/notifications/latest', [NotificationController::class, 'latest'])->name('notifications.latest');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('resident')->name('resident.')->middleware('resident')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'resident'])->name('dashboard');
        Route::get('/request-document', [ResidentDocumentRequestController::class, 'create'])->name('requests.create');
        Route::post('/request-document', [ResidentDocumentRequestController::class, 'store'])->name('requests.store');
        Route::get('/my-requests', [ResidentDocumentRequestController::class, 'index'])->name('requests.index');
        Route::get('/my-requests/{documentRequest}', [ResidentDocumentRequestController::class, 'show'])->name('requests.show');
        Route::get('/notifications', [ResidentNotificationController::class, 'index'])->name('notifications.index');
        Route::patch('/notifications/{notification}/read', [ResidentNotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::get('/my-concerns', [ResidentConcernController::class, 'index'])->name('concerns.index');
        Route::get('/submit-concern', [ResidentConcernController::class, 'create'])->name('concerns.create');
        Route::post('/submit-concern', [ResidentConcernController::class, 'store'])->name('concerns.store');
        Route::get('/my-concerns/{concern}', [ResidentConcernController::class, 'show'])->name('concerns.show');
        Route::get('/concerns', [ResidentConcernController::class, 'index']);
        Route::get('/concerns/create', [ResidentConcernController::class, 'create']);
        Route::post('/concerns', [ResidentConcernController::class, 'store']);
        Route::get('/concerns/{concern}', [ResidentConcernController::class, 'show']);
        Route::get('/announcements', [ResidentAnnouncementController::class, 'index'])->name('announcements.index');
        Route::get('/announcements/{announcement}', [ResidentAnnouncementController::class, 'show'])->name('announcements.show');
        Route::view('/profile', 'resident.profile')->name('profile');
    });

    Route::prefix('admin')->name('admin.')->middleware('admin_or_staff')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        Route::get('/document-requests', [AdminDocumentRequestController::class, 'index'])->name('requests.index');
        Route::get('/document-requests/{documentRequest}', [AdminDocumentRequestController::class, 'show'])->name('requests.show');
        Route::patch('/document-requests/{documentRequest}/status', [AdminDocumentRequestController::class, 'updateStatus'])->name('requests.status');
        Route::patch('/document-requests/{documentRequest}/remarks', [AdminDocumentRequestController::class, 'updateRemarks'])->name('requests.remarks');
        Route::get('/resident-records', [AdminResidentController::class, 'index'])->name('resident-records');
        Route::get('/resident-records/{resident}', [AdminResidentController::class, 'show'])->name('resident-records.show');
        Route::patch('/resident-records/{resident}', [AdminResidentController::class, 'update'])->name('resident-records.update');
        Route::patch('/resident-records/{resident}/deactivate', [AdminResidentController::class, 'deactivate'])->name('resident-records.deactivate');
        Route::get('/concerns', [AdminConcernController::class, 'index'])->name('concerns.index');
        Route::get('/concerns/{concern}', [AdminConcernController::class, 'show'])->name('concerns.show');
        Route::patch('/concerns/{concern}/status', [AdminConcernController::class, 'updateStatus'])->name('concerns.status');
        Route::patch('/concerns/{concern}', [AdminConcernController::class, 'updateStatus']);
        Route::get('/announcements', [AdminAnnouncementController::class, 'index'])->name('announcements.index');
        Route::get('/announcements/create', [AdminAnnouncementController::class, 'create'])->name('announcements.create');
        Route::post('/announcements', [AdminAnnouncementController::class, 'store'])->name('announcements.store');
        Route::get('/announcements/{announcement}/edit', [AdminAnnouncementController::class, 'edit'])->name('announcements.edit');
        Route::patch('/announcements/{announcement}', [AdminAnnouncementController::class, 'update'])->name('announcements.update');
        Route::patch('/announcements/{announcement}/archive', [AdminAnnouncementController::class, 'archive'])->name('announcements.archive');
        Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export/pdf', [AdminReportController::class, 'exportPdfPlaceholder'])->name('reports.export.pdf');
        Route::get('/reports/export/excel', [AdminReportController::class, 'exportExcelPlaceholder'])->name('reports.export.excel');
    });

    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/settings', [AdminSettingsController::class, 'edit'])->name('settings');
        Route::patch('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');
        Route::delete('/announcements/{announcement}', [AdminAnnouncementController::class, 'destroy'])->name('announcements.destroy');
    });

    Route::prefix('documents')->name('documents.')->middleware('admin_or_staff')->group(function () {
        // Official printable templates and PDF generation are planned for a later release.
        Route::get('/{documentRequest}/preview', [DocumentPrintController::class, 'preview'])->name('preview');
        Route::get('/{documentRequest}/download-pdf', [DocumentPrintController::class, 'downloadPdf'])->name('download-pdf');
    });
});

require __DIR__.'/auth.php';
