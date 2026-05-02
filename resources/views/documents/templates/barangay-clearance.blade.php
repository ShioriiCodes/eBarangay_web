@extends('documents.layouts.print')

@section('document_title')
    Barangay Clearance
@endsection

@section('document_body')
    <p class="body-text">
        This is to certify that <strong>{{ $presenter->fullName() }}</strong>, of legal age,
        @if ($presenter->civilStatus())
            <strong>{{ $presenter->civilStatus() }}</strong>,
        @endif
        @if ($presenter->gender())
            <strong>{{ $presenter->gender() }}</strong>,
        @endif
        a resident of <strong>{{ $presenter->localityLine() }}</strong>
        @if ($presenter->streetAddress() !== '' && $presenter->streetAddress() !== $presenter->localityLine())
            ({{ $presenter->streetAddress() }})
        @endif
        , is known to be of good moral character and a law-abiding citizen of Barangay {{ $settings->barangay_name }}.
    </p>
    <p class="body-text">
        This certification is issued upon the request of the interested party for <strong>{{ $request->purpose }}</strong>.
    </p>
    <p class="body-text">
        This clearance is valid from the date of issue unless revoked for cause and subject to the rules of the barangay.
    </p>
@endsection
