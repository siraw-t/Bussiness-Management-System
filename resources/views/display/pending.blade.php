<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Requests</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .header {
            background-color: green;
            /* Bootstrap primary color */
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 0.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            margin-top: 5px;
        }

        .no-requests {
            text-align: center;
            font-size: 1.1rem;
            color: #6c757d;
        }

        .action-buttons {
            text-align: center;
        }

        .table-container {
            margin-bottom: 40px;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .btn {
            padding: 10px;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn:hover {
            transform: scale(1.05);
        }

        .table-hover tbody tr:hover {
            background-color: #e9ecef;
            /* Light grey on hover */
        }
    </style>
</head>

<body>

    <div class="container mb-5">
        <h2 class="header text-center">{{ Auth::user()->company->company_name }} Pending Requests</h2>
        <a class="btn btn-outline-primary" href="{{ route('manager.dashboard') }}">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>

        <!-- Pending Employee Plans -->
        <div class="table-container">
            <h5 class="text-primary mt-5">Pending Employee Plans</h5>
            @if ($pendingEmployeePlans->isEmpty())
                <p class="no-requests">No pending employee plans at this time.</p>
            @else
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Employee Name</th>
                            <th>Week</th>
                            <th>Plan</th>
                            <th>Submitted At</th>
                            <th>Urgent Issues</th>
                            <th>Status</th>
                            <th>Reference Number</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendingEmployeePlans as $plan)
                            <tr>
                                <td>{{ $plan->employee->full_name ?? 'N/A' }}</td>
                                <td>{{ $plan->week }}</td>
                                <td>{{ $plan->plan }}</td>
                                <td>{{ $plan->submitted_at->format('Y-m-d H:i:s') }}</td>
                                <td>{{ $plan->urgent_issues ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-warning">{{ ucfirst($plan->status) }}</span>
                                </td>
                                <td>{{ $plan->reference_number }}</td>
                                <td class="action-buttons">
                                    <!-- Approve Button -->
                                    <button class="btn btn-success btn-sm" data-toggle="modal"
                                        data-target="#approveModal{{ $plan->plan_id }}">
                                        <i class="fas fa-check"></i> Approve
                                    </button>

                                    <!-- Reject Button -->
                                    <button class="btn btn-danger btn-sm" data-toggle="modal"
                                        data-target="#rejectModal{{ $plan->plan_id }}">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </td>
                            </tr>

                            <!-- Approval Modal -->
                            <div class="modal fade" id="approveModal{{ $plan->plan_id }}" tabindex="-1" role="dialog"
                                aria-labelledby="approveModalLabel{{ $plan->plan_id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Approve Employee Plan</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <form action="{{ route('manager.plan.approve', $plan->plan_id) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="manager-comments-approve{{ $plan->plan_id }}">Manager
                                                        Comments:</label>
                                                    <textarea class="form-control" id="manager-comments-approve{{ $plan->plan_id }}" name="manager_comments" rows="3"
                                                        required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-success">Approve</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Rejection Modal -->
                            <div class="modal fade" id="rejectModal{{ $plan->plan_id }}" tabindex="-1" role="dialog"
                                aria-labelledby="rejectModalLabel{{ $plan->plan_id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reject Employee Plan</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <form action="{{ route('manager.plan.reject', $plan->plan_id) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="manager-comments-reject{{ $plan->plan_id }}">Manager
                                                        Comments:</label>
                                                    <textarea class="form-control" id="manager-comments-reject{{ $plan->plan_id }}" name="manager_comments" rows="3"
                                                        required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-danger">Reject</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Pending Employee Reports -->
        <div class="table-container">
            <h5 class="text-primary mt-5">Pending Employee Reports</h5>
            @if ($pendingEmployeeReports->isEmpty())
                <p class="no-requests">No pending employee reports at this time.</p>
            @else
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Employee Name</th>
                            <th>Week</th>
                            <th>Last Week's Plan</th>
                            <th>Report</th>
                            <th>Submitted At</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendingEmployeeReports as $report)
                            @php
                                $lastWeekPlan = \App\Models\EmployeePlan::where('employee_id', $report->employee_id)
                                    ->where('week', $report->week) // Fetch plan for the same week
                                    ->orderBy('week', 'desc')
                                    ->first();
                            @endphp
                            <tr>
                                <td>{{ $report->employee->full_name ?? 'N/A' }}</td>
                                <td>{{ $report->week }}</td>
                                <td>
                                    @if ($lastWeekPlan)
                                        {{ $lastWeekPlan->plan }}
                                    @else
                                        <span class="text-muted">No plan available</span>
                                    @endif
                                </td>
                                <td>{{ $report->report }}</td>
                                <td>{{ $report->submitted_at->format('Y-m-d H:i:s') }}</td>
                                <td>
                                    <span class="badge bg-warning">{{ ucfirst($report->status) }}</span>
                                </td>
                                <td class="action-buttons">
                                    <!-- Approve Button -->
                                    <button class="btn btn-success btn-sm" data-toggle="modal"
                                        data-target="#approveModal{{ $report->report_id }}">
                                        <i class="fas fa-check"></i> Approve
                                    </button>

                                    <!-- Reject Button -->
                                    <button class="btn btn-danger btn-sm" data-toggle="modal"
                                        data-target="#rejectModal{{ $report->report_id }}">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </td>
                            </tr>

                            <!-- Approval Modal -->
                            <div class="modal fade" id="approveModal{{ $report->report_id }}" tabindex="-1"
                                role="dialog" aria-labelledby="approveModalLabel{{ $report->report_id }}"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Approve Employee Report</h5>
                                            <button type="button" class="close"
                                                data-dismiss="modal">&times;</button>
                                        </div>
                                        <form action="{{ route('manager.report.approve', $report->report_id) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label
                                                        for="manager-comments-approve{{ $report->report_id }}">Manager
                                                        Comments:</label>
                                                    <textarea class="form-control" id="manager-comments-approve{{ $report->report_id }}" name="manager_comments"
                                                        rows="3" required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-success">Approve</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Rejection Modal -->
                            <div class="modal fade" id="rejectModal{{ $report->report_id }}" tabindex="-1"
                                role="dialog" aria-labelledby="rejectModalLabel{{ $report->report_id }}"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reject Employee Report</h5>
                                            <button type="button" class="close"
                                                data-dismiss="modal">&times;</button>
                                        </div>
                                        <form action="{{ route('manager.report.reject', $report->report_id) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label
                                                        for="manager-comments-reject{{ $report->report_id }}">Manager
                                                        Comments:</label>
                                                    <textarea class="form-control" id="manager-comments-reject{{ $report->report_id }}" name="manager_comments"
                                                        rows="3" required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-danger">Reject</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Pending Leave Requests -->
        <div class="table-container">
            <h5 class="text-primary mt-5">Pending Leave Requests</h5>
            @if ($pendingLeaveRequests->isEmpty())
                <p class="no-requests">No pending leave requests at this time.</p>
            @else
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Leave Type</th>
                            <th>Other Reason</th>
                            <th>Requested By</th>
                            <th>Uploaded File</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendingLeaveRequests as $request)
                            <tr>
                                <td>{{ ucfirst(str_replace('_', ' ', $request->leave_type)) }}</td>
                                <td>{{ $request->other_reason ?? 'N/A' }}</td>
                                <td>{{ $request->user->full_name }}</td>
                                <td>
                                    @if ($request->attachment)
                                        <a href="{{ asset('storage/' . $request->attachment) }}" target="_blank">View
                                            File</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $request->start_date }}</td>
                                <td>{{ $request->end_date }}</td>
                                <td>
                                    <span class="badge bg-warning">{{ ucfirst($request->status) }}</span>
                                </td>
                                <td class="action-buttons">
                                    <!-- Approve Button -->
                                    <button class="btn btn-success btn-sm" data-toggle="modal"
                                        data-target="#approveModal{{ $request->leave_request_id }}">
                                        <i class="fas fa-check"></i> Approve
                                    </button>

                                    <!-- Reject Button -->
                                    <button class="btn btn-danger btn-sm" data-toggle="modal"
                                        data-target="#rejectModal{{ $request->leave_request_id }}">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </td>
                            </tr>

                            <!-- Approval Modal -->
                            <div class="modal fade" id="approveModal{{ $request->leave_request_id }}" tabindex="-1"
                                role="dialog" aria-labelledby="approveModalLabel{{ $request->leave_request_id }}"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Approve Leave Request</h5>
                                            <button type="button" class="close"
                                                data-dismiss="modal">&times;</button>
                                        </div>
                                        <form
                                            action="{{ route('manager.leave.approve', ['leave_request_id' => $request->leave_request_id]) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label
                                                        for="manager-comments-approve{{ $request->leave_request_id }}">Manager
                                                        Comments:</label>
                                                    <textarea class="form-control" id="manager-comments-approve{{ $request->leave_request_id }}" name="manager_comments"
                                                        rows="3" required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-success">Approve</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Rejection Modal -->
                            <div class="modal fade" id="rejectModal{{ $request->leave_request_id }}" tabindex="-1"
                                role="dialog" aria-labelledby="rejectModalLabel{{ $request->leave_request_id }}"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reject Leave Request</h5>
                                            <button type="button" class="close"
                                                data-dismiss="modal">&times;</button>
                                        </div>
                                        <form
                                            action="{{ route('manager.leave.reject', ['leave_request_id' => $request->leave_request_id]) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label
                                                        for="manager-comments-reject{{ $request->leave_request_id }}">Manager
                                                        Comments:</label>
                                                    <textarea class="form-control" id="manager-comments-reject{{ $request->leave_request_id }}" name="manager_comments"
                                                        rows="3" required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-danger">Reject</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Pending Payment Requests -->
        <div class="table-container">
            <h5 class="text-primary">Pending Payment Requests</h5>
            @if ($pendingPaymentRequests->isEmpty())
                <p class="no-requests">No pending payment requests at this time.</p>
            @else
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Requested By</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendingPaymentRequests as $request)
                            <tr>
                                <td>{{ $request->description }}</td>
                                <td>${{ number_format($request->amount, 2) }}</td>
                                <td>{{ $request->user->full_name }}</td>
                                <td>{{ ucfirst($request->status) }}</td>
                                <td class="action-buttons">
                                    <form
                                        action="{{ route('manager.payment.approve', ['payment_request_id' => $request->payment_request_id]) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm"
                                            title="Approve Payment">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                    <form
                                        action="{{ route('manager.payment.reject', ['payment_request_id' => $request->payment_request_id]) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm" title="Reject Payment">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Pending Purchase Requests -->
        <div class="table-container">
            <h5 class="text-primary">Pending Purchase Requests</h5>
            @if ($pendingPurchaseRequests->isEmpty())
                <p class="no-requests">No pending purchase requests at this time.</p>
            @else
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Reference Number</th>
                            <th>Item Info</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Requested By</th>
                            <th>Finance Comment</th>
                            <th>Checked By</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendingPurchaseRequests as $request)
                            <tr>
                                <td>{{ $request->reference_number }}</td>
                                <td>{{ $request->item_info }}</td>
                                <td>{{ $request->quantity_with_unit }}</td>
                                <td>{{ $request->price_with_currency }}</td>
                                <td>{{ $request->user->full_name }}</td>
                                <td>{{ $request->finance_comment ?? 'N/A' }}</td>
                                <td>
                                    {{ $request->checkedBy ? $request->checkedBy->full_name : 'Not Checked' }}
                                </td>
                                <td>
                                    <span class="badge bg-warning">{{ ucfirst($request->status) }}</span>
                                </td>
                                <td class="action-buttons">
                                    @if (empty($request->finance_comment))
                                        <button class="btn btn-success btn-sm" disabled
                                            title="Cannot approve - Missing finance verification">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button class="btn btn-danger btn-sm" disabled
                                            title="Cannot reject - Missing finance verification">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    @else
                                        <!-- Existing modal buttons -->
                                        <button class="btn btn-success btn-sm" data-toggle="modal"
                                            data-target="#approveModal{{ $request->purchase_request_id }}">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#rejectModal{{ $request->purchase_request_id }}">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    @endif
                                </td>
                            </tr>

                            <!-- Approval Modal -->
                            <div class="modal fade" id="approveModal{{ $request->purchase_request_id }}"
                                tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Approve Purchase Request</h5>
                                            <button type="button" class="close"
                                                data-dismiss="modal">&times;</button>
                                        </div>
                                        <form
                                            action="{{ route('manager.purchase.approve', ['purchase_request_id' => $request->purchase_request_id]) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label
                                                        for="manager-comment-approve{{ $request->purchase_request_id }}">Manager
                                                        Comments:</label>
                                                    <textarea class="form-control" id="manager-comment-approve{{ $request->purchase_request_id }}"
                                                        name="manager_comment" rows="3" required></textarea>
                                                </div>
                                                <div>
                                                    <strong>Attachments:</strong>
                                                    <ul>
                                                        @foreach (json_decode($request->attachments, true) as $attachment)
                                                            <li><a href="{{ asset($attachment) }}"
                                                                    target="_blank">{{ basename($attachment) }}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-success">Approve</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Rejection Modal -->
                            <div class="modal fade" id="rejectModal{{ $request->purchase_request_id }}"
                                tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reject Purchase Request</h5>
                                            <button type="button" class="close"
                                                data-dismiss="modal">&times;</button>
                                        </div>
                                        <form
                                            action="{{ route('manager.purchase.reject', ['purchase_request_id' => $request->purchase_request_id]) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label
                                                        for="manager-comment-reject{{ $request->purchase_request_id }}">Manager
                                                        Comments:</label>
                                                    <textarea class="form-control" id="manager-comment-reject{{ $request->purchase_request_id }}" name="manager_comment"
                                                        rows="3" required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-danger">Reject</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Pending Payrolls -->
        <div class="table-container">
            <h5 class="text-primary">Pending Payrolls</h5>
            @if ($pendingPayrolls->isEmpty())
                <p class="no-requests">No pending payrolls at this time.</p>
            @else
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Employee</th>
                            <th>Job Title</th>
                            <th>Pay Period</th>
                            <th>Basic Salary</th>
                            <th>Transport Allowance</th>
                            <th>Taxable Transport Allowance</th>
                            <th>Position Allowance</th>
                            <th>Total Taxable Earnings</th>
                            <th>Income Tax</th>
                            <th>Pension Fund 7%</th>
                            <th>Pension Fund 11%</th>
                            <th>Salary Advance</th>
                            <th>Net Salary</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendingPayrolls as $payroll)
                            <tr>
                                <td>{{ $payroll->employee->full_name }}</td>
                                <td>{{ $payroll->job_title }}</td>
                                <td>{{ $payroll->pay_period }}</td>
                                <td>${{ number_format($payroll->basic_salary, 2) }}</td>
                                <td>${{ number_format($payroll->transport_allowance ?? 0, 2) }}</td>
                                <td>${{ number_format($payroll->taxable_transport_allowance ?? 0, 2) }}</td>
                                <td>${{ number_format($payroll->position_allowance ?? 0, 2) }}</td>
                                <td>${{ number_format($payroll->total_taxable_earnings, 2) }}</td>
                                <td>${{ number_format($payroll->income_tax, 2) }}</td>
                                <td>${{ number_format($payroll->pension_fund_7, 2) }}</td>
                                <td>${{ number_format($payroll->pension_fund_11, 2) }}</td>
                                <td>${{ number_format($payroll->salary_advance ?? 0, 2) }}</td>
                                <td>${{ number_format($payroll->net_payment, 2) }}</td>
                                <td>{{ ucfirst($payroll->status) }}</td>
                                <td class="action-buttons">
                                    <!-- Approve Button -->
                                    <button class="btn btn-success btn-sm" data-toggle="modal"
                                        data-target="#approveModal{{ $payroll->payroll_id }}">
                                        <i class="fas fa-check"></i> Approve
                                    </button>

                                    <!-- Reject Button -->
                                    <button class="btn btn-danger btn-sm" data-toggle="modal"
                                        data-target="#rejectModal{{ $payroll->payroll_id }}">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </td>
                            </tr>

                            <!-- Approval Modal -->
                            <div class="modal fade" id="approveModal{{ $payroll->payroll_id }}" tabindex="-1"
                                role="dialog" aria-labelledby="approveModalLabel{{ $payroll->payroll_id }}"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Approve Payroll</h5>
                                            <button type="button" class="close"
                                                data-dismiss="modal">&times;</button>
                                        </div>
                                        <form
                                            action="{{ route('manager.payroll.approve', ['payroll_id' => $payroll->payroll_id]) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label
                                                        for="manager-comments-approve{{ $payroll->payroll_id }}">Manager
                                                        Comments</label>
                                                    <textarea class="form-control" id="manager-comments-approve{{ $payroll->payroll_id }}" name="manager_comment"
                                                        rows="3" required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-success">Approve</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Rejection Modal -->
                            <div class="modal fade" id="rejectModal{{ $payroll->payroll_id }}" tabindex="-1"
                                role="dialog" aria-labelledby="rejectModalLabel{{ $payroll->payroll_id }}"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reject Payroll</h5>
                                            <button type="button" class="close"
                                                data-dismiss="modal">&times;</button>
                                        </div>
                                        <form
                                            action="{{ route('manager.payroll.reject', ['payroll_id' => $payroll->payroll_id]) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label
                                                        for="manager-comments-reject{{ $payroll->payroll_id }}">Manager
                                                        Comments</label>
                                                    <textarea class="form-control" id="manager-comments-reject{{ $payroll->payroll_id }}" name="manager_comment"
                                                        rows="3" required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-danger">Reject</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            $(function() {
                $('[data-toggle="tooltip"]').tooltip();
            });
        </script>
</body>

</html>
