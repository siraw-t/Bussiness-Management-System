<?php

namespace App\Http\Controllers;

use App\Models\EmployeeReport;
use App\Models\User; // Import User model
use App\Models\EmployeePlan; // Import EmployeePlan model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class EmployeeReportController extends Controller
{
    public function index()
    {
        $reports = EmployeeReport::where('employee_id', Auth::id())->get();
        return view('weekly-reports.index', compact('reports'));
    }

    public function create()
    {
        return view('weekly-reports.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'week' => 'required|date_format:Y-m-d', // Ensure week is a date
            'report' => 'required|string',
            'tasks_in_progress' => 'nullable|string',
            'tasks_completed' => 'nullable|string',
            'self_assessment' => 'nullable|string',
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

        EmployeeReport::create([
    'employee_id' => Auth::id(),
    'week' => $request->week,
    'report' => $request->report,
    'status' => 'pending', // Default status
    'reference_number' => uniqid('RGV/ER/2025/'), // Ensure this line is executed
    'submitted_at' => now(),
    'approved_by' => $manager ? $manager->user_id : null, // Set approved_by if manager exists
    'tasks_in_progress' => $request->tasks_in_progress,
    'tasks_completed' => $request->tasks_completed,
    'self_assessment' => $request->self_assessment,
    'last_week_plan_id' => $lastWeekPlanId, // Use retrieved last week's plan ID
]);

        return redirect()->route('weekly-reports.index')->with('success', 'Weekly report submitted successfully.');
    }

    public function edit(EmployeeReport $weeklyReport)
    {
        return view('weekly-reports.edit', compact('weeklyReport'));
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
        ]);

        $weeklyReport->update($request->only(['week', 'report', 'tasks_in_progress', 'tasks_completed', 'self_assessment', 'last_week_plan_id']));

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

        // Generate PDF with A4 size and portrait orientation
        $pdf = PDF::loadView('weekly-reports.pdf', compact('report'))
                    ->setPaper('A4', 'portrait');

        return $pdf->download('weekly-report_' . $report->report_id . '.pdf');
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