@php
    $data = $request->request_data ?? [];
    $fields = is_array($data['fields'] ?? null) ? $data['fields'] : [];
@endphp
@if (filled($request->purpose))
    <p class="body-text"><strong>Purpose:</strong> {{ $request->purpose }}</p>
@endif
<p class="body-text">
    This output is a <strong>temporary placeholder</strong>. The official barangay inquiry / report / request layout for
    <strong>{{ $categoryLabel ?? $request->document_type }}</strong>
    will replace this page once templates are uploaded.
</p>
<p class="body-text">
    Submitted data is stored in <code>request_data</code> (category, subtype, purpose, and dynamic fields) for later mail-merge into the final PDF.
</p>
@if ($fields !== [])
    <p class="body-text"><strong>Structured fields (preview):</strong></p>
    <ul style="margin:0 0 12px 18px;padding:0;">
        @foreach ($fields as $k => $v)
            <li><strong>{{ str_replace('_', ' ', (string) $k) }}:</strong> {{ is_scalar($v) ? (string) $v : json_encode($v, JSON_UNESCAPED_UNICODE) }}</li>
        @endforeach
    </ul>
@endif
