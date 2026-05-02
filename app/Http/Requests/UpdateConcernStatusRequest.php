<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConcernStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['admin', 'staff'], true);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'in:pending,reviewing,resolved,rejected'],
            'response' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $nextStatus = $this->input('status');
            $concern = $this->route('concern');
            $currentStatus = $concern?->status;

            $allowedTransitions = [
                'pending' => ['pending', 'reviewing', 'rejected'],
                'reviewing' => ['reviewing', 'resolved', 'rejected'],
                'resolved' => ['resolved'],
                'rejected' => ['rejected'],
            ];

            if ($currentStatus && isset($allowedTransitions[$currentStatus]) && ! in_array($nextStatus, $allowedTransitions[$currentStatus], true)) {
                $validator->errors()->add('status', 'Invalid concern status transition.');
            }

            if ($nextStatus === 'rejected' && blank($this->input('response'))) {
                $validator->errors()->add('response', 'Response/remarks are required when rejecting a concern.');
            }
        });
    }
}
