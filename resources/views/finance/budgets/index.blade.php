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
        font-size: 0.85rem; color: var(--text-primary); vertical-align: middle;
    }
    .table-modern tbody tr { transition: background-color 0.15s ease; }
    .table-modern tbody tr:hover { background: var(--primary-soft); }
    .table-modern tbody tr:last-child td { border-bottom: none; }

    .status-badge {
        display: inline-flex; align-items: center;
        padding: 5px 12px; border-radius: 999px;
        font-size: 0.75rem; font-weight: 600; text-transform: capitalize;
    }
    .status-approved { background: #d1fae5; color: #065f46; }
    .status-pending  { background: #fef3c7; color: #92400e; }
    .status-rejected { background: #fee2e2; color: #991b1b; }
    [data-bs-theme="dark"] .status-badge { filter: brightness(0.85); }

    .amount-cell { font-weight: 600; white-space: nowrap; }
    .amount-total { font-weight: 700; color: var(--success); white-space: nowrap; }

    .period-pill {
        display: inline-flex; align-items: center;
        padding: 5px 11px; border-radius: 999px;
        background: var(--primary-soft); color: var(--primary-dark);
        font-size: 0.78rem; font-weight: 600; text-transform: capitalize;
    }
    [data-bs-theme="dark"] .period-pill { color: var(--primary-light); }

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
        .table-modern { min-width: 1500px; }
    }
</style>

<div class="page-head">
    <div>
        <h1>Budget Forecasting</h1>
        <p>All budget forecasts created for your company</p>
    </div>
    <a href="{{ route('finance.budgets.create') }}" class="btn-add">
        <i class="bi bi-plus-lg"></i> Add Budget
    </a>
</div>

<div class="table-card">
    <table class="table-modern">
        <thead>
            <tr>
                <th>Reference</th>
                <th>Period</th>
                <th>Description</th>
                <th>Item</th>
                <th>Code</th>
                <th>Department</th>
                <th>Source</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Unit Cost</th>
                <th>Currency</th>
                <th>Total Budget</th>
                <th>Status</th>
                <th>Manager Comments</th>
                <th>Created By</th>
                <th>Approved By</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($budgets as $budget)
                <tr>
                    <td>{{ $budget->reference_number }}</td>
                    <td><span class="period-pill">{{ $budget->forecast_period }}</span></td>
                    <td>{{ $budget->budget_description }}</td>
                    <td><strong>{{ $budget->budget_item }}</strong></td>
                    <td>{{ $budget->budget_code }}</td>
                    <td>{{ $budget->department }}</td>
                    <td>{{ $budget->budget_source }}</td>
                    <td>{{ $budget->quantity }}</td>
                    <td>{{ $budget->unit }}</td>
                    <td class="amount-cell">{{ number_format($budget->unit_cost, 2) }}</td>
                    <td>{{ $budget->currency }}</td>
                    <td class="amount-total">{{ number_format($budget->total_budget, 2) }} {{ $budget->currency }}</td>
                    <td><span class="status-badge status-{{ $budget->status }}">{{ ucfirst($budget->status) }}</span></td>
                    <td>{{ $budget->manager_comment ?? 'N/A' }}</td>
                    <td>{{ $budget->created_by }}</td>
                    <td>{{ $budget->approver ? $budget->approver->full_name : 'N/A' }}</td>
                    <td>
                        <div class="action-group">
                            @if ($budget->status !== 'approved')
                                <a href="{{ route('finance.budgets.edit', $budget) }}" class="action-btn edit" title="Edit Budget">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('finance.budgets.destroy', $budget) }}" method="POST" style="display:inline;"
                                    onsubmit="return confirm('Are you sure you want to delete this budget item?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete" title="Delete Budget">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @else
                                <button class="action-btn edit" disabled title="Cannot edit approved budgets">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="action-btn delete" disabled title="Cannot delete approved budgets">
                                    <i class="bi bi-trash"></i>
                                </button>
                            @endif

                            <a href="{{ route('finance.budgets.downloadBudgetPdf', $budget) }}" class="action-btn pdf" title="Download PDF">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="17">
                        <div class="empty-state">
                            <i class="bi bi-wallet"></i>
                            <p class="mb-0">No budget items found.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection