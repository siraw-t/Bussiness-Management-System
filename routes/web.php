<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\PaymentRequestController;
use App\Http\Controllers\EmployeePlanController;
use App\Http\Controllers\EmployeeReportController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\FinanceController;
use App\Models\Inventory; // Import the Inventory model
use App\Http\Controllers\ArchivedDocumentController;
use App\Models\EmployeePlan;
use App\Models\EmployeeReport;
use App\Models\BudgetForecasting;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\StockRequisitionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\RequestFormTemplateController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PettyCashRequestController;



// Public routes
Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::put('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

    // Optional: Route to mark all notifications as read
    Route::put('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

});


// Authentication routes
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

Route::get('/profile', function () {
    // Only verified users may access this route...
})->middleware(['auth', 'verified']);




// Password Reset Routes
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');


// Protected routes
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');



// Role-specific Dashboard Routes
Route::get('/dashboards/superadmin', function () {
    return auth()->check() && auth()->user()->role === 'superadmin'
        ? app(DashboardController::class)->superadminDashboard()
        : redirect('/login');
})->name('superadmin.dashboard');

Route::get('/dashboards/admin', function () {
    return auth()->check() && auth()->user()->role === 'admin'
        ? app(DashboardController::class)->adminDashboard()
        : redirect('/login');
})->name('admin.dashboard');

Route::get('/dashboards/employee', function () {
    return auth()->check() && auth()->user()->role === 'employee'
        ? app(DashboardController::class)->employeeDashboard()
        : redirect('/login');
})->name('employee.dashboard');



// Leave Requests Routes
Route::resource('leave-requests', LeaveRequestController::class);

// Payment Requests Routes
Route::resource('payment-requests', PaymentRequestController::class);

// Purchase Requests Routes
Route::resource('purchase-requests', PurchaseRequestController::class);

         // Petty Cash Requests Resource
Route::resource('petty_cash_requests', PettyCashRequestController::class);

// Show Pending Petty Cash Requests
Route::get('/finance/petty_cash_requests/pending', [DashboardController::class, 'showPendingPettyCashRequests'])
    ->name('display.pending_petty_cash');

// Approve Petty Cash Request
Route::post('/manager/petty_cash_requests/{petty_cash_request_id}/approve', [DashboardController::class, 'approvePettyCash'])
    ->name('manager.petty_cash.approve');

// Reject Petty Cash Request
Route::post('/manager/petty_cash_requests/{petty_cash_request_id}/reject', [DashboardController::class, 'rejectPettyCash'])
    ->name('manager.petty_cash.reject');

// Check Petty Cash Request
Route::post('/finance/petty_cash_requests/{pettyCashRequest}/check', [PettyCashRequestController::class, 'check'])
    ->name('petty_cash_requests.check');
Route::post('/finance/petty_cash_requests/comment/{petty_cash_request_id}', [PettyCashRequestController::class, 'addFinanceComment'])->name('finance.petty_cash.comment');
Route::post('/finance/petty_cash_requests/attach/{petty_cash_request_id}', [PettyCashRequestController::class, 'addFinanceCheque'])->name('finance.petty_cash.attach');




Route::prefix('weekly-plan')->group(function () {
    Route::get('/', [EmployeePlanController::class, 'index'])->name('weekly-plan.index'); // List all weekly plans
    Route::get('/create', [EmployeePlanController::class, 'create'])->name('weekly-plan.create'); // Show form to create a plan
    Route::post('/', [EmployeePlanController::class, 'store'])->name('weekly-plan.store'); // Store a new plan
    Route::get('/{weeklyPlan}/edit', [EmployeePlanController::class, 'edit'])->name('weekly-plan.edit'); // Show form to edit a plan
    Route::put('/{weeklyPlan}', [EmployeePlanController::class, 'update'])->name('weekly-plan.update'); // Update the plan
    Route::delete('/{weeklyPlan}', [EmployeePlanController::class, 'destroy'])->name('weekly-plan.destroy'); // Delete the plan
});

Route::get('/employee-plans', [EmployeePlanController::class, 'showEmployeePlans'])->name('employee.plans');

Route::prefix('weekly-reports')->group(function () {
    Route::get('/', [EmployeeReportController::class, 'index'])->name('weekly-reports.index'); // List all weekly reports
    Route::get('/create', [EmployeeReportController::class, 'create'])->name('weekly-reports.create'); // Show form to create a report
    Route::post('/', [EmployeeReportController::class, 'store'])->name('weekly-reports.store'); // Store a new report
    Route::get('/{weeklyReport}/edit', [EmployeeReportController::class, 'edit'])->name('weekly-reports.edit'); // Show form to edit a report
    Route::put('/{weeklyReport}', [EmployeeReportController::class, 'update'])->name('weekly-reports.update'); // Update the report
    Route::delete('/{weeklyReport}', [EmployeeReportController::class, 'destroy'])->name('weekly-reports.destroy'); // Delete the report
});


Route::get('documents', [ArchivedDocumentController::class, 'index'])->name('documents.index');
Route::get('documents/create', [ArchivedDocumentController::class, 'create'])->name('documents.create');
Route::post('documents', [ArchivedDocumentController::class, 'store'])->name('documents.store');
Route::get('documents/{document}', [ArchivedDocumentController::class, 'show'])->name('documents.show');

Route::resource('documents', ArchivedDocumentController::class);
Route::get('documents/generate-pdf', [ArchivedDocumentController::class, 'generatePdfForUserAndCompany'])->name('documents.generatePdf');
Route::get('leave-requests', [LeaveRequestController::class, 'index'])->name('leave-requests.index');

Route::get('/dashboards/storekeeper', function () {
    return auth()->check() && auth()->user()->role === 'storekeeper'
        ? app(DashboardController::class)->storekeeperDashboard()
        : redirect('/login');
})->name('storekeeper.dashboard');

// Inventory Management Routes
Route::prefix('inventory')->group(function () {
    Route::get('/', [InventoryController::class, 'index'])->name('inventory.index'); // List all inventories
    Route::get('/create', [InventoryController::class, 'create'])->name('inventory.create'); // Show form to create an inventory item
    Route::post('/', [InventoryController::class, 'store'])->name('inventory.store'); // Store a new inventory item
    Route::get('/{inventory}/edit', [InventoryController::class, 'edit'])->name('inventory.edit'); // Show form to edit an inventory item
    Route::put('/{inventory}', [InventoryController::class, 'update'])->name('inventory.update'); // Update the inventory item
    Route::delete('/{inventory}', [InventoryController::class, 'destroy'])->name('inventory.destroy'); // Delete the inventory item
    Route::post('/inventory/{inventory}/stock-out', [InventoryController::class, 'stockOut'])->name('inventory.stockOut');
    Route::get('inventory/combined-summary', [InventoryController::class, 'combinedSummary'])->name('inventory.combinedSummary');
});


Route::prefix('stock_requisitions')->group(function () {
    Route::get('/', [StockRequisitionController::class, 'index'])->name('stock_requisitions.index'); // List all stock requisitions
    Route::get('/create', [StockRequisitionController::class, 'create'])->name('stock_requisitions.create'); // Show form to create a stock requisition
    Route::post('/', [StockRequisitionController::class, 'store'])->name('stock_requisitions.store'); // Store a new stock requisition
    Route::get('/{stockRequisition}/edit', [StockRequisitionController::class, 'edit'])->name('stock_requisitions.edit'); // Show form to edit a stock requisition
    Route::put('/{stockRequisition}', [StockRequisitionController::class, 'update'])->name('stock_requisitions.update'); // Update the stock requisition
    Route::delete('/{stockRequisition}', [StockRequisitionController::class, 'destroy'])->name('stock_requisitions.destroy'); // Delete the stock requisition

Route::post('/stock_requisitions/comment/{stock_requisition_id}', [StockRequisitionController::class, 'addEmployeeComment'])->name('stock_requisitions.comment');
});


Route::get('/dashboards/finance', function () {
    return auth()->check() && auth()->user()->role === 'finance'
        ? app(DashboardController::class)->financeDashboard()
        : redirect('/login');
})->name('finance.dashboard');

                // views

Route::get('/display/index', function () {
    $inventories = \App\Models\Inventory::with(['manager', 'approver'])->get(); // Fetch inventories with related users
    return view('display.index', compact('inventories'));
    })->name('display.index');

    Route::get('/display/leave', function () {
        $leaveRequests = \App\Models\LeaveRequest::with('user')->get(); // Fetch leave requests
        $employee = auth()->user();
        $country = $employee->company->country;
    
        // Set the annual leave limit based on the company country
        $annualLeaveLimit = ($country === 'Ethiopia') ? 16 : (($country === 'Uganda') ? 21 : 0);
    
        // Count approved annual leave days taken by the user
        $totalAnnualLeaveTaken = \App\Models\LeaveRequest::where('user_id', $employee->id)
            ->where('leave_type', 'annual_leave')
            ->where('status', 'approved')
            ->get()
            ->sum(function ($leave) {
                return (new \DateTime($leave->end_date))->diff(new \DateTime($leave->start_date))->days + 1;
            });
    
        // Calculate remaining leave days
        $remainingLeaveDays = $annualLeaveLimit - $totalAnnualLeaveTaken;
    
        return view('display.leave', compact('leaveRequests', 'remainingLeaveDays')); // Pass the data to the view
    })->name('display.leave');

Route::get('/display/payment', function () {
    $paymentRequests = \App\Models\PaymentRequest::with('user')->get(); // Fetch leave requests
    return view('display.payment', compact('paymentRequests')); // Pass the data to the view
})->name('display.payment');

Route::get('/display/purchase', function () {
    $purchaseRequests = \App\Models\PurchaseRequest::with('user')->get(); // Fetch leave requests
    return view('display.purchase', compact('purchaseRequests')); // Pass the data to the view
})->name('display.purchase');
Route::get('/display/petty_cash', function () {
    $pettyCashRequests = \App\Models\PettyCashRequest::with('user')->get(); // Fetch leave requests
    return view('display.petty_cash', compact('pettyCashRequests')); // Pass the data to the view
})->name('display.petty_cash');

Route::get('/display/plan', function () {
    $employeePlans = \App\Models\EmployeePlan::with('user')->get(); // Fetch employee plans
    return view('display.plan', compact('employeePlans')); // Pass the data to the view
})->name('display.plan');

// Route to display employee reports
Route::get('/display/report', function () {
    $employeeReports = \App\Models\EmployeeReport::with('user')->get(); // Fetch employee reports
    return view('display.report', compact('employeeReports')); // Pass the data to the view
})->name('display.report');

Route::get('/display/payroll', function () {
    $payrolls = \App\Models\Payroll::with('user')->get();
    return view('display.payroll', compact('payrolls'));
})->name('display.payroll');

// Route to display employee reports
Route::get('/display/budget', function () {
    $budgetForecasts = \App\Models\BudgetForecasting::with('user')->get();
    return view('display.budget', compact('budgetForecasts'));
})->name('display.budget');

Route::get('/display/stock-requisition', function () {
    $stockRequisitions = \App\Models\StockRequisition::with('user')->get();
    return view('display.stock-requisition', compact('stockRequisitions'));
})->name('display.stock-requisition');

               // all pendings

            //    Route::get('/pending-leave', [LeaveRequestController::class, 'showPendingRequests'])->name('display.pending-leave');

Route::get('/display/pending', [ManagerController::class, 'managerDashboard'])->name('display.pending');
Route::get('/display/admin', [DashboardController::class, 'displayAdmins'])->name('display.admin');
Route::get('/display/users', [DashboardController::class, 'displayUsers'])->name('display.users');
Route::get('/display/forms', [DashboardController::class, 'displayForms'])->name('display.forms');


// Budget Forecasting Routes
Route::prefix('finance/budgets')->name('finance.budgets.')->group(function () {
    Route::get('/', [FinanceController::class, 'indexBudget'])->name('index');
    Route::get('/create', [FinanceController::class, 'createBudget'])->name('create');
    Route::post('/', [FinanceController::class, 'storeBudget'])->name('store');
    Route::get('/{budget}/edit', [FinanceController::class, 'editBudget'])->name('edit');
    Route::put('/{budget}', [FinanceController::class, 'updateBudget'])->name('update');
    Route::delete('/{budget}', [FinanceController::class, 'destroyBudget'])->name('destroy');
});

// Payroll Management Routes
Route::prefix('finance/payrolls')->name('finance.payrolls.')->group(function () {
    Route::get('/', [FinanceController::class, 'indexPayroll'])->name('index');
    Route::get('/create', [FinanceController::class, 'createPayroll'])->name('create');
    Route::post('/', [FinanceController::class, 'storePayroll'])->name('store');
    Route::get('/{payroll}/edit', [FinanceController::class, 'editPayroll'])->name('edit');
    Route::put('/{payroll}', [FinanceController::class, 'updatePayroll'])->name('update');
    Route::delete('/{payroll}', [FinanceController::class, 'destroyPayroll'])->name('destroy');
});

Route::get('/dashboards/manager', function () {
    return auth()->check() && auth()->user()->role === 'manager'
        ? app(DashboardController::class)->managerDashboard()
        : redirect('/login');
})->name('manager.dashboard');

Route::get('/dashboards/manager', [DashboardController::class, 'managerDashboard'])->name('manager.dashboard');

Route::post('/manager/leave-request/{leave_request_id}/approve', [DashboardController::class, 'approveLeaveRequest'])->name('manager.leave.approve');
Route::post('/manager/leave-request/{leave_request_id}/reject', [DashboardController::class, 'rejectLeaveRequest'])->name('manager.leave.reject');
Route::get('/pending-requests', [LeaveRequestController::class, 'showPendingRequests'])->name('pending.requests');

Route::post('/manager/payment-request/{payment_request_id}/approve', [DashboardController::class, 'approvePaymentRequest'])->name('manager.payment.approve');
Route::post('/manager/payment-request/{payment_request_id}/reject', [DashboardController::class, 'rejectPaymentRequest'])->name('manager.payment.reject');
Route::post('/finance/payment-request/comment/{payment_request_id}', [PaymentRequestController::class, 'addFinanceComment'])->name('finance.payment.comment');
Route::get('/finance/payment-requests/pending', [DashboardController::class, 'showPendingPaymentRequests'])->name('display.payment-pending');

Route::post('/manager/purchase-request/{purchase_request_id}/approve', [DashboardController::class, 'approvePurchaseRequest'])->name('manager.purchase.approve');
Route::post('/manager/purchase-request/{purchase_request_id}/reject', [DashboardController::class, 'rejectPurchaseRequest'])->name('manager.purchase.reject');
Route::post('/finance/purchase-request/comment/{purchase_request_id}', [PurchaseRequestController::class, 'addFinanceComment'])->name('finance.purchase.comment');
Route::get('/finance/dashboard', [DashboardController::class, 'financeDashboard'])->name('finance.dashboard');
Route::get('/finance/purchase-requests/pending', [DashboardController::class, 'showPendingRequests'])->name('display.finance-pending');
// Route::get('/pending-requests', [PurchaseRequestController::class, 'showPendingRequests'])->name('pending.requests');

Route::post('/manager/weekly-plan/{plan_id}/approve', [DashboardController::class, 'approveEmployeePlan'])->name('manager.plan.approve');
Route::post('/manager/weekly-plan/{plan_id}/reject', [DashboardController::class, 'rejectEmployeePlan'])->name('manager.plan.reject');

Route::post('/manager/weekly-reports/{report_id}/approve', [DashboardController::class, 'approveEmployeeReport'])->name('manager.report.approve');
Route::post('/manager/weekly-reports/{report_id}/reject', [DashboardController::class, 'rejectEmployeeReport'])->name('manager.report.reject');

Route::post('/manager/payroll/{payroll_id}/approve', [DashboardController::class, 'approvePayroll'])->name('manager.payroll.approve');
Route::post('/manager/payroll/{payroll_id}/reject', [DashboardController::class, 'rejectPayroll'])->name('manager.payroll.reject');

Route::post('/manager/budget/{budget_id}/approve', [DashboardController::class, 'approveBudget'])->name('manager.budget.approve');
Route::post('/manager/budget/{budget_id}/reject', [DashboardController::class, 'rejectBudget'])->name('manager.budget.reject');

// Stock Requisition Routes
Route::post('/manager/stock_requisition/{stock_requisition_id}/approve', [DashboardController::class, 'approveStockRequisition'])->name('manager.stock_requisition.approve');
Route::post('/manager/stock_requisition/{stock_requisition_id}/reject', [DashboardController::class, 'rejectStockRequisition'])->name('manager.stock_requisition.reject');
Route::get('/stock-requisitions', [StockRequisitionController::class, 'showStockRequisitions'])->name('stock.requisitions');

// templates for admin

Route::get('/templates', [RequestFormTemplateController::class, 'index'])->name('templates.index'); // List all templates
Route::get('/templates/create', [RequestFormTemplateController::class, 'create'])->name('templates.create'); // Show form to create a new template
Route::post('/templates', [RequestFormTemplateController::class, 'store'])->name('templates.store'); // Store a new template
Route::get('/templates/{template}', [RequestFormTemplateController::class, 'show'])->name('templates.show'); // Show a specific template
Route::get('/templates/{template}/edit', [RequestFormTemplateController::class, 'edit'])->name('templates.edit'); // Show form to edit a template
Route::put('/templates/{template}', [RequestFormTemplateController::class, 'update'])->name('templates.update'); // Update a specific template
Route::delete('/templates/{template}', [RequestFormTemplateController::class, 'destroy'])->name('templates.destroy'); // Delete a specific template


// Superadmin Dashboard Route
Route::get('/dashboards/superadmin', function () {
    if (!auth()->check() || auth()->user()->role !== 'superadmin') {
        return redirect('/login');
    }
    return app(DashboardController::class)->superadminDashboard();
})->name('superadmin.dashboard');

// Admin Dashboard Route
Route::get('/dashboards/admin', function () {
    if (!auth()->check() || auth()->user()->role !== 'admin') {
        return redirect('/login');
    }
    return app(DashboardController::class)->adminDashboard();
})->name('admin.dashboard');

// Superadmin Company CRUD Routes
Route::get('/companies', function () {
    if (!auth()->check() || auth()->user()->role !== 'superadmin') {
        return redirect('/login');
    }
    return app(CompanyController::class)->index();
})->name('companies.index');

Route::get('/companies/create', function () {
    if (!auth()->check() || auth()->user()->role !== 'superadmin') {
        return redirect('/login');
    }
    return app(CompanyController::class)->create();
})->name('companies.create');

Route::post('/companies', function (Request $request) {
    if (!auth()->check() || auth()->user()->role !== 'superadmin') {
        return redirect('/login');
    }
    return app(CompanyController::class)->store($request);
})->name('companies.store');

Route::get('/companies/{company}', function (Company $company) {
    if (!auth()->check() || auth()->user()->role !== 'superadmin') {
        return redirect('/login');
    }
    return app(CompanyController::class)->show($company);
})->name('companies.show');

Route::get('/companies/{company}/edit', function (Company $company) {
    if (!auth()->check() || auth()->user()->role !== 'superadmin') {
        return redirect('/login');
    }
    return app(CompanyController::class)->edit($company);
})->name('companies.edit');

Route::put('/companies/{company}', function (Request $request, Company $company) {
    if (!auth()->check() || auth()->user()->role !== 'superadmin') {
        return redirect('/login');
    }
    return app(CompanyController::class)->update($request, $company);
})->name('companies.update');

Route::delete('/companies/{company}', function (Company $company) {
    if (!auth()->check() || auth()->user()->role !== 'superadmin') {
        return redirect('/login');
    }
    return app(CompanyController::class)->destroy($company);
})->name('companies.destroy');

// User CRUD Routes
Route::get('/users', function () {
    if (!auth()->check()) {
        return redirect('/login');
    }
    return app(UserController::class)->index();
})->name('users.index');

Route::get('/users/create', function () {
    if (!auth()->check()) {
        return redirect('/login');
    }
    return app(UserController::class)->create();
})->name('users.create');

Route::post('/users', function (Request $request) {
    if (!auth()->check()) {
        return redirect('/login');
    }
    return app(UserController::class)->store($request);
})->name('users.store');

Route::get('/users/{user}/edit', function (User $user) {
    if (!auth()->check()) {
        return redirect('/login');
    }
    return app(UserController::class)->edit($user);
})->name('users.edit');

Route::put('/users/{user}', function (Request $request, User $user) {
    if (!auth()->check()) {
        return redirect('/login');
    }
    return app(UserController::class)->update($request, $user);
})->name('users.update');

Route::delete('/users/{user}', function (User $user) {
    if (!auth()->check() || (auth()->user()->role !== 'admin' && auth()->user()->role !== 'superadmin')) {
        return redirect('/login');
    }
    return app(UserController::class)->destroy($user);
})->name('users.destroy');


Route::post('/user/upload-profile-image', [UserController::class, 'uploadProfileImage']);// downloading functions
Route::get('/leave-requests/{request}/download-pdf', [LeaveRequestController::class, 'downloadPdf'])->name('leave-requests.downloadPdf');
Route::get('/purchase-requests/{request}/download-pdf', [PurchaseRequestController::class, 'downloadPdf'])->name('purchase-requests.downloadPdf');
Route::get('/payment-requests/{request}/download-pdf', [PaymentRequestController::class, 'downloadPdf'])->name('payment-requests.downloadPdf');
Route::get('/weekly-plan/{plan}/download-pdf', [EmployeePlanController::class, 'downloadPdf'])->name('weekly-plan.downloadPdf');
Route::get('/weekly-reports/{report}/download-pdf', [EmployeeReportController::class, 'downloadPdf'])->name('weekly-reports.downloadPdf');
Route::get('/finance/payrolls/{payroll}/download-pdf', [FinanceController::class, 'downloadPdf'])->name('finance.payrolls.downloadPdf');
Route::get('/finance/budgets/{budget}/download-pdf', [FinanceController::class, 'downloadBudgetPdf'])->name('finance.budgets.downloadBudgetPdf');
Route::get('/stock_requisitions/{requisition}/download-pdf', [StockRequisitionController::class, 'downloadPdf'])->name('stock_requisitions.downloadPdf');
Route::get('/inventory/{id}/download-pdf', [InventoryController::class, 'downloadPdf'])->name('inventory.downloadPdf');
Route::get('petty_cash_requests/{id}/download', [PettyCashRequestController::class, 'downloadPdf'])->name('petty_cash_requests.downloadPdf');
  
// Summaries for manager and finance
Route::get('/manager-summary', [PaymentRequestController::class, 'managerSummary'])
->name('summaries.manager');

Route::get('/finance-summary', [PaymentRequestController::class, 'financeSummary'])
->name('summaries.finance');

