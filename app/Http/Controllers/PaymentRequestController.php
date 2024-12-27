<?php

namespace App\Http\Controllers;

use App\Models\PaymentRequest;
use App\Models\User; // Import User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'amount' => 'required|numeric',
            'description' => 'required|string',
            'comment' => 'nullable|string',
        ]);

        // Get the currently logged-in user
        $employee = Auth::user();

        // Retrieve the manager associated with the employee's company
        $manager = User::where('company_id', $employee->company_id)
                       ->where('role', 'manager')
                       ->first();

        if (!$manager) {
            return redirect()->route('payment-requests.index')->with('error', 'No manager found for your company.');
        }

        PaymentRequest::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'description' => $request->description,
            'status' => 'pending', // Default status
            'comment' => $request->comment,
            'approved_by' => $manager->user_id, // Set approved_by to manager's ID
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
            'status' => 'nullable|string',
            'comment' => 'nullable|string',
        ]);

        $paymentRequest->update($request->only([
            'amount', 
            'description', 
            'status', 
            'comment', 
            'approved_by'
        ]));

        return redirect()->route('payment-requests.index')->with('success', 'Payment request updated successfully.');
    }

    public function destroy(PaymentRequest $paymentRequest)
    {
        $paymentRequest->delete();
        return redirect()->route('payment-requests.index')->with('success', 'Payment request deleted successfully.');
    }

    public function approve(PaymentRequest $paymentRequest)
    {
        $manager = Auth::user();
        if ($manager->role !== 'manager') {
            return redirect()->route('payment-requests.index')->with('error', 'You are not authorized to approve payment requests.');
        }

        $paymentRequest->update([
            'status' => 'approved',
            'approved_by' => $manager->user_id,
        ]);

        return redirect()->route('payment-requests.index')->with('success', 'Payment request approved successfully.');
    }
}