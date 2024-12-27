@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Document</h1>
    <form action="{{ route('documents.update', $document) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="document_name">Document Name</label>
            <input type="text" class="form-control" id="document_name" name="document_name" value="{{ old('document_name', $document->document_name) }}" required>
        </div>
        <div class="form-group">
            <label for="document_type">Document Type</label>
            <select class="form-control" id="document_type" name="document_type" required>
                <option value="leave" {{ $document->document_type == 'leave' ? 'selected' : '' }}>Leave</option>
                <option value="plan" {{ $document->document_type == 'plan' ? 'selected' : '' }}>Plan</option>
                <option value="report" {{ $document->document_type == 'report' ? 'selected' : '' }}>Report</option>
                <!-- Add any other specific document types here -->
            </select>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description">{{ old('description', $document->description) }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Document</button>
    </form>
</div>
@endsection