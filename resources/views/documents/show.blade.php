@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $document->document_name }}</h1>
    <p><strong>Type:</strong> {{ $document->document_type }}</p>
    <p><strong>Description:</strong> {{ $document->description }}</p>
    <p><strong>Owner:</strong> {{ $document->owner->name }}</p>
    @if($document->pdf_path)
        <a href="{{ asset('storage/' . $document->pdf_path) }}" class="btn btn-success" target="_blank">Download PDF</a>
    @endif
    <br><br>
    <a href="{{ route('documents.index') }}" class="btn btn-secondary">Back to Documents</a>
</div>
@endsection