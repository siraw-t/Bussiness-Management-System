<?php

namespace App\Http\Controllers;

use App\Models\BudgetForecasting;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Company;
use PDF;


class FinanceController extends Controller
{
    public function indexBudget()
    {
        $budgets = BudgetForecasting::where('created_by', Auth::id())->get();
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
            'budget_description' => 'nullable|string',
            'budget_item' => 'nullable|string',
            'budget_code' => 'nullable|string',
            'department' => 'nullable|string',
            'budget_source' => 'nullable|string',
            'quantity' => 'nullable|integer',
            'unit' => 'nullable|string',
            'unit_cost' => 'nullable|numeric',
            'status' => 'nullable|string',
        ]);

        $employee = Auth::user();
        $manager = User::where('company_id', $employee->company_id)->where('role', 'manager')->first();

        // Calculate total budget
        $total_budget = $request->unit_cost * ($request->quantity ?? 0);

        $company = Company::find($employee->company_id);

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
    $requestName = 'Budget_Req'; // Using the table name
    $currentYear = date('Y');

    // Get the last reference number for the same request name and year to increment
    $lastRequest = BudgetForecasting::where('reference_number', 'LIKE', "{$companyAcronym}/{$requestName}/{$currentYear}/%")
        ->orderBy('created_at', 'desc')
        ->first();

    // Determine the next sequence number
    $sequenceNumber = $lastRequest ? intval(substr($lastRequest->reference_number, strrpos($lastRequest->reference_number, '/') + 1)) + 1 : 1;

    // Format the reference number
    $reference_number = "{$companyAcronym}/{$requestName}/{$currentYear}/" . str_pad($sequenceNumber, 2, '0', STR_PAD_LEFT);


        BudgetForecasting::create([
            'created_by' => Auth::id(),
            'forecast_period' => $request->forecast_period,
            'budget_description' => $request->budget_description,
            'budget_item' => $request->budget_item,
            'budget_code' => $request->budget_code,
            'department' => $request->department,
            'budget_source' => $request->budget_source,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'unit_cost' => $request->unit_cost,
            'total_budget' => $total_budget,
            'status' => $request->status ?? 'pending',
            'manager_comment' => null,
            'approved_by' => $manager->user_id ?? null,
            'reference_number' => $reference_number,
            'company_country' => $employee->company->country ?? 'N/A',
            'currency' => $request->currency,
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
            'budget_description' => 'nullable|string',
            'budget_item' => 'nullable|string',
            'budget_code' => 'nullable|string',
            'department' => 'nullable|string',
            'budget_source' => 'nullable|string',
            'quantity' => 'nullable|integer',
            'unit' => 'nullable|string',
            'unit_cost' => 'nullable|numeric',
            'status' => 'nullable|string',
        ]);

        // Calculate total budget
        $total_budget = $request->unit_cost * ($request->quantity ?? 0);

        $budget->update([
            'forecast_period' => $request->forecast_period,
            'budget_description' => $request->budget_description,
            'budget_item' => $request->budget_item,
            'budget_code' => $request->budget_code,
            'department' => $request->department,
            'budget_source' => $request->budget_source,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'unit_cost' => $request->unit_cost,
            'total_budget' => $total_budget,
            'status' => $request->status ?? $budget->status,
        ]);

        return redirect()->route('finance.budgets.index')->with('success', 'Budget forecasting updated successfully.');
    }

    public function destroyBudget(BudgetForecasting $budget)
    {
        $budget->delete();
        return redirect()->route('finance.budgets.index')->with('success', 'Budget forecasting deleted successfully.');
    }

    public function downloadBudgetPdf($id)
    {
        $budget = BudgetForecasting::findOrFail($id);
        $company = Auth::user()->company;

        $pdf = PDF::loadView('finance.budgets.pdf', compact('budget','company'))
                    ->setPaper('A4', 'portrait');

        return $pdf->download('budget_forecasting_' . $budget->budget_id . '.pdf');
    }



    public function indexPayroll()
    {
        $payrolls = Payroll::with(['employee', 'preparedBy', 'approver'])
            ->where('prepared_by', Auth::id())
            ->get();

        return view('finance.payrolls.index', compact('payrolls'));
    }

    public function createPayroll()
    {
        $employees = User::where('company_id', Auth::user()->company_id)->get();
        $employee = Auth::user();
        $company = Company::find($employee->company_id); // Fetch the company
        $currencies = ['USD', 'ETB', 'UGX']; // Replace with your currency fetching logic

        return view('finance.payrolls.create', compact('employees', 'currencies','company'));
    }

    public function storePayroll(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,user_id',
            'pay_period' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'basic_salary' => 'required|numeric',
            'currency' => 'required|string',
            'no_of_days' => 'required|integer|min:0|max:26', // Allow 0 to 26
            'position_allowance' => 'nullable|numeric',
            'salary_advance' => 'nullable|numeric',
            'status' => 'nullable|string',
            'transport_allowance' => 'nullable|numeric',
            'taxable_transport_allowance' => 'nullable|numeric',
        ]);
         
        $employee = Auth::user();
        $manager = User::where('company_id', $employee->company_id)->where('role', 'manager')->first();
        $company = Company::find($employee->company_id);

        // Generate a unique reference number
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
        $requestName = 'Payroll_Req'; // Using the table name
        $currentYear = date('Y');
    
        // Get the last reference number for the same request name and year to increment
        $lastRequest = Payroll::where('reference_number', 'LIKE', "{$companyAcronym}/{$requestName}/{$currentYear}/%")
            ->orderBy('created_at', 'desc')
            ->first();
    
        // Determine the next sequence number
        $sequenceNumber = $lastRequest ? intval(substr($lastRequest->reference_number, strrpos($lastRequest->reference_number, '/') + 1)) + 1 : 1;
    
        // Format the reference number
        $reference_number = "{$companyAcronym}/{$requestName}/{$currentYear}/" . str_pad($sequenceNumber, 2, '0', STR_PAD_LEFT);
        
        $pay_period_date = \Carbon\Carbon::createFromFormat('F Y', $request->pay_period)->startOfMonth();
        // Calculate adjusted basic salary based on days worked
        $basicSalary = $request->basic_salary;
        $daysWorked = $request->no_of_days; // Directly use days worked
        $dailyRate = $basicSalary / 26;

        // Adjusted basic salary
        $adjustedBasicSalary = ($daysWorked == 26) ? $basicSalary : $dailyRate * $daysWorked;

        // Initialize allowances
        $transportAllowance = $request->transport_allowance;
        $taxableTransportAllowance = $request->taxable_transport_allowance;
        $positionAllowance = $request->position_allowance ?? 0;

        // Calculate transport allowances if not provided and only for Ethiopia
if ($company->country === 'Ethiopia') {
    if (is_null($transportAllowance) && is_null($taxableTransportAllowance)) {
        $transportAllowanceComputed = $adjustedBasicSalary / 4;
        $transportAllowance = min($transportAllowanceComputed, 2200);
        $taxableTransportAllowance = max($transportAllowanceComputed - 2200, 0);
    }
}

        // Adjust allowances based on days worked
if ($company->country === 'Ethiopia') {
    if ($daysWorked < 26) {
        $transportAllowance *= ($daysWorked / 26);
        $taxableTransportAllowance *= ($daysWorked / 26);
        $positionAllowance *= ($daysWorked / 26);
    }
} else { // For Uganda
    // Set allowances to 0 if days worked are less than 26
    if ($daysWorked < 26) {
        $transportAllowance = 0;
        $taxableTransportAllowance = 0;
        // $positionAllowance = 0;
    }
}

        // Calculate pension funds
if ($company->country === 'Ethiopia') {
    $pensionFund7 = $adjustedBasicSalary * 0.07;
    $pensionFund11 = $adjustedBasicSalary * 0.11;
} else { // For Uganda
    $pensionFund7 = 0;
    $pensionFund11 = 0;
}

        // Total taxable earnings
        $totalTaxableEarnings = $adjustedBasicSalary + $taxableTransportAllowance + $positionAllowance;

        // Calculate income tax based on company country
        $incomeTax = $this->calculateIncomeTax($totalTaxableEarnings, $company->country);

        // Calculate net payment
        $netPayment = $totalTaxableEarnings - $incomeTax - $pensionFund7 - ($request->salary_advance ?? 0) + $transportAllowance;

        // Create payroll record
        Payroll::create([
            'employee_id' => $request->employee_id,
            'reference_number' => $reference_number,
            'job_title' => $request->job_title,
            'no_of_days' => 26 - $daysWorked, // Calculate absent days
            'basic_salary' => $adjustedBasicSalary,
            'currency' => $request->currency,
            'transport_allowance' => $transportAllowance,
            'taxable_transport_allowance' => $taxableTransportAllowance,
            'position_allowance' => $positionAllowance,
            'total_taxable_earnings' => $totalTaxableEarnings,
            'income_tax' => $incomeTax,
            'pension_fund_7' => $pensionFund7,
            'pension_fund_11' => $pensionFund11,
            'salary_advance' => $request->salary_advance ?? 0,
            'net_payment' => $netPayment,
            'pay_period' => $pay_period_date,
            'status' => $request->status ?? 'Pending',
            'prepared_by' => Auth::id(),
            'approved_by' => $manager->user_id ?? null,
            'company_country' => $company->country ?? 'N/A',
        ]);

        return redirect()->route('finance.payrolls.index')->with('success', 'Payroll created successfully.');
    }

    public function editPayroll(Payroll $payroll)
    {
        $employees = User::where('company_id', Auth::user()->company_id)->get();
        $currencies = ['USD', 'ETB', 'UGX']; // Replace with your currency fetching logic

        return view('finance.payrolls.edit', compact('payroll', 'employees', 'currencies'));
    }

    public function updatePayroll(Request $request, Payroll $payroll)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,user_id',
            'pay_period' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'basic_salary' => 'required|numeric',
            'currency' => 'required|string',
            'no_of_days' => 'required|integer|min:0|max:26', // Allow 0 to 26
            'position_allowance' => 'nullable|numeric',
            'salary_advance' => 'nullable|numeric',
            'status' => 'nullable|string',
            'transport_allowance' => 'nullable|numeric',
            'taxable_transport_allowance' => 'nullable|numeric',
        ]);

        $employee = Auth::user();
        $manager = User::where('company_id', $employee->company_id)->where('role', 'manager')->first();
        $company = Company::find($employee->company_id);

        // Calculate adjusted basic salary based on days worked
        $basicSalary = $request->basic_salary;
        $daysWorked = $request->no_of_days;
        $dailyRate = $basicSalary / 26;

        // Adjusted basic salary
        $adjustedBasicSalary = ($daysWorked == 26) ? $basicSalary : $dailyRate * $daysWorked;

        // Initialize allowances
        $transportAllowance = $request->transport_allowance;
        $taxableTransportAllowance = $request->taxable_transport_allowance;
        $positionAllowance = $request->position_allowance ?? 0;

        // Calculate transport allowances if not provided and only for Ethiopia
if ($company->country === 'Ethiopia') {
    if (is_null($transportAllowance) && is_null($taxableTransportAllowance)) {
        $transportAllowanceComputed = $adjustedBasicSalary / 4;
        $transportAllowance = min($transportAllowanceComputed, 2200);
        $taxableTransportAllowance = max($transportAllowanceComputed - 2200, 0);
    }
}

        // Adjust allowances based on days worked
if ($company->country === 'Ethiopia') {
    if ($daysWorked < 26) {
        $transportAllowance *= ($daysWorked / 26);
        $taxableTransportAllowance *= ($daysWorked / 26);
        $positionAllowance *= ($daysWorked / 26);
    }
} else { // For Uganda
    // Set allowances to 0 if days worked are less than 26
    if ($daysWorked < 26) {
        $transportAllowance = 0;
        $taxableTransportAllowance = 0;
        // $positionAllowance = 0;
    }
}

        // Calculate pension funds
if ($company->country === 'Ethiopia') {
    $pensionFund7 = $adjustedBasicSalary * 0.07;
    $pensionFund11 = $adjustedBasicSalary * 0.11;
} else { // For Uganda
    $pensionFund7 = 0;
    $pensionFund11 = 0;
}

        // Total taxable earnings
        $totalTaxableEarnings = $adjustedBasicSalary + $taxableTransportAllowance + $positionAllowance;

        // Calculate income tax based on company country
        $company = Company::find(Auth::user()->company_id);
        $incomeTax = $this->calculateIncomeTax($totalTaxableEarnings, $company->country);

        // Calculate net payment
        $netPayment = $totalTaxableEarnings - $incomeTax - $pensionFund7 - ($request->salary_advance ?? 0) + $transportAllowance;

        // Update payroll record
        $payroll->update([
            'employee_id' => $request->employee_id,
            'job_title' => $request->job_title,
            'no_of_days' => 26 - $daysWorked, // Calculate absent days
            'basic_salary' => $adjustedBasicSalary,
            'currency' => $request->currency,
            'transport_allowance' => $transportAllowance,
            'taxable_transport_allowance' => $taxableTransportAllowance,
            'position_allowance' => $positionAllowance,
            'total_taxable_earnings' => $totalTaxableEarnings,
            'income_tax' => $incomeTax,
            'pension_fund_7' => $pensionFund7,
            'pension_fund_11' => $pensionFund11,
            'salary_advance' => $request->salary_advance ?? 0,
            'net_payment' => $netPayment,
            'pay_period' => $request->pay_period,
            'status' => $request->status ?? 'Pending',
        ]);

        return redirect()->route('finance.payrolls.index')->with('success', 'Payroll updated successfully.');
    }

    public function destroyPayroll(Payroll $payroll)
    {
        $payroll->delete();
        return redirect()->route('finance.payrolls.index')->with('success', 'Payroll deleted successfully.');
    }

    private function calculateIncomeTax($totalTaxableEarnings, $country)
    {
        if ($country === 'Uganda') {
            if ($totalTaxableEarnings <= 235000) {
                return 0;
            } elseif ($totalTaxableEarnings <= 335000) {
                return ($totalTaxableEarnings - 235000) * 0.1;
            } elseif ($totalTaxableEarnings <= 410000) {
                return ($totalTaxableEarnings - 335000) * 0.2 + 10000;
            } elseif ($totalTaxableEarnings <= 10000000) {
                return ($totalTaxableEarnings - 410000) * 0.3 + 25000;
            } else {
                return ($totalTaxableEarnings - 410000) * 0.3 + 25000 + ($totalTaxableEarnings - 10000000) * 0.1;
            }
        } else { // For Ethiopia
            if ($totalTaxableEarnings < 600) {
                return 0;
            } elseif ($totalTaxableEarnings <= 1650) {
                return ($totalTaxableEarnings * 0.1) - 60;
            } elseif ($totalTaxableEarnings <= 3200) {
                return ($totalTaxableEarnings * 0.15) - 142.5;
            } elseif ($totalTaxableEarnings <= 5250) {
                return ($totalTaxableEarnings * 0.2) - 302.5;
            } elseif ($totalTaxableEarnings <= 7800) {
                return ($totalTaxableEarnings * 0.25) - 565;
            } elseif ($totalTaxableEarnings <= 10900) {
                return ($totalTaxableEarnings * 0.3) - 955;
            } else {
                return ($totalTaxableEarnings * 0.35) - 1500;
            }
        }
    }

    public function downloadPdf($id)
{
    // Find the payroll record by ID
    $payroll = Payroll::findOrFail($id);

    // Retrieve the company information
    // Assuming there's a relationship between Employee and Company
    $company = $payroll->employee->company; // Adjust as necessary based on your relationships

    // Generate PDF with A4 size and portrait orientation
    $pdf = PDF::loadView('finance.payrolls.pdf', compact('payroll', 'company'))
                ->setPaper('A4', 'portrait');

    return $pdf->download('finance_payrolls_' . $payroll->payroll_id . '.pdf');
}

    
}