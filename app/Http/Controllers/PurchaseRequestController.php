<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use App\Models\User; // Import the User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class PurchaseRequestController extends Controller
{
    public function index()
    {
        $requests = PurchaseRequest::where('user_id', Auth::id())->get();
        return view('purchase-requests.index', compact('requests'));
    }

    public function create()
    {
        return view('purchase-requests.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_info' => 'required|string|max:255',
            'quantity_with_unit' => 'required|string',
            'price_with_currency' => 'required|string',
            'attachments.*' => 'nullable|file|mimes:jpg,png,pdf,docx|max:2048', // Adjust as needed
        ]);

        // Get the currently logged-in user
        $employee = Auth::user();

        // Retrieve the manager associated with the employee's company
        $manager = User::where('company_id', $employee->company_id)
                       ->where('role', 'manager') // Ensure we get the manager
                       ->first();

        $finance = User::where('company_id', $employee->company_id)
                       ->where('role', 'finance') // Ensure we get the manager
                       ->first();

        // Check if a manager exists for the company
        if (!$manager) {
            return redirect()->route('purchase-requests.index')->with('error', 'No manager found for your company.');
        }

        // Handle attachments
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');
                $attachments[] = $path;
            }
        }

        // Create the purchase request
        PurchaseRequest::create([
            'user_id' => Auth::id(),
            'reference_number' => uniqid('RGV/PR/2025/'), // Generate a unique reference number
            'item_info' => $request->item_info,
            'quantity_with_unit' => $request->quantity_with_unit,
            'price_with_currency' => $request->price_with_currency,
            'status' => 'pending', // Default status
            'approved_by' => $manager->user_id, // Set approved_by to manager's ID
            'checked_by' => $finance->user_id, // Initially set to null until checked by finance
            'attachments' => json_encode($attachments), // Store attachments as JSON
        ]);

        return redirect()->route('purchase-requests.index')->with('success', 'Purchase request created successfully.');
    }

    public function edit(PurchaseRequest $purchaseRequest)
    {
        return view('purchase-requests.edit', compact('purchaseRequest'));
    }

    public function update(Request $request, PurchaseRequest $purchaseRequest)
    {
        $request->validate([
            'item_info' => 'required|string|max:255',
            'quantity_with_unit' => 'required|string',
            'price_with_currency' => 'required|string',
            'status' => 'nullable|string', // Validate status
            'attachments.*' => 'nullable|file|mimes:jpg,png,pdf,docx|max:2048', // Adjust as needed
        ]);

        // Handle attachments
        $attachments = json_decode($purchaseRequest->attachments) ?? [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');
                $attachments[] = $path;
            }
        }

        // Update the purchase request
        $purchaseRequest->update([
            'item_info' => $request->item_info,
            'quantity_with_unit' => $request->quantity_with_unit,
            'price_with_currency' => $request->price_with_currency,
            'status' => $request->status ?? $purchaseRequest->status, // Use existing status if not provided
            'attachments' => json_encode($attachments), // Update attachments
        ]);

        return redirect()->route('purchase-requests.index')->with('success', 'Purchase request updated successfully.');
    }

    public function destroy(PurchaseRequest $purchaseRequest)
    {
        $purchaseRequest->delete();
        return redirect()->route('purchase-requests.index')->with('success', 'Purchase request deleted successfully.');
    }

    public function approve(Request $request, PurchaseRequest $purchaseRequest)
    {
        // Ensure the logged-in user is a manager
        $manager = Auth::user();
        if ($manager->role !== 'manager') {
            return redirect()->route('purchase-requests.index')->with('error', 'You are not authorized to approve purchase requests.');
        }

        $request->validate([
            'manager_comment' => 'nullable|string', // Validate manager's comment
        ]);

        // Update the purchase request status and approved_by field
        $purchaseRequest->update([
            'status' => 'approved',
            'approved_by' => $manager->user_id,
            'manager_comment' => $request->manager_comment, // Store manager's comment
        ]);

        return redirect()->route('purchase-requests.index')->with('success', 'Purchase request approved successfully.');
    }

    public function check(Request $request, PurchaseRequest $purchaseRequest)
    {
        // Ensure the logged-in user is in the finance department
        $financeUser = Auth::user();
        if ($financeUser->role !== 'finance') {
            return redirect()->route('purchase-requests.index')->with('error', 'You are not authorized to check purchase requests.');
        }

        $request->validate([
            'finance_comment' => 'nullable|string', // Validate finance's comment
        ]);

        // Update the purchase request status and checked_by field
        $purchaseRequest->update([
            'status' => 'checked',
            'checked_by' => $finance->user_id,
            'finance_comment' => $request->finance_comment, // Store finance's comment
        ]);

        return redirect()->route('purchase-requests.index')->with('success', 'Purchase request checked successfully.');
    }

    public function downloadPdf($id)
    {
        $request = PurchaseRequest::findOrFail($id);
        
        // Generate PDF with A4 size and portrait orientation
        $pdf = PDF::loadView('purchase-requests.pdf', compact('request'))
                    ->setPaper('A4', 'portrait');

        return $pdf->download('purchase-request_' . $request->id . '.pdf');
    }

    public function showPendingRequests()
    {
        $userId = Auth::id(); // Get the currently logged-in user's ID
    
        // Fetch pending leave requests for the authenticated user
        $pendingPurchaseRequests = PurchaseRequest::where('status', 'pending')
            ->where('user_id', $userId)
            ->with('user') // Load the user relationship if needed
            ->get();
    
        return view('display.pending', compact('pendingPurchaseRequests'));
    }

    

    public function addFinanceComment(Request $request, $purchase_request_id)
{
    $request->validate([
        'finance_comment' => 'required|string|max:255',
    ]);

    $purchaseRequest = PurchaseRequest::findOrFail($purchase_request_id);
    $purchaseRequest->finance_comment = $request->finance_comment;
    $purchaseRequest->save();

    return redirect()->back()->with('success', 'Finance comment added successfully.');
}
}