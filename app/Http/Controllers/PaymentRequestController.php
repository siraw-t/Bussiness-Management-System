<?php

namespace App\Http\Controllers;

use App\Models\PaymentRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class PaymentRequestController extends Controller
{
    public function index()
    {
        $requests = PaymentRequest::where('user_id', Auth::id())->get();
        return view('payment-requests.index', compact('requests'));
    }

    public function create()
    {
        return view('payment-requests.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'ref_no' => 'nullable|string',
        'from' => 'nullable|string',
        'amount' => 'required|numeric',
        'description' => 'required|string',
        'unit_and_qty' => 'nullable|string',
        'cost_center' => 'nullable|string',
        'account_code' => 'nullable|string',
        'country_cost_recharge' => 'nullable|numeric',
        'allocation_for_country' => 'nullable|numeric',
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
        return redirect()->route('payment-requests.index')->with('error', 'No manager found for your company.');
    }
    
    $vat = $request->amount * 0.18; // 18% VAT
    $total = $request->amount + $vat; // Total including VAT (fixed this line)
    $withHolding = $total * 0.06; // 6% withholding
    $netPayment = $total - $withHolding; // Net Payment

    PaymentRequest::create([
        'user_id' => Auth::id(),
        'amount' => $request->amount,
        'description' => $request->description,
        'unit_and_qty' => $request->unit_and_qty,
        'cost_center' => $request->cost_center,
        'account_code' => $request->account_code,
        'country_cost_recharge' => $request->country_cost_recharge,
        'allocation_for_country' => $request->allocation_for_country,
        'vat' => $vat,
        'total' => $total,
        'with_holding' => $withHolding,
        'net_payment' => $netPayment,
        'status' => 'pending',
        'ref_no' => $request->ref_no, // Store the reference number from the request
        'request_date' => now(), // Set to current date
        'from' => $request->from, // Store the department from the request
        'finance_comment' => null, // Initially null
        'manager_comment' => null, // Initially null
        'approved_by' => $manager->user_id, // Initially null
        'reviewed_by' => $finance->user_id, // Initially null
    ]);

    return redirect()->route('payment-requests.index')->with('success', 'Payment request created successfully.');
}

    public function edit(PaymentRequest $paymentRequest)
    {
        return view('payment-requests.edit', compact('paymentRequest'));
    }

    public function update(Request $request, PaymentRequest $paymentRequest)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'description' => 'required|string',
            'unit_and_qty' => 'nullable|string',
            'cost_center' => 'nullable|string',
            'account_code' => 'nullable|string',
            'country_cost_recharge' => 'nullable|numeric',
            'allocation_for_country' => 'nullable|numeric',
            'status' => 'nullable|string',
        ]);

        $vat = $request->amount * 0.18; // 18% VAT
        $total = $request->amount - $vat; // Total including VAT
        $withHolding = $total * 0.06; // 6% withholding
        $netPayment = $total - $withHolding; // Net Payment

        $paymentRequest->update(array_merge($request->only([
            'status',
            'unit_and_qty',
            'cost_center',
            'account_code',
            'country_cost_recharge',
            'allocation_for_country',
        ]), [
            'amount' => $request->amount,
            'description' => $request->description,
            'vat' => $vat,
            'total' => $total,
            'with_holding' => $withHolding,
            'net_payment' => $netPayment,
        ]));

        return redirect()->route('payment-requests.index')->with('success', 'Payment request updated successfully.');
    }

    public function destroy(PaymentRequest $paymentRequest)
    {
        $paymentRequest->delete();
        return redirect()->route('payment-requests.index')->with('success', 'Payment request deleted successfully.');
    }

    public function approve(Request $request, PaymentRequest $paymentRequest)
{
    // Ensure the logged-in user is a manager
    $manager = Auth::user();
    if ($manager->role !== 'manager') {
        return redirect()->route('payment-requests.index')->with('error', 'You are not authorized to approve payment requests.');
    }

    $request->validate([
        'manager_comment' => 'nullable|string',
    ]);

    // Update the payment request
    $paymentRequest->update([
        'status' => 'approved',
        'approved_by' => $manager->user_id,
        'manager_comment' => $request->manager_comment,
    ]);

    return redirect()->route('payment-requests.index')->with('success', 'Payment request approved successfully.');
}

public function check(Request $request, PaymentRequest $paymentRequest)
{
    // Ensure the logged-in user is in finance
    $financeUser = Auth::user();
    if ($financeUser->role !== 'finance') {
        return redirect()->route('payment-requests.index')->with('error', 'You are not authorized to check payment requests.');
    }

    $request->validate([
        'finance_comment' => 'nullable|string',
    ]);

    // Update the payment request
    $paymentRequest->update([
        'status' => 'checked',
        'reviewed_by' => $finance->user_id,
        'finance_comment' => $request->finance_comment,
    ]);

    return redirect()->route('payment-requests.index')->with('success', 'Payment request checked successfully.');
}

public function downloadPdf($id)
{
    $request = PaymentRequest::findOrFail($id);  // Changed model
    
    $pdf = PDF::loadView('payment-requests.pdf', compact('request'))  // Updated view path
            ->setPaper('A4', 'portrait');

    return $pdf->download('payment-request_' . $request->id . '.pdf');  // Updated filename
}

public function showPendingPaymentRequests()
{
    $userId = Auth::id();
    
    // Get pending payment requests for the authenticated user
    $pendingPaymentRequests = PaymentRequest::where('status', 'pending')
        ->where('user_id', $userId)
        ->with('user')
        ->get();

    return view('display.pending', compact('pendingPaymentRequests'));
}

public function addFinanceComment(Request $request, $payment_request_id)  // Updated parameter
{
    $request->validate([
        'finance_comment' => 'required|string|max:255',
    ]);

    $paymentRequest = PaymentRequest::findOrFail($payment_request_id);  // Changed model
    $paymentRequest->finance_comment = $request->finance_comment;
    $paymentRequest->save();

    return redirect()->back()->with('success', 'Finance comment added successfully.');
}
}