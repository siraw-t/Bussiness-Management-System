<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Company;
use PDF;

class LeaveRequestController extends Controller
{
    public function index()
{
    $requests = LeaveRequest::where('user_id', Auth::id())->get();
    $employee = Auth::user();
    $country = $employee->company->country;

    // Set the annual leave limit based on the company country
    $annualLeaveLimit = ($country === 'Ethiopia') ? 16 : (($country === 'Uganda') ? 21 : 0);

    // Count approved annual leave days taken by the user
    $totalAnnualLeaveTaken = LeaveRequest::where('user_id', $employee->user_id)
        ->where('leave_type', 'annual_leave')
        ->where('status', 'approved')
        ->get()
        ->sum(function ($leave) {
            return (new \DateTime($leave->end_date))->diff(new \DateTime($leave->start_date))->days + 1;
        });

    // Calculate remaining leave days
    $remainingLeaveDays = $annualLeaveLimit - $totalAnnualLeaveTaken;

    return view('leave-requests.index', compact('requests', 'remainingLeaveDays'));
}

    public function create()
{
    $managers = User::where('company_id', Auth::user()->company_id)
                    ->where('role', 'manager')
                    ->get();
    return view('leave-requests.create', compact('managers'));
}

    public function store(Request $request)
{
    // Validate request inputs
    $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'leave_type' => 'required|string|in:annual_leave,bereavement_leave,maternity_leave,sick_leave,unpaid_leave,parental_leave,other',
        'other_reason' => 'nullable|string|max:255',
        'employee_signature' => 'nullable|file|mimes:jpg,jpeg,png|max:1024',
        'attachment' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
        'approved_by' => 'required|exists:users,user_id', // Validate that the selected manager exists

    ]);

    $employee = Auth::user();
    $country = $employee->company->country;

    // Set the annual leave limit based on the company country
    $annualLeaveLimit = ($country === 'Ethiopia') ? 16 : (($country === 'Uganda') ? 21 : 0);

    // Calculate the number of days requested
    $daysRequested = (new \DateTime($request->start_date))->diff(new \DateTime($request->end_date))->days + 1;

    // Count approved annual leave days taken by the user
    $totalAnnualLeaveTaken = LeaveRequest::where('user_id', $employee->user_id)
        ->where('leave_type', 'annual_leave')
        ->where('status', 'approved')
        ->get()
        ->sum(function ($leave) {
            return (new \DateTime($leave->end_date))->diff(new \DateTime($leave->start_date))->days + 1;
        });

        // Calculate remaining leave days
    // $remainingLeaveDays = $annualLeaveLimit - $totalAnnualLeaveTaken;

    // Check if the user can request the leave
    if ($request->leave_type === 'annual_leave' && ($totalAnnualLeaveTaken + $daysRequested > $annualLeaveLimit)) {
        return redirect()->back()->withErrors(['error' => 'You cannot request more than ' . $annualLeaveLimit . ' days of annual leave.']);
    }

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
    $requestName = 'Leave_Req'; // Using the table name
    $currentYear = date('Y');

    // Get the last reference number for the same request name and year to increment
    $lastRequest = LeaveRequest::where('reference_number', 'LIKE', "{$companyAcronym}/{$requestName}/{$currentYear}/%")
        ->orderBy('created_at', 'desc')
        ->first();

    // Determine the next sequence number
    $sequenceNumber = $lastRequest ? intval(substr($lastRequest->reference_number, strrpos($lastRequest->reference_number, '/') + 1)) + 1 : 1;

    // Format the reference number
    $reference_number = "{$companyAcronym}/{$requestName}/{$currentYear}/" . str_pad($sequenceNumber, 2, '0', STR_PAD_LEFT);


    // Create the leave request
    $leaveRequest = new LeaveRequest();
    $leaveRequest->start_date = $request->start_date;
    $leaveRequest->end_date = $request->end_date;
    $leaveRequest->leave_type = $request->leave_type;
    $leaveRequest->other_reason = $request->other_reason;

    // Handle employee signature file upload
    if ($request->hasFile('employee_signature')) {
        $signaturePath = $request->file('employee_signature')->store('signatures', 'public');
        $leaveRequest->employee_signature = $signaturePath;
    }

    // Retrieve the manager associated with the employee's company
    $manager = User::where('company_id', $employee->company_id)->where('role', 'manager')->first();
    $leaveRequest->user_id = $employee->user_id;
    $leaveRequest->attachment = $request->attachment ? $request->attachment->store('attachments', 'public') : null;
    $leaveRequest->status = 'pending';
    $leaveRequest->approved_by = $request->approved_by; // Use the selected manager's ID
    $leaveRequest->company_country = $country;
    $leaveRequest->reference_number = $reference_number; // Assign the generated reference number

    // if ($manager) {
    //     $manager->notify(new \App\Notifications\LeaveRequestNotification($leaveRequest));
    // }

    $selectedManager = User::find($request->approved_by);
        if ($selectedManager) {
            $selectedManager->notify(new \App\Notifications\LeaveRequestNotification($leaveRequest));
        }

    // Attempt to save the leave request
    if ($leaveRequest->save()) {
        return redirect()->route('leave-requests.index')->with('success', 'Leave request created successfully. ');
    } else {
        return redirect()->back()->withErrors(['error' => 'Failed to create leave request. Please try again.']);
    }
}

public function edit(LeaveRequest $leaveRequest)
{
    $managers = User::where('company_id', Auth::user()->company_id)
                    ->where('role', 'manager')
                    ->get();
    return view('leave-requests.edit', compact('leaveRequest', 'managers'));
}

    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'leave_type' => 'required|string|in:annual_leave,bereavement_leave,maternity_leave,sick_leave,unpaid_leave,parental_leave,other',
            'other_reason' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
            'employee_signature' => 'nullable|file|mimes:jpg,jpeg,png|max:1024',
            'approved_by' => 'required|exists:users,user_id', // Validate that the selected manager exists

        ]);

        $leaveRequest->fill($request->only(['start_date', 'end_date', 'leave_type', 'other_reason']));

        // Handle employee signature upload
        if ($request->hasFile('employee_signature')) {
            $signaturePath = $request->file('employee_signature')->store('signatures', 'public');
            $leaveRequest->employee_signature = $signaturePath;
        }

        // Handle attachment update
        if ($request->hasFile('attachment')) {
            $leaveRequest->attachment = $request->attachment->store('attachments', 'public');
        }
        $leaveRequest->approved_by = $request->approved_by; // Update the selected manager's ID


        $leaveRequest->save();

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
    $company = Auth::user()->company;
    $employee = Auth::user();
    $country = $employee->company->country;

    // Set the annual leave limit based on the company country
    $annualLeaveLimit = ($country === 'Ethiopia') ? 16 : (($country === 'Uganda') ? 21 : 0);

    // Count approved annual leave days taken by the user
    $totalAnnualLeaveTaken = LeaveRequest::where('user_id', $employee->id)
        ->where('leave_type', 'annual_leave')
        ->where('status', 'approved')
        ->get()
        ->sum(function ($leave) {
            return (new \DateTime($leave->end_date))->diff(new \DateTime($leave->start_date))->days + 1;
        });

    // Calculate remaining leave days
    $remainingLeaveDays = $annualLeaveLimit - $totalAnnualLeaveTaken;

    $pdf = PDF::loadView('leave-requests.pdf', compact('request', 'company', 'remainingLeaveDays'))
                ->setPaper('A4', 'portrait');

    return $pdf->download('leave-request_' . $request->id . '.pdf');
}

    public function showPendingRequests()
    {
        $userId = Auth::id();
        $pendingLeaveRequests = LeaveRequest::where('status', 'pending')
            ->where('user_id', $userId)
            ->with('user')
            ->get();

        return view('display.pending', compact('pendingLeaveRequests'));
    }

//     public function markAsRead($id)
// {
//     $notification = Auth::user()->notifications()->findOrFail($id);
//     $notification->markAsRead();
//     return response()->json(['success' => true]);
// }
}