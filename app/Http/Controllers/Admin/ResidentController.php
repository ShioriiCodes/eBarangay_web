<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResidentController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()
            ->where('role', 'resident')
            ->with('residentProfile');

        if ($search = $request->string('q')->trim()->toString()) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('contact_number', 'like', '%'.$search.'%');
            });
        }

        if ($status = $request->string('status')->trim()->toString()) {
            $query->where('status', $status);
        }

        $residents = $query->latest()->paginate(12)->withQueryString();

        return view('admin.residents.index', [
            'residents' => $residents,
            'filters' => [
                'q' => $request->string('q')->toString(),
                'status' => $request->string('status')->toString(),
            ],
        ]);
    }

    public function show(User $resident): View
    {
        abort_if($resident->role !== 'resident', 404);

        $resident->load('residentProfile');

        return view('admin.residents.show', compact('resident'));
    }

    public function update(Request $request, User $resident): RedirectResponse
    {
        abort_if($resident->role !== 'resident', 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:25'],
            'address' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive,pending'],
        ]);

        $resident->update($validated);

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'resident_profile_updated_by_staff',
            'description' => "Updated resident record {$resident->email}.",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Resident profile updated.');
    }

    public function deactivate(Request $request, User $resident): RedirectResponse
    {
        abort_if($resident->role !== 'resident', 404);

        $resident->update(['status' => 'inactive']);

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'resident_account_deactivated',
            'description' => "Deactivated resident account {$resident->email}.",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Resident account set to inactive.');
    }
}
