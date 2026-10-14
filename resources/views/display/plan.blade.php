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

    .btn-pdf {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        padding: 7px 12px; border-radius: 10px;
        background: #dbeafe; color: #1e40af;
        font-size: 0.8rem; font-weight: 600;
        border: none; cursor: pointer; transition: var(--transition);
        text-decoration: none;
    }
    .btn-pdf:hover { background: #bfdbfe; color: #1e40af; transform: translateY(-1px); }

    .plan-text { max-width: 320px; color: var(--text-secondary); font-size: 0.82rem; }

    .empty-state { padding: 60px 20px; text-align: center; color: var(--text-secondary); }
    .empty-state i { font-size: 3rem; opacity: 0.35; margin-bottom: 14px; display: block; }

    @media (max-width: 1100px) {
        .table-card { overflow-x: auto; }
        .table-modern { min-width: 1000px; }
    }
</style>

<div class="page-head">
    <div>
        <h1>Employee Plans</h1>
        <p>All weekly plans submitted by employees</p>
    </div>
</div>

<div class="table-card">
    <table class="table-modern">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Week</th>
                <th>Plan</th>
                <th>Submitted At</th>
                <th>Urgent Issues</th>
                <th>Status</th>
                <th>Reference</th>
                <th>Manager Comment</th>
                <th style="text-align:right;">Action</th>
            </tr>
        </thead>
        <tbody>
            @if ($employeePlans->isEmpty())
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <i class="bi bi-calendar-x"></i>
                            <p class="mb-0">No plans found.</p>
                        </div>
                    </td>
                </tr>
            @else
                @foreach ($employeePlans as $plan)
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="u-avatar">{{ strtoupper(substr($plan->employee->full_name ?? 'E', 0, 1)) }}</div>
                                <span class="u-name">{{ $plan->employee->full_name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td>{{ $plan->week }}</td>
                        <td><div class="plan-text">{{ $plan->plan }}</div></td>
                        <td>{{ $plan->submitted_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $plan->urgent_issues ?? 'N/A' }}</td>
                        <td><span class="status-badge status-{{ $plan->status }}">{{ ucfirst($plan->status) }}</span></td>
                        <td>{{ $plan->reference_number }}</td>
                        <td>{{ $plan->manager_comment ?? 'N/A' }}</td>
                        <td style="text-align:right;">
                            <a href="{{ route('weekly-plan.downloadPdf', $plan) }}" class="btn-pdf">
                                <i class="bi bi-file-earmark-pdf"></i> PDF
                            </a>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
@endsection