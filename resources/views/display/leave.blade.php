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

    .table-card {
        background: var(--bg-card); border: 1px solid var(--border-color);
        border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm);
    }
    .table-modern { width: 100%; margin: 0; border-collapse: separate; border-spacing: 0; }
    .table-modern thead th {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        color: var(--text-secondary);
        font-weight: 700; font-size: 0.72rem;
        text-transform: uppercase; letter-spacing: 0.08em;
        padding: 14px 16px; text-align: left;
        border-bottom: 1px solid var(--border-color); white-space: nowrap;
    }
    [data-bs-theme="dark"] .table-modern thead th { background: #0b1120; }
    .table-modern tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--border-color);
        font-size: 0.85rem; color: var(--text-primary); vertical-align: middle;
    }
    .table-modern tbody tr { transition: background-color 0.15s ease; }
    .table-modern tbody tr:hover { background: var(--primary-soft); }
    .table-modern tbody tr:last-child td { border-bottom: none; }

    .user-cell { display: flex; align-items: center; gap: 10px; }
    .user-cell .u-avatar {
        width: 34px; height: 34px; border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: #fff; display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.8rem; flex-shrink: 0;
    }
    .user-cell .u-name { font-weight: 600; color: var(--text-primary); }

    .status-badge {
        display: inline-flex; align-items: center;
        padding: 5px 12px; border-radius: 999px;
        font-size: 0.75rem; font-weight: 600; text-transform: capitalize;
    }
    .status-approved { background: #d1fae5; color: #065f46; }
    .status-pending  { background: #fef3c7; color: #92400e; }
    .status-rejected { background: #fee2e2; color: #991b1b; }
    [data-bs-theme="dark"] .status-badge { filter: brightness(0.85); }

    .signature-thumb {
        width: 80px; height: auto;
        border-radius: 8px; border: 1px solid var(--border-color);
        background: #fff; padding: 2px;
    }

    .file-link {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 6px 10px; border-radius: 8px;
        background: #dbeafe; color: #1e40af;
        font-size: 0.78rem; font-weight: 600;
        text-decoration: none; transition: var(--transition);
    }
    .file-link:hover { background: #bfdbfe; color: #1e40af; }

    .btn-pdf {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        padding: 7px 12px; border-radius: 10px;
        background: #dbeafe; color: #1e40af;
        font-size: 0.8rem; font-weight: 600;
        border: none; cursor: pointer; transition: var(--transition);
        text-decoration: none;
    }
    .btn-pdf:hover { background: #bfdbfe; color: #1e40af; transform: translateY(-1px); }

    .leave-pill {
        display: inline-flex; align-items: center;
        padding: 5px 11px; border-radius: 999px;
        background: var(--primary-soft); color: var(--primary-dark);
        font-size: 0.78rem; font-weight: 600;
    }
    [data-bs-theme="dark"] .leave-pill { color: var(--primary-light); }

    .empty-state { padding: 60px 20px; text-align: center; color: var(--text-secondary); }
    .empty-state i { font-size: 3rem; opacity: 0.35; margin-bottom: 14px; display: block; }

    @media (max-width: 1200px) {
        .table-card { overflow-x: auto; }
        .table-modern { min-width: 1400px; }
    }
</style>

<div class="page-head">
    <div>
        <h1>Leave Requests</h1>
        <p>All leave requests submitted within your company</p>
    </div>
</div>

<div class="table-card">
    <table class="table-modern">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Leave Type</th>
                <th>Other Reason</th>
                <th>Signature</th>
                <th>Attachment</th>
                <th>Status</th>
                <th>Approved By</th>
                <th>Manager Comments</th>
                <th>Country</th>
                <th>Reference</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Remaining Days</th>
                <th style="text-align:right;">Action</th>
            </tr>
        </thead>
        <tbody>
            @auth
                @php
                    $user = auth()->user();
                    $companyId = $user->company_id ?? null;
                    $filteredLeaveRequests = $companyId
                        ? $leaveRequests->filter(fn($request) => optional($request->user)->company_id == $companyId)
                        : collect();
                @endphp

                @if ($filteredLeaveRequests->isEmpty())
                    <tr>
                        <td colspan="16">
                            <div class="empty-state">
                                <i class="bi bi-calendar-x"></i>
                                <p class="mb-0">No leave requests found.</p>
                            </div>
                        </td>
                    </tr>
                @else
                    @foreach ($filteredLeaveRequests as $request)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="u-avatar">{{ strtoupper(substr($request->user->full_name ?? 'U', 0, 1)) }}</div>
                                    <span class="u-name">{{ $request->user->full_name }}</span>
                                </div>
                            </td>
                            <td>{{ $request->start_date }}</td>
                            <td>{{ $request->end_date }}</td>
                            <td>
                                <span class="leave-pill">
                                    {{ ucfirst(str_replace('_', ' ', $request->leave_type)) }}
                                </span>
                            </td>
                            <td>{{ $request->other_reason ?? 'N/A' }}</td>
                            <td>
                                @if ($request->employee_signature)
                                    <img src="{{ asset('storage/' . $request->employee_signature) }}" alt="Signature" class="signature-thumb">
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if ($request->attachment)
                                    <a href="{{ asset('storage/' . $request->attachment) }}" target="_blank" class="file-link">
                                        <i class="bi bi-paperclip"></i> View File
                                    </a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge status-{{ $request->status }}">{{ ucfirst($request->status) }}</span>
                            </td>
                            <td>{{ optional(\App\Models\User::find($request->approved_by))->full_name ?? 'N/A' }}</td>
                            <td>{{ $request->manager_comments ?? 'N/A' }}</td>
                            <td>{{ $request->company_country }}</td>
                            <td>{{ $request->reference_number }}</td>
                            <td>{{ $request->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $request->updated_at->format('Y-m-d H:i') }}</td>
                            <td><strong>{{ $remainingLeaveDays }}</strong></td>
                            <td style="text-align:right;">
                                <a href="{{ route('leave-requests.downloadPdf', $request) }}" class="btn-pdf">
                                    <i class="bi bi-file-earmark-pdf"></i> PDF
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            @else
                <tr>
                    <td colspan="16">
                        <div class="empty-state">
                            <i class="bi bi-lock"></i>
                            <p class="mb-0">Please log in to view leave requests.</p>
                            <a href="{{ route('login') }}" class="btn btn-primary mt-3">Login</a>
                        </div>
                    </td>
                </tr>
            @endauth
        </tbody>
    </table>
</div>
@endsection