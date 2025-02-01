@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">{{ Auth::user()->full_name }}'s Archived Documents</h1>

        <div class="text-center mb-4">
            <a href="{{ route('documents.create') }}" class="btn btn-primary mb-3 ">
                <i class="bi bi-file-earmark-plus "></i> Add New Document
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Document Name</th>
                    <th>Document Type</th>
                    <th>Description</th>
                    <th>Owner</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($documents as $document)
                    <tr>
                        <td>{{ $document->document_name }}</td>
                        <td>{{ $document->document_type }}</td>
                        <td>{{ $document->description }}</td>
                        <td>{{ $document->owner->username }}</td>
                        <td>
                            <a href="{{ route('documents.show', $document) }}" class="btn btn-info btn-sm">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <a href="{{ route('documents.edit', $document) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('documents.destroy', $document) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this document?');">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                            @if ($document->pdf_path)
                                <a href="{{ route('documents.generate', $document) }}" class="btn btn-success btn-sm">
                                    <i class="bi bi-file-earmark-arrow-down"></i> Download PDF
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <style>
        .table-hover tr:hover {
            background-color: #f8f9fa;
            /* Light grey on hover */
            cursor: pointer;
            /* Pointer cursor on hover */
        }
    </style>
@endsection
