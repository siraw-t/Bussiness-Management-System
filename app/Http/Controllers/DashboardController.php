<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\StockRequisition;
use App\Models\User;
use App\Models\LeaveRequest; // Leave requests model
use App\Models\PaymentRequest; // Payment requests model
use App\Models\PurchaseRequest; // Purchase requests model
use App\Models\Inventory; // Inventory model
use App\Models\BudgetForecasting; // Budget forecasting model
use App\Models\Payroll; // Payroll model
use App\Models\EmployeePlan; // Employee plan model
use App\Models\EmployeeReport; // Employee report model
use App\Models\ArchivedDocument; // Archived documents model
use App\Models\RequestFormTemplate; // Request form templates model
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;



class DashboardController extends Controller
{
    public function index()
    {
        // Redirect to the appropriate dashboard based on the user's role
        if (Auth::check()) {
            return redirect()->route(Auth::user()->role . '.dashboard');
        }

        return redirect('/login'); // Redirect to login if not authenticated
    }

    public function superadminDashboard()
    {
        if (!Auth::check() || Auth::user()->role !== 'superadmin') {
            return redirect('/login');
        }

        $totalCompanies = Company::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $admins = User::where('role', 'admin')->get();
        $recentCompanies = Company::with('admin')->orderBy('created_at', 'desc')->take(5)->get();
        
        return view('dashboards.superadmin', compact('totalCompanies', 'totalAdmins', 'admins', 'recentCompanies'));
    }

    public function displayAdmins()
{
    if (!Auth::check() || Auth::user()->role !== 'superadmin') {
        return redirect('/login');
    }

    $admins = User::where('role', 'admin')->get(); // Fetch admin users

    return view('display.admin', compact('admins')); // Return the view for admin users
}


public function adminDashboard()
{
    if (!Auth::check() || Auth::user()->role !== 'admin') {
        return redirect('/login');
    }

    $companyId = Auth::user()->company_id;
    $totalUsers = User::where('company_id', $companyId)
        ->whereIn('role', ['admin', 'employee','finance','manager','storekeeper']) // Only count admin and user roles
        ->count();
        
    $users = User::where('company_id', $companyId)
        ->whereIn('role', ['admin', 'employee','finance','manager','storekeeper']) // Retrieve only admin and user roles
        ->get();

    return view('dashboards.admin', compact('totalUsers', 'users'));
}

public function displayUsers()
{
    if (!Auth::check() || Auth::user()->role !== 'admin') {
        return redirect('/login');
    }

    $companyId = Auth::user()->company_id; // Get the company ID of the logged-in admin

    // Fetch all users for the company, regardless of their role
    $users = User::where('company_id', $companyId)->get();

    $totalUsers = User::where('company_id', $companyId)
        ->whereIn('role', ['admin', 'employee','finance','manager','storekeeper']) // Only count admin and user roles
        ->count();


    return view('display.users', compact('users','totalUsers')); // Return the view for company users
}

public function displayForms()
    {
        $templates = RequestFormTemplate::all(); // Fetch all templates
        return view('display.forms', compact('templates')); // Adjust the view name accordingly
    }

    public function employeeDashboard()
    {
        $userId = Auth::id(); // Get the logged-in employee's user ID
    
        // Fetch leave, payment, and purchase requests
        $leaveRequests = LeaveRequest::where('user_id', $userId)->get();
        $paymentRequests = PaymentRequest::where('user_id', $userId)->get();
        $purchaseRequests = PurchaseRequest::where('user_id', $userId)->get();
        $weeklyPlans = EmployeePlan::where('employee_id', $userId)->get();
        $reports = EmployeeReport::where('employee_id', $userId)->get();
    
        // Get counts for leave requests
        $leaveCounts = $leaveRequests->groupBy('status')->map->count()->toArray();
    
        // Get counts for payment requests
        $paymentCounts = $paymentRequests->groupBy('status')->map->count()->toArray();
    
        // Get counts for purchase requests
        $purchaseCounts = $purchaseRequests->groupBy('status')->map->count()->toArray();
    
        // Get counts for weekly plans
        $planCounts = $weeklyPlans->groupBy('status')->map->count()->toArray();
    
        // Get counts for weekly reports
        $reportCounts = $reports->groupBy('status')->map->count()->toArray();
    
        return view('dashboards.employee', compact(
            'leaveCounts',
            'paymentCounts',
            'purchaseCounts',
            'planCounts',
            'reportCounts'
        ));
    }

    public function storekeeperDashboard()
    {
        if (!Auth::check() || Auth::user()->role !== 'storekeeper') {
            return redirect('/login');
        }

        $inventories = Inventory::all(); // Fetch inventories
        return view('dashboards.storekeeper', compact('inventories'));
    }

    public function financeDashboard()
    {
        $inventories = Inventory::all(); // Fetch all inventories
        $leaveCounts = LeaveRequest::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');
        
        $paymentCounts = PaymentRequest::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');
        
        $purchaseCounts = PurchaseRequest::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');
        

           

        return view('dashboards.finance', compact('inventories', 'leaveCounts', 'paymentCounts', 'purchaseCounts'));
    }

     public function showPendingRequests()
            {
                // Fetch all pending purchase requests
                $pendingPurchaseRequests = PurchaseRequest::where('status', 'pending')
                    ->with('user') // Load the user relationship
                    ->get();
            
                return view('display.finance-pending', compact('pendingPurchaseRequests'));
            }

            public function showPendingPaymentRequests()
    {
        // Fetch all pending payment requests
        $pendingPaymentRequests = PaymentRequest::where('status', 'pending')
            ->with('user') // Load the user relationship
            ->get();
        
        return view('display.payment-pending', compact('pendingPaymentRequests'));
    }

    public function managerDashboard()
    {

          

        $managerId = Auth::id(); // Get the logged-in manager's user ID
        $inventories = Inventory::all();

        // Get counts for leave requests
    $leaveCounts = LeaveRequest::selectRaw('status, count(*) as count')
    ->where('approved_by', $managerId)
    ->groupBy('status')
    ->pluck('count', 'status')
    ->toArray();

// Get counts for payment requests
$paymentCounts = PaymentRequest::selectRaw('status, count(*) as count')
    ->where('approved_by', $managerId)
    ->groupBy('status')
    ->pluck('count', 'status')
    ->toArray();

// Get counts for purchase requests
$purchaseCounts = PurchaseRequest::selectRaw('status, count(*) as count')
    ->where('approved_by', $managerId)
    ->groupBy('status')
    ->pluck('count', 'status')
    ->toArray();

// Get counts for weekly plans
$planCounts = EmployeePlan::selectRaw('DATE(submitted_at) as submitted_date, count(*) as count')
    ->where('approved_by', $managerId)
    ->groupBy('submitted_date')
    ->pluck('count', 'submitted_date')
    ->toArray();

// Get counts for weekly reports
$reportCounts = EmployeeReport::selectRaw('DATE(submitted_at) as submitted_date, count(*) as count')
    ->where('approved_by', $managerId)
    ->groupBy('submitted_date')
    ->pluck('count', 'submitted_date')
    ->toArray();

// Initialize an array to hold all pending counts
$pendingCounts = [
    'leave_requests' => LeaveRequest::where('approved_by', $managerId)
        ->where('status', 'pending')
        ->count(),

    'payment_requests' => PaymentRequest::where('approved_by', $managerId)
        ->where('status', 'pending')
        ->count(),

    'purchase_requests' => PurchaseRequest::where('approved_by', $managerId)
        ->where('status', 'pending')
        ->count(),

    'weekly_plans' => EmployeePlan::where('approved_by', $managerId)
        ->where('status', 'pending')
        ->count(),

    'weekly_reports' => EmployeeReport::where('approved_by', $managerId)
        ->where('status', 'pending')
        ->count(),
];

return view('dashboards.manager', compact(
    'inventories',
    'leaveCounts',
    'paymentCounts',
    'purchaseCounts',
    'planCounts',
    'reportCounts',
    'pendingCounts'
));


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


        return view('dashboards.manager', compact(
            'inventories',
            'pendingLeaveRequests', 
            'pendingStockRequisitionRequests',
            'pendingPaymentRequests', 
            'pendingPurchaseRequests', 
            'pendingPayrolls',
            'pendingEmployeePlans',
            'pendingEmployeeReports',
            'pendingBudgetForecasts'


        ));
    }

    public function approveLeaveRequest(Request $request, $leave_request_id)
{
    $request->validate([
        'manager_comments' => 'required|string|max:255',
    ]);

    $leaveRequest = LeaveRequest::where('leave_request_id', $leave_request_id)
        ->where('approved_by', Auth::id())
        ->firstOrFail();

    $leaveRequest->status = 'approved';
    $leaveRequest->approved_by = Auth::id();
    $leaveRequest->manager_comments = $request->manager_comments; // Save comments
    $leaveRequest->save();

    return redirect()->back()->with('success', 'Leave request approved successfully.');
}

public function rejectLeaveRequest(Request $request, $leave_request_id)
{
    $request->validate([
        'manager_comments' => 'required|string|max:255',
    ]);

    $leaveRequest = LeaveRequest::where('leave_request_id', $leave_request_id)
        ->where('approved_by', Auth::id())
        ->firstOrFail();

    $leaveRequest->status = 'rejected';
    $leaveRequest->approved_by = Auth::id();
    $leaveRequest->manager_comments = $request->manager_comments; // Save comments
    $leaveRequest->save();

    return redirect()->back()->with('success', 'Leave request rejected successfully.');
}


public function approvePaymentRequest(Request $request, $payment_request_id)
{
    $request->validate([
        'manager_comment' => 'required|string|max:255', // Validate manager comment
    ]);

    $paymentRequest = PaymentRequest::where('payment_request_id', $payment_request_id)
        ->where('status', 'pending') // Check if status is pending
        ->firstOrFail();

    $paymentRequest->status = 'approved';
    $paymentRequest->approved_by = Auth::id();
    $paymentRequest->manager_comment = $request->manager_comment; // Save manager comment
    $paymentRequest->save();

    return redirect()->back()->with('success', 'Payment request approved successfully.');
}

public function rejectPaymentRequest(Request $request, $payment_request_id)
{
    $request->validate([
        'manager_comment' => 'required|string|max:255', // Validate manager comment
    ]);

    $paymentRequest = PaymentRequest::where('payment_request_id', $payment_request_id)
        ->where('status', 'pending') // Ensure it hasn't been approved yet
        ->firstOrFail();

    $paymentRequest->status = 'rejected';
    $paymentRequest->approved_by = Auth::id();
    $paymentRequest->manager_comment = $request->manager_comment; // Save comments
    $paymentRequest->save();

    return redirect()->back()->with('success', 'Payment request rejected successfully.');
}

public function approvePurchaseRequest(Request $request, $purchase_request_id)
{
    $request->validate([
        'manager_comment' => 'required|string|max:255',
    ]);

    // Find the purchase request by ID
    $purchaseRequest = PurchaseRequest::findOrFail($purchase_request_id);

    // Ensure the status is 'pending'
    if ($purchaseRequest->status !== 'pending') {
        return redirect()->back()->with('error', 'Purchase request is not pending.');
    }

    // Update the purchase request
    $purchaseRequest->status = 'approved';
    $purchaseRequest->approved_by = Auth::id();
    $purchaseRequest->manager_comment = $request->manager_comment;
    $purchaseRequest->save();

    return redirect()->back()->with('success', 'Purchase request approved successfully.');
}

public function rejectPurchaseRequest(Request $request, $purchase_request_id)
{
    $request->validate([
        'manager_comment' => 'required|string|max:255',
    ]);

    // Find the purchase request by ID
    $purchaseRequest = PurchaseRequest::findOrFail($purchase_request_id);

    // Ensure the status is 'pending'
    if ($purchaseRequest->status !== 'pending') {
        return redirect()->back()->with('error', 'Purchase request is not pending.');
    }

    // Update the purchase request
    $purchaseRequest->status = 'rejected';
    $purchaseRequest->approved_by = Auth::id();
    $purchaseRequest->manager_comment = $request->manager_comment; // Save comments
    $purchaseRequest->save();

    return redirect()->back()->with('success', 'Purchase request rejected successfully.');
}

public function approveEmployeePlan(Request $request, $plan_id)
{
    $request->validate([
        'manager_comments' => 'required|string|max:255',
    ]);

    // Fetch the employee plan that matches the given ID and is approved by the current manager
    $employeePlan = EmployeePlan::where('plan_id', $plan_id) // Replace 'plan_id' with the actual primary key if different
        ->where('approved_by', Auth::id())
        ->firstOrFail();

    // Update the employee plan status and details
    $employeePlan->status = 'approved';
    $employeePlan->approved_by = Auth::id(); // Set the ID of the approving manager
    $employeePlan->manager_comment = $request->manager_comments; // Save comments
    $employeePlan->save(); // Save the changes

    return redirect()->back()->with('success', 'Employee plan approved successfully.');
}

public function rejectEmployeePlan(Request $request, $plan_id)
{
    $request->validate([
        'manager_comments' => 'required|string|max:255',
    ]);

    // Fetch the employee plan that matches the given ID and is approved by the current manager
    $employeePlan = EmployeePlan::where('plan_id', $plan_id) // Replace 'plan_id' with the actual primary key if different
        ->where('approved_by', Auth::id())
        ->firstOrFail();

    // Update the employee plan status and details
    $employeePlan->status = 'rejected';
    $employeePlan->approved_by = Auth::id(); // Set the ID of the rejecting manager
    $employeePlan->manager_comment = $request->manager_comments; // Save comments
    $employeePlan->save(); // Save the changes

    return redirect()->back()->with('success', 'Employee plan rejected successfully.');
}

public function approveEmployeeReport(Request $request, $report_id)
{
    $request->validate([
        'manager_comments' => 'required|string|max:255',
    ]);

    // Fetch the employee report that matches the given ID
    $employeeReport = EmployeeReport::where('report_id', $report_id) // Replace 'id' with the actual primary key if different
        ->where('status', 'pending') // Ensure it's in a pending state before approving
        ->firstOrFail();

    // Update the employee report status and details
    $employeeReport->status = 'approved';
    $employeeReport->manager_comment = $request->manager_comments; // Save comments
    $employeeReport->approved_by = Auth::id(); // Optional: Set the ID of the approving manager
    $employeeReport->save(); // Save the changes

    return redirect()->route('display.pending')->with('success', 'Employee report approved successfully.');
}

public function rejectEmployeeReport(Request $request, $report_id)
{
    $request->validate([
        'manager_comments' => 'required|string|max:255',
    ]);

    // Fetch the employee report that matches the given ID
    $employeeReport = EmployeeReport::where('report_id', $report_id) // Replace 'id' with the actual primary key if different
        ->where('status', 'pending') // Ensure it's in a pending state before rejecting
        ->firstOrFail();

    // Update the employee report status and details
    $employeeReport->status = 'rejected';
    $employeeReport->manager_comment = $request->manager_comments; // Save comments
    $employeeReport->approved_by = Auth::id(); // Optional: Set the ID of the rejecting manager
    $employeeReport->save(); // Save the changes

    return redirect()->back()->with('success', 'Employee report rejected successfully.');
}

public function approvePayroll(Request $request, $payroll_id)
{
    $request->validate([
        'manager_comment' => 'required|string|max:255',
    ]);

    $payroll = Payroll::where('payroll_id', $payroll_id)
        ->where('approved_by', Auth::id())
        ->firstOrFail();

    $payroll->status = 'approved';
    $payroll->approved_by = Auth::id();
    $payroll->manager_comment = $request->manager_comment;
    $payroll->save();

    return redirect()->back()->with('success', 'Payroll approved successfully.');
}

public function rejectPayroll(Request $request, $payroll_id)
{
    $request->validate([
        'manager_comment' => 'required|string|max:255',
    ]);

    $payroll = Payroll::where('payroll_id', $payroll_id)
        ->where('approved_by', Auth::id())
        ->firstOrFail();

    $payroll->status = 'rejected';
    $payroll->approved_by = Auth::id();
    $payroll->manager_comment = $request->manager_comment;
    $payroll->save();

    return redirect()->back()->with('success', 'Payroll rejected successfully.');
}

public function approveBudget(Request $request, $budget_id)
{
    $request->validate([
        'manager_comment' => 'required|string|max:255',
    ]);

    $budget = BudgetForecasting::where('budget_id', $budget_id) // Correct primary key
        ->where('status', 'pending') // Ensure the budget is pending before approving
        ->firstOrFail();

    $budget->status = 'approved';
    $budget->approved_by = Auth::id();
    $budget->manager_comment = $request->manager_comment; // Save manager comment
    $budget->save();

    return redirect()->back()->with('success', 'Budget approved successfully.');
}

public function rejectBudget(Request $request, $budget_id)
{
    $request->validate([
        'manager_comment' => 'required|string|max:255',
    ]);

    $budget = BudgetForecasting::where('budget_id', $budget_id) // Correct primary key
        ->where('status', 'pending') // Ensure the budget is pending before rejecting
        ->firstOrFail();

    $budget->status = 'rejected';
    $budget->approved_by = Auth::id();
    $budget->manager_comment = $request->manager_comment; // Save manager comment
    $budget->save();

    return redirect()->back()->with('success', 'Budget rejected successfully.');
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



}