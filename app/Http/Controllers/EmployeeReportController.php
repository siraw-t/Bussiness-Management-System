<?php

namespace App\Http\Controllers;

use App\Models\EmployeeReport;
use App\Models\User; // Import User model
use App\Models\Company;
use App\Models\EmployeePlan; // Import EmployeePlan model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class EmployeeReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $reports = EmployeeReport::where('employee_id', Auth::id())->get();
        return view('weekly-reports.index', compact('reports', 'user'));
    }

    public function create()
{
    $managers = User::where('company_id', Auth::user()->company_id)
                    ->where('role', 'manager')
                    ->get();
    return view('weekly-reports.create', compact('managers'));
}

    public function store(Request $request)
    {
        $request->validate([
            'week' => 'required|date_format:Y-m-d', // Ensure week is a date
            'report' => 'required|string',
            'tasks_in_progress' => 'nullable|string',
            'tasks_completed' => 'nullable|string',
            'self_assessment' => 'nullable|string',
            'approved_by' => 'required|exists:users,user_id', // Validate that the selected manager exists
            // Removed last_week_plan_id validation; it will be assigned in the controller
        ]);

        // Get the currently logged-in user
        $employee = Auth::user();

         // Retrieve the last week's plan
         // Retrieve the last week's plan by plan ID
    $lastWeekPlan = EmployeePlan::where('employee_id', $employee->id)
    ->orderBy('week', 'desc') // Get the most recent plan
    ->first();

$lastWeekPlanId = $lastWeekPlan ? $lastWeekPlan->plan_id : null;

        // Retrieve the manager for approval
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
                              $acronym .= strtoupper($word[0]); // Take the first letter of each word
                          }
                      }
              
                      return $acronym;
                  }
              
                  // Generate the acronym for the company name
                  $companyAcronym = createAcronym($company->company_name);
              
                  // Format the reference number
                  $requestName = 'ER_Req'; // Using the table name
                  $currentYear = date('Y');
              
                  // Get the last reference number for the same request name and year to increment
                  $lastRequest = EmployeeReport::where('reference_number', 'LIKE', "{$companyAcronym}/{$requestName}/{$currentYear}/%")
                      ->orderBy('created_at', 'desc')
                      ->first();
              
                  // Determine the next sequence number
                  $sequenceNumber = $lastRequest ? intval(substr($lastRequest->reference_number, strrpos($lastRequest->reference_number, '/') + 1)) + 1 : 1;
              
                  // Format the reference number
                  $reference_number = "{$companyAcronym}/{$requestName}/{$currentYear}/" . str_pad($sequenceNumber, 2, '0', STR_PAD_LEFT);
              

        $employeeReport = EmployeeReport::create([
    'employee_id' => Auth::id(),
    'week' => $request->week,
    'report' => $request->report,
    'status' => 'pending', // Default status
    'reference_number' => $reference_number, // Ensure this line is executed
    'submitted_at' => now(),
    'approved_by' => $request->approved_by, // Use the selected manager's ID
    'tasks_in_progress' => $request->tasks_in_progress,
    'tasks_completed' => $request->tasks_completed,
    'self_assessment' => $request->self_assessment,
    'last_week_plan_id' => $lastWeekPlanId, // Use retrieved last week's plan ID
]);

// if ($manager) {
//     $manager->notify(new \App\Notifications\EmployeeReportRequestNotification($employeeReport));
// }

$selectedManager = User::find($request->approved_by);
if ($selectedManager) {
    $selectedManager->notify(new \App\Notifications\EmployeeReportRequestNotification($employeeReport));
}

        return redirect()->route('weekly-reports.index')->with('success', 'Weekly report submitted successfully.');
    }

    public function edit(EmployeeReport $weeklyReport)
{
    $managers = User::where('company_id', Auth::user()->company_id)
                    ->where('role', 'manager')
                    ->get();
    return view('weekly-reports.edit', compact('weeklyReport', 'managers'));
}

    public function update(Request $request, EmployeeReport $weeklyReport)
    {
        $request->validate([
            'week' => 'required|date_format:Y-m-d', // Ensure week is a date
            'report' => 'required|string',
            'tasks_in_progress' => 'nullable|string',
            'tasks_completed' => 'nullable|string',
            'self_assessment' => 'nullable|string',
            'last_week_plan_id' => 'nullable|exists:employee_plan,plan_id', // Ensure it exists in employee_plan table
            'manager_comment' => 'nullable|string',
            'approved_by' => 'required|exists:users,user_id', // Validate that the selected manager exists

        ]);

        $weeklyReport->update($request->only(['week', 'report', 'tasks_in_progress', 'tasks_completed', 'self_assessment', 'last_week_plan_id', 'approved_by']));

        return redirect()->route('weekly-reports.index')->with('success', 'Weekly report updated successfully.');
    }

    public function destroy(EmployeeReport $weeklyReport)
    {
        $weeklyReport->delete();
        return redirect()->route('weekly-reports.index')->with('success', 'Weekly report deleted successfully.');
    }

    public function downloadPdf($id)
{
    $report = EmployeeReport::findOrFail($id);
    
    // Retrieve the employee (user) associated with the report
    $employee = User::findOrFail($report->employee_id);
    
    // Get the company associated with the employee
    $company = Company::find($employee->company_id);

    // Handle the case where the company is not found
    if (!$company) {
        return redirect()->back()->withErrors(['message' => 'Company not found.']);
    }

    // Generate PDF
    $pdf = PDF::loadView('weekly-reports.pdf', compact('report', 'company'))
                ->setPaper('A4', 'portrait');

    return $pdf->download('weekly-report_' . $report->id . '.pdf');
}

    public function showPendingRequests()
    {
        $userId = Auth::id(); // Get the currently logged-in user's ID

        // Fetch pending employee reports for the authenticated user
        $pendingEmployeeReports = EmployeeReport::where('status', 'pending')
            ->where('employee_id', $userId)
            ->get();

        return view('display.pending', compact('pendingEmployeeReports'));
    }

    public function showEmployeeReports()
{
    // Get all employee reports for the current user's company
    $reports = EmployeeReport::with(['employee', 'lastWeekPlan']) // Load necessary relationships
        ->whereHas('employee', function($query) {
            $query->where('company_id', Auth::user()->company_id);
        })
        ->get();

    return view('display.report', compact('reports')); // Pass the reports variable to the view
}
}