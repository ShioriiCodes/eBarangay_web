@extends('documents.layouts.print')

@section('document_title', 'Official form — template pending')

@section('document_body')
    @include('documents.categories._shared.form-placeholder-body', [
        'categoryLabel' => \App\Support\DocumentRequestCatalog::labelFor($request->document_type),
    ])
@endsection
