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
            'pendingPaymentRequests', 
            'pendingPurchaseRequests', 
            'pendingPayrolls',
            'pendingEmployeePlans',
            'pendingEmployeeReports',
            'pendingBudgetForecasts',
            'pendingStockRequisitionRequests'
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

public function approveStockRequisition($stock_requisition_id)
    {
        // Find the stock requisition with the given ID
        $requisition = StockRequisition::where('stock_requisition_id', $stock_requisition_id)
            ->where('status', 'pending') // Ensure it's pending
            ->firstOrFail();

        // Update the requisition status and approver details
        $requisition->status = 'approved';
        $requisition->approved_by = Auth::id(); 
        $requisition->save();

        // Optionally, you can add logic to update inventory here

        return redirect()->back()->with('success', 'Stock requisition approved successfully.');
    }

    public function rejectStockRequisition($stock_requisition_id)
    {
        // Find the stock requisition with the given ID
        $requisition = StockRequisition::where('stock_requisition_id', $stock_requisition_id)
            ->where('status', 'pending') // Ensure it's pending
            ->firstOrFail();

        // Update the requisition status and approver details
        $requisition->status = 'rejected';
        $requisition->approved_by = Auth::id(); 
        $requisition->save();

        return redirect()->back()->with('success', 'Stock requisition rejected successfully.');
    }


}