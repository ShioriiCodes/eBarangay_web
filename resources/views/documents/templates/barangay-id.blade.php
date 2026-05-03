@extends('documents.layouts.print')

@section('document_title')
    Barangay Identification Certificate
@endsection

@section('document_body')
    <p class="body-text">
        This certifies that <strong>{{ $presenter->fullName() }}</strong>
        @if ($presenter->birthdateFormatted())
            , born on <strong>{{ $presenter->birthdateFormatted() }}</strong>,
        @endif
        @if ($presenter->gender())
            <strong>{{ $presenter->gender() }}</strong>,
        @endif
        is a registered resident of Barangay {{ $settings->barangay_name }}, Municipality of {{ $settings->municipality }}, Province of {{ $settings->province }}.
    </p>
    <p class="body-text">
        Resident address on file: <strong>{{ $presenter->streetAddress() !== '' ? $presenter->streetAddress() : $presenter->localityLine() }}</strong>
        @if ($presenter->purok())
            — Purok <strong>{{ $presenter->purok() }}</strong>
        @endif
        .
    </p>
    <p class="body-text">
        Purpose of issuance: <strong>{{ $request->purpose }}</strong>.
    </p>
    <p class="body-text" style="font-size:10pt;color:#475569;">
        This document supports barangay-issued identification procedures. Official ID cards, if applicable, are issued separately after verification and payment of applicable fees.
    </p>
@endsection
