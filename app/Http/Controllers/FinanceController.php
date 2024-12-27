<?php

namespace App\Http\Controllers;

use App\Models\BudgetForecasting;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class FinanceController extends Controller
{
    public function indexBudget()
    {
        // Fetch budgets with creator and approver relationships
        $budgets = BudgetForecasting::with(['creator', 'approver'])
            ->where('created_by', Auth::id())
            ->get();

        return view('finance.budgets.index', compact('budgets'));
    }

    public function createBudget()
    {
        return view('finance.budgets.create');
    }

    public function storeBudget(Request $request)
    {
        $request->validate([
            'forecast_period' => 'required|string|max:255',
            'forecast_amount' => 'required|numeric',
            'actual_amount' => 'required|numeric',
            'description' => 'nullable|string',
            'comment' => 'nullable|string',
        ]);

        // Get the currently logged-in user
        $employee = Auth::user();

        // Retrieve the manager for approval
        $manager = User::where('company_id', $employee->company_id)
                       ->where('role', 'manager')
                       ->first(); 

        BudgetForecasting::create([
            'forecast_period' => $request->forecast_period,
            'forecast_amount' => $request->forecast_amount,
            'actual_amount' => $request->actual_amount,
            'description' => $request->description,
            'comment' => $request->comment,
            'created_by' => Auth::id(),
            'approved_by' => $manager->user_id, // Only the manager's ID
        ]);

        return redirect()->route('finance.budgets.index')->with('success', 'Budget forecasting created successfully.');
    }

    public function editBudget(BudgetForecasting $budget)
    {
        return view('finance.budgets.edit', compact('budget'));
    }

    public function updateBudget(Request $request, BudgetForecasting $budget)
    {
        $request->validate([
            'forecast_period' => 'required|string|max:255',
            'forecast_amount' => 'required|numeric',
            'actual_amount' => 'required|numeric',
            'description' => 'nullable|string',
            'comment' => 'nullable|string',
        ]);

        $budget->update($request->all());

        return redirect()->route('finance.budgets.index')->with('success', 'Budget forecasting updated successfully.');
    }

    public function destroyBudget(BudgetForecasting $budget)
    {
        $budget->delete();
        return redirect()->route('finance.budgets.index')->with('success', 'Budget forecasting deleted successfully.');
    }

    public function indexPayroll()
    {
        // Fetch payrolls with employee and approver relationships
        $payrolls = Payroll::with(['employee', 'preparedBy', 'approver'])
            ->where('prepared_by', Auth::id())
            ->get();

        return view('finance.payrolls.index', compact('payrolls'));
    }

    public function createPayroll()
    {
        $companyId = Auth::user()->company_id;
        $employees = User::where('company_id', $companyId)->get();
        return view('finance.payrolls.create', compact('employees'));
    }

    public function storePayroll(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,user_id',
            'pay_period' => 'required|string|max:255',
            'basic_salary' => 'required|numeric',
            'allowances' => 'nullable|numeric',
            'deductions' => 'nullable|numeric',
            'net_salary' => 'required|numeric',
            'status' => 'nullable|string',
        ]);

        // Get the currently logged-in user
        $employee = Auth::user();

        // Retrieve the manager for approval
        $manager = User::where('company_id', $employee->company_id)
                       ->where('role', 'manager')
                       ->first(); 

        Payroll::create([
            'employee_id' => $request->employee_id,
            'pay_period' => $request->pay_period,
            'basic_salary' => $request->basic_salary,
            'allowances' => $request->allowances,
            'deductions' => $request->deductions,
            'net_salary' => $request->net_salary,
            'prepared_by' => Auth::id(),
            'status' => $request->status ?? 'pending',
            'approved_by' => $manager->user_id, // Only the manager's ID
        ]);

        return redirect()->route('finance.payrolls.index')->with('success', 'Payroll created successfully.');
    }

    public function editPayroll(Payroll $payroll)
    {
        $companyId = Auth::user()->company_id;
        $employees = User::where('company_id', $companyId)->get();
        return view('finance.payrolls.edit', compact('payroll', 'employees'));
    }

    public function updatePayroll(Request $request, Payroll $payroll)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,user_id',
            'pay_period' => 'required|string|max:255',
            'basic_salary' => 'required|numeric',
            'allowances' => 'nullable|numeric',
            'deductions' => 'nullable|numeric',
            'net_salary' => 'required|numeric',
            'status' => 'nullable|string',
        ]);

        $payroll->update($request->all());

        return redirect()->route('finance.payrolls.index')->with('success', 'Payroll updated successfully.');
    }

    public function destroyPayroll(Payroll $payroll)
    {
        $payroll->delete();
        return redirect()->route('finance.payrolls.index')->with('success', 'Payroll deleted successfully.');
    }
}