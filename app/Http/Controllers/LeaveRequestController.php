<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use PDF;

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
        'leave_type' => 'required|string|in:annual_leave,bereavement_leave,maternity_leave,sick_leave,unpaid_leave,parental_leave,other',
        'other_reason' => 'nullable|string|max:255',
        'employee_signature' => 'nullable|file|mimes:jpg,jpeg,png|max:1024', // Validate as an image file
        'attachment' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
    ]);

    // Create a new LeaveRequest
    $leaveRequest = new LeaveRequest();
    $leaveRequest->start_date = $request->start_date;
    $leaveRequest->end_date = $request->end_date;
    $leaveRequest->leave_type = $request->leave_type;
    $leaveRequest->other_reason = $request->other_reason;

    // Handle employee signature file upload
    if ($request->hasFile('employee_signature')) {
        $signaturePath = $request->file('employee_signature')->store('signatures', 'public');
        $leaveRequest->employee_signature = $signaturePath; // Save the file path to the database
    }

    // Get the currently logged-in employee
    $employee = Auth::user();

    // Retrieve the manager associated with the employee's company
    $manager = User::where('company_id', $employee->company_id)
                   ->where('role', 'manager')
                   ->first();

    // Create the leave request
    $leaveRequest->user_id = $employee->user_id; // Use 'id' instead of 'user_id'
    $leaveRequest->attachment = $request->attachment ? $request->attachment->store('attachments', 'public') : null;
    $leaveRequest->status = 'pending'; // Default status
    $leaveRequest->approved_by = $manager ? $manager->user_id : null; // Set approved_by to manager's ID if exists

    $leaveRequest->save(); // Save the leave request

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
        'leave_type' => 'required|string|in:annual_leave,bereavement_leave,maternity_leave,sick_leave,unpaid_leave,parental_leave,other',
        'other_reason' => 'nullable|string|max:255',
        'attachment' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
        'employee_signature' => 'nullable|file|mimes:jpg,jpeg,png|max:1024', // Added for signature upload
    ]);

    // Update basic fields
    $leaveRequest->fill($request->only([
        'start_date', 
        'end_date', 
        'leave_type', 
        'other_reason'
    ]));

    // Handle employee signature upload
    if ($request->hasFile('employee_signature')) {
        // Store the new signature
        $signaturePath = $request->file('employee_signature')->store('signatures', 'public');
        $leaveRequest->employee_signature = $signaturePath; // Update the signature path
    }

        if ($request->hasFile('attachment')) {
            $leaveRequest->attachment = $request->attachment->store('attachments', 'public'); // Store in public disk
        }

        $leaveRequest->save(); // Ensure the changes are saved

        return redirect()->route('leave-requests.index')->with('success', 'Leave request updated successfully.');
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        $leaveRequest->delete();
        return redirect()->route('leave-requests.index')->with('success', 'Leave request deleted successfully.');
    }

    public function downloadPdf($id)
    {
        $request = LeaveRequest::findOrFail($id);
        
        // Generate PDF with A4 size and portrait orientation
        $pdf = PDF::loadView('leave-requests.pdf', compact('request'))
                    ->setPaper('A4', 'portrait');

        return $pdf->download('leave-request_' . $request->id . '.pdf');
    }

    public function showPendingRequests()
    {
        $userId = Auth::id(); // Get the currently logged-in user's ID
    
        // Fetch pending leave requests for the authenticated user
        $pendingLeaveRequests = LeaveRequest::where('status', 'pending')
            ->where('user_id', $userId)
            ->with('user') // Load the user relationship if needed
            ->get();
    
        return view('display.pending', compact('pendingLeaveRequests'));
    }
}