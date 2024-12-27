<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\PaymentRequest;
use App\Models\PurchaseRequest;
use App\Models\Inventory;
use App\Models\Payroll;
use App\Models\BudgetForecasting;
use App\Models\ArchivedDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerController extends Controller
{
    // Method to view the manager dashboard
    public function managerDashboard()
    {
        $managerId = Auth::id(); // Get the logged-in manager's user ID

        // Get pending leave requests approved by this manager
        $pendingLeaveRequests = LeaveRequest::where('status', 'pending')
            ->where('approved_by', $managerId)
            ->with('user')
            ->get();

        // Get pending payment requests approved by this manager
        $pendingPaymentRequests = PaymentRequest::where('status', 'pending')
            ->where('approved_by', $managerId)
            ->with('user')
            ->get();

        // Get pending purchase requests approved by this manager
        $pendingPurchaseRequests = PurchaseRequest::where('status', 'pending')
            ->where('approved_by', $managerId)
            ->with('user')
            ->get();

        // Get pending payrolls approved by this manager
        $pendingPayrolls = Payroll::where('status', 'pending')
            ->where('approved_by', $managerId)
            ->with(['employee', 'preparedBy'])
            ->get();

        return view('display.pending', compact(
            'pendingLeaveRequests', 
            'pendingPaymentRequests', 
            'pendingPurchaseRequests', 
            'pendingPayrolls'
        ));
    }

    public function approveLeaveRequest($leave_request_id)
{
    $request = LeaveRequest::where('leave_request_id', $leave_request_id) // Use the correct primary key
        ->where('approved_by', Auth::id()) // Check if the logged-in user is the approver
        ->firstOrFail();

    $request->status = 'approved';
    $request->approved_by = Auth::id(); 
    $request->save();

    return redirect()->back()->with('success', 'Leave request approved successfully.');
}

public function rejectLeaveRequest($leave_request_id)
{
    $request = LeaveRequest::where('leave_request_id', $leave_request_id) // Use the correct primary key
        ->where('approved_by', Auth::id())
        ->firstOrFail();

    $request->status = 'rejected';
    $request->approved_by = Auth::id(); 
    $request->save();

    return redirect()->back()->with('success', 'Leave request rejected.');
}

// Similar changes for PaymentRequest, PurchaseRequest, and Payroll methods
public function approvePaymentRequest($payment_request_id)
{
    $request = PaymentRequest::where('payment_request_id', $payment_request_id) // Correct primary key
        ->where('approved_by', Auth::id())
        ->firstOrFail();

    $request->status = 'approved';
    $request->approved_by = Auth::id(); 
    $request->save();

    return redirect()->back()->with('success', 'Payment request approved successfully.');
}

public function rejectPaymentRequest($payment_request_id)
{
    $request = PaymentRequest::where('payment_request_id', $payment_request_id) // Correct primary key
        ->where('approved_by', Auth::id())
        ->firstOrFail();

    $request->status = 'rejected';
    $request->approved_by = Auth::id(); 
    $request->save();

    return redirect()->back()->with('success', 'Payment request rejected.');
}

public function approvePurchaseRequest($purchase_request_id)
{
    $request = PurchaseRequest::where('purchase_request_id', $purchase_request_id) // Correct primary key
        ->where('approved_by', Auth::id())
        ->firstOrFail();

    $request->status = 'approved';
    $request->approved_by = Auth::id(); 
    $request->save();

    return redirect()->back()->with('success', 'Purchase request approved successfully.');
}

public function rejectPurchaseRequest($purchase_request_id)
{
    $request = PurchaseRequest::where('purchase_request_id', $purchase_request_id) // Correct primary key
        ->where('approved_by', Auth::id())
        ->firstOrFail();

    $request->status = 'rejected';
    $request->approved_by = Auth::id(); 
    $request->save();

    return redirect()->back()->with('success', 'Purchase request rejected.');
}

public function approvePayroll($payroll_id)
{
    $payroll = Payroll::where('payroll_id', $payroll_id) // Correct primary key
        ->where('approved_by', Auth::id())
        ->firstOrFail();

    $payroll->status = 'approved';
    $payroll->approved_by = Auth::id(); 
    $payroll->save();

    return redirect()->back()->with('success', 'Payroll approved successfully.');
}

public function rejectPayroll($payroll_id)
{
    $payroll = Payroll::where('payroll_id', $payroll_id) // Correct primary key
        ->where('approved_by', Auth::id())
        ->firstOrFail();

    $payroll->status = 'rejected';
    $payroll->approved_by = Auth::id(); 
    $payroll->save();

    return redirect()->back()->with('success', 'Payroll rejected.');
}
}