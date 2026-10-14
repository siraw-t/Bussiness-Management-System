@extends('layouts.app')

@section('content')
<style>
    .page-head {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 12px; margin-bottom: 22px;
    }
    .page-head h1 {
        font-size: 1.6rem; font-weight: 800; margin: 0;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
    .page-head p { color: var(--text-secondary); margin: 4px 0 0; font-size: 0.88rem; }

    .btn-back {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 16px; border-radius: 12px;
        background: var(--bg-card); color: var(--text-primary);
        border: 1px solid var(--border-color);
        font-weight: 600; font-size: 0.85rem;
        text-decoration: none; transition: var(--transition);
    }
    .btn-back:hover { border-color: var(--primary); color: var(--primary); transform: translateY(-1px); }

    .section-card {
        background: var(--bg-card); border: 1px solid var(--border-color);
        border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm);
        margin-bottom: 24px;
    }
    .section-head {
        padding: 16px 22px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-bottom: 1px solid var(--border-color);
        display: flex; align-items: center; gap: 10px;
    }
    [data-bs-theme="dark"] .section-head { background: #0b1120; }
    .section-head h5 { font-size: 0.95rem; font-weight: 700; margin: 0; color: var(--text-primary); display: flex; align-items: center; gap: 10px; }
    .section-head .badge-count {
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: #fff; font-size: 0.72rem; font-weight: 700;
        padding: 3px 10px; border-radius: 999px;
    }

    .table-modern { width: 100%; margin: 0; border-collapse: separate; border-spacing: 0; }
    .table-modern thead th {
        background: #fff;
        color: var(--text-secondary);
        font-weight: 700; font-size: 0.71rem;
        text-transform: uppercase; letter-spacing: 0.08em;
        padding: 12px 16px; text-align: left;
        border-bottom: 1px solid var(--border-color); white-space: nowrap;
    }
    [data-bs-theme="dark"] .table-modern thead th { background: #131c31; }
    .table-modern tbody td {
        padding: 13px 16px;
        border-bottom: 1px solid var(--border-color);
        font-size: 0.84rem; color: var(--text-primary); vertical-align: top;
    }
    .table-modern tbody tr { transition: background-color 0.15s ease; }
    .table-modern tbody tr:hover { background: var(--primary-soft); }
    .table-modern tbody tr:last-child td { border-bottom: none; }

    .status-badge {
        display: inline-flex; align-items: center;
        padding: 4px 11px; border-radius: 999px;
        font-size: 0.73rem; font-weight: 600; text-transform: capitalize;
    }
    .status-approved { background: #d1fae5; color: #065f46; }
    .status-pending  { background: #fef3c7; color: #92400e; }
    .status-rejected { background: #fee2e2; color: #991b1b; }

    .action-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 5px;
        padding: 7px 11px; border-radius: 9px;
        font-size: 0.78rem; font-weight: 600; border: none; cursor: pointer;
        transition: var(--transition); white-space: nowrap;
    }
    .action-btn.approve { background: #d1fae5; color: #065f46; }
    .action-btn.approve:hover:not(:disabled) { background: #a7f3d0; transform: translateY(-1px); }
    .action-btn.reject  { background: #fee2e2; color: #991b1b; }
    .action-btn.reject:hover:not(:disabled)  { background: #fecaca; transform: translateY(-1px); }
    .action-btn:disabled { opacity: 0.45; cursor: not-allowed; }

    .action-buttons { white-space: nowrap; text-align: right; }
    .action-buttons .action-btn + .action-btn { margin-left: 5px; }

    .empty-state {
        padding: 40px 20px; text-align: center; color: var(--text-secondary);
        font-size: 0.88rem;
    }
    .empty-state i { font-size: 2rem; opacity: 0.35; margin-bottom: 10px; display: block; }

    textarea.form-control { font-size: 0.85rem; }

    @media (max-width: 1200px) {
        .section-card { overflow-x: auto; }
        .table-modern { min-width: 900px; }
    }
</style>

<div class="page-head">
    <div>
        <h1>Pending Requests</h1>
        <p>Review all pending items requiring your approval</p>
    </div>
    <a href="{{ route('manager.dashboard') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
</div>

{{-- ==================== 1. PENDING STOCK REQUISITIONS ==================== --}}
<div class="section-card">
    <div class="section-head">
        <h5><i class="bi bi-box-seam text-primary"></i> Pending Stock Requisition Requests
            @if (!$pendingStockRequisitionRequests->isEmpty())
                <span class="badge-count">{{ $pendingStockRequisitionRequests->count() }}</span>
            @endif
        </h5>
    </div>
    @if ($pendingStockRequisitionRequests->isEmpty())
        <div class="empty-state"><i class="bi bi-inbox"></i>No pending stock requisition requests at this time.</div>
    @else
        <table class="table-modern">
            <thead>
                <tr><th>Item Name</th><th>Description</th><th>Qty Requested</th><th>Status</th><th>Requested By</th><th style="text-align:right;">Actions</th></tr>
            </thead>
            <tbody>
                @foreach ($pendingStockRequisitionRequests as $request)
                    <tr>
                        <td>{{ $request->item_name }}</td>
                        <td>{{ $request->description ?? 'N/A' }}</td>
                        <td>{{ $request->quantity_requested }} {{ $request->unit_requested ?? 'N/A' }}</td>
                        <td><span class="status-badge status-{{ $request->status }}">{{ ucfirst($request->status) }}</span></td>
                        <td>{{ optional($request->user)->full_name ?? 'N/A' }}</td>
                        <td class="action-buttons">
                            <button class="action-btn approve" data-bs-toggle="modal" data-bs-target="#approveModalStock{{ $request->stock_requisition_id }}">
                                <i class="fas fa-check"></i> Approve
                            </button>
                            <button class="action-btn reject" data-bs-toggle="modal" data-bs-target="#rejectModalStock{{ $request->stock_requisition_id }}">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </td>
                    </tr>

                    <div class="modal fade" id="approveModalStock{{ $request->stock_requisition_id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Approve Stock Requisition</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('manager.stock_requisition.approve', $request->stock_requisition_id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label" for="approved_quantity{{ $request->stock_requisition_id }}">Approved Quantity:</label>
                                            <input type="number" class="form-control" id="approved_quantity{{ $request->stock_requisition_id }}" name="approved_quantity" min="1" max="{{ $request->quantity_requested }}" required>
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label" for="comment-approve{{ $request->stock_requisition_id }}">Comments:</label>
                                            <textarea class="form-control" id="comment-approve{{ $request->stock_requisition_id }}" name="manager_comment" rows="3" required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-success">Approve</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="rejectModalStock{{ $request->stock_requisition_id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Reject Stock Requisition</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('manager.stock_requisition.reject', $request->stock_requisition_id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="mb-0">
                                            <label class="form-label" for="comment-reject{{ $request->stock_requisition_id }}">Comments:</label>
                                            <textarea class="form-control" id="comment-reject{{ $request->stock_requisition_id }}" name="manager_comment" rows="3" required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

{{-- ==================== 2. PENDING EMPLOYEE PLANS ==================== --}}
<div class="section-card">
    <div class="section-head">
        <h5><i class="bi bi-calendar-week text-primary"></i> Pending Employee Plans
            @if (!$pendingEmployeePlans->isEmpty())<span class="badge-count">{{ $pendingEmployeePlans->count() }}</span>@endif
        </h5>
    </div>
    @if ($pendingEmployeePlans->isEmpty())
        <div class="empty-state"><i class="bi bi-inbox"></i>No pending employee plans at this time.</div>
    @else
        <table class="table-modern">
            <thead>
                <tr><th>Employee</th><th>Week</th><th>Plan</th><th>Submitted At</th><th>Urgent Issues</th><th>Status</th><th>Reference</th><th style="text-align:right;">Actions</th></tr>
            </thead>
            <tbody>
                @foreach ($pendingEmployeePlans as $plan)
                    <tr>
                        <td>{{ $plan->employee->full_name ?? 'N/A' }}</td>
                        <td>{{ $plan->week }}</td>
                        <td>{{ $plan->plan }}</td>
                        <td>{{ $plan->submitted_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $plan->urgent_issues ?? 'N/A' }}</td>
                        <td><span class="status-badge status-{{ $plan->status }}">{{ ucfirst($plan->status) }}</span></td>
                        <td>{{ $plan->reference_number }}</td>
                        <td class="action-buttons">
                            <button class="action-btn approve" data-bs-toggle="modal" data-bs-target="#approveModalPlan{{ $plan->plan_id }}"><i class="fas fa-check"></i> Approve</button>
                            <button class="action-btn reject" data-bs-toggle="modal" data-bs-target="#rejectModalPlan{{ $plan->plan_id }}"><i class="fas fa-times"></i> Reject</button>
                        </td>
                    </tr>

                    <div class="modal fade" id="approveModalPlan{{ $plan->plan_id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Approve Employee Plan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('manager.plan.approve', $plan->plan_id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <label class="form-label" for="manager-comments-approve{{ $plan->plan_id }}">Manager Comments:</label>
                                        <textarea class="form-control" id="manager-comments-approve{{ $plan->plan_id }}" name="manager_comments" rows="3" required></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-success">Approve</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="rejectModalPlan{{ $plan->plan_id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Reject Employee Plan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('manager.plan.reject', $plan->plan_id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <label class="form-label" for="manager-comments-reject{{ $plan->plan_id }}">Manager Comments:</label>
                                        <textarea class="form-control" id="manager-comments-reject{{ $plan->plan_id }}" name="manager_comments" rows="3" required></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

{{-- ==================== 3. PENDING EMPLOYEE REPORTS ==================== --}}
<div class="section-card">
    <div class="section-head">
        <h5><i class="bi bi-file-earmark-text text-primary"></i> Pending Employee Reports
            @if (!$pendingEmployeeReports->isEmpty())<span class="badge-count">{{ $pendingEmployeeReports->count() }}</span>@endif
        </h5>
    </div>
    @if ($pendingEmployeeReports->isEmpty())
        <div class="empty-state"><i class="bi bi-inbox"></i>No pending employee reports at this time.</div>
    @else
        <table class="table-modern">
            <thead>
                <tr><th>Employee</th><th>Week</th><th>Last Week's Plan</th><th>Report</th><th>Submitted At</th><th>Status</th><th style="text-align:right;">Actions</th></tr>
            </thead>
            <tbody>
                @foreach ($pendingEmployeeReports as $report)
                    @php
                        $lastWeekPlan = \App\Models\EmployeePlan::where('employee_id', $report->employee_id)
                            ->where('week', $report->week)->orderBy('week', 'desc')->first();
                    @endphp
                    <tr>
                        <td>{{ $report->employee->full_name ?? 'N/A' }}</td>
                        <td>{{ $report->week }}</td>
                        <td>@if ($lastWeekPlan) {{ $lastWeekPlan->plan }} @else <span class="text-muted">No plan available</span> @endif</td>
                        <td>{{ $report->report }}</td>
                        <td>{{ $report->submitted_at->format('Y-m-d H:i') }}</td>
                        <td><span class="status-badge status-{{ $report->status }}">{{ ucfirst($report->status) }}</span></td>
                        <td class="action-buttons">
                            <button class="action-btn approve" data-bs-toggle="modal" data-bs-target="#approveModalReport{{ $report->report_id }}"><i class="fas fa-check"></i> Approve</button>
                            <button class="action-btn reject" data-bs-toggle="modal" data-bs-target="#rejectModalReport{{ $report->report_id }}"><i class="fas fa-times"></i> Reject</button>
                        </td>
                    </tr>

                    <div class="modal fade" id="approveModalReport{{ $report->report_id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header"><h5 class="modal-title">Approve Employee Report</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                                <form action="{{ route('manager.report.approve', $report->report_id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <label class="form-label" for="manager-comments-approve-r{{ $report->report_id }}">Manager Comments:</label>
                                        <textarea class="form-control" id="manager-comments-approve-r{{ $report->report_id }}" name="manager_comments" rows="3" required></textarea>
                                    </div>
                                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-success">Approve</button></div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="rejectModalReport{{ $report->report_id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header"><h5 class="modal-title">Reject Employee Report</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                                <form action="{{ route('manager.report.reject', $report->report_id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <label class="form-label" for="manager-comments-reject-r{{ $report->report_id }}">Manager Comments:</label>
                                        <textarea class="form-control" id="manager-comments-reject-r{{ $report->report_id }}" name="manager_comments" rows="3" required></textarea>
                                    </div>
                                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-danger">Reject</button></div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

{{-- ==================== 4. PENDING LEAVE REQUESTS ==================== --}}
<div class="section-card">
    <div class="section-head">
        <h5><i class="bi bi-card-list text-primary"></i> Pending Leave Requests
            @if (!$pendingLeaveRequests->isEmpty())<span class="badge-count">{{ $pendingLeaveRequests->count() }}</span>@endif
        </h5>
    </div>
    @if ($pendingLeaveRequests->isEmpty())
        <div class="empty-state"><i class="bi bi-inbox"></i>No pending leave requests at this time.</div>
    @else
        <table class="table-modern">
            <thead>
                <tr><th>Leave Type</th><th>Other Reason</th><th>Requested By</th><th>File</th><th>Start</th><th>End</th><th>Status</th><th>Country</th><th>Reference</th><th style="text-align:right;">Actions</th></tr>
            </thead>
            <tbody>
                @foreach ($pendingLeaveRequests as $request)
                    <tr>
                        <td>{{ ucfirst(str_replace('_', ' ', $request->leave_type)) }}</td>
                        <td>{{ $request->other_reason ?? 'N/A' }}</td>
                        <td>{{ $request->user->full_name }}</td>
                        <td>
                            @if ($request->attachment)
                                <a href="{{ asset('storage/' . $request->attachment) }}" target="_blank" class="text-primary"><i class="bi bi-paperclip"></i> View</a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $request->start_date }}</td>
                        <td>{{ $request->end_date }}</td>
                        <td><span class="status-badge status-{{ $request->status }}">{{ ucfirst($request->status) }}</span></td>
                        <td>{{ $request->company_country }}</td>
                        <td>{{ $request->reference_number }}</td>
                        <td class="action-buttons">
                            <button class="action-btn approve" data-bs-toggle="modal" data-bs-target="#approveModalLeave{{ $request->leave_request_id }}"><i class="fas fa-check"></i> Approve</button>
                            <button class="action-btn reject" data-bs-toggle="modal" data-bs-target="#rejectModalLeave{{ $request->leave_request_id }}"><i class="fas fa-times"></i> Reject</button>
                        </td>
                    </tr>

                    <div class="modal fade" id="approveModalLeave{{ $request->leave_request_id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header"><h5 class="modal-title">Approve Leave Request</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                                <form action="{{ route('manager.leave.approve', ['leave_request_id' => $request->leave_request_id]) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <label class="form-label" for="manager-comments-approve-l{{ $request->leave_request_id }}">Manager Comments:</label>
                                        <textarea class="form-control" id="manager-comments-approve-l{{ $request->leave_request_id }}" name="manager_comments" rows="3" required></textarea>
                                    </div>
                                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-success">Approve</button></div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="rejectModalLeave{{ $request->leave_request_id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header"><h5 class="modal-title">Reject Leave Request</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                                <form action="{{ route('manager.leave.reject', ['leave_request_id' => $request->leave_request_id]) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <label class="form-label" for="manager-comments-reject-l{{ $request->leave_request_id }}">Manager Comments:</label>
                                        <textarea class="form-control" id="manager-comments-reject-l{{ $request->leave_request_id }}" name="manager_comments" rows="3" required></textarea>
                                    </div>
                                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-danger">Reject</button></div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

{{-- ==================== 5. PENDING PURCHASE REQUESTS ==================== --}}
<div class="section-card">
    <div class="section-head">
        <h5><i class="bi bi-cart text-primary"></i> Pending Purchase Requests
            @if (!$pendingPurchaseRequests->isEmpty())<span class="badge-count">{{ $pendingPurchaseRequests->count() }}</span>@endif
        </h5>
    </div>
    @if ($pendingPurchaseRequests->isEmpty())
        <div class="empty-state"><i class="bi bi-inbox"></i>No pending purchase requests at this time.</div>
    @else
        <table class="table-modern">
            <thead>
                <tr><th>Reference</th><th>Item</th><th>Description</th><th>Qty</th><th>Unit</th><th>Price</th><th>Total</th><th>Country</th><th>Requested By</th><th>Finance Comment</th><th>Checked By</th><th>Status</th><th style="text-align:right;">Actions</th></tr>
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
                        <td><strong>{{ $request->total_price }} {{ $request->currency }}</strong></td>
                        <td>{{ $request->company_country }}</td>
                        <td>{{ $request->user->full_name }}</td>
                        <td>{{ $request->finance_comment ?? 'N/A' }}</td>
                        <td>{{ optional(\App\Models\User::find($request->checked_by))->full_name ?? 'N/A' }}</td>
                        <td><span class="status-badge status-{{ $request->status }}">{{ ucfirst($request->status) }}</span></td>
                        <td class="action-buttons">
                            @if (empty($request->finance_comment))
                                <button class="action-btn approve" disabled title="Cannot approve - Missing finance verification"><i class="fas fa-check"></i> Approve</button>
                                <button class="action-btn reject" disabled title="Cannot reject - Missing finance verification"><i class="fas fa-times"></i> Reject</button>
                            @else
                                <button class="action-btn approve" data-bs-toggle="modal" data-bs-target="#approveModalPR{{ $request->purchase_request_id }}"><i class="fas fa-check"></i> Approve</button>
                                <button class="action-btn reject" data-bs-toggle="modal" data-bs-target="#rejectModalPR{{ $request->purchase_request_id }}"><i class="fas fa-times"></i> Reject</button>
                            @endif
                        </td>
                    </tr>

                    <div class="modal fade" id="approveModalPR{{ $request->purchase_request_id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header"><h5 class="modal-title">Approve Purchase Request</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                                <form action="{{ route('manager.purchase.approve', ['purchase_request_id' => $request->purchase_request_id]) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label" for="manager-comment-approve-pr{{ $request->purchase_request_id }}">Manager Comments:</label>
                                            <textarea class="form-control" id="manager-comment-approve-pr{{ $request->purchase_request_id }}" name="manager_comment" rows="3" required></textarea>
                                        </div>
                                        <div>
                                            <strong>Attachments:</strong>
                                            <ul class="mb-0">
                                                @foreach (json_decode($request->attachments, true) as $attachment)
                                                    <li><a href="{{ asset($attachment) }}" target="_blank">{{ basename($attachment) }}</a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-success">Approve</button></div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="rejectModalPR{{ $request->purchase_request_id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header"><h5 class="modal-title">Reject Purchase Request</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                                <form action="{{ route('manager.purchase.reject', ['purchase_request_id' => $request->purchase_request_id]) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <label class="form-label" for="manager-comment-reject-pr{{ $request->purchase_request_id }}">Manager Comments:</label>
                                        <textarea class="form-control" id="manager-comment-reject-pr{{ $request->purchase_request_id }}" name="manager_comment" rows="3" required></textarea>
                                    </div>
                                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-danger">Reject</button></div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

{{-- ==================== 6. PENDING PAYMENT REQUESTS ==================== --}}
<div class="section-card">
    <div class="section-head">
        <h5><i class="bi bi-cash-stack text-primary"></i> Pending Payment Requests
            @if (!$pendingPaymentRequests->isEmpty())<span class="badge-count">{{ $pendingPaymentRequests->count() }}</span>@endif
        </h5>
    </div>
    @if ($pendingPaymentRequests->isEmpty())
        <div class="empty-state"><i class="bi bi-inbox"></i>No pending payment requests at this time.</div>
    @else
        <table class="table-modern">
            <thead>
                <tr><th>Request Date</th><th>Department</th><th>Finance Comment</th><th>Item Name</th><th>Qty</th><th>Amount</th><th>VAT</th><th>Total</th><th>Net Payment</th><th>Withholding</th><th>Status</th><th>Manager Comment</th><th>Checked By</th><th style="text-align:right;">Actions</th></tr>
            </thead>
            <tbody>
                @foreach ($pendingPaymentRequests as $request)
                    <tr>
                        <td>{{ $request->request_date ? $request->request_date->format('Y-m-d') : 'N/A' }}</td>
                        <td>{{ $request->department ?? 'N/A' }}</td>
                        <td>{{ $request->finance_comment ?? 'N/A' }}</td>
                        <td>{{ $request->item_name ?? 'N/A' }}</td>
                        <td>{{ $request->quantity ?? 'N/A' }} {{ $request->unit ?? 'N/A' }}</td>
                        <td>{{ number_format($request->amount, 2) }} {{ $request->currency ?? 'N/A' }}</td>
                        <td>{{ number_format($request->vat, 2) ?? '0.00' }} {{ $request->currency ?? 'N/A' }}</td>
                        <td>{{ number_format($request->total, 2) }} {{ $request->currency ?? 'N/A' }}</td>
                        <td><strong>{{ number_format($request->net_payment, 2) }} {{ $request->currency ?? 'N/A' }}</strong></td>
                        <td>{{ number_format($request->with_holding, 2) ?? '0.00' }} {{ $request->currency ?? 'N/A' }}</td>
                        <td><span class="status-badge status-{{ $request->status }}">{{ ucfirst($request->status) }}</span></td>
                        <td>{{ $request->manager_comment ?? 'N/A' }}</td>
                        <td>{{ $request->reviewed_by ? optional(\App\Models\User::find($request->reviewed_by))->full_name : 'Not Checked' }}</td>
                        <td class="action-buttons">
                            @if (empty($request->finance_comment))
                                <button class="action-btn approve" disabled title="Missing finance verification"><i class="fas fa-check"></i> Approve</button>
                                <button class="action-btn reject" disabled title="Missing finance verification"><i class="fas fa-times"></i> Reject</button>
                            @else
                                <button class="action-btn approve" data-bs-toggle="modal" data-bs-target="#approveModalPAY{{ $request->payment_request_id }}"><i class="fas fa-check"></i> Approve</button>
                                <button class="action-btn reject" data-bs-toggle="modal" data-bs-target="#rejectModalPAY{{ $request->payment_request_id }}"><i class="fas fa-times"></i> Reject</button>
                            @endif
                        </td>
                    </tr>

                    <div class="modal fade" id="approveModalPAY{{ $request->payment_request_id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header"><h5 class="modal-title">Approve Payment Request</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                                <form action="{{ route('manager.payment.approve', ['payment_request_id' => $request->payment_request_id]) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <label class="form-label" for="manager-comment-approve-pay{{ $request->payment_request_id }}">Manager Comments:</label>
                                        <textarea class="form-control" id="manager-comment-approve-pay{{ $request->payment_request_id }}" name="manager_comment" rows="3" required></textarea>
                                    </div>
                                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-success">Approve</button></div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="rejectModalPAY{{ $request->payment_request_id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header"><h5 class="modal-title">Reject Payment Request</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                                <form action="{{ route('manager.payment.reject', ['payment_request_id' => $request->payment_request_id]) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <label class="form-label" for="manager-comment-reject-pay{{ $request->payment_request_id }}">Manager Comments:</label>
                                        <textarea class="form-control" id="manager-comment-reject-pay{{ $request->payment_request_id }}" name="manager_comment" rows="3" required></textarea>
                                    </div>
                                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-danger">Reject</button></div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

{{-- ==================== 7. PENDING PAYROLLS ==================== --}}
<div class="section-card">
    <div class="section-head">
        <h5><i class="bi bi-people text-primary"></i> Pending Payrolls
            @if (!$pendingPayrolls->isEmpty())<span class="badge-count">{{ $pendingPayrolls->count() }}</span>@endif
        </h5>
    </div>
    @if ($pendingPayrolls->isEmpty())
        <div class="empty-state"><i class="bi bi-inbox"></i>No pending payrolls at this time.</div>
    @else
        <table class="table-modern">
            <thead>
                <tr><th>Employee</th><th>Job Title</th><th>Pay Period</th><th>Basic</th><th>Transport</th><th>Taxable Transport</th><th>Position Allow.</th><th>Total Taxable</th><th>Income Tax</th><th>Pension 7%</th><th>Pension 11%</th><th>Salary Advance</th><th>Net Salary</th><th>Status</th><th style="text-align:right;">Actions</th></tr>
            </thead>
            <tbody>
                @foreach ($pendingPayrolls as $payroll)
                    <tr>
                        <td>{{ $payroll->employee->full_name }}</td>
                        <td>{{ $payroll->job_title }}</td>
                        <td>{{ $payroll->pay_period }}</td>
                        <td>{{ number_format($payroll->basic_salary, 2) }} {{ $payroll->currency }}</td>
                        <td>{{ number_format($payroll->transport_allowance ?? 0, 2) }} {{ $payroll->currency }}</td>
                        <td>{{ number_format($payroll->taxable_transport_allowance ?? 0, 2) }} {{ $payroll->currency }}</td>
                        <td>{{ number_format($payroll->position_allowance ?? 0, 2) }} {{ $payroll->currency }}</td>
                        <td>{{ number_format($payroll->total_taxable_earnings, 2) }} {{ $payroll->currency }}</td>
                        <td>{{ number_format($payroll->income_tax, 2) }} {{ $payroll->currency }}</td>
                        <td>{{ number_format($payroll->pension_fund_7, 2) }} {{ $payroll->currency }}</td>
                        <td>{{ number_format($payroll->pension_fund_11, 2) }} {{ $payroll->currency }}</td>
                        <td>{{ number_format($payroll->salary_advance ?? 0, 2) }} {{ $payroll->currency }}</td>
                        <td><strong>{{ number_format($payroll->net_payment, 2) }} {{ $payroll->currency }}</strong></td>
                        <td><span class="status-badge status-{{ $payroll->status }}">{{ ucfirst($payroll->status) }}</span></td>
                        <td class="action-buttons">
                            <button class="action-btn approve" data-bs-toggle="modal" data-bs-target="#approveModalPayroll{{ $payroll->payroll_id }}"><i class="fas fa-check"></i> Approve</button>
                            <button class="action-btn reject" data-bs-toggle="modal" data-bs-target="#rejectModalPayroll{{ $payroll->payroll_id }}"><i class="fas fa-times"></i> Reject</button>
                        </td>
                    </tr>

                    <div class="modal fade" id="approveModalPayroll{{ $payroll->payroll_id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header"><h5 class="modal-title">Approve Payroll</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                                <form action="{{ route('manager.payroll.approve', ['payroll_id' => $payroll->payroll_id]) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <label class="form-label" for="manager-comments-approve-pl{{ $payroll->payroll_id }}">Manager Comments:</label>
                                        <textarea class="form-control" id="manager-comments-approve-pl{{ $payroll->payroll_id }}" name="manager_comment" rows="3" required></textarea>
                                    </div>
                                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-success">Approve</button></div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="rejectModalPayroll{{ $payroll->payroll_id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header"><h5 class="modal-title">Reject Payroll</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                                <form action="{{ route('manager.payroll.reject', ['payroll_id' => $payroll->payroll_id]) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <label class="form-label" for="manager-comments-reject-pl{{ $payroll->payroll_id }}">Manager Comments:</label>
                                        <textarea class="form-control" id="manager-comments-reject-pl{{ $payroll->payroll_id }}" name="manager_comment" rows="3" required></textarea>
                                    </div>
                                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-danger">Reject</button></div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

{{-- ==================== 8. PENDING BUDGET FORECASTS ==================== --}}
<div class="section-card">
    <div class="section-head">
        <h5><i class="bi bi-wallet text-primary"></i> Pending Budget Forecasts
            @if (!$pendingBudgetForecasts->isEmpty())<span class="badge-count">{{ $pendingBudgetForecasts->count() }}</span>@endif
        </h5>
    </div>
    @if ($pendingBudgetForecasts->isEmpty())
        <div class="empty-state"><i class="bi bi-inbox"></i>No pending budget forecasts at this time.</div>
    @else
        <table class="table-modern">
            <thead>
                <tr><th>Period</th><th>Description</th><th>Department</th><th>Source</th><th>Quantity</th><th>Unit Cost</th><th>Total Budget</th><th style="text-align:right;">Actions</th></tr>
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
                        <td><strong>{{ number_format($forecast->total_budget, 2) }} {{ $forecast->currency }}</strong></td>
                        <td class="action-buttons">
                            <button class="action-btn approve" data-bs-toggle="modal" data-bs-target="#approveModalBudget{{ $forecast->budget_id }}"><i class="fas fa-check"></i> Approve</button>
                            <button class="action-btn reject" data-bs-toggle="modal" data-bs-target="#rejectModalBudget{{ $forecast->budget_id }}"><i class="fas fa-times"></i> Reject</button>
                        </td>
                    </tr>

                    <div class="modal fade" id="approveModalBudget{{ $forecast->budget_id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header"><h5 class="modal-title">Approve Budget</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                                <form action="{{ route('manager.budget.approve', ['budget_id' => $forecast->budget_id]) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <label class="form-label" for="manager-comments-approve-b{{ $forecast->budget_id }}">Manager Comments:</label>
                                        <textarea class="form-control" id="manager-comments-approve-b{{ $forecast->budget_id }}" name="manager_comment" rows="3" required></textarea>
                                    </div>
                                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-success">Approve</button></div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="rejectModalBudget{{ $forecast->budget_id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header"><h5 class="modal-title">Reject Budget</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                                <form action="{{ route('manager.budget.reject', ['budget_id' => $forecast->budget_id]) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <label class="form-label" for="manager-comments-reject-b{{ $forecast->budget_id }}">Manager Comments:</label>
                                        <textarea class="form-control" id="manager-comments-reject-b{{ $forecast->budget_id }}" name="manager_comment" rows="3" required></textarea>
                                    </div>
                                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-danger">Reject</button></div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

{{-- ==================== 9. PENDING PETTY CASH ==================== --}}
<div class="section-card">
    <div class="section-head">
        <h5><i class="bi bi-wallet2 text-primary"></i> Pending Petty Cash Requests
            @if (!$pendingPettyCashRequests->isEmpty())<span class="badge-count">{{ $pendingPettyCashRequests->count() }}</span>@endif
        </h5>
    </div>
    @if ($pendingPettyCashRequests->isEmpty())
        <div class="empty-state"><i class="bi bi-inbox"></i>No pending petty cash requests at this time.</div>
    @else
        <table class="table-modern">
            <thead>
                <tr><th>Title</th><th>Description</th><th>Amount</th><th>Currency</th><th>Status</th><th>Requested By</th><th style="text-align:right;">Actions</th></tr>
            </thead>
            <tbody>
                @foreach ($pendingPettyCashRequests as $request)
                    <tr>
                        <td>{{ $request->title }}</td>
                        <td>{{ $request->description ?? 'N/A' }}</td>
                        <td><strong>{{ number_format($request->amount_requested, 2) }} {{ $request->currency }}</strong></td>
                        <td>{{ $request->currency }}</td>
                        <td><span class="status-badge status-{{ $request->status }}">{{ ucfirst($request->status) }}</span></td>
                        <td>{{ optional($request->user)->full_name ?? 'N/A' }}</td>
                        <td class="action-buttons">
                            <button class="action-btn approve" data-bs-toggle="modal" data-bs-target="#approveModalPC{{ $request->petty_cash_request_id }}"><i class="fas fa-check"></i> Approve</button>
                            <button class="action-btn reject" data-bs-toggle="modal" data-bs-target="#rejectModalPC{{ $request->petty_cash_request_id }}"><i class="fas fa-times"></i> Reject</button>
                        </td>
                    </tr>

                    <div class="modal fade" id="approveModalPC{{ $request->petty_cash_request_id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header"><h5 class="modal-title">Approve Petty Cash Request</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                                <form action="{{ route('manager.petty_cash.approve', $request->petty_cash_request_id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label" for="amount_approved{{ $request->petty_cash_request_id }}">Approved Amount:</label>
                                            <input type="number" class="form-control" id="amount_approved{{ $request->petty_cash_request_id }}" name="amount_approved" min="1" required oninput="convertToWords(this.value, '{{ $request->petty_cash_request_id }}')">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="amount_approved_in_words{{ $request->petty_cash_request_id }}">Approved Amount in Words:</label>
                                            <input type="text" class="form-control" id="amount_approved_in_words{{ $request->petty_cash_request_id }}" name="amount_approved_in_words" readonly required>
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label" for="comment-approve{{ $request->petty_cash_request_id }}">Comments:</label>
                                            <textarea class="form-control" id="comment-approve{{ $request->petty_cash_request_id }}" name="manager_comment" rows="3" required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-success">Approve</button></div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="rejectModalPC{{ $request->petty_cash_request_id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header"><h5 class="modal-title">Reject Petty Cash Request</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                                <form action="{{ route('manager.petty_cash.reject', $request->petty_cash_request_id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <label class="form-label" for="comment-reject{{ $request->petty_cash_request_id }}">Comments:</label>
                                        <textarea class="form-control" id="comment-reject{{ $request->petty_cash_request_id }}" name="manager_comment" rows="3" required></textarea>
                                    </div>
                                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-danger">Reject</button></div>
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
        const words = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine',
            'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen',
            'seventeen', 'eighteen', 'nineteen', 'twenty', 'thirty', 'forty', 'fifty',
            'sixty', 'seventy', 'eighty', 'ninety'];
        const scales = ['', 'thousand', 'million', 'billion'];

        if (amount < 0) return 'minus ' + convertToWords(Math.abs(amount), requestId);
        if (amount === 0 || amount === '') { document.getElementById('amount_approved_in_words' + requestId).value = amount === 0 ? 'zero' : ''; return; }

        let numberInWords = '', scaleIndex = 0;
        while (amount > 0) {
            let chunk = amount % 1000;
            if (chunk > 0) {
                let chunkInWords = '';
                if (chunk >= 100) {
                    chunkInWords += words[Math.floor(chunk / 100)] + ' hundred';
                    chunk %= 100;
                    if (chunk > 0) chunkInWords += ' and ';
                }
                if (chunk < 20) { chunkInWords += words[chunk]; }
                else {
                    chunkInWords += words[Math.floor(chunk / 10) * 10];
                    if (chunk % 10 > 0) chunkInWords += ' ' + words[chunk % 10];
                }
                numberInWords = chunkInWords + (scales[scaleIndex] ? ' ' + scales[scaleIndex] : '') + ' ' + numberInWords;
            }
            amount = Math.floor(amount / 1000);
            scaleIndex++;
        }
        document.getElementById('amount_approved_in_words' + requestId).value = numberInWords.trim();
    }
</script>
@endsection