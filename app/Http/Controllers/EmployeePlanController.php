<?php

namespace App\Http\Controllers;

use App\Models\EmployeePlan;
use App\Models\User; // Import User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;


class EmployeePlanController extends Controller
{
    public function index()
    {
        $plans = EmployeePlan::where('employee_id', Auth::id())->get();
        return view('weekly-plan.index', compact('plans'));
    }

    public function create()
    {
        return view('weekly-plan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'week' => 'required|string|max:255',
            'plan' => 'required|string',
            'urgent_issues' => 'nullable|string',
        ]);

        $employee = Auth::user();
        $manager = User::where('company_id', $employee->company_id)
                       ->where('role', 'manager')
                       ->first();

        EmployeePlan::create([
            'employee_id' => Auth::id(),
            'week' => $request->week,
            'plan' => $request->plan,
            'status' => 'pending', // Default status
            'reference_number' => uniqid('RGV/PR/2025/'), // Generate unique reference number
            'submitted_at' => now(),
            'approved_by' => $manager ? $manager->user_id : null,
            'urgent_issues' => $request->urgent_issues,
        ]);

        return redirect()->route('weekly-plan.index')->with('success', 'Weekly plan submitted successfully.');
    }

    public function edit(EmployeePlan $weeklyPlan)
    {
        return view('weekly-plan.edit', compact('weeklyPlan'));
    }

    public function update(Request $request, EmployeePlan $weeklyPlan)
    {
        $request->validate([
            'week' => 'required|string|max:255',
            'plan' => 'required|string',
            'urgent_issues' => 'nullable|string',
            'manager_comment' => 'nullable|string',
        ]);

        $weeklyPlan->update($request->only(['week', 'plan', 'urgent_issues', 'manager_comment']));

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
        
        // Generate PDF with A4 size and portrait orientation
        $pdf = PDF::loadView('weekly-plan.pdf', compact('plan'))
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