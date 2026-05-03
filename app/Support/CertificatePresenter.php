<?php

namespace App\Support;

use App\Models\ResidentProfile;
use App\Models\User;
use Illuminate\Support\Str;

class CertificatePresenter
{
    public function __construct(
        public User $user,
        public ?ResidentProfile $profile = null,
    ) {
        $this->profile = $profile ?? $user->residentProfile;
    }

    public function fullName(): string
    {
        if ($this->profile !== null) {
            $composed = Str::trim(collect([
                $this->profile->first_name,
                $this->profile->middle_name,
                $this->profile->last_name,
                $this->profile->suffix,
            ])->filter()->implode(' '));

            if ($composed !== '') {
                return $composed;
            }
        }

        return Str::trim((string) $this->user->name);
    }

    public function streetAddress(): string
    {
        return Str::trim((string) ($this->user->address ?? ''));
    }

    public function purok(): ?string
    {
        $p = $this->profile?->purok;

        return filled($p) ? (string) $p : null;
    }

    public function civilStatus(): ?string
    {
        $v = $this->profile?->civil_status;

        return filled($v) ? (string) $v : null;
    }

    public function gender(): ?string
    {
        $g = $this->user->gender;

        return filled($g) ? ucfirst((string) $g) : null;
    }

    public function birthdateFormatted(): ?string
    {
        return $this->user->birthdate?->timezone(config('app.timezone'))->format('F j, Y');
    }

    /**
     * Resident locality line (purok / barangay / municipality / province from profile when present).
     */
    public function localityLine(): string
    {
        if ($this->profile === null) {
            return $this->streetAddress();
        }

        $segments = array_filter([
            $this->profile->purok ? 'Purok '.$this->profile->purok : null,
            $this->profile->barangay,
            $this->profile->municipality,
            $this->profile->province,
        ], fn ($v) => filled($v));

        $joined = $segments !== [] ? implode(', ', $segments) : '';

        return $joined !== '' ? $joined : $this->streetAddress();
    }
}
