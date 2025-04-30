<?php

namespace App\Http\Controllers;

use App\Models\PaymentRequest;
use App\Models\PurchaseRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use App\Models\PettyCashRequest;
use PDF;

class PaymentRequestController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to view payment requests.');
        }

        $requests = PaymentRequest::where('user_id', Auth::id())->get();
        $company = Company::find(Auth::user()->company_id);
        $companyCountry = $company->country;

        

        return view('payment-requests.index', compact('requests', 'companyCountry'));
    }

    public function create()
{
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'You must be logged in to create a payment request.');
    }

    $managers = User::where('company_id', Auth::user()->company_id)->where('role', 'manager')->get();
    $finances = User::where('company_id', Auth::user()->company_id)->where('role', 'finance')->get();

    // Fetch approved purchase items for dropdown
    $approvedPurchaseItems = PurchaseRequest::where('user_id', Auth::id())
        ->where('status', 'approved')
        ->get(['item_name']);

    return view('payment-requests.create', compact('managers', 'finances', 'approvedPurchaseItems'));
}

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to create a payment request.');
        }

        $request->validate([
            'department' => 'nullable|string',
            'amount' => 'required|numeric',
            'unit_price' => 'required|numeric',
            'item_description' => 'required|string',
            'item_name' => 'required_if:item_type,purchase|string|nullable',
            'item_name_manual' => 'required_if:item_type,service|string|nullable',
            'unit' => 'nullable|string',
            'quantity' => 'nullable|integer',
            'cost_center' => 'nullable|string',
            'account_code' => 'nullable|string',
            'currency' => 'nullable|string',
            'vat_inclusive' => 'required|boolean',
            'item_type' => 'required|in:service,purchase',
            'approved_by' => 'required|exists:users,user_id', // Validate that the selected manager exists
            'checked_by' => 'required|exists:users,user_id', // Validate that the selected finance user exists
            'cash_type' => 'required|in:petty_cash,account_payable', // Validate cash type

        ]);

        $employee = Auth::user();
        $company = Company::find($employee->company_id);
        $manager = User::where('company_id', $employee->company_id)->where('role', 'manager')->first();
        $finance = User::where('company_id', $employee->company_id)->where('role', 'finance')->first();

        if (!$manager) {
            return redirect()->route('payment-requests.index')->with('error', 'No manager found for your company.');
        }

        // Initialize calculations
        $withHolding = 0;
        $vat = 0;
        $netPayment = 0;
        $total = 0;
        $quantity = $request->quantity ?? 1;
        $unitPrice = $request->unit_price;

        $amount = $quantity * $unitPrice;
        $request->merge(['amount' => $amount]);


        // Calculate withholding based on item type and amount
if ($company->country === 'Ethiopia') {
    if ($request->item_type === 'service' && $amount > 3000) {
        $withHolding = $amount * 0.02;
    } elseif ($request->item_type === 'purchase' && $amount > 10000) {
        $withHolding = $amount * 0.02;
    }

    // Additional calculations for Ethiopia
    if ($request->vat_inclusive) {
        $normalAmount = $amount / 1.15;
        $total = $normalAmount - $withHolding;
        $vat = $normalAmount * 0.15;
    } else {
        $total = $amount - $withHolding;
        $vat = ($amount - $withHolding) * 0.15;
    }
} elseif ($company->country === 'Uganda') {
    if ($request->item_type === 'purchase' && $amount > 1000000) {
        $withHolding = $amount * 0.02;
    }

    // Additional calculations for Uganda
    if ($request->vat_inclusive) {
        $normalAmount = $amount / 1.18;
        $total = $normalAmount - $withHolding;
        $vat = $normalAmount * 0.18;
    } else {
        $total = $amount - $withHolding;
        $vat = ($amount - $withHolding) * 0.18;
    }
} else {
    return redirect()->route('payment-requests.index')->with('error', 'Unsupported country.');
}

        $netPayment = $total + $vat;

        // Generate the reference number
        $companyAcronym = $this->createAcronym($company->company_name);
        $requestName = 'Pay_Req';
        $currentYear = date('Y');

        // Get the last reference number for the same request name and year to increment
        $lastRequest = PaymentRequest::where('reference_no', 'LIKE', "{$companyAcronym}/{$requestName}/{$currentYear}/%")
            ->orderBy('created_at', 'desc')
            ->first();

        // Determine the next sequence number
        $sequenceNumber = $lastRequest ? intval(substr($lastRequest->reference_no, strrpos($lastRequest->reference_no, '/') + 1)) + 1 : 1;


        // Format the reference number
        $reference_no = "{$companyAcronym}/{$requestName}/{$currentYear}/" . str_pad($sequenceNumber, 2, '0', STR_PAD_LEFT);
        
         // Determine the item name based on the selected type (Corrected)
         $itemName = null;
         if ($request->item_type === 'service') {
             $itemName = $request->input('item_name_manual');
             // Validation already handled this, but double check doesn't hurt
             if (empty($itemName)) {
                  return redirect()->back()->withInput()->with('error', 'Item name is required for service requests.');
             }
         } else { // 'purchase'
             $itemName = $request->input('item_name');
              if (empty($itemName)) {
                  return redirect()->back()->withInput()->with('error', 'Item name selection is required for purchase requests.');
             }
         }



        // Create the payment request
        $paymentRequest = PaymentRequest::create([
            'user_id' => Auth::id(),
            'reference_no' => $reference_no,
            'request_date' => now(),
            'department' => $request->department,
            'item_description' => $request->item_description,
            'item_type' => $request->item_type, // Ensure this is included
            'item_name' => $itemName,
            'unit' => $request->unit,
            'item_name_manual' => $request->input('item_name_manual'), // Store manual item name if provided            'unit' => $request->unit,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'amount' => $amount,
            'with_holding' => $withHolding,
            'vat' => $vat,
            'total' => $total,
            'net_payment' => $netPayment,
            'cost_center' => $request->cost_center,
            'account_code' => $request->account_code,
            'company_country' => $company->country,
            'currency' => $request->currency,
            'status' => 'pending',
            'manager_comment' => null,
            'approved_by' => $request->approved_by, // Use the selected manager's ID
            'reviewed_by' => $request->checked_by, // Use the selected finance user's ID
            'cash_type' => $request->cash_type, // Store cash type

        ]);

//         // Update the Petty Cash Request if cash type is petty cash
// if ($request->cash_type === 'petty_cash') {
//     // Get all approved petty cash requests for this user
//     $pettyCashRequests = PettyCashRequest::where('user_id', $employee->user_id)
//         ->where('status', 'approved')
//         ->orderBy('created_at', 'asc') // Deduct from oldest first
//         ->get();

//     // Calculate total available funds
//     $totalApproved = $pettyCashRequests->sum('amount_approved');
    
//     // Check if payment exceeds total available
//     if ($netPayment > $totalApproved) {
//         return redirect()->back()->with('error', 'Net payment exceeds total approved petty cash ('.$totalApproved.')')->withInput();
//     }

//     $remainingPayment = $netPayment;
//     $usedRequests = [];

//     // Deduct payment from requests in sequence
//     foreach ($pettyCashRequests as $pettyCashRequest) {
//         if ($remainingPayment <= 0) break;

//         $deductAmount = min($pettyCashRequest->amount_approved, $remainingPayment);
//         $pettyCashRequest->amount_approved -= $deductAmount;
//         $pettyCashRequest->save();
        
//         $remainingPayment -= $deductAmount;
//         $usedRequests[] = $pettyCashRequest;
//     }

//     // Calculate remaining approved amount for message
//     $remainingApprovedAmount = $totalApproved - $netPayment;
// }

// Update the Petty Cash Request if cash type is petty cash
if ($request->cash_type === 'petty_cash') {
    // Get all approved petty cash requests for this user
    $pettyCashRequests = PettyCashRequest::where('user_id', $employee->user_id)
        ->where('status', 'approved')
        ->orderBy('created_at', 'asc') // Deduct from oldest first
        ->get();

    // Calculate total available funds
    $totalApproved = $pettyCashRequests->sum('amount_approved');

    // Check if payment exceeds total available
    if ($netPayment > $totalApproved) {
        return redirect()->back()->with('error', 'Net payment exceeds total approved petty cash ('.$totalApproved.')')->withInput();
    }

    $remainingPayment = $netPayment;
    $usedRequests = [];

    // Deduct payment from requests in sequence
    foreach ($pettyCashRequests as $pettyCashRequest) {
        if ($remainingPayment <= 0) break;

        $deductAmount = min($pettyCashRequest->amount_approved, $remainingPayment);
        $pettyCashRequest->amount_approved -= $deductAmount;
        $pettyCashRequest->save();
        
        $remainingPayment -= $deductAmount;
        $usedRequests[] = $pettyCashRequest;
    }

    // Calculate remaining approved amount for message
    $remainingApprovedAmount = $totalApproved - $netPayment;

    // Update the amount in words
    $pettyCashRequest->amount_approved_in_words = $this->numberToWords($remainingApprovedAmount);
    $pettyCashRequest->save();
}

        $selectedFinance = User::find($request->checked_by);
        if ($selectedFinance) {
            $selectedFinance->notify(new \App\Notifications\PaymentRequestNotification($paymentRequest));
        }

        // Set success message
    $successMessage = 'Payment request created successfully.';
    if ($request->cash_type === 'petty_cash') {
        $successMessage .= ' Remaining approved amount: ' . $remainingApprovedAmount;
    }

    return redirect()->route('payment-requests.index')->with('success', $successMessage);
}
   

    public function edit(PaymentRequest $paymentRequest)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to edit a payment request.');
        }

        $managers = User::where('company_id', Auth::user()->company_id)->where('role', 'manager')->get();
        $finances = User::where('company_id', Auth::user()->company_id)->where('role', 'finance')->get();

        // Fetch approved purchase items for dropdown
        $approvedPurchaseItems = PurchaseRequest::where('user_id', Auth::id())
            ->where('status', 'approved')
            ->get(['item_name']);

        return view('payment-requests.edit', compact('paymentRequest', 'managers', 'finances', 'approvedPurchaseItems'));
    }

    public function update(Request $request, PaymentRequest $paymentRequest)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to update a payment request.');
        }

        $request->validate([
            'unit_price' => 'required|numeric',
            'item_type' => 'required|in:service,purchase',
            'item_name' => 'required_if:item_type,purchase|string|nullable',
            'item_name_manual' => 'required_if:item_type,service|string|nullable',
            'unit' => 'nullable|string',
            'quantity' => 'nullable|integer',
            'cost_center' => 'nullable|string',
            'account_code' => 'nullable|string',
            'currency' => 'nullable|string',
            'vat_inclusive' => 'required|boolean',
            'approved_by' => 'required|exists:users,user_id', // Validate that the selected manager exists
            'checked_by' => 'required|exists:users,user_id', // Validate that the selected finance user exists
            'cash_type' => 'required|in:petty_cash,account_payable',

        ]);

        $withHolding = 0;
        $vat = 0;
        $netPayment = 0;
        $total = 0;
        $employee = Auth::user();
        // $company = Company::find($employee->company_id);
        $company = Company::find(Auth::user()->company_id);
        $quantity = $request->quantity ?? 1;
        $unitPrice = $request->unit_price;
        $amount = $quantity * $unitPrice;

        // Calculate withholding based on item type and amount
if ($company->country === 'Ethiopia') {
    if ($request->item_type === 'service' && $amount > 3000) {
        $withHolding = $amount * 0.02;
    } elseif ($request->item_type === 'purchase' && $amount > 10000) {
        $withHolding = $amount * 0.02;
    }

    // Additional calculations for Ethiopia
    if ($request->vat_inclusive) {
        $normalAmount = $amount / 1.15;
        $total = $normalAmount - $withHolding;
        $vat = $normalAmount * 0.15;
    } else {
        $total = $amount - $withHolding;
        $vat = ($amount - $withHolding) * 0.15;
    }
} elseif ($company->country === 'Uganda') {
    if ($request->item_type === 'purchase' && $amount > 1000000) {
        $withHolding = $amount * 0.02;
    }

    // Additional calculations for Uganda
    if ($request->vat_inclusive) {
        $normalAmount = $amount / 1.18;
        $total = $normalAmount - $withHolding;
        $vat = $normalAmount * 0.18;
    } else {
        $total = $amount - $withHolding;
        $vat = ($amount - $withHolding) * 0.18;
    }
} else {
    return redirect()->route('payment-requests.index')->with('error', 'Unsupported country.');
}

        $netPayment = $total + $vat;

        // --- DETERMINE ITEM NAME (Corrected) ---
        $itemName = null;
        if ($request->item_type === 'service') {
            $itemName = $request->input('item_name_manual');
        } else { // 'purchase'
            $itemName = $request->input('item_name');
        }

        // Calculate remaining approved amount
    // $remainingApprovedAmount = $request->status === 'rejected' ? $approvedAmount : $approvedAmount - $amount;
                
        $paymentRequest->update(array_merge($request->only([
            'currency',
            'cost_center',
            'account_code',
        ]), [
            'amount' => $amount,
            'unit_price' => $unitPrice,
            'item_name' => $itemName, // Use the determined item name
            'item_name_manual' => $request->input('item_name_manual'), // Store manual name if provided
            'unit' => $request->unit,
            'quantity' => $quantity,
            'item_type' => $request->item_type,
            'with_holding' => $withHolding,
            'vat' => $vat,
            'total' => $total,
            'net_payment' => $netPayment,
            'approved_by' => $request->approved_by, // Update the selected manager's ID
            'reviewed_by' => $request->checked_by, // Update the selected finance user's ID
            'cash_type' => $request->cash_type, // Update cash type

        ]));

//         // Update the Petty Cash Request if cash type is petty cash
// if ($request->cash_type === 'petty_cash') {
//     // Get all approved petty cash requests for this user
//     $pettyCashRequests = PettyCashRequest::where('user_id', $employee->user_id)
//         ->where('status', 'approved')
//         ->orderBy('created_at', 'asc') // Deduct from oldest first
//         ->get();

//     // Calculate total available funds
//     $totalApproved = $pettyCashRequests->sum('amount_approved');
    
//     // Check if payment exceeds total available
//     if ($netPayment > $totalApproved) {
//         return redirect()->back()->with('error', 'Net payment exceeds total approved petty cash ('.$totalApproved.')')->withInput();
//     }

//     $remainingPayment = $netPayment;
//     $usedRequests = [];

//     // Deduct payment from requests in sequence
//     foreach ($pettyCashRequests as $pettyCashRequest) {
//         if ($remainingPayment <= 0) break;

//         $deductAmount = min($pettyCashRequest->amount_approved, $remainingPayment);
//         $pettyCashRequest->amount_approved -= $deductAmount;
//         $pettyCashRequest->save();
        
//         $remainingPayment -= $deductAmount;
//         $usedRequests[] = $pettyCashRequest;
//     }

//     // Calculate remaining approved amount for message
//     $remainingApprovedAmount = $totalApproved - $netPayment;
// }

// Update the Petty Cash Request if cash type is petty cash
if ($request->cash_type === 'petty_cash') {
    // Get all approved petty cash requests for this user
    $pettyCashRequests = PettyCashRequest::where('user_id', $employee->user_id)
        ->where('status', 'approved')
        ->orderBy('created_at', 'asc') // Deduct from oldest first
        ->get();

    // Calculate total available funds
    $totalApproved = $pettyCashRequests->sum('amount_approved');

    // Check if payment exceeds total available
    if ($netPayment > $totalApproved) {
        return redirect()->back()->with('error', 'Net payment exceeds total approved petty cash ('.$totalApproved.')')->withInput();
    }

    $remainingPayment = $netPayment;
    $usedRequests = [];

    // Deduct payment from requests in sequence
    foreach ($pettyCashRequests as $pettyCashRequest) {
        if ($remainingPayment <= 0) break;

        $deductAmount = min($pettyCashRequest->amount_approved, $remainingPayment);
        $pettyCashRequest->amount_approved -= $deductAmount;
        $pettyCashRequest->save();
        
        $remainingPayment -= $deductAmount;
        $usedRequests[] = $pettyCashRequest;
    }

    // Calculate remaining approved amount for message
    $remainingApprovedAmount = $totalApproved - $netPayment;

    // Update the amount in words
    $pettyCashRequest->amount_approved_in_words = $this->numberToWords($remainingApprovedAmount);
    $pettyCashRequest->save();
}

        // Set success message
    $successMessage = 'Payment request updated successfully.';
    if ($request->cash_type === 'petty_cash') {
        $successMessage .= ' Remaining approved amount: ' . $remainingApprovedAmount;
    }

    return redirect()->route('payment-requests.index')->with('success', $successMessage);
}

    public function destroy(PaymentRequest $paymentRequest)
    {
        $paymentRequest->delete();
        return redirect()->route('payment-requests.index')->with('success', 'Payment request deleted successfully.');
    }

    public function approve(Request $request, PaymentRequest $paymentRequest)
    {
        $manager = Auth::user();
        if ($manager->role !== 'manager') {
            return redirect()->route('payment-requests.index')->with('error', 'You are not authorized to approve payment requests.');
        }

        $request->validate(['manager_comment' => 'nullable|string']);

        $paymentRequest->update([
            'status' => 'approved',
            'approved_by' => $manager->user_id,
            'manager_comment' => $request->manager_comment,
        ]);

        return redirect()->route('payment-requests.index')->with('success', 'Payment request approved successfully.');
    }

    public function check(Request $request, PaymentRequest $paymentRequest)
    {
        $financeUser = Auth::user();
        if ($financeUser->role !== 'finance') {
            return redirect()->route('payment-requests.index')->with('error', 'You are not authorized to check payment requests.');
        }

        $request->validate(['finance_comment' => 'nullable|string']);

        $paymentRequest->update([
            'status' => 'checked',
            'reviewed_by' => $financeUser->user_id,
            'finance_comment' => $request->finance_comment,
        ]);

        return redirect()->route('payment-requests.index')->with('success', 'Payment request checked successfully.');
    }

    public function downloadPdf($id)
    {
        $request = PaymentRequest::findOrFail($id);
        $company = Auth::user()->company;

        $pdf = PDF::loadView('payment-requests.pdf', compact('request', 'company'))->setPaper('A4', 'portrait');
        return $pdf->download('payment-request_' . $request->payment_request_id . '.pdf');
    }

    public function showPendingPaymentRequests()
    {
        $userId = Auth::id();
        $pendingPaymentRequests = PaymentRequest::where('status', 'pending')->where('user_id', $userId)->get();
        return view('display.pending', compact('pendingPaymentRequests'));
    }

    public function addFinanceComment(Request $request, $payment_request_id)
    {
        $request->validate(['finance_comment' => 'required|string|max:255']);

        $employee = Auth::user();
        // $company = Company::find($employee->company_id);
        // $manager = User::where('company_id', $employee->company_id)->where('role', 'manager')->first();

        $paymentRequest = PaymentRequest::findOrFail($payment_request_id);
        $paymentRequest->finance_comment = $request->finance_comment;
        $paymentRequest->save();

        // if ($manager) {
        //     $manager->notify(new \App\Notifications\PaymentRequestReviewedNotification($paymentRequest));
        // }

        $manager = User::find($paymentRequest->approved_by);
        if ($manager) {
            $manager->notify(new \App\Notifications\PaymentRequestReviewedNotification($paymentRequest));
        }

        return redirect()->back()->with('success', 'Finance comment added successfully.');
    }

    // Define the createAcronym method
    private function createAcronym($companyName)
    {
        $words = preg_split('/\s+/', $companyName);
        $acronym = '';

        foreach ($words as $word) {
            if (!empty($word)) {
                $acronym .= strtoupper($word[0]); // Take the first character of each word
            }
        }

        return $acronym;
    }

    private function numberToWords($number)
{
    $words = [
        '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine',
        'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen',
        'seventeen', 'eighteen', 'nineteen', 'twenty', 'thirty', 'forty', 'fifty',
        'sixty', 'seventy', 'eighty', 'ninety'
    ];

    $scales = ['', 'thousand', 'million', 'billion'];

    if ($number < 0) {
        return 'minus ' . $this->numberToWords(abs($number));
    }

    if ($number === 0) {
        return 'zero';
    }

    $numberInWords = '';
    $scaleIndex = 0;

    while ($number > 0) {
        $chunk = $number % 1000;
        if ($chunk > 0) {
            $chunkInWords = '';

            if ($chunk >= 100) {
                $chunkInWords .= $words[intval($chunk / 100)] . ' hundred';
                $chunk %= 100;
                if ($chunk > 0) {
                    $chunkInWords .= ' and ';
                }
            }

            if ($chunk < 20) {
                $chunkInWords .= $words[$chunk];
            } else {
                $chunkInWords .= $words[intval($chunk / 10) * 10];
                if ($chunk % 10 > 0) {
                    $chunkInWords .= ' ' . $words[$chunk % 10];
                }
            }

            $numberInWords = $chunkInWords . ' ' . $scales[$scaleIndex] . ' ' . $numberInWords;
        }
        $number = intval($number / 1000);
        $scaleIndex++;
    }

    return trim($numberInWords);
}

public function managerSummary()
    {
        $manager = Auth::user();
        if ($manager->role !== 'manager') abort(403);

        // Get approved payment requests by this manager
        $paymentRequests = PaymentRequest::where('approved_by', $manager->user_id)
            ->where('status', 'approved')
            ->with('user')
            ->get();

        // Get approved petty cash requests by this manager
        $pettyCashRequests = PettyCashRequest::where('approved_by', $manager->user_id)
            ->where('status', 'approved')
            ->with('user')
            ->get();

        return view('summaries.manager', compact('paymentRequests', 'pettyCashRequests'));
    }

    public function financeSummary()
    {
        $financeUser = Auth::user();
        if ($financeUser->role !== 'finance') abort(403);

        // Get approved payment requests for finance check
        $paymentRequests = PaymentRequest::where('reviewed_by', $financeUser->user_id)
            ->where('status', 'approved')
            ->with('user')
            ->get();

        // Get approved petty cash requests for finance action
        $pettyCashRequests = PettyCashRequest::where('checked_by', $financeUser->user_id)
            ->where('status', 'approved')
            ->with('user')
            ->get();

        return view('summaries.finance', compact('paymentRequests', 'pettyCashRequests'));
    }
}