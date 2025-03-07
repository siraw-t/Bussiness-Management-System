<?php


namespace App\Http\Controllers;

use App\Models\PaymentRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company; // Import the Company model

use PDF;

class PaymentRequestController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to view payment requests.');
        }

        $requests = PaymentRequest::where('user_id', Auth::id())->get();
        $company = Company::find(Auth::user()->company_id); // Fetch company based on logged-in user
        $companyCountry = $company->country; // Get the company country

        return view('payment-requests.index', compact('requests', 'companyCountry'));
    }

    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to create a payment request.');
        }

        return view('payment-requests.create');
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to create a payment request.');
        }

        $request->validate([
            // 'reference_no' => 'nullable|string',
            'department' => 'nullable|string',
            'amount' => 'required|numeric',
            'item_description' => 'required|string',
            'item_name' => 'nullable|string',
            'unit' => 'nullable|string',
            'quantity' => 'nullable|integer',
            'cost_center' => 'nullable|string',
            'account_code' => 'nullable|string',
            'currency' => 'nullable|string',
            'vat_inclusive' => 'required|boolean',
        ]);

        $employee = Auth::user();
        $company = Company::find($employee->company_id); // Fetch company details

        $manager = User::where('company_id', $employee->company_id)->where('role', 'manager')->first();
        $finance = User::where('company_id', $employee->company_id)->where('role', 'finance')->first();

        if (!$manager) {
            return redirect()->route('payment-requests.index')->with('error', 'No manager found for your company.');
        }

        // Calculate values based on country and VAT inclusion
        $withHolding = 0;
        $vat = 0;
        $netPayment = 0;
        $total = 0;

        if ($company->country === 'Ethiopia') {
            if ($request->vat_inclusive) {
                $normalAmount = $request->amount / 1.15;
                $withHolding = $normalAmount * 0.02;
                $total = $normalAmount - $withHolding;
                $vat = $normalAmount * 0.15;
            } else {
                $withHolding = $request->amount * 0.02;
                $total = $request->amount - $withHolding;
                $vat = ($request->amount - $withHolding) * 0.15;
            }
        } elseif ($company->country === 'Uganda') {
            if ($request->vat_inclusive) {
                $normalAmount = $request->amount / 1.18;
                $withHolding = $normalAmount * 0.06;
                $total = $normalAmount - $withHolding;
                $vat = $normalAmount * 0.18;
            } else {
                $withHolding = $request->amount * 0.06;
                $total = $request->amount - $withHolding;
                $vat = ($request->amount - $withHolding) * 0.18;
            }
        } else {
            return redirect()->route('payment-requests.index')->with('error', 'Unsupported country.');
        }

        $netPayment = $total + $vat;

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
    $requestName = 'Pay_Req'; // Using the table name
    $currentYear = date('Y');

    // Get the last reference number for the same request name and year to increment
    $lastRequest = PaymentRequest::where('reference_no', 'LIKE', "{$companyAcronym}/{$requestName}/{$currentYear}/%")
        ->orderBy('created_at', 'desc')
        ->first();

    // Determine the next sequence number
    $sequenceNumber = $lastRequest ? intval(substr($lastRequest->reference_no, strrpos($lastRequest->reference_no, '/') + 1)) + 1 : 1;

    // Format the reference number
    $reference_no = "{$companyAcronym}/{$requestName}/{$currentYear}/" . str_pad($sequenceNumber, 2, '0', STR_PAD_LEFT);
        PaymentRequest::create([
            'user_id' => Auth::id(),
            'reference_no' => $reference_no,
            'request_date' => now(),
            'department' => $request->department,
            'item_description' => $request->item_description,
            'item_name' => $request->item_name,
            'unit' => $request->unit,
            'quantity' => $request->quantity,
            'amount' => $request->amount,
            'with_holding' => $withHolding,
            'vat' => $vat,
            'total' => $total,
            'net_payment' => $netPayment,
            'cost_center' => $request->cost_center,
            'account_code' => $request->account_code,
            'company_country' => $company->country, // Store the company country
            'currency' => $request->currency,
            'status' => 'pending',
            'manager_comment' => null,
            'approved_by' => $manager->user_id,
            'reviewed_by' => $finance ? $finance->user_id : null,
        ]);

        return redirect()->route('payment-requests.index')->with('success', 'Payment request created successfully.');
    }

    public function edit(PaymentRequest $paymentRequest)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to edit a payment request.');
        }

        return view('payment-requests.edit', compact('paymentRequest'));
    }

    public function update(Request $request, PaymentRequest $paymentRequest)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to update a payment request.');
        }

        $request->validate([
            'amount' => 'required|numeric',
            'item_name' => 'required|string',
            'unit' => 'nullable|string',
            'quantity' => 'nullable|integer',
            'cost_center' => 'nullable|string',
            'account_code' => 'nullable|string',
            'currency' => 'nullable|string',
            'vat_inclusive' => 'required|boolean',
        ]);

        $withHolding = 0;
        $vat = 0;
        $netPayment = 0;
        $total = 0;

        $company = Company::find(Auth::user()->company_id); // Fetch company details

        if ($company->country === 'Ethiopia') {
            if ($request->vat_inclusive) {
                $normalAmount = $request->amount / 1.15;
                $withHolding = $normalAmount * 0.02;
                $total = $normalAmount - $withHolding;
                $vat = $normalAmount * 0.15;
            } else {
                $withHolding = $request->amount * 0.02;
                $total = $request->amount - $withHolding;
                $vat = ($request->amount - $withHolding) * 0.15;
            }
        } elseif ($company->country === 'Uganda') {
            if ($request->vat_inclusive) {
                $normalAmount = $request->amount / 1.18;
                $withHolding = $normalAmount * 0.06;
                $total = $normalAmount - $withHolding;
                $vat = $normalAmount * 0.18;
            } else {
                $withHolding = $request->amount * 0.06;
                $total = $request->amount - $withHolding;
                $vat = ($request->amount - $withHolding) * 0.18;
            }
        }

        $netPayment = $total + $vat;

        $paymentRequest->update(array_merge($request->only([
            'currency',
            'cost_center',
            'account_code',
        ]), [
            'amount' => $request->amount,
            'item_name' => $request->item_name,
            'unit' => $request->unit,
            'quantity' => $request->quantity,
            'with_holding' => $withHolding,
            'vat' => $vat,
            'total' => $total,
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
        $paymentRequest = PaymentRequest::findOrFail($payment_request_id);
        $paymentRequest->finance_comment = $request->finance_comment;
        $paymentRequest->save();
        return redirect()->back()->with('success', 'Finance comment added successfully.');
    }
}