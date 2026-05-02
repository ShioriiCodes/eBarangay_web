@extends('documents.layouts.print')

@section('document_title')
    Certificate of Indigency
@endsection

@section('document_body')
    <p class="body-text">
        This is to certify that <strong>{{ $presenter->fullName() }}</strong>
        @if ($presenter->civilStatus())
            , <strong>{{ $presenter->civilStatus() }}</strong>,
        @endif
        @if ($presenter->gender())
            <strong>{{ $presenter->gender() }}</strong>,
        @endif
        residing at <strong>{{ $presenter->localityLine() }}</strong>
        @if ($presenter->streetAddress() !== '' && $presenter->streetAddress() !== $presenter->localityLine())
            ({{ $presenter->streetAddress() }})
        @endif
        , belongs to an indigent family in this barangay based on the records and assessment available to the barangay.
    </p>
    <p class="body-text">
        This certification is issued for <strong>{{ $request->purpose }}</strong> and may be used for appropriate government or private assistance programs, subject to further verification by the requesting agency.
    </p>
@endsection
