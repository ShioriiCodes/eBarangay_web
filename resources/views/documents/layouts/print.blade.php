<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('document_title') — {{ $settings->barangay_name }}</title>
    <style>
        @page { size: A4 portrait; margin: 18mm 16mm; }
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11pt;
            color: #111827;
            line-height: 1.45;
            margin: 0;
        }
        .header { text-align: center; margin-bottom: 14px; border-bottom: 1px solid #cbd5e1; padding-bottom: 12px; }
        .logo { max-height: 64px; max-width: 140px; margin: 0 auto 8px; display: block; }
        .rep { font-size: 10pt; text-transform: uppercase; letter-spacing: 0.04em; margin: 2px 0; }
        .province { font-size: 10.5pt; font-weight: bold; margin: 2px 0; }
        .muni { font-size: 10.5pt; font-weight: bold; margin: 2px 0; }
        .brgy { font-size: 12pt; font-weight: bold; color: #0038A8; margin-top: 6px; }
        h1.doc-title {
            text-align: center;
            font-size: 14pt;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin: 18px 0 12px;
            color: #0038A8;
        }
        .body-text { text-align: justify; margin: 0 0 12px; }
        .meta { font-size: 9.5pt; color: #475569; margin: 16px 0 8px; border-top: 1px solid #e2e8f0; padding-top: 10px; }
        .meta-row { margin: 3px 0; }
        .signatures { margin-top: 28px; display: table; width: 100%; }
        .sig { display: table-cell; width: 50%; vertical-align: top; text-align: center; padding: 0 8px; }
        .sig-line { border-top: 1px solid #111827; margin: 36px 12px 6px; padding-top: 4px; font-size: 10pt; font-weight: bold; }
        .sig-role { font-size: 9pt; color: #475569; }
        .footer-note {
            margin-top: 22px;
            font-size: 8.5pt;
            color: #64748b;
            text-align: center;
            font-style: italic;
        }
    </style>
</head>
<body>
    <header class="header">
        @if (! empty($logoDataUri))
            <img src="{{ $logoDataUri }}" alt="Barangay logo" class="logo">
        @endif
        <p class="rep">Republic of the Philippines</p>
        <p class="province">Province of {{ $settings->province }}</p>
        <p class="muni">Municipality of {{ $settings->municipality }}</p>
        <p class="brgy">Barangay {{ $settings->barangay_name }}</p>
        @if (filled($settings->office_address))
            <p style="font-size:9pt;color:#64748b;margin-top:6px;">{{ $settings->office_address }}</p>
        @endif
    </header>

    <h1 class="doc-title">@yield('document_title')</h1>

    <main>
        @yield('document_body')
    </main>

    <div class="meta">
        <div class="meta-row"><strong>Request No.:</strong> {{ $request->request_number }}</div>
        @if (filled($request->request_subtype))
            <div class="meta-row"><strong>Form sub-type:</strong> {{ str_replace('_', ' ', $request->request_subtype) }}</div>
        @endif
        <div class="meta-row"><strong>Date requested:</strong> {{ $request->created_at?->timezone(config('app.timezone'))->format('F j, Y') }}</div>
        @if ($request->approved_at)
            <div class="meta-row"><strong>Date approved:</strong> {{ $request->approved_at->timezone(config('app.timezone'))->format('F j, Y') }}</div>
        @endif
    </div>

    <div class="signatures">
        <div class="sig">
            <div class="sig-line">{{ $settings->captain_name ?: '_________________________' }}</div>
            <div class="sig-role">Punong Barangay / Barangay Captain</div>
        </div>
        <div class="sig">
            <div class="sig-line">{{ $settings->secretary_name ?: '_________________________' }}</div>
            <div class="sig-role">Barangay Secretary</div>
        </div>
    </div>

    <p class="footer-note">
        This document is system-generated and requires the signature of the authorized barangay official.
    </p>
</body>
</html>
