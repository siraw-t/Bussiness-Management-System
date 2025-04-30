<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\StockRequisition;
use App\Models\EmployeePlan;
use App\Models\EmployeeReport;
use App\Models\PaymentRequest;
use App\Models\PurchaseRequest;
use App\Models\Inventory;
use App\Models\Payroll;
use App\Models\BudgetForecasting;
use App\Models\ArchivedDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PettyCashRequest;


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

         // Get pending petty cash requests approved by this manager
         $pendingPettyCashRequests = PettyCashRequest::where('status', 'pending')
         ->where('approved_by', $managerId)
         ->with('user')
         ->get();
 

        // Get pending payrolls approved by this manager
        $pendingPayrolls = Payroll::where('status', 'pending')
            ->where('approved_by', $managerId)
            ->with(['employee', 'preparedBy'])
            ->get();

        // Get pending payrolls approved by this manager
        $pendingBudgetForecasts = BudgetForecasting::where('status', 'pending')
            ->where('approved_by', $managerId)
            ->with('user')
            ->get();

        // Get pending employee plans approved by this manager
    $pendingEmployeePlans = EmployeePlan::where('status', 'pending')
    ->where('approved_by', $managerId)
    ->with('employee') // Load the employee relationship if needed
    ->get();

    // Get pending employee plans approved by this manager
    $pendingEmployeeReports = EmployeeReport::where('status', 'pending')
    ->where('approved_by', $managerId)
    ->with('employee') // Load the employee relationship if needed
    ->get();

    $pendingStockRequisitionRequests = StockRequisition::where('status', 'pending')
    ->where('approved_by', $managerId)
    ->with('user') // Load the user relationship if needed
    ->get();

        return view('display.pending', compact(
            'pendingLeaveRequests', 
            'pendingStockRequisitionRequests',
            'pendingPaymentRequests', 
            'pendingPurchaseRequests', 
            'pendingPayrolls',
            'pendingEmployeePlans',
            'pendingEmployeeReports',
            'pendingBudgetForecasts',
            'pendingPettyCashRequests'
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

public function approveBudget($budget_id)
{
    $budget = BudgetForecasting::where('budget_id', $budget_id) // Correct primary key
        ->where('status', 'pending') // Ensure the budget is pending before approving
        ->firstOrFail();

    $budget->status = 'approved';
    $budget->approved_by = Auth::id(); 
    $budget->save();

    return redirect()->back()->with('success', 'Budget approved successfully.');
}

public function rejectBudget($budget_id)
{
    $budget = BudgetForecasting::where('budget_id', $budget_id) // Correct primary key
        ->where('status', 'pending') // Ensure the budget is pending before rejecting
        ->firstOrFail();

    $budget->status = 'rejected';
    $budget->approved_by = Auth::id(); 
    $budget->save();

    return redirect()->back()->with('success', 'Budget rejected.');
}

public function approveStockRequisition(Request $request, $stock_requisition_id)
{
    // Validate the request for manager comments and approved quantity
    $request->validate([
        'manager_comment' => 'required|string|max:255',
        'approved_quantity' => 'required|integer|min:1',
    ]);

    // Find the stock requisition
    $requisition = StockRequisition::where('stock_requisition_id', $stock_requisition_id)
        ->where('status', 'pending') // Ensure it's pending
        ->firstOrFail();

    // Update the requisition details
    $requisition->status = 'approved';
    $requisition->approved_by = Auth::id();
    $requisition->manager_comment = $request->manager_comment; // Save manager comments
    $requisition->quantity_approved = $request->approved_quantity; // Save approved quantity
    $requisition->save();

    // Optionally, add logic to update inventory here

    return redirect()->back()->with('success', 'Stock requisition approved successfully.');
}

    public function rejectStockRequisition(Request $request, $stock_requisition_id)
{
    // Validate the request for manager comments
    $request->validate([
        'manager_comment' => 'required|string|max:255',
    ]);

    // Find the stock requisition
    $requisition = StockRequisition::where('stock_requisition_id', $stock_requisition_id)
        ->where('status', 'pending') // Ensure it's pending
        ->firstOrFail();

    // Update the requisition details
    $requisition->status = 'rejected';
    $requisition->approved_by = Auth::id();
    $requisition->manager_comment = $request->manager_comment; // Save manager comments
    $requisition->save();

    return redirect()->back()->with('success', 'Stock requisition rejected successfully.');
}

public function approvePettyCash(Request $request, $petty_cash_request_id)
{
    // Validate the request for manager comments and approved amounts
    $request->validate([
        'manager_comment' => 'required|string|max:255',
        'amount_approved' => 'required|numeric', // Ensure the approved amount is validated
        'amount_approved_in_words' => 'required|string|max:255', // Validate the words for the approved amount
    ]);

    // Find the petty cash request
    $pettyCashRequest = PettyCashRequest::where('petty_cash_request_id', $petty_cash_request_id)
        ->where('status', 'pending') // Ensure it's pending
        ->firstOrFail();

    // Update the petty cash request details
    $pettyCashRequest->status = 'approved';
    $pettyCashRequest->approved_by = Auth::id();
    $pettyCashRequest->manager_comment = $request->manager_comment; // Save manager comments
    $pettyCashRequest->amount_approved = $request->amount_approved; // Store the approved amount from the request
    $pettyCashRequest->amount_approved_in_words = $request->amount_approved_in_words; // Store the approved amount in words
    $pettyCashRequest->save();

    return redirect()->back()->with('success', 'Petty cash request approved successfully.');
}

public function rejectPettyCash(Request $request, $petty_cash_request_id)
{
    // Validate the request for manager comments
    $request->validate([
        'manager_comment' => 'required|string|max:255',
    ]);

    // Find the petty cash request
    $pettyCashRequest = PettyCashRequest::where('petty_cash_request_id', $petty_cash_request_id)
        ->where('status', 'pending') // Ensure it's pending
        ->firstOrFail();

    // Update the petty cash request details
    $pettyCashRequest->status = 'rejected';
    $pettyCashRequest->approved_by = Auth::id();
    $pettyCashRequest->manager_comment = $request->manager_comment; // Save manager comments
    $pettyCashRequest->save();

    return redirect()->back()->with('success', 'Petty cash request rejected successfully.');
}


}