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

    .btn-add {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 11px 18px; border-radius: 12px;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: #fff; font-weight: 600; font-size: 0.88rem;
        border: none; box-shadow: 0 8px 20px rgba(99,102,241,0.3);
        transition: var(--transition); text-decoration: none;
    }
    .btn-add:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 12px 26px rgba(99,102,241,0.45); }

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
        font-size: 0.85rem; color: var(--text-primary); vertical-align: top;
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
    .user-cell .u-name { font-weight: 600; color: var(--text-primary); white-space: nowrap; }

    .status-badge {
        display: inline-flex; align-items: center;
        padding: 5px 12px; border-radius: 999px;
        font-size: 0.75rem; font-weight: 600; text-transform: capitalize;
    }
    .status-approved { background: #d1fae5; color: #065f46; }
    .status-pending  { background: #fef3c7; color: #92400e; }
    .status-rejected { background: #fee2e2; color: #991b1b; }
    [data-bs-theme="dark"] .status-badge { filter: brightness(0.85); }

    .text-block { max-width: 260px; color: var(--text-secondary); font-size: 0.82rem; }

    .action-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 5px;
        padding: 7px 11px; border-radius: 9px;
        font-size: 0.78rem; font-weight: 600; border: none; cursor: pointer;
        transition: var(--transition); text-decoration: none; white-space: nowrap;
    }
    .action-btn.edit   { background: #fef3c7; color: #92400e; }
    .action-btn.edit:hover { background: #fde68a; transform: translateY(-1px); }
    .action-btn.delete { background: #fee2e2; color: #991b1b; }
    .action-btn.delete:hover { background: #fecaca; transform: translateY(-1px); }
    .action-btn.pdf    { background: #dbeafe; color: #1e40af; }
    .action-btn.pdf:hover { background: #bfdbfe; transform: translateY(-1px); }
    .action-btn:disabled { opacity: 0.45; cursor: not-allowed; transform: none; }

    .action-group { display: flex; gap: 5px; flex-wrap: wrap; justify-content: flex-end; }

    .empty-state { padding: 60px 20px; text-align: center; color: var(--text-secondary); }
    .empty-state i { font-size: 3rem; opacity: 0.35; margin-bottom: 14px; display: block; }

    @media (max-width: 1200px) {
        .table-card { overflow-x: auto; }
        .table-modern { min-width: 1300px; }
    }
</style>

<div class="page-head">
    <div>
        <h1>Weekly Plans</h1>
        <p>All weekly plans submitted by employees</p>
    </div>
    <a href="{{ route('weekly-plan.create') }}" class="btn-add">
        <i class="bi bi-plus-lg"></i> Submit New Plan
    </a>
</div>

<div class="table-card">
    <table class="table-modern">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Country</th>
                <th>Week</th>
                <th>Plan</th>
                <th>Submitted At</th>
                <th>Manager Comment</th>
                <th>Urgent Issues</th>
                <th>Reference</th>
                <th>Status</th>
                <th>Approved By</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($plans as $plan)
                <tr>
                    <td>
                        <div class="user-cell">
                            <div class="u-avatar">{{ strtoupper(substr($plan->employee->full_name ?? 'E', 0, 1)) }}</div>
                            <span class="u-name">{{ $plan->employee->full_name ?? 'N/A' }}</span>
                        </div>
                    </td>
                    <td>{{ $plan->employee->company->country ?? 'N/A' }}</td>
                    <td>{{ $plan->week }}</td>
                    <td><div class="text-block">{{ $plan->plan }}</div></td>
                    <td>{{ $plan->submitted_at }}</td>
                    <td>{{ $plan->manager_comment ?? 'N/A' }}</td>
                    <td>{{ $plan->urgent_issues ?? 'N/A' }}</td>
                    <td>{{ $plan->reference_number }}</td>
                    <td><span class="status-badge status-{{ $plan->status }}">{{ ucfirst($plan->status) }}</span></td>
                    <td>{{ optional(\App\Models\User::find($plan->approved_by))->full_name ?? 'N/A' }}</td>
                    <td>
                        <div class="action-group">
                            @if ($plan->status === 'approved')
                                <button class="action-btn edit" disabled title="Cannot edit approved plans">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            @else
                                <a href="{{ route('weekly-plan.edit', $plan) }}" class="action-btn edit" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            @endif

                            <form action="{{ route('weekly-plan.destroy', $plan) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete"
                                    onclick="return confirm('Are you sure?');"
                                    title="Delete"
                                    @if ($plan->status === 'approved') disabled @endif>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>

                            <a href="{{ route('weekly-plan.downloadPdf', $plan) }}" class="action-btn pdf" title="Download PDF">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11">
                        <div class="empty-state">
                            <i class="bi bi-calendar-x"></i>
                            <p class="mb-0">No weekly plans found.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection