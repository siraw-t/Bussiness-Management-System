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

        return view('dashboards.manager', compact(
            'inventories',
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