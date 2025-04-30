<?php

namespace App\Http\Controllers;

use App\Models\PettyCashRequest;
use App\Models\PaymentRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use PDF;

class PettyCashRequestController extends Controller
{
    public function index()
    {
        $requests = PettyCashRequest::where('user_id', Auth::id())->get();
        return view('petty_cash_requests.index', compact('requests'));
    }

    public function create()
    {
        $currencies = ['USD', 'ETB', 'UGX'];
        $managers = User::where('company_id', Auth::user()->company_id)->where('role', 'manager')->get();
        $finances = User::where('company_id', Auth::user()->company_id)->where('role', 'finance')->get();
        return view('petty_cash_requests.create', compact('currencies', 'managers', 'finances'));
    }

    public function store(Request $request)
    {

        // 1. Check for an existing request with amount_approved = 0
    $lastRequest = PettyCashRequest::where('user_id', Auth::id())
    ->latest('created_at')
    ->first();                                     
if ($lastRequest && $lastRequest->amount_approved > 0) {
    return redirect()->back()
        ->with('error', 'You already have an approved petty‑cash request. Please settle it before creating a new one.');
}

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount_requested' => 'required|numeric',
            'amount_requested_in_words' => 'nullable|string',
            'currency' => 'required|string',
            'approved_by' => 'required|exists:users,user_id',
            'checked_by' => 'required|exists:users,user_id',
        ]);

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
        $requestName = 'Petty_cash_Req'; // Using the table name
        $currentYear = date('Y');

        // Get the last reference number for the same request name and year to increment
        $lastRequest = PettyCashRequest::where('reference_number', 'LIKE', "{$companyAcronym}/{$requestName}/{$currentYear}/%")
            ->orderBy('created_at', 'desc')
            ->first();

        // Determine the next sequence number
        $sequenceNumber = $lastRequest ? intval(substr($lastRequest->reference_number, strrpos($lastRequest->reference_number, '/') + 1)) + 1 : 1;

        // Format the reference number
        $reference_number = "{$companyAcronym}/{$requestName}/{$currentYear}/" . str_pad($sequenceNumber, 2, '0', STR_PAD_LEFT);

        $pettyCashRequest = PettyCashRequest::create([
            'user_id' => Auth::id(),
            'reference_number' => $reference_number,
            'title' => $request->title,
            'description' => $request->description,
            'amount_requested' => $request->amount_requested,
            'amount_requested_in_words' => $request->amount_requested_in_words,
            'currency' => $request->currency,
            'status' => 'pending',
            'company_country' => $company->country,
            'approved_by' => $request->approved_by,
            'checked_by' => $request->checked_by,
        ]);

        return redirect()->route('petty_cash_requests.index')->with('success', 'Petty cash request created successfully.');
    }

    public function edit(PettyCashRequest $pettyCashRequest)
    {
        $currencies = ['USD', 'ETB', 'UGX'];
        $managers = User::where('company_id', Auth::user()->company_id)->where('role', 'manager')->get();
        $finances = User::where('company_id', Auth::user()->company_id)->where('role', 'finance')->get();
        return view('petty_cash_requests.edit', compact('pettyCashRequest', 'currencies', 'managers', 'finances'));
    }

    public function update(Request $request, PettyCashRequest $pettyCashRequest)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount_requested' => 'required|numeric',
            'amount_requested_in_words' => 'nullable|string',
            'currency' => 'required|string',
        ]);

        $pettyCashRequest->update([
            'title' => $request->title,
            'description' => $request->description,
            'amount_requested' => $request->amount_requested,
            'amount_requested_in_words' => $request->amount_requested_in_words,
            'currency' => $request->currency,
        ]);

        return redirect()->route('petty_cash_requests.index')->with('success', 'Petty cash request updated successfully.');
    }

    public function destroy(PettyCashRequest $pettyCashRequest)
{
    

    // Delete the petty cash request
    $pettyCashRequest->delete();

    return redirect()->route('petty_cash_requests.index')->with('success', 'Petty cash request deleted successfully.');
}


    public function approve(Request $request, PettyCashRequest $pettyCashRequest)
    {
        $manager = Auth::user();
        if ($manager->role !== 'manager') {
            return redirect()->route('petty_cash_requests.index')->with('error', 'You are not authorized to approve petty cash requests.');
        }

        $request->validate([
            'manager_comment' => 'nullable|string',
            'amount_approved' => 'required|numeric',
            'amount_approved_in_words' => 'nullable|string',
        ]);

        $pettyCashRequest->update([
            'status' => 'approved',
            'approved_by' => $manager->user_id,
            'manager_comment' => $request->manager_comment,
            'amount_approved' => $request->amount_approved,
            'amount_approved_in_words' => $request->amount_approved_in_words,
        ]);

        return redirect()->route('petty_cash_requests.index')->with('success', 'Petty cash request approved successfully.');
    }

    public function check(Request $request, PettyCashRequest $pettyCashRequest)
{
    $finance = Auth::user();
    if ($finance->role !== 'finance') {
        return redirect()->route('petty_cash_requests.index')->with('error', 'You are not authorized to check petty cash requests.');
    }

    $request->validate([
        'finance_comment' => 'nullable|string',
        'attachments' => 'nullable|array',
        'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048', // File validation
    ]);

    $attachments = [];

    // Check if the 'attachments' field is present and contains files
    if ($request->hasFile('attachments')) {
        $attachments = array_map(function ($file) {
            return $file->store('attachments', 'public');
        }, $request->file('attachments'));
    }

    // Update the petty cash request
    $pettyCashRequest->update([
        'finance_comment' => $request->finance_comment,
        'attachments' => json_encode($attachments), // Update attachments if any
    ]);

    return redirect()->route('petty_cash_requests.index')->with('success', 'Petty cash request updated successfully.');
}


public function showPendingPettyCashRequests()
{
    $userId = Auth::id(); // Get the currently logged-in user's ID

    // Fetch pending purchase requests for the authenticated user
    $pendingPettyCashRequests = PettyCashRequest::where('status', 'pending')
            ->where('user_id', $userId)
            ->with('user')
            ->get();

    return view('display.pending', compact('pendingPettyCashRequests'));
}

    public function downloadPdf($id)
    {
        $request = PettyCashRequest::findOrFail($id);
        $company = Auth::user()->company;

        $pdf = PDF::loadView('petty_cash_requests.pdf', compact('request', 'company'))
                    ->setPaper('A4', 'portrait');

        return $pdf->download('petty_cash_request_' . $request->id . '.pdf');
    }

    public function addFinanceComment(Request $request, $petty_cash_request_id)
{
    $request->validate([
        'finance_comment' => 'required|string|max:255',
        'attachments' => 'nullable|array',
        'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    $pettyCashRequest = PettyCashRequest::findOrFail($petty_cash_request_id);

    $pettyCashRequest->finance_comment = $request->finance_comment;

    if ($request->hasFile('attachments')) {
        $attachments = [];
        foreach ($request->file('attachments') as $file) {
            // Store each file and get the path
            $path = $file->store('attachments', 'public');
            $attachments[] = $path;
        }

        // Merge with existing attachments
        $existingAttachments = json_decode($pettyCashRequest->attachments, true) ?? [];
        $pettyCashRequest->attachments = json_encode(array_merge($existingAttachments, $attachments));
    }

    $pettyCashRequest->save();

    return redirect()->back()->with('success', 'Finance comment and attachments added successfully.');
}

public function addFinanceCheque(Request $request, $petty_cash_request_id)
{
    $request->validate([
        'attachments' => 'nullable|array',
        'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    $pettyCashRequest = PettyCashRequest::findOrFail($petty_cash_request_id);


    if ($request->hasFile('attachments')) {
        $attachments = [];
        foreach ($request->file('attachments') as $file) {
            // Store each file and get the path
            $path = $file->store('attachments', 'public');
            $attachments[] = $path;
        }

        // Merge with existing attachments
        $existingAttachments = json_decode($pettyCashRequest->attachments, true) ?? [];
        $pettyCashRequest->attachments = json_encode(array_merge($existingAttachments, $attachments));
    }

    $pettyCashRequest->save();

    return redirect()->back()->with('success', 'Finance cheque  attached  successfully.');
}

// Add these methods to PettyCashRequestController
public function pettyCashBalance()
    {
        $user = Auth::user();
        $totalApproved = PettyCashRequest::where('user_id', $user->user_id)
            ->where('status', 'approved')
            ->sum('amount_approved');

        $usedAmount = PaymentRequest::where('user_id', $user->user_id)
            ->where('cash_type', 'petty_cash')
            ->sum('net_payment');

        return [
            'total_approved' => $totalApproved,
            'used_amount' => $usedAmount,
            'remaining_balance' => $totalApproved - $usedAmount
        ];
    }

    
}