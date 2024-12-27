@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Archived Documents</h1>
    <a href="{{ route('documents.create') }}" class="btn btn-primary">Add New Document</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Document Name</th>
                <th>Document Type</th>
                <th>Description</th>
                <th>Owner</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($documents as $document)
                <tr>
                    <td>{{ $document->document_name }}</td>
                    <td>{{ $document->document_type }}</td>
                    <td>{{ $document->description }}</td>
                    <td>{{ $document->owner->username }}</td>
                    <td>
                        <a href="{{ route('documents.show', $document) }}" class="btn btn-info">View</a>
                        <a href="{{ route('documents.edit', $document) }}" class="btn btn-warning">Edit</a>
                        <a href="#" class="btn btn-success">Download As PDF</a> <!-- This is the download button -->

                        <form action="{{ route('documents.destroy', $document) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this document?');">Delete</button>
                        </form>
                        @if($document->pdf_path)
                            <a href="{{ route('documents.generate', $document) }}" class="btn btn-success">Download PDF</a> <!-- This is the download button -->
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection