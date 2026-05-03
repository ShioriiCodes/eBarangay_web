<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentRequestStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['admin', 'staff'], true);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'in:pending,under_review,approved,ready_for_printing,ready_for_claiming,completed,rejected',
            ],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            if ($this->input('status') === 'rejected' && blank($this->input('remarks'))) {
                $validator->errors()->add('remarks', 'Remarks are required when rejecting a request.');
            }
        });
    }
}
