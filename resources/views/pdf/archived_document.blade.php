{{-- resources/views/pdf/archived_document.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>{{ $document->document_name }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h1, p { margin: 0; }
    </style>
</head>
<body>
    <h1>{{ $document->document_name }}</h1>
    <p><strong>Type:</strong> {{ $document->document_type }}</p>
    <p><strong>Description:</strong> {{ $document->description }}</p>
    <p><strong>Owner:</strong> {{ $document->owner->name }}</p>
    <p><strong>Company:</strong> {{ $document->company->company_name }}</p>
</body>
</html>