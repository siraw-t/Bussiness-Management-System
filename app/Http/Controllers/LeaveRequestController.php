<?php


namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\PdfService;
use App\Models\User;


class LeaveRequestController extends Controller
{
    public function index()
    {
        $requests = LeaveRequest::where('user_id', Auth::id())->get();
        return view('leave-requests.index', compact('requests'));
    }

    public function create()
    {
        return view('leave-requests.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'reason' => 'required|string|max:255',
    ]);

    // Get the currently logged-in employee
    $employee = Auth::user();

    // Retrieve the manager associated with the employee's company
    $manager = User::where('company_id', $employee->company_id)
                   ->where('role', 'manager') // Assuming the role is 'manager'
                   ->first();

    // Create the leave request
    LeaveRequest::create([
        'user_id' => $employee->user_id,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'reason' => $request->reason,
        'status' => 'pending', // Default status
        'approved_by' => $manager->user_id, // Set approved_by to manager's ID if exists
    ]);

    return redirect()->route('leave-requests.index')->with('success', 'Leave request created successfully.');
}


  

    public function edit(LeaveRequest $leaveRequest)
    {
        return view('leave-requests.edit', compact('leaveRequest'));
    }

    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:255',
        ]);

        $leaveRequest->update($request->only(['start_date', 'end_date', 'reason']));
        return redirect()->route('leave-requests.index')->with('success', 'Leave request updated successfully.');
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        $leaveRequest->delete();
        return redirect()->route('leave-requests.index')->with('success', 'Leave request deleted successfully.');
    }
}