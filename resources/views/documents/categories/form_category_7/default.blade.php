@extends('documents.layouts.print')

@section('document_title', 'Official form — category 7 (pending)')

@section('document_body')
    @include('documents.categories._shared.form-placeholder-body', [
        'categoryLabel' => \App\Support\DocumentRequestCatalog::labelFor('form_category_7'),
    ])
@endsection
