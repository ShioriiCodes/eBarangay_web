@php
    /** @var \App\Models\DocumentRequest $documentRequest */
    $raw = $documentRequest->request_data;
    $data = is_array($raw) ? $raw : [];

    $rows = [];
    foreach ($data as $key => $value) {
        if ($value === null || $value === '') {
            continue;
        }
        if ($key === 'category' && (string) $value === (string) $documentRequest->document_type) {
            continue;
        }
        if ($key === 'subtype' && filled($documentRequest->request_subtype) && (string) $value === (string) $documentRequest->request_subtype) {
            continue;
        }
        if ($key === 'purpose' && (string) $value === (string) $documentRequest->purpose) {
            continue;
        }
        if ($key === 'fields' && is_array($value) && $value === []) {
            continue;
        }
        $rows[$key] = $value;
    }
@endphp

@if ($data === [])
    <span class="text-slate-500">No additional structured data.</span>
@elseif ($rows === [])
    <span class="text-slate-500">Structured payload matches the request summary above.</span>
@else
    <dl class="space-y-4 text-sm">
        @foreach ($rows as $key => $value)
            @php
                $label = match ($key) {
                    'category' => 'Category (form payload)',
                    'subtype' => 'Form sub-type (payload)',
                    'purpose' => 'Purpose (form payload)',
                    'fields' => 'Dynamic form fields',
                    'additional_notes' => 'Additional notes',
                    'additional_details' => 'Additional details',
                    default => \Illuminate\Support\Str::headline(str_replace('_', ' ', (string) $key)),
                };
            @endphp

            @if ($key === 'fields' && is_array($value))
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $label }}</dt>
                    <dd class="mt-2">
                        <ul class="divide-y divide-slate-200 rounded-lg border border-slate-200 bg-white">
                            @foreach ($value as $fieldKey => $fieldVal)
                                <li class="flex flex-col gap-0.5 px-3 py-2.5 sm:flex-row sm:items-start sm:gap-4">
                                    <span class="shrink-0 text-xs font-medium uppercase tracking-wide text-slate-500 sm:w-44">
                                        {{ str_replace('_', ' ', (string) $fieldKey) }}
                                    </span>
                                    <span class="min-w-0 flex-1 text-slate-800">{{ is_scalar($fieldVal) || $fieldVal === null ? (string) $fieldVal : json_encode($fieldVal, JSON_UNESCAPED_UNICODE) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </dd>
                </div>
            @elseif (is_array($value))
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $label }}</dt>
                    <dd class="mt-1 rounded-lg border border-slate-200 bg-white px-3 py-2 font-mono text-xs text-slate-700">{{ json_encode($value, JSON_UNESCAPED_UNICODE) }}</dd>
                </div>
            @else
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $label }}</dt>
                    <dd class="mt-1 whitespace-pre-wrap text-slate-800">{{ $value }}</dd>
                </div>
            @endif
        @endforeach
    </dl>
@endif
