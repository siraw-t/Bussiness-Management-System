@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow mx-auto" style="max-width: 600px;">
        <div class="card-header text-center bg-primary text-white">
            <h2>{{ $document->document_name }}</h2>
        </div>
        <div class="card-body">
            <p class="lead"><strong>Type:</strong> {{ $document->document_type }}</p>
            <p class="lead"><strong>Description:</strong> {{ $document->description }}</p>
            <p class="lead"><strong>Owner:</strong> {{ $document->owner->full_name }}</p>
            @if($document->pdf_path)
                <a href="{{ asset('storage/' . $document->pdf_path) }}" class="btn btn-success btn-lg mb-3" target="_blank">
                    <i class="bi bi-file-earmark-arrow-down"></i> Download PDF
                </a>
            @endif
            <br>
            <a href="{{ route('documents.index') }}" class="btn btn-success btn-lg">
                <i class="bi bi-arrow-left"></i> Back to Documents
            </a>
        </div>
    </div>
</div>
@endsection