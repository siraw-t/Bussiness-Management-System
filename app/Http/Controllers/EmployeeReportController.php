<?php

namespace App\Http\Controllers;

use App\Models\EmployeeReport;
use App\Models\User; // Import User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'comment' => 'nullable|string',
        ]);

        // Get the currently logged-in user
        $employee = Auth::user();

        // Retrieve the manager for approval
        $manager = User::where('company_id', $employee->company_id)
                       ->where('role', 'manager')
                       ->first();

        EmployeeReport::create([
            'employee_id' => Auth::id(),
            'week' => $request->week,
            'report' => $request->report,
            'comment' => $request->comment,
            'approved_by' => $manager ? $manager->user_id : null, // Set approved_by if manager exists
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
            'comment' => 'nullable|string',
        ]);

        $weeklyReport->update($request->only(['week', 'report', 'comment']));

        return redirect()->route('weekly-reports.index')->with('success', 'Weekly report updated successfully.');
    }

    public function destroy(EmployeeReport $weeklyReport)
    {
        $weeklyReport->delete();
        return redirect()->route('weekly-reports.index')->with('success', 'Weekly report deleted successfully.');
    }
}