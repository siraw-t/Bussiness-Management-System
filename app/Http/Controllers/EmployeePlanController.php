<?php

namespace App\Http\Controllers;

use App\Models\EmployeePlan;
use App\Models\User; // Import User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'comment' => 'nullable|string',
        ]);

        // Get the currently logged-in user
        $employee = Auth::user();

        // Retrieve the manager for approval
        $manager = User::where('company_id', $employee->company_id)
                       ->where('role', 'manager')
                       ->first();

        EmployeePlan::create([
            'employee_id' => Auth::id(),
            'week' => $request->week,
            'plan' => $request->plan,
            'comment' => $request->comment,
            'approved_by' => $manager ? $manager->user_id : null, // Set approved_by if manager exists
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
            'comment' => 'nullable|string',
        ]);

        $weeklyPlan->update($request->only(['week', 'plan', 'comment']));

        return redirect()->route('weekly-plan.index')->with('success', 'Weekly plan updated successfully.');
    }

    public function destroy(EmployeePlan $weeklyPlan)
    {
        $weeklyPlan->delete();
        return redirect()->route('weekly-plan.index')->with('success', 'Weekly plan deleted successfully.');
    }
}