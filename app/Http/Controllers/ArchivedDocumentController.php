<?php

namespace App\Http\Controllers;

use App\Models\ArchivedDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class ArchivedDocumentController extends Controller
{
    // Display a listing of archived documents
    public function index()
    {
        $documents = ArchivedDocument::with('owner')
            ->where('company_id', Auth::user()->company_id)
            ->where('document_owner', Auth::id()) // Filter by logged-in user
            ->get();

        return view('documents.index', compact('documents'));
    }

    // Show the form for creating a new archived document
    public function create()
    {
        // Get users of the same company, excluding the logged-in user
        $users = User::where('company_id', Auth::user()->company_id)
            ->where('user_id', '!=', Auth::id()) // Exclude the logged-in user
            ->get();

        return view('documents.create', compact('users'));
    }

    // Store a newly created archived document in storage
    public function store(Request $request)
    {
        $request->validate([
            'document_name' => 'required|string|max:255',
            'document_type' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        // Create the document with the logged-in user as the owner
        $document = ArchivedDocument::create([
            'document_name' => $request->document_name,
            'document_type' => $request->document_type,
            'document_owner' => Auth::id(), // Set the owner as the logged-in user
            'description' => $request->description,
            'company_id' => Auth::user()->company_id,
        ]);

        $this->generatePdf($document);

        return redirect()->route('documents.index')->with('success', 'Document archived successfully.');
    }

    // Show the form for editing the specified archived document
    public function edit(ArchivedDocument $document)
    {
        return view('documents.edit', compact('document'));
    }

    // Update the specified archived document in storage
    public function update(Request $request, ArchivedDocument $document)
    {
        $request->validate([
            'document_name' => 'required|string|max:255',
            'document_type' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        $document->update([
            'document_name' => $request->document_name,
            'document_type' => $request->document_type,
            'description' => $request->description,
        ]);

        return redirect()->route('documents.index')->with('success', 'Document updated successfully.');
    }

    // Remove the specified archived document from storage
    public function destroy(ArchivedDocument $document)
    {
        // Optionally delete the PDF file if it exists
        if ($document->pdf_path) {
            \Storage::delete('public/' . $document->pdf_path);
        }

        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Document deleted successfully.');
    }

    // Generate PDF for the archived document
    protected function generatePdf($document)
    {
        $data = ['document' => $document];
        $pdf = PDF::loadView('pdf.archived_document', $data);
        $pdfPath = 'documents/' . $document->document_id . '.pdf';
        $pdf->save(storage_path('app/public/' . $pdfPath));

        $document->update(['pdf_path' => $pdfPath]);
    }

    // Display the specified archived document
    public function show(ArchivedDocument $document)
    {
        return view('documents.show', compact('document'));
    }
}