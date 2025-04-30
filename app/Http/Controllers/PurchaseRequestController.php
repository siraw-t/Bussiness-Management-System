<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use App\Models\User; // Import the User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company; 
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
    $currencies = ['USD', 'ETB', 'UGX'];
    $units = ['kg', 'pcs', 'ltr'];
    $managers = User::where('company_id', Auth::user()->company_id)->where('role', 'manager')->get();
    $finances = User::where('company_id', Auth::user()->company_id)->where('role', 'finance')->get();

    return view('purchase-requests.create', compact('currencies', 'units', 'managers', 'finances'));
}

    public function store(Request $request)
{
    $request->validate([
        'item_name' => 'required|string|max:255',
        'item_description' => 'nullable|string',
        'quantity' => 'required|integer',
        'unit' => 'required|string',
        'price' => 'required|numeric',
        'currency' => 'required|string',
        'attachments.*' => 'nullable|file|mimes:jpg,png,pdf,docx|max:2048',
        'approved_by' => 'required|exists:users,user_id', // Validate that the selected manager exists
        'checked_by' => 'required|exists:users,user_id', // Validate that the selected finance exists
    ]);

    // Get the currently logged-in user
    $employee = Auth::user();
    $company = Company::find($employee->company_id);


    // Function to create an acronym from the company name
function createAcronym($companyName)
{
   $words = explode(' ', $companyName);
   $acronym = '';

   foreach ($words as $word) {
       if (!empty($word)) {
           $acronym .= strtoupper($word[0]); // Take the first letter of each word
       }
   }

   return $acronym;
}

// Generate the acronym for the company name
$companyAcronym = createAcronym($company->company_name);

// Format the reference number
$requestName = 'Purchase_Req'; // Using the table name
$currentYear = date('Y');

// Get the last reference number for the same request name and year to increment
$lastRequest = PurchaseRequest::where('reference_number', 'LIKE', "{$companyAcronym}/{$requestName}/{$currentYear}/%")
   ->orderBy('created_at', 'desc')
   ->first();

// Determine the next sequence number
$sequenceNumber = $lastRequest ? intval(substr($lastRequest->reference_number, strrpos($lastRequest->reference_number, '/') + 1)) + 1 : 1;

// Format the reference number
$reference_number = "{$companyAcronym}/{$requestName}/{$currentYear}/" . str_pad($sequenceNumber, 2, '0', STR_PAD_LEFT);




    $attachments = [];
    if ($request->hasFile('attachments')) {
        foreach ($request->file('attachments') as $file) {
            $path = $file->store('attachments', 'public');
            $attachments[] = $path;
        }
    }

    $manager = User::where('company_id', $employee->company_id)->where('role', 'manager')->first();
    $finance = User::where('company_id', $employee->company_id)->where('role', 'finance')->first();


    // Create the purchase request
    $purchaseRequest = PurchaseRequest::create([
        'user_id' => Auth::id(),
        'reference_number' => $reference_number, // Generate a unique reference number
        'item_name' => $request->item_name,
        'item_description' => $request->item_description,
        'quantity' => $request->quantity,
        'unit' => $request->unit,
        'price' => $request->price,
        'currency' => $request->currency,
        'total_price' => $request->quantity * $request->price, // Calculate total price
        'company_country' => $company->country, // Get country from company
        'status' => 'pending', // Default status
        'attachments' => json_encode($attachments), // Store attachments as JSON
        'approved_by' => $request->approved_by, // Use the selected manager's ID
        'checked_by' => $request->checked_by, // Use the selected finance user's ID
    ]);

    // if ($finance) {
    //     $finance->notify(new \App\Notifications\PurchaseRequestNotification($purchaseRequest));
    // }

    $selectedFinance = User::find($request->checked_by);
    if ($selectedFinance) {
        $selectedFinance->notify(new \App\Notifications\PurchaseRequestNotification($purchaseRequest));
    }

    return redirect()->route('purchase-requests.index')->with('success', 'Purchase request created successfully.');
}

public function edit(PurchaseRequest $purchaseRequest)
{
    $currencies = ['USD', 'ETB', 'UGX'];
    $units = ['kg', 'pcs', 'ltr'];
    $managers = User::where('company_id', Auth::user()->company_id)->where('role', 'manager')->get();
    $finances = User::where('company_id', Auth::user()->company_id)->where('role', 'finance')->get();

    return view('purchase-requests.edit', compact('purchaseRequest', 'currencies', 'units', 'managers', 'finances'));
}

    public function update(Request $request, PurchaseRequest $purchaseRequest)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'item_description' => 'nullable|string',
            'quantity' => 'required|integer',
            'unit' => 'required|string',
            'price' => 'required|numeric',
            'currency' => 'required|string',
            'attachments.*' => 'nullable|file|mimes:jpg,png,pdf,docx|max:2048',
            'approved_by' => 'required|exists:users,user_id', // Validate that the selected manager exists
            'checked_by' => 'required|exists:users,user_id', // Validate that the selected finance exists
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
            'item_name' => $request->item_name,
            'item_description' => $request->item_description,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'price' => $request->price,
            'currency' => $request->currency,
            'total_price' => $request->quantity * $request->price, // Update total price
            'attachments' => json_encode($attachments), // Update attachments
            'approved_by' => $request->approved_by, // Update the selected manager's ID
            'checked_by' => $request->checked_by, // Update the selected finance user's ID
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
        $finance = Auth::user();
        if ($finance->role !== 'finance') {
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
        $company = Auth::user()->company;

        // Generate PDF with A4 size and portrait orientation
        $pdf = PDF::loadView('purchase-requests.pdf', compact('request', 'company'))
                    ->setPaper('A4', 'portrait');

        return $pdf->download('purchase-request_' . $request->purchase_request_id . '.pdf');
    }

    public function showPendingRequests()
{
    $userId = Auth::id(); // Get the currently logged-in user's ID

    // Fetch pending purchase requests for the authenticated user
    $pendingPurchaseRequests = PurchaseRequest::where('status', 'pending')
            ->where('user_id', $userId)
            ->with('user')
            ->get();

    return view('display.pending', compact('pendingPurchaseRequests'));
}

    public function addFinanceComment(Request $request, $purchase_request_id)
    {
        $request->validate([
            'finance_comment' => 'required|string|max:255',
        ]);
        $employee = Auth::user();
    // $company = Company::find($employee->company_id);

    //     $manager = User::where('company_id', $employee->company_id)->where('role', 'manager')->first();


        $purchaseRequest = PurchaseRequest::findOrFail($purchase_request_id);
        $purchaseRequest->finance_comment = $request->finance_comment;
        $purchaseRequest->save();

        // if ($manager) {
        //     $manager->notify(new \App\Notifications\PurchaseRequestReviewedNotification($purchaseRequest));
        // }

        $manager = User::find($purchaseRequest->approved_by);
        if ($manager) {
            $manager->notify(new \App\Notifications\PurchaseRequestReviewedNotification($purchaseRequest));
        }

        return redirect()->back()->with('success', 'Finance comment added successfully.');
    }
}