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
        <h2 class="header text-center"> Pending Requests</h2>
        <a class="btn btn-outline-primary" href="{{ route('manager.dashboard') }}">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>

        {{-- @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif --}}

        <div class="table-container">
            <h5 class="text-primary mt-5">Pending Stock Requisition Requests</h5>
            @if ($pendingStockRequisitionRequests->isEmpty())
                <p class="no-requests">No pending stock requisition requests at this time.</p>
            @else
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Item Name</th>
                            <th>Description</th>
                            <th>Quantity Requested</th>
                            <th>Status</th>
                            <th>Requested By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendingStockRequisitionRequests as $request)
                            <tr>
                                <td>{{ $request->item_name }}</td>
                                <td>{{ $request->description ?? 'N/A' }}</td>
                                <td>{{ $request->quantity_requested }} {{ $request->unit_requested ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-warning">{{ ucfirst($request->status) }}</span>
                                </td>
                                <td>{{ optional($request->user)->full_name ?? 'N/A' }}</td>
                                <td class="action-buttons">
                                    <!-- Approve Button -->
                                    <button class="btn btn-success btn-sm" data-toggle="modal"
                                        data-target="#approveModal{{ $request->stock_requisition_id }}">
                                        <i class="fas fa-check"></i> Approve
                                    </button>

                                    <!-- Reject Button -->
                                    <button class="btn btn-danger btn-sm" data-toggle="modal"
                                        data-target="#rejectModal{{ $request->stock_requisition_id }}">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </td>
                            </tr>

                            <!-- Approval Modal -->
                            <div class="modal fade" id="approveModal{{ $request->stock_requisition_id }}" tabindex="-1"
                                role="dialog" aria-labelledby="approveModalLabel{{ $request->stock_requisition_id }}"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Approve Stock Requisition</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <form
                                            action="{{ route('manager.stock_requisition.approve', $request->stock_requisition_id) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label
                                                        for="approved_quantity{{ $request->stock_requisition_id }}">Approved
                                                        Quantity:</label>
                                                    <input type="number" class="form-control"
                                                        id="approved_quantity{{ $request->stock_requisition_id }}"
                                                        name="approved_quantity" min="1"
                                                        max="{{ $request->quantity_requested }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        for="comment-approve{{ $request->stock_requisition_id }}">Comments:</label>
                                                    <textarea class="form-control" id="comment-approve{{ $request->stock_requisition_id }}" name="manager_comment"
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
                            <div class="modal fade" id="rejectModal{{ $request->stock_requisition_id }}" tabindex="-1"
                                role="dialog" aria-labelledby="rejectModalLabel{{ $request->stock_requisition_id }}"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reject Stock Requisition</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <form
                                            action="{{ route('manager.stock_requisition.reject', $request->stock_requisition_id) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label
                                                        for="comment-reject{{ $request->stock_requisition_id }}">Comments:</label>
                                                    <textarea class="form-control" id="comment-reject{{ $request->stock_requisition_id }}" name="manager_comment"
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
                            <div class="modal fade" id="approveModal{{ $plan->plan_id }}" tabindex="-1"
                                role="dialog" aria-labelledby="approveModalLabel{{ $plan->plan_id }}"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Approve Employee Plan</h5>
                                            <button type="button" class="close"
                                                data-dismiss="modal">&times;</button>
                                        </div>
                                        <form action="{{ route('manager.plan.approve', $plan->plan_id) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="manager-comments-approve{{ $plan->plan_id }}">Manager
                                                        Comments:</label>
                                                    <textarea class="form-control" id="manager-comments-approve{{ $plan->plan_id }}" name="manager_comments"
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
                            <div class="modal fade" id="rejectModal{{ $plan->plan_id }}" tabindex="-1"
                                role="dialog" aria-labelledby="rejectModalLabel{{ $plan->plan_id }}"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reject Employee Plan</h5>
                                            <button type="button" class="close"
                                                data-dismiss="modal">&times;</button>
                                        </div>
                                        <form action="{{ route('manager.plan.reject', $plan->plan_id) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="manager-comments-reject{{ $plan->plan_id }}">Manager
                                                        Comments:</label>
                                                    <textarea class="form-control" id="manager-comments-reject{{ $plan->plan_id }}" name="manager_comments"
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
                            <th>Company Country</th>
                            <th>Reference Number</th>
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
                                <td>{{ $request->company_country }}</td>
                                <td>{{ $request->reference_number }}</td>
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
                            <th>Item Name</th>
                            <th>Item Description</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Price</th>
                            <th>Total Price</th>
                            <th>Company Country</th>
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
                                <td>{{ $request->item_name }}</td>
                                <td>{{ $request->item_description ?? 'N/A' }}</td>
                                <td>{{ $request->quantity }}</td>
                                <td>{{ $request->unit }}</td>
                                <td>{{ $request->price }} {{ $request->currency }}</td>
                                <td>{{ $request->total_price }} {{ $request->currency }}</td>
                                <td>{{ $request->company_country }}</td>
                                <td>{{ $request->user->full_name }}</td>
                                <td>{{ $request->finance_comment ?? 'N/A' }}</td>
                                <td>{{ optional(\App\Models\User::find($request->checked_by))->full_name ?? 'N/A' }}
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
                                                    <textarea class="form-control" id="manager-comment-reject{{ $request->purchase_request_id }}"
                                                        name="manager_comment" rows="3" required></textarea>
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
                            {{-- <th>Reference Number</th> --}}
                            <th>Request Date</th>
                            <th>Department</th>
                            <th>Finance Comment</th>
                            {{-- <th>Item Description</th> --}}
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Amount</th>
                            <th>VAT</th>
                            <th>Total</th>
                            <th>Net Payment</th>
                            <th>Withholding</th>
                            <th>Status</th>
                            <th>Manager Comment</th>
                            <th>Checked By</th>
                            {{-- <th>Approved By</th> --}}
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendingPaymentRequests as $request)
                            <tr>
                                {{-- <td>{{ $request->reference_no ?? 'N/A' }}</td> --}}
                                <td>{{ $request->request_date ? $request->request_date->format('Y-m-d') : 'N/A' }}
                                </td>
                                <td>{{ $request->department ?? 'N/A' }}</td>
                                <td>{{ $request->finance_comment ?? 'N/A' }}</td>
                                {{-- <td>{{ $request->item_description }}</td> --}}
                                <td>{{ $request->item_name ?? 'N/A' }}</td>
                                <td>{{ $request->quantity ?? 'N/A' }} {{ $request->unit ?? 'N/A' }}</td>
                                <td>{{ number_format($request->amount, 2) }} {{ $request->currency ?? 'N/A' }}</td>
                                <td>{{ number_format($request->vat, 2) ?? '0.00' }}
                                    {{ $request->currency ?? 'N/A' }}
                                </td>
                                <td>{{ number_format($request->total, 2) }} {{ $request->currency ?? 'N/A' }}</td>
                                <td>{{ number_format($request->net_payment, 2) }} {{ $request->currency ?? 'N/A' }}
                                </td>
                                <td>{{ number_format($request->with_holding, 2) ?? '0.00' }}
                                    {{ $request->currency ?? 'N/A' }}</td>
                                <td>
                                    <span
                                        class="badge {{ $request->status === 'approved' ? 'bg-success' : ($request->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td>{{ $request->manager_comment ?? 'N/A' }}</td>
                                <td>{{ $request->reviewed_by ? optional(\App\Models\User::find($request->reviewed_by))->full_name : 'Not Checked' }}
                                </td>
                                {{-- <td>{{ optional(\App\Models\User::find($request->approved_by))->full_name ?? 'N/A' }}
                                </td> --}}
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
                                        <button class="btn btn-success btn-sm" data-toggle="modal"
                                            data-target="#approveModal{{ $request->payment_request_id }}">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#rejectModal{{ $request->payment_request_id }}">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    @endif
                                </td>
                            </tr>

                            <!-- Approval Modal -->
                            <div class="modal fade" id="approveModal{{ $request->payment_request_id }}"
                                tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Approve Payment Request</h5>
                                            <button type="button" class="close"
                                                data-dismiss="modal">&times;</button>
                                        </div>
                                        <form
                                            action="{{ route('manager.payment.approve', ['payment_request_id' => $request->payment_request_id]) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label
                                                        for="manager-comment-approve{{ $request->payment_request_id }}">Manager
                                                        Comments:</label>
                                                    <textarea class="form-control" id="manager-comment-approve{{ $request->payment_request_id }}"
                                                        name="manager_comment" rows="3" required></textarea>
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
                            <div class="modal fade" id="rejectModal{{ $request->payment_request_id }}"
                                tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reject Payment Request</h5>
                                            <button type="button" class="close"
                                                data-dismiss="modal">&times;</button>
                                        </div>
                                        <form
                                            action="{{ route('manager.payment.reject', ['payment_request_id' => $request->payment_request_id]) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label
                                                        for="manager-comment-reject{{ $request->payment_request_id }}">Manager
                                                        Comments:</label>
                                                    <textarea class="form-control" id="manager-comment-reject{{ $request->payment_request_id }}" name="manager_comment"
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
                                <td>{{ number_format($payroll->basic_salary, 2) }} {{ $payroll->currency }}</td>
                                <td>{{ number_format($payroll->transport_allowance ?? 0, 2) }}
                                    {{ $payroll->currency }}</td>
                                <td>{{ number_format($payroll->taxable_transport_allowance ?? 0, 2) }}
                                    {{ $payroll->currency }}</td>
                                <td>{{ number_format($payroll->position_allowance ?? 0, 2) }}
                                    {{ $payroll->currency }}</td>
                                <td>{{ number_format($payroll->total_taxable_earnings, 2) }}
                                    {{ $payroll->currency }}</td>
                                <td>{{ number_format($payroll->income_tax, 2) }} {{ $payroll->currency }}</td>
                                <td>{{ number_format($payroll->pension_fund_7, 2) }} {{ $payroll->currency }}</td>
                                <td>{{ number_format($payroll->pension_fund_11, 2) }} {{ $payroll->currency }}</td>
                                <td>{{ number_format($payroll->salary_advance ?? 0, 2) }} {{ $payroll->currency }}
                                </td>
                                <td>{{ number_format($payroll->net_payment, 2) }} {{ $payroll->currency }}</td>
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

        <!-- Pending Budget Forecasts -->
        <div class="table-container">
            <h5 class="text-primary">Pending Budget Forecasts</h5>
            @if ($pendingBudgetForecasts->isEmpty())
                <p class="no-requests">No pending budget forecasts at this time.</p>
            @else
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Forecast Period</th>
                            <th>Budget Description</th>
                            <th>Department</th>
                            <th>Budget Source</th>
                            <th>Quantity</th>
                            <th>Unit Cost</th>
                            <th>Total Budget</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendingBudgetForecasts as $forecast)
                            <tr>
                                <td>{{ ucfirst($forecast->forecast_period) }}</td>
                                <td>{{ $forecast->budget_description }}</td>
                                <td>{{ $forecast->department }}</td>
                                <td>{{ $forecast->budget_source }}</td>
                                <td>{{ $forecast->quantity }} {{ $forecast->unit }}</td>
                                <td>{{ number_format($forecast->unit_cost, 2) }} {{ $forecast->currency }}</td>
                                <td>{{ number_format($forecast->total_budget, 2) }} {{ $forecast->currency }}</td>
                                <td class="action-buttons">
                                    <!-- Approve Button -->
                                    <button class="btn btn-success btn-sm" data-toggle="modal"
                                        data-target="#approveModal{{ $forecast->budget_id }}">
                                        <i class="fas fa-check"></i> Approve
                                    </button>

                                    <!-- Reject Button -->
                                    <button class="btn btn-danger btn-sm" data-toggle="modal"
                                        data-target="#rejectModal{{ $forecast->budget_id }}">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </td>
                            </tr>

                            <!-- Approval Modal -->
                            <div class="modal fade" id="approveModal{{ $forecast->budget_id }}" tabindex="-1"
                                role="dialog" aria-labelledby="approveModalLabel{{ $forecast->budget_id }}"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Approve Budget</h5>
                                            <button type="button" class="close"
                                                data-dismiss="modal">&times;</button>
                                        </div>
                                        <form
                                            action="{{ route('manager.budget.approve', ['budget_id' => $forecast->budget_id]) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label
                                                        for="manager-comments-approve{{ $forecast->budget_id }}">Manager
                                                        Comments</label>
                                                    <textarea class="form-control" id="manager-comments-approve{{ $forecast->budget_id }}" name="manager_comment"
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
                            <div class="modal fade" id="rejectModal{{ $forecast->budget_id }}" tabindex="-1"
                                role="dialog" aria-labelledby="rejectModalLabel{{ $forecast->budget_id }}"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reject Budget</h5>
                                            <button type="button" class="close"
                                                data-dismiss="modal">&times;</button>
                                        </div>
                                        <form
                                            action="{{ route('manager.budget.reject', ['budget_id' => $forecast->budget_id]) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label
                                                        for="manager-comments-reject{{ $forecast->budget_id }}">Manager
                                                        Comments</label>
                                                    <textarea class="form-control" id="manager-comments-reject{{ $forecast->budget_id }}" name="manager_comment"
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

        <div class="table-container">
            <h5 class="text-primary mt-5">Pending Petty Cash Requests</h5>
            @if ($pendingPettyCashRequests->isEmpty())
                <p class="no-requests">No pending petty cash requests at this time.</p>
            @else
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Amount Requested</th>
                            <th>Currency</th>
                            <th>Status</th>
                            <th>Requested By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendingPettyCashRequests as $request)
                            <tr>
                                <td>{{ $request->title }}</td>
                                <td>{{ $request->description ?? 'N/A' }}</td>
                                <td>{{ number_format($request->amount_requested, 2) }} {{ $request->currency }}</td>
                                <td>{{ $request->currency }}</td>
                                <td>
                                    <span class="badge bg-warning">{{ ucfirst($request->status) }}</span>
                                </td>
                                <td>{{ optional($request->user)->full_name ?? 'N/A' }}</td>
                                <td class="action-buttons">
                                    <button class="btn btn-success btn-sm" data-toggle="modal"
                                        data-target="#approveModal{{ $request->petty_cash_request_id }}">
                                        <i class="fas fa-check"></i> Approve
                                    </button>

                                    <button class="btn btn-danger btn-sm" data-toggle="modal"
                                        data-target="#rejectModal{{ $request->petty_cash_request_id }}">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </td>
                            </tr>

                            <!-- Approval Modal -->
                            <div class="modal fade" id="approveModal{{ $request->petty_cash_request_id }}"
                                tabindex="-1" role="dialog"
                                aria-labelledby="approveModalLabel{{ $request->petty_cash_request_id }}"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Approve Petty Cash Request</h5>
                                            <button type="button" class="close"
                                                data-dismiss="modal">&times;</button>
                                        </div>
                                        <form
                                            action="{{ route('manager.petty_cash.approve', $request->petty_cash_request_id) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label
                                                        for="amount_approved{{ $request->petty_cash_request_id }}">Approved
                                                        Amount:</label>
                                                    <input type="number" class="form-control"
                                                        id="amount_approved{{ $request->petty_cash_request_id }}"
                                                        name="amount_approved" min="1" required
                                                        oninput="convertToWords(this.value, '{{ $request->petty_cash_request_id }}')">
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        for="amount_approved_in_words{{ $request->petty_cash_request_id }}">Approved
                                                        Amount in Words:</label>
                                                    <input type="text" class="form-control"
                                                        id="amount_approved_in_words{{ $request->petty_cash_request_id }}"
                                                        name="amount_approved_in_words" readonly required>
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        for="comment-approve{{ $request->petty_cash_request_id }}">Comments:</label>
                                                    <textarea class="form-control" id="comment-approve{{ $request->petty_cash_request_id }}" name="manager_comment"
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
                            <div class="modal fade" id="rejectModal{{ $request->petty_cash_request_id }}"
                                tabindex="-1" role="dialog"
                                aria-labelledby="rejectModalLabel{{ $request->petty_cash_request_id }}"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reject Petty Cash Request</h5>
                                            <button type="button" class="close"
                                                data-dismiss="modal">&times;</button>
                                        </div>
                                        <form
                                            action="{{ route('manager.petty_cash.reject', $request->petty_cash_request_id) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label
                                                        for="comment-reject{{ $request->petty_cash_request_id }}">Comments:</label>
                                                    <textarea class="form-control" id="comment-reject{{ $request->petty_cash_request_id }}" name="manager_comment"
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

        <script>
            function convertToWords(amount, requestId) {
                const words = [
                    '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine',
                    'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen',
                    'seventeen', 'eighteen', 'nineteen', 'twenty', 'thirty', 'forty', 'fifty',
                    'sixty', 'seventy', 'eighty', 'ninety'
                ];

                const scales = ['', 'thousand', 'million', 'billion'];

                if (amount < 0) {
                    return 'minus ' + convertToWords(Math.abs(amount), requestId);
                }

                if (amount === 0) {
                    return 'zero';
                }

                let numberInWords = '';
                let scaleIndex = 0;

                while (amount > 0) {
                    let chunk = amount % 1000;
                    if (chunk > 0) {
                        let chunkInWords = '';

                        if (chunk >= 100) {
                            chunkInWords += words[Math.floor(chunk / 100)] + ' hundred';
                            chunk %= 100;
                            if (chunk > 0) {
                                chunkInWords += ' and ';
                            }
                        }

                        if (chunk < 20) {
                            chunkInWords += words[chunk];
                        } else {
                            chunkInWords += words[Math.floor(chunk / 10) * 10];
                            if (chunk % 10 > 0) {
                                chunkInWords += ' ' + words[chunk % 10];
                            }
                        }

                        numberInWords = chunkInWords + (scales[scaleIndex] ? ' ' + scales[scaleIndex] : '') + ' ' +
                            numberInWords;
                    }
                    amount = Math.floor(amount / 1000);
                    scaleIndex++;
                }

                // Update the input for approved amount in words
                document.getElementById('amount_approved_in_words' + requestId).value = numberInWords.trim();
            }
        </script>



        <!-- Optional: Add Bootstrap Icons -->

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
