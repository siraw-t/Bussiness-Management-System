<?php

namespace App\Http\Controllers;

use App\Models\Company;
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

    public function employeeDashboard()
{
    $userId = Auth::id();

    // Fetch leave, payment, and purchase requests
    $leaveRequests = LeaveRequest::where('user_id', $userId)->get();
    $paymentRequests = PaymentRequest::where('user_id', $userId)->get();
    $purchaseRequests = PurchaseRequest::where('user_id', $userId)->get();
    $weeklyPlans = EmployeePlan::where('employee_id', $userId)->get();
    $reports = EmployeeReport::where('employee_id', $userId)->get();

    // Aggregate data for the charts
    $leaveCounts = $leaveRequests->groupBy('status')->map->count();
    $paymentCounts = $paymentRequests->groupBy('status')->map->count();
    $purchaseCounts = $purchaseRequests->groupBy('status')->map->count();
    $planCounts = $weeklyPlans->groupBy('status')->map->count();
    $reportCounts = $reports->groupBy('status')->map->count();

    return view('dashboards.employee', compact('leaveCounts', 'paymentCounts', 'purchaseCounts', 'planCounts', 'reportCounts'));
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

    public function managerDashboard()
    {
        $managerId = Auth::id(); // Get the logged-in manager's user ID
        $inventories = Inventory::all();
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


        return view('dashboards.manager', compact(
            'inventories',
            'pendingLeaveRequests', 
            'pendingPaymentRequests', 
            'pendingPurchaseRequests', 
            'pendingPayrolls',
            'pendingEmployeePlans',
            'pendingEmployeeReports'

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

public function approvePurchaseRequest(Request $request, $purchase_request_id)
{
    $request->validate([
        'manager_comment' => 'required|string|max:255',
    ]);

    $purchaseRequest = PurchaseRequest::where('purchase_request_id', $purchase_request_id)
        ->where('status', 'pending') // Check if status is pending
        ->firstOrFail();

    $purchaseRequest->status = 'approved';
    $purchaseRequest->approved_by = Auth::id();
    $purchaseRequest->manager_comment = $request->manager_comment;
    $purchaseRequest->save();

    return redirect()->back()->with('success', 'Purchase request approved successfully.');
}

public function rejectPurchaseRequest(Request $request, $purchase_request_id)
{
    $request->validate([
        'manager_comments' => 'required|string|max:255',
    ]);

    $purchaseRequest = PurchaseRequest::where('purchase_request_id', $purchase_request_id)
        ->where('approved_by', 'pending') // Ensure it hasn't been approved yet
        ->firstOrFail();

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

    return redirect()->back()->with('success', 'Employee report approved successfully.');
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
}