@extends('documents.layouts.print')

@section('document_title')
    Certificate of Residency
@endsection

@section('document_body')
    <p class="body-text">
        This is to certify that <strong>{{ $presenter->fullName() }}</strong>
        @if ($presenter->birthdateFormatted())
            , born on <strong>{{ $presenter->birthdateFormatted() }}</strong>,
        @endif
        @if ($presenter->civilStatus())
            <strong>{{ $presenter->civilStatus() }}</strong>,
        @endif
        @if ($presenter->gender())
            <strong>{{ $presenter->gender() }}</strong>,
        @endif
        is a bona fide resident of <strong>{{ $presenter->localityLine() }}</strong>
        @if ($presenter->streetAddress() !== '' && $presenter->streetAddress() !== $presenter->localityLine())
            with address at <strong>{{ $presenter->streetAddress() }}</strong>
        @endif
        under the jurisdiction of Barangay {{ $settings->barangay_name }}, Municipality of {{ $settings->municipality }}, Province of {{ $settings->province }}.
    </p>
    <p class="body-text">
        This certificate is issued upon request for <strong>{{ $request->purpose }}</strong> and for whatever legal purpose it may serve.
    </p>
@endsection
