<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBarangaySettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'barangay_name' => ['required', 'string', 'max:255'],
            'municipality' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'captain_name' => ['nullable', 'string', 'max:255'],
            'secretary_name' => ['nullable', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255'],
            'office_address' => ['nullable', 'string', 'max:2000'],
            'logo' => ['nullable', 'file', 'image', 'max:2048'],
            'remove_logo' => ['nullable', Rule::in(['1'])],
        ];
    }
}
