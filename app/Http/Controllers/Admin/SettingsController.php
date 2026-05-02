<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateBarangaySettingsRequest;
use App\Models\ActivityLog;
use App\Models\BarangaySetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.index', [
            'setting' => BarangaySetting::current(),
        ]);
    }

    public function update(UpdateBarangaySettingsRequest $request): RedirectResponse
    {
        $setting = BarangaySetting::current();
        $validated = $request->validated();

        $removeLogo = ($validated['remove_logo'] ?? null) === '1';
        unset($validated['remove_logo'], $validated['logo']);

        if ($removeLogo && $setting->logo_path) {
            Storage::disk('public')->delete($setting->logo_path);
            $validated['logo_path'] = null;
        }

        if ($request->hasFile('logo')) {
            if ($setting->logo_path) {
                Storage::disk('public')->delete($setting->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('barangay', 'public');
        }

        $setting->fill($validated);
        $setting->save();

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'barangay_settings_updated',
            'description' => 'Updated barangay profile / system settings.',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()
            ->route('admin.settings')
            ->with('success', 'Settings saved.');
    }
}
