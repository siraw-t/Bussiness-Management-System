<?php

namespace App\Http\Controllers;

use App\Models\EmployeePlan;
use App\Models\User; // Import User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use App\Models\Company;


class EmployeePlanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $plans = EmployeePlan::where('employee_id', Auth::id())->get();
        return view('weekly-plan.index', compact('plans','user'));
    }

    public function create()
{
    $managers = User::where('company_id', Auth::user()->company_id)
                    ->where('role', 'manager')
                    ->get();
    return view('weekly-plan.create', compact('managers'));
}

    public function store(Request $request)
{
    $request->validate([
        'week' => 'required|string|max:255',
        'plan' => 'required|string',
        'urgent_issues' => 'nullable|string',
        'approved_by' => 'required|exists:users,user_id', // Validate that the selected manager exists

    ]);

    $employee = Auth::user();
    $manager = User::where('company_id', $employee->company_id)
                   ->where('role', 'manager')
                   ->first();

    $company = Company::find($employee->company_id);
    
    // Function to create an acronym from the company name
    function createAcronym($companyName)
    {
        $words = explode(' ', $companyName);
        $acronym = '';

        foreach ($words as $word) {
            if (!empty($word)) {
                $acronym .= strtoupper($word[0]);
            }
        }

        return $acronym;
    }

    $companyAcronym = createAcronym($company->company_name);
    $requestName = 'EP_Req';
    $currentYear = date('Y');

    $lastRequest = EmployeePlan::where('reference_number', 'LIKE', "{$companyAcronym}/{$requestName}/{$currentYear}/%")
        ->orderBy('created_at', 'desc')
        ->first();

    $sequenceNumber = $lastRequest ? intval(substr($lastRequest->reference_number, strrpos($lastRequest->reference_number, '/') + 1)) + 1 : 1;
    $reference_number = "{$companyAcronym}/{$requestName}/{$currentYear}/" . str_pad($sequenceNumber, 2, '0', STR_PAD_LEFT);

    // Create the EmployeePlan instance and assign it to a variable
    $employeePlan = EmployeePlan::create([
        'employee_id' => Auth::id(),
        'week' => $request->week,
        'plan' => $request->plan,
        'status' => 'pending',
        'reference_number' => $reference_number,
        'submitted_at' => now(),
        'approved_by' => $request->approved_by, // Use the selected manager's ID
        'urgent_issues' => $request->urgent_issues,
    ]);

    // // Notify the manager using the created EmployeePlan instance
    // if ($manager) {
    //     $manager->notify(new \App\Notifications\EmployeePlanRequestNotification($employeePlan));
    // }

     // Notify the selected manager using the created EmployeePlan instance
     $selectedManager = User::find($request->approved_by);
     if ($selectedManager) {
         $selectedManager->notify(new \App\Notifications\EmployeePlanRequestNotification($employeePlan));
     }

    return redirect()->route('weekly-plan.index')->with('success', 'Weekly plan submitted successfully.');
}

public function edit(EmployeePlan $weeklyPlan)
{
    $managers = User::where('company_id', Auth::user()->company_id)
                    ->where('role', 'manager')
                    ->get();
    return view('weekly-plan.edit', compact('weeklyPlan', 'managers'));
}

    public function update(Request $request, EmployeePlan $weeklyPlan)
    {
        $request->validate([
            'week' => 'required|string|max:255',
            'plan' => 'required|string',
            'urgent_issues' => 'nullable|string',
            'manager_comment' => 'nullable|string',
            'approved_by' => 'required|exists:users,user_id', // Validate that the selected manager exists

        ]);

        $weeklyPlan->update($request->only(['week', 'plan', 'urgent_issues', 'manager_comment', 'approved_by']));

        return redirect()->route('weekly-plan.index')->with('success', 'Weekly plan updated successfully.');
    }

    public function destroy(EmployeePlan $weeklyPlan)
    {
        $weeklyPlan->delete();
        return redirect()->route('weekly-plan.index')->with('success', 'Weekly plan deleted successfully.');
    }

    public function downloadPdf($id)
{
    $plan = EmployeePlan::findOrFail($id);
    
    // Retrieve the employee (user) associated with the plan
    $employee = User::findOrFail($plan->employee_id);
    
    // Get the company associated with the employee
    $company = Company::find($employee->company_id);

    // Handle the case where the company is not found
    if (!$company) {
        return redirect()->back()->withErrors(['message' => 'Company not found.']);
    }

    // Generate PDF
    $pdf = PDF::loadView('weekly-plan.pdf', compact('plan', 'company'))
                ->setPaper('A4', 'portrait');

    return $pdf->download('weekly-plan_' . $plan->id . '.pdf');
}


    public function showPendingRequests()
{
    $userId = Auth::id(); // Get the currently logged-in user's ID

    // Fetch pending employee plans for the authenticated user
    $pendingEmployeePlans = EmployeePlan::where('status', 'pending')
        ->where('employee_id', $userId) // Assuming 'employee_id' is the foreign key
        ->with('employee') // Load the employee relationship if needed
        ->get();

    return view('display.pending', compact('pendingEmployeePlans'));
}

public function showEmployeePlans()
{
    // Get all employee plans
    $plans = EmployeePlan::with('employee') // Ensure you load the employee relationship
        ->whereHas('employee', function($query) {
            $query->where('company_id', Auth::user()->company_id);
        })
        ->get();

    return view('display.plan', compact('plans')); // Pass the plans to the view
}

}