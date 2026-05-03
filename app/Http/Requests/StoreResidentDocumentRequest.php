<?php

namespace App\Http\Requests;

use App\Support\DocumentRequestCatalog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreResidentDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === 'resident';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'document_type' => ['required', 'string', 'max:100', Rule::in(DocumentRequestCatalog::allowedDocumentTypeKeys())],
            'request_subtype' => ['nullable', 'string', 'max:120'],
            'purpose' => ['required', 'string', 'max:500'],
            'additional_details' => ['nullable', 'string', 'max:1000'],
            'form_fields' => ['nullable', 'array'],
            'form_fields.*' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $type = (string) $this->input('document_type');
            $rawSubtype = $this->input('request_subtype');
            $rawSubtype = is_string($rawSubtype) ? $rawSubtype : null;

            if (! DocumentRequestCatalog::isValidSubtype($type, $rawSubtype)) {
                $validator->errors()->add('request_subtype', 'Select a valid form sub-type.');

                return;
            }

            $resolved = DocumentRequestCatalog::canonicalSubtype($type, $rawSubtype);

            $formFields = $this->input('form_fields');
            if (! is_array($formFields)) {
                $formFields = [];
            }

            foreach (DocumentRequestCatalog::fieldsFor($type, $resolved) as $field) {
                $val = $formFields[$field['name']] ?? null;
                if ($field['required'] && ($val === null || $val === '')) {
                    $validator->errors()->add('form_fields.'.$field['name'], "The {$field['label']} field is required.");

                    continue;
                }
                if ($val === null || $val === '') {
                    continue;
                }
                if (! is_string($val)) {
                    $validator->errors()->add('form_fields.'.$field['name'], "The {$field['label']} field must be text.");

                    continue;
                }
                if (mb_strlen($val) > $field['max']) {
                    $validator->errors()->add('form_fields.'.$field['name'], "The {$field['label']} may not exceed {$field['max']} characters.");
                }
            }
        });
    }
}
