<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'residentProfile' => $request->user()->residentProfile,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $validated = $request->validated();

        if (in_array($user->role, ['admin', 'staff'], true)) {
            $nameTrim = Str::trim((string) $validated['name']);
            $split = $this->deriveSplitNamePartsFromFullName($nameTrim);

            $user->fill([
                'name' => $nameTrim,
                'first_name' => $split['first_name'],
                'middle_name' => $split['middle_name'],
                'last_name' => $split['last_name'],
                'suffix' => null,
                'email' => $validated['email'],
                'contact_number' => $validated['contact_number'] ?? null,
                'address' => $validated['address'] ?? null,
                'birthdate' => $validated['birthdate'] ?? null,
                'gender' => $validated['gender'] ?? null,
            ]);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

            $profilePayload = [
                'civil_status' => $validated['civil_status'] ?? null,
                'occupation' => $validated['occupation'] ?? null,
                'purok' => $validated['purok'] ?? null,
                'barangay' => $validated['barangay'] ?? 'Alfonso XIII',
                'municipality' => $validated['municipality'] ?? 'Quezon',
                'province' => $validated['province'] ?? 'Palawan',
            ];

            $hasProfileInput = collect($profilePayload)
                ->except(['barangay', 'municipality', 'province'])
                ->filter(fn ($value) => filled($value))
                ->isNotEmpty();

            if ($hasProfileInput || $user->residentProfile()->exists()) {
                $user->residentProfile()->updateOrCreate(
                    ['user_id' => $user->id],
                    array_merge([
                        'first_name' => $split['first_name'],
                        'middle_name' => $split['middle_name'],
                        'last_name' => $split['last_name'],
                        'suffix' => null,
                    ], array_filter($profilePayload, fn ($value) => ! is_null($value)))
                );
            }

            Auth::setUser($user->fresh());

            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'profile_updated',
                'description' => 'Updated own profile information.',
                'ip_address' => $request->ip(),
                'user_agent' => Str::limit((string) $request->userAgent(), 1000),
            ]);

            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        }

        $fn = Str::trim((string) ($validated['first_name'] ?? ''));
        $ln = Str::trim((string) ($validated['last_name'] ?? ''));
        $mn = Str::trim((string) ($validated['middle_name'] ?? ''));
        $sx = Str::trim((string) ($validated['suffix'] ?? ''));

        // Single-token display names (e.g. seeded "Admin"): avoid storing "Admin Admin" when first === last with no middle/suffix.
        if ($fn !== '' && $fn === $ln && $mn === '' && $sx === '') {
            $fullName = $fn;
        } else {
            $fullName = collect([$fn, $mn !== '' ? $mn : null, $ln, $sx !== '' ? $sx : null])
                ->filter(fn ($part) => filled($part))
                ->implode(' ');
        }

        $user->fill([
            'name' => $fullName !== '' ? $fullName : $user->name,
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'suffix' => $validated['suffix'] ?? null,
            'email' => $validated['email'],
            'contact_number' => $validated['contact_number'] ?? null,
            'address' => $validated['address'] ?? null,
            'birthdate' => $validated['birthdate'] ?? null,
            'gender' => $validated['gender'] ?? null,
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $profilePayload = [
            'civil_status' => $validated['civil_status'] ?? null,
            'occupation' => $validated['occupation'] ?? null,
            'purok' => $validated['purok'] ?? null,
            'barangay' => $validated['barangay'] ?? 'Alfonso XIII',
            'municipality' => $validated['municipality'] ?? 'Quezon',
            'province' => $validated['province'] ?? 'Palawan',
        ];

        $hasProfileInput = collect($profilePayload)
            ->except(['barangay', 'municipality', 'province'])
            ->filter(fn ($value) => filled($value))
            ->isNotEmpty();

        if ($hasProfileInput || $user->residentProfile()->exists()) {
            $user->residentProfile()->updateOrCreate(
                ['user_id' => $user->id],
                array_merge([
                    'first_name' => $validated['first_name'],
                    'middle_name' => $validated['middle_name'] ?? null,
                    'last_name' => $validated['last_name'],
                    'suffix' => $validated['suffix'] ?? null,
                ], array_filter($profilePayload, fn ($value) => ! is_null($value)))
            );
        }

        Auth::setUser($user->fresh());

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'profile_updated',
            'description' => 'Updated own profile information.',
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 1000),
        ]);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        /** @var User $user */
        $user = $request->user();

        Auth::logout();

        User::query()->whereKey($user->id)->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * @return array{first_name: string, middle_name: ?string, last_name: string}
     */
    private function deriveSplitNamePartsFromFullName(string $fullName): array
    {
        $trimmed = Str::trim($fullName);
        if ($trimmed === '') {
            return ['first_name' => '', 'middle_name' => null, 'last_name' => ''];
        }

        $parts = preg_split('/\s+/', $trimmed) ?: [];
        $firstName = (string) ($parts[0] ?? $trimmed);
        $lastName = count($parts) > 1 ? (string) $parts[count($parts) - 1] : $firstName;
        $middleName = count($parts) > 2 ? implode(' ', array_slice($parts, 1, -1)) : null;

        return [
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName,
        ];
    }
}
